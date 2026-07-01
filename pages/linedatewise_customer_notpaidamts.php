<?php defined('ACCESS_SUBFILES') or die('Restricted access');

			$page_url="home.php?p=linedatewise_customer_notpaidamt";

  $_SESSION['is_notpaidpage']=1;
 $dt1=date('d-m-Y');$dt2=date('d-m-Y');
      if($_REQUEST['frm_date']!='')
	$dt1=$_REQUEST['frm_date'];
 if($_REQUEST['to_date']!='')
	$dt2=$_REQUEST['to_date'];

			    $weekdys=array('SUN','MON','TUE','WED','THU','FRI','SAT');
 			?>
<script>
 
function get_linddts(id){
location.href='<?php echo $page_url;?>&frm_date='+$("#frm_date").val()+'&to_date='+$("#to_date").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
}
function getCitydts(id){ 
location.href='<?php echo $page_url;?>&frm_date='+$("#frm_date").val()+'&to_date='+$("#to_date").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
}
	

 function get_dailycollection(){
	location.href='<?php echo $page_url;?>&frm_date='+$("#frm_date").val()+'&to_date='+$("#to_date").val()+'&line_id='+$("#line_id").val()+'&city_id='+$("#city_id").val();
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

 <div class="tab-content" ng-controller="custnotpaydts">
<div class="tab-header" >
    <!-- Tabs -->
           <?php $actionarr=array('add','edit');?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
			 
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
        
<div class="table-responsive">
                          <!-- 🔍 Search Box -->
                          
                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Customer NotPaid List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>


<?php /*?><div class="row g-3 " >
      <div class="col-md-2" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="frm_date"  to_date="off"   value="<?php echo $dt1;?>"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>


				

 		  <div class="col-md-2" >
          <label class="form-label"> Line</label>
          <div class="input-group">
 			  <select class="form-select valid"  id="line_id" name="line_id" onchange="get_linddts(this.value);">
              <option  value="">ALL LINES</option>
			<?php $lincnd="";
			   if($_SESSION['user_type']!='management')
			       $lincnd=" AND line_id in(0".$_SESSION['assign_line_ids'].") ";
       $linedts=$obj_db->qry("SELECT * FROM ".TABLE_LINE_NAMES." a where  is_delete=0 $lincnd");
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
<?php */?>

          <div class="row  " >
            
          <div class="col-md-2" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="frm_date"  to_date="off" ng-model="g_date"  ng-change="get_dtwisnotpaidlist(g_date);"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>

          <div class="col-md-2" >
                <label class="form-label"> Line</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select ng-options="x.line_id as x.line_name for x in linelist"  class="form-control form-control-sm" id="scrchbarowline_id" ng-model="scrchbarowline_id" ng-change="get_borrowlines()">
        <option value="">--select Line--</option>           
        </select>				
				</div>
           </div>



           <div class="col-md-2" >
                <label class="form-label"> City</label>
                 <div class="input-group"> 
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select   class="form-control form-control-sm" id="borowcity_id" ng-model="borowcity_id" ng-change="get_borowcitys(borowcity_id)">
          <option  value="">--Select--</option>    
          <option ng-repeat="x in orglinectiydts[scrchbarowline_id]['city']" value="{{x.cityid}}">{{x.cityname}}</option>  
        </select>				
				</div>
           </div>
       <input type="hidden" ng-model="is_notpaidpage" value="1">

	</div>		

                            <table id="patientTable" class="table align-middle text-dark small tablesortsearchable">
									<thead>
					<tr >
						  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							 <th data-sort="string">
                                    <span class="sort-handle">Customer <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>

								   <th data-sort="string">
                                    <span class="sort-handle">CustomerNo <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
							<th data-sort="number">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
										
						 <th data-sort="number">
                                    <span class="sort-handle">Date <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
					     <th data-sort="string">
                                    <span class="sort-handle">PendingAmount <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
					</tr>
					</thead>
					<tbody role="alert" aria-live="polite" aria-relevant="all"> 
            <tr ng-repeat="x in getnotpaidcustmers">
              <td>{{$index+1}}</td>
              <td><a ng-click="get_custduedts(x.customer_id);"  data-bs-toggle="offcanvas" onclci data-bs-target="#offcanvasForm">{{x.customer_name}}</a></td>
              <td>{{x.customer_no}}</td>
              <td>{{x.mobile_no}}</td>
              <td>{{x.due_date}}</td>
              <td>{{x.monthlydue_amt}}</td>
            </tr>
 					
				</table>

        <div class="text-center"><i class="fa fa-spinner fa-spin fa-lg fa-fw" ng-show="loading"></i></div>
</div>



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

           <div class="col-md-6"  >
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
</div>