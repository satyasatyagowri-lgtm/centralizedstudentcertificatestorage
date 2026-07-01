<?php defined('ACCESS_SUBFILES') or die('Restricted access');

			$page_url="home.php?p=due_list_uptodate";

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
	DueLists
	<div class="action-icons">
              <button  id="exportExcel" class="excel"><i class="fa-solid fa-file-excel"></i></button>
              <button id="exportPDF" class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
             <!-- <button class="print"><i class="fa-solid fa-print"></i></button>-->
            </div>

            <!-- Action dropdown (Mobile) -->
            <div class="action-dropdown dropdown">
              <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-download"></i>
              </button>
            <ul class="dropdown-menu dropdown-menu-end">
    <li id="exportExcel">
        <a class="dropdown-item text-success" href="#">
            <i class="fa-solid fa-file-excel me-2"></i> Excel
        </a>
    </li>

    <li id="exportPDF">
        <a class="dropdown-item text-danger">
            <i class="fa-solid fa-file-pdf me-2"></i> PDF
        </a>
    </li>
</ul>

            </div>
</div>
<div class="tab-content">
<div class="row" ng-controller="duelist_uptodate" ng-model="exceltitle">
	
 				
		<div class="row  " >
				<div class="col-md-3" >
                <label class="form-label"> Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 					<input type="text"  id="dat"  autocomplete="off"  ng-model="dat" ng-change="get_datetodue(dat);"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				
				</div>
					</div>
		
<div class="col-md-3" >
                <label class="form-label"> Line</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<!--<select ng-options="x as x.linename for x in linelist"  class="form-control form-control-sm" id="search_line_id" ng-model="search_line_id" ng-change="get_lines(search_city_id = search_line_id)">
                      </select>	-->

					  <select ng-options="x.line_id as x.line_name for x in linelist"  class="form-control form-control-sm" id="search_line_id" ng-model="lineid" ng-change="get_lines(lineid)">
                        <option value="">--Select--</option>
					</select>

				</div>
					</div>

					<div class="col-md-3" >
                <label class="form-label"> Week</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span> 
 					<select ng-options="x.week_id as x.week_name for x  in lineweekarr[linid]"  class="form-control form-control-sm" id="week_id" ng-model="week_id" ng-change="get_lineweekcitys(week_id)">
                      </select>				
				</div>
					</div>

					<div class="col-md-3" >
                <label class="form-label"> City</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>  
 					<select ng-options="x.city_id as x.city_name for x  in linewekctiydtsarr"  class="form-control form-control-sm" id="city_id" ng-model="city_id" ng-change="get_lineweekcityolc(city_id)">
                      </select>				
				</div>
					</div>
					</div>			
 				   
				       
						<div style="display: none;" id="export_datatitle">DueList-{{dat}}</div>
 				  
					<div id="exportArea" class="exportArea">
  					  <div class="panel-body table-responsive" id="excel" >
  						<table class="table table-sm table-striped table-hover table-bordered feeduecolc" cellpadding="0" cellspacing="0">
														<thead>
															<tr>
																<th>#</th>
																<th class="text-center">Name</th><th>Cust.No</th>
 																<th >TakenDate</th>
																<th >TotalAmt</th><th >Discount</th>
																<th >Pending Months/Week</th>
																<th class="text-center">Total</th>
																<th >Mon/Week Amt</th><th >TotalPaid</th>
																<th >LastPaid</th>
																<th >OverallPending</th>
															</tr>
														</thead>
														<tbody >
																<tr  ng-repeat="row in duedts" >
																<td >{{$index+1}}</td>
																<td >{{row.customer_name}}</td><td >{{row.customer_no}}</td>
 																<td >{{row.tdt}}</td>
																
																
																<td >{{row.org_amount}}</td><td >{{row.conces}}</td>
																<td >{{row.tweeks}}</td>
																<td >{{row.term_due}}</td>
																<td >{{row.monthly_amount}}</td><td >{{row.tpaid}}</td>
																
																<td >{{row.lst_padidt}}</td>
																<td >{{row.remain_balance}}</td>
																</tr>	
														<tr><td colspan="4">Grand Tot</td>
														
														<td ng-model="tot_org" >{{tot_org}}</td>														
														<td ng-model="tot_conces" >{{tot_conces}}</td><td></td>
														<td ng-model="tot_custamt" >{{tot_wekdue}}</td>
														<td ng-model="tot_monweekdue" >{{tot_weekpabledue}}</td>
														<td ng-model="tot_tpaid" >{{tot_tpaid}}</td>
														<td></td>														
														
														<td ng-model="sub_tot" >{{tot_bal}}</td></tr>
														</tbody>
													</table>
													<div class="text-center"><i class="fa fa-spinner fa-spin fa-lg fa-fw" ng-show="loading"></i></div>
					</div>	
 		
	</div>
	</div>
</div>
