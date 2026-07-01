<?php  session_start();
require_once("../../includes/DbConfig.php");
  /* Foundation Table Insert Update Delete Opertation Start*/ 
if($_REQUEST['action']=='get_organization'){
	    	 
    		 $org_query=$obj_db->qry("SELECT * FROM ".TABLE_BRANCH." a   where a.org_id='".$_REQUEST['org_id']."'  
			ORDER BY a.branch_id desc");
			 $org_courses=$obj_db->qry("SELECT * FROM ".TABLE_COURSE." a   where a.org_id='".$_REQUEST['org_id']."'  
			ORDER BY a.course_id asc");
			
			$brntmp='<option value="">--Select Branch--</option>';
			foreach($org_query as $k=>$v) { 
					$brntmp.='<option value='.$v['branch_id'].'>'.$v['branch_short_name'].'</option>';
					 }
			
			$coursetmp='<option value="">--Select Branch--</option>';
			foreach($org_courses as $k=>$v) { 
					$coursetmp.='<option value='.$v['course_id'].'>'.$v['course_name'].'</option>';
					 }
			 echo $brntmp.'^'.$coursetmp;
		
   		}
   		elseif($_REQUEST['action']=='get_course'){
			if(isset($_REQUEST['gen_comp_course'])){$var="";}
			else{$var="and course_model=1";}
				  $found_del_query="SELECT * FROM ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b  where b.branch_id='".$_REQUEST['id']."' $var and a.course_id=b.course_id";
				 $res=$obj_db->get_qresult($found_del_query);
				 $tmp='<option value="">--Select Course--</option>';
				 while($course_select_rows=$obj_db->fetchArray($res)) { 
						 $tmp.='<option value='.$course_select_rows['course_id'].'>'.$course_select_rows['course_name'].'</option>';
						  }
				  echo $tmp;
			 
				}
   		elseif($_REQUEST['action']=='get_coursesubjectss'){
			$subnames=$obj_db->qry("SELECT b.* FROM ".TABLE_SUB_TO_COURSE." a,".TABLE_SUB_NAME." b,".TABLE_COURSE." c where  a.sub_id=b.sub_id and
			a.course_id='".$_REQUEST['course_id']."' and a.course_id=c.course_id order by b.sub_id asc");
			$coursetmp='<option value="">--Select Branch--</option>';
			foreach($subnames as $k=>$v) 
					$coursetmp.='<option value='.$v['sub_id'].'>'.$v['sub_name'].'</option>';
					 echo $coursetmp;
		}
		
		elseif($_REQUEST['action']=='get_course_activity_and_subjects'){

			$subnames=$obj_db->qry("SELECT b.* FROM ".TABLE_SUB_TO_COURSE." a,".TABLE_SUB_NAME." b,".TABLE_COURSE." c where  a.sub_id=b.sub_id and
			a.course_id='".$_REQUEST['course_id']."' and c.org_id in(".$_SESSION['assign_org_ids'].") and a.course_id=c.course_id order by b.sub_id asc");
 			foreach($subnames as $subk=>$subv) {

				$coursetmp.='<div class="row  justify-content-center">
					 <div class="col-md-6">
                       <div class="form-group  m-b-0">
					   <input class="form-control  form-control-sm " title="Date" type="hidden" name="sub_id[]" value="'.$subv['sub_id'].'" />
					<input class="form-control  form-control-sm " title="Date" type="hidden" name="sub_name[]" value="'.$subv['sub_name'].'" />
					<input class="form-control  form-control-sm " title="Date" type="hidden" name="is_activity" value="0" />
                       <label for="recipient-name" class="col-form-label">'.$subv['sub_name'].'<b style="color:#FF0000;"></b> </label>
 						<textarea class="form-control valid form-control-sm " title="Description"   name="description[]" ></textarea>
 						<div id="error" class="error"></div>
 						</div>
 					</div>
				  </div>';
            }
			$stdactivityqry=$obj_db->qry("SELECT * FROM ".TABLE_STUDENT_ACTIVITIES."   ");
			foreach($stdactivityqry as $subk=>$subv) {

				$coursetmp.='<div class="row  justify-content-center">
					 <div class="col-md-6">
                       <div class="form-group  m-b-0">
					   <input class="form-control  form-control-sm " title="Date" type="hidden" name="sub_id[]" value="'.$subv['activity_id'].'" />
					<input class="form-control  form-control-sm " title="Date" type="hidden" name="sub_name[]" value="'.$subv['activity_name'].'" />
					<input class="form-control  form-control-sm " title="Date" type="hidden" name="is_activity" value="0" />
                       <label for="recipient-name" class="col-form-label">'.$subv['activity_name'].'<b style="color:#FF0000;"></b> </label>
 						<textarea class="form-control valid form-control-sm " title="Description"   name="description[]" ></textarea>
 						<div id="error" class="error"></div>
 						</div>
 					</div>
				  </div>';
            }
 			
					 echo $coursetmp;
		}
		 elseif($_REQUEST['action']=='get_branch_course'){
		 	        $course_query=$obj_db->qry("SELECT * FROM ".TABLE_COURSE." a where a.org_id='".$_REQUEST['org_id']."' and course_id not in(select course_id from course_branch_map where branch_id='".$_REQUEST['branch_id']."') order by a.course_id asc");

        foreach($course_query as $k=>$v){
         $ctmp.='<div class="form-group col-sm-6">		
											<div class="form-check">
											 <input type="checkbox" aria-label="Checkbox for following text input"  name="course_id[]" id="mmenu_id'.$v['course_id'].'" class="  alcourses"   value="'.$v['course_id'].'"   >
											 <label class="form-check-label" for="mmenu_id'.$v['course_id'].'">'.$v['course_name'].'</label>
											 </div> 
											 </div>';
          }
			 echo $ctmp;
   		}

	/*instute wise all courses*/	
	 elseif($_REQUEST['action']=='get_all_courses'){
	       $gen_course="SELECT * FROM ".TABLE_COURSE." where   org_id='".$_REQUEST['org_id']."' and course_id not in(select course_id from ".TABLE_SUB_TO_COURSE." where
		  org_id='".$_REQUEST['org_id']."') and course_model=1  ORDER BY course_id desc";
			$res=$obj_db->get_qresult($gen_course);
			
			$ctmp='<option value="">--Select Courses--</option>';
			
			while($course_select_rows=$obj_db->fetchArray($res)) { 
					$ctmp.='<option value='.$course_select_rows['course_id'].'>'.$course_select_rows['course_name'].'</option>';
					 }
	 /*instute wise all subjects*/
		  $sub_name=$obj_db->qry("SELECT * FROM ".TABLE_SUB_NAME." where   org_id='".$_REQUEST['org_id']."'");
 			foreach($sub_name as $k=>$v) { 
				$tmp.='  <div class="form-group col-sm-6">		
											<div class="form-check">
             <input type="checkbox" title="Checbox" aria-label="Checkbox for following text input"  class="  alsubs" name="sub_id[]" value="'.$v['sub_id'].'">
			  <label class="form-check-label" for="sub_id'.$v['sub_id'].'">'.$v['sub_name'].'</label>
											 </div> 
											 </div>';
					 }
					 $tmp.='<div id="cherror" class="error"></div>';
	 
	     echo $tmp.'^'.$ctmp;
		
   		}
		
		 elseif($_REQUEST['action']=='get_brncourses'){
    	 $course_sel=$obj_db->qry("SELECT a.* FROM ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b  where b.branch_id='".$_REQUEST['branch_id']."' and course_model=1 and a.course_id=b.course_id order by a.course_id asc");
 			$tmp='<option value="">--Select Course--</option>';
			foreach($course_sel as $k=>$v) { 
					$tmp.='<option value='.$v['course_id'].'>'.$v['course_name'].'</option>';
					 }
 echo $tmp;
 }
		/*branchwise course fees*/	
	 elseif($_REQUEST['action']=='get_branwisecourse_fees'){
            if($_REQUEST['pay_type']==1){$cnd=" and month_term_status=1";}else{$cnd="";} 
    	$fee_sel=$obj_db->qry("select * from ".TABLE_FEE_TYPE." where is_tution=1 $cnd and fee_id not in(select fee_id from ".TABLE_FEE_COURSE_MAP." where 
		course_id=".$_REQUEST['course_id']."  and branch_id=".$_REQUEST['branch_id']."  )");
 			  for($i=1;$i<=12;$i++){ 
					 $trm.='<option value="'.$i.'"  >Term-'.$i.'</option>';
				  } $numvar="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');";
				  
				  
				  
			foreach($fee_sel as $k=>$v){ 
			if($v['fee_short_name']=="BUF" || $v['fee_short_name']=="NAT" || $v['fee_short_name']=='FN' || $v['fee_short_name']=='VF' || $v['fee_short_name']=='ODF'){
				   $varr="style='display:none;'";
				   $camtval='0';
				  }else{
				   $varr="style='display:block;'";
				   $camtval='';
				  }
			$tmp.='<tr>
					<td><div class="form-group  m-b-0">
                      <div class="form-check">
										<input class="alftyp" aria-label="Checkbox for following text input" id="cfeid'.$v['fee_id'].'" name="feeid[]" onClick="get_feetypests('.$v['fee_id'].');" value="'.$v['fee_id'].'"  type="checkbox">
										 <label class="form-check-label" for="cfeid'.$v['fee_id'].'">'.$v['fee_name'].'</label>
										 <input type="hidden" id="fshortname_'.$v['fee_id'].'" value="'.$v['fee_short_name'].'" >
 									</div>
                  </div></td>
				  <td>';
				 /* if($v['fee_short_name']=="BUF" || $v['fee_short_name']=="NAT" || $v['fee_short_name']=='FN' || $v['fee_short_name']=='VF' || $v['fee_short_name']=='ODF'){
				  $tmp.='';
				  }else{*/
				  $tmp.='<div class="form-group" '.$varr.'>
					<label for="recipient-name" class="col-form-label">Amount<b style="color:#FF0000;">*</b> </label>
                    <input class="form-control form-control-sm alcoursefe coursefe'.$v['fee_id'].'"  disabled="disabled" oninput="'.$numvar.'"  title="Amount" autocomplete="off" id="course_fee_'.$v['fee_id'].'"  onKeyUp="checknumber('.$v['fee_id'].',this.value)" name="coursefe_'.$v['fee_id'].'" type="text" value="'.$camtval.'"   />
					<div id="error" class="error fxamt'.$v['fee_id'].'"></div>
                    </div>
					<div class="form-group" '.$varr.'>
					<label for="recipient-name" class="col-form-label">Concession<b style="color:#FF0000;">*</b> </label>
                    <input class="form-control form-control-sm  alcourseconce coursecon'.$v['fee_id'].'"   disabled="disabled" oninput="'.$numvar.'"  title="" autocomplete="off" id="conces_'.$v['fee_id'].'"   onKeyUp="alfeconcess_amountstatus('.$v['fee_id'].',this.value)" name="conces_'.$v['fee_id'].'" type="text"   />
					<div id="error" class="error concer'.$v['fee_id'].'"></div>
                    </div>';
					//}
					$tmp.='</td>
					
					<td>
				 <select name="term_'.$v['fee_id'].'" class="form-control form-control-sm  m-bot5 alcoursefe coursefe'.$v['fee_id'].'" disabled="disabled"     title="Terms" id="noterms_'.$v['fee_id'].'" onChange="alfeno_terms('.$v['fee_id'].',this.value);" >
					<option value="">--Terms--</option>'.$trm.'
					</select>
					</td>
					
					<td id="feetrm_'.$v['fee_id'].'" class="alfeterms">
					</td>
					</tr>';
					}
 		
					 echo $tmp;
		}
		
 		elseif($_REQUEST['action']=='eachcoursefe_terms'){
		  if($_REQUEST['fee_short_name']=="BUF" || $_REQUEST['fee_short_name']=="NAT" || $_REQUEST['fee_short_name']=='FN' || $_REQUEST['fee_short_name']=='VF' || $_REQUEST['fee_short_name']=='ODF'){
				   $varr="style='display:none;'";
				   $camtval='0';
				  }else{
				   $varr="style='display:block;'";
				   $camtval='';
				  }
		$numvar="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');";
		for($i=1;$i<=$_REQUEST['noterms'];$i++){
		$tmp.='<tr><td><div class="form-group"> <label for="recipient-name" class="col-form-label">Term-'.$i.' Date<b style="color:#FF0000;">*</b> </label> 
		              <input class="form-control form-control-sm valid default-date-picker" title="Date" autocomplete="off"    name="trmdt_'.$_REQUEST['sfeid'].'_'.$i.'" type="text"   />
					<div id="error" class="error"></div></div></td>';
					/*if($_REQUEST['fee_short_name']=="BUF" || $_REQUEST['fee_short_name']=="NAT" || $_REQUEST['fee_short_name']=='FN' || $_REQUEST['fee_short_name']=='VF' || $_REQUEST['fee_short_name']=='ODF'){
					$tmp.='';
					}else{*/
                    $tmp.='<td><div class="form-group" '.$varr.' >
					<label for="recipient-name" class="col-form-label">Term-'.$i.' Amount<b style="color:#FF0000;">*</b> </label> 
                    <input class="form-control form-control-sm valid coursetrmfes'.$_REQUEST['sfeid'].'" title="Term Amount"  autocomplete="off" id="conces"  oninput="'.$numvar.'" name="trmamt_'.$_REQUEST['sfeid'].'_'.$i.'" type="text" value="'.$camtval.'"  />
					<div id="error" class="error trmamter'.$_REQUEST['sfeid'].'"></div></div></td></tr>';
					//}
		}
		echo $tmp;
		}
 		
		
		
		 elseif($_REQUEST['action']=='get_branwisecourse_fees_mapping'){
 			$coursedts=$obj_db->qry("select b.* from ".TABLE_COURSE_BRANCH_MAP." a,".TABLE_COURSE." b where a.course_id=b.course_id and a.branch_id='".$_REQUEST['branch_id']."' and b.course_id not in(select course_id from ".TABLE_YEARLY_COURSEFEES_MAPPING." where branch_id='".$_REQUEST['branch_id']."' and year_id='".$_REQUEST['year_id']."')");
    	$fee_sel=$obj_db->qry("select * from ".TABLE_FEE_TYPE."  ");
 			 
			
				  
				  
			foreach($coursedts as $k=>$v){ 
			
			$tmp.='<tr>
					<td><div class="form-group  m-b-0">
                      <div class="form-check">
										 <label class="form-check-label" >'.$v['course_name'].'<input type="hidden" name="courseids[]" value="'.$v['course_id'].'"></label>
  									</div>
                  </div></td>
				  <td><div class="form-group  m-b-0">
                      <div class="form-check">
										<input class="alftyp" aria-label="Checkbox for following text input" id="courseid'.$v['course_id'].'"  onClick="get_courses('.$v['course_id'].');" value="'.$v['course_id'].'"  type="checkbox">
										 <label class="form-check-label" for="courseid'.$v['course_id'].'">All</label>
  									</div>
                  </div></td>
				  <td>';
				  $cfes='';
				   foreach($fee_sel as $fk=>$fv){
			 $cfes.='<div class="form-group  m-b-0">
                      <div class="form-check">
										<input  aria-label="Checkbox for following text input" id="fee_id'.$v['course_id'].$fv['fee_id'].'" name="fid['.$v['course_id'].'][]" onClick="get_feetypests('.$v['course_id'].');" value="'.$fv['fee_id'].'" class="alftyp coursefees'.$v['course_id'].'"  type="checkbox">
										 <label class="form-check-label" for="fee_id'.$v['course_id'].$fv['fee_id'].'">'.$fv['fee_name'].'</label>
  									</div>
                  </div>';
			 }
				  $tmp.=$cfes.'</td>
					</tr>';
					}
 		
					 echo $tmp;
		}
		
		 elseif($_REQUEST['action']=='get_yearly_sec_mapping'){
		    $coursedts=$obj_db->qry("select b.* from ".TABLE_COURSE_BRANCH_MAP." a,".TABLE_COURSE." b where a.course_id=b.course_id and a.branch_id='".$_REQUEST['branch_id']."' and b.course_id not in(select course_id from ".TABLE_YEARLY_SECTION_MAPPING." where branch_id='".$_REQUEST['branch_id']."' and year_id='".$_REQUEST['secyear_id']."')");
    	$secdts=$obj_db->qry("select * from ".TABLE_SECTION." where is_delete=0 and branch_id='".$_REQUEST['branch_id']."'  ");
				  
			foreach($coursedts as $k=>$v){ 
			
			$tmp.='<tr>
					<td><div class="form-group  m-b-0">
                      <div class="form-check">
										 <label class="form-check-label" >'.$v['course_name'].'<input type="hidden" name="courseids[]" value="'.$v['course_id'].'"></label>
  									</div>
                  </div></td>
				  <td><div class="form-group  m-b-0">
                      <div class="form-check">
										<input class="alftyp" aria-label="Checkbox for following text input" id="courseid'.$v['course_id'].'"  onClick="get_courses('.$v['course_id'].');" value="'.$v['course_id'].'"  type="checkbox">
										 <label class="form-check-label" for="courseid'.$v['course_id'].'">All</label>
  									</div>
                  </div></td>
				  <td>';
				  $cfes='';
				  $coruseid=$v['course_id'];
				  $sec_dts = array_filter($secdts,function($v,$k) use ($coruseid){
  return $v['course_id'] == $coruseid;
},ARRAY_FILTER_USE_BOTH);

				   foreach($sec_dts as $fk=>$fv){
			 $cfes.='<div class="form-group  m-b-0">
                      <div class="form-check">
										<input  aria-label="Checkbox for following text input" id="sec_id'.$v['course_id'].$fv['sec_id'].'" name="secid['.$v['course_id'].'][]" onClick="get_secypests('.$v['course_id'].');" value="'.$fv['sec_id'].'" class="alftyp coursesections'.$v['course_id'].'"  type="checkbox">
										 <label class="form-check-label" for="sec_id'.$v['course_id'].$fv['sec_id'].'">'.$fv['sec_name'].'</label>
  									</div>
                  </div>';
			 }
				  $tmp.=$cfes.'</td>
					</tr>';
					}
 		
					 echo $tmp;
		 }
		
		 elseif($_REQUEST['action']=='get_yearly_brnroute_mapping'){
 
		    $yrroutedts=$obj_db->qry("select * from ".TABLE_YEARLY_BUSROUTE_MAPPING." where branch_id='".$_REQUEST['branch_id']."' and year_id='".$_SESSION['year_id']."'");
			if(count($yrroutedts)==0){
 
    	$routedts=$obj_db->qry("select * from ".TABLE_BUS_ROUTES." where status=1 and branch_id in(".$_SESSION['assign_branch_ids'].")  ");
		foreach($routedts as $k=>$v){
		
		  $tmp.='<div class="form-group col-sm-6">		
											<div class="form-check">
											<input  aria-label="Checkbox for following text input" id="routeid'.$v['route_id'].'" name="route_id[]"   value="'.$v['route_id'].'"   class="alroutdts routedts"  type="checkbox">
										 <label class="form-check-label" for="routeid'.$v['route_id'].'">'.$v['route_name'].'</label>
											 </div> 
											 </div>';
		}
		  }
				  
 		
					 echo $tmp;
		 }
		
		 elseif($_REQUEST['action']=='get_fee_terms'){
	        $fee_type_exp=explode('^',$_REQUEST['fee_id']);
		    $fee_type_qry="SELECT * FROM ".TABLE_FEE_TYPE."  where   fee_id='".$fee_type_exp[0]."'";
			$fee_type_row=$obj_db->fetchRow($fee_type_qry);
			if($fee_type_row['fee_short_name']=="BUF" || $fee_type_row['fee_short_name']=="NAT" || $fee_type_row['fee_short_name']=='FN' || $fee_type_row['fee_short_name']=='VF' || $fee_type_row['fee_short_name']=='ODF'){
					   $varr="style='display:none;'";
					   }
				else{
				       $varr="style='display:block;'";
				}
		 
		    $tmp='<h4 class="form-section"><i class="ft-info"></i> TERM DETAILS</h4>
								
								<div class="row">
	                    			<div class="col-md-4">
				                        <div class="form-group row" >
										<label for="recipient-name" class="col-form-label">DUE DATE<b style="color:#FF0000;">*</b> </label>
 				                        	
				                        </div>
				                    </div>
				                  
									<div class="col-md-4" '.$varr.'>
				                         <div class="form-group row">
										 <label for="recipient-name" class="col-form-label">TERM PAID AMOUNT<b style="color:#FF0000;">*</b> </label>
 				                        	
				                        </div>
			                        </div>
		                        </div>';
		 
					 for($t=0;$t<$_REQUEST['id'];$t++){
					 
			if($fee_type_row['fee_short_name']=="BUF" || $fee_type_row['fee_short_name']=="NAT" || $fee_type_row['fee_short_name']=='FN' || $fee_type_row['fee_short_name']=='VF' || $fee_type_row['fee_short_name']=='ODF'){
					   $var="style='display:none;'";
					   $valid="";
					   $value="value='0'";
					   }
					   else{
					      $var="style='display:block;'";
						  $valid="valid";
						  $value="";
					   }
					  $p=$t+1;
		                      $tmp.='<div class="row">
	                    			<div class="col-md-4">
				                        <div class="form-group row"  >
				                        	<label class="col-md-3 label-control" for="userinput1">Term-'.$p.'</label>';
				                        	$tmp.='<div class="col-md-9">
				                            	<input  class="form-control form-control-sm  default-date-picker valid" autocomplete="off" title="Due date-'.$p.'"  name="due_date[]" type="text"   /><div id="error" class="error"></div>
				                            </div>
				                        </div>
				                    </div>';
				                    $tmp.='<div class="col-md-4" '.$var.'>
									        <div class="form-group row" >
				                        	<div class="col-md-9">
				                            	<input class="form-control form-control-sm amt tper valid" autocomplete="off" id="term_paidper_'.$t.'" onKeyUp="termvalchk(2,'.$t.')" '.$value.' title="Term Paid Percentage-'.$p.'"  name="term_paid_percent[]" type="text"   />
					<div id="error" class="terror"></div>
			                        		</div>
				                        </div>
			                        </div>
		                        </div>';
					 }
					 echo $tmp;
   		}
		
	
		//Hostel select branch_wise
		 elseif($_REQUEST['action']=='get_hostel_type'){
		 
		     $hostel_type="SELECT b.hostel_type_id as htid,b.hostel_type as hosteltype FROM ".TABLE_HOSTEL_BRANCH_MAP." a,".TABLE_HOSTEL_TYPE." b  where   a.branch_id='".$_REQUEST['branch_id']."' and a.hostel_type_id=b.hostel_type_id group by a.id";
			$hostel_type_res=$obj_db->get_qresult($hostel_type);
			
			$tmp='<option value="">--Hostel Types--</option>';
			
			while($hostel_type_rows=$obj_db->fetchArray($hostel_type_res)) { 
					$tmp.='<option value='.$hostel_type_rows['htid'].'>'.$hostel_type_rows['hosteltype'].'</option>';
					 }
	         echo $tmp;
		
		}	
		
		//get course and hostel
		
		 elseif($_REQUEST['action']=='get_course_hostel'){
    	 $course_sel="SELECT * FROM ".TABLE_COURSE." a,".TABLE_COURSE_BRANCH_MAP." b  where b.branch_id='".$_REQUEST['id']."' and course_model=1 and a.course_id=b.course_id";
			$res=$obj_db->get_qresult($course_sel);
			$tmp='<option value="">--Select Course--</option>';
			while($course_select_rows=$obj_db->fetchArray($res)) { 
					$tmp.='<option value='.$course_select_rows['course_id'].'>'.$course_select_rows['course_name'].'</option>';
					 }
		
			 
			  $hostel_branches="SELECT b.hostel_id as hid,b.hostel_name as hostelname FROM ".TABLE_HOSTEL_BRANCH_MAP." a,".TABLE_HOSTEL." b  
			  where   branch_id='".$_REQUEST['id']."' and a.hostel_id=b.hostel_id group by b.hostel_id";
			$hostel_res=$obj_db->get_qresult($hostel_branches);
			
			$ctmp='<option value="">--Hostel Names--</option>';
			
			while($hostel_rows=$obj_db->fetchArray($hostel_res)) { 
					$ctmp.='<option value='.$hostel_rows['hid'].'>'.$hostel_rows['hostelname'].'</option>';
					 }
		 echo $tmp.'^'.$ctmp;
   		}

//hostel_branch_map.php
elseif($_REQUEST['action']=='getbranch_hostels'){
    $hostel_branches="SELECT * FROM ".TABLE_HOSTEL." where hostel_id not in(select hostel_id from ".TABLE_HOSTEL_BRANCH_MAP." where branch_id='".$_REQUEST['branch_id']."' and org_id='".$_REQUEST['org_id']."') order by hostel_id";
			$hostel_res=$obj_db->get_qresult($hostel_branches);
			
			$tmp='<option value="">--Hostel Names--</option>';
			
			while($hostel_rows=$obj_db->fetchArray($hostel_res)) { 
					$tmp.='<option value='.$hostel_rows['hostel_id'].'>'.$hostel_rows['hostel_name'].'</option>';
					 }
		 echo $tmp;
}
//This is used for perticular course and branch wise only.use page in fee_course_map.php
 elseif($_REQUEST['action']=='get_fee_types'){
            if($_REQUEST['pay_type']==1){$cnd=" and month_term_status=1";}else{$cnd="";} 
    	$fee_sel="select * from ".TABLE_FEE_TYPE." where is_tution=1 $cnd and fee_id not in(select fee_id from ".TABLE_FEE_COURSE_MAP." where 
		course_id=".$_REQUEST['course_id']."  and branch_id=".$_REQUEST['branch_id']."  )";
			$res=$obj_db->get_qresult($fee_sel);
			$tmp='<option value="">--Select Course--</option>';
			while($fee_select_rows=$obj_db->fetchArray($res)) { 
					$tmp.='<option value='.$fee_select_rows['fee_id'].'^'.$fee_select_rows['fee_short_name'].'>'.$fee_select_rows['fee_name'].'</option>';
					 }
					 echo $tmp;
		}
		
		
	//This is used for perticular course and branch wise only.use page in fee_hostel_map.php
 elseif($_REQUEST['action']=='get_fee_hostel_types'){
    	$fee_sel="select * from ".TABLE_FEE_TYPE." where is_tution=2  and fee_id not in(select fee_id from ".TABLE_FEE_COURSE_MAP." where 
		course_id='".$_REQUEST['course_id']."' and branch_id='".$_REQUEST['branch_id']."' and hostel_id='".$_REQUEST['hostel_id']."')";
			$res=$obj_db->get_qresult($fee_sel);
			$tmp='<option value="">--Fee Types--</option>';
			while($fee_select_rows=$obj_db->fetchArray($res)) { 
					$tmp.='<option value='.$fee_select_rows['fee_id'].'>'.$fee_select_rows['fee_name'].'</option>';
					 }
					 echo $tmp;
		}	
		
		

 elseif($_REQUEST['action']=="get_branches"){
 
  
    		 $branch_query=$obj_db->get_qresult("SELECT * FROM ".TABLE_BRANCH." a   where a.org_id='".$_REQUEST['org_id']."' ORDER BY a.branch_id desc");
			$tmp.='<div class="form-group">';
                              $tmp.='<label class="control-label col-md-3">Branches</label>';
                             $tmp.='<div class="col-md-4 col-xs-11">';
			while($branch_select_rows=$obj_db->fetchArray($branch_query)) { 
                               $tmp.='<input   class="form-control valid chkbrnh"  title="Branch Name" type="checkbox" onclick="get_sections();" name="branch_name[]" value="'.$branch_select_rows['branch_id'].'" />'.$branch_select_rows['branch_name'];
         		 }
			  $tmp.='<div id="error"></div>';
                              $tmp.='</div>';
                           $tmp.='</div>';
			 echo $tmp;
 }

 
  elseif($_REQUEST['action']=="get_branch_sections"){
      $branch_ids=substr($_REQUEST['branch_id'],0,-1);
	  if($branch_ids){
    		 $section_query=$obj_db->get_qresult("SELECT * FROM ".TABLE_SECTION." a   where a.branch_id in($branch_ids) ORDER BY a.sec_id desc");
			$tmp.='<div class="form-group">';
                              $tmp.='<label class="control-label col-md-3">Sections</label>';
                             $tmp.='<div class="col-md-4 col-xs-11">';
			while($sec_select_rows=$obj_db->fetchArray($section_query)) { 
                               $tmp.='<input   class="form-control valid chksec"  title="Exam Name" type="checkbox" onclick="get_subjs();" name="sec_name[]" value="'.$sec_select_rows['sec_id'].'" />'.$sec_select_rows['sec_name'];
         		 }
			  $tmp.='<div id="error"></div>';
                              $tmp.='</div>';
                           $tmp.='</div>'; 
						   }
			 echo $tmp;
			
 }
 
 
 elseif($_REQUEST['action']=="get_section_subjs"){
		    
		     $sec_id=substr($_REQUEST['sec_id'],0,-1);
			
			
			// select group_concat(distinct(course_id)) from section_names where sec_id in(17,18,19,34,38)
			 $sec_select_query="SELECT group_concat(distinct(course_id)) as course_id FROM ".TABLE_SECTION." where sec_id in($sec_id)";
			 $sec_res=$obj_db->fetchRow($sec_select_query);
			
			$coursid=$sec_res['course_id'];
			
			  if($coursid){
			 $sub_select_query="SELECT short_name,b.sub_id as subid FROM ".TABLE_SUB_TO_COURSE." a,".TABLE_SUB_NAME." b where a.course_id in($coursid) and a.sub_id=b.sub_id group by b.sub_id";
			$sub_res=$obj_db->get_qresult($sub_select_query);
			
			   $sub.='<div class="row-fluid">';
               			$sub.='<div class="span12">';
						$sub.='<div class="control-group">';
                              $sub.='<div class="controls span3">';
							       $sub.='<label class="control-label">Subjects</label>';
                                $sub.='<input type="checkbox" name="sec_id[]" onclick="checkallsubj();" id="selectall" value="'.$sec_name_rows['sec_id'].'" >Check All';
                              $sub.='</div>';
                              $sub.='<div class="controls span3">';
							  $sub.='<label class="control-label"><div align="center">Marks</div></label>';
                              
                                $sub.='</div>';
								
								$sub.='<div class="controls span3">';
							  $sub.='<label class="control-label"><div align="center">Max Marks</div></label>';
                             
                                $sub.='</div>';
								
								
								$sub.='<div class="controls span3">';
							  $sub.='<label class="control-label"><div align="center">Exam date</div></label>';
                             
                                $sub.='</div>';
								
								
                        $sub.='</div>';
						$sub.='</div>';
                        $ub.='</div>';
			 
			while($sub_name_rows=$obj_db->fetchArray($sub_res)){
			 $sub.='<div class="row-fluid">';
               			$sub.='<div class="span12">';
						$sub.='<div class="control-group">';
                       $sub.='<div class="controls span3">';
							      $sub.='<label class="control-label" >'.'<input type="checkbox" class="eachsubject" value="'.$sub_name_rows['subid'].'" name="sub_id[]" id="sub_name_'.$sub_name_rows['subid'].'" onclick="opensub_marks(this.value)" >'.$sub_name_rows['short_name'].'</label><input type="hidden" name="subhid[]" id="subhid_'.$sub_name_rows['subid'].'"> ';
                               
                                $sub.='</div>';
                        $sub.='<div class="controls span3">';
				$sub.='<input type="text" name=sub_marks[]" class="submrk" disabled="disabled" id="sub_marks_'.$sub_name_rows['subid'].'" style="display:none;" >';
                         $sub.='</div>';
								
								
								$sub.='<div class="controls span3" '.$sty.' >';
					 $sub.='<input type="text" name=max_marks[]" class="submxmrk" disabled="disabled" id="max_marks_'.$sub_name_rows['subid'].'" style="display:none;" >';
                                $sub.='</div>';
								
					$sub.='<div class="controls span3" '.$sty.' >';
					 $sub.='<input type="text" name=exam_date[]" class="exdates" disabled="disabled" id="exam_date_'.$sub_name_rows['subid'].'" style="display:none;" >';
                                $sub.='</div>';
					
					 $sub.='</div>';
						$sub.='</div>';
                        $sub.='</div><br>';
				
				
			}
			}
			
			echo $sub;
			}
			
   //stock_price.php
    elseif($_REQUEST['action']=='get_stock'){
	 $split_feeid=explode('^',$_REQUEST['fee_id']);
	// echo $spli
 
     	 $stocktype_query="SELECT * FROM ".TABLE_STOCK." where stock_id not in(select stock_id from ".TABLE_STOCK_PRICE." where year_id='".$_REQUEST['year_id']."'
		 and branch_id='".$_REQUEST['branch_id']."' and fee_id='".$split_feeid[0]."')";
			$stock_res=$obj_db->get_qresult($stocktype_query);
			$tmp='<option value="">--Select Course--</option>';
			while($stock_rows=$obj_db->fetchArray($stock_res)) { 
					$tmp.='<option value='.$stock_rows['stock_id'].'>'.$stock_rows['stock_name'].'</option>';
					 }
					 echo $tmp;
		}
	
	
	elseif($_REQUEST['action']=='get_feetype'){
	 $split_feeid=explode('^',$_REQUEST['fee_id']);
	// echo $spli
 
     	 $fee_typequery="SELECT * FROM ".TABLE_FEE_TYPE." where is_tution=4  and fee_id not in(select fee_id from ".TABLE_STOCK_PRICE." where year_id='".$_REQUEST['year_id']."'              and branch_id='".$_REQUEST['branch_id']."')";
		
			$feetype_res=$obj_db->get_qresult($fee_typequery);
			$tmp='<option value="">--Select Course--</option>';
			while($fee_type_rows=$obj_db->fetchArray($feetype_res)) { 
					$tmp.='<option value='.$fee_type_rows['fee_id'].'>'.$fee_type_rows['fee_name'].'</option>';
					 }
					 echo $tmp;
		}
			
   //stock_price.php
    elseif($_REQUEST['action']=='get_stock'){
	 $split_feeid=explode('^',$_REQUEST['fee_id']);
	// echo $spli
 
     	 $stocktype_query="SELECT * FROM ".TABLE_STOCK." where stock_id not in(select stock_id from ".TABLE_STOCK_PRICE." where year_id='".$_REQUEST['year_id']."'
		 and branch_id='".$_REQUEST['branch_id']."' and fee_id='".$split_feeid[0]."')";
			$stock_res=$obj_db->get_qresult($stocktype_query);
			$tmp='<option value="">--Select Course--</option>';
			while($stock_rows=$obj_db->fetchArray($stock_res)) { 
					$tmp.='<option value='.$stock_rows['stock_id'].'>'.$stock_rows['stock_name'].'</option>';
					 }
					 echo $tmp;
		}
	
	
	elseif($_REQUEST['action']=='get_feetype'){
	 $split_feeid=explode('^',$_REQUEST['fee_id']);
	// echo $spli
 
     	 $fee_typequery="SELECT * FROM ".TABLE_FEE_TYPE." where is_tution=4  and fee_id not in(select fee_id from ".TABLE_STOCK_PRICE." where year_id='".$_REQUEST['year_id']."'              and branch_id='".$_REQUEST['branch_id']."')";
		
			$feetype_res=$obj_db->get_qresult($fee_typequery);
			$tmp='<option value="">--Select Course--</option>';
			while($fee_type_rows=$obj_db->fetchArray($feetype_res)) { 
					$tmp.='<option value='.$fee_type_rows['fee_id'].'>'.$fee_type_rows['fee_name'].'</option>';
					 }
					 echo $tmp;
		}
			
   //stock_price.php
    elseif($_REQUEST['action']=='stock_fee'){
	   if($_REQUEST['course_id']!=''){
				  $ctb=" a,".TABLE_STOCKCOURSE_MAP." b";
				  $cnd=" and course_id='".$_REQUEST['course_id']."' and a.fee_id=b.fee_id and a.stock_id=b.stock_id";
				  }
				  else{
				    $ctb=" a";
				    $cnd='';
				  }
	
	
	            $books_qeury="SELECT a.stock_id,stock_short_code FROM ".TABLE_STOCK." $ctb where a.fee_id='".$_REQUEST['fee_id']."' and branch_id='".$_SESSION['branch_id']."' and a.org_id='".$_REQUEST['org_id']."' and 
		 a.stock_id not in(select stock_id from ".TABLE_STOCK_PRICE." where org_id='".$_REQUEST['org_id']."' and  
		 fee_id='".$_REQUEST['fee_id']."' and branch_id='".$_SESSION['branch_id']."') and book_type='".$_REQUEST['book_type']."' $cnd"; 
		       $fetch_num=$obj_db->fetchNum($books_qeury);
			   $stk_num=0;
				if($fetch_num){
				$stk_num=1;
                $tmp.='<table class="table mb-0 font-small-3" role="grid" aria-describedby="DataTables_Table_1_info" cellpadding="0" cellspacing="0">
				       <thead>
					<tr >
						<th class="center">Book Name</th>
						<th class="center">Price</th>
				    </tr>
					</thead><tbody role="alert" aria-live="polite" aria-relevant="all">';		 
				
     	 
		 		$books_res=$obj_db->get_qresult($books_qeury);
				  $i=1;
				 while($books_row=$obj_db->fetchArray($books_res)){
			     	 
					 $tmp.='<tr>
						<td class="center">
					<input name="stock_id[]" type="hidden" value="'.$books_row['stock_id'].'">'.$books_row['stock_short_code'].'</td>
					<td class="center">
						<input class="form-control form-control-sm " autocomplete="off" title="Stock Price" id="price_'.$books_row['stock_id'].'"  onKeyUp="numvalid('.$books_row['stock_id'].');" type="text" name="price[]"  />
						<div id="error" class="error"></div>
						</td>
					</tr>';
			          $i++;
							 }
						$tmp.='</tbody></table>';
							 }
					 echo $tmp.'^'.$stk_num;
					 
		}
		
		
		//stock_price.php 
		 elseif($_REQUEST['action']=='uniformstock_fee'){
	 
	             $books_qeury="SELECT a.stock_id,stock_short_code FROM ".TABLE_STOCK." a  where a.fee_id='".$_REQUEST['fee_id']."' and a.org_id='".$_REQUEST['org_id']."' and 
		 branch_id='".$_SESSION['branch_id']."' and  a.stock_id  not in(select stock_id from ".TABLE_STOCK_PRICE." where  org_id='".$_REQUEST['org_id']."' and  
		 fee_id='".$_REQUEST['fee_id']."' and branch_id='".$_SESSION['branch_id']."') and book_type='".$_REQUEST['book_type']."'"; 
		       $fetch_num=$obj_db->fetchNum($books_qeury);
				 $stknum=0;
				if($fetch_num){
				$stknum=1;
                $tmp.='<table class="table mb-0 font-small-3" role="grid" aria-describedby="DataTables_Table_1_info" cellpadding="0" cellspacing="0">
				       <thead>
					<tr >
						<th class="center">Uniform Name</th>
						<th class="center">Price per Each Meter</th>
				    </tr>
					</thead><tbody role="alert" aria-live="polite" aria-relevant="all">';		 
				
     	 
		 		$books_res=$obj_db->get_qresult($books_qeury);
				  $i=1;
				 while($books_row=$obj_db->fetchArray($books_res)){
			     	 
					 $tmp.='<tr>
						<td class="center"><input name="stock_id[]" type="hidden" value="'.$books_row['stock_id'].'">'.$books_row['stock_short_code'].'</td>
						<td class="center">
						<input class="form-control " title="Stock Price" id="price_'.$books_row['stock_id'].'"  onKeyUp="numvalid('.$books_row['stock_id'].');" type="text" name="price[]"  />
						<div id="error" class="error"></div>
						</td>
					</tr>';
			          $i++;
							 }
							 $tmp.='</tbody></table>';
							 }
					 echo $tmp.'^'.$stknum;
					 
		}
		
		
		
		  elseif($_REQUEST['action']=='branch_stock_fee'){
		  
		        if($_REQUEST['course_id']!=''){
				  $ctb=" a,".TABLE_STOCKCOURSE_MAP." b";
				  $cnd=" and course_id='".$_REQUEST['course_id']."' and a.fee_id=b.fee_id and a.stock_id=b.stock_id";
				  }
				  else{
				    $ctb=" a";
				    $cnd='';
				  }
                $tmp.='<table class="table mb-0 font-small-3" id="DataTables_Table_1" role="grid" aria-describedby="DataTables_Table_1_info" cellpadding="0" cellspacing="0">
				       <thead>
					<tr >
						<th class="center">Name</th>
						<th class="center">Quantity</th>
						<th class="center">Price</th>
				    </tr>
					<thead><tbody role="alert" aria-live="polite" aria-relevant="all">';		 
				
     	 $course_qeury="SELECT a.stock_id,a.stock_short_code FROM ".TABLE_STOCK." $ctb where a.fee_id='".$_REQUEST['fee_id']."' and branch_id='".$_SESSION['branch_id']."' and a.org_id='".$_REQUEST['org_id']."' and 
		 a.book_type='".$_REQUEST['book_type']."' $cnd"; 
		 		$course_res=$obj_db->get_qresult($course_qeury);
				  $i=1;
				 while($course_row=$obj_db->fetchArray($course_res)){
			     	  
					 $tmp.='<tr id="stockgrp_'.$course_row['stock_id'].'">
						<td class="center"><input id="stock_id_'.$course_row['stock_id'].'" 
						name="stock_id[]" type="hidden" value="'.$course_row['stock_id'].'">'.$course_row['stock_short_code'].'</td>
						<td class="center"><input class="form-control" title=" Quantity" id="qty_'.$course_row['stock_id'].'"  onKeyUp="qtynumvalid('.$course_row['stock_id'].');" type="text" name="qty[]"  /><div id="error" class="error"></div></td>
						<td><input class="form-control" title=" Price" id="price_'.$course_row['stock_id'].'"  onKeyUp="prcnumvalid('.$course_row['stock_id'].');" type="text" name="price[]"  />	<div id="error" class="error"></div></td></tr>';
			          $i++;
							 }
					$tmp.='</tbody></table>';
					 echo $tmp;
		}


   //stock_entry.php
   
   elseif($_REQUEST['action']=='get_uniformlist'){
                $tmp.='<table class="table mb-0 font-small-3" id="DataTables_Table_1" role="grid" aria-describedby="DataTables_Table_1_info" cellpadding="0" cellspacing="0">
				       <thead>
					<tr >
						<th class="center">Name</th>
						<th class="center">Quantity</th>
						<th class="center">Price</th>
				    </tr>
					<thead><tbody role="alert" aria-live="polite" aria-relevant="all">';		 
				
     	 $course_qeury="SELECT a.stock_id,a.stock_short_code FROM ".TABLE_STOCK." a where a.fee_id='".$_REQUEST['fee_id']."' and branch_id='".$_REQUEST['branch_id']."' "; 
		 $stknum=0;
		 		$course_res=$obj_db->get_qresult($course_qeury);
				  $i=1;
				   $get_unflst=$obj_db->fetchNum($course_qeury);
			 $stknum=0;
			 if($get_unflst){
			 $stknum=1;
				 while($course_row=$obj_db->fetchArray($course_res)){
			     	   $tmp.='<tr id="stockgrp_'.$course_row['stock_id'].'">
						<td class="center"><input id="stock_id_'.$course_row['stock_id'].'" 
						name="stock_id[]" type="hidden" value="'.$course_row['stock_id'].'">'.$course_row['stock_short_code'].'</td>
						<td class="center"><input class="form-control" title=" Quantity" id="qty_'.$course_row['stock_id'].'"  onKeyUp="qtynumvalid('.$course_row['stock_id'].');" type="text" name="qty[]"  /><div id="error" class="error"></div></td>
						<td><input class="form-control" title=" Price" id="price_'.$course_row['stock_id'].'"  onKeyUp="prcnumvalid('.$course_row['stock_id'].');" type="text" name="price[]"  /><div id="error" class="error"></div></td></tr>';
			             $i++;
							 }
							 }
						$tmp.='</tbody></table>';
				echo $tmp.'^'.$stknum;
		
   }
	
	
	elseif($_REQUEST['action']=='stock_setmap'){
	  
	   if($_REQUEST['course_id']!=''){
				  $ctb=" a,".TABLE_STOCKCOURSE_MAP." b";
				  $cnd=" and course_id='".$_REQUEST['course_id']."' and a.fee_id=b.fee_id and a.stock_id=b.stock_id";
				  }
				  else{
				    $ctb=" a";
					$book_type="and a.book_type='".$_REQUEST['book_type']."'";
				    $cnd='';
				  }	
	
	           $books_qeury="SELECT a.stock_id,a.stock_short_code FROM ".TABLE_STOCK." $ctb where a.fee_id='".$_REQUEST['fee_id']."' AND is_set=0 and a.org_id='".$_REQUEST['org_id']."'
			                      $book_type $cnd"; 
		       $fetch_num=$obj_db->fetchNum($books_qeury);
				if($fetch_num){
                $tmp.='<table class="table mb-0 font-small-3" role="grid" aria-describedby="DataTables_Table_1_info" cellpadding="0" cellspacing="0">
				       <thead>
					<tr >
						<th class="center">Book Name</th>
						<th class="center">Quantity</th>
				    </tr>
					</thead><tbody role="alert" aria-live="polite" aria-relevant="all">';		 
				
     	 
		 		$books_res=$obj_db->get_qresult($books_qeury);
				  $i=1;
				 while($books_row=$obj_db->fetchArray($books_res)){
			     	 
					 $tmp.='<tr>
						<td class="center"><input name="stock_id[]" type="hidden" value="'.$books_row['stock_id'].'">'.$books_row['stock_short_code'].'</td>
						<td class="center">
						<input class="form-control form-control-sm " autocomplete="off" title="Stock Price" id="qty_'.$books_row['stock_id'].'"  onKeyUp="numvalid('.$books_row['stock_id'].');" type="text" name="qty[]"  />
						<div id="error" class="error"></div>
						</td>
					</tr>';
			          $i++;
							 }
							 }
					$tmp.='<tbody</table>';
					 echo $tmp;
					 
		
	}
	
	//stock_corusmap
	elseif($_REQUEST['action']=='course_textbooks'){
	  
	  
	           $books_qeury="SELECT stock_id,stock_short_code FROM ".TABLE_STOCK."  where is_set=0 and branch_id='".$_REQUEST['branch_id']."' and book_type=1 order by stock_id asc"; 
		       $fetch_num=$obj_db->fetchNum($books_qeury);
				if($fetch_num){
                $tmp.='<table class="table mb-0 font-small-3" role="grid" aria-describedby="DataTables_Table_1_info" cellpadding="0" cellspacing="0">
				       <thead>
					<tr >
						<th class="center">Book Name</th>
						<th class="center">Quantity</th>
				    </tr>
					</thead><tbody role="alert" aria-live="polite" aria-relevant="all">';		 
				
     	 
		 		$books_res=$obj_db->get_qresult($books_qeury);
				  $i=1;
				 while($books_row=$obj_db->fetchArray($books_res)){
			     	 
					 $tmp.='<tr>
						<td class="center">'.$books_row['stock_short_code'].'</td>
						<td class="center"><input name="stock_id[]" class="ace" type="checkbox" value="'.$books_row['stock_id'].'">
						</td>
					</tr>';
			          $i++;
							 }
							 }
					$tmp.='<tbody</table>';
					 echo $tmp;
					 
		
	}
	//boook_setmap.php
	
	//boook_setmap.php
	
	 elseif($_REQUEST['action']=='coursewise_stock_set'){
		 
		     $stockset_query="SELECT stock_name,stock_id FROM ".TABLE_STOCK." a  where   a.fee_id='".$_REQUEST['fee_id']."' and stock_id not in(select set_id from
			  ".TABLE_STOCK_SETS.") and  a.is_set=1 and branch_id='".$_SESSION['branch_id']."' group by stock_name desc";
			$stockset_type_res=$obj_db->get_qresult($stockset_query);
			
			$tmp='<option value="">--Select--</option>';
			
			while($stockset_type_rows=$obj_db->fetchArray($stockset_type_res)) { 
					$tmp.='<option value='.$stockset_type_rows['stock_id'].'>'.$stockset_type_rows['stock_name'].'</option>';
					 }
	         echo $tmp;
		
		}
		
		elseif($_REQUEST['action']=='get_stockset_course'){
		 
		     $stockset_query="SELECT c.course_id,course_name FROM ".TABLE_STOCK." a,".TABLE_STOCKCOURSE_MAP." b,".TABLE_COURSE." c  where   a.stock_id='".$_REQUEST['set_id']."' and a.stock_id=b.stock_id and a.is_set=1 and branch_id='".$_SESSION['branch_id']."' and b.course_id=c.course_id";
			$stockset_type_res=$obj_db->get_qresult($stockset_query);
			
			$tmp='<option value="">--Select--</option>';
			
			while($stockset_type_rows=$obj_db->fetchArray($stockset_type_res)) { 
					$tmp.='<option value='.$stockset_type_rows['course_id'].'>'.$stockset_type_rows['course_name'].'</option>';
					 }
	         echo $tmp;
		
		}
		
		elseif($_REQUEST['action']=='stock_coursemaps'){
		 
		     $stockset_query="SELECT stock_name,stock_id FROM ".TABLE_STOCK." where stock_id not in(select stock_refid from
			  ".TABLE_STOCKCOURSE_MAP." where branch_id='".$_REQUEST['branch_id']."' and stock_refid!=0)   and  is_set=1 and branch_id='".$_REQUEST['branch_id']."' group by stock_name desc";
			$stockset_type_res=$obj_db->get_qresult($stockset_query);
			
			$tmp='<option value="">--Select--</option>';
			
			while($stockset_type_rows=$obj_db->fetchArray($stockset_type_res)) { 
			$stock_course=$obj_db->fetchRow("select course_id from ".TABLE_STOCKCOURSE_MAP." where stock_id='".$stockset_type_rows['stock_id']."'");
					$tmp.='<option value='.$stockset_type_rows['stock_id'].'^'.$stock_course['course_id'].'>'.$stockset_type_rows['stock_name'].'</option>';
					 }
	         echo $tmp;
		
		}
		
		
		
		//stock get branch id 
		
		elseif($_REQUEST['action']=='get_stockbranch_course'){
		 
				  $brnch_org=$obj_db->get_qresult("SELECT * FROM ".TABLE_BRANCH." where org_id ='".$_REQUEST['org_id']."'"); 
				$tmp.='<option value="">--Select--</option>';
				 while($brnch_org_row=$obj_db->fetchArray($brnch_org)){
				$tmp.='<option value='.$brnch_org_row['branch_id'].'>'.$brnch_org_row['branch_name'].'</option>';
				 }
				 
			 $course_qry=$obj_db->get_qresult("SELECT course_id,course_name FROM ".TABLE_COURSE."  where org_id='".$_REQUEST['org_id']."'   ORDER BY course_id asc"); 
				$ctmp.='<option value="">--Select--</option>';
				 while($course_qrw=$obj_db->fetchArray($course_qry)){
				$ctmp.='<option value="'.$course_qrw['course_id'].'">'.$course_qrw['course_name'].'</option>';
				 }
		 echo $tmp.'^'.$ctmp;
		}

?>
		
		
		