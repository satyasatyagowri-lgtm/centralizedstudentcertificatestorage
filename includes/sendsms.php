<?php session_start();
include "DbConfig.php";
if($_REQUEST['send_gensms']=='send_gensms'){
 $branch_query="SELECT branch_name,branch_short_name,branch_city FROM ".TABLE_BRANCH." where branch_id='".$_SESSION['branch_id']."'";
			 	 $branch_res_row=$obj_db->fetchRow($branch_query);
 $msg=$_REQUEST['messag']." -".$branch_res_row['branch_name'];
 
 
 
  $tmp_qry=$obj_db->qry("SELECT * FROM ".TABLE_SMS_TEMPLATES." where temp_id='".$_REQUEST['temp_id']."' order by temp_id asc");
$orgvar="";
$expcont=explode('{#var#}',$tmp_qry[0]['message']);
$tcnvar=count($expcont)-1;
$i=0;$j=1; $txtinputvar=$_REQUEST['Myvar0'];
foreach($expcont as $k=>$v){
 
  $orgvar.=$v.$txtinputvar;
  if($i==$tcnvar)break;
  else $txtinputvar=$_REQUEST['Myvar'.$j];
  $i++;$j++;
  }
   $extmp=0;
  if($tcnvar!=$j){
  $j=$j-1;
  $extmp=1;
  $removbletmp="Myvar".$j;
$orgvar=str_replace("Myvar".$j,"",$orgvar);
}
 
 
 $msg=$orgvar;
/*if($_REQUEST['lang']=="te"){
		    sendunicodesms($_REQUEST['mob'],$msg,SMS_URL2,SMS_USERNAME,SMS_PWORD,SENDERID,FEEPAYMENTSMS_TEMPID);
}else{*/
    sendsms($_REQUEST['mobile'],$msg,SMS_ENGURL,SMS_USERNAME,SMS_PWORD,SENDERID,$tmp_qry[0]['template_no']); 
//}
//redirect_page($page_url."&success=success");
echo 'success';
}
elseif($_REQUEST['action']=='get_studens_strength'){

  if($_REQUEST['course_all']==1){
		  $sec=$_REQUEST['sec_ids'];
		$secids=substr($sec,0,-1);
		if($_REQUEST['sec_ids']!='')
		$secid=" sec_id in(".$secids.") and ";
		else
		$secid=" course_id='".$_REQUEST['course_id']."' and";
	  }
		  else{
		  $secid='';
		  }
		  $branch_query=$obj_db->fetchRow("SELECT branch_name,branch_short_name,branch_city  FROM ".TABLE_BRANCH." where branch_id=".$_REQUEST['branch_id']."");
		  
		$get_totstds=$obj_db->fetchNum("select mobile_no,first_name,last_name from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b where $secid  branch_id='".$_REQUEST['branch_id']."' and is_delete=0 and y_id='".$_SESSION['year_id']."' and a.student_id=b.student_id and mobile_no RLIKE '[0-9]{10}' group by mobile_no");
		echo $get_totstds;
}
elseif($_REQUEST['action']=='get_studens_sms'){

  if($_REQUEST['course_all']==1){
		  $sec='';
		  foreach($_REQUEST['sec_id'] as $k=>$v)
		   $sec.=$v.',';
		$secids=substr($sec,0,-1);
		if(count($_REQUEST['sec_id'])>0)
		$secid=" sec_id in(".$secids.") and ";
		else
		$secid=" course_id in (".$_REQUEST['course_id'][0].") and";
	  }
       elseif($_REQUEST['course_all']==0 && is_numeric($_REQUEST['course_all'])){
 $secid='';
}
	  elseif(count($_REQUEST['course_id'])>0){
	   foreach($_REQUEST['course_id'] as $k=>$v)
		   $coursids.=$v.',';
	    $trm_coursids=substr($coursids,0,-1);
		$secid=" course_id in (".$trm_coursids.") and";
	  }
		  else{
		  $secid='';
		  }
		  $branch_query=$obj_db->fetchRow("SELECT branch_name,branch_short_name,branch_city  FROM ".TABLE_BRANCH." where branch_id=".$_REQUEST['branch_id']."");
             $stdquery=$obj_db->qry("select mobile_no,first_name,last_name from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b where $secid  branch_id='".$_REQUEST['branch_id']."' and is_delete=0 and y_id='".$_SESSION['year_id']."' and a.student_id=b.student_id and mobile_no RLIKE '[0-9]{10}' group by mobile_no limit ".$_REQUEST['strt'].",1000");
		  
		$implodemobnums=implode(',',array_column($stdquery,'mobile_no'));
		//$msg=$_REQUEST['messag']." -".$branch_query['branch_name'];
		
		
		 $tmp_qry=$obj_db->qry("SELECT * FROM ".TABLE_SMS_TEMPLATES." where temp_id='".$_REQUEST['temp_id']."' order by temp_id asc");
$orgvar="";
$expcont=explode('{#var#}',$tmp_qry[0]['message']);
$tcnvar=count($expcont)-1;
$i=0;$j=1; $txtinputvar=$_REQUEST['Myvar0'];
foreach($expcont as $k=>$v){
 
  $orgvar.=$v.$txtinputvar;
  if($i==$tcnvar)break;
  else $txtinputvar=$_REQUEST['Myvar'.$j];
  $i++;$j++;
  }
   $extmp=0;
  if($tcnvar!=$j){
  $j=$j-1;
  $extmp=1;
  $removbletmp="Myvar".$j;
$orgvar=str_replace("Myvar".$j,"",$orgvar);
}
 
 
  $msg=$orgvar;
		
		
		/*if($_REQUEST['lang']=="te"){
		     sendunicodesms($trmmob,$msg,SMS_URL2,SMS_USERNAME,SMS_PWORD,SENDERID,FEEPAYMENTSMS_TEMPID);
}else{
     sendsms($trmmob,$msg,SMS_ENGURL,SMS_USERNAME,SMS_PWORD,SENDERID,FEEPAYMENTSMS_TEMPID);
}*/
  sendsms($implodemobnums,$msg,SMS_ENGURL,SMS_USERNAME,SMS_PWORD,SENDERID,$tmp_qry[0]['template_no']); 
//close connection 
echo '^'.count($stdquery);
}			

elseif($_REQUEST['action']=='corusewisestdsms'){

  for($i=0;$i<sizeof($_REQUEST['mobile']);$i++){
		   $mob.=trim($_REQUEST['mobile'][$i]).",";
		}	
	echo	$trmmob=substr($mob,0,-1);
		
		  $branch_query=$obj_db->fetchRow("SELECT branch_name,branch_short_name,branch_city  FROM ".TABLE_BRANCH." where branch_id=".$_REQUEST['branch_id']."");
          
		//$msg=$_REQUEST['messag']." -".$branch_query['branch_name'];
		
		
		 $tmp_qry=$obj_db->qry("SELECT * FROM ".TABLE_SMS_TEMPLATES." where temp_id='".$_REQUEST['temp_id']."' order by temp_id asc");
$orgvar="";
$expcont=explode('{#var#}',$tmp_qry[0]['message']);
$tcnvar=count($expcont)-1;
$i=0;$j=1; $txtinputvar=$_REQUEST['Myvar0'];
foreach($expcont as $k=>$v){
 
  $orgvar.=$v.$txtinputvar;
  if($i==$tcnvar)break;
  else $txtinputvar=$_REQUEST['Myvar'.$j];
  $i++;$j++;
  }
   $extmp=0;
  if($tcnvar!=$j){
  $j=$j-1;
  $extmp=1;
  $removbletmp="Myvar".$j;
$orgvar=str_replace("Myvar".$j,"",$orgvar);
}
 
 
 echo $msg=$orgvar;
		
		
		/*if($_REQUEST['lang']=="te"){
		     sendunicodesms($trmmob,$msg,SMS_URL2,SMS_USERNAME,SMS_PWORD,SENDERID,FEEPAYMENTSMS_TEMPID);
}else{
     sendsms($trmmob,$msg,SMS_ENGURL,SMS_USERNAME,SMS_PWORD,SENDERID,FEEPAYMENTSMS_TEMPID);
}*/
 sendsms($trmmob,$msg,SMS_ENGURL,SMS_USERNAME,SMS_PWORD,SENDERID,$tmp_qry[0]['template_no']); 
//close connection 
echo '^'.count($stdquery);
}
		
elseif($_REQUEST['action']=='give_std_logpermission'){ 
           if($_REQUEST['status']=='active')
		      $updcnd=" is_login_permission=1 ";
			else $updcnd=" is_login_permission=0 ";
			   $upd_logpermis=$obj_db->get_qresult("update ignore ".TABLE_STUDENTDETAILS." set $updcnd where student_id in(".$_REQUEST['std_ids'].")");
			 echo 'success';
			 }
			 
		elseif($_REQUEST['action']=='std_pwordchange'){
		  for($i=0;$i<count($_REQUEST['chkpwd']);$i++){
		
		  $upd_logpermis=$obj_db->get_qresult("update ignore ".TABLE_STUDENTDETAILS." set pword='".$_REQUEST['stdpw_'.$_REQUEST['chkpwd'][$i]]."' where student_id='".$_REQUEST['chkpwd'][$i]."'");
		  $upd_logut=$obj_db->get_qresult("update ignore ".TABLE_APPLOGIN_PARENTS." set is_logout=1 where 	parent_mobile='".$_REQUEST['stmob_'.$_REQUEST['chkpwd'][$i]]."'");
		  }
          
		  
			 echo 'success';
			 }
elseif($_REQUEST['action']=='get_messagetmp'){
 $tmp_qry=$obj_db->qry("SELECT * FROM ".TABLE_SMS_TEMPLATES." where temp_id='".$_REQUEST['temp_id']."' order by temp_id asc");
$orgvar="";
$expcont=explode('{#var#}',$tmp_qry[0]['message']);
$tcnvar=count($expcont)-1;
$nline="\n";
$i=0;$j=1; $txtinputvar='<input   type="text" id="Myvar'.$j.'" name="Myvar0" maxlength="38" style="max-height:35px;">';
foreach($expcont as $k=>$v){
 
  $orgvar.=$v.$txtinputvar;
  if($i==$tcnvar)break;
  else $txtinputvar='<input type="text"  id="Myvar'.$j.'" name="Myvar'.$j.'" maxlength="38" style="max-height:35px;">';
  $i++;$j++;
  }
   $extmp=0;
  if($tcnvar!=$j){
  $j=$j-1;
  $extmp=1;
  $removbletmp="Myvar".$j;
//echo str_replace("Myvar".$i,"",$orgvar);
}
echo $orgvar.'^'.$extmp.'^'.$removbletmp;
}

elseif($_REQUEST['action']=='give_std_admissionno_permission'){  
	if($_REQUEST['status']=='active')
	   $updcnd=" permission_for_admissionno=1,admissionno_permission_date='".date('d-m-Y H:i:s')."',admission_permission_givenby='".$_SESSION['user_id']."' ";
	 else $updcnd=" permission_for_admissionno=0 ";
		$upd_logpermis=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_STUDENTDETAILS." set $updcnd where student_id in(".$_REQUEST['std_ids'].")");
	  echo 'success';
	  }

	  elseif($_REQUEST['action']=='give_std_certificatepermission'){ 
		if($_REQUEST['status']=='active')
		   $updcnd=" certificate_issue_permission=1,certificate_issue_permissiondate='".date('d-m-Y H:i:s')."',certificate_issue_permission_givenby='".$_SESSION['user_id']."' ";
		 else $updcnd=" certificate_issue_permission=0 ";
			$upd_logpermis=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_STUDENTDETAILS." set $updcnd where student_id in(".$_REQUEST['std_ids'].")");
		  echo 'success';
		  }
?> 