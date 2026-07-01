<?php class customer_operations{

     	function get_customer_details($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES."  where id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
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


			
			function delete_customer_details($id) {
			global $obj_db, $page_url;			
 			  $del_student=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_DTS." set is_delete='1',customer_no='',customer_givid=0 where customer_id='".$_REQUEST['id']."'");
			   redirect_page($page_url.'&line_id='.$_REQUEST['line_id']);
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