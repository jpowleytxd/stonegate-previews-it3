<?php

foreach(glob("client.demo/*/branded/*.html") as $filename){
  $template = file_get_contents($filename);
  print($filename . '<br/>');
  print($template);
}

?>
