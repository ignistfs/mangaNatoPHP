<?php


class Kakalot{
  public $result;
  public $name;
  public $mangakakalotURL = 'https://ww.mangakakalot.tv';

  //method for searching manga
  //param $name required (manga title to look for)
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
          //skipping first 60 links (links for login, home etc.)
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
            "nr"=>$nr,
            "mangaid"=> explode("/",$link->getAttribute('href'))[2]
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
    //picking the manga with most similarity
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
    //method for getting manga info
    //param $mangaid required (the id of the manga to look for)
    //returns array with manga info
    function getMangaInfo($mangaid, $latestN = null){
      if(!isset($mangaid)){
        return;
      }
      $ii=0;
      $html = file_get_contents($this->mangakakalotURL.'/manga/'.$mangaid);
      $dom = new DOMDocument;
      @$dom->loadHTML($html);
      $links = $dom->getElementsByTagName('a');
      $manga = array();
      $mangalink = $this->mangakakalotURL."/manga/".$mangaid;
      $page = file_get_contents($mangalink);
      $doc = new DOMDocument();
      @$doc->loadHTML($page);
      $xpath = new DomXPath($doc);
      //get manga cover
      foreach($doc->getElementsByTagName('div') as $div){
          if($div->getAttribute('class') == 'manga-info-pic' OR $div->getAttribute('class') == 'manga-info-pic'){
              foreach($div->getElementsByTagName('img') as $i){
                  $coverimg= $this->mangakakalotURL.$i->getAttribute('src');
              }
          }
      }
      //
      //get manga description and title
      $getdesc=$doc->getElementById("noidungm");
      $desc = str_replace("summary:","",strstr($getdesc->nodeValue,'summary:'));
      $title = strstr($getdesc->nodeValue,'summary:',true);
      //
      //get manga chapters
      $chapters = array();
      foreach ($links as $key=>$link){
          if($key <=60){
            //skipping first irrelevant 60 links (links for login, home etc.)
            continue;
          }
          if($latestN != null && $ii >= $latestN){
            break;
          }
          $linkparts = array_pad(explode('/', $link->getAttribute('href')), 3, null);
          if($linkparts[2] != null && (trim($link->nodeValue) != null || trim($link->nodeValue) != '')){
          if($linkparts[1] == 'chapter' && $linkparts[2] == $mangaid){
            $mangalink = $this->mangakakalotURL.$link->getAttribute('href');
            $chapter = array(
              "title"=>$link->nodeValue,
              "link"=>$mangalink
            );
            array_push($chapters,$chapter);
            $ii++;
          }

        }
      }

      $mangainfo = array(
        "title"=>$title,
        "description"=>$desc,
        "cover"=>$coverimg,
        "chapters"=>$chapters
      );
      $this->result=$mangainfo;

    }




}








?>
