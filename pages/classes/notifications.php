<?php 
  class homework_operations{
  
    		
	function homework_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);
			 // echo 'test';exit;
			 $school_query=$obj_db->fetchRow("SELECT branch_name FROM ".TABLE_BRANCH." where branch_id='".$data['branch_id']."'");
			 
		     for($i=0;$i<count($data['sec_id']);$i++){
			   $savehomework=INSERT_KEYWORD."   ".TABLE_STD_NOTIFICATION." SET 
			   								sec_id='".$obj_db->real_escape_string($data['sec_id'][$i])."',
											branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
											notification_title='".$obj_db->real_escape_string($data['notification_title'])."',
											discription='".$obj_db->real_escape_string($data['discription'])."',
											date_time='".date('Y-m-d H:i:s')."',
											user_id='".$_SESSION['user_id']."',
											enter_time='".date('H:i:s')."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."'"; 
			  $res=$obj_db->get_qresult($savehomework);
			  
			  $id=$obj_db->insert_id();
			 // $this->send_sms($data['branch_id'],$data['course_id'][$i],$school_query['branch_name'],$data['discription']);
			 }
		  redirect_page($page_url);
			}
			
			
			function send_sms($bran_id,$course_id,$school_name,$desc){
	global $obj_db, $page_url;
	
	 $stdquery="select mobile_no,first_name,last_name from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b where course_id='".$course_id."'  and is_delete=0 and y_id='".$_SESSION['year_id']."' and b.branch_id='".$bran_id."' and a.student_id=b.student_id and mobile_no!='' group by mobile_no ";
		  $stdqueryres=$obj_db->get_qresult($stdquery);
		  $mob='';
		  while($stdquerrow=$obj_db->fetchArray($stdqueryres)){
		  $mob.=trim($stdquerrow['mobile_no']).",";
		  }
		 $trmmob=substr($mob,0,-1);
		$msg=$desc." -".$school_name;
		//$url = SMS_URL2;
		if($_REQUEST['lang']=='te'){
$fields = array('userid'=>urlencode(SMS_USERNAME),
                'pass'=>urlencode(SMS_PWORD),
                'unicode'=>urlencode(1),
				'phone'=>urlencode($trmmob),
                'msg'=>urlencode($msg));
 }else{
 $fields = array('userid'=>urlencode(SMS_USERNAME),
                'pass'=>urlencode(SMS_PWORD),
				'phone'=>urlencode($trmmob),
                'msg'=>urlencode($msg));

 }
  //url-ify the data for the POST 
			foreach($fields as $key=>$value)
			{ 
			$fields_string.= $key.'='.$value.'&';   
			}  
			rtrim($fields_string,'&');
			$ch = curl_init(); 
			//set the url, number of POST vars, POST data 
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,count($fields)); 
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string); 
			//execute post  
			$result = curl_exec($ch);
			//close connection 
			curl_close($ch);
	}
	
			function delete_homework($id) {
			global $obj_db, $page_url;
			
			    $prdrel_del="DELETE FROM ".TABLE_STD_NOTIFICATION." WHERE  id='".$id."'";
		        $obj_db->get_qresult($prdrel_del);
			   redirect_page($page_url);
		}
		}
		?>