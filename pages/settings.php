<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/settings.php");

			$action = $_GET['action'];

			$page_url="home.php?p=settings";
			
			
			$obj_press = new userdetails_pword();						

			if($_POST) $data=$_POST;
			$data = remove_slashes($data);
			$msg=array();	

			if(isset($_POST['btn_save_data'])) {//echo '<pre>';print_r($_POST);echo '</pre>';exit;
					extract($_POST);
 					$msg = $obj_press->user_pwordchange($data,$id);
			    }

?>

<script>
  function valid(){
	  $("#success_report").html('');
$('.TempOwner input,textarea').removeClass('valid');
 $('.valid').css('border','1px solid red');
	  var flag=1;
	       $('.valid').each(function(){
	          var error=$(this).parent().find("#error");
			  if($(this).val()=='')
			  {
			  flag=0; error.html('Missing '+$(this).attr('title')+'<br>')
			  $(this).css('border','1px solid red');
			  }
			  else
			  {
			    error.html('');
			    $(this).css('border','1px solid #ccc');
			  }
		});

	  if(($("#pword").val()!='') && ($("#pword").val()!=$("#cpword").val()))
        {
		  flag=0; $(".cpword").html('Confirm Password doesnot match with password'+'<br>')
		}
		if(flag==1)
	    $(' #frm1 ').submit();  
}
</script>
       
<div class="tab-header">
	Settings

</div>
<div class="tab-content">


<div class="row">
					<form   class="form-horizontal" id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url;?>">

					<?php if(!$_POST){$data=$obj_db->fetchRow("select * from ".TABLE_USER_DETAILS." where user_id='".$_SESSION['user_id']."'");}
					 // print_r($msg);
					 $msger=explode('^',$msg);?>
					 
					 <div class="row justify-content-center">
                     <div class="col-md-4">
          <label class="form-label">User Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
					<input class="form-control valid" title="User Name" readonly="" type="text" name="user_name" value="<?php echo $data['user_name'];?>" />
					<div id="error" class="error">
					<?php if($msger[0]=='aunameexist' || $msger[1]=='unameexist'){?>
                      *Username already exist
					 <?php }?>
					 </div>
					</div>
				    </div>
					</div>

                     
					 <div class="row justify-content-center">
                    <div class="col-md-4">
          <label class="form-label">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-check-fill text-success"></i></span>
					
					<input class="form-control " title="Email Name" type="text" name="email" value="<?php echo $data['email'];?>" />
					<div id="error" class="error">
					</div>
					</div>
				    </div>
					</div>
					
					 <div class="row justify-content-center">
                    <div class="col-md-4">
          <label class="form-label">Mobile</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
					<input class="form-control valid" title="Sms Mobile" type="text" name="sms_mobile" value="<?php if($data['sms_mobile']!='')echo $data['sms_mobile'];else echo '';?>" />
 					<div id="error" class="error">
					</div>
					</div>
				    </div>
					</div>

					 <div class="row justify-content-center">
                    <div class="col-md-4">
          <label class="form-label">Passowrd</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
					<input class="form-control valid" title="Password" id="pword" type="password" name="user_password" value="<?php echo $data['user_password'];?>" />
 					<div id="error" class="error"></div>
					</div>
				    </div>
					</div>

					 <div class="row justify-content-center">
                    <div class="col-md-4">
          <label class="form-label">Confirm Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
					<input class="form-control valid" title="Confirm Password" id="cpword" type="password" name="cadmin_password" value="<?php echo $data['user_password'];?>" />
 					<div id="error" class="error cpword"></div>
					</div>
				    </div>
					</div>

					<input type="hidden" name="btn_save_data" value="Update" >
					<div class="form-actions">
								<div class="text-center">
					<button type="button" onClick="valid();" class="btn btn-primary">Submit <i class="ft-thumbs-up position-right"></i></button>
					<?php if($_REQUEST['success']=='success'){?>
					<div id="success_report" style="font-size:16px;color:#009900">Profile updated success fully</div>
					<?php }?>
					</div>
					</div>
					</form>
</div>
</div>