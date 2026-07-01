<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/expenditure.php");
			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];

			$page_url="home.php?p=expense";

			$obj_press = new vouchergenerator();						
			if($id && $action=="delete"){			
			$delmsg=$obj_press->exp_cencel($id);
			}

			if($id && $action=="edit"){			
			$data=$obj_press->get_expenditurenew($id);
			}

			if(isset($_POST['btn_save_data']) || $action == "edit" || $action == "status") {
			$mode ="Add";
			if($_POST) $data=$_POST;
			$data = remove_slashes($data);
			$msg=array();	

			if(isset($_POST['btn_save_data'])) {
			extract($_POST);
			$msg = $obj_press->expnewdts_save($data,$id);
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
$dt1=date('d-m-Y');$dt2=date('d-m-Y');
 if($_REQUEST['frmdt']!='')
	$dt1=$_REQUEST['frmdt'];
 if($_REQUEST['todt']!='')
	$dt2=$_REQUEST['todt'];
?>
<script type="text/javascript">
			function getdate()
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&exptype_id='+$("#exptype_id").val()+'&dt='+dt;
	}
   </script>
<script>

  function valid(){
//$('.TempOwner input,textarea').removeClass('valid');
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
	
	function get_lineusers(id){
$('.glass').fadeIn();	
	    $('.loadimg').show();
	     $.ajax({	 
					  url:'includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'get_linecitys_lstenterdate','line_id':$("#line_id").val()},
					 success:function(data)
					  {//alert(data);
					  var dt=data.split('^');
					   if(data!=''){
					  $('.glass').fadeOut();	
	                  $('.loadimg').hide();
             $("#upayuser_id").html(dt[2]);
 					 
                       }
 				     }
			});
	}
	
	
	function payment_types(id){
  if(id!=1){
    $(".payment_div").show();
	$("#cheque_date").addClass('valid');
	$("#cheque_num").addClass('valid');
	$("#bank_name").addClass('valid');

	$('.glass').fadeIn();	
	    $('.loadimg').show();
	     $.ajax({	 
					  url:'includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'get_linecitys_lstenterdate','line_id':$("#line_id").val()},
					 success:function(data)
					  {//alert(data);
					  var dt=data.split('^');
					   if(data!=''){
					  $('.glass').fadeOut();	
	                  $('.loadimg').hide();
					  $("#city_id").html(dt[0]);
					  $("#lstentdt").val(dt[1]);
            $("#upayuser_id").html(dt[2]);
			 $("#upayuser_id").addClass('valid');
					 if(dt[1]!=''){
					   $("#last_entdate").html('Last Enter Date:'+dt[1]);
					   
					   }
                       }
 				     }
			});

	
	}
  else {
	$("#upayuser_id").val('');
	$("#upayuser_id").removeClass('valid');
  $(".payment_div").hide();
  $("#cheque_date").removeClass('valid');
	$("#cheque_num").removeClass('valid');
	$("#bank_name").removeClass('valid');
  }
  }

  function get_expensedts(){
	location.href='<?php echo $page_url;?>&frmdt='+$("#frmdt").val()+'&todt='+$("#todt").val()+'&line_id='+$("#line_id").val();
  }
 </script>

 
<div class="tab-header">
    <!-- Tabs -->
          <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Expense Details</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?> Expense</button></li>
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
          <label class="form-label">Exp Type *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
           <?php
				$exptypsqry =  $obj_db->get_qresult("select * from  ".TABLE_EXPENDITURE_TYPE." where is_bank_person!=1");?>
				<select class="chosen-select form-control form-control-sm valid" name="exp_type_id" id="exp_type_id" >
				<option value="" >--Select Exp--  </option>
				<?php while($exptypsqry_rw=$obj_db->fetchArray($exptypsqry)){?>
				<option value="<?php echo $exptypsqry_rw['exp_type_id'];?>" <?php if($_REQUEST['exp_type_id']==$exptypsqry_rw['exp_type_id']){?> selected="selected" <?php }?> ><?php echo $exptypsqry_rw['exp_name'];?></option>
				<?php }?>
				</select>
            <div class="invalid-feedback">Please Select Expense.</div>
          </div>
        </div>
     </div>
		
	 
	 <div class="row  g-3 mt-3" style="display:<?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management'){?>block; <?php }else{?> none; <?php }?>">
				<div class="col-md-4">
          <label class="form-label"> Date</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
			 
 					<input type="text"  name="exp_date" id="exp_date" autocomplete="off" value="<?php echo date('d-m-Y');?>"   class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
					<div id="error1" class="error"></div>
					</div>
					</div>	
                </div>


				
	 
	 <div class="row  g-3 mt-3">
				<div class="col-md-4">
          <label class="form-label"> Line</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
			<?php   
			if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management')
				 $linedtcnd="    ";
			else
             $linedtcnd=" and  line_id in(0".$_SESSION['assign_line_ids'].") ";
			$getlinedts=$obj_db->qry("select * from ".TABLE_LINE_NAMES." where is_delete=0 $linedtcnd ");
			if(count($getlinedts)>1)
				$selline="";
			else $sellineid=$getlinedts[0]['line_id'];?>
 					<select  name="line_id" id="line_id" autocomplete="off"   class="form-control form-control-sm valid " title="Exp amount" onchange="get_lineusers(this.value);" >
						<option value="">--Select--</opttion>
					<?php foreach($getlinedts as $lineky=>$linev){?>
						<option value="<?php echo $linev['line_id'];?>" ><?php echo $linev['line_name'];?></option>
					<?php }?>
				    </select>
					<div id="error1" class="error"></div>
					</div>
					</div>	
                </div>

	 <div class="row  g-3 mt-3">
				<div class="col-md-4">
          <label class="form-label"> Amount</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
					<input name="amount" id="amount" autocomplete="off"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" class="form-control form-control-sm valid custgivpays" title="Exp amount"  value="<?php echo $data['amount'];?>" >
					<div id="error1" class="error"></div>
					</div>
					</div>	
                </div>
          

				<div class="row  g-3 mt-3">
				<div class="col-md-4">
          <label class="form-label"> Narration</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-comment-dots"></i></span>
					<textarea name="reason" id="reason" autocomplete="off"   class="form-control form-control-sm valid " title="Narration"  ><?php echo $data['reason'];?></textarea>
					<div id="error1" class="error"></div>
					</div>
					</div>	
                </div>
					
				<div class="row  g-3 mt-3">
							 <div class="col-md-2 col-xs-4 mb-2"><b>Payment Type</b>
						<select class="form-control form-control-sm valid2" id="payment_type"  title="Select Payment type" onChange="payment_types_bk(this.value);"   name="pay_type_id" >
 					<?php $bankname_qry=$obj_db->qry("select a.* from  ".TABLE_PAYMENT_TYPE." a  ");
						  foreach($bankname_qry as $ptypky=>$ptypv){?>
					<option value="<?php echo $ptypv['pay_type_id'];?>"><?php echo $ptypv['pay_name'];?></option>
						<?php }?>
						   </select>
						   <div id="error2" class="error"></div>
						   </div>
</div>  

					
 					  <div class="row  g-3 mt-3 payment_div" id="payment_div"  >
<div class="col-md-3">
					<div class="form-group">
					<p class="card-title mb-1 text-muted">Select User</p>
					<select name="upayuser_id"  id="upayuser_id"  class="form-control form-control-sm valid" title="Get Upi User"  >
          <option value="">--Select--</option>
        </select>

					<div id="error1" class="error cashlessdt_err"></div>
					
					</div>
					</div>

                <!-- <div class="col-md-3">
					<div class="form-group">
					<p class="card-title mb-1 text-muted">Tr. Date</p>
					<input type="date" class="form-control default-date-picker " onchange="get_cashlesstransactiondate(this.value)" title="Date" id="cheque_date" autocomplete="off" name="cheque_date" />

					<div id="error1" class="error cashlessdt_err"></div>
					
					</div>
					</div>-->
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
					</div><?php */?>
					
					
					 </div>
					
				
				<input type="hidden" name="btn_save_data" value="Update" >
				<input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
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



						  <div class="row g-3 " >
				<div class="col-md-3" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 					<input type="text"  id="frmdt"  autocomplete="off" value="<?php echo $dt1;?>"   class="form-control form-control-sm valid default-date-picker " title="Frm Date"  >
 					</div>
					</div>
					

					<div class="col-md-3" >
                <label class="form-label">To Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 					<input type="text"  id="todt"  autocomplete="off" value="<?php echo $dt2;?>"    class="form-control form-control-sm valid default-date-picker " title="To Date"  >
 					</div>
					</div>
					
 		  <div class="col-md-3" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>">
          <label class="form-label"> Line</label>
          <div class="input-group">
 			<?php   
			if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management')
				 $linedtcnd="    ";
			else
             $linedtcnd=" and  line_id in(0".$_SESSION['assign_line_ids'].") ";
			$getlinedts=$obj_db->qry("select * from ".TABLE_LINE_NAMES." where is_delete=0 $linedtcnd ");
			?>
 					<select  name="line_id" id="line_id" autocomplete="off"   class="form-control form-control-sm valid " title="Exp amount"  >
						<option value="">ALL LINES</opttion>
					<?php foreach($getlinedts as $lineky=>$linev){?>
						<option value="<?php echo $linev['line_id'];?>" <?php if($linev['line_id']==$_REQUEST['line_id']){?> selected="selected" <?php }?>><?php echo $linev['line_name'];?></option>
					<?php }?>
				    </select>
					<div id="error1" class="error"></div>
					</div>
					</div>	


					<div class="col-md-3" >
                <label class="form-label"> &nbsp;</label>
                 <div class="input-group">
				    <button type="button" class="btn btn-success rounded-pill px-4" onclick="get_expensedts();">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                  </button>
 					</div>
	                </div>
					</div>
                          
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





			
			

         
				


                            <table id="patientTable" class="table align-middle text-dark small tablesortsearchable">




						<?php	
						if($_REQUEST['line_id']>0)
						 $usrcnd=" and a.line_id in(0".$_REQUEST['line_id'].") ";
						elseif($_SESSION['user_type']!='account')
						 $usrcnd="";
						else $usrcnd=" and a.user_id='".$_SESSION['user_id']."' and a.line_id in(".$_SESSION['assign_line_ids'].")";
                  	$custtakamtqry=$obj_db->qry("SELECT a.id,reason,voucher_no,a.amount,b.exp_name,b.exp_name,c.user_name,date_format(str_to_date(a.exp_date,'%Y-%m-%d'),'%d-%m-%Y') as expdt,d.line_name FROM ".TABLE_EXPENDITURE." a,".TABLE_EXPENDITURE_TYPE." b,".TABLE_USER_DETAILS." c,".TABLE_LINE_NAMES." d where a.user_id=c.user_id and a.line_id=d.line_id and (str_to_date(a.exp_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y') or str_to_date(a.cancel_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y')) and a.exp_type_id=b.exp_type_id and a.user_id=c.user_id $usrcnd and a.is_cancel=0 order by a.id desc");
?>						<thead>
					<tr >
						  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							 <th data-sort="string">
                                    <span class="sort-handle">Exp Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							<th data-sort="number">
                                    <span class="sort-handle">lION <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							<th data-sort="number">
                                    <span class="sort-handle">Reason <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
										
						 <th data-sort="number">
                                    <span class="sort-handle">Date <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
					     <th data-sort="string">
                                    <span class="sort-handle">VoucherNo <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
						 <th data-sort="number">
                                    <span class="sort-handle">Amt <i class="fa-solid fa-sort sort-icon"></i></span>
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
 						<td><?php echo  $custv['exp_name'];?></td><td><?php echo  $custv['line_name'];?></td>
			<td> <?php echo substr($custv['reason'],0,60);?></td>
 			<td id="borrow_name_<?php echo $custv['id'];?>"> <?php echo $custv['expdt'];?></td>
			<td > <?php echo $custv['voucher_no'];?></td>
			<td > <?php echo $custv['amount'];?></td>
			<td><?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management'){?> 				 
			<a onClick="if(!confirm('Are you sure want to Delete this...?')) return false;" href="<?php echo $page_url;?>&action=delete&id=<?php echo $custv['id'];?>&frmdt=<?php echo $_REQUEST['frmdt'];?>&todt=<?php echo $_REQUEST['todt'];?>" class="red" style="color:#FF0000" class="btn btn-danger"  > <i class="fa-solid fa-trash-can"></i></a>
			<?php }?></td>
                     </tr>
						<?php  $j++;} ?>
					</tbody>
				</table>
		</div>
			<?php }?>
 </div>                     
 