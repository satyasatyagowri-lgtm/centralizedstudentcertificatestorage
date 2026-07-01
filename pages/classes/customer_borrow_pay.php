<?php class fee_operations{
					
	/*-----Start Ricepurchase feepay-----*/
	function get_fee_struct($data,$id) {
            global $obj_db, $page_url;
			//echo '<pre>';print_r($data);echo '</pre>';exit;
			$partybranh_dts=$obj_db->fetchRow("select line_id from ".TABLE_CUSTOMER_DTS." where customer_id='".$data['customer_id']."'");
			 $branch_query="select * from ".TABLE_BRANCH." where 	branch_id='".$partybranh_dts['branch_id']."'"; 
		$branch_row=$obj_db->fetchRow($branch_query);
		$branchid=$branch_row['branch_id'];

		if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}
		$exppaytypes=array(1=>"Cash",2=>"Upi");
//$_SESSION['linematch_users'][$partybranh_dts['line_id']][0]['user_id'];;
 
		if($data['sel_user_id']!='')
          $seluserid=$data['sel_user_id'];
	    else $seluserid=$_SESSION['user_id'];;
		
		if($data['g_date']!='' && date('d-m-Y')==$data['g_date']){
		$pay_dt=date('Y-m-d H:i:s');
		$pdt=date('Y-m-d',strtotime($data['g_date']));
		}elseif($data['g_date']!=''){
		$pay_dt=date('Y-m-d H:i:s',strtotime($data['g_date']));
		$pdt=date('Y-m-d',strtotime($data['g_date']));
		}else{$pay_dt=date('Y-m-d H:i:s');$pdt=date('Y-m-d');}	  
		 	 
	    $paidgen_inc=0;
		$tpaid=0;

		

 		
			  if($data['payment_type']>1)
				$ptypuser_payid=$data['upayuser_id'];
			 else $ptypuser_payid=$seluserid;
		
		
		$chk_dtwise_numdts=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$partybranh_dts['line_id']."' and user_id='".$seluserid."' order by date(date_time) desc limit 1");	 
		  $chk_dtwise_lineamtdts=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$partybranh_dts['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
		  $get_givdt_curbals=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$partybranh_dts['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
		  
		
 //if($chk_dtwise_numdts['date_time']<=$pdt || $chk_dtwise_numdts['date_time']==''){
		for($i=0;$i<sizeof($data['customer_pays']);$i++){
		if($data['customer_pays'][$i]>0){
	
		 $pay_fee=$data['customer_pays'][$i];
		 $tpaid=$tpaid+$pay_fee;
		$custfee_updates=UPDATE_KEYWORD."  ".TABLE_CUSTOMER_GENPAYMENTS." set remain_balance=(remain_balance-'$pay_fee') where customer_id='".$data['customer_id']."' and borrow_id='".$data['borrow_id'][$i]."' and year_id='".$_SESSION['year_id']."'";
		               $fee_updres=$obj_db->get_qresult($custfee_updates);
		$get_monweekpayments=$obj_db->get_qresult("select borrow_id,monthlydue_amt,month_week from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." where customer_id='".$data['customer_id']."' and borrow_id='".$data['borrow_id'][$i]."' and year_id='".$_SESSION['year_id']."' group by month_week having sum(monthlydue_amt)>0  order by month_week asc");
		$weeks='';$dueamts='';
		while($get_monweekprw=$obj_db->fetchArray($get_monweekpayments)){
		 if($pay_fee>=$get_monweekprw['monthlydue_amt'])
		 {
		   $pay_fee=$pay_fee-$get_monweekprw['monthlydue_amt'];
		    $wk_monpay=$get_monweekprw['monthlydue_amt'];
		    $weeks.=$get_monweekprw['month_week'].',';
			$dueamts.=$get_monweekprw['monthlydue_amt'].',';
		 }
		 elseif($pay_fee>0){
		 $wk_monpay=$pay_fee;
		    $weeks.=$get_monweekprw['month_week'].',';
			$dueamts.=$pay_fee.',';
			$pay_fee=0;
		 }
		$upd_dueamts=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." set monthlydue_amt=monthlydue_amt-'".$wk_monpay."' where customer_id='".$data['customer_id']."' and borrow_id='".$data['borrow_id'][$i]."' and year_id='".$_SESSION['year_id']."' and month_week='".$get_monweekprw['month_week']."'");
		$wk_monpay=0;
		}
		$substr_weeks=substr($weeks,0,-1);
		$substr_dueamts=substr($dueamts,0,-1);
					   
		$cust_payment=INSERT_KEYWORD."   ".TABLE_CUST_PAYMENTS." SET 
	                               			customer_id='".$obj_db->real_escape_string($data['customer_id'])."', 
											borrow_id='".$obj_db->real_escape_string($data['borrow_id'][$i])."',
											line_id='".$obj_db->real_escape_string($partybranh_dts['line_id'])."',
											enter_by='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											user_id='".$obj_db->real_escape_string($seluserid)."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."', 
										    is_paid_amount='".$obj_db->real_escape_string($data['customer_pays'][$i])."',
											paid_date='".$pay_dt."',
											enter_date='".date('Y-m-d H:i:s')."',
											paid_week_months='".$substr_weeks."',
											paid_amts='".$substr_dueamts."',
											receipt_no='".$obj_db->real_escape_string($receipt_no_tf)."',
	                               			pay_type_id='".$obj_db->real_escape_string($data['payment_type'])."',
											payto_user_id='".$obj_db->real_escape_string($data['upayuser_id'])."', 
											received_bank_id='".$obj_db->real_escape_string($data['received_bank_id'])."',
											bank_name='".$obj_db->real_escape_string($data['bank_name'])."',
											cheque_num='".$obj_db->real_escape_string($data['cheque_num'])."',
											cheque_date='".$obj_db->real_escape_string($_SESSION['cheque_date'])."'";
							$cuspay_updres=$obj_db->get_qresult($cust_payment);
					$paidref_id=$obj_db->insert_id();
				if($paidgen_inc==0) {
			   $commrpidefid1=$paidref_id;
		       $paidgen_inc++;
               }

			    $ref_id=$obj_db->insert_id();
             
			 $comfidarr[]=$paidref_id;
			 $comfamtsarr[]=$data['customer_pays'][$i];
		}
        }   
		
		//($chk_dtwise_numdts['date_time']<$pdt || $chk_dtwise_numdts['date_time']=='')
		/* if($chk_dtwise_numdts['date_time']=='' && !$chk_dtwise_lineamtdts){
			
			   
			   $colc_amt=$get_givdt_curbals['line_collect_amts']+$tpaid;
			   $rembal=($chk_dtwise_numdts['line_remain_bal']+$get_givdt_curbals['line_taken_amt']+$colc_amt)-($get_givdt_curbals['line_given_amts']+$get_givdt_curbals['line_expense_amt']);
			    $insr_dtwseusrtakamt=INSERT_KEYWORD."   ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
			                                line_open_bal='".$obj_db->real_escape_string($chk_dtwise_numdts['line_remain_bal'])."', 
 											line_collect_amts='".$obj_db->real_escape_string($colc_amt)."',
											line_remain_bal='".$rembal."',
											user_id='".$_SESSION['user_id']."',
											line_id='".$obj_db->real_escape_string($partybranh_dts['line_id'])."',
											date_time='".$pdt."'";   
			   $res=$obj_db->get_qresult($insr_dtwseusrtakamt);
			   }
			  else if($chk_dtwise_lineamtdts){
			    //$chk_dtwise_numdts['date_time']<=$pdt && 
			   $colc_amt=$get_givdt_curbals['line_collect_amts']+$tpaid;
			   $rembal=($get_givdt_curbals['line_open_bal']+$get_givdt_curbals['line_taken_amt']+$colc_amt)-($get_givdt_curbals['line_given_amts']+$get_givdt_curbals['line_expense_amt']);
			    $upd_dtwseusrtakamt=UPDATE_KEYWORD."    ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
 											line_collect_amts='".$obj_db->real_escape_string($colc_amt)."',
											line_remain_bal='".$rembal."' where 
											line_id='".$obj_db->real_escape_string($partybranh_dts['line_id'])."' and 
											date_time='".$pdt."'";   
			   $res=$obj_db->get_qresult($upd_dtwseusrtakamt);
			   
		   }*/


			   foreach($_SESSION['linematch_users'][$partybranh_dts['line_id']] as $usrky=>$usrv){
           foreach($exppaytypes as $ptypky=>$ptypv){
		     $chk_empusrrecordingivdate=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where  user_id='".$usrv['user_id']."' and line_id='".$partybranh_dts['line_id']."' and pay_type_id='".$ptypky."' and date(date_time)='".date('Y-m-d',strtotime($pay_dt))."'");
								if(!$chk_empusrrecordingivdate){
									$insrempusr_givdtrecord=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
											user_id='".$usrv['user_id']."',
											pay_type_id='".$ptypky."',
											line_id='".$partybranh_dts['line_id']."',
										   date_time='".$pay_dt."'");
										   
								}
								}
						}
 	//	foreach($ptypidamtdtsarr as $ptypky=>$ptypv){
		//	if($ptypv>0){				
         $updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_collect_amts=line_collect_amts+'".array_sum($data['customer_pays'])."' where user_id='".$ptypuser_payid."' AND pay_type_id='".$data['payment_type']."' and line_id='".$partybranh_dts['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($pay_dt))."' ");
		   $getuserlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_collect_amts=line_collect_amts+'".array_sum($data['customer_pays'])."' where user_id='".$ptypuser_payid."' and pay_type_id='".$data['payment_type']."' and line_id='".$partybranh_dts['line_id']."' ");
	//	}	
	//}		



 /* $chk_empusrrecordingivdate=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where  user_id='".$seluserid."' and line_id='".$partybranh_dts['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($pay_dt))."'");
								if(!$chk_empusrrecordingivdate){
									$insrempusr_givdtrecord=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
											user_id='".$seluserid."',
											line_id='".$partybranh_dts['line_id']."',
										   date_time='".$pay_dt."'");
								}

		  	$updlinecolcamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_collect_amts=line_collect_amts+'".$tpaid."' where user_id='".$seluserid."' and line_id='".$partybranh_dts['line_id']."' and date(date_time)='".$pay_dt."' ");
		//}
*/

		$usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($tpaid)."'
											 where user_id='".$ptypuser_payid."' and pay_type_id='".$data['payment_type']."'");
 		$upd_refid=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUST_PAYMENTS." set ref_id='".$comfidarr[0]."' where customer_id='".$data['customer_id']."' and id in(0".implode(',',$comfidarr).") ");
					  
        $get_maxgen_id=$obj_db->fetchRow("select max(gen_id) as rec_id from ".TABLE_CUST_PAYMENTS." where year_id='".$_SESSION['year_id']."'");  
		$gen_id=$get_maxgen_id['rec_id']+1;
 	      $genr='REC/'.$partybranh_dts['line_id'].'/'.$_SESSION['year_id'].'/'.$gen_id;	
		$upd_refid=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUST_PAYMENTS." set receipt_no='".$genr."',gen_id='".$gen_id."' where ref_id='".$commrpidefid1."' and customer_id='".$data['customer_id']."'");
		
			 	
	$std_nameqry=$obj_db->fetchRow("SELECT * from ".TABLE_CUSTOMER_DTS." where customer_id='".$data['customer_id']."'");
       $msg="Dear  ".$std_nameqry['party_name']." Thank you for paying ".$branch_row['branch_name'].", Your Paid Amount is : ".$tpaid." Remaining Balance Rs-".$rembal['rbal'];

//sendsms($std_nameqry['mobile_no'],$msg,SMS_ENGURL,SMS_USERNAME,SMS_PWORD,SENDERID);
unset($_SESSION['form_token']);
	  redirect_page("home.php?p=accountswise_rep");
			}
	/*-----End   Ricepurchase feepay-----*/				

}
		?>