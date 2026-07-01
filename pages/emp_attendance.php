<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/emp_details.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=emp_attendance";
			
			$obj_press = new employee_operations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_employee_attendancedetails($id);
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
 					$msg = $obj_press->employee_attendancedetails_savenew($data,$id);
			    }
			}
    //  $weekdys=array('SUB','MON','TUE','WED','THU','FRI','SAT');
       $weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");

      //echo '<pre>'; print_r($_SESSION['linematch_users']);echo '</pre>';
 		
     if($_GET['frm_date']=='' && $_GET['to_date']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }
 else{
	$dt1=$_REQUEST['frm_date'];
 $dt2=$_REQUEST['to_date'];
 }

    ?>
<script>

function get_lineattendancelist(){
	location.href='<?php echo $page_url;?>&frm_date='+$("#frm_date").val()+'&to_date='+$("#to_date").val()+'&line_id='+$("#line_id").val();
}
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
function get_attendancedate(id){
 	   $("#line_id").val('');
      $("#attdempusrdts").html('');
}

  function get_linedts(id){
  	    $('.glass').fadeIn();	
	    $('.loadimg').show();
	     $.ajax({	 
					  url:'includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'getline_empusers','gdt':$("#attendancedt").val(),'line_id':$("#line_id").val()},
					 success:function(data)
					  {//alert(data);
  					  $('.glass').fadeOut();	
	                  $('.loadimg').hide();
              $("#attdempusrdts").html(data);
					
  				     }
			});
	}

	
 </script>

<div class="tab-header">
    <!-- Tabs -->
           <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Emp Attendance Details</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> Emp Attendance <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
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
           
         <div class="col-md-4">
          <label class="form-label">Attendance Date *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid default-date-picker" id="attendancedt" value="<?php echo date('d-m-Y');?>" name="attendancedt" onchange="get_attendancedate(this.value);"  placeholder="Enter full name" required>
            <div class="invalid-feedback">Attendance Date.</div>
          </div>
        </div>

       <div class="col-md-4">
          <label class="form-label">Line Details</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
          <select class="form-select valid"  id="line_id" name="line_id"  onchange="get_linedts(this.value);">
              <option  value="">Select</option>
			<?php $lincnd="";
      if($_SESSION['user_type']=='account')
        $lincnd=" and line_id in(0".$_SESSION['assign_line_ids'].") ";
       $linedts=$obj_db->qry("SELECT * FROM ".TABLE_LINE_NAMES."  where   is_delete=0 $lincnd");
			foreach($linedts as $lineky=>$linev){?>
              <option value="<?php echo $linev['line_id'];?>" <?php if($linev['line_id']==$data['line_id']){?> selected="selected" <?php }?>><?php echo $linev['line_name'].' '.$linev['line_code'];?></option>
            <?php }?>
            </select>
          </div>
        </div>

      </div>

      <br clear="all">
      <div class="row g-3" id="attdempusrdts">
 			</div>

				<input type="hidden" name="btn_save_data" value="Update" >
         <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
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

                          <div class="row g-3 " >
      <div class="col-md-2" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="frm_date"  to_date="off"   value="<?php echo $dt1;?>"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
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



					<div class="col-md-2" >
                <label class="form-label"> &nbsp;</label>
                 <div class="input-group">
				    <button type="button" class="btn btn-success rounded-pill px-4" onclick="get_lineattendancelist()">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                  </button>
 					</div>
	                </div>
					</div>

          <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Employee AttendanceList</h5>
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
                                    <span class="sort-handle">Date <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  
                                  <th data-sort="string">
                                    <span class="sort-handle">Full Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <th data-sort="string">
                                    <span class="sort-handle">Line <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  
                                 
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
	<?php	
						/*$path="includes/cusomer_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/
 						 $linusrcnd="";						
					if($_REQUEST['line_id']>0) $linusrcnd="  and a.line_id in(0".$_REQUEST['line_id'].")";
     				       $empattdts=$obj_db->qry("SELECT a.*,date_format(str_to_date(b.attendance_date,'%Y-%m-%d'),'%d-%m-%Y') as attdt,b.id,c.line_name FROM ".TABLE_EMPUSER_DETAILS."  a,".TABLE_EMPUSER_ATTENDANCELIST." b,".TABLE_LINE_NAMES." c WHERE a.emp_user_id=b.emp_user_id $linusrcnd and b.line_id=c.line_id and str_to_date(b.attendance_date,'%Y-%m-%d') between str_to_date('".trim($dt1)."','%d-%m-%Y') and str_to_date('".trim($dt2)."','%d-%m-%Y') ORDER BY emp_name asc");
 				     $j=1;

                     foreach($empattdts as $empky=>$empv){
						if(!file_exists('includes/user_img/'.$empv['emp_user_id'].'.jpg'))
							$usrimg="photo.jpg?rand=".rand();
						else $usrimg=$empv['emp_user_id'].'.jpg?rand='.rand();
						?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                   <td><?php echo $empv['attdt'];?></td>
                                      <td><?php echo $empv['emp_name'];?></td>
                                     <td><?php echo $empv['line_name'];?></td>
                                  <td><?php echo $empv['empmobile_no'];?></td> 
                                  
                                  <td>
                                    <a onClick="if(!confirm('Are you sure want to Delete <?php echo $empv['emp_name'];?>...?')) return false;"  href="<?php echo $page_url;?>&action=delete&frm_dt=<?php echo $dt1;?>&to_dt=<?php echo $dt2;?>&id=<?php echo $empv['id'];?>"   style="color:#FF0000"    > <i class="fa-solid fa-trash-can"></i>
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