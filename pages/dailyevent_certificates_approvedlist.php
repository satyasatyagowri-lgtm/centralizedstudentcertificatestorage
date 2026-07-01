<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/dailyevent_certificate.php");
			$action = $_GET['action'];
            $pg=$_GET['page'];
			$id=(int)$_GET['id'];
			$orid=(int)$_GET['org_id'];

			$page_url="home.php?p=dailyevent_certificates_approvedlist";
			
			$obj_press = new customer_operations();						
			if($id && $action=="delete"){			
				$delmsg=$obj_press->delete_customer_details($id);
			}
      if($id && $action=="cancelapproved"){			
				$delmsg=$obj_press->disapprove_certificate($id);
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
<?php
                     if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management')
          $crtifcnd=" where a.approval_status in(1,2) and a.frmuser_id=b.user_id ";
         else if($_SESSION['user_type']=='student')
          $crtifcnd=" where  a.frmuser_id=b.user_id and a.frmuser_id='".$_SESSION['user_id']."'  and a.approval_status in(1,2) ";
        else if($_SESSION['user_type']=='faculty')
          $crtifcnd=" where a.assigned_touser_id=b.user_id and a.assigned_touser_id='".$_SESSION['user_id']."' and a.approval_status in(1,2) ";
        else  $crtifcnd=" where a.approval_status=0 adn a.frmuser_id=b.user_id ";
    	$getuploadedcerficatesqry=$obj_db->qry("select a.*,if(assigned_touser_id>0,c.full_name,'NotAssigned') as assigned,if(assigned_touser_id>0,'block','none') as assignusrsts,if(approval_status=1,'green','red') as approvestscolor
      ,
    CASE
        WHEN a.approval_status = 1 THEN 'Approval'
        WHEN a.approval_status = 2 THEN 'Reject'
        ELSE 'Pending'
    END AS approvests
      ,if(assigned_touser_id>0,'success','danger')  as assignedcolor,b.full_name as senderstudent,DATE_FORMAT(
    STR_TO_DATE(approval_date, '%Y-%m-%d %H:%i:%s'),
    '%d-%m-%Y %h:%i:%s %p'
) AS approvdt,b.mobile from ".TABLE_UPLOAD_ACADEMIC_CERTIFICATES." a left join ".TABLE_USER_DETAILS." c on a.assigned_touser_id=c.user_id,".TABLE_USER_DETAILS." b $crtifcnd order by date(enter_date) asc");
                    ?>

                      <div class="tab-pane fade show active" id="home">
                          <div class="card-body">

                          <div class="table-responsive">
                          <!-- ðŸ” Search Box -->


                          
                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Approved/Disapprove List</h5>
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
                                    <span class="sort-handle">Score <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                   <th data-sort="string">
                                    <span class="sort-handle">File <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management' || $_SESSION['user_type']=='faculty'){?>
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
                                   <b style="text-align:center;display: <?php echo $usrv['assignusrsts'];?>;color:<?php echo $usrv['approvestscolor'];?>" ><?php echo $usrv['approvests'].'<br>'.$usrv['approvdt'];?></b> 
                                  </td>
                                  <th><?php echo $usrv['score'];?></th>
                                   <td><a href="<?php echo SITE_URL.'includes/uploaded_files/'.$usrv['document_path'];?>" target="_blank"> <i class="fa-solid fa-download"></i></a></td>
                                 <?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management' || $_SESSION['user_type']=='faculty'){?>
                                  <td class="action-btns"> 
                                    <a  onClick="if(!confirm('Are you sure want to Disapprove this...?')) return false;" href="<?php echo $page_url;?>&action=cancelapproved&id=<?php echo $usrv['id'];?>"  class="red" style="color:#FF0000" class="btn btn-danger"  > <i class="fa-solid fa-trash-can"></i>
                                    </a>

                                  </td>
                                  <?php }?>
                                 
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