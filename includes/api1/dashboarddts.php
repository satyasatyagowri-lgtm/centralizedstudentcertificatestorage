<?php session_start();
include "../DbConfig.php";
  if($_REQUEST['action']=="dwisebrn_colc"){
  $brncolcs=array();$brnexps=array();
  if($_REQUEST['gdt']=='')
   $gdt=date('Y-m-d');
  else $gdt=date('Y-m-d',strtotime($_REQUEST['gdt']));
  
       $branch_fecolc=$obj_db->qry("select DATE_FORMAT(STR_TO_DATE(b.paid_date, '%Y-%m-%d'), '%d-%m-%Y') as pdt,b.branch_id,sum(is_paid_amount) as tpaids,b.payment_type,a.user_name,a.user_id,c.exp_name from ".TABLE_USER_DETAILS." a,".TABLE_FEE_PAYMENT." b,".TABLE_EXPENDITURE_TYPE." c  where  b.branch_id in(".$_SESSION['assign_branch_ids'].")   and c.exp_type_id=b.payment_type and a.user_id=b.user_id and receipt_cancelled=0 group by b.branch_id,date(b.paid_date),receipt_no order by b.id desc,b.branch_id asc limit 100");
	 if(count($branch_fecolc)>0)
	  $branch_fecolc=$branch_fecolc;
	else $branch_fecolc=array();  
	   $colcbrndis=array_column($branch_fecolc,'branch_id');
	    $colcpdts=array_column($branch_fecolc,'pdt');
		 $colcusrids=array_column($branch_fecolc,'user_id');
		  $colcpaytyps=array_column($branch_fecolc,'payment_type');
	  $brncolcs[]=array('pdt'=>'','branch_id'=>0,'tpaids'=>'','payment_type'=>'','user_name'=>'','user_id'=>'','exp_name'=>'');
	  foreach($branch_fecolc as  $k=>$v)
	  array_push($brncolcs,$v);
	   $branch_exps=$obj_db->qry("select DATE_FORMAT(STR_TO_DATE(b.enter_date, '%Y-%m-%d'), '%d-%m-%Y') as edt,b.branch_id,sum(amount) as expamt,a.exp_name from ".TABLE_EXPENDITURE_TYPE." a,".TABLE_EXPENDITURE." b  where b.branch_id in(".$_SESSION['assign_branch_ids'].") and a.exp_type_id=b.exp_type_id and b.is_cancel=0 group by b.branch_id,date(b.enter_date),b.exp_type_id order by b.branch_id asc limit 100");
	   
	   if(count($branch_exps)>0)
	   $branch_exps=$branch_exps;
	  else $branch_exps=array();
	   $expbrnids=array_column($branch_exps,'branch_id');
	   
	   $expdts=array_column($branch_exps,'pdt');
	   
	   $dtsmerge=array_unique(array_merge($colcpdts,$expdts));
	   
	   $tbrns=array();
	   foreach($colcbrndis as $k=>$v)
	   $tbrns[]=$v;
	   foreach($expbrnids as $k=>$v)
	   $tbrns[]=$v;
 	   foreach($tbrns as $k=>$v)
	     $tbrnids.=$v.',';
		  $trmbrnids=substr($tbrnids,0,-1);
		$get_brndts=$obj_db->qry("select * from ".TABLE_BRANCH." where branch_id in(0".$trmbrnids.")");
	   $brnexps[]=array('edt'=>'','branch_id'=>0,'expamt'=>'','exp_name'=>'');
	 
	  foreach($branch_exps as  $k=>$v)
	  array_push($brnexps,$v);
	  
	  
	   $dtsmerge=array_unique(array_merge($colcpdts,$expdts));
	   $dtwisecolc_exp=array();
	   $i=0;
 	   foreach($dtsmerge as $dtk=>$dtv){
	   $pdt=$dtv;
			  $dtwisecolc = array_filter($brncolcs,function($v,$k) use ($pdt){
				 return $v['pdt'] == $pdt;
			   },ARRAY_FILTER_USE_BOTH);
 			   
			   $dtwiseincmbrnids=array_column($dtwisecolc,'branch_id');
			   
		$edt=$dtv;
			  $dtwisexp = array_filter($brnexps,function($v,$k) use ($edt){
				 return $v['edt'] == $edt;
			   },ARRAY_FILTER_USE_BOTH);
			   $dtwisexp_brnids=array_column($dtwisexp,'branch_id');
			   $dtwisebrnids=array_unique(array_merge($dtwiseincmbrnids,$dtwisexp_brnids));
 			  foreach($dtwisebrnids as $dtbrnky=>$dtbrnval){
			   $brnky=array_search($dtbrnval,array_column($get_brndts,'branch_id'));
			  $brnid=$dtbrnval;
			    $dtwisebrncolc = array_filter($dtwisecolc,function($v,$k) use ($brnid){
				 return $v['branch_id'] == $brnid;
			   },ARRAY_FILTER_USE_BOTH);
			   $dtwisebrnincm=array_column($dtwisebrncolc,'tpaids');
 			   $dtwisbrnexp = array_filter($dtwisexp,function($v,$k) use ($brnid){
				 return $v['branch_id'] == $brnid;
			   },ARRAY_FILTER_USE_BOTH);
 			   $dtwisebrnexps=array_column($dtwisbrnexp,'expamt');
			   
			$dtwisecolc_exp[$i][$dtv][$dtbrnval]['branch_short_name']=$get_brndts[$brnky]['branch_short_name'];
			$dtwisecolc_exp[$i][$dtv][$dtbrnval]['dt']=$dtv;
			$dtwisecolc_exp[$i][$dtv][$dtbrnval]['incm']=array_sum($dtwisebrnincm);
			$dtwisecolc_exp[$i][$dtv][$dtbrnval]['exp']=array_sum($dtwisebrnexps);
			$dtwisecolc_exp[$i][$dtv][$dtbrnval]['bal']=array_sum($dtwisebrnincm)-array_sum($dtwisebrnexps);
			}
			$dtarrdts[$i]['dt']=$dtv;
			$i++;
			
	   }
	  
	  //colcusrids
	  
 	   $dtusrwisecolc_exp=array();$dtwiseusrs=array();$dtwisepays=array();
	   $i=0;
	   foreach($dtsmerge as $dtk=>$dtv){
	   
	   $pdt=$dtv;
			  $dtwisecolc = array_filter($brncolcs,function($v,$k) use ($pdt){
				 return $v['pdt'] == $pdt;
			   },ARRAY_FILTER_USE_BOTH);
			   sort($dtwisecolc['payment_type']);
			   $dtusrids=array();$dtpayids=array();
			   $g=1;
			   foreach($dtwisecolc as $k=>$v){
			   if(!in_array($v['user_id'],$dtusrids)){
			   $dtusrids[]=$v['user_id'];
			   $dtwiseusrs[$dtv]['user_id'][$v['user_id']]=$v['user_name'];
			   }
			    if(!in_array($v['payment_type'],$dtpayids)){
				$dtpayids[]=$v['payment_type'];
				if($v['exp_name']!=''){
			   $dtwisepays[$dtv]['payment_type'][$g]=$v['exp_name'];
			    $g++;
				}
			   }
			  
			   }
			   
			   $colcusrids=array_unique(array_column($dtwisecolc,'user_id'));
			   $paytypes=array_unique(array_column($dtwisecolc,'payment_type'));
			    $incmaxpaytyp=max($paytypes)+1;
			$usrpayamts=array();
			$g=1;
			  foreach($colcusrids as $usrky=>$usrval){
			   $usrid=$usrval;
			  $usralpaytyps = array_filter($dtwisecolc,function($v,$k) use ($usrid){
				 return $v['user_id'] == $usrid;
			   },ARRAY_FILTER_USE_BOTH);
			   $usrky=array_search($usrid,array_column($brncolcs,'user_id'));
			   $dtusrwisecolc_exp[$dtv][$usrid]['pamt'][$g]=$brncolcs[$usrky]['user_name'];
			 $usrtotsum=0;
			 //print_r($paytypes);
			 $g++;
			  foreach($paytypes as $payky=>$pays){
			   $payid=$pays;
			   $usralpaytypsamt = array_filter($usralpaytyps,function($v,$k) use ($payid){
				 return $v['payment_type'] == $payid;
			   },ARRAY_FILTER_USE_BOTH);
			   $tpaidamts=array_sum(array_column($usralpaytypsamt,'tpaids'));
			  $usrtotsum=$usrtotsum+$tpaidamts;
			$dtusrwisecolc_exp[$dtv][$usrid]['pamt'][$g]=$tpaidamts;
			$usrpayamts[$pays][]=$tpaidamts;
			$g++;
			}
			$dtusrwisecolc_exp[$dtv][$usrid]['pamt'][$g]=$usrtotsum;
			}
			//echo '<pre>';print_r($usrpayamts);echo '</pre>';
			
			 //foreach($colcusrids as $k=>$v){
			 $g=2;
			  foreach($paytypes as $k1=>$v1){
			//  echo $v.'  '.$v1.'  '.$usrpayamts[$v][$v1].'<br>';
			  $dtusrwisecolc_exp[$dtv]['tamt'][$g]=array_sum($usrpayamts[$v1]);
			  $g++;
			  }
			  $dtusrwisecolc_exp[$dtv]['tamt'][$g]=array_sum($dtusrwisecolc_exp[$dtv]['tamt']);
			// }
 			$i++;
			
	   
	   }
	  
	//  echo '<pre>';print_r($dtusrwisecolc_exp);echo '</pre>';
	 /* $brnfecolc=array();
	  foreach($get_brndts as $k=>$v){
	  $colcbrnky=array_search($v['branch_id'],array_column($brncolcs,'branch_id'));
	  $epbrnky=array_search($v['branch_id'],array_column($brnexps,'branch_id'));
	    $brnfecolc[$k]['branch']=$v['branch_short_name'];
		$brnfecolc[$k]['colc']=$brncolcs[$colcbrnky]['tpaids'];
		$brnfecolc[$k]['exp']=$brnexps[$epbrnky]['expamt'];
		$brnfecolc[$k]['bal']=$brncolcs[$colcbrnky]['tpaids']-$brnexps[$epbrnky]['expamt'];
	  }
	  $tcolcamt=array_sum(array_column($brnfecolc,'colc'));
	   $texpamt=array_sum(array_column($brnfecolc,'exp'));
	    $tbalamt=array_sum(array_column($brnfecolc,'bal'));*/
	  
	   echo json_encode(array('brnfecolc_exp'=>$dtwisecolc_exp,'dtusrwisecolc_exp'=>$dtusrwisecolc_exp,'branch_fecolc'=>$branch_fecolc,'branch_exps'=>$branch_exps,'brndts'=>$get_brndts,'dtarrdts'=>$dtarrdts,'dtwiseusrs'=>$dtwiseusrs,'dtwisepays'=>$dtwisepays));
  }
  else if($_REQUEST['action']=="dwisebrn_colc"){
  $brncolcs=array();$brnexps=array();
  if($_REQUEST['gdt']=='')
   $gdt=date('Y-m-d');
  else $gdt=date('Y-m-d',strtotime($_REQUEST['gdt']));
  
       $branch_fecolc=$obj_db->qry("select b.branch_id,sum(is_paid_amount) as tpaids from ".TABLE_FEE_PAYMENT." b  where   date(paid_date) between '".$gdt."' and '".$gdt."' and b.branch_id in(".$_SESSION['assign_branch_ids'].") and receipt_cancelled=0 group by b.branch_id order by branch_id asc");
	   $colcbrndis=array_column($branch_fecolc,'branch_id');
	  $brncolcs[]=array('branch_id'=>0,'tpaids'=>'');
	  foreach($branch_fecolc as  $k=>$v)
	  array_push($brncolcs,$v);
	   $branch_exps=$obj_db->qry("select b.branch_id,sum(amount) as expamt from ".TABLE_EXPENDITURE." b  where  date(enter_date) between '".$gdt."' and '".$gdt."' and b.branch_id in(".$_SESSION['assign_branch_ids'].") and is_cancel=0 group by b.branch_id order by branch_id asc");
	   $expbrnids=array_column($branch_exps,'branch_id');
	   
	   $tbrns=array();
	   foreach($colcbrndis as $k=>$v)
	   $tbrns[]=$v;
	   foreach($expbrnids as $k=>$v)
	   $tbrns[]=$v;
 	   foreach($tbrns as $k=>$v)
	     $tbrnids.=$v.',';
		  $trmbrnids=substr($tbrnids,0,-1);
		$get_brndts=$obj_db->qry("select * from ".TABLE_BRANCH." where branch_id in(0".$trmbrnids.")");
	   $brnexps[]=array('branch_id'=>0,'expamt'=>'');
	 
	  foreach($branch_exps as  $k=>$v)
	  array_push($brnexps,$v);
	  $brnfecolc=array();
	  foreach($get_brndts as $k=>$v){
	  $colcbrnky=array_search($v['branch_id'],array_column($brncolcs,'branch_id'));
	  $epbrnky=array_search($v['branch_id'],array_column($brnexps,'branch_id'));
	    $brnfecolc[$k]['branch']=$v['branch_short_name'];
		$brnfecolc[$k]['colc']=$brncolcs[$colcbrnky]['tpaids'];
		$brnfecolc[$k]['exp']=$brnexps[$epbrnky]['expamt'];
		$brnfecolc[$k]['bal']=$brncolcs[$colcbrnky]['tpaids']-$brnexps[$epbrnky]['expamt'];
	  }
	  $tcolcamt=array_sum(array_column($brnfecolc,'colc'));
	   $texpamt=array_sum(array_column($brnfecolc,'exp'));
	    $tbalamt=array_sum(array_column($brnfecolc,'bal'));
	  
	   echo json_encode(array('brnfecolc'=>$brnfecolc,'tcolcamt'=>$tcolcamt,'texpamt'=>$texpamt,'tbalamt'=>$tbalamt));
  }
 elseif($_REQUEST['action']=="overal_income"){
  $incomedts=$obj_db->qry("select d.fee_id,d.fee_name,a.branch_short_name,b.branch_id,ROUND(sum(term_amount)) as orgamt,ROUND(sum(term_amount)-sum(term_income)) as coness,ROUND(sum(term_income)) as commitment,ROUND(sum(term_income)-sum(term_due)) as tpaid,ROUND(sum(term_due)) as tbal,round((sum(term_income)-sum(term_due))/sum(term_income)*100) as paidper from ".TABLE_BRANCH." a,".TABLE_STUDENT_EDU_DETAILS." b,".TABLE_STUDENT_FEE." c,".TABLE_FEE_TYPE." d  where  c.fee_type=d.fee_id and b.branch_id=a.branch_id and b.branch_id in(".$_SESSION['assign_branch_ids'].") and b.student_id=c.student_id and b.y_id=c.y_id and b.y_id='".$_SESSION['year_id']."' group by b.branch_id,c.fee_type order by a.branch_id asc,d.fee_id asc");
   
    $feids=array_column($incomedts,'fee_id');
	$feid_aryunique=array_unique($feids);
	$brnids=array_column($incomedts,'branch_id');
    $brn_aryunique=array_unique($brnids);
	$incomedtsarr=array();$fenamedtsarr=array();
	foreach($brn_aryunique as $bk=>$bv){
	$brnid=$bv;
	 $brn_incmdts = array_filter($incomedts,function($v,$k) use ($brnid){
				return $v['branch_id'] == $brnid;
			  },ARRAY_FILTER_USE_BOTH);
	
	$orgamts=array_column($brn_incmdts,'orgamt');
	$coness=array_column($brn_incmdts,'coness');
	$commitment=array_column($brn_incmdts,'commitment');
	$tpaid=array_column($brn_incmdts,'tpaid');
	$tbal=array_column($brn_incmdts,'tbal');
	
	 $brnky=array_search($bv,array_column($incomedts,'branch_id'));
	 $incomedtsarr[$bk]['branch_short_name']=$incomedts[$brnky]['branch_short_name'];
	 $incomedtsarr[$bk]['orgamt']=array_sum($orgamts);
	 $incomedtsarr[$bk]['coness']=array_sum($coness);
	 $incomedtsarr[$bk]['commitment']=array_sum($commitment);
	 $incomedtsarr[$bk]['tpaid']=array_sum($tpaid);
	 $incomedtsarr[$bk]['tbal']=array_sum($tbal);
	 
	
	}
	$arcolors=array(0=>'blue',1=>'green',2=>'red',3=>'yellow');
	$icolor=0;
	foreach($feid_aryunique as $fk=>$fv){
	$color=$arcolors[$icolor];
	$fid=$fv;
	 $brn_fesdts = array_filter($incomedts,function($v,$k) use ($fid){
				return $v['fee_id'] == $fid;
			  },ARRAY_FILTER_USE_BOTH);
	
	$orgamts=array_column($brn_fesdts,'orgamt');
	$coness=array_column($brn_fesdts,'coness');
	$commitment=array_column($brn_fesdts,'commitment');
	$tpaid=array_column($brn_fesdts,'tpaid');
	$tbal=array_column($brn_fesdts,'tbal');
	$tpaidper=array_sum(array_column($brn_fesdts,'paidper'))/count(array_column($brn_fesdts,'paidper'));
	
	 $fnameky=array_search($fv,array_column($incomedts,'fee_id'));
	 $fenamedtsarr[$fk]['fee_name']=$incomedts[$fnameky]['fee_name'];
	 $fenamedtsarr[$fk]['orgamt']=array_sum($orgamts);
	 $fenamedtsarr[$fk]['coness']=array_sum($coness);
	 $fenamedtsarr[$fk]['commitment']=array_sum($commitment);
	 $fenamedtsarr[$fk]['tpaid']=array_sum($tpaid);
	 $fenamedtsarr[$fk]['tbal']=array_sum($tbal);
	 $fenamedtsarr[$fk]['paidper']=round($tpaidper);
	 $fenamedtsarr[$fk]['color']=$color;
	$icolor++;
	if($icolor>3)$icolor=0;
	}

  echo json_encode(array('incomedts'=>$incomedtsarr,'fewiseincome'=>$fenamedtsarr));
 }
 
?>