<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/line_chitdetails.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=line_chiitpaid_dt";
			
			$obj_press = new linchitoperations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_linchitpaiddetails($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_linchitpaid_details($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->linchitpaid_details_savenew($data,$id);
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
	
  function get_chitdts(line){
 $('.glass').fadeIn();	
    $('.loadimg').show();
     $.ajax({	 
					  url:'includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'get_linecithdts','line_id':$("#line_id").val()},
					 success:function(data)
					  { var dt=data.split('^');
					   $('.glass').fadeOut();	
	                   $('.loadimg').hide();
                        $("#line_chit_id").html(dt[0]);
                         $("#lieusrdts").html(dt[1]);
					  }
					  });
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

                  <?php if($action=='add' || $action=='edit'){?>
				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				
				  <h5 class="mb-3 border-bottom pb-2">Line Chit Paid Form</h5>
      <div class="row g-3">
          <?php  
$getlinedts=$obj_db->qry("select a.*  from ".TABLE_LINE_NAMES." a where a.is_delete=0 order by a.line_name asc");

?>
  <div class="col-md-4">
          <label class="form-label">Line Details</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
          <select class="form-select valid"  id="line_id" name="line_id" onchange="get_chitdts(this.value);" >
              <option  value="">--Select Line--</option>
 			<?php
     
			foreach($getlinedts as $lineky=>$linev){?>
              <option value="<?php echo $linev['line_id'];?>" <?php if($linev['line_id']==$data['line_id']){?> selected="selected" <?php }?>><?php echo $linev['line_name'].' '.$linev['line_code'];?></option>
            <?php }?>
            </select>
          </div>
        </div> 



        <div class="col-md-4">
          <label class="form-label">Chit Details</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
          <select class="form-select valid"  id="line_chit_id" name="line_chit_id" >
              <option  value="">--Select Chit--</option>
 		       </select>
          </div>
        </div> 

         <div class="col-md-4">
          <label class="form-label">Paid Date *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid default-date-picker" autocomplete="off" id="paid_date" name="paid_date"  value="<?php echo $data['paid_date'];?>" placeholder="Date" required>
            <div class="invalid-feedback">Please enter Chit Start Date.</div>
          </div>
        </div>

         <div class="col-md-4">
          <label class="form-label">Paid Amount *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="paid_amount" autocomplete="off" name="paid_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['paid_amount'];?>" placeholder="Paid Amount" required>
            <div class="invalid-feedback">Please enter Paid Amount.</div>
          </div>
        </div>

      

       


        <div class="col-md-4">
          <label class="form-label">Terms *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="term_id" autocomplete="off" name="term_id" oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['term_id'];?>" placeholder="Paid Terms" required>
            <div class="invalid-feedback">Please enter Term.</div>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Description</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <textarea  class="form-control valid" id="description" name="description"><?php echo $data['description'];?></textarea>
            <div class="invalid-feedback">Please enter Description.</div>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">User *</label>
          <div class="input-group">
            
				<select class="chosen-select form-control form-control-sm valid select2-single" name="by_user_id" id="lieusrdts"  >
				<option value="" >--Select User--  </option>
				
				</select>
            <div class="invalid-feedback">Please Select User.</div>
          </div>
        </div>

         <div class="col-md-2 col-xs-4 mb-2"><b>Payment Type</b>
						<select class="form-control form-control-sm valid2" id="payment_type"  title="Select Payment type" onChange="payment_types(this.value);"   name="pay_type_id" >
 					<?php $bankname_qry=$obj_db->qry("select a.* from  ".TABLE_PAYMENT_TYPE." a  ");
						  foreach($bankname_qry as $ptypky=>$ptypv){?>
					<option value="<?php echo $ptypv['pay_type_id'];?>"><?php echo $ptypv['pay_name'];?></option>
						<?php }?>
						   </select>
						   <div id="error2" class="error"></div>
						   </div>


        
</div>  


 <div class="row  g-3 mt-3 payment_div" id="payment_div" style="display:none" >
                 <div class="col-md-3">
					<div class="form-group">
					<p class="card-title mb-1 text-muted">Tr. Date</p>
					<input type="date" class="form-control default-date-picker " onchange="get_cashlesstransactiondate(this.value)" title="Date" id="cheque_date" autocomplete="off" name="cheque_date" />

					<div id="error1" class="error cashlessdt_err"></div>
					
					</div>
					</div>
					<input type="hidden" id="curmondtsts" value="0">
					<?php /*?>	
						<div class="col-md-3">
					<div class="form-group">
					<p class="card-title mb-1 text-muted">Received Bank Name</p>
					<select name="received_bank_id" id="received_bank_id" class="form-control form-control-sm ">
						<option value="">--Select--</option>
						<?php $get_bankdts=$obj_db->qry("select a.* from ".TABLE_EXPENDITURE_TYPE." a,".TABLE_EXPENDITURE_CATEGORY." b where  a.exp_catg_id=b.exp_catg_id and b.is_bank_person=2");
						foreach($get_bankdts as $key=>$value){?>
						<option value="<?php echo $value['exp_type_id'];?>"><?php echo $value['exp_name'];?></option>
						<?php }?> 
                     </select>
					<div id="error1" class="error"></div>
					</div>
					</div>
                   <?php */?>
					<div class="col-md-3">
					<div class="form-group">
					<p class="card-title mb-1 text-muted">Bank Name</p>
					<input type="text" class="form-control" title="Bank Name" name="bank_name" id="bank_name" />
					<div id="error1" class="error"></div>
					</div>
					</div>
					
					<div class="col-md-3">
					<div class="form-group">
					<p class="card-title mb-1 text-muted">Cheque/ Tr. Number</p>
					<input type="text" class="form-control" title="Cheque Number" name="transaction_no" id="cheque_num" />
					<div id="error1" class="error"></div>
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
                                  <h5 class="mb-0 fw-bold">Line Chit Paid List</h5>
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
                                    <span class="sort-handle">Chit Amount <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">PaidAmt <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">PaidDate <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                    <th data-sort="string">
                                    <span class="sort-handle">EnterBy <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                 
                                 
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
	<?php	
						/*$path="includes/cusomer_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/
     				       $linchitdts=$obj_db->qry("SELECT c.*,b.line_name,a.chit_amount,if(c.enter_by='".$_SESSION['user_id']."','block','none') as delsts,date_format(str_to_date(c.paid_date,'%Y-%m-%d'),'%d-%m-%Y') as pdt,d.user_name FROM ".TABLE_LINECHIT_DETAILS."  a,".TABLE_LINE_NAMES." b,".TABLE_LINE_CHITAMT_PAIDDETAILS." c,".TABLE_USER_DETAILS." d where c.enter_by=d.user_id and a.line_id=c.line_id and a.line_chit_id=c.line_chit_id and a.line_id=b.line_id and a.is_complete=0 ORDER BY line_chit_name asc");
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
                                     <td><?php echo $linchitv['chit_amount'];?></td>
                                     <td><?php echo $linchitv['paid_amount'];?></td><td><?php echo $linchitv['pdt'];?></td>
                                     <td><?php echo $linchitv['user_name'];?></td>
                                  <td>
                                    <a style="display: <?php echo $linchitv['delsts'];?>;color:#FF0000;" onClick="if(!confirm('Are you sure want to Delete this...?')) return false;" href="<?php echo $page_url;?>&action=delete&id=<?php echo $linchitv['id'];?>"  class="danger "  >
                                      <i class="fa-solid fa-trash-can"></i>
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