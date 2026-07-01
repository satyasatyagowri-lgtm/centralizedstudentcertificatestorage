<?php 
class userdetails_operations{
/*----Start user_details.php-----*/
    function get_user_details($id) {
					global $obj_db;
					  $userper_query="SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($userper_query);
					$user = $user_sel_row;
					return $user;
					}

 function user_details_savenew($data,$id){
	 global $obj_db, $page_url;
			//$msg=array();
				if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}
			$ercount=0;
			/* $aumsg='aunamenotexist';
			 $admin_uname=$obj_db->fetchRow("select user_name from ".TABLE_ADMINISTRATOR." where admin_id=1");
			 if($admin_uname['user_name']==$data['user_name']){
			  $aumsg='aunameexist';
			 $ercount=1;
			 }	
			 $msg.=$aumsg.'^';	*/	 
			 $username_query="SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_name='".$data['user_name']."'";
			 $usernum=$obj_db->fetchNum($username_query);
			$username_row=$obj_db->fetchRow($username_query);
			
			$unameexistquery=$obj_db->fetchRow("SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_id='".(int)$id."'");
			
			$umsg='unamenotexist';
			if($usernum && strtolower($unameexistquery['user_name'])!=strtolower($data['user_name'])){
			$umsg='unameexist';
			 $ercount=1;
			}
 			$msg=$umsg;
             $datapermisvarbles="";$datapermissts=0;	$isdatapermis="";		
			if($data['mobile_permission']==1){
			  $datapermisvarbles.="mobile_no,mobile_no2,";
			  $datapermissts++;
			  }
			if($data['studentaddress_permission']==1){
			  $datapermisvarbles.="street,city,";
			  $datapermissts++;
			  }
			if($data['prevschool_detail_permission']==1){
			  $datapermisvarbles.="previous_school,previous_city,pin_no,aadhar_no,";
			  $datapermissts++;
			  }
			  
			if($datapermissts>0){
			$isdatapermis=1;
			$datapermisvarbles=substr($datapermisvarbles,0,-1);
			}
			
			
			$permsilevels=json_encode(array('data_permisdts'=>array('is_havedata_permission'=>$isdatapermis,'datapermis_validdte'=>$data['datapermis_validdte'],'mobile_permission'=>$data['mobile_permission'],'studentaddress_permission'=>$data['studentaddress_permission'],'prevschool_detail_permission'=>$data['prevschool_detail_permission']),'datapermisvarbles'=>$datapermisvarbles,'is_feepaydate_permission'=>$data['is_feepaydate_permission'],'paydate_permis_validdte'=>$data['paydate_permis_validdte'],'is_concession_permission'=>$data['is_concession_permission'],'concession_permis_validdte'=>$data['concession_permis_validdte'],'is_changeborrowdate_permission'=>$data['is_changeborrowdate_permission'],'changeborrowdate_permis_validdte'=>$data['changeborrowdate_permis_validdte'],'is_expensehave_directpermission'=>$data['is_expensehave_directpermission'],'expenpermission_permis_validdte'=>$data['expenpermission_permis_validdte']));
 			
			
		  	//echo '<pre>';print_r($_POST);echo $subid_count;echo '<pre>';
			$get_usertype=$obj_db->fetchRow("SELECT type_name FROM ".TABLE_USER_TYPES." WHERE user_type_id='".$obj_db->real_escape_string($data['user_type_id'])."'");
			$encpword=($data['user_password']);
			$encsmsmobile=($data['sms_mobile']);

			$expasignlineids=explode(',',implode(',',$data['line_ids']));
		    if($ercount==0){
       	 if($id)
			  {
			  
			      $usr_per_upd=UPDATE_KEYWORD."  ".TABLE_USER_DETAILS."  SET
				                        user_name='".$obj_db->real_escape_string($data['user_name'])."',
										full_name='".$obj_db->real_escape_string($data['full_name'])."',
										aadhar_no='".$obj_db->real_escape_string($data['aadhar_no'])."',
										gender='".$obj_db->real_escape_string($data['gender'])."',
										assign_line_ids='".implode(',',$data['line_ids'])."',
										empuser_ids='".implode(',',$data['empuser_ids'])."',
										address='".$obj_db->real_escape_string($data['address'])."',
										mobile='".$obj_db->real_escape_string($data['mobile'])."',
										data_permission_enddate='".$data['data_permission_enddate']."',
										sms_mobile='".$obj_db->real_escape_string($encsmsmobile)."',
										email='".$obj_db->real_escape_string(strtolower($data['email']))."',
										is_cashier='".$obj_db->real_escape_string($data['is_cashier'])."',
										user_type_id='".$obj_db->real_escape_string($data['user_type_id'])."',
										branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
									    user_password='".$obj_db->real_escape_string($encpword)."',
										org_id='".$obj_db->real_escape_string($data['org_id'])."',
										permission_levels='".$permsilevels."',
										update_by='".$_SESSION['user_id']."',
										update_date='".date('d-m-Y H:i:s')."',
										assign_branch_ids='$assig_brids',
										biometric_campus_ids='$assig_cmpids',
										assign_course_ids='$assig_corids',
										user_type='".$get_usertype['type_name']."',
										assign_org_ids='$assig_orgids',
										user_status='".$data['user_status']."' WHERE user_id='".$id."'";  
				  $res=$obj_db->get_qresult($usr_per_upd);
			}
			
			 else
			 {
			
			    $usr_dts=INSERT_KEYWORD."   ".TABLE_USER_DETAILS." SET 
										user_name='".$obj_db->real_escape_string($data['user_name'])."',
										full_name='".$obj_db->real_escape_string($data['full_name'])."',
										aadhar_no='".$obj_db->real_escape_string($data['aadhar_no'])."',
										gender='".$obj_db->real_escape_string($data['gender'])."',
										assign_line_ids='".implode(',',$data['line_ids'])."',
										empuser_ids='".implode(',',$data['empuser_ids'])."',
										address='".$obj_db->real_escape_string($data['address'])."',
										mobile='".$obj_db->real_escape_string($data['mobile'])."',
										sms_mobile='".$obj_db->real_escape_string($encsmsmobile)."',
										data_permission_enddate='".$data['data_permission_enddate']."',
										is_cashier='".$obj_db->real_escape_string($data['is_cashier'])."',
										email='".$obj_db->real_escape_string(strtolower($data['email']))."',
										user_type_id='".$obj_db->real_escape_string($data['user_type_id'])."',
										branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
										org_id='".$obj_db->real_escape_string($data['org_id'])."',
										assign_branch_ids='$assig_brids',
										biometric_campus_ids='$assig_cmpids',
										insert_by='".$_SESSION['user_id']."',
										insert_date='".date('d-m-Y H:i:s')."',
										assign_org_ids='$assig_orgids',
										assign_course_ids='$assig_corids',
										user_type='".$get_usertype['type_name']."',
										permission_levels='".$permsilevels."',
										user_password='".$obj_db->real_escape_string($encpword)."',
										user_status='".$data['user_status']."'";
				   $ress=$obj_db->get_qresult($usr_dts);
				   $id=$obj_db->insert_id();

				 
				   $getusryptp=$obj_db->qry("select * from ".TABLE_USER_TYPES." where user_type_id='".$data['user_type_id']."'");
						
						$usrtypmenujsondts=json_decode($getusryptp[0]['default_assign_rollids'],true);
    				   	$get_components=$obj_db->qry("select * from ".TABLE_COMPONENTS."  order by mainview_order asc,menu_view asc ,component_id asc");
 				 $defalutmenupageidky=array_search($usrtypmenujsondts['defaultmenuid'],array_column($get_components,'component_id'));
					$row=array();
                       $usrdefaultpg=$get_components[$defalutmenupageidky]['page_name'];
                  
 			$isdefaultchk=1;
				  $isdefalutchkdts = array();
	
                    $accountantmenudts = $get_components;

                 

				/*   $isaccountantmens=1;
				  $accountantmenudts = array_filter($get_components,function($v,$k) use ($isaccountantmens){
					 return $usertypdmenus[$data['user_type_id']] == $isaccountantmens;
				   },ARRAY_FILTER_USE_BOTH);*/
 
			 // echo 'test';exit;
			 $main_menuview=array();$compids='';
			 foreach($_REQUEST['component_id'] as $k=>$v)
			   $compids.=$v.',';
			  $trm_lstcoma=substr($compids,0,-1);
			  $exp_compids=$usrtypmenujsondts['usermenuids'];
 			 //  $exp_compids=array_column($accountantmenudts,'component_id');
 			  foreach($isdefalutchkdts as $k=>$v){
				if(!in_array($v['component_id'],$exp_compids))
				 array_push($exp_compids,$v['component_id']);
			  }
             $scndsubarr=array();	
			 
			 $pages_view=array();
			 
			 $pagview=array();
 		    $arr1=array();$arr1_cmpids=array();$scndsub_cmpids=array();
		   $first_subchild=array();$scnd_sub=array();$scnd_subclild=array();
		    foreach($exp_compids as $k=>$v){ 
			$compnam_arrkey=array_search($v, array_column($get_components,'component_id'));
			$parentcmp_arrkey=array_search($get_components[$compnam_arrkey]['parent_component'], array_column($get_components,'component_id'));
			 if($get_components[$compnam_arrkey]['menu_view']==3)
              {
			    if($get_components[$parentcmp_arrkey]['menu_view']==1){ 
					if($get_components[$compnam_arrkey]['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
 				 $first_subchild[$get_components[$compnam_arrkey]['parent_component']][]=array('firstmenu_id'=>$get_components[$compnam_arrkey]['component_id'],'firstmenu_name'=>$get_components[$compnam_arrkey]['component_name'],'controller_name'=>$get_components[$compnam_arrkey]['controller_name'],'page_name'=>$get_components[$compnam_arrkey]['page_name'],'first_subveiw_order'=>$get_components[$compnam_arrkey]['first_subveiw_order'],'menu_view'=>$get_components[$compnam_arrkey]['menu_view'],'is_excel'=>$get_components[$compnam_arrkey]['is_download_excel'],'is_menu'=>$get_components[$compnam_arrkey]['is_menu'],'is_default_check'=>$get_components[$compnam_arrkey]['is_default_check'],'ischecked'=>$ischecked,'is_havescnd_sub'=>0);
				  $pagview[$get_components[$compnam_arrkey]['component_id']]=array('p'=>$get_components[$compnam_arrkey]['controller_name'],'page'=>$get_components[$compnam_arrkey]['page_name'],'is_excel'=>$get_components[$compnam_arrkey]['is_download_excel'],'component_id'=>$get_components[$compnam_arrkey]['component_id'],'mparent_id'=>$get_components[$parentcmp_arrkey]['component_id'],'sparent_id'=>0);
				 if(!in_array($get_components[$compnam_arrkey]['parent_component'],$arr1_cmpids))
				 {
				   $arr1_cmpids[]=$get_components[$compnam_arrkey]['parent_component'];
				   $arr1[$get_components[$compnam_arrkey]['parent_component']]=array('menu_name'=>$get_components[$parentcmp_arrkey]['component_name'],'menu_id'=>$get_components[$parentcmp_arrkey]['component_id'],'logo_name'=>$get_components[$parentcmp_arrkey]['logo_name'],'mainview_order'=>$get_components[$parentcmp_arrkey]['mainview_order']);
				 }
				 }
				 
				 
             elseif($get_components[$parentcmp_arrkey]['menu_view']==2)
              {
			  $mparentcmp_arrkey=array_search($get_components[$parentcmp_arrkey]['parent_component'], array_column($get_components,'component_id'));
 			     if($get_components[$compnam_arrkey]['menu_view']==3){
					if($get_components[$compnam_arrkey]['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
				 $scnd_subclild[$get_components[$parentcmp_arrkey]['component_id']][]=array('firstmenu_id'=>$get_components[$compnam_arrkey]['component_id'],'firstmenu_name'=>$get_components[$compnam_arrkey]['component_name'],'controller_name'=>$get_components[$compnam_arrkey]['controller_name'],'page_name'=>$get_components[$compnam_arrkey]['page_name'],'second_subveiw_order'=>$get_components[$compnam_arrkey]['second_subveiw_order'],'is_excel'=>$get_components[$compnam_arrkey]['is_download_excel'],'is_menu'=>$get_components[$compnam_arrkey]['is_menu'],'is_default_check'=>$get_components[$compnam_arrkey]['is_default_check'],'ischecked'=>$ischecked);
				$pagview[$get_components[$compnam_arrkey]['component_id']]=array('p'=>$get_components[$compnam_arrkey]['controller_name'],'page'=>$get_components[$compnam_arrkey]['page_name'],'is_excel'=>$get_components[$compnam_arrkey]['is_download_excel'],'component_id'=>$get_components[$compnam_arrkey]['component_id'],'mparent_id'=>$get_components[$mparentcmp_arrkey]['component_id'],'sparent_id'=>$get_components[$parentcmp_arrkey]['component_id']); 
				}
				 
				 if(!in_array($get_components[$mparentcmp_arrkey]['component_id'],$arr1_cmpids))
				 {
				   $arr1_cmpids[]=$get_components[$mparentcmp_arrkey]['component_id'];
				   $arr1[$get_components[$mparentcmp_arrkey]['component_id']]=array('menu_name'=>$get_components[$mparentcmp_arrkey]['component_name'],'menu_id'=>$get_components[$mparentcmp_arrkey]['component_id'],'logo_name'=>$get_components[$mparentcmp_arrkey]['logo_name'],'mainview_order'=>$get_components[$mparentcmp_arrkey]['mainview_order']);
				 }
				 
				if(!in_array($get_components[$parentcmp_arrkey]['component_id'],$scndsub_cmpids))
				 {
				   $scndsub_cmpids[]=$get_components[$parentcmp_arrkey]['component_id'];
				   $first_subchild[$get_components[$parentcmp_arrkey]['parent_component']][]=array('firstmenu_id'=>$get_components[$parentcmp_arrkey]['component_id'],'firstmenu_name'=>$get_components[$parentcmp_arrkey]['component_name'],'controller_name'=>'','page_name'=>'','first_subveiw_order'=>$get_components[$parentcmp_arrkey]['first_subveiw_order'],'menu_view'=>$get_components[$parentcmp_arrkey]['menu_view'],'is_havescnd_sub'=>1);
				 }
			  } 
 			  }
			  
   			}
 			array_multisort( array_column($arr1, "mainview_order"), SORT_ASC, $arr1 );
 			
 			foreach($arr1 as $k1=>$v1){
			 $first_subarr=array();
			 array_multisort( array_column($first_subchild[$v1['menu_id']], "menu_view"), SORT_DESC, $first_subchild[$v1['menu_id']] );
 			$cntfristsubardefalutchkcnt=0;
			 foreach($first_subchild[$v1['menu_id']] as $k2=>$v2){
              if($v2['is_default_check']==0)
				$cntfristsubardefalutchkcnt++;
			 $scndsubchldarr=array();
			 if($v2['is_havescnd_sub']==0)
			 {
				if($v2['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
			  $first_subarr[]=array('firstmenu_id'=>$v2['firstmenu_id'],'firstmenu_name'=>$v2['firstmenu_name'],'controller_name'=>$v2['controller_name'],'page_name'=>$v2['page_name'],'first_subveiw_order'=>$v2['first_subveiw_order'],'is_excel'=>$v2['is_excel'],'is_menu'=>$v2['is_menu'],'is_default_check'=>$v2['is_default_check'],'ischecked'=>$ischecked,'is_havescnd_sub'=>0);
			  
  			}  else{
			 $cntscntsubardefalutchkcnt=0;
			 foreach($scnd_subclild[$v2['firstmenu_id']] as $k3=>$v3){
				if($v3['is_default_check']==0)
				$cntscntsubardefalutchkcnt++;
			if($v3['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
			  $scndsubchldarr[]=array('scndmenu_id'=>$v3['firstmenu_id'],'firstmenu_name'=>$v3['firstmenu_name'],'controller_name'=>$v3['controller_name'],'page_name'=>$v3['page_name'],'second_subveiw_order'=>$v3['second_subveiw_order'],'is_excel'=>$v3['is_excel'],'is_menu'=>$v3['is_menu'],'is_default_check'=>$v3['is_default_check'],'ischecked'=>$ischecked,'is_havescnd_sub'=>0);
  			}
			  array_multisort( array_column($scndsubchldarr, "second_subveiw_order"), SORT_ASC, $scndsubchldarr );
			  $isscndmenuvisable=0;
			 if($cntscntsubardefalutchkcnt>0)
				$isscndmenuvisable=1;
			  $first_subarr[]=array('firstmenu_id'=>$v2['firstmenu_id'],'firstmenu_name'=>$v2['firstmenu_name'],'controller_name'=>'','page_name'=>'','first_subveiw_order'=>$v2['first_subveiw_order'],'is_havescnd_sub'=>$v2['is_havescnd_sub'],'scndsub'=>$scndsubchldarr,'ismenuvisable'=>$isscndmenuvisable);
			  }
			 }
			 array_multisort( array_column($first_subarr, "first_subveiw_order"), SORT_ASC, $first_subarr );
			$isfrstmenuvisable=0;
			 if($cntfristsubardefalutchkcnt>0)
				$isfrstmenuvisable=1;
			  
			 $main_menuview[]=array('menu_name'=>$v1['menu_name'],'menu_id'=>$v1['menu_id'],'logo_name'=>$v1['logo_name'],'firstsub'=>$first_subarr,'ismenuvisable'=>$isfrstmenuvisable);
			}
			
			$pages_view=array('pages'=>$pagview,'defalut_page'=>$usrdefaultpg);
			  $pgsv=json_encode($pages_view);
			  $menuar=json_encode(array('main_menu'=>$main_menuview));			
			
			     $updusr_roll=UPDATE_KEYWORD."   ".TABLE_USER_DETAILS." SET 
						                  user_pages='".$obj_db->real_escape_string($pgsv)."',
										  user_default_page='".$obj_db->real_escape_string($usrdefaultpg)."',
										  user_menus='".$obj_db->real_escape_string($menuar)."'
 											WHERE user_id='".$id."'";
				 $res=$obj_db->get_qresult($updusr_roll);
						
			 }

			 $msge='<table style="width:750px; " border="0" align="center" cellpadding="0" cellspacing="0";> 
      <tbody><tr> 
     <td> 
     <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
       <tbody><tr> 
         <td colspan="2"> 
          <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 16px; color: #585858;line-height: 30px; padding:20px 30px 20px 40px;"> 
            <tbody>
			<tr><td style="height:30px;"><h1>'.SITE_NAME_TITLE.'</h1></td></tr>
			<tr><td style="height:30px;">&nbsp;</td></tr>
			<tr><td style="height:30px; width:600px;">Dear '.$data['user_name'].'</td></tr>
			<tr><td><div style="width:137px; float:left;">User Name:</div><div style="padding-left: 10px; width:100px; float:left;">'.$data['user_name'].'</div></td></tr>
           <tr><td><div style="width:137px; float:left;">Password:</div><div style="padding-left: 10px; width:100px; float:left;">'.$data['user_password'].'</div></td></tr>
		<tr><td><div style="width:137px; float:left;">Please Contact:</div><div style="padding-left: 10px; width:100px; float:left;">'.SITE_SSL_URL.'</div></td></tr>
			<br><br><br>
			<tr><td colspan="4" style="padding-left: 10px;">
			Thanks for Your Continued Support to us. </td></tr>
             <tr><td colspan="4" style="padding-left: 10px;">
              Regards,
			  </td></tr>
			  <tr><td colspan="4" style="padding-left: 10px;">
              '.SITE_NAME_TITLE.'<br>
              91 - '.ADMIN_MOBILE.'
			</td></tr>
          </tbody></table> 
          </td> 
        </tr> 
      </tbody></table> </td> 
    </tr> 
  </tbody></table>';
  $From_Display = SITE_NAME_TITLE;
					 $usermail=$data['email'];
					$From_Email = ADMIN_EMAIL;
					$To_Email = $usermail;
					$CC = "";
					$BCC = "";
					$Sub = SITE_NAME_TITLE;
					$Message = $msge;
					$Format = 1;
					$msg2=SendMail($From_Display,$From_Email,$To_Email,$CC,$BCC,$Sub,$Message,$Format);	

			 
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
				$newwidth1=100;
				$newheight1=100;
				$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
				
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
				
				$imagetyp = explode("/", $_FILES['file']['type']);
				
				$filename1 = "../includes/user_img/".$id.".jpg";
				
				imagejpeg($tmp1,$filename1,100);
				
				imagedestroy($src);
				imagedestroy($tmp);
				imagedestroy($tmp1);
				
				}
}
unset($_SESSION['form_token']);
		redirect_page($page_url);
		}else return $msg;
			}
			
			 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
			
			function delete_user_details($id) {
			global $obj_db, $page_url;
			    $usr_del=UPDATE_KEYWORD."  ".TABLE_USER_DETAILS." set user_status='0' WHERE  user_id='".$id."'";
		        $obj_db->get_qresult($usr_del);
			redirect_page($page_url);
        
		}
	/*-----End user_details.php-------*/
	
	/*-----Start branch_sms_details.php-------*/
	function get_smsuser_details($id) {
					
					global $obj_db;
					
					  $userper_query="SELECT * FROM ".TABLE_BRANCH_SMSUSERS." WHERE id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($userper_query);
					$user = $user_sel_row;
					return $user;
					}
	function smsupdate_status($id){
     global $obj_db,$page_url;
	 
	     $sel_cancel_recepquery=$obj_db->fetchRow("select status from ".TABLE_BRANCH_SMSUSERS." where id='".$id."'");
		 if($sel_cancel_recepquery['status']==1)
		  {$status=0;}
		 else{$status=1;}
	      $update_studentfee=$obj_db->get_qresult("UPDATE ".TABLE_BRANCH_SMSUSERS." SET status='$status' where  id='".$id."'"); 
	  		  
		 redirect_page($page_url);
   }

 function smsuser_details_save($data,$id){
	 global $obj_db, $page_url;
           
       	 if($id)
			  {
			    $usr_per_upd=UPDATE_KEYWORD."   ".TABLE_BRANCH_SMSUSERS."  SET
			                                url='".$obj_db->real_escape_string($data['url'])."',
				                        user_name='".$obj_db->real_escape_string($data['user_name'])."',
										branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
										pword='".$obj_db->real_escape_string($data['pword'])."',
										status='".$data['status']."'
										 WHERE id='".$id."'";  
				  $res=$obj_db->get_qresult($usr_per_upd);
			}
			
			 else
			 {
			    $usr_dts="INSERT ignore INTO  ".TABLE_BRANCH_SMSUSERS." SET 
			                                                        url='".$obj_db->real_escape_string($data['url'])."',
										user_name='".$obj_db->real_escape_string($data['user_name'])."',
										branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
										pword='".$obj_db->real_escape_string($data['pword'])."',
									    status='".$data['status']."'";
				   $ress=$obj_db->get_qresult($usr_dts);
				   $id=$obj_db->insert_id();
			 }
		redirect_page($page_url);	  		
         }
	/*-----End branch_sms_details.php-------*/
}
?>