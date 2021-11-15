<?php



function hr($return = false){
  if ($return) { return "<hr>\n"; } else { echo "<hr>\n"; }
}



function br($return = false){
  if ($return){ return "<br>\n"; } else { echo "<br>\n"; }
}



function dump($var, $return = false){
  if (is_array($var)){
    $out = print_r($var, true);
  } else if (is_object($var)) {
    $out = var_export($var, true);
  } else {
    $out = $var;
  }

  if ($return){
    return "\n<pre style='direction: ltr'>$out</pre>\n";
  } else {
    echo "\n<pre style='direction: ltr'>$out</pre>\n";
  }
}



function getUserId(){
  if (session_isset('user_id')) {
    return session_get('user_id');
  } else {
    return 0;
  }
}



function getCurrentDateTime(){
  return date("Y-m-d H:i:s");
}



function encryptCharacters($characters){
  global $config;
  return sha1($config['salt1']. $characters. $config['salt2']);
}



function randomString($length){
     $token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[mt_rand(0, $max-1)];
    }

    return $token;
}



function getFullUrl(){
  $fullurl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  return $fullurl;
}



function getRequestUri(){
  return $_SERVER['REQUEST_URI'];
}



function baseUrl(){
  global $config;
  return $config['base'];
}



function fullBaseUrl(){
  global $config;
  return 'http://' . $_SERVER['HTTP_HOST'] . $config['base'];
}



function fullUrl(){
  return 'http://' . $_SERVER['HTTP_HOST'];
}



function strhas($string, $search, $caseSensitive = false){
  if ($caseSensitive){
    return strpos($string, $search) !== false;
  } else {
    return strpos(strtolower($string), strtolower($search)) !== false;
  }
}



function message($type, $message, $mustExit = false, $args = array()) {
  $data['message'] = $message;
  $string = View::renderPartial("/message/$type.php", $data, true);

  foreach ($args as $arg=>$value){
    $string = str_replace(':' . $arg, $value, $string);
  }

  $content = $string;
  require_once(getcwd() . "/theme/default.php");

  if ($mustExit){
    exit;
  }
}



function twoDigitNumber($number){
  return ($number < 10) ? $number = "0" . $number : $number;
}



function jdate($date, $format="Y-m-d"){
  $timestamp = strtotime($date);
  $secondsInOneDay = 24*60*60;
  $daysPassed = floor($timestamp / $secondsInOneDay) + 1;

  $days = $daysPassed;
  $month = 11;
  $year = 1348;

  $days -= 19;

  $daysInMonths = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );

  $monthNames = array(
    'فروردین',
    'اردیبهشت',
    'خرداد',
    'تیر',
    'مرداد',
    'شهریور',
    'مهر',
    'آبان',
    'آذر',
    'دی',
    'بهمن',
    'اسفند',
  );


  while (true){
    if ($days > $daysInMonths[$month-1]){
      $days -= $daysInMonths[$month-1];
      $month++;
      if ($month == 13){
        $year++;
        if (($year - 1347) % 4 == 0){
          $days--;
        }
        $month = 1;
      }
    } else {
      break;
    }
  }

  $month = twoDigitNumber($month);
  $days =  twoDigitNumber($days);

  $monthName = $monthNames[$month-1];

  $output = $format;
  $output = str_replace("Y", $year, $output);
  $output = str_replace("m", $month, $output);
  $output = str_replace("d", $days, $output);
  $output = str_replace("M", $monthName, $output);

  return $output;
}

function calcGregorianToJalali($g_d, $g_m, $g_y) {
  $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

  $gy = $g_y-1600;
  $gm = $g_m-1;
  $gd = $g_d-1;

  $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

  for ($i=0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
  if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      // leap and after Feb
      $g_day_no++;
  $g_day_no += $gd;

  $j_day_no = $g_day_no-79;

  $j_np = div($j_day_no, 12053); //12053 = 365*33 + 32/4
  $j_day_no = $j_day_no % 12053;

  $jy = 979+33*$j_np+4*div($j_day_no,1461); // 1461 = 365*4 + 4/4

  $j_day_no %= 1461;

  if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
  }

  for ($j=0; $j < 11 && $j_day_no >= $j_days_in_month[$j]; ++$j)
      $j_day_no -= $j_days_in_month[$j];
  $jm = $j+1;
  $jd = $j_day_no+1;

  return ($jy ."/". $jm ."/". $jd);
}
function div($a, $b){
  return (int)($a/$b);
}



function pagination($url, $showCount, $activeClass, $deactiveClass, $currentPageIndex, $pageCount, $jsFunction = null){
  ob_start();

  if ($jsFunction){
    $tags = "span";
    $action = 'onclick="' . $jsFunction . '(#)"';
  } else {
    $tags = "a";
    $action = 'href="' . $url . '/#"';
  }
  ?>

  <? $rAction = str_replace("#", "1", $action); ?>
  <<?=$tags?> <?=$rAction?> class="<?if($currentPageIndex==1){echo($activeClass);}else{echo($deactiveClass);}?>">1</<?=$tags?>>
  <span>..</span>
<? for ($i=$currentPageIndex-$showCount; $i<=$currentPageIndex+$showCount; $i++){
     if ($i <= 1) { continue; }
     if ($i >= $pageCount) { continue; }
     if ($i == $currentPageIndex) { ?>
      <span class="<?=$activeClass?>"><?=$i?></span>
  <? } else { ?>
      <? $rAction = str_replace("#", $i, $action); ?>
      <<?=$tags?> <?=$rAction?> class="<?=$deactiveClass?>"><?=$i?></<?=$tags?>>
  <? } ?>
<? } ?>
  <span>..</span>
  <? $rAction = str_replace("#", $pageCount, $action); ?>
  <<?=$tags?> <?=$rAction?> class="<?if($currentPageIndex==$pageCount){echo($activeClass);}else{echo($deactiveClass);}?>"><?=$pageCount?></<?=$tags?>>

  <?
  $output = ob_get_clean();
  return $output;
}



function generateHash($length = 32) {
  $characters = '2345679acdefghjkmnpqrstuvwxyz';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}




function session_get($field, $default = null){
  if (isset($_SESSION[$field])) {
    return $_SESSION[$field];
  }

  return $default;
}



function session_isset($field){
  return isset($_SESSION[$field]);
}



function session_set($field, $value){
  $_SESSION[$field] = $value;
}



function session_set_if_undefined($field, $value){
  if (!isset($_SESSION[$field])) {
    $_SESSION[$field] = $value;
  }
}



function post($field, $default = null){
  if (isset($_POST[$field])) {
    return $_POST[$field];
  }

  return $default;
}



function computeDiscountedPrice($price, $discount, $quantity = 1){
  return $quantity * ($price - $discount * $price / 100);
}


function deleteDirectory($dir){
  if (!file_exists($dir)) {
    return true;
  }
  if (!is_dir($dir)) {
    return unlink($dir);
  }

  foreach (scandir($dir) as $item) {
    if ($item == '.' || $item == '..') {
      continue;
    }
    if (!deleteDirectory($dir . "/" . $item)) {
      return false;
    }
  }

  return rmdir($dir);
}


function sendSMSWithPanel($message, $receptors){
    // SMS Panel "http://kavenegar.com"
    require_once(getcwd() . '/system/kavenegarPhpMaster/vendor/autoload.php');

    //Example = "https://github.com/KaveNegar/kavenegar-examples-php"
    try{
        $api = new \Kavenegar\KavenegarApi("684B4F36754A7344454C624A655666594E6C45554258574E554D456550624238");
        $sender = "10000099009090";
        $result = $api->Send($sender,$receptors,$message);
        if($result){
            foreach($result as $r){
                if($r->status == 1){
                    return "SMS_Sent";
                }else{
                    return "There is a problem with sending SMS";
                }
            }
        }
    }
    catch(\Kavenegar\Exceptions\ApiException $e){
        // Happen when webservice output is not 200
        return $e->errorMessage();
    }
    catch(\Kavenegar\Exceptions\HttpException $e){
        // Happen when there is a problem with webservice connection
        return $e->errorMessage();
    }
}
