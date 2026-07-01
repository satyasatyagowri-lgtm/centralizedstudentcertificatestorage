<?php 
 class cashier_reportoperations{
 
   function update_cancel_status($recepid){
     global $obj_db,$page_url;
	     $sel_cancel_recepquery=$obj_db->fetchRow("select receipt_cancel_status from ".TABLE_FEE_PAYMENT." where receipt_no='".$recepid."'");
		 if($sel_cancel_recepquery['receipt_cancel_status']==1)
		  {$status=0;}
		 else{$status=1;}
		 
	      $update_studentfee=$obj_db->get_qresult("UPDATE ".TABLE_FEE_PAYMENT." SET receipt_cancel_status='$status',
		                                                                            permission_givenby='".$_SESSION['user_id']."',
											                                        permission_givendate='".date('Y-m-d H:i:s')."' where  receipt_no='".$recepid."'"); 

	
		
			//echo $sel_cancel_recepquery['receipt_cancel_status'];exit;  
		 redirect_page($page_url."&dt=".$_GET['dt']);
		 
   }
   
  /*---start schooltimings.php--------*/ 
   function get_schooltiming($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_SCHOOL_TIMINGS." WHERE id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
   
   function insert_schooltimings($data,$id){
     global $obj_db,$page_url;
	     if($id)
			  {
			     $upd_schooltimings="UPDATE  ".TABLE_SCHOOL_TIMINGS." SET 
											punch_time='".$obj_db->real_escape_string($data['punch_time'])."'
											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($upd_schooltimings);
			 }
			 else
			 {
			   $insr_schooltimings="INSERT INTO  ".TABLE_SCHOOL_TIMINGS." SET 
			                                branch_id='".$obj_db->real_escape_string($data['branch_id'])."', 
											punch_time='".$obj_db->real_escape_string($data['punch_time'])."',
											session='".$obj_db->real_escape_string($data['session'])."',
											is_staff_time='".$obj_db->real_escape_string($data['is_staff_time'])."',
											in_out='".$obj_db->real_escape_string($data['in_out'])."'"; 
			  $res=$obj_db->get_qresult($insr_schooltimings);
			  $id=$obj_db->insert_id();
			 }
		  redirect_page($page_url);
 }
 /*---End schooltimings.php--------*/ 
 
 /*---start school_workings.php--------*/ 
   function get_schoolworkings($id) {
					global $obj_db;
				$user_sel_query="SELECT * FROM ".TABLE_MONTHSCHOOL_WORKDYS." WHERE id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
   
   function insert_schoolworkings($data,$id){
     global $obj_db,$page_url;
	     if($id)
			  {
			     $upd_schoolworks="UPDATE  ".TABLE_MONTHSCHOOL_WORKDYS." SET 
											tot_workdys='".$data['tot_workdys']."'
											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($upd_schoolworks);
			 }
			 else
			 {
			   $insr_schoolworks="INSERT INTO  ".TABLE_MONTHSCHOOL_WORKDYS." SET 
			                                branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
											tot_workdys='".$data['tot_workdys']."',
											month_id='".$obj_db->real_escape_string($data['month_id'])."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."'"; 
			  $res=$obj_db->get_qresult($insr_schoolworks);
			  $id=$obj_db->insert_id();
			 }
		  redirect_page($page_url);
 }
 /*---End school_workings.php--------*/ 
 
 
 /*---start nolates_per_day.php--------*/ 
   function get_latesperday($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_NOLATES_DAYS." WHERE id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
   
   function insert_latesperday($data,$id){
     global $obj_db,$page_url;
	     if($id)
			  {
			     $upd_schoollateperday="UPDATE  ".TABLE_NOLATES_DAYS." SET 
											tot_lates_perday='".$obj_db->real_escape_string($data['tot_lates_perday'])."'
											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($upd_schoollateperday);
			 }
			 else
			 {
			    $insr_schoollateperday="INSERT INTO  ".TABLE_NOLATES_DAYS." SET 
			                                branch_id='".$obj_db->real_escape_string($data['branch_id'])."', 
											tot_lates_perday='".$obj_db->real_escape_string($data['tot_lates_perday'])."'"; 
			  $res=$obj_db->get_qresult($insr_schoollateperday);
			  $id=$obj_db->insert_id();
			 }
		  redirect_page($page_url);
 }
 /*---End nolates_per_day.php--------*/ 
 
  /*---start biometric_deviceid.php--------*/ 
   function get_biometricdevice($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_BIOMETRIC_MECHINE." WHERE id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
   
   function insert_biometricdevice($data,$id){
     global $obj_db,$page_url;
	     if($id)
			  {
			     $upd_schoollateperday="UPDATE  ".TABLE_BIOMETRIC_MECHINE." SET 
											MachineId='".$obj_db->real_escape_string($data['MachineId'])."'
											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($upd_schoollateperday);
			 }
			 else
			 {
			    $insr_schoollateperday="INSERT INTO  ".TABLE_BIOMETRIC_MECHINE." SET 
			                                branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
											is_student_device='".$obj_db->real_escape_string($data['is_student_device'])."', 
											MachineId='".$obj_db->real_escape_string($data['MachineId'])."'"; 
			  $res=$obj_db->get_qresult($insr_schoollateperday);
			  $id=$obj_db->insert_id();
			 }
		  redirect_page($page_url);
 }
 /*---End biometric_deviceid.php--------*/
 }
?>