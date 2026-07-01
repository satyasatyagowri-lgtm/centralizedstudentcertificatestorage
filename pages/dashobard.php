<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/user_detail.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=user_details";
			
			$obj_press = new userdetails_operations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_user_details($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_user_details($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->user_details_savenew($data,$id);
			    }
			}

?>
<script>
  function valid(){
$('.TempOwner input,textarea').removeClass('valid');
 $('.valid').css('border','1px solid red');
	  var flag=1;
	       $('.valid').each(function(){
	          var error=$(this).parent().find("#error");
			  if($(this).val()=='')
			  {
			  flag=0; error.html('Missing '+$(this).attr('title')+'<br>')
			  $(this).css('border','1px solid red');
			  }
			  else
			  {
			    error.html('');
			    $(this).css('border','1px solid #ccc');
			  }
		});
		
		
		
	  if(($('[name="user_status"]:checked ').length)<1)
	      {
        flag=0; $("#stserr").html('select status'+'<br>')
	      }
	  else {
             $("#stserr").html('');
		   }

	  if(($("#pword").val()!='') && ($("#pword").val()!=$("#cpword").val()))
        {
		  flag=0; $(".cpword").html('Confirm Password doesnot match with password'+'<br>')
		}
		
		 if(flag==1){
		 $('.glass').fadeIn();	
	   $('.loadingimg').show();
	    $(' #frm1 ').submit();  
	 }
	}
	
	function get_orgid(id){
		  $.ajax({	  
					  url:'../includes/functions/ajax.php',  
					  type:'POST',  
					  data:{'action':'get_all_courses','id':id},
					 success:function(data)
					  {
					   if(data!=''){
					  var dt=data.split('^');
					  $("#course_id").html(dt[1]);
					  $("#chkbox").html(dt[0]);
					 					 }
				     }
			});
	}		 
	   
	   function get_datapermission(id){
	   
	    $(".datapermissubdiv").prop('checked',false);
		$("#datapermis_validdte").val('');
	     if($("#is_havedata_permission").is(":checked")){
		   $("#data_permisdiv").show();
		  $(".datapermissubdiv").prop('checked',true);
		   }
		   else $("#data_permisdiv").hide();
	   }
</script>

<div class="row">
 <div class="modal-content">        
     		<?php if($action=='add'||$action=='edit'){?>
			
			<div class="modal-content">
					<div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Form</h5>
				<div class="media-right white text-right">
											<a  class="btn btn-sm btnradius btn-warning" href="<?php echo $page_url;?>" ><span style="color:#FFFFFF">Back</span></a>
				</div>
											
              </div>
		
		   <div class="modal-body m-b-0">
				
			<form   class="form-horizontal" id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
					<div class="form-row">
                                <div class="form-group col-md-6">
									
									<label for="inputEmail4">Image</label>
									<div class="input-group">
					<input class="form-control form-control-sm " title="Image" type="file" name="file"  />
					<div id="error" class="error"></div>
					</div>
				    </div>
					<?php $msger=explode('^',$msg);?>
		     		 <div class="form-group col-md-6">
									
									<label for="inputEmail4">User Name</label>
									<div class="input-group">
					<input class="form-control form-control-sm valid" <?php if($action=='edit'){?> readonly="" <?php }?> title="User Name" type="text" name="user_name" value="<?php echo $data['user_name'];?>" />
					
					</div>
					<div id="error" class="error">
					<?php if($msger[0]=='aunameexist' || $msger[1]=='unameexist'){?>
					*Username already exist
					<?php }?>
					</div>
				    </div>
					
					 <div class="form-group col-md-6">
									<label for="inputEmail4">Password</label>
									<div class="input-group">
					<input class="form-control form-control-sm valid" title="Password" id="pword" type="password" name="user_password" value="<?php if($data['user_password']!='')echo $data['user_password'];else echo '';?>" />				
					</div>
						<div id="error" class="error"></div>
				    </div>
					
					<div class="form-group col-md-6">
									<label for="inputEmail4">Confirm Password</label>
									<div class="input-group">
					<input class="form-control form-control-sm valid" title="Confirm Password" id="cpword" type="password" name="cuser_password" value="<?php if($data['user_password']!='')echo $data['user_password'];else echo '';?>" />
					
					</div><div id="error" class="error cpword"></div>
				    </div>
					
					<div class="form-group col-md-6">
									<label for="inputEmail4">Mobile</label>
									<div class="input-group">
					<input class="form-control form-control-sm valid" title="mobile" type="text" name="mobile" value="<?php echo $data['mobile'];?>" />
					
					</div><div id="error" class="error"></div>
				    </div>
					
					<div class="form-group col-md-6">
									<label for="inputEmail4">Sms Mobile</label>
									<div class="input-group">
					<input class="form-control form-control-sm valid" title="mobile" type="text" name="sms_mobile" value="<?php if($data['sms_mobile']!='')echo $data['sms_mobile'];else echo ''?>" />
					
					</div>
					<div id="error" class="error"></div>
				    </div>
					
					<div class="form-group col-md-6">
									<label for="inputEmail4">Email</label>
									<div class="input-group">
					<input class="form-control form-control-sm " title="Email id" type="text" name="email" value="<?php echo $data['email'];?>" />
					
					</div>
					<div id="error" class="error">
					<?php if($msger[2]=='aemilexist' || $msger[3]=='emilexist'){?>
					*Email already exist
					<?php }?>
					</div>
				    </div>
					
					<div class="form-group col-md-6">
									<label for="inputEmail4">User Type</label>
									<div class="input-group">
					<?php 		
					$usr_typeid=  $data['user_type_id'];	
					$usr_type_query=$obj_db->qry("SELECT * FROM ".TABLE_USER_TYPES."  where status=1 ORDER BY user_type_id desc");		?>
					<select class="form-control form-control-sm m-bot15 valid" title="User type" name="user_type_id" id="user_type_id">
					<option value="">--Select Type--</option>
					<?php foreach($usr_type_query as $key=>$value){?>
					<option value="<?php echo $value['user_type_id'];?>" <?php if($value['user_type_id']==$data['user_type_id']){?> selected="selected" <?php }?>><?php echo $value['type_name'];?></option>
					<?php }?>
					</select>
					
					</div><div id="error" class="error"></div>
					</div>
					
				 <div class="form-group col-md-6">
					<label for="inputEmail4">Branch</label>
									<div class="input-group">
					<?php 
 					$brnqry=$obj_db->qry("SELECT * FROM ".TABLE_BRANCH."  ORDER BY branch_id desc");
						$branch_id=  $data['branch_id'];		
					?>
					<select class="form-control form-control-sm m-bot15 valid"   title="Select Branch" onChange="getcourse(this.value);" name="branch_id" id="branch_id">	
                    <option value="">--Select--</option>
					<?php foreach($brnqry as $k=>$v){?>
					<option value="<?php echo $v['branch_id'];?>" <?php if($v['branch_id']==$data['branch_id']){?> selected="selected" <?php }?>><?php echo $v['branch_name'];?></option>
					<?php }?>
 				</select>
				<div id="error" class="error"></div>
					</div>
					</div>
					</div>
					
					
					<div class="form-group  ">
					<label class="control-label col-md-3">Branches</label>
					<div id="brnchse" class="row">
					<?php 	
			 $brn_asid_count=count($data['assign_branch_ids']);
			 for($b=0;$b<$brn_asid_count;$b++){
			 if($data['assign_branch_ids']){
			   $assig_brid.=$data['assign_branch_ids'][$b].',';
			 }
			 }
			 $assig_brids=substr($assig_brid,0,-1);
			 
			 if(is_array($data['assign_branch_ids']))
			  {
			    $ass_brid=$assig_brids;
			  }
			  else{
			     $ass_brid=$data['assign_branch_ids'];
			  }
			 
					$branch_ids=explode(',',$ass_brid);
 							   $k=0;?>
 					<?php 
						foreach($brnqry as $k=>$v) {
						
						?>
						<div class="form-group col-md-6">
                                <div class="form-check">
                          <input type="checkbox" name="assign_branch_ids[]" title="Checbox" class="valid  form-check-input " value="<?php echo $v['branch_id'];?>"
						 id="brnid<?php echo $v['branch_id'];?>" <?php if(in_array($v['branch_id'],$branch_ids)){ ?> checked="checked"<?php  }?> />
 						   <label class="form-check-label" for="brnid<?php echo $v['branch_id'];?>">
                            <?php echo $v['branch_short_name'];?></label>
                            </div></div>
						<?php
						$k++;}?>
						<div id="brnherr"></div>
  					</div>
					</div>
					
					<div class="form-row">
					  <div class="form-group col-md-6">
					<label for="inputEmail4">Organization</label>
									<div class="input-group">
					<?php 
 					$orgqry=$obj_db->qry("SELECT * FROM ".TABLE_ORG."  ORDER BY org_id desc");
						$branch_id=  $data['org_id'];		
					?>
					<select class="form-control form-control-sm m-bot15 valid"   title="Select Organization"  name="org_id" id="org_id">	
                    <option value="">--Select--</option>
					<?php foreach($orgqry as $k=>$v){?>
					<option value="<?php echo $v['org_id'];?>" <?php if($v['org_id']==$data['org_id']){?> selected="selected" <?php }?>><?php echo $v['org_name'];?></option>
					<?php }?>
 				</select>
				<div id="error" class="error"></div>
					</div>
					</div>
					</div>
					
					<div class="form-group  ">
					<label class="control-label col-md-3">Organization</label>
					<div id="brnchse" class="row">
					<?php 	
			 $org_asid_count=count($data['assign_org_ids']);
			 for($b=0;$b<$org_asid_count;$b++){
			 if($data['assign_org_ids']){
			   $assig_orgid.=$data['assign_org_ids'][$b].',';
			 }
			 }
			 $assig_orgids=substr($assig_orgid,0,-1);
			 
			 if(is_array($data['assign_org_ids']))
			  {
			    $ass_orgid=$assig_orgids;
			  }
			  else{
			     $ass_orgid=$data['assign_org_ids'];
			  }
			 
					$org_ids=explode(',',$ass_orgid);
 							   $k=0;?>
 					<?php 
						foreach($orgqry as $k=>$v) {
						
						?>
						<div class="form-group col-md-6">
                                <div class="form-check">
                          <input type="checkbox" name="assign_org_ids[]" title="Checbox" class="valid  form-check-input " value="<?php echo $v['org_id'];?>"
						 id="orgid<?php echo $v['org_id'];?>" <?php if(in_array($v['org_id'],$org_ids)){ ?> checked="checked"<?php  }?> />
 						   <label class="form-check-label" for="orgid<?php echo $v['org_id'];?>">
                            <?php echo $v['org_name'];?></label>
                            </div></div>
						<?php
						$k++;}?>
						<div id="orgherr"></div>
  					</div>
					</div>
				
			
				<?php $decode_permislevels=json_decode($data['permission_levels'],true);
				   $is_datpermis=$decode_permislevels['data_permisdts']['is_havedata_permission'];
				   $datapermisvaliddate=$decode_permislevels['data_permisdts']['datapermis_validdte'];
				   
				   $is_mobilepermis=$decode_permislevels['data_permisdts']['mobile_permission'];
				   $is_stdaddrpermis=$decode_permislevels['data_permisdts']['studentaddress_permission'];
				   $is_pervschlpermis=$decode_permislevels['data_permisdts']['prevschool_detail_permission'];
				   
				   $is_feepay_datepermis=$decode_permislevels['is_feepaydate_permission'];
				    $feepay_permisvaliddate=$decode_permislevels['paydate_permis_validdte'];
				   
				   $is_concession_permis=$decode_permislevels['is_concession_permission'];
				   $concepermisvaliddate=$decode_permislevels['concession_permis_validdte'];

				   $is_expensehave_directpermission=$decode_permislevels['is_expensehave_directpermission'];
				   $expensepermisvaliddate=$decode_permislevels['expenpermission_permis_validdte'];
				   $todaydate=strtotime(date('d-m-Y'));
				?>
 				
				<div class="form-group col-md-6 m-l-5  ">		
				<label for="inputEmail4">Is Have Mobile Address Data Permission (Provide Valid Date)</label>
											<div class="input-group-prepend ">
											
                                        <div class="input-group-text " style="padding:0px 10px; height:31px; background-color:white">
											 <input type="checkbox" <?php if(($is_datpermis==1 && strtotime($datapermisvaliddate)>=$todaydate && $datapermisvaliddate!='') || ($is_datpermis==1 && $datapermisvaliddate=='')){?> checked="checked" <?php }?> onclick="get_datapermission(this.value);" aria-label="Checkbox for following text input" name="is_havedata_permission" id="is_havedata_permission" class="firstsub1 allcompos form-control form-control-sm" value="1">
											  <label for="component_id3" class="cr"></label>
											 </div>
											 <input type="text" name="datapermis_validdte" id="datapermis_validdte" value="<?php echo $datapermisvaliddate;?>" style="background-color:white;border-radius:0px" class="form-control form-control-sm default-date-picker"  placeholder="Valid Date" aria-label="Text input with checkbox"  >
											 
											
											
											
											
											
											 </div> 
											 <div style=" <?php if(($is_datpermis==1 && strtotime($datapermisvaliddate)>=$todaydate && $datapermisvaliddate!='') || ($is_datpermis==1 && $datapermisvaliddate=='')){?> display:block; <?php }else{?> display:none; <?php }?>" id="data_permisdiv">
											 
						  							  <div class="form-group col-sm-6 m-l-5  ">		
											<div class="input-group-prepend ">
                                        <div class="input-group-text " style="padding:0px 10px; height:31px; background-color:white">
											 <input type="checkbox" aria-label="Checkbox for following text input" <?php if(($is_mobilepermis==1 && strtotime($datapermisvaliddate)>=$todaydate && $datapermisvaliddate!='') || ($is_mobilepermis==1 && $datapermisvaliddate=='')){?> checked="checked" <?php }?>   name="mobile_permission" id="mobile_permission" class="datapermissubdiv form-control form-control-sm" value="1">
											 </div>
											 <input type="text" style="background-color:white;border-radius:0px" class="form-control form-control-sm" aria-label="Text input with checkbox" readonly="" value="Mobile Data Permission">
											 </div> 
											 </div>
																	  <div class="form-group col-sm-6 m-l-5  ">		
											<div class="input-group-prepend ">
                                        <div class="input-group-text " style="padding:0px 10px; height:31px; background-color:white">
											 <input type="checkbox" aria-label="Checkbox for following text input" <?php if(($is_stdaddrpermis==1 && strtotime($datapermisvaliddate)>=$todaydate &&  $datapermisvaliddate!='') || ($is_mobilepermis==1 && $datapermisvaliddate=='')){?> checked="checked" <?php }?>  name="studentaddress_permission" id="studentaddress_permission" class="datapermissubdiv form-control form-control-sm" value="1">
											 </div>
											 <input type="text" style="background-color:white;border-radius:0px" class="form-control form-control-sm" aria-label="Text input with checkbox" readonly="" value="Student Address Permission">
											 </div> 
											 </div>
																	  <div class="form-group col-sm-6 m-l-5  ">		
											<div class="input-group-prepend ">
                                        <div class="input-group-text " style="padding:0px 10px; height:31px; background-color:white">
											 <input type="checkbox" aria-label="Checkbox for following text input" <?php if(($is_pervschlpermis==1 && strtotime($datapermisvaliddate)>=$todaydate &&  $datapermisvaliddate!='') || ($is_mobilepermis==1 && $datapermisvaliddate=='')){?> checked="checked" <?php }?>  name="prevschool_detail_permission" id="prevschool_detail_permission" class="datapermissubdiv form-control form-control-sm" value="1">
											 </div>
											 <input type="text" style="background-color:white;border-radius:0px" class="form-control form-control-sm" aria-label="Text input with checkbox" readonly="" value="Previous School Details">
											 </div> 
											 </div>
																				
											 </div>
				
				<div class="form-group col-md-6 m-l-5  ">		
				<label for="inputEmail4">Is Payment Date Permission (Provide Valid Date)</label>
											<div class="input-group-prepend ">
											
                                        <div class="input-group-text " style="padding:0px 10px; height:31px; background-color:white">
											 <input type="checkbox" <?php if(($is_feepay_datepermis==1 && strtotime($feepay_permisvaliddate)>=$todaydate && $feepay_permisvaliddate!='') || ($is_feepay_datepermis==1 && $feepay_permisvaliddate=='')){?> checked="checked" <?php }?> aria-label="Checkbox for following text input"  name="is_feepaydate_permission" id="is_feepaydate_permission" class="firstsub1 allcompos form-control form-control-sm" value="1">
											  <label for="component_id3" class="cr"></label>
											 </div>
											 <input type="text" name="paydate_permis_validdte" value="<?php echo $feepay_permisvaliddate;?>" style="background-color:white;border-radius:0px" class="form-control form-control-sm default-date-picker" placeholder="Valid Date" aria-label="Text input with checkbox"  >
											 </div> 
											 </div>
					
					
					<div class="form-group col-md-6 m-l-5  ">		
				<label for="inputEmail4">Is Concession Permission (Provide Valid Date)</label>
											<div class="input-group-prepend ">
											
                                        <div class="input-group-text " style="padding:0px 10px; height:31px; background-color:white">
											 <input type="checkbox" <?php if(($is_concession_permis==1 && strtotime($concepermisvaliddate)>=$todaydate && $concepermisvaliddate!='') || ($is_concession_permis==1 && $concepermisvaliddate=='')){?> checked="checked" <?php }?> aria-label="Checkbox for following text input"   name="is_concession_permission" id="is_concession_permission" class="firstsub1 allcompos form-control form-control-sm" value="1">
											  <label for="component_id3" class="cr"></label>
											 </div>
											 <input type="text" name="concession_permis_validdte" value="<?php echo $concepermisvaliddate;?>" style="background-color:white;border-radius:0px" class="form-control form-control-sm default-date-picker" placeholder="Valid Date" aria-label="Text input with checkbox" >
											 </div> 
											 </div>

					<div class="form-group col-md-6 m-l-5  ">		
				<label for="inputEmail4">Is Expense Have Direct Permission</label>
											<div class="input-group-prepend ">
											
                                        <div class="input-group-text " style="padding:0px 10px; height:31px; background-color:white">
											 <input type="checkbox" <?php if(($is_expensehave_directpermission==1 && strtotime($expensepermisvaliddate)>=$todaydate && $expensepermisvaliddate!='') || ($is_expensehave_directpermission==1 && $expensepermisvaliddate=='')){?> checked="checked" <?php }?> aria-label="Checkbox for following text input"   name="is_expensehave_directpermission" id="is_expensehave_directpermission" class="firstsub1 allcompos form-control form-control-sm" value="1">
											  <label for="component_id3" class="cr"></label>
											 </div>
											 <input type="text" name="expenpermission_permis_validdte" value="<?php echo $expensepermisvaliddate;?>" style="background-color:white;border-radius:0px" class="form-control form-control-sm default-date-picker" placeholder="Valid Date" aria-label="Text input with checkbox" >
											 </div> 
											 </div>
					
					<div class="form-group col-md-6">
									<label for="inputEmail4">Status</label>
									<div class="input-group">
					<label class="checkbox-inline">
					<input type="radio" name="user_status" value="1" <?php if($data['user_status']==1){?> checked="checked" <?php }?> />Active
					
					</label>
					<label class="checkbox-inline"><input type="radio" name="user_status" value="0" <?php if($data['user_status']!='' && $data['user_status']==0){?> checked="checked" <?php }?> />Inactive
					
					</label>
					</div>
					<div id="stserr"></div>
					</div>
					<input type="hidden" name="orgid" value="<?php echo $_GET['org_id'];?>" />
					<input type="hidden" name="btn_save_data" value="Update" >
					<div class="form-group"  align="center">
					<div class="col-md-8">
					<button type="button" onClick="valid();" class="btn btn-sm btn-primary">Submit</button>
					</div>
					</div>
					</div>
					</form>
					</div>
					</div>
					
				 <?php }else{?>
				 <div class="modal-content">
					<div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Details </h5>
				<div class="media-right white text-right">
											<a  class="btn btn-sm btnradius btn-success" href="<?php echo $page_url;?>&action=add" ><span style="color:#FFFFFF">Add</span></a>	&nbsp;&nbsp;<a download="Alfaniti Solutions FarmDetails.xls" 
  			  href="#" onclick="return ExcellentExport.excel(this, 'excel', 'Sheet Name Here');" style="float: right;" class="btn btn-sm btnradius btn-primary" ><i class="fas fa-file-excel lg"></i></a>
				</div>
											
              </div>
			  
         <div class="panel-body table-responsive"  >
		 <br clear="all" />
                           <table  class="table table-striped table-sm m-t-0 table-bordered border-1" id="sorting"  >
						<?php	
						/*$path="../includes/user_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/
				       $usr_dtrws=$obj_db->qry("SELECT user_id ,a.branch_id ,assign_branch_ids,is_cashier,type_name,user_name,a.user_password, mobile,email,user_status 
					     FROM ".TABLE_USER_DETAILS." a,".TABLE_USER_TYPES." b  where b.user_type_id=a.user_type_id and b.status=1");
				        ?>
					<thead>
					<tr class="thcolor" >
						<th class="center">Sno</th>
						<th class="center">User Name</th>
						<th class="center">User Type</th>
						<th class="center">Mobile</th>
						<th class="center">Email</th>
						<th class="center">User Status</th>
				       <th  class="center">Edit</th>
					</tr>
					</thead>
					<tbody role="alert" aria-live="polite" aria-relevant="all">
						<?php	
						$n=0;
						$j=0;
						foreach($usr_dtrws as $key=>$value) {
						$n++;
						$j++;
						?>	
					<tr>
						<td class="center"><?php echo $j;?></td>
						<td class="center"><?php echo $value['user_name'];?></td>
						<td class="center"><?php echo $value['type_name'];?></td>
						<td class="center"><?php echo $value['mobile'];?></td>
						<td class="center"><?php echo $value['email'];?></td>
						
						 <td class="center"><?php if($value['user_status']==1){?>Active<?php }else{?>Inactive<?php }?></td>
						 
							<td  style="text-align:center">
				<a href="<?php echo $page_url;?>&action=edit&id=<?php echo $value['user_id'];?>"
				 ><i  class="fa fa-edit fa-lg"> </i></a>
				 
						</td>
						
					</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			</div>
 <?php }?>
</div>
</div>
