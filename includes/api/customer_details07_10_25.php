<?php session_start();
include "../DbConfig.php";
$data = json_decode(file_get_contents("php://input"));

if($_GET['action']=="linedls"){

 $result =$obj_db->qry ("select line_name,line_id from  ".TABLE_LINE_NAMES." where  line_id in(".$_SESSION['assign_line_ids'].")");

                    $myarray[0]['lineid']= 0;						
						$myarray[0]['linename']= "All";	
					for($m=0; $m<count($result); $m++)
					{
						if($_SESSION['line_id']==$result[$m]['line_id'])
						{ $sel_branpos=$m;}
						
						$secarray =$obj_db->qry ("SELECT * FROM ".TABLE_LINE_CITYS." where  line_id='".$result[$m]['line_id']."' and is_delete=0 "); 	
						
						$myarray[$result[$m]['line_id']]['lineid']= $result[$m]['line_id'];						
						$myarray[$result[$m]['line_id']]['linename']= $result[$m]['line_name'];		
						
						$n=0;
						$myarray[$result[$m]['line_id']]['city'][$n] = array("lineid"=>0,"cityid"=>'all',"cityname"=>'All');
						for($n=0; $n<count($secarray); $n++)
						{	
							$myarray[$result[$m]['line_id']]['city'][$secarray[$n]['city_id']] = array("lineid"=>$secarray[$n]['line_id'],"cityid"=>$secarray[$n]['city_id'],"cityname"=>$secarray[$n]['city_name']);							
						}					

					}
     $myarray=array('myarray'=>$myarray,'sel_branch'=>$sel_branpos);
  echo json_encode($myarray);
  
  
  
  
  
  /* 
  
  
  
  
  
  
  
  

 $result =$obj_db->qry ("select state_name,state_id from  ".TABLE_STATES." ");
					for($m=0; $m<count($result); $m++)
					{
					
						$secarray =$obj_db->qry ("SELECT * FROM ".TABLE_DISTRICTS." where  state_id='".$result[$m]['state_id']."' "); 	
						
						$myarray[$result[$m]['state_id']]['stateid']= $result[$m]['state_id'];						
						$myarray[$result[$m]['state_id']]['state']= $result[$m]['state_name'];						
						$n=0;
						$myarray[$result[$m]['state_id']]['district'][$n] = array("stateid"=>0,"districtid"=>'all',"districtname"=>'All');
						for($n=0; $n<count($secarray); $n++)
						{	
							$myarray[$result[$m]['state_id']]['district'][$secarray[$n]['dist_id']] = array("stateid"=>$secarray[$n]['state_id'],"districtid"=>$secarray[$n]['dist_id'],"districtname"=>$secarray[$n]['dist_name']);							
						}	
						
					}
     $myarray=array('myarray'=>$myarray);
  echo json_encode($myarray);*/
 
 
 }elseif($_REQUEST['action']=='customer_borrowdetails'){

	  $weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");
	if($_SESSION['user_type']=='account')
		$usrlincnd=" and c.line_id in(0".$_SESSION['assign_line_ids'].") ";
	else $usrlincnd="";
$linedtsarr=array();$linectiydtsarr=array();$lineweedts=array();
	$getlinncitys=$obj_db->qry("select a.*,c.weekd_id,c.city_id,c.city_name from ".TABLE_LINE_NAMES." a,".TABLE_LINE_CITYS." c where a.line_id=c.line_id $usrlincnd order by a.line_id asc,c.weekd_id asc,c.city_name asc");

	$uniquelineids=array_unique(array_column($getlinncitys,'line_id'));
    $lineweekarr=array();
 	foreach($getlinncitys as $lincityky=>$lincityv){
		$lineweekarr[$lincityv['line_id']][$lincityv['weekd_id']]=array("week_id"=>$lincityv['weekd_id'],"week_name"=>$weekdts[$lincityv['weekd_id']]);
		$linedtsarr[$lincityv['line_id']]=array('line_id'=>$lincityv['line_id'],'line_name'=>$lincityv['line_name']);
                     
					}

				foreach($uniquelineids as $linkky=>$linv){
					$linid=$linv;
							$linedts = array_filter($getlinncitys,function($v,$k) use ($linid){
								return $v['line_id'] == $linid;
							  },ARRAY_FILTER_USE_BOTH);
                   $lineweedts[$linid][]=array('week_id'=>0,'week_name'=>"All Weeks");
					foreach($lineweekarr[$linid] as $linwek=>$linwekv){
						$wekid=$linwekv['week_id'];
						  $lineweedts[$linid][]=array('week_id'=>$linwekv['week_id'],'week_name'=>$linwekv['week_name']);
						
							$linecitys = array_filter($linedts,function($v,$k) use ($wekid){
								return $v['weekd_id'] == $wekid;
							  },ARRAY_FILTER_USE_BOTH);
							   $linectiydtsarr[$linid][$wekid][]=array('city_id'=>0,'city_name'=>"All Citys");
						foreach($linecitys as $cityky=>$cityv){	   
                       $linectiydtsarr[$linid][$wekid][]=array('city_id'=>$cityv['city_id'],'city_name'=>$cityv['city_name']);
						}
					}
				}
     $get_customerdts=$obj_db->qry("select a.*,b.borrow_id,b.total_amount,(b.total_amount-b.tot_amt_updconces) as conceamt,b.tot_amt_updconces as custamt,(b.tot_amt_updconces-b.remain_balance) as custpaid,b.remain_balance as rbal,c.line_name,a.city_id,d.city_name,d.weekd_id from ".TABLE_CUSTOMER_DTS." a,".TABLE_CUSTOMER_GENPAYMENTS." b,".TABLE_LINE_NAMES." c,".TABLE_LINE_CITYS." d where  a.customer_id=b.customer_id and a.city_id=d.city_id and a.line_id=c.line_id $usrlincnd and b.remain_balance>0 order by cast(a.customer_no as unsigned) asc");
	 $get_custwekdues=$obj_db->qry("select borrow_id,sum(monthlydue_amt) as wekamt,count(month_week) as pendweeks from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS."  where borrow_id in(0".implode(',',array_column($get_customerdts,'borrow_id')).") and STR_TO_DATE(due_date, '%d-%m-%Y') <='".date('Y-m-d')."' group by borrow_id");
 	 $custpadts=$obj_db->qry("SELECT cp.borrow_id,
       cp.is_paid_amount AS paidamt,
       DATE_FORMAT(cp.paid_date, '%d-%m-%Y') AS pdt
FROM ".TABLE_CUST_PAYMENTS." cp
WHERE cp.borrow_id IN (0".implode(',',array_column($get_customerdts,'borrow_id')).")
  AND cp.is_delete = 0
  AND (
       SELECT COUNT(*)
       FROM ".TABLE_CUST_PAYMENTS." cp2
       WHERE cp2.borrow_id = cp.borrow_id
         AND cp2.is_delete = 0
         AND cp2.paid_date > cp.paid_date
     ) < 2 
ORDER BY `cp`.`borrow_id`  DESC ");
	
	 $get_uptodatpamtarr=array();
	 foreach($custpadts as $custpdtky=>$custpdtv){
		$get_uptodatpamtarr[$custpdtv['borrow_id']][]=$custpdtv;
	 }

	  $get_uptodatedueamtarr=array();
	 foreach($get_custwekdues as $custdueky=>$custduev){
		$get_uptodatedueamtarr[$custduev['borrow_id']]=$custduev;
	 }

    /* $unque_custlinecitydts = array_unique(array_column($get_customerdts, 'city_id'));
						$getline_allunque_custlincitydts = array_intersect_key($get_customerdts, $unque_custlinecitydts);
 		
		
$unque_custlinedts = array_unique(array_column($get_customerdts, 'line_id'));
						$getline_allunque_custlindts = array_intersect_key($get_customerdts, $unque_custlinedts);
 		
		foreach($getline_allunque_custlindts as $lineky=>$linev){
          //   $linedtsarr[]=array('line_id'=>$linev['line_id'],'line_name'=>$linev['line_name']);
           
			 $linid=$linev['line_id'];
							$linecitys = array_filter($get_customerdts,function($v,$k) use ($linid){
								return $v['line_id'] == $linid;
							  },ARRAY_FILTER_USE_BOTH);


 					foreach($linecitys as $lincityky=>$lincityv){
                       $lineweedts[$linid][$lincityv['weekd_id']]=array('week_id'=>$lincityv['weekd_id'],'week_name'=>$weekdts[$lincityv['weekd_id']]);
					}

                   $linectiydtsarr[$linid][$lincityv['weekd_id']][]=array('city_id'=>0,'city_name'=>"All Citys");
					foreach($linecitys as $lincityky=>$lincityv){
                       $linectiydtsarr[$linid][$lincityv['weekd_id']][$lincityv['city_id']]=array('city_id'=>$lincityv['city_id'],'city_name'=>$lincityv['city_name']);
					}
		}*/
		$unque_custdts = array_unique(array_column($get_customerdts, 'customer_id'));
						$getline_allunque_custdts = array_intersect_key($get_customerdts, $unque_custdts);
		$custdts=array_values($getline_allunque_custdts);
		$getcustdts_arr=array();
		foreach($custdts as $custdtky=>$custdtv){
			

				$custid=$custdtv['customer_id'];
							$custduedtslst = array_filter($get_customerdts,function($v,$k) use ($custid){
								return $v['customer_id'] == $custid;
							  },ARRAY_FILTER_USE_BOTH);
                  $custduearr=array();
                   foreach($custduedtslst as $custdueky=>$custduev)  
                    {
                     $custduearr[]=array('borrow_id'=>$custduev['borrow_id'],'total_amount'=>$custduev['total_amount'],'conceamt'=>$custduev['conceamt'],'custuptodateamt'=>$get_uptodatedueamtarr[$custduev['borrow_id']]['wekamt'],'pableweeks'=>$get_uptodatedueamtarr[$custduev['borrow_id']]['pendweeks'],'custamt'=>$custduev['custamt'],'custpaid'=>$custduev['custpaid'],'rbal'=>$custduev['rbal']);
					}          
$getcustdts_arr[]=array('customer_id'=>$custdtv['customer_id'],'customer_name'=>$custdtv['customer_name'],'week_id'=>$custdtv['weekd_id'],'weekday'=>$custdtv['weekday'],'customer_no'=>$custdtv['customer_no'],'rbal'=>$custdtv['rbal'],'father_name'=>$custdtv['father_name'],'line_id'=>$custdtv['line_id'],'city_id'=>$custdtv['city_id'],'aadhar_no'=>$custdtv['aadhar_no'],'mobile_no'=>$custdtv['mobile_no'],'city'=>$custdtv['city'],'address'=>$custdtv['address'],'custduedts'=>$custduearr);

		}
	 //	echo '<pre>';print_r(array('linedts'=>array_values($linedtsarr),'lineweedts'=>$lineweedts,'linectiydts'=>$linectiydtsarr,'getcustdts_arr'=>$getcustdts_arr,'paydts'=>$get_uptodatpamtarr));echo '</pre>';
echo json_encode(array('linedts'=>array_values($linedtsarr),'lineweedts'=>$lineweedts,'linectiydts'=>$linectiydtsarr,'getcustdts_arr'=>$getcustdts_arr,'paydts'=>$get_uptodatpamtarr));
}

elseif($_REQUEST['action']=='cutomer_lineuserdts'){
  $get_lineusrs=$obj_db->qry("select user_id,full_name from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$_REQUEST['cutomer_lineusers']."', assign_line_ids) > 0 and user_id>1 and user_type_id!=1 and user_status=1");
 $getuserlsit=array();
 $getuserlsit[]=array("user_id"=>"","full_name"=>"--Select User--");
 foreach($get_lineusrs as $usrky=>$usrv)
	$getuserlsit[]=$usrv;

  echo json_encode($getuserlsit);
}
elseif($_REQUEST['action']=='getline_citys'){
	 $weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");
	if($_SESSION['user_type']=='account')
		$usrlincnd=" and c.line_id in(0".$_SESSION['assign_line_ids'].") ";
	else $usrlincnd="";
$linedtsarr=array();$linectiydtsarr=array();$lineweedts=array();
	$getlinncitys=$obj_db->qry("select a.*,c.weekd_id,c.city_id,c.city_name from ".TABLE_LINE_NAMES." a,".TABLE_LINE_CITYS." c where a.line_id=c.line_id $usrlincnd order by a.line_id asc,c.weekd_id asc,c.city_name asc");

	$uniquelineids=array_unique(array_column($getlinncitys,'line_id'));
    $lineweekarr=array();
 	foreach($getlinncitys as $lincityky=>$lincityv){
		$lineweekarr[$lincityv['line_id']][$lincityv['weekd_id']]=array("week_id"=>$lincityv['weekd_id'],"week_name"=>$weekdts[$lincityv['weekd_id']]);
		$linedtsarr[$lincityv['line_id']]=array('line_id'=>$lincityv['line_id'],'line_name'=>$lincityv['line_name']);
                     
					}

				foreach($uniquelineids as $linkky=>$linv){
					$linid=$linv;
							$linedts = array_filter($getlinncitys,function($v,$k) use ($linid){
								return $v['line_id'] == $linid;
							  },ARRAY_FILTER_USE_BOTH);
                   $lineweedts[$linid][]=array('week_id'=>0,'week_name'=>"All Weeks");
					foreach($lineweekarr[$linid] as $linwek=>$linwekv){
						$wekid=$linwekv['week_id'];
						  $lineweedts[$linid][]=array('week_id'=>$linwekv['week_id'],'week_name'=>$linwekv['week_name']);
						
							$linecitys = array_filter($linedts,function($v,$k) use ($wekid){
								return $v['weekd_id'] == $wekid;
							  },ARRAY_FILTER_USE_BOTH);
							   $linectiydtsarr[$linid][$wekid][]=array('city_id'=>0,'city_name'=>"All Citys");
						foreach($linecitys as $cityky=>$cityv){	   
                       $linectiydtsarr[$linid][$wekid][]=array('city_id'=>$cityv['city_id'],'city_name'=>$cityv['city_name']);
						}
					}
				}

		echo json_encode(array('linedts'=>array_values($linedtsarr),'lineweedts'=>$lineweedts,'linectiydts'=>$linectiydtsarr));
			}

elseif($_REQUEST['action']=='getlinewise_paid_pendlists'){
	if($_SESSION['user_type']=='account')
		$usrlincnd=" and c.line_id in(0".$_SESSION['assign_line_ids'].") ";
	else $usrlincnd="";
     $get_customerdts=$obj_db->qry("select a.*,b.borrow_id,b.total_amount,(b.total_amount-b.tot_amt_updconces) as conceamt,b.tot_amt_updconces as custamt,(b.tot_amt_updconces-b.remain_balance) as custpaid,b.remain_balance as rbal,c.line_name,d.monthly_amt,d.monthlydue_amt,if(d.monthlydue_amt>0,1,0) as paidsts,due_date  as duedt,date_format(str_to_date(due_date,'%d-%m-%Y'),'%Y-%m-%d') as duedts ,c.line_name,e.city_name,e.weekd_id  from ".TABLE_CUSTOMER_DTS." a,".TABLE_CUSTOMER_GENPAYMENTS." b,".TABLE_LINE_NAMES." c,".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." d,".TABLE_LINE_CITYS." e where  c.line_id=e.line_id and b.borrow_id=d.borrow_id and a.customer_id=b.customer_id and a.line_id=c.line_id $usrlincnd and b.remain_balance>0 order by a.customer_name asc,date_format(STR_TO_DATE(d.due_date, '%d-%m-%Y'),'%Y-%m-%d') asc");
  	

	  $custlineamt_duepaidarr=array();$custline_duedtarr=array();$custline_custdtsarr=array();
	 foreach($get_customerdts as $custdutky=>$custdutv){
		$custlineamt_duepaidarr[$custdutv['line_id']][$custdutv['customer_id']][$custdutv['duedt']]=$custdutv;
		$custline_duedtarr[$custdutv['line_id']][$custdutv['duedt']]=$custdutv['duedt'];
		$custline_custdtsarr[$custdutv['line_id']][$custdutv['customer_id']]=$custdutv['customer_name'];
	 }
  
 $unque_custlinedts = array_unique(array_column($get_customerdts, 'line_id'));
						$getline_allunque_custlindts = array_intersect_key($get_customerdts, $unque_custlinedts);

$filtered = array_filter($get_customerdts, function($record) {
    $recordDate = strtotime($record['duedts']);
    $twoWeeksAgo = strtotime('-8 weeks');
    $today = time();

    return $recordDate >= $twoWeeksAgo && $recordDate <= $today;
  //  return $recordDate <= $twoWeeksAgo;
});

echo '<pre>';print_r($filtered);echo '</pre>';

 		$linedtsarr=array();
		foreach($getline_allunque_custlindts as $lineky=>$linev)
             $linedtsarr[]=array('line_id'=>$linev['line_id'],'line_name'=>$linev['line_name']);
	
$cuslinepend_paidamtarr=array();

    foreach($filtered as $custlineky=>$custlinev){
		$lineky=0;$hed=1;
		$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]="Sno";
		$hed++;
		$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]="Name";
		$hed++;
		foreach($custline_duedtarr[$custlinev['line_id']] as $custdtky=>$custdtv){
			$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]=$custdtv;
			$hed++;
	}
       $lineky++;$hed=1;$sno=1;
		foreach($custline_custdtsarr[$custlinev['line_id']] as $custky=>$cusstv){
			$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]=$sno;
			$hed++;
			$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]=$cusstv;
			$hed++;
			foreach($custline_duedtarr[$custlinev['line_id']] as $custdtky=>$custdtv){
				/*if(is_numeric($custlineamt_duepaidarr[$custlinev['line_id']][$custky][$custdtv]['monthlydue_amt']) && $custlineamt_duepaidarr[$custlinev['line_id']][$custky][$custdtv]['monthlydue_amt']>0)
			$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]=$custlineamt_duepaidarr[$custlinev['line_id']][$custky][$custdtv]['monthlydue_amt'];
		elseif(is_numeric($custlineamt_duepaidarr[$custlinev['line_id']][$custky][$custdtv]['monthlydue_amt']) && $custlineamt_duepaidarr[$custlinev['line_id']][$custky][$custdtv]['monthlydue_amt']==0)
			$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]="Paid";
		elseif(!is_numeric($custlineamt_duepaidarr[$custlinev['line_id']][$custky][$custdtv]['monthlydue_amt']))
			$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]="NULL";*/
		$cuslinepend_paidamtarr[$custlinev['line_id']][$lineky][$hed]=$custlineamt_duepaidarr[$custlinev['line_id']][$custky][$custdtv]['monthlydue_amt'];
			$hed++;
	}$hed=1;
	$sno++;
	$lineky++;
	}
	}
	
// echo '<pre>';print_r($cuslinepend_paidamtarr);echo '</pre>';
echo json_encode(array('linedts'=>$linedtsarr,'linebook_dts'=>$cuslinepend_paidamtarr));
}
elseif($_REQUEST['action']=='linewise_daily_transaction'){
$split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= $split_dt[0];
 if($split_dt[1]=='')
 $dt2= date('d-m-Y');
else $dt2=$split_dt[1];
 if($_REQUEST['dat_range']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }

	$linecnd="";
	if($_SESSION['user_type']=='account')
		$linecnd=" and a.line_id in(0".$_SESSION['assign_line_ids'].") AND a.user_id='".$_SESSION['user_id']."' ";
      $getlinewisetransctiondts=$obj_db->qry("select sum(a.line_taken_amt) as line_taken_amt,sum(a.line_given_amts) as line_given_amts,sum(a.line_collect_amts) as line_collect_amts,sum(a.line_expense_amt) as line_expense_amt,((sum(a.line_taken_amt)+sum(a.line_collect_amts))-(sum(a.line_given_amts)+sum(a.line_expense_amt))) as rembal,b.line_name,c.full_name,b.line_id,b.line_name from ".TABLE_LINEWISE_DATEWISE_AMTS." a,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c where a.line_id=b.line_id $linecnd and a.user_id=c.user_id and str_to_date(a.date_time,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y')   group by b.line_id order by b.line_id asc ");
  $unque_custlinedts = array_unique(array_column($getlinewisetransctiondts, 'line_id'));
						$getline_allunque_custlindts = array_intersect_key($getlinewisetransctiondts, $unque_custlinedts);
	
	$linedtsarr=array();
	$linedtsarr[]=array('line_id'=>0,'line_name'=>"All Lines");
		foreach($getline_allunque_custlindts as $lineky=>$linev)
             $linedtsarr[]=array('line_id'=>$linev['line_id'],'line_name'=>$linev['line_name']);
	
	echo json_encode(array('linedts'=>$linedtsarr,'linewisetransdt'=>$getlinewisetransctiondts));
}
elseif($_REQUEST['action']=='linewise_daily_transaction_details'){
$split_dt=explode("TO",$_REQUEST['dat_range']); 
 $dt1= $split_dt[0];
 if($split_dt[1]=='')
 $dt2= date('d-m-Y');
else $dt2=$split_dt[1];
 if($_REQUEST['dat_range']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }

  		$linecnd=" and a.line_id in(0".$_REQUEST['selline_id'].")  ";
$ptypdtsarr=array();$ptypuserdts=array();$userwisepaywisecolc=array();
$custpayentdtsarr=array();
   $linewiscollections=$obj_db->qry("select a.is_paid_amount,a.receipt_no,a.pay_type_id,e.pay_name,b.line_name,c.full_name,b.line_id,b.line_name,if(a.payto_user_id>0,a.payto_user_id,a.user_id) as puser_id,if(a.payto_user_id>0,d.full_name,c.full_name) as puser_fullname,d.full_name,c.user_name,c.full_name as line_enterusrname,f.customer_name,f.customer_no,g.city_name from ".TABLE_CUST_PAYMENTS." a left join ".TABLE_USER_DETAILS." d on a.payto_user_id=d.user_id ,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c,".TABLE_PAYMENT_TYPE." e,".TABLE_CUSTOMER_DTS." f,".TABLE_LINE_CITYS." g where a.customer_id=f.customer_id and b.line_id=g.line_id and a.pay_type_id=e.pay_type_id and a.line_id=b.line_id $linecnd and a.user_id=c.user_id and a.is_delete=0 and str_to_date(a.paid_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y') group by a.ref_id order by b.line_id asc,a.id desc ");
 $custcolcptypusrdtypdtsarr=array();$usertotptypcustcolcarr=array();
  foreach($linewiscollections as $lincolcky=>$lincolcv){
	$ptypdtsarr[$lincolcv['pay_type_id']]=array('pay_type_id'=>$lincolcv['pay_type_id'],'pay_name'=>$lincolcv['pay_name']);
	$ptypuserdts[$lincolcv['puser_id']]=array('puser_id'=>$lincolcv['puser_id'],'full_name'=>$lincolcv['puser_fullname']);
	//$userwisepaywisecolc[$lincolcv['puser_id']][$lincolcv['pay_type_id']][]=$lincolcv;
	$custpayentdtsarr[$lincolcv['puser_id']][$lincolcv['pay_type_id']][]=$lincolcv['is_paid_amount'];
	$usertotptypcustcolcarr[$lincolcv['puser_id']][$lincolcv['pay_type_id']]=$usertotptypcustcolcarr[$lincolcv['puser_id']][$lincolcv['pay_type_id']]+$lincolcv['is_paid_amount'];
 }
 foreach($usertotptypcustcolcarr as $usrky=>$usrpdtv){
	$username=$ptypuserdts[$usrky]['full_name'];
	$usercolcptypdts='';
	foreach($usrpdtv as $ptypky=>$ptypv){
          $usercolcptypdts.=$ptypdtsarr[$ptypky]['pay_name'].':'.$ptypv.' ,';
	}
	$custcolcptypusrdtypdtsarr[]=array('full_name'=>$ptypuserdts[$usrky]['full_name'],'amts'=>substr($usercolcptypdts,0,-1));
 }
 //echo '<pre>';print_r($custcolcptypusrdtypdtsarr);echo '</pre>';
 $lineexpdtsarr=array();$expdtsusrtypptyparr=array();$exptotptyptotsumarr=array();
  $linexpenses=$obj_db->qry("select a.amount,a.voucher_no,a.pay_type_id,e.pay_name,b.line_name,c.full_name,b.line_id,b.line_name,if(a.payby_user_id>0,a.payby_user_id,a.user_id) as puser_id,if(a.payby_user_id>0,d.full_name,c.full_name) as puser_fullname,d.full_name,c.user_name,c.full_name as line_enterusrname,f.exp_name from ".TABLE_EXPENDITURE." a left join ".TABLE_USER_DETAILS." d on a.payby_user_id=d.user_id ,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c,".TABLE_PAYMENT_TYPE." e,".TABLE_EXPENDITURE_TYPE." f where  a.pay_type_id=e.pay_type_id and a.line_id=b.line_id $linecnd and a.user_id=c.user_id and a.exp_type_id=f.exp_type_id and a.is_cancel=0 and str_to_date(a.exp_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y') order by b.line_id asc,a.id desc ");
 foreach($linexpenses as $linexpky=>$linexpv){
	$ptypdtsarr[$linexpv['pay_type_id']]=array('pay_type_id'=>$linexpv['pay_type_id'],'pay_name'=>$linexpv['pay_name']);
	$ptypuserdts[$linexpv['puser_id']]=array('puser_id'=>$linexpv['puser_id'],'full_name'=>$linexpv['puser_fullname']);
	$lineexpdtsarr[$linexpv['puser_id']][$linexpv['pay_type_id']][]=$linexpv['amount'];
	$exptotptyptotsumarr[$linexpv['puser_id']][$linexpv['pay_type_id']]=$exptotptyptotsumarr[$linexpv['puser_id']][$linexpv['pay_type_id']]+$linexpv['amount'];
	//$userwisepaywisecolc[$linexpv['puser_id']][$linexpv['pay_type_id']][]=$linexpv;
 }

foreach($exptotptyptotsumarr as $usrky=>$usrpdtv){
	$username=$ptypuserdts[$usrky]['full_name'];
	$usercolcptypdts='';
	foreach($usrpdtv as $ptypky=>$ptypv){
          $usercolcptypdts.=$ptypdtsarr[$ptypky]['pay_name'].':'.$ptypv.' ,';
	}
	$expdtsusrtypptyparr[]=array('full_name'=>$ptypuserdts[$usrky]['full_name'],'amts'=>substr($usercolcptypdts,0,-1));
 }

 $lintakamtdtsarr=array();$emptakamtptypusrtyparr=array();$emptyptotsumarr=array();
 $emptakeamts=$obj_db->qry("select a.amount as taken_amt,a.pay_type_id,e.pay_name,b.line_name,c.full_name,b.line_id,b.line_name,c.user_name,c.full_name as line_enterusrname,a.takenuser_id as puser_id,c.full_name as puser_fullname  from ".TABLE_EMPUSER_TAKEN_AMTS." a,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c,".TABLE_PAYMENT_TYPE." e where  a.pay_type_id=e.pay_type_id and a.line_id=b.line_id $linecnd and a.takenuser_id=c.user_id and str_to_date(a.taken_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y') and a.is_delete=0   order by b.line_id asc,a.id desc ");
 foreach($emptakeamts as $lintakamtky=>$lintakamtv){
	$ptypdtsarr[$lintakamtv['pay_type_id']]=array('pay_type_id'=>$lintakamtv['pay_type_id'],'pay_name'=>$lintakamtv['pay_name']);
	$ptypuserdts[$lintakamtv['puser_id']]=array('puser_id'=>$lintakamtv['puser_id'],'full_name'=>$lintakamtv['puser_fullname']);
//	$userwisepaywisecolc[$lintakamtv['puser_id']][$lintakamtv['pay_type_id']][]=$lintakamtv;
	$lintakamtdtsarr[$lintakamtv['puser_id']][$lintakamtv['pay_type_id']][]=$lintakamtv['taken_amt'];
	$emptyptotsumarr[$lintakamtv['puser_id']][$lintakamtv['pay_type_id']]=$emptyptotsumarr[$lintakamtv['puser_id']][$lintakamtv['pay_type_id']]+$lintakamtv['taken_amt'];
 }


 foreach($emptyptotsumarr as $usrky=>$usrpdtv){
	$username=$ptypuserdts[$usrky]['full_name'];
	$usercolcptypdts='';
	foreach($usrpdtv as $ptypky=>$ptypv){
          $usercolcptypdts.=$ptypdtsarr[$ptypky]['pay_name'].':'.$ptypv.' ,';
	}
	$emptakamtptypusrtyparr[]=array('full_name'=>$ptypuserdts[$usrky]['full_name'],'amts'=>substr($usercolcptypdts,0,-1));
 }
 
 $linecusttakamtdts_arr=array();$lincusttakamtptypusrtyparr=array();$lincusttakamtptyptotsumarr=array();
  $customer_takenamts=$obj_db->qry("select d.amount,d.pay_type_id,e.pay_name,b.line_name,c.full_name,b.line_id,b.line_name,c.user_name,c.full_name as line_enterusrname,d.user_id as puser_id,c.full_name as puser_fullname,a.customer_no,a.customer_name from ".TABLE_CUSTOMER_GENPAYMENTS_PAYTYPES." d ,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c,".TABLE_CUSTOMER_DTS." a,".TABLE_PAYMENT_TYPE." e where  d.pay_type_id=e.pay_type_id and a.customer_id=d.customer_id and a.line_id=b.line_id $linecnd and d.user_id=c.user_id and d.is_cancel=0 and str_to_date(d.taken_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y')   order by b.line_id asc,d.id desc ");
foreach($customer_takenamts as $lintcustakamtky=>$lincusttakamtv){
	$ptypdtsarr[$lincusttakamtv['pay_type_id']]=array('pay_type_id'=>$lincusttakamtv['pay_type_id'],'pay_name'=>$lincusttakamtv['pay_name']);
	$ptypuserdts[$lincusttakamtv['puser_id']]=array('puser_id'=>$lincusttakamtv['puser_id'],'full_name'=>$lincusttakamtv['puser_fullname']);
//	$userwisepaywisecolc[$lincusttakamtv['puser_id']][$lincusttakamtv['pay_type_id']][]=$lincusttakamtv;
	$linecusttakamtdts_arr[$lincusttakamtv['puser_id']][$lincusttakamtv['pay_type_id']][]=$lincusttakamtv['amount'];
	$lincusttakamtptyptotsumarr[$lincusttakamtv['puser_id']][$lincusttakamtv['pay_type_id']]=$lincusttakamtptyptotsumarr[$lincusttakamtv['puser_id']][$lincusttakamtv['pay_type_id']]+$lincusttakamtv['amount'];
}

foreach($lincusttakamtptyptotsumarr as $usrky=>$usrpdtv){
	$username=$ptypuserdts[$usrky]['full_name'];
	$usercolcptypdts='';
	foreach($usrpdtv as $ptypky=>$ptypv){
          $usercolcptypdts.=$ptypdtsarr[$ptypky]['pay_name'].':'.$ptypv.' ,';
	}
	$lincusttakamtptypusrtyparr[]=array('full_name'=>$ptypuserdts[$usrky]['full_name'],'amts'=>substr($usercolcptypdts,0,-1));
 }

array_multisort(array_column($ptypdtsarr,'pay_type_id'), SORT_ASC, $ptypdtsarr);
 $customercolcusrwiseptypwisarr=array();$i=0;
foreach($ptypuserdts as $pusrky=>$pusrv){
	 $customercolcusrwiseptypwisarr[$i][1]=$pusrv['full_name'];
	 $j=2;
	foreach($ptypdtsarr as $ptypky=>$ptypv){
          $customercolcusrwiseptypwisarr[$i][$j]=array_sum($custpayentdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']]);
		  $j++;
	}
	$i++;
}


$lineexpptypdtsarr=array();$i=0;
foreach($ptypuserdts as $pusrky=>$pusrv){
	 $lineexpptypdtsarr[$i][1]=$pusrv['full_name'];
	 $j=2;
	foreach($ptypdtsarr as $ptypky=>$ptypv){
          $lineexpptypdtsarr[$i][$j]=array_sum($lineexpdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']]);
		  $j++;
	}
	$i++;
}


$lintakamttypdtsarr=array();$i=0;
foreach($ptypuserdts as $pusrky=>$pusrv){
	 $lintakamttypdtsarr[$i][1]=$pusrv['full_name'];
	 $j=2;
	foreach($ptypdtsarr as $ptypky=>$ptypv){
          $lintakamttypdtsarr[$i][$j]=array_sum($lintakamtdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']]);
		  $j++;
	}
	$i++;
}


$lincusttakamttypdtsarr=array();
$i=0;
foreach($ptypuserdts as $pusrky=>$pusrv){
	 $lincusttakamttypdtsarr[$i][1]=$pusrv['full_name'];
	 $j=2;
	foreach($ptypdtsarr as $ptypky=>$ptypv){
          $lincusttakamttypdtsarr[$i][$j]=array_sum($linecusttakamtdts_arr[$pusrv['puser_id']][$ptypv['pay_type_id']]);
		  $j++;
	}
	$i++;
}
 $lineexist_ptypamtdtsarr=array();
$i=0;
foreach($ptypuserdts as $pusrky=>$pusrv){
 	$lineexist_ptypamtdtsarr[$i][1]=$pusrv['full_name'];
	 $j=2;
 	foreach($ptypdtsarr as $ptypky=>$ptypv){
	//	echo '<pre>';print_r($custpayentdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']]);echo '</pre>';
		 // print_r($custpayentdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']]);;echo '<br>';
	  	$rembal=(array_sum($custpayentdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']])+array_sum($lintakamtdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']]))-(array_sum($lineexpdtsarr[$pusrv['puser_id']][$ptypv['pay_type_id']])+array_sum($linecusttakamtdts_arr[$pusrv['puser_id']][$ptypv['pay_type_id']]));
		$lineexist_ptypamtdtsarr[$i][$j]=$rembal;
		  $j++;
	}
	$i++;
}

$linerembal=(array_sum(array_column($linewiscollections,'is_paid_amount'))+array_sum(array_column($emptakeamts,'taken_amt')))-(array_sum(array_column($linexpenses,'amount'))+array_sum(array_column($customer_takenamts,'amount')));
//$colc_pys_takcusttakamtdtsarr[0]=array("Collection","TakenFromOffice","Expense","CusomerTakenAmt","Rem.Bal");
$colc_pys_takcusttakamtdtsarr=array("colc"=>array_sum(array_column($linewiscollections,'is_paid_amount')),"emptak"=>array_sum(array_column($emptakeamts,'taken_amt')),"expamt"=>array_sum(array_column($linexpenses,'amount')),"custborrow"=>array_sum(array_column($customer_takenamts,'amount')),"rembal"=>$linerembal);
//$colc_pys_takcusttakamtdtsarr=array("lincolcamt"=>array_sum(array_column($linewiscollections,'is_paid_amount')),"linexpamt"=>array_sum(array_column($linexpenses,'amount')),"linetakamt"=>array_sum(array_column($emptakeamts,'taken_amt')),"custtakamt"=>array_sum(array_column($customer_takenamts,'amount')));
//echo  '<pre>';print_r(array('lineoveralview'=>$colc_pys_takcusttakamtdtsarr,'userwiseptypwiseexistbal'=>$lineexist_ptypamtdtsarr,'users'=>$ptypuserdts,'ptypes'=>$ptypdtsarr,'lincolcamtdts'=>$linewiscollections,'linexpdts'=>$linexpenses,'linemptakamtdts'=>$emptakeamts,'lincusttakamtdts'=>$customer_takenamts,'custcolcptypusrdtypdtsarr'=>$custcolcptypusrdtypdtsarr,'custptypcolc'=>$customercolcusrwiseptypwisarr,'emptakamtptypusrtyparr'=>$emptakamtptypusrtyparr,'lineptypexp'=>$lineexpptypdtsarr,'lincusttakamtptypusrtyparr'=>$lincusttakamtptypusrtyparr,'expdtsusrtypptyparr'=>$expdtsusrtypptyparr,'linptyptakamt'=>$lintakamttypdtsarr,'lineptypcsusttakamt'=>$lincusttakamttypdtsarr));echo '</pre>';
echo json_encode(array('lineoveralview'=>$colc_pys_takcusttakamtdtsarr,'userwiseptypwiseexistbal'=>$lineexist_ptypamtdtsarr,'users'=>$ptypuserdts,'ptypes'=>$ptypdtsarr,'lincolcamtdts'=>$linewiscollections,'linexpdts'=>$linexpenses,'linemptakamtdts'=>$emptakeamts,'lincusttakamtdts'=>$customer_takenamts,'custcolcptypusrdtypdtsarr'=>$custcolcptypusrdtypdtsarr,'custptypcolc'=>$customercolcusrwiseptypwisarr,'emptakamtptypusrtyparr'=>$emptakamtptypusrtyparr,'lineptypexp'=>$lineexpptypdtsarr,'lincusttakamtptypusrtyparr'=>$lincusttakamtptypusrtyparr,'expdtsusrtypptyparr'=>$expdtsusrtypptyparr,'linptyptakamt'=>$lintakamttypdtsarr,'lineptypcsusttakamt'=>$lincusttakamttypdtsarr));
}
?>