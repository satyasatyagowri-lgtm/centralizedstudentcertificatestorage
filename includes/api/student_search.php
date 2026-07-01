<?php session_start();
include "../DbConfig.php";
if($_REQUEST['sbranch_id']!='')
$sbranch_id=$_REQUEST['sbranch_id'];
else $sbranch_id=$_SESSION['assign_branch_ids'];
if($_REQUEST['sline_id']>0 && $_REQUEST['scity_id']>0){$var=" and line_id='".$_REQUEST['sline_id']."' and city='".$_REQUEST['scity_id']."'";}
elseif($_REQUEST['sline_id']>0){$var=" and line_id='".$_REQUEST['sline_id']."' ";}
else{$var=" and line_id in(".$_SESSION['assign_line_ids'].") ";}
$data = json_decode(file_get_contents("php://input"));

$tex = $data->searchText;


if($tex!="")
{ $cnd=" and (customer_name like '%{$tex}%' or mobile_no like '%{$tex}%' or aadhar_no like '%{$tex}%') ";
}else{$cnd="";}
 // echo "SELECT customer_name,customer_id,mobile_no,city FROM ".TABLE_CUSTOMER_DTS."  where is_delete=0  $cnd  $var  limit 20 ";
      $std_data=$obj_db->qry("SELECT customer_name,customer_id,mobile_no,city FROM ".TABLE_CUSTOMER_DTS."  where is_delete=0  $cnd  $var   ");

echo json_encode($std_data);


?>