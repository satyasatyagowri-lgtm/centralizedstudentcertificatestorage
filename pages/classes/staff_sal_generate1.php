<?php 
class salgenerate{
   //echo "test"; exit;
					
function staff_salsave($data,$id) {
			global $obj_db, $page_url;
			$msg=array();
  			   
			   
			   $exp_monthid_range=explode('^',$_REQUEST['date']);
			   
			   $daterangeexp=explode(' to ',$exp_monthid_range[0]);
			   $dateexp1=explode('-',$daterangeexp[0]);
			   $dateexp2=explode('-',$daterangeexp[1]);
			   
			   $date1=$daterangeexp[0];
			   $date2=$daterangeexp[1];
			   
			   $del_stf_sal=$obj_db->get_qresult(DELETE_KEYWORD."  ".TABLE_STAFFSAL_PAID." where month_id='".$exp_monthid_range[1]."' and branch_id='".$data['branch_id']."' and year_id='".$_SESSION['year_id']."'");
			   
			   $year_row =  $obj_db->fetchRow("select * from  ".TABLE_YEAR." where year_id='".$_SESSION['year_id']."'");
				 $year_rg=$year_row['year'];
				 $expyrg=explode('-',$year_rg);
				 if($exp_monthid_range[2]>=6)
				 { $salgen_yr=$expyrg[0];}
				 else{$salgen_yr=$expyrg[1];}
			   
			   
			    $date1=date('Y-m-d',strtotime($dateexp1[0].'-'.$dateexp1[1].'-'.$salgen_yr));
			   $date2=$dateexp2[0].'-'.$dateexp2[1].'-'.$salgen_yr;
			   $date2=date('Y-m-d',strtotime(ltrim($date2)));
			   
			   
			   $month = array(1=>6,2=>7,3=>8,4=>9,5=>10,6=>11,7=>12,8=>1,9=>2,10=>3,11=>4,12=>5);
			   
    		  $qgetworkdays = $obj_db->fetchRow("select * from ".TABLE_MONTHLY_WORKINGDAYS." where  branch_id='".$data['branch_id']."' and year_id='".$_SESSION['year_id']."' and month_id='".$exp_monthid_range[1]."'");
               $workingdays = $qgetworkdays['working_days'];
			   

			   $getsalary="select staff_id,salary,pf_value,staffcatg_id from ".TABLE_STAFF_DETAILS." where branch_id='".$data['branch_id']."' and status=1"; 
			   $getsalary_row=$obj_db->get_qresult($getsalary);
			   $sal_insrcount=0;
			   while($getsalary_rows=$obj_db->fetchArray($getsalary_row)){
			     $paid_salary=0;
			    if($getsalary_rows['pf_value']>0){
				  $pfpercent=($getsalary_rows['salary']*$getsalary_rows['pf_value'])/100;
				}
				else{$pfpercent=0;}
			
			 	$get_prevexistcls=$obj_db->fetchRow("SELECT SUM(IFNULL(absents,0) + IFNULL( leaves, 0 )) as tals FROM  ".TABLE_MONTHLY_STF_ABSENTS." WHERE staff_id='".$getsalary_rows['staff_id']."' and month_id<'".$exp_monthid_range[1]."' and year_id='".$_SESSION['year_id']."'");
			
	      $get_totabst_leav=$obj_db->fetchRow("SELECT SUM(IFNULL(absents,0) + IFNULL( leaves, 0 )) as tals FROM  ".TABLE_MONTHLY_STF_ABSENTS." WHERE staff_id='".$getsalary_rows['staff_id']."' and month_id<='".$exp_monthid_range[1]."' and year_id='".$_SESSION['year_id']."'");
		  
		  $cmonth_totabst_leav=$obj_db->fetchRow("SELECT SUM(IFNULL(absents,0) + IFNULL( leaves, 0 )) as tals FROM  ".TABLE_MONTHLY_STF_ABSENTS." WHERE staff_id='".$getsalary_rows['staff_id']."' and month_id='".$exp_monthid_range[1]."' and year_id='".$_SESSION['year_id']."'");
						
		  $ggetabsents_rows="select absents,leaves,permissions,notjoin_days from ".TABLE_MONTHLY_STF_ABSENTS." where staff_id='".$getsalary_rows['staff_id']."'  and  branch_id=".$data['branch_id']." and year_id='".$_SESSION['year_id']."' and month_id='".$exp_monthid_range[1]."'";
		  
		 
				 $ggetabsents_rows=$obj_db->fetchRow($ggetabsents_rows);
				 $absents_count=0;
				 $leave_count=0;
				 $abst_count=0;$leav_count=0;$permiss_reduce=0;$rem_days=0;$exleav_cost=0;$remdays_cost=0;
				 
				 $get_existcls=12-$get_prevexistcls['tals'];
				
				if($ggetabsents_rows['absents']>0 || $ggetabsents_rows['leaves']>0 || $ggetabsents_rows['permissions']>0){
				$absents_count=$ggetabsents_rows['absents'];
				$abst_count=$absents_count;

				$leave_count=$ggetabsents_rows['leaves'];
				$leav_count=$leave_count;
				
				$permission_count=$ggetabsents_rows['permissions'];
				$permission_count=$permission_count;
				}
				
				$tot_abs_lev=$absents_count+$leave_count;
				
				$oneday_salary=($getsalary_rows['salary']/30);
				$oneforth_sal=$oneday_salary/4;
				
				$notjoinamt=$ggetabsents_rows['notjoin_days']*$oneday_salary;
				
				if($permission_count>1)
				$permiss_reduce=($permission_count-1)*$oneforth_sal;
				
				$loss_absents_cost=0;
				$loss_leaves_cost=0;
		 
		 		$absent_cost_days=$obj_db->get_qresult("select * from ".TABLE_STAFF_LCS."");
				while($absent_cost_days_rows=$obj_db->fetchArray($absent_cost_days)){
				if($absents_count>0 && $absent_cost_days_rows['attendance_status']=='A')
				{
				  $loss_absents_cost=($oneday_salary*($absent_cost_days_rows['cost_days']*$abst_count));
				}
				if($leave_count>0 && $absent_cost_days_rows['attendance_status']=='L'){
				  if($get_totabst_leav['tals']>18 && ($getsalary_rows['staffcatg_id']==1 || $getsalary_rows['staffcatg_id']==3)){
				  
				  $totex_leav=$get_totabst_leav['tals']-18;
				  if($totex_leav>0)
				  $exleav_cost=($oneday_salary*(2*$totex_leav));
                  $rem_days=$leav_count-$totex_leav;
				  if($rem_days>0)
				  $remdays_cost=($oneday_salary*(1*$rem_days));
				  
				  $loss_leaves_cost=$exleav_cost+$remdays_cost;
				
				  }
				  elseif($get_totabst_leav['tals']>6 && $getsalary_rows['staffcatg_id']==5){
				  $totex_leav=$get_totabst_leav['tals']-6;
				  if($totex_leav>0)
				  $exleav_cost=($oneday_salary*(2*$totex_leav));
                  $rem_days=$leav_count-$totex_leav;
				  if($rem_days>0)
				  $remdays_cost=($oneday_salary*(1*$rem_days));
				  
				  $loss_leaves_cost=$exleav_cost+$remdays_cost;
				  }
				  elseif($get_totabst_leav['tals']>0 && ($getsalary_rows['staffcatg_id']==2 || $getsalary_rows['staffcatg_id']==4))
				  $loss_leaves_cost=($oneday_salary*(1*$leav_count));
				  else
				  $loss_leaves_cost=($oneday_salary*($absent_cost_days_rows['cost_days']*$leav_count));
				}
				}
				
				
				//echo $pfpercent.'<br>'.$loss_leaves_cost.'<br>'.$loss_leaves_cost;exit;
				$paid_salary=($getsalary_rows['salary']-($pfpercent+$loss_absents_cost+$loss_leaves_cost+$permiss_reduce+$notjoinamt));
			     $insr_staffsal_qry=INSERT_KEYWORD."  ".TABLE_STAFFSAL_PAID." SET 
			                                staff_id='".$obj_db->real_escape_string($getsalary_rows['staff_id'])."', 
											month_id='".$obj_db->real_escape_string($exp_monthid_range[1])."',
											paid_sal='".$obj_db->real_escape_string($paid_salary)."',
											no_working='".$obj_db->real_escape_string($workingdays)."',
											no_absents='".$obj_db->real_escape_string($absents_count)."',
											tot_cls='".$get_existcls."',
											used_leaves='".$cmonth_totabst_leav['tals']."',
											extra_clamt='".$extr_cl."',
											no_leaves='".$obj_db->real_escape_string($leave_count)."',
											pf_amount='".$obj_db->real_escape_string($pfpercent)."',
											not_joinamt='".$notjoinamt."',
											permission_amt='".$permiss_reduce."',
											loss_absents_cost='".$obj_db->real_escape_string($loss_absents_cost)."',
											loss_leaves_cost='".$obj_db->real_escape_string($loss_leaves_cost)."',
											year_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											branch_id='".$obj_db->real_escape_string($data['branch_id'])."',
											is_receive='0',
											generate_date='".$obj_db->real_escape_string(date('d-m-Y'))."'"; 
			 $res=$obj_db->get_qresult($insr_staffsal_qry);
			 $sal_insrcount++;
			 }  
		  redirect_page($page_url."&cnt=".$sal_insrcount."&date=".$data['date']."&branch_id=".$data['branch_id']);
			}
			
			
	}
	?>