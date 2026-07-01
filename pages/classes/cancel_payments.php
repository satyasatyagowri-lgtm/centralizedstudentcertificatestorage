<?php class cancel_operations{ 
   	function cancel_customer_payment($id) {
					global $obj_db,$page_url;
					$get_paymentdts=$obj_db->qry("select paid_week_months,paid_amts,borrow_id,is_paid_amount,date(paid_date) as pdt,line_id,pay_type_id,user_id from ".TABLE_CUST_PAYMENTS." where is_delete=0 and id='".$_REQUEST['id']."'");
					if(count($get_paymentdts)>0){
						$$tpaid=0;
					for($h=0;$h<count($get_paymentdts);$h++){
					$tpaid=$tpaid+$get_paymentdts[$h]['is_paid_amount'];
					 $upd_salpaidamts=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_CUSTOMER_GENPAYMENTS." set remain_balance=remain_balance+'".$get_paymentdts[$h]['is_paid_amount']."' where borrow_id='".$get_paymentdts[$h]['borrow_id']."'");
					 $get_paymntwks=explode(',',$get_paymentdts[$h]['paid_week_months']);
					 $get_paymnts=explode(',',$get_paymentdts[$h]['paid_amts']);
					 for($i=0;$i<count($get_paymntwks);$i++){
                     $get_wekpaments=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." set monthlydue_amt=monthlydue_amt+'".$get_paymnts[$i]."' where borrow_id='".$get_paymentdts[$h]['borrow_id']."' and month_week='".$get_paymntwks[$i]."'");
					 }
					 $pdt=$get_paymentdts[$h]['pdt'];
					 $line_id=$get_paymentdts[$h]['line_id'];
             		}
					$upd_cancelsts=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_CUST_PAYMENTS." set is_delete=1,cancel_date='".date('Y-m-d H:i:s')."',cancel_by='".$_SESSION['user_id']."' where id='".$_REQUEST['id']."'");
					
						$updlinecolcamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_collect_amts=line_collect_amts-'".$tpaid."' where user_id='".$get_paymentdts[0]['user_id']."' and pay_type_id='".$get_paymentdts[0]['pay_type_id']."' and line_id='".$get_paymentdts[0]['line_id']."' and date(date_time)='".$get_paymentdts[0]['pdt']."' ");
						$updusrlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_collect_amts=line_collect_amts-'".$tpaid."',line_updremain_bal=line_updremain_bal-'".$tpaid."' where user_id='".$get_paymentdts[0]['user_id']."' and pay_type_id='".$get_paymentdts[0]['pay_type_id']."' and line_id='".$get_paymentdts[0]['line_id']."'  ");
					/* 
					$get_givdt_curbals=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$line_id."' and date(date_time)='".$pdt."'");
					 $colc_amt=$get_givdt_curbals['line_collect_amts']-$tpaid;
			   $rembal=($get_givdt_curbals['line_open_bal']+$get_givdt_curbals['line_taken_amt']+$colc_amt)-($get_givdt_curbals['line_given_amts']+$get_givdt_curbals['line_expense_amt']);
			    $upd_dtwseusrtakamt=UPDATE_KEYWORD."   ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
 											line_collect_amts='".$obj_db->real_escape_string($colc_amt)."',
											line_remain_bal='".$rembal."' where 
											line_id='".$obj_db->real_escape_string($line_id)."' and 
											date_time='".$pdt."'";   
			   $res=$obj_db->get_qresult($upd_dtwseusrtakamt);
*/
			   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance-'".$obj_db->real_escape_string($tpaid)."'
											 where user_id='".$get_paymentdts[0]['user_id']."' and pay_type_id='".$get_paymentdts[0]['pay_type_id']."'");
			   }
				redirect_page($page_url."&dt=".$_REQUEST['dt']);
					}
            
 }
?>