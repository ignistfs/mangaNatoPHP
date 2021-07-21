<?php

require("../mangaNatoPHP.php");
$nato = new Nato;
$nato->getChapter("cache_img",$_GET['url']);
$result=$nato->getResult();
foreach($result as $image){
  print($image['localFileHTML']);
}


?>
