<?php session_start();
include "../DbConfig.php";
 if($_REQUEST['action']=="daily_totcolcamts"){ 
 $split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= $split_dt[0];
 $dt2= $split_dt[1];
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
				   $genfearr[]=$value;
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
				 $genfearr[]=$value;
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
	 
	 /* echo '<pre>';
	  print_r($fetch_data);
	  echo '</pre>';
	  
	   echo '<pre>';
	  print_r($fetch_othdata);
	  echo '</pre>';
	  
	   echo '<pre>';
	  print_r($fetch_longdata);
	  echo '</pre>';*/
  $field_array=array("payment_amts"=>$fetch_data,"grdtotal"=>$subtotal,"extitle"=>$title,"pay_names"=>$pay_names,"all_grdtot"=>$all_grdtot);
	echo json_encode($field_array);
}
?>