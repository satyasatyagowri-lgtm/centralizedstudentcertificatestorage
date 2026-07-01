<?php class linchitoperations{

     	function get_linchit_details($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_LINECHIT_DETAILS."  where line_chit_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}

 function linchit_details_savenew($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
}
			if($id)
			  {
			     
			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_LINECHIT_DETAILS." SET 
				 							line_chit_name='".$obj_db->real_escape_string($data['line_chit_name'])."',
						                	chit_amount='".$obj_db->real_escape_string($data['chit_amount'])."', 
										   chit_start_date='".$obj_db->real_escape_string($data['chit_start_date'])."', 
 											no_of_terms='".$data['no_of_terms']."',
											line_id='".$data['line_id']."',
											update_date='".date('Y-m-d H:i:s')."',
											update_by='".$_SESSION['user_id']."'
											WHERE line_chit_id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
			 }
			 else
			 {
			   $std_insert_query=INSERT_KEYWORD."   ".TABLE_LINECHIT_DETAILS." SET 
			   line_chit_name='".$obj_db->real_escape_string($data['line_chit_name'])."',
			                               chit_amount='".$obj_db->real_escape_string($data['chit_amount'])."', 
										   chit_start_date='".$obj_db->real_escape_string($data['chit_start_date'])."', 
 											no_of_terms='".$data['no_of_terms']."',
											line_id='".$data['line_id']."',
											create_date='".date('Y-m-d H:i:s')."',
											create_by='".$_SESSION['user_id']."'"; 
			  $res=$obj_db->get_qresult($std_insert_query); 
			  $id=$obj_db->insert_id();
			  
			  
					  }
 			///}
			unset($_SESSION['form_token']);
			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
			}


			
			function delete_linchitdetails($id) {
			global $obj_db, $page_url;			
			  $del_student=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_LINECHIT_DETAILS." set is_delete='1' where line_chit_id='".$_REQUEST['id']."'");
			   redirect_page($page_url);
		}

		/*---------------------------------------------------------------------*/
function get_linchitpaid_details($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_LINE_CHITAMT_PAIDDETAILS."  where line_chit_id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}

 function linchitpaid_details_savenew($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
}
			$exppaytypes=array();
$ptypes=$obj_db->qry("select a.* from  ".TABLE_PAYMENT_TYPE." a  ");
foreach($ptypes as $ptypky=>$ptypv)
$exppaytypes[$ptypv['pay_type_id']]=$ptypv['pay_name'];

			if($data['pay_type_id']>1)
	     $bnkid=$data['bank_id'];
	   else $bnkid=$data['pay_type_id'];

	    if($data['by_user_id']!='')
          $seluserid=$data['by_user_id'];

		$pdt=$data['paid_date'];
		

		 $ptypuser_payid=$seluserid;
			
			if($id)
			  {
			     
			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_LINE_CHITAMT_PAIDDETAILS." SET 
				 							line_chit_name='".$obj_db->real_escape_string($data['line_chit_name'])."',
						                	chit_amount='".$obj_db->real_escape_string($data['chit_amount'])."', 
										   chit_start_date='".$obj_db->real_escape_string($data['chit_start_date'])."', 
 											no_of_terms='".$data['no_of_terms']."',
											line_id='".$data['line_id']."',
											update_date='".date('Y-m-d H:i:s')."',
											update_by='".$_SESSION['user_id']."'
											WHERE line_chit_id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
			 }
			 else
			 {
			   $std_insert_query=INSERT_KEYWORD."   ".TABLE_LINE_CHITAMT_PAIDDETAILS." SET 
			   paid_date='".$obj_db->real_escape_string(date('Y-m-d',strtotime($data['paid_date'])))."',
			                               paid_amount='".$obj_db->real_escape_string($data['paid_amount'])."', 
										   line_chit_id='".$obj_db->real_escape_string($data['line_chit_id'])."',
										   description='".$obj_db->real_escape_string($data['description'])."', 
										   by_user_id='".$seluserid."',
										   pay_type_id='".$data['pay_type_id']."',
 											term_id='".$data['term_id']."',
											line_id='".$data['line_id']."',
											enter_date='".date('Y-m-d H:i:s')."',
											enter_by='".$_SESSION['user_id']."'"; 
			  $res=$obj_db->get_qresult($std_insert_query); 
			  $id=$obj_db->insert_id();

			   $del_student=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_LINECHIT_DETAILS." set upd_chitpaid_amt=upd_chitpaid_amt+'".$data['paid_amount']."' where line_chit_id='".$data['line_chit_id']."'");
			  

			    $userlinearrdts=array();
$getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$data['line_id']."', assign_line_ids) > 0 and user_status=1");
                foreach($getline_matchusrids as $mtchusrky=>$matchusrv)  
				$userlinearrdts[$data['line_id']][]=array('user_id'=>$matchusrv['user_id'],'full_name'=>$matchusrv['full_name']);
			   			   foreach($userlinearrdts[$data['line_id']] as $usrky=>$usrv){
           foreach($exppaytypes as $ptypky=>$ptypv){
		     $chk_empusrrecordingivdate=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where  user_id='".$usrv['user_id']."' and line_id='".$data['line_id']."' and pay_type_id='".$ptypky."' and date(date_time)='".date('Y-m-d',strtotime($pdt))."'");
								if(!$chk_empusrrecordingivdate){
									$insrempusr_givdtrecord=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
											user_id='".$usrv['user_id']."',
											pay_type_id='".$ptypky."',
											line_id='".$data['line_id']."',
										   date_time='".date('Y-m-d',strtotime($pdt))."'");
										   
								}
								}
						}
 				
         $updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_expense_amt=line_expense_amt+'".$data['paid_amount']."' where user_id='".$ptypuser_payid."' AND pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($pdt))."' ");
		   $getuserlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_expense_amt=line_expense_amt+'".$data['paid_amount']."',line_updremain_bal=line_updremain_bal-'".$data['paid_amount']."' where user_id='".$ptypuser_payid."' and pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' ");


		   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance-'".$obj_db->real_escape_string($data['paid_amount'])."'
											 where user_id='".$seluserid."' and pay_type_id='".$data['pay_type_id']."'");
			  
					  }
 			///}
			unset($_SESSION['form_token']);
			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
			}


			
			function delete_linchitpaiddetails($id) {
			global $obj_db, $page_url;
			$getchitpaiddts=$obj_db->qry("select *,date(paid_date) as pdt from ".TABLE_LINE_CHITAMT_PAIDDETAILS." where id='".$_REQUEST['id']."' and is_delete='0'");		
			  $delchitpaidamt=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_LINE_CHITAMT_PAIDDETAILS." set is_delete='1',delete_by='".$_SESSION['user_id']."',delete_date='".date('Y-m-d H:i:s')."' where id='".$_REQUEST['id']."'");

			  $del_student=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_LINECHIT_DETAILS." set upd_chitpaid_amt=upd_chitpaid_amt-'".$getchitpaiddts[0]['paid_amount']."' where line_chit_id='".$getchitpaiddts[0]['line_chit_id']."'");
			
			
			   	$updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_expense_amt=line_expense_amt-'".$getchitpaiddts[0]['paid_amount']."' where user_id='".$getchitpaiddts[0]['by_user_id']."' and pay_type_id='".$getchitpaiddts[0]['pay_type_id']."' and line_id='".$getchitpaiddts[0]['line_id']."' and date(date_time)='".$getchitpaiddts[0]['pdt']."' ");
					$upduserexp_exstamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_expense_amt=line_expense_amt-'".$getchitpaiddts[0]['paid_amount']."',line_updremain_bal=line_updremain_bal+'".$getchitpaiddts[0]['paid_amount']."' where user_id='".$getchitpaiddts[0]['by_user_id']."' and pay_type_id='".$getchitpaiddts[0]['pay_type_id']."' and line_id='".$getchitpaiddts[0]['line_id']."'  ");
			 //  $cancelpay_exptrans=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." set is_cancel='1'  where transref_id='".$id."' and year_id='".$_SESSION['year_id']."' and transaction_typeid='5'");
			 
			   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($getchitpaiddts[0]['paid_amount'])."'
											 where user_id='".$getchitpaiddts[0]['by_user_id']."' and pay_type_id='".$getchitpaiddts[0]['pay_type_id']."'");
			
			  redirect_page($page_url);
		}
		/*---------------------------------------------------------------------*/



		/*---------------------------------------------------------------------*/
function get_lineadjustmentamt_details($id) {
					global $obj_db;
					$user_sel_query="SELECT * FROM ".TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS."  where id='".(int)$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}

 function linadjustmentamt_details_savenew($data,$id) {
			global $obj_db, $page_url;
			$msg=array();

			if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}


			$getadtustmenttypes=array(1=>"line_taken_amt",2=>"line_expense_amt",3=>"line_taken_amt",4=>"line_expense_amt",5=>"line_expense_amt",6=>"line_taken_amt",7=>"line_expense_amt");
			$getadtustmenttypests=array(1=>"+",2=>"-",3=>"+",4=>"-",5=>"-",6=>"+",7=>"-");

             $adjustcndamts=$getadtustmenttypes[$data['is_sortaccess_borrorwtype']]."=".$getadtustmenttypes[$data['is_sortaccess_borrorwtype']].'+'.$data['adjustment_amt'];

			  $linexistrembalstscnds=" ,line_updremain_bal=line_updremain_bal".$getadtustmenttypests[$data['is_sortaccess_borrorwtype']]."'".$data['adjustment_amt']."' ";
//$exppaytypes=array(1=>"Cash",2=>"Upi");

$exppaytypes=array();
$ptypes=$obj_db->qry("select a.* from  ".TABLE_PAYMENT_TYPE." a  ");
foreach($ptypes as $ptypky=>$ptypv)
$exppaytypes[$ptypv['pay_type_id']]=$ptypv['pay_name'];
//echo 'test<pre>';print_r($exppaytypes);echo '</pre>';exit;

			if($data['g_date']!='' && date('d-m-Y')==$data['g_date']){
		$pay_dt=date('Y-m-d H:i:s');
		$pdt=date('Y-m-d',strtotime($data['g_date']));
		}elseif($data['g_date']!=''){
		$pay_dt=date('Y-m-d H:i:s',strtotime($data['g_date']));
		$pdt=date('Y-m-d',strtotime($data['g_date']));
		}else{$pay_dt=date('Y-m-d H:i:s');$pdt=date('Y-m-d');}	
		
	
		 if($data['sel_user_id']!='')
          $seluserid=$data['sel_user_id'];
		elseif($data['upayuser_id']>0)
          $seluserid=$data['upayuser_id'];
	    else $seluserid=$_SESSION['user_id'];

		 if($data['pay_type_id']>1)
				$ptypuser_payid=$data['upayuser_id'];
			 else $ptypuser_payid=$seluserid;

			if($id)
			  {
			     
			     $std_upd_query=UPDATE_KEYWORD."   ".TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS." SET 
				 							line_chit_name='".$obj_db->real_escape_string($data['line_chit_name'])."',
						                	chit_amount='".$obj_db->real_escape_string($data['chit_amount'])."', 
										   chit_start_date='".$obj_db->real_escape_string($data['chit_start_date'])."', 
 											no_of_terms='".$data['no_of_terms']."',
											line_id='".$data['line_id']."',
											update_date='".date('Y-m-d H:i:s')."',
											update_by='".$_SESSION['user_id']."'
											WHERE line_chit_id='".$id."'";
				 $res=$obj_db->get_qresult($std_upd_query);
			 }
			 else
			 {
			   $std_insert_query=INSERT_KEYWORD."   ".TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS." SET 
			   date_time='".$obj_db->real_escape_string(date('Y-m-d',strtotime($data['g_date'])))."',
			                               adjustment_amt='".$obj_db->real_escape_string($data['adjustment_amt'])."', 
										   reason='".$obj_db->real_escape_string($data['reason'])."', 
										   is_sortaccess_borrorwtype='".$data['is_sortaccess_borrorwtype']."',
										   pay_type_id='".$obj_db->real_escape_string($data['pay_type_id'])."',
 											user_id='".$seluserid."',
											line_id='".$data['line_id']."',
											enter_date='".date('Y-m-d H:i:s')."',
											enter_by='".$_SESSION['user_id']."'"; 
			  $res=$obj_db->get_qresult($std_insert_query); 
			  $id=$obj_db->insert_id();


			    $userlinearrdts=array();
$getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$data['line_id']."', assign_line_ids) > 0 and user_status=1");
                foreach($getline_matchusrids as $mtchusrky=>$matchusrv)  
				$userlinearrdts[$data['line_id']][]=array('user_id'=>$matchusrv['user_id'],'full_name'=>$matchusrv['full_name']);
			   			   foreach($userlinearrdts[$data['line_id']] as $usrky=>$usrv){


            foreach($exppaytypes as $ptypky=>$ptypv){
		     $chk_empusrrecordingivdate=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where  user_id='".$usrv['user_id']."' and line_id='".$data['line_id']."' and pay_type_id='".$ptypky."' and date(date_time)='".date('Y-m-d',strtotime($pay_dt))."'");
								if(!$chk_empusrrecordingivdate){
									$insrempusr_givdtrecord=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
											user_id='".$usrv['user_id']."',
											pay_type_id='".$ptypky."',
											line_id='".$data['line_id']."',
										   date_time='".$pay_dt."'");
										   
								}
								}
						}

          $updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set $adjustcndamts where user_id='".$ptypuser_payid."' AND pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($pay_dt))."' ");
		   $getuserlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set $adjustcndamts $linexistrembalstscnds where user_id='".$ptypuser_payid."' and pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' ");

			  
 					  }
 			///}
			unset($_SESSION['form_token']);
 			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
			}


			
			function delete_linadjustmentamtdetails($id) {
			global $obj_db, $page_url;
			$adustmentdts=$obj_db->qry("select *,date(date_time) as dt from ".TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS." where id='".$_REQUEST['id']."' and is_cancel='0'");	
			$adustamt=$adustmentdts[0]['adjustment_amt'];

         $getadtustmenttypes=array(1=>"line_taken_amt",2=>"line_expense_amt",3=>"line_taken_amt",4=>"line_expense_amt",5=>"line_expense_amt",6=>"line_taken_amt",7=>"line_expense_amt");
			$getadtustmenttypests=array(1=>"+",2=>"-",3=>"+",4=>"-");
			$getadtustmenttypests_oppsigns=array(1=>"-",2=>"+",3=>"-",4=>"+",5=>"+",6=>"-",7=>"+");

             $adjustcndamts=$getadtustmenttypes[$adustmentdts[0]['is_sortaccess_borrorwtype']]."=".$getadtustmenttypes[$adustmentdts[0]['is_sortaccess_borrorwtype']].'-'.$adustamt;
             
			 $linexistrembalstscnds=" ,line_updremain_bal=line_updremain_bal".$getadtustmenttypests_oppsigns[$adustmentdts[0]['is_sortaccess_borrorwtype']]."'".$adustamt."' ";

			  $delchitpaidamt=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS." set is_cancel='1',cancel_by='".$_SESSION['user_id']."',cancel_date='".date('Y-m-d H:i:s')."' where id='".$_REQUEST['id']."'");
 $updlinecolcamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set $adjustcndamts where user_id='".$adustmentdts[0]['user_id']."' and pay_type_id='".$adustmentdts[0]['pay_type_id']."' and line_id='".$adustmentdts[0]['line_id']."' and date(date_time)='".$adustmentdts[0]['dt']."' ");
						$updusrlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set $adjustcndamts $linexistrembalstscnds where user_id='".$adustmentdts[0]['user_id']."' and pay_type_id='".$adustmentdts[0]['pay_type_id']."' and line_id='".$adustmentdts[0]['line_id']."'  ");
			  redirect_page($page_url);
		}
		/*---------------------------------------------------------------------*/
	
		}
		?>