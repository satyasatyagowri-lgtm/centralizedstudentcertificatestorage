<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/line_chitdetails.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=inewise_amt_adjustments";
			
			$obj_press = new linchitoperations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_linadjustmentamtdetails($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_lineadjustmentamt_details($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->linadjustmentamt_details_savenew($data,$id);
			    }
			}
    //  $weekdys=array('SUB','MON','TUE','WED','THU','FRI','SAT');
       $weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");
       $amtadjusttyps=array(1=>"Access Amount",2=>"Sort Amount",3=>"Borrowing Money",4=>"Repay Money",5=>"Intrest discount",6=>"Ticket refund",7=>"Chits");

      //echo '<pre>'; print_r($_SESSION['linematch_users']);echo '</pre>';
$dt1=date('d-m-Y');$dt2=date('d-m-Y');
      if($_REQUEST['frm_date']!='')
	$dt1=$_REQUEST['frm_date'];
 if($_REQUEST['to_date']!='')
	$dt2=$_REQUEST['to_date'];
 			?>
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
	


function get_linddts(id){
location.href='<?php echo $page_url;?>&frm_date='+$("#frm_date").val()+'&to_date='+$("#to_date").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
}
function getCitydts(id){ 
location.href='<?php echo $page_url;?>&frm_date='+$("#frm_date").val()+'&to_date='+$("#to_date").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
}

function get_adjustmentdts(id){ 
location.href='<?php echo $page_url;?>&frm_date='+$("#frm_date").val()+'&to_date='+$("#to_date").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
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
 </script>

<div class="tab-header">
    <!-- Tabs -->
           <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Adjustment Amount</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> Adjustment Amount <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
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
				
				  <h5 class="mb-3 border-bottom pb-2">Line Adjustment Paid Form</h5>
      <div class="row g-3">
          <?php  
           if($_SESSION['user_type']=='account')
			       $lincnd=" AND line_id in(0".$_SESSION['assign_line_ids'].") ";
$getlinedts=$obj_db->qry("select a.*  from ".TABLE_LINE_NAMES." a where a.is_delete=0 $lincnd order by a.line_name asc");

?>
  <div class="col-md-4">
          <label class="form-label">Line Details</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
          <select class="form-select valid"  id="line_id" name="line_id" onchange="get_lineusers(this.value);" >
              <option  value="">--Select Line--</option>
 			<?php
     
			foreach($getlinedts as $lineky=>$linev){?>
              <option value="<?php echo $linev['line_id'];?>" <?php if($linev['line_id']==$data['line_id']){?> selected="selected" <?php }?>><?php echo $linev['line_name'].' '.$linev['line_code'];?></option>
            <?php }?>
            </select>
          </div>
        </div> 



           

         <div class="col-md-4">
          <label class="form-label">Type*</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
          <select class="form-select valid"  id="is_sortaccess_borrorwtype" name="is_sortaccess_borrorwtype" onchange="get_chitdts(this.value);" >
              <option  value="">--Select Type--</option>
 			<?php
     
			foreach($amtadjusttyps as $lineky=>$linev){?>
              <option value="<?php echo $lineky;?>" <?php if($lineky==$data['is_sortaccess_borrorwtype']){?> selected="selected" <?php }?>><?php echo $linev;?></option>
            <?php }?>
            </select>
          </div>
        </div> 



         <div class="col-md-4" style="display:  <?php if($_SESSION['user_type']=='management'){?> block; <?php }else{?> none; <?php }?>" >
          <label class="form-label">Date *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid default-date-picker" id="g_date" name="g_date"  value="<?php echo date('d-m-Y');?>" placeholder="Date" required>
            <div class="invalid-feedback">Please  Date.</div>
          </div>
        </div>

         <div class="col-md-4">
          <label class="form-label"> Amount *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="adjustment_amt" autocomplete="off" name="adjustment_amt" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['amount'];?>" placeholder="Paid Amount" required>
            <div class="invalid-feedback">Please enter Paid Amount.</div>
          </div>
        </div>



 <div class="col-md-4">
          <label class="form-label"> Payment Type *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
 					 
						<select class="form-control form-control-sm valid2" id="payment_type"  title="Select Payment type" onChange="payment_types_bk(this.value);"   name="pay_type_id" >
 					<?php $bankname_qry=$obj_db->qry("select a.* from  ".TABLE_PAYMENT_TYPE." a  ");
						  foreach($bankname_qry as $ptypky=>$ptypv){?>
					<option value="<?php echo $ptypv['pay_type_id'];?>"><?php echo $ptypv['pay_name'];?></option>
						<?php }?>
						   </select>
						   <div id="error2" class="error"></div>
						   </div>
 </div>
 
 <div class="col-md-4">
          <label class="form-label"> Select User *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
					<select name="upayuser_id"  id="upayuser_id"  class="form-control form-control-sm valid" title="Get Upi User"  >
          <option value="">--Select--</option>
        </select>

					<div id="error1" class="error cashlessdt_err"></div>
					
					</div>
					</div>

      
        <div class="col-md-4">
          <label class="form-label">Description</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <textarea  class="form-control valid" id="reason" name="reason"><?php echo $data['reason'];?></textarea>
            <div class="invalid-feedback">Please enter Description.</div>
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
                                  <h5 class="mb-0 fw-bold">AdjustmentAmounts List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>


<div class="row g-3 " >
      <div class="col-md-2" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="frm_date"  autocomplete="off"   value="<?php echo $dt1;?>"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>


					<div class="col-md-2" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>" >
                <label class="form-label">To Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="to_date"  autocomplete="off" value="<?php echo $dt2;?>"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>


					
					
 		  <div class="col-md-2" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>">
          <label class="form-label"> Line</label>
          <div class="input-group">
 			  <select class="form-select valid"  id="line_id" name="line_id" onchange="get_linddts(this.value);">
              <option  value="">ALL LINES</option>
			<?php $lincnd="";
			   if($_SESSION['user_type']=='account')
			       $lincnd=" AND line_id in(0".$_SESSION['assign_line_ids'].") ";
       $linedts=$obj_db->qry("SELECT * FROM ".TABLE_LINE_NAMES."  where  is_delete=0 $lincnd");
			foreach($linedts as $lineky=>$linev){?>
              <option value="<?php echo $linev['line_id'];?>" <?php if($linev['line_id']==$_REQUEST['line_id']){?> selected="selected"  <?php }?>><?php echo $linev['line_name'];?></option>
            <?php }?>
            </select>
					<div id="error1" class="error"></div>
					</div>
					</div>	

					  


					<div class="col-md-2" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>">
                <label class="form-label"> &nbsp;</label>
                 <div class="input-group">
				    <button type="button" class="btn btn-success rounded-pill px-4" onclick="get_adjustmentdts()">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                  </button>
 					</div>
	                </div>
					</div>	
                
                            <table id="patientTable" class="table align-middle text-dark small">
                              <thead>
                                <tr>
                                   <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <th data-sort="number">
                                    <span class="sort-handle">Date <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Line <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  
                                  <th data-sort="string">
                                    <span class="sort-handle">Amount <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <th data-sort="string">
                                    <span class="sort-handle">Type <i class="fa-solid fa-sort sort-icon"></i></span>
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

  if($_SESSION['user_type']!='account' && !is_numeric($_REQUEST['line_id']))
						 $usrcnd="";						
						elseif($_REQUEST['line_id']>0 && $_REQUEST['city_id']>0) $usrcnd="  and a.city_id='".$_REQUEST['city_id']."' and a.line_id in(0".$_REQUEST['line_id'].")";
						elseif($_REQUEST['city_id']>0) $usrcnd=" and a.line_id in(0".$_REQUEST['line_id'].") and a.city_id='".$_REQUEST['city_id']."' ";
						elseif($_REQUEST['line_id']>0) $usrcnd=" and a.line_id in(0".$_REQUEST['line_id'].") ";
						else $usrcnd=" and b.user_id='".$_SESSION['user_id']."' and a.line_id in(".$_SESSION['assign_line_ids'].")";

     				       $linchitdts=$obj_db->qry("SELECT b.*,a.line_name,if(b.enter_by='".$_SESSION['user_id']."','block','none') as delsts,date_format(str_to_date(b.date_time,'%Y-%m-%d'),'%d-%m-%Y') as dt,c.user_name FROM ".TABLE_LINE_NAMES." a,".TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS." b,".TABLE_USER_DETAILS." c where b.enter_by=c.user_id and a.line_id=b.line_id and date(b.date_time) between '".date('Y-m-d',strtotime($dt1))."' and '".date('Y-m-d',strtotime($dt2))."'   $usrcnd ORDER BY b.id desc");
 				     $j=1;

          $iscncel=0;
				  $actchitdts = array_filter($linchitdts,function($v,$k) use ($iscncel){
					 return $v['is_cancel'] == $iscncel;
				   },ARRAY_FILTER_USE_BOTH);
                     foreach($actchitdts as $linchitky=>$linchitv){					 
						?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                  <td><?php echo $linchitv['dt'];?></td>
                                  <td><?php echo $linchitv['line_name'];?></td>
                                     <td><?php echo $linchitv['adjustment_amt'];?></td>
                                      <td><?php echo $amtadjusttyps[$linchitv['is_sortaccess_borrorwtype']];?></td>
                                     <td><?php echo $linchitv['user_name'];?></td>
                                  <td>
                                    <?php if($_SESSION['user_type']=='management'){?>
                                    <a style="color:#FF0000;" onClick="if(!confirm('Are you sure want to Delete this...?')) return false;" href="<?php echo $page_url;?>&action=delete&btypid=<?php echo $linchitv['is_sortaccess_borrorwtype'];?>&id=<?php echo $linchitv['id'].'&dt1='.$dt1.'&dt2='.$dt2;?>"  class="danger "  >
                                      <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                    <?php }?>
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