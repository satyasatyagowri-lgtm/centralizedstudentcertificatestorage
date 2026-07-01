<?php session_start();
include "../DbConfig.php";
$data = json_decode(file_get_contents("php://input"));

if($_REQUEST['action']=="std_compexamdts"){
$exp_courseid=explode('^',$_REQUEST['course_id']);
if($_REQUEST['set_promtecourse']=='yes')
 $_SESSION['std_promote_course']=$_REQUEST['course_id'];
 /*$qgetstddetails = $obj_db->fetchRow("select * from Student_Edu_Details  where course_model=1 and student_id='".$_SESSION['ang_stdid']."' and course_id='".$exp_courseid[0]."' ");
			$secid=	$qgetstddetails['sec_id'];*/
			
			
			$secname=$obj_db->qry("select course_id,course_name,course_type_id,is_bipc,coursewise_ord from ".TABLE_COURSE."  ");
		   $coursenameky = array_search($exp_courseid[0], array_column($secname, 'course_id'));
		 $coursewiseord_kys = array_keys(array_column($secname, 'coursewise_ord'),$secname[$coursenameky]['coursewise_ord']);
		 $selcourseid_relatedcourses='';
 		 foreach($coursewiseord_kys as $k=>$v)
		   $selcourseid_relatedcourses.=$secname[$v]['course_id'].',';
		   $trmrelated_courseids=substr($selcourseid_relatedcourses,0,-1);

$gtexamtype=$obj_db->qry("select exam_type_name,c.exam_type_id,group_concat(b.exam_id) as exids from ".TABLE_EXAM_COURSE_MAP." a,".TABLE_EXAM_NAMES." b,exam_types c where 
						a.course_id in(0".$trmrelated_courseids.")   and b.year_id='".$exp_courseid[1]."'  and b.exam_type_id=c.exam_type_id
						and a.exam_id=b.exam_id group by c.exam_type_id order by exam_type_order asc");
			
                    for($m=0; $m<count($gtexamtype); $m++)
					{

					 $qgetexamsubjects =  $obj_db->qry("select a.sub_id as subid,a.short_name as subname
		 from ".TABLE_SUB_NAME." a,".TABLE_SUB_TO_COURSE." b,".TABLE_EXAM_SUB_MAP." c where b.course_id	in(0".$trmrelated_courseids.") and c.exam_id in(0".$gtexamtype[$m]['exids'].") and b.sub_id=c.sub_id and c.year_id='".$exp_courseid[1]."' and a.sub_id=c.sub_id  and b.sub_id=a.sub_id group by c.sub_id order by a.sub_id");
		   	
						$myarray[$m]['exam_type_name']= $gtexamtype[$m]['exam_type_name'];						
						$myarray[$m]['exam_type_id']= $gtexamtype[$m]['exam_type_id'];
						$subid='';
						for($n=0; $n<count($qgetexamsubjects); $n++)
						{				
							$myarray[$m]['exam_subs'][$n] = array("sub_id"=>$qgetexamsubjects[$n]['subid'],"sub_name"=>$qgetexamsubjects[$n]['subname']); 
							 $subid.=$qgetexamsubjects[$n]['subid'].',';
		                 }
		 	 $subids = substr($subid,0,-1);
             $totalsub = explode(",",$subids);			
						
						   $qgetsexamscount = "select b.*,DATE_FORMAT(STR_TO_DATE(b.dt_of_exam, '%d-%m-%Y'), '%d-%m') as doe from ".TABLE_EXAM_COURSE_MAP." a,".TABLE_EXAM_NAMES." b where a.course_id	in(0".$trmrelated_courseids.") and b.year_id='".$exp_courseid[1]."' and exam_type_id='".$gtexamtype[$m]['exam_type_id']."' and   a.exam_id=b.exam_id group by a.exam_id";
 
     $rsgetsexamscount = $obj_db->qry($qgetsexamscount);
	
	 for($p=0; $p<count($rsgetsexamscount); $p++){
	  if($rsgetsexamscount[$p]['exam_model']==1)
	   $exqry=TABLE_COMMPETETIVE_EXAM_MARKS;
	  else $exqry=TABLE_EXAM_RESULT;
         $qgetstudentmarks = "select * from $exqry a where student_id=".$_SESSION['ang_stdid']." and exam_id='".$rsgetsexamscount[$p]['exam_id']."'";  
    $rsgetstudentmarks = $obj_db->qry($qgetstudentmarks);
	$examnumrows = $obj_db->fetchNum($qgetstudentmarks);
	
	/*$examtotmarks_rows =$obj_db->fetchRow("select sum(ifnull(reading,0) +ifnull(writing,0) +ifnull(project,0) +ifnull(sub_marks,0)) as exam_total_marks from exam_sub_map where sec_id='".$qgetstddetails['sec_id']."' and exam_id='".$rsgetsexamscount[$p]['exam_id']."'"); */
	if($examnumrows == 1){
	$q=0;
	//$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$rsgetsexamscount[$p]['exam_name']);
	//$q++;
	$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$rsgetsexamscount[$p]['doe']);
	$q++;
	for($b=0;$b<count($rsgetstudentmarks);$b++){
	$exp_mxmrks=explode(',',$rsgetstudentmarks[$b]['max_marks']);
	$subject = explode(',',$rsgetstudentmarks[$b]['sub_id']);
	 $mark = explode(',',$rsgetstudentmarks[$b]['marks']);
	 $maxmarks = explode(",",$rsgetstudentmarks[$b]['max_marks']);
	$x=0;
 	//echo count($totalsub).'<br>';
	while($x<count($totalsub)){ 
					    $arr_serch=array_search($totalsub[$x],$subject);
						$mrkss=$mark[$arr_serch]."/".$maxmarks[$arr_serch];
						 /*if(in_array($totalsub[$x],$subject))
						$myarray[$gtexamtype[$m]['exam_type_id']][$b]['exam_mrks'][$q] = array("submrks"=>$mrkss);
						else $myarray[$gtexamtype[$m]['exam_type_id']][$b]['exam_mrks'][$q]=array("submrks"=>'');*/
					    if(in_array($totalsub[$x],$subject))
						$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$mrkss);
						else $myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>'');
						$q++;$x++;
}	
$extot=round($rsgetstudentmarks[$b]['total_marks'])."/".array_sum($exp_mxmrks);
$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>$extot);
$q++;
$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>$rsgetstudentmarks[$b]['section_rank']);		 
					}
					}
}
 }
  echo json_encode($myarray);
 }
 elseif($_REQUEST['action']=="exam_subwiseavgdts"){
 $branch_name=$obj_db->fetchRow("select branch_name from ".TABLE_BRANCH." where branch_id='".$_REQUEST['branch_id']."'");
 $course_name=$obj_db->fetchRow("select course_name from ".TABLE_COURSE." where course_id='".$_REQUEST['course_id']."'");
 $extitle=$branch_name['branch_name'].' '.$course_name['course_name'];
					 $qgetexamsubjects =  $obj_db->qry("select a.sub_id as subid,a.short_name as subname
		 from ".TABLE_SUB_NAME." a,".TABLE_SUB_TO_COURSE." b,".TABLE_EXAM_SUB_MAP." c,".TABLE_EXAM_NAMES." d where b.course_id	='".$_REQUEST['course_id']."' and is_competitive in(1,2) and c.exam_id=d.exam_id and d.exam_type_id='".$_REQUEST['exam_type_id']."' and a.sub_id=c.sub_id and b.sub_id=a.sub_id group by b.sub_id order by a.sub_id");
		
		 $getnumexams =  $obj_db->qry("select a.exam_name,a.dt_of_exam,a.exam_id from  ".TABLE_EXAM_NAMES." a,".TABLE_EXAM_BRANCH_MAP." b,".TABLE_EXAM_COURSE_MAP." c  where c.course_id='".$_REQUEST['course_id']."' and a.exam_type_id='".$_REQUEST['exam_type_id']."' and b.branch_id='".$_REQUEST['branch_id']."' and a.year_id='".$_SESSION['year_id']."' and a.exam_id=b.exam_id and b.exam_id=c.exam_id and a.exam_model=1");
		 for($g=0; $g<count($getnumexams); $g++)
						{
						 $condexams.=$getnumexams[$g]['exam_name'].' ('.$getnumexams[$g]['dt_of_exam'].'),';
						 $exam_ids.=$getnumexams[$g]['exam_id'].',';
						}
						
						 $condexams = substr($condexams,0,-1);
						 $condexamids = substr($exam_ids,0,-1);
						
						for($n=0; $n<count($qgetexamsubjects); $n++)
						{		
						    $subs[$n] = $qgetexamsubjects[$n]['subname'];		
							 $subid.=$qgetexamsubjects[$n]['subid'].',';
		                 }
		 	 $subids = substr($subid,0,-1);
             $totalsub = explode(",",$subids);			
 $get_stddtsqry=$obj_db->qry("select first_name,last_name,a.student_id,course_id,b.admission_no from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b where b.course_id='".$_REQUEST['course_id']."' and branch_id='".$_REQUEST['branch_id']."' and is_delete=0 and a.student_id=b.student_id and b.y_id='".$_SESSION['year_id']."' order by cast(b.admission_no as unsigned) asc ");
  for($m=0; $m<count($get_stddtsqry); $m++){
   $k=0;
    $myarray[$m][$k]=$get_stddtsqry[$m]['admission_no'];
	$k++;
	$myarray[$m][$k]=$get_stddtsqry[$m]['first_name'].' '.$get_stddtsqry[$m]['last_name'];
	
//	echo "select marks,max_marks,sec_sub_rank,sub_id from ".TABLE_COMMPETETIVE_EXAM_MARKS."  where student_id='".$get_stddtsqry[$m]['student_id']."' and year_id='".$_SESSION['year_id']."' limit 1 ";
	 $std_compexams = $obj_db->qry("select marks,max_marks,sec_sub_rank,sub_id from ".TABLE_COMMPETETIVE_EXAM_MARKS."  where student_id='".$get_stddtsqry[$m]['student_id']."' and year_id='".$_SESSION['year_id']."' and exam_id in(".$condexamids.") and course_id='".$_REQUEST['course_id']."' ");
	   
	 $tot_stmrks=0;$tot_exmrks=0;$k++;
	 for($p=0;$p<count($totalsub);$p++){
	  $stsub_mrks='';$stsub_mxmrks='';$stsub_rnks='';
	  $std_writnexams=0;$std_subwritenexms=0;
	//  $exp_mrks='';$exp_mxmrks='';$exp_mxmrks='';
	  $exp_subid='';$exp_mrks='';$exp_mxmrks='';$exp_surnks='';
	   for($q=0;$q<count($std_compexams);$q++){
	  $exp_subid=explode(',',$std_compexams[$q]['sub_id']);
	  $exp_mrks=explode(',',$std_compexams[$q]['marks']);
	  $exp_mxmrks=explode(',',$std_compexams[$q]['max_marks']);
	  $exp_surnks=explode(',',$std_compexams[$q]['sec_sub_rank']);
	  $cnt=0;
	  for($j=0;$j<count($exp_mrks);$j++){
	   if(is_numeric($exp_mrks[$j]))
	    $cnt++;
	  }
	  if($cnt>0)
	  $std_writnexams++;
	  $find_arpos=array_search($totalsub[$p],$exp_subid);

	  if(!in_array($totalsub[$p],$exp_subid))$find_arpos='null';
	  else {$find_arpos=$find_arpos;
	        $std_subwritenexms++;
			}
		   $stsub_mrks[]=$exp_mrks[$find_arpos];
		   
		   $stsub_mxmrks[]=$exp_mxmrks[$find_arpos];
		   $stsub_rnks[]=$exp_surnks[$find_arpos];
	   }
		$rank_avg=number_format(array_sum($stsub_rnks)/count($stsub_rnks),2);
		$mrks=number_format((array_sum($stsub_mrks)/$std_subwritenexms),1).'/'.number_format((array_sum($stsub_mxmrks)/$std_subwritenexms),1);
		$myarray[$m][$k]=$mrks;
		$k++;
		$myarray[$m][$k]=$rank_avg;
		$tot_stmrks=$tot_stmrks+array_sum($stsub_mrks);
		$tot_exmrks=$tot_exmrks+array_sum($stsub_mxmrks);
		$k++;
	 
	 
	 }$tmrks=number_format(($tot_stmrks/$std_writnexams),1).'/'.number_format(($tot_exmrks/count($getnumexams)),1);
	// $k++;
	 $myarray[$m][$k]=$tmrks;
	 $k++;
	 $myarray[$m][$k]=$std_writnexams.'/'.count($getnumexams);
	 }
	 $fetch_data=array('sub_names'=>$subs,'std_avgmrks'=>$myarray,'exam_dts'=>$condexams,'no_exams'=>count($getnumexams),'extitle'=>$extitle);
  echo json_encode($fetch_data);
 }

 elseif($_REQUEST['action']=="examtypewise_subwiseavgdts"){
 $branch_name=$obj_db->fetchRow("select branch_name from ".TABLE_BRANCH." where branch_id='".$_REQUEST['branch_id']."'");
 $course_name=$obj_db->fetchRow("select group_concat(course_id) as coursids from ".TABLE_COURSE." where course_type_id='".$_REQUEST['juni_senior']."'");
 $extitle=$branch_name['branch_name'].' '.$course_name['course_name'];
$get_exams=$obj_db->qry("select * from ".TABLE_EXAM_NAMES." a,".TABLE_EXAM_COURSE_MAP." b where a.exam_type_id='".$_REQUEST['exam_type_id']."' and a.exam_id=b.exam_id and b.course_id in(".$course_name['coursids'].") and a.year_id='".$_SESSION['year_id']."' group by b.exam_id order by a.exam_id desc limit 3");
$sexid='';
$exnamarr=array();
foreach($get_exams as $kk=>$kv)
{
$sexid.=$kv['exam_id'].',';
 $exnamarr[$kv['exam_id']]=$kv['exam_name'];
}			
  $trm_exids=substr($sexid,0,-1);

		 $qgetexamsubjects =  $obj_db->qry("select c.exam_id,c.sub_id,a.short_name,c.sub_marks 
		 from ".TABLE_SUB_NAME." a,".TABLE_SUB_TO_COURSE." b,".TABLE_EXAM_SUB_MAP." c  where    is_competitive in(1,2)  and c.exam_id in(".$trm_exids.") and a.sub_id=c.sub_id and b.sub_id=a.sub_id  GROUP by c.exam_id,c.sub_id order by c.exam_id,a.sub_id");
		 $myarray=array();
		 $arsubids=array();$arsubnames=array();$exam_subar=array();$exam_submxmrkar=array();
		 $k=0;$w=0; $exnamearr=array();$exam_idar=array();$g=0;
		 for($n=0; $n<count($qgetexamsubjects); $n++)
						{		
						
						     $exam_subar[$qgetexamsubjects[$n]['exam_id']][$qgetexamsubjects[$n]['sub_id']]=$qgetexamsubjects[$n]['sub_id'];
							  $exam_submxmrkar[$qgetexamsubjects[$n]['exam_id']][$qgetexamsubjects[$n]['sub_id']]=$qgetexamsubjects[$n]['sub_marks'];
							  if(!in_array($v,$arsubids)){
							  $arsubids[]=$qgetexamsubjects[$n]['sub_id'];
							  $arsubnames[$qgetexamsubjects[$n]['sub_id']]=$qgetexamsubjects[$n]['short_name'];
							  }
							   if(!in_array($qgetexamsubjects[$n]['exam_id'],$exam_idar)){
							   $exam_idar[]=$qgetexamsubjects[$n]['exam_id'];
							  
						 $exam_ids.=$qgetexamsubjects[$n]['exam_id'].',';
						 
						$g++;
						 }
		                 }
             $totalsub = $arsubids;	$g=0;
			 foreach($exam_idar as $ek=>$ev){
			 $exlen=(count($exam_subar[$ev])*2)+2;
			 $exnamearr['exam_name'][$g]=array('exam_name'=>$exnamarr[$ev],'totlen'=>$exlen);
			  foreach($exam_subar[$ev] as $subk=>$subidv){
			 $myarrays[$w][$k]=$arsubnames[$subidv].'-'.$exam_submxmrkar[$ev][$subidv];
						$k++;
						$myarrays[$w][$k]="Rank";
						$k++;
						}
						$k++;
						$myarrays[$w][$k]="ToT";
				   $k++;
						$myarrays[$w][$k]="Rank";
				    $k++;
					$g++;
			 }
		
		/* $getnumexams =  $obj_db->qry("select a.exam_name,a.dt_of_exam,a.exam_id from  ".TABLE_EXAM_NAMES." a,".TABLE_EXAM_TYPES." b   where  a.exam_type_id='".$_REQUEST['exam_type_id']."'  and a.year_id='".$_SESSION['year_id']."' and a.exam_type_id=b.exam_type_id   and a.exam_model=1 group by a.exam_id order by a.exam_id asc");
		
		 $exnamearr=array();
		 for($g=0; $g<count($getnumexams); $g++)
						{
						 $condexams.=$getnumexams[$g]['exam_name'].' ('.$getnumexams[$g]['dt_of_exam'].'),';
						 $exam_ids.=$getnumexams[$g]['exam_id'].',';
						 $exnamearr['exam_name'][$g]=array('exam_name'=>$getnumexams[$g]['exam_name'].' ('.$getnumexams[$g]['dt_of_exam'].')','totsubs'=>count($totalsub));
						}*/
						
						 $condexamids = substr($exam_ids,0,-1);
						$expcondexamids=explode(',',$condexamids);
			 
			  $std_compexams = $obj_db->qry("select marks,max_marks,sec_sub_rank,sub_id,student_id,exam_id,section_rank from ".TABLE_COMMPETETIVE_EXAM_MARKS."  where   year_id='".$_SESSION['year_id']."' and exam_id in(".$condexamids.") ");
			  $stidsar=array();
			  $stids='';
			  foreach($std_compexams as $k=>$v){
			  if(!in_array($v['student_id'],$stidsar)){
			  $stids.=$v['student_id'].',';
			  $stidsar[]=$v['student_id'];
			  }
			 }
			 $trmstids=substr($stids,0,-1);
 $get_stddtsqry=$obj_db->qry("select first_name,last_name,a.student_id,b.course_id,b.admission_no,course_short_name,branch_short_name from ".TABLE_STUDENTDETAILS." a,".TABLE_STUDENT_EDU_DETAILS." b,".TABLE_COURSE." c,".TABLE_BRANCH." d where   a.student_id=b.student_id and b.course_id=c.course_id and d.branch_id=b.branch_id and b.y_id='".$_SESSION['year_id']."' and a.student_id in(0".$trmstids.") order by cast(b.admission_no as unsigned) asc ");
 $w=0;
  for($m=0; $m<count($get_stddtsqry); $m++){
     $k=0;
	 $sno=$m+1;
	  $myarray[$w][$k]=$sno;
	  $k++;
    $myarray[$w][$k]=$get_stddtsqry[$m]['admission_no'];
	$k++;
	$myarray[$w][$k]=$get_stddtsqry[$m]['first_name'].' '.$get_stddtsqry[$m]['last_name'];
	 $k++;
    $myarray[$w][$k]=$get_stddtsqry[$m]['course_short_name'];
	$k++;
	$myarray[$w][$k]=$get_stddtsqry[$m]['branch_short_name'];
	
//	echo "select marks,max_marks,sec_sub_rank,sub_id from ".TABLE_COMMPETETIVE_EXAM_MARKS."  where student_id='".$get_stddtsqry[$w]['student_id']."' and year_id='".$_SESSION['year_id']."' limit 1 ";
   $stdexms_arkys=array_keys(array_column($std_compexams,'student_id'),$get_stddtsqry[$m]['student_id']);
   $stdexm_arkys=array();
    foreach($stdexms_arkys as $exk=>$exidv)
	 $stdexm_arkys[$exidv]=$std_compexams[$exidv]['exam_id'];
     foreach($expcondexamids as $ek=>$ev){
	 $tot_stmrks=0;$tot_exmrks=0;$k++;
	 if(in_array($ev,$stdexm_arkys)){
	 $exarky=array_search($ev,$stdexm_arkys);
	 $exp_subid=explode(',',$std_compexams[$exarky]['sub_id']);
	  $exp_mrks=explode(',',$std_compexams[$exarky]['marks']);
	  $exp_mxmrks=explode(',',$std_compexams[$exarky]['max_marks']);
	  $exp_surnks=explode(',',$std_compexams[$exarky]['sec_sub_rank']);
 	 foreach($exam_subar[$ev] as $sk=>$sv){
	//  $exp_mrks='';$exp_mxmrks='';$exp_mxmrks='';
	 
	  $exstsubarky=array_search($sv,$exp_subid);
	  $cnt=0;
	 // for($j=0;$j<count($exp_mrks);$j++){
	  $myarray[$w][$k]=$exp_mrks[$exstsubarky];
	  $k++;
	  $myarray[$w][$k]=$exp_surnks[$exstsubarky];
	  $k++;
	  $tot_stmrks=$tot_stmrks+$exp_mrks[$exstsubarky];
	  //}
	 }
	  $k++;
	  $myarray[$w][$k]=$tot_stmrks;
	   $k++;
	  $myarray[$w][$k]=$std_compexams[$exarky]['section_rank'];
	 }
	 else{
	  for($p=0;$p<count($exam_subar[$ev]);$p++){
	  $myarray[$w][$k]="";
	  $k++;
	  $myarray[$w][$k]="";
	  $k++;
	  }
	   $k++;
	  $myarray[$w][$k]="";
	   $k++;
	  $myarray[$w][$k]="";
	 }
	 } 
	 $k++;
	  $myarray[$w][$k]=count($stdexms_arkys)."/".count($expcondexamids);
	  $w++;
  }
 	 // echo '<pre>';print_r($myarray);echo '</pre>';
	 $fetch_data=array('sub_names'=>$myarrays,'std_avgmrks'=>$myarray,'exam_dts'=>$condexams,'no_exams'=>count($getnumexams),'extitle'=>$exnamearr);
  echo json_encode($fetch_data);
 }
?>