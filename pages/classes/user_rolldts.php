<?php 
class insert_update {		
		/*Start year.php*/
		
		
		function userroll_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);
 			$get_components=$obj_db->qry("select * from ".TABLE_COMPONENTS." order by mainview_order asc,menu_view asc ,component_id asc");


               	if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}


			$isdefaultchk=1;
				  $isdefalutchkdts = array_filter($get_components,function($v,$k) use ($isdefaultchk){
					 return $v['is_default_check'] == $isdefaultchk;
				   },ARRAY_FILTER_USE_BOTH);



			 // echo 'test';exit;
			 $main_menuview=array();$compids='';
			 foreach($_REQUEST['component_id'] as $k=>$v)
			   $compids.=$v.',';
			  $trm_lstcoma=substr($compids,0,-1);
			  $exp_compids=explode(',',$trm_lstcoma);
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
			
			$pages_view=array('pages'=>$pagview,'defalut_page'=>$data['user_default_page']);
			  $pgsv=json_encode($pages_view);
			  $menuar=json_encode(array('main_menu'=>$main_menuview));			
			
			     $updusr_roll=UPDATE_KEYWORD."   ".TABLE_USER_DETAILS." SET 
						                  user_pages='".$obj_db->real_escape_string($pgsv)."',
										  user_default_page='".$obj_db->real_escape_string($data['user_default_page'])."',
										  user_menus='".$obj_db->real_escape_string($menuar)."'
 											WHERE user_id='".$id."'";
				 $res=$obj_db->get_qresult($updusr_roll);
			
   unset($_SESSION['form_token']);
		redirect_page($page_url);
			}
			
			
			function delete_year($id) {

			global $obj_db, $page_url;
			
			    $prdrel_del="DELETE FROM ".TABLE_YEAR." WHERE  year_id='".$id."'";
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
			
			    $org_del="DELETE FROM ".TABLE_ORG." WHERE  org_id='".$id."'";
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
											is_hostel='".$obj_db->real_escape_string($data['is_hostel'])."'
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
											branch_start_date='".$obj_db->real_escape_string($data['branch_start_date'])."'"; 
											
											
			  $res=$obj_db->get_qresult($branch_insert_query);
			  
			  $id=$obj_db->insert_id();
							
			 }
		redirect_page($page_url);
			}
			
			
			function delete_branch($id) {

			global $obj_db, $page_url;
			
			  $branch_del="DELETE FROM ".TABLE_BRANCH." WHERE  branch_id='".$id."'";
		        $del_brnch=$obj_db->get_qresult($branch_del);
			
		
			redirect_page($page_url);
        
		}
		/*----End Branch--------*/
		
		/*----Start Expenditure Addings---*/
		 function get_exp_add($id) {

					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_EXPENDITURE_TYPE." WHERE exp_type_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
         
  	 	function exp_add_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);

		     if($id)
			  {
			     $org_upd_query=UPDATE_KEYWORD."   ".TABLE_EXPENDITURE_TYPE." SET 
 											exp_name='".$obj_db->real_escape_string($data['exp_name'])."',
											exp_catg_id='".$obj_db->real_escape_string($data['exp_catg_id'])."',
											is_bank_person='".$obj_db->real_escape_string($data['is_bank_person'])."'
 											WHERE exp_type_id='".$id."'";
				 $res=$obj_db->get_qresult($org_upd_query);
							 }
			 else
			 {
			 
			   $org_insert_query=INSERT_KEYWORD."   ".TABLE_EXPENDITURE_TYPE." SET 
			                                exp_name='".$obj_db->real_escape_string($data['exp_name'])."',
											exp_catg_id='".$obj_db->real_escape_string($data['exp_catg_id'])."',
											is_bank_person='".$obj_db->real_escape_string($data['is_bank_person'])."'";  
											
											
			  $res=$obj_db->get_qresult($org_insert_query);
			  
			  $id=$obj_db->insert_id();
							
			 }
		redirect_page($page_url);
			}
		/*----End Expenditure Addings---*/
	}
	
	?>