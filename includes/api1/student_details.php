<?php session_start();
include "../DbConfig.php";
$data = json_decode(file_get_contents("php://input"));

if($_GET['action']=="bchcls"){

/*if($_SESSION['user_type']=='department' || $_SESSION['user_type']=='staff' || $_SESSION['user_type']=='exams')
									  $depvarcnds=" a.course_id in (".$_SESSION['assign_course_ids'].")  and ";
									else*/
									  $depvarcnds=" ";

 $result =$obj_db->qry ("select branch_name,branch_id from  ".TABLE_BRANCH." where  branch_id in(".$_SESSION['assign_branch_ids'].")");

					for($m=0; $m<count($result); $m++)
					{
						if($_SESSION['branch_id']==$result[$m]['branch_id'])
						{ $sel_branpos=$m;}
						$secarray =$obj_db->qry ("SELECT a.course_id,a.course_name,b.branch_id FROM ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b where a.course_model=1 and $depvarcnds branch_id='".$result[$m]['branch_id']."' and  a.course_id=b.course_id order by a.course_id"); 	
						
						$myarray[$result[$m]['branch_id']]['branchid']= $result[$m]['branch_id'];						
						$myarray[$result[$m]['branch_id']]['branch']= $result[$m]['branch_name'];						
						$n=0;
						$myarray[$result[$m]['branch_id']]['course'][$n] = array("branchid"=>"0","courseid"=>"0","coursename"=>'All');
						for($n=0; $n<count($secarray); $n++)
						{	
							$myarray[$result[$m]['branch_id']]['course'][$secarray[$n]['course_id']] = array("branchid"=>$secarray[$n]['branch_id'],"courseid"=>$secarray[$n]['course_id'],"coursename"=>$secarray[$n]['course_name']);							
						}	
						
					}
     $myarray=array('myarray'=>$myarray,'sel_branch'=>$sel_branpos);
  echo json_encode($myarray);
 }
	
	
	else if($_GET['action']=="stdprofile"){
	//echo $_GET['student_id'];
 $result1 =$obj_db->qry("SELECT first_name,last_name,mobile_no,course_name,roll_no,a.student_id,c.branch_id,sec_id,previous_marks,hostel,vehicle,father_name,city,street,previous_marks,previous_city,previous_school
						  FROM ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." c,".TABLE_COURSE." d where 
						 d.course_id=c.course_id and a.student_id=c.student_id and y_id='".$_SESSION['year_id']."' and c.student_id='".$_SESSION['student_id']."'
						 and c.course_model=1");
 $result2 =$obj_db->qry("SELECT sec_name FROM ".TABLE_SECTION." where sec_id='".$result1['sec_id']."'");	
 
 
 $result3=$obj_db->qry("select fee_name,a.course_hostel_id,a.student_id,a.fee_type,a.course_id,a.term,course_amount,student_amount as std_fee,sum(term_concess)-sum(term_due) as paid,sum(term_due) as tdue,course_amount-student_amount as conc, sum(term_due) as due,is_promote from ".TABLE_STUDENT_FEE." a, ".TABLE_FEE_TYPE."  b where a.fee_type=b.fee_id and
			   student_id='".$_SESSION['student_id']."' and y_id=".$_SESSION['year_id']." group by fee_id,course_id");
				   
					for($m=0; $m<count($result3); $m++)
					{
						 $paidfees=$obj_db->fetchRow("select sum(is_paid_amount) as paidamts from ".TABLE_FEE_PAYMENT." where student_id='".$_SESSION['student_id']."' and course_hostel_id='".$result3['course_hostel_id']."' and fee_id='".$result3['fee_type']."' and year_id='".$_SESSION['year_id']."' and 	receipt_cancelled=0");
						if ($paidfees['paid']=='')	
						$paid = 0;
						else $paid = $paidfees['paid'];				
						$std_feearray[$m]['fee_name']= $result3[$m]['fee_name'];
						$std_feearray[$m]['course_hostel_id']= $result3[$m]['course_hostel_id'];
						$std_feearray[$m]['fee_type']= $result3[$m]['fee_type'];
						$std_feearray[$m]['course_id']= $result3[$m]['course_id'];
						$std_feearray[$m]['term']= $result3[$m]['term'];
						$std_feearray[$m]['course_amount']= $result3[$m]['course_amount'];
						$std_feearray[$m]['std_fee']= $result3[$m]['std_fee'];
						$std_feearray[$m]['paid']= $paid;
						$std_feearray[$m]['tdue']= $result3[$m]['tdue'];
						$std_feearray[$m]['conc']= $result3[$m]['conc'];
						$std_feearray[$m]['due']= $result3[$m]['due'];
						$std_feearray[$m]['is_promote']= $result3[$m]['is_promote'];
						
					}
					 
						 
	$result = array('profile' => $result1,'secdetails' => $result2,'std_feedts' => $std_feearray);					 					 
 
  echo json_encode($result);
 
 }	
 
  else if($_GET['action']=="stdreg_dts"){
	//echo $_GET['student_id'];
	
 $std_reddts =$obj_db->qry("SELECT first_name,last_name,mobile_no,father_name,pin_no,c.admission_no,course_name,device_enroll_id,MachineId	,roll_no,a.student_id,c.branch_id,sec_id,previous_marks,hostel,vehicle,father_name,city,street,previous_marks,previous_city,previous_school
						  FROM ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." c,".TABLE_COURSE." d where 
						 d.course_id=c.course_id and a.student_id=c.student_id and y_id='".$_SESSION['year_id']."' and student_status in('R','OA') and c.sec_id=0 and c.branch_id in(".$_SESSION['assign_branch_ids'].") and is_delete=0  and c.course_model=1");
						 
	$result =$std_reddts;					 					 
 
  echo json_encode($result);
 
 }
 else if($_GET['action']=="fee_conc"){
	
 extract($_POST);
//print_r($feedetails); exit;
for($i=0;$i<count($feedetails);$i++){
//echo $regdetails[$i]['name'];
if($feedetails[$i]['feeconc'] > 0){

//echo ("INSERT INTO (feeconc) VALUES (".$feedetails[$i]['feeconc'].")"); 

	  $std_fee_query="select * from ".TABLE_STUDENT_FEE."   where  student_id='".$_SESSION['student_id']."' and course_id='".$feedetails[$i]['course_id']."' and
		fee_type='".$feedetails[$i]['fee_type']."' and y_id='".$_SESSION['year_id']."' order by term desc"; 
		   
		  		   
		    $stdfees=$obj_db->get_qresult($std_fee_query);
		    $paidamount=$feedetails[$i]['feeconc'];
			$conce_amount=$feedetails[$i]['feeconc']; //for term concession update
			
			$tanm_conat='';$trmincconat='';$trmduconat='';$tdue_conat='';
			
			 $update_onlystd_fee="update ".TABLE_STUDENT_FEE." set student_amount=student_amount-'$paidamount' where student_id='".$_SESSION['student_id']."' and
					 course_id='".$feedetails[$i]['course_id']."' and y_id='".$_SESSION['year_id']."' and	fee_type='".$feedetails[$i]['fee_type']."'";
			$obj_db->get_qresult($update_onlystd_fee);
					 
			while($std_fee_rows=$obj_db->fetchArray($stdfees)) {
			   
                  if($std_fee_rows['term_due']<=$paidamount){
				    $tdueconces=$std_fee_rows['term_due'];
				    
					 $stdfee_updates="update ".TABLE_STUDENT_FEE." set term_due='0',term_income=(term_income-'$tdueconces') where student_id='".$_SESSION['student_id']."' and
					 course_id='".$feedetails[$i]['course_id']."' and	fee_type='".$feedetails[$i]['fee_type']."' and y_id='".$_SESSION['year_id']."' and term='".$std_fee_rows['term']."'";
		              $fee_updres=$obj_db->get_qresult($stdfee_updates);
					  $trmduconat.=$std_fee_rows['term'].',';$tdue_conat.=$std_fee_rows['term_due'].',';
		               	$paidamount=$paidamount-$std_fee_rows['term_due'];				
				  }
				  else {
				  $term_due=$std_fee_rows['term_due']-$paidamount;
				  $trmduconat.=$std_fee_rows['term'].',';$tdue_conat.=$paidamount.',';
				$stdfee_updates="update ".TABLE_STUDENT_FEE." set term_due='$term_due',term_income=(term_income-'$paidamount') where
				 student_id='".$_SESSION['student_id']."' and course_id='".$feedetails[$i]['course_id']."' and y_id='".$_SESSION['year_id']."' and fee_type='".$feedetails[$i]['fee_type']."' and term='".$std_fee_rows['term']."'";
		        $fee_updres=$obj_db->get_qresult($stdfee_updates);
				   $paidamount=0;
				  }
		      }	
	
			
			 $trm_incterms=substr($tanm_conat,0,-1);
			 $trm_incdue=substr($trmincconat,0,-1);
			 $trm_duetrms=substr($trmduconat,0,-1);
			 $trm_dueamt=substr($tdue_conat,0,-1);
			 $getorgid_query=$obj_db->fetchRow("SELECT org_id FROM ".TABLE_BRANCH." where branch_id='".$_SESSION['branch_id']."'");

			   $std_fee_conces_query="INSERT INTO  ".TABLE_STD_FEE_CONCES." SET 
			                                student_id='".$obj_db->real_escape_string($_SESSION['student_id'])."',
											org_id='".$obj_db->real_escape_string($getorgid_query['org_id'])."', 
											branch_id='".$obj_db->real_escape_string($_SESSION['branch_id'])."',
											concess_amount='".$obj_db->real_escape_string($feedetails[$i]['feeconc'])."', 
											course_hostel_id='".$obj_db->real_escape_string($feedetails[$i]['course_hostel_id'])."',
											fee_id='".$obj_db->real_escape_string($feedetails[$i]['fee_type'])."',
											user_id='".$obj_db->real_escape_string($_SESSION['user_id'])."',
											refer_by='".$obj_db->real_escape_string($_SESSION['user_id'])."', 
											reason='".$obj_db->real_escape_string($data['conce_reason'])."',
											conce_date='".date('Y-m-d h:m:s')."',
											inc_terms='".$trm_incterms."',
											inc_amts='".$trm_incdue."',
											due_trms='".$trm_duetrms."',
											due_amts='".$trm_dueamt."',
											year_id='".$_SESSION['year_id']."',
											course_id='".$obj_db->real_escape_string($feedetails[$i]['course_id'])."'";
										
											 
			   $res=$obj_db->get_qresult($std_fee_conces_query);
			 }


}
 
 echo $_SESSION['student_id']; 
}

	

 else if($_GET['action']=="seclist"){
	
 $result =$obj_db->qry("SELECT sec_id,sec_name FROM ".TABLE_SECTION." where course_id='".$_REQUEST['courseid']."' and branch_id='".$_REQUEST['branch_id']."' order by course_id asc,sec_id asc");
 for($p=0;$p<count($result);$p++){
   $seclst[$result[$p]['sec_id']]['sec_id']=$result[$p]['sec_id'];
   $seclst[$result[$p]['sec_id']]['sec_name']=$result[$p]['sec_name'];
   
  }
  echo json_encode($seclst);
 
 }
 
 else if($_GET['action']=="routeslist"){
	
 $result =$obj_db->qry("select route_id,route_name,route_short_name from ".TABLE_BUS_ROUTES." ORDER BY route_id");
 
  echo json_encode($result);
 
 }
 
  else if($_GET['action']=="stoplist"){
	//echo $_REQUEST['route_id'];
 $result =$obj_db->qry("select * from ".TABLE_BUS_STOPS." where route_id='".$_REQUEST['route_id']."' ORDER BY stop_id");
 
  echo json_encode($result);
 
 }
 
  else if($_GET['action']=="inserreg"){
	//echo $_REQUEST['route_id'];
	$first_name = $data->first_name;
	$last_name = $data->last_name;
	$father_name = $data->father_name;
	$gender = $data->gender;
	$mobile_no = $data->mobile_no;
	$street = $data->street;
	$city = $data->city;
	$pin = $data->pin;
	$selctedbranch = $data->selctedbranch;
	$courseid = $data->courseid;
	$student_way = $data->student_way;
	$route_id = $data->route_id;
	$stop_id = $data->stop_id;
	$is_conc = $data->is_conc;

 $result =$obj_db->get_qresult("INSERT INTO  ".TABLE_STUDENTDETAILS." SET 
			                                first_name='".$obj_db->real_escape_string($first_name)."', 
											last_name='".$obj_db->real_escape_string($last_name)."',
											father_name='".$obj_db->real_escape_string($father_name)."',
											gender='".$obj_db->real_escape_string($gender)."',
											mobile_no='".$obj_db->real_escape_string($mobile_no)."', 
											street='".$obj_db->real_escape_string($street)."',
											city='".$obj_db->real_escape_string($city)."',
											state='".$obj_db->real_escape_string($state)."',
											pin='".$obj_db->real_escape_string($pin)."'");
	$id=$obj_db->insert_id();	
	$getorgid_query=$obj_db->fetchRow("SELECT org_id FROM ".TABLE_BRANCH." where branch_id=".$_SESSION['branch_id']."");
	if(student_way == 'Bus') {
	$vehicle = 'yes'; 
	$hostel = 'no';
	}
	if(student_way == 'Hostel') { $vehicle = 'no'; 
	$hostel = 'yes';
	}
	
	$result =$obj_db->get_qresult("INSERT INTO  ".TABLE_STUDENT_EDU_DETAILS." SET 
			                                student_id='".$obj_db->real_escape_string($id)."',
											org_id='".$obj_db->real_escape_string($getorgid_query['org_id'])."', 
											branch_id='".$obj_db->real_escape_string($_SESSION['branch_id'])."',
											hostel='".$obj_db->real_escape_string($hostel)."', 
											vehicle='".$obj_db->real_escape_string($vehicle)."',
											course_id='".$obj_db->real_escape_string($courseid)."',
											course_model='1', 
                                            stop_id='".$obj_db->real_escape_string($stop_id)."',
											route_id='".$obj_db->real_escape_string($route_id)."', 
											y_id='".$obj_db->real_escape_string($_SESSION['year_id'])."'");										
 	
	$get_stdfees=$obj_db->get_qresult("SELECT b.course_fee,a.* FROM ".TABLE_FEE_TERMS." a,".TABLE_FEE_COURSE_MAP." b, ".TABLE_FEE_TYPE." c  where a.fee_id=b.fee_id and a.course_id=b.course_id and b.course_id='".$courseid."' and branch_id='".$_SESSION['branch_id']."' and a.fee_id=c.fee_id and common_fees=1 order by term desc");
			 
 				while($get_stdfeerw=$obj_db->fetchArray($get_stdfees)) {
							
			 $std_fee_insert_query="INSERT INTO  ".TABLE_STUDENT_FEE." SET 
			  								student_id='".$obj_db->real_escape_string($id)."',
											course_hostel_id='".$obj_db->real_escape_string($get_stdfeerw['fee_course_id'])."', 
											course_id='".$obj_db->real_escape_string($courseid)."',
											term='".$obj_db->real_escape_string($get_stdfeerw['term'])."', 
											term_amount='".$obj_db->real_escape_string($get_stdfeerw['term_amount'])."',
											term_due='".$obj_db->real_escape_string($get_stdfeerw['term_amount'])."',
											fee_type='".$obj_db->real_escape_string($get_stdfeerw['fee_id'])."', 
											course_amount='".$obj_db->real_escape_string($get_stdfeerw['course_fee'])."',
											student_amount='".$obj_db->real_escape_string($get_stdfeerw['course_fee'])."',
											y_id='".$obj_db->real_escape_string($_SESSION['year_id'])."',
											term_date='".$obj_db->real_escape_string($get_stdfeerw['due_date'])."',
											term_income='".$obj_db->real_escape_string($get_stdfeerw['term_amount'])."',
											term_concess='".$obj_db->real_escape_string($get_stdfeerw['term_amount'])."'";
											
			$res=$obj_db->get_qresult($std_fee_insert_query); 
						 
					}
	
  echo $id;
 
 }

else if($_GET['action']=="stdlist"){
	$seclst=array();//$fetchdata=array();
$secid="and c.sec_id='".$_REQUEST['sec_id']."'";
if($_REQUEST['course_id']>0 && $_REQUEST['sec_id']>0)
 $secid="and c.course_id='".$_REQUEST['course_id']."' and c.sec_id='".$_REQUEST['sec_id']."'";
elseif($_REQUEST['course_id']>0)
$secid="and c.course_id='".$_REQUEST['course_id']."' ";
else $secid=""; 
if($_SESSION['user_type']=='exams'){
 $campcnd=" and c.exam_campus_id in(0".$_SESSION['exam_campus_ids'].") ";
 $brncnd="   ";
 }
else {$campcnd="";
$brncnd="  and c.branch_id='".$_REQUEST['branch_id']."' ";
}
//and (c.student_status!='' or c.student_status is not null)
 
 $result =$obj_db->qry("SELECT Replace(first_name,'&nbsp;','') as first_name,father_name,last_name,a.gender,roll_no,c.branch_id,c.course_id as courseid,c.sec_id,mobile_no,a.mobile_no2,street,course_name,vehicle,city,pin_no,c.admission_no,c.reservation_no,a.student_id,previous_marks,join_date,previous_marks,previous_city,previous_school,admission_form_filepath,e.pro_name FROM ".TABLE_STUDENTDETAILS." a left join ".TABLE_PRO." e on a.pro_id=e.pro_id, ".TABLE_STUDENT_EDU_DETAILS." c,".TABLE_COURSE." d  where  a.student_id=c.student_id and y_id='".$_SESSION['year_id']."'  and c.course_id=d.course_id  $campcnd  and  c.course_model=1 $secid and c.is_delete=0 $brncnd order by  CAST(c.admission_no as UNSIGNED) asc");
 
  /*$sec_lst =$obj_db->qry("SELECT sec_id,sec_name FROM ".TABLE_SECTION." where course_id='".$_REQUEST['course_id']."' and branch_id='".$_REQUEST['branch_id']."' order by course_id asc,sec_id asc");
  for($p=0;$p<count($sec_lst);$p++){
   $seclst[$sec_lst[$p]['sec_id']]['sec_id']=$sec_lst[$p]['sec_id'];
   $seclst[$sec_lst[$p]['sec_id']]['sec_name']=$sec_lst[$p]['sec_name'];
   
  }*/
  $fetchdata=array('std_list'=>$result,'seclst'=>$seclst);
//header('Content-Type: application/json; charset=utf-8'); 
//echo json_encode($fetchdata,JSON_PRETTY_PRINT_DOUBLE_SPACES);
  echo json_encode($fetchdata, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
 
 }
 else if($_GET['action']=="outgoing_stddts"){
	//echo $_GET['student_id'];
	/*"SELECT first_name,last_name,mobile_no,mobile_no2,father_name,pin_no,c.admission_no,course_name,device_enroll_id,MachineId	,roll_no,a.student_id,c.branch_id,sec_id,previous_marks,hostel,vehicle,father_name,city,street,sum(term_due) as tdue,sum(term_amount) as tamt
						  FROM ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." c,".TABLE_COURSE." d,".TABLE_STUDENT_FEE." b where 
						 d.course_id=c.course_id and a.student_id=c.student_id and c.y_id='".$_SESSION['year_id']."' and c.y_id=b.y_id and a.student_id=b.student_id and c.student_id=b.student_id  and c.sec_id=0 and c.branch_id in(".$_SESSION['assign_branch_ids'].") and is_delete=2  and c.course_model=1";*/
					
 $ogstd_reddts =$obj_db->qry("SELECT first_name,last_name,mobile_no,mobile_no2,father_name,pin_no,c.admission_no,course_name,device_enroll_id,MachineId	,roll_no,a.student_id,c.branch_id,sec_id,previous_marks,hostel,vehicle,father_name,city,street,sum(term_due) as tdue,sum(term_amount) as tamt,sum(term_income) as stdamt,sum(term_amount)-sum(term_income) as conces,sum(term_income)-sum(term_due) as paid
						  FROM ".TABLE_STUDENTDETAILS." a left join ".TABLE_STUDENT_FEE." b on a.student_id=b.student_id,".TABLE_STUDENT_EDU_DETAILS." c,".TABLE_COURSE." d where 
						 d.course_id=c.course_id and a.student_id=c.student_id and c.y_id='".$_SESSION['year_id']."' and c.sec_id=0 and c.branch_id in(".$_SESSION['assign_branch_ids'].") and is_delete=2  and c.course_model=1 group by a.student_id ");
						 
	for($i=0;$i<count($ogstd_reddts);$i++){
	  $rsdata[$i]['student_id']=$ogstd_reddts[$i]['student_id'];
	  $rsdata[$i]['name']=$ogstd_reddts[$i]['first_name'].' '.$ogstd_reddts[$i]['last_name'];
	  $rsdata[$i]['father_name']=$ogstd_reddts[$i]['father_name'];
	  $rsdata[$i]['mobile_no']=$ogstd_reddts[$i]['mobile_no'];
	  $rsdata[$i]['mobile_no2']=$ogstd_reddts[$i]['mobile_no2'];
	  $rsdata[$i]['course_name']=$ogstd_reddts[$i]['course_name'];
	  $rsdata[$i]['city']=$ogstd_reddts[$i]['city'];
	  $rsdata[$i]['street']=$ogstd_reddts[$i]['street'];
	  $rsdata[$i]['tdue']=$ogstd_reddts[$i]['tdue'];
	  $rsdata[$i]['tamt']=$ogstd_reddts[$i]['tamt'];
	  $rsdata[$i]['conces']=$ogstd_reddts[$i]['conces'];
	  $rsdata[$i]['paid']=$ogstd_reddts[$i]['paid'];
	}				 					 
 
  echo json_encode($rsdata);
 
 }
 else if($_GET['action']=="get_branch_exams"){

 $result =$obj_db->qry("select exam_name,a.exam_model,a.exam_type_id,a.exam_id as exam_id,exam_type_name,branch_id from ".TABLE_EXAM_NAMES." a,".TABLE_EXAM_TYPES." b,".TABLE_EXAM_BRANCH_MAP." c where  a.exam_id=c.exam_id  and c.branch_id ='".$_REQUEST['branch_id']."' and year_id='".$_SESSION['year_id']."' and a.exam_type_id=b.exam_type_id  group by exam_id ORDER BY a.exam_id");
 for($i=0;$i<count($result);$i++){
 $myarray[$i]['exam_name']= $result[$i]['exam_name'];						
 $myarray[$i]['exam_id']= $result[$i]['exam_id'];
 $myarray[$i]['branch_id']= $result[$i]['branch_id'];		
 $myarray[$i]['exam_type_name']= $result[$i]['exam_type_name'];		
 $myarray[$i]['exam_model']= $result[$i]['exam_model'];			
 $exam_subfrm="select sec_id from ".TABLE_EXAM_SUB_MAP." where exam_id='".$result[$i]['exam_id']."'";
						  $exam_subfrm_num=$obj_db->fetchNum($exam_subfrm);
 $myarray[$i]['exam_submap']= $exam_subfrm_num;	
 }
 
  echo json_encode($myarray);
 
 }
 
  else if($_REQUEST['action']=="exam_view"){

 $result =$obj_db->qry("select b.sec_id,b.sec_name from ".TABLE_EXAM_SEC_MAP." a,".TABLE_SECTION." b where a.sec_id=b.sec_id and branch_id IN(".$_SESSION['assign_branch_ids'].") and exam_id='".$_SESSION['exam_id']."' and b.course_id='".$_SESSION['exam_course']."'");
 for($m=0; $m<count($result); $m++)
					{
						$secarray =$obj_db->qry ("select a.exam_id,a.sec_id,b.sub_id,b.sub_name,a.sub_marks,a.min_marks,a.exam_date,reading,project,writing,sub_order from ".TABLE_EXAM_SUB_MAP." a,".TABLE_SUB_NAME." b where a.exam_id='".$_SESSION['exam_id']."' and a.sec_id=".$result[$m]['sec_id']."
			 and b.sub_id=a.sub_id order by sub_order asc"); 	
						$myarray[$m]['sec_name']= $result[$m]['sec_name'];						
						$myarray[$m]['sec_id']= $result[$m]['sec_id'];
						for($n=0; $n<count($secarray); $n++)
						{				
							$myarray[$m]['exam_sec_subs'][$n] = array("exam_id"=>$secarray[$n]['exam_id'],"sub_id"=>$secarray[$n]['sub_id'],"sub_name"=>$secarray[$n]['sub_name'],"sub_marks"=>$secarray[$n]['sub_marks'],"min_marks"=>$secarray[$n]['min_marks'],"exam_date"=>$secarray[$n]['exam_date'],"reading"=>$secarray[$n]['reading'],"project"=>$secarray[$n]['project'],"writing"=>$secarray[$n]['writing'],"sub_order"=>$secarray[$n]['sub_order']); 
						}	
					}
 
  echo json_encode($myarray); 
 }
?>