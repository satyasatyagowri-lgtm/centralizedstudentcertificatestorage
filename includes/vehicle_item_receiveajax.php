<?php session_start();
      include "DbConfig.php";
   if($_REQUEST['vehicle_receivdts']=='vehicle_receivdts'){
   $chaseconct='';$trmexistchaseno='';$errsts=0;
   for($i=0;$i<count($_REQUEST['chase_no']);$i++){
     $chaseconct.='"'.$_REQUEST['chase_no'][$i].'"'.',';
   }
  // print_r($_REQUEST);
   $trmchaseno=substr($chaseconct,0,-1);
   $chk_chasenos=$obj_db->qry("select * from ".TABLE_EACHVEHICLE_ITEM_ENTRY_DTS."");
   $eachvehdtschsno = array_map(function ($value) {
    return  $value['chase_no'];
}, $chk_chasenos); 

 $eachvehdtsmotno = array_map(function ($value) {
    return  $value['motor_no'];
}, $chk_chasenos); 

 $eachvehdtsbatno = array_map(function ($value) {
    return  $value['battery_no'];
}, $chk_chasenos); 

 $eachvehdtschargno = array_map(function ($value) {
    return  $value['charger_no'];
}, $chk_chasenos); 
   
   
   $getdupchsno=array_intersect($_REQUEST['chase_no'],$eachvehdtschsno);
   $getdupmotno=array_intersect($_REQUEST['motor_no'],$eachvehdtsmotno);
   $getdupbatno=array_intersect($_REQUEST['battery_no'],$eachvehdtsbatno);
   $getdupchargno=array_intersect($_REQUEST['chrargerno'],$eachvehdtschargno);
   
   $commonerr=0;
   $extchsnodts='';$extmotnodts='';$extbatnodts='';$extchargnodts='';$errmsg="";
   if(count($getdupchsno)>0){
   $commonerr=1;
    foreach($getdupchsno as $key=>$value)
	  $extchsnodts.=$value.',';
	  $trmchsno=substr($extchsnodts,0,-1);
	  $errmsg.="Chase No:".$trmchsno." Already Exists <br>";
	  }
	  
	if(count($getdupmotno)>0){
	$commonerr=1;
    foreach($getdupmotno as $key=>$value)
	  $extmotnodts.=$value.',';
	  $trmmotno=substr($extmotnodts,0,-1);
	  $errmsg.="Motor No:".$trmmotno." Already Exists <br>";
	  }
	  
	 if(count($getdupbatno)>0){
	 $commonerr=1;
    foreach($getdupbatno as $key=>$value)
	  $extbatnodts.=$value.',';
	  $trmbatno=substr($extbatnodts,0,-1);
	  $errmsg.="Battery No:".$trmbatno." Already Exists <br>";
	  }
	  
	  if(count($getdupchargno)>0){
	  $commonerr=1;
    foreach($getdupchargno as $key=>$value)
	  $extchargnodts.=$value.',';
	  $trmchargno=substr($extchargnodts,0,-1);
	  $errmsg.="Charger No:".$trmchsno." Already Exists <br>";
	  }
   
   if($commonerr==0){
   $exp_itemid=explode('^',$_REQUEST['item_vehicle_id']);
   $get_gstdts=$obj_db->fetchRow("select * from ".TABLE_ITEM_VEHICLE_DETAILS."  where item_vehicle_id='".$exp_itemid[0]."'");
    $insr_vehrecv=$obj_db->get_qresult(INSERT_KEYWORD."   ".TABLE_VEHICLE_ITEM_ENTRY_DTS." set
				 item_vehicle_id='".$obj_db->real_escape_string($exp_itemid[0])."',
				 vehicle_item_type_id='1',
				 color_id='".$_REQUEST['color_id']."',
				 no_items='".$obj_db->real_escape_string($_REQUEST['no_items'])."',
				 purchase_price='".$obj_db->real_escape_string($_REQUEST['purchase_price'])."',
				 sgst='".$obj_db->real_escape_string($_REQUEST['tsgst'])."',
				  cgst='".$obj_db->real_escape_string($_REQUEST['tcgst'])."',
				 invoice_no='".$obj_db->real_escape_string($_REQUEST['invoice_no'])."',
				  invoice_date='".$obj_db->real_escape_string($_REQUEST['invoice_date'])."',
				  enter_date='".$obj_db->real_escape_string(date('Y-m-d H:i:s'))."',
			   branch_id='".$obj_db->real_escape_string($_REQUEST['branch_id'])."', 
			   enter_by='".$_SESSION['user_id']."'"); 
			   $id=$obj_db->insert_id();
			//  $get_vehdts=$obj_db->fetchRow("select * from ".TABLE_VEHICLE_ITEMS." where item_vehicle_id='".$exp_itemid[0]."'");
			  for($i=0;$i<count($_REQUEST['chase_no']);$i++){
			  if($_REQUEST['prc'][$i]>0){
			   $vprc=$_REQUEST['prc'][$i];
			   $cgstper=(($_REQUEST['prc'][$i]*$get_gstdts['cgst'])/100);
			   $sgstper=(($_REQUEST['prc'][$i]*$get_gstdts['sgst'])/100);
			   $tprc=$vprc+$cgstper+$sgstper;
			   $sql.=INSERT_KEYWORD."   ".TABLE_EACHVEHICLE_ITEM_ENTRY_DTS." (veicle_item_receive_id,vehicle_item_type_id,item_vehicle_id,color_id,branch_id, chase_no,motor_no,battery_no,charger_no,chase_warranty,motor_warranty,battery_warranty,charger_warranty,price,sgst,cgst,tot_price,no_services) VALUES
			                             ('".$obj_db->real_escape_string($id)."',
										   '1',
										   '".$obj_db->real_escape_string($exp_itemid[0])."',
										   '".$obj_db->real_escape_string($_REQUEST['color_id'])."',
										   '".$obj_db->real_escape_string($_REQUEST['branch_id'])."',
										   '".$obj_db->real_escape_string($_REQUEST['chase_no'][$i])."',
										   '".$obj_db->real_escape_string($_REQUEST['motor_no'][$i])."',
										   '".$obj_db->real_escape_string($_REQUEST['battery_no'][$i])."',
										   '".$obj_db->real_escape_string($_REQUEST['chrargerno'][$i])."',
										    '".$obj_db->real_escape_string($_REQUEST['chaseno_monwarnty'])."',
										   '".$obj_db->real_escape_string($_REQUEST['mot_monwarnty'])."',
										    '".$obj_db->real_escape_string($_REQUEST['bat_monwarnty'])."',
										   '".$obj_db->real_escape_string($_REQUEST['charg_monwarnty'])."',
										   '".$obj_db->real_escape_string($vprc)."',
										   '".$obj_db->real_escape_string($get_gstdts['sgst'])."',
										   '".$obj_db->real_escape_string($get_gstdts['cgst'])."',
										   '".$obj_db->real_escape_string($tprc)."',
										   '".$obj_db->real_escape_string($get_gstdts['no_services'])."');";
			  
			   }
			   }
			   echo $sql;
			     $res=$obj_db->runMultipleQueries($sql); 
   }else{$errsts=1;
   foreach($chk_chasenos as $key=>$value)
      $existchasenos.=$value['chase_no'].',';
	  $trmexistchaseno=substr($existchasenos,0,-1);
   }
   
   echo $errsts.'^'.$errmsg;
   
   }
?>