<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/line_chitdetails.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=line_chiitdt";
			
			$obj_press = new linchitoperations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_linchitdetails($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_linchit_details($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->linchit_details_savenew($data,$id);
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
 Chit Details</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> Chit <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
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
                   $_SESSION['form_token'] = bin2hex(openssl_random_pseudo_bytes(32));
                    ?>
				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				
				  <h5 class="mb-3 border-bottom pb-2">Emp Information</h5>
      <div class="row g-3">
          <?php  
$getlinedts=$obj_db->qry("select a.*  from ".TABLE_LINE_NAMES." a where a.is_delete=0 order by a.line_name asc");

?>
  <div class="col-md-4">
          <label class="form-label">Line Details</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
          <select class="form-select valid"  id="line_id" name="line_id" >
              <option  value="">--Select Line--</option>
 			<?php
     
			foreach($getlinedts as $lineky=>$linev){?>
              <option value="<?php echo $linev['line_id'];?>" <?php if($linev['line_id']==$data['line_id']){?> selected="selected" <?php }?>><?php echo $linev['line_name'].' '.$linev['line_code'];?></option>
            <?php }?>
            </select>
          </div>
        </div> 

        

        <div class="col-md-4">
          <label class="form-label">Chit Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="line_chit_name" name="line_chit_name"  value="<?php echo $data['line_chit_name'];?>" placeholder="Chit Name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>

         <div class="col-md-4">
          <label class="form-label">Chit Amount *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
            <input type="text" class="form-control valid" id="chit_amount" name="chit_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['chit_amount'];?>" placeholder="Chit Amount" required>
            <div class="invalid-feedback">Please enter Amount.</div>
          </div>
        </div>

      

        <div class="col-md-4">
          <label class="form-label">Chit Date *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid default-date-picker" id="chit_start_date" name="chit_start_date"   value="<?php echo $data['chit_start_date'];?>" placeholder="Date" required>
            <div class="invalid-feedback">Please enter Chit Start Date.</div>
          </div>
        </div>


        <div class="col-md-4">
          <label class="form-label">No of Terms *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-shield"></i></span>
            <input type="text" class="form-control valid" id="no_of_terms" name="no_of_terms" oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['no_of_terms'];?>" placeholder="No Of Terms" required>
            <div class="invalid-feedback">Please enter Chit Amt.</div>
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
                                  <th data-sort="string">
                                    <span class="sort-handle">Line <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Chit Amount <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">TakenDate <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">PaidAmt UptoNow <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                 
                                 
                                 
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
	<?php	
						/*$path="includes/cusomer_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/
    				       $linchitdts=$obj_db->qry("SELECT a.*,b.line_name FROM ".TABLE_LINECHIT_DETAILS."  a,".TABLE_LINE_NAMES." b where a.line_id=b.line_id and a.is_complete=0 ORDER BY line_chit_name asc");
 				     $j=1;

          $iscncel=0;
				  $actchitdts = array_filter($linchitdts,function($v,$k) use ($iscncel){
					 return $v['is_delete'] == $iscncel;
				   },ARRAY_FILTER_USE_BOTH);
                     foreach($actchitdts as $linchitky=>$linchitv){
					 
						?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                  <td><?php echo $linchitv['line_name'];?></td>
                                  <td><?php echo $linchitv['line_chit_name'];?></td>
                                     <td><?php echo $linchitv['chit_amount'];?></td>
                                     <td><?php echo $linchitv['chit_start_date'];?></td><td><?php echo $linchitv['upd_chitpaid_amt'];?></td>
                                  <td>
                                    <a  href="<?php echo $page_url;?>&action=edit&id=<?php echo $linchitv['line_chit_id'];?>"  class="btn btn-outline-primary btn-sm" >
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