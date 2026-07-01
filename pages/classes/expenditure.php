<?php class vouchergenerator{

	/*---------Expnew Details---------------*/
  function get_expenditurenew($id) {
					global $obj_db;
					 $user_sel_query="SELECT amount,reason,received_by,exp_name,date(a.enter_date) as edt,a.exp_type_id,a.pay_type_id,a.is_approval,a.exp_catg_id,voucher_no,a.branch_id,branch_name,bank_id,transcaction_no FROM ".TABLE_EXPENDITURE." a,".TABLE_EXPENDITURE_TYPE." b,".TABLE_BRANCH." c WHERE id='".(int)$id."' and year_id='".$_SESSION['year_id']."' and a.exp_type_id=b.exp_type_id and a.branch_id=c.branch_id";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
		function get_expcancelnew($id){
		global $obj_db, $page_url;
	   if($_REQUEST['status']==1)
	    $sts=0;
		else $sts=1;
	   $exp_updquery=UPDATE_KEYWORD."  ".TABLE_EXPENDITURE." SET 
											cancel_permission='".$sts."'											
											where id='".$id."'";  
			   $res=$obj_db->get_qresult($exp_updquery);			   
			   redirect_page($page_url."&dt=".$_REQUEST['dt1']." TO ".$_REQUEST['dt2']."&exptype_id=".$_REQUEST['exptype_id']);
		}		
		
		function exp_cencel($id){
		global $obj_db, $page_url;
	  $get_expdts=$obj_db->qry("select *,date(exp_date) as expdt from ".TABLE_EXPENDITURE." where id='".$id."' and is_cancel=0");

	   $exp_updquery=UPDATE_KEYWORD."  ".TABLE_EXPENDITURE." SET 
											update_date='".date('Y-m-d h-m-s')."',
											cancel_reason='".$obj_db->real_escape_string($data['cancel_reason'])."',
											is_cancel='1',
											cancel_dete='".date('Y-m-d H:i:s')."',
											cancel_by='".$_SESSION['user_id']."'
											where id='".$id."'";  
			   $res=$obj_db->get_qresult($exp_updquery);
			   	$updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_expense_amt=line_expense_amt-'".$get_expdts[0]['amount']."' where user_id='".$get_expdts[0]['user_id']."' and pay_type_id='".$get_expdts[0]['pay_type_id']."' and line_id='".$get_expdts[0]['line_id']."' and date(date_time)='".$get_expdts[0]['expdt']."' ");
					$upduserexp_exstamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_expense_amt=line_expense_amt-'".$get_expdts[0]['amount']."',line_updremain_bal=line_updremain_bal+'".$get_expdts[0]['amount']."' where user_id='".$get_expdts[0]['user_id']."' and pay_type_id='".$get_expdts[0]['pay_type_id']."' and line_id='".$get_expdts[0]['line_id']."'  ");
			   $cancelpay_exptrans=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." set is_cancel='1'  where transref_id='".$id."' and year_id='".$_SESSION['year_id']."' and transaction_typeid='5'");
			 
			   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($get_expdts[0]['amount'])."'
											 where user_id='".$get_expdts[0]['user_id']."' and pay_type_id='".$get_expdts[0]['pay_type_id']."'");

			   redirect_page($page_url."&frmdt=".$_REQUEST['frmdt']."&todt=".$_REQUEST['todt']);
		}		

  function expnewdts_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
	 if($data['exp_date']!='')
	   $exp_dt=date('Y-m-d H:i:s',strtotime($data['exp_date']));
	else $exp_dt=date('Y-m-d H:i:s');

	if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}


//$exppaytypes=array(1=>"Cash",2=>"Upi");
$exppaytypes=array();
$ptypes=$obj_db->qry("select a.* from  ".TABLE_PAYMENT_TYPE." a  ");
foreach($ptypes as $ptypky=>$ptypv)
$exppaytypes[$ptypv['pay_type_id']]=$ptypv['pay_name'];

	   if($data['pay_type_id']>1)
	     $bnkid=$data['bank_id'];
	   else $bnkid=$data['pay_type_id'];

	    if($data['sel_user_id']!='')
          $seluserid=$data['sel_user_id'];
		elseif($data['upayuser_id']>0)
          $seluserid=$data['upayuser_id'];
	    else $seluserid=$_SESSION['user_id'];

		 if($data['pay_type_id']>1)
				$ptypuser_payid=$data['upayuser_id'];
			 else $ptypuser_payid=$seluserid;

	   if($id){
		$get_expdts=$obj_db->qry("select * from ".TABLE_EXPENDITURE." where id='".$id."'");

	   $exp_updquery=UPDATE_KEYWORD."  ".TABLE_EXPENDITURE." SET 
											update_date='".date('Y-m-d h-m-s')."',
											cancel_reason='".$obj_db->real_escape_string($data['cancel_reason'])."',
											is_cancel='1',
											cancel_dete='".date('Y-m-d H:i:s')."',
											cancel_by='".$_SESSION['user_id']."'
											where id='".$id."'";  
			   $res=$obj_db->get_qresult($exp_updquery);
			   
			   $cancelpay_exptrans=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." set is_cancel='1'  where transref_id='".$id."' and year_id='".$_SESSION['year_id']."' and transaction_typeid='5'");
			 
			   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($data['amount'])."'
											 where user_id='".$get_expdts[0]['user_id']."' and pay_type_id='".$get_expdts[0]['pay_type_id']."'");

			   redirect_page($page_url."&dt=".$_REQUEST['dt1']." TO ".$_REQUEST['dt2']);
			   }else{
				   $chk_dtwise_numdts=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' order by date(date_time) desc limit 1");	 
		  $chk_dtwise_lineamtdts=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
		  $get_givdt_curbals=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
 
			   $exp_expid=explode(' ',$data['exp_type_id']);

			   $getgivtotyp=$obj_db->fetchRow("select a.*,b.is_bank_person from  ".TABLE_EXPENDITURE_TYPE." a,".TABLE_EXPENDITURE_CATEGORY." b  where a.exp_type_id='".$exp_expid[0]."' and a.exp_catg_id=b.exp_catg_id ");
				$getgivbytyp=$obj_db->fetchRow("select a.*,b.is_bank_person from  ".TABLE_EXPENDITURE_TYPE." a,".TABLE_EXPENDITURE_CATEGORY." b  where a.exp_type_id='".$bnkid."' and a.exp_catg_id=b.exp_catg_id ");
				$patypedesc='Pay Type : '.$getgivbytyp['exp_name'];
				$append_recp="select max(voucher_no) as voucher_no from ".TABLE_EXPENDITURE." where year_id='".$_SESSION['year_id']."' and line_id=".$data['line_id']; 
	    $append_recp_r=$obj_db->fetchRow($append_recp);
		$v_no = $append_recp_r['voucher_no']+1;
		$expcatg_id=$obj_db->fetchRow("select exp_catg_id  from ".TABLE_EXPENDITURE_TYPE." where exp_type_id='".$obj_db->real_escape_string($exp_expid[0])."'"); 
			    $exp_query=INSERT_KEYWORD."   ".TABLE_EXPENDITURE." SET 
			                                amount='".$obj_db->real_escape_string($data['amount'])."', 
											exp_type_id='".$obj_db->real_escape_string($exp_expid[0])."',
											exp_catg_id='".$obj_db->real_escape_string($expcatg_id['exp_catg_id'])."',
											user_id='".$obj_db->real_escape_string($seluserid)."',
											 payby_user_id='".$ptypuser_payid."',
											enter_by='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											reason='".$obj_db->real_escape_string($data['reason'])."',
											exp_date='".$exp_dt."',
											enter_date='".date('Y-m-d H:i:s')."',
											is_income='".$data['is_income']."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											voucher_no='".$v_no."',
											branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
											line_id='".$obj_db->real_escape_string($data['line_id'])."',
											pay_type_id='".$obj_db->real_escape_string($data['pay_type_id'])."',
											bank_id='".$obj_db->real_escape_string($bnkid)."',
											transcaction_no='".$obj_db->real_escape_string($data['transcaction_no'])."',
											received_by='".$obj_db->real_escape_string($data['received_by'])."'";  
			   $res=$obj_db->get_qresult($exp_query);
			   $id=$obj_db->insert_id();
			  
			   $incr_commortransactions=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." SET 
			 							    transaction_typeid='5',
											exp_type_id='".$exp_expid[0]."',
											given_by='".$bnkid."',
											given_to='".$exp_expid[0]."',
											bill_no='".$obj_db->real_escape_string($v_no)."',
											transref_id='".$obj_db->real_escape_string($id)."',
											is_receive='1',
											is_income='0',
											exp_type_persion='".$getgivtotyp['is_bank_person']."',
											date_time='".$exp_dt."',
 											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											 user_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											 line_id='".$data['line_id']."',
											branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
 											amount='".$obj_db->real_escape_string($data['amount'])."',
											transaction_no	='".$data['transcaction_no']."',
											exp_received_by='".$data['received_by']."',
                                            description='".$obj_db->real_escape_string($data['purpose'].'  ,'.$patypedesc)."'");

											$incr_commortransactions=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." SET 
											transaction_typeid='5',
											exp_type_id='".$bnkid."',
										   given_by='".$bnkid."',
										   given_to='".$exp_expid[0]."',
										   bill_no='".$obj_db->real_escape_string($v_no)."',
										   transref_id='".$obj_db->real_escape_string($id)."',
										   is_receive='1',
										   exp_type_persion='".$getgivbytyp['is_bank_person']."',
										   is_income='0',
										   date_time='".$exp_dt."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											user_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											
										   branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
											amount='".$obj_db->real_escape_string($data['amount'])."',
										   transaction_no	='".$data['transcaction_no']."',
										   exp_received_by='".$data['received_by']."',
										   description='".$obj_db->real_escape_string($data['purpose'].'  ,'.$patypedesc)."'");


				/*    if($chk_dtwise_numdts['date_time']=='' && !$chk_dtwise_lineamtdts){
			
			   $exp_amt=$get_givdt_curbals['line_expense_amt']+$data['amount'];
			   $rembal=($chk_dtwise_numdts['line_remain_bal']+$get_givdt_curbals['line_taken_amt']+$get_givdt_curbals['line_collect_amts'])-($exp_amt+$get_givdt_curbals['line_given_amts']);
			    $insr_dtwseusrtakamt=INSERT_KEYWORD."   ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
			                                line_open_bal='".$obj_db->real_escape_string($chk_dtwise_numdts['line_remain_bal'])."', 
 											line_expense_amt='".$obj_db->real_escape_string($exp_amt)."',
											line_remain_bal='".$rembal."',
											line_id='".$obj_db->real_escape_string($data['line_id'])."',
											date_time='".$pdt."'";   
			   $res=$obj_db->get_qresult($insr_dtwseusrtakamt);
			   }
			  else if($chk_dtwise_lineamtdts){
			    //$chk_dtwise_numdts['date_time']<=$pdt && 
			   $exp_amt=$get_givdt_curbals['line_expense_amt']+$data['amount'];
			   $rembal=($get_givdt_curbals['line_open_bal']+$get_givdt_curbals['line_taken_amt']+$get_givdt_curbals['line_collect_amts'])-($exp_amt+$get_givdt_curbals['line_given_amts']);
			    $upd_dtwseusrtakamt=UPDATE_KEYWORD."   ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
 											line_expense_amt='".$obj_db->real_escape_string($exp_amt)."',
											line_remain_bal='".$rembal."' where 
											line_id='".$obj_db->real_escape_string($data['line_id'])."' and 
											date_time='".$pdt."'";   
			   $res=$obj_db->get_qresult($upd_dtwseusrtakamt);
			   
		   }*/


			   $userlinearrdts=array();
$getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$data['line_id']."', assign_line_ids) > 0 and user_status=1");
                foreach($getline_matchusrids as $mtchusrky=>$matchusrv)  
				$userlinearrdts[$data['line_id']][]=array('user_id'=>$matchusrv['user_id'],'full_name'=>$matchusrv['full_name']);
			   			   foreach($userlinearrdts[$data['line_id']] as $usrky=>$usrv){
           foreach($exppaytypes as $ptypky=>$ptypv){
		     $chk_empusrrecordingivdate=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where  user_id='".$usrv['user_id']."' and line_id='".$data['line_id']."' and pay_type_id='".$ptypky."' and date(date_time)='".date('Y-m-d',strtotime($exp_dt))."'");
								if(!$chk_empusrrecordingivdate){
									$insrempusr_givdtrecord=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
											user_id='".$usrv['user_id']."',
											pay_type_id='".$ptypky."',
											line_id='".$data['line_id']."',
										   date_time='".$exp_dt."'");
										   
								}
								}
						}
 				
         $updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_expense_amt=line_expense_amt+'".$data['amount']."' where user_id='".$ptypuser_payid."' AND pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($exp_dt))."' ");
		   $getuserlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_expense_amt=line_expense_amt+'".$data['amount']."',line_updremain_bal=line_updremain_bal-'".$data['amount']."' where user_id='".$ptypuser_payid."' and pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' ");


		   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance-'".$obj_db->real_escape_string($data['amount'])."'
											 where user_id='".$seluserid."' and pay_type_id='".$data['pay_type_id']."'");
							
 
  			} 
			unset($_SESSION['form_token']);
		  redirect_page($page_url);
			}
 /*---------Expnew Details---------------*/

 


/*----------Exp catmap------------------*/
function get_exptypecatgs($id){
	global $obj_db, $page_url;	   
   $get_expcatgs=$obj_db->fetchRow("select * from ".TABLE_EXP_CATEGORYS_MAP." where exp_catg_id='".$id."'");  
	return $get_expcatgs;
   
	}			
function exp_catmap($data,$id) {
		global $obj_db, $page_url;
		
		$del_exptyps=$obj_db->get_qresult(DELETE_KEYWORD."  ".TABLE_EXP_CATEGORYS_MAP." where exp_catg_id='".$id."'");
   for($i=0;$i<count($data['exp_type_id']);$i++){
		$exp_typeqry=INSERT_KEYWORD."   ".TABLE_EXP_CATEGORYS_MAP." SET 
										 exp_type_id='".$obj_db->real_escape_string($data['exp_type_id'][$i])."',
										exp_catg_id='".$obj_db->real_escape_string($data['exp_catg_id'])."'";  
		   $res=$obj_db->get_qresult($exp_typeqry);
		   }
	  redirect_page($page_url);
		}
		
	/*----------------Exp cat_map----------------*/
 }
?>