<?php 
class insert_update {		
		/*Start year.php*/
			function get_line_details($id) {
			global $obj_db;
			$user_sel_query="SELECT * FROM ".TABLE_LINE_NAMES." WHERE line_id='".(int)$id."'";
			$user_sel_row = $obj_db->fetchRow($user_sel_query);
			$user = $user_sel_row;
			return $user;
		}
		
		function lnie_detailssave($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);	
				if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}		 	  
		     if($id)
			  {
			     $lineupd=UPDATE_KEYWORD."   ".TABLE_LINE_NAMES." SET 
						                  line_name='".$obj_db->real_escape_string($data['line_name'])."', 
											short_name='".$obj_db->real_escape_string($data['short_name'])."',
											city='".$obj_db->real_escape_string($data['city'])."',
											line_code='".$obj_db->real_escape_string($data['line_code'])."'
											WHERE line_id='".$id."'";
				 $res=$obj_db->get_qresult($lineupd);
				
			 
			 }
			 else
			 {
			 
			   $linedts=INSERT_KEYWORD."   ".TABLE_LINE_NAMES." SET 
			                                line_name='".$obj_db->real_escape_string($data['line_name'])."', 
											short_name='".$obj_db->real_escape_string($data['short_name'])."',
											city='".$obj_db->real_escape_string($data['city'])."',
											line_code='".$obj_db->real_escape_string($data['line_code'])."'";										
			  $res=$obj_db->get_qresult($linedts);			  
			  $id=$obj_db->insert_id();			
			  
			   if($data['user_id']>0)
			 {
				$usrdts=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where user_id='".$data['user_id']."'");
				$getlinedtsar=explode(',',$usrdts[0]['assign_line_ids']);
				if(!in_array($data['line_id'],$getlinedtsar))
					$pushlineintoarr=array_push($getlinedtsar,$data['line_id']);
				$updusedts=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_USER_DETAILS." set assign_line_ids='".implode(',',$pushlineintoarr)."' where user_id='".$data['user_id']."'");
			 }
			 }

			unset($_SESSION['form_token']);
		redirect_page($page_url);
			}
			
			
			function delete_lnie_details($id) {
			global $obj_db, $page_url;			
			    $prdrel_del=UPDATE_KEYWORD."  ".TABLE_LINE_NAMES." set is_delete=1 WHERE  line_id='".$id."'";
		        $obj_db->get_qresult($prdrel_del);
				redirect_page($page_url);
        
		}
		/*-----End Year-------*/



		/*---------------line_citys.php-----------------*/
		function get_linecity($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_LINE_CITYS."  where city_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}

 function linecity_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
				if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}
			if($id)
			  {
			     $line_nameupd="UPDATE  ".TABLE_LINE_CITYS." SET 
						                	line_id='".$obj_db->real_escape_string($data['line_id'])."',
											weekd_id='".$obj_db->real_escape_string($data['weekd_id'])."',
											city_name='".$obj_db->real_escape_string($data['city_name'])."',
											short_name='".$obj_db->real_escape_string($data['short_name'])."'
											WHERE city_id='".$id."'";
				 $res=$obj_db->get_qresult($line_nameupd);
		     }
			 else
			 {
			   $std_insert_query="INSERT INTO  ".TABLE_LINE_CITYS." SET 
			                               line_id='".$obj_db->real_escape_string($data['line_id'])."',
										   weekd_id='".$obj_db->real_escape_string($data['weekd_id'])."',
										   city_name='".$obj_db->real_escape_string($data['city_name'])."',
										   short_name='".$obj_db->real_escape_string($data['short_name'])."'"; 
			  $res=$obj_db->get_qresult($std_insert_query); 
			  $id=$obj_db->insert_id();			 
			 } 
			 unset($_SESSION['form_token']);
			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
			}
			
			function delete_linecity($id) {
			global $obj_db, $page_url;
			  $del_student=$obj_db->get_qresult("update ".TABLE_LINE_CITYS." set is_delete='1' where city_id='".$_REQUEST['id']."'");
			   redirect_page($page_url);
		}
		/*---------------line_citys.php-----------------*/
			}
	
	?>