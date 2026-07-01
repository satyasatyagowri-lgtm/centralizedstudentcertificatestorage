<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/dailyevent_certificate.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=dailyevent_certificateslist";
			
			$obj_press = new customer_operations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_customer_details($id);
			}
			if($id && $action=="edit"){			
				$data=$obj_press->get_cerficatedts($id);
			}
			if($action == "add" || $action == "edit" || $action == "status") {
				$mode ="Add";
      
	     	if($_POST) $data=$_POST;
			$data = remove_slashes($data);
             $msg=array();	
			if(isset($_POST['btn_save_data'])) {
		//	print_r($_REQUEST);exit;
					extract($_POST);
 					$msg = $obj_press->certficatework_assignto_user($data,$id);
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
 					
           </ul>
               <div align="right" class="div2" style="float:right;display: <?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management' || $_SESSION['user_type']=='staff'){?> block; <?php }else{?> none; <?php }?>">
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
				
				  <h5 class="mb-3 border-bottom pb-2">Students Uploaded List</h5>

             <table  class="table align-middle text-dark small export-table tablesortsearchable">
                              <thead>
                                 <tr>  
                                  <td >
                                   Student Name  
                                  </td><td><?php echo $data['senderstudent'];?></td>
                                 </tr>
                                   <td >
                                   Mobile  
                                  </td><td><?php echo $data['mobile'];?></td>
                                 </tr>

                                   <tr>  
                                  <td >
                                   Event  
                                  </td><td><?php echo $data['event_title'];?></td>
                                 </tr>
                                   <tr>  
                                  <td >
                                   category  
                                  </td><td><?php echo $data['activity_category'];?></td>
                                 </tr>
                                   <tr>  
                                  <td >
                                   Issuing Organization  
                                  </td><td><?php echo $data['issuing_organization'];?></td>
                                 </tr>
                                 
                                  <tr>  
                                  <td >
                                   Date  
                                  </td><td><?php echo $data['issuedt'];?></td>
                                 </tr>
                              </thead>
             </table>


              <div align="center">
          <iframe
    src="<?php echo SITE_URL.'includes/uploaded_files/'.$data['document_path'];?>"
    width="600"
    height="400">
</iframe>
</div>

                   <div class="row justify-content-center">
					<div class="col-md-4">
          <label class="form-label">Assing To Staff</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
           <select class=" select2-multiple "  name="assigned_touser_id[]" id="assigned_touser_id">
            <option value="">--Select--</option>
			<?php
               $userslist=$obj_db->qry("SELECT user_id ,a.branch_id ,assign_branch_ids,is_cashier,type_name,a.full_name,user_name,a.user_password, mobile,email,user_status 
					     FROM ".TABLE_USER_DETAILS." a,".TABLE_USER_TYPES." b  where b.user_type_id=a.user_type_id and a.user_type_id=2 and b.status=1");
			foreach($userslist as $usrky=>$usrv){?>
              <option value="<?php echo $usrv['user_id'];?>" <?php if(in_array($usrv['user_id'],explode(',',$data['user_id']))){?> selected="selected" <?php }?>><?php echo $usrv['full_name'].' '.$usrv['mobile'];?></option>
            <?php }?>
            </select>
          </div>
        </div>
					<div class="error" id="error" ></div>
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
          $crtifcnd=" where  a.frmuser_id=b.user_id and a.frmuser_id='".$_SESSION['user_id']."' ";
        else if($_SESSION['user_type']=='faculty')
          $crtifcnd=" where a.assigned_touser_id=b.user_id and a.assigned_touser_id='".$_SESSION['user_id']."' and a.approval_status=0 ";
        else  $crtifcnd=" where a.approval_status=0 adn a.frmuser_id=b.user_id ";
    	$getuploadedcerficatesqry=$obj_db->qry("select a.*,if(assigned_touser_id>0,c.full_name,'NotAssigned') as assigned,if(assigned_touser_id>0,'success','danger') as assignedcolor,b.full_name as senderstudent,date_format(str_to_date(approval_date,'%Y-%m-%d'),'%d-%m-%Y') as approvdt,b.mobile from ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." a left join ".TABLE_USER_DETAILS." c on a.assigned_touser_id=c.user_id,".TABLE_USER_DETAILS." b $crtifcnd order by date(enter_date) asc");
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
                                    <span class="sort-handle">Assigned <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <th data-sort="string">
                                    <span class="sort-handle">File <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="none" class="text-center">Actions</th>
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
                                  <td><span class="badge bg-<?php echo $usrv['assignedcolor'];?>"><?php echo $usrv['assigned'];?></span></td>
                                   <td class="action-btns">
                                  
                                    <a  href="<?php echo $page_url;?>&action=edit&id=<?php echo $usrv['id'];?>"  class="btn btn-outline-primary btn-sm" >
                                      <i class="fa-regular fa-eye"></i>
                                    </a>
                                    
                                  </td>
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