<?php

ini_set('max_execution_time', 3000);

function sendToFile($output, $append, $serverName){
  $send = true;
  if($send === true){
    $outputPath = "pre_made/";
    $brand = '/branded/';
    $fileType=".html";

    $dirName = $outputPath . $serverName;
    if(!is_dir($dirName)){
      mkdir($dirName, 0755);
    }

    $dirName = $outputPath . $serverName . $brand ;
    if(!is_dir($dirName)){
      mkdir($dirName, 0755);
    }

    file_put_contents(($outputPath . $serverName . $brand . $append . $fileType), $output);
  }
}

function textColor($color){
  $r = hexdec(substr($color,1,2));
  $g = hexdec(substr($color,3,2));
  $b = hexdec(substr($color,5,2));

  if ($r + $g + $b > 500){
    //return $r + $g + $b;
      return '#000';
    } else {
      //return $r + $g + $b;
      return '#fff';
  }
}

function nameCheck($name){
  if($name === ''){

  } else{
    return $name;
  }
}

function marginBuilder($block){
  $blockStart = '<table border="0" cellpadding="0" cellspacing="0" width="600" class="block block1Column structureBlock wrapper" data-id="block1Column" style="width:600px;width:600px;width:600px;">
      <tr>
          <td align="center" valign="0">
              <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table blockArea block" data-id="blockArea" style="width:600px;width:600px;width:600px;">
                  <tr><td align="center" width="30"></td>
                      <td valign="top">';
  $blockEnd = '</td><td align="center" width="30"></td>
  </tr>
  </table>
  </td>
  </tr>
  </table>';

  $block = $blockStart . $block .$blockEnd;
  return $block;
}

$column = file_get_contents("bosleys/_defaults/1column.html");
$imageBlock = file_get_contents("bosleys/_defaults/image.html");
$imageBlock = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', 'http://placehold.it/640x360', $imageBlock);

foreach(glob("*/templates/*_branded.html") as $filename){
  $template = file_get_contents($filename);
  $parentFolder = preg_replace('/(.*?)\/.*/', '$1', $filename);

  $serverName = nameCheck($parentFolder);

  preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  $textColor = textColor($color);

  //Prep Heading
  $heading = file_get_contents($parentFolder . '/bespoke blocks/' . $parentFolder . '_heading.html');
  $heading = str_replace('align="left"', 'align="center"', $heading);
  $heading = marginBuilder($heading);

  preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);

  $headingStyle = $matches[1][0];
  $headingStyleNew = $headingStyle . ' font-size: 24px;';
  $heading = str_replace($headingStyle, $headingStyleNew, $heading);

  //Prep Spacer
  $emptySpacer = file_get_contents('basic-spacer.html');
  $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);

  //Prep All Text
  $basicText = file_get_contents('bosleys/_defaults/text.html');
  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';
  $basicText = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $basicText);
  $basicText = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $basicText);
  $basicText = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $basicText);

  $insert = $imageBlock . $emptySpacer . $heading . $emptySpacer . $basicText . $largeSpacer;

  $column = preg_replace('/<!-- Insert -->/', $insert, $column);

  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  $append = "adhoc";

  sendToFile($output, $append, $serverName);

  print_r($output);
}

?>
