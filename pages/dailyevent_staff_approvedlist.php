<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/dailyevent_certificate.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=dailyevent_staff_approvedlist";
			
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
 					$msg = $obj_press->certficatework_assign_permission($data,$id);
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
          <?php
                     if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management')
          $crtifcnd=" where a.approval_status=0 and a.frmuser_id=b.user_id ";
         else if($_SESSION['user_type']=='student')
          $crtifcnd=" where  a.frmuser_id=b.user_id and a.frmuser_id='".$_SESSION['user_id']."' ";
        else if($_SESSION['user_type']=='faculty')
          $crtifcnd=" where a.assigned_touser_id=b.user_id and a.assigned_touser_id='".$_SESSION['user_id']."' and a.approval_status=0 ";
        else  $crtifcnd=" where a.approval_status=0 adn a.frmuser_id=b.user_id ";
    	$getuploadedcerficatesqry=$obj_db->qry("select a.*,if(assigned_touser_id>0,c.full_name,'NotAssigned') as assigned,b.full_name as senderstudent,date_format(str_to_date(approval_date,'%Y-%m-%d'),'%d-%m-%Y') as approvdt,b.mobile from ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." a left join ".TABLE_USER_DETAILS." c on a.assigned_touser_id=c.user_id,".TABLE_USER_DETAILS." b $crtifcnd order by date(enter_date) asc");
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
                        
                </div>