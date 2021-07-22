<?php
class Nato{
  public $result;
  public $name;
  public $mangasiteURL = 'https://manganato.com';

  //method for searching manga
  //param $name required (manga title to look for)
  //returns array with manga info
  function searchManga($name){
    $matches = array();
    if(!isset($name)){
      return;
    }
    $name = str_replace(" ","_",$name);
    $html = file_get_contents($this->mangasiteURL.'/search/story/'.$name);
    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $nr=0;
    $links = $dom->getElementsByTagName('a');
    $manga = array();
    $ii=0;
    foreach ($links as $key=>$link){
      $linkparts = array_pad(explode('/', $link->getAttribute('href')), 5, null);
      if(substr($linkparts[3], 0, 5) != 'manga'){
        continue;
      }
      if($key <= 60){
        continue;
      }
      if($key >= 92){
        break;
      }

      if($linkparts[4] == null){
          $mangalink = $link->getAttribute("href");
          $page = file_get_contents($mangalink);
          $doc = new DOMDocument();
          @$doc->loadHTML($page);
          $xpath = new DomXPath($doc);
          foreach($doc->getElementsByTagName('div') as $div){
              if($div->getAttribute('class') == 'panel-story-info' OR $div->getAttribute('class') == 'panel-story-info'){
                  foreach($div->getElementsByTagName('img') as $i){
                    if($i->getAttribute('class') == 'img-loading'){
                      $coverimg= $i->getAttribute('src');
                    }
                    else{
                      //
                    }
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
            "mangaid"=> $linkparts[3]
          );
        similar_text($link->nodeValue,str_replace("_","",$name),$percent);
        $match_result = array(
          "nr"=>$nr,
          "percent"=>$percent
        );
        array_push($matches,$match_result);
        array_push($manga,$mangatopush);
        $nr++;

      }
      else{
        $chapterlink = $this->mangasiteURL.'/'.$link->getAttribute('href');
        array_push($manga[$nr-1]['latest'],array(
          "link"=>$chapterlink,
          "title"=>$link->nodeValue
        ));
      }

    $i++;
    $ii++;
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
      $this->mangasiteURL=$url;
    }
    //method for getting manga info
    //param $mangalink required (the link of the manga to look for)
    //returns array with manga info
    function getMangaInfo($mangalink, $latestN = null){
      if(!isset($mangalink)){
        return;
      }
      $ii=0;
      $html = file_get_contents($mangalink);
      $dom = new DOMDocument;
      @$dom->loadHTML($html);
      $links = $dom->getElementsByTagName('a');
      $manga = array();
      $page = file_get_contents($mangalink);
      $doc = new DOMDocument();
      @$doc->loadHTML($page);
      $xpath = new DomXPath($doc);
      //get manga cover
      foreach($doc->getElementsByTagName('div') as $div){
        if($div->getAttribute('class') == 'story-info-right' OR $div->getAttribute('class') == 'story-info-right'){
          $title = strstr($div->nodeValue,'Alternative',true);
        }
        if($div->getAttribute('class') == 'panel-story-info' OR $div->getAttribute('class') == 'panel-story-info'){
            foreach($div->getElementsByTagName('img') as $i){
              if($i->getAttribute('class') == 'img-loading'){
                $coverimg= $i->getAttribute('src');
              }
              else{
                //
              }
            }
        }
      }
      //
      //get manga description
      $getdesc=$doc->getElementById("panel-story-info-description");
      $desc = str_replace("Description :","",strstr($getdesc->nodeValue,'Description :'));
      //
      //get manga chapters
      $chapters = array();
      foreach($dom->getElementsByTagName('div') as $div){
        if($div->getAttribute('class') == 'panel-story-chapter-list' OR $div->getAttribute('class') == 'panel-story-chapter-list'){
          $links = $div->getElementsByTagName('a');
          foreach ($links as $key=>$link){
            $linkparts = array_pad(explode('/', $link->getAttribute('href')), 5, null);
            if(substr($linkparts[4], 0, 7) != 'chapter'){
              continue;
            }
            $chapter = array(
              "title"=>$link->nodeValue,
              "link"=>$link->getAttribute('href')
            );
            array_push($chapters,$chapter);
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
    //method for fetching chapter images
    //images are protected so they need to be downloaded and stored locally
    //param $chaplink is required (the link for the chapter)
    //param $downloadLocally is required (true or false to download the files locally)
    //param $localPATH is required (the folder to save the images locally)
    //returns an array with the path to the local file, a html element with the image and a url with the remote file

    function getChapter($localPATH,$chaplink,$downloadLocally = false){
      if(!isset($chaplink)){
        return;
      }

      $page = file_get_contents($chaplink);
      $doc = new DOMDocument();
      @$doc->loadHTML($page);
      $xpath = new DomXPath($doc);
      $images = array();
      foreach($doc->getElementsByTagName('div') as $div){
          if($div->getAttribute('class') == 'container-chapter-reader' OR $div->getAttribute('class') == 'container-chapter-reader'){
            foreach($div->getElementsByTagName('img') as $img){

            if($downloadLocally == true){
              if(!isset($localPATH)){
                return;
              }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $img->getAttribute('src'));
            curl_setopt($ch, CURLOPT_REFERER, "https://readmanganato.com/");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                $rawdata=curl_exec ($ch);
                curl_close ($ch);
            $uqfilename = uniqid().'.png';
            $fp = fopen($localPATH."/$uqfilename",'w');
            fwrite($fp, $rawdata);
            fclose($fp);
            $localFileHTML=("<img src='".$localPATH."/".$uqfilename."'/><br>");
            $localFile = 'cache_img/'.$uqfilename;
            }
            $remoteFile = $img->getAttribute('src');
            $remoteFileHTML=("<img src='".$remoteFile."'/><br>");
            if(!isset($localFile)){
              $localFile = 'null';
            }
            if(!isset($localFileHTML)){
              $localFileHTML = 'null';
            }
            $image = array(
              "localFile"=> $localFile,
              "localFileHTML"=> $localFileHTML,
              "remoteFile"=> $remoteFile,
              "remoteFileHTML"=> $remoteFileHTML,

            );
            array_push($images,$image);


          }
      }
      }
      $this->result=$images;

    }
}








?>
