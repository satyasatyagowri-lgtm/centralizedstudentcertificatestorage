<?php class userdetails_pword{
 function user_pwordchange($data,$id){
	 global $obj_db, $page_url;
			//$msg=array();
            
			 $ercount=0;
			 $aumsg='aunamenotexist';
			
			 $msg.=$aumsg.'^';		 
			 $username_query="SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_name='".$data['user_name']."'";
			 $usernum=$obj_db->fetchNum($username_query);
			$username_row=$obj_db->fetchRow($username_query);
			
			$unameexistquery=$obj_db->fetchRow("SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_id='".(int)$_SESSION['user_id']."'");
			
			$umsg='unamenotexist';
			if($usernum && $unameexistquery['user_name']!=$data['user_name']){
			$umsg='unameexist';
			 $ercount=1;
			}
			$msg.=$umsg;
			
			
		    if($ercount==0){
			$obj_db->get_qresult("update ".TABLE_USER_DETAILS." set user_name='".$data['user_name']."',
					email='".$data['email']."',user_password='".$data['user_password']."',sms_mobile='".$data['sms_mobile']."' where  user_id='".$_SESSION['user_id']."'");

             redirect_page($page_url."&success=success");
		
		}else return $msg;
			}
			
			
	
}
?>