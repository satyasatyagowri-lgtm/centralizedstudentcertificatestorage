<?php class vouchergenerator{

	/*---------Expnew Details---------------*/
  function get_exptypes($id) {
					global $obj_db;
					 $user_sel_query="SELECT * FROM ".TABLE_EXPENDITURE_TYPE."  WHERE exp_type_id='".$id."'";
					$user_sel_row = $obj_db->fetchRow($user_sel_query);
					$user = $user_sel_row;
					return $user;
					}
		

  function exptypdts_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
	if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}
	   if($id){
 
	   $exp_updquery=UPDATE_KEYWORD."  ".TABLE_EXPENDITURE_TYPE." SET 
											exp_catg_id='".$obj_db->real_escape_string($data['exp_catg_id'])."',
											exp_name='".$obj_db->real_escape_string($data['exp_name'])."'
											where exp_type_id='".$id."'";  
			   $res=$obj_db->get_qresult($exp_updquery);
			   }else{
			
				    $exp_query=INSERT_KEYWORD."   ".TABLE_EXPENDITURE_TYPE." SET 
  											exp_catg_id='".$obj_db->real_escape_string($data['exp_catg_id'])."',
											exp_name='".$obj_db->real_escape_string($data['exp_name'])."'";  
			   $res=$obj_db->get_qresult($exp_query);
			   $id=$obj_db->insert_id();   			  			
 
  			} 
			unset($_SESSION['form_token']);
		  redirect_page($page_url);
			}
 /*---------Expnew Details---------------*/


function emp_cancelamt($id){
		global $obj_db, $page_url;
	  $gettakamts=$obj_db->qry("select *,date(taken_date) as takdt from ".TABLE_EMPUSER_TAKEN_AMTS." where id='".$id."' and is_delete=0");
 	   $exp_updquery=UPDATE_KEYWORD."  ".TABLE_EMPUSER_TAKEN_AMTS." SET 
 											delete_reason='".$obj_db->real_escape_string($data['cancel_reason'])."',
											is_delete='1',
											delete_date='".date('Y-m-d H:i:s')."',
											delete_by='".$_SESSION['user_id']."'
											where id='".$id."'";  
			   $res=$obj_db->get_qresult($exp_updquery);
 			   	$updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_taken_amt=line_taken_amt-'".$gettakamts[0]['amount']."' where user_id='".$gettakamts[0]['takenuser_id']."' and pay_type_id='".$gettakamts[0]['pay_type_id']."' and line_id='".$gettakamts[0]['line_id']."' and date(date_time)='".$gettakamts[0]['takdt']."' ");
				$updcusexist_takamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_taken_amt=line_taken_amt-'".$gettakamts[0]['amount']."',line_updremain_bal=line_updremain_bal-'".$gettakamts[0]['amount']."' where user_id='".$gettakamts[0]['takenuser_id']."' and pay_type_id='".$gettakamts[0]['pay_type_id']."' and line_id='".$gettakamts[0]['line_id']."'  ");
			   $cancelpay_exptrans=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." set is_cancel='1'  where transref_id='".$id."' and year_id='".$_SESSION['year_id']."' and transaction_typeid='7'");
			 
			   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance-'".$obj_db->real_escape_string($gettakamts[0]['amount'])."'
											 where user_id='".$gettakamts[0]['user_id']."' and pay_type_id='".$gettakamts[0]['pay_type_id']."'");

			   redirect_page($page_url."&dt=".$_REQUEST['dt']);
		}



 function emptakenamt_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
	 if($data['amt_takdate']!='')
	   $exp_dt=date('Y-m-d H:i:s',strtotime($data['amt_takdate']));
	else $exp_dt=date('Y-m-d H:i:s');

	if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}

	$exppaytypes=array(1=>"Cash",2=>"Upi");

	   if($data['pay_type_id']>1)
	     $bnkid=$data['bank_id'];
	   else $bnkid=$data['pay_type_id'];

	    if($data['to_user_id']!='')
          $seluserid=$data['to_user_id'];
	    else $seluserid=$_SESSION['user_id'];

			 if($data['pay_type_id']>1)
				$ptypuser_payid=$data['pay_type_id'];
			 else $ptypuser_payid=$seluserid;

	   if($id){
		$get_expdts=$obj_db->qry("select * from ".TABLE_EMPUSER_TAKEN_AMTS." where id='".$id."'");

	   $exp_updquery=UPDATE_KEYWORD."  ".TABLE_EMPUSER_TAKEN_AMTS." SET 
											update_date='".date('Y-m-d h-m-s')."',
											cancel_reason='".$obj_db->real_escape_string($data['cancel_reason'])."',
											is_cancel='1',
											cancel_dete='".date('Y-m-d H:i:s')."',
											cancel_by='".$_SESSION['user_id']."'
											where id='".$id."'";  
			   $res=$obj_db->get_qresult($exp_updquery);
			   
			   $cancelpay_exptrans=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." set is_cancel='1'  where transref_id='".$id."' and year_id='".$_SESSION['year_id']."' and transaction_typeid='7'");
			 
			   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($data['amount'])."'
											 where user_id='".$get_expdts[0]['user_id']."' and pay_type_id='".$get_expdts[0]['pay_type_id']."'");

			   redirect_page($page_url."&dt=".$_REQUEST['dt1']." TO ".$_REQUEST['dt2']);
			   }else{
				   $chk_dtwise_numdts=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' order by date(date_time) desc limit 1");	 
		  $chk_dtwise_lineamtdts=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
		  $get_givdt_curbals=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
 
			   $exp_expid=explode(' ',$data['exp_type_id']);

			 //  $getgivtotyp=$obj_db->fetchRow("select a.*,b.is_bank_person from  ".TABLE_EMPUSER_TAKEN_AMTS." a,".TABLE_EXPENDITURE_CATEGORY." b  where a.exp_type_id='".$exp_expid[0]."' and a.exp_type_id=b.exp_type_id ");
				//$getgivbytyp=$obj_db->fetchRow("select a.*,b.is_bank_person from  ".TABLE_EMPUSER_TAKEN_AMTS." a,".TABLE_EXPENDITURE_CATEGORY." b  where a.exp_type_id='".$bnkid."' and a.exp_type_id=b.exp_type_id ");
				$patypedesc='Pay Type : '.$getgivbytyp['exp_name'];
   			    $exp_query=INSERT_KEYWORD."   ".TABLE_EMPUSER_TAKEN_AMTS." SET 
			                                amount='".$obj_db->real_escape_string($data['amount'])."', 
											takenuser_id='".$obj_db->real_escape_string($seluserid)."',
											exp_type_id='".$obj_db->real_escape_string($data['frm_user_id'])."',
 											enter_by='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											reason='".$obj_db->real_escape_string($data['reason'])."',
											taken_date='".$exp_dt."',
											enter_date='".date('Y-m-d H:i:s')."',
 											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
 											line_id='".$obj_db->real_escape_string($data['line_id'])."',
											pay_type_id='".$obj_db->real_escape_string($data['pay_type_id'])."',
											bank_id='".$obj_db->real_escape_string($bnkid)."',
											transacton_no='".$obj_db->real_escape_string($data['transcaction_no'])."'";  
			   $res=$obj_db->get_qresult($exp_query);
			   $id=$obj_db->insert_id();
			  
			   $incr_commortransactions=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." SET 
			 							    transaction_typeid='7',
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
											branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
 											amount='".$obj_db->real_escape_string($data['amount'])."',
											transaction_no	='".$data['transcaction_no']."',
											exp_received_by='".$data['received_by']."',
                                            description='".$obj_db->real_escape_string($data['purpose'].'  ,'.$patypedesc)."'");

											$incr_commortransactions=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_PAYMENT_EXPENSE_TRANSACTIONHISTORY." SET 
											transaction_typeid='7',
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
 				
         $updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_taken_amt=line_taken_amt+'".$data['amount']."' where user_id='".$seluserid."' AND pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($exp_dt))."' ");
		   $getuserlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_taken_amt=line_taken_amt+'".$data['amount']."',line_updremain_bal=line_updremain_bal+'".$data['amount']."' where user_id='".$seluserid."' and pay_type_id='".$data['pay_type_id']."' and line_id='".$data['line_id']."' ");



							

            /*                    $chk_empusrrecordingivdate=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where  user_id='".$seluserid."' and line_id='".$data['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($exp_dt))."'");
								if(!$chk_empusrrecordingivdate){
									$insrempusr_givdtrecord=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
											user_id='".$seluserid."',
											line_id='".$data['line_id']."',
										   date_time='".$exp_dt."'");
								}
										
								$updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_taken_amt=line_taken_amt+'".$data['amount']."' where user_id='".$seluserid."' and line_id='".$data['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($exp_dt))."' ");

				   */
		   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($data['amount'])."'
											 where user_id='".$seluserid."' and pay_type_id='".$data['pay_type_id']."'");
							
 
  			} 
			unset($_SESSION['form_token']);
		  redirect_page($page_url);
			}
 /*---------Expnew Details---------------*/

		
 }
?>