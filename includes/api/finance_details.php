<?php session_start();
include "../DbConfig.php";
  if($_REQUEST['action']=="line_dts"){
    if($_SESSION['user_type']=='admin')
	 $cndvar="";
	else $cndvar=" where line_id in(".$_SESSION['assign_line_ids'].") ";
      $branch_qrys=$obj_db->qry("select line_name,line_id from ".TABLE_LINE_NAMES." $cndvar  order by line_name asc");
	   echo json_encode($branch_qrys);
  }
 elseif($_REQUEST['action']=="monthly_collection"){
  $branchname =  $obj_db->fetchRow("select branch_name,branch_short_name,branch_city from  ".TABLE_BRANCH." where  branch_id=".$_REQUEST['branch_id']."")	;	
 
 $date_mont=$_REQUEST['yearid'].'-'.$_REQUEST['month'];
  $give_mon=$_REQUEST['yearid'].'-'.$_REQUEST['month']."-"."01";
  $monname=date("F", strtotime($give_mon));
  $title= $monname." Month -".$branchname['branch_name'];
  $giv_mondays=cal_days_in_month(CAL_GREGORIAN, $_REQUEST['month'], $_REQUEST['yearid']);
 
 $monthly_names=$obj_db->get_qresult("select a.fee_id,a.fee_name from ".TABLE_FEE_TYPE." a,".TABLE_STUDENT_FEE." b,".TABLE_STUDENT_EDU_DETAILS." c where b.y_id='".$_SESSION['year_id']."' and c.branch_id='".$_REQUEST['branch_id']."' and b.y_id=c.y_id   and a.fee_id=b.fee_type group by b.fee_type order by fee_id");
 $s=0;$p=0;$i=0;
 while($monthly_namerw=$obj_db->fetchArray($monthly_names)){
 $fetch_data[$s][$p]=$monthly_namerw['fee_name'];
 $fee_id[$i]=$monthly_namerw['fee_id'];
 $p++;$i++;
 }
 $tot_fields=$p;
  $s=1;$p=0;
  for($d=1;$d<=$giv_mondays;$d++) {
  $fetch_data[$s][0]=$d;
 $tot_fine=0;
			$tot_sum=0;$c=1;
			for($k=0;$k<sizeof($fee_id);$k++){

		 	$rsgetdue=   $obj_db->fetchRow("select sum(is_paid_amount) as paid_amount from ".TABLE_FEE_PAYMENT." where  
			branch_id=".$_REQUEST['branch_id']." and date(paid_date)='$date_mont-$d'  and fee_id='".$fee_id[$k]."' and receipt_cancelled=0 group by fee_id order by fee_id");
			
			$fetch_data[$s][$c]=$rsgetdue['paid_amount'];
			
			$tot_sum=$tot_sum+$rsgetdue['paid_amount'];
		$sum[$k]=$sum[$k]+$rsgetdue['paid_amount'];
		$tot_fine=$rsgetdue['std_fine']+$tot_fine;
		$sums[$p][$k]=$sums[$p][$k]+$rsgetdue['paid_amount'];
		 $c++;
			}
			$c=$c+1;
			$fetch_data[$s][$c]=$tot_sum;
			$s++;$p++;
		}
		$fetch_data[$s][0]='Total';
		 for($j=0;$j<sizeof($sums[1]);$j++){
	 for($i=0;$i<sizeof($sums);$i++)
 		{ $subtotal[$j]=$subtotal[$j]+$sums[$i][$j]; }
		}
		$grand_sum=0;
		$c=1;
 for($j=0;$j<sizeof($subtotal);$j++)
	{	
	  $grand_sum=$grand_sum+$subtotal[$j];
	  $fetch_data[$s][$c]=number_format($subtotal[$j]);
	  $c++;
	 }
	 $c=$c+1;
	  $fetch_data[$s][$c]=number_format($grand_sum,2);
	  
	   $field_array=array("Feedata"=>$fetch_data,"extitle"=>$title,"Totfields"=>$tot_fields);
	echo json_encode($field_array);

 }
 elseif($_REQUEST['action']=='cancel_cust_billbpay'){
	$get_paymentdts=$obj_db->qry("select customer_id,paid_week_months,paid_amts,borrow_id,is_paid_amount,date(paid_date) as pdt,line_id,pay_type_id,user_id,payto_user_id from ".TABLE_CUST_PAYMENTS." where is_delete=0 and id='".$_REQUEST['fpid']."'");
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
					$updcusttotpaids=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_CUSTOMER_DTS." set tot_custpaids=tot_custpaids-'".$tpaid."' where customer_id='".$get_paymentdts[0]['customer_id']."'");
					$upd_cancelsts=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_CUST_PAYMENTS." set is_delete=1,cancel_date='".date('Y-m-d H:i:s')."',cancel_by='".$_SESSION['user_id']."' where id='".$_REQUEST['fpid']."'");
					
						$updlinecolcamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_collect_amts=line_collect_amts-'".$tpaid."' where user_id='".$get_paymentdts[0]['payto_user_id']."' and pay_type_id='".$get_paymentdts[0]['pay_type_id']."' and line_id='".$get_paymentdts[0]['line_id']."' and date(date_time)='".$get_paymentdts[0]['pdt']."' ");
						$updusrlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_collect_amts=line_collect_amts-'".$tpaid."',line_updremain_bal=line_updremain_bal-'".$tpaid."' where user_id='".$get_paymentdts[0]['payto_user_id']."' and pay_type_id='".$get_paymentdts[0]['pay_type_id']."' and line_id='".$get_paymentdts[0]['line_id']."'  ");

			   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance-'".$obj_db->real_escape_string($tpaid)."'
											 where user_id='".$get_paymentdts[0]['payto_user_id']."' and pay_type_id='".$get_paymentdts[0]['pay_type_id']."'");
			   }
			   echo 'success';
 }
 
 elseif($_REQUEST['action']=="monthly_headwiseexpense"){
  $branchname =  $obj_db->fetchRow("select branch_name,branch_short_name,branch_city from  ".TABLE_BRANCH." where  branch_id=".$_REQUEST['branch_id']."")	;	
 
 $date_mont=$_REQUEST['yearid'].'-'.$_REQUEST['month'];
  $give_mon=$_REQUEST['yearid'].'-'.$_REQUEST['month']."-"."01";
  $monname=date("F", strtotime($give_mon));
  $title= $monname." Month -".$branchname['branch_name'];
  $giv_mondays=cal_days_in_month(CAL_GREGORIAN, $_REQUEST['month'], $_REQUEST['yearid']);
 
 $fee_typeqry=$obj_db->get_qresult("select a.exp_type_id,a.exp_name from ".TABLE_EXPENDITURE_TYPE." a,".TABLE_EXP_CATEGORYS_MAP." b,".TABLE_EXPENDITURE_CATEGORY." c where a.exp_type_id=b.exp_type_id and b.exp_catg_id=c.exp_catg_id and c.exp_catg_shortname='HOST' order by a.exp_type_id asc");
 $s=0;$p=0;$i=0;
 for($d=1;$d<=$giv_mondays;$d++) {
  $fetch_data[$s][$i]=$d;
 
 $p++;$i++;
 }
 $tot_fields=$p;
  $s=1;
  while($fee_typeqrw=$obj_db->fetchArray($fee_typeqry)) {
  $fetch_data[$s][0]=$fee_typeqrw['exp_name'];
 $tot_fine=0;
			$tot_sum=0;$k=0;
			for($d=1;$d<=$giv_mondays;$d++) {
			$rsgetdue=   $obj_db->fetchRow("select sum(amount) as amount from ".TABLE_EXPENDITURE." where  
			branch_id=".$_REQUEST['branch_id']." and date(enter_date)='$date_mont-$d'  and exp_type_id='".$fee_typeqrw['exp_type_id']."' and is_cancel=0 ");
			
			$fetch_data[$s][$d]=$rsgetdue['amount'];
			$tot_sum=$tot_sum+$rsgetdue['amount'];
		$sum[$k]=$sum[$k]+$rsgetdue['amount'];
		$sums[$s][$k]=$sums[$s][$k]+$rsgetdue['amount'];
		 $k++;
			}
			$d=$d+1;
			$fetch_data[$s][$d]=$tot_sum;
			$s++;
		}
		
		$s=$s+1;
		$fetch_data[$s][0]='Total';
		 for($j=0;$j<$giv_mondays;$j++){
	 for($i=0;$i<=sizeof($sums);$i++)
 		{ $subtotal[$j]=$subtotal[$j]+$sums[$i][$j]; }
		}
		$grand_sum=0;
		$c=1;
 for($j=0;$j<sizeof($subtotal);$j++)
	{	
	  $grand_sum=$grand_sum+$subtotal[$j];
	  $fetch_data[$s][$c]=number_format($subtotal[$j]);
	  $c++;
	 }
	 $c=$c+1;
	  $fetch_data[$s][$c]=number_format($grand_sum,2);
	  
	   $field_array=array("hostexpdts"=>$fetch_data,"extitle"=>$title,"Totfields"=>$tot_fields);
	echo json_encode($field_array);

 }
 elseif($_REQUEST['action']=="opcashierwise_reprot"){
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= $split_dt[0];
 if($split_dt[1]=='')
 $dt2= date('d-m-Y');
else $dt2=$split_dt[1];
 if($_REQUEST['dat_range']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }
 $dt1=$_REQUEST['frm_date'];$dt2=$_REQUEST['to_date'];
  //$tdat=date('d-m-Y');
  $tdat=$dt1;
   if($_REQUEST['line_id']==0 && ($_SESSION['user_type']=='admin'  || $_SESSION['user_type']=='management')){
			  $brn_id=$_SESSION['assign_line_ids'];
			  $selbrnid=$_SESSION['line_id'];
			  $cndlin="   ";
			  }
	elseif($_REQUEST['line_id']==0){
			  $brn_id=$_SESSION['assign_line_ids'];
			  $selbrnid=$_SESSION['line_id'];
			  $cndlin=" and e.line_id in(0".$_SESSION['assign_line_ids'].")  ";
			  }
			  elseif($_REQUEST['line_id']>0 && $_REQUEST['city_id']>0) {
			 $brn_id=$_REQUEST['line_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 $cndlin=" and e.line_id='".$_REQUEST['line_id']."' and e.city='".$_REQUEST['city_id']."' ";
			 }
			 else {
			 $brn_id=$_REQUEST['line_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 $cndlin=" and e.line_id='".$_REQUEST['line_id']."' ";
			 }
			 
			 $branch_name=$obj_db->fetchRow("select branch_short_name,branch_city,branch_name from ".TABLE_BRANCH." where branch_id='1'");
		      $branch_name_city=$branch_name['branch_short_name'].'-'.$branch_name['branch_city'];
			 if($_REQUEST['line_id']<1) 
			  $title='ALL BRANCH-['. $dt1. ' To ' . $dt2.']';
			 else  $title=$branch_name_city.'-['. $dt1. ' To ' . $dt2.']';
   			  $getcolcrep=$obj_db->qry("select a.id,a.ref_id,line_name,a.customer_id,sum(is_paid_amount) as amt,a.gen_id,receipt_no,date_format(str_to_date(a.paid_date,'%Y-%m-%d %H:%i:%s %p'),'%d-%m-%y %h:%i %p')  as pdt,date_format(str_to_date(a.enter_date,'%Y-%m-%d %H:%i:%s %p'),'%d-%m-%y %h:%i %p')  as edt,if(date(a.enter_date)='".date('Y-m-d')."',1,0) as istodayentry,date_format(str_to_date(a.cancel_date,'%Y-%m-%d %H:%i:%s %p'),'%d-%m-%y %h:%i %p') as cdt,a.cancel_reason,b.user_name,d.borrow_name,customer_name,mobile_no,f.pay_type_id,f.pay_name,e.customer_no,a.is_delete as isdel from  ".TABLE_CUST_PAYMENTS." a,".TABLE_USER_DETAILS." b,".TABLE_LINE_NAMES." c,".TABLE_CUSTOMER_GENPAYMENTS." d,".TABLE_CUSTOMER_DTS." e,".TABLE_PAYMENT_TYPE." f where   a.pay_type_id=f.pay_type_id and a.borrow_id=d.borrow_id and e.customer_id=a.customer_id  and b.user_id=a.user_id $cndlin and a.line_id=c.line_id and (date(a.paid_date) between '".date('Y-m-d',strtotime(trim($dt1)))."'  and '".date('Y-m-d',strtotime(trim($dt2)))."' or date(a.cancel_date) between '".date('Y-m-d',strtotime($dt1))."' and '".date('Y-m-d',strtotime($dt2))."')   group by a.id,receipt_no,a.borrow_id order by a.id asc ");
                 $uniquebrn_getcolcrep = array_unique(array_column($getcolcrep, 'pay_type_id'));
						$getuniquebrn_getcolcrep = array_intersect_key($getcolcrep, $uniquebrn_getcolcrep);

						$paydtsar=array();
						foreach($getuniquebrn_getcolcrep as $ptypky=>$ptypv){
							 $ptyp=$ptypv['pay_type_id'];
					 $ptypamts = array_filter($getcolcrep,function($v,$k) use ($ptyp){
  return $v['pay_type_id'] == $ptyp;
},ARRAY_FILTER_USE_BOTH);
 
 $paydtsar[]=array('ptypname'=>$ptypv['pay_name'],'patypamt'=>array_sum(array_column($ptypamts,'amt')));
						}
					 $total_amt=0;$d=0;

					  $iscancel=0;
					 $paydts = array_filter($getcolcrep,function($v,$k) use ($iscancel){
  return $v['isdel'] == $iscancel;
},ARRAY_FILTER_USE_BOTH);
					 
					 foreach($paydts as $pdtky=>$pdtv){ 
					// $getnames=$obj_db->fetchRow("select customer_name,mobile_no from ".TABLE_CUSTOMER_DTS." where customer_id='".$pay_detailss_rows['customer_id']."'");
					 $dailyfetds[$d]['customer_name']=$pdtv['customer_name'];
					 $dailyfetds[$d]['customer_no']=$pdtv['customer_no'];
					 $dailyfetds[$d]['line_name']=$pdtv['line_name'];
					 $dailyfetds[$d]['borrow_name']=$pdtv['borrow_name'];
					 $dailyfetds[$d]['receipt_no']=$pdtv['receipt_no'];
					 $dailyfetds[$d]['gen_id']=$pdtv['gen_id'];
					  $dailyfetds[$d]['dt']=$pdtv['pdt'];
					 $dailyfetds[$d]['fee_name']=$pdtv['fee_name'];
					 $dailyfetds[$d]['user_name']=$pdtv['user_name'];
					 $dailyfetds[$d]['amt']=$pdtv['amt'];
					 $dailyfetds[$d]['id']=$pdtv['id'];
					 $dailyfetds[$d]['ref_id']=$pdtv['ref_id'];
					 $dailyfetds[$d]['istodayentry']=$pdtv['istodayentry'];
					 $dailyfetds[$d]['user_type']=$_SESSION['user_type'];
					 $total_amt=$total_amt+$pdtv['amt'];
					 $d++;
					}
					$dailyfetdss['tot']=number_format($total_amt,2);
					
					 
 $iscancel=1;
					 $cancelpaydts = array_filter($getcolcrep,function($v,$k) use ($iscancel){
  return $v['isdel'] == $iscancel;
},ARRAY_FILTER_USE_BOTH);
$c=0;
  foreach($cancelpaydts as $cncsfpky=>$cncfpv){ 
                     $dailycancels[$c]['customer_name']=$cncfpv['customer_name'].' '.$cncfpv['last_name'];
					 $dailycancels[$c]['line_name']=$cncfpv['line_name'];
					 $dailycancels[$c]['borrow_name']=$cncfpv['borrow_name'];
					 $dailycancels[$c]['amount']=$cncfpv['amt'];
					 
					 $dailycancels[$c]['user_name']=$cncfpv['user_name'];
					 $dailycancels[$c]['receipt_no']=$cncfpv['receipt_no'];
					  $dailycancels[$c]['cancel_date']=$cncfpv['cdt'];
					   $dailycancels[$c]['paid_date']=$cncfpv['pdt'];
					 $dailycancels[$c]['reason']=$cncfpv['cancel_reason'];
					 $c++;
  }
  
  $field_array=cleanArrayUtf8(array("payment_types"=>$paydtsar,"extitle"=>$title,"dailyfees"=>$dailyfetds,"grdtotal"=>number_format($total_amt,2),"tdat"=>$tdat,"cancelfees"=>$dailycancels));
	echo json_encode($field_array);
}
elseif($_REQUEST['action']=="datewise_userwise_paywisecollection"){
 
  
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= $split_dt[0];
 if($split_dt[1]=='')
 $dt2= date('d-m-Y');
else $dt2=$split_dt[1];
  if($_REQUEST['dat_range']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }


 /*if($_REQUEST['lineid']>0 && $_REQUEST['city_id']>0)
    $lnecnd=" and a.line_id='".$_REQUEST['lineid']."' and b.city='".$_REQUEST['city_id']."' ";
  else */
  if($_REQUEST['lineid']>0)
    $lnecnd=" and b.line_id='".$_REQUEST['lineid']."' ";
   else $lnecnd="";

    if($_REQUEST['branch_id']==0){
			  $brn_id=$_SESSION['assign_branch_ids'];
			  $selbrnid=$_SESSION['branch_id'];
			  }
			 else {
			 $brn_id=$_REQUEST['branch_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 }
			 $branch_name=$obj_db->fetchRow("select branch_short_name,branch_city,branch_name from ".TABLE_BRANCH." where branch_id='".$_REQUEST['branch_id']."'");
		      $branch_name_city=$branch_name['branch_short_name'].'-'.$branch_name['branch_city'];
			 if($_REQUEST['branch_id']<1) 
			  $title='ALL BRANCH-['. $dt1. ' To ' . $dt2.']';
			 else  $title=$branch_name_city.'-['. $dt1. ' To ' . $dt2.']';
  				 $fee_paymentqry=$obj_db->qry("select date(paid_date) as pdts,sum(is_paid_amount) as pamt,a.pay_type_id as pay_type_id,b.pay_name,a.borrow_id,a.user_id from ".TABLE_CUST_PAYMENTS." a,".TABLE_PAYMENT_TYPE." b  where   str_to_date(a.paid_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y')  $var and is_delete=0  $lnecnd and a.pay_type_id=b.pay_type_id  group by date(paid_date),a.user_id asc,a.pay_type_id asc order by date(paid_date) asc,a.user_id asc,a.pay_type_id asc");
				 
$unq_custpays = array_unique(array_column($fee_paymentqry, 'pay_type_id'));
						$getunq_custpays = array_intersect_key($fee_paymentqry, $unq_custpays);

				  $fepay_fids = array_map(function ($value) {
    return  $value['pay_type_id'];
}, $fee_paymentqry);

if(count($fepay_fids)==0)
 $fepayfids=array();
 else $fepayfids=$fepay_fids;
				 
				  $fepay_dats = array_map(function ($value) {
    return  $value['pdts'];
}, $fee_paymentqry);

 $genfe_usrids = array_map(function ($value) {
    return  $value['user_id'];
}, $fee_paymentqry);
if(count($genfe_usrids)==0)
 $genfeusrids=array();
 else $genfeusrids=$genfe_usrids;
 
				 
				 if(count($fepay_dats)==0)
				   $fepay_dats=array();
				  else $fepay_dats=$fepay_dats;
				
	
     foreach($getunq_custpays as $unqpky=>$unqpv){
    $fee_namearr[$unqpv['pay_type_id']]=array('pay_type_id'=>$unqpv['pay_type_id'],'pay_name'=>$unqpv['pay_name']);
    }
//	print_r($getunq_custpays);print_r($fee_namearr);
				  $othfepay_dats = array_map(function ($value) {
    return  $value['opdt'];
}, $othrfees); 

$othfe_usrids = array_map(function ($value) {
    return  $value['user_id'];
}, $othrfees);
if(count($othfe_usrids)==0)
 $othfeusrids=array();
 else $othfeusrids=$othfe_usrids;

				  if(count($othfepay_dats)==0)
				   $othfepay_dats=array();
				  else $othfepay_dats=$othfepay_dats;
				  
				 $all_dats = array_unique(array_merge($fepay_dats, $othfepay_dats));
  $s=0;
 
 foreach($all_dats  as $key=>$value){
  $all_dates[$s]=$value;
   $s++;
 } 
				 $all_usrids = array_unique(array_merge($genfeusrids, $othfeusrids));
 
				
  $usr_dts=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where user_id in(0".implode(',',$all_usrids).")");
   for($i=0;$i<count($usr_dts);$i++)
    $user_namearr[$usr_dts[$i]['user_id']]=$usr_dts[$i]['user_name'];
  	
		//	print_r($fee_paymentqry);	
				
					 				$all_grdtot=0;	
									$j=0;
					for($w=0;$w<count($all_dates);$w++){
					 $datkeys = array_keys(array_column($fee_paymentqry, 'pdts'),$all_dates[$w]);
					// echo '<pre>';print_r($datkeys);echo '<pre>';
					 $othdatkeys = array_keys(array_column($othrfees, 'opdt'),$all_dates[$w]);
					 $genfedatars=array();
					 
					
					 if(count($datkeys)>0 || count($othdatkeys)>0){
					 for($b=0;$b<count($datkeys);$b++){
					  $genfedatars[]=$fee_paymentqry[$datkeys[$b]];
					 }
					  for($b=0;$b<count($othdatkeys);$b++){
					  $genfedatars[]=$othrfees[$othdatkeys[$b]];
					 }
					 $usrids=array();
					 foreach($genfedatars as $key=>$value)
					  if(!in_array($value['user_id'],$usrids))
					      $usrids[]=$value['user_id'];
					 for($t=0;$t<count($usrids);$t++){
 					    $usrfee_arrkys=array_keys(array_column($genfedatars, 'user_id'),$usrids[$t]);
						$usrfee_arrs=array();
						foreach($usrfee_arrkys as $key=>$value)
						 $usrfee_arrs[]=$genfedatars[$value];
						
						//echo '<pre>';print_r($usrids);print_r($genfedatars);print_r($usrfee_arrs);
					//	echo $all_dates[$w].'   '.$usrids[$t].'<br>';
						// print_r($genfedatars);//print_r($usrfee_arrkys);
					//	echo '</pre>';
						
					 $s=0;$sno=$j+1;

					  $fetch_data[$j][$s]=$sno;
					 $s++;
					 $fetch_data[$j][$s]=date('d-m-Y',strtotime($all_dates[$w]));
					 $s++;
					 $fetch_data[$j][$s]=$user_namearr[$usrids[$t]];
					 $s++;
					// echo '<pre>';
					// print_r($usrfee_arrs);print_r($fee_namearr);echo '<pre>';
					/*------ */
						 $sub_tot=0;
						  foreach($fee_namearr as $key=>$value){
					   $keys = array_keys(array_column($usrfee_arrs, 'pay_type_id'),$key);
					    $tgsum=0;
						 for($a=0;$a<count($keys);$a++){
						   $tgsum=$tgsum+$usrfee_arrs[$keys[$a]]['pamt'];
						 }

					 $fetch_data[$j][$s]=$tgsum;
					 $sub_tot=$sub_tot+$tgsum;
					  $s++;
					 }
					 $s=$s+1;
					 $fetch_data[$j][$s]=$sub_tot;
					
					/*------ */
					 $j++;
					}
					 
					 
					
					 }
					 }
					$j++;
					$r=0;
					$tcnt=sizeof($fetch_data[0])-1;
					
					 for($p=3;$p<sizeof($fetch_data[0]);$p++){
	 for($i=0;$i<sizeof($fetch_data);$i++)
 		{
		if($p!=$tcnt)
		 $subtotal[$r]=$subtotal[$r]+$fetch_data[$i][$p]; }
		$r++;
		}
	 	$grand_sum=0;  $r++;
  for($p=0;$p<sizeof($subtotal);$p++)
	{	
	  $grand_sum=$grand_sum+$subtotal[$p];
	 }
	  $subtotal[$r]=$grand_sum;
	
	  $all_grdtot=$all_grdtot+$grand_sum;
 	// echo '<pre>'; print_r($fetch_data);print_r($subtotal);echo '<pre>';

  $field_array=array("payment_amts"=>$fetch_data,"grdtotal"=>$subtotal,"all_grdtot"=>$all_grdtot,'fee_names'=>$fee_namearr);
	echo json_encode($field_array);

 

 }

elseif($_REQUEST['action']=="cust_fee_due"){
	$pagesize = $_GET['limit'];
     
	 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= $split_dt[0];
 $dt2= $split_dt[1];
 if($_REQUEST['dat_range']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }
   
  if($_REQUEST['lineid']>0 && $_REQUEST['city_id']>0)
    $lnecnd=" and b.line_id='".$_REQUEST['lineid']."' and b.city='".$_REQUEST['city_id']."' ";
  else if($_REQUEST['lineid']>0)
    $lnecnd=" and b.line_id='".$_REQUEST['lineid']."' ";
   else $lnecnd="";
			 $branch_sec=$obj_db->fetchRow("select branch_short_name from ".TABLE_BRANCH."  where branch_id='1' ");
			  $secname=" Over All Cusomers ";

			$course_secname=$branch_sec['branch_short_name'].'-'.$secname;

			$exids=$course_secname;
			if($pagesize==0){
			
			$count=$obj_db->fetchNum("select a.customer_id,monthlydue_amt from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." a,".TABLE_CUSTOMER_DTS." b,".TABLE_CUSTOMER_GENPAYMENTS." c where  c.is_delete=0 and a.customer_id=b.customer_id and a.customer_id=c.customer_id and DATE_FORMAT(STR_TO_DATE(a.due_date, '%d-%m-%Y'), '%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y') and a.borrow_id=c.borrow_id and a.year_id=c.year_id and b.is_delete=0 $lnecnd and a.year_id=".$_SESSION['year_id']." group by c.borrow_id,a.customer_id having sum(monthlydue_amt)>0  ");
			
						 $count=$count;
						} 
						else{
						  $count=$_REQUEST['tot_count'];
						}
						 
				
	   $std_due_details="select a.customer_id,customer_name,sum(monthlydue_amt) as term_due,sum(CASE WHEN monthlydue_amt > 0 THEN 1 ELSE 0 END) as pendweeks,count(month_week) as totweeks,c.total_amount,a.monthly_amt,c.tot_amt_updconces,(c.total_amount-c.tot_amt_updconces) as conces,(c.tot_amt_updconces-c.remain_balance) as tpaid,c.no_months,borrow_name,mobile_no,city,date(taken_date) as tdt from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." a,".TABLE_CUSTOMER_DTS." b,".TABLE_CUSTOMER_GENPAYMENTS." c where  $cnd  c.is_delete=0 and a.customer_id=b.customer_id and a.customer_id=c.customer_id  and a.borrow_id=c.borrow_id and a.year_id=c.year_id $lnecnd and b.is_delete=0 and a.year_id='".$_SESSION['year_id']."' and  DATE_FORMAT(STR_TO_DATE(a.due_date, '%d-%m-%Y'), '%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y') group by c.borrow_id,a.customer_id having sum(monthlydue_amt)>0 order by sum(monthlydue_amt) asc ,customer_name asc  limit $pagesize, 1000";					
		$std_due_details_rows=$obj_db->qry($std_due_details);
					 $total_amt=0;
					  $sno=$pagesize+1;
					 for($s=0;$s<count($std_due_details_rows);$s++){
					 $city=$obj_db->fetchRow("select city_name from ".TABLE_LINE_CITYS." where city_id='".$std_due_details_rows[$s]['city']."' ");
					 $last_paid=$obj_db->fetchRow("select date(paid_date) as pdt from ".TABLE_CUST_PAYMENTS." where customer_id='".$std_due_details_rows[$s]['customer_id']."' and borrow_id='".$std_due_details_rows[$s]['borrow_id']."' and is_delete=0 order by date(paid_date) desc limit 1");
					 if($last_paid['pdt']!='')
					  $pdt=date('d-m-Y',strtotime($last_paid['pdt']));
					 else $pdt="";
					  $fetch_data[$s]['sno']=$sno;
					  $fetch_data[$s]['customer_name']=$std_due_details_rows[$s]['customer_name'];
					  $fetch_data[$s]['city']=$city['city_name'];
					  $fetch_data[$s]['mobile_no']=$std_due_details_rows[$s]['mobile_no'];
					  $fetch_data[$s]['borrow_name']=$std_due_details_rows[$s]['borrow_name'];
					  $fetch_data[$s]['tdt']=date('d-m-Y',strtotime($std_due_details_rows[$s]['tdt']));
					  $fetch_data[$s]['org_amount']=$std_due_details_rows[$s]['total_amount'];
					  $fetch_data[$s]['cust_amt']=$std_due_details_rows[$s]['tot_amt_updconces'];
					  $fetch_data[$s]['conces']=$std_due_details_rows[$s]['conces'];
					  $fetch_data[$s]['tpaid']=$std_due_details_rows[$s]['tpaid'];
					  $fetch_data[$s]['lst_padidt']=$pdt;
					  $fetch_data[$s]['term_due']=$std_due_details_rows[$s]['term_due'];
					  $fetch_data[$s]['monthly_amount']=$std_due_details_rows[$s]['monthly_amt'];
					  $fetch_data[$s]['pay_type']=$std_due_details_rows[$s]['pay_type'];
					  $fetch_data[$s]['tweeks']=$std_due_details_rows[$s]['pendweeks'].'/'.$std_due_details_rows[$s]['totweeks'].'('.$std_due_details_rows[$s]['no_months'].')';
					
					  $tot_sum=$std_due_details_rows[$s]['term_due']+$tot_sum;
 					
		  $sno++; } 
		
  $exp_rettotsums=explode(',',$_REQUEST['ret_tots']);
	$arr_fetchdata=array("feedue"=>$fetch_data,"extitle"=>$exids,"totalCount"=>$count,"fee_ids"=>$retfeids,"ret_tots"=>$trmretsub_tot,"sub_tot"=>$tot_sum);
 echo json_encode($arr_fetchdata);
   
}

elseif($_REQUEST['action']=="cust_pendingupto_date"){
	$pagesize = $_GET['limit'];
     
	 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= $split_dt[0];
 $dt2= $split_dt[1];
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }

 $weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");
   
   if($_REQUEST['lineid']>0 && $_REQUEST['city_id']>0)
    $lnecnd=" and b.line_id='".$_REQUEST['lineid']."' and b.city='".$_REQUEST['city_id']."' ";
  else if($_REQUEST['lineid']>0)
    $lnecnd=" and b.line_id='".$_REQUEST['lineid']."' ";
   else $lnecnd=" and b.line_id in(".$_SESSION['assign_line_ids'].")";
			 $branch_sec=$obj_db->fetchRow("select branch_short_name from ".TABLE_BRANCH."  where branch_id='1' ");
			  $secname=" Over All Cusomers ";

			$course_secname=$branch_sec['branch_short_name'].'-'.$secname;

			$exids=$course_secname;
			if($pagesize==0){
				
 			$totgenborrowcount=$obj_db->qry("select a.customer_id,monthlydue_amt,b.line_id,d.city_id,d.city_name,d.weekd_id from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." a,".TABLE_CUSTOMER_DTS." b,".TABLE_CUSTOMER_GENPAYMENTS." c,".TABLE_LINE_CITYS." d where  c.is_delete=0 and a.customer_id=b.customer_id and b.city_id=d.city_id and a.customer_id=c.customer_id and DATE_FORMAT(STR_TO_DATE(due_date, '%d-%m-%Y'), '%Y-%m-%d')<='".date('Y-m-d',strtotime(trim($_REQUEST['dat_range'])))."' and a.borrow_id=c.borrow_id and a.year_id=c.year_id and b.is_delete=0 $lnecnd and a.year_id=".$_SESSION['year_id']." group by c.borrow_id,a.customer_id having sum(monthlydue_amt)>0  ");
			
						 $count=count($totgenborrowcount);


						 $uniquelineids[]=$_REQUEST['lineid'];
						$lineweekarr=array();$linedtsarr=array();
 	foreach($totgenborrowcount as $lincityky=>$lincityv){
		$lineweekarr[$lincityv['line_id']][$lincityv['weekd_id']]=array("week_id"=>$lincityv['weekd_id'],"week_name"=>$weekdts[$lincityv['weekd_id']]);
		$linedtsarr[$lincityv['line_id']]=array('line_id'=>$lincityv['line_id'],'line_name'=>$lincityv['line_name']);
                     
					}
$lineweedts=array();$linectiydtsarr=array();
				foreach($uniquelineids as $linkky=>$linv){
					$linid=$linv;
					$lineweedts[$linid][0]=array('week_id'=>0,'week_name'=>"All Weeks");
							$linedts = array_filter($totgenborrowcount,function($v,$k) use ($linid){
								return $v['line_id'] == $linid;
							  },ARRAY_FILTER_USE_BOTH);
                   
					foreach($lineweekarr[$linid] as $linwek=>$linwekv){
						$wekid=$linwekv['week_id'];
						   $lineweedts[$linid][]=array('week_id'=>$linwekv['week_id'],'week_name'=>$linwekv['week_name']);
						
							$linecitys = array_filter($linedts,function($v,$k) use ($wekid){
								return $v['weekd_id'] == $wekid;
							  },ARRAY_FILTER_USE_BOTH);
							   $linectiydtsarr[$linid][$wekid][]=array('city_id'=>0,'city_name'=>"All Citys");
						foreach($linecitys as $cityky=>$cityv){	   
                       $linectiydtsarr[$linid][$wekid][$cityv['city_id']]=array('city_id'=>$cityv['city_id'],'city_name'=>$cityv['city_name']);
						}
					}
				}
			// 	echo '<pre>';print_r($linedtsarr);print_r($lineweedts);print_r($linectiydtsarr);echo '</pre>';exit;
						} 
						else{
						    $count=$_REQUEST['tot_count'];
						  $linectiydtsarr=json_decode($_REQUEST['linectiydtsarr'],true);
						   $lineweedts=json_decode($_REQUEST['week_dts'],true);
						  //  echo '<pre>';print_r($linedtsarr);print_r($lineweekarr);print_r($linectiydtsarr);echo '</pre>';
 						}
						 

					








	      $getcustdueslsit=$obj_db->qry("select a.customer_id,customer_name,b.customer_no,b.line_id,d.weekd_id,b.city_id,sum(monthlydue_amt) as term_due,pay_type,sum(CASE WHEN monthlydue_amt > 0 THEN 1 ELSE 0 END) as pendweeks,count(month_week) as totweeks,a.monthly_amt,c.total_amount,c.tot_amt_updconces,(c.total_amount-c.tot_amt_updconces) as conces,(c.tot_amt_updconces-c.remain_balance) as tpaid,c.no_months,borrow_name,mobile_no,city,date_format(str_to_date(c.taken_date,'%Y-%m-%d'),'%d-%m-%Y') as tdt,c.borrow_id,c.remain_balance,d.city_name,d.city_id,d.weekd_id from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." a,".TABLE_CUSTOMER_DTS." b,".TABLE_CUSTOMER_GENPAYMENTS." c,".TABLE_LINE_CITYS." d where  $cnd  c.is_delete=0 and a.customer_id=b.customer_id and b.city_id=d.city_id and a.customer_id=c.customer_id  and a.borrow_id=c.borrow_id and a.year_id=c.year_id $lnecnd and b.is_delete=0 and a.year_id='".$_SESSION['year_id']."' and DATE_FORMAT(STR_TO_DATE(due_date, '%d-%m-%Y'), '%Y-%m-%d')<='".date('Y-m-d',strtotime(trim($_REQUEST['dat_range'])))."' group by c.borrow_id,a.customer_id having sum(monthlydue_amt)>0 order by pendweeks desc  limit $pagesize, 1000");					
 
		 $last_paid=$obj_db->qry("SELECT p.customer_id,date_format(str_to_date(p.paid_date,'%Y-%m-%d'),'%d-%m-%Y') as pdt FROM ".TABLE_CUST_PAYMENTS." p INNER JOIN (SELECT customer_id, MAX(paid_date) AS pdt
    FROM ".TABLE_CUST_PAYMENTS." where customer_id in(0".implode(',',array_column($getcustdueslsit,'customer_id')).")
    GROUP BY customer_id
) latest ON p.customer_id = latest.customer_id
         AND date(p.paid_date) = date(latest.pdt)");
		 $arrlst_paidcustdtsarr=array();
		 foreach($last_paid as $lstpaidky=>$lstpaidv){
             $arrlst_paidcustdtsarr[$lstpaidv['customer_id']]=$lstpaidv;
		 }
	//	 select date(paid_date) as pdt from ".TABLE_CUST_PAYMENTS." where customer_id in(0".$std_due_details_rows[$s]['customer_id']."' and borrow_id='".$std_due_details_rows[$s]['borrow_id']."' and is_delete=0 order by date(paid_date) desc limit 1");
					 $total_amt=0;
					  $sno=$pagesize+1;$s=0;
					 foreach($getcustdueslsit as $custdueky=>$custduev){
					  $fetch_data[$s]['sno']=$sno;
					  $fetch_data[$s]['customer_name']=$custduev['customer_name'];
					  $fetch_data[$s]['customer_no']=$custduev['customer_no'];
					  $fetch_data[$s]['city']=$city['city_name'];
					  $fetch_data[$s]['mobile_no']=$custduev['mobile_no'];
					  $fetch_data[$s]['borrow_name']=$custduev['borrow_name'];
					  $fetch_data[$s]['tdt']=$custduev['tdt'];
					  $fetch_data[$s]['org_amount']=$custduev['total_amount'];
					  $fetch_data[$s]['cust_amt']=$custduev['tot_amt_updconces'];
					  $fetch_data[$s]['remain_balance']=$custduev['remain_balance'];
					  $fetch_data[$s]['conces']=$custduev['conces'];
					  $fetch_data[$s]['week_id']=$custduev['weekd_id'];
					  $fetch_data[$s]['city_id']=$custduev['city_id'];
					  $fetch_data[$s]['tpaid']=$custduev['tpaid'];
					  $fetch_data[$s]['lst_padidt']=$arrlst_paidcustdtsarr[$custduev['customer_id']]['pdt'];
					  $fetch_data[$s]['term_due']=$custduev['term_due'];
					  $fetch_data[$s]['monthly_amount']=$custduev['monthly_amt'];
					  $fetch_data[$s]['pay_type']=$custduev['pay_type'];
					  $fetch_data[$s]['tweeks']=$custduev['pendweeks'].'/'.$custduev['totweeks'].'('.$custduev['no_months'].')';
					
					   $tot_org=$custduev['total_amount']+$tot_org;
					  $tot_custamt=$custduev['tot_amt_updconces']+$tot_custamt;
					  $tot_conces=$custduev['conces']+$tot_conces;
					  $tot_tpaid=$custduev['tpaid']+$tot_tpaid;
					  $tot_monweekdue=$custduev['monthly_amt']+$tot_monweekdue;
					  
					  $tot_sum=$custduev['term_due']+$tot_sum;
 					
		  $sno++; $s++;} 
 		
 	$arr_fetchdata=array("feedue"=>$fetch_data,"extitle"=>$exids,"totalCount"=>$count,"sub_tot"=>$tot_sum,"tot_org"=>$tot_org,"tot_custamt"=>$tot_custamt,"tot_conces"=>$tot_conces,"tot_tpaid"=>$tot_tpaid,"tot_monweekdue"=>$tot_monweekdue,'lineweekarr'=>$lineweedts,'linectiydtsarr'=>$linectiydtsarr);
 echo json_encode($arr_fetchdata);
   
}




?>