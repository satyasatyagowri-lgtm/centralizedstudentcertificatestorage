<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			require_once("classes/years.php");

			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];

			$page_url="home.php?p=year";

			$obj_press = new insert_update();						
			if($id && $action=="delete"){			
			$delmsg=$obj_press->delete_year($id);
			}

			if($id && $action=="edit"){			
			$data=$obj_press->get_year($id);
			}

			if(isset($_POST['btn_save_data']) || $action == "edit" || $action == "status") {
			$mode ="Add";
			if($_POST) $data=$_POST;
			$data = remove_slashes($data);
			$msg=array();	

			if(isset($_POST['btn_save_data'])) {
			extract($_POST);
			$msg = $obj_press->year_save($data,$id);
			}
			}

			$parent_show="Year";			
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
 		 
<div class="row">
 <div class="modal-content">
		<?php if($action=="add" || $action=="edit"){?>	
		<div class="modal-content">
					<div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Year Details </h5>
				<div class="media-right white text-right">
											<a  class="btn btn-sm btnradius btn-warning" href="<?php echo $page_url;?>" ><span style="color:#FFFFFF">Back</span></a>
				</div>
											
              </div>
		
		   <div class="modal-body m-b-0">
				<form   class="form-horizontal" id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				   <div class="row justify-content-center">
											 <div class="col-sm-4">
											<div class="form-group  m-b-0">
											
												<label for="recipient-name" class="col-form-label">Academic Year  <b style="color:#FF0000;">*</b> </label>
												<input type="text" name="year" id="year" class="form-control form-control-sm valid"  aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?php echo $data['year'];?>">
											</div>
											</div>
											 
					                          <div class="col-sm-4">
											<div class="form-group  m-b-0">
												<label for="recipient-name" class="col-form-label">Current Year  <b style="color:#FF0000;">*</b> </label>
												<input type="text" name="current_year" id="current_year" class="form-control form-control-sm valid"  aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?php echo $data['current_year'];?>">
											</div>
											</div>
											
											<div class="col-sm-4">
											<div class="form-group m-b-0">
											<label for="recipient-name" class="col-form-label">Date Range <b style="color:#FF0000;">*</b>  </label>
											<div class="input-daterange input-group">
											<input type="text" class="form-control form-control-sm  default-date-picker valid" name="start_date" value="<?php echo $data['start_date'];?>" />
											<span class="input-group-addon">
											<i class="fa fa-exchange"></i>
											</span>
											<input type="text"  class="form-control form-control-sm  default-date-picker valid" name="end_date" value="<?php echo $data['end_date'];?>" />
											</div>				
											</div>
											</div>
				                         </div>
				
				
				
				
                
             
				<input type="hidden" name="btn_save_data" value="Update" >
				<br />
				
				<div class="modal-footer m-t-2 justify-content-center">
 										<button type="button" class="btn btn-sm  btn-primary btnradius submtbtn" onclick="valid();">Save</button>
										<img src="includes/assets/pre-loader/Searching1.gif" / id="formload" style="display:none;">
									    </div>

				</form>
				</div>
         </div>		
		
		<?php }else{?>
            <div class="modal-content">
					<div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Year Details </h5>
				<div class="media-right white text-right">
											<a  class="btn btn-sm btnradius btn-success" href="<?php echo $page_url;?>&action=add" ><span style="color:#FFFFFF">Add</span></a>	&nbsp;&nbsp;<a download="Alfaniti Solutions FarmDetails.xls" 
  			  href="#" onclick="return ExcellentExport.excel(this, 'excel', 'Sheet Name Here');" style="float: right;" class="btn btn-sm btnradius btn-primary" ><i class="fas fa-file-excel lg"></i></a>
				</div>
											
              </div>
			  
         <div class="panel-body table-responsive"  >
		 <br clear="all" />
                           <table  class="table table-striped table-sm m-t-0 table-bordered border-1" id="sorting"  >
                        <thead>
                            <tr>
                             <th>Sno</th>
					         <th class="text-center">year</th>
					         <th class="text-center">Current Year</th>
					         <th class="text-center">Start Year</th>
					         <th  class="text-center">End Year</th>
					         <th class="text-center">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php	
					$yrdts=$obj_db->qry("SELECT * FROM ".TABLE_YEAR." ORDER BY year_id desc");
					$j=1;
                     foreach($yrdts as $k=>$v){?>
                            <tr >
                                <td><?php echo $j;?></td>
					<td class="text-center"><?php echo $v['year'];?></td>
					<td class="text-center"><?php echo $v['current_year'];?></td>
					<td class="text-center"><?php echo $v['start_date'];?></td>
					<td class="text-center"><?php echo $v['end_date'];?></td>
					<td class="text-center">
					<a href="<?php echo $page_url;?>&action=edit&id=<?php echo $v['year_id'];?>"><i  class="fa fa-edit fa-lg"></i> </a>
    				</td>
                            </tr>
                            <?php $j++;}?>
                        </tbody>
                             </table>
					</div>
                </div>
 		<?php }?> 
</div>
</div>
