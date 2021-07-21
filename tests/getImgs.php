
<?php

$mangalink = 'https://readmanganato.com/manga-ci980191/chapter-146';
$page = file_get_contents($mangalink);
echo '<textarea>'.$page.'</textarea>';
$doc = new DOMDocument();
@$doc->loadHTML($page);
$xpath = new DomXPath($doc);
//get manga cover
foreach($doc->getElementsByTagName('img') as $img){

  print('<img style="width:800px" src="'.$img->getAttribute('src').'"/><br>');
}


?>
