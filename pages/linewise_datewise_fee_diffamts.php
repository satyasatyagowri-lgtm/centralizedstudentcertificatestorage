<?php defined('ACCESS_SUBFILES') or die('Restricted access');
			$page_url="index.php?p=linewise_datewise_fee_diffamt";	
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
						<th >Date</th><th >Line</th><th >User</th>
  						<th class="center"  >line.giv</th>
						<th class="center">Lin.colc</th>
						<th class="center">Line.pays</th>
						<th class="center">Line.Exp</th>

						<th class="center">Lintakfrmoffice</th>
						<th class="center">LinGencolc</th>
						<th class="center">BorwColc</th>
						<th class="center">Ticketrefundcolc</th>


						
						<th class="center">GenExp</th>
						<th class="center">Custpays</th>
						<th class="center">Chitpays</th>
						<th class="center">Sortexp</th>
						<th class="center">Borrowpays</th>
						<th class="center">IntrestDisc</th>
						<th class="center">Chitpaidexp</th>
					</tr>
					</thead>
					
					<tbody role="alert" aria-live="polite" aria-relevant="all" id="showtable_data" >
					 <?php
					 
					 $linedtwiscolcdts=$obj_db->qry("select b.line_id,b.line_name,c.user_name,a.user_id,sum(a.line_taken_amt) as lta,sum(a.line_given_amts) as lga,sum(a.line_collect_amts) as lca,sum(a.line_expense_amt) as lea,((SUM(a.line_taken_amt) + sum(a.line_collect_amts)) - (sum(a.line_given_amts) + sum(a.line_expense_amt))) as rembal,date(date_time) as pdt from ".TABLE_LINEWISE_DATEWISE_AMTS." a ,".TABLE_LINE_NAMES." b,".TABLE_USER_DETAILS." c where a.user_id=c.user_id and date(a.date_time)>'2026-04-23' and a.line_id=b.line_id  group by date(a.date_time),b.line_id,a.user_id order by (a.date_time) asc ,a.line_id asc,a.user_id asc");

                      $getcustomergenpayments=$obj_db->qry("select sum(d.tekenamount_without_documentcharge) as takamt,date(d.taken_date) as tdt,b.line_name,b.line_id,b.line_name,d.user_id as puser_id from ".TABLE_CUSTOMER_GENPAYMENTS." d ,".TABLE_LINE_NAMES." b,".TABLE_CUSTOMER_DTS." a  where    a.customer_id=d.customer_id and a.line_id=b.line_id  and d.is_delete=0 and date(d.taken_date)>='2026-04-23'  group by date(d.taken_date),b.line_id,d.user_id order by date(d.taken_date) asc");
                      $custgenpaysarr=array();
					  foreach($getcustomergenpayments as $gcolcky=>$gcolcv)
						$custgenpaysarr[$gcolcv['tdt']][$gcolcv['line_id']][$gcolcv['puser_id']][]=$gcolcv;

					  $linewiscollections=$obj_db->qry("select sum(a.is_paid_amount) as pamt,date(a.paid_date) as pdt,b.line_name,b.line_id,b.line_name,a.payto_user_id from ".TABLE_CUST_PAYMENTS." a , ".TABLE_LINE_NAMES." b where a.line_id=b.line_id and a.is_delete=0 and date(a.paid_date)>='2026-04-23' group by date(a.paid_date),b.line_id,a.payto_user_id");
					$colecdtsarr=array();
					  foreach($linewiscollections as $lincolky=>$lincolv)
						$colecdtsarr[$lincolv['pdt']][$lincolv['line_id']][$lincolv['payto_user_id']][]=$lincolv;
					//echo '<pre>';print_r($colecdtsarr);echo '</pre>';
 $linexpenses=$obj_db->qry("select sum(a.amount) as expamt,b.line_id,b.line_name,a.payby_user_id,date(a.exp_date) as expdt from ".TABLE_EXPENDITURE." a ,".TABLE_LINE_NAMES." b where   a.line_id=b.line_id  and a.is_cancel=0 and date(a.exp_date)>='2026-04-23' group by date(a.exp_date),a.line_id,a.user_id order by date(a.exp_date) asc ");
               $dailyexpdtsar=array();
					  foreach($linexpenses as $linexpky=>$linexpv)
						$dailyexpdtsar[$linexpv['expdt']][$linexpv['line_id']][$linexpv['payby_user_id']][]=$linexpv;
 $emptakeamts=$obj_db->qry("select sum(a.amount) as taken_amt,b.line_id,b.line_name,a.takenuser_id as puser_id,date(a.taken_date) as takdt  from ".TABLE_EMPUSER_TAKEN_AMTS." a,".TABLE_LINE_NAMES." b where  a.line_id=b.line_id  and  date(a.taken_date)>='2026-04-23' and a.is_delete=0 group by date(a.taken_date),a.line_id,a.takenuser_id   order by date(a.taken_date) asc ");
   $dailyemptakamtdtsar=array();
					  foreach($emptakeamts as $emptakamtky=>$emptakamtv)
						$dailyemptakamtdtsar[$emptakamtv['takdt']][$emptakamtv['line_id']][$emptakamtv['puser_id']][]=$emptakamtv;

	$linchitdts=$obj_db->qry("SELECT  b.line_id,sum(c.paid_amount) as pamt,b.line_name,date(c.paid_date) as pdt,c.by_user_id as busrid FROM ".TABLE_LINE_NAMES." b,".TABLE_LINE_CHITAMT_PAIDDETAILS." c where  c.is_delete=0  and date(c.paid_date)>='2026-04-23'  group by date(c.paid_date),b.line_id,c.by_user_id ORDER BY date(c.paid_date) asc");
      $linchitamtpaidsarr=array();
					  foreach($linchitdts as $lninchitamky=>$lninchitamv)
						$linchitamtpaidsarr[$lninchitamv['pdt']][$lninchitamv['line_id']][$lninchitamv['busrid']][]=$lninchitamv;
 $lineadustmentamts=$obj_db->qry("SELECT b.user_id,sum(adjustment_amt) as adjamts,b.is_sortaccess_borrorwtype,a.line_name,a.line_id,date(b.date_time) as dt FROM ".TABLE_LINE_NAMES." a,".TABLE_LINEWISE_SORTACCESS_BORROWFROMANOTHERLINE_AMTS." b where    a.line_id=b.line_id and b.is_cancel=0 and date(b.date_time) ='2026-03-30'  group by date(b.date_time),b.line_id,b.is_sortaccess_borrorwtype,b.user_id ORDER BY date(b.date_time) asc");
     			$linadjustamtsarr=array();
					  foreach($lineadustmentamts as $linadjamtk=>$linadjamtv)
						$linadjustamtsarr[$linadjamtv['dt']][$linadjamtv['line_id']][$linadjamtv['user_id']][]=$linadjamtv;
					//echo '<pre>';print_r($linadjustamtsarr);echo '</pre>';
						$j=1;$stdids='';
						foreach($linedtwiscolcdts as $stddtky=>$stdtv) {

						$dailycolcs=array_sum(array_column($colecdtsarr[$stdtv['pdt']][$stdtv['line_id']][$stdtv['user_id']],'pamt'));
						 $takfrmofice=array_sum(array_column($dailyemptakamtdtsar[$stdtv['pdt']][$stdtv['line_id']][$stdtv['user_id']],'taken_amt'));

						 $linexpnses=array_sum(array_column($dailyexpdtsar[$stdtv['pdt']][$stdtv['line_id']][$stdtv['user_id']],'expamt'));
						 $linecustomerpays=array_sum(array_column($custgenpaysarr[$stdtv['pdt']][$stdtv['line_id']][$stdtv['user_id']],'takamt'));
						 $linechitpays=array_sum(array_column($linchitamtpaidsarr[$stdtv['pdt']][$stdtv['line_id']][$stdtv['user_id']],'pamt'));
						 $linadustarr=$linadjustamtsarr[$stdtv['pdt']][$stdtv['line_id']][$stdtv['user_id']];
						 
						 $colcaccess=1;
				  $accessamtcolc = array_filter($linadustarr,function($v,$k) use ($colcaccess){
					 return $v['is_sortaccess_borrorwtype'] == $colcaccess;
				   },ARRAY_FILTER_USE_BOTH);
				    $acceamtcolcs=array_sum(array_column($accessamtcolc,'adjamts'));

					$sortaccess=2;
				  $sortamtcolc = array_filter($linadustarr,function($v,$k) use ($sortaccess){
					 return $v['is_sortaccess_borrorwtype'] == $sortaccess;
				   },ARRAY_FILTER_USE_BOTH);
					$sortamtexp=array_sum(array_column($sortamtcolc,'adjamts'));

					$borwcolc=3;
				  $borrwofrmothrscolc = array_filter($linadustarr,function($v,$k) use ($borwcolc){
					 return $v['is_sortaccess_borrorwtype'] == $borwcolc;
				   },ARRAY_FILTER_USE_BOTH);
					$borocolccredit=array_sum(array_column($borrwofrmothrscolc,'adjamts'));


					$borwrepay=4;
				  $borwworepaydts = array_filter($linadustarr,function($v,$k) use ($borwrepay){
					 return $v['is_sortaccess_borrorwtype'] == $borwrepay;
				   },ARRAY_FILTER_USE_BOTH);
					$borowpayexp=array_sum(array_column($borwworepaydts,'adjamts'));


					$intrestdicount=5;
				  $intrestdicout = array_filter($linadustarr,function($v,$k) use ($intrestdicount){
					 return $v['is_sortaccess_borrorwtype'] == $intrestdicount;
				   },ARRAY_FILTER_USE_BOTH);
					$intrestdiscexp=array_sum(array_column($intrestdicout,'adjamts'));

					$ticketrefund=6;
				  $ticketrefundscolc = array_filter($linadustarr,function($v,$k) use ($ticketrefund){
					 return $v['is_sortaccess_borrorwtype'] == $ticketrefund;
				   },ARRAY_FILTER_USE_BOTH);
 					$ticketrefundcolcs=array_sum(array_column($ticketrefundscolc,'adjamts'));

					$ischitspaid=7;
				  $chitsrefunds = array_filter($linadustarr,function($v,$k) use ($ischitspaid){
					 return $v['is_sortaccess_borrorwtype'] == $ischitspaid;
				   },ARRAY_FILTER_USE_BOTH);
					$chitespaidsexp=array_sum(array_column($chitsrefunds,'adjamts'));

					$creditsum=$dailycolcs+$takfrmofice+$acceamtcolcs+$borocolccredit+$ticketrefundcolcs;
						 $debitsum=$linexpnses+$linecustomerpays+$linechitpays+$sortamtexp+$borowpayexp+$intrestdiscexp+$chitespaidsexp;

						  $dtwiseall_crdits_dbtstots=$creditsum-$debitsum;
						
					if($dtwiseall_crdits_dbtstots!=$stdtv['rembal']){
						echo $dtwiseall_crdits_dbtstots.'  '.$stdtv['rembal'];echo '<br>';
						?>
					<tr>
					 <td class=" sorting_1"><?php echo $j;?></td>
					  <td class=" sorting_1"><?php echo $stdtv['pdt'];?></td><td><?php echo $stdtv['line_id'].'  '.$stdtv['line_name'];?></td>
 						<td class="center"><?php echo $stdtv['user_id'].'  '.$stdtv['user_name'];?></td>
						 <td class="center"><?php echo $stdtv['lta'];?></td>
                        <td class="center"><?php echo $stdtv['lca'];?></td>
						<td class="center"><?php echo $stdtv['lga'];?></td>
                        <td class="center"><?php echo $stdtv['lea'];?></td>

<td><?php echo $takfrmofice;?></td>
<td><?php echo $dailycolcs;?></td>
<td><?php echo $borocolccredit;?></td>
<td><?php echo $ticketrefundcolcs;?></td>

 						<td><?php echo $linexpnses;?></td>
						<td><?php echo $linecustomerpays;?></td>
						<td><?php echo $linechitpays;?></td>
						<td><?php echo $sortamtexp;?></td>
						<td><?php echo $borowpayexp;?></td>
						<td><?php echo $intrestdiscexp;?></td>
						<td><?php echo $chitespaidsexp;?></td>
						
					 </tr> 
						<?php  $j++; } 
						}
					?>
					</tbody>					
						</table>
						</div></div>		
					
				   
				     
					</form>         
      
	  </div>
	  </div>
	  </div>
	  </div>
	  