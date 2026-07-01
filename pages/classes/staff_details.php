<?php class staff_operations{

function get_staff($id) {
		   global $obj_db;
		   $staff_sel_query="SELECT * FROM ".TABLE_STAFF_DETAILS."  where  staff_id='".(int)$id."' ";
		   $staff_sel_row = $obj_db->fetchRow($staff_sel_query);
		   $staff = $staff_sel_row;
		   return $staff;
		   }

function get_actvie($id) {
		   global $obj_db,$page_url;
		   $staff_actve=UPDATE_KEYWORD."  ".TABLE_STAFF_DETAILS." set status=1,activ_date='".date('Y-m-d h:i:s')."' where  staff_id='".(int)$id."'";
		   $staff_active = $obj_db->get_qresult($staff_actve);
		   redirect_page($page_url);
		   }

function staff_save($data,$id) {

   global $obj_db, $page_url;
   $msg=array();
   $pwrand = rand(10000,99999);
	// echo 'test';exit;
	
	$brn_asid_count=count($_POST['assign_branch_ids']);
	for($b=0;$b<$brn_asid_count;$b++){
	  $assig_brid.=$_POST['assign_branch_ids'][$b].',';
	}
	$assig_brids=substr($assig_brid,0,-1);
	$unique_sec_ids=array_unique($data['sec_ids']);
	$conctcourseids='';
	foreach($unique_sec_ids as $k=>$v)
	  $conctcourseids.=$v.',';
	  $trim_uniquecoursids=substr($conctcourseids,0,-1);
				for($n=0;$n<sizeof($data['sec_ids']);$n++){$subs=array();
	 for($p=0;$p<sizeof($data['subid_'.$_POST['sec_ids'][$n]]);$p++){
	  $assig_secids.=$_POST['sec_ids'][$n].',';
	   $subs[]=$data['subid_'.$_POST['sec_ids'][$n]][$p];
	  
	  }
	  $subcourse[$_POST['sec_ids'][$n]]=$subs;
	}
	$stf_subrws=json_encode($subcourse);

	
	$assig_secids=substr($assig_secids,0,-1);
	 $get_courseids=$obj_db->fetchRow("select group_concat(DISTINCT course_id) as courseids from ".TABLE_SECTION." where sec_id in(0".$assig_secids.")  order by course_id asc");
	$assig_coursids=$get_courseids['courseids'];

	 for($n=0;$n<sizeof($data['subid']);$n++){
	  $assig_subids.=$_POST['subid'][$n].',';
	}
	$assig_sbids=substr($assig_subids,0,-1);
	
	if($data['is_login']==0)
	 $usersts=0;
	else $usersts=1;
	
	 $getorgid_query=$obj_db->fetchRow("SELECT org_id FROM ".TABLE_BRANCH." where branch_id=".$data['branch_id']."");
	if($id)
	 {
	 
		$staff_upd_query=UPDATE_KEYWORD."   ".TABLE_STAFF_DETAILS." SET 
									first_name='".$obj_db->real_escape_string($data['first_name'])."', 
								   last_name='".$obj_db->real_escape_string($data['last_name'])."',
								   gender='".$obj_db->real_escape_string($data['gender'])."',
								   mobile='".$obj_db->real_escape_string($data['mobile'])."', 
								   qualification='".$obj_db->real_escape_string($data['qualification'])."',
								   experience='".$obj_db->real_escape_string($data['experience'])."',
								   salary='".$obj_db->real_escape_string($data['salary'])."',
								   pf_status='".$obj_db->real_escape_string($data['pf_status'])."',
								   pf_value='".$obj_db->real_escape_string($data['pf_value'])."',
								   status='".$obj_db->real_escape_string($data['status'])."',
								   designetion='".$obj_db->real_escape_string($data['designetion'])."',
								   staff_batch_id='".$obj_db->real_escape_string($data['staff_batch_id'])."',
								   biometric_campus_id='".$obj_db->real_escape_string($data['biometric_campus_id'])."',
								   role='".$obj_db->real_escape_string($data['role'])."', 
								   staff_deal_branchids='".$assig_brids."',
								   date_of_birth='".$obj_db->real_escape_string($data['date_of_birth'])."',
								   date_of_join='".$obj_db->real_escape_string($data['date_of_join'])."',
								   email='".$obj_db->real_escape_string($data['email'])."',
								   address='".$obj_db->real_escape_string($data['address'])."',
								   marital_status='".$obj_db->real_escape_string($data['marital_status'])."',
								   branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
								   org_id='".$obj_db->real_escape_string($getorgid_query['org_id'])."',
								   staffcatg_id='".$obj_db->real_escape_string($data['staffcatg_id'])."',
								   device_enroll_id='".$obj_db->real_escape_string($data['device_enroll_id'])."',
								   is_login='".$obj_db->real_escape_string($data['is_login'])."',
								   MachineId='".$obj_db->real_escape_string($data['MachineId'])."',
								   activ_date='".$obj_db->real_escape_string(date('Y-m-d',strtotime($data['activ_date'])))."'
								   WHERE staff_id='".$id."'";
		$res=$obj_db->get_qresult($staff_upd_query);
				   
	}
	else
	{
	 /*echo '<pre>';
	 print_r($_POST);echo '</pre>';exit; */
	
	  $staff_insert_query=INSERT_KEYWORD."   ".TABLE_STAFF_DETAILS." SET 
								  first_name='".$obj_db->real_escape_string($data['first_name'])."', 
								   last_name='".$obj_db->real_escape_string($data['last_name'])."',
								   gender='".$obj_db->real_escape_string($data['gender'])."',
								   mobile='".$obj_db->real_escape_string($data['mobile'])."', 
								   qualification='".$obj_db->real_escape_string($data['qualification'])."',
								   experience='".$obj_db->real_escape_string($data['experience'])."',
								   salary='".$obj_db->real_escape_string($data['salary'])."',
								   pf_status='".$obj_db->real_escape_string($data['pf_status'])."',
								   pf_value='".$obj_db->real_escape_string($data['pf_value'])."',
								   staff_batch_id='".$obj_db->real_escape_string($data['staff_batch_id'])."',
								   biometric_campus_id='".$obj_db->real_escape_string($data['biometric_campus_id'])."',
								   designetion='".$obj_db->real_escape_string($data['designetion'])."',
								   role='".$obj_db->real_escape_string($data['role'])."', 
								   date_of_birth='".$obj_db->real_escape_string($data['date_of_birth'])."',
								   date_of_join='".$obj_db->real_escape_string($data['date_of_join'])."',
								   email='".$obj_db->real_escape_string($data['email'])."',
								   address='".$obj_db->real_escape_string($data['address'])."',
								   marital_status='".$obj_db->real_escape_string($data['marital_status'])."',
								   branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
								   staff_deal_branchids='".$assig_brids."',
								   org_id='".$obj_db->real_escape_string($getorgid_query['org_id'])."',
								   staffcatg_id='".$obj_db->real_escape_string($data['staffcatg_id'])."',
								   device_enroll_id='".$obj_db->real_escape_string($data['device_enroll_id'])."',
								   MachineId='".$obj_db->real_escape_string($data['MachineId'])."',
								   is_login='".$obj_db->real_escape_string($data['is_login'])."',
								   activ_date='".$obj_db->real_escape_string(date('Y-m-d',strtotime($data['activ_date'])))."'";  
	  $res=$obj_db->get_qresult($staff_insert_query); 
	
	 $id=$obj_db->insert_id();
				   $imgpath='../includes/staff_imgs/'.$id.'.jpg';
	 $image_insr=UPDATE_KEYWORD."  ".TABLE_STAFF_DETAILS." SET image_path='$imgpath' where staff_id='$id'";
	 $obj_db->get_qresult($image_insr);	
			 
   // echo $page_url;
   }
   
   
	$user_type=$obj_db->fetchRow("select user_type_id from ".TABLE_USER_TYPES." where type_short_name='STF'");
	$chk_usrsts=$obj_db->fetchNum("select * from ".TABLE_USER_DETAILS." where staff_id='".$id."'");
	$get_usertype=$obj_db->fetchRow("SELECT type_name FROM ".TABLE_USER_TYPES." WHERE user_type_id='".$obj_db->real_escape_string($user_type['user_type_id'])."'");
	 if(!$chk_usrsts && $data['is_login']==1){
	 $usr_dts=INSERT_KEYWORD."   ".TABLE_USER_DETAILS." SET 
							   user_name='".$obj_db->real_escape_string($data['user_name'])."',
							   mobile='".$obj_db->real_escape_string($data['mobile'])."',
							   email='".$obj_db->real_escape_string(strtolower($data['email']))."',
							   user_type_id='".$user_type['user_type_id']."',
							   branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
							   org_id='".$obj_db->real_escape_string($getorgid_query['org_id'])."',
							   staff_id='".$obj_db->real_escape_string($id)."',
							   assign_branch_ids='$assig_brids',
							   assign_course_ids='$assig_corids',
							   assign_sec_ids='$trim_uniquecoursids',
							   assign_sub_ids='".$stf_subrws."',
							   assign_org_ids='$assig_orgids',
							   user_password='".$obj_db->real_escape_string($data['user_password'])."',
							   user_type='".$get_usertype['type_name']."',
							   user_status='".$usersts."'";
		  $usr_res=$obj_db->get_qresult($usr_dts);
		  }
		  else{
		  $usr_per_upd=UPDATE_KEYWORD."   ".TABLE_USER_DETAILS."  SET
							   user_name='".$obj_db->real_escape_string($data['user_name'])."',
							   mobile='".$obj_db->real_escape_string($data['mobile'])."',
							   email='".$obj_db->real_escape_string(strtolower($data['email']))."',
							   user_type_id='".$user_type['user_type_id']."',
							   branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
							   user_password='".$obj_db->real_escape_string($data['user_password'])."',
							   org_id='".$obj_db->real_escape_string($getorgid_query['org_id'])."',
							   assign_branch_ids='$assig_brids',
							   assign_course_ids='$assig_corids',
							   assign_sec_ids='$trim_uniquecoursids',
							   assign_sub_ids='".$stf_subrws."',
							   assign_org_ids='$assig_orgids',
							   user_type='".$get_usertype['type_name']."',
							   user_status='".$usersts."' WHERE staff_id='".$id."'";  
		 $res=$obj_db->get_qresult($usr_per_upd);
		  }
   
   if($data['status']==0 && $data['status']!=''){
	  $getexist_not=$obj_db->fetchNum("select * from ".TABLE_STAFFINACTIVE_REASON." where inactive_date='".date('Y-m-d')."' and staff_id='".$id."'");
	  if($getexist_not){
		$update_reason=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_STAFFINACTIVE_REASON." set reason='".$obj_db->real_escape_string($data['reason'])."' where inactive_date='".date('Y-m-d')."' and staff_id='".$id."'"); 
	  }
	  else{
	  $insert_reason=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_STAFFINACTIVE_REASON." set staff_id='".$id."',
																						 reason='".$obj_db->real_escape_string($data['reason'])."',
																						 inactive_date='".date('Y-m-d')."'"); 
	  }
   }
   
   if($_FILES['file']['tmp_name']!=''){//echo SITE_URL.'../includes/stu_img/'.$id.'.jpg';exit;
	   
	   $image =$_FILES["file"]["name"];
	   $uploadedfile = $_FILES['file']['tmp_name'];
	   
	   if ($image) 
	   {
	   $filename = stripslashes($_FILES['file']['name']);
	   $extension = $this->getExtension($filename);
	   $extension = strtolower($extension);
	   $size=filesize($_FILES['file']['tmp_name']);
	   
	   $uploadedfile = $_FILES['file']['tmp_name'];
	   $src = imagecreatefromjpeg($uploadedfile);
					   
	   list($width,$height)=getimagesize($uploadedfile);
	   $newwidth=$width;
	   $newheight=$height;
	   $tmp=imagecreatetruecolor($newwidth,$newheight);
	   $newwidth1=120;
	   $newheight1=120;
	   $tmp1=imagecreatetruecolor($newwidth1,$newheight1);
	   
	   imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	   
	   imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
	   
	   $imagetyp = explode("/", $_FILES['file']['type']);
	   
	   $filename1 = "../includes/staff_imgs/".$id.".jpg";
	   
	   imagejpeg($tmp1,$filename1,100);
	   
	   imagedestroy($src);
	   imagedestroy($tmp);
	   imagedestroy($tmp1);
	   
	   }
}			 
	unset($data);
 redirect_page($page_url);
   }
   
   function getExtension($str) {
$i = strrpos($str,".");
if (!$i) { return ""; }
$l = strlen($str) - $i;
$ext = substr($str,$i+1,$l);
return $ext;
}
   
   function delete_student($id) {
   global $obj_db, $page_url;
   
	   $std_del=UPDATE_KEYWORD."   ".TABLE_STAFF_DETAILS." set status=2 WHERE  staff_id='".$id."'";
	   $obj_db->get_qresult($std_del);
	   
		
	  redirect_page($page_url);
}
/*-----Start staff_attendance.php-----*/

/*-----Start staff_sub_map.php-----*/
function staff_sub_save($data,$id) {
   global $obj_db, $page_url,$success;
   $msg=array();
   $course_id=$obj_db->fetchRow("select course_id from ".TABLE_SECTION." where sec_id='".$data['sec_id']."'");
   
   if($course_id['course_id']!=''){
		 for($p=0;$p<sizeof($data['staff_id']);$p++){
		 
			 $atten_absent_insert_query=INSERT_KEYWORD."   ".TABLE_STAFF_SUB_MAP." SET 
								   staff_id='".$obj_db->real_escape_string($data['staff_id'][$p])."',
								   branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
								   year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
								   course_id='".$obj_db->real_escape_string($course_id['course_id'])."',
								   sec_id='".$obj_db->real_escape_string($data['sec_id'])."',
								   sub_id='".$obj_db->real_escape_string($data['sub_id'])."'"; 
	 $obj_db->get_qresult($atten_absent_insert_query);
		 }  
	}
   unset($data);
redirect_page($page_url);
}	

function staffsub_del($data) {
   global $obj_db, $page_url,$success;
   $course_id=$obj_db->fetchRow("select course_id,branch_id from ".TABLE_SECTION." where sec_id='".$data['sec_id']."'");
	$del_staff=DELETE_KEYWORD."  ".TABLE_STAFF_SUB_MAP." where course_id='".$course_id['course_id']."' and sec_id='".$data['sec_id']."' and year_id='".$_SESSION['year_id']."' and sub_id='".$data['sub_id']."'";        
			 $obj_db->get_qresult($del_staff);
   
	for($p=0;$p<sizeof($data['staff_ind_id']);$p++){
		$upd_staffsub=INSERT_KEYWORD."   ".TABLE_STAFF_SUB_MAP." SET 
								   staff_id='".$obj_db->real_escape_string($data['staff_ind_id'][$p])."',
								   course_id='".$obj_db->real_escape_string($course_id['course_id'])."',
								   branch_id='".$obj_db->real_escape_string($course_id['branch_id'])."',
								   year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
								   sec_id='".$obj_db->real_escape_string($data['sec_id'])."',
								   sub_id='".$obj_db->real_escape_string($data['sub_id'])."'"; 
	 $obj_db->get_qresult($upd_staffsub);
   
	   }
	   redirect_page($page_url);
   }

/*-----End staff_sub_map.php-----*/
}
?>