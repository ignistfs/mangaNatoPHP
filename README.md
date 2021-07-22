# mangaNatoPHP : Nato

A PHP Class for scraping data from manganato.com



#USAGE&nbsp;&nbsp;
Search for manga using manga name&nbsp;
```php
<?php
require('mangaNatoPHP');
$nato = new Nato;
$nato->searchManga($name);
print_r($nato->getResult());
?>
```
&nbsp;&nbsp;
Requires parameter $name for searching manga, returns array(title, img, link,latest chapters,mangaid)  with manga  info.
&nbsp;&nbsp;
Get manga information using manga link&nbsp;
```php
<?php
require('mangaNatoPHP');
$nato = new Nato;
$nato->getMangaInfo($mangalink,$latestN);
print_r($nato->getResult());
?>
```
&nbsp;
Requires parameter $mangalink,returns array(title, description, cover,chapters)  with manga  info.
Optional parameter $latestN for specifying the number of latest chapters to fetch.
&nbsp;&nbsp;
Get chapter images using manga link&nbsp;
```php
<?php
require('mangaNatoPHP');
$nato = new Nato;
$nato->getChapter($localPATH,$chaplink,$downloadLocally);
print_r($nato->getResult());
?>
```
&nbsp;
Requires parameter $mangalink,returns array(localFile, localFileHTML, remoteFile,remoteFileHTML).Images are protected so they need to be downloaded locally first before displaying. Use the $downloadLocally(boolean) parameter for specifying to download or not the files and $localPATH for setting the download directory.
&nbsp;&nbsp;
You can have a look on how it works by trying the test files in tests/
