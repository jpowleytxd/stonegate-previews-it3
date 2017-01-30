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
foreach (glob("pre_made/*/branded/belly-band.html") as $filename) {
  $temp = file_get_contents($filename);
  $parentFolder = preg_replace('/.*?\/(.*?)\/.*/', '$1', $filename);

  $serverName = $parentFolder;

  //Remove comment tags
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);

  $temp = base64_encode($temp);

  $upperCaseName = str_replace('_', ' ', $serverName);
  $upperCaseName = ucwords($upperCaseName);

  // $initialQuery = 'SELECT * FROM stonegate_lookup WHERE brand = "' . $upperCaseName . '"';

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

  $type = 'Belly Band Template';

  //$name = $upperCaseName . ' - T:' . date("Ymd") . ' -  ' . $type;
  $name = $upperCaseName . ' ' . $type;
  //print_r($name . '<br/>');

  $sql .= "INSERT INTO `tbl_template_editor_templates` (`template_account_id`, `template_name`, `template_subject`, `template_html`, `template_text`, `template_created_datetime`, `template_type`, `template_image`, `template_status`) VALUES
          ('1222', '" . $name . "', NULL, '" . $temp . "', NULL, NULL, '" . $type . "', NULL, '1');\n";
}

$append = "belly-band-insert";

sendToFile($sql, $append);

print_r($sql);

?>
