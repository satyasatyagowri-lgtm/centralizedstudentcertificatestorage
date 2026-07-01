<?php session_start();
include "../DbConfig.php";
if($_REQUEST['action']=="month_dts"){
	$get_mothdts=$obj_db->qry("select * from ".TABLE_MONTHS." ");
	$coursesubjs=$obj_db->qry("select * from ".TABLE_SUB_NAME." a,".TABLE_SUB_TO_COURSE." b,".TABLE_BRANCH." c where course_id='".$_REQUEST['std_courseid']."' and c.branch_id='".$_REQUEST['std_branchid']."' and a.sub_id=b.sub_id and c.org_id=a.org_id  order by a.sub_id asc");
	 
	echo json_encode(array('monthdts'=>$get_mothdts,'course_subjs'=>$coursesubjs));
 }  
elseif($_REQUEST['action']=="subsyllabus_details"){
	$get_curmonth_subsyllabus=$obj_db->qry("select *,DATE_FORMAT(STR_TO_DATE(topic_date, '%d-%m-%Y'), '%Y-%m-%d') as sdt,DATE_FORMAT(STR_TO_DATE(end_date, '%d-%m-%Y'), '%Y-%m-%d') as edt from ".TABLE_ACADEMIC_SYLLABUS." where sub_id='".$_REQUEST['sub_id']."' and course_id='".$_REQUEST['stdcourse_id']."'  and branch_id='".$_REQUEST['stdbranch_id']."'");
	 
	echo json_encode(array('syllabus_schedules'=>$get_curmonth_subsyllabus,'seldt'=>date('Y').'-'.$_REQUEST['month_num'].'-'.date('d')));
 }
 
 elseif($_REQUEST['action']=="eventschedule_details"){
	//echo "select * from ".TABLE_ACADEMIC_SYLLABUS." where sub_id='".$_REQUEST['sub_id']."' and course_id='".$_REQUEST['course_id']."' and month(topic_date)='".$_REQUEST['month_num']."' and branch_id='".$_REQUEST['branch_id']."'";
	$get_curmonth_subsyllabus=$obj_db->qry("select *,DATE_FORMAT(STR_TO_DATE(event_date, '%d-%m-%Y'), '%Y-%m-%d') as sdt,DATE_FORMAT(STR_TO_DATE(end_date, '%d-%m-%Y'), '%Y-%m-%d') as edt from ".TABLE_ACADEMIC_EVENT_SCHEDULE." where  branch_id='".$_REQUEST['branch_id']."'");
	 
	echo json_encode(array('event_schedules'=>$get_curmonth_subsyllabus,'seldt'=>date('Y-m-d')));
 }
?>