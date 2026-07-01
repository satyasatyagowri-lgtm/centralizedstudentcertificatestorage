<?php session_start();
include "../DbConfig.php";
if($_REQUEST['action']=='get_banks'){
 $get_banks=$obj_db->qry("select * from ".TABLE_EXPENDITURE_TYPE." where is_bank_person=1");
 echo json_encode($get_banks);
}
  elseif($_REQUEST['action']=="datewise_paywisecollection"){
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
			 if($_REQUEST['bank_id']==0)
			   $bnkcnd="";
			  else $bnkcnd=" and a.received_bank_id='".$_REQUEST['bank_id']."' ";
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
			   $upayfees=$obj_db->qry("select * from ".TABLE_UPAY_DETAILS." ");
			  //  $upaytypemodes=$obj_db->qry("select * from ".TABLE_UPAY_TYPE_MODES." ");
			//  $pay_types=$obj_db->qry("select * from ".TABLE_PAYMENT_TYPE." ");
				 $fee_paymentqry=$obj_db->qry("select d.first_name,d.last_name,DATE_FORMAT(STR_TO_DATE(a.paid_date, '%Y-%m-%d'), '%d-%m-%Y') as pdts,sum(is_paid_amount) as pamt,a.receipt_no as recno,payment_type as pay_type_id,a.fee_id,b.course_type_id,a.user_id,c.exp_name as bank_name,a.cheque_num as transnum,e.user_name,c.exp_type_id,f.pay_name,a.cheque_date as transdt,a.upay_id,a.upay_type from ".TABLE_FEE_PAYMENT." a,".TABLE_COURSE." b,".TABLE_EXPENDITURE_TYPE." c,".TABLE_STUDENTDETAILS." d,".TABLE_USER_DETAILS." e,".TABLE_PAYMENT_TYPE." f  where a.branch_id in(".$brn_id.") $bnkcnd and e.user_id=a.user_id and a.student_id=d.student_id and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and receipt_cancelled=0 and a.received_bank_id=c.exp_type_id  and a.payment_type=f.pay_type_id  and a.received_bank_id>0 and a.course_id=b.course_id  group by date(paid_date),a.receipt_no,a.user_id asc,a.payment_type asc order by date(paid_date) asc,a.user_id asc,a.payment_type asc");
				 $paytype_pusharr=array();
				 foreach($fee_paymentqry as $key=>$value){
				  array_push($paytype_pusharr,$value);
				 }
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
				
				  $othrfees=$obj_db->qry("select d.first_name,DATE_FORMAT(STR_TO_DATE(a.paid_date, '%Y-%m-%d'), '%d-%m-%Y')  pdts,sum(fee_amt) as pamt,a.pay_type_id,a.rec_id as recno,a.fee_id,b.course_type_id,a.user_id,c.exp_name as bank_name,a.transaction_no as transnum,e.user_name,c.exp_type_id,f.pay_name,a.transaction_date as transdt,a.upay_id,a.upay_type from ".TABLE_STD_OTHERFEES." a,".TABLE_COURSE." b,".TABLE_EXPENDITURE_TYPE." c,".TABLE_STUDENTDETAILS." d,".TABLE_USER_DETAILS." e,".TABLE_PAYMENT_TYPE." f  where a.branch_id in(".$brn_id.") $bnkcnd and date(paid_date) between '".trim($dt1)."' and '".trim($dt2)."' $var and is_cancel=0 and e.user_id=a.user_id and a.student_id=d.student_id and a.pay_type_id=f.pay_type_id and a.received_bank_id=c.exp_type_id and a.received_bank_id>0 and a.course_id=b.course_id group by date(paid_date),a.pay_type_id order by date(paid_date) asc,a.user_id asc,a.pay_type_id asc");
				  
				   foreach($othrfees as $key=>$value){
				  array_push($paytype_pusharr,$value);
				 }
				   $othfepay_fids = array_map(function ($value) {
    return  $value['pay_type_id'];
}, $othrfees);

if(count($othfepay_fids)==0)
 $othfepayfids=array();
 else $othfepayfids=$othfepay_fids;
				  
 $alfeids = array_unique(array_merge($fepayfids, $othfepayfids));
 $arr_merge=array_merge($fepayfids, $othfepayfids);
 foreach($alfeids  as $key=>$value){
  $feidss.=$value.',';
 } 	
  $feidss=substr($feidss,0,-1);
     $paymt_names=$obj_db->qry("select * from ".TABLE_PAYMENT_TYPE." where pay_type_id in(0".$feidss.") order by pay_type_id asc");
   for($i=0;$i<count($paymt_names);$i++)
    $fee_namearr[$paymt_names[$i]['pay_type_id']]=$paymt_names[$i]['pay_name'];
				  
				  $othfepay_dats = array_map(function ($value) {
    return  $value['opdt'];
}, $othrfees); 

$tot_paytpeamt=array();$gotot=0;
foreach($fee_namearr as $key=>$value){
$paytypearrkys=array_keys(array_column($paytype_pusharr, 'pay_type_id'),$key);

$subtot_ptype=0;
for($i=0;$i<count($paytypearrkys);$i++){
 $subtot_ptype=$subtot_ptype+$paytype_pusharr[$paytypearrkys[$i]]['pamt'];
}
$gotot=$gotot+$subtot_ptype;
$tot_paytpeamt[]=array('pay_type_id'=>$key,'pay_type_id'=>$key,'paytypeamt'=>$subtot_ptype);
 }

//print_r($tot_paytpeamt);
//	 echo '<pre>'; print_r($fetch_data);print_r($subtotal);echo '<pre>';
$upaytypes =array(array('upay_type'=>"1",'upay_name'=>'QR Scanner'),array('upay_type'=>"2",'upay_name'=>'Direct Pay'));

  $field_array=array("payment_stdamts"=>$paytype_pusharr,'paymt_names'=>$paymt_names,'paytypeamts'=>$tot_paytpeamt,'gotot'=>$gotot,'exceltitle'=>'All Payment Details','upayfees'=>$upayfees,'upaytypes'=>$upaytypemodes,'upaytypesss'=>$upaytypes);
	echo json_encode($field_array);

 

 }


?>