<center>
  <h1>Test and demo for getMangaInfo method</h1>
  <h2>searchManga will be used first to get a mangaid</h2>
<form action="test2.php" method="GET">
<input style="margin:5px;width:200px;;height:60px;" placeholder="Search manga by name" name="k" />

</form>
<?php
if(!isset($_GET['k'])){
  return;
}

require("../mangaNatoPHP.php");


$getmanga= new Nato;
$getmanga->searchManga($_GET['k']); //search for mangaid with the search method
$mangaid = $getmanga->getResult()['link']; // get mangaid
$getmanga->getMangaInfo($mangaid); //fetch manga info
$MangaInfo=$getmanga->getResult();


?>

Query : <span style="color:green"><?php print($_GET['k']);?></span><br><br>
Json Encoded response <br><br><textarea><?php print_r(json_encode($MangaInfo)) ?> </textarea><br><br>
Html Result :
<h2><?php print($MangaInfo['title'])?></h2><br>
<h4><img src="<?php print($MangaInfo['cover'])?>"/></h4><br>
<h4><?php print($MangaInfo['description'])?></h4><br>
<?php

foreach($MangaInfo['chapters'] as $chapter){
  print("<a href='test4.php?url=".$chapter['link']."'>".$chapter['title']."</a><br>");
}


?>
