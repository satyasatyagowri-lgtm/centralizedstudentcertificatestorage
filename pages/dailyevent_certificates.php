<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/dailyevent_certificate.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=dailyevent_certificate";
			
			$obj_press = new customer_operations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_certificate_details($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_customer_details($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->upload_centralized_cerficates($data,$id);
			    }
			}
    //  $weekdys=array('SUB','MON','TUE','WED','THU','FRI','SAT');
       $weekdts=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");

      //echo '<pre>'; print_r($_SESSION['linematch_users']);echo '</pre>';
 			?>
<script>
function get_existingcustomeraadhar(id){
   $(".glass").fadeIn();
	$(".loadimg").show();
	 $.ajax({	  
					  url:'includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'get_customeraadhar','aadhar_no':$("#aadhar_no").val(),'customer_id':'<?php echo $_REQUEST['id'];?>'},
					 success:function(data)
					  {	var dt=data.split('^');
					   $(".glass").fadeOut();
	                   $(".loadimg").hide();
					   $(".exist_aadharno").html('');
					   $("#customer_aadharextcnt").val(dt[1]);
					  if(dt[1]>0){
					  $(".exist_aadharno").html('*'+dt[0]);
					  }
				     }
			});
 
}


function get_existingcustomerno(id){
    $(".glass").fadeIn();
	$(".loadimg").show();
	 $.ajax({	  
					  url:'includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'get_customerno','customer_no':$("#customer_no").val(),line_id:$("#line_id").val(),'city_id':$("#city_id").val(),'customer_id':'<?php echo $_REQUEST['id'];?>'},
					 success:function(data)
					  {	var dt=data.split('^');
					   $(".glass").fadeOut();
	                   $(".loadimg").hide();
					   $(".exist_customerno").html('');
					   $("#customer_extcnt").val(dt[1]);
					  if(dt[1]>0){
					  $(".exist_customerno").html('*'+dt[0]);
					  }
				     }
			});
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
	
 </script>

 <style>
  .table-responsive-custom {
  width: 100%;
  overflow-x: auto;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 16px;
}

.custom-table th,
.custom-table td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}

.custom-table th {
  background: #f4f4f4;
  text-align: center;
}

/* Inputs */
.input-sm {
  width: 100%;
  padding: 6px;
  font-size: 14px;
}

/* ðŸ“± Mobile view */
@media (max-width: 768px) {
  .custom-table thead {
    display: none;
  }

  .custom-table,
  .custom-table tbody,
  .custom-table tr,
  .custom-table td {
    display: block;
    width: 100%;
  }

  .custom-table tr {
    margin-bottom: 15px;
    border: 1px solid #ddd;
    padding: 10px;
    background: #fff;
  }

  .custom-table td {
    border: none;
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
  }

  .custom-table td::before {
    content: attr(data-label);
    font-weight: bold;
    color: #333;
  }
}

 </style>

<div class="tab-header">
    <!-- Tabs -->
           <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Certificates Details</button></li>
 				<li class="nav-item"><button onclick="window.location.href='<?php echo $page_url;?>&action=add'" class="nav-link  <?php  if(in_array($_REQUEST['action'],$actionarr)){?> active <?php }?> <?php //echo $actionarr[$_REQUEST['action']];?>" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-clipboard-user"></i> Certificates <?php if($_REQUEST['action']=='')echo 'Add';echo $_REQUEST['action'];?></button></li>
           </ul>
            <!-- Action icons (Desktop) -->
             <div style="display: <?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management'){?> block; <?php }else{?> none; <?php }?>">
           <!-- <div class="action-icons"  >
              <button  id="exportExcel"  class="excel"><i class="fa-solid fa-file-excel"></i></button>
              <button id="downloadPdf" class="pdfbk"><i class="fa-solid fa-file-pdf"></i></button>
             </div>-->

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
  </div>

                <div class="tab-content">

                  <?php if($action=='add' || $action=='edit'){
                    $_SESSION['form_token'] = bin2hex(openssl_random_pseudo_bytes(32));?>
				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				
				  <h5 class="mb-3 border-bottom pb-2">Students Uploaded Form</h5>


                   <div class="row justify-content-center">
					<div class="col-md-4">
					<div class="form-group">
					<label class="form-label">Event Title *</label>
					<input   class="form-control form-control-sm valid" title="Event Name " id="event_title" type="text" name="event_title" value="<?php echo $data['event_title'];?>" />
					<div class="error" id="error" ></div>
					</div>
					</div>
					</div>

           <div class="row justify-content-center">
					<div class="col-md-4">
					<div class="form-group">
					<label class="form-label">Activity Category *</label>
					<input   class="form-control form-control-sm valid" title="Activity Category  " id="activity_category" type="text" name="activity_category" value="<?php echo $data['activity_category'];?>" />
					<div class="error" id="error" ></div>
					</div>
					</div>
					</div>

           <div class="row justify-content-center">
					<div class="col-md-4">
					<div class="form-group">
					<label class="form-label">Issuing Organization  *</label>
					<input   class="form-control form-control-sm valid" title="Issuing Organization  " id="issuing_organization" type="text" name="issuing_organization" value="<?php echo $data['issuing_organization'];?>" />
					<div class="error" id="error" ></div>
					</div>
					</div>
					</div>


           <div class="row justify-content-center">
					<div class="col-md-4">
					<div class="form-group">
					<label class="form-label">Date of Issue *</label>
					<input   class="form-control form-control-sm default-date-picker valid" title="Issuing Date  " id="date_of_issue" type="text" name="date_of_issue" value="<?php echo $data['date_of_issue'];?>" />
					<div class="error" id="error" ></div>
					</div>
					</div>
					</div>

         <div class="row justify-content-center">
         <div class="col-md-4">
          <label class="form-label">Upload File *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="file" class="form-control valid" id="upload_file" name="upload_file"  required>
            <div class="invalid-feedback">Must be lessthan 200 kb</div>
          </div>
        </div>
         </div>
 
      


	   <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
				
				<input type="hidden" name="btn_save_data" value="Update" >
        <input type="hidden" id="customer_bordtcnt"  value="0" >
         <input type="hidden" id="customer_aadharextcnt"  value="0" >
      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="button" class="btn btn-success rounded-pill px-4" onclick="valid();">
          <i class="bi bi-check2-circle me-1"></i> Submit
        </button>
      </div>
            <div align="center" id="tekamterr_msg" style="color:red; font-size:14px; "></div>
<div align="center"  class="error exist_customerno exist_aadharno" style="color:red; font-size:14px; "></div>
<div align="center"  class="error customer_bordtcntmsg " id="customer_bordtcntmsg" style="color:red; font-size:14px; "></div>

     </form>

                      </div>
                   <?php }else{
                     if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management')
          $crtifcnd=" where a.approval_status=0 and a.frmuser_id=b.user_id ";
         else if($_SESSION['user_type']=='student')
          $crtifcnd=" where  a.frmuser_id=b.user_id and a.frmuser_id='".$_SESSION['user_id']."' and a.approval_status=0 ";
        else if($_SESSION['user_type']=='faculty')
          $crtifcnd=" where a.assigned_touser_id=b.user_id and a.assigned_touser_id='".$_SESSION['user_id']."' and a.approval_status=0 ";
        else  $crtifcnd=" where a.approval_status=0 adn a.frmuser_id=b.user_id ";
    	$getuploadedcerficatesqry=$obj_db->qry("select a.*,if(assigned_touser_id>0,c.full_name,'NotAssigned') as assigned,if(assigned_touser_id>0,'block','none') as assignusrsts,if(approval_status>0,'success','danger') as approvestscolor,if(a.approval_status>0,'Approval','Pending') as approvests,if(assigned_touser_id>0,'success','danger') as assignedcolor,b.full_name as senderstudent,date_format(str_to_date(approval_date,'%Y-%m-%d'),'%d-%m-%Y') as approvdt,b.mobile from ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." a left join ".TABLE_USER_DETAILS." c on a.assigned_touser_id=c.user_id,".TABLE_USER_DETAILS." b $crtifcnd order by date(enter_date) asc");
                    ?>

                      <div class="tab-pane fade show active" id="home">
                          <div class="card-body">

                          <div class="table-responsive">
                          <!-- ðŸ” Search Box -->


                          
                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Certiicates List</h5>
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
                                   <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Student Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  
                                   <th data-sort="string">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Event <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Category <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Organization <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <th data-sort="string">
                                    <span class="sort-handle">Status <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <th data-sort="string">
                                    <span class="sort-handle">File <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <?php if($_SESSION['user_type']==3){?>
                                  <th data-sort="none" class="text-center">Actions</th>
                                  <?php }?>
                                </tr>
                              </thead>
                              <tbody>
	<?php	
						/*$path="includes/cusomer_img";
  if(!file_exists($path))
   mkdir($path,0777,true);echo 'ok';*/
         $j=1;
          
                     foreach($getuploadedcerficatesqry as $usrk=>$usrv){
						if(!file_exists('includes/cusomer_img/'.$usrv['user_id'].'.jpg'))
							$usrimg="photo.jpg?rand=".rand();
						else $usrimg=$usrv['user_id'].'.jpg?rand='.rand();
						?>
                                <tr>
                                  <td><?php echo $j;?></td>
                                  <td><?php echo $usrv['senderstudent'];?></td>
                                  <td><?php echo $usrv['mobile'];?></td>
                                    <td><?php echo $usrv['event_title'];?></td>
                                   <td><?php echo $usrv['activity_category'];?></td>
                                  <td><?php echo $usrv['issuing_organization'];?></td>
                                  <td>
                                  <span style="text-align: center;" class="badge bg-<?php echo $usrv['assignedcolor'];?>"><?php echo $usrv['assigned'];?></span>
                                   <span style="text-align:center;display: <?php echo $usrv['assignusrsts'];?>;" class="badge bg-<?php echo $usrv['approvestscolor'];?>"><?php echo $usrv['approvests'];?></span> 
                                  </td>
                                  <?php if($_SESSION['user_type']==3){?>
                                   <td class="action-btns">
                                    <a  onClick="if(!confirm('Are you sure want to Delete this...?')) return false;" href="<?php echo $page_url;?>&action=delete&id=<?php echo $usrv['customer_id'];?>"  class="red" style="color:#FF0000" class="btn btn-danger"  > <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                  
                                  </td>
                                  <?php }?>
                                  <td><a href="<?php echo SITE_URL.'includes/uploaded_files/'.$usrv['document_path'];?>" target="_blank"> <i class="fa-solid fa-download"></i></a></td>
                                </tr>
                              <?php $j++;}
                              ?>
                               
                              </tbody>
                              
                            </table>
                            </div>
                          </div>

                          
                      </div>                    
                       </div>
                        
                     <?php }?>
                     
                </div>