<?php 
  class fee_operations{
  
    				
	function get_fee_struct($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			$pwrand = rand(10000,99999);
			if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}
			//	print_r($data);
             $partybranh_dts=$obj_db->fetchRow("select line_id from ".TABLE_CUSTOMER_DTS." where customer_id='".$data['customer_id']."'");
		    for($i=0;$i<sizeof($data['concession']);$i++){
			if($data['concession'][$i]>0){
			  
			 $paidamount=$data['concession'][$i];			 
			 
			 
			    $custfee_updates=UPDATE_KEYWORD."  ".TABLE_CUSTOMER_GENPAYMENTS." set remain_balance=(remain_balance-'$paidamount'),tot_amt_updconces=tot_amt_updconces-'".$paidamount."' where customer_id='".$data['customer_id']."' and borrow_id='".$data['borrow_id'][$i]."' and year_id='".$_SESSION['year_id']."'";
		               $fee_updres=$obj_db->get_qresult($custfee_updates);
		$get_monweekpayments=$obj_db->get_qresult("select borrow_id,monthlydue_amt,month_week from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." where customer_id='".$data['customer_id']."' and borrow_id='".$data['borrow_id'][$i]."' and year_id='".$_SESSION['year_id']."' group by month_week having sum(monthlydue_amt)>0  order by month_week asc");
		$weeks='';$dueamts='';
		while($get_monweekprw=$obj_db->fetchArray($get_monweekpayments)){
		 if($paidamount>=$get_monweekprw['monthlydue_amt'])
		 {
		   $paidamount=$paidamount-$get_monweekprw['monthlydue_amt'];
		    $wk_monpay=$get_monweekprw['monthlydue_amt'];
		    $weeks.=$get_monweekprw['month_week'].',';
			$dueamts.=$get_monweekprw['monthlydue_amt'].',';
		 }
		 elseif($paidamount>0){
		 $wk_monpay=$paidamount;
		    $weeks.=$get_monweekprw['month_week'].',';
			$dueamts.=$paidamount.',';
			$paidamount=0;
		 }
		$upd_dueamts=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." set monthlydue_amt=monthlydue_amt-'".$wk_monpay."' where customer_id='".$data['customer_id']."' and borrow_id='".$data['borrow_id'][$i]."' and year_id='".$_SESSION['year_id']."' and month_week='".$get_monweekprw['month_week']."'");
		$wk_monpay=0;
		}
		$substr_weeks=substr($weeks,0,-1);
		$substr_dueamts=substr($dueamts,0,-1);
			 
			 
  
			    $std_fee_conces_query=INSERT_KEYWORD."   ".TABLE_CUST_CONCESSION." SET 
			                                customer_id='".$obj_db->real_escape_string($data['customer_id'])."',
											line_id='".$obj_db->real_escape_string($partybranh_dts['line_id'])."',
											cones_amt='".$obj_db->real_escape_string($data['concession'][$i])."', 
											borrow_id='".$obj_db->real_escape_string($data['borrow_id'][$i])."',
											user_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											paid_week_months='".$substr_weeks."',
											paid_amts='".$substr_dueamts."',
											reason='".$obj_db->real_escape_string($data['conce_reason'])."',
											conces_date='".date('Y-m-d H:i:s')."'";
											 
			   $res=$obj_db->get_qresult($std_fee_conces_query);
			 }
			  }
	unset($_SESSION['form_token']);
		  redirect_page('home.php?p=success_pg&sts=conce&locationpg=cust_hist&customer_id='.$data['customer_id']);
			}
			
			


			 function get_fee_rollback($cust_id,$amt,$concid){
	    global $obj_db, $page_url;
		 
	   $paidamt=0;
	 $get_recp_amount_rows=$obj_db->fetchRow("select * from ".TABLE_CUST_CONCESSION." where conces_id='".$concid."'");	 
		 $exp_payweeks=explode(',',$get_recp_amount_rows['paid_week_months']);
		 $exp_payamts=explode(',',$get_recp_amount_rows['paid_amts']);
		 for($i=0;$i<count($exp_payweeks);$i++){
	  	 $update_onlystd_fee=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." set monthlydue_amt=monthlydue_amt+'".$exp_payamts[$i]."' where customer_id='".$cust_id."' and borrow_id='".$get_recp_amount_rows['borrow_id']."' and month_week='".$exp_payweeks[$i]."'");
	     }
	  
	    $custfee_updates=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_GENPAYMENTS." set remain_balance=remain_balance+'".$amt."',tot_amt_updconces=tot_amt_updconces+'".$amt."' where customer_id='".$cust_id."' and borrow_id='".$get_recp_amount_rows['borrow_id']."' and year_id='".$_SESSION['year_id']."'");
					   
		$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUST_CONCESSION." set is_cancel=1,cancel_by='".$_SESSION['user_id']."',cancel_date='".date('Y-m-d H:i:s')."' where conces_id='".$concid."'");
	
		redirect_page($page_url."&customer_id=".$cust_id);
	 }

  }
  ?>