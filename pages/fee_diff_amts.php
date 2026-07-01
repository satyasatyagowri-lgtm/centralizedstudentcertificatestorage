<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			$page_url="index.php?p=fee_diff_amts";	
			$var='1666.6666';
			echo (int)$var;
			?>

        <style>
		  #error{
		   color:#FF0000;
		  }
		
	.chk_box{
  width   : 28px;!important
  margin  : 0;!important
  padding : 0;!important
  opacity : 0;!important
}
	</style>
	
<div class="row">
		<div class="col-12">
            <div class="card">
        
			 <div class="card-body gradient-crystal-clear mb-2">
							<div class="card-block pt-2 pb-2">
								<div class="media">
									<div class="media-body white text-left">
										<h3 class="card-title font-small-3 mt-0 mb-0 white">FEE DIF AMTS</h3>
									</div>
									<div class="media-right white text-right">
									
									</div>
								</div>
							</div>
							<!--<div id="Widget-line-chart1" class="height-100 WidgetlineChart WidgetlineChartshadow mb-2">					
							</div>-->
						</div>
				
			<div class="card-block">
				 <form   class="form-horizontal" id="frm1" method="post" enctype="multipart/form-data" action="<?php echo $page_url."&action=".$action.($id?"&id=".$id:"");?>">
                 	
						   <br clear="all" />
						  
						    <div class="form-group">
						    <div class="panel-body" align="center">
				  
						<table   class="table table-striped table-bordered table-hover table-sm font-small-3" id="sorting"  >
						<thead>
					<tr  class="tableheader_color">
						<th >Sno</th>
						<th >Name</th>
  						<th class="center" width="10%" >Class</th>
						<th class="center">Org Amount</th>
						<th class="center">Due Amount</th>
						<th class="center">Concess Amount</th>
						<th class="center">Paid Amount</th>
						<th class="center">Difference Amount</th>
										
						
					</tr>
					</thead>
					
					<tbody role="alert" aria-live="polite" aria-relevant="all" id="showtable_data" >
					 <?php
					 
					 $getstdts=$obj_db->qry("select  b.customer_id,b.customer_name from  
					".TABLE_CUSTOMER_DTS." b,".TABLE_LINE_NAMES." c  where b.line_id=23 and b.line_id=c.line_id ");
				 	 $stdfedtsarr=array();
					$getstdfees=$obj_db->qry("select sum(remain_balance) as tdue,sum(total_amount) tamt,customer_id from ".TABLE_CUSTOMER_GENPAYMENTS." where  customer_id in(0".implode(',',array_column($getstdts,'customer_id')).") group by customer_id order by customer_id");
					 foreach($getstdfees as $stdfesky=>$stdfesv)
					  $stdfedtsarr[$stdfesv['customer_id']]=$stdfesv;
					$stdfeconcedtsarr=array();
					$getstdfeeconces=$obj_db->qry("select sum(cones_amt) camt,customer_id from ".TABLE_CUST_CONCESSION." where   customer_id in(0".implode(',',array_column($getstdts,'customer_id')).") and is_cancel=0 group by customer_id");
				       foreach($getstdfeeconces as $stdfeconsky=>$stdfesconcv)
					  $stdfeconcedtsarr[$stdfesconcv['customer_id']]=$stdfesconcv;

					  $stdfepaydtsarr=array();
					$stfpays=$obj_db->qry("select sum(is_paid_amount) pamt,customer_id from ".TABLE_CUST_PAYMENTS." where  customer_id in(0".implode(',',array_column($getstdts,'customer_id')).") and is_delete=0 group by customer_id");
						foreach($stfpays as $stdfepaysky=>$stdfepayvs)
					  $stdfepaydtsarr[$stdfepayvs['customer_id']]=$stdfepayvs;
     			
						$j=1;$stdids='';
						foreach($getstdts as $stddtky=>$stdtv) {
						
					 /*   $get_conces=$obj_db->fetchRow("select sum(concess_amount) camt from ".TABLE_STD_FEE_CONCES." where year_id='".$_SESSION['year_id']."' and customer_id='".$get_feerw['customer_id']."' and is_cancel=0");
						
						$get_paids=$obj_db->fetchRow("select sum(is_paid_amount) pamt from ".TABLE_FEE_PAYMENT." where year_id='".$_SESSION['year_id']."' and customer_id='".$get_feerw['customer_id']."' and receipt_cancelled=0");
						$get_stds=$obj_db->fetchRow("select  first_name,last_name,course_name from ".TABLE_STUDENT_EDU_DETAILS." a,
					".TABLE_STUDENTDETAILS." b,".TABLE_COURSE." c  where a.customer_id=b.customer_id  and a.course_id=c.course_id and a.course_model=1 and a.y_id='".$_SESSION['year_id']."' and b.customer_id='".$get_feerw['customer_id']."'");
					*/
					$paybleamt=$stdfedtsarr[$stdtv['customer_id']]['tamt'];
					$std_tamts=$stdfedtsarr[$stdtv['customer_id']]['tdue']+$stdfeconcedtsarr[$stdtv['customer_id']]['camt']+$stdfepaydtsarr[$stdtv['customer_id']]['pamt'];
					$diffamt=($paybleamt-$std_tamts);
					if($std_tamts!=$paybleamt){$stdids.=$stdfedtsarr[$stdtv['customer_id']]['customer_id'].',';
						?>
					<tr>
					 <td class=" sorting_1"><?php echo $j;?></td>
 						<td class="center"><?php echo $stdtv['first_name'].' '.$stdtv['last_name'].'   '.$stdtv['customer_id'];?></td>
						 <td class="center"><?php echo $stdtv['course_name'];?></td>
						<td><?php echo $stdfedtsarr[$stdtv['customer_id']]['tamt'];?></td>
						<td><?php echo $stdfedtsarr[$stdtv['customer_id']]['tdue'];?></td>
						<td><?php echo $stdfeconcedtsarr[$stdtv['customer_id']]['camt'];?></td>
						<td><?php echo $stdfepaydtsarr[$stdtv['customer_id']]['pamt'];?></td>
						 <td class="center">
						 <?php echo $diffamt;?>
						</td> 
						
					 </tr> 
						<?php  $j++; } 
						}echo $stdids;
					?>
					</tbody>					
						</table>
						</div></div>		
					
				   
				     
					</form>         
      
	  </div>
	  </div>
	  </div>
	  </div>
	  