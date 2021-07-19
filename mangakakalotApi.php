<?php
class Kakalot{

  public $result;
  public $name;

  //param @name - the name of the searching manga
  //returns array with manga info
  function searchManga($name){
    $matches = array();
    $html = file_get_contents('https://ww.mangakakalot.tv/search/'.$name);
    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $nr=0;
    $links = $dom->getElementsByTagName('a');
    $manga = array();
    $i=0;
    foreach ($links as $key=>$link){
        if($key <=60){
          continue;
        }
        if($key >= 100){
          break;
        }

        if(explode("/",$link->getAttribute('href'))[2] != NULL && (trim($link->nodeValue) != NULL || trim($link->nodeValue) != '')){
        if(explode("/",$link->getAttribute('href'))[1] == 'manga'){
          $mangalink = 'https://ww.mangakakalot.tv/manga/'.explode("/",$link->getAttribute('href'))[2];
          $page = file_get_contents($mangalink);
          $doc = new DOMDocument();
          @$doc->loadHTML($page);
          $xpath = new DomXPath($doc);
          foreach($doc->getElementsByTagName('div') as $div){
              if($div->getAttribute('class') == 'manga-info-pic' OR $div->getAttribute('class') == 'manga-info-pic'){
                  foreach($div->getElementsByTagName('img') as $i){
                      $coverimg= 'https://ww.mangakakalot.tv'.$i->getAttribute('src');
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
        $chapterlink = 'https://ww.mangakakalot.tv/'.$link->getAttribute('href');
        array_push($manga[$nr-1]['latest'],$chapterlink);

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


    function getResult(){
      return $this->result;
    }







}








?>
