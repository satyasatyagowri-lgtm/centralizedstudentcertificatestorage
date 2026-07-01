<?php session_start();
include "../DbConfig.php";
  if($_REQUEST['action']=="branch_dts"){
      $branch_qrys=$obj_db->qry("select branch_short_name,branch_id from ".TABLE_BRANCH."  where branch_id in(".$_SESSION['assign_branch_ids'].") order by branch_id asc");
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

  elseif($_REQUEST['action']=="oppaytype_reprot"){
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
    if($_REQUEST['branch_id']==0){
			  $brn_id=$_SESSION['assign_branch_ids'];
			  $selbrnid=$_SESSION['branch_id'];
			  }
			 else {
			 $brn_id=$_REQUEST['branch_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 }
			 $branch_name=$obj_db->qry("select branch_short_name,branch_city,branch_name,branch_id from ".TABLE_BRANCH." where branch_id in(".$_SESSION['assign_branch_ids'].")");
			   for($g=0;$g<count($branch_name);$g++)
			    $brnch_name[]=$branch_name[$g]['branch_short_name'];
			 
			  $paytyps=$obj_db->qry("select pay_name,pay_type_id from ".TABLE_PAYMENT_TYPE."");
			  

			 if($_REQUEST['branch_id']<1) 
			  $title='ALL BRANCH-['. $dt1. ' To ' . $dt2.']';
			 else  $title=$branch_name_city.'-['. $dt1. ' To ' . $dt2.']';
			 
  if($_REQUEST['course_id']==0)$var="";
						      else $var=" and  b.course_id='".$_REQUEST['course_id']."' ";
													  
				$tc=0;$paytype_rep=array();$n=0;
			    $t=1;
				for($i=0;$i<count($paytyps);$i++){		
			  	 $paytype_rep[$i][$t]=$paytyps[$i]['pay_name'];
				 $tc++;$sbtotsm=0;
				 for($p=0;$p<count($branch_name);$p++){$t++;
				 $paytype=$obj_db->fetchRow("select  sum(is_paid_amount) as amt from ".TABLE_FEE_PAYMENT."  where branch_id='".$branch_name[$p]['branch_id']."' and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' and payment_type='".$paytyps[$i]['pay_type_id']."' $var and receipt_cancelled=0 ");
				  $paytype_rep[$i][$t]=$paytype['amt'];
				  $sbtotsm=$sbtotsm+$paytype['amt'];
				  
				 $sum[$i][$p]=$sum[$i][$p]+$paytype['amt'];
				
				}//$p++;
				$t++;
				  $paytype_rep[$i][$t]=$sbtotsm;
				$sum[$i][$p]=$sum[$i][$p]+$sbtotsm;
				}
				
		//print_r($sum);		
for($j=0;$j<sizeof($sum[0]);$j++){
	 for($m=0;$m<sizeof($sum);$m++)
 		{ $subtotal[$j]=$subtotal[$j]+$sum[$m][$j]; }
		}//print_r($subtotal);	
        $paytype_rep[$i][$t]="Total";
		for($s=0;$s<count($subtotal);$s++){//$q=$t+1;
		$t++;
		$paytype_rep[$i][$t]=$subtotal[$s];
		
		}
				
  $field_array=array("paytype_rws"=>$paytype_rep,"extitle"=>$title,"brnch_name"=>$brnch_name);
	echo json_encode($field_array);
} 
 elseif($_REQUEST['action']=="opcashierwise_reprot"){
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
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
			 
  if($_REQUEST['course_id']==0)$var="";
						      else $var=" and  b.course_id='".$_REQUEST['course_id']."' ";
				  $paytype="select pay_name, sum(is_paid_amount) as amt from ".TABLE_FEE_PAYMENT." b,".TABLE_PAYMENT_TYPE." a,".TABLE_COURSE." c where a.pay_type_id=b.payment_type and b.branch_id in(".$brn_id.")   AND b.course_id=c.course_id  and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 group by payment_type";
				$paytype_res=$obj_db->qry($paytype);
			 	 //$paytype_ress=$obj_db->get_qresult($paytype);
					 $total_paytyps=0;
					 $s=0;
					 for($p=0;$p<count($paytype_res);$p++){
					 $fetch_data[$p]['pay_name']=$paytype_res[$p]['pay_name'];
					 $fetch_data[$p]['amt']=$paytype_res[$p]['amt'];
					 $total_paytyps= $total_paytyps+$paytype_res[$p]['amt'];
					 }$fetch_data[$p]['pay_name']="Total";
					 $fetch_data[$p]['amt']=number_format($total_paytyps,2);
					 /*$s=1;
					 for($p=0;$p<count($paytype_res);$p++){
					 $fetch_data[$s][$p]=$paytype_res[$p]['amt'];
					 $total_paytyps= $total_paytyps+$paytype_res[$p]['amt'];
					 }$fetch_data[$s][$p]=number_format($total_paytyps,2);
					 
					 $s=0;$p=0;
					 while($paytype_rows=$obj_db->fetchArray($paytype_res)){
					 $fetch_data[$s][$p]=$paytype_rows['pay_name'];
					 $p++;
					 }
					 $s=1;$p=0;
					while($paytype_rowss=$obj_db->fetchArray($paytype_res)){
					 $fetch_data[$s][$p]=$paytype_rowss['amt'];
					 $total_paytyps= $total_paytyps+$paytype_rowss['amt'];
					 $p++;
					 }
					 $p=$p+1;
					 $fetch_data[$s][$p]=number_format($total_paytyps,2);*/
					 
					 
					 
					  $year_paymens="select year,sum(is_paid_amount) as amt from ".TABLE_FEE_PAYMENT." b,".TABLE_YEAR." a,".TABLE_COURSE." c where  
				b.branch_id in(".$brn_id.")    and a.year_id=b.year_id  and b.course_id=c.course_id and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 group by a.year_id order by b.year_id asc";
				$yearpay_res=$obj_db->get_qresult($year_paymens);
					 $total_paytyps=0;
					 
					 $s=0;$p=0;
					 while($yearpay_rows=$obj_db->fetchArray($yearpay_res)){
					 $yardts_data[$s][$p]=$yearpay_rows['year'];
					 $yaramt_data[$s][$p]=$yearpay_rows['amt'];
					 $tot_yramt=$tot_yramt+$yearpay_rows['amt'];
					 $p++;
					 }
					
					 $p=$p+1;
					 $yaramt_data[$s][$p]=number_format($tot_yramt,2);
					 
					 
					 
					 
		 if($_REQUEST['course_id']==0)
					  $cnd="";
					 else $cnd=" and d.course_id ='".$_REQUEST['course_id']."'";
		   $pay_details=$obj_db->qry("select fee_name,b.student_id,sum(is_paid_amount) as amt,a.fee_id,receipt_no,date(paid_date) as 				                      dt,b.course_id,d.course_name,course_hostel_id,c.user_name,pay_name,bank_name,cheque_num,cheque_date,receipt_cancelled,b.cancel_reason,date(cancel_date) as cdt,f.first_name,f.last_name from ".TABLE_FEE_TYPE." a, ".TABLE_FEE_PAYMENT." b,".TABLE_USER_DETAILS." c,".TABLE_COURSE." d,".TABLE_PAYMENT_TYPE." e,".TABLE_STUDENTDETAILS." f where a.fee_id=b.fee_id and b.branch_id in(".$brn_id.") $cnd and b.user_id=c.user_id and b.course_id=d.course_id and date(paid_date) between '".trim($dt1)."' and '".($dt2)."' and f.student_id=b.student_id and b.payment_type=.e.pay_type_id  group by receipt_no,fee_id order by b.id asc ");
 					 $total_amt=0;$d=0;
					 $iscancel=0;
					 $paydts = array_filter($pay_details,function($v,$k) use ($iscancel){
  return $v['receipt_cancelled'] == $iscancel;
},ARRAY_FILTER_USE_BOTH);

					 foreach($paydts as $k=>$v){ $i++;
					/*$getnames=$obj_db->fetchRow("select b.first_name,b.last_name,a.roll_no,a.sec_id,a.student_status,a.admission_no from ".TABLE_STUDENT_EDU_DETAILS." a,
					 ".TABLE_STUDENTDETAILS." b  where a.student_id=b.student_id and a.student_id='".$pay_detailss_rows['student_id']."'");*/
					 $dailyfetds[$d]['std_name']=$v['first_name'].' '.$v['last_name'];
					 $dailyfetds[$d]['course_name']=$v['course_name'];
						 $dailyfetds[$d]['pro_name']=$v['pro_name'];
					  	$dailyfetds[$d]['admission_no']=$v['admission_no'];
					 $dailyfetds[$d]['receipt_no']=$v['receipt_no'];
					 $dailyfetds[$d]['pay_name']=$v['pay_name'];
 					 $dailyfetds[$d]['bank_name']=$v['bank_name'];
					 $dailyfetds[$d]['cheque_num']=$v['cheque_num'];

					  $dailyfetds[$d]['dt']=date('d-m-Y',strtotime($v['dt']));
					 $dailyfetds[$d]['fee_name']=$v['fee_name'];
					 $dailyfetds[$d]['user_name']=$v['user_name'];
					 $dailyfetds[$d]['amt']=$v['amt'];
					 $total_amt=$total_amt+$v['amt'];
					 $d++;
					}
					$dailyfetdss['tot']=number_format($total_amt,2);
					
			//print_r($dailyfetds);
					if($_REQUEST['course_id']==0)
					  $cnds="";
					 else $cnds=" and a.course_id ='".$_REQUEST['course_id']."'";
	/*$pay_details=$obj_db->get_qresult(" select b.first_name,b.last_name,e.course_name,a.sec_id,a.roll_no,date(paid_date) as pdt,d.user_name,sum(is_paid_amount) as pamt,c.cancel_reason,c.receipt_no,date(cancel_date) as cdt from ".TABLE_STUDENT_EDU_DETAILS." a,".TABLE_STUDENTDETAILS." b,".TABLE_FEE_PAYMENT." c,".TABLE_USER_DETAILS." d,".TABLE_COURSE." e where date(c.cancel_date) between '".trim($dt1)."' and '".trim($dt2)."' and c.receipt_cancelled=1 $cnds and a.student_id=b.student_id and a.student_id=b.student_id and b.student_id=c.student_id and a.student_id=c.student_id and a.course_id=e.course_id and c.course_id=e.course_id and c.cancel_by='".$_SESSION['user_id']."' and a.course_model=1  and c.cancel_by=d.user_id  and a.y_id=c.year_id and  c.year_id='".$_SESSION['year_id']."'  group by c.receipt_no");*/
 $c=0;
 
 $iscancel=1;
					 $cancel_paydts = array_filter($pay_details,function($v,$k) use ($iscancel){
  return $v['receipt_cancelled'] == $iscancel;
},ARRAY_FILTER_USE_BOTH);
 
  foreach($cancel_paydts as $k=>$v){ 
                     $dailycancels[$c]['std_name']=$v['first_name'].' '.$v['last_name'];
					 $dailycancels[$c]['course_name']=$v['course_name'];
					 $dailycancels[$c]['amount']=$v['pamt'];
					 
					 $dailycancels[$c]['user_name']=$v['user_name'];
					 $dailycancels[$c]['receipt_no']=$v['receipt_no'];
					  $dailycancels[$c]['cancel_date']=date('d-m-Y',strtotime($v['cdt']));
					   $dailycancels[$c]['paid_date']=date('d-m-Y',strtotime($v['pdt']));
					 $dailycancels[$c]['reason']=$v['cancel_reason'];
					 $c++;
  }
  
  $field_array=array("year_dts"=>$yardts_data,"year_amts"=>$yaramt_data,"payment_types"=>$fetch_data,"extitle"=>$title,"dailyfees"=>$dailyfetds,"grdtotal"=>number_format($total_amt,2),"cancelfees"=>$dailycancels);
	echo json_encode($field_array);
}

elseif($_REQUEST['action']=='opcashierwise_longtrmreprot'){

 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
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
			 
  if($_REQUEST['course_id']==0)$var="";
						      else $var=" and  b.course_id='".$_REQUEST['course_id']."' ";
				 $paytype="select pay_name, sum(is_paid_amount) as amt from ".TABLE_FEE_PAYMENT." b,".TABLE_PAYMENT_TYPE." a,".TABLE_COURSE." c where a.pay_type_id=b.payment_type and b.branch_id in(".$brn_id.") and b.user_id='".$_SESSION['user_id']."' and c.course_type_id=3 and b.course_id=c.course_id  and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 group by payment_type";
				$paytype_res=$obj_db->get_qresult($paytype);
				$paytype_ress=$obj_db->get_qresult($paytype);
					 $total_paytyps=0;
					 
					 $s=0;$p=0;
					 while($paytype_rows=$obj_db->fetchArray($paytype_res)){
					 $fetch_data[$s][$p]=$paytype_rows['pay_name'];
					 $p++;
					 }
					 $s=1;$p=0;
					while($paytype_rowss=$obj_db->fetchArray($paytype_ress)){
					 $fetch_data[$s][$p]=$paytype_rowss['amt'];
					 $total_paytyps= $total_paytyps+$paytype_rowss['amt'];
					 $p++;
					 }
					 $p=$p+1;
					 $fetch_data[$s][$p]=number_format($total_paytyps,2);
					 
					 
					 
					  $year_paymens="select year,sum(is_paid_amount) as amt from ".TABLE_FEE_PAYMENT." b,".TABLE_YEAR." a,".TABLE_COURSE." c where  
				b.branch_id in(".$brn_id.") and b.user_id='".$_SESSION['user_id']."' and c.course_type_id=3 and b.course_id=c.course_id  and a.year_id=b.year_id and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 group by a.year_id order by b.year_id asc";
				$yearpay_res=$obj_db->get_qresult($year_paymens);
					 $total_paytyps=0;
					 
					 $s=0;$p=0;
					 while($yearpay_rows=$obj_db->fetchArray($yearpay_res)){
					 $yardts_data[$s][$p]=$yearpay_rows['year'];
					 $yaramt_data[$s][$p]=$yearpay_rows['amt'];
					 $tot_yramt=$tot_yramt+$yearpay_rows['amt'];
					 $p++;
					 }
					
					 $p=$p+1;
					 $yaramt_data[$s][$p]=number_format($tot_yramt,2);
					 
					 
					 
					 
		 if($_REQUEST['course_id']==0)
					  $cnd="";
					 else $cnd=" and d.course_id ='".$_REQUEST['course_id']."'";
		$pay_detailss_rows=$obj_db->qry("select fee_name,b.student_id,sum(is_paid_amount) as amt,a.fee_id,receipt_no,date(paid_date) as 				                      dt,b.course_id,d.course_name,course_hostel_id,c.user_name,pay_name,bank_name,cheque_num,cheque_date from ".TABLE_FEE_TYPE." a, ".TABLE_FEE_PAYMENT." b,".TABLE_USER_DETAILS." c,".TABLE_COURSE." d,".TABLE_PAYMENT_TYPE." e where a.fee_id=b.fee_id and b.branch_id in(".$brn_id.") and b.user_id='".$_SESSION['user_id']."' and receipt_cancelled=0  $cnd and b.user_id=c.user_id and b.course_id=d.course_id and d.course_type_id=3  and date(paid_date) between '".trim($dt1)."' and '".($dt2)."' and b.payment_type=e.pay_type_id  group by receipt_no,fee_id order by b.id asc ");
					 $total_amt=0;$d=0;
					 
					 for($d=0;$d<count($pay_detailss_rows);$d++){ $i++;
					$getnames=$obj_db->fetchRow("select b.first_name,b.last_name,a.roll_no,a.sec_id,a.student_status,a.admission_no from ".TABLE_STUDENT_EDU_DETAILS." a,
					 ".TABLE_STUDENTDETAILS." b  where a.student_id=b.student_id  and a.student_id='".$pay_detailss_rows[$d]['student_id']."'");
					 $dailyfetds[$d]['std_name']=$getnames['first_name'].' '.$getnames['last_name'];
					 $dailyfetds[$d]['course_name']=$pay_detailss_rows[$d]['course_name'];
						 $dailyfetds[$d]['pro_name']=$getnames['pro_name'];
					  	$dailyfetds[$d]['admission_no']=$getnames['admission_no'];
					 $dailyfetds[$d]['receipt_no']=$pay_detailss_rows[$d]['receipt_no'];
					 $dailyfetds[$d]['pay_name']=$pay_detailss_rows[$d]['pay_name'];
 					 $dailyfetds[$d]['bank_name']=$pay_detailss_rows[$d]['bank_name'];
					 $dailyfetds[$d]['cheque_num']=$pay_detailss_rows[$d]['cheque_num'];

					  $dailyfetds[$d]['dt']=date('d-m-Y',strtotime($pay_detailss_rows[$d]['dt']));
					 $dailyfetds[$d]['fee_name']=$pay_detailss_rows[$d]['fee_name'];
					 $dailyfetds[$d]['user_name']=$pay_detailss_rows[$d]['user_name'];
					 $dailyfetds[$d]['amt']=$pay_detailss_rows[$d]['amt'];
					 $total_amt=$total_amt+$pay_detailss_rows[$d]['amt'];
					}
					
					
					
					$std_otherfes=$obj_db->qry("SELECT a.id,fee_name,date(paid_date) as dt,recp_no,transaction_no,course_name,a.fee_amt  as amt,a.reason,a.rec_id,a.pay_type_id,a.student_id,a.year_id,c.pay_name,a.transaction_no,a.bank_name FROM ".TABLE_STD_OTHERFEES." a,".TABLE_FEE_TYPE." b, ".TABLE_PAYMENT_TYPE." c,".TABLE_USER_DETAILS." d,".TABLE_COURSE." f where a.fee_id=b.fee_id $cnd  and is_cancel='0' and a.user_id=d.user_id and a.pay_type_id=c.pay_type_id and f.course_type_id=3 and a.branch_id in(".$_SESSION['assign_branch_ids'].") and a.course_id=f.course_id $usrcnd and  date(enter_date) between '".trim($dt1)."' and '".trim($dt2)."'  order by a.id desc");
					for($i=0;$i<count($std_otherfes);$i++){
					 $std_dts=$obj_db->fetchRow("select first_name,last_name,b.admission_no from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b   where a.student_id='".$std_otherfes[$i]['student_id']."' and a.student_id=b.student_id  and b.y_id='".$std_otherfes[$i]['year_id']."'");
					$dailyfetds[$d]['std_name']=$std_dts['first_name'].' '.$std_dts['last_name'];
					 $dailyfetds[$d]['course_name']=$std_otherfes[$i]['course_name'];
						// $dailyfetds[$d]['pro_name']=$std_dts['pro_name'];
					  	$dailyfetds[$d]['admission_no']=$std_otherfes[$i]['admission_no'];
					 $dailyfetds[$d]['receipt_no']=$std_otherfes[$i]['recp_no'];
					 $dailyfetds[$d]['pay_name']=$std_otherfes[$i]['pay_name'];
 					 $dailyfetds[$d]['bank_name']=$std_otherfes[$i]['bank_name'];
					 $dailyfetds[$d]['cheque_num']=$std_otherfes[$i]['transaction_no'];

					  $dailyfetds[$d]['dt']=date('d-m-Y',strtotime($std_otherfes[$i]['dt']));
					 $dailyfetds[$d]['fee_name']=$std_otherfes[$i]['fee_name'];
					 $dailyfetds[$d]['user_name']=$std_otherfes[$i]['user_name'];
					 $dailyfetds[$d]['amt']=$std_otherfes[$i]['amt'];
					 $total_amt=$total_amt+$std_otherfes[$i]['amt'];
					$d++;
					}
					
					
					$dailyfetdss['tot']=number_format($total_amt,2);
					
			
					if($_REQUEST['course_id']==0)
					  $cnds="";
					 else $cnds=" and a.course_id ='".$_REQUEST['course_id']."'";
	$pay_details=$obj_db->get_qresult(" select b.first_name,b.last_name,e.course_name,a.sec_id,a.roll_no,date(paid_date) as pdt,d.user_name,sum(is_paid_amount) as pamt,c.cancel_reason,c.receipt_no,date(cancel_date) as cdt from ".TABLE_STUDENT_EDU_DETAILS." a,".TABLE_STUDENTDETAILS." b,".TABLE_FEE_PAYMENT." c,".TABLE_USER_DETAILS." d,".TABLE_COURSE." e where date(c.cancel_date) between '".trim($dt1)."' and '".trim($dt2)."' and c.receipt_cancelled=1 $cnds and a.student_id=b.student_id and a.student_id=b.student_id and b.student_id=c.student_id and a.student_id=c.student_id and e.course_type_id=3 and a.course_id=e.course_id and c.course_id=e.course_id and c.cancel_by='".$_SESSION['user_id']."' and a.course_model=1 and c.cancel_by=d.user_id  and a.y_id=c.year_id and c.year_id='".$_SESSION['year_id']."'  group by c.receipt_no");
 $c=0;
  while($pay_detailss_rows=$obj_db->fetchArray($pay_details)){ 
                     $dailycancels[$c]['std_name']=$pay_detailss_rows['first_name'].' '.$pay_detailss_rows['last_name'];
					 $dailycancels[$c]['course_name']=$pay_detailss_rows['course_name'];
					 $dailycancels[$c]['amount']=$pay_detailss_rows['pamt'];
					 
					 $dailycancels[$c]['user_name']=$pay_detailss_rows['user_name'];
					 $dailycancels[$c]['receipt_no']=$pay_detailss_rows['receipt_no'];
					  $dailycancels[$c]['cancel_date']=date('d-m-Y',strtotime($pay_detailss_rows['cdt']));
					   $dailycancels[$c]['paid_date']=date('d-m-Y',strtotime($pay_detailss_rows['pdt']));
					 $dailycancels[$c]['reason']=$pay_detailss_rows['cancel_reason'];
					 $c++;
  }
  
  $field_array=array("year_dts"=>$yardts_data,"year_amts"=>$yaramt_data,"payment_types"=>$fetch_data,"extitle"=>$title,"dailyfees"=>$dailyfetds,"grdtotal"=>number_format($total_amt,2),"cancelfees"=>$dailycancels);
	echo json_encode($field_array);

}

elseif($_REQUEST['action']=="monthly_daywisereport"){
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
    if($_REQUEST['branch_id']==0){
			  $brn_id=$_SESSION['assign_branch_ids'];
			  $selbrnid=$_SESSION['branch_id'];
			  }
			 else {
			 $brn_id=$_REQUEST['branch_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 }
			 
			 
			$dts1=strtotime($dt1);$dts2=strtotime($dt2);$datearray=array();
  for ($currentDate = $dts1; $currentDate <= $dts2; 
                                $currentDate += (86400)) {
                                      
$Store = date('Y-m-d', $currentDate);
$datearray[] = $Store;
}
			 
			 $branch_name=$obj_db->fetchRow("select branch_short_name,branch_city,branch_name from ".TABLE_BRANCH." where branch_id='".$_REQUEST['branch_id']."'");
		      $branch_name_city=$branch_name['branch_short_name'].'-'.$branch_name['branch_city'];
			 if($_REQUEST['branch_id']<1) 
			  $title='ALL BRANCH-['. $dt1. ' To ' . $dt2.']';
			 else  $title=$branch_name_city.'-['. $dt1. ' To ' . $dt2.']';
			 
  if($_REQUEST['course_id']==0)$var="";
						      else $var=" and  b.course_id='".$_REQUEST['course_id']."' ";

					 
					 
		 if($_REQUEST['course_id']==0)
					  $cnd="";
					 else $cnd=" and d.course_id ='".$_REQUEST['course_id']."'";
		$pay_details=$obj_db->qry("select sum(is_paid_amount) as amt,date(paid_date) as dt,b.course_id,b.course_name,b.course_type_id from ".TABLE_FEE_PAYMENT." a,".TABLE_COURSE." b where a.branch_id in(0".$selbrnid.")  and receipt_cancelled=0  $cnd and b.course_id=a.course_id  and date(a.paid_date) between '".trim($dt1)."' and '".trim($dt2)."' group by date(a.paid_date),b.course_type_id order by date(a.paid_date) asc,
b.course_type_id ");
    for($i=0;$i<count($datearray);$i++){
	if (array_search($datearray[$i], array_column($pay_details, 'dt')) !== FALSE && array_search(1, array_column($pay_details, 'course_type_id')) !== FALSE){
	 $arrsearch=multi_array_search($pay_details, array('dt' => $datearray[$i], 'course_type_id' => 1));
	 $mon_daycoldata[$i]['firstyrcolc']=$pay_details[$arrsearch]['amt'];
	 }else $mon_daycoldata[$i]['firstyrcolc']=0; 
	 
	 if (array_search($datearray[$i], array_column($pay_details, 'dt')) !== FALSE && array_search(2, array_column($pay_details, 'course_type_id')) !== FALSE){
	 $arrsearch=multi_array_search($pay_details, array('dt' => $datearray[$i], 'course_type_id' => 2));
	 $mon_daycoldata[$i]['secondyrcolc']=$pay_details[$arrsearch]['amt'];
	 }else $mon_daycoldata[$i]['secondyrcolc']=0;
	 $mon_daycoldata[$i]['date']=$datearray[$i];
     $mon_daycoldata[$i]['subtot']=$mon_daycoldata[$i]['firstyrcolc']+$mon_daycoldata[$i]['secondyrcolc'];
	 $first_yrgrdtot=$first_yrgrdtot+$mon_daycoldata[$i]['firstyrcolc'];
	 $second_yrgrdtot=$second_yrgrdtot+$mon_daycoldata[$i]['secondyrcolc'];
	 
	}
	 $grdtot=$first_yrgrdtot+$second_yrgrdtot;
					
  
  $field_array=array("mon_daycoldata"=>$mon_daycoldata,"firstyrtotamt"=>$first_yrgrdtot,"scndyrtotamt"=>$second_yrgrdtot,"extitle"=>$title,"grdtotal"=>number_format($grdtot,2));
	echo json_encode($field_array);
}

elseif($_REQUEST['action']=="opcashierwise_totreprot"){
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
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
			 
  if($_REQUEST['course_id']==0)$var="";
						      else $var=" and  b.course_id='".$_REQUEST['course_id']."' ";
							  
							  $get_usr=$obj_db->qry("select pay_name, sum(is_paid_amount) as amt,payment_type,b.user_id from ".TABLE_FEE_PAYMENT." b,".TABLE_PAYMENT_TYPE." a where a.pay_type_id=b.payment_type and 
				b.branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 group by user_id order by a.pay_type_id");

				 $paytype_ress=$obj_db->qry("select pay_name, sum(is_paid_amount) as amt,payment_type,b.user_id from ".TABLE_FEE_PAYMENT." b,".TABLE_PAYMENT_TYPE." a where a.pay_type_id=b.payment_type and 
				b.branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 group by payment_type order by a.pay_type_id");
					 $total_paytyps=0;
					 
					 $s=0;$paytyps=array();$arusrids=array();
					 for($i=0;$i<count($get_usr);$i++){
					  if(!in_array($get_usr[$i]['user_id'],$arusrids)){$arusrids[]=$get_usr[$i]['user_id'];
					 $usrids.=$get_usr[$i]['user_id'].',';
					 }
					 }
					 for($i=0;$i<count($paytype_ress);$i++){
					 $fetch_data[$s][$i]=$paytype_ress[$i]['pay_name'];
					 $paytyps[]=$paytype_ress[$i]['payment_type'];
					 
					 }
					 $s=1;
					 for($i=0;$i<count($paytype_ress);$i++){
					 $fetch_data[$s][$i]=$paytype_ress[$i]['amt'];
					 $total_paytyps= $total_paytyps+$paytype_ress[$i]['amt'];
					 }
					 $fetch_data[$s][$i]=number_format($total_paytyps,2);
					 
					 $trm_usrs=substr($usrids,0,-1);
					 
					  $usrdts=$obj_db->qry("select user_name,user_id from ".TABLE_USER_DETAILS."  where    user_id in(0".$trm_usrs.") order by user_id asc");
					 $total_paytyps=0;
					 $t=0;
					 for($m=0;$m<count($usrdts);$m++){
					  
					 $yaramt_data[$m][$t]=$usrdts[$m]['user_name'];
					 $t++;
					// $t=1;
					$tot_yramt=0;
					  for($s=0;$s<count($paytyps);$s++){
					 //  echo "select sum(is_paid_amount) as amt from ".TABLE_FEE_PAYMENT." where  date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' and user_id='".$usrdts[$m]['user_id']."' and payment_type='".$paytyps[$s]."' $var and receipt_cancelled=0    order by user_id asc";
					   $usrwise_totpaymets=$obj_db->fetchRow("select sum(is_paid_amount) as amt from ".TABLE_FEE_PAYMENT." where  date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' and user_id='".$usrdts[$m]['user_id']."' and payment_type='".$paytyps[$s]."' $var and receipt_cancelled=0  order by user_id asc");
					 $yaramt_data[$m][$t]=$usrwise_totpaymets['amt'];
					 $tot_yramt=$tot_yramt+$usrwise_totpaymets['amt'];
					 $t++;
					 
					 $sum[$m][$s]=$sum[$m][$s]+$usrwise_totpaymets['amt'];
					 }$t++;
					 $yaramt_data[$m][$t]=$tot_yramt;
					 $t++;
					 }
					
					for($j=0;$j<sizeof($sum[0]);$j++){
	 for($m=0;$m<sizeof($sum);$m++)
 		{ $subtotal[$j]=$subtotal[$j]+$sum[$m][$j]; }
		}//print_r($subtotal);	
        $yaramt_data[$m][$t]="Total";
		for($s=0;$s<count($subtotal);$s++){//$q=$t+1;
		$t++;
		$yaramt_data[$m][$t]=$subtotal[$s];
		$gtot=$gtot+$subtotal[$s];
		
		}$t++;$yaramt_data[$m][$t]=$gtot;
		//print_r($yaramt_data);
					 $totyramt=number_format($tot_yramt,2);
					
  
  $field_array=array("tot_yramt"=>$totyramt,"year_amts"=>$yaramt_data,"payment_types"=>$fetch_data,"extitle"=>$title);
	echo json_encode($field_array);
}

 elseif($_REQUEST['action']=="daily_totcolcamt"){
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
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
			 
				 $fee_paymentqry=$obj_db->qry("select date(paid_date) as pdt from ".TABLE_FEE_PAYMENT."  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 group by date(paid_date) order by date(paid_date) asc");
				
				 $paytype_nameqry=$obj_db->qry("select pay_name,pay_type_id from ".TABLE_PAYMENT_TYPE."  ");
			
					 for($s=0;$s<count($paytype_nameqry);$s++){
					 $pay_names[$s]=$paytype_nameqry[$s]['pay_name'];
					 }
					 					
					for($j=0;$j<count($fee_paymentqry);$j++){
					 $s=0;$sno=$j+1;
					  $fetch_data[$j][$s]=$sno;
					 $s++;
					 $fetch_data[$j][$s]=date('d-m-Y',strtotime($fee_paymentqry[$j]['pdt']));
					 $s++;
					 $sub_tot=0;
					   for($q=0;$q<count($paytype_nameqry);$q++){
					     $fee_payamt=$obj_db->fetchRow("select sum(is_paid_amount) as pamt from ".TABLE_FEE_PAYMENT."  where branch_id in(".$brn_id.") and date(paid_date)='".$fee_paymentqry[$j]['pdt']."' and payment_type='".$paytype_nameqry[$q]['pay_type_id']."' and receipt_cancelled=0 ");
					 $fetch_data[$j][$s]=$fee_payamt['pamt'];
					 $sub_tot=$sub_tot+$fee_payamt['pamt'];
					  $s++;
					 }
					$s=$s+1;
					 $fetch_data[$j][$s]=$sub_tot;
					 }
					$j++;
					$r=0;
					$tcnt=sizeof($fetch_data[0])-1;
					 for($p=2;$p<sizeof($fetch_data[0]);$p++){
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
		
  $field_array=array("payment_amts"=>$fetch_data,"extitle"=>$title,"pay_names"=>$pay_names,"grdtotal"=>$subtotal);
	echo json_encode($field_array);
}




 elseif($_REQUEST['action']=="daily_totcolcamts"){ 
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
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
			 $fids="1,4,10";
			 $expfid=explode(',',$fids);
			 $fee_types=$obj_db->fetchRow("select group_concat(fee_id) as fid from ".TABLE_FEE_TYPE." where fee_id not in(".$fids.")");
			 $exp_othfid=explode(',',$fee_types['fid']);
			// echo "select date(paid_date) as pdt,sum(is_paid_amount) as pamt,payment_type as pay_type_id,a.fee_id,b.course_type_id from ".TABLE_FEE_PAYMENT." a,".TABLE_COURSE." b  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 and a.course_id=b.course_id  group by date(paid_date),a.id order by date(paid_date) asc";
				 $fee_paymentqry=$obj_db->qry("select date(paid_date) as pdt,sum(is_paid_amount) as pamt,payment_type as pay_type_id,a.fee_id,b.course_type_id from ".TABLE_FEE_PAYMENT." a,".TABLE_COURSE." b  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 and a.course_id=b.course_id  group by date(paid_date),a.id order by date(paid_date) asc");
				 
				  $fepay_dats = array_map(function ($value) {
    return  $value['pdt'];
}, $fee_paymentqry);
				 
				 if(count($fepay_dats)==0)
				   $fepay_dats=array();
				  else $fepay_dats=$fepay_dats;
				
				 
				 $longtermfearr=array();$genfearr=array();$othfearr=array();
				 foreach($fee_paymentqry as $key=>$value){
				  if($value['course_type_id']==3)
				   $longtermfearr[]=$value;
				  elseif(in_array($value['fee_id'],$expfid))
				   $genfearr[]=$value;
				  else $othfearr[]=$value;
				 }
				 
				 
				 /*echo '<pre>';
				 print_r($genfearr);
				 print_r($longtermfearr);
				 print_r($othfearr);
				 echo '</pre>';*/
				 
				  $othrfees=$obj_db->qry("select date(paid_date) as pdt,sum(fee_amt) as pamt,pay_type_id,a.fee_id,b.course_type_id from ".TABLE_STD_OTHERFEES." a,".TABLE_COURSE." b  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and is_cancel=0  and a.course_id=b.course_id group by date(paid_date),a.id order by date(paid_date) asc");
				  
				  $othfepay_dats = array_map(function ($value) {
    return  $value['pdt'];
}, $othrfees); 
				  if(count($othfepay_dats)==0)
				   $othfepay_dats=array();
				  else $othfepay_dats=$othfepay_dats;
				  
				 $all_dats = array_unique(array_merge($fepay_dats, $othfepay_dats));
  $s=0;
 
 foreach($all_dats  as $key=>$value){
  $all_dates[$s]=$value;
   $s++;
 } 
				  foreach($othrfees as $key=>$value){
				 $othfearr[]=$value;
				 }
				 
				 
		//	print_r($fee_paymentqry);	
				 $paytype_nameqry=$obj_db->qry("select pay_name,pay_type_id from ".TABLE_PAYMENT_TYPE."  ");
			
					 for($s=0;$s<count($paytype_nameqry);$s++){
					 $pay_names[$s]=$paytype_nameqry[$s]['pay_name'];
					 }
					 				$all_grdtot=0;	
									$j=0;
					for($w=0;$w<count($all_dates);$w++){
					 $datkeys = array_keys(array_column($genfearr, 'pdt'),$all_dates[$w]);
					 $genfedatars=array();
					 if(count($datkeys)>0){
					 for($b=0;$b<count($datkeys);$b++){
					  $genfedatars[]=$genfearr[$datkeys[$b]];
					 }/*echo '<pre>';
					 print_r($genfedatars);echo '</pre>';*/
					 $s=0;$sno=$j+1;
					  $fetch_data[$j][$s]=$sno;
					 $s++;
					 $fetch_data[$j][$s]=date('d-m-Y',strtotime($all_dates[$w]));
					 $s++;
					 $sub_tot=0;
					 
					   for($q=0;$q<count($paytype_nameqry);$q++){
					   $keys = array_keys(array_column($genfedatars, 'pay_type_id'),$paytype_nameqry[$q]['pay_type_id']);
					    $tgsum=0;
						 for($t=0;$t<count($keys);$t++){
						   $tgsum=$tgsum+$genfedatars[$keys[$t]]['pamt'];
						 }

					 //    $fee_payamt=$obj_db->fetchRow("select sum(is_paid_amount) as pamt from ".TABLE_FEE_PAYMENT."  where branch_id in(".$brn_id.") and date(paid_date)='".$fee_paymentqry[$j]['pdt']."' and payment_type='".$paytype_nameqry[$q]['pay_type_id']."' and receipt_cancelled=0 ");
					 $fetch_data[$j][$s]=$tgsum;
					 $sub_tot=$sub_tot+$tgsum;
					  $s++;
					 }
					$s=$s+1;
					 $fetch_data[$j][$s]=$sub_tot;
					 $j++;
					 }
					 }
					$j++;
					$r=0;
					$tcnt=sizeof($fetch_data[0])-1;
					
					 for($p=2;$p<sizeof($fetch_data[0]);$p++){
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
	 
					 					
					$j=0;
					for($w=0;$w<count($all_dates);$w++){
					 $datkeys = array_keys(array_column($othfearr, 'pdt'),$all_dates[$w]);
					 $othfedatars=array();
					 if(count($datkeys)>0){
					 for($b=0;$b<count($datkeys);$b++){
					  $othfedatars[]=$othfearr[$datkeys[$b]];
					 }
					 
					 
					 $s=0;$sno=$j+1;
					  $fetch_othdata[$j][$s]=$sno;
					 $s++;
					 $fetch_othdata[$j][$s]=date('d-m-Y',strtotime($all_dates[$w]));
					 $s++;
					 $sub_tot=0;
					   for($q=0;$q<count($paytype_nameqry);$q++){
					   $keys = array_keys(array_column($othfedatars, 'pay_type_id'),$paytype_nameqry[$q]['pay_type_id']);
					    $tgsum=0;
						 for($t=0;$t<count($keys);$t++){
						   $tgsum=$tgsum+$othfedatars[$keys[$t]]['pamt'];
						 }

					 //    $fee_payamt=$obj_db->fetchRow("select sum(is_paid_amount) as pamt from ".TABLE_FEE_PAYMENT."  where branch_id in(".$brn_id.") and date(paid_date)='".$fee_paymentqry[$j]['pdt']."' and payment_type='".$paytype_nameqry[$q]['pay_type_id']."' and receipt_cancelled=0 ");
					 $fetch_othdata[$j][$s]=$tgsum;
					 $sub_tot=$sub_tot+$tgsum;
					  $s++;
					 }
					$s=$s+1;
					 $fetch_othdata[$j][$s]=$sub_tot;
					 $j++;
					 }
					 }
					$j++;
					$r=0;
					$tcnt=sizeof($fetch_othdata[0])-1;
					 for($p=2;$p<sizeof($fetch_othdata[0]);$p++){
	 for($i=0;$i<sizeof($fetch_othdata);$i++)
 		{
		if($p!=$tcnt)
		 $othsubtotal[$r]=$othsubtotal[$r]+$fetch_othdata[$i][$p]; }
		$r++;
		}
	 	$grand_sum=0;  $r++;
  for($p=0;$p<sizeof($othsubtotal);$p++)
	{	
	  $grand_sum=$grand_sum+$othsubtotal[$p];
	 }
	  $othsubtotal[$r]=$grand_sum;
	  
	  
	  $all_grdtot=$all_grdtot+$grand_sum;
	  
	  

					 					
					
					$j=0;
					for($w=0;$w<count($all_dates);$w++){
					 $datkeys = array_keys(array_column($longtermfearr, 'pdt'),$all_dates[$w]);
					 $longtrmfedatars=array();
					 if(count($datkeys)>0){
					 for($b=0;$b<count($datkeys);$b++){
					  $longtrmfedatars[]=$longtermfearr[$datkeys[$b]];
					 }
					
					 $s=0;$sno=$j+1;
					  $fetch_longdata[$j][$s]=$sno;
					 $s++;
					 $fetch_longdata[$j][$s]=date('d-m-Y',strtotime($all_dates[$w]));
					 $s++;
					 $sub_tot=0;
					   for($q=0;$q<count($paytype_nameqry);$q++){
					   $keys = array_keys(array_column($longtrmfedatars, 'pay_type_id'),$paytype_nameqry[$q]['pay_type_id']);
					    $tgsum=0;
						 for($t=0;$t<count($keys);$t++){
						   $tgsum=$tgsum+$longtrmfedatars[$keys[$t]]['pamt'];
						 }

					 //    $fee_payamt=$obj_db->fetchRow("select sum(is_paid_amount) as pamt from ".TABLE_FEE_PAYMENT."  where branch_id in(".$brn_id.") and date(paid_date)='".$fee_paymentqry[$j]['pdt']."' and payment_type='".$paytype_nameqry[$q]['pay_type_id']."' and receipt_cancelled=0 ");
					 $fetch_longdata[$j][$s]=$tgsum;
					 $sub_tot=$sub_tot+$tgsum;
					  $s++;
					 }
					$s=$s+1;
					 $fetch_longdata[$j][$s]=$sub_tot;
					 $j++;
					 }
					 }
					$j++;
					$r=0;
					$tcnt=sizeof($fetch_longdata[0])-1;
					 for($p=2;$p<sizeof($fetch_longdata[0]);$p++){
	 for($i=0;$i<sizeof($fetch_longdata);$i++)
 		{
		if($p!=$tcnt)
		 $lonftrmsubtotal[$r]=$lonftrmsubtotal[$r]+$fetch_longdata[$i][$p]; }
		$r++;
		}
	 	$grand_sum=0;  $r++;
  for($p=0;$p<sizeof($lonftrmsubtotal);$p++)
	{	
	  $grand_sum=$grand_sum+$lonftrmsubtotal[$p];
	 }
	  $lonftrmsubtotal[$r]=$grand_sum;
	  $all_grdtot=$all_grdtot+$grand_sum;
	 /* echo '<pre>';
	  print_r($fetch_data);
	  echo '</pre>';
	  
	   echo '<pre>';
	  print_r($fetch_othdata);
	  echo '</pre>';
	  
	   echo '<pre>';
	  print_r($fetch_longdata);
	  echo '</pre>';*/
  $field_array=array("payment_amts"=>$fetch_data,"grdtotal"=>$subtotal,"oth_amts"=>$fetch_othdata,"othtotal"=>$othsubtotal,"longterm_data"=>$fetch_longdata,"longtrmtotal"=>$lonftrmsubtotal,"extitle"=>$title,"pay_names"=>$pay_names,"all_grdtot"=>$all_grdtot);
	echo json_encode($field_array);
}


 elseif($_REQUEST['action']=="datewise_userwise_feewisecollection"){ 
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
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
			 $fids="1,4,10";
			 $expfid=explode(',',$fids);
			 $fee_types=$obj_db->fetchRow("select group_concat(fee_id) as fid from ".TABLE_FEE_TYPE." ");
			 $exp_othfid=explode(',',$fee_types['fid']);
			  $fee_types=$obj_db->qry("select * from ".TABLE_FEE_TYPE."  ");
				 $fee_paymentqry=$obj_db->qry("select date(paid_date) as pdts,sum(is_paid_amount) as pamt,payment_type as pay_type_id,a.fee_id,b.course_type_id,a.user_id from ".TABLE_FEE_PAYMENT." a,".TABLE_COURSE." b  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0  and a.course_id=b.course_id  group by date(paid_date),a.user_id asc,a.fee_id asc order by date(paid_date) asc,a.user_id asc,a.fee_id asc");
				 
				  $fepay_fids = array_map(function ($value) {
    return  $value['fee_id'];
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
				
				  $othrfees=$obj_db->qry("select date(paid_date) as opdt,sum(fee_amt) as pamt,pay_type_id,a.fee_id,b.course_type_id,a.user_id from ".TABLE_STD_OTHERFEES." a,".TABLE_COURSE." b  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and is_cancel=0   and a.course_id=b.course_id group by date(paid_date),a.user_id,a.fee_id order by date(paid_date) asc,a.user_id asc,a.fee_id asc");
				   $othfepay_fids = array_map(function ($value) {
    return  $value['fee_id'];
}, $othrfees);

if(count($othfepay_fids)==0)
 $othfepayfids=array();
 else $othfepayfids=$othfepay_fids;
				  
				$alfeids = array_unique(array_merge($fepayfids, $othfepayfids));
 
 foreach($alfeids  as $key=>$value){
  $feidss.=$value.',';
 } 	
  $feidss=substr($feidss,0,-1);
     $fenames=$obj_db->qry("select * from ".TABLE_FEE_TYPE." where fee_id in(0".$feidss.") order by fee_id asc");
   for($i=0;$i<count($fenames);$i++)
    $fee_namearr[$fenames[$i]['fee_id']]=$fenames[$i]['fee_name'];
				  
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
 
 foreach($all_usrids  as $key=>$value){
  $usrids.=$value.',';
 } 	
  $trm_usrids=substr($usrids,0,-1);
  $usr_dts=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where user_id in(0".$trm_usrids.")");
   for($i=0;$i<count($usr_dts);$i++)
    $user_namearr[$usr_dts[$i]['user_id']]=$usr_dts[$i]['user_name'];
  	
		//	print_r($fee_paymentqry);	
				 $paytype_nameqry=$obj_db->qry("select pay_name,pay_type_id from ".TABLE_PAYMENT_TYPE."  ");
			
					 for($s=0;$s<count($paytype_nameqry);$s++){
					 $pay_names[$s]=$paytype_nameqry[$s]['pay_name'];
					 }
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
					   $keys = array_keys(array_column($usrfee_arrs, 'fee_id'),$key);
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
//	 echo '<pre>'; print_r($fetch_data);print_r($subtotal);echo '<pre>';

  $field_array=array("payment_amts"=>$fetch_data,"grdtotal"=>$subtotal,"all_grdtot"=>$all_grdtot,'fee_names'=>$fenames);
	echo json_encode($field_array);
}

 elseif($_REQUEST['action']=="datewise_userwise_paywisecollection"){
 

  
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
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
			 $fids="1,4,10";
			 $expfid=explode(',',$fids);
			 $fee_types=$obj_db->fetchRow("select group_concat(fee_id) as fid from ".TABLE_FEE_TYPE." where fee_id not in(".$fids.")");
			 $exp_othfid=explode(',',$fee_types['fid']);
			  $fee_types=$obj_db->qry("select * from ".TABLE_FEE_TYPE." where fee_id not in(".$fids.")");
			//  $pay_types=$obj_db->qry("select * from ".TABLE_PAYMENT_TYPE." ");
				 $fee_paymentqry=$obj_db->qry("select date(paid_date) as pdts,sum(is_paid_amount) as pamt,payment_type as pay_type_id,a.fee_id,b.course_type_id,a.user_id from ".TABLE_FEE_PAYMENT." a,".TABLE_COURSE." b  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0  and a.course_id=b.course_id  group by date(paid_date),a.user_id asc,a.payment_type asc order by date(paid_date) asc,a.user_id asc,a.payment_type asc");
				 
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
				
				  $othrfees=$obj_db->qry("select date(paid_date) as opdt,sum(fee_amt) as pamt,pay_type_id,a.fee_id,b.course_type_id,a.user_id from ".TABLE_STD_OTHERFEES." a,".TABLE_COURSE." b  where branch_id in(".$brn_id.") and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and is_cancel=0 and a.course_id=b.course_id group by date(paid_date),a.user_id,a.pay_type_id order by date(paid_date) asc,a.user_id asc,a.pay_type_id asc");
				   $othfepay_fids = array_map(function ($value) {
    return  $value['pay_type_id'];
}, $othrfees);

if(count($othfepay_fids)==0)
 $othfepayfids=array();
 else $othfepayfids=$othfepay_fids;
				  
 $alfeids = array_unique(array_merge($fepayfids, $othfepayfids));
 
 foreach($alfeids  as $key=>$value){
  $feidss.=$value.',';
 } 	
  $feidss=substr($feidss,0,-1);
     $fenames=$obj_db->qry("select * from ".TABLE_PAYMENT_TYPE." where pay_type_id in(0".$feidss.") order by pay_type_id asc");
   for($i=0;$i<count($fenames);$i++)
    $fee_namearr[$fenames[$i]['pay_type_id']]=$fenames[$i]['pay_name'];
				  
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
 
 foreach($all_usrids  as $key=>$value){
  $usrids.=$value.',';
 } 	
  $trm_usrids=substr($usrids,0,-1);
  $usr_dts=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where user_id in(0".$trm_usrids.")");
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
//	 echo '<pre>'; print_r($fetch_data);print_r($subtotal);echo '<pre>';

  $field_array=array("payment_amts"=>$fetch_data,"grdtotal"=>$subtotal,"all_grdtot"=>$all_grdtot,'fee_names'=>$fenames);
	echo json_encode($field_array);

 

 }

elseif($_REQUEST['action']=='brnwise_academic_monreport'){
$split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= date('Y-m-d',strtotime($split_dt[0]));
 $dt2= date('Y-m-d',strtotime($split_dt[1]));
 if($_REQUEST['dat_range']==''){
 $dt1=date('Y-m-d');
 $dt2=date('Y-m-d');
 }
    if($_REQUEST['branch_id']==0){
			  $brn_id=$_SESSION['assign_branch_ids'];
			  $selbrnid=$_SESSION['branch_id'];
			  }
			 else {
			 $brn_id=$_REQUEST['branch_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 }
			 $branch_name=$obj_db->qry("select branch_short_name,branch_city,branch_name,branch_id from ".TABLE_BRANCH." where branch_id in(0".$brn_id.")");
		      $branch_name_city=$branch_name[0]['branch_short_name'].'-'.$branch_name[0]['branch_city'];
			 if($_REQUEST['branch_id']<1) 
			  $title='ALL BRANCH-['. $dt1. ' To ' . $dt2.']';
			 else  $title=$branch_name_city.'-['. $dt1. ' To ' . $dt2.']';
			 
 $monthsarr=array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'June',7=>'July',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
 $fee_paymentqry=$obj_db->qry("SELECT month(paid_date) as paymonth,date(paid_date) as pdts,sum(is_paid_amount) as pamt,branch_id FROM `".TABLE_FEE_PAYMENT."` WHERE date(paid_date) BETWEEN '".trim($dt1)."' and '".trim($dt2)."' and receipt_cancelled=0 group by month(paid_date),branch_id ORDER by date(paid_date) asc");
  $othrfees=$obj_db->qry("SELECT month(paid_date) as paymonth,date(paid_date) as pdts,sum(fee_amt)  as pamt,branch_id FROM ".TABLE_STD_OTHERFEES." WHERE date(paid_date) BETWEEN '".trim($dt1)."' and '".trim($dt2)."' and is_cancel=0 group by month(paid_date),branch_id ORDER by date(paid_date) asc");
  
  
  
    $fepay_mons= array_map(function ($value) {
    return  $value['paymonth'];
}, $fee_paymentqry);
if(count($fepay_mons)==0)
 $fepaymons=array();
 else $fepaymons=$fepay_mons;
				 
				  $otfepay_dats = array_map(function ($value) {
    return  $value['paymonth'];
}, $othrfees);

 if(count($otfepay_dats)==0)
   $otfepaydts=array();
 else $otfepaydts=$otfepay_dats;
 
 $all_dats = array_unique(array_merge($fepaymons, $otfepaydts));
  $s=0;
 foreach($all_dats  as $key=>$value){
  $all_mons[$s]=$value;
   $s++; 
 } 
 $j=0;
 for($w=0;$w<count($all_mons);$w++){
					 $datkeys = array_keys(array_column($fee_paymentqry, 'paymonth'),$all_mons[$w]);
					 $othdatkeys = array_keys(array_column($othrfees, 'paymonth'),$all_mons[$w]);
					 $genfedatars=array();
					 
					
					 if(count($datkeys)>0 || count($othdatkeys)>0){
					 for($b=0;$b<count($datkeys);$b++){
					  $genfedatars[]=$fee_paymentqry[$datkeys[$b]];
					 }
					  for($b=0;$b<count($othdatkeys);$b++){
					  $genfedatars[]=$othrfees[$othdatkeys[$b]];
					 }
					  $s=0;$sno=$j+1;

					  $fetch_data[$j][$s]=$sno;
					 $s++;
					 $fetch_data[$j][$s]=$monthsarr[$all_mons[$w]];
					 $s++;$sub_tot=0;
					 for($t=0;$t<count($branch_name);$t++){
 					    $brnfe_arrkys=array_keys(array_column($genfedatars, 'branch_id'),$branch_name[$t]['branch_id']);
						$brn_tot=0;
						foreach($brnfe_arrkys as $key=>$value){
						 $brn_tot=$brn_tot+$genfedatars[$value]['pamt'];
						}
						$fetch_data[$j][$s]=$brn_tot;
						
					
						 $sub_tot=$sub_tot+$brn_tot;
					 $s++;
					 
					
					/*------ */
					}
					 
					 $fetch_data[$j][$s]=$sub_tot;
					 $j++;
					 }
					
					 }
					
					$r=0;
					$tcnt=sizeof($fetch_data[0])-1;
					
					 for($p=2;$p<sizeof($fetch_data[0]);$p++){
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
	  
	  $field_array=array("payment_amts"=>$fetch_data,"grdtotal"=>$subtotal,"all_grdtot"=>$all_grdtot,'brn_names'=>$branch_name);
	 // echo '<pre>';print_r($field_array);echo '</pre>';
	echo json_encode($field_array);
}

elseif($_REQUEST['action']=='get_branch_courses'){
  if($_REQUEST['branch_id']==0)
   $brn_id=$_SESSION['assign_branch_ids'];
  else $brn_id=$_REQUEST['branch_id'];
  $qgetcourses =  $obj_db->qry("select a.course_id,a.course_name from ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where a.course_id=b.course_id and branch_id in(".$brn_id.") order by course_id asc");
  echo json_encode($qgetcourses);
}

elseif($_REQUEST['action']=='get_branch_regcourses'){
  if($_REQUEST['branch_id']==0)
   $brn_id=$_SESSION['assign_branch_ids'];
  else $brn_id=$_REQUEST['branch_id'];
  $qgetcourses =  $obj_db->qry("select a.course_id,a.course_name from ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where a.course_id=b.course_id and a.course_type_id!=3 and branch_id in(".$brn_id.") order by course_id asc");
  echo json_encode($qgetcourses);
}

elseif($_REQUEST['action']=='get_branch_neetcourses'){
  if($_REQUEST['branch_id']==0)
   $brn_id=$_SESSION['assign_branch_ids'];
  else $brn_id=$_REQUEST['branch_id'];
  $qgetcourses =  $obj_db->qry("select a.course_id,a.course_name from ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where a.course_id=b.course_id and a.course_type_id=3 and branch_id in(".$brn_id.") order by course_id asc");
  echo json_encode($qgetcourses);
}

elseif($_REQUEST['action']=='get_branch_course_terms'){
  if($_REQUEST['branch_id']==''){
   $brn_ids=$_SESSION['assign_branch_ids'];
   $brn_id=$_SESSION['branch_id'];
   }
  else {
   $brn_ids=$_REQUEST['branch_id'];
   $brn_id=$_REQUEST['branch_id'];
   }
  
  $b=0;
  $qgetbranches =  $obj_db->get_qresult("select branch_short_name,branch_name,branch_id from ".TABLE_BRANCH."  where branch_id in(".$brn_ids.") order by branch_id asc");
  while($qgetbranchrw=$obj_db->fetchArray($qgetbranches)){
   $branch_dts[$b]['branch_id']=$qgetbranchrw['branch_id'];
   $branch_dts[$b]['branch_name']=$qgetbranchrw['branch_name'];
   $b++;
  }
  
  $qgetsectoins =  $obj_db->get_qresult("select a.course_id,a.course_name from ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where a.course_id=b.course_id and branch_id in(".$brn_id.") order by course_id asc");$c=0;
  while($qgetsecrw=$obj_db->fetchArray($qgetsectoins)){
   $sec_dts[$c]['course_id']=$qgetsecrw['course_id'];
   $sec_dts[$c]['course_name']=$qgetsecrw['course_name'];
   $c++;
  }
  
$d=0;
$get_brnmaxtrm=$obj_db->qry("select * from ".TABLE_YEARLY_COURSEFEES_MAPPING." where branch_id='".$brb_id."' and course_id=0");
  for($i=1;$i<=$get_brnmaxtrm[0]['max_term'];$i++){
   $trmdts[$d]['term']=$i;
   $d++;
  }
  $fild_array=array("branchlist"=>$branch_dts,"courselst"=>$sec_dts,"termdts"=>$trmdts);
  echo json_encode($fild_array);
}

elseif($_REQUEST['action']=='get_branch_sections_terms'){
  if($_REQUEST['branch_id']==''){
   $brn_ids=$_SESSION['assign_branch_ids'];
   $brn_id=$_SESSION['branch_id'];
   }
  else {
   $brn_ids=$_REQUEST['branch_id'];
   $brn_id=$_REQUEST['branch_id'];
   }
  $branch_dts=array();
  $b=0;
  $qgetbranches =  $obj_db->qry("select branch_short_name,branch_name,branch_id from ".TABLE_BRANCH."  where branch_id in(".$brn_ids.") order by branch_id asc");
  foreach($qgetbranches as $k=>$v){
  
    $myarray[$v['branch_id']]['branch_id']= $v['branch_id'];						
    $myarray[$v['branch_id']]['branch_name']= $v['branch_name'];	
  
   $courseqry =  $obj_db->qry("select a.course_id,a.course_name,b.branch_id from ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where a.course_id=b.course_id and b.branch_id='".$v['branch_id']."'  order by course_id asc");
   $n=0;
   $myarray[$v['branch_id']]['course'][$n] = array("branch_id"=>0,"course_id"=>'all',"course_name"=>'All');
   
						for($n=0; $n<count($courseqry); $n++)
						{	
						  $myarray[$v['branch_id']]['course'][$courseqry[$n]['course_id']] = array("branch_id"=>$v['branch_id'],"course_id"=>$courseqry[$n]['course_id'],"course_name"=>$courseqry[$n]['course_name']);
						  
						    $yrsecmap_ids=$obj_db->fetchRow("select sec_ids from ".TABLE_YEARLY_SECTION_MAPPING." where  course_id='0' and year_id='".$_SESSION['year_id']."' and branch_id='".$brn_id."'");	
  $qgetsectoins =  $obj_db->qry("select a.sec_id,a.sec_name,a.course_id,a.branch_id from ".TABLE_SECTION." a,".TABLE_BRANCH." b where a.branch_id=b.branch_id and a.branch_id in(".$brn_id.") and a.sec_id in(0".$yrsecmap_ids['sec_ids'].") and a.course_id='".$courseqry[$n]['course_id']."' order by course_id asc");
   foreach($qgetsectoins as $sk=>$sv)
  $myarray[$v['branch_id']]['course'][$courseqry[$n]['course_id']]['secs'][] = array("branch_id"=>$v['branch_id'],"sec_id"=>$sv['sec_id'],"sec_name"=>$sv['sec_name']);
  
						}
      $b++;
  }
  
   
  $yrsecmap_ids=$obj_db->fetchRow("select sec_ids from ".TABLE_YEARLY_SECTION_MAPPING." where  course_id='0' and year_id='".$_SESSION['year_id']."' and branch_id='".$brn_id."'");	
  $qgetsectoins =  $obj_db->qry("select a.sec_id,a.sec_name,a.course_id,a.branch_id from ".TABLE_SECTION." a,".TABLE_BRANCH." b where a.branch_id=b.branch_id and a.branch_id in(".$brn_id.") and a.sec_id in(0".$yrsecmap_ids['sec_ids'].") order by course_id asc");$c=0;
 
  $get_brnmaxtrm=$obj_db->qry("select * from ".TABLE_YEARLY_COURSEFEES_MAPPING." where branch_id='".$brn_id."' and year_id='".$_SESSION['year_id']."' and course_id=0");
  $d=0;
  for($i=1;$i<=$get_brnmaxtrm[0]['max_term'];$i++){
   $trmdts[$d]['term']=$i;
   $d++;
  }
  $fild_array=array("branchlist"=>$myarray,"seclist"=>$qgetsectoins,"termdts"=>$trmdts);
  echo json_encode($fild_array);
}

elseif($_REQUEST['action']=="std_fee_due"){
	$pagesize = $_GET['limit'];
    for($h=1;$h<=$_REQUEST['terms'];$h++){
					  $tterms.=$h.',';
					  }
					  $trimterms=substr($tterms,0,-1);
   $sec_name=$obj_db->fetchRow("select sec_name from ".TABLE_SECTION." where  sec_id='".$_REQUEST['sec_id']."'");
			 $branch_sec=$obj_db->fetchRow("select branch_short_name from ".TABLE_BRANCH."  where branch_id='".$_REQUEST['branchid']."' ");
			 if($_REQUEST['sec_id']<1)
			  $secname=" Over All Students ";
			 else $secname=$sec_name['sec_name'];
			
		
			
			if($_REQUEST['sec_id']>0 && $_REQUEST['course_id']>0)
			{$course_secname=$branch_sec['branch_short_name'].'-'.$secname;
			 $cnd=" and c.sec_id='".$_REQUEST['sec_id']."' and c.course_id='".$_REQUEST['course_id']."'";
			 }
			 elseif($_REQUEST['course_id']>0)
			{$course_secname=$branch_sec['branch_short_name'].'-'.$secname;
			 $cnd=" and c.course_id='".$_REQUEST['course_id']."' ";
			 }
			 elseif($_REQUEST['sec_id']>0 )
			{$course_secname=$branch_sec['branch_short_name'].'-'.$secname;
			 $cnd=" and c.sec_id='".$_REQUEST['sec_id']."' ";
			 }
			else {$course_secname=$branch_sec['branch_short_name'].'-'.$secname;
			$cnd=" ";
			}	
			$exids=$course_secname;
			if($pagesize==0){
			$count=$obj_db->fetchNum("select a.student_id,first_name,last_name,roll_no,term_amount,term_due,fee_type,gender,mobile_no,c.course_id from ".TABLE_STUDENT_FEE." a,".TABLE_STUDENTDETAILS." b,".TABLE_STUDENT_EDU_DETAILS." c where branch_id='".$_REQUEST['branchid']."' $cnd and c.is_delete=0 and a.student_id=b.student_id and a.student_id=c.student_id and course_model=1 and term in(".$trimterms.") and a.course_id=c.course_id and a.y_id=c.y_id  and a.y_id=".$_SESSION['year_id']." group by a.student_id having sum(term_due)>0 order by c.course_id asc,sum(term_due) asc ");
			if($_REQUEST['course_id']>0)
			  $courseid=$_REQUEST['course_id'];
			 else $courseid=0;
			 $get_yrfids=$obj_db->fetchRow("select duefee_ids from ".TABLE_YEARLY_COURSEFEES_MAPPING." where branch_id='".$_REQUEST['branchid']."' and year_id='".$_SESSION['year_id']."' and course_id='".$courseid."'");
             $brancfees="select b.fee_id,b.fee_name from ".TABLE_FEE_TYPE." b where  fee_id in(0".$get_yrfids['duefee_ids'].") ";
	                    $branchfees=$obj_db->qry($brancfees);
 						$tdnum=8+count($branchfees);
						$i=0;
					    $s=0;$p=3;
						foreach($branchfees as $k=>$v){ 
						 $fetch_heads[$s][$p]=$v['fee_name'];
						$apdfee_id.=$v['fee_id'].',';
						  $p++;
						  $i++; }
						  $trm_feid=substr($apdfee_id,0,-1);
						 $retfeids=$trm_feid;
						 $fee_id=explode(',',$trm_feid);
						 $count=$count;
						} 
						else{
						 $fee_id=explode(',',$_REQUEST['fee_ids']);
						  $retfeids=$_REQUEST['fee_ids'];
						  $count=$_REQUEST['tot_count'];
						}
						 
				
	 $std_due_details="select a.student_id,first_name,last_name,roll_no,term_amount,term_due,fee_type,gender,mobile_no,c.course_id from ".TABLE_STUDENT_FEE." a,".TABLE_STUDENTDETAILS." b,".TABLE_STUDENT_EDU_DETAILS." c where branch_id='".$_REQUEST['branchid']."' $cnd and c.is_delete=0 and a.student_id=b.student_id and a.student_id=c.student_id and course_model=1 and term in(".$trimterms.") and a.course_id=c.course_id and a.y_id=c.y_id  and a.y_id=".$_SESSION['year_id']." group by a.student_id having sum(term_due)>0 order by sum(term_due) asc,cast(c.course_id as decimal(5,2)) asc,CAST(roll_no as UNSIGNED) ,first_name asc,last_name asc  limit $pagesize, 1000";					
		$std_due_details_res=$obj_db->get_qresult($std_due_details);
					 $j=0;
					 $total_amt=0;
					  $s=0;$i=1;$sno=$pagesize+1;
					 while($std_due_details_rows=$obj_db->fetchArray($std_due_details_res)){
					 $st_course=$obj_db->fetchRow("select course_name from ".TABLE_COURSE." where course_id='".$std_due_details_rows['course_id']."'");
					  $fetch_data[$s][0]=$sno;
					  $fetch_data[$s][1]=$std_due_details_rows['first_name'].' '.$std_due_details_rows['last_name'];
					  $fetch_data[$s][2]=$st_course['course_name'];
					 // if($_SESSION['user_id']==4) $fetch_data[$s][3]=$std_due_details_rows['mobile_no'];
					  
					  $tot_sum=0;
 						$grand_sum=0;
						$c=4;
					 for($k=0;$k<sizeof($fee_id);$k++){
					 
					$rsgetdue= $obj_db->fetchRow("select sum(term_due) as termdue from ".TABLE_STUDENT_FEE." where student_id=".$std_due_details_rows['student_id']." and
					 term in($trimterms) and y_id='".$_SESSION['year_id']."' and fee_type='".$fee_id[$k]."'  group by fee_type  having sum(term_due)>0");
                     $fetch_data[$s][$c]=$rsgetdue['termdue'];
		 $tot_sum=$tot_sum+$rsgetdue['termdue'];
		 $sum[$j][$k]=$sum[$j][$k]+$rsgetdue['termdue'];
		 $c++;
  }
  $c=$c+1;
   $fetch_data[$s][$c]=$tot_sum;
		  $j++;$s++;$sno++; } 
		   
		 
 $s=$s+1;$c=3;
 for($j=0;$j<sizeof($sum[0]);$j++){
	 for($i=0;$i<sizeof($sum);$i++)
 		{ $subtotal[$j]=$subtotal[$j]+$sum[$i][$j]; }
		}

  $grand_sum=0;
  
  $c=0;
  $exp_rettotsums=explode(',',$_REQUEST['ret_tots']);
 for($j=0;$j<sizeof($subtotal);$j++)
	{	$grand_sum=$grand_sum+$subtotal[$j]+$exp_rettotsums[$j];
	$sub_tot[0][$c]=$subtotal[$j]+$exp_rettotsums[$j];
	$retsub_tot.=$sub_tot[0][$c].',';
	$c++;
	}$trmretsub_tot=substr($retsub_tot,0,-1);
	$c=$c+1;
	$sub_tot[0][$c]=$grand_sum;
	
	
	$arr_fetchdata=array("fetch_heads"=>$fetch_heads,"feedue"=>$fetch_data,"extitle"=>$exids,"totalCount"=>$count,"fee_ids"=>$retfeids,"ret_tots"=>$trmretsub_tot,"sub_tot"=>$sub_tot);
 echo json_encode($arr_fetchdata);
   
}

elseif($_REQUEST['action']=="std_fee_duesms"){
	
    for($h=1;$h<=$_REQUEST['terms'];$h++){
					  $tterms.=$h.',';
					  }
					  $trimterms=substr($tterms,0,-1);
   $sec_name=$obj_db->fetchRow("select course_name from ".TABLE_COURSE." where  course_id='".$_REQUEST['course_id']."'");
			 $branch_sec=$obj_db->fetchRow("select branch_short_name from ".TABLE_BRANCH."  where branch_id='".$_REQUEST['branchid']."' ");
			 if($_REQUEST['sec_id']<1)
			  $secname=" Over All Students ";
			 else $secname=$sec_name['course_name'];
			
			
			if($_REQUEST['sec_id']<1)
			{$course_secname=$branch_sec['branch_short_name'].'-'.$secname;
			 }
			else {$course_secname=$branch_sec['branch_short_name'].'-'.$secname;
			}	
			 
	  $std_due_details="select a.student_id,first_name,last_name,sum(term_due) as tdue,mobile_no from ".TABLE_STUDENT_FEE." a,".TABLE_STUDENTDETAILS." b,".TABLE_STUDENT_EDU_DETAILS." c where branch_id='".$_REQUEST['branchid']."' and c.course_id='".$_REQUEST['course_id']."' and c.is_delete=0 and a.student_id=b.student_id and a.student_id=c.student_id and course_model=1 and term in(".$trimterms.") and a.course_id=c.course_id and a.y_id=c.y_id  and a.y_id=".$_SESSION['year_id']." group by a.student_id having sum(term_due)>0 order by sum(term_due) asc,cast(c.course_id as decimal(5,2)) asc,CAST(roll_no as UNSIGNED) ,first_name asc,last_name asc";					
		$fetch_data=$obj_db->qry($std_due_details);
					 $j=0;
 echo json_encode($fetch_data);
   
}

elseif($_REQUEST['action']=="get_managent_dashboard"){
if(isset($_REQUEST['date']))
 $todaydate=$_REQUEST['date'];
$todaydate=date('d-m-Y');
 $rsgettodayincome= $obj_db->fetchRow("select sum(is_paid_amount) as amount from ".TABLE_FEE_PAYMENT." where	date(paid_date)='".$todaydate."' and year_id='".$_SESSION['year_id']."' and receipt_cancelled=0 and branch_id in (".$_SESSION['assign_branch_ids'].")");
  if($rsgettodayincome['amount']=='')
   $daycolc=0;
  else $daycolc=$rsgettodayincome['amount'];
 
 $getinst_income=$obj_db->qry("select sum(is_paid_amount)as income,branch_name as branch_name,b.branch_id from ".TABLE_FEE_PAYMENT." a,".TABLE_BRANCH." b  where a.branch_id=b.branch_id and date(paid_date)='$todaydate' and receipt_cancelled=0  and a.branch_id in (".$_SESSION['assign_branch_ids'].") and year_id=".$_SESSION['year_id']." group by b.branch_id");  
 
 $gettodayexpense= $obj_db->fetchRow("select sum(amount) as amount from ".TABLE_EXPENDITURE." where	date(enter_date)='".$todaydate."' and year_id='".$_SESSION['year_id']."'
 						 and branch_id in (".$_SESSION['assign_branch_ids'].")");
						 
		if($gettodayexpense['amount']=='')
   $dayexpense=0;
  else $dayexpense=$gettodayexpense['amount'];
						 
 $getcourse= "select a.course_name,b.course_id from ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where branch_id=".$_SESSION['branch_id']."
		  and a.course_id=b.course_id and course_model=1";
			$getcourse_res=$obj_db->get_qresult($getcourse);
						$j=0;
						$tot_strenth=0;

						while($rsgetcourse=$obj_db->fetchArray($getcourse_res)) {
						$tot_clsboys[$j][0]=$rsgetcourse['course_name'];
						$boys_stren=$obj_db->fetchRow("select count(*) as boys from ".TABLE_STUDENT_EDU_DETAILS." a,".TABLE_STUDENTDETAILS." b where a.course_id=".$rsgetcourse['course_id']." and a.student_id=b.student_id and gender='Male' and a.branch_id=".$_SESSION['branch_id']." and y_id='".$_SESSION['year_id']."' and is_delete=0");
						$tot_clsboys[$j][1]=$boys_stren['boys'];
						$subtot[$j][0]=$boys_stren['boys']+$subtot[$j][0];
						$girls_stren=$obj_db->fetchRow("select count(*) as girls from ".TABLE_STUDENT_EDU_DETAILS." a,".TABLE_STUDENTDETAILS." b where a.course_id=".$rsgetcourse['course_id']." and a.student_id=b.student_id and gender='Female' and a.branch_id=".$_SESSION['branch_id']." and y_id='".$_SESSION['year_id']."' and is_delete=0");
 $subtot[$j][1]=$girls_stren['girls']+$subtot[$j][1];
 $tot_clsboys[$j][2]=$girls_stren['girls'];
 
           $tot_sum=$girls_stren['girls']+$boys_stren['boys'];
			$tot_clsboys[$j][3]=$tot_sum;
						$j++;}
	
	$j=$j+1;$c=2;					
		 for($k=0;$k<sizeof($subtot[0]);$k++){
	 for($i=0;$i<sizeof($subtot);$i++)
 		{ $subtotal[$k]=$subtotal[$k]+$subtot[$i][$k]; }
		}
		
		$grand_sum=0;
  for($c=0;$c<2;$c++){
  $tot_clsboys[$j][$c]="";
  }
  $c=1;
 for($p=0;$p<sizeof($subtotal);$p++)
	{	$grand_sum=$grand_sum+$subtotal[$p];
	$tot_clsboys[$j][$c]=$subtotal[$p];
	$c++;
	} 
	$c=$c+1;
	$tot_clsboys[$j][$c]=$grand_sum;
	
    $mrng_sec= $obj_db->get_qresult("select sec_name,sec_id from ".TABLE_SECTION."   where branch_id ='".$_SESSION['branch_id']."' order by course_id asc");
		 $g=0;
		while($sec_res= $obj_db->fetchArray($mrng_sec)){
		
      $secwise_attper= $obj_db->fetchRow("select count(*)/2,no_of_present as present,no_of_absent as absent,no_of_leaves as leaves, no_of_present+no_of_absent+no_of_leaves as strenght from ".TABLE_ATTEND_REPORT." a where   date(date_attendance)='".date('Y-m-d')."' and sec_id='".$sec_res['sec_id']."'");
 	  $secwiseattd_per=number_format(($secwise_attper['present']/$secwise_attper['strenght'])*100,2);
	 $secwiseabst_per=100-$secwiseattd_per;
	 $sec[$g]['name']=$sec_res['sec_name'];
	 $sec[$g]['percent']=$secwiseabst_per;
	 $g++;
	 }	

	$arr_fetchdata=array("tot_stdstdrength"=>$tot_clsboys,"day_collection"=>$daycolc,"branch_collection"=>$getinst_income,"day_expense"=>$dayexpense,"stdattdance"=>$sec);
 echo json_encode($arr_fetchdata);
}


elseif($_REQUEST['action']=="income_details"){

   $std_tot_strength=0;
						$amt_tot=0;
						$concession_tot=0;
						$income_tot=0;
						$collected_tot=0;
						$balance_tot=0;
						$branchdetails="select branch_name,branch_id from  ".TABLE_BRANCH." where  branch_id in(".$_SESSION['assign_branch_ids'].")";
						$branchdetails_res=$obj_db->get_qresult($branchdetails);
						$p=0;
						while($branchdetails_rows=$obj_db->fetchArray($branchdetails_res)) {
						$branch_stren="select count(*) strength from  ".TABLE_STUDENT_EDU_DETAILS." a, ".TABLE_STUDENTDETAILS." b where
						a.student_id=b.student_id and course_model=1 $cnd and is_delete=0 and y_id='".$_SESSION['year_id']."' and 
						branch_id ='".$branchdetails_rows['branch_id']."'";
						$branch_stren_res=$obj_db->fetchRow($branch_stren);
						if($branch_stren_res['strength']!=0){
						$std_tot_strength=$std_tot_strength+$branch_stren_res['strength'];
						
						$fetch_data[$p]['branch_name']=$branchdetails_rows['branch_name'];
						$fetch_data[$p]['branch_id']=$branchdetails_rows['branch_id'];
						
						
						 $courefee_details="select sum(term_amount) as tot_amt,sum(term_income) income,sum(term_amount)-sum(term_income) as concession,sum(term_due) as rbal from  
						".TABLE_STUDENT_EDU_DETAILS." a, ".TABLE_STUDENTDETAILS." b, ".TABLE_STUDENT_FEE." c,".TABLE_FEE_TYPE." d where a.student_id=b.student_id and course_model=1 and a.branch_id='".$branchdetails_rows['branch_id']."' and is_delete=0 and a.student_id=c.student_id $cnd and c.fee_type=d.fee_id  and  c.student_id=b.student_id  and
						 a.y_id=".$_SESSION['year_id']." and a.y_id=c.y_id ";
						$courefee_details_res=$obj_db->fetchRow($courefee_details);
						
						$fetch_data[$p]['strength']=$branch_stren_res['strength'];
						$fetch_data[$p]['tot_amt']=$courefee_details_res['tot_amt'];
						$fetch_data[$p]['concession']=$courefee_details_res['concession'];
						$fetch_data[$p]['income']=$courefee_details_res['income'];
						$collect=$courefee_details_res['income']-$courefee_details_res['rbal'];
						$fetch_data[$p]['collect']=$collect;
						$fetch_data[$p]['rbal']=$courefee_details_res['rbal'];
						
						
						$amt_tot=$amt_tot+$courefee_details_res['tot_amt'];
						$concession_tot=$concession_tot+$courefee_details_res['concession'];
						$income_tot=$income_tot+$courefee_details_res['income'];
						$collected_tot=$collected_tot+$collect;
                        $balance_tot=$bal_amt+$courefee_details_res['rbal'];
                        }
						$p++;
						}
						$p=$p+1;
						
						$fetch_data[$p]['branch_name']="Grand Total";
						$fetch_data[$p]['branch_id']=0;
						$fetch_data[$p]['strength']=$std_tot_strength;
						$fetch_data[$p]['tot_amt']=$amt_tot;
						$fetch_data[$p]['concession']=$concession_tot;
						$fetch_data[$p]['income']=$income_tot;
						$fetch_data[$p]['collect']=$collected_tot;
						$fetch_data[$p]['rbal']=$balance_tot;
						
 echo json_encode($fetch_data);
}
elseif($_REQUEST['action']=="coursewise_income"){
  $std_tot_strength=0;
						$amt_tot=0;
						$concession_tot=0;
						$income_tot=0;
						$collected_tot=0;
						$balance_tot=0;
					$coursedetails_res=$obj_db->get_qresult("select course_name,a.course_id,course_model from  ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where
					a.course_id=b.course_id  and branch_id='".$_REQUEST['branch_id']."'");
					$p=0;
						while($coursedetails_rows=$obj_db->fetchArray($coursedetails_res)) {
						 $fetch_data[$p]['course_name']=$coursedetails_rows['course_name'];
						 
						 $branchdetails_res=$obj_db->fetchRow("select count(*) strength from  ".TABLE_STUDENT_EDU_DETAILS." a, ".TABLE_STUDENTDETAILS." b where
						a.student_id=b.student_id $cnd   and is_delete=0 and y_id=".$_SESSION['year_id']." and a.course_id=".$coursedetails_rows['course_id']." and branch_id ='".$_REQUEST['branch_id']."'");
						
						$std_tot_strength=$std_tot_strength+$branchdetails_res['strength']; 
						$fetch_data[$p]['strength']=$branchdetails_res['strength'];
						
						$courefee_details_res=$obj_db->fetchRow("select sum(term_amount) as tot_amt,sum(term_income) income,sum(term_amount)-sum(term_income) as concession,sum(term_due) as rbal from ".TABLE_STUDENT_EDU_DETAILS." a, ".TABLE_STUDENTDETAILS." b, ".TABLE_STUDENT_FEE." c,".TABLE_FEE_TYPE." d where a.student_id=b.student_id    and is_delete=0 $cnd and a.student_id=c.student_id and c.fee_type=d.fee_id  and  c.student_id=b.student_id  and a.course_id=c.course_id and
						 a.y_id=".$_SESSION['year_id']." and a.y_id=c.y_id and a.course_id=".$coursedetails_rows['course_id']." and branch_id ='".$_REQUEST['branch_id']."'");
						$amt_tot=$amt_tot+$courefee_details_res['tot_amt'];
						$concession_tot=$concession_tot+$courefee_details_res['concession'];
						$income_tot=$income_tot+$courefee_details_res['income'];
						
						$fetch_data[$p]['tot_amt']=number_format($courefee_details_res['tot_amt'],2);
						$fetch_data[$p]['concession']=number_format($courefee_details_res['concession'],2);
						$fetch_data[$p]['income']=number_format($courefee_details_res['income'],2);
						
						$collect=$courefee_details_res['income']-$courefee_details_res['rbal'];
						$collected_tot=$collected_tot+$collect;
						 $bal=$courefee_details_res['rbal'];
						 $balance_tot=$bal+$balance_tot;
						 
						$fetch_data[$p]['collect']=number_format($collect,2);
						$fetch_data[$p]['bal']=number_format($bal,2);
						$p++;
						}
						$p=$p+1;
						
						$fetch_data[$p]['course_name']="Grand Total";
						$fetch_data[$p]['strength']=number_format($std_tot_strength,2);
						$fetch_data[$p]['tot_amt']=number_format($amt_tot,2);
						$fetch_data[$p]['concession']=number_format($concession_tot,2);
						$fetch_data[$p]['income']=number_format($income_tot,2);
						$fetch_data[$p]['collect']=number_format($collected_tot,2);
						$fetch_data[$p]['bal']=number_format($balance_tot,2);
						
				echo json_encode($fetch_data);		
}

elseif($_REQUEST['action']=='get_conces_courses'){
  if(isset($_REQUEST['branch_id']))
   $brn_id=$_REQUEST['branch_id'];
  else 
   $brn_id=$_SESSION['branch_id'];
  
 $qgetbranches =  $obj_db->qry("select branch_short_name,branch_name,branch_id from ".TABLE_BRANCH."  where branch_id in(".$_SESSION['assign_branch_ids'].") order by branch_id asc");
 
   $qgetcourse =  $obj_db->qry("select b.course_id,b.course_name from ".TABLE_COURSE_BRANCH_MAP." a,".TABLE_COURSE." b where a.course_id=b.course_id and a.branch_id in(".$brn_id.") order by b.course_id asc");

   	$arr_fetchdata=array("branch_dts"=>$qgetbranches,"course_dts"=>$qgetcourse);
    echo json_encode($arr_fetchdata);
}
elseif($_REQUEST['action']=='get_coursesconces_dts'){
    $course_rw=$obj_db->fetchRow("select course_name from ".TABLE_COURSE."  where  course_id='".$_REQUEST['course_id']."'");
	$branch_rw=$obj_db->fetchRow("select branch_short_name,branch_name,branch_id from ".TABLE_BRANCH." where  branch_id='".$_REQUEST['branch_id']."'");
	if($course_rw['course_name']!='')
	 $extitle=$branch_rw['branch_name'].'-'.$course_rw['course_name'];
	else $extitle=$branch_rw['branch_name'];
    $get_secstds=$obj_db->qry("select  first_name,last_name,mobile_no,b.student_id,roll_no from ".TABLE_STUDENT_EDU_DETAILS." a,
					".TABLE_STUDENTDETAILS." b  where a.student_id=b.student_id $secid and is_delete=0 and a.branch_id='".$_REQUEST['branch_id']."' and course_model=1 and a.y_id='".$_SESSION['year_id']."' and course_id='".$_REQUEST['course_id']."'");
 						$j=0;
						$grnd_corseamttot=0;$grnd_conceamttot=0;
						
						for($m=0;$m<count($get_secstds);$m++){
						 $myarray[$m]['std_name']= $get_secstds[$m]['first_name'].' '.$get_secstds[$m]['last_name'];						
						 $myarray[$m]['mobile_no']= $get_secstds[$m]['mobile_no'];
						
						$std_fee_details=$obj_db->qry("select course_amount,student_amount,fee_name,fee_short_name,concess_amount,user_name,reason from
						 ".TABLE_STUDENT_FEE." a,".TABLE_FEE_TYPE." b,".TABLE_STD_FEE_CONCES." c,".TABLE_USER_DETAILS." d where a.student_id=".$get_secstds[$m]['student_id']." and a.course_hostel_id=c.course_hostel_id and a.y_id='".$_SESSION['year_id']."' and a.y_id=c.year_id
						  and a.term=1 and a.fee_type=b.fee_id and a.student_id=c.student_id and b.fee_id=c.fee_id and c.refer_by=d.user_id");
						$sub_corseamttot=0;$sub_conceamttot=0;
						for($n=0; $n<count($std_fee_details); $n++){						
						 $sub_corseamttot=$sub_corseamttot+$std_fee_details[$n]['course_amount'];
						 $sub_conceamttot=$sub_conceamttot+$std_fee_details[$n]['concess_amount'];
						 
						 $myarray[$m]['std_feedts'][$n] = array("fee_name"=>$std_fee_details[$n]['fee_name'],"course_amount"=>$std_fee_details[$n]['course_amount'],"concess_amount"=>$std_fee_details[$n]['concess_amount'],"user_name"=>$std_fee_details[$n]['user_name'],"reason"=>$std_fee_details[$n]['reason']); 
			 			 $grnd_corseamttot=$grnd_corseamttot+$sub_corseamttot;
				 	        $grnd_conceamttot=$grnd_conceamttot+$sub_conceamttot;
						 }
						 }
					$array_data=array("myarray"=>$myarray,"course_amttot"=>$grnd_corseamttot,"conces_tot"=>$grnd_conceamttot,"extitle"=>$extitle);
    echo json_encode($array_data);
}
?>