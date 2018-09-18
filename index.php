<html>
<head>
<title>POP Gallery</title>
</head>
<body>
  <?php
define("DOCUMENT_ROOT", __DIR__ . "/");
define("UPLOADS_DIR", DOCUMENT_ROOT . "img/");
define("S_UPLOADS_DIR", DOCUMENT_ROOT . "l_img/");
define("MAX_FILE_SIZE", 5000000); // 5 MB

  $dir = 'img/'; 
  $dir_small = 'l_img/';
  $files = scandir($dir);
  for ($i = 0; $i < count($files); $i++) { 
    if (($files[$i] != ".") 
    && ($files[$i] != "..") 
    && ($files[$i] != ".DS_Store")) { //для Мак ОС
      $path = $dir_small.$files[$i]; 
      $lpath = $dir.$files[$i]; 
      echo "<a href='$lpath' target='_blank'>"; 
      echo "<img src='$path' alt='' width='200' />"; 
      echo "</a>"; 
    }
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if($_FILES['file']['type'] == 'image/jpeg'
      && filesize ($_FILES['file']['tmp_name']) < MAX_FILE_SIZE
      ) {
      $filename = UPLOADS_DIR . $_FILES['file']['name']; //выводит странный путь. проработать пути!!!!
      $sfilename = S_UPLOADS_DIR . $_FILES['file']['name'];
      if(file_exists($filename)){
        $suniq = uniqid();
        $filename1 = substr($filename, 0, strlen($filename)-4);
        $filename2 = substr($filename, strlen($filename)-4 ,4);
        $filename = $filename1.$suniq.$filename2;

        $sfilename1 = substr($sfilename, 0, strlen($sfilename)-4);
        $sfilename2 = substr($sfilename, strlen($sfilename)-4 ,4);
        $sfilename = $sfilename1.$suniq.$sfilename2;
          
    }

      move_uploaded_file($_FILES['file']['tmp_name'], $filename);
      if(!file_exists($sfilename)){
        include('classSimpleImage.php');
        $image = new SimpleImage();
        $image->load($filename);
        $image->resizeToWidth(200);
        $image->save($sfilename);
        }   

     header("Refresh:0");
    } else {
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "Размер или тип файла не подходят. Выберите другой файл!<br>";
      $sizetemp = filesize ($_FILES['file']['tmp_name'])/1000;
      $maxsizetemp = MAX_FILE_SIZE/1000;
      echo "Размер = ".$sizetemp." КБ, допустимый ".$maxsizetemp." КБ <br>";
      echo "Тип файла = ".$_FILES['file']['type'].", допустимый = image/jpeg<br>";
    }
}

?>

<br>
<br>
<br>

<form action="" enctype="multipart/form-data" method="post">
    <input type="file" name = 'file'>
    <input type="submit">
</form>

</body>
</html>
