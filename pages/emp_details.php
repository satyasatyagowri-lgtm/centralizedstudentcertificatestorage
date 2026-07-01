<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/emp_details.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=empdts";
			
			$obj_press = new employee_operations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_employee_details($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_employee_details($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->employee_details_savenew($data,$id);
			    }
			}
    //  $weekdys=array('SUB','MON','TUE','WED','THU','FRI','SAT');
       $weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");

      //echo '<pre>'; print_r($_SESSION['linematch_users']);echo '</pre>';
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
	
 </script>

<div class="tab-header">
    <!-- Tabs -->
           <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Emp Details</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> Emp <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
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

                  <?php if($action=='add' || $action=='edit'){?>
				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				
				  <h5 class="mb-3 border-bottom pb-2">Emp Information</h5>
      <div class="row g-3">
           



         <div class="col-md-4">
          <label class="form-label">Full Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="emp_name" name="emp_name" value="<?php echo $data['emp_name'];?>" placeholder="Enter full name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>



        <div class="col-md-4">
          <label class="form-label">Spouse /Father /Mother *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="father_name" name="father_name" value="<?php echo $data['father_name'];?>" placeholder="Enter full name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>


<div class="col-md-4">
          <label class="form-label">Mobile *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" class="form-control  "name="empmobile_no" id="empmobile_no" value="<?php echo $data['empmobile_no'];?>"  pattern="[0-9]{10}" placeholder="10-digit number" required>
            <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Alter Mobile *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" class="form-control  " name="empaltermobile_no" id="empaltermobile_no" value="<?php echo $data['empaltermobile_no'];?>"  pattern="[0-9]{10}" placeholder="10-digit number" required>
            <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Aadhar No</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" class="form-control  "name="aadhar_no" id="aadhar_no" value="<?php echo $data['aadhar_no'];?>" >
            <div class="invalid-feedback">Please enter a valid 12-digit Aadhar number.</div>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            <input type="text" class="form-control" placeholder="Enter address" id="address" name="address" value="<?php echo $data['address'];?>">
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">City/Village</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            <input type="text" class="form-control" placeholder="City" id="alter_address" name="city" value="<?php echo $data['city'];?>">
          </div>
        </div>


        

      </div>

      

				<input type="hidden" name="btn_save_data" value="Update" >
      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="button" class="btn btn-success rounded-pill px-4" onclick="valid();">
          <i class="bi bi-check2-circle me-1"></i> Submit
        </button>
      </div>
            <div align="center" id="tekamterr_msg" style="color:red; font-size:14px; "></div>
<div align="center"  class="error exist_customerno" style="color:red; font-size:14px; "></div>
     </form>

                      </div>
                   <?php }else{
                    
                    ?>

                      <div class="tab-pane fade show active" id="home">
                          <div class="card-body">

                          <div class="table-responsive">
                          <!-- 🔍 Search Box -->

          <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Customer List</h5>
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
                                  <th data-sort="number">
                                    <span class="sort-handle">Img <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Full Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Address <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                 
                                   <th data-sort="number">
                                    <span class="sort-handle">AlterMobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                 
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
	<?php	
						/*$path="includes/cusomer_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/
    				       $empdts=$obj_db->qry("SELECT a.* FROM ".TABLE_EMPUSER_DETAILS."  a ORDER BY emp_name asc");
 				     $j=1;

          $iscncel=0;
				  $actempdts = array_filter($empdts,function($v,$k) use ($iscncel){
					 return $v['is_delete'] == $iscncel;
				   },ARRAY_FILTER_USE_BOTH);
                     foreach($actempdts as $empky=>$empv){
						if(!file_exists('includes/user_img/'.$empv['emp_user_id'].'.jpg'))
							$usrimg="photo.jpg?rand=".rand();
						else $usrimg=$empv['emp_user_id'].'.jpg?rand='.rand();
						?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                  <td><img src="includes/user_img/<?php echo $usrimg;?>" width="80" height="80" alt=""> </td>
                                     <td><?php echo $empv['emp_name'];?></td>
                                     <td><?php echo $empv['address'].' '. $empv['city'];?></td>
                                  <td><?php echo $empv['empmobile_no'];?></td><td><?php echo $empv['empaltermobile_no'];?></td>
                                  <td>
                                    <a  href="<?php echo $page_url;?>&action=edit&id=<?php echo $empv['emp_user_id'];?>"  class="btn btn-outline-primary btn-sm" >
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