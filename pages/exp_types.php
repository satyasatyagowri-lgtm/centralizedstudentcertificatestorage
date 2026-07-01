<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/exp_types.php");
			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];

			$page_url="home.php?p=exp_type";

			$obj_press = new vouchergenerator();						
			

			if($id && $action=="edit"){			
			$data=$obj_press->get_exptypes($id);
			}

			if(isset($_POST['btn_save_data']) || $action == "edit" || $action == "status") {
			$mode ="Add";
			if($_POST) $data=$_POST;
			$data = remove_slashes($data);
			$msg=array();	

			if(isset($_POST['btn_save_data'])) {
			extract($_POST);
			$msg = $obj_press->exptypdts_save($data,$id);
			}
			}

  
			
  $explode_dt=explode("TO", $_GET['dt']); 
	//echo  $explode_dt[0];
 $dt1= $explode_dt[0];
 $dt2= $explode_dt[1];
// echo date('Y-d-m');
 if($_GET['dt']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }
 
 if($_GET['dt']==''){
 $dt=date('d-m-Y').' TO '.date('d-m-Y');
 }
 else {
  $dt=$_GET['dt'];
 }
?>
<script type="text/javascript">
			function getdate()
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&exptype_id='+$("#exptype_id").val()+'&dt='+dt;
	}
   </script>
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
	}function payment_types(id){
  if(id!=1){
    $(".payment_div").show();
	$("#cheque_date").addClass('valid');
	$("#cheque_num").addClass('valid');
	$("#bank_name").addClass('valid');
	}
  else {
  $(".payment_div").hide();
  $("#cheque_date").removeClass('valid');
	$("#cheque_num").removeClass('valid');
	$("#bank_name").removeClass('valid');
  }
  }

  function get_expensedts(){
	location.href='<?php echo $page_url;?>&dt='+$("#dateRange").val()+'&line_id='+$("#line_id").val();
  }
 </script>

 
<div class="tab-header">
    <!-- Tabs -->
          <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Expense Types</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> Expense <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
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
			 
	  <h5 class="mb-3 border-bottom pb-2">Exp Form</h5>
					<div class="row g-3 mt-3">
<div class="col-md-4">
          <label class="form-label">Exp Category *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-cart-shopping"></i></span>
           <?php
				$expcatgs =  $obj_db->get_qresult("select * from  ".TABLE_EXPENDITURE_CATEGORY." where exp_catg_id in(2,5)");?>
				<select class="chosen-select form-control form-control-sm valid" name="exp_catg_id" id="exp_catg_id" >
				<option value="" >--Select Exp--  </option>
				<?php foreach($expcatgs as $expcatgky=>$expcatgv){?>
				<option value="<?php echo $expcatgv['exp_catg_id'];?>" <?php if($data['exp_catg_id']==$expcatgv['exp_catg_id']){?> selected="selected" <?php }?> ><?php echo $expcatgv['exp_category'];?></option>
				<?php }?>
				</select>
            <div class="invalid-feedback">Please Select Category.</div>
          </div>
        </div>
     </div>
		
	 <div class="row  g-3 mt-3">
				<div class="col-md-4">
          <label class="form-label"> Type</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-receipt"></i></span>
					<input name="exp_name" id="exp_name" autocomplete="off"  class="form-control form-control-sm valid " title="Exp Type"  value="<?php echo $data['exp_name'];?>" >
					<div id="error1" class="error"></div>
					</div>
					</div>	
                </div>
                <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
				<input type="hidden" name="btn_save_data" value="Update" >
      <!-- Submit Button -->
	   <?php //if(count($getcustprevamtdts)==0){?>
      <div class="text-center mt-4">
        <button type="button" class="btn btn-success rounded-pill px-4" onclick="valid();">
          <i class="bi bi-check2-circle me-1"></i> Submit
        </button>
      </div>
	  <?php //}?>
            <div align="center" id="tekamterr_msg" style="color:red; font-size:14px; "></div>
    </form>
<?php }else{?>
		
<div class="table-responsive">
                          <!-- 🔍 Search Box -->

                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Expense List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>
				


                            <table id="patientTable" class="table align-middle text-dark small">




						<?php	
						if($_SESSION['user_type']!='account')
						 $usrcnd="";
						else $usrcnd=" and b.user_id='".$_SESSION['user_id']."' and a.line_id in(".$_SESSION['assign_line_ids'].")";
                 	$custtakamtqry=$obj_db->qry("SELECT a.*,b.is_bank_person,b.exp_category FROM ".TABLE_EXPENDITURE_TYPE." a,".TABLE_EXPENDITURE_CATEGORY." b where a.exp_catg_id=b.exp_catg_id  order by a.exp_name desc");
?>						<thead>
					<tr >
						  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
								  <th data-sort="number">
                                    <span class="sort-handle">Category <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							 <th data-sort="string">
                                    <span class="sort-handle">Exp Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							
 						<th>Action</th>
					</tr>
					</thead>
					<tbody role="alert" aria-live="polite" aria-relevant="all">
						<?php	
						$j=1;
						foreach($custtakamtqry as $custky=>$custv) {
						?>	
					<tr>
						<td ><?php echo $j;?></td>
 						<td><?php echo  $custv['exp_category'];?></td><td><?php echo  $custv['exp_name'];?></td>
						<td>
 			<a  href="<?php echo $page_url;?>&action=edit&id=<?php echo $custv['exp_type_id'];?>"     class="btn btn-outline-primary btn-sm"  > <i class="fa-regular fa-pen-to-square"></i></a>
			</td>
                     </tr>
						<?php  $j++;} ?>
					</tbody>
				</table>
			<?php }?>
                      </div>
 