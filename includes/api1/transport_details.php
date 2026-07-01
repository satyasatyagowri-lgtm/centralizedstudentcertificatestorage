<?php session_start();
include "../DbConfig.php";
 
 if($_REQUEST['action']=="stop_dts"){
 $result =$obj_db->qry("select stop_id,stop_name,bus_fee from ".TABLE_BUS_STOPS."  where route_id='".$_REQUEST['route_id']."' ");
   echo json_encode($result); 
 }
?>