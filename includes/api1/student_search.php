<?php session_start();
include "../DbConfig.php";
if($_REQUEST['sbranch_id']!='')
$sbranch_id=$_REQUEST['sbranch_id'];
else $sbranch_id=$_SESSION['assign_branch_ids'];
if($_REQUEST['scourse_id']>0){$var=" and c.course_id='".$_REQUEST['scourse_id']."'";}
else{$var="";}
$data = json_decode(file_get_contents("php://input"));

$tex = $data->searchText;

/*if($_SESSION['user_type']=='principal' || $_SESSION['user_type']=='staff' || $_SESSION['user_type']=='exams')
							        {
									  $depvarcnds=" and c.exam_campus_id in (".$_SESSION['exam_campus_ids'].")   ";
									}
									else{*/
									 
									  $depvarcnds=" and c.branch_id in(".$sbranch_id.") ";
								//	}
if($tex!="")
{ $cnd=" and (CONCAT(first_name,' ',last_name) like '%{$tex}%'  or first_name like '%{$tex}%' or last_name like '%{$tex}%' or a.pin_no like '%{$tex}%' or c.admission_no like '%{$tex}%' or mobile_no like '%{$tex}%') ";
}else{$cnd="";}
//echo "SELECT first_name,last_name,a.student_id,mobile_no,course_short_name,a.admission_no,mobile_no FROM Student_Details a,Student_Edu_Details c, course_details d  where a.student_id=c.student_id and is_delete=0  and y_id='".$_SESSION['year_id']."' $cnd and c.branch_id in(".$sbranch_id.") and c.course_id=d.course_id $var  and c.course_model=1  limit 20 ";

     $std_data=$obj_db->qry("SELECT first_name,last_name,a.student_id,mobile_no,pin_no,c.admission_no,mobile_no,c.branch_id,c.course_id FROM Student_Details a,Student_Edu_Details c, course_details d  where a.student_id=c.student_id and is_delete=0 and y_id='".$_SESSION['year_id']."' $cnd    $depvarcnds and c.course_id=d.course_id $var  and c.course_model=1  limit 20 ");

echo json_encode($std_data);

?>