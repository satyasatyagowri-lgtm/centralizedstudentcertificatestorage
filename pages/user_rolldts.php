<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/user_rolldts.php");

			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];

			$page_url="home.php?p=user_rolldt";

			$obj_press = new insert_update();						
			if($id && $action=="delete"){			
			$delmsg=$obj_press->delete_year($id);
			}			

			if(isset($_POST['btn_save_data']) || $action == "edit" || $action == "status") {
			$mode ="Add";
			if($_POST) $data=$_POST;
			$data = remove_slashes($data);
			$msg=array();	

			if(isset($_POST['btn_save_data'])) {
			extract($_POST);
 			$msg = $obj_press->userroll_save($data,$id);
			}
			}



 			?>
<script>

  function valid(){
$('.TempOwner input,textarea').removeClass('valid');
 $('.valid').css('border','1px solid red');
	  var flag=1;
      $('.valid').each(function(){
	       	  var error= $(this).parent().find('#error');
			  if($(this).val()=='')
			  {
			   flag=0; 
              error.html('Missing '+$(this).attr('title'));
 			  $(this).css('border','1px solid red');
			  }
			  else
			  {
			    $(this).css('border','1px solid #ccc');
				error.html('');
			  }
	  });
 	   if(flag==1)
	   { 
         $('.glass').fadeIn();	
	     $('.loadimg').show();
	     $(' #frm1 ').submit();  
	   }
	}
	function get_chkallcopmos(){
	 if($("#all_components").is(":checked"))
	  $(".allcompos").prop('checked',true);
	 else $(".allcompos").prop('checked',false);
	}
	
	function get_mainmenu(id){
	 if($("#mmenu_id"+id).is(":checked"))
	  $(".firstsub"+id).prop('checked',true);
	 else $(".firstsub"+id).prop('checked',false);
	}
	
	function get_smainmenu(id){
	 if($("#smenu_id"+id).is(":checked"))
	  $(".scndsub"+id).prop('checked',true);
	 else $(".scndsub"+id).prop('checked',false);
	}
	
	
	//get_mainmenu(mmenu_id,firstsub get_smainmenu(smenu_id,scndsub
</script>






<style>
   /*   #timePicker {
  padding: 8px;
  width: 150px;
}

.time-box {
  display: none;
  position: absolute;
  background: #fff;
  border: 1px solid #ccc;
  padding: 10px;
  margin-top: 5px;
}

.time-row select {
  padding: 5px;
}*/



body {
  font-family: Arial, sans-serif;
  padding: 40px;
}

input {
  padding: 10px;
  font-size: 18px;
  width: 190px;
}

#output {
  margin-top: 15px;
  font-weight: bold;
}



</style>














<script>
const input = document.getElementById("time");
const output = document.getElementById("output");

// Set default
input.value = "12:00 AM";
update();

input.addEventListener("input", formatTime);
input.addEventListener("click", toggleAmPm);
input.addEventListener("blur", update);

function formatTime() {
  let v = input.value.toUpperCase().replace(/[^0-9APM: ]/g, "");

  // Auto colon
  if (v.length === 2 && !v.includes(":")) {
    v += ":";
  }

  // Auto space before AM/PM
  if (v.match(/\d{2}:\d{2}(A|P)$/)) {
    v = v.slice(0, 5) + " " + v.slice(5);
  }

  input.value = v;
}

function toggleAmPm() {
  if (!input.value.includes("AM") && !input.value.includes("PM")) return;

  input.value = input.value.includes("AM")
    ? input.value.replace("AM", "PM")
    : input.value.replace("PM", "AM");

  update();
}

function update() {
  const regex = /^(0[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/;

  if (regex.test(input.value)) {
    output.textContent = "Selected Time: " + input.value;
    output.style.color = "green";
  } else {
    output.textContent = "Invalid time";
    output.style.color = "red";
  }
}
	/*
const timePicker = document.getElementById("timePicker");
const timeBox = document.getElementById("timeBox");

const hours = document.getElementById("hours");
const minutes = document.getElementById("minutes");

// Populate hours
for (let i = 1; i <= 12; i++) {
  hours.innerHTML += `<option value="${i}">${i}</option>`;
}

// Populate minutes
for (let i = 0; i < 60; i++) {
  minutes.innerHTML += `<option value="${i.toString().padStart(2, '0')}">${i.toString().padStart(2, '0')}</option>`;
}

// Show picker
timePicker.addEventListener("click", () => {
  timeBox.style.display = "block";
});

// Set time
function setTime() {
  const h = hours.value;
  const m = minutes.value;
  const ap = document.getElementById("ampm").value;
  timePicker.value = `${h}:${m} ${ap}`;
  timeBox.style.display = "none";
}

// Hide when clicking outside
document.addEventListener("click", function(e) {
  if (!timePicker.contains(e.target) && !timeBox.contains(e.target)) {
    timeBox.style.display = "none";
  }
});
*/
</script>



<div class="tab-header">
    <!-- Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
				<?php if($action=='add' || $action=='edit'){?>
					 <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-clipboard-user"></i> User Form</button></li>
              <li class="nav-item"><a href="<?php echo $page_url;?>"><i class="fa-solid fa-user-friends"></i> Back</a></li>
               <?php }else{?>
				<li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-user-friends"></i> User List</button></li>
			<?php }?>

			 
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#contact"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#settings"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#about"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#extra"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#more"></button></li>
            </ul>

            <!-- Action icons (Desktop) -->
            <div class="action-icons">
              <button class="excel"><i class="fa-solid fa-file-excel"></i></button>
              <button class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
              <button class="print"><i class="fa-solid fa-print"></i></button>
            </div>

            <!-- Action dropdown (Mobile) -->
            <div class="action-dropdown dropdown">
              <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-download"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-success" href="#"><i class="fa-solid fa-file-excel me-2"></i> Excel</a></li>
                <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-file-pdf me-2"></i> PDF</a></li>
                <li><a class="dropdown-item text-primary" href="#"><i class="fa-solid fa-print me-2"></i> Print</a></li>
              </ul>
            </div>
  </div>

                <div class="tab-content">

                  <?php if($action=='add' || $action=='edit'){
					$_SESSION['form_token'] = bin2hex(openssl_random_pseudo_bytes(32));?>
                      <div class="tab-pane fade active show" id="profile">
                      

 		<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">

      <!-- Basic Info -->
      <h5 class="mb-3 border-bottom pb-2">Basic Information</h5>
     
	  <div class="row">
					<div class="col-sm-6">
											<div class="form-group  m-b-0">
												<label for="recipient-name" class="col-form-label">User </label>
												<?php $usrdt=$obj_db->fetchRow("select * from ".TABLE_USER_DETAILS." where user_id='".$_REQUEST['id']."'");
												 $usrpgs=json_decode($usrdt['user_pages'],true);
												 $usrpgs=$usrpgs['pages'];
												 $pagids='';
												 foreach($usrpgs as $k=>$v)
												  $pagids.=$k.',';
												  $trm_pagids=substr($pagids,0,-1);
												  $exp_pgids=explode(',',$trm_pagids);
												?>
												<input type="text" disabled="disabled"  class="form-control form-control-sm " id="user_name"  value="<?php echo $usrdt['user_name'];?>">
											</div>
						</div>
						
					
						</div>

     
								 <?php 
								 $main_menuview=array();$compids='';
								  $get_components=$obj_db->qry("select * from ".TABLE_COMPONENTS." order by mainview_order asc,menu_view asc,component_id asc");
							
								    $menuview=3;
								  $get_pagesview = array_filter($get_components,function($v,$k) use ($menuview){
  return $v['menu_view'] == $menuview;
},ARRAY_FILTER_USE_BOTH);
			 foreach($get_components as $k=>$v)
			   $compids.=$v['component_id'].',';
			  $trm_lstcoma=substr($compids,0,-1);
			  $exp_compids=explode(',',$trm_lstcoma);
             $scndsubarr=array();	
								 
								 
 		   $arr1=array();$arr1_cmpids=array();$scndsub_cmpids=array();
		   $first_subchild=array();$scnd_sub=array();$scnd_subclild=array();
		    foreach($exp_compids as $k=>$v){
			$compnam_arrkey=array_search($v, array_column($get_components,'component_id'));
			$parentcmp_arrkey=array_search($get_components[$compnam_arrkey]['parent_component'], array_column($get_components,'component_id'));
			 if($get_components[$compnam_arrkey]['menu_view']==3)
              {
			    if($get_components[$parentcmp_arrkey]['menu_view']==1){
					if($get_components[$compnam_arrkey]['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
 				 $first_subchild[$get_components[$compnam_arrkey]['parent_component']][]=array('firstmenu_id'=>$get_components[$compnam_arrkey]['component_id'],'firstmenu_name'=>$get_components[$compnam_arrkey]['component_name'],'controller_name'=>$get_components[$compnam_arrkey]['controller_name'],'page_name'=>$get_components[$compnam_arrkey]['page_name'],'first_subveiw_order'=>$get_components[$compnam_arrkey]['first_subveiw_order'],'menu_view'=>$get_components[$compnam_arrkey]['menu_view'],'is_menu'=>$get_components[$compnam_arrkey]['is_menu'],'is_default_check'=>$get_components[$compnam_arrkey]['is_default_check'],'ischecked'=>$ischecked,'is_havescnd_sub'=>0);
				 if(!in_array($get_components[$compnam_arrkey]['parent_component'],$arr1_cmpids))
				 {
				   $arr1_cmpids[]=$get_components[$compnam_arrkey]['parent_component'];
				   $arr1[$get_components[$compnam_arrkey]['parent_component']]=array('menu_name'=>$get_components[$parentcmp_arrkey]['component_name'],'menu_id'=>$get_components[$parentcmp_arrkey]['component_id'],'logo_name'=>$get_components[$parentcmp_arrkey]['logo_name'],'mainview_order'=>$get_components[$parentcmp_arrkey]['mainview_order']);
				 }
				 }
				 
				 
             elseif($get_components[$parentcmp_arrkey]['menu_view']==2)
              {
			  $mparentcmp_arrkey=array_search($get_components[$parentcmp_arrkey]['parent_component'], array_column($get_components,'component_id'));
 			     if($get_components[$compnam_arrkey]['menu_view']==3){
					if($get_components[$compnam_arrkey]['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
				 $scnd_subclild[$get_components[$parentcmp_arrkey]['component_id']][]=array('firstmenu_id'=>$get_components[$compnam_arrkey]['component_id'],'firstmenu_name'=>$get_components[$compnam_arrkey]['component_name'],'controller_name'=>$get_components[$compnam_arrkey]['controller_name'],'page_name'=>$get_components[$compnam_arrkey]['page_name'],'second_subveiw_order'=>$get_components[$compnam_arrkey]['second_subveiw_order'],'is_menu'=>$get_components[$compnam_arrkey]['is_menu'],'is_default_check'=>$get_components[$compnam_arrkey]['is_default_check'],'ischecked'=>$ischecked);
				 }
				 
				 if(!in_array($get_components[$mparentcmp_arrkey]['component_id'],$arr1_cmpids))
				 {
				   $arr1_cmpids[]=$get_components[$mparentcmp_arrkey]['component_id'];
				   $arr1[$get_components[$mparentcmp_arrkey]['component_id']]=array('menu_name'=>$get_components[$mparentcmp_arrkey]['component_name'],'menu_id'=>$get_components[$mparentcmp_arrkey]['component_id'],'logo_name'=>$get_components[$mparentcmp_arrkey]['logo_name'],'mainview_order'=>$get_components[$mparentcmp_arrkey]['mainview_order']);
				 }
				 
				if(!in_array($get_components[$parentcmp_arrkey]['component_id'],$scndsub_cmpids))
				 {
				   $scndsub_cmpids[]=$get_components[$parentcmp_arrkey]['component_id'];
				   $first_subchild[$get_components[$parentcmp_arrkey]['parent_component']][]=array('firstmenu_id'=>$get_components[$parentcmp_arrkey]['component_id'],'firstmenu_name'=>$get_components[$parentcmp_arrkey]['component_name'],'controller_name'=>'','page_name'=>'','first_subveiw_order'=>$get_components[$parentcmp_arrkey]['first_subveiw_order'],'menu_view'=>$get_components[$parentcmp_arrkey]['menu_view'],'is_havescnd_sub'=>1);
				 }
			  } 
			  }
			  
   			}
 			array_multisort( array_column($arr1, "mainview_order"), SORT_ASC, $arr1 );
			
			
 			foreach($arr1 as $k1=>$v1){
			 $first_subarr=array();
			 array_multisort( array_column($first_subchild[$v1['menu_id']], "menu_view"), SORT_DESC, $first_subchild[$v1['menu_id']] );
 			$cntfristsubardefalutchkcnt=0;
			 foreach($first_subchild[$v1['menu_id']] as $k2=>$v2){
              if($v2['is_default_check']==0)
				$cntfristsubardefalutchkcnt++;
			 $scndsubchldarr=array();
			 if($v2['is_havescnd_sub']==0)
			 {
				if($v2['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
			  $first_subarr[]=array('firstmenu_id'=>$v2['firstmenu_id'],'firstmenu_name'=>$v2['firstmenu_name'],'controller_name'=>$v2['controller_name'],'page_name'=>$v2['page_name'],'first_subveiw_order'=>$v2['first_subveiw_order'],'is_default_check'=>$v2['is_default_check'],'ischecked'=>$ischecked,'is_havescnd_sub'=>0);
			  
			 if(count($allsubcompnetsarr[$v2['firstmenu_id']])==0)
			  $allsubcompnetsarr[$v2['firstmenu_id']]=array('component_id'=>$v2['firstmenu_id'],'componenttitle'=>$v2['firstmenu_name'],'controller_name'=>$v2['controller_name'],'pag'=>$v2['page_name'],'ischecked'=>$ischecked);
			}  else{
			 $cntscntsubardefalutchkcnt=0;
			 foreach($scnd_subclild[$v2['firstmenu_id']] as $k3=>$v3){
				if($v3['is_default_check']==0)
				$cntscntsubardefalutchkcnt++;
			if($v3['is_default_check']==1)
						$ischecked=true;
				    else $ischecked=false;
			  $scndsubchldarr[]=array('scndmenu_id'=>$v3['firstmenu_id'],'firstmenu_name'=>$v3['firstmenu_name'],'controller_name'=>$v3['controller_name'],'page_name'=>$v3['page_name'],'second_subveiw_order'=>$v3['second_subveiw_order'],'is_default_check'=>$v3['is_default_check'],'ischecked'=>$ischecked,'is_havescnd_sub'=>0);
			 if(count($allsubcompnetsarr[$v3['firstmenu_id']])==0)
			  $allsubcompnetsarr[$v3['firstmenu_id']]=array('component_id'=>$v3['firstmenu_id'],'componenttitle'=>$v3['firstmenu_name'],'controller_name'=>$v3['controller_name'],'pag'=>$v3['page_name'],'ischecked'=>$ischecked);  
			}
			  array_multisort( array_column($scndsubchldarr, "second_subveiw_order"), SORT_ASC, $scndsubchldarr );
			  $isscndmenuvisable=0;
			 if($cntscntsubardefalutchkcnt>0)
				$isscndmenuvisable=1;
			  $first_subarr[]=array('firstmenu_id'=>$v2['firstmenu_id'],'firstmenu_name'=>$v2['firstmenu_name'],'controller_name'=>'','page_name'=>'','first_subveiw_order'=>$v2['first_subveiw_order'],'is_havescnd_sub'=>$v2['is_havescnd_sub'],'scndsub'=>$scndsubchldarr,'ismenuvisable'=>$isscndmenuvisable);
			  }
			 }
			 array_multisort( array_column($first_subarr, "first_subveiw_order"), SORT_ASC, $first_subarr );
			$isfrstmenuvisable=0;
			 if($cntfristsubardefalutchkcnt>0)
				$isfrstmenuvisable=1;
			  
			 $main_menuview[]=array('menu_name'=>$v1['menu_name'],'menu_id'=>$v1['menu_id'],'logo_name'=>$v1['logo_name'],'firstsub'=>$first_subarr,'ismenuvisable'=>$isfrstmenuvisable);
			}
			
			  // echo '<pre>';print_r($main_menuview);echo '</pre>';
   								 foreach($main_menuview as $kv=>$kvl){
 								// echo $kv.' '.$kvl['menu_name'];?>
							 <?php if($kvl['ismenuvisable']==1){?>
								<div class="form-group card bg-light" style="padding:10px;" >
                                <div class="form-check" <?php if($main_menuview[$kv]['is_default_check']==1){?> style="display:none;" <?php }?>>
                                    <input class="form-check-input   allcompos" type="checkbox" <?php if(in_array($main_menuview[$kv]['menu_id'],$arrcompnent_ids) || $main_menuview[$kv]['is_default_check']==1){?> checked="checked" <?php }?> id="mmenu_id<?php echo $main_menuview[$kv]['menu_id'];?>" value="<?php echo $main_menuview[$kv]['menu_id'];?>" onclick="get_mainmenu(<?php echo $main_menuview[$kv]['menu_id'];?>);">
                                    <label class="form-check-label" for="mmenu_id<?php echo $main_menuview[$kv]['menu_id'];?>"> <?php echo $main_menuview[$kv]['menu_name'];?> </label>
                                </div>
                            </div>
						
						  <div class="row">
						  <?php 
										 foreach($kvl['firstsub'] as $kvlk=>$kvlkv){
							  if($kvlkv['is_havescnd_sub']==0){?>
                                 <div class="form-group col-sm-4 m-t-0"  style="padding-left:60px;  <?php if($kvlkv['is_default_check']==1){?> display:none; <?php }?> ">		
										 
 											 <input type="checkbox"  class="form-check-input  firstsub<?php echo $main_menuview[$kv]['menu_id'];?> allcompos" <?php if(in_array($kvlkv['firstmenu_id'],$exp_pgids) || $kvlkv['is_default_check']==1){?> checked="checked" <?php }?> name="component_id[]" id="firstmenu_id<?php echo $kvlkv['firstmenu_id'];?>"    value="<?php echo $kvlkv['firstmenu_id'];?>"   >
 											 <label class="form-check-label" for="firstmenu_id<?php echo $kvlkv['firstmenu_id'];?>"> <b  ><?php echo $kvlkv['firstmenu_name'];?></b> </label>
 											 </div>
										<?php }else{?>
									 
										<div class="form-group col-sm-12 m-b-0 " style="padding-left:40px; <?php if($kvlkv['is_default_check']==1){?> display:none; <?php }?>"   >
                                <div class="form-check">
                                    <input class="form-check-input allcompos firstsub<?php echo $main_menuview[$kv]['menu_id'];?>" type="checkbox" <?php if(in_array($kvlkv['firstmenu_id'],$arrcompnent_ids) || $kvlkv['is_default_check']==1){?> checked="checked" <?php }?>   id="smenu_id<?php echo $kvlkv['firstmenu_id'];?>" value="<?php echo $kvlkv['firstmenu_id'];?>"  onclick="get_smainmenu(<?php echo $kvlkv['firstmenu_id'];?>);">
                                    <label class="form-check-label" for="smenu_id<?php echo $kvlkv['firstmenu_id'];?>"> <b style="color:#0000FF;"><?php echo $kvlkv['firstmenu_name'];?></b> </label>
                                </div>
                            </div>
						 
						 
							<div class="card-body" > 
							<?php 
										foreach($kvlkv['scndsub'] as $scbk=>$scbv){?>
										<div class="form-group col-sm-4 m-t-0" style="padding-left:60px;  <?php if($scbv['is_default_check']==1){?> display:none; <?php }?> ">		
										 
 											 <input type="checkbox"  class="form-check-input firstsub<?php echo $main_menuview[$kv]['menu_id'];?> scndsub<?php echo $kvlkv['firstmenu_id'];?> allcompos" <?php if(in_array($scbv['scndmenu_id'],$exp_pgids)  || $scbv['is_default_check']==1){?> checked="checked" <?php }?> name="component_id[]" id="firstmenu_id<?php echo $scbv['scndmenu_id'];?> "    value="<?php echo $scbv['scndmenu_id'];?>"   >
 											 <label class="form-check-label" for="mmenu_id<?php echo $scbv['scndmenu_id'];?>"> <b  ><?php echo $scbv['firstmenu_name'];?></b> </label>
 											 </div> 
											 
										<?php }?>
								</div>
									<?php	}
										}?>
										</div>
										<?php }
										}?>
				
				
				
				<div class="col-sm-6">
											<div class="form-group  m-b-0">
												<label for="recipient-name" class="col-form-label">Defalut Page </label>
 												<select    class="form-control form-control-sm valid" id="user_default_page" name="user_default_page">
												<option value="">--Select--</option>
												<?php foreach($get_pagesview as $k=>$v){?>
												<option value="<?php echo $v['page_name'];?>" <?php if($v['page_name']==$usrdt['user_default_page']){?> selected="selected" <?php }?>><?php echo $v['component_name'];?></option>
												<?php }?> 
												</select>
											</div>
						</div>
                
             
				<input type="hidden" name="btn_save_data" value="Update" >
                <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">

      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="button" class="btn btn-success rounded-pill px-4" onclick="valid();">
          <i class="bi bi-check2-circle me-1"></i> Submit
        </button>
      </div>

    </form>

                      </div>
                   <?php }else{?>

                      <div class="tab-pane fade show active" id="home">
                          <div class="card-body">

                          <div class="table-responsive">
                          <!-- 🔍 Search Box -->
                          
                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Users List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>
                            <table id="patientTable" class="table align-middle text-dark small">
                              <thead>
                                <tr>
                                  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Full Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">User Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">Roll <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                	<?php	
					$yrdts=$obj_db->qry("SELECT a.*,b.type_name FROM ".TABLE_USER_DETAILS." a,".TABLE_USER_TYPES." b where a.user_type_id=b.user_type_id and user_id!=1 ORDER BY user_name asc");
					$j=1;
                     foreach($yrdts as $usrk=>$usrv){?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                  <td> <?php echo $usrv['full_name'];?></td>
                                  <td><?php echo $usrv['user_name'];?></td>
                                  <td><?php echo $usrv['mobile_no'];?></td>
								   <td><?php echo $usrv['type_name'];?></td>
                                   <td class="action-btns">
                                    <!--<a   class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#delRow">
                                      <i class="fa-regular fa-trash-can"></i>
                                    </a>-->
                                    <a  href="<?php echo $page_url;?>&action=edit&id=<?php echo $usrv['user_id'];?>"  class="btn btn-outline-primary btn-sm" >
                                      <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                  </td>
                                </tr>
                              <?php $j++;}?>
                               
                              </tbody>
                            </table>
                          </div>

                          
                      </div>                    
                       </div>
                        
                     <?php }?>
                     
                </div>