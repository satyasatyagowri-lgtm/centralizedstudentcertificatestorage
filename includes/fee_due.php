<?php session_start();
      include "DbConfig.php";
     
	 if($_REQUEST['action']=='fee_due_sms'){
             
	  $termname="Term-".$_REQUEST['terms'];

	  $expbrnid=explode("string:",$_REQUEST['branchid']);
	  
	  $mobile=explode('^',$_REQUEST['mobiles']);
	  $st_name=explode('^',$_REQUEST['st_name']);
	  $st_amt=explode('^',$_REQUEST['st_amt']);
	  $st_ptype=explode('^',$_REQUEST['pay_type']);
	  
    $mob_count=count($mobile);
 		 $branch_query=$obj_db->fetchRow("SELECT branch_name,branch_short_name,sms_title FROM ".TABLE_BRANCH." where branch_id='".$expbrnid[1]."'");
		 $brnname=$branch_query['sms_title'];
	  $brnamelen=strlen($brnname);
			  if($brnamelen>30)
			  $brnname=substr($brnname,0,26).'...';
			  else  $brnname=$brnname;
		   
    $smsuser="SELECT user_name,pword,status,url,sender_id FROM ".TABLE_BRANCH_SMSUSERS." where branch_id='".$expbrnid[1]."'";
  $smsuser_select_row= $obj_db->fetchRow($smsuser);
	   $SMS_USERNAME=$smsuser_select_row['user_name'];
          $SMS_PWORD=$smsuser_select_row['pword'];
		  
           
  for($p=0;$p<$mob_count;$p++){
   if($st_name[$p]=='test')
   { $stnme='';}
   else{$stnme=$st_name[$p];}

   $stnamelen=strlen($stnme);
			  if($stnamelen>30)
			  $stdname=substr($stnme,0,36).'...';
			  else  $stdname=$stnme;

   $trmmob=trim($mobile[$p]);
    
	 $termname="TERM-".$_REQUEST['terms'];

	// $msg="Dear parent , kindly clear the fee dues of your child ".$stnme." Fees Rs. ".$st_amt[$p]."  on or before ".$_REQUEST['due_date'].".If already paid,Please ignore -".$brnname;
        $msg="DEAR PARENT YOUR WARD ".$stdname." FEE BALANCE UPTO ".$termname." IS RS. ".$st_amt[$p]." / PLEASE PAY ON OR BEFORE ".$_REQUEST['due_date']." PRINCIPAL -".$brnname." 
HFT";
   
  send_loopsms($trmmob,$msg,SMS_ENGURL,SMS_USERNAME,SMS_PWORD,SENDERID,FEEDUESMS_TEMPID2);										
				} 
		echo 'success';
				}
				
				 elseif($_REQUEST['action']=='send_uploaded_exam_marks'){
     $get_exammrsks=$obj_db->qry("select * from ".TABLE_EXAM_SMSDATA." order by id asc");
	 foreach($get_exammrsks as $key=>$value){
	    send_loopsms($value['std_mobile'],$value['msg'],$value['sms_url'],$value['sms_user_name'],$value['sms_pword'],$value['sender_id'],$value['temp_id']);	
	     $del_msgdata=$obj_db->get_qresult(DELETE_KEYWORD." ".TABLE_EXAM_SMSDATA." where id='".$value['id']."'");
	 }
	 echo 'success';
   } 
				
	elseif($_REQUEST['action']=='fee_duesms_all'){
	
	$branch_query=$obj_db->fetchRow("SELECT branch_nam,sms_titlee,branch_short_name FROM ".TABLE_BRANCH." where branch_id=".$_REQUEST['branch_id']."");
	
	$smsuser="SELECT user_name,pword,status,url,sender_id FROM ".TABLE_BRANCH_SMSUSERS." where branch_id='".$_REQUEST['branch_id']."'";
  $smsuser_select_row= $obj_db->fetchRow($smsuser);
	   $SMS_USERNAME=$smsuser_select_row['user_name'];
          $SMS_PWORD=$smsuser_select_row['pword'];
	
   for($h=1;$h<=$_REQUEST['terms'];$h++){
					  $tterms.=$h.',';
					  }
					  $trimterms=substr($tterms,0,-1);
 	  if($_REQUEST['course']!='' && $_REQUEST['sms_sections']!='')
	   $cnd=" and c.course_id='".$_REQUEST['course']."' and sec_id in(".$_REQUEST['sms_sections'].") ";
	  elseif($_REQUEST['course']!='')
		$cnd=" and c.course_id='".$_REQUEST['course']."'";
	  else $cnd="";		
	  
	   $std_due_details="select a.student_id,first_name,last_name,roll_no,sum(term_due) as term_due,fee_type,gender,mobile_no from ".TABLE_STUDENT_FEE." a,".TABLE_STUDENTDETAILS." b,".TABLE_STUDENT_EDU_DETAILS." c,".TABLE_FEE_TYPE." d where  d.generate_receipt not in('EF','IEF','SEF') and term in(".$trimterms.") $cnd and branch_id='".$_REQUEST['branch_id']."' and c.is_delete=0 and a.fee_type=d.fee_id and a.course_id=c.course_id and course_model=1 and a.student_id=c.student_id and a.y_id=c.y_id and a.student_id=b.student_id and a.y_id='".$_SESSION['year_id']."' and mobile_no!='' group by a.student_id having sum(term_due)>0 order by roll_no limit ".$_REQUEST['start'].",200";			
	   $get_nums=$obj_db->fetchNum($std_due_details);	
		$std_due_details_res=$obj_db->get_qresult($std_due_details);
					if($_REQUEST['lang']=='te')
			$lang=1;
			else $lang=0;
					 while($std_due_details_rows=$obj_db->fetchArray($std_due_details_res)){
					$trmmob=trim($std_due_details_rows['mobile_no']);
					 $msg="Dear parent of,".$std_due_details_rows['first_name'].' '.$std_due_details_rows['last_name']." Please pay your ward's upto Term-".$_REQUEST['terms']." fee due of Rs. ".$std_due_details_rows['term_due']." before ".$_REQUEST['due_date']." if already paid ignore -".$branch_query['branch_short_name']."";
				
				
				$hostname2 = "localhost";
$username2 = "highfija_cronsms";
$password2 = "a!@#$%^A";
$database2 = "highfija_send_cron_sms";
$connect2 = mysql_connect($hostname2,$username2,$password2);
$select_db2 = mysql_select_db($database2);
			  $sms_insert="insert into send_cron_sms SET 
			                                mobile_no='".$trmmob."', 
											message='".$msg."',
											sms_url='".SMS_URL."',
											user_name='".SMS_USERNAME."',
											pass_word='".SMS_PWORD."',
											sms_gate=1,
											lang_status='".$lang."'";
				mysql_query($sms_insert);
		 sendsms($trmmob,$msg,SMS_ENGURL,$SMS_USERNAME,$SMS_PWORD,$smsuser_select_row['sender_id']);	
			
		  } 
 		echo $get_nums;
   }
   
   elseif($_REQUEST['action']=='send_std_mrksms'){
   
      $trmstids=substr($_REQUEST['stids'],0,-1);
	  
	   $smsbrn_dts=$obj_db->fetchRow("select * from ".TABLE_BRANCH_SMSUSERS1." where branch_id='".$_REQUEST['branch_id']."' ");
	  
	  $smstempdts=$obj_db->fetchRow("select * from ".TABLE_EXAM_SMS_TEMPLATES." where id='".$_REQUEST['sms_tempid']."'");
			 $exp_smstmpsubs=explode(',',$smstempdts['sub_ids']);
			  sort($exp_smstmpsubs,SORT_NUMERIC);
 			$exp_subvrcontent=explode(',',$smstempdts['var_dts']);
			 $sms_modifytmp=$smstempdts['temp_modifycontent'];
      
      $sec_ids=$obj_db->fetchRow("select group_concat(a.sec_id) as sec_ids from ".TABLE_SECTION." a,".TABLE_EXAM_SEC_MAP." b where course_id='".$_REQUEST['course_id']."' and a.sec_id='".$_REQUEST['sec_id']."' and branch_id='".$_REQUEST['branch_id']."' and a.sec_id=b.sec_id");
	  
	   $get_submrks=$obj_db->qry("select group_concat(sub_marks order by a.sub_order) as submarks,group_concat(b.sub_id order by a.sub_order) as subids from ".TABLE_EXAM_SUB_MAP." a,".TABLE_SUB_NAME." b where a.sub_id=b.sub_id and exam_id='".$_REQUEST['exam_id']."' and a.sec_id in(0".$sec_ids['sec_ids'].")  group by a.exam_id,a.sec_id order by a.sub_order asc");
	   
	   $sortexp_subid=$get_submrks['subids'];
	  sort($sortexp_subid,SORT_NUMERIC);
			//   print_r($exp_smstmpsubs);
			  $diff_arsubids=array_diff($exp_smstmpsubs,$sortexp_subid);
			  
			  if(count($diff_arsubids)>0)
$validtemp=0;
else $validtemp=1;
      
      if($_REQUEST['course_id']!='')
       $secids=$sec_ids['sec_ids'];
      else  $secids=$_REQUEST['sec_id'];
      
   if($validtemp==1)   {
   
   $branch_query=$obj_db->fetchRow("SELECT branch_name,sms_title,branch_short_name FROM ".TABLE_BRANCH." where branch_id=".$_REQUEST['branch_id']."");
// $exam_totmarks=("select sum(sub_marks) as tot from ".TABLE_EXAM_SUB_MAP." a,".." b where exam_id='".$_REQUEST['exam_id']."' and sec_id='".$_REQUEST['sec_id']."'");
   
   	$exam_sub=$obj_db->fetchRow("select sum(sub_marks) as tot from exam_sub_map a,subject_names b where a.sub_id=b.sub_id and exam_id='".$_REQUEST['exam_id']."' and a.sec_id in(".$secids.") ");
  			 $std_nameqry=$obj_db->qry("select first_name,last_name,mobile_no,a.student_id from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b where is_delete=0 and y_id='".$_SESSION['year_id']."'   and a.student_id=b.student_id $coursecnd AND a.student_id in(".$trmstids.") limit ".$_REQUEST['limit'].",20");
			 $stids='';
 			 foreach($std_nameqry as $k=>$v)
			  $stids.=$v['student_id'].',';
			  $trmstidss=substr($stids,0,-1);
			  if(count($std_nameqry)>0)
			  $std_exam_marks_rows=$obj_db->qry("select  * from ".TABLE_EXAM_RESULT." where  exam_id='".$_REQUEST['exam_id']."' and student_id in(".$trmstidss.")  and year_id='".$_SESSION['year_id']."'");
			 $exam_name=$obj_db->fetchRow("select exam_name,dt_of_exam from ".TABLE_EXAM_NAMES." where exam_id='".$_REQUEST['exam_id']."'");
			 
			 
			 $brnname=trim($branch_query['sms_title']);
		$brnamelen=strlen($brnname);
				if($brnamelen>40)
				$brnname=substr($brnname,0,36).'...';
				else  $brnname=$brnname;
			
			
			$exmname=trim($exam_name['exam_name']);
		$exmnamelen=strlen($exmname);
				if($exmnamelen>40)
				$exmname=substr($exmname,0,36).'...';
				else  $exmname=$exmname;
			 
						 $j=0;
						 $lang=0;
						 $nline="\n";
                           foreach($std_nameqry as $k=>$v){
						   $stname=$v['first_name'].' '.$v['last_name'];
		$namelen=strlen($stname);
				if($namelen>40)
				$stname=substr($stname,0,36).'...';
				else  $stname=$stname;
						  $sms_modifytmps=$sms_modifytmp;
						  $stdmrksky=array_search($v['student_id'],array_column($std_exam_marks_rows,'student_id'));
                    
					       $sms_modifytmps=str_replace('stdname',$stname,$sms_modifytmps);
	$sms_modifytmps=str_replace('branchname',$brnname,$sms_modifytmps);
	$sms_modifytmps=str_replace('exmname',$exmname,$sms_modifytmps);
                          	   
                          	   if($v['student_id']>0 && strlen($v['mobile_no'])==10){
				   $subname_qryrow=$obj_db->fetchRow("select group_concat(short_name) as shortname from ".TABLE_SUB_NAME." where sub_id in(".$std_exam_marks_rows[0]['sub_id'].")");
				   
				  
						   $exp_sub=explode(',',$std_exam_marks_rows[$stdmrksky]['sub_id']);
						   $exp_mrks=explode(',',$std_exam_marks_rows[$stdmrksky]['marks']);
						   $exp_grd=explode(',',$std_exam_marks_rows[$stdmrksky]['grades']);
						   $exp_gps=explode(',',$std_exam_marks_rows[$stdmrksky]['gpoints']);
						   $exp_mxmarks=explode(',',$std_exam_marks_rows[$stdmrksky]['max_marks']);
						   
						  $examtot= round($std_exam_marks_rows[$stdmrksky]['total_marks']).'/'.$exam_totmarks['tot'];
						   $mrks='';
						   for($i=0;$i<count($exp_sub);$i++){
						    $stmrkstot=$stmrkstot+$mrks;
			  $stsmsmrks=$exp_mrks[$i].'/'.$exp_mxmarks[$i];
			$sms_modifytmps=str_replace('sub_'.$exp_sub[$i],$stsmsmrks,$sms_modifytmps);
						   
						   
						   }
						   
						   
						    $stdtotmrks=round(array_sum($exp_mrks)).'/'.array_sum($exp_mxmarks);
                             $sms_modifytmps=str_replace('exms_tot',$stdtotmrks,$sms_modifytmps);
                             $sms_modifytmps=str_replace('exms_rnk',$std_exam_marks_rows[$stdmrksky]['section_rank'],$sms_modifytmps);
						       $msg=$sms_modifytmps;
 if($validtemp>0)
			 $insr_examdata=$obj_db->get_qresult(INSERT_KEYWORD." ".TABLE_EXAM_SMSDATA." SET 
			                                sms_user_name='".$smsbrn_dts['user_name']."',
											std_mobile='".$v['mobile_no']."',
											sms_pword='".$obj_db->real_escape_string($smsbrn_dts['pword'])."',
											sender_id='".$obj_db->real_escape_string($smsbrn_dts['sender_id'])."',
											temp_id='".$obj_db->real_escape_string($smstempdts['template_id'])."',
											sms_url='".$obj_db->real_escape_string(SMS_ENGURL)."',
											msg='".$obj_db->real_escape_string($msg)."'"); 						   
						   
						   			
					  }	
						   
						  }
						  }
			echo '^'.count($std_nameqry).'^'.$validtemp;
   
   }
   
   elseif($_REQUEST['action']=='send_std_compmrksms'){
      $trmstids=substr($_REQUEST['stids'],0,-1);
	  
	  $smsbrn_dts=$obj_db->fetchRow("select * from ".TABLE_BRANCH_SMSUSERS1." where branch_id='".$_REQUEST['branch_id']."' ");
	  
	  $smstempdts=$obj_db->fetchRow("select * from ".TABLE_EXAM_SMS_TEMPLATES." where id='".$_REQUEST['sms_tempid']."'");
			 $exp_smstmpsubs=explode(',',$smstempdts['sub_ids']);
			  sort($exp_smstmpsubs,SORT_NUMERIC);
 			$exp_subvrcontent=explode(',',$smstempdts['var_dts']);
			$sms_modifytmp=$smstempdts['temp_modifycontent'];
      
      $sec_ids=$obj_db->fetchRow("select group_concat(a.sec_id) as sec_ids from ".TABLE_SECTION." a,".TABLE_EXAM_SEC_MAP." b where course_id='".$_REQUEST['course_id']."' and a.sec_id='".$_REQUEST['sec_id']."' and branch_id='".$_REQUEST['branch_id']."' and a.sec_id=b.sec_id");
	  
	   $get_submrks=$obj_db->qry("select group_concat(sub_marks order by a.sub_order) as submarks,group_concat(b.sub_id order by a.sub_order) as subids from ".TABLE_EXAM_SUB_MAP." a,".TABLE_SUB_NAME." b where a.sub_id=b.sub_id and exam_id='".$_REQUEST['exam_id']."' and a.sec_id in(0".$sec_ids['sec_ids'].")  group by a.exam_id,a.sec_id order by a.sub_order asc");
	   
	   $sortexp_subid=$get_submrks['subids'];
	  sort($sortexp_subid,SORT_NUMERIC);
			//   print_r($exp_smstmpsubs);
			  $diff_arsubids=array_diff($exp_smstmpsubs,$sortexp_subid);
			  
			  if(count($diff_arsubids)>0)
$validtemp=0;
else $validtemp=1;
      
      if($_REQUEST['course_id']!='')
       $secids=$sec_ids['sec_ids'];
      else  $secids=$_REQUEST['sec_id'];
      
   if($validtemp==1)   {
   
   $branch_query=$obj_db->fetchRow("SELECT branch_name,sms_title,branch_short_name FROM ".TABLE_BRANCH." where branch_id=".$_REQUEST['branch_id']."");
// $exam_totmarks=("select sum(sub_marks) as tot from ".TABLE_EXAM_SUB_MAP." a,".." b where exam_id='".$_REQUEST['exam_id']."' and sec_id='".$_REQUEST['sec_id']."'");
   
   	$exam_sub=$obj_db->fetchRow("select sum(sub_marks) as tot from exam_sub_map a,subject_names b where a.sub_id=b.sub_id and exam_id='".$_REQUEST['exam_id']."' and a.sec_id in(".$secids.") ");
  			 $std_nameqry=$obj_db->qry("select first_name,last_name,mobile_no,a.student_id from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b where is_delete=0 and y_id='".$_SESSION['year_id']."'   and a.student_id=b.student_id $coursecnd AND a.student_id in(".$trmstids.") limit ".$_REQUEST['limit'].",20");
			 $stids='';
 			 foreach($std_nameqry as $k=>$v)
			  $stids.=$v['student_id'].',';
			  $trmstidss=substr($stids,0,-1);
			  if(count($std_nameqry)>0)
			  $std_exam_marks_rows=$obj_db->qry("select  * from ".TABLE_COMMPETETIVE_EXAM_MARKS." where  exam_id='".$_REQUEST['exam_id']."' and student_id in(".$trmstidss.")  and year_id='".$_SESSION['year_id']."'");
 			
			 $exam_name=$obj_db->fetchRow("select exam_name,dt_of_exam from ".TABLE_EXAM_NAMES." where exam_id='".$_REQUEST['exam_id']."'");
			 
			 
			 $brnname=trim($branch_query['sms_title']);
		$brnamelen=strlen($brnname);
				if($brnamelen>40)
				$brnname=substr($brnname,0,36).'...';
				else  $brnname=$brnname;
			
			
			$exmname=trim($exam_name['exam_name']);
		$exmnamelen=strlen($exmname);
				if($exmnamelen>40)
				$exmname=substr($exmname,0,36).'...';
				else  $exmname=$exmname;
			 
						 $j=0;
						 $lang=0;
						 $nline="\n";
                           foreach($std_nameqry as $k=>$v){
						   $stname=$v['first_name'].' '.$v['last_name'];
		$namelen=strlen($stname);
				if($namelen>40)
				$stname=substr($stname,0,36).'...';
				else  $stname=$stname;
						  $sms_modifytmps=$sms_modifytmp;
						  $stdmrksky=array_search($v['student_id'],array_column($std_exam_marks_rows,'student_id'));
                    
					       $sms_modifytmps=str_replace('stdname',$stname,$sms_modifytmps);
	$sms_modifytmps=str_replace('branchname',$brnname,$sms_modifytmps);
	$sms_modifytmps=str_replace('exmname',$exmname,$sms_modifytmps);
                          	   
                          	   if($v['student_id']>0 && strlen($v['mobile_no'])==10){
				   $subname_qryrow=$obj_db->fetchRow("select group_concat(short_name) as shortname from ".TABLE_SUB_NAME." where sub_id in(".$std_exam_marks_rows[0]['sub_id'].")");
				   
				  
						  
						   $exp_sub=explode(',',$std_exam_marks_rows[$stdmrksky]['sub_id']);
						   $exp_mrks=explode(',',$std_exam_marks_rows[$stdmrksky]['marks']);
						   $exp_grd=explode(',',$std_exam_marks_rows[$stdmrksky]['grades']);
						   $exp_gps=explode(',',$std_exam_marks_rows[$stdmrksky]['gpoints']);
						   $exp_mxmarks=explode(',',$std_exam_marks_rows[$stdmrksky]['max_marks']);
						   
						  $examtot= round($std_exam_marks_rows[$stdmrksky]['total_marks']).'/'.$exam_totmarks['tot'];
						   $mrks='';
						   for($i=0;$i<count($exp_sub);$i++){
						    $stmrkstot=$stmrkstot+$mrks;
			 $stsmsmrks=$exp_mrks[$i].'/'.$exp_mxmarks[$i];
			$sms_modifytmps=str_replace('sub_'.$exp_sub[$i],$stsmsmrks,$sms_modifytmps);
						   
						   
						   }
						   
						   
						    $stdtotmrks=round(array_sum($exp_mrks)).'/'.array_sum($exp_mxmarks);
                             $sms_modifytmps=str_replace('exms_tot',$stdtotmrks,$sms_modifytmps);
                             $sms_modifytmps=str_replace('exms_rnk',$std_exam_marks_rows[$stdmrksky]['section_rank'],$sms_modifytmps);
						       $msg=$sms_modifytmps;
 if($validtemp>0)
			 $insr_examdata=$obj_db->get_qresult(INSERT_KEYWORD." ".TABLE_EXAM_SMSDATA." SET 
			                                sms_user_name='".$smsbrn_dts['user_name']."',
											std_mobile='".$v['mobile_no']."',
											sms_pword='".$obj_db->real_escape_string($smsbrn_dts['pword'])."',
											sender_id='".$obj_db->real_escape_string($smsbrn_dts['sender_id'])."',
											temp_id='".$obj_db->real_escape_string($smstempdts['template_id'])."',
											sms_url='".$obj_db->real_escape_string(SMS_ENGURL)."',
											msg='".$obj_db->real_escape_string($msg)."'"); 						   
						   
						   			
					  }	
						   
						  }
						  }
			echo '^'.count($std_nameqry).'^'.$validtemp;
   }
   
   elseif($_REQUEST['action']=='get_routes'){
	 $bus_details=$obj_db->get_qresult("SELECT * FROM ".TABLE_BUS_ROUTES." where branch_id='".$_REQUEST['branch_id']."'");
	 $tmp.='<option value="">--Select--</option>';
	 while($bus_detailsrw=$obj_db->fetchArray($bus_details)){
	  $tmp.='<option value="'.$bus_detailsrw['route_id'].'">'.$bus_detailsrw['route_name'].'</option>';
	 }
	 echo $tmp;
	}
 elseif($_REQUEST['action']=='get_monthsal_status'){
	$exp_month=explode('^',$_REQUEST['month_id']);
	$chk_saldts=$obj_db->fetchNum("SELECT * FROM ".TABLE_STAFFSAL_PAID." where branch_id='".$_REQUEST['branch_id']."' and year_id='".$_SESSION['year_id']."' and month_id='".$exp_month[1]."'");
	
	$month_name=$obj_db->fetchRow("select month_name from ".TABLE_MONTHS." where month_id='".$exp_month[1]."'");
	echo $chk_saldts.'^'.$month_name['month_name'];
	}
	
	elseif($_REQUEST['action']=="get_exptypes"){
    $exam_typeqry=$obj_db->get_qresult("SELECT * FROM ".TABLE_EXPENDITURE_TYPE."  where exp_type_id not in(select exp_type_id from ".TABLE_EXP_CATEGORYS_MAP." where exp_catg_id='".$_REQUEST['exp_catid']."') and exp_catg_id='".$_REQUEST['exp_catid']."' order by exp_type_id asc");

			while($exam_typeqrw=$obj_db->fetchArray($exam_typeqry)) { 
			   $tmp.='<div class="col-lg-3">
 			 <div class="input-group m-bot15">
			           <div class="checkbox">
						  <label>
                       <input type="checkbox" class="eachexp ace" name="exp_type_id[]" value="'.$exam_typeqrw['exp_type_id'].'"  />
                           <span class="lbl bigger-120"> </span>'.$exam_typeqrw['exp_name'].
						   '</label>				    
						   </div>
                           </div> </div>';
					 }
			 echo $tmp;
}
elseif($_REQUEST['action']=='get_branch_courses'){
   	
			$section_res=$obj_db->get_qresult("select course_name,b.course_id from  ".TABLE_COURSE_BRANCH_MAP." a,".TABLE_COURSE." b where  branch_id in(".$_REQUEST['branch_id'].") and a.course_id=b.course_id order by b.course_id asc");
			$tmp='<option value="All">All</option>';
			while($select_name_rows=$obj_db->fetchArray($section_res)) { 
			$tmp.='<option value='.$select_name_rows['course_id'].'>'.$select_name_rows['course_name'].'</option>';
			}
			
         
			  $sec_select_terms=$obj_db->fetchRow("SELECT max(terms) as trm FROM  ".TABLE_FEE_COURSE_MAP."  where branch_id='".$_REQUEST['branch_id']."'");
			
			$ctmp='<option value="">--Select--</option>';
			for($p=1;$p<=$sec_select_terms['trm'];$p++){
			$ctmp.='<option value='.$p.'>Term-'.$p.'</option>';
			}
			
			
			 $get_fees=$obj_db->get_qresult("SELECT a.fee_id,a.fee_name FROM ".TABLE_FEE_TYPE." a,".TABLE_FEE_COURSE_MAP." b where is_tution=1 and a.fee_id=b.fee_id and b.branch_id='".$_REQUEST['branch_id']."' group by b.fee_id asc ORDER BY fee_id asc");
			
			$fetmp='<option value="">--Select--</option>';
			while($get_feerw=$obj_db->fetchArray($get_fees)){
							$fetmp.='<option value="'.$get_feerw['fee_id'].'">'.$get_feerw['fee_name'].'</option>';
						 }

			echo $tmp.'^'.$ctmp.'^'.$fetmp;
   }
   
   elseif($_REQUEST['action']=="get_in_out"){
   $get_inoutqry=$obj_db->get_qresult("select in_out from ".TABLE_SCHOOL_TIMINGS." where branch_id='".$_REQUEST['branch_id']."' and is_staff_time='".$_REQUEST['is_staff_time']."' and session='".$_REQUEST['session']."'");
			while($get_inoutqrw=$obj_db->fetchArray($get_inoutqry)) { 
					$in_out.=$get_inoutqrw['in_out'].',';
					 }
				$trm_inout=substr($in_out,0,-1);
				$exp_inout=explode(',',$trm_inout);
		$tmp.='<option value="">--Select--</option>';
		for($i=1;$i<=3;$i++){
		 if($i==1)
		  $inoutname="In";
		 elseif($i==2) $inoutname="Out";
		 else $inoutname="Intime Start";
		 if(!in_array($i,$exp_inout))
		 $tmp.='<option value="'.$i.'">'.$inoutname.'</option>';
		}
			 echo $tmp;
  }
  
   elseif($_REQUEST['action']=="get_working_months"){
   $get_monthqry=$obj_db->get_qresult("select month_id,month_name from ".TABLE_MONTHS." where month_id not in(select month_id from ".TABLE_MONTHSCHOOL_WORKDYS." where year_id='".$_SESSION['year_id']."' and branch_id='".$_REQUEST['branch_id']."') order by month_id asc");
           $tmp.='<option value="">--Select--</option>';
			while($get_monthqrw=$obj_db->fetchArray($get_monthqry)) { 
					$tmp.="<option value='".$get_monthqrw['month_id']."'>".$get_monthqrw['month_name']."</option>";
					 }
			
			 echo $tmp;
  }
   elseif($_REQUEST['action']=='get_monthsal_status'){
	$exp_month=explode('^',$_REQUEST['month_id']);
	$chk_saldts=$obj_db->fetchNum("SELECT * FROM ".TABLE_STAFFSAL_PAID." where year_id='".$_SESSION['year_id']."' and month_id='".$exp_month[1]."'");
	
	$month_name=$obj_db->fetchRow("select month_name from ".TABLE_MONTHS." where month_id='".$exp_month[1]."'");
	echo $chk_saldts.'^'.$month_name['month_name'];
	}
	
elseif($_REQUEST['action']=='get_devicestaff_workdaysgen'){
	$exp_month=explode('^',$_REQUEST['month_id']);
	$chk_saldts=$obj_db->fetchNum("SELECT * FROM ".TABLE_STAFF_DEVICEWORKDAYS." where year_id='".$_SESSION['year_id']."' and month_id='".$exp_month[1]."' and branch_id='".$_REQUEST['branch_id']."'");
	
	$month_name=$obj_db->fetchRow("select month_name from ".TABLE_MONTHS." where month_id='".$exp_month[1]."'");
	echo $chk_saldts.'^'.$month_name['month_name'];
	}
	
	else if($_REQUEST['action']=='get_month_absents'){
	  
		 $todaydetails = $obj_db->get_qresult("select  day(date_attendance) as d from ".TABLE_ABSENTIES." where student_id='".$_SESSION['std_id']."' and  month(date_attendance) ='".$_REQUEST['month']."'");	
		 while($todaydetails_rows=$obj_db->fetchArray($todaydetails)) { $attend.=$todaydetails_rows['d'].',';}
		 $trm_attend=substr($attend,0,-1);
		 echo $trm_attend;
	}
	
 elseif($_REQUEST['online_shedules']=='online_shedules'){
    if($_REQUEST['sub_action']=='edit'){
	$course_ids='';
	 for($i=0;$i<count($_REQUEST['courses']);$i++){
				  $course_ids.=$_REQUEST['courses'][$i].',';
				   }	
				   $trm_course=substr($course_ids,0,-1); 
				   
				    if($trm_course!=''){
                          $exp_tpoic=explode('^',$_REQUEST['topic_1']);
			      $shedule_qry="update ignore   ".TABLE_ONLINE_EXAM_SHEDULE_TEST." SET 
			                                sub_name='".$obj_db->real_escape_string($_REQUEST['sbname_1'])."', 
											topic_id='".$obj_db->real_escape_string($exp_tpoic[0])."',
											topic_name='".$obj_db->real_escape_string($exp_tpoic[1])."',
                                            sub_topic='".$obj_db->real_escape_string($_REQUEST['subtopic_1'])."',
											course_ids='".$trm_course."',
											shedule_date='".$obj_db->real_escape_string(date('Y-m-d',strtotime($_REQUEST['shedule_date'])))."' where id='".$_REQUEST['edit_id']."'"; 
			   $ress=$obj_db->get_qresult($shedule_qry); 
			  }
	
	}else{
			  for($m=1;$m<$_REQUEST['tot_subjs'];$m++)
			  {
			 $course_ids='';
			  for($i=0;$i<count($_REQUEST['courses']);$i++){
			   $sub_id=$obj_db->fetchRow("select group_concat(sub_id) as sub_id from  ".TABLE_SUB_TO_COURSE."   where course_id='".$_REQUEST['courses'][$i]."'");
			   $exp_courseids=explode(',',$sub_id['sub_id']);
		         if(in_array($_REQUEST['subid_'.$m],$exp_courseids) && $_REQUEST['shedule_type']==2)
				   $course_ids.=$_REQUEST['courses'][$i].',';
				 else $course_ids.=$_REQUEST['courses'][$i].',';
				   }	
				   $trm_course=substr($course_ids,0,-1); 
				   			  
			   if($_REQUEST['subid_'.$m]!=''){
                          $exp_tpoic=explode('^',$_REQUEST['topic_'.$m]);
			      $shedule_qry="INSERT ignore INTO  ".TABLE_ONLINE_EXAM_SHEDULE_TEST." SET 
			                                sub_name='".$obj_db->real_escape_string($_REQUEST['sbname_'.$m])."', 
											sub_id='".$obj_db->real_escape_string($_REQUEST['subid_'.$m])."',
											user_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
                                                                                       topic_id='".$obj_db->real_escape_string($exp_tpoic[0])."',
											topic_name='".$obj_db->real_escape_string($exp_tpoic[1])."',
                                                                                        sub_topic='".$obj_db->real_escape_string($_REQUEST['subtopic_'.$m])."',
											test_name='".$obj_db->real_escape_string($_REQUEST['test_name'])."',
											course_id='".$obj_db->real_escape_string($_REQUEST['course_id'])."',
											course_ids='".$trm_course."',
                                                                                        branch_id='".$obj_db->real_escape_string($_REQUEST['branch_id'])."',
                                                                                        year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											enter_date='".date('Y-m-d H:i:s')."',
											shedule_date='".$obj_db->real_escape_string(date('Y-m-d',strtotime($_REQUEST['shedule_date'])))."', 
											shedule_type='".$obj_db->real_escape_string($_REQUEST['shedule_type'])."'"; 
			   $ress=$obj_db->get_qresult($shedule_qry); 
			   $id=$obj_db->insert_id();
			   $sourcePath = $_FILES["subfile_".$m]['tmp_name'];
			  $name = $_FILES["subfile_".$m]["name"];
				 $ext = end((explode(".", $name)));
				$image_name=$id.'.'.$ext;
				  $targetPath = "pdfs/".$image_name;	
				//image_ajaxupload($_FILES,$m,$targetPath);
				 move_uploaded_file($sourcePath,$targetPath);
			  }
			  
	        }
			}
			//echo 'success';
  }
  
  elseif($_REQUEST['homework_sheets']=='homework_sheets'){
    if($_REQUEST['sub_action']=='edit'){
	$course_ids='';
	 for($i=0;$i<count($_REQUEST['courses']);$i++){
				  $course_ids.=$_REQUEST['courses'][$i].',';
				   }	
				   $trm_course=substr($course_ids,0,-1); 
				   
				    if($trm_course!=''){
                          $exp_tpoic=explode('^',$_REQUEST['topic_1']);
			      $shedule_qry="update ignore   ".TABLE_HOMEWORK_WORKSHEETS." SET 
			                                sub_name='".$obj_db->real_escape_string($_REQUEST['sbname_1'])."', 
											topic_id='".$obj_db->real_escape_string($exp_tpoic[0])."',
											topic_name='".$obj_db->real_escape_string($exp_tpoic[1])."',
                                            sub_topic='".$obj_db->real_escape_string($_REQUEST['subtopic_1'])."',
											course_ids='".$trm_course."',
											worksheet_date='".$obj_db->real_escape_string(date('Y-m-d',strtotime($_REQUEST['worksheet_date'])))."' where id='".$_REQUEST['edit_id']."'"; 
			   $ress=$obj_db->get_qresult($shedule_qry); 
			  }
	
	}else{
			  for($i=0;$i<count($_REQUEST['courses']);$i++){
			   $course_ids='';
			    $sub_id=$obj_db->fetchRow("select group_concat(sub_id) as sub_id from  ".TABLE_SUB_TO_COURSE."   where course_id='".$_REQUEST['courses'][$i]."'");
			   $exp_courseids=explode(',',$sub_id['sub_id']);
		         if(in_array($_REQUEST['subid'.$_REQUEST['courses'][$i]][$m],$exp_courseids))
				   $course_ids.=$_REQUEST['courses'][$i].',';
				 else $course_ids.=$_REQUEST['courses'][$i].',';
				   $trm_course=substr($course_ids,0,-1); 
			   for($m=0;$m<count($_REQUEST['subid'.$_REQUEST['courses'][$i]]);$m++)
			  {
			 
			  
				   
			  
			   if($_REQUEST['subid'.$_REQUEST['courses'][$i]][$m]!='' && $trm_course!=''){
			    $sourcePath = $_FILES["subfile".$_REQUEST['courses'][$i]]['tmp_name'][$m];
			 $name = $_FILES["subfile".$_REQUEST['courses'][$i]]["name"][$m];
				 $ext = end((explode(".", $name)));
			   
			   
                          $exp_tpoic=explode('^',$_REQUEST['topic'.$_REQUEST['courses'][$i]][$m]);
			     $shedule_qry="INSERT ignore INTO  ".TABLE_HOMEWORK_WORKSHEETS." SET 
			                                sub_name='".$obj_db->real_escape_string($_REQUEST['sbname'.$_REQUEST['courses'][$i]][$m])."', 
											sub_id='".$obj_db->real_escape_string($_REQUEST['subid'.$_REQUEST['courses'][$i]][$m])."',
                                                                                     
                                                                                       topic_id='".$obj_db->real_escape_string($exp_tpoic[0])."',
											topic_name='".$obj_db->real_escape_string($exp_tpoic[1])."',
                                                                                        sub_topic='".$obj_db->real_escape_string($_REQUEST['subtopic'.$_REQUEST['courses'][$i]][$m])."',
											course_ids='".$trm_course."',
                                                                                        branch_id='".$obj_db->real_escape_string($_REQUEST['branch_id'])."',
                                                                                        year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
																						 user_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											enter_date='".date('Y-m-d H:i:s')."',
											worksheet_date='".$obj_db->real_escape_string(date('Y-m-d',strtotime($_REQUEST['worksheet_date'])))."'"; 
			   $ress=$obj_db->get_qresult($shedule_qry); 
			   $id=$obj_db->insert_id();
			   $image_name=$id.'.'.$ext;
			   $upd_imgpath=$obj_db->get_qresult("update ignore ".TABLE_HOMEWORK_WORKSHEETS." set image_path='".$image_name."' where id='".$id."'");
			   
			   
			  
				  $targetPath = "homework_sheets/".$image_name;	
				//image_ajaxupload($_FILES,$m,$targetPath);
				 move_uploaded_file($sourcePath,$targetPath);
			  }
			  }
			  
	        }
			}
			echo 'success';
  }
elseif($_REQUEST['stf_chat']=='stf_chat'){
    
			  if($_FILES['audioFile']['name']!=''){
			   $audio_path="";
			   
			   $name = $_FILES["audioFile"]["name"];
				$ext = end((explode(".", $name))); # extra () to prevent notice
				if($ext=='mp3' || $ext=='qt' || $ext=='mov' || $ext=='flv' || $ext=='ogg'){
				 $is_image=0; $is_audiofile=1;
				 }
				else {$is_image=1;
				      $is_audiofile=0;
					  }
			   }
			   else{
			   $is_audiofile=0;
			   $audio_path="";
			   }
			   if($_FILES['audioFile']['name']!=''){
			   $audio_path = "../includes/std_chats/".date('d').date('m').date('Y').'_'.date('H').date('i').date('s').'.'.$ext;
			    move_uploaded_file($_FILES['audioFile']['tmp_name'],$audio_path);
				}
			     $voice_record="INSERT ignore INTO  ".TABLE_STUDENT_CHATING." SET 
			   								course_ids='".$obj_db->real_escape_string($_REQUEST['chstcourse_id'])."',
											frm_user_ids='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											to_user_ids='".$obj_db->real_escape_string($_REQUEST['chstd_id'])."',
											to_user_name='".$obj_db->real_escape_string($_REQUEST['std_name'])."',
											frm_user_name='".$obj_db->real_escape_string($_SESSION['admin_user'])."',
											no_stds='1',
											base_link='".$obj_db->real_escape_string($audio_path)."',
			                                branch_id='".$obj_db->real_escape_string($_REQUEST['branch_id'])."', 
											chat_message='".$obj_db->real_escape_string($_REQUEST['chat_msg'])."',
											is_video_audio='".$is_audiofile."',
											is_image='".$is_image."',
											sendby_staff='1',
											date_time='".$obj_db->real_escape_string(date('Y-m-d h:i:s'))."',
											chat_time='".$obj_db->real_escape_string(date('H:i:s'))."'"; 
			  $res=$obj_db->get_qresult($voice_record);
			  
			   $stdchat_vistclr=$obj_db->get_qresult("INSERT ignore INTO  ".TABLE_STUDENT_CHATVIST_CLEAR." SET 
			   								std_chat_id='".$obj_db->real_escape_string($id)."',
											frm_user_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											to_user_id='".$obj_db->real_escape_string($_REQUEST['chstd_id'])."',
                                            is_msgfor_staff='0',
											course_id='".$obj_db->real_escape_string($_REQUEST['chstcourse_id'])."'"); 
			     
echo 'success';		}

 elseif($_REQUEST['std_chat']=='std_chat'){
    
			  if($_FILES['audioFile']['name']!=''){
			   $audio_path="";
			   
			   $name = $_FILES["audioFile"]["name"];
				$ext = end((explode(".", $name))); # extra () to prevent notice
				if($ext=='mp3' || $ext=='qt' || $ext=='mov' || $ext=='flv' || $ext=='ogg'){
				 $is_image=0; $is_audiofile=1;
				 }
				else {$is_image=1;
				      $is_audiofile=0;
					  }
			   }
			   else{
			   $is_audiofile=0;
			   $audio_path="";
			   }
			   if($_FILES['audioFile']['name']!=''){
			   $audio_path = "../includes/std_chats/".date('d').date('m').date('Y').'_'.date('H').date('i').date('s').'.'.$ext;
			    move_uploaded_file($_FILES['audioFile']['tmp_name'],$audio_path);
				}
			     $voice_record="INSERT ignore INTO  ".TABLE_STUDENT_CHATING." SET 
			   								course_ids='".$obj_db->real_escape_string($_REQUEST['chstcourse_id'])."',
											frm_user_ids='".$obj_db->real_escape_string($_SESSION['std_id'])."',
											to_user_ids='".$obj_db->real_escape_string($_REQUEST['chstf_id'])."',
											to_user_name='".$obj_db->real_escape_string($_REQUEST['stf_name'])."',
											frm_user_name='".$obj_db->real_escape_string($_REQUEST['std_name'])."',
											no_stds='1',
											base_link='".$obj_db->real_escape_string($audio_path)."',
			                                branch_id='".$obj_db->real_escape_string($_REQUEST['branch_id'])."', 
											chat_message='".$obj_db->real_escape_string($_REQUEST['chat_msg'])."',
											is_video_audio='".$is_audiofile."',
											is_image='".$is_image."',
											sendby_staff='1',
											date_time='".$obj_db->real_escape_string(date('Y-m-d h:i:s'))."',
											chat_time='".$obj_db->real_escape_string(date('H:i:s'))."'"; 
			  $res=$obj_db->get_qresult($voice_record);
			  
			   $stdchat_vistclr=$obj_db->get_qresult("INSERT ignore INTO  ".TABLE_STUDENT_CHATVIST_CLEAR." SET 
			   								std_chat_id='".$obj_db->real_escape_string($id)."',
											frm_user_id='".$obj_db->real_escape_string($_SESSION['std_id'])."',
											to_user_id='".$obj_db->real_escape_string($_REQUEST['chstf_id'])."',
                                            is_msgfor_staff='1',
											course_id='".$obj_db->real_escape_string($_REQUEST['chstcourse_id'])."'"); 
			     
echo 'success';		}
?>