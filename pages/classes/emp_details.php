<?php class employee_operations{

     	function get_employee_details($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_EMPUSER_DETAILS."  where emp_user_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}

 function employee_details_savenew($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			
			if($id)
			  {
			     
			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_EMPUSER_DETAILS." SET 
						                	emp_name='".$obj_db->real_escape_string($data['emp_name'])."', 
											empmobile_no='".$obj_db->real_escape_string($data['empmobile_no'])."',
											father_name='".$obj_db->real_escape_string($data['father_name'])."', 
											empaltermobile_no='".$data['empaltermobile_no']."',
											address='".$obj_db->real_escape_string($data['address'])."',
											city='".$obj_db->real_escape_string($_SESSION['city'])."',
											aadhar_no='".$obj_db->real_escape_string($data['aadhar_no'])."',
											join_date='".$obj_db->real_escape_string($data['join_date'])."'
											WHERE emp_user_id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
			 }
			 else
			 {
			   $std_insert_query=INSERT_KEYWORD."   ".TABLE_EMPUSER_DETAILS." SET 
			                               emp_name='".$obj_db->real_escape_string($data['emp_name'])."', 
										   father_name='".$obj_db->real_escape_string($data['father_name'])."', 
 											empmobile_no='".$obj_db->real_escape_string($data['empmobile_no'])."',
											empaltermobile_no='".$data['empaltermobile_no']."',
											address='".$obj_db->real_escape_string($data['address'])."',
											city='".$obj_db->real_escape_string($_SESSION['city'])."',
											aadhar_no='".$obj_db->real_escape_string($data['aadhar_no'])."',
											join_date='".$obj_db->real_escape_string($data['join_date'])."'"; 
			  $res=$obj_db->get_qresult($std_insert_query); 
			  $id=$obj_db->insert_id();
			  
			  
					  }
 			///}
			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
			}


			
			function delete_employee_details($id) {
			global $obj_db, $page_url;			
			  $del_student=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_EMPUSER_DETAILS." set is_delete='1' where emp_user_id='".$_REQUEST['id']."'");
			   redirect_page($page_url);
		}


	/*-----------------------------------------------------*/
	 function employee_attendancedetails_savenew($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$attedancedt=date('Y-m-d',strtotime($data['attendancedt']));

			if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}
			if($id)
			  {
			     
			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_EMPUSER_ATTENDANCELIST." SET 
						                	emp_user_id='".$obj_db->real_escape_string($data['emp_user_id'])."',
										   line_id='".$obj_db->real_escape_string($data['line_id'])."', 
										   attendance_date='".$obj_db->real_escape_string($attedancedt)."',
											update_date='".$obj_db->real_escape_string($data['join_date'])."'
											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
			 }
			 else
			 {
				foreach($data['empuserid'] as $empusrky=>$empusrv){
			   $empusrattendance=INSERT_KEYWORD."   ".TABLE_EMPUSER_ATTENDANCELIST." SET 
			                               emp_user_id='".$obj_db->real_escape_string($empusrv)."',
										   line_id='".$obj_db->real_escape_string($data['line_id'])."', 
										   attendance_date='".$obj_db->real_escape_string($attedancedt)."', 
 											enter_date='".date('Y-m-d H:i:s')."',
 											enter_by='".$obj_db->real_escape_string($_SESSION['user_id'])."'"; 
			  $res=$obj_db->get_qresult($empusrattendance); 
			  $id=$obj_db->insert_id();
				}
			  
			  
					  }
 			///}
			unset($_SESSION['form_token']);
			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
		  
			}


			
			function delete_employee_attendancedetails($id) {
			global $obj_db, $page_url;			
			  $del_student=$obj_db->get_qresult(DELETE_KEYWORD."  ".TABLE_EMPUSER_ATTENDANCELIST."  where id='".$_REQUEST['id']."'");
			   redirect_page($page_url.'&frm_dt='.$_REQUEST['frm_dt'].'&to_dt='.$_REQUEST['to_dt']);
		}

    /*------------------------------------------------------------------*/
		}
		?>