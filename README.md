# mangaNatoPHP : Nato

A PHP Class for scraping data from manganato.com



USAGE&nbsp;&nbsp;

Search for manga using manga name&nbsp;
```php
<?php
require 'mangaNatoPHP'
$nato = new Nato;
$nato->searchManga("Manga name");
print_r($nato->getResult());
?>
```
&nbsp;
Requires parameter $name for searching manga, returns array(title, img, link,latest chapters,mangaid)  with manga  info. 
