<?php session_start();
include "../DbConfig.php";
$data = json_decode(file_get_contents("php://input"));

 
if($_GET['action']=="linedls"){
	$lincnd="";
if($_SESSION['user_type']=='account')
	 $lincnd=" and line_id in(".$_SESSION['assign_line_ids'].") ";
 $result =$obj_db->qry ("select line_name,line_id from  ".TABLE_LINE_NAMES." where is_delete=0 $lincnd ");

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
 
 
 }
 elseif($_GET['action']=="getall_lines"){
	$lincnd="";
if($_SESSION['user_type']=='account')
	 $lincnd=" and line_id in(".$_SESSION['assign_line_ids'].") ";
 $result =$obj_db->qry ("select line_name,line_id from  ".TABLE_LINE_NAMES." where is_delete=0 $lincnd ");
 echo json_encode($result);
}
 
 elseif($_REQUEST['action']=='customer_borrowdetails'){

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
     $get_customerdts=$obj_db->qry("select a.*,b.borrow_id,b.total_amount,(b.total_amount-b.tot_amt_updconces) as conceamt,b.tot_amt_updconces as custamt,(b.tot_amt_updconces-b.remain_balance) as custpaid,b.remain_balance as rbal,c.line_name,a.city_id,d.city_name,d.weekd_id from ".TABLE_CUSTOMER_DTS." a,".TABLE_CUSTOMER_GENPAYMENTS." b,".TABLE_LINE_NAMES." c,".TABLE_LINE_CITYS." d where  a.customer_id=b.customer_id and b.is_delete=0 and a.city_id=d.city_id and a.line_id=c.line_id $usrlincnd and b.remain_balance>0 order by cast(a.customer_no as unsigned) asc");
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
                //   $lineweedts[$linid][]=array('week_id'=>0,'week_name'=>"All Weeks");
					foreach($lineweekarr[$linid] as $linwek=>$linwekv){
						$wekid=$linwekv['week_id'];
						  $lineweedts[$linid][]=array('week_id'=>$linwekv['week_id'],'week_name'=>$linwekv['week_name']);
						
							$linecitys = array_filter($linedts,function($v,$k) use ($wekid){
								return $v['weekd_id'] == $wekid;
							  },ARRAY_FILTER_USE_BOTH);
							//   $linectiydtsarr[$linid][$wekid][]=array('city_id'=>0,'city_name'=>"All Citys");
						foreach($linecitys as $cityky=>$cityv){	   
                       $linectiydtsarr[$linid][$wekid][]=array('city_id'=>$cityv['city_id'],'city_name'=>$cityv['city_name']);
						}
					}
				}

		echo json_encode(array('linedts'=>array_values($linedtsarr),'lineweedts'=>$lineweedts,'linectiydts'=>$linectiydtsarr));
			}

elseif($_REQUEST['action']=='getlinewise_paid_pendlists'){
	$weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");
	if($_SESSION['user_type']=='account')
		$usrlincnd=" and c.line_id in(0".$_SESSION['assign_line_ids'].") ";
	else $usrlincnd="";

	$get_customerdts=$obj_db->qry("select a.*,b.borrow_id,date_format(str_to_date(b.taken_date,'%Y-%m-%d'),'%d-%m-%Y') as takdt,b.no_months,b.total_amount,(b.total_amount-b.tot_amt_updconces) as conceamt,b.tot_amt_updconces as custamt,(b.tot_amt_updconces-b.remain_balance) as custpaid,b.remain_balance as rbal,c.line_name,d.monthly_amt,d.monthlydue_amt,(d.monthly_amt-d.monthlydue_amt) as monwispaid,if(d.monthlydue_amt>0,1,0) as paidsts,due_date  as duedt,date_format(str_to_date(due_date,'%d-%m-%Y'),'%Y-%m-%d') as duedts ,d.month_week,c.line_name,e.city_name,e.weekd_id  from ".TABLE_CUSTOMER_DTS." a,".TABLE_CUSTOMER_GENPAYMENTS." b,".TABLE_LINE_NAMES." c,".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." d,".TABLE_LINE_CITYS." e where  c.line_id=e.line_id and a.city_id=e.city_id and b.borrow_id=d.borrow_id and a.customer_id=b.customer_id and a.line_id='".$_REQUEST['line_id']."' and a.line_id=c.line_id and e.weekd_id='".$_REQUEST['week_id']."' and e.city_id='".$_REQUEST['city_id']."' and b.remain_balance>0 and date_format(str_to_date(d.due_date,'%d-%m-%Y'),'%Y-%m-%d')<= date_format(str_to_date('".date('d-m-Y')."','%d-%m-%Y'),'%Y-%m-%d')  order by cast(a.customer_no as unsigned) asc,date_format(STR_TO_DATE(d.due_date, '%d-%m-%Y'),'%Y-%m-%d') asc");
  	
$getallcustomergenbowr_allweekduedtsarr=array();
  foreach($get_customerdts as $custweeky=>$custweekv){
	$getallcustomergenbowr_allweekduedtsarr[$custweekv['borrow_id']][$custweekv['duedt']]=$custweekv;	
 }
 

$linedtsarr=array();$linectiydtsarr=array();$lineweedts=array();
 $uniquelineids=array_unique(array_column($get_customerdts,'line_id'));
    $lineweekarr=array();
 	foreach($get_customerdts as $lincityky=>$lincityv){
		$lineweekarr[$lincityv['line_id']][$lincityv['weekd_id']]=array("week_id"=>$lincityv['weekd_id'],"week_name"=>$weekdts[$lincityv['weekd_id']]);
		$linedtsarr[$lincityv['line_id']]=array('line_id'=>$lincityv['line_id'],'line_name'=>$lincityv['line_name']);
                     
					}
//echo '<pre>';print_r($lineweekarr);echo '</pre>';
				foreach($uniquelineids as $linkky=>$linv){
					$linid=$linv;
							$linedts = array_filter($get_customerdts,function($v,$k) use ($linid){
								return $v['line_id'] == $linid;
							  },ARRAY_FILTER_USE_BOTH);
                //   $lineweedts[$linid][]=array('week_id'=>0,'week_name'=>"All Weeks");
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
 
  
 $unque_custborrowdts = array_unique(array_column($get_customerdts, 'borrow_id'));
						$getline_allunque_custborrowdts= array_intersect_key($get_customerdts, $unque_custborrowdts);

   $unque_custborrowdatesdts = array_unique(array_column($get_customerdts, 'duedts'));
   $getall_unquecustborrowdatesdts= array_intersect_key($get_customerdts, $unque_custborrowdatesdts);

 /*$beforecutomdtdues = array_filter($get_customerdts, function($record) {
    $recordDate = strtotime($record['duedts']);
    $customdueweeks = strtotime('-8 weeks');
    $today = time();

   // return $recordDate >= $twoWeeksAgo && $recordDate <= $today;
    return $recordDate <= $customdueweeks;
});


$aftercustomduedtdues = array_filter($get_customerdts, function($record) {
    $recordDate = strtotime($record['duedts']);
    $customdueweeks = strtotime('-8 weeks');
    $today = time();

   // return $recordDate >= $twoWeeksAgo && $recordDate <= $today;
    return $customdueweeks >= $recordDate;
});
*/




$today = strtotime(date('Y-m-d'));
$givcustomweeks = strtotime('-'.$_REQUEST['manualweeks'].' weeks', $today);
// echo date('d-m-Y',$givcustomweeks);
$beforecustomWeekduedts = array_filter($get_customerdts, function($record) use ($givcustomweeks) {
    $date = strtotime($record['duedts']);
    return $date < $givcustomweeks;
});

$aftercustomWeekduedts = array_filter($get_customerdts, function($record) use ($givcustomweeks) {
    $date = strtotime($record['duedts']);
    return $date >= $givcustomweeks;
});

$getoveralluniqe_customabovedates = array_filter($getall_unquecustborrowdatesdts, function($record) use ($givcustomweeks) {
    $date = strtotime($record['duedts']);
    return $date >= $givcustomweeks;
});

$beforecustomWeekduedts=array_values($beforecustomWeekduedts);$aftercustomWeekduedts=array_values($aftercustomWeekduedts);$getoveralluniqe_customabovedates=array_values($getoveralluniqe_customabovedates);

//echo '<pre>';print_r($aftercustomWeekduedts);echo '</pre>';exit;

 		$linedtsarr=array();
		foreach($getline_allunque_custlindts as $lineky=>$linev)
             $linedtsarr[]=array('line_id'=>$linev['line_id'],'line_name'=>$linev['line_name']);
	
$cuslinepend_paidamtarr=array();

   /* foreach($getline_allunque_custborrowdts as $borrowky=>$borrowv){
		$i=0;$j=0;
		$cuslinepend_paidamtarr[$i][$j]="Sno";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="Name";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="Cust.No";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="TakenDate";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="TakenAmt";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="Discount";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="WeeklyAmt";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="PendAamt";
		$j++;
		$cuslinepend_paidamtarr[$i][$j]="Paid";
		$j++;*/
		$duedtsarr=array();$lineweekdudtsarr=array(); $totweek_citydtsarr=array();
 		foreach($getoveralluniqe_customabovedates as $dtky=>$dtv){
			//$cuslinepend_paidamtarr[$i][$j]=$dtv['duedt'];
			//$j++;
			$totweek_citydtsarr[$dtv['weekd_id']][]=$dtv;
			$duedtsarr[$dtv['weekd_id']][$dtv['city_id']][]=$dtv['duedt'];
			$lineweekdudtsarr[$dtv['weekd_id']][]=$dtv['duedt'];
 	}
  // }
      $i=0;$j=0;
		foreach($getline_allunque_custborrowdts as $borrowky=>$borrowv){
			$sno=$i+1;
			$cuslinepend_paidamtarr[$i]['sno']=$sno;
			$j++;
			$cuslinepend_paidamtarr[$i]['customer_name']=$borrowv['customer_name'];
			$j++;
			$cuslinepend_paidamtarr[$i]['customer_no']=$borrowv['customer_no'];
			$j++;
			$cuslinepend_paidamtarr[$i]['weekd_id']=$borrowv['weekd_id'];
			$j++;
			$cuslinepend_paidamtarr[$i]['city_id']=$borrowv['city_id'];
			$j++;
			$cuslinepend_paidamtarr[$i]['takdt']=$borrowv['takdt'];
			$j++;
			$cuslinepend_paidamtarr[$i]['total_amount']=$borrowv['total_amount'].'('.$borrowv['no_months'].')';
			$j++;
			$cuslinepend_paidamtarr[$i]['conceamt']=$borrowv['conceamt'];
			$j++;
			$cuslinepend_paidamtarr[$i]['monthly_amt']=$borrowv['monthly_amt'];
			$j++;
			$cuslinepend_paidamtarr[$i]['custpaid']=$borrowv['custpaid'];

               $borowid=$borrowv['borrow_id'];
							$beforecustomweekcustmergenborow = array_filter($beforecustomWeekduedts,function($v,$k) use ($borowid){
								return $v['borrow_id'] == $borowid;
							  },ARRAY_FILTER_USE_BOTH);


			$j++;
			$cuslinepend_paidamtarr[$i]['tbal']=array_sum(array_column($beforecustomweekcustmergenborow,'monthlydue_amt')).'('.count($beforecustomweekcustmergenborow).')';
		    $j++;
			$cuslinepend_paidamtarr[$i]['tpaid']=$borrowv['custpaid'];

			$j++;$custdatewiseduedtsarr=array();
			foreach($totweek_citydtsarr as $wky=>$ctydtidvs){
			foreach($ctydtidvs as $cityky=>$cityv){
			//echo $cityv['city_id'].'<pre>';print_r($lineweekdudtsarr[$wky]);echo '</pre>';
			$m=0;
 			foreach($duedtsarr[$wky][$cityv['city_id']] as $dtky=>$dtv){
 			//$cuslinepend_paidamtarr[$i][$j]=$getallcustomergenbowr_allweekduedtsarr[$borowid][$dtv['duedt']]['monthlydue_amt'];
			$custdatewiseduedtsarr[$wky][$cityv['city_id']][$m]['dueamt']=$getallcustomergenbowr_allweekduedtsarr[$borowid][$dtv];
			$j++;$m++;
           	}
		}
		}
			$cuslinepend_paidamtarr[$i]['duedtsar']=$custdatewiseduedtsarr;
	$i++;
	} 
	
  //echo '<pre>';print_r($cuslinepend_paidamtarr);print_r($duedtsarr);print_r($lineweekdudtsarr);echo '</pre>';
echo json_encode(array('linedts'=>$linedtsarr,'customerduelists'=>$cuslinepend_paidamtarr,'linectiydtsarr'=>$linectiydtsarr,'lineweedts'=>$lineweedts,'duedtsarr'=>$duedtsarr,'lineweekdudtsarr'=>$lineweekdudtsarr));
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

	$linecnd="";$lincnd1="";
	if($_SESSION['user_type']=='account'){
		$linecnd=" and a.line_id in(0".$_SESSION['assign_line_ids'].") AND a.user_id='".$_SESSION['user_id']."' ";
		$lincnd1="  and line_id in(0".$_SESSION['assign_line_ids'].") ";
	}


	$getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$_REQUEST['selline_id']."', assign_line_ids) > 0 and user_status=1 and user_type_id=2");
 $mathcusrdtsarr=array();
 foreach($getline_matchusrids as $matchusrky=>$matchusrv){
	$mathcusrdtsarr[$matchusrv['user_id']]=$matchusrv['full_name'];
 } 
 //$getlindts=$obj_db->qry("select * from ".TABLE_LINE_NAMES." where is_delete=0 $lincnd1  ");
   $getprevlinecolc=$obj_db->qry("select a.*,b.pay_name,date(date_time) as pdt,c.line_name from ".TABLE_LINEWISE_DATEWISE_AMTS." a,".TABLE_PAYMENT_TYPE." b,".TABLE_LINE_NAMES." c where a.pay_type_id=b.pay_type_id and a.line_id=c.line_id $linecnd and str_to_date(a.date_time,'%Y-%m-%d')< str_to_date('".trim($dt1)."','%d-%m-%Y') group by a.id HAVING sum(a.line_taken_amt+a.line_given_amts+a.line_collect_amts+a.line_expense_amt)>0 order by date(a.date_time) desc");
$prevlinelinecolcdtsar=array();$lineidar=array();
 foreach($getprevlinecolc as $prevamtlinky=>$prevlinev){
	$lineidar[$prevlinev['line_id']]=$prevlinev;
      $prevlinelinecolcdtsar[$prevlinev['line_id']][]=$prevlinev;
}	
 
$lineopenbaldtsar=array();
foreach($lineidar as $linky=>$linv){
	$getprevlinecolcs=$prevlinelinecolcdtsar[$linv['line_id']];
$linetakamt_frmoffice=array_sum(array_column($getprevlinecolcs,'line_taken_amt'));
  $linecolcamt_frmcustmrs=array_sum(array_column($getprevlinecolcs,'line_collect_amts'));
  $linegivamt_tocustmrs=array_sum(array_column($getprevlinecolcs,'line_given_amts'));
  $lineexpamt_frmline=array_sum(array_column($getprevlinecolcs,'line_expense_amt'));

  $previous_overalavlbal=($linetakamt_frmoffice+$linecolcamt_frmcustmrs)-($linegivamt_tocustmrs+$lineexpamt_frmline);
  $lineopenbaldtsar[$linv['line_id']]=array('line_id'=>$linv['line_id'],'line_name'=>$linv['line_name'],'prevopenbal'=>$previous_overalavlbal);
}
 $getlinewisetransctiondt=$obj_db->qry("select sum(a.line_taken_amt) as line_taken_amt,sum(a.line_given_amts) as line_given_amts,sum(a.line_collect_amts) as line_collect_amts,sum(a.line_expense_amt) as line_expense_amt,((sum(a.line_taken_amt)+sum(a.line_collect_amts))-(sum(a.line_given_amts)+sum(a.line_expense_amt))) as rembal,b.line_name,c.full_name,b.line_id,b.line_name from ".TABLE_LINEWISE_DATEWISE_AMTS." a,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c where a.line_id=b.line_id $linecnd and a.user_id=c.user_id and str_to_date(a.date_time,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y')   group by b.line_id order by b.line_id asc ");
$linewisectransdtsar=array();
foreach($getlinewisetransctiondt as $linky=>$lintrnsdtv)   
 $linewisectransdtsar[$lintrnsdtv['line_id']]=$lintrnsdtv;
$linecolcatmdtsarr=array();
	  foreach($lineopenbaldtsar as $lintrnsky=>$lintrnsv)
	   {
		$rembal=$linewisectransdtsar[$lintrnsky]['rembal']+$lintrnsv['prevopenbal'];
         $linecolcatmdtsarr[$lintrnsky]=array('openbal'=>$lintrnsv['prevopenbal'],'line_taken_amt'=>$linewisectransdtsar[$lintrnsky]['line_taken_amt'],'line_given_amts'=>$linewisectransdtsar[$lintrnsky]['line_given_amts'],'line_collect_amts'=>$linewisectransdtsar[$lintrnsky]['line_collect_amts'],'line_expense_amt'=>$linewisectransdtsar[$lintrnsky]['line_expense_amt'],'rembal'=>$rembal,'line_name'=>$lintrnsv['line_name'],'full_name'=>$linewisectransdtsar[$lintrnsky]['full_name'],'line_id'=>$lintrnsky);
	   }

	   $unque_custlinedts = array_unique(array_column($getlinewisetransctiondt, 'line_id'));
						$getline_allunque_custlindts = array_intersect_key($getlinewisetransctiondt, $unque_custlinedts);
	
	$linedtsarr=array();
	$linedtsarr[]=array('line_id'=>0,'line_name'=>"All Lines");
		foreach($getline_allunque_custlindts as $lineky=>$linev)
             $linedtsarr[]=array('line_id'=>$linev['line_id'],'line_name'=>$linev['line_name']);
	
	echo json_encode(array('linedts'=>$linedtsarr,'linewisetransdt'=>$linecolcatmdtsarr));
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

 $getline_matchusrids=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$_REQUEST['selline_id']."', assign_line_ids) > 0 and user_status=1 and user_type_id=2");
 $mathcusrdtsarr=array();
 foreach($getline_matchusrids as $matchusrky=>$matchusrv){
	$mathcusrdtsarr[$matchusrv['user_id']]=$matchusrv['full_name'];
 } 
 $getprevlinecolc=$obj_db->qry("select a.*,b.pay_name,date(date_time) as pdt from ".TABLE_LINEWISE_DATEWISE_AMTS." a,".TABLE_PAYMENT_TYPE." b where a.pay_type_id=b.pay_type_id and a.user_id in(0".implode(',',array_column($getline_matchusrids,'user_id')).") and a.line_id in(0".$_REQUEST['selline_id'].") and str_to_date(a.date_time,'%Y-%m-%d')<= str_to_date('".trim($dt1)."','%d-%m-%Y') group by a.id HAVING sum(a.line_taken_amt+a.line_given_amts+a.line_collect_amts+a.line_expense_amt)>0 order by date(a.date_time) desc");

$given_date=date('Y-m-d',strtotime($dt1));
$getprevlinecolcs = array_filter($getprevlinecolc, function($item) use ($given_date) {
    return strtotime($item['pdt']) < strtotime($given_date);
});




  $linetakamt_frmoffice=array_sum(array_column($getprevlinecolcs,'line_taken_amt'));
  $linecolcamt_frmcustmrs=array_sum(array_column($getprevlinecolcs,'line_collect_amts'));
  $linegivamt_tocustmrs=array_sum(array_column($getprevlinecolcs,'line_given_amts'));
  $lineexpamt_frmline=array_sum(array_column($getprevlinecolcs,'line_expense_amt'));

  $previous_overalavlbal=($linetakamt_frmoffice+$linecolcamt_frmcustmrs)-($linegivamt_tocustmrs+$lineexpamt_frmline);

 $unque_getprevlinecolcptyp= array_unique(array_column($getprevlinecolc, 'pay_type_id'));
						$get_unqueprevlinecolcptyps= array_intersect_key($getprevlinecolc, $unque_getprevlinecolcptyp);

			$unque_getprevlinecolcusrs= array_unique(array_column($getprevlinecolc, 'user_id'));
						$get_unqueprevlinecolcuserdts= array_intersect_key($getprevlinecolc, $unque_getprevlinecolcusrs);

			$prevamts_frmlinepatypwise=array();
			foreach($get_unqueprevlinecolcuserdts as $linurky=>$linusrv){    
				$usrid=$linusrv['user_id'];
							$linusrcolcdts = array_filter($getprevlinecolc,function($v,$k) use ($usrid){
								return $v['user_id'] == $usrid;
							  },ARRAY_FILTER_USE_BOTH);
				$lineprevdt_usrptypamtarr=array();
				foreach($get_unqueprevlinecolcptyps as $linptyp=>$linptypv){  
					$ptyp=$linptypv['pay_type_id'];
                  $linusrptypdts = array_filter($linusrcolcdts,function($v,$k) use ($ptyp){
								return $v['pay_type_id'] == $ptyp;
							  },ARRAY_FILTER_USE_BOTH);

         $lintakamt_usrptypdts=array_sum(array_column($linusrptypdts,'line_taken_amt'));
         $lincolcamt_usrptypdts=array_sum(array_column($linusrptypdts,'line_collect_amts'));
         $lingivamt_usrptypdts=array_sum(array_column($linusrptypdts,'line_given_amts'));
         $linexpamt_usrptypdts=array_sum(array_column($linusrptypdts,'line_expense_amt'));

		 $avlbal=($lintakamt_usrptypdts+$lincolcamt_usrptypdts)-($lingivamt_usrptypdts-$linexpamt_usrptypdts);
            $lineprevdt_usrptypamtarr[]=array('pay_name'=>$linptypv['pay_name'],'ptypamt'=>$avlbal);
			}  
			$usrptypeamtdts="";
			foreach($lineprevdt_usrptypamtarr as $usrptypamky=>$usrptypamv)
			{
				
                $usrptypeamtdts.=$usrptypamv['pay_name']." : ".$usrptypamv['ptypamt'].',';
			}    

			$prevamts_frmlinepatypwise[$usrid]=array('usrfulname'=>$mathcusrdtsarr[$usrid],'linpamts'=>substr($usrptypeamtdts,0,-1));       
			}

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

 $getcustomergenpayments=$obj_db->qry("select d.taken_amount,d.total_amount,d.interest_amount,d.document_charge,sum(d.interest_amount+d.document_charge) as intrestanddocucharge,b.line_name,c.full_name,b.line_id,b.line_name,c.user_name,c.full_name as line_enterusrname,d.user_id as puser_id,c.full_name as puser_fullname,a.customer_no,a.customer_name from ".TABLE_CUSTOMER_GENPAYMENTS." d ,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c,".TABLE_CUSTOMER_DTS." a  where    a.customer_id=d.customer_id and a.line_id=b.line_id $linecnd and d.user_id=c.user_id and d.is_delete=0 and str_to_date(d.taken_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y')  group by d.borrow_id order by b.line_id asc,d.borrow_id desc ");
 
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
$colc_pys_takcusttakamtdtsarr=array("colc"=>array_sum(array_column($linewiscollections,'is_paid_amount')),"emptak"=>array_sum(array_column($emptakeamts,'taken_amt')),"expamt"=>array_sum(array_column($linexpenses,'amount')),"custborrow"=>array_sum(array_column($getcustomergenpayments,'total_amount')),'intrestanddocucharge'=>array_sum(array_column($getcustomergenpayments,'intrestanddocucharge')),"rembal"=>($linerembal+$previous_overalavlbal));
//$colc_pys_takcusttakamtdtsarr=array("lincolcamt"=>array_sum(array_column($linewiscollections,'is_paid_amount')),"linexpamt"=>array_sum(array_column($linexpenses,'amount')),"linetakamt"=>array_sum(array_column($emptakeamts,'taken_amt')),"custtakamt"=>array_sum(array_column($customer_takenamts,'amount')));
//echo  '<pre>';print_r(array('lineoveralview'=>$colc_pys_takcusttakamtdtsarr,'userwiseptypwiseexistbal'=>$lineexist_ptypamtdtsarr,'users'=>$ptypuserdts,'ptypes'=>$ptypdtsarr,'lincolcamtdts'=>$linewiscollections,'linexpdts'=>$linexpenses,'linemptakamtdts'=>$emptakeamts,'lincusttakamtdts'=>$customer_takenamts,'custcolcptypusrdtypdtsarr'=>$custcolcptypusrdtypdtsarr,'custptypcolc'=>$customercolcusrwiseptypwisarr,'emptakamtptypusrtyparr'=>$emptakamtptypusrtyparr,'lineptypexp'=>$lineexpptypdtsarr,'lincusttakamtptypusrtyparr'=>$lincusttakamtptypusrtyparr,'expdtsusrtypptyparr'=>$expdtsusrtypptyparr,'linptyptakamt'=>$lintakamttypdtsarr,'lineptypcsusttakamt'=>$lincusttakamttypdtsarr));echo '</pre>';
echo json_encode(array('lineoveralview'=>$colc_pys_takcusttakamtdtsarr,'userwiseptypwiseexistbal'=>$lineexist_ptypamtdtsarr,'users'=>$ptypuserdts,'ptypes'=>$ptypdtsarr,'lincolcamtdts'=>$linewiscollections,'linexpdts'=>$linexpenses,'linemptakamtdts'=>$emptakeamts,'lincusttakamtdts'=>$customer_takenamts,'custcolcptypusrdtypdtsarr'=>$custcolcptypusrdtypdtsarr,'custptypcolc'=>$customercolcusrwiseptypwisarr,'emptakamtptypusrtyparr'=>$emptakamtptypusrtyparr,'lineptypexp'=>$lineexpptypdtsarr,'lincusttakamtptypusrtyparr'=>$lincusttakamtptypusrtyparr,'expdtsusrtypptyparr'=>$expdtsusrtypptyparr,'linptyptakamt'=>$lintakamttypdtsarr,'lineptypcsusttakamt'=>$lincusttakamttypdtsarr,'previous_overalavlbal'=>$previous_overalavlbal,'prevamts_frmlinepatypwisear'=>$prevamts_frmlinepatypwise,'getcustomergenpayments'=>$getcustomergenpayments));
}
elseif($_REQUEST['action']=='update_borrowdate' && $_REQUEST['access_tokenid']==$_COOKIE['lograndval']){
	 $takdat=date('Y-m-d',strtotime($_REQUEST['updbrw_date']));
  	 $updgenbowrtakdate=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_CUSTOMER_GENPAYMENTS."  SET taken_date='".$takdat."' where borrow_id='".$_REQUEST['br_id']."'");  
 	$getweekpaydts=$obj_db->qry("select * from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." where borrow_id='".$_REQUEST['br_id']."'  order by month_week asc");
	
	if($_REQUEST['updbrw_date']!='')
	 $curdat=$_REQUEST['updbrw_date'];
	else
	$curdat=date('d-m-Y');
	if($_REQUEST['pay_type']==1){
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
	foreach($getweekpaydts as $brdtky=>$brdtv){  

    $updduedt=UPDATE_KEYWORD."   ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS."  SET due_date='".$due_date."' where id='".$brdtv['id']."'";  
			  $res=$obj_db->get_qresult($updduedt);	

			  $exp_duedt=explode('-',$due_date);
			  if($_REQUEST['pay_type']==1)
			  $due_date=date('d-m-Y', strtotime($due_date. ' + '.$nodays.' day'));
			  else{
			  $due_dat = mktime(0,0,0,$exp_duedt[1]+1,1,$exp_duedt[2]);
			  $due_date=date('d-m-Y',$due_dat);
			  }
	 }
	 echo 'success';
}
elseif($_REQUEST['action']=='submit_customer_payments' && $_REQUEST['access_tokenid']==$_COOKIE['lograndval']){
	extract($_POST);
 	$custpadts=json_decode($_REQUEST['custbrowdata'],true);
$partybranh_dts=$obj_db->fetchRow("select line_id from ".TABLE_CUSTOMER_DTS." where customer_id='".$_REQUEST['customer_id']."'");
			 $branch_query="select * from ".TABLE_BRANCH." where 	branch_id='".$partybranh_dts['branch_id']."'"; 
		$branch_row=$obj_db->fetchRow($branch_query);
		$branchid=$branch_row['branch_id'];

		$exppaytypes=array(1=>"Cash",2=>"Upi");
//$_SESSION['linematch_users'][$partybranh_dts['line_id']][0]['user_id'];;
 
		if($_REQUEST['sel_user_id']!='')
          $seluserid=$_REQUEST['sel_user_id'];
	    else $seluserid=$_SESSION['user_id'];;
		
		if($_REQUEST['g_date']!='' && date('d-m-Y')==$_REQUEST['g_date']){
		$pay_dt=date('Y-m-d H:i:s');
		$pdt=date('Y-m-d',strtotime($_REQUEST['g_date']));
		}elseif($_REQUEST['g_date']!=''){
		$pay_dt=date('Y-m-d H:i:s',strtotime($_REQUEST['g_date']));
		$pdt=date('Y-m-d',strtotime($_REQUEST['g_date']));
		}else{$pay_dt=date('Y-m-d H:i:s');$pdt=date('Y-m-d');}	  
		 	 
	    $paidgen_inc=0;
		$tpaid=0;

		

 		
			  if($_REQUEST['payment_type']>1)
				$ptypuser_payid=$_REQUEST['upayuser_id'];
			 else $ptypuser_payid=$seluserid;
		
		
		$chk_dtwise_numdts=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$partybranh_dts['line_id']."' and user_id='".$seluserid."' order by date(date_time) desc limit 1");	 
		  $chk_dtwise_lineamtdts=$obj_db->fetchNum("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$partybranh_dts['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
		  $get_givdt_curbals=$obj_db->fetchRow("select * from ".TABLE_LINEWISE_DATEWISE_AMTS." where line_id='".$partybranh_dts['line_id']."' and user_id='".$seluserid."' and date(date_time)='".$pdt."'");
		  
		
 //if($chk_dtwise_numdts['date_time']<=$pdt || $chk_dtwise_numdts['date_time']==''){
		foreach($custpadts as $custpky=>$custpv){
		if($custpv['customer_pays']>0){
	
		 $pay_fee=$custpv['customer_pays'];
		 $tpaid=$tpaid+$pay_fee;
		$custfee_updates=UPDATE_KEYWORD."  ".TABLE_CUSTOMER_GENPAYMENTS." set remain_balance=(remain_balance-'$pay_fee') where customer_id='".$_REQUEST['customer_id']."' and borrow_id='".$custpv['borrow_id']."' and year_id='".$_SESSION['year_id']."'";
		               $fee_updres=$obj_db->get_qresult($custfee_updates);
		$get_monweekpayments=$obj_db->get_qresult("select borrow_id,monthlydue_amt,month_week from ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." where customer_id='".$_REQUEST['customer_id']."' and borrow_id='".$custpv['borrow_id']."' and year_id='".$_SESSION['year_id']."' group by month_week having sum(monthlydue_amt)>0  order by month_week asc");
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
		$upd_dueamts=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUSTOMER_WEEKMON_PAYYMENTS." set monthlydue_amt=monthlydue_amt-'".$wk_monpay."' where customer_id='".$_REQUEST['customer_id']."' and borrow_id='".$custpv['borrow_id']."' and year_id='".$_SESSION['year_id']."' and month_week='".$get_monweekprw['month_week']."'");
		$wk_monpay=0;
		}
		$substr_weeks=substr($weeks,0,-1);
		$substr_dueamts=substr($dueamts,0,-1);
					   
		  $cust_payment=INSERT_KEYWORD."   ".TABLE_CUST_PAYMENTS." SET 
	                               			customer_id='".$obj_db->real_escape_string($_REQUEST['customer_id'])."', 
											borrow_id='".$obj_db->real_escape_string($custpv['borrow_id'])."',
											line_id='".$obj_db->real_escape_string($partybranh_dts['line_id'])."',
											enter_by='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											user_id='".$obj_db->real_escape_string($seluserid)."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."', 
										    is_paid_amount='".$obj_db->real_escape_string($custpv['customer_pays'])."',
											paid_date='".$pay_dt."',
											enter_date='".date('Y-m-d H:i:s')."',
											paid_week_months='".$substr_weeks."',
											paid_amts='".$substr_dueamts."',
											receipt_no='".$obj_db->real_escape_string($receipt_no_tf)."',
	                               			pay_type_id='".$obj_db->real_escape_string($_REQUEST['payment_type'])."',
											payto_user_id='".$obj_db->real_escape_string($_REQUEST['upayuser_id'])."', 
											received_bank_id='".$obj_db->real_escape_string($_REQUEST['received_bank_id'])."',
											bank_name='".$obj_db->real_escape_string($_REQUEST['bank_name'])."',
											cheque_num='".$obj_db->real_escape_string($_REQUEST['cheque_num'])."',
											cheque_date='".$obj_db->real_escape_string($_SESSION['cheque_date'])."'";
							$cuspay_updres=$obj_db->get_qresult($cust_payment);
					$paidref_id=$obj_db->insert_id();
				if($paidgen_inc==0) {
			   $commrpidefid1=$paidref_id;
		       $paidgen_inc++;
               }

			    $ref_id=$obj_db->insert_id();
             
			 $comfidarr[]=$paidref_id;
			 $comfamtsarr[]=$custpv['customer_pays'];
		}
        }   
		
		


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

				$updcustakamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_DATEWISE_AMTS." set line_collect_amts=line_collect_amts+'".array_sum(array_column($custpadts,'customer_pays'))."' where user_id='".$ptypuser_payid."' AND pay_type_id='".$_REQUEST['payment_type']."' and line_id='".$partybranh_dts['line_id']."' and date(date_time)='".date('Y-m-d',strtotime($pay_dt))."' ");
		   $getuserlinwiseexistamt=$obj_db->get_qresult(UPDATE_KEYWORD." ".TABLE_LINEWISE_USEREXIST_AMTS." set line_collect_amts=line_collect_amts+'".array_sum(array_column($custpadts,'customer_pays'))."' where user_id='".$ptypuser_payid."' and pay_type_id='".$_REQUEST['payment_type']."' and line_id='".$partybranh_dts['line_id']."' ");
	
		   $usrptypavlbalnce=$obj_db->get_qresult(UPDATE_KEYWORD."   ".TABLE_USERWISE_PAYTYPEWISE_AVAILABLEBALANCE."  SET 
											avlbalance=avlbalance+'".$obj_db->real_escape_string($tpaid)."'
											 where user_id='".$ptypuser_payid."' and pay_type_id='".$_REQUEST['payment_type']."'");
 		$upd_refid=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUST_PAYMENTS." set ref_id='".$comfidarr[0]."' where customer_id='".$_REQUEST['customer_id']."' and id in(0".implode(',',$comfidarr).") ");
					  
        $get_maxgen_id=$obj_db->fetchRow("select max(gen_id) as rec_id from ".TABLE_CUST_PAYMENTS." where year_id='".$_SESSION['year_id']."'");  
		$gen_id=$get_maxgen_id['rec_id']+1;
 	      $genr='REC/'.$partybranh_dts['line_id'].'/'.$_SESSION['year_id'].'/'.$gen_id;	
		$upd_refid=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_CUST_PAYMENTS." set receipt_no='".$genr."',gen_id='".$gen_id."' where ref_id='".$commrpidefid1."' and customer_id='".$_REQUEST['customer_id']."'");
		
			 	
	$std_nameqry=$obj_db->fetchRow("SELECT * from ".TABLE_CUSTOMER_DTS." where customer_id='".$_REQUEST['customer_id']."'");
       $msg="Dear  ".$std_nameqry['party_name']." Thank you for paying ".$branch_row['branch_name'].", Your Paid Amount is : ".$tpaid." Remaining Balance Rs-".$rembal['rbal'];
echo 'success';

}
?>