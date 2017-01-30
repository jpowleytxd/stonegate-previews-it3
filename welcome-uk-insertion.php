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

function databaseQuery($query, $config){
  //Define Connection
  static $connection;

  $configFile;
  if($config === 1){
    $configFile = 'config1.ini';
  } else if($config === 2){
    $configFile = 'config2.ini';
  }

  //Attempt to connect to the database, if connection is yet to be established.
  if(!isset($connection)){
    //Load congig file
    $config = parse_ini_file($configFile);
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

function getData($query, $brand, $config){
  $initialQuery = "SELECT * FROM " . $brand . " WHERE `email` = '" . $query . "'";

  $rows = databaseQuery($initialQuery, $config);

  foreach($rows as $key => $row){
    return $row;
  }
}

function buildTemplateSettings($title, $preHeader, $subject, $brandID, $profileID){
  $settings = '{"emailName":"' . $title . '","textOnly":"","preheader":"' . $preHeader . '","h": -1369038629,"subject":"' . $subject . '","template": "' . $brandID . '","senderProfile":' . $profileID . '}';

  return $settings;
}

function buildTemplateMappings(){
  $mappings = '[{"id": "dynamic1","val": "brand_name"},{"id": "dynamic2","val": "fav_venue_name"},{"id": "dynamic3","val": "fav_venue_code"},{"id": "dynamic4","val": "valid_from"},{"id": "dynamic5","val": "valid_to"},{"id": "dynamic6","val": "content_type"},{"id": "dynamic7","val": "brand_type"},{"id": "dynamic9","val": "new_password"}]';

  return $mappings;
}

function removeWhiteSpace($html){
  $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );

  $replace = array(
      '>',
      '<',
      '\\1'
  );

  $html = preg_replace($search, $replace, $html);
  $html = preg_replace('/\"(\s)data-styles/', '" data-styles', $html);
  $html = preg_replace('/\"(\s)data-mappings/', '" data-mappings', $html);
  $html = preg_replace('/\sdata-variants=""\s/', '" data-variants=""', $html);

  return $html;
}

foreach (glob("pre_made/*/branded/welcome*.html") as $filename) {
    if((strpos($filename, 'uk') !== false)){
      $temp = file_get_contents($filename);

      preg_match_all('/.*?\/(.*?)\/.*?\/(.*?).html/', $filename, $matches);
      $parentFolder = $matches[1][0];
      $type = $matches[2][0];

      //Remove comment tags
      $temp = preg_replace('/\{.*?\}/ms', '', $temp);
      $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);
      $temp = preg_replace('/\'/ms', '\\\'', $temp);
      $temp = removeWhiteSpace($temp);

      $serverName = $parentFolder;

      $upperCaseName = str_replace('_', ' ', $serverName);
      $upperCaseName = ucwords($upperCaseName);

      $type = str_replace('_', ' ', $type);
      $type = ucwords($type);

      if($type === 'Welcome 1 Day Uk'){
        $type="Welcome 1 + 1 Day";
      } else if($type === 'Welcome 7 Days Uk'){
        $type="Welcome 2 + 7 Days";
      } else if($type === 'Welcome 21 Days Uk'){
        $type="Welcome 3 + 21 Days";
      }

      print_r($upperCaseName . '<br/><br/>');

      // $initialQuery = 'SELECT * FROM stonegate_lookup WHERE brand = "' . $upperCaseName . '"';
      //
      // $rows = databaseQuery1($initialQuery, 2);
      //
      // $accountID = null;
      // $profileID = null;
      // $brandID = null;
      // $venueID = null;
      //
      // foreach($rows as $key => $row){
      //   $accountID = $row[2];
      //   $profileID = $row[3];
      //   $brandID = $row[4];
      //   $venueID = $row[5];
      // }
    }
}

 ?>
