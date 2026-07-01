<?php defined('ACCESS_SUBFILES') or die('Restricted access');

			$page_url="home.php?p=linewise_daily_transaction_detail";

			 if($_REQUEST['branch_id']==''){
			  $brn_id=$_SESSION['assign_branch_ids'];
			  $selbrnid=$_SESSION['branch_id'];
			  }
			 else {
			 $brn_id=$_REQUEST['branch_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 }
				
	          $branch_name=$obj_db->fetchRow("select branch_short_name,branch_city,branch_name from ".TABLE_BRANCH." where branch_id='".$_SESSION['branch_id']."'");
			   $linedts=$obj_db->fetchRow("select * from ".TABLE_LINE_NAMES." where line_id='".$_REQUEST['selline_id']."'");
		      $branch_name_city=$branch_name['branch_short_name'].'-'.$branch_name['branch_city'];
	
	$_SESSION['selline_id']=$_REQUEST['selline_id'];
	?>		

  <script>
   function getdate()
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&dt='+dt+'&branch_id='+$("#branch_id").val()+'&course_id='+$("#course_id").val();
	}
	function getcourseid(id)
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&dt='+dt+'&branch_id='+$("#branch_id").val()+'&course_id='+$("#course_id").val();
	}
	function get_branches(id)
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&dt='+dt+'&branch_id='+$("#branch_id").val();
	}
  </script>
    
<?php		

			
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
 $_SESSION['dt']=$dt;
?>			
<script type="text/javascript">
function printTable(obj) {
$(".hide_inprint").remove();
content = document.getElementById(obj).innerHTML;
newwin = window.open('');
newwin.document.write('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"\n',
'"http://www.w3.org/TR/html4/strict.dtd">\n',
'<html>\n',
'<head>\n',
'<title>Printing...</title>\n',
'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">\n',
'<body style="font:12px Arial, Helvetica, sans-serif;">\n',
''+content+'\n',
'</body>\n',
'</html>');
newwin.print();
newwin.close();
}

</script>
<style>
  

  .calculator {
    width: 100%;
    margin: auto;
    border: 1px solid #ccc;
    background-color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .header {
    background-color: #8bc34a;
    color: #3a4a24;
    font-size: 24px;
    font-weight: bold;
    padding: 15px;
  }

  .payment {
    background-color: #b0a89f8f;
    color: black;
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    padding: 0px 9px;
  }

  .section {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid #ccc;
  }

  .section div {
    width: 100%;
  }

 

  .box h3, .box2 h3 {
    background-color: rgba(0, 0, 0, 0.75);
    color: white;
    margin: 0;
    padding: 10px;
    font-size: 16px;
  }

  .box table, .box2 table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }

  .box td, .box2 td {
    padding: 8px 10px;
    border-bottom: 1px solid #ddd;
  }

  .box td:nth-child(2),
  .box2 td:nth-child(2) {
    text-align: right;
  }




/* Add to your CSS file */
.hide-for-pdf {
    display: none !important;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    display: table-header-group;
}

tr {
    page-break-inside: avoid;
    page-break-after: auto;
}

/* Ensure table cells have borders for better readability */
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
}
</style>

<style>
.glass-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-family: Poppins, sans-serif;
}

.glass-table th {
  background: rgba(0, 0, 0, 0.75);
  color: white;
  padding: 12px;
  backdrop-filter: blur(4px);
}

.glass-table td {
  padding: 12px;
  background: rgba(255, 255, 255, 0.4);
  border-bottom: 1px solid rgba(0,0,0,0.2);
}

.glass-table tr:hover td {
  background: rgba(255, 255, 255, 0.7);
}
</style>

<div class="tab-header">
	linewise Transactions Details
  <div style="display: <?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management'){?> block; <?php }else{?> none; <?php }?>">
	<!--<div class="action-icons">
              <button  id="exportExcel" class="excel"><i class="fa-solid fa-file-excel"></i></button>
              <button  id="downloadPdf" class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
             </div>-->

            <!-- Action dropdown (Mobile) -->
            <div class="  dropdown">
              <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-download"></i>
              </button>
            <ul class="dropdown-menu dropdown-menu-end">
    <li>
        <button  id="exportExcel" class="dropdown-item text-success" href="#">
            <i class="fa-solid fa-file-excel me-2"></i> Excel
        </button>
    </li>

    <li id="exportPDF_bk">
        <button  id="downloadPdf" class="dropdown-item text-danger">
            <i class="fa-solid fa-file-pdf me-2"></i> PDF
        </button>
    </li>
</ul>

            </div>
</div>
</div>

<!--<div class="mb-3">
    <button id="downloadPdf" class="btn btn-success">
        Download PDF
    </button>
</div>-->


<div class="tab-content">
<div class="row" ng-controller="linewise_daily_transaction_details" ng-model="exceltitle">
	
 				
		<div class="row  " >
				<div class="col-md-4" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="dateRange"  autocomplete="off"  ng-model="frm_date"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>

          <div class="col-md-4" >
                <label class="form-label">To Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="dateRange"  autocomplete="off"  ng-model="to_date"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>
		
<!--<div class="col-md-4" >
                <label class="form-label"> Line</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<?php /*?><select ng-options="x as x.linename for x in linelist"  class="form-control form-control-sm" id="search_line_id" ng-model="search_line_id" ng-change="get_lines(search_city_id = search_line_id)">
                      </select><?php */?>
				<select ng-options="x.line_id as x.line_name for x in linelist"  class="form-control form-control-sm" id="scrchline_id" ng-model="scrchline_id" ng-change="get_lines(scrchline_id)">
                      </select>		
				</div>
					</div>-->

					<div class="col-md-4" >
                <label class="form-label"> &nbsp;</label>
                 <div class="input-group">
				    <button type="button" class="btn btn-success rounded-pill px-4" ng-click="get_dailycollection()">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                  </button>
 					</div>
	                </div>
					</div>			
					<div style="display: none;" id="export_datatitle"><?php echo $linedts['line_name'].'_From';?>{{frm_date}}_To_{{to_date}}</div>
 				  <div id="exportTitle"
     data-title="<?php echo $linedts['line_name']; ?>">
</div>
					<div id="exportArea" class="exportArea">
            
   					
  					  <div class="panel-body table-responsive" id="excel" >

					       <div class="calculator " id="calculator">
							<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0">
														<thead>
							<tr style="text-align: center;"  ><td ng-repeat="x in	prevamts_frmlinepatypwisear">{{x.usrfulname}} {{x.linpamts}}</td></tr>
														</thead>
							</table>
   <div class="row">
    <div style="display: none;" id="export_datatitle"><?php echo $linedts['line_name'].'_From_';?>{{frm_date}}_To_{{to_date}}</div>
    	<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0">
        <tr><td class="payment col-md-6">
    
  <div  ><?php echo $linedts['line_name'];?> <h6>From {{frm_date}} To {{to_date}}</h6></div>
  </td><td class="payment col-md-6">
  <div >Opening Balance <br>{{lineprevcolcrembal}}</div>
  </td>
</tr>
</table>
  </div>

  <div class="section">
    <table >
      <tr><td width="50%" style="text-align: center;">
       <table> 
        <tr><td colspan="2" style="text-align: center;" align="center"> CREDITS</td></tr>
        <tr><td>Collections</td><td style="text-align: center;" align="center">{{lineoveralview.colc | number:2}}</td></tr>
        <tr><td>Investment</td><td style="text-align: center;" align="center">{{lineoveralview.emptak | number:2}}</td></tr>
         
        <tr><td>IntrestAmount</td><td style="text-align: center;" align="center">{{lineoveralview.intrestanddocucharge | number:2}}</td></tr>
<tr ng-repeat-start="(key, value) in adjustment_incomdtsarr['adustcolcname']">
    <td colspan="2" style="text-align: center;"><strong>{{value['adjname']}} </strong></td>
</tr>

<tr ng-repeat-end ng-repeat="q in lineadustmentamtarrs[value.adjtyp]">
    <td>{{ q.reason }}</td>
    <td style="text-align: center;font-weight:600;" align="center">{{ q.adjustment_amt }}</td>
</tr> 
<tr><td>Total </td><td  style="text-align: center;font-weight:600;" align="center">   {{ (isNaN(parseFloat(grdcredits)) || grdcredits == 'NaN' || grdcredits == null || grdcredits === '') ? 0 : grdcredits }}</td></tr>
       
        </table>
  
 


</td>
<td width="50%" style="text-align: center;">
  
       <table class="adjclass">
        <tr><td colspan="2" align="center" style="text-align: center;font-weight:600;"> DEBITS</td></tr>
		<tr><td>Payments</td><td style="text-align: center;" align="center">{{lineoveralview.custborrow | number:2}}</td></tr>
        <tr><td>Expense</td><td style="text-align: center;" align="center">{{lineoveralview.expamt | number:2}}</td></tr>
        <tr ng-repeat-start="(key, value) in adjustment_incomdtsarr['adustexpname']">
    <td colspan="2" style="text-align: center;"><strong>{{value['adjname']}} </strong></td>
  </tr>

<tr ng-repeat-end ng-repeat="q in lineadustmentamtarrs[value.adjtyp]">
    <td  >{{ q.reason }}</td>
    <td style="text-align: center;" align="center">{{ q.adjustment_amt }}</td>
</tr>
        <!-- <tr ng-repeat="(key,value) in adjustment_incomdtsarr['adustexpname']"><td>{{value}}</td><td>{{adjustment_incomdtsarr['adustexpamt'][key] | number:2}}</td></tr>-->
          <tr ng-show="linechitamtpaidtotamt>0"><td>Chit Payments</td><td style="text-align: center;" align="center">{{linechitamtpaidtotamt | number:2}}</td></tr>
          <tr><td>Total </td><td  style="text-align: center;font-weight:600;" align="center">  {{ isNaN(parseFloat(grddebits)) ? 0 : grddebits | number:2}} </td></tr>
           <tr><td>Closing Balance :</td><td  style="text-align: center;font-weight:600;" align="center">  {{ isNaN(parseFloat(lineoveralview['rembal'])) ? 0 : lineoveralview['rembal'] | number:2}} </td></tr>
       </table>
 
 


</td></tr>
</table>
    
  </div>
</div>



<!--	<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0">
														<thead>
															<tr style="text-align: center;"  ><td ng-repeat="x in	prevamts_frmlinepatypwisear">{{x.usrfulname}} {{x.linpamts}}</td></tr>
															<tr><th class="text-center">OpenBal</th>
 																<th class="text-center">Collection</th>
																<th class="text-center">TakenFromOffice</th>
																<th class="text-center">Expense</th><th class="text-center">CusomerTakenAmt</th><th class="text-center">IntrestDocumentCharge</th>
 																<th >Bal</th>
															</tr>
														</thead>
														<tbody> 
															<tr > 
 																<td class="text-center">{{lineprevcolcrembal}}</td><td class="text-center" ng-repeat="x in lineoveralview">{{x}}</td>
																
															</tr>
														</tbody>
						</table>--->

<!--<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0" ng-show="usrwiseptypavlbal.length>0">
														<thead>
                              <tr><th>Sno</th><th>City</th><th>Amt</th></tr>
                            </thead>
                            <tbody>
															<tr ng-repeat="x in linecitywisecolcamts">
 																<th >{{$index+1}}</th><th >{{x.city_name}}</th><th>{{x.citywisamt}}</th>
															</tr>
                           </tbody>
													
						</table>-->

							<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0" ng-show="usrwiseptypavlbal.length>0">
														<thead>
															<tr>
 																<th class="text-center">Name</th>
																<th ng-repeat="x in ptypes">{{x.pay_name}}</th>
															</tr>
														</thead>
														<tbody>
															<tr ng-repeat="x in usrwiseptypavlbal">
 																<td class="text-center" ng-repeat="y in x">{{y}}</td>
																
															</tr>
														</tbody>
						</table>



            <table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0" >
            <tr><td  style="text-align: center;">Collection</td></tr>      
            <tr ng-repeat="x in	custcolcusrarr"><td>{{x.full_name}} {{x.amts}}</td></tr></table>
   						<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0" ng-show="linecolcdt.length>0">
														<thead>
  															<tr>
																<th>#</th>
																<th class="text-center ">Name</th><th>IdNo</th>
																<th class="text-center">Collection</th>
																<th class="text-center">Type</th><th class="text-center">User</th>
																
															</tr>
														</thead>
 														<tbody >
																<tr  ng-repeat="row in linecolcdt" >
																<td width="4px;" >{{$index+1}}</td>
															 <td class="">{{row.customer_name}}  </td><td>{{row.customer_no}}</td>
																<td >{{row.is_paid_amount}}</td>
																<td >{{row.pay_name}}</td>															
																<td >{{row.puser_fullname}}</td>
																</tr>	
                                <tr><td colspan="5" style="text-align: center;">Citywise Amounts</td></tr>
                                <tr ng-repeat="x in linecitywisecolcamts">
 																<th colspan="3" ></th><th >{{x.city_name}}</th><th>{{x.citywisamt | number:2}}</th>
															</tr>
                              <th colspan="3" ></th><th >Total : </th><th>{{citywisegrdtots | number:2}}</th>
 														</tbody>
													</table>

 
							<div ng-show="lineexpdts.length>0">
 							<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0" >
              <tr><td  style="text-align: center;">Expeces</td></tr>    
              <tr ng-repeat="x in	expdtsusrtypptyparr"><td>{{x.full_name}} {{x.amts}}</td></tr></table>
  						<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0">
														<thead>
 															<tr>
																<th>#</th>
																<th class="text-center">Type</th><th class="text-center">Reason</th>
																<th class="text-center">Amt</th>
																<th class="text-center">Type</th><th class="text-center">User</th>
																
															</tr>
														</thead>
														<tbody >
																<tr  ng-repeat="row in lineexpdts" >
																<td >{{$index+1}}</td>
																<td >{{row.exp_name}}</td><td >{{row.reason}}</td>
																<td >{{row.amount}}</td>
																<td >{{row.pay_name}}</td>															
																<td >{{row.puser_fullname}}</td>
																</tr>	
 														</tbody>
													</table>
							</div>



							<div ng-show="lineemptakamts.length>0">
 						<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0" >
            <tr><td  style="text-align: center;">Taken AmountFrom Line</td></tr>  
            <tr ng-repeat="x in	emptakamtptypusrtyparr"><td>{{x.full_name}} {{x.amts}}</td></tr></table>
  						<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0">
														<thead>
 															<tr>
																<th>#</th>
																<th class="text-center">Amt</th>
																<th class="text-center">Type</th><th class="text-center">User</th>
																
															</tr>
														</thead>
														<tbody >
																<tr  ng-repeat="row in lineemptakamts" >
																<td >{{$index+1}}</td>
 																<td >{{row.taken_amt}}</td>
																<td >{{row.pay_name}}</td>															
																<td >{{row.puser_fullname}}</td>
																</tr>	
 														</tbody>
													</table>
							</div>


 							<div ng-show="getcustomergenpayments.length>0">
                            <div align="center">Customer Borrows</div>
                            <table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0" >
                              <tr><td  style="text-align: center;">Customer Borrows</td></tr>
                              <tr ng-repeat="x in	lincusttakamtptypusrtyparr"><td>{{x.full_name}} {{x.amts}}</td></tr></table>
   						<table class="table table-sm table-striped table-hover table-bordered export-table" cellpadding="0" cellspacing="0">
														<thead>
  															<tr>
																<th>#</th>
																<th class="text-center">Name</th><th>IdNo</th>
																<th class="text-center">Amt</th>
																<th class="text-center">Interest</th><th class="text-center">User</th>
																
															</tr>
														</thead>
														 <tbody ng-init="totTaken=0; totInterest=0;">
    <tr ng-repeat="row in getcustomergenpayments"
         ng-init="
          rowAmt = (row.taken_amount && !isNaN(row.taken_amount)) ? parseFloat(row.taken_amount) : 0;
          rowInt = (row.interest_amount && !isNaN(row.interest_amount)) ? parseFloat(row.interest_amount) : 0;
          totTaken = totTaken + rowAmt;
          totInterest = totInterest + rowInt;
        ">
      <td>{{$index + 1}}</td>
      <td>{{row.customer_name}} </td><td> {{row.customer_no}}</td>
      <td style="text-align:right;">{{row.total_amount | number:2}}</td>
      <td style="text-align:center;">{{row.intrestanddocucharge | number:2}}</td>
      <td style="text-align:center;">{{row.puser_fullname}}</td>
    </tr>

    <!-- Grand Total Row -->
    <tr style="font-weight:bold; background-color:#f9f9f9;">
      <td colspan="3" style="text-align:right;">Grand Total</td>
      <td style="text-align:right;">{{totTaken | number:2}}</td>
      <td style="text-align:center;">{{totInterest | number:2}}</td>
      <td></td>
    </tr>
  </tbody>
													</table>
							</div>
													<div class="text-center"><i class="fa fa-spinner fa-spin fa-lg fa-fw" ng-show="loading"></i></div>
					</div>	
					</div>
	</div>
	</div>


<style>
    @font-face {
        font-family: 'Noto Sans Telugu';
        src: url('https://fonts.gstatic.com/s/notosanstelugu/v26/0FlxVOGZlE2Rrtr-Hmqk7bL6egma-bryLw.ttf') format('truetype');
    }
    
    * {
        font-family: 'Noto Sans Telugu', 'Nirmala UI', 'Segoe UI', 'Gautami', 'Vijaya', sans-serif;
    }
    
    /* Improve table rendering */
    table {
        border-collapse: collapse;
        width: 100%;
        page-break-inside: avoid;
    }
    
    td, th {
        border: 1px solid #ddd;
        padding: 8px;
        vertical-align: top;
    }
</style>