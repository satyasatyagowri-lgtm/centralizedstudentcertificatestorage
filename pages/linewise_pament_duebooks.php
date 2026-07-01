<?php defined('ACCESS_SUBFILES') or die('Restricted access'); 			
			

$page_url="home.php?p=linewise_pament_duebooks";
		

 			?>
 
<div class="tab-header">
	AccountBook
	<div class="action-icons">
              <button class="excel"><i class="fa-solid fa-file-excel"></i></button>
              <!--<button class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
              <button class="print"><i class="fa-solid fa-print"></i></button>-->
            </div>
</div>
<div class="tab-content">
<div class="row" ng-controller="linewise_accountbook">


<div class="row  " >		
<div class="col-md-3" >
                <label class="form-label"> Line</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select ng-options="x.line_id as x.line_name for x in linedts"  class="form-control form-control-sm" id="line_id" ng-model="line_id" ng-change="get_lines(line_id)">
          <option value="">--Select--</option>         
        </select>				
				</div>
 </div>

 <div class="col-md-3" >
                <label class="form-label"> Week</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select ng-options="x.week_id as x.week_name for x in lineweedts[line_id]"  class="form-control form-control-sm" id="week_id" ng-model="week_id" ng-change="get_lineweeks(week_id)">
          <option value="">--Select--</option>         
        </select>				
				</div>
 </div>

 <div class="col-md-3" >
                <label class="form-label"> City</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<select ng-options="x.city_id as x.city_name for x in linectiydts[line_id][week_id]"  class="form-control form-control-sm" id="city_id" ng-model="city_id" ng-change="get_lineweekcitys(city_id)">
          <option value="">--Select--</option>         
        </select>				
				</div>
 </div>

 <div class="col-md-3"  >
                <label class="form-label"> No.Of.Weeks</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<input type="text" class="form-control form-control-sm" autocomplete="off" ng-model="manualweeks" ng-change="getmanualweek(manualweeks)" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g,);" >				
				</div>
 </div>
	</div>		 
    <div class="panel-body table-responsive" id="excel" >
                             <table  class="table table-striped table-sm m-t-0 table-bordered border-1 feeduecolc"   ng-show="custduelist.length>0">
 														<thead>
															<tr>
                                <th>Sno</th><th>Name</th><th>Cust.No</th><th>TakeAmt</th><th>Date</th><th>Discount</th><th>WeekPayble</th><th>Tot.Paid</th><th>Prev.Due</th><th ng-repeat="x in linweekdtar">{{x}}</th> 
                                 <!--<th ng-repeat="(key, value) in actlinebookdts[0]">{{ value }}</th>-->
  															</tr>
														</thead>
														<tbody >
                               <tr ng-repeat="row in custduelist" >
                                  <td>{{row.sno}}</td>
                                  <td>{{row.customer_name}}</td>
                                  <td>{{row.customer_no}}</td>
                                  <td>{{row.total_amount}}</td>
                                  <td>{{row.takdt}}</td>
                                  <td>{{row.conceamt}}</td>
                                 
                                  <td>{{row.monthly_amt}}</td> <td>{{row.custpaid}}</td><td>{{row.tbal}}  </td>
                                     <td  ng-repeat="(key, value) in row.duedtsar[week_id][city_id]">
                                      <span ng-if="value.dueamt['monthly_amt'] == value.dueamt['monwispaid']" style="color: green; font-weight: bold;">
  {{ value.dueamt['monwispaid'] }}
</span>
<span ng-if="value.dueamt['monthly_amt'] != value.dueamt['monwispaid']" style="color: red; font-weight: bold;">
 {{ value.dueamt['monwispaid'] }}
</span>                              <!--<span ng-if="value.dueamt['monthlydue_amt'] > 0">{{ value.dueamt['monthlydue_amt'] }}</span>
     <span ng-if="value.dueamt['monthlydue_amt'] == 0" style="color: green; font-weight: bold;">PAID</span>
   <span ng-if=" isNaN(value.dueamt['monthlydue_amt'])">NULL</span>-->

                                       <!--<span >{{ value.dueamt['monthlydue_amt'] }}</span>-->
                                  
                                     </td>
                                 </tr>
                              

 
                                  <!--<tr ng-repeat="row in actlinebookdts track by $index" ng-if="$index > 0">
                                     <td ng-repeat="(key, value) in row">
                                       <span ng-if="key<3">{{ value }}</span>
                                     <span ng-if="key>2 && !isNaN(value) && value > 0">{{ value }}</span>
     <span ng-if="key>2 &&!isNaN(value) && value == 0" style="color: green; font-weight: bold;">PAID</span>
   <span ng-if="key>2 && isNaN(value)">NULL</span>





                                     </td>
                                 </tr>-->
												 
 														</tbody>
													</table>
                          <div class="text-center"><i class="fa fa-spinner fa-spin fa-lg fa-fw" ng-show="loading"></i></div>

                          </div>
</div>
</div>