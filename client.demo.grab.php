<?php

$brands = array();

foreach(glob("*/templates/*_branded.html") as $filename){
  $template = file_get_contents($filename);
  $parentFolder = preg_replace('/(.*?)\/.*/', '$1', $filename);
  $parentFolder = str_replace('halfway_heaven', 'halfway_to_heaven', $parentFolder);
  $parentFolder = str_replace('colors_basildon', 'colors', $parentFolder);
  $parentFolder = str_replace('duke_wellington', 'duke_of_wellington', $parentFolder);
  $parentFolder = str_replace('finnegans', 'finnegans_wake', $parentFolder);
  $parentFolder = str_replace('pit_pendulum', 'pit_and_pendulum', $parentFolder);

  array_push($brands, $parentFolder);
}

foreach($brands as $key => $brand){
  $html = 'http://clientdemos.txdlimited.co.uk/2016/stonegate/compiled/' . $brand . '/welcome_3_plus_21_days.html';

  $page = file_get_contents($html);
  print_r($page);
}

 ?>
