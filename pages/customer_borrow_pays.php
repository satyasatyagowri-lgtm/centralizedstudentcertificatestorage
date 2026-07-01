<?php defined('ACCESS_SUBFILES') or die('Restricted access'); 			
			require_once("classes/customer_borrow_pay.php");
			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];

$page_url="home.php?p=cust_borrow_pay";
			$obj_press = new fee_operations();		

			if(isset($_POST['btn_save_data'])) {
			if($_POST) $data=$_POST;
			$data = remove_slashes($data);
			extract($_POST);
			$msg = $obj_press->get_fee_struct($data,$id);
			}

 			?>
<script>

	

  function valid(){
$(".entdter").html('');
$('.TempOwner input,textarea').removeClass('valid');
 $('.valid').css('border','1px solid red');
	  var flag=1;
	  $('#error').html('<a href="javascript:void(0)" class="glyphicon glyphicon-remove-circle" title="Outing List" onClick="$(this).parent().slideUp().hide(\'slow\')" style="float:right"></a>');
      var i=0;
      $('.valid').each(function(){
	   	  if($(this).val()!='')
			  {
			   i++;
			  }
			  });

	 $('.valid1').each(function(){
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

      if(i<=0)
	  {
	   $(".valid").css('border','1px solid red');
	   $("#error").html("Give atleast one fee value");
	   }
	   /* if($("#ent_dtsts").val()==0)
        {
		  flag=0; $(".entdter").html('*Your previous Enter Date:'+$("#lstentdt").val()+' Please Check')
		}*/
	  else if(flag==1){
		   	$('.glass').fadeIn();	
	        $('.loadprocess').show();
			 $("#frm1").submit();
		}
	}
	
function payment_types(id){
  if(id!=1){
    $(".payment_div").show();
	$("#cheque_date").addClass('valid1');
	$("#cheque_num").addClass('valid1');
	$("#bank_name").addClass('valid1');
	}
  else {
  $(".payment_div").hide();
  $("#cheque_date").removeClass('valid1');
	$("#cheque_num").removeClass('valid1');
	$("#bank_name").removeClass('valid1');
  }
  }

function get_amount(f,jval)
{ //alert(jval);
  $("#hiddval").val(jval);
  if(isNaN($('#customer_pays'+jval).val()))
  {
    $("#feerr_"+jval).html("Must be Number.....");
	
	$('#customer_pays'+jval).val('');
	$('#customer_pays'+jval).focus();
	$("#comm_recp_"+jval).removeClass('valid1');
  }
  else{
  $("#comm_recp_"+jval).addClass('valid1');
  }
 
 customer_amt=document.getElementById('customer_amt'+jval).value;
if(parseFloat(customer_amt) < parseFloat(f)){
// $('#hamt').html(customer_amt);
  $("#feerr_"+jval).html("Should not grater than :"+customer_amt);
// $('.mvalalert').show();
// $('.glass').fadeIn();
document.getElementById('customer_pays'+jval).value='';
$("#comm_recp_"+jval).removeClass('valid1');
}
else if(parseFloat(customer_amt)>0){
  $("#comm_recp_"+jval).addClass('valid1');
  }

 if($('#customer_pays'+jval).val()=='')
  {
    $("#comm_recp_"+jval).removeClass('valid1');
  }
calculateSum();
 setTimeout(function(){
  if ($("#feerr_"+jval).length > 0) {
    $("#feerr_"+jval).html('');
  }
}, 3000)
}

 function calculateSum() {
     var sum = 0;
     $(".eachpaid").each(function () {
           
         if (!isNaN($(this).val()) && $(this).val().length != 0) {
             sum += parseFloat($(this).val());
         }
     });
     $("#amount_total").val(sum.toFixed(2));
 }
 
 function get_prayarity(id){
 var enter_tot=parseFloat(id);
 var postions='';var upd_arry='';
 $(".eachpaid").val('');
 $(".eachupdpos").each(function(){
   postions +=$(this).val()+',';
 });
 var updaamts='';
 $(".eachupdbal").each(function(){
   updaamts +=$(this).val()+',';
 });
 var updamt_trim=updaamts.slice(0,-1);  //To remove last comma value from item_ids
	  var upd_arry=new Array();
	 upd_arry = updamt_trim.split(",");
 
 var positon_trim=postions.slice(0,-1);  //To remove last comma value from item_ids
	  var position_arry=new Array();
	  position_arry = positon_trim.split(",");
	  for(var i=0;i<position_arry.length;i++){
	  
        if(enter_tot>=upd_arry[i])
		{
		 $("#customer_pays"+position_arry[i]).val(upd_arry[i]);
		 enter_tot=enter_tot-upd_arry[i];
		}
		else if(enter_tot<upd_arry[i] && enter_tot>0){
		$("#customer_pays"+position_arry[i]).val(enter_tot);
		 enter_tot=0;
		}
	  }

}

function get_isfullpaid(id){//alert(id);
  var totpaidsum=0;
  if(id==1){
   var tstdamt=parseFloat($("#tot_stdamt").val());
   $("#amount_total").val(parseFloat($("#tot_stdamt").val()));
      get_prayarity(tstdamt);  
  
	// $(".amount_total").val(parseFloat($("#tot_stdamt").val()));
	 }
	else{
	 //$(".eachamt").attr('readonly',false);
	$(".eachpaid").val('');
	$("#amount_total").val(0);
	}
}
 
 function get_totamount(id){
   if(isNaN(id))
  {
    $("#tfeerr").html("Must be Number.....");
	$('#amount_total').val('');
	$(".eachpaid").val('');
	$('#amount_total').focus();
  }
  if(parseFloat($("#tot_stdamt").val()) < parseFloat(id)){
  $("#tfeerr").html("Should not grater than :"+$("#tot_stdamt").val());
  $('#amount_total').val('');
  $(".eachpaid").val('');
  $('#amount_total').focus();
}
setTimeout(function(){
  if ($("#tfeerr").length > 0) {
    $("#tfeerr").html('');
  }
}, 3000)
}
 
function close_numerr(){
$("#customer_pays"+$("#hiddval").val()).focus();
$('.numalert').hide();
$('.mvalalert').hide();
	$('.glass').fadeOut();
}	

function get_date(id){//alert(id);
	var sdt=$("#g_date").val().split('-');
	var ldt=$("#lstentdt").val().split('-');//alert(sdt[2]+'-'+sdt[1]+'-'+sdt[0]+'   '+ldt[2]+'-'+ldt[1]+'-'+ldt[0]);
	 var dateOne = new Date(sdt[2], sdt[1], sdt[0]); //Year, Month, Date    
           var dateTwo = new Date(ldt[2], ldt[1], ldt[0]); //Year, Month, Date    
           if (dateTwo > dateOne) 
		      $("#ent_dtsts").val(0);
			else $("#ent_dtsts").val(1);
	}
 </script>

<div class="tab-header">
    <!-- Tabs -->
           <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
 					 <li class="nav-item "><button    class="nav-link   active " data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-user-friends"></i>
 Customer Pay</button></li>
            </ul>

  </div>

                <div class="tab-content">

 				<form   class="form-horizontal needs-validation" novalidate id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
 
						<?php
						if($_REQUEST['cust_id']!='')
				  $cust_id=$_REQUEST['cust_id'];
				 else $cust_id=$data['cust_id'];
				 $cust_line=$obj_db->qry("select * from  ".TABLE_CUSTOMER_DTS." where  customer_id='".$cust_id."'"); 
				 $get_linedts=$obj_db->qry("select line_id,line_name from ".TABLE_LINE_NAMES." ");
				 $custliky=array_search($cust_line[0]['line_id'],array_column($get_linedts,'line_id'));
				  $getcustprevamtdts=$obj_db->qry("select * from  ".TABLE_CUSTOMER_GENPAYMENTS." where  customer_id='".$cust_id."' and is_delete=0 and remain_balance>0"); 
				 $line_lsttamtdt=$obj_db->fetchRow("SELECT date(taken_date) as takdt FROM ".TABLE_LINETAKEN_AMTS."  where line_id='".$cust_line[0]['line_id']."' order by date(taken_date) desc limit 1");
		if($line_lsttamtdt['takdt']!='')
		 $takdt=date('d-m-Y',strtotime($line_lsttamtdt['takdt']));
		else $takdt="";
						           ?>
					 <p class="mb-3 border-bottom pb-2">Customer : <?php echo $cust_line[0]['customer_name'];?></p>
				  <p class="mb-3 border-bottom pb-2">Mobile : <?php echo $cust_line[0]['mobile_no'];?>,<?php echo $get_linedts[0]['line_name'];?></p>
					
					<p class="mb-3 border-bottom pb-2">Select Date<br /><input type="text"  class="form-control default-date-picker form-control-sm valid1" onchange="get_date(this.value);" value="<?php echo date('d-m-Y');?>" name="g_date" id="g_date"  placeholder="Date"></p>				
 			
<div class="table-responsive">
                        
                            <table id="patientTable" class="table align-middle text-dark small">
                            <thead>
                            <tr >
							<th> SNo</th>
                                <th> Narration No</th>
                                <th class="hidden-phone">Customer Amount</th>
                                <th>Due Amount</th>
								<th>
								Enter
								</th>
                            </tr>
                            </thead>
						<?php	
								$cust_orgamount	=0;
								$remain_balance	=0;
				 $eachfe_totamt=$obj_db->fetchRow("select sum(remain_balance) as tdue from ".TABLE_CUSTOMER_GENPAYMENTS." where  customer_id='".$cust_id."' ");
	 $getcustborowsdts=$obj_db->qry("select *,date(taken_date) as cdt  from ".TABLE_CUSTOMER_GENPAYMENTS." where customer_id='".$cust_id."'   group by borrow_id having sum(remain_balance)>0 order by borrow_id asc");

 						$i=0;
                        $j=1;
						$tot_rbl=0;$tot_custorgamt=0;
						foreach($getcustborowsdts as $custbrwky=>$custbrwv) {
                        if(date('d-m-Y',strtotime($custbrwv['cdt']))!=date('d-m-Y'))
						 $dayof_purchase="<b style='color:#FF0000'> [Old Balance] </b>";
						else $dayof_purchase="";
						$color=array('danger','success','info','warning','success','info');
						?>						
						<tbody>
                            <tr>
							<td > <?php echo $j;?></td>
							<td > <?php echo $custbrwv['borrow_name']."   ".$dayof_purchase;?></td>
                                <td style="color:#0033FF"> <?php echo $custbrwv['total_amount'];?></td>
                                <td  class="hidden-phone" id="eachpayamt<?php echo $i;?>"><?php echo $term_due= $custbrwv['remain_balance'];
								$tot_rbl=$tot_rbl+$custbrwv['remain_balance'];								
								$cust_orgamt=$custbrwv['total_amount'];
								$cust_orgamount=$cust_orgamount+$custbrwv['total_amount'];
								$remain_balance=$remain_balance+$term_due;
								  $graph=($cust_orgamt-$term_due)*100/$term_amount;
								?></td>
 								<td >
							<input class="form-control valid eachpaid" type="text" name="customer_pays[]" id="customer_pays<?php echo $i;?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" onKeyUp="get_amount(this.value,<?php echo $i;?>)" size="1" width="80"   style="height:30px;"  autocomplete="off"  />
							<div id="feerr_<?php echo $i;?>" style="color:#FF0000;"></div>
							   
								</td>
								<div id="numerr_<?php echo $i;?>" style="color:#FF0000; font-size:16px;"></div>
							   <div id="grateramt_<?php echo $i;?>" style="color:#FF0000; font-size:16px;"></div>
								<td>
                                    <div class="progress progress-striped progress-xs">
                                        <div style="width: <?php echo round($graph)?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" 
										class="progress-bar progress-bar-<?php echo $color[$i]?>">
                                            
                                        </div>
                                    </div>
                                </td>
								</tr>                          
					<input type="hidden" value="<?php echo $custbrwv['remain_balance']; ?>" id="customer_amt<?php echo $i;?>" class="eachupdbal"  />
					<input type="hidden" value="<?php echo $i; ?>"  class="eachupdpos"  />
					<input type="hidden" value="<?php echo $custbrwv['borrow_id']; ?>" name="borrow_id[]"  />
						<?php $i++;$j++; } ?>

						<div id="error"></div>
						<input type="hidden" value="<?php echo $i;?>" name="ivalue" id="ivalue"  />
                        <input type="hidden" value="<?php echo $cust_id;?>" name="customer_id"   />
						<input type="hidden" value="<?php echo $customerdetails['branch_id'];?>" name="branch_id"   />
						<input type="hidden" value="<?php echo $depostrem_bal;?>" id="rem_depostitamt" />
						<tr>
						<td></td>
						<td>Total</td>
						
						<td><?php echo $cust_orgamount; ?></td>
						<td><?php echo $tot_rbl; ?></td>
						<td>
						<input type="hidden" value="<?php echo $eachfe_totamt['tdue'];?>"  id="tot_stdamt"  />
					<input class="form-control form-control-sm" autocomplete="off" type="text" name="amount_total" id="amount_total" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" onKeyUp="get_totamount(this.value)" onchange="get_prayarity(this.value);" autocomplete="off"><div id="tfeerr" style="color:#FF0000; font-size:14px;"></div>
					<!--<input class="form-control" style="text-align:right;height:30px;" type="text" name="amount_total"  size="1" width="80" id="amount_total" value="" readonly="">-->
					</td>
</tr>
</tbody>
</table> 
</div>	
<input type="hidden" value="<?php echo $cust_id; ?>" name="customer_id" id="customer_id"  />					 
  <input type="hidden" name="btn_save_data" value="btn_save_data"  />



<div class="row  g-3 mt-3">
							 <div class="col-md-2 col-xs-4 mb-2"><b>Payment Type</b><select class="form-control form-control-sm valid2" id="payment_type"  title="Select Payment type" onChange="payment_types(this.value);"   name="payment_type" >
 					<?php $bankname_qry=$obj_db->get_qresult("select a.* from  ".TABLE_PAYMENT_TYPE." a  ");
						  while($bankname_qryrw=$obj_db->fetchArray($bankname_qry)){?>
					<option value="<?php echo $bankname_qryrw['pay_type_id'];?>"><?php echo $bankname_qryrw['pay_name'];?></option>
						<?php }?>
						   </select>

						   <div id="error2" class="error"></div>
						   </div>
</div>  

					
 					  <div class="row  g-3 mt-3 payment_div" id="payment_div" style="display:none" >
                 <div class="col-md-3">
					<div class="form-group">
					<p class="card-title mb-1 text-muted">Tr. Date</p>
					<input type="date" class="form-control default-date-picker " onchange="get_cashlesstransactiondate(this.value)" title="Date" id="cheque_date" autocomplete="off" name="cheque_date" />

					<div id="error1" class="error cashlessdt_err"></div>
					
					</div>
					</div>
					<input type="hidden" id="curmondtsts" value="0">
						
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
					</div>
					
					
					 </div>	 

					<div align="center" id="last_entdate" style="color:#FFFFFF"></div>
                      <input type="hidden" id="lstentdt" value="<?php echo $takdt;?>" />
							 <input type="hidden" id="ent_dtsts" value="1" ><input type="hidden" id="line_id" value="<?php echo $cust_line[0]['line_id'];?>" />
						<div class="text-center mt-4">
        <button type="button" class="btn btn-success rounded-pill px-4" onclick="valid();">
          <i class="bi bi-check2-circle me-1"></i> Submit
        </button>
      </div>
						   <div align="center" class="entdter" style="color:#FF0000;"></div>
 

				
       <!-- Submit Button -->
      
     </form>

                      </div>
                 
