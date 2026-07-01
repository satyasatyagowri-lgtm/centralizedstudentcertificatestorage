<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			require_once("classes/cancel_payments.php");
			$action = $_GET['action'];
			$pg=$_GET['page'];
			$id=$_GET['id'];

			$page_url="home.php?p=accountswise_rep";

			$obj_press = new cancel_operations();						
			if($id && $action=="delete"){		
			$delmsg=$obj_press->cancel_customer_payment($_REQUEST['id']);
			}
			
			 if($_REQUEST['branch_id']==''){
			  $brn_id=$_SESSION['assign_branch_ids'];
			  $selbrnid=$_SESSION['branch_id'];
			  }
			 else {
			 $brn_id=$_REQUEST['branch_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 }
				
	          $branch_name=$obj_db->fetchRow("select branch_short_name,branch_city,branch_name from ".TABLE_BRANCH." where branch_id='".$_SESSION['branch_id']."'");
		      $branch_name_city=$branch_name['branch_short_name'].'-'.$branch_name['branch_city'];
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
/*function get_confirmdel(id){alert('k');
	$("#hid_browid").val(id);
	$('.glass').fadeIn();
	   $(".confirmdiv").show();
            $(".confirmboxmsg").html("Are you sure wants to delete "+$("#borrow_name_"+id).html()+"...!");
	}*/
	function confirm_box(){
	   $(".confirmdiv").hide();
	   $('.glass').fadeIn();
	   location.href='<?php echo $page_url;?>&action=delete&dt='+$("#daterange-btn").val()+'&id='+$("#hid_recno").val();
	   }
	   
	   function confirm_cancel(){
  $('.glass').fadeOut();	
  $('.loadimg').hide();
  $(".confirmdiv").hide();
 }

function downloadCSV() { 
  const table = document.getElementById("dailycollectionrep");
   let csv = [];
  for (let row of table.rows) {
    let cols = Array.from(row.cells).map(cell => `"${cell.innerText}"`);
    csv.push(cols.join(","));
  }

  const csvBlob = new Blob([csv.join("\n")], { type: "text/csv" });
  const url = URL.createObjectURL(csvBlob);
  const link = document.createElement("a");
  link.href = url;
  var csvtile=$("#extitle").html().replace('.', '_')
  link.download = csvtile;
  link.click();
}
</script>
<input id="hid_recid" type="hidden" />
<input id="hid_recno" type="hidden" />
<div class="tab-header">
	Collection
	<div class="action-icons">
              <button class="excel" onclick="downloadCSV();"><i class="fa-solid fa-file-excel"></i></button>
              <!--<button class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
              <button class="print"><i class="fa-solid fa-print"></i></button>-->
            </div>
</div>
<div class="tab-content">
<div class="row" ng-controller="opcashierwise_report" ng-model="exceltitle">
	
 				
		<div class="row g-3 " >
				


						<div class="col-md-3" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="dateRange"  autocomplete="off"  ng-model="frm_date"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>

          <div class="col-md-3" >
                <label class="form-label">To Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="dateRange"  autocomplete="off"  ng-model="to_date"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>
					
					
 		  <div class="col-md-3" style="display: <?php if($_SESSION['user_type']!='account'){?> block; <?php }else{?> none; <?php }?>">
          <label class="form-label"> Line</label>
          <div class="input-group">
 			<select ng-options="x as x.linename for x in linelist"  class="form-control form-control-sm" id="search_line_id" ng-model="search_line_id" ng-change="get_lines(search_city_id = search_line_id)">
         </select>
					<div id="error1" class="error"></div>
					</div>
					</div>	


					<div class="col-md-3" >
                <label class="form-label"> &nbsp;</label>
                 <div class="input-group">
				    <button type="button" class="btn btn-success rounded-pill px-4" ng-click="get_dailycollection()">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                  </button>
 					</div>
	                </div>
					</div>			
 				   
				        <div class="panel-body table-responsive">
						<table border="1"   cellpadding="1" cellspacing="0" class="table table-striped table-bordered" >
						<thead>
						<tr>
						
						<th ng-repeat="header in payment_types">{{header.ptypname}}</th>
						<th>Total</th>
						</tr>
						</thead>
						<tbody>
						<tr  >
						<td ng-repeat="cell in payment_types">{{cell.patypamt}}</td>
						<td>{{totptypsums}}</td>
						</tr> 
						
						</tbody>
						</table>
						</div>
						<br />
                    <div ng-model="tdat"></div>
						
				  <div  id="excel"  >
 					  <div class="panel-body table-responsive">
						<div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Customer List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>

					   <table id="dailycollectionrep" class="table align-middle text-dark small tablesortsearchable">
						<thead>
						<tr><td colspan="9" align="center" id="extitle"><b>CollectionReport</b></td></tr>
					<tr >
						<th class="center">Sno</th>
						<th data-sort="number" data-sort-method="number">
                                    <span class="sort-handle">CustCode <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
								  <th data-sort="string">
                                    <span class="sort-handle">Line <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
								<th data-sort="number" data-sort-method="number">
                                    <span class="sort-handle">RecNo <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                              <th data-sort="date" >
                                    <span class="sort-handle">Date <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
 						<th class="center" data-sort="string">User</th>
 						<th data-sort="number" data-sort-method="number">
                                    <span class="sort-handle">Amount <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
						
						<th class="center" >Action</th>		
					</tr>
					</thead>
				
					<tbody role="alert" aria-live="polite" aria-relevant="all">
				
					<tr ng-repeat="x in fee_data" >
					<td class="center">{{$index+1}}</td>
					<td class="center">{{x.customer_no}}</td> 
 					<td class="center">{{x.line_name}}</td>
 					<td class="center" id="recname_{{x.id}}">{{x.gen_id}}</td>
					<td class="center" style="width: 25%;">{{x.dt}}</td>
					<td>{{x.user_name}}</td>
					<td class="text-center">{{x.amt}} </td>
					<td class="text-center">
					<a ng-show="x.user_type=='admin' || x.user_type=='management'" ng-click="get_confirmdel(x.id,x.ref_id);" class="red" style="color:#FF0000"  ><i class="fa-solid fa-trash-can"></i></a>
					<input type="hidden" id="recno_{{x.id}}" value="{{x.receipt_no}}" /></td>
					</tr>
					</tbody>
					<tfooter><tr><td colspan="6" align="center"><b>Total</b></td><td class="center" align="center" ng-model="grdtotal">{{grdtotal}}</td></tr></tfooter>
  					
					
					
						</table>
						</div>
					<div class="text-center"><i class="fa fa-spinner fa-spin fa-lg fa-fw" ng-show="loading"></i></div> 
						
						<div class="panel-body table-responsive" ng-show="cancel_data.length>0">
						<table class="table table-striped table-bordered table-hover cashierreport" id="downloadCSV" cellpadding="0" cellspacing="0">	
						<thead>
						<tr>
						<td style="text-align:center; font-size:16px; height:5px" colspan="10"> Cancelled Receipts </td>
						</tr>
					<tr >
						<th >Sno</th>
						<th >Customer Name</th>
  						<th class="center" >City</th>
						<th>Receipt No</th>
						<th>Paid Date</th>
						<th>Cancel Date</th>
						<th>Cancel By</th>
 						<th class="center" >Amount</th>
					</tr>
					
					</thead>
				
					<tbody >
					
					<tr ng-repeat="y in cancel_data" >
					<td>{{$index+1}}</td>
					<td>{{y.customer_name}}</td> 
					<td>{{y.line_name}}</td>
					<td >{{y.receipt_no}}</td>
					<td>{{y.paid_date}}</td>
					<td>{{y.cancel_date}}</td>
					<td>{{y.user_name}}</td>
					<td class="text-center">{{y.amount}}</td>
					</tr>
			
					</tbody>
						</table>
	</div>
	</div>
	</div>
	</div>