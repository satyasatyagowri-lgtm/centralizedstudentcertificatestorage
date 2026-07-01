<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/linedts.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=line";
			
			$obj_press = new insert_update();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_lnie_details($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_line_details($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->lnie_detailssave($data,$id);
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
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="bi bi-diagram-2 me-2" ></i>
 Line Details <?php echo $_SESSION['user_id'];?></button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> Line <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
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
				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				
				  <h5 class="mb-3 border-bottom pb-2">Basic Information</h5>
      <div class="row g-3">
         <div class="col-md-4">
          <label class="form-label">Line Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="line_name" name="line_name" value="<?php echo $data['line_name'];?>" placeholder="Line name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Short Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="short_name" name="short_name" value="<?php echo $data['short_name'];?>" placeholder="Short name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>


      <?php /*?>  <div class="col-md-4">
          <label class="form-label">Line Code *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="line_code" name="line_code" value="<?php echo $data['line_code'];?>" placeholder="Line code" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">City/Village *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="city" name="city" value="<?php echo $data['city'];?>" placeholder="city" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>
        <?php if($action=='add'){?>
            <div class="col-md-4">
          <label class="form-label">User Details</label>
          <div class="input-group" >
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
               
           <select class=" form-control "  name="user_id" id="user_id">
			<option selected disabled value="">Select</option>
			<?php $userdts=$obj_db->qry("SELECT * FROM ".TABLE_USER_DETAILS."   where user_status=1");
			foreach($usrtypes as $usrtypk=>$usrtypv){?>
              <option value="<?php echo $usrtypv['user_id'];?>" <?php if($usrtypv['user_id']==$data['user_id']){?> selected="selected" <?php }?>><?php echo $usrtypv['full_name'].' '.$usrtypv['mobile'];?></option>
            <?php }?>
            </select>
          </div>
        </div>
<?php }?>
       <?php */?>
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
                                  <h5 class="mb-0 fw-bold">Line Details</h5>
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
                                    <span class="sort-handle"> Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Short Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                 
                                 
                                 
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
	<?php	
						/*$path="../includes/user_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/

				       $usr_dtrws=$obj_db->qry("SELECT * FROM ".TABLE_LINE_NAMES." a  ");
				     $j=1;
                     foreach($usr_dtrws as $usrk=>$usrv){
						if(!file_exists('includes/user_img/'.$usrv['user_id'].'.jpg'))
							$usrimg="photo";
						else $usrimg=$usrv['user_id'].'.jpg?rand='.rand();
						?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                   <td><?php echo $usrv['line_name'];?></td>
                                    <td><?php echo $usrv['short_name'];?></td>
                                    <td class="action-btns">
                                    <!--<a   class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#delRow">
                                      <i class="fa-regular fa-trash-can"></i>
                                    </a>-->
                                    <a  href="<?php echo $page_url;?>&action=edit&id=<?php echo $usrv['line_id'];?>"  class="btn btn-outline-primary btn-sm" >
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