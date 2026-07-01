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

/*	  const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
      form.addEventListener('button', event => {alert('');
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })*/

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

<div class="tab-header">
    <!-- Tabs -->
	 <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 User Details</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> User <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
           </ul>

            <!-- Action icons (Desktop) -->
           

            <!-- Action dropdown (Mobile) -->
           <div class="  dropdown" >
              <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-download"></i>
              </button>
            <ul class="dropdown-menu dropdown-menu-end">
    <li >
        <button id="exportExcel" class="excel dropdown-item text-success" href="#">
            <i class="fa-solid fa-file-excel me-2"></i> Excel
        </button>
    </li>

    <li >
        <button id="downloadPdf" class="dropdown-item text-danger">
            <i  class="fa-solid fa-file-pdf me-2"></i> PDF
        </button>
    </li>
</ul>
</div>
  </div>

                <div class="tab-content">

                  <?php if($action=='add' || $action=='edit'){
                    $_SESSION['form_token'] = bin2hex(openssl_random_pseudo_bytes(32));

                    $decode_permislevels=json_decode($data['permission_levels'],true);
                    $is_feepay_datepermis=$decode_permislevels['is_feepaydate_permission'];
				    $feepay_permisvaliddate=$decode_permislevels['paydate_permis_validdte'];
				   
				   $is_concession_permis=$decode_permislevels['is_concession_permission'];
				   $concepermisvaliddate=$decode_permislevels['concession_permis_validdte'];

           $is_changeborrowdate_permis=$decode_permislevels['is_changeborrowdate_permission'];
				   $changeborrowdatepermisvaliddate=$decode_permislevels['changeborrowdate_permis_validdte'];

				   $todaydate=strtotime(date('d-m-Y'));
                    ?>
				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				
				  <h5 class="mb-3 border-bottom pb-2">Basic Information</h5>
      <div class="row g-3">
         <div class="col-md-4">
          <label class="form-label">Full Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="full_name" name="full_name" value="<?php echo $data['full_name'];?>" placeholder="Enter full name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>

     

            <div class="col-md-4">
          <label class="form-label">User Type</label>
          <div class="input-group" >
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
               
           <select class=" form-control valid"  name="user_type_id" id="user_type_id">
			<option selected disabled value="">Select</option>
			<?php  $usrtypcnd=" and user_type_id in(2,3) ";
            if($_SESSION['user_id']=='1')
            $usrtypcnd=" and user_type_id in(1,2,3) ";
       $usrtypes=$obj_db->qry("SELECT * FROM ".TABLE_USER_TYPES."  where status=1 $usrtypcnd");
			foreach($usrtypes as $usrtypk=>$usrtypv){?>
              <option value="<?php echo $usrtypv['user_type_id'];?>" <?php if($usrtypv['user_type_id']==$data['user_type_id']){?> selected="selected" <?php }?>><?php echo $usrtypv['type_name'];?></option>
            <?php }?>
            </select>
          </div>
        </div>
<?php $msger=explode('^',$msg);?>
        <div class="col-md-4">
          <label class="form-label">User Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control valid" placeholder="Enter user name" <?php if($action=='edit'){?> readonly="" <?php }?> title="User Name" type="text" name="user_name" value="<?php echo $data['user_name'];?>" required>
            <div class="error invalid-feedback" id="error">Please enter a username.</div>
                
          </div>
		   <div style="color:red;">
					
  					<?php if($msger[0]=='unameexist' || $msger[1]=='unameexist'){?>
 					*Username already exist
					<?php }?>
				 </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Password *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control valid" placeholder="Enter password" required minlength="6" title="Password" id="pword" type="password" name="user_password" value="<?php if($data['user_password']!='')echo $data['user_password'];else echo '';?>">
            <div class="invalid-feedback">Password must be at least 6 characters.</div>
          </div>
        </div>

		<div class="col-md-4">
          <label class="form-label">Confirm Password *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control valid" placeholder="Enter password" required minlength="6" title="Confirm Password" id="cpword" type="password" name="cuser_password" value="<?php if($data['user_password']!='')echo $data['user_password'];else echo '';?>">
            <div class="invalid-feedback">Password must be at least 6 characters.</div>
          </div>
        </div>
       
       
      </div>

	   <div class="row g-3 mt-3">
        
        <div class="col-md-4">
          <label class="form-label">Upload User Image</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-upload"></i></span>
            <input type="file" class="form-control" id="userImage" name="file" accept="image/*">
          </div>
          <img id="preview" class="preview-img" alt="Preview">
        </div>

        <div class="col-md-4">
          <label class="form-label">Status</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-toggle-on text-success"></i></span>
            <select class="form-select valid"   id="user_status" name="user_status">
              <option  value="">Select</option>
              <option value="1" <?php if($data['user_status']=="1"){?> selected="selected" <?php }?>>Active</option>
              <option value="0" <?php if($data['user_status']=="0"){?> selected="selected" <?php }?>>InActive</option>
            </select>
          </div>
        </div>
      </div>

<input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
   <input type="hidden" name="btn_save_data" value="Update" >
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
                             <div id="exportArea" class="exportArea">
                            <table id="contentToDownload" class="table align-middle text-dark small export-table tablesortsearchable">
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
                                   <th data-sort="string">
                                    <span class="sort-handle">Roll <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
	<?php	
						/*$path="../includes/user_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/

				       $usr_dtrws=$obj_db->qry("SELECT user_id ,a.branch_id ,assign_branch_ids,is_cashier,b.type_name,a.full_name,user_name,a.user_password, mobile,email,user_status 
					     FROM ".TABLE_USER_DETAILS." a,".TABLE_USER_TYPES." b  where b.user_type_id=a.user_type_id and a.user_type_id!=1 and b.status=1");
				     $j=1;
                     foreach($usr_dtrws as $usrk=>$usrv){
						if(!file_exists('includes/user_img/'.$usrv['user_id'].'.jpg'))
							$usrimg="photo.jpg?rand=".rand();
						else $usrimg=$usrv['user_id'].'.jpg?rand='.rand();
						?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                  <td> <img src="includes/user_img/<?php echo $usrimg;?>" width="80" height="80" alt=""> <?php echo $usrv['full_name'];?></td>
                                  <td><?php echo $usrv['user_name'];?></td> 
                                  <td><?php echo $usrv['type_name'];?></td> 
                                   <td class="action-btns">
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
                       </div>
                        
                     <?php }?>
                     
                </div>