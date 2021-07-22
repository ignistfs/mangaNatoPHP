# mangaNatoPHP : Nato

A PHP Class for scraping data from manganato.com



#USAGE&nbsp;
&nbsp;
Search for manga using manga name&nbsp;
```php
<?php
require('mangaNatoPHP');
$nato = new Nato;
$nato->searchManga("Manga name");
print_r($nato->getResult());
?>
```
&nbsp;
Requires parameter $name for searching manga, returns array(title, img, link,latest chapters,mangaid)  with manga  info.
&nbsp;
Get manga information using manga link&nbsp;
```php
<?php
require('mangaNatoPHP');
$nato = new Nato;
$nato->getMangaInfo("<manga link>");
print_r($nato->getResult());
?>
```
&nbsp;
Requires parameter $mangalink,returns array(title, description, cover,chapters)  with manga  info.
Optional parameter $latestN for specifying the number of latest chapters to fetch.
