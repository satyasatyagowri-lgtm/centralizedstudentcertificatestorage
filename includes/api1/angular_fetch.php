<?php session_start();
include "../DbConfig.php";
if($_REQUEST['action']=="std_admission"){
    
 $get_stddts =$obj_db->qry ("SELECT first_name, last_name, mobile_no, course_name, b.student_id, a.student_status, previous_marks, caste, vehicle, father_name, a.admission_no, city, street FROM ".TABLE_STUDENTDETAILS." a,".TABLE_PARENT." b,".TABLE_STUDENT_EDU_DETAILS." c,".TABLE_COURSE." d where  a.student_id=b.student_id and
  d.course_id=c.course_id and a.student_id=c.student_id and y_id='".$_SESSION['year_id']."' and c.course_model=1 and c.is_delete=0 ORDER BY join_date desc");
 
  echo json_encode($get_stddts);
 
 }
elseif($_REQUEST['action']=="std_fee_due"){
    
	
    for($h=1;$h<=$_REQUEST['terms'];$h++){
					  $tterms.=$h.',';
					  }
					  $trimterms=substr($tterms,0,-1);
   $course_sec=$obj_db->fetchRow("select course_name,sec_name,branch_short_name from ".TABLE_COURSE." a,".TABLE_SECTION." b,".TABLE_BRANCH." c where
             b.branch_id='".$_REQUEST['branchid']."' and c.branch_id=b.branch_id and  sec_id='".$_REQUEST['sec_id']."' and a.course_id=b.course_id");
			
			$exids="'excel'".","." 'Sheet Name Here'";
			if($_REQUEST['sec_id']=="All")
			{$course_secname="Over All Students";
			 $cnd="";
			 }
			else {$course_secname=$course_sec['course_name'].'-'.$course_sec['sec_name'];
			$cnd=" and c.sec_id='".$_REQUEST['sec_id']."'";
			}	
             $brancfees="select b.fee_id,b.fee_name from ".TABLE_STUDENT_FEE." a,".TABLE_FEE_TYPE." b,".TABLE_STUDENT_EDU_DETAILS." c where  branch_id='".$_REQUEST['branchid']."' and a.y_id='".$_SESSION['year_id']."' $cnd and a.course_id=c.course_id and c.is_delete=0 and a.y_id=c.y_id and b.fee_id=a.fee_type group by a.fee_type order by b.fee_id";
	                    $branchfees=$obj_db->get_qresult($brancfees);
						$branchnum=$obj_db->fetchNum($brancfees);
						$tdnum=8+$branchnum;
						$i=0;
					
						while($branchfees_rows=$obj_db->fetchArray($branchfees)){ 
						 $fetch_data[]=$branchfees_rows['fee_name'];
						$fee_id[$i]=$branchfees_rows['fee_id'];
						
						  $i++; }  
				
	  $std_due_details="select a.student_id,concat(first_name,' ',last_name) as stname,roll_no,term_amount,term_due,fee_type,gender,mobile_no,c.course_id from ".TABLE_STUDENT_FEE." a,".TABLE_STUDENTDETAILS." b,".TABLE_STUDENT_EDU_DETAILS." c where branch_id='".$_REQUEST['branchid']."' $cnd and is_delete=0 and a.student_id=b.student_id and a.student_id=c.student_id and course_model=1  and term in(".$trimterms.") and a.course_id=c.course_id and a.y_id=c.y_id  and a.y_id=".$_SESSION['year_id']." group by a.student_id having sum(term_due)>0 order by cast(c.course_id as decimal(5,2)) asc,CAST(roll_no as UNSIGNED) ,first_name asc,last_name asc";					
		$std_due_details_res=$obj_db->get_qresult($std_due_details);
					 $j=0;
					 $total_amt=0;
					  
					 while($std_due_details_rows=$obj_db->fetchArray($std_due_details_res)){
					 $st_course=$obj_db->fetchRow("select course_name from ".TABLE_COURSE." where course_id='".$std_due_details_rows['course_id']."'");
					  $fetch_data[]=$std_due_details_rows['stname'];
					  $fetch_data[]=$st_course['course_name'];
					  $fetch_data[]=$std_due_details_rows['mobile_no'];
					 
			
					
					  $tot_sum=0;
 						$grand_sum=0;
					 for($k=0;$k<sizeof($fee_id);$k++){
					 
					$rsgetdue= $obj_db->fetchRow("select sum(term_due) as termdue from ".TABLE_STUDENT_FEE." where student_id=".$std_due_details_rows['student_id']." and
					 term in($trimterms) and y_id=".$_SESSION['year_id']." and fee_type='".$fee_id[$k]."'  group by fee_type  having sum(term_due)>0");
                     $fetch_data[]=$rsgetdue['termdue'];
		 $tot_sum=$tot_sum+$rsgetdue['termdue'];
		 $sum[$j][$k]=$sum[$j][$k]+$rsgetdue['termdue'];
  }
   $fetch_data[]=$tot_sum;
		  $j++; } 
		   
		 
 
 for($j=0;$j<sizeof($sum[0]);$j++){
	 for($i=0;$i<sizeof($sum);$i++)
 		{ $subtotal[$j]=$subtotal[$j]+$sum[$i][$j]; }
		}

  $grand_sum=0;
 for($j=0;$j<sizeof($subtotal);$j++)
	{	$grand_sum=$grand_sum+$subtotal[$j];
	$fetch_data[]=$subtotal[$j];
	} 
	$fetch_data[]=$grand_sum;
	
 echo json_encode($fetch_data);
   }
?>