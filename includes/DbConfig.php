<?php date_default_timezone_set('Asia/Calcutta'); 
 error_reporting(E_ALL);
 ini_set("display_errors",0);
 /*ini_set('memory_limit', '320000M');
ini_set('max_execution_time', '600000000');
ini_set('post_max_size', '1024M');
ini_set('upload_max_filesize', '100000000000024M');*/
if(!class_exists("db")) { 

	class db 
	{ 
public function open_connection()

{global $connect; 
				 
				$hostname = "localhost";
					$username = "root";
					$password = "";
          $port = "3314";
					$database = "centralized_student_storgae"; 					

					$connect = mysqli_connect($hostname,$username,$password,$database,$port);

//$select_db = mysqli_select_db($connect,$database);
}

//Mysql connection close

public function close_connection()
{
mysqli_close();
}

//Multiple rows fetching

function fetchArray($query_result){	
global $connect;
$array = mysqli_fetch_array($query_result,MYSQLI_ASSOC);
return $array;
}

//Get query result

function get_qresult($get_query){
global $connect;
$get_result = mysqli_query($connect,$get_query) or die($get_query.mysqli_query($connect));

return $get_result;

}
function runMultipleQueries($get_query){
global $connect;
$get_result = mysqli_multi_query($connect,$get_query) or die($get_query.mysqli_multi_query($connect));

return $get_result;
}

function qry($qry) {
        $result = $this->get_qresult($qry);
		 while($row = mysqli_fetch_assoc($result))
				$output[] = $row;
			return $output;
    }

//getting single record

function fetchRow($select_query){
global $connect;
$select_result=mysqli_query($connect,$select_query) or die($select_query.mysqli_query($connect));
$select_row = mysqli_fetch_array($select_result);
   	return $select_row;
}	

//getting mysqli_real_escape_string
function real_escape_string($get_var){
global $connect;
$real_string = mysqli_real_escape_string($connect,$get_var);

return $real_string;

}

function escape_string($get_var){
global $connect;
$real_string = mysqli_escape_string($connect,$get_var);

return $real_string;

}

function insert_id(){
   global $connect;
$insert_idstring = mysqli_insert_id($connect);

return $insert_idstring;

}

//getting selected field in a record

function fetch_field($select_query){
global $connect;
    $select_result=$obj_db->get_qresult($connect,$select_query);
   	$select_row = $obj_db->fetchArray($select_result);
if($select_row) return $select_row[0]; else return 0;
}	

//count records

function fetchNum($select_query){
global $connect;
$select_result=mysqli_query($connect,$select_query) or die($select_query.mysqli_query($connect));
$get_num = mysqli_num_rows($select_result);
return $get_num;
}

function ismscURL($link){
  $http = curl_init($link);
  curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
  $http_result = curl_exec($http);
  $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
  curl_close($http);
  return $http_result;
}
}// class ending
}	

// database tables
        define('TABLE_ACADEMIC_FINANCIAL_YEAR','academic_financial_year'); 
	define('TABLE_ADMINISTRATOR','admin');
	
	define('TABLE_BRANCH','branch_names');
    define('TABLE_BRANCH_SMSUSERS','branch_sms_users');
	define('TABLE_BRANCH_SMSUSERS1','branch_sms_users1');
	
	define('TABLE_UPLOAD_ACADEMIC_CERTIFICATES','upload_academic_certificates');
	define('TABLE_CUST_FEERELOAD','cust_fepayreload');
	define('TABLE_CUSTOMER_GENPAYMENTS','customer_generalpayments');
  define('TABLE_GENPAYMENTS_DATEUPD_DETAILS','customer_generalpayment_update_datedts');
  define('TABLE_COMPONENTS','components');
	define('TABLE_EMPUSER_ATTENDANCELIST','empuser_attendance_list');
	define('TABLE_EMPUSER_DETAILS','empuser_details');
	define('TABLE_EMPUSER_TAKEN_AMTS','empuser_taken_amtdts');
	define('TABLE_EXPENDITURE_CATEGORY','expenditure_category');
	define('TABLE_EXPENDITURE','expenditure');
    define('TABLE_EXPENDITURE_TYPE','expenditure_types');
	define('TABLE_EXP_CATEGORYS_MAP','exp_catg_map');
	define('TABLE_EXPENDITURE_TAB_AMOUNT','exp_table_amount');

	define('TABLE_INVESTMENT_BORROWAMTS','investment_borrowamts'); 
	define('TABLE_LINE_CITYS','line_citys');
	define('TABLE_LINE_NAMES','line_names');
	define('TABLE_LINECHIT_DETAILS','linewise_chitdetails');
	define('TABLE_LINE_CHITAMT_PAIDDETAILS','line_chitamts_paids');
	define('TABLE_LINEWISE_DATEWISE_AMTS','linewise_datewise_amts');
	define('TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS','linewise_sortaccess_borrowfromanotherline');
	define('TABLE_LINEWISE_USEREXIST_AMTS','linewise_user_existamt');
	define('TABLE_LINETAKEN_AMTS','line_taken_amts');
	
define('TABLE_MONTHS','month_table');
	define('TABLE_PARTNERS','partners');
	define('TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY','payments_expenses_tractionshistory');
	define('TABLE_PAYMENT_TYPE','payment_type');
	define('TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE','userwise_paytypewise_avlbalance');
	define('TABLE_USER_DETAILS','user_details');
    define('TABLE_LOGIN_HISTORY','user_login_details');
	define('TABLE_USER_TYPES','user_types');

	
	
	define('TABLE_YEAR','year');
	define('TABLE_YEARLY_BUSROUTE_MAPPING','yearly_branchroute_map');
	define('TABLE_YEARLY_COURSEFEES_MAPPING','yearly_coursefees_mapping');
	define('TABLE_YEARLY_SECTION_MAPPING','yearly_sec_map');
	define('TABLE_WEB_PAGES','web_pages'); 
 

 
	
	define('SITE_URL','http://localhost/centralizedstudentcertificatestorage/'); 
	define('SITE_SSL_URL','http://localhost/centralizedstudentcertificatestorage/');   
 

	define('BASE_URL','/');
		define('SITE_NAME','CENTRALIZED STUDENT STORAGE');
	define('SITE_NAME_TITLE','CENTRALIZED STUDENT STORAGE'); 

define('INSERT_KEYWORD','INSERT IGNORE INTO ');
	define('UPDATE_KEYWORD','UPDATE IGNORE');
	define('DELETE_KEYWORD','DELETE FROM');  

	$obj_db = new db();

	$obj_db->open_connection();
	$obj_admin_db = new db();
	$obj_admin_db->open_connection();
 

  	$admin_select_query="SELECT * FROM ".TABLE_ADMINISTRATOR." where admin_id=1";
    $admin_select_row= $obj_db->fetchRow($admin_select_query);

	      define('ADMIN_EMAIL',$admin_select_row['admin_email']);
          define('ADMIN_MOBILE',$admin_select_row['mobile']);  
define('APPSERVER_KEY',$admin_select_row['app_server_key']); 
		  define('APP_NOTIFICATION_LANDINGPAGE',$admin_select_row['app_notification_landpage']);
  if($_REQUEST['branch_id']!=''){
	$branid=$_REQUEST['branch_id'];
	}else{$branid=$_SESSION['branch_id'];}
 $smsuser="SELECT user_name,pword,status,url,sender_id FROM ".TABLE_BRANCH_SMSUSERS."  ";
  $smsuser_select_row= $obj_db->fetchRow($smsuser);
      define('SMS_URL',$smsuser_select_row['url']);
		   define('SMS_URL2','http://bulksms.highfuturetech.com/API/unicodesms.php'); 
      define('SMS_ENGURL','http://bulksms.highfuturetech.com/API/sms.php'); 
      define('SMS_CHKBAL_URL','http://bulksms.highfuturetech.com/API/get_balance.php');
      define('SENDERID',$smsuser_select_row['sender_id']);
	   define('SMS_USERNAME',$smsuser_select_row['user_name']);
          define('SMS_PWORD',$smsuser_select_row['pword']);
		  define('SMS_PERMISSION','1');
               
function moneyFormatIndia($num) {
    $num = sprintf("%.2f", $num);
    $parts = explode('.', $num);
    $last3 = substr($parts[0], -3);
    $rest = substr($parts[0], 0, -3);
    if ($rest != '') {
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
    }
    return '₹' . $rest . ',' . $last3 . '.' . $parts[1];
} 
  
		 function sendsms($mobile,$msg,$url,$uname,$pword,$senderid,$tempid){
$fields = array('username'=>urlencode($uname),
'password'=>urlencode($pword),
'type'=>urlencode(1),
'from'=>urlencode($senderid),
'template_id'=>urlencode($tempid),
'to'=>urlencode($mobile),
'dnd_check'=>urlencode(0),
'msg'=>urlencode($msg));
foreach($fields as $key=>$value)
{ 
$fields_string.= $key.'='.$value.'&';   
}  
 rtrim($fields_string,'&');
//open connection 
$ch = curl_init(); 
//set the url, number of POST vars, POST data 
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields)); 
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string); 
//execute post  
echo $result = curl_exec($ch);
}

function send_loopsms($mobile,$msg,$url,$uname,$pword,$senderid,$tempid){
$fields = array('username'=>urlencode($uname),
'password'=>urlencode($pword),
'type'=>urlencode(1),
'from'=>urlencode($senderid),
'template_id'=>urlencode($tempid),
'to'=>urlencode($mobile),
'dnd_check'=>urlencode(0),
'msg'=>urlencode($msg));
foreach($fields as $key=>$value)
{ 
$fields_string.= $key.'='.$value.'&';   
}  
 rtrim($fields_string,'&');
$url_final=$url.'?'.$fields_string;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url_final);
$result = curl_exec($ch);
curl_close($ch);
}

function sendunicodesms($mobile,$msg,$url,$uname,$pword,$senderid,$tempid){
$fields = array('username'=>urlencode($uname),
'password'=>urlencode($pword),
'type'=>urlencode(1),
'from'=>urlencode($senderid),
'template_id'=>urlencode($tempid),
'to'=>urlencode($mobile),
'dnd_check'=>urlencode(0),
'msg'=>urlencode($msg));
foreach($fields as $key=>$value)
{ 
$fields_string.= $key.'='.$value.'&';   
}  
 rtrim($fields_string,'&');
//open connection 
$ch = curl_init(); 
//set the url, number of POST vars, POST data 
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields)); 
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string); 
//execute post  
$result = curl_exec($ch);
}


function image_ajaxupload($imagedata,$i,$image_path,$newwidth,$newheight){
	

  if($imagedata["subfile_".$i]["name"]!='') {
   //echo SITE_URL.'../includes/stu_img/'.$id.'.jpg';exit;
$image =$imagedata["subfile_".$i]["name"];
$uploadedfile = $imagedata["subfile_".$i]['tmp_name'];

if ($image) 
{
$filename = stripslashes($imagedata["subfile_".$i]['name']);
$extension = getfileExtension($filename);
$extension = strtolower($extension);
$size=filesize($imagedata["subfile_".$i]['tmp_name']);

$uploadedfile = $imagedata["subfile_".$i]['tmp_name'];
if($extension=='jpg' || $extension=='jpeg'){
$src = imagecreatefromjpeg($uploadedfile);
}elseif($extension=='png'){
$src = imagecreatefrompng($uploadedfile);
}
elseif($extension=='gif'){
$src = imagecreatefromgif($uploadedfile);
}
else{
$src = imagecreatefromjpeg($uploadedfile);
}

list($width,$height)=getimagesize($uploadedfile);
$oldwidth=$width;
$oldheight=$height;
$tmp=imagecreatetruecolor($oldwidth,$oldheight);
$newwidth1=$newwidth;
$newheight1=$newheight;
$tmp1=imagecreatetruecolor($newwidth1,$newheight1);

imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);

 $filename1 =$image_path;

imagejpeg($tmp1,$filename1,100);

imagedestroy($src);
imagedestroy($tmp);
imagedestroy($tmp1);

}
             }


	}
	
			
			function getfileExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);
         return $ext;
        }

	function sms_tmpnamefun($orgsubs,$expexmsubname){
 //$nline="\n";
   $exp_tempsub=explode(':{#var#}',$orgsubs);
  $mxarpos_exp_tempsub=count($exp_tempsub)-1;
   $finalalsubarposlen=array_search(trim($exp_tempsub[$mxarpos_exp_tempsub]),$expexmsubname);
  //array_search(max($expexmsubname), $expexmsubname)
$chkstaallvailblepos=max(array_keys($expexmsubname))-$finalalsubarposlen;
$finalexmsubmatch="";$prevchkky=0;
$validtemp=0;
$arrsubfils=array();
foreach($exp_tempsub as $k=>$v){
    $chkarky=array_search(trim($v),$expexmsubname);
   $availablekyvals=$chkarky-$prevchkky;
if($k==0 && is_numeric($chkarky)){
 $finalexmsubmatch.=$v.':{#var#},'.$nline;
 $arrsubfils[]=$v;
 $prevchkky=1;
  $prevchkkys=$chkarky;
  $validtemp=1;
}
elseif($k>0 && $availablekyvals<=3){
$validtemp=1;$sbuinc=0;
for($l=$prevchkky;$l<=$chkarky;$l++){

/*if($k==1)
 $incky_frmalsub=$prevchkkys+$l;
else */ $incky_frmalsub=$l;
if(!in_array($expexmsubname[$incky_frmalsub],$arrsubfils)){
 $finalexmsubmatch.=$expexmsubname[$incky_frmalsub].':{#var#},'.$nline;
$arrsubfils[]=$expexmsubname[$incky_frmalsub]; 
 }
 $sbuinc++;
 }
 $prevchkky=$chkarky;
  $prevchkkys=$chkarky;
}else{
$validtemp=0;
break;
}

}
 $arrdif=array_diff($expexmsubname,$arrsubfils);
  if($validtemp==1 && $chkstaallvailblepos==1){

$incky_frmalsub=$finalalsubarposlen+1;
 

$finalexmsubmatch.=$expexmsubname[$incky_frmalsub].':{#var#},';
 $expmatsubs=substr($finalexmsubmatch,0,-1);
 }
else $expmatsubs=substr($finalexmsubmatch,0,-1);
// $expmatsubs=explode(',',$expmatsubs);
     return array('validtemp'=>$validtemp,'expmatsubs'=>$expmatsubs);
}
 

function multi_array_search($array, $search) {
    // Create the result array
    $result = array();
 
    // Iterate over each array element
    foreach ($array as $key => $value){

      // Iterate over each search condition
      foreach ($search as $k => $v){

        // If the array element does not meet the search condition then continue to the next element
        if (!isset($value[$k]) || $value[$k] != $v){
		
          continue 2;
        }
      }
      // Add the array element's key to the result array
      $result = $key;
     }
     // Return the result array
    return $result;
  }
  
  function multi_array_searchkeys($array, $search) {
    // Create the result array
 	$results = array();

    // Iterate over each array element
    foreach ($array as $key => $value){

      // Iterate over each search condition
      foreach ($search as $k => $v){

        // If the array element does not meet the search condition then continue to the next element
        if (!isset($value[$k]) || $value[$k] != $v){
		
          continue 2;
        }
      }
      // Add the array element's key to the result array
 	  $results[] = $key;
    }
     // Return the result array
    return $results;
  }
function cleanArrayUtf8($array) {
    array_walk_recursive($array, function(&$value) {
        if (is_string($value)) {
            $value = cleanUtf8($value);
        }
    });
    return $array;
}
function cleanUtf8($string) {
    return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
}

function translateToEnglish($text) {
    $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=en&dt=t&q=" . urlencode($text);
    $response = file_get_contents($url);
    $result = json_decode($response, true);
    return $result[0][0][0];
}

function translateArray(&$array) {
    foreach ($array as &$value) {
        if (is_array($value)) {
            translateArray($value);
        } else {
            $value = translateToEnglish($value);
        }
    }
}
if($_REQUEST['lang']=='' || $_REQUEST['lang']=='en'){
						         $max_char=160;
								  $lang="en";
								 }
								 else{
								  $max_char=70;
								  $lang=$_REQUEST['lang'];
								  }?>