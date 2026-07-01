<?php class customer_operations{

     	function get_cerficatedts($id) {
					global $obj_db;
					$user_sel_query="select a.*,b.full_name as senderstudent,date_format(str_to_date(date_of_issue,'%Y-%m-%d'),'%d-%m-%Y') as issuedt,b.mobile from ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." a ,".TABLE_USER_DETAILS." b WHERE a.frmuser_id=b.user_id and a.id='".$id."' order by date(enter_date) asc";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}




function certficatework_assign_permission($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}

			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." SET 
						                	approval_status='".$obj_db->real_escape_string($data['approval_status'])."', 
											score='".$obj_db->real_escape_string($data['score'])."', 
											approval_by='".$obj_db->real_escape_string($_SESSION['user_id'])."',
  											approval_date='".date('Y-m-d H:i:s')."'
 											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
				 redirect_page($page_url);
}

function certficatework_assignto_user($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}

			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." SET 
						                	assigned_touser_id='".$obj_db->real_escape_string($data['assigned_touser_id'][0])."', 
											assigned_by='".$obj_db->real_escape_string($_SESSION['user_id'])."',
  											assigned_date='".date('Y-m-d H:i:s')."'
 											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
				 redirect_page($page_url);
}
					
 function upload_centralized_cerficates($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}

    
			if($id)
			  {
			     
			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." SET 
						                	event_title='".$obj_db->real_escape_string($data['event_title'])."', 
											activity_category='".$obj_db->real_escape_string($data['activity_category'])."',
  											issuing_organization='".$obj_db->real_escape_string($data['issuing_organization'])."',
											frmuser_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											date_of_issue='".$obj_db->real_escape_string(date('Y-m-d',strtotime($data['date_of_issue'])))."'
 											WHERE id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
			 }
			 else
			 {
 			   $std_insert_query=INSERT_KEYWORD."   ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." SET 
			                               event_title='".$obj_db->real_escape_string($data['event_title'])."', 
											activity_category='".$obj_db->real_escape_string($data['activity_category'])."',
  											issuing_organization='".$obj_db->real_escape_string($data['issuing_organization'])."',
											frmuser_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											date_of_issue='".$obj_db->real_escape_string(date('Y-m-d',strtotime($data['date_of_issue'])))."'"; 
			  $res=$obj_db->get_qresult($std_insert_query); 
			  $id=$obj_db->insert_id();
 			
			  if($_FILES['upload_file']['tmp_name']!=''){
	$name = $_FILES["upload_file"]["name"];
 	   $extension = end((explode(".", $name)));
 	$target_path = "includes/uploaded_files/".$id.'.'.$extension;
	$admform_img = $id.'.'.$extension;
	$imgname=$id.'.'.$extension;
	move_uploaded_file($_FILES['upload_file']['tmp_name'], $target_path);

	$upd_admisform=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." set document_path='".$admform_img."' where id='".$id."'");
	}

  			///}
			unset($_SESSION['form_token']);
 			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
			}


 }	
			function delete_certificate_details($id) {
			global $obj_db, $page_url;			
 			  $del_student=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." set is_delete='1',delete_by='".$_SESSION['user_id']."',delete_date='".date('Y-m-d H:i:s')."' where id='".$_REQUEST['id']."'");
			   redirect_page($page_url);
		}


		function disapprove_certificate($id) {
			global $obj_db, $page_url;			
 			  $del_student=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." set approval_status='0' where id='".$_REQUEST['id']."'");
			   redirect_page($page_url);
		}

		


		function upload_jpegpngimage($filename,$source,$imagename,$target,$type,$imagepath,$save,$file,$modwidth,$modheight,$extension){		
	
		$imagename=$imagename;
		$source = $source;
		$target = $target;
		  $type = $type;
		if ($type == "image/jpeg" || $type == "image/jpg") {
			move_uploaded_file($source, $target);
			$imagepath = $imagename;
			$save = $save; //Path to save the image
			$file = $file; //path to orginal size image
			list($width, $height) = getimagesize($file);
			$modwidth = $modwidth;
			$diff = $width / $modwidth; // Use $modheight = $idff to mantain aspect ratio
			$modheight = $modheight;
			$tn = imagecreatetruecolor($modwidth, $modheight);
			$image = imagecreatefromjpeg($file);
			imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
			 imagejpeg($tn, $save, 100);
		} elseif ($type == "image/png") { //PNG
			move_uploaded_file($source, $target);
			$imagepath = $imagename;
			$save = $save; //Path to save the image
			$file = $file; //path to orginal size image
			list($width, $height) = getimagesize($file);
			$modwidth = $modwidth;
			$diff = $width / $modwidth; // Use $modheight = $idff to mantain aspect ratio
			$modheight = $modheight;
			$tn = imagecreatetruecolor($modwidth, $modheight);
			imagealphablending($tn, false);
			imagesavealpha($tn, true);
			$image = imagecreatefrompng($file);
			imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
			 imagejpeg($tn, $save, 9);
			 
		} else {
			echo "Error!";
		}
 			 unlink($file);
	   }

	   function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);
         return $ext;
        }
		
		}
		?>