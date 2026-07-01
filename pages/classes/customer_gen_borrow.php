<?php class customer_genborrow_operations{

     	
		
   /*--------person_general_borrows.php-----------*/
   function genborrow_save($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
			  /*---Customer Borrow----*/
               
			  if($data['sel_user_id']!='')
          $seluserid=$data['sel_user_id'];
	    else $seluserid=$_SESSION['user_id'];

		if ($_POST['form_token'] != $_SESSION['form_token']) {
    redirect_page($page_url);
			}

 $linusrwiseptypdtsarr=array();
$paytypdtsarr=array(1=>"cash",2=>"Upi");
	 foreach($data['linusrs'] as $linusrky=>$linusrv){
        foreach($paytypdtsarr as $ptypky=>$ptypv){
            if($data['usrptyp_'.$linusrv.'_'.$ptypky]>0)
				$linusrwiseptypdtsarr[]=array('user_id'=>$linusrv,'pay_type_id'=>$ptypky,'pamt'=>$data['usrptyp_'.$linusrv.'_'.$ptypky]);
		}
	 }
 //echo '<pre>';print_r($data);print_r($linusrwiseptypdtsarr);echo '</pre>';
	 $cptyp=1;$uptyp=2;
	 $clinusrwiseptypdtsarrs = array_filter($linusrwiseptypdtsarr,function($v,$k) use ($cptyp){
								return $v['pay_type_id'] == $cptyp;
							  },ARRAY_FILTER_USE_BOTH);

	$ulinusrwiseptypdtsarrs = array_filter($linusrwiseptypdtsarr,function($v,$k) use ($uptyp){
								return $v['pay_type_id'] == $uptyp;
							  },ARRAY_FILTER_USE_BOTH);
	
	 $ptypamtdtsarrjson=json_encode(array(1=>array_sum(array_column($clinusrwiseptypdtsarrs,'pamt')),2=>array_sum(array_column($ulinusrwiseptypdtsarrs,'pamt'))));


			  $ptypidamtdtsarr=array(1=>$data['cashpay'],2=>$data['upipay']);
			  $ptypidamtusrdts=array(1=>$seluserid,2=>$data['upayuser_id']);


			  $chk_dtwise_numdts=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' order by date(date_time) desc limit 1");	 
		  $chk_dtwise_lineamtdts=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
		  $get_givdt_curbals=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$data['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
			  
			  
	if($data['taken_date']!='')
	 $curdat=date('Y-m-d',strtotime($data['taken_date']));
	else
	$curdat=date('Y-m-d');
	if($data['pay_type']==1){
	 $nodays=7;
	// $curdat=strtotime($curdat);
	$due_date=date('d-m-Y', strtotime($curdat. ' + '.$nodays.' day'));
	
	//strtotime($date);
   //$date = strtotime("+7 day", $date);
	 }
	else{
	 $nodays=30;
	 $exp_curdt=explode('-',$curdat);
	  $due_dat = mktime(0,0,0,$exp_curdt[1]+1,1,$exp_curdt[2]);
	 $due_date=date('d-m-Y',$due_dat);
	 }

	 
	 $exppaytypes=array(1=>"Cash",2=>"Upi");

	 $tekenamount_with_documentcharge=$data['tekenamount_without_documentcharge']+$data['document_charge'];
	 $intesrt_amt=$data['payble_amount_withintrest']-$tekenamount_with_documentcharge;

    $weelymalupayblamtarr=array();
	 if($data['isset_manualweeks']==1)
	 {		
		$weelymalupayblamtarr[1]=$data['monpaybelamt_1'];
		for($i=2;$i<=$data['no_months'];$i++){  
			$weelymalupayblamtarr[$i]=$data['monpaybelamt_2'];
		}
	 }else{
	  $mons_payamt=(int)($data['payble_amount_withintrest']/$data['no_months']);
	  for($i=1;$i<=$data['no_months'];$i++){  
			$weelymalupayblamtarr[$i]=$mons_payamt;
		}
	//  $weelymalupayblamtarr[1]=$mons_payamt;
	 }

	$mon_payamt=max($weelymalupayblamtarr);
	  $interest_per=(($intesrt_amt/$data['payble_amount_withintrest'])*100);
	$tot_monamt=0;
 	 $gen_payment=INSERT_KEYWORD."   ".TABLE_CUSTOMER_GENPAYMENTS."  SET 
											customer_id='".$obj_db->real_escape_string($data['customer_id'])."',
											borrow_name='".$obj_db->real_escape_string($data['borrow_name'])."',
											witness_name='".$obj_db->real_escape_string($data['witness_name'])."',
											witness_mobile='".$obj_db->real_escape_string($data['witness_mobile'])."',
											taken_amount='".$obj_db->real_escape_string($tekenamount_with_documentcharge)."',
											pay_type_amtjsondts='".$ptypamtdtsarrjson."',
											tekenamount_without_documentcharge='".$obj_db->real_escape_string($data['tekenamount_without_documentcharge'])."',
											document_charge='".$obj_db->real_escape_string($data['document_charge'])."',
											interest_amount='".$obj_db->real_escape_string($intesrt_amt)."',
											interest_per='".$obj_db->real_escape_string($interest_per)."',
											no_months='".$obj_db->real_escape_string($data['no_months'])."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											user_id='".$obj_db->real_escape_string($seluserid)."',
											enter_by='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											pay_type='".$obj_db->real_escape_string($data['pay_type'])."',
											weekly_monthly_paybleamt='".$obj_db->real_escape_string($mon_payamt)."',
											taken_date='".$obj_db->real_escape_string($curdat)."',
											enter_date='".date('Y-m-d H:i:s')."',
											remain_balance='".$data['payble_amount_withintrest']."',
											total_amount='".$data['payble_amount_withintrest']."',
											tot_amt_updconces='".$data['payble_amount_withintrest']."'";  
			  $res=$obj_db->get_qresult($gen_payment);
			  $borrowid=$obj_db->insert_id();

			  $totgenpamts=0;
			   foreach($linusrwiseptypdtsarr as $ptyky=>$ptypamt){
				if($ptypamt['pamt']>0){
					$totgenpamts=$totgenpamts+$ptypamt['pamt'];
				 $map_persons=$obj_db->get_qresult(INSERT_KEYWORD."   ".TABLE_CUSTOMER_GENPAYMENTS_PAYTYPES."  SET 
											customer_id='".$obj_db->real_escape_string($data['customer_id'])."',
											borrow_id='".$obj_db->real_escape_string($borrowid)."',
											pay_type_id	='".$ptypamt['pay_type_id']."',
											amount='".$ptypamt['pamt']."',
											user_id='".$obj_db->real_escape_string($ptypamt['user_id'])."',
 											taken_date='".$curdat."',
											enter_date='".date('Y-m-d H:i:s')."'"); 

				$usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance-'".$obj_db->real_escape_string($ptypamt['pamt'])."'
											 where user_id='".$ptypamt['user_id']."' and pay_type_id='".$ptypamt['pay_type_id']."'");
				}
			  }

 /* for($i=1;$i<=$data['no_months'];$i++){  
  $tot_monamt=$tot_monamt+$mon_payamt;
   $map_persons=INSERT_KEYWORD."   ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS."  SET 
											customer_id='".$obj_db->real_escape_string($data['customer_id'])."',
											borrow_id='".$obj_db->real_escape_string($borrowid)."',
											monthly_amt	='".$mon_payamt."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											monthlydue_amt	='".$mon_payamt."',
											no_months='".$obj_db->real_escape_string($data['no_months'])."',
											month_week='".$i."',
											due_date='".$due_date."'";  
			  $res=$obj_db->get_qresult($map_persons);	
			  $lstid=$obj_db->insert_id();
			  $exp_duedt=explode('-',$due_date);
			  if($data['pay_type']==1)
			  $due_date=date('d-m-Y', strtotime($due_date. ' + '.$nodays.' day'));
			  else{
			  $due_dat = mktime(0,0,0,$exp_duedt[1]+1,1,$exp_duedt[2]);
			  $due_date=date('d-m-Y',$due_dat);
			  }
		}	
		 $diff_amt=$data['payble_amount_withintrest']-$tot_monamt;
		 $upd_lastmonamt=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." set monthly_amt=monthly_amt+'".$diff_amt."',monthlydue_amt=monthlydue_amt+'".$diff_amt."' where id='".$lstid."'");			*/ 
	
	 $custweekpaybleamtarr=array();
   for($i=1;$i<=$data['no_months'];$i++){  
    $tot_monamt=$tot_monamt+$weelymalupayblamtarr[$i];
  
     $custweekpaybleamtarr[] = "( '".$data['customer_id']."',
					 '".$obj_db->real_escape_string($borrowid)."',
					 '".$obj_db->real_escape_string($weelymalupayblamtarr[$i])."',
					 '".$_SESSION['year_id']."',
					 '".$weelymalupayblamtarr[$i]."',
					 '".$data['no_months']."',
					 '".$i."',
					 '".$due_date."')";
	

  /* $map_persons=INSERT_KEYWORD."   ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS."  SET 
											customer_id='".$obj_db->real_escape_string($id)."',
											borrow_id='".$obj_db->real_escape_string($borrowid)."',
											monthly_amt	='".$mon_payamt."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											monthlydue_amt	='".$mon_payamt."',
											no_months='".$obj_db->real_escape_string($data['no_months'])."',
											month_week='".$i."',
											due_date='".$due_date."'";  
			  $res=$obj_db->get_qresult($map_persons);	
			  $lstid=$obj_db->insert_id();*/
			  $exp_duedt=explode('-',$due_date);
			  if($data['pay_type']==1)
			  $due_date=date('d-m-Y', strtotime($due_date. ' + '.$nodays.' day'));
			  else{
			  $due_dat = mktime(0,0,0,$exp_duedt[1]+1,1,$exp_duedt[2]);
			  $due_date=date('d-m-Y',$due_dat);
			  }
		}	
  $stdtsinsr_statement=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." (customer_id,borrow_id,monthly_amt,year_id,monthlydue_amt,no_months,month_week,due_date) values ".implode(',',$custweekpaybleamtarr));

		 $diff_amt=$data['payble_amount_withintrest']-$tot_monamt;
		 $upd_lastmonamt=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." set monthly_amt=monthly_amt+'".$diff_amt."',monthlydue_amt=monthlydue_amt+'".$diff_amt."' where borrow_id='".$obj_db->real_escape_string($borrowid)."' and month_week='".$data['no_months']."'");
	
	
		 //($chk_dtwise_numdts['date_time']<$pdt || $chk_dtwise_numdts['date_time']=='')   
		/*	if($chk_dtwise_numdts['date_time']=='' && !$chk_dtwise_lineamtdts){
			
			   $giv_amt=$get_givdt_curbals['line_given_amts']+$data['taken_amount'];
			   $rembal=($chk_dtwise_numdts['line_remain_bal']+$get_givdt_curbals['line_taken_amt']+$get_givdt_curbals['line_collect_amts'])-($giv_amt+$get_givdt_curbals['line_expense_amt']);
			    $insr_dtwseusrtakamt=INSERT_KEYWORD."   ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
			                                line_open_bal='".$obj_db->real_escape_string($chk_dtwise_numdts['line_remain_bal'])."', 
 											line_given_amts='".$obj_db->real_escape_string($giv_amt)."',
											line_remain_bal='".$rembal."',
											user_id='".$seluserid."',
											line_id='".$obj_db->real_escape_string($data['line_id'])."',
											date_time='".$pdt."'";   
			   $res=$obj_db->get_qresult($insr_dtwseusrtakamt);
			   }
			  else if($chk_dtwise_lineamtdts){
			    //$chk_dtwise_numdts['date_time']<=$pdt && 
			   $giv_amt=$get_givdt_curbals['line_given_amts']+$data['taken_amount'];
			   $rembal=($get_givdt_curbals['line_open_bal']+$get_givdt_curbals['line_taken_amt']+$get_givdt_curbals['line_collect_amts'])-($giv_amt+$get_givdt_curbals['line_expense_amt']);
			    $upd_dtwseusrtakamt=UPDATE_KEYWORD."    ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
 											line_given_amts='".$obj_db->real_escape_string($giv_amt)."',
											line_remain_bal='".$rembal."' where 
											line_id='".$obj_db->real_escape_string($data['line_id'])."' and 
											date_time='".$pdt."'";   
			   $res=$obj_db->get_qresult($upd_dtwseusrtakamt);
			   
		   }  */

 $userlinearrdts=array();
$getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$data['line_id']."', assign_line_ids) > 0 and user_status=1");
                foreach($getline_matchusrids as $mtchusrky=>$matchusrv)  
				$userlinearrdts[$data['line_id']][]=array('user_id'=>$matchusrv['user_id'],'full_name'=>$matchusrv['full_name']);
			 
				
  		  foreach($userlinearrdts[$data['line_id']] as $usrky=>$usrv){
           foreach($exppaytypes as $ptypky=>$ptypv){
		     $chk_empusrrecordingivdate=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where  user_id='".$usrv['user_id']."' and line_id='".$data['line_id']."' and pay_type_id='".$ptypky."' and date(date_time)='".date('Y-m-d',strtotime($curdat))."'");
								if(!$chk_empusrrecordingivdate){
									$insrempusr_givdtrecord=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_LINEWISE_DATEWISE_AMTS." SET 
											user_id='".$usrv['user_id']."',
											pay_type_id='".$ptypky."',
											line_id='".$data['line_id']."',
										   date_time='".$curdat."'");
										   
								}
								}
						}
 		foreach($linusrwiseptypdtsarr as $ptypky=>$ptypv){
			if($ptypv['pamt']>0){				
         $updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_given_amts=line_given_amts+'".$ptypv['pamt']."' where user_id='".$ptypv['user_id']."' AND pay_type_id='".$ptypv['pay_type_id']."' and line_id='".$data['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($curdat))."' ");
		   $getuserlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_given_amts=line_given_amts+'".$ptypv['pamt']."' where user_id='".$ptypv['user_id']."' and pay_type_id='".$ptypv['pay_type_id']."' and line_id='".$data['line_id']."' ");
		}	
	}
			  /*---Customer Borrow----*/
		 
		 
			//   }
			unset($_SESSION['form_token']);
			 unset($data);
			// echo $page_url;
		  redirect_page($page_url);
			}
			
	function delete_genborrow($id){
	 global $obj_db, $page_url;
	 $get_genborrow=$obj_db->fetchRow("select *,date(taken_date) as amgdt from  ".TABLE_CUSTOMER_GENPAYMENTS."   where borrow_id='".$id."'");
	 if($get_genborrow['is_delete']==0){
		$custdts=$obj_db->fetchRow("select * from ".TABLE_CUSTOMER_DTS." where customer_id='".$get_genborrow['customer_id']."'");
	 $pdt=$get_genborrow['amgdt'];
	 $tgiv=$get_genborrow['taken_amount'];
	 $line_id=$get_genborrow['line_id'];
	 $user_id=$get_genborrow['user_id'];
	 $ptypjsondts=json_decode($get_genborrow['pay_type_amtjsondts'],true);
	 $del_genborrow=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_CUSTOMER_GENPAYMENTS." set is_delete='1',cancel_by='".$_SESSION['user_id']."',cancel_date='".date('Y-m-d H:i:s')."' where borrow_id='".$id."'");

	 $getcustgnptypamts=$obj_db->qry("select * from  ".TABLE_CUSTOMER_GENPAYMENTS_PAYTYPES." where borrow_id='".$id."' and is_cancel=0");
	 $updcustgnpays=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_CUSTOMER_GENPAYMENTS_PAYTYPES." set is_cancel='1' where borrow_id='".$id."'");
	 
	 $del_genborrow=$obj_db->get_qresult(DELETE_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." where borrow_id='".$id."'");
	 //$delcust_ptypamts=$obj_db->get_qresult(DELETE_KEYWORD."  ".TABLE_CUSTOMER_GENPAYMENTS_PAYTYPES." where borrow_id='".$id."'");
	  
	// $get_givdt_curbals=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$line_id."' and date(date_time)='".$pdt."'");
					 $giv_amt=$get_givdt_curbals['line_given_amts']-$tgiv;
			   $rembal=($get_givdt_curbals['line_open_bal']+$get_givdt_curbals['line_taken_amt']+$get_givdt_curbals['line_collect_amts'])-($giv_amt+$get_givdt_curbals['line_expense_amt']);
			   
			   foreach($getcustgnptypamts as $ptypky=>$ptypv){
				
                $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($ptypv['amount'])."'
											 where user_id='".$ptypv['user_id']."' and pay_type_id='".$ptypv['pay_type_id']."'");
										 
  $updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_given_amts=line_given_amts-'".$ptypv['amount']."' where user_id='".$ptypv['user_id']."' and pay_type_id='".$ptypv['pay_type_id']."' and line_id='".$custdts['line_id']."' and date(date_time)='".$pdt."' ");
  $upduserlineamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_given_amts=line_given_amts-'".$ptypv['amount']."' where user_id='".$ptypv['user_id']."' and pay_type_id='".$ptypv['pay_type_id']."' and line_id='".$custdts['line_id']."'  ");
 			   }
			
		
		}
	 redirect_page($page_url);
	}
   /*--------person_general_borrows.php-----------*/		
		}
		?>