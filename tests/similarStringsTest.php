<?php


$firstString = "slo leveling";
$secondString = "Level Up Just By Eating";
$thirdString = "Solo Leveling";

//similar text
similar_text($secondString,$firstString,$perc);
echo $perc.'<br>';
similar_text($thirdString,$firstString,$perc1);
echo $perc1.'<br>--<br>';

echo metaphone($firstString).'<br>';
echo metaphone($secondString).'<br>';
echo metaphone($thirdString).'<br>';




?>
