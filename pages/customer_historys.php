<?php defined('ACCESS_SUBFILES') or die('Restricted access');
	  require_once("classes/customer_history.php");
			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];
			
			$page_url="home.php?p=cust_hist";
 			$obj=new fee_operations();
			
			if($action=='conce_bk'){
			   $rollbk_amt=$obj->get_fee_rollback($_REQUEST['customer_id'],$_REQUEST['amount'],$_REQUEST['concid']);
			}
			if($_POST['button_save']=="button_save"){
			  $data=$_POST;
			  $msg=$obj->get_fee_struct($data);
			}

			
	//		$givenDate = "2025-12-09"; // YYYY-MM-DD format
//echo $newDate = date("Y-m-d", strtotime($givenDate . " +12 weeks"));		?>
  <script>
   function chekconcesion_status(id){//alert('k');
     $("#hiddval").val(id);
    if(isNaN($("#concession_"+id).val())){
	$("#conceer_"+id).html('*Must be Number..');
	//  $('.numalert').show();
	//$('.glass').fadeIn();
	 $("#concession_"+id).val('');
	 $("#concession_"+id).focus();
   }
   else if(parseInt($("#concession_"+id).val())>parseInt($("#dueamt_"+id).val())){
    // $('#hamt').html($("#dueamt_"+id).val());
	 $("#conceer_"+id).html('*Must be or equal to '+$("#dueamt_"+id).val());
 //$('.mvalalert').show();
// $('.glass').fadeIn();
	 $("#concession_"+id).val('');
	 $("#concession_"+id).focus()
    }
	
	setTimeout(function(){
  if ($("#conceer_"+id).length > 0) {
    $("#conceer_"+id).html('');
  }
}, 3000)
	}
	
	function update_takendate(brid,ptyp,linid,cityid){
		if($("#upd_takendt_"+brid).val()!=''){
			$("#updbtn_"+brid).hide();
	    $('.glass').fadeIn();	
	    $('.loadimg').show();
	     $.ajax({	 
					  url:'includes/api/customer_details.php',  
					  type:'POST',  
					  data:{'action':'update_borrowdate','br_id':brid,'updbrw_date':$("#upd_takendt_"+brid).val(),'prev_takdate':$("#prev_takendt_"+brid).val(),'line_id':linid,'city_id':cityid,'pay_type':ptyp,'access_tokenid':'<?php echo $_SESSION['lograndval']?>'},
					 
					 success:function(data)
					  {					
					   if(data!=''){
						var dt=data.split('^');
 						if(dt[1]>0){ 
							$("#errdatemsg_"+brid).html(dt[2]);
							 $('.glass').fadeOut();	
	                  $('.loadimg').hide();
					  $("#updbtn_"+brid).show();
                       }
						else{
							$("#errdatemsg_"+brid).html('');
					  	location.href="<?php echo $page_url."&customer_id=".$_REQUEST['customer_id'];?>";
						$("#prevtakdat_"+brid).html($("#upd_takendt_"+brid).val());
					  $('.glass').fadeOut();	
	                  $('.loadimg').hide();
                       }
                       }
				     }
			});
		}else{
			alert("Date Must Give....");
		}
	     
	}
	
	function close_numerr(){
$("#concession_"+$("#hiddval").val()).focus();;
$('.numalert').hide();
$('.mvalalert').hide();
	$('.glass').fadeOut();
}
	
	function closeabsents(){
		$(".absent_popup").hide();
		$(".glasses").hide();
		$(".studentabsent").hide(data);
	}
	
	function valid(){
	  

 $('.valid').css('border','1px solid red');
	  var flag=1;
	  $('#error').html('<a href="javascript:void(0)" class="glyphicon glyphicon-remove-circle" title="Outing List" onClick="$(this).parent().slideUp().hide(\'slow\')" style="float:right"></a>');
	  var i=0;
      $('.valid').each(function(){
	   	  if($(this).val()!='')
			  {
			   i++;
			   flag=0; 
			  }
			  });
      if(i<=0)
	  {//alert(i);
	   $(".valid").css('border','1px solid red');
	   $("#error").html("Give atleast one fee value");
	  
	   }
	  else if(i>0 && $("#creason").val()==""){
	  // alert(i);
	    $(".valid").css('border','1px solid #ccc');
	    $("#cerror").html("Give Reason");
		$(".cerror").css('border','1px solid red');
	   }
	  
	  //if(i>0 && $("#creason").val()!="")
	  else
	   { //alert("k");
	      $(".valid").css('border','1px solid #ccc');
		  $(".glass").fadeIn();
          $(".loadprocess").show();
	      $(' #conce_frm ').submit();
	     
  
	     }
	

	}
	
	function get_confirmdel(custid,camt,conid){//alert('k');
	$("#hid_custid").val(custid);
	$("#hid_camt").val(camt);
	$("#hid_concesid").val(conid);
	$('.glass').fadeIn();
	   $(".confirmdiv").show();
            $(".confirmboxmsg").html("Are you sure wants to delete "+$("#borrow_name_"+conid).html()+"...!");
	}
	function confirm_box(){
	   $(".confirmdiv").hide();
	   $('.glass').fadeIn();
	   location.href='<?php echo $page_url;?>&action=conce_bk&customer_id='+$("#hid_custid").val()+'&amount='+$("#hid_camt").val()+'&concid='+$("#hid_concesid").val();
	   }
	   
	   function confirm_cancel(){
  $('.glass').fadeOut();	
  $('.loadimg').hide();
  $(".confirmdiv").hide();
 }
  </script>
  <input type="hidden" id="hid_custid" />
  <input type="hidden" id="hid_camt" />
  <input type="hidden" id="hid_concesid" />
  
     
	<input type="hidden" id="hiddamt" >
	<input type="hidden" id="hiddval" >
  
             <!-- page start-->

<div class="tab-header">
	Customer Hislory
	
</div>
<div class="tab-content">

					  <input type="hidden" id="sec_data" value="sec_data" />
	 <?php	
					 	 $std_select_query="SELECT *  FROM ".TABLE_CUSTOMER_DTS."   where  customer_id='".$_GET['customer_id']."'";
						$resgetstddetails=$obj_db->fetchRow($std_select_query);
 						$custconceqry=$obj_db->qry("select *,b.total_amount,taken_amount,date_format(str_to_date(conces_date,'%Y-%m-%d %H:%i:%s %p'),'%d-%m-%Y %h:%i:%s %p') as concedt,c.full_name from ".TABLE_CUST_CONCESSION." a,".TABLE_CUSTOMER_GENPAYMENTS." b,".TABLE_USER_DETAILS." c where a.customer_id='".$_REQUEST['customer_id']."' and a.user_id=c.user_id and a.borrow_id=b.borrow_id and is_cancel=0 order by conces_id,date(conces_date) desc");
						
						$getcust_genpayqry=$obj_db->qry("select *,(total_amount-tot_amt_updconces) as tconce,tot_amt_updconces,(tot_amt_updconces-remain_balance) as paid,remain_balance from ".TABLE_CUSTOMER_GENPAYMENTS."  where  customer_id='".$_REQUEST['customer_id']."' and is_delete=0 group by borrow_id having sum(remain_balance)>0 order by date(taken_date) asc");
 						?>
			<table   class="table table-striped table-hover table-bordered" >
			  
			  <tr bgcolor="#FFFFFF">
				<th>Name </th>
				<td ><b><?php echo $resgetstddetails['customer_name'];?></b></td>
				<th>Address:<b><?php echo $resgetstddetails['address'];?></b> </th>
			  </tr>	
			  <tr >
			   
				<th>Mobile No </th>
				<td><b><?php echo $resgetstddetails['mobile_no'];?></b></td>
	            <td   width="120" height="120" style="width:20%;">
				<?php if(!file_exists('../includes/stu_img/'.$resgetstddetails['customer_id'].'.jpg')){
				  $std_img="../includes/stu_img/photo.jpg";
				 }
				 else{$std_img="../includes/stu_img/".$resgetstddetails['customer_id'].".jpg";
                  }
				 ?>
				Witness : <?php echo $getcust_genpayqry[0]['witness_name'];?><br>
	            MObile  : <?php echo $getcust_genpayqry[0]['witness_mobile'];?>
				 
				  
				  </td>
			  </tr>
			  
			  
			  
			</table>
			</div>
			
		<!----End Student Info --->
		
		<div class="row match-height">
    <div class="col-xl-12 col-lg-12">
      <div class="card">
        
        <div class="card-body ">
          <div class="card-block">

			 <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#due"><i class="fa-solid fa-money-bill-wave"></i> Due</button></li>
              <?php if($_SESSION['is_concession_permission']==1 || $_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management'){?>
			  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#concession"><i class="fa-solid fa-tag"></i> Discount</button></li>
			  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancelconce"><i class="fa-solid fa-ban"></i> Cancel</button></li>
			  <?php }if($_SESSION['is_changeborrowdate_permission']==1 || $_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management'){?>
			 <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#updtakendate"><i class="fa-solid fa-calendar"></i> DateChange</button></li>
			  <?php }?>
  			 </ul>
            <div class="tab-content px-1 pt-1 panel-body table-responsive">
               
              <div class="tab-pane" id="cancelconce" aria-labelledby="base-tab3">
				<table  class="table table-striped table-bordered mb-0 table-sm font-small-3">
						<thead>
					<tr >
					  <th>Sno</th><th>Date</th>
					  <th>ActualAmt</th>
 					  <th>Amount</th>
					  <th>GivenBy</th>
					  <th>Reason</th>	
					  <th>Action</th>	
					  
					   </tr>
					   </thead>
					<?php
			
			       $p=1;
					 foreach($custconceqry as $custconcky=>$custconv){;
					?>
					<tr >
					<td><?php echo $p; ?> </td><td><?php echo $custconv['concedt']; ?> </td>
					<td id="borrow_name_<?php echo $custconv['conces_id'];?>"><?php echo $custconv['total_amount']; ?> </td>
					<td ><?php echo $custconv['cones_amt']; ?> </td><td ><?php echo $custconv['full_name']; ?> </td>
					<td><?php echo $custconv['reason']; ?> </td>
					<td><a onClick="if(!confirm('Are you sure want to Delete this...?')) return false;" href="<?php echo $page_url;?>&action=conce_bk&customer_id=<?php echo $custconv['customer_id'];?>&amount=<?php echo $custconv['cones_amt'];?>&concid=<?php echo $custconv['conces_id'];?>"  style="cursor:pointer;color:red;"  ><i class="fa-regular fa-trash-can"></i></a>
					 </td>
					
					</tr>
					<?php 
					$p++;} ?>
					
					<tr>
					<div id="error"></div>
					<td colspan="7"> </td>
					</tr>
					<tr>
				
				<td align="center" colspan="6"> 
				</td>
				</tr>
				</table>
                  </div><!-- /.tab-pane -->
                  <div class="tab-pane table-responsive" id="concession">
                   <form action="" id="conce_frm" method="post" >
					<?php $_SESSION['form_token'] = bin2hex(openssl_random_pseudo_bytes(32));?>
						<table   class="table table-striped table-bordered mb-0 table-sm font-small-3">
						<thead>
					<tr >
					 <th>Date</th>
					  <th>Actual Amount</th>
					  <th>Concession</th>
					  <th>Customer Fee</th>	
					  <th>Is Paid</th>	
					  <th>Is Due</th>	
					  <th>Amount</th>	
					  
					   </tr>
					   </thead>
 <?php			
			       $p=0;
				   
					 foreach($getcust_genpayqry as $custgpky=>$custgpv) {
				 //      $get_dconces=$obj_db->fetchRow("select sum(cones_amt) as camt from ".TABLE_CUST_CONCESSION." where customer_id='".$custgpv['customer_id']."' and borrow_id='".$custgpv['borrow_id']."' ");
						
					//	$get_dpaids=$obj_db->fetchRow("select sum(is_paid_amount) as pamt from ".TABLE_CUST_PAYMENTS." where customer_id='".$custgpv['customer_id']."' and borrow_id='".$custgpv['borrow_id']."' and is_delete=0");
					?>
					<tr style="color: #666666">
 					<td><?php echo date('d-m-Y',strtotime($custgpv['taken_date'])); ?> </td>
					<td><?php echo $custgpv['total_amount']; ?> </td>
					<td><?php echo $custgpv['tconce']; ?> </td>
					<td><?php echo $custgpv['tot_amt_updconces']; ?> </td>
					<td><?php echo $custgpv['paid']; ?> </td>
					<td><?php echo $custgpv['remain_balance']; ?>
					 <input type="hidden"  id="dueamt_<?php echo $p;?>"  value="<?php echo $custgpv['remain_balance']; ?>" class="span4" />
					  <input type="hidden" name="customer_id" value="<?php echo $custgpv['customer_id'];?>" />
					   <input type="hidden" name="borrow_id[]" value="<?php echo $custgpv['borrow_id'];?>" />
					 </td>
				<td><input type="text" id="concession_<?php echo $p;?>" autocomplete="off" name="concession[]" onKeyUp="chekconcesion_status(<?php echo $p;?>);" value=""
				 class="valid form-control span6" /><div style="color:#FF0000; font-size:14px;" id="conceer_<?php echo $p;?>"></div></td>
					
					</tr>
					<?php 
					$p++;}?>
					
					<input type="hidden" name="ivalue" value="<?php echo $p;?>" />
					<input type="hidden" name="branch_id" value="<?php echo $resgetstddetails['branch_id'];?>" />
					
					<tr>
					<div id="error"></div>
					<td colspan="7"> 
					<textarea name="conce_reason" id="creason"  class="form-control valid cerror" placeholder="Reason for Concession"></textarea>
					<div id="cerror"></div>
					</td>
					</tr>
					<tr>
				<td align="center" colspan="3">
				<input type="hidden" value="button_save" name="button_save" />
				 <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
                      <button type="button" onClick="valid();" class="btn btn-sm btn-primary">Submit</button>
                
				</td>
				<td align="center" colspan="6"> 
				 <a href="#" class="btn btn-sm btn-primary" data-rel="back">Cancel</a>
				</td>
				</tr>
				</table>
				</form>
                  </div><!-- /.tab-pane -->

<div class="tab-pane table-responsive" id="updtakendate">
 						<table   class="table table-striped table-bordered mb-0 table-sm font-small-3">
						<thead>
					<tr >
					 <th>Date</th>
					  <th>Actual Amount</th>
					  <th>Concession</th>
					  <th>Customer Amt</th>	
					  <th>Is Paid</th>	
					  <th>Is Due</th>	
					  <th>EnterDate</th>	
					  <th>Update</th>
					   </tr>
					   </thead>
 <?php			       $p=0;
				   
					 foreach($getcust_genpayqry as $custgpky=>$custgpv) {
				 //      $get_dconces=$obj_db->fetchRow("select sum(cones_amt) as camt from ".TABLE_CUST_CONCESSION." where customer_id='".$custgpv['customer_id']."' and borrow_id='".$custgpv['borrow_id']."' ");
						
					//	$get_dpaids=$obj_db->fetchRow("select sum(is_paid_amount) as pamt from ".TABLE_CUST_PAYMENTS." where customer_id='".$custgpv['customer_id']."' and borrow_id='".$custgpv['borrow_id']."' and is_delete=0");
					?>
					<tr style="color: #666666">
 					<td id="prevtakdat_<?php echo $custgpv['borrow_id'];?>"><?php echo date('d-m-Y',strtotime($custgpv['taken_date'])); ?> </td>
					<td><?php echo $custgpv['total_amount']; ?> </td>
					<td><?php echo $custgpv['tconce']; ?> </td>
					<td><?php echo $custgpv['tot_amt_updconces']; ?> </td>
					<td><?php echo $custgpv['paid']; ?> </td>
					<td><?php echo $custgpv['remain_balance']; ?></td>
				<td><input type="text" id="upd_takendt_<?php echo $custgpv['borrow_id'];?>" autocomplete="off" name="upd_takendt_<?php echo $custgpv['borrow_id'];?>" class="valid form-control default-date-picker span6" />
				<input type="hidden" id="prev_takendt_<?php echo $custgpv['borrow_id'];?>"  value="<?php echo date('d-m-Y',strtotime($custgpv['taken_date'])); ?>"/>
			 <div id="errdatemsg_<?php echo $custgpv['borrow_id'];?>" style="color:#FF0000;font-size:16px;"></div>	
			</td>
					<td><a id="updbtn_<?php echo $custgpv['borrow_id'];?>" style="cursor:pointer;" onclick="update_takendate(<?php echo $custgpv['borrow_id'].','.$custgpv['pay_type'].','.$resgetstddetails['line_id'].','.$resgetstddetails['city_id'];?>);" >Update</a>
			            
				</td>
					</tr>
					<?php 
					$p++;}?>
					</table>
                   </div><!-- /.tab-pane -->

                  <div class="tab-pane table-responsive active" id="due">
                   <table   class="table table-striped table-bordered mb-0 table-sm font-small-3">
						<thead>			  
			  <tr >
   			    <th>Actual Amount </th> 
				<th>Discount </th>
				<th>Customer Amount</th>
				<th>Paid Amount</th>
				<th>Due</th>
			  </tr>
			  </thead>
			  <?php
								$customer_amt	=0;
								$custorg_amt	=0;
								$sum_custorgamt=0;
								$sum_conceamt=0;
								$sum_custamt=0;
								$sum_pamt=0;
								$sum_rembal=0;
			  // $getcust_genpayqry=$obj_db->qry("select *,(total_amount-tot_amt_updconces) as tconce,tot_amt_updconces,(tot_amt_updconces-remain_balance) as paid,remain_balance from ".TABLE_CUSTOMER_GENPAYMENTS."   where customer_id='".$_REQUEST['customer_id']."' and is_delete=0 group by borrow_id having sum(remain_balance)>0 order by date(taken_date) asc");
 						$i=0;
					//	$get_conces=$obj_db->qry("select sum(cones_amt) as camt from ".TABLE_CUST_CONCESSION." where customer_id='".$custpv['customer_id']."' and borrow_id in(0".implode(',',array_column($getcust_genpayqry,'borrow_id')).") ");
						
					//	$get_paids=$obj_db->qry("select sum(is_paid_amount) as pamt from ".TABLE_CUST_PAYMENTS." where customer_id='".$custpv['customer_id']."' and borrow_id in(0".implode(',',array_column($getcust_genpayqry,'borrow_id')).")  and is_delete=0");
						foreach($getcust_genpayqry as $custgpky=>$custgpv) {
						
						//$customer_amt= $custgpv['total_amount']-$get_conces['camt'];
						$customer_amt= $custgpv['tot_amt_updconces'];
						$custorg_amt= $custgpv['total_amount'];
						
						?>						
			  <tr bgcolor="#FFFFFF">
 				<td><a href="home.php?p=customer_borrow_pending_paiddt&customer_id=<?php echo $_REQUEST['customer_id'];?>&borrow_id=<?php echo $custgpv['borrow_id'];?>" ><?php echo number_format($custorg_amt);?></a> </td>
				<td><?php echo number_format($custgpv['tconce']);?> </td>
				<td><?php echo number_format($custgpv['tot_amt_updconces']);?> </td>
				
				<td><?php echo  number_format($custgpv['paid']);?> </td>
				<td><?php echo number_format($custgpv['remain_balance']) ?> </td>
			  </tr>
			  
			  <?php 
			 					$sum_custorgamt	=$sum_custorgamt+$custgpv['total_amount'];
								$sum_conceamt	=$sum_conceamt+$custgpv['tconce'];
								$sum_custamt			=$sum_custamt+$customer_amt;
								$sum_pamt		=$sum_pamt+$custgpv['paid'];
								$sum_rembal			=$sum_rembal+$custgpv['remain_balance'];
			  } ?>	
			   <tr bgcolor="#FFFFFF">
 				<th><?php echo number_format($sum_custorgamt); ?></th>
				<th><?php echo number_format($sum_conceamt); ?></th>
				<th><?php echo number_format($sum_custamt); ?></th>
				<th><?php echo number_format($sum_pamt); ?></th>
				<th><?php echo number_format($sum_rembal); ?></th>
				</tr>
			</table>
	</div>
	
	
	
	<br clear="all" />
	
	<div class="tab-pane table-responsive active" id="cust_pays">
                   <table   class="table table-striped table-bordered mb-0 table-sm font-small-3" >
						<thead>			 <tr >
				<th align="center" class="center" colspan="4"> <div align="center">Payments</div></th></tr> 
			  <tr >
				<th>Sno</th>
  			    <th>TakenDate</th> 
				<th>Amount </th>
				<th>PaidDate</th>
			  </tr>
			  </thead>
			  <?php
			   $cust_paymenst=$obj_db->qry("select b.*,a.borrow_name,date(b.paid_date) as pdt,date(taken_date) as tdt from ".TABLE_CUSTOMER_GENPAYMENTS." a,".TABLE_CUST_PAYMENTS." b where a.customer_id='".$_REQUEST['customer_id']."' and b.is_delete=0 and a.customer_id=b.customer_id and a.borrow_id=b.borrow_id order by date(paid_date) asc");
 						$i=1;
						foreach($cust_paymenst as $custpayky=>$custpv) {
						?>						
			  <tr bgcolor="#FFFFFF"><td><?php echo $i;?> </td>
				<td><?php echo date('d-m-Y',strtotime($custpv['tdt']));?> </td>
				<td><?php echo number_format($custpv['is_paid_amount']);?> </td>
				<td><?php echo date('d-m-Y',strtotime($custpv['pdt']));?> </td>
			  </tr>
			  <?php 
			 					$tpaid	=$tpaid+$custpv['is_paid_amount'];
			 $i++; } ?>	
			   <tr bgcolor="#FFFFFF">
				<td colspan="2">Grand Total</th>
				
				<th><?php echo number_format($tpaid); ?></th>
				</tr>
			</table>
	</div>
		
	</div>
	</div>
	</div>
	</div>
	</div>
</div>