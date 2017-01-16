<?php

ini_set('max_execution_time', 3000);

function sendToFile($output, $append, $serverName){
  $send = false;
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

function databaseQuery($query){
  //Define Connection
  static $connection;

  //Attempt to connect to the database, if connection is yet to be established.
  if(!isset($connection)){
    //Load congig file
    $config = parse_ini_file('config.ini');
    $connection = mysqli_connect('localhost', $config['username'], $config['password'], $config['dbname']);
  }

  //Arrays to store all retrieved records
  $rows = array();
  $result = null;

  //Connection error handle
  if($connection === false){
    print('Connection Error');
    return false;
  } else{
    //Query the database
    $result = mysqli_query($connection, $query);

    //IF query failed, return 'false'
    if($result === false){
      print('Query Failed');
      return false;
    }

    //Fetch all the rows in the Array
    while($row = mysqli_fetch_row($result)){
      $rows[] = $row;
    }
    return $rows;
  }
}

function getData($query, $brand){
  $initialQuery = "SELECT * FROM " . $brand . " WHERE `email` = '" . $query . "'";

  $rows = databaseQuery($initialQuery);

  foreach($rows as $key => $row){
    return $row;
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

function getURL($serverName, $email){
  if(($email === 'WIFI sign in 1 + 1 Day') || ($email === 'WIFI sign in 2 + 7 Days')){
    $urlStart = 'http://img2.email2inbox.co.uk/2017/stonegate/01/promo/';
    $urlEnd = '/shot.png';
    return $urlStart . $serverName . $urlEnd;
  } else{
    $url = 'http://img2.email2inbox.co.uk/2016/stonegate/templates/placeholder.jpg';
    return $url;
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

function lineSpacerBuild($parentFolder){
  $spacer = file_get_contents('bosleys/_defaults/spacer.html');

  $voucher = file_get_contents($parentFolder . '/bespoke blocks/' . $parentFolder . '_voucher.html');
  preg_match('/BG: (.*)/', $voucher, $matches);
  $color = $matches[1];

  $style='border-top: 1px dotted ' . $color . ';';

  $spacer = preg_replace('/background-color:.*?;/', '', $spacer);
  $spacer = preg_replace('/border-top:.*?;/', $style, $spacer);

  return $spacer;
}

function termsBuilder($terms){
  $blockStart = '<table border="0" cellpadding="0" cellspacing="0" width="600" class="block block1Column structureBlock wrapper" data-id="block1Column" style="width:600px;width:600px;width:600px;">
      <tr>
          <td align="center" valign="0">
              <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table blockArea block" data-id="blockArea" style="width:600px;width:600px;width:600px;">
                  <tr><td align="center" width="30"></td>
                      <td valign="top">';
  $blockEnd = '</td><td align="center" width="30"></td>
  </tr><tr><td height="30" valign="0"></td></tr>
  </table>
  </td>
  </tr>
  </table>';

  $block = $blockStart . $terms .$blockEnd;
  return $block;
}

//Wifi Week 1
foreach(glob("*/templates/*_branded.html") as $filename){
  $template = file_get_contents($filename);
  $parentFolder = preg_replace('/(.*?)\/.*/', '$1', $filename);

  $serverName = nameCheck($parentFolder);

  //Get content
  $wifiRow = null;
  $email ="WIFI sign in 1 + 1 Day";
  if($serverName === 'bosleys'){
    $wifiRow = getData($email, 'yates');
  } else{
    $wifiRow = getData($email, $serverName);
  }

  //Get Background Color
  preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  $textColor = textColor($color);

  //Prep Heading
  $heading = file_get_contents($parentFolder . '/bespoke blocks/' . $parentFolder . '_heading.html');
  $heading = str_replace('Heading goes here', $wifiRow[3], $heading);
  $heading = str_replace('align="left"', 'align="center"', $heading);
  $heading = marginBuilder($heading);

  preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);

  $headingStyle = $matches[1][0];
  $headingStyleNew = $headingStyle . ' font-size: 24px;';
  $heading = str_replace($headingStyle, $headingStyleNew, $heading);

  //Prep Image
  $image = file_get_contents('bosleys/_defaults/image.html');
  $image = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', getURL($serverName, $email), $image);

  //Prep Spacers
  $emptySpacer = file_get_contents('basic-spacer.html');
  $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);
  $lineSpacer = lineSpacerBuild($parentFolder);

  //Prep All Text
  $basicText = file_get_contents('bosleys/_defaults/text.html');
  $textOne = $textTwo = $basicText;

  //Prep Text One
  $wifiRow[4] = str_replace('"', '', $wifiRow[4]);
  $textOne = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $wifiRow[4], $textOne);
  //$textOne = str_replace('"', '', $textOne);
  $textOne = preg_replace('/##/m', '<p>', $textOne);

  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';

  $textOne = str_replace('<td class="text" align="left">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textOne);
  $textOne = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textOne);
  $textOne = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textOne);

  //Prep Text Two
  $wifiRow[7] = str_replace('"', '', $wifiRow[7]);
  $textTwo = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $wifiRow[7], $textTwo);
  $textTwo = preg_replace('/##(.+?)##/m', '<p>$1</p>', $textTwo);

  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';

  $textTwo = str_replace('<td class="text" align="left">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textTwo);
  $textTwo = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textTwo);
  $textTwo = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textTwo);

  //Prep Voucher
  $voucherInstructions = $wifiRow[9];

  $voucher = file_get_contents($parentFolder . '/bespoke blocks/' . $parentFolder . '_voucher.html');
  $voucherSearch = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
  $voucher = str_replace($voucherSearch, $voucherInstructions, $voucher);

  $voucher = marginBuilder($voucher);

  preg_match('/"emailBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  //print($color . ' : ');
  $textColor = textColor($color);
  //print($textColor . '<br/>');

  $terms = termsBuilder($wifiRow[8]);
  $styleInsert = 'style="font-size: 11px; color: ' . $textColor . '"';
  $terms = preg_replace('/<td valign="top">/', '<td valign="top" align="center" ' . $styleInsert . '>', $terms);

  $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne . $emptySpacer . $voucher . $emptySpacer. $lineSpacer . $emptySpacer . $textTwo . $largeSpacer;

  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  $search = "/<!-- terms insert -->/";
  $output = preg_replace($search, $terms, $output);

  $append = "wifi_1_day";

  sendToFile($output, $append, $serverName);

  print_r($output);
}

//Wifi Week 2
foreach(glob("*/templates/*_branded.html") as $filename){
  $template = file_get_contents($filename);
  $parentFolder = preg_replace('/(.*?)\/.*/', '$1', $filename);

  $serverName = nameCheck($parentFolder);

  print($serverName . '<br/><br/>');

  //Get content
  $wifiRow = null;
  $email ="WIFI sign in 2 + 7 Days";
  if($serverName === 'bosleys'){
    $wifiRow = getData($email, 'yates');
  } else{
    $wifiRow = getData($email, $serverName);
  }

  //Get Background Color
  preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  $textColor = textColor($color);

  //Prep Heading
  $heading = file_get_contents($parentFolder . '/bespoke blocks/' . $parentFolder . '_heading.html');
  $heading = str_replace('Heading goes here', $wifiRow[3], $heading);
  $heading = str_replace('align="left"', 'align="center"', $heading);
  $heading = marginBuilder($heading);

  preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);

  $headingStyle = $matches[1][0];
  $headingStyleNew = $headingStyle . ' font-size: 24px;';
  $heading = str_replace($headingStyle, $headingStyleNew, $heading);

  //Prep Image
  $image = file_get_contents('bosleys/_defaults/image.html');
  $image = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', getURL($serverName, $email), $image);

  //Prep Spacers
  $emptySpacer = file_get_contents('basic-spacer.html');
  $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);
  $lineSpacer = lineSpacerBuild($parentFolder);

  //Prep All Text
  $basicText = file_get_contents('bosleys/_defaults/text.html');
  $textOne = $textTwo = $basicText;

  //Prep Text One
  $wifiRow[4] = str_replace('"', '', $wifiRow[4]);
  $textOne = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $wifiRow[4], $textOne);
  //$textOne = str_replace('"', '', $textOne);
  $textOne = preg_replace('/##/m', '<p>', $textOne);

  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';

  $textOne = str_replace('<td class="text" align="left">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textOne);
  $textOne = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textOne);
  $textOne = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textOne);

  //Prep Text Two
  $wifiRow[7] = str_replace('"', '', $wifiRow[7]);
  $textTwo = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $wifiRow[7], $textTwo);
  $textTwo = preg_replace('/##(.+?)##/m', '<p>$1</p>', $textTwo);

  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';

  $textTwo = str_replace('<td class="text" align="left">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textTwo);
  $textTwo = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textTwo);
  $textTwo = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textTwo);

  preg_match('/"emailBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  //print($color . ' : ');
  $textColor = textColor($color);
  //print($textColor . '<br/>');

  $terms = termsBuilder($wifiRow[8]);
  $styleInsert = 'style="font-size: 11px; color: ' . $textColor . '"';
  $terms = preg_replace('/<td valign="top">/', '<td valign="top" align="center" ' . $styleInsert . '>', $terms);

  $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne . $emptySpacer . $lineSpacer . $emptySpacer . $textTwo . $largeSpacer;

  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  $search = "/<!-- terms insert -->/";
  $output = preg_replace($search, $terms, $output);

  $append = "wifi_7_days";

  sendToFile($output, $append, $serverName);

  print_r($output);
}

//Wifi Week 3
foreach(glob("*/templates/*_branded.html") as $filename){
  $template = file_get_contents($filename);
  $parentFolder = preg_replace('/(.*?)\/.*/', '$1', $filename);

  $serverName = nameCheck($parentFolder);

  //Get content
  $wifiRow = null;
  $email ="WIFI sign in 3 + 21 Days";
  if($serverName === 'bosleys'){
    $wifiRow = getData($email, 'yates');
  } else{
    $wifiRow = getData($email, $serverName);
  }

  //Get Background Color
  preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  $textColor = textColor($color);

  //Prep Heading
  $heading = file_get_contents($parentFolder . '/bespoke blocks/' . $parentFolder . '_heading.html');
  $heading = str_replace('Heading goes here', $wifiRow[3], $heading);
  $heading = str_replace('align="left"', 'align="center"', $heading);
  $heading = marginBuilder($heading);

  preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);

  $headingStyle = $matches[1][0];
  $headingStyleNew = $headingStyle . ' font-size: 24px;';
  $heading = str_replace($headingStyle, $headingStyleNew, $heading);

  //Prep Image
  $image = file_get_contents('bosleys/_defaults/image.html');
  $image = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', getURL($serverName, $email), $image);

  //Prep Spacers
  $emptySpacer = file_get_contents('basic-spacer.html');
  $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);
  $lineSpacer = lineSpacerBuild($parentFolder);

  //Prep All Text
  $basicText = file_get_contents('bosleys/_defaults/text.html');
  $textOne = $textTwo = $basicText;

  //Prep Text One
  $wifiRow[4] = str_replace('"', '', $wifiRow[4]);
  $textOne = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $wifiRow[4], $textOne);
  //$textOne = str_replace('"', '', $textOne);
  $textOne = preg_replace('/##/m', '<p>', $textOne);

  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';

  $textOne = str_replace('<td class="text" align="left">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textOne);
  $textOne = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textOne);
  $textOne = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textOne);

  //Prep Voucher
  $voucherInstructions = $wifiRow[9];

  $voucher = file_get_contents($parentFolder . '/bespoke blocks/' . $parentFolder . '_voucher.html');
  $voucherSearch = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
  $voucher = str_replace($voucherSearch, $voucherInstructions, $voucher);

  $voucher = marginBuilder($voucher);

  //Prep Text Two
  $wifiRow[7] = str_replace('"', '', $wifiRow[7]);
  $textTwo = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $wifiRow[7], $textTwo);
  $textTwo = preg_replace('/##(.+?)##/m', '<p>$1</p>', $textTwo);

  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';

  $textTwo = str_replace('<td class="text" align="left">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textTwo);
  $textTwo = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textTwo);
  $textTwo = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textTwo);

  preg_match('/"emailBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  //print($color . ' : ');
  $textColor = textColor($color);
  //print($textColor . '<br/>');

  $terms = termsBuilder($wifiRow[8]);
  $styleInsert = 'style="font-size: 11px; color: ' . $textColor . '"';
  $terms = preg_replace('/<td valign="top">/', '<td valign="top" align="center" ' . $styleInsert . '>', $terms);

  $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne . $emptySpacer . $voucher . $emptySpacer . $lineSpacer . $emptySpacer . $textTwo . $largeSpacer;

  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  $search = "/<!-- terms insert -->/";
  $output = preg_replace($search, $terms, $output);

  $append = "wifi_21_days";

  sendToFile($output, $append, $serverName);

  print_r($output);
}

 ?>
