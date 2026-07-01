<?php 	session_start();
	session_destroy();
	session_start();
     require_once("includes/classes/general.php");
	 require_once("includes/DbConfig.php");
	$expire_time =  time() + 60*60*24*365;
     
	$tablet_browser = 0;
$mobile_browser = 0;
 
if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
   $tablet_browser++;
}

if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
   $mobile_browser++;
}

if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
   $mobile_browser++;
}

$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
$mobile_agents = array(
   'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
   'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
   'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
   'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
   'newt','noki','palm','pana','pant','phil','play','port','prox',
   'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
   'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
   'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
   'wapr','webc','winw','winw','xda ','xda-');

if (in_array($mobile_ua,$mobile_agents)) {
   $mobile_browser++;
}

if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
   $mobile_browser++;
   //Check for tablets on opera mini alternative headers
   $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
   if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
     $tablet_browser++;
   }
}


if (isset($_SERVER['HTTP_COOKIE']) && $_REQUEST['logout']=='logout') {
     $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
	
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
		//unset($_COOKIE[$name]);
        setcookie($name, '', time()-$expire_tim);
		setcookie($name, '', time()-$expire_time,"/");
		 setcookie($name, '',time()-$expire_time,"/",SITE_URL);
}
 redirect_page('index.php');
    }
 	//print_r($_COOKIE);exit;
if(isset($_COOKIE["cokuser_id"]) || isset($_COOKIE["cokuname"])) {
/*$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie=>$cook_val) {
	$_SESSION[$cookie]=$cook_val;
	}*/
	$_SESSION['expire_time']=$_COOKIE['cokexpire_time'];
if($_COOKIE['cokuser_id']>0){
 $usrdt=$obj_db->qry("SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_name='".$_COOKIE['cokuname']."' and user_password='".decryptIt($_COOKIE['cokpword'])."' and user_status=1");
 $userlinearrdts=array();
               $explineids=explode(',',$usrdt[0]['assign_line_ids']);
			  foreach($explineids as $linky=>$linv){
				$getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$linv."', assign_line_ids) > 0   and user_status=1");
                foreach($getline_matchusrids as $mtchusrky=>$matchusrv)  
				$userlinearrdts[$linv][]=array('user_id'=>$matchusrv['user_id'],'full_name'=>$matchusrv['full_name']);
			  } 
if(!$usrdt && $_COOKIE['cokusrtyp']!='parent')
  redirect_page('index.php?logout=logout');
  
					$_SESSION['user_menus']=$usrdt[0]['user_menus'];

$user_dayloghist=$obj_db->fetchNum("select * from   ".TABLE_LOGIN_HISTORY." where user_id='".$obj_db->real_escape_string($_COOKIE['cokuser_id'])."' and  
											date(login_date)='".date('Y-m-d')."'");
if(!$user_dayloghist)	
$insr_admind=$obj_db->get_qresult(INSERT_KEYWORD."   ".TABLE_LOGIN_HISTORY." SET  
			                                user_id='".$obj_db->real_escape_string($_COOKIE['cokuser_id'])."', 
											login_date='".$obj_db->real_escape_string(date('Y-m-d h:i:s'))."'"); 
 if($usrdt[0]['user_type']=='management' || $usrdt[0]['user_type']=='account' || $usrdt[0]['user_type']=='admin' || $usrdt[0]['user_type']=='parent' || $usrdt[0]['user_type']=='principal'){
 
   $decode_permislevels=json_decode($usrdt[0]['permission_levels'],true);
				           $is_datpermis=$decode_permislevels['data_permisdts']['is_havedata_permission'];
				   $datapermisvaliddate=$decode_permislevels['data_permisdts']['datapermis_validdte'];
				   
				   $is_mobilepermis=$decode_permislevels['data_permisdts']['mobile_permission'];
				   $is_stdaddrpermis=$decode_permislevels['data_permisdts']['studentaddress_permission'];
				   $is_pervschlpermis=$decode_permislevels['data_permisdts']['prevschool_detail_permission'];


				   
				   $is_feepay_datepermis=$decode_permislevels['is_feepaydate_permission'];
				    $feepay_permisvaliddate=$decode_permislevels['paydate_permis_validdte'];
				   
				   $is_concession_permis=$decode_permislevels['is_concession_permission'];
				   $concepermisvaliddate=$decode_permislevels['concession_permis_validdte'];

				    $is_expensehave_directpermis=$decode_permislevels['is_expensehave_directpermission'];
				   $exppermisvaliddate=$decode_permislevels['expenpermission_permis_validdte'];

			  $todaydate=strtotime(date('d-m-Y'));
			  $isdatpermis='';$datapermisvalidate='';$isfepaypermis='';$feepaypermisvaliddate='';$isconcessionpermis='';$concepermisvalidate='';
			  $datapermissts=0;	$isdatapermis="";$datapermisvarbles="";
			  $isexpensepermis=0;$exppermisvalidate='';
			  
			  if(($is_mobilepermis==1 && strtotime($datapermisvaliddate)>=$todaydate) || ($is_mobilepermis==1 && $datapermisvaliddate=='')){
			  $datapermisvarbles.="mobile_no,mobile_no2,";
			   $datapermissts++;
			   $mobpermis=1;
			   }
			  if(($is_stdaddrpermis==1 && strtotime($datapermisvaliddate)>=$todaydate) || ($is_stdaddrpermis==1  && $datapermisvaliddate=='')){
			  $datapermissts++;
			  $datapermisvarbles.="street,city,";
			  $stdaddrpermis=1;
			  }
			  if(($is_pervschlpermis==1 && strtotime($datapermisvaliddate)>=$todaydate) || ($is_pervschlpermis==1  && $datapermisvaliddate=='')){
			  $datapermissts++;
			  $datapermisvarbles.="previous_school,previous_city,pin_no,aadhar_no,";
			  $stdprevschl_permis=1;
			  }
			  if($datapermissts>0){
			$isdatapermis=1;
			$datapermisvarbles=substr($datapermisvarbles,0,-1);
			}
			  if(($isdatapermis==1 && strtotime($datapermisvaliddate)>=$todaydate && $datapermisvaliddate!='' && $datapermissts>0) || ($isdatapermis==1 && $datapermisvaliddate=='' && $datapermissts>0)){
			    $isdatpermis=$decode_permislevels['data_permisdts']['is_havedata_permission'];
				   $datapermisvalidate=$decode_permislevels['data_permisdts']['datapermis_validdte'];
				}
              
				 
			 if(($is_feepay_datepermis==1 && strtotime($feepay_permisvaliddate)>=$todaydate && $feepay_permisvaliddate!='') || ($is_feepay_datepermis==1 && $feepay_permisvaliddate==''))
			 {
			 $isfepaypermis=$decode_permislevels['is_feepaydate_permission'];
				   $feepaypermisvaliddate=$decode_permislevels['paydate_permis_validdte'];
			 }
			 if(($is_concession_permis==1 && strtotime($concepermisvaliddate)>=$todaydate && $concepermisvaliddate!='') || ($is_concession_permis==1 && $concepermisvaliddate=='')){
			  $isconcessionpermis=$decode_permislevels['is_concession_permission'];
				   $concepermisvalidate=$decode_permislevels['concession_permis_validdte'];
			 }

			  if(($is_expensehave_directpermis==1 && strtotime($exppermisvaliddate)>=$todaydate && $exppermisvaliddate!='') || ($is_expensehave_directpermis==1 && $exppermisvaliddate=='')){
			  $isexpensepermis=$decode_permislevels['is_expensehave_directpermission'];
				   $exppermisvalidate=$decode_permislevels['expenpermission_permis_validdte'];
			 }
				
			 $permsilevels=json_encode(array('data_permisdts'=>array('is_havedata_permission'=>$isdatpermis,'datapermis_validdte'=>$datapermisvalidate,'mobile_permission'=>$mobpermis,'studentaddress_permission'=>$stdaddrpermis,'prevschool_detail_permission'=>$stdprevschl_permis),'datapermisvarbles'=>$datapermisvarbles,'is_feepaydate_permission'=>$isfepaypermis,'paydate_permis_validdte'=>$feepaypermisvaliddate,'is_concession_permission'=>$isconcessionpermis,'concession_permis_validdte'=>$concepermisvalidate,'is_expensehave_directpermission'=>$isexpensepermis,'expenpermission_permis_validdte'=>$exppermisvalidate));
			
			
			 $usr_per_upd=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_USER_DETAILS."  SET
 										permission_levels='".$permsilevels."' WHERE user_id='".$usrdt[0]['user_id']."'");    
 
 //$get_cuyr=$obj_db->qry("select * from ".TABLE_YEAR." where is_promote=0 and  date(date_format(str_to_date(end_date, '%d-%m-%Y'), '%Y-%m-%d'))>='".date('Y-m-d')."'");
 $get_cuyr=$obj_db->qry("select * from ".TABLE_YEAR." where is_promote=0 ");
 if(count($get_cuyr)>0)
 $_SESSION['year_id']=$get_cuyr[0]['year_id'];
else unset($_SESSION['year_id']);


 $_SESSION['admin_user']=$usrdt[0]['user_name'];
 $_SESSION['user_fullname']=$usrdt[0]['full_name'];
 $_SESSION['user_mobile']=$usrdt[0]['mobile'];
				$_SESSION['urlname']=$_COOKIE['cokurlname'];
					$_SESSION['staff_id']=$usrdt[0]['staff_id'];
				    $_SESSION['user_id'] = $_COOKIE['cokuser_id']; 
					$_SESSION['branch_id'] = $usrdt[0]['branch_id'];
					$_SESSION['branhid'] = $usrdt[0]['branch_id'];
					$_SESSION['angbranch_id'] =0;
					$_SESSION['angcourse_id'] =0;
					$_SESSION['user_pages']=$usrdt[0]['user_pages'];
					$_SESSION['org_id'] = $usrdt[0]['org_id'];
					$_SESSION['user_type'] = $usrdt[0]['user_type'];
					
					$_SESSION['is_mobdata_permis']=$mobpermis;
					$_SESSION['is_stdaddr_permis']=$stdaddrpermis;
					$_SESSION['is_stdprevschl_permis']=$stdprevschl_permis;
					$_SESSION['datapermisvarbles']=$datapermisvarbles;
					$_SESSION['is_feepaydate_permission']=$isfepaypermis;
					$_SESSION['is_concession_permission']=$isconcessionpermis;
					$_SESSION['is_expensehave_directpermission']=$isexpensepermis;
					
					$_SESSION['show_address'] = $usrdt[0]['address'];
					$_SESSION['assign_branch_ids'] = $usrdt[0]['assign_branch_ids'];
					$_SESSION['assign_org_ids'] = $usrdt[0]['assign_org_ids'];
					$_SESSION['assign_course_ids'] = $usrdt[0]['assign_course_ids'];
					$_SESSION['assign_sec_ids'] = $usrdt[0]['assign_sec_ids'];
					$_SESSION['assign_line_ids'] = $usrdt[0]['assign_line_ids'];
					$_SESSION['linematch_users']=$userlinearrdts;
					$_SESSION['is_cashier']=$usrdt[0]['is_cashier'];
					$_SESSION['mob_browser']=$_COOKIE['cokmobile_browser'];
					$_SESSION['is_loggedin']=$_COOKIE['cokis_loggedin'];
		}
		else  redirect_page('index.php?logout=logout');
		}
	
	if(!isLoginSessionExpired()) {// print_r($_SESSION);
	  if ($mobile_browser > 0 && ($_SESSION['is_appgetnotify']==0 || $_SESSION['is_appgetnotify']=''))
	  redirect_page("".SITE_SSL_URL."home.php");
	  elseif ($mobile_browser > 0 && $_SESSION['is_appgetnotify']==1)
	   redirect_page(APP_NOTIFICATION_LANDINGPAGE);
	  
	   redirect_page("".SITE_SSL_URL."home.php");
	} else {
	session_destroy();
		redirect_page('index.php');
	}	
    
}
	//$admin_sel_query=$obj_db->fetchRow("SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_id=2");
	
   if(isset($_POST['btn_login'])) 	{		
	extract($_POST);
	$_POST = remove_slashes($_POST);
	$ermsg="";
	if(!strlen(trim($_POST['lusername']))) $ermsg="* Enter Username.<br>";
  
		if(!strlen(trim($_POST['lpword']))) $ermsg.="* Enter Password<br>";		
      	if($ermsg==""){
		 
	  $admin_sel_query="SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_name='".$obj_db->real_escape_string(trim($_POST['lusername']))."' AND  
						user_password='".trim($_POST['lpword'])."'";
   			
			$admin_sel_row = $obj_db->fetchRow($admin_sel_query);
			$admin_sel_num = $obj_db->fetchNum($admin_sel_query);
					  
			  $chkhostel_status=$obj_db->fetchRow("select is_hostel from ".TABLE_BRANCH." where branch_id='".$admin_sel_row['branch_id']."'");
			  
			//  $std_qry="SELECT b.student_id,first_name,last_name,b.email,b.mobile_no,course_name,c.course_id,d.y_id,d.branch_id,c.course_name,d.branch_id,a.branch_name,branch_city FROM  ".TABLE_BRANCH." a,".TABLE_STUDENTDETAILS." b,".TABLE_COURSE." c,".TABLE_STUDENT_EDU_DETAILS." d where   parent_mobile='".$obj_db->real_escape_string(trim($_POST['lusername']))."' AND pword='".trim($_POST['lpword'])."' and a.student_id=b.student_id and b.is_promote=0 and is_login_permission=1 and is_delete=0 order by b.course_id asc";
			  
			  $decrypt_pword=encryptIt(trim($_POST['lpword']));
			 
			 // $parent_sel_query="SELECT student_id FROM ".TABLE_STUDENTDETAILS."  WHERE parent_mobile='".$obj_db->real_escape_string(trim($_POST['lusername']))."' AND pword='".trim($_POST['lpword'])."'";  
 			if($admin_sel_num)	{		
			if($admin_sel_row['user_status']==1){	
				$userlinearrdts=array();
               $explineids=explode(',',$admin_sel_row['assign_line_ids']);
			  foreach($explineids as $linky=>$linv){
				$getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$linv."', assign_line_ids) > 0 and user_status=1");
                foreach($getline_matchusrids as $mtchusrky=>$matchusrv)  
				$userlinearrdts[$linv][]=array('user_id'=>$matchusrv['user_id'],'full_name'=>$matchusrv['full_name']);
			  }  
			
			  $decode_permislevels=json_decode($admin_sel_row['permission_levels'],true);
                 $is_datpermis=$decode_permislevels['data_permisdts']['is_havedata_permission'];
				   $datapermisvaliddate=$decode_permislevels['data_permisdts']['datapermis_validdte'];
				   
				   $is_mobilepermis=$decode_permislevels['data_permisdts']['mobile_permission'];
				   $is_stdaddrpermis=$decode_permislevels['data_permisdts']['studentaddress_permission'];
				   $is_pervschlpermis=$decode_permislevels['data_permisdts']['prevschool_detail_permission'];


				   
				   $is_feepay_datepermis=$decode_permislevels['is_feepaydate_permission'];
				    $feepay_permisvaliddate=$decode_permislevels['paydate_permis_validdte'];
				   
				   $is_concession_permis=$decode_permislevels['is_concession_permission'];
				   $concepermisvaliddate=$decode_permislevels['concession_permis_validdte'];

				   $is_expensehave_directpermis=$decode_permislevels['is_expensehave_directpermission'];
				   $exppermisvaliddate=$decode_permislevels['expenpermission_permis_validdte'];
				   
			  $todaydate=strtotime(date('d-m-Y'));
			  $isdatpermis='';$datapermisvalidate='';$isfepaypermis='';$feepaypermisvaliddate='';$isconcessionpermis='';$concepermisvalidate='';
			  $datapermissts=0;	$isdatapermis="";$datapermisvarbles="";
			  $isexpensepermis=0;$exppermisvalidate='';
			  
			  if(($is_mobilepermis==1 && strtotime($datapermisvaliddate)>=$todaydate) || ($is_mobilepermis==1 && $datapermisvaliddate=='')){
			  $datapermisvarbles.="mobile_no,mobile_no2,";
			   $datapermissts++;
			   $mobpermis=1;
			   }
			  if(($is_stdaddrpermis==1 && strtotime($datapermisvaliddate)>=$todaydate) || ($is_stdaddrpermis==1  && $datapermisvaliddate=='')){
			  $datapermissts++;
			  $datapermisvarbles.="street,city,";
			  $stdaddrpermis=1;
			  }
			  if(($is_pervschlpermis==1 && strtotime($datapermisvaliddate)>=$todaydate) || ($is_pervschlpermis==1  && $datapermisvaliddate=='')){
			  $datapermissts++;
			  $datapermisvarbles.="previous_school,previous_city,pin_no,aadhar_no,";
			  $stdprevschl_permis=1;
			  }
			  
			  if($datapermissts>0){
			$isdatapermis=1;
			$datapermisvarbles=substr($datapermisvarbles,0,-1);
			}
			  if(($isdatapermis==1 && strtotime($datapermisvaliddate)>=$todaydate && $datapermisvaliddate!='' && $datapermissts>0) || ($isdatapermis==1 && $datapermisvaliddate=='' && $datapermissts>0)){
			    $isdatpermis=$decode_permislevels['data_permisdts']['is_havedata_permission'];
				   $datapermisvalidate=$decode_permislevels['data_permisdts']['datapermis_validdte'];
				}
              
				 
			 if(($is_feepay_datepermis==1 && strtotime($feepay_permisvaliddate)>=$todaydate && $feepay_permisvaliddate!='') || ($is_feepay_datepermis==1 && $feepay_permisvaliddate==''))
			 {
			 $isfepaypermis=$decode_permislevels['is_feepaydate_permission'];
				   $feepaypermisvaliddate=$decode_permislevels['paydate_permis_validdte'];
			 }
			 if(($is_concession_permis==1 && strtotime($concepermisvaliddate)>=$todaydate && $concepermisvaliddate!='') || ($is_concession_permis==1 && $concepermisvaliddate=='')){
			  $isconcessionpermis=$decode_permislevels['is_concession_permission'];
				   $concepermisvalidate=$decode_permislevels['concession_permis_validdte'];
			 }
			
 			  if(($is_expensehave_directpermis==1 && strtotime($exppermisvaliddate)>=$todaydate && $exppermisvaliddate!='') || ($is_expensehave_directpermis==1 && $exppermisvaliddate=='')){
			  $isexpensepermis=$decode_permislevels['is_expensehave_directpermission'];
				   $exppermisvalidate=$decode_permislevels['expenpermission_permis_validdte'];
			 }

			$permsilevels=json_encode(array('data_permisdts'=>array('is_havedata_permission'=>$isdatpermis,'datapermis_validdte'=>$datapermisvalidate,'mobile_permission'=>$mobpermis,'studentaddress_permission'=>$stdaddrpermis,'prevschool_detail_permission'=>$stdprevschl_permis),'datapermisvarbles'=>$datapermisvarbles,'is_feepaydate_permission'=>$isfepaypermis,'paydate_permis_validdte'=>$feepaypermisvaliddate,'is_concession_permission'=>$isconcessionpermis,'concession_permis_validdte'=>$concepermisvalidate,'is_expensehave_directpermission'=>$isexpensepermis,'expenpermission_permis_validdte'=>$exppermisvalidate));
			
			 $usr_per_upd=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_USER_DETAILS."  SET
 										permission_levels='".$permsilevels."' WHERE user_id='".$admin_sel_row['user_id']."'");  
			
$user_dayloghist=$obj_db->fetchNum("select * from   ".TABLE_LOGIN_HISTORY." where user_id='".$obj_db->real_escape_string($admin_sel_row['user_id'])."' and date(login_date)='".date('Y-m-d')."'");
if(!$user_dayloghist)	
$insr_admind=$obj_db->get_qresult(INSERT_KEYWORD."   ".TABLE_LOGIN_HISTORY." SET 
			                                user_id='".$obj_db->real_escape_string($admin_sel_row['user_id'])."', 
											login_date='".$obj_db->real_escape_string(date('Y-m-d h:i:s'))."'"); 
											
//$get_cuyr=$obj_db->qry("select * from ".TABLE_YEAR." where is_promote=0 and  date(date_format(str_to_date(end_date, '%d-%m-%Y'), '%Y-%m-%d'))>='".date('Y-m-d')."'");
$get_cuyr=$obj_db->qry("select * from ".TABLE_YEAR." where is_promote=0 ");
 if(count($get_cuyr)>0)
 $_SESSION['year_id']=$get_cuyr[0]['year_id'];
else unset($_SESSION['year_id']);


               $_SESSION['admin_user']=$admin_sel_row['user_name'];
			   $_SESSION['user_fullname']=$admin_sel_row['full_name'];
 $_SESSION['user_mobile']=$admin_sel_row['mobile'];
				$_SESSION['admin_id']=$admin_sel_row['admin_id'];
				$_SESSION['urlname']="acccontbookgmd";
 					$_SESSION['staff_id']=$admin_sel_row['staff_id'];
				    $_SESSION['user_id'] = $admin_sel_row['user_id']; 
					$_SESSION['branch_id'] = $admin_sel_row['branch_id'];
					$_SESSION['branhid'] = $admin_sel_row['branch_id'];
					$_SESSION['angbranch_id'] =0;
 					$_SESSION['user_type'] = $admin_sel_row['user_type'];
					$_SESSION['show_address'] = $admin_sel_row['show_address'];
					$_SESSION['assign_branch_ids'] = $admin_sel_row['assign_branch_ids'];
  					 $_SESSION['assign_line_ids'] = $admin_sel_row['assign_line_ids'];
 					 $_SESSION['linematch_users']=$userlinearrdts;
					 

 					$_SESSION['is_cashier']=$admin_sel_row['is_cashier'];
					$_SESSION['mob_browser']=$mobile_browser;
					
					$_SESSION['user_pages']=$admin_sel_row['user_pages'];
					$_SESSION['user_menus']=$admin_sel_row['user_menus'];
					
					$_SESSION['is_mobdata_permis']=$mobpermis;
					$_SESSION['is_stdaddr_permis']=$stdaddrpermis;
					$_SESSION['is_stdprevschl_permis']=$stdprevschl_permis;
					$_SESSION['datapermisvarbles']=$datapermisvarbles;
					$_SESSION['is_feepaydate_permission']=$isfepaypermis;
					$_SESSION['is_concession_permission']=$isconcessionpermis;
					$_SESSION['is_expensehave_directpermission']=$isexpensepermis;
					
					$_SESSION['expire_time']=$expire_time;
					
					setcookie("cokexpire_time",$expire_time,$expire_time,'/');
					setcookie("cokuname",trim($_POST['lusername']),$expire_time,'/');
					setcookie("cokusrtyp",$admin_sel_row['user_type'],$expire_time,'/');
					setcookie("cokpword",$decrypt_pword,$expire_time,'/');
  				    setcookie("cokurlname","acccontbookgmd",$expire_time,'/');
				    setcookie("cokuser_id",$admin_sel_row['user_id'],$expire_time,'/');
					setcookie("cokloggedin_time",date('d-m-Y H:i:s'),$expire_time,'/');
					setcookie("cokis_loggedin","true",$expire_time,'/');
					if ($tablet_browser > 0) {
						  // do something for tablet devices
						//echo  $_SESSION['pc_type']='tablet';
						}
						else if ($mobile_browser > 0) {
						  // do something for mobile devices
						 //echo $_SESSION['pc_type']='mobile';
						 redirect_page("".SITE_SSL_URL."home.php");
						}
						else {
						  // do something for everything else
						 // echo $_SESSION['pc_type']='desktop';
						}
						 //echo "".SITE_SSL_URL.$admin_sel_row['user_type']."/index.php";exit;
				redirect_page("".SITE_SSL_URL."home.php");
				} 
				else { $ermsg="Your Permission Status is Inactive";}
			 }
			 else $ermsg="Invalid Username or Password";						
		
	/*--------------*/
	}	
   	}	
  
  
  /*--Forgotpassword.php--------*/
  
  
   if(isset($_POST['forgot_password'])) 	{		

      	$nline="\n";	
		
		 $smsuser="SELECT user_name,pword,status,url FROM ".TABLE_BRANCH_SMSUSERS."";
  $smsuser_select_row= $obj_db->fetchRow($smsuser);
			 $url ='http://sms.highfuturetech.com/postsms.aspx';

	extract($_POST);

	$_POST = remove_slashes($_POST);

	$mermsg="";
    if(!strlen(trim($_POST[user_name]))) $mermsg="* Enter User Name.<br>";
	if(!strlen(trim($_POST[sms_mobile]))) $mermsg.="* Enter Sms Mobile.<br>";

		// elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $_POST['email'])) $mermsg="email address appears to be incorrect.<br>";	
      	if($mermsg==""){
		
	 $admin_sel_query="SELECT * FROM ".TABLE_ADMINISTRATOR." WHERE user_name='".$obj_db->real_escape_string(trim($_POST[user_name]))."' and sms_mobile='".encryptIt(trim($_POST[sms_mobile]))."'";

			$admin_sel_row = $obj_db->fetchRow($admin_sel_query);
			if($admin_sel_row)	{		
			if($admin_sel_row[admin_status]==1){		
			$dec_pwrd=decryptIt($admin_sel_row['admin_password']);
			$dec_mob=decryptIt($admin_sel_row['sms_mobile']);
			
			 $mobmsg="Dear User your".$nline."username : ".$admin_sel_row['user_name'].",".$nline."Password :".$dec_pwrd;
			

            sendsms($dec_mob,$mobmsg,$url,$smsuser_select_row['user_name'],$smsuser_select_row['pword']);

		$msge='<table style="width:750px; " border="0" align="center" cellpadding="0" cellspacing="0";> 
               <tbody><tr> 
               <td> 
     <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
       <tbody><tr> 
         <td colspan="2"> 
          <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 16px; color: #585858;line-height: 30px; padding:20px 30px 20px 40px;"> 
            <tbody>
			<tr><td style="height:30px;"><h1>'.SITE_NAME.'</h1></td></tr>
			<tr><td style="height:30px;">&nbsp;</td></tr>
			<tr><td style="height:30px; width:600px;">Dear '.$admin_sel_row['user_name'].'</td></tr>
			<tr><td><div style="width:137px; float:left;">User Name:</div><div style="padding-left: 10px; width:100px; float:left;">'.$admin_sel_row['user_name'].'</div></td></tr>
           <tr><td><div style="width:137px; float:left;">Password:</div><div style="padding-left: 10px; width:100px; float:left;">'.$admin_sel_row['admin_password'].'</div></td>            </tr>
		<tr><td><div style="width:137px; float:left;">Please Contact:</div><div style="padding-left: 10px; width:100px; float:left;">'.SITE_SSL_URL.'</div></td></tr>
			<br><br><br>

			<tr><td colspan="4" style="padding-left: 10px;">
			Thanks for Your Continued Support to us. </td></tr>
             <tr><td colspan="4" style="padding-left: 10px;">
              Regards,
			  </td></tr>
			  <tr><td colspan="4" style="padding-left: 10px;">
             '.SITE_NAME_TITLE.' <br>
              91 - '.ADMIN_MOBILE.'

			</td></tr>
          </tbody></table> 
          </td> 
        </tr> 
      </tbody></table> </td> 
    </tr> 

  </tbody></table>';

  $From_Display = SITE_NAME_TITLE;

					 $usermail=$_POST['email'];
					$From_Email = ADMIN_EMAIL;
					$To_Email = $usermail;
					$CC = "";
					$BCC = "";
					$Sub = SITE_NAME_TITLE;
					$Message = $msge;
					$Format = 1;
					$msg2=SendMail($From_Display,$From_Email,$To_Email,$CC,$BCC,$Sub,$Message,$Format);	

				redirect_page("".SITE_SSL_URL."index.php?success=success");
				} 
				else $ermsg="<br>* Your Permission Status is Inactive";
			 }

			 else if(!$admin_sel_row) {
			   $user_sel_query= "SELECT * FROM ".TABLE_USER_DETAILS." a,".TABLE_USER_TYPES." b WHERE user_name='".$obj_db->real_escape_string(trim($_POST[user_name]))."' and sms_mobile='".encryptIt(trim($_POST[sms_mobile]))."'";  

			  $user_sel_row = $obj_db->fetchRow($user_sel_query);
			    if($user_sel_row)	{		
			     if($user_sel_row[user_status]==1){	
				 
				 $dec_pwrd=decryptIt($user_sel_row['user_password']);
			$dec_mob=decryptIt($user_sel_row['sms_mobile']);
			
			 $mobmsg="Dear User your".$nline."username : ".$user_sel_row['user_name'].",".$nline."Password :".$dec_pwrd;
			

            sendsms($dec_mob,$mobmsg,$url,$smsuser_select_row['user_name'],$smsuser_select_row['pword']);
				 $msge='<table style="width:750px; " border="0" align="center" cellpadding="0" cellspacing="0";> 

      <tbody><tr> 

     <td> 

     <table width="100%" border="0" cellpadding="0" cellspacing="0"> 

       <tbody><tr> 

         <td colspan="2"> 

          <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 16px; color: #585858;line-height: 30px; padding:20px 30px 20px 40px;"> 

            <tbody>

			<tr><td style="height:30px;"><h1>'.SITE_NAME.' </h1></td></tr>

			<tr><td style="height:30px;">&nbsp;</td></tr>

			<tr><td style="height:30px; width:600px;">Dear '.$user_sel_row['user_name'].'</td></tr>

			<tr><td><div style="width:137px; float:left;">User Name:</div><div style="padding-left: 10px; width:100px; float:left;">'.$user_sel_row['user_name'].'</div></td></tr>

           <tr><td><div style="width:137px; float:left;">Password:</div><div style="padding-left: 10px; width:100px; float:left;">'.$user_sel_row['user_password'].'</div></td></tr>

		<tr><td><div style="width:137px; float:left;">Please Contact:</div><div style="padding-left: 10px; width:100px; float:left;">'.SITE_SSL_URL.'</div></td></tr>

			<br><br><br>

			<tr><td colspan="4" style="padding-left: 10px;">

			Thanks for Your Continued Support to us. </td></tr>

             <tr><td colspan="4" style="padding-left: 10px;">

              Regards,

			  </td></tr>

			  <tr><td colspan="4" style="padding-left: 10px;">'

              .SITE_NAME.' <br>

              91 - '.ADMIN_MOBILE.'

			</td></tr>

          </tbody></table> 

          </td> 

        </tr> 

      </tbody></table> </td> 

    </tr> 

  </tbody></table>';

  $From_Display = SITE_NAME_TITLE;

					 $usermail=$_POST['email'];
					$From_Email = ADMIN_EMAIL;
					$To_Email = $usermail;
					$CC = "";
					$BCC = "";
					$Sub = SITE_NAME;
					$Message = $msge;
					$Format = 1;
					$msg2=SendMail($From_Display,$From_Email,$To_Email,$CC,$BCC,$Sub,$Message,$Format);	
                    redirect_page("".SITE_SSL_URL."index.php?success=success");
			    }
				else $mermsg="<br>* Your Permission Status is Inactive";
				}
				else $mermsg="<br>* Invalid  Username or Sms Mobile";		
			 }
			else $mermsg="<br>* Invalid Username or Sms Mobile";						
		}	
   	}	
	
	 
?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN"
"http://www.wapforum.org/DTD/wml_1.1.xml">
<html lang="en" class="loading">
  
<head>
   
    <title><?php echo SITE_NAME;?></title>

   <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: url("includes/images/bk.jpg") no-repeat center center/cover; /* Change background here */
    }

    .container {
      width: 360px;
      background: rgb(255 255 255 / 43%);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.3);
      text-align: center;
      backdrop-filter: blur(5px);
    }

    .logo {
      margin-top: 20px;
    }

    .logo img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .form {
      padding: 20px 30px 30px;
      text-align: center;
    }

    .tabs {
      display: flex;
      justify-content: space-around;
      margin: 20px 0;
    }

    .tabs span {
      font-weight: bold;
      color: #555;
      cursor: pointer;
      padding-bottom: 6px;
    }

    .tabs span.active {
      color: #05024e;
      
      padding: 4px 5px 3px 5px;
    border-radius: 8px;
    font-size: 21px;
     }

    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #2b00ff, #210399);
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 25px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      opacity: 0.9;
    }

    .forgot {
      margin-top: 12px;
      font-size: 13px;
      color: #666;
      cursor: pointer;
    }

    .forgot:hover {
      text-decoration: underline;
    }
  </style>
  </head>
  <body>
  
<div class="container">
    <!-- Logo Section -->
    <div class="logo">
      <img src="includes/images/logo.jpg" alt="Logo">
    </div>

    <!-- Form Section -->
	  <form class="card-body1" action="" method="post" name="frmDefault">
    <div class="form">
     <div class="tabs">
        <span class="active">WEL COME TO MVR TRUST</span>
        
      </div>
      <input type="text"    placeholder="User Id" value="<?php echo stripcslashes(trim($_POST['lusername']));?>" id="lusername" name="lusername">
      <input type="password" placeholder="Password" value="<?php echo stripcslashes(trim($_POST['lpword']));?>" name="lpword">
      <button class="btn" name="btn_login" type="submit">Login</button>
      <div class="margin text-center error " style=" background-color:#FF0000;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #FFFFFF;";>
                <?php echo $ermsg;?>
               </div>
    </div>
	  </form>
  </div>
  
  </body>
</html>