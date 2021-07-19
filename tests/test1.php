<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
<style>
.card{
  position: relative;
  box-shadow: 0 0 0 #ffb733;
  height: 460px;
  text-align: center;
  overflow: hidden;
  cursor: pointer;
  color: white;
  transition: .2s;
  filter: grayscale(20%);
  margin: 15px;
  right: 10px;
  background: #141413;
  width:200px;
}
.card h5{
  color:white;
}
</style>
<form action="test1.php" method="GET">
<input style="margin:5px;width:200px;;height:60px;" placeholder="Search manga by name" name="k" />

</form>
<?php

require("../mangakakalotApi.php");


$getmanga= new Kakalot;
$getmanga->searchManga($_GET['k']);
if(!isset($_GET['k'])){
  return;
}
$manga_item =  $getmanga->getResult();
print('Result : <br> <div class="card">
  <div class="card-image">
    <figure class="image is-4by5">
      <img src="'.$manga_item['img'].'" alt="Placeholder image">
    </figure>
  </div>
  <div class="card-content">

    <div class="content">
       <a href="'.$manga_item['link'].'"><h5><b>'.$manga_item['title'].'</b></h5></a><br>
       Last chapter : <a href="'.$manga_item['latest'][0]['link'].'">'.$manga_item['latest'][0]['title'].'</a>
      <br>
    </div>
  </div>
</div><br>');



?>
