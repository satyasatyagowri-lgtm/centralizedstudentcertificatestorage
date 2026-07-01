<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			 require_once("classes/customer_gen_borrow.php");
			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];

			$page_url="home.php?p=customer_gen_borrow";

			$obj_press = new customer_genborrow_operations();						
			if($id && $action=="delete"){			
			$delmsg=$obj_press->delete_genborrow($id);
			}

			if($id && $action=="edit"){			
			$data=$obj_press->get_genborrow($id);
			}

			if(isset($_POST['btn_save_data']) || $action == "edit" || $action == "status") {
			$mode ="Add";
			if($_POST) $data=$_POST;
			$data = remove_slashes($data);
			$msg=array();	

			if(isset($_POST['btn_save_data'])) {
			extract($_POST);
			$msg = $obj_press->genborrow_save($data,$id);
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

 if($_REQUEST['cust_id']!='')
				  $cust_id=$_REQUEST['cust_id'];
				 else $cust_id=$data['cust_id'];

         $cust_line=$obj_db->qry("select a.*,b.weekd_id as weeksdid from  ".TABLE_CUSTOMER_DTS."  a,".TABLE_LINE_CITYS." b where  a.customer_id='".$cust_id."' and a.city_id=b.city_id");
          $weekdys=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat");
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
//ctuspaytyppaydt,custgivpays

var csutptyppdtsum = 0;var cstugipasum=0;var csutmanualeachwekpableamt=0;
  /*   $(".ctuspaytyppaydt").each(function () {
                    if (!isNaN($(this).val()) && $(this).val().length != 0) {
             csutptyppdtsum += parseFloat($(this).val());
         }
     });*/

	  $(".custpamttypwis").each(function () {
                    if (!isNaN($(this).val()) && $(this).val().length != 0) {
             csutptyppdtsum += parseFloat($(this).val());
         }
     });

	 $(".custgivpays").each(function () {
                    if (!isNaN($(this).val()) && $(this).val().length != 0) {
             cstugipasum += parseFloat($(this).val());
         }
     });

	 $(".eachmanweekpaybleamt").each(function () {
                    if (!isNaN($(this).val()) && $(this).val().length != 0) {
             csutmanualeachwekpableamt += parseFloat($(this).val());
         }
     });

 $("#tekamterr_msg").html('');
 
 if($("#isset_manualweeks").val()==1){
   // fristweekamt,remweekamts
   var fristweekamt=$(".fristweekamt").val();
    var remweeks=parseInt($("#no_months").val())-1;
    var remweekamts=parseFloat($(".remweekamts").val())*remweeks;
    var totwekspabelamt=parseFloat(remweekamts)+parseFloat(fristweekamt);
   }
    else 
var totwekspabelamt=parseFloat($("#weekly_monthly_paybleamt").val())*parseFloat($("#no_months").val());
 $(".custgivpays,.ctuspaytyppaydt").css('border','1px solid #ccc');
         var tot_tek_ducumentcharge=parseFloat($("#document_charge").val())+parseFloat($("#tekenamount_without_documentcharge").val());
    if(parseFloat($("#document_charge").val())>0)  var tot_tek_ducumentcharge=tot_tek_ducumentcharge;
    else var tot_tek_ducumentcharge=parseFloat($("#tekenamount_without_documentcharge").val());
       if(parseFloat(tot_tek_ducumentcharge)>parseFloat($("#payble_amount_withintrest").val()))
      {
         flag=0;
         $("#tekamterr_msg").html('*Payble Amount Mustbe GreaterThan '+tot_tek_ducumentcharge);
      }
      else if(parseFloat(totwekspabelamt)!=parseFloat($("#payble_amount_withintrest").val()))
      {
         flag=0;
         $("#tekamterr_msg").html('*NO.Of Weeks Pable Amount Should be Equal to '+$("#payble_amount_withintrest").val());
      }else if(parseFloat($("#tekenamount_without_documentcharge").val())!=csutptyppdtsum){ 
		flag=0; 
        $("#tekamterr_msg").html('*TakenAmount and Paid Cash & Online not equal');
		$(".custgivpays,.ctuspaytyppaydt").css('border','1px solid red');
	  }

	   if(flag==1 && $("#customer_bordtcnt").val()==0)
	   { 
         $('.glass').fadeIn();	
	     $('.loadimg').show();
	      $(' #frm1 ').submit();  
	   }
	}
	function get_chkallcopmos(){
	 if($("#all_components").is(":checked"))
	  $(".allcompos").prop('checked',true);
	 else $(".allcompos").prop('checked',false);
	}
	
	function get_mainmenu(id){
	 if($("#mmenu_id"+id).is(":checked"))
	  $(".firstsub"+id).prop('checked',true);
	 else $(".firstsub"+id).prop('checked',false);
	}
	
	function get_smainmenu(id){
	 if($("#smenu_id"+id).is(":checked"))
	  $(".scndsub"+id).prop('checked',true);
	 else $(".scndsub"+id).prop('checked',false);
	}
	setTimeout(function(){
get_date(1);
  }, 1000);
  function get_date(id){   
	       $('.glass').fadeIn();	
	    $('.loadimg').show();
	     $.ajax({	 
					  url:'includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'check_takdate_valid_ornot','line_id':$("#line_id").val(),'city_id':'<?php echo $cust_line[0]['city_id'];?>','taken_date':$("#taken_date").val()},
					 success:function(data)
					  {//alert(data);
					  var dt=data.split('^');
					   if(data!=''){
					  $('.glass').fadeOut();	
            $('.loadimg').hide();					 
           $("#customer_bordtcnt").val(dt[0]);
           $(".customer_bordtcntmsg").html(dt[1]);
        //   $("#taken_date").val(dt[2]);
				     }
            }
			});

     
    	}
//tekenamount_without_documentcharge
function get_teknamtwitoutdocchargedts(id)
{
  $("#document_charge").val('');
   $("#payble_amount_withintrest").val('');
    $("#no_months").val('');
}
function get_docchargedts(id)
{
    $("#payble_amount_withintrest").val('');
    $("#no_months").val('');
}
function get_paybleamtdts(id)
{
     $("#no_months").val('');
}

function get_issetmanualweeks(id){
 if($("#isset_manualweeks").val()==1)
 {
    $(".weeklypaybleamt").attr('disabled',true);
    $(".weeklypaybleamt").val('');
    $("#no_months").val('');
    $("#weeklypableamt_div").hide();
    $("#manualpaybleamtdiv").show();
    $(".weeklypaybleamt").removeClass('valid');
 }
 else{
  $(".weeklypaybleamt").attr('disabled',false);
  $("#manualpaybleamtdiv").html('');
  $("#manualpaybleamtdiv").hide();
  $("#weeklypableamt_div").show();
   $(".weeklypaybleamt").addClass('valid');
 }
}

  function allowOnlyNumbersAndDecimal(input) {
    // Remove non-numeric characters except dot
    input.value = input.value.replace(/[^0-9.]/g, '');

    // Allow only one decimal point
    input.value = input.value.replace(/(\..*)\./g, '$1');
}

function get_noofweks_mons(id)
{ 
 // weekly_monthly_paybleamt,no_months,payble_amount_withintrest
 // var totwekspabelamt=parseFloat($("#weekly_monthly_paybleamt").val())*parseFloat($("#no_months").val());
if($("#isset_manualweeks").val()==1)
 {
 var remweeks=parseInt($("#no_months").val())-1;
  var manwekamtdts='<br clear="all"><br clear="all"><br clear="all"><div style="color:#56089f;">Enter Each Week Amount Manually</div>';
  //for(var i=1;i<=parseFloat($("#no_months").val());i++){
      
         //weeklypableamt_div,weekly_monthly_paybleamt,manualweekly_amts ,get_issetmanualweeks()isset_manualweeks
          manwekamtdts +='<div class=" form-group center" >'+
            

           '<div class="col-md-4" >'+
                    '<div class="form-group">'+
				'<p class="card-title mb-1 text-muted">1-Week</p>'+
				'<input type="text" name="monpaybelamt_1" autocomplete="off" inputmode="decimal" oninput="allowOnlyNumbersAndDecimal(this)"  class="form-control fristweekamt eachmanweekpaybleamt form-control-sm valid" title="Weekly/Monthly Payble Amount"  >'+
					'<div id="error1" class="error"></div>'+
					'</div>'+
					'</div>'+
          '</div>'+
          
         

           '<div class=" form-group center" ><div class="col-md-4" >'+
                    '<div class="form-group">'+
				'<p class="card-title mb-1 text-muted">'+remweeks+' Weeks</p>'+
				'<input type="text" name="monpaybelamt_2" autocomplete="off" inputmode="decimal" oninput="allowOnlyNumbersAndDecimal(this)"  class="form-control remweekamts eachmanweekpaybleamt form-control-sm valid" title="Weekly/Monthly Payble Amount"  >'+
					'<div id="error1" class="error"></div>'+
					'</div>'+
					'</div>'+
          '</div>';
  // }
   $("#manualpaybleamtdiv").html(manwekamtdts);
 }
 else{
   $("#manualpaybleamtdiv").html('');$("#manualpaybleamtdiv").hide('');
    var wek_monpaybleamt=(parseFloat($("#payble_amount_withintrest").val())/parseFloat($("#no_months").val())).toFixed(2);
     $("#weekly_monthly_paybleamt").val(wek_monpaybleamt);
 }
 }
 
function get_linddts(id){
location.href='<?php echo $page_url;?>&dt='+$("#dateRange").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
}
function getCitydts(id){ 
location.href='<?php echo $page_url;?>&dt='+$("#dateRange").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
}
	function get_numvalid(type,id){  
if(type=='nomonts' && isNaN($("#no_months").val())){
  $("#no_months").val('');
  $("#no_months").focus();
 }
  
 var insrt_per=0;
	  if($("#interest_amount").val()=='')
	  insrt_per=0;
	 else insrt_per=$("#interest_amount").val();
	  
	  if($("#taken_amount").val()=='')
	  var totamt=0;
	 else var totamt=parseFloat($("#taken_amount").val());
	  var tot_intrest=((parseFloat(totamt)*parseFloat(insrt_per))/100).toFixed(2);
	  
	 
	  if($("#no_months").val()=='')
	  var tot_mons_weks=0;
	 else var tot_mons_weks=parseInt($("#no_months").val());
	  
	  var tot_intr_pinc=totamt+parseFloat(tot_intrest);
 var tot_monpaybamt=tot_intr_pinc/tot_mons_weks;
 $("#tot_int_princ").val(tot_intr_pinc);
 
 $("#weekly_monthly_paybleamt").val(tot_monpaybamt);
 
 
}

function get_upiamtdts(id){
   $("#upayuser_id").val('');
  if(parseFloat($("#upipay").val())>0){ 
    $("#upayuser_id").addClass('valid');
    $(".upidiv").show();
  }
  else{
    $("#upayuser_id").removeClass('valid');
    
    $(".upidiv").hide();
  }
}


 function get_dailycollection(){
	location.href='<?php echo $page_url;?>&dt='+$("#dateRange").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
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

/* 📱 Mobile view */
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
				<?php if($action=='add'){?>
 					 <li class="nav-item "><button  onclick="window.location.href='<?php echo $page_url;?>'" class="nav-link <?php  if(!in_array($_REQUEST['action'],$actionarr)){?> active <?php }?>" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Get Borrows</button></li>
                <?php }?>
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
				 $get_linedts=$obj_db->qry("select line_id,line_name from ".TABLE_LINE_NAMES." ");
				 $custliky=array_search($cust_line[0]['line_id'],array_column($get_linedts,'line_id'));
				  $getcustprevamtdts=$obj_db->qry("select * from  ".TABLE_CUSTOMER_GENPAYMENTS." where  customer_id='".$cust_id."' and is_delete=0 and remain_balance>0"); 
				 $line_lsttamtdt=$obj_db->fetchRow("SELECT date(taken_date) as takdt FROM ".TABLE_LINETAKEN_AMTS."  where line_id='".$cust_line[0]['line_id']."' order by date(taken_date) desc limit 1");
		if($line_lsttamtdt['takdt']!='')
		 $takdt=date('d-m-Y',strtotime($line_lsttamtdt['takdt']));
		else $takdt="";
					?>
 				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
				
				  <p class="mb-3 border-bottom pb-2">Customer : <?php echo $cust_line[0]['customer_name'];?></p>
				  <p class="mb-3 border-bottom pb-2">Mobile : <?php echo $cust_line[0]['mobile_no'];?>,<?php echo $get_linedts[$custliky]['line_name'];?></p>

				  <?php if(count($getcustprevamtdts)>0){?>
							<div style="color: red;">
								*Sorry You have already pending Rs.<?php echo $getcustprevamtdts[0]['remain_balance'];?><br clear="all">
							<div align="center">	<a href="home.php?p=customer&line_id=<?php echo $cust_line[0]['line_id'];?>">Back</a></div>
								<br>
							</div>
  						<?php }?>
    <?php /*?>  <div class="row g-3">
         <div class="col-md-4">
          <label class="form-label"> Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="customer_naem" disabled="disabled" name="customer_naem" value="<?php echo $cust_line[0]['customer_name'];?>" placeholder="Enter full name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>

	<div class="col-md-4">
          <label class="form-label">Mobile *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" class="form-control valid" disabled="disabled" name="mobile_no" id="mobile_no" value="<?php echo $cust_line[0]['mobile_no'];?>"  pattern="[0-9]{10}" placeholder="10-digit number" required>
            <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
          </div>
        </div>

   

        <div class="col-md-4">
          <label class="form-label">Line Details</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
          <select class="form-select valid"   disabled="disabled">
              <option selected disabled value="">Select</option>
	        <?php
				
					       foreach($get_linedts as $lineky=>$linev){?>
						   <option value="<?php echo $linev['line_id'];?>" <?php if($cust_line[0]['line_id']==$linev['line_id']){?> selected="selected" <?php }?>><?php echo $linev['line_name'];?></option>
						   <?php }?>
            </select>
          </div>
        </div>

            
		
       
      </div>
<?php */?>

<?php if(count($getcustprevamtdts)==0){?>
	  <br clear="all">
	  <h5 class="mb-3 border-bottom pb-2">Witness Information</h5>
					 


		<div class="row g-3 mt-3">

<div class="col-md-4">
          <label class="form-label">Witness Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control valid" id="witness_name"   name="witness_name"   placeholder="Enter full name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>

	<div class="col-md-4">
          <label class="form-label">Witness Mobile *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" class="form-control valid"   name="witness_mobile" id="witness_mobile"   pattern="[0-9]{10}" placeholder="10-digit number" required>
            <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
          </div>
        </div>
		</div>

 <br clear="all">
	  <h5 class="mb-3 border-bottom pb-2">Borrow Information</h5>
<div class="row g-3 mt-3">
 					 <div class="col-md-4" style="display: none;">
          <label class="form-label">Weekly/Monthly</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
					<select class="form-select  valid" title="Weekly/Monthly" name="pay_type" id="pay_type" onchange="get_paytype(this.value);">
 				 <option value="1" <?php if($data['pay_type']==1){?> selected="selected" <?php }?>>Weekly</option>
				<?php /*?> <option value="2" <?php if($data['pay_type']==2){?> selected="selected" <?php }?>>Monthly</option><?php */?>
				</select>
					<div id="error1" class="error"></div>
					</div>
					</div>
					
					<div class="col-md-4">
          <label class="form-label">Taken Amount</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
					<input name="tekenamount_without_documentcharge" id="tekenamount_without_documentcharge" autocomplete="off" onkeyup="get_teknamtwitoutdocchargedts(this.value);" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" class="form-control form-control-sm custgivpays valid" title="Taken amount"  value="<?php echo $data['taken_amount'];?>" >
					<div id="error1" class="error"></div>
					</div>
					</div>	
          
          <div class="col-md-4">
          <label class="form-label">DocumentCharge</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
					<input name="document_charge" id="document_charge" autocomplete="off" onkeyup="get_docchargedts(this.value);" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" class="form-control form-control-sm custgivpays " title="Document Charge"  value="<?php echo $data['taken_amount'];?>" >
					<div id="error1" class="error"></div>
					</div>
					</div>	
					
					
				 <div class="col-md-4">
          <label class="form-label">Payble Amount</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
					<input name="payble_amount_withintrest" id="payble_amount_withintrest" onkeyup="get_paybleamtdts(this.value)"  autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" class="form-control form-control-sm valid" title="Interst amount" value="<?php echo $data['interest_amount'];?>"  >
					<div id="error1" class="error"></div>
					</div>
					</div>
					
					 <div class="col-md-4">
          <label class="form-label">Taken Date</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
					<input name="taken_date" id="taken_date" autocomplete="off" class="form-control form-control-sm default-date-picker valid" title="Taken Date" value="<?php if($data['taken_date']=='')echo date('d-m-Y');else echo $data['taken_date'];?>" onchange="get_date(this.value);" >
					<div id="error1" class="error"></div>
					</div>
					</div>			
					
					
				<div class="col-md-4">
          <label class="form-label">IsSet Manualweeks</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
					<select name="isset_manualweeks" id="isset_manualweeks"   class="form-control form-control-sm valid" onchange="get_issetmanualweeks(this.value);" >
             <option value="0">No</option>
             <option value="1">Yes</option>
          </select> 
					<div id="error1" class="error"></div>
					</div>
					</div>

					 <div class="col-md-4">
          <label class="form-label">No.Of.Weeks/Months</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
					<input type="text" name="no_months" id="no_months" autocomplete="off" class="form-control form-control-sm valid" onkeyup="get_noofweks_mons(this.value,1);" oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['no_months'];?>" title="No Of months/weeks"  >
					<div id="error1" class="error"></div>
					</div>
					</div>
					<div class="col-md-4" id="weeklypableamt_div">
                    <div class="form-group">
					<p class="card-title mb-1 text-muted">Monthly/Weekely Payble Amount</p>
				<input type="text" name="monpaybelamt_1" autocomplete="off" id="weekly_monthly_paybleamt" onkeyup="get_noofweks_mons(this.value,2);" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['weekly_monthly_paybleamt'];?>" class="form-control form-control-sm weeklypaybleamt valid" title="Weekly/Monthly Payble Amount"  >
					<div id="error1" class="error"></div>
					</div>
					</div>
          </div>
    
          <div id="manualpaybleamtdiv" align="center" class=" row justify-content-center">
			
		  </div>
           
        <?php /*?>
		  <div class="row g-3 mt-3">
					 <div class="col-md-4">
          <label class="form-label">Cash</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
					<input name="cashpay" id="cashpay" autocomplete="off" class="form-control form-control-sm ctuspaytyppaydt"  value="<?php echo $data['no_months'];?>" title="No Of months/weeks"  >
					<div id="error1" class="error"></div>
					</div>
					</div>
					<div class="col-md-4">
           <label class="form-label">Upi</label>
		   <div class="input-group">
					 <span class="input-group-text"><i class="fa-solid fa-money-bill-wave"></i></span>
					 
				<input name="upipay" autocomplete="off" onkeyup="get_upiamtdts(this.value);" id="upipay" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" value="<?php echo $data['weekly_monthly_paybleamt'];?>" class="form-control form-control-sm ctuspaytyppaydt" title="Weekly/Monthly Payble Amount"  >
					<div id="error1" class="error"></div>
					</div>
					</div>

          <div class="col-md-4 upidiv" style="display: none;">
           <label class="form-label">PhonePay From</label>
		   <div class="input-group">
					 <span class="input-group-text"><i class="fa-solid fa-money-bill-wave"></i></span>
					 
				<select name="upayuser_id"  id="upayuser_id"  class="form-control form-control-sm " title="Get Upi User"  >
				<?php 	$get_lineusrs=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$cust_line[0]['line_id']."', assign_line_ids) > 0 and user_status=1");?>
 	       <option value="">--Select--</option>
		<?php foreach($get_lineusrs as $usrky=>$userv){
        ?>
          <option value="<?php echo $userv['user_id'];?>"><?php echo $userv['full_name'];?></option>
		  <?php }?>
        </select>
					<div id="error1" class="error"></div>
					</div>
					</div>
			</div>
     <?php */?>

						 <div class="table-responsive-custom" id="customusrpaytypdts">

						<table class="custom-table">
    <thead>
      <tr>
        <th>User</th>
        <th>Cash</th>
        <th>UPI</th>
      </tr>
    </thead>
    <tbody>
		<?php  
		$get_lineusrs=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$cust_line[0]['line_id']."', assign_line_ids) > 0 and user_status=1 and user_type_id in(2)");
	foreach($get_lineusrs as $usrky=>$userv){?>
            <tr>
        <td data-label="User"><input type="hidden" name="linusrs[]" value="<?php echo $userv['user_id'];?>"><b><?php echo $userv['full_name'];?></b></td>
        <td data-label="Cash">
          <input type="text" name="usrptyp_<?php echo $userv['user_id'];?>_1" autocomplete="off" inputmode="decimal" oninput="allowOnlyNumbersAndDecimal(this)"  class="input-sm numeric-decimal custpamttypwis">
        </td>
        <td data-label="UPI">
          <input type="text" name="usrptyp_<?php echo $userv['user_id'];?>_2" autocomplete="off" inputmode="decimal" oninput="allowOnlyNumbersAndDecimal(this)"  class="input-sm numeric-decimal custpamttypwis">
        </td>
      </tr>
		<?php }?>
</tbody>
  </table>
						 </div>



					
						<input type="hidden" id="customer_bordtcnt"  value="0" >
					
               <input type="hidden" name="customer_id" value="<?php echo $cust_id;?>">
			    <input type="hidden" name="line_id" value="<?php echo $cust_line[0]['line_id'];?>">
				<input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
				<input type="hidden" name="btn_save_data" value="Update" >
      <!-- Submit Button -->
       <div class="text-center mt-4">
        <button type="button" class="btn btn-success rounded-pill px-4" onclick="valid();">
          <i class="bi bi-check2-circle me-1"></i> Submit
        </button>
      </div>
	  <?php }?>
            <div align="center" id="tekamterr_msg" style="color:red; font-size:14px; "></div>
            <div align="center"  class="error customer_bordtcntmsg" id="customer_bordtcntmsg" style="color:red; font-size:14px; "></div>
    </form>
<?php }else{?>
		
<div class="table-responsive">
                          <!-- 🔍 Search Box -->
                          
                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Borrows List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>


<div class="row g-3 " >
				<div class="col-md-3" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>" >
                <label class="form-label"> Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 					<input type="text" value="<?php echo $dt;?>" id="dateRange"  autocomplete="off"    class="form-control form-control-sm valid dateRange " title="Exp Date"  >
				
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
          <label class="form-label"> City</label>
          <div class="input-group">
           <select class="form-select valid"  id="city_id" name="city_id" onchange="getCitydts(this.value);">
              <option  value="">Select</option>
			<?php
      $getcitydts=$obj_db->qry("select * from ".TABLE_LINE_CITYS." where line_id='".$_REQUEST['line_id']."'");
 			foreach($getcitydts as $cityky=>$cityv){?>
              <option value="<?php echo $cityv['city_id'];?>" <?php if($cityv['city_id']==$_REQUEST['city_id']){?> selected="selected"  <?php }?>><?php echo $cityv['city_name'].' '.$weekdys[$cityv['week_id']];?></option>
            <?php }?>
            </select>
					<div id="error1" class="error"></div>
					</div>
					</div>	


					<div class="col-md-2" >
                <label class="form-label"> &nbsp;</label>
                 <div class="input-group">
				    <button type="button" class="btn btn-success rounded-pill px-4" onclick="get_dailycollection()">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                  </button>
 					</div>
	                </div>
					</div>		

                            <table id="patientTable" class="table align-middle text-dark small tablesortsearchable">
						<?php	
						if($_SESSION['user_type']!='account' && !is_numeric($_REQUEST['line_id']))
						 $usrcnd="";						
						elseif($_REQUEST['line_id']>0 && $_REQUEST['city_id']>0) $usrcnd="  and a.city_id='".$_REQUEST['city_id']."' and a.line_id in(0".$_REQUEST['line_id'].")";
						elseif($_REQUEST['city_id']!='') $usrcnd=" and a.line_id in(0".$_REQUEST['line_id'].") and a.city_id='".$_REQUEST['city_id']."' ";
						elseif($_REQUEST['line_id']>0) $usrcnd=" and a.line_id in(0".$_REQUEST['line_id'].") ";
						else $usrcnd=" and a.line_id in(".$_SESSION['assign_line_ids'].")";
   					 	 $custtakamtqry=$obj_db->qry("SELECT customer_name,weekday,mobile_no,customer_no,a.address,b.*,c.user_name,date_format(str_to_date(b.taken_date,'%Y-%m-%d'),'%d-%m-%Y') as takdt,d.user_name as cuser,date_format(str_to_date(b.cancel_date,'%Y-%m-%d'),'%d-%m-%Y') as cdt FROM ".TABLE_CUSTOMER_DTS." a,".TABLE_CUSTOMER_GENPAYMENTS." b left join ".TABLE_USER_DETAILS." d on b.cancel_by=d.user_id,".TABLE_USER_DETAILS." c where a.customer_id=b.customer_id  and ( date(taken_date) between '".date('Y-m-d',strtotime(trim($dt1)))."' and '".date('Y-m-d',strtotime(trim($dt1)))."' or date(cancel_date) between '".date('Y-m-d',strtotime($dt1))."' and '".date('Y-m-d',strtotime($dt2))."') and b.user_id=c.user_id $usrcnd  order by b.borrow_id desc");

                $iscncel=0;
				  $actcusttakamtqry = array_filter($custtakamtqry,function($v,$k) use ($iscncel){
					 return $v['is_delete'] == $iscncel;
				   },ARRAY_FILTER_USE_BOTH);
?>						<thead>
					<tr >
						  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							 <th data-sort="string">
                                    <span class="sort-handle">Full Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							<th data-sort="number">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
										
						 <th data-sort="number">
                                    <span class="sort-handle">Date <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
					     <th data-sort="string">
                                    <span class="sort-handle">User <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
						 <th data-sort="number">
                                    <span class="sort-handle">TakenAmount <i class="fa-solid fa-sort sort-icon"></i></span>
                         </th>

						 <th data-sort="number">
                                    <span class="sort-handle">IntrestAmt <i class="fa-solid fa-sort sort-icon"></i></span>
                         </th>

						  <th data-sort="number">
                                    <span class="sort-handle">Total <i class="fa-solid fa-sort sort-icon"></i></span>
                         </th>

						 <th data-sort="string">
                                    <span class="sort-handle">Pable W/M <i class="fa-solid fa-sort sort-icon"></i></span>
                         </th>
 						<th>Action</th>
					</tr>
					</thead>
					<tbody role="alert" aria-live="polite" aria-relevant="all">
						<?php	
						$j=1;
  						foreach($actcusttakamtqry as $custky=>$custv) {
						?>	
					<tr>
						<td ><?php echo $j;?></td>
 						<td><?php echo  $custv['customer_name'];?></td>
			<td> <?php echo $custv['mobile_no'];?></td>
 			<td id="borrow_name_<?php echo $custv['borrow_id'];?>"> <?php echo $custv['takdt'];?></td>
			<td > <?php echo $custv['user_name'];?></td>
			<td > <?php echo $custv['tekenamount_without_documentcharge'];?></td>
			<td> <?php echo $custv['interest_amount'];?></td>
			<td> <?php echo $custv['total_amount'];?></td>
			<td><?php echo $custv['weekly_monthly_paybleamt'];?></td>
			<td><?php if($custv['total_amount']==$custv['remain_balance'] && ($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management')){?> 
				 
			<a onClick="if(!confirm('Are you sure want to Delete this...?')) return false;" href="<?php echo $page_url;?>&action=delete&id=<?php echo $custv['borrow_id'];?>&dt=<?php echo $_REQUEST['dt'];?>" onclick="get_confirmdel(<?php echo $custv['borrow_id'];?>);" class="red" style="color:#FF0000" class="btn btn-danger"  > <i class="fa-solid fa-trash-can"></i></a>
			<?php }?></td>
                     </tr>
						<?php  $j++;} ?>
					</tbody>
					<tfooter><tr><td colspan="5">Grand Total</td><td><?php echo array_sum(array_column($actcusttakamtqry,'tekenamount_without_documentcharge'))?></td>
				<td><?php echo array_sum(array_column($actcusttakamtqry,'interest_amount'))?></td>
			<td><?php echo array_sum(array_column($actcusttakamtqry,'interest_amount'))?></td>
		<td><?php echo array_sum(array_column($actcusttakamtqry,'weekly_monthly_paybleamt'))?></td>
 	</tr></tfooter>
				</table>



          <table id="patientTable" class="table align-middle text-dark small  ">
						<?php	
 
                $iscncel=1;
				  $delcusttakamtqry = array_filter($custtakamtqry,function($v,$k) use ($iscncel){
					 return $v['is_delete'] == $iscncel;
				   },ARRAY_FILTER_USE_BOTH);
?>						<thead>
  <tr><th colspan="10" style="text-align: center;">Delete List</th></tr>
					<tr >
						  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							 <th data-sort="string">
                                    <span class="sort-handle">Full Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							<th data-sort="number">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
										
						 <th data-sort="number">
                                    <span class="sort-handle">Date <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
					     <th data-sort="string">
                                    <span class="sort-handle">User <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
						 <th data-sort="number">
                                    <span class="sort-handle">TakenAmount <i class="fa-solid fa-sort sort-icon"></i></span>
                         </th>

						 <th data-sort="number">
                                    <span class="sort-handle">IntrestAmt <i class="fa-solid fa-sort sort-icon"></i></span>
                         </th>

						  <th data-sort="number">
                                    <span class="sort-handle">Total <i class="fa-solid fa-sort sort-icon"></i></span>
                         </th>

						 					</tr>
					</thead>
					<tbody role="alert" aria-live="polite" aria-relevant="all">
						<?php	
						$j=1;
  						foreach($delcusttakamtqry as $custky=>$custv) {
						?>	
					<tr>
						<td ><?php echo $j;?></td>
 						<td><?php echo  $custv['customer_name'];?></td>
			<td> <?php echo $custv['mobile_no'];?></td>
 			<td id="borrow_name_<?php echo $custv['borrow_id'];?>"> <?php echo $custv['cdt'];?></td>
			<td > <?php echo $custv['cuser'];?></td>
			<td > <?php echo $custv['tekenamount_without_documentcharge'];?></td>
			<td> <?php echo $custv['interest_amount'];?></td>
			<td> <?php echo $custv['total_amount'];?></td>
 			
                     </tr>
						<?php  $j++;} ?>
					</tbody>
				</table>
			<?php }?>
                      </div>
 