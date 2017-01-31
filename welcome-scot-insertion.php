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

function databaseQuery1($query){
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

function databaseQuery2($query){
  //Define Connection
  static $connection;

  //Attempt to connect to the database, if connection is yet to be established.
  if(!isset($connection)){
    //Load congig file
    $config = parse_ini_file('config1.ini');
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

function buildTemplateSettings($title, $preHeader, $subject, $brandID, $profileID){
  $settings = '{"emailName":"' . $title . '","textOnly":"","preheader":"' . $preHeader . '","h": -1369038629,"subject":"' . $subject . '","template": "' . $brandID . '","senderProfile":"' . $profileID . '"}';

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

$sql1 = null;
$sql2 = null;
$sql3 = null;

foreach (glob("client.demo/*/branded/welcome*.html") as $filename) {
  if((strpos($filename, 'scot') !== false)){
    $temp = file_get_contents($filename);

    preg_match_all('/.*?\/(.*?)\/.*?\/(.*?).html/', $filename, $matches);
    $parentFolder = $matches[1][0];
    $type = $matches[2][0];

    //Remove comment tags
    // $temp = preg_replace('/\{.*?\}/ms', '', $temp);
    $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);
    $temp = preg_replace('/\'/ms', '\\\'', $temp);
    $temp = str_replace("WHAT\'S", "WHAT&apos;S", $temp);
    $temp = removeWhiteSpace($temp);

    $serverName = $parentFolder;

    $upperCaseName = str_replace('_', ' ', $serverName);
    $upperCaseName = ucwords($upperCaseName);

    $type = str_replace('_', ' ', $type);
    $type = ucwords($type);

    if($type === 'Welcome 1 Day Scot'){
      $type="Welcome 1 + 1 Day (Scot)";
    } else if($type === 'Welcome 7 Days Scot'){
      $type="Welcome 2 + 7 Days (Scot)";
    } else if($type === 'Welcome 21 Days Scot'){
      $type="Welcome 3 + 21 Days (Scot)";
    }

    $initialQuery = 'SELECT * FROM stonegate_lookup WHERE brand = "' . $upperCaseName . '"';

    $rows = databaseQuery1($initialQuery);

    $accountID = null;
    $profileID = null;
    $brandID = null;
    $venueID = null;

    foreach($rows as $key => $row){
      $accountID = $row[2];
      $profileID = $row[3];
      $brandID = $row[4];
      $venueID = $row[5];
    }

    $name = $upperCaseName . ' - T:' . date("Ymd") . ' - ' . $type;

    //Get content
    $rows = null;
    if($serverName === 'bosleys'){
      $initialQuery = 'SELECT * FROM yates';
      $rows = databaseQuery2($initialQuery);
    } else{
      $initialQuery = 'SELECT * FROM ' . $serverName . '';
      $rows = databaseQuery2($initialQuery);
    }

    $rowCount = null;

    $welcomeRows = array();

    foreach($rows as $key => $row){

      foreach($row as $i => $single){

        if($i === 0){
          if(strpos($single, 'Welcome') !== false){
            array_push($welcomeRows, $row);
            $rowCount++;
          }
        }
      }
    }

    $subject = null;
    $voucher = null;
    $preHeader = null;

    if($type === 'Welcome 1 + 1 Day (Scot)'){
      $subject = $welcomeRows[1][2];
      $preHeader = $welcomeRows[1][3];
      $voucher = '0';
    } else if($type === 'Welcome 2 + 7 Days (Scot)'){
      $subject = $welcomeRows[2][2];
      $preHeader = $welcomeRows[2][3];
      $voucher = '0';
    } else if($type === 'Welcome 3 + 21 Days (Scot)'){
      $subject = $welcomeRows[3][2];
      $preHeader = $welcomeRows[3][3];
      $voucher = '1';
    }

    $settings = buildTemplateSettings($name, $preHeader, $subject, $brandID, $profileID);
    $mappings = buildTemplateMappings();

    if($type === 'Welcome 1 + 1 Day (Scot)'){
      $sql1 .= "insert into `tbl_email_templates` (`template_account_id`, `template_status`, `template_html`, `template_text`, `template_title`, `template_description`, `template_added`, `template_modified`, `template_visible`, `template_subject`, `template_preview`, `template_last_used`, `template_sender_id`, `template_dynamic1_mapping`, `template_dynamic2_mapping`, `template_dynamic3_mapping`, `template_dynamic4_mapping`, `template_dynamic5_mapping`, `template_dynamic6_mapping`, `template_dynamic7_mapping`, `template_dynamic8_mapping`, `template_isTemp`, `template_visual_editor`, `template_has_voucher`, `template_ve_settings`, `template_ve_mappings`)
              values('" . $accountID . "', '1', '" . $temp . "', '', '" . $name . "', '', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', '1', '" . $subject . "', '', '" . date("Y-m-d H:i:s") . "', '" . $profileID . "', '', '', '', '', '', '', '', '', '0', '1', '" . $voucher . "', '" . $settings . "', '" . $mappings . "');\n";
    } else if($type === 'Welcome 2 + 7 Days (Scot)'){
      $sql2 .= "insert into `tbl_email_templates` (`template_account_id`, `template_status`, `template_html`, `template_text`, `template_title`, `template_description`, `template_added`, `template_modified`, `template_visible`, `template_subject`, `template_preview`, `template_last_used`, `template_sender_id`, `template_dynamic1_mapping`, `template_dynamic2_mapping`, `template_dynamic3_mapping`, `template_dynamic4_mapping`, `template_dynamic5_mapping`, `template_dynamic6_mapping`, `template_dynamic7_mapping`, `template_dynamic8_mapping`, `template_isTemp`, `template_visual_editor`, `template_has_voucher`, `template_ve_settings`, `template_ve_mappings`)
              values('" . $accountID . "', '1', '" . $temp . "', '', '" . $name . "', '', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', '1', '" . $subject . "', '', '" . date("Y-m-d H:i:s") . "', '" . $profileID . "', '', '', '', '', '', '', '', '', '0', '1', '" . $voucher . "', '" . $settings . "', '" . $mappings . "');\n";
    } else if($type === 'Welcome 3 + 21 Days (Scot)'){
      $sql3 .= "insert into `tbl_email_templates` (`template_account_id`, `template_status`, `template_html`, `template_text`, `template_title`, `template_description`, `template_added`, `template_modified`, `template_visible`, `template_subject`, `template_preview`, `template_last_used`, `template_sender_id`, `template_dynamic1_mapping`, `template_dynamic2_mapping`, `template_dynamic3_mapping`, `template_dynamic4_mapping`, `template_dynamic5_mapping`, `template_dynamic6_mapping`, `template_dynamic7_mapping`, `template_dynamic8_mapping`, `template_isTemp`, `template_visual_editor`, `template_has_voucher`, `template_ve_settings`, `template_ve_mappings`)
              values('" . $accountID . "', '1', '" . $temp . "', '', '" . $name . "', '', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', '1', '" . $subject . "', '', '" . date("Y-m-d H:i:s") . "', '" . $profileID . "', '', '', '', '', '', '', '', '', '0', '1', '" . $voucher . "', '" . $settings . "', '" . $mappings . "');\n";
      $voucher = '1';
    }
  }
}

$append = "welcome-1-day-scot-insert";
sendToFile($sql1, $append);

$append = "welcome-7-days-scot-insert";
sendToFile($sql2, $append);

$append = "welcome-21-days-scot-insert";
sendToFile($sql3, $append);

print($sql1);
print($sql2);
print($sql3);
 ?>
