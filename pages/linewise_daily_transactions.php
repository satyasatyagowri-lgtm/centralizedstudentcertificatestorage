<?php defined('ACCESS_SUBFILES') or die('Restricted access');

			$page_url="home.php?p=linewise_daily_transaction";

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

</script>
<div class="tab-header">
	linewise DailyTransactions
	<div class="action-icons">
              <button class="excel"><i class="fa-solid fa-file-excel"></i></button>
              <!--<button class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
              <button class="print"><i class="fa-solid fa-print"></i></button>-->
            </div>
</div>
<div class="tab-content">
<div class="row" ng-controller="linewise_daily_transactions" ng-model="exceltitle">
	
 				
		<div class="row  " >
				<div class="col-md-4" >
                <label class="form-label"> Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 					<input type="text"   id="dateRange"  autocomplete="off"  ng-model="dat_range"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				
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
 				   
				       
						
  					  <div class="panel-body table-responsive" id="excel" >
  						<table class="table table-sm table-striped table-hover table-bordered linetransdts" cellpadding="0" cellspacing="0">
														<thead>
															<tr>
																<th>#</th>
																<th class="text-center">Line</th><th>Details</th>
																<th class="text-center">OpenBal</th><th >Collection</th>
																<th class="text-center">TakenFromOffice</th>
																<th >Exp</th>
																<th class="text-center">CustomerTakens</th>																
																<th >Avl</th>
															</tr>
														</thead>
														<tbody >
																<tr  ng-repeat="row in linewisetransactins" >
																<td >{{$index+1}}</td>
																<td ><a href="home.php?p=linewise_dailytransactionoverview&selline_id={{row.line_id}}">{{row.line_name}}</td>
																<td ><a href="home.php?p=linewise_daily_transaction_detail&selline_id={{row.line_id}}">GetDetails</td>
																<td >{{row.openbal}}</td>
																<td >{{row.line_collect_amts}}</td>
																<td >{{row.line_taken_amt}}</td>															
																
																<td >{{row.line_expense_amt}}</td><td >{{row.line_given_amts}}</td><td >{{row.rembal}}</td>
																</tr>	
 														</tbody>
													</table>
													<div class="text-center"><i class="fa fa-spinner fa-spin fa-lg fa-fw" ng-show="loading"></i></div>
					</div>	
 		
	</div>
	</div>
