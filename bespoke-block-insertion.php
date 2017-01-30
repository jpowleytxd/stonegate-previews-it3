<?php

ini_set('max_execution_time', 3000);

function sendToFile($output, $append){
  $send = false;
  if($send == true){
    $outputPath = "inserts/";
    $fileType=".sql";

    file_put_contents(($outputPath . $append . $fileType), $output);
  }
}

function databaseQuery($query){
  //Define Connection
  static $connection;

  //Attempt to connect to the database, if connection is yet to be established.
  if(!isset($connection)){
    //Load congig file
    $config = parse_ini_file('config2.ini');
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

$sql = null;
foreach (glob("*/bespoke blocks/*.html") as $filename) {
  $temp = file_get_contents($filename);

  preg_match_all('/(.*?)\/(.*?)\/(.*?).html/', $filename, $matches);

  $parentFolder = $matches[1][0];

  $serverName = $parentFolder;

  $brand = str_replace('_', ' ', $serverName);
  $brand = ucwords($brand);

  $blockType = $matches[3][0];
  $blockType = str_replace($parentFolder . '_', '', $blockType);

  $lowerName = $blockType;
  $lowerName = str_replace('col', 'column', $lowerName);

  $blockType = str_replace('_', ' ', $blockType);
  $blockType = str_replace('col', 'column', $blockType);

  $blockType = ucwords($blockType);

  //Remove comment tags
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);

  $temp = base64_encode($temp);

  $upperCaseName = str_replace('_', ' ', $serverName);
  $upperCaseName = ucwords($upperCaseName);

  $lowerName = 'stonegate_' . $parentFolder . '_' .  $lowerName;

  $initialQuery = 'SELECT * FROM stonegate_lookup WHERE brand = "' . $upperCaseName . '"';

  // $rows = databaseQuery($initialQuery);
  //
  // $accountID = null;
  // $profileID = null;
  // $brandID = null;
  // $venueID = null;
  // $veID = null;
  //
  // foreach($rows as $key => $row){
  //   $accountID = $row[2];
  //   $profileID = $row[3];
  //   $brandID = $row[4];
  //   $venueID = $row[5];
  //   $veID = $row[6];
  // }

  $blockName = $upperCaseName . ' ' . $blockType;

  $sql .= "INSERT INTO `tbl_template_editor_blocks` (`block_name`, `block_account_id`, `block_type_id`, `block_type`, `block_html`, `block_category`) VALUES
          ('" . $blockName . "', '1222', '" . $lowerName . "', 'bespoke', '" . $temp . "', '" . $upperCaseName . "');\n";
}

$append = "bespoke-block-insert";

sendToFile($sql, $append);

print_r($sql);

?>
