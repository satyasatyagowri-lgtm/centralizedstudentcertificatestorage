<?php session_start();
include "../DbConfig.php";
$data = json_decode(file_get_contents("php://input"));
$exp_courseid=explode('^',$_REQUEST['course_id']);
if($_REQUEST['action']=="std_compexamdts"){
	$exp_courseid=explode('^',$_REQUEST['course_id']);
	if($_REQUEST['set_promtecourse']=='yes')
	 $_SESSION['std_promote_course']=$_REQUEST['course_id'];
	 /*$qgetstddetails = $obj_db->fetchRow("select * from Student_Edu_Details  where course_model=1 and student_id='".$_SESSION['ang_stdid']."' and course_id='".$exp_courseid[0]."' ");
				$secid=	$qgetstddetails['sec_id'];*/
				$stdoveralexmrksordwise=array();$course_alstdalexms_arr=array();
				$extyparr=array(0,1);
				$secname=$obj_db->qry("select course_id,course_name,course_type_id,is_bipc,coursewise_ord from ".TABLE_COURSE."  ");
			   $coursenameky = array_search($exp_courseid[0], array_column($secname, 'course_id'));
			 $coursewiseord_kys = array_keys(array_column($secname, 'coursewise_ord'),$secname[$coursenameky]['coursewise_ord']);
			 $selcourseid_relatedcourses='';
			  foreach($coursewiseord_kys as $k=>$v)
			   $selcourseid_relatedcourses.=$secname[$v]['course_id'].',';
			   $trmrelated_courseids=substr($selcourseid_relatedcourses,0,-1);
	
	$gtexamtype=$obj_db->qry("select exam_type_name,c.exam_type_id,group_concat(distinct b.exam_id) as exids from ".TABLE_EXAM_COURSE_MAP." a,".TABLE_EXAM_NAMES." b,".TABLE_EXAM_TYPES." c where 
							a.course_id in(0".$trmrelated_courseids.")   and b.year_id='".$exp_courseid[1]."'  and b.exam_type_id=c.exam_type_id
							and a.exam_id=b.exam_id group by c.exam_type_id order by exam_type_order asc");
	
							$qgetsexamscount = "select b.*,DATE_FORMAT(STR_TO_DATE(b.dt_of_exam, '%d-%m-%Y'), '%d-%m') as doe from ".TABLE_EXAM_COURSE_MAP." a,".TABLE_EXAM_NAMES." b where a.course_id	in(0".$trmrelated_courseids.") and b.year_id='".$exp_courseid[1]."' and   a.exam_id=b.exam_id group by a.exam_id";
	 
							$rsgetsexamscounts = $obj_db->qry($qgetsexamscount);
	
							$qgetexamsubjects =  $obj_db->qry("select a.sub_id as subid,a.short_name as subname from ".TABLE_SUB_NAME." a order by a.sub_id");
				
						for($m=0; $m<count($gtexamtype); $m++)
						{
	
							$exmtypid=$gtexamtype[$m]['exam_type_id'];
					$rsgetsexamscount = array_filter($rsgetsexamscounts,function($v,$k) use ($exmtypid){
						return $v['exam_type_id'] == $exmtypid;
					  },ARRAY_FILTER_USE_BOTH);	  
		 $exmstdtyp_exmids=array();
		 foreach($rsgetsexamscount as $k=>$v){
		 $exmstdtyp_exmids[$v['exam_model']][]=$v['exam_id'];
		 }
	
		   $std_genexdts=array();$std_compexdts=array();$alexdts=array();
		   if(count($exmstdtyp_exmids[0])>0){
			 $genexids='';
			foreach($exmstdtyp_exmids[0] as $k=>$v)
			 $genexids.=$v.',';
			 $trmgenexids=substr($genexids,0,-1);
			$std_genexdts=$obj_db->qry("select student_id,exam_id,sub_id,max_marks,marks,section_rank,total_marks,course_id from ".TABLE_EXAM_RESULT." a where course_id in(".$trmrelated_courseids.") and exam_id in(".$trmgenexids.")");
			}
			if(count($exmstdtyp_exmids[1])>0){
			 $cmpexids='';
			foreach($exmstdtyp_exmids[1] as $k=>$v)
			 $cmpexids.=$v.',';
			 $trmcmpexids=substr($cmpexids,0,-1);
			$std_compexdts=$obj_db->qry("select student_id,exam_id,sub_id,max_marks,marks,section_rank,total_marks,course_id from ".TABLE_COMMPETETIVE_EXAM_MARKS." a where course_id in(".$trmrelated_courseids.") and exam_id in(".$trmcmpexids.")");
			}
			foreach($std_genexdts as $k=>$v){
			 array_push($alexdts,$v);
			 array_push($course_alstdalexms_arr,$v);
			}
			 foreach($std_compexdts as $k=>$v){
			 array_push($alexdts,$v);
			 array_push($course_alstdalexms_arr,$v);
		}
		$stid=$_SESSION['ang_stdid'];
	//	$stdexmrksordwise=array();
		$stdgenexmrrks = array_filter($std_genexdts,function($v,$k) use ($stid){
	  return $v['student_id'] == $stid;
	},ARRAY_FILTER_USE_BOTH);
	  foreach($stdgenexmrrks as $k=>$v){
	  // $stdexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>0,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);
	   $stdoveralexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>0,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);  
	}
	//$stdcomexmrksordwise=array();
	$stdcompexmrrks = array_filter($std_compexdts,function($v,$k) use ($stid){
	  return $v['student_id'] == $stid;
	},ARRAY_FILTER_USE_BOTH);
	foreach($stdcompexmrrks as $k=>$v){
		//   $stdexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>1,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);
	   $stdoveralexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>1,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);
		}
	   
	
	   
	 
	 
	 /*  $clstoppermrks='';$clsavgs='';$stdtmarks='';$totstmrks=0;
		$gpv=1;
		 for($p=0; $p<count($rsgetsexamscount); $p++){
		 $exmid=$rsgetsexamscount[$p]['exam_id'];
		   $stdxmrrks = array_filter($alexdts,function($v,$k) use ($exmid){
	  return $v['exam_id'] == $exmid;
	},ARRAY_FILTER_USE_BOTH);
		 $totstmrks=array_column($stdxmrrks, 'total_marks');
		 $exmmaxmrks=max(array_column($stdxmrrks, 'total_marks'));
		 $exmminmrks=max(array_column($stdxmrrks, 'total_marks'));
		 $exmminmrks=min(array_column($stdxmrrks, 'total_marks'));
		 
		 $exmarky=array_search($rsgetsexamscount[$p]['exam_id'],array_column($stdexmrksordwise,'exam_id'));
		
		//$examnumrows = $obj_db->fetchNum($qgetstudentmarks);
		
		if(is_numeric($exmarky)){
		$q=0;
		//$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$rsgetsexamscount[$p]['exam_name']);
		//$q++;
		$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$rsgetsexamscount[$p]['doe']);
		$q++;
		 $exp_mxmrks=explode(',',$stdexmrksordwise[$exmarky]['max_marks']);
		$subject = explode(',',$stdexmrksordwise[$exmarky]['sub_id']);
		 $mark = explode(',',$stdexmrksordwise[$exmarky]['marks']);
		 $maxmarks = explode(",",$stdexmrksordwise[$exmarky]['max_marks']);
		$x=0; 
		 //echo count($totalsub).'<br>';
		while($x<count($totalsub)){ 
		
	 
							$arr_serch=array_search($totalsub[$x],$subject);
							$mrkss=$mark[$arr_serch]."/".$maxmarks[$arr_serch];
	
							if(in_array($totalsub[$x],$subject))
							$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$mrkss);
							else $myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>'');
							$q++;$x++;
	}	
	$extot=round(array_sum($mark))."/".array_sum($maxmarks);
	$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>$extot);
	$q++;
	$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>$stdexmrksordwise[$exmarky]['section_rank']);
	
	$clstoppermrks.=round((($exmmaxmrks/array_sum($maxmarks))*100)).',';$clsavgs.=round((array_sum($totstmrks)/count($totstmrks)),2).',';
	$stdtmarks.=round((($stdexmrksordwise[$exmarky]['total_marks']/array_sum($maxmarks))*100));
	 $stdexmname.='"'.$rsgetsexamscount[$p]['doe'].'"'.',';
	
		 $myarray[$m]['typtopmrks'][]= round((($exmmaxmrks/array_sum($maxmarks))*100));
		 $myarray[$m]['typclsavgmrks'][]= round((array_sum($totstmrks)/count($totstmrks)),2);
		 $myarray[$m]['typminmrks'][]= round((($exmminmrks/array_sum($maxmarks))*100));
		 $myarray[$m]['typstdmrks'][]= round((($stdexmrksordwise[$exmarky]['total_marks']/array_sum($maxmarks))*100));	
		 $myarray[$m]['exam_names'][]= $rsgetsexamscount[$p]['doe']; 
			$gpv++;
						 }
		 }
		 $trmclstoppermrks=substr($clstoppermrks,0,-1);
		 $trmclsavgs=substr($clsavgs,0,-1);
		 $trmstdtmarks=substr($stdtmarks,0,-1);
		 $trmstdtexmnames=substr($stdexmname,0,-1);*/
						
	  }
	
	  $exmtypidarr=array();$exmtypstdwritesubs=array();
	  $exmtypnamear=array();$stdexmtypmrks=array();
	   foreach($stdoveralexmrksordwise as $stdovralexmk=>$stdovralexmv){
	   foreach($gtexamtype as $extypk=>$extypv){
		$exmtypstdwritesubs[$extypv['exam_type_id']].=$stdovralexmv['sub_id'].',';
		   $exptypexmids=explode(',',$extypv['exids']);
		   if(in_array($stdovralexmv['exam_id'],$exptypexmids) && !in_array($extypv['exam_type_id'],$exmtypidarr)){
			$extypid=$extypv['exam_type_id'];
			$exmtypidarr[]=$extypv['exam_type_id'];
			$exmtypnamear[$extypv['exam_type_id']]=$extypv['exam_type_name'];
			 //  $myarray[$m]['exam_type_name']= $extypv['exam_type_name'];						
	//		   $myarray[$m]['exam_type_id']= $extypv['exam_type_id'];
			}
	
	   }
	  // $stdexmtypmrks[$extypid][]=$stdovralexmv;
	  }
	//  echo '<pre>';print_r($stdexmtypmrks);echo '</pre>';
	$m=0;
	 foreach($exmtypidarr as $exmtypk=>$exmtypv)
	  {
		$myarray[$m]['exam_type_name']= $exmtypnamear[$exmtypv];						
			   $myarray[$m]['exam_type_id']= $exmtypv;
		$trm_expexmtypsusb=explode(',',substr($exmtypstdwritesubs[$exmtypv],0,-1));
		
		$exmtyp_subarr=array_unique($trm_expexmtypsusb);
		$n=0;$subid="";
		foreach($exmtyp_subarr as $exmtypsubk=>$exmtypsubv){
			$subky=array_search($exmtypsubv,array_column($qgetexamsubjects,'subid'));
		$myarray[$m]['exam_subs'][$n] = array("sub_id"=>$qgetexamsubjects[$subky]['subid'],"sub_name"=>$qgetexamsubjects[$subky]['subname']); 
		$n++;
	
		$subid.=$qgetexamsubjects[$subky]['subid'].',';
	}
	$subids = substr($subid,0,-1);
	$totalsub = explode(",",$subids);
	
	
		$clstoppermrks='';$clsavgs='';$stdtmarks='';$totstmrks=0;
		$exmtypid=$exmtypv;
		$rsgetsexamscount = array_filter($rsgetsexamscounts,function($v,$k) use ($exmtypid){
			return $v['exam_type_id'] == $exmtypid;
		  },ARRAY_FILTER_USE_BOTH);	  
		  $p=0;
		 foreach($rsgetsexamscount as $resexk=>$resexv){
		 $exmid=$resexv['exam_id'];
		   $stdxmrrks = array_filter($course_alstdalexms_arr,function($v,$k) use ($exmid){
	  return $v['exam_id'] == $exmid;
	},ARRAY_FILTER_USE_BOTH);
	    $exam_alstdovrmrks=array();
		foreach($stdxmrrks as $stdxmrrky=>$stdxmrrkv){
			$expmrkarray = explode(',',$stdxmrrkv['marks']);
 if (count( array_filter( $expmrkarray, 'is_numeric' ) )>0) 
      {
		$exam_alstdovrmrks[]=round((array_sum($expmrkarray)/array_sum(explode(',',$stdxmrrkv['max_marks'])))*100);
	  }
 		}
		 $totstmrks=array_column($stdxmrrks, 'total_marks');
		  $exmmaxmrks=max(array_column($stdxmrrks, 'total_marks'));
		 $exmminmrks=max(array_column($stdxmrrks, 'total_marks'));
		 $exmminmrks=min(array_column($stdxmrrks, 'total_marks'));
		 
		 $exmarky=array_search($resexv['exam_id'],array_column($stdoveralexmrksordwise,'exam_id'));
		
		if(is_numeric($exmarky)){
		$q=0;
		$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$resexv['doe']);
		$q++;
		 $exp_mxmrks=explode(',',$stdoveralexmrksordwise[$exmarky]['max_marks']);
		$subject = explode(',',$stdoveralexmrksordwise[$exmarky]['sub_id']);
		 $mark = explode(',',$stdoveralexmrksordwise[$exmarky]['marks']);
		 $maxmarks = explode(",",$stdoveralexmrksordwise[$exmarky]['max_marks']);
		$x=0; 
		while($x<count($totalsub)){ 
		
	 
							$arr_serch=array_search($totalsub[$x],$subject);
							$mrkss=$mark[$arr_serch]."/".$maxmarks[$arr_serch];
							if(in_array($totalsub[$x],$subject))
							$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$mrkss);
							else $myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>'');
							$q++;$x++;
	}	
	$extot=round(array_sum($mark))."/".array_sum($maxmarks);
	$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>$extot);
	$q++;
	$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>$stdoveralexmrksordwise[$exmarky]['section_rank']);
	$q++;
	$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>round($exmmaxmrks)."/".array_sum($maxmarks));
	$q++;
	$myarray[$m]['exam_type_marks'][$p]['exam_mrks'][$q]=array("submrks"=>round(array_sum($exam_alstdovrmrks)/count($exam_alstdovrmrks)).'%');
	$clstoppermrks.=round((($exmmaxmrks/array_sum($maxmarks))*100)).',';$clsavgs.=round((array_sum($totstmrks)/count($totstmrks)),2).',';
	$stdtmarks.=round((($stdoveralexmrksordwise[$exmarky]['total_marks']/array_sum($maxmarks))*100));
	 $stdexmname.='"'.$resexv['doe'].'"'.',';
	
		 $myarray[$m]['typtopmrks'][]= round((($exmmaxmrks/array_sum($maxmarks))*100));
		 $myarray[$m]['typclsavgmrks'][]= round(array_sum($exam_alstdovrmrks)/count($exam_alstdovrmrks));
		 $myarray[$m]['typminmrks'][]= round(array_sum($exam_alstdovrmrks)/count($exam_alstdovrmrks));
		 $myarray[$m]['typstdmrks'][]= round((($stdoveralexmrksordwise[$exmarky]['total_marks']/array_sum($maxmarks))*100));	
		 $myarray[$m]['exam_names'][]= $resexv['doe']; 
			$gpv++;
						 }
		$p++; }
		 $trmclstoppermrks=substr($clstoppermrks,0,-1);
		 $trmclsavgs=substr($clsavgs,0,-1);
		 $trmstdtmarks=substr($stdtmarks,0,-1);
		 $trmstdtexmnames=substr($stdexmname,0,-1);
		$m++;
	  }
	  echo json_encode($myarray);
	 }
	
	
	 elseif($_REQUEST['action']=="std_subwise_performnance"){
		$exp_courseid=explode('^',$_REQUEST['course_id']);
		if($_REQUEST['set_promtecourse']=='yes')
		 $_SESSION['std_promote_course']=$_REQUEST['course_id'];
		 /*$qgetstddetails = $obj_db->fetchRow("select * from Student_Edu_Details  where course_model=1 and student_id='".$_SESSION['ang_stdid']."' and course_id='".$exp_courseid[0]."' ");
					$secid=	$qgetstddetails['sec_id'];*/
					$stdoveralexmrksordwise=array();$course_alstdalexms_arr=array();
					$extyparr=array(0,1);
					$secname=$obj_db->qry("select course_id,course_name,course_type_id,is_bipc,coursewise_ord from ".TABLE_COURSE."  ");
				   $coursenameky = array_search($exp_courseid[0], array_column($secname, 'course_id'));
				 $coursewiseord_kys = array_keys(array_column($secname, 'coursewise_ord'),$secname[$coursenameky]['coursewise_ord']);
				 $selcourseid_relatedcourses='';
				  foreach($coursewiseord_kys as $k=>$v)
				   $selcourseid_relatedcourses.=$secname[$v]['course_id'].',';
				   $trmrelated_courseids=substr($selcourseid_relatedcourses,0,-1);
		
		$gtexamtype=$obj_db->qry("select exam_type_name,c.exam_type_id,group_concat(distinct b.exam_id) as exids from ".TABLE_EXAM_COURSE_MAP." a,".TABLE_EXAM_NAMES." b,".TABLE_EXAM_TYPES." c where 
								a.course_id in(0".$trmrelated_courseids.")   and b.year_id='".$exp_courseid[1]."'  and b.exam_type_id=c.exam_type_id
								and a.exam_id=b.exam_id group by c.exam_type_id order by exam_type_order asc");
		
								$qgetsexamscount = "select b.*,DATE_FORMAT(STR_TO_DATE(b.dt_of_exam, '%d-%m-%Y'), '%d-%m') as doe from ".TABLE_EXAM_COURSE_MAP." a,".TABLE_EXAM_NAMES." b where a.course_id	in(0".$trmrelated_courseids.") and b.year_id='".$exp_courseid[1]."' and   a.exam_id=b.exam_id group by a.exam_id";
		 
								$rsgetsexamscounts = $obj_db->qry($qgetsexamscount);
	
								$qgetexamsubjects =  $obj_db->qry("select a.sub_id as subid,a.short_name as subname from ".TABLE_SUB_NAME." a order by a.sub_id");
					
							for($m=0; $m<count($gtexamtype); $m++)
							{
		
							$exmtypid=$gtexamtype[$m]['exam_type_id'];
						$rsgetsexamscount = array_filter($rsgetsexamscounts,function($v,$k) use ($exmtypid){
							return $v['exam_type_id'] == $exmtypid;
						  },ARRAY_FILTER_USE_BOTH);	  
			 $exmstdtyp_exmids=array();
			 foreach($rsgetsexamscount as $k=>$v){
			 $exmstdtyp_exmids[$v['exam_model']][]=$v['exam_id'];
			 }
		
			   $std_genexdts=array();$std_compexdts=array();$alexdts=array();
			   if(count($exmstdtyp_exmids[0])>0){
				 $genexids='';
				foreach($exmstdtyp_exmids[0] as $k=>$v)
				 $genexids.=$v.',';
				 $trmgenexids=substr($genexids,0,-1);
				$std_genexdts=$obj_db->qry("select student_id,exam_id,sub_id,max_marks,marks,section_rank,total_marks,course_id from ".TABLE_EXAM_RESULT." a where course_id in(".$trmrelated_courseids.") and exam_id in(".$trmgenexids.")");
				}
				if(count($exmstdtyp_exmids[1])>0){
				 $cmpexids='';
				foreach($exmstdtyp_exmids[1] as $k=>$v)
				 $cmpexids.=$v.',';
				 $trmcmpexids=substr($cmpexids,0,-1);
				$std_compexdts=$obj_db->qry("select student_id,exam_id,sub_id,max_marks,marks,section_rank,total_marks,course_id from ".TABLE_COMMPETETIVE_EXAM_MARKS." a where course_id in(".$trmrelated_courseids.") and exam_id in(".$trmcmpexids.")");
				}
				foreach($std_genexdts as $k=>$v){
				 array_push($alexdts,$v);
				 array_push($course_alstdalexms_arr,$v);
				}
				 foreach($std_compexdts as $k=>$v){
				 array_push($alexdts,$v);
				 array_push($course_alstdalexms_arr,$v);
			}
			$stid=$_SESSION['ang_stdid'];
			//$stdexmrksordwise=array();
			$stdgenexmrrks = array_filter($std_genexdts,function($v,$k) use ($stid){
		  return $v['student_id'] == $stid;
		},ARRAY_FILTER_USE_BOTH);
		  foreach($stdgenexmrrks as $k=>$v){
		   //$stdexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>0,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);
		   $stdoveralexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>0,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);  
		}
		//$stdcomexmrksordwise=array();
		$stdcompexmrrks = array_filter($std_compexdts,function($v,$k) use ($stid){
		  return $v['student_id'] == $stid;
		},ARRAY_FILTER_USE_BOTH);
		foreach($stdcompexmrrks as $k=>$v){
			 //  $stdexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>1,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);
		   $stdoveralexmrksordwise[]=array('student_id'=>$v['student_id'],'exam_id'=>$v['exam_id'],'exam_model'=>1,'sub_id'=>$v['sub_id'],'max_marks'=>$v['max_marks'],'marks'=>$v['marks'],'section_rank'=>$v['section_rank'],'total_marks'=>$v['total_marks'],'course_id'=>$v['course_id']);
			}
		}
		
		  $exmtypidarr=array();$exmtypstdwritesubs=array();
		  $exmtypnamear=array();$stdexmtypmrks=array();
		   foreach($stdoveralexmrksordwise as $stdovralexmk=>$stdovralexmv){
		   foreach($gtexamtype as $extypk=>$extypv){
			$exmtypstdwritesubs[$extypv['exam_type_id']].=$stdovralexmv['sub_id'].',';
			   $exptypexmids=explode(',',$extypv['exids']);
			   if(in_array($stdovralexmv['exam_id'],$exptypexmids) && !in_array($extypv['exam_type_id'],$exmtypidarr)){
				$extypid=$extypv['exam_type_id'];
				$exmtypidarr[]=$extypv['exam_type_id'];
				$exmtypnamear[$extypv['exam_type_id']]=$extypv['exam_type_name'];
				 }
		
		   }
		   $stdexmtypmrks[$extypid][]=$stdovralexmv;
		  }
		$m=0;
		$exmtyp_alexmsubmxmrksdts=array();
		$exmtyp_alexmsubstdmrksdts=array();
		$exmtypedts=array();$b=0;
		foreach($exmtypidarr as $exmtypk=>$exmtypv)
		  {
			$exmtypedts[]=array('exam_type_id'=>$exmtypv,'exam_type_name'=>$exmtypnamear[$exmtypv]);
		   $b++;
		}  
		foreach($exmtypidarr as $exmtypk=>$exmtypv)
		  {
			$myarray[$exmtypv]['exam_type_name']= $exmtypnamear[$exmtypv];						
				   $myarray[$exmtypv]['exam_type_id']= $exmtypv;
			$trm_expexmtypsusb=explode(',',substr($exmtypstdwritesubs[$exmtypv],0,-1));
			
			$exmtyp_subarr=array_unique($trm_expexmtypsusb);
			$n=0;$subid="";
			foreach($exmtyp_subarr as $exmtypsubk=>$exmtypsubv){
				$subky=array_search($exmtypsubv,array_column($qgetexamsubjects,'subid'));
			$myarray[$exmtypv]['exam_subs'][$n] = array("sub_id"=>$qgetexamsubjects[$subky]['subid'],"sub_name"=>$qgetexamsubjects[$subky]['subname']); 
			$n++;
		
			$subid.=$qgetexamsubjects[$subky]['subid'].',';
		}
		$subids = substr($subid,0,-1);
		$totalsub = explode(",",$subids);
		
		
			$clstoppermrks='';$clsavgs='';$stdtmarks='';$totstmrks=0;
			$exmtypid=$exmtypv;
			$rsgetsexamscount = array_filter($rsgetsexamscounts,function($v,$k) use ($exmtypid){
				return $v['exam_type_id'] == $exmtypid;
			  },ARRAY_FILTER_USE_BOTH);	  
			  $p=0;
			 foreach($rsgetsexamscount as $resexstk=>$resexmstv){
			 $exmid=$resexmstv['exam_id'];
			   $stdxmrrks = array_filter($course_alstdalexms_arr,function($v,$k) use ($exmid){
		  return $v['exam_id'] == $exmid;
		},ARRAY_FILTER_USE_BOTH);
	
		foreach($stdxmrrks as $stdmrksky=>$stdmrksv){
			$expsubids=explode(',',$stdmrksv['sub_id']);
			$expsubmxmrks=explode(',',$stdmrksv['max_marks']);
			$subject = explode(',',$stdmrksv['sub_id']);
			 $expsubmark = explode(',',$stdmrksv['marks']);
		   foreach($totalsub as $subk=>$subv){
			$subky=array_search($subv,$expsubids);
			if(is_numeric($subky) && is_numeric($expsubmark[$subky])){
				$exmtyp_alexmsubmxmrksdts[$exmtypid][$subv][$stdmrksv['exam_id']][]=$expsubmxmrks[$subky];
				$exmtyp_alexmsubstdmrksdts[$exmtypid][$subv][$stdmrksv['exam_id']][]=$expsubmark[$subky];
			}
		   // exmtyp_alexmsubmxmrksdts,exmtyp_alexmsubstdmrksdts
		   }
		}
			 
			 $exmarky=array_search($resexmstv['exam_id'],array_column($stdoveralexmrksordwise,'exam_id'));
			
			if(is_numeric($exmarky)){
			//$q=0;
			//$myarray[$exmtypv]['exam_type_marks'][$p]['exam_mrks'][$q] = array("submrks"=>$resexmstv['doe']);
			$q++;
			$subject = explode(',',$stdoveralexmrksordwise[$exmarky]['sub_id']);
			 $mark = explode(',',$stdoveralexmrksordwise[$exmarky]['marks']);
			 $maxmarks = explode(",",$stdoveralexmrksordwise[$exmarky]['max_marks']);
			$x=0; 
			while($x<count($totalsub)){ 
				$arr_serch=array_search($totalsub[$x],$subject);
				$subexm_stdalmrks=array();
				foreach($exmtyp_alexmsubstdmrksdts[$exmtypv][$totalsub[$x]][$resexmstv['exam_id']] as $exstmrky=>$exstmrkv){
					if(is_numeric($exstmrkv))
                   $subexm_stdalmrks[]=round(($exstmrkv/$maxmarks[$arr_serch])*100);
				}
                // $exmsubavg=round((array_sum($subexm_stdalmrks)/(count($subexm_stdalmrks)*$maxmarks[$arr_serch]))*100);
				$exmsubavg=round(array_sum($subexm_stdalmrks)/count($subexm_stdalmrks));
				//exmtyp_alexmsubstdmrksdts
				$myarray[$exmtypv][$totalsub[$x]]['exam_mrks'][$p][$q] = array("submrks"=>$resexmstv['doe']);
				$q++;
								
								$mrkss=$mark[$arr_serch]."/".$maxmarks[$arr_serch];
								if(in_array($totalsub[$x],$subject))
								$myarray[$exmtypv][$totalsub[$x]]['exam_mrks'][$p][$q] = array("submrks"=>$mrkss);
								else $myarray[$exmtypv][$totalsub[$x]]['exam_mrks'][$p][$q]=array("submrks"=>'');
								$q++;

							   $subclsmxmrs=max($exmtyp_alexmsubstdmrksdts[$exmtypv][$totalsub[$x]][$resexmstv['exam_id']]);
							   $subclsminrs=min($exmtyp_alexmsubstdmrksdts[$exmtypv][$totalsub[$x]][$resexmstv['exam_id']]);
								$myarray[$exmtypv][$totalsub[$x]]['exam_mrks'][$p][$q]=array("submrks"=>$subclsmxmrs.'/'.$maxmarks[$arr_serch]);
								$q++;
								$myarray[$exmtypv][$totalsub[$x]]['exam_mrks'][$p][$q]=array("submrks"=>$exmsubavg.'%');
								
	
								$myarray[$exmtypv][$totalsub[$x]]['typtopmrks'][]= round((($subclsmxmrs/$maxmarks[$arr_serch])*100));
								$myarray[$exmtypv][$totalsub[$x]]['typclsavgmrks'][]= round((array_sum($totstmrks)/count($totstmrks)),2);
								$myarray[$exmtypv][$totalsub[$x]]['typminmrks'][]= $exmsubavg;
								//$myarray[$exmtypv][$totalsub[$x]]['typminmrks'][]= round((($subclsminrs/$maxmarks[$arr_serch])*100));
								$myarray[$exmtypv][$totalsub[$x]]['typstdmrks'][]= round((($mark[$arr_serch]/$maxmarks[$arr_serch])*100));	
								$myarray[$exmtypv][$totalsub[$x]]['exam_names'][]= $resexmstv['doe'];
								$x++;
		}	
		
		$clstoppermrks.=round((($exmmaxmrks/array_sum($maxmarks))*100)).',';$clsavgs.=round((array_sum($totstmrks)/count($totstmrks)),2).',';
		$stdtmarks.=round((($stdoveralexmrksordwise[$exmarky]['total_marks']/array_sum($maxmarks))*100));
		 $stdexmname.='"'.$resexmstv['doe'].'"'.',';
		
			
							 }
							 $p++;
			 }
			 $trmclstoppermrks=substr($clstoppermrks,0,-1);
			 $trmclsavgs=substr($clsavgs,0,-1);
			 $trmstdtmarks=substr($stdtmarks,0,-1);
			 $trmstdtexmnames=substr($stdexmname,0,-1);
			$m++;
		  }
		  //echo '<pre>';print_r($myarray[1][12]);echo '</pre>';
		  //echo '<pre>';print_r($myarray);echo '</pre>';
		  echo json_encode(array('myarray'=>$myarray,'exmtypedts'=>$exmtypedts));
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