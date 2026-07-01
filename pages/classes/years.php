<?php 
class insert_update {		
		/*Start year.php*/
			function get_year($id) {
			global $obj_db;
			$user_sel_query="SELECT * FROM ".TABLE_YEAR." WHERE year_id='".(int)$id."'";
			$user_sel_row = $obj_db->fetchRow($user_sel_query);
			$user = $user_sel_row;
			return $user;
		}
		
		function year_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);

			 // echo 'test';exit;
			 	  
		     if($id)
			  {
			     $year_upd_query=UPDATE_KEYWORD."   ".TABLE_YEAR." SET 
						                  year='".$obj_db->real_escape_string($data['year'])."', 
											start_date='".$obj_db->real_escape_string($data['start_date'])."',
											end_date='".$obj_db->real_escape_string($data['end_date'])."',
										    current_year='".$obj_db->real_escape_string($data['current_year'])."'
											WHERE year_id='".$id."'";
				 $res=$obj_db->get_qresult($year_upd_query);
				
			 
			 }
			 else
			 {
			 
			   $year_insert_query=INSERT_KEYWORD."   ".TABLE_YEAR." SET 
			                                year='".$obj_db->real_escape_string($data['year'])."', 
											start_date='".$obj_db->real_escape_string($data['start_date'])."',
											end_date='".$obj_db->real_escape_string($data['end_date'])."',
										    current_year='".$obj_db->real_escape_string($data['current_year'])."'";
											
											
			  $res=$obj_db->get_qresult($year_insert_query);
			  
			  $id=$obj_db->insert_id();
							
			 }
		redirect_page($page_url);
			}
			
			
			function delete_year($id) {

			global $obj_db, $page_url;
			
			    $prdrel_del=DELETE_KEYWORD."  ".TABLE_YEAR." WHERE  year_id='".$id."'";
		        $obj_db->get_qresult($prdrel_del);
			
		
			redirect_page($page_url);
        
		}
		/*-----End Year-------*/
		
		/*-----Start Organisation----*/
		function get_org($id) {

					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_ORG." WHERE org_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
         
  	 	function org_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);

			 // echo 'test';exit;
			 	  
		     if($id)
			  {
			     $org_upd_query=UPDATE_KEYWORD."   ".TABLE_ORG." SET 
						                  org_name='".$obj_db->real_escape_string($data['org_name'])."', 
											org_short_code='".$obj_db->real_escape_string($data['org_short_code'])."',
											start_year='".$obj_db->real_escape_string($data['start_year'])."',
											is_school='".$obj_db->real_escape_string($data['is_school'])."'
											WHERE org_id='".$id."'";
				 $res=$obj_db->get_qresult($org_upd_query);
				
			 
			 }
			 else
			 {
			 
			   $org_insert_query=INSERT_KEYWORD."   ".TABLE_ORG." SET 
			                                org_name='".$obj_db->real_escape_string($data['org_name'])."', 
											org_short_code='".$obj_db->real_escape_string($data['org_short_code'])."',
											is_school='".$obj_db->real_escape_string($data['is_school'])."',
											start_year='".$obj_db->real_escape_string($data['start_year'])."'";
											
											
			  $res=$obj_db->get_qresult($org_insert_query);
			  
			  $id=$obj_db->insert_id();
							
			 }
		redirect_page($page_url);
			}
			
			
			function delete_org($id) {

			global $obj_db, $page_url;
			
			    $org_del=DELETE_KEYWORD."  ".TABLE_ORG." WHERE  org_id='".$id."'";
		        $obj_db->get_qresult($org_del);
			
		
			redirect_page($page_url);
        
		}
		/*----End Organisation------*/
		
		
		/*---Start Branch-------*/
		function get_branch($id) {
					
					global $obj_db;
					
					$user_sel_query="SELECT * FROM ".TABLE_BRANCH." WHERE branch_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
 
 function branch_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);

			 // echo 'test';exit;
			 	  
		     if($id)
			  {
			     $branch_upd_query=UPDATE_KEYWORD."   ".TABLE_BRANCH." SET 
						                    branch_name='".$obj_db->real_escape_string($data['branch_name'])."', 
										    branch_short_name='".$obj_db->real_escape_string($data['branch_short_name'])."',
											branch_incharge='".$obj_db->real_escape_string($data['branch_incharge'])."',
											branch_city='".$obj_db->real_escape_string($data['branch_city'])."',
											city_short_name='".$obj_db->real_escape_string($data['city_short_name'])."', 
										    branch_adress='".$obj_db->real_escape_string($data['branch_adress'])."',
											branch_mobile='".$obj_db->real_escape_string($data['branch_mobile'])."',
											org_id='".$obj_db->real_escape_string($data['org_id'])."',
											branch_start_date='".$obj_db->real_escape_string($data['branch_start_date'])."',
											is_hostel='".$obj_db->real_escape_string($data['is_hostel'])."',
											is_transport='".$obj_db->real_escape_string($data['is_transport'])."'
											WHERE branch_id='".$id."'";
				 $res=$obj_db->get_qresult($branch_upd_query);
				
			 
			 }
			 else
			 {
			 
			    $branch_insert_query=INSERT_KEYWORD."   ".TABLE_BRANCH." SET 
			                                branch_name='".$obj_db->real_escape_string($data['branch_name'])."', 
										    branch_short_name='".$obj_db->real_escape_string($data['branch_short_name'])."',
											branch_incharge='".$obj_db->real_escape_string($data['branch_incharge'])."',
											branch_city='".$obj_db->real_escape_string($data['branch_city'])."',
											city_short_name='".$obj_db->real_escape_string($data['city_short_name'])."',
										    branch_adress='".$obj_db->real_escape_string($data['branch_adress'])."',
											branch_mobile='".$obj_db->real_escape_string($data['branch_mobile'])."',
											org_id='".$obj_db->real_escape_string($data['org_id'])."',
											is_hostel='".$obj_db->real_escape_string($data['is_hostel'])."',
											is_transport='".$obj_db->real_escape_string($data['is_transport'])."',
											branch_start_date='".$obj_db->real_escape_string($data['branch_start_date'])."'"; 
											
			  $res=$obj_db->get_qresult($branch_insert_query);
			  $id=$obj_db->insert_id();
			 }
 			  if($_FILES['branch_logo']['tmp_name']!=''){//echo SITE_URL.'../includes/stu_img/'.$id.'.jpg';exit;
				
				$image =$_FILES["branch_logo"]["name"];
				$uploadedfile = $_FILES['branch_logo']['tmp_name'];
				
				
				if ($image) 
				{
				$filename = stripslashes($_FILES['branch_logo']['name']);
				$extension = $this->getExtension($filename);
				$extension = strtolower($extension);
				$size=filesize($_FILES['branch_logo']['tmp_name']);
				
				$uploadedfile = $_FILES['branch_logo']['tmp_name'];
				$src = imagecreatefromjpeg($uploadedfile);
								
				list($width,$height)=getimagesize($uploadedfile);
				$newwidth=$width;
				$newheight=$height;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				$newwidth1=140;
				$newheight1=140;
				$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
				
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
				
				$imagetyp = explode("/", $_FILES['branch_logo']['type']);
				
				  $filename1 = "includes/school_imgs/recep_logo".$id.".png";
				
				 imagejpeg($tmp1,$filename1,100);
				
				imagedestroy($src);
				imagedestroy($tmp);
				imagedestroy($tmp1);
				
				}
}
			 
		redirect_page($page_url);
			}
			
			
			function delete_branch($id) {

			global $obj_db, $page_url;
			
			  $branch_del=DELETE_KEYWORD."  ".TABLE_BRANCH." WHERE  branch_id='".$id."'";
		        $del_brnch=$obj_db->get_qresult($branch_del);
			
		
			redirect_page($page_url);
        
		}


		function getExtension($str) {
			$i = strrpos($str,".");
			if (!$i) { return ""; }
			$l = strlen($str) - $i;
		   $ext = substr($str,$i+1,$l);
			return $ext;
		   }
		/*----End Branch--------*/
		
	/*----Start Expenditure Category New Addings---*/
		 function get_expcatg_new($id) {

					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_EXPENDITURE_CATEGORY." WHERE exp_catg_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
         
  	 	function exp_catg_save_new($data,$id) {
			global $obj_db, $page_url;
  
		     if($id)
			  {
			     $uprexp_catg=UPDATE_KEYWORD."  ".TABLE_EXPENDITURE_CATEGORY." SET 
 											exp_category='".$obj_db->real_escape_string($data['exp_category'])."',
											exp_catg_shortname='".$obj_db->real_escape_string($data['exp_catg_shortname'])."',
											is_bank_person='".$obj_db->real_escape_string($data['is_bank_person'])."'
 											WHERE exp_catg_id='".$id."'";
				 $res=$obj_db->get_qresult($uprexp_catg);
							 }
			 else
			 {
			 
			   $insr_expcatg=INSERT_KEYWORD."   ".TABLE_EXPENDITURE_CATEGORY." SET 
			                                exp_category='".$obj_db->real_escape_string($data['exp_category'])."',
											exp_catg_shortname='".$obj_db->real_escape_string($data['exp_catg_shortname'])."',
											is_bank_person='".$obj_db->real_escape_string($data['is_bank_person'])."'";  
			  $res=$obj_db->get_qresult($insr_expcatg);
			  
			  $id=$obj_db->insert_id();
							
			 }
		redirect_page($page_url);
			}
		/*----End Expenditure New Addings---*/
   
   		/*----Start Expenditure New Addings---*/
		 function get_exp_add_new($id) {

					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_EXPENDITURE_TYPE." WHERE exp_type_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
         
  	 	function exp_add_save_new($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);
		     if($id)
			  {
			     $org_upd_query=UPDATE_KEYWORD."  ".TABLE_EXPENDITURE_TYPE." SET 
 											exp_name='".$obj_db->real_escape_string($data['exp_name'])."',
											opening_bal='".$obj_db->real_escape_string($data['opening_bal'])."',
											exp_catg_id='".$obj_db->real_escape_string($data['exp_catg_id'])."',
											is_bank_person='".$obj_db->real_escape_string($data['is_bank_person'])."'
 											WHERE exp_type_id='".$id."'";
				 $res=$obj_db->get_qresult($org_upd_query);
							 }
			 else
			 {
			 
			   $org_insert_query=INSERT_KEYWORD."   ".TABLE_EXPENDITURE_TYPE." SET 
			                                exp_name='".$obj_db->real_escape_string($data['exp_name'])."',
											opening_bal='".$obj_db->real_escape_string($data['opening_bal'])."',
											exp_catg_id='".$obj_db->real_escape_string($data['exp_catg_id'])."',
											is_bank_person='".$obj_db->real_escape_string($data['is_bank_person'])."'";  
											
											
			  $res=$obj_db->get_qresult($org_insert_query);
			  
			  $id=$obj_db->insert_id();
							
			 }
		redirect_page($page_url);
			}
		/*----End Expenditure New Addings---*/
	}
	
	?>