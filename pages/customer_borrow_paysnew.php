<?php defined('ACCESS_SUBFILES') or die('Restricted access'); 			
			require_once("classes/customer_borrow_pay.php");
			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=(int)$_GET['id'];
       $_SESSION['is_notpaidpage']=0;

$page_url="home.php?p=customer_borrow_paynew";
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
	   	  if($(this).val()=='')
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
        if(flag==1 && i==0){ 
 		   	$('.glass').fadeIn();	
	        $('.loadprocess').show();
		  	  $("#frm1").submit();
	 	$("#saveBtn").attr('disabled',true);
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



<style>
    /*tabvar styles */
    .tab-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      background: #0000ff0f;
    }

    /* Tabs styling */
    .nav-tabs {
     /* background: #f1f3f5;
      padding: 0.25rem 0.5rem; */
      border-radius: 0.75rem;
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
      border: none;
    }
    .nav-tabs::-webkit-scrollbar { display: none; }

    .nav-tabs .nav-item { flex: 0 0 auto; margin: 0 0.25rem; }
    .nav-tabs .nav-link {
      border: none;
      color: #117fed;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover { background: #e9ecef; color: #1904a4; }
    .nav-tabs .nav-link.active {
      background: #0d6efd;
      color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    /* Action icons */
    .action-icons button {
      border: none;
      background: transparent;
      font-size: 1.2rem;
      margin-left: 0.5rem;
      transition: transform 0.2s;
    }
    .action-icons button:hover { transform: scale(1.2); }

    /* Colors */
    .excel { color: #28a745; }
    .pdf { color: #dc3545; }
    .print { color: #0d6efd; }

    /* Mobile dropdown */
    .action-icons { display: flex; }
    .action-dropdown { display: none; }

    @media (max-width: 768px) {
      .action-icons { display: none; }
      .action-dropdown { display: block; }
    }

    .tab-content {
      background: #ffffff;
      border: 1px solid #dee2e6;
      padding: 1.25rem;
      border-radius: 0.75rem;
      margin-top: 0.5rem;
    }
  </style>

    <style>
    /* Make Select2 match Bootstrap height */
    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
      height: auto !important;
      min-height: 38px;
      padding: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 28px;
    }
    .select2-container {
    box-sizing: border-box;
    display: block !important;
    /* margin: 0; */
    position: relative;
    /* vertical-align: middle; */
    }
  </style>
    <style>
    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .preview-img {
      max-width: 150px;
      max-height: 150px;
      margin-top: 10px;
      border-radius: 8px;
      display: none;
    }
/* right side toggle styles*/

.offcanvas {
    position: fixed;
    bottom: 0;
    z-index: var(--bs-offcanvas-zindex);
    display: flex;
    flex-direction: column;
    max-width: 100%;
    color: var(--bs-offcanvas-color);
    visibility: hidden;
    background-color: #f9fafb;
    background-clip: padding-box;
    outline: 0;
    transition: var(--bs-offcanvas-transition);
}

.offcanvas-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--bs-offcanvas-padding-y) var(--bs-offcanvas-padding-x);
     background-color: #fff;
     border-bottom: 1px solid rgba(203, 203, 212, 0.622);
}
.p-2 {
    padding: .1rem !important;
}
 .payment-type button {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px 20px;
      margin-right: 8px;
      margin-bottom: 8px;
    }
    .payment-type button.active {
      border: 2px solid #007bff;
      background-color: #e6f0ff;
    }
    .save-btn {
      background: #007bff;
      color: white;
      font-weight: bold;
      padding: 11px;
      width: 100%;
      border: none;
      border-radius: 0px 0px 0px 0px;
    }
    .save-btn:disabled {
      background: #d9deff;
      cursor: not-allowed;
    }
    .offcanvas-body {
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .form-content {
      flex: 1;
      overflow-y: auto;
      padding-bottom: 70px; /* space for save button */
    }
    .save-btn-container {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 0px;
      background: #fff;
      border-top: 0px solid #ddd;
    }
    .collapse-toggle {
      cursor: pointer;
      background: #f1f1f1;
      padding: 10px;
      border-radius: 5px;
    }
    .summary-box {
      display: flex;
      justify-content: space-around;
      margin-bottom: 15px;
    }
    .summary-item {
      text-align: center;
    }
    .summary-item span {
      font-weight: bold;
    }
    .green { color: green; }
    .red { color: brown; }

.section-title{font-size:12px; font-weight:600; color:var(--muted); letter-spacing:.02em; margin:18px 16px 8px}
    .segmented{
      display:flex; gap:8px; flex-wrap:wrap; margin:0 16px 16px; padding:8px; background:#F3F4F6; border-radius:14px; border:1px solid var(--border);
    }
    .segmented input{display:none}
    .segmented label{
      padding:10px 14px; border-radius:12px; font-weight:600; font-size:14px; border:1px solid transparent; cursor:pointer; user-select:none; background:#fff;
      box-shadow:0 1px 0 rgba(0,0,0,.02);
    }
    .segmented input:checked + label{ border-color:var(--accent); box-shadow:0 0 0 3px rgba(11,87,208,.12); color:#0B57D0}


  </style>
          
   <div ng-controller="custpaydts">
     <div class="row">

<div class="tab-header">
Customer Amounts    
</div>

                <div class="tab-content">
                      <div class="tab-pane fade show active" id="home">
                          <div class="card-body">

                          <div class="table-responsive">
                          <!-- 🔍 Search Box -->


<div class="row  " >		
<div class="col-md-4" >
                <label class="form-label"> Line</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select ng-options="x.line_id as x.line_name for x in linelist"  class="form-control form-control-sm" id="scrchline_id" ng-model="scrchline_id" ng-change="get_lines()">
        <option value="">--select Line--</option>           
        </select>				
				</div>
           </div>

            <div class="col-md-4" >
                <label class="form-label"> Week</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select ng-options="x.week_id as x.week_name for x in lineweedts"  class="form-control form-control-sm" id="week_id" ng-model="week_id" ng-change="get_weekcitydts(week_id)">
         </select>				
				</div>
           </div>

           <div class="col-md-4" >
                <label class="form-label"> City</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select ng-options="x.city_id as x.city_name for x in selweklinecity"  class="form-control form-control-sm" id="city_id" ng-model="city_id" ng-change="get_citycustomers()">
                      </select>				
				</div>
           </div>
	</div>		
                           
                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Customers List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>
                             <table id="patientTable" class="table align-middle text-dark small tablesortsearchable">
                              <thead>
                                <tr>
                                  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Full Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">Id.No <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="date">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                
                                  <th data-sort="string">
                                    <span class="sort-handle">Address <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">BalancelAmt <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                 </tr>
                              </thead>
                              <tbody> 
                                <tr  ng-repeat="row in actcustomers" >
																<td >{{$index+1}}</td>
																<td ><a ng-click="get_custduedts(row.customer_id);"  data-bs-toggle="offcanvas" onclci data-bs-target="#offcanvasForm">{{row.customer_name}}</a></td>
																<td >{{row.customer_no}}</td>
																<td >{{row.mobile_no}}</td>
																<td >{{row.address}} {{row.city}}</td>
																<td >{{row.rbal}}</td>
																</tr>	

                              </tbody>
                            </table>
                          </div>
                       </div>                    
                       </div>
                        
                     
                </div>


    
     


        

     </div>
  <!-- ✅ Add this Button somewhere (like above the table) -->

 <!-- <div class="container mt-3 b-1">
  <button class="btn btn-primary" data-bs-toggle="offcanvas" onclci data-bs-target="#offcanvasForm">
    + Add New Party
  </button>
</div>-->
<form   class="form-horizontal needs-validation" novalidate id="customerpayfrm" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasForm">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title text-success">{{custduedts['customer_name']}}
    <!--<p style="font-size: 14px;">{{custduedts['address']}} {{custduedts['city']}}</p>-->
    </h5>    
    <button type="button" class="btn btn-sm btn-light " data-bs-dismiss="offcanvas"><i class="fa-solid fa-arrow-right"></i></button>
    
  </div>
  
  <div class="offcanvas-body">

    <div class="row  " >		
<div class="col-md-6"  ng-show="custloandts.length>1" >
                <label class="form-label"> Loan</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-credit-card"></i></span>
 					<select ng-options="x.borrow_id as x.total_amount for x in custloandts"  class="form-control form-control-sm" id="sborrow_id" ng-model="sborrow_id" ng-change="get_selborrow(sborrow_id)">
                     
				</select>				
				</div>
           </div>

           <div class="col-md-6" >
                <label class="form-label">Paid Date</label>
                 <div class="input-group">
  					<input type="text" autocomplete="off"  class="form-control form-control-sm default-date-picker valid" ng-change="getDayName(g_date)" id="g_date" ng-model="g_date" name="g_date" >
                     
 				</div>
           </div>
	</div>	

 <div class="row">
  <div class="col-md-6"  >
  <small  class="text-primary">TakenDate {{custselbordts['takdt']}}</small>
  </div>
  <div class="col-md-6"   >
  <small  style="padding-right: 20px;" class="text-primary">EndDate {{custselbordts['enddate']}}</small>
  </div>
 </div>


    <div class="form-content " >
     <div class="section-title mb-3 mt-2">PAYMENT DETAILS</div> 

      <!-- Summary Fields -->
     <!-- Summary Line with Border & Background -->
<div class="d-flex justify-content-between text-center mb-4">
  <div class="flex-fill mx-1 p-2 rounded" style="border:1px solid #007bff; background:#eaf3ff;">
    <small class="text-primary">Total</small><br>
    <span class="fw-bold text-primary">{{custselbordts['total_amount']}}</span>
  </div>
  <div class="flex-fill mx-1 p-2 rounded" style="border:1px solid #dc3545; background:#fdeaea;">
    <small class="text-danger">Payable({{custselbordts['pableweeks']}})</small><br>
    <span class="fw-bold text-danger">₹{{custselbordts['custuptodateamt']}}</span>
  </div>
  <div class="flex-fill mx-1 p-2 rounded" style="border:1px solid #28a745; background:#eaf8f0;">
    <small class="text-success">Balance</small><br>
    <span class="fw-bold text-success custrbal">₹{{custselbordts['rbal']}}</span>
  </div>
</div>
<input type="hidden" class="cusorgrbal" value="{{custselbordts['rbal']}}">

      <!-- Amount Input -->
       <div class="mb-4" ng-repeat="x in custborrodts">
		<input type="hidden" id="custpableamt{{x.borrow_id}}" value="{{x.rbal}}">
    <div class="mb-3" >
        <input  name="customer_cashpays[]" ng-model="x.customer_cashpays" ng-disabled="custselbordts['rbal'] <= 0" autocomplete="off" id="customer_cashpays{{x.borrow_id}}" type="text" inputmode="decimal" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" ng-KeyUp="get_amount(x.borrow_id,$index)" class="form-control form-control-lg customerpys" placeholder="₹ Enter cash ">
    </div>
         <div class="mb-3" >
        <input  name="customer_upipays[]" ng-model="x.customer_upipays" ng-disabled="custselbordts['rbal'] <= 0" autocomplete="off" id="customer_upipays{{x.borrow_id}}" type="text" inputmode="decimal" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" ng-KeyUp="get_amount(x.borrow_id,$index)" class="form-control form-control-lg customerpys" placeholder="₹ Enter Upi">
         </div>
        <div id="feerr_{{x.borrow_id}}" style="color:#FF0000;"></div> 
	<input type="hidden" value="{{x.borrow_id}}" name="borrow_id[]"  />
	</div>

      <!-- Payment Type -->
     <!-- <div class="section-title">PAYMENT TYPE</div>
      <div class="segmented justify-content-between align-items-center" role="radiogroup" aria-label="Payment type">
        <input type="radio" id="p-cash" ng-model="p_upi" ng-click="get_paytype(p_upi);" ng-model="payment_type" name="payment_type" value="1" checked>
        <label for="p-cash"><i class="fa-solid fa-wallet"></i> Cash</label>

        <input type="radio" id="p-upi" ng-model="p_upi" ng-click="get_paytype(p_upi);" ng-model="payment_type" name="payment_type" value="2">
        <label for="p-upi"> <i class="fa-brands qrcode"></i> UPI</label>
     
      </div>-->
<div class="row">
<div class="mb-3">
  <!--ng-options="x.user_id as x.full_name for x in lineusrlst" -->
	<select  class="form-control form-control-sm" name="upayuser_id" id="upayuser_id" ng-model="upayuser_id" >
               <option ng-repeat="x in lineusrlst" value="{{x.user_id}}">{{x.full_name}}</option>	        
				</select>		
 </div>
</div>  


      <!-- Transactions Collapse -->
      <div class="mb-3">
        <div class="collapse-toggle d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#transactionsTable">
          <span>Transactions</span>
          <span class="indicator">▼</span>
        </div>
        <div class="collapse mt-2 " id="transactionsTable">
          <table class="table align-middle  text-dark small">
            
              <tr style="background: #a4a2a2;">
                <th>Date</th>
                <th>Paid</th>
               </tr>
             
            <tbody>
              <tr ng-repeat="x in custlstpdts">
                <td>{{x.pdt}}</td>
                <td>{{x.paidamt}}</td>
               </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <input type="hidden" value="{{customer_id}}" name="customer_id" ng-model="customer_id"   />
  <input type="hidden" name="btn_save_data" value="btn_save_data"  />
  
    <!-- Fixed Save Button -->
    <div class="save-btn-container">
      <div class="text-center"><i class="fa fa-spinner fa-spin fa-lg fa-fw" ng-show="loading"></i></div>
      <div align="center"  ng-style="paymentprocesssts == 1 ? {'color':'green','font-size':'16px'} : {'color':'red','font-size':'16px'}"   id="paymentsuccess">{{paymentsuccessmsg}}</div>
      <button type="button" id="saveBtn" class="save-btn btn-sm" ng-click="submittrborrow_frm();" disabled >SAVE</button>
    </div>
    
  </div>
</div>
</form>
 
	<div class=" modalview modalview-dialog confirmdiv" style="z-index:100000;display:none;  position:fixed;" align="center" >
<div class="modal-content"><div class="modal-body">
<button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;" id="del_cancel" onclick="confirm_cancel()"></button>
<div class="bootbox-body confirmboxmsg" ng-style="paymentprocesssts == 1 ? {'color':'green','font-size':'16px'} : {'color':'red','font-size':'16px'}">{{paymentsuccessmsg}}</div>
</div>
<div class="modal-footer" style="padding-right: 47%;"><button type="button" data-bb-handler="success" id="del_confirm"  class="btn btn-sm btn-primary" onclick="window.location.href='index.php'" >OK</button></div>
</div>
</div>

  </div>  

                 
<style>
  .modalview-dialog {
  width: 466px !important;
 }
 .modalview {
   top: 50% !important;
 }
</style>