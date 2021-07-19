<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Kakalot{

  public $result;
  public $name;
  public $mangakakalotURL = 'https://ww.mangakakalot.tv';




  //method for searching manga
  //param $name
  //returns array with manga info
  function searchManga($name){
    $matches = array();
    if(!isset($name)){
      return;
    }
    $html = file_get_contents($this->mangakakalotURL.'/search/'.$name);
    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $nr=0;
    $links = $dom->getElementsByTagName('a');
    $manga = array();
    $i=0;
    foreach ($links as $key=>$link){
        if($key <=60){
          //skipping first 60 links
          continue;
        }
        if($key >= 100){
          //fetch only 40 links for ~ 10 manga results
          break;
        }
        if(explode("/",$link->getAttribute('href'))[2] != NULL && (trim($link->nodeValue) != NULL || trim($link->nodeValue) != '')){
        if(explode("/",$link->getAttribute('href'))[1] == 'manga'){
          $mangalink = $this->mangakakalotURL.'/manga/'.explode("/",$link->getAttribute('href'))[2];
          $page = file_get_contents($mangalink);
          $doc = new DOMDocument();
          @$doc->loadHTML($page);
          $xpath = new DomXPath($doc);
          foreach($doc->getElementsByTagName('div') as $div){
              if($div->getAttribute('class') == 'manga-info-pic' OR $div->getAttribute('class') == 'manga-info-pic'){
                  foreach($div->getElementsByTagName('img') as $i){
                      $coverimg= $this->mangakakalotURL.$i->getAttribute('src');
                  }
              }
          }
        $uqid= uniqid();
        $mangatopush = array(
            "title"=> $link->nodeValue,
            "img"=>$coverimg,
            "latest" =>array(),
            "link"=> $mangalink,
            "nr"=>$nr
          );
        similar_text($link->nodeValue,$name,$percent);
        $match_result = array(
          "nr"=>$nr,
          "percent"=>$percent
        );
        array_push($matches,$percent);
        array_push($manga,$mangatopush);
        $nr++;

      }
      else{
        $chapterlink = $this->mangakakalotURL.'/'.$link->getAttribute('href');
        array_push($manga[$nr-1]['latest'],array(
          "link"=>$chapterlink,
          "title"=>$link->nodeValue
        ));
      }
      }

    $i++;
    }
    $highest=0;
    $index=0;
    foreach($matches as $result){
      if($result['percent'] > $highest){
        $highest = $result['percent'];
        $index=$result['nr'];
      }
    }

    $this->result=$manga[$index];
    }

    //method for returning result

    function getResult(){
      return $this->result;
    }

    //method for changing mangakakalot in case of blocked or no longer available
    //param $url is new URL
    //returns nothing
    function setURL($url){
      $this->mangakakalotURL=$url;
    }






}








?>
