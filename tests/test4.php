<?php
$files = glob('cache_img/*');
foreach($files as $file){
  if(is_file($file)) {
    unlink($file);
  }
}
require("../mangaNatoPHP.php");
$nato = new Nato;
$nato->getChapter("cache_img",$_GET['url'],false);
$result=$nato->getResult();
foreach($result as $image){
  print($image['localFileHTML']);
}


?>
