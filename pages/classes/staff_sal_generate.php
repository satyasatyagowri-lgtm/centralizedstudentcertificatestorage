<?php 
class salgenerate{
   //echo "test"; exit;
					
function staff_salsave($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
  			   
			   
			   $exp_monthid_range=explode('^',$_REQUEST['date']);
			   
			   $lstmonth_id=$exp_monthid_range[1]-1;
			   if($lstmonth_id==0){
			    $month_row =  $obj_db->fetchRow("select max(month_id) mxmonth from  ".TABLE_MONTHS."");
				 $lstmonth_id=$month_row['mxmonth'];
			   }
			   
			   $del_stf_sal=$obj_db->get_qresult(DELETE_KEYWORD."  ".TABLE_STAFFSAL_PAID." where month_id='".$exp_monthid_range[1]."' and year_id='".$_SESSION['year_id']."'");
			   
			   $year_row =  $obj_db->fetchRow("select * from  ".TABLE_YEAR." where year_id='".$_SESSION['year_id']."'");
				 $year_rg=$year_row['year'];
				 $expyrg=explode('-',$year_rg);
				 if($exp_monthid_range[2]>=6)
				 { $salgen_yr=$expyrg[0];}
				 else{$salgen_yr=$expyrg[1];}
				 
				 $giv_mondays=cal_days_in_month(CAL_GREGORIAN, $exp_monthid_range[2], $salgen_yr);
				 
			   $daterangeexp=explode('to',$_REQUEST['date']);
			   $dateexp1=explode('-',$daterangeexp[0]);
			   $dateexp2=explode('-',$daterangeexp[1]);
			   
	   
			    $date1=date('Y-m-d',strtotime($dateexp1[0].'-'.$dateexp1[1].'-'.$salgen_yr));
			   $date2=$dateexp2[0].'-'.$dateexp2[1].'-'.$salgen_yr;
			   $date2=date('Y-m-d',strtotime(ltrim($date2)));
			   
			   
			   $month = array(1=>6,2=>7,3=>8,4=>9,5=>10,6=>11,7=>12,8=>1,9=>2,10=>3,11=>4,12=>5);
    		  $qgetworkdays = "select count(*)/2 as work from ".TABLE_STAFF_ABSENTS." where  date(date_attendance)>='$date1' and date(date_attendance)<='$date2'";
			   $rsgetworkdays = $obj_db->get_qresult($qgetworkdays);
               $resgetworkdays = $obj_db->fetchArray($rsgetworkdays);
               $workingdays = $resgetworkdays['work'];
			   
			   $staff_totattd_firstday=$obj_db->fetchRow("select min(date(date_attendance)) as totreport  from ".TABLE_STAFF_ABSENTS." where  date(date_attendance)>='$date1' and date(date_attendance)<='$date2' and  branch_id='".$_SESSION['branch_id']."' and year_id='".$_SESSION['year_id']."'");
			   $staff_totattd_firstdate='"'.$staff_totattd_firstday['totreport'].'"';
			   $getsalary="select staff_id,salary,pf_value from ".TABLE_STAFF_DETAILS." where branch_id='".$_SESSION['branch_id']."' and status=1"; 
			   $getsalary_row=$obj_db->get_qresult($getsalary);
			   $sal_insrcount=0;
			   while($getsalary_rows=$obj_db->fetchArray($getsalary_row)){
			     $paid_salary=0;
			    if($getsalary_rows['pf_value']>0){
				  $pfpercent=($getsalary_rows['salary']*$getsalary_rows['pf_value'])/100;
				}
				else{$pfpercent=0;}
			
			 $check_lastmonth_sal=$obj_db->fetchNum("select * from ".TABLE_STAFFSAL_PAID." where staff_id='".$getsalary_rows['staff_id']."' and month_id='".$lstmonth_id."' and year_id='".$_SESSION['year_id']."'");
			 
			 $staff_active_date=$obj_db->fetchRow("select activ_date from ".TABLE_STAFF_DETAILS." where staff_id='".$getsalary_rows['staff_id']."'");
		     $activedate='"'.$staff_active_date['activ_date'].'"';
		
						
		  $getabsents="select COUNT(*)/2 as cnt , absent_status from ".TABLE_STAFF_ATTENDANCE." where staff_id='".$getsalary_rows['staff_id']."' and date(attendance_date)>='$date1' and date(attendance_date)<='$date2' and  branch_id=".$_SESSION['branch_id']." and year_id='".$_SESSION['year_id']."'  and absent_status in('A','L') GROUP BY absent_status";
		   
				 $ggetabsents_res=$obj_db->get_qresult($getabsents);
				 $absents_count=0;
				 $leave_count=0;
				 $abst_count=0;$leav_count=0;
				 
			    while($ggetabsents_rows=$obj_db->fetchArray($ggetabsents_res)){
				
				if($ggetabsents_rows['absent_status']=='A'){
				$absents_count=$ggetabsents_rows['cnt'];
				$abst_count=$absents_count;
				}else{
				$leave_count=$ggetabsents_rows['cnt'];
				$leav_count=$leave_count;
				}
				}
				
				$oneday_salary=($getsalary_rows['salary']/$giv_mondays);
				
				$loss_absents_cost=0;
				$loss_leaves_cost=0;
		
		 if($check_lastmonth_sal)
		 {
		  $staff_notjoin_amt=0;
		 }
		 else{
		    $seldate_diff=$obj_db->fetchRow("select datediff('".$date2."','".$staff_active_date['activ_date']."') as totworkdays ");
			$staff_notjoin_days=$giv_mondays-($seldate_diff['totworkdays']+1);
			$staff_notjoin_amt=$staff_notjoin_days*$oneday_salary;
			
		 }
		 
		 if($absents_count>0 && $staff_notjoin_amt==0){
				$abst_count=$absents_count-1;
				if($abst_count<0){
				 $abst_count=0;
				}
				}elseif($leave_count>0 && $staff_notjoin_amt==0){
				 $leav_count=$leave_count-1;
				 if($leav_count<0){
				 $leav_count=0;
				}
				}
				
		 		$absent_cost_days=$obj_db->get_qresult("select * from ".TABLE_STAFF_LCS."  where  branch_id='".$_SESSION['branch_id']."' and year_id='".$_SESSION['year_id']."'");
				while($absent_cost_days_rows=$obj_db->fetchArray($absent_cost_days)){
				if($absents_count>0 && $absent_cost_days_rows['attendance_status']=='A')
				{
				  $loss_absents_cost=($oneday_salary*($absent_cost_days_rows['cost_days']*$abst_count));
				}
				if($leave_count>0 && $absent_cost_days_rows['attendance_status']=='L'){
				  $loss_leaves_cost=($oneday_salary*($absent_cost_days_rows['cost_days']*$leav_count));
				}
				}
				
				if($staff_notjoin_amt==0 && $tot_abs_lev==0)
				{$extr_cl=$oneday_salary;}
				else{$extr_cl=0;}
				
				//echo $pfpercent.'<br>'.$loss_leaves_cost.'<br>'.$loss_leaves_cost;exit;
				$paid_salary=($getsalary_rows['salary']-($pfpercent+$loss_absents_cost+$loss_leaves_cost+$staff_notjoin_amt));
				if(strtotime($staff_active_date['activ_date'])<=strtotime($date2) && $staff_totattd_firstday['totreport']!=''){
			     $insr_staffsal_qry=INSERT_KEYWORD."   ".TABLE_STAFFSAL_PAID." SET 
			                                staff_id='".$obj_db->real_escape_string($getsalary_rows['staff_id'])."', 
											month_id='".$obj_db->real_escape_string($exp_monthid_range[1])."',
											paid_sal='".$obj_db->real_escape_string($paid_salary)."',
											no_working='".$obj_db->real_escape_string($workingdays)."',
											no_absents='".$obj_db->real_escape_string($absents_count)."',
											no_leaves='".$obj_db->real_escape_string($leave_count)."',
											pf_amount='".$obj_db->real_escape_string($pfpercent)."',
											not_joinamt='".$staff_notjoin_amt."',
											extra_clamt='".$extr_cl."',
											loss_absents_cost='".$obj_db->real_escape_string($loss_absents_cost)."',
											loss_leaves_cost='".$obj_db->real_escape_string($loss_leaves_cost)."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											is_receive='0',
											generate_date='".$obj_db->real_escape_string(date('d-m-Y'))."'"; 
			 $res=$obj_db->get_qresult($insr_staffsal_qry);
			  $sal_insrcount++;
   			 }
			 }  
		  redirect_page($page_url."&cnt=".$sal_insrcount);
			}
			
			
	}
	?>