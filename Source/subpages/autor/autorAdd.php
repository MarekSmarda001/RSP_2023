<?php 
include '../../connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $surname = mysqli_real_escape_string($con, $_POST['surname']);
    $theme = mysqli_real_escape_string($con, $_POST['theme']);
    $heading = mysqli_real_escape_string($con, $_POST['heading']);
    $content = mysqli_real_escape_string($con, $_POST['content']);

    $sql = "SELECT id_uzivatele FROM uzivatele WHERE jmeno = '$name' AND prijmeni = '$surname'";
    $result = $con->query($sql);
   
    if ($result) {
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_autora = $row['id_uzivatele'];
            if (isset($_FILES['pdf_file']['name'])) {
              $file_name = $_FILES['pdf_file']['name'];
              $file_tmp = $_FILES['pdf_file']['tmp_name'];
          
              if (move_uploaded_file($file_tmp, "./pdf/" . $file_name)) {
                  $sql_insert = "INSERT INTO prispevky (nazev, autor, tema, soubor_pdf, obsah_text, stav) VALUES ('$heading', '$id_autora', '$theme','$file_name', '$content', 'Odesláno k recenzi')";
                  $result = mysqli_query($con, $sql_insert);

                  if (!$result) {
                      echo mysqli_error($con);
                  }
                } else {
                  echo "Error uploading file";
              }
          }



        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./autorStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/x-icon" href="fav.png" class="fav">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Handjet:wght@300&family=Press+Start+2P&family=Roboto&display=swap" rel="stylesheet">
    <title>| Autor - přidání článku</title>
</head>
<body>
   <center>
   <div class="container">
      <div>
   <img src="./text.png" alt="">
   Jste přihlášen jako <b>autor</b>
   <a  href="../../index.php">Odhlásit</a> <br>
   <a  href="./autor.php">Zpět</a>
</div>
   <hr>
      <form method="post" enctype="multipart/form-data">
         <h1>Přidání příspěvku</h1>
         <div class="info">
           <input  type="text" name="name" id='name' placeholder="Jméno autora" required>
           <input  type="text" name="surname" id="surname" placeholder="Příjmení autora" required>
         </div>
         <div>
         <h4>Obsah příspěvku</h4>
           <select name="theme" id="theme" required>
            <option value="IT" selected="selected">IT</option>
            <option value="CR">Cestovní ruch</option>
            <option value="EK">Ekonomie</option>
            </select> <br> <br>
           <input  type="text" name="heading" id="heading" placeholder="Název příspěvku" required><br><br>
           <textarea type="text" name="content" id="content" rows="4" placeholder="Obsah příspěvku" required></textarea>
         </div>
         <div>
             <h4>Vybrat PDF</h4>
           <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" title="Nahrání PDF"/>
         </div>
         <br>
         <hr>
    <br>
    <div style="overflow-x:auto;">
    
       <button class="add" type="submit" name="send">Přidat článek</button>
      </div>
   </div> 
       </form>
   
   <br>
</center>
</body>
</html>