<?php session_start();
      include "DbConfig.php";
  if($_REQUEST['action']=='get_menu_sources'){
	$get_qty=$obj_db->qry("select * from ".TABLE_QUANTITY." where status=1 ");
 	$get_sourse=$obj_db->qry("select * from ".TABLE_SOURSE." where sourse_id not in(select sourse_id from  ".TABLE_MENUITEMS_PRICE." where id_brand='".$_REQUEST['id_brand']."' and id_menu='".$_REQUEST['id_menu']."') and status=1 ");
	$numvar="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');";
	    	foreach($get_sourse as $k=>$v){
			
 					$tmp.='<tr>
					
					<td>'.$v['name_sourse'].'
					<input type="hidden" name="source[]" value="'.$v['sourse_id'].'" />
					</td>';
   						//foreach($get_qty as $key=>$value){
						$arserch=array_search($value['iq_id'], array_column($data, 'iq_id'));
						if(is_numeric($arserch))
						   $itmprc=$data[$arserch]['price_menu'];
						 else $itmprc="";
  					$tmp.='<td><input type="text" name="prc['.$v['sourse_id'].']" '.$numvar.' autocomplete="off" class="form-control form-control-sm" value="'.$itmprc.'" >
					</td>';
					 // }  
 		 					$tmp.='</tr>';
			  
			} 
			  echo $tmp;
	}
	elseif($_REQUEST['action']=='get_session_posssion'){
	echo $_SESSION['user_id'];
	}
	elseif($_REQUEST['action']=='get_expsubcats'){
	$get_subcats=$obj_db->qry("select * from ".TABLE_EXP_SUBCATEGORY." where exp_catg_id='".$_REQUEST['exp_catg_id']."' ");
	$tmp.='<option value="">--Select--</option>';
	foreach($get_subcats as $k=>$v){
	  $tmp.='<option value="'.$v['exp_subcat_id'].'">'.$v['exp_subcategory'].'</option>';
	}
	echo $tmp;
	}
	elseif($_REQUEST['action']=='get_exptyps'){
	$get_subcats=$obj_db->qry("select * from ".TABLE_EXPENDITURE_TYPES." where exp_catg_id='".$_REQUEST['exp_catg_id']."' ");
	$tmp.='<option value="">--Select--</option>';
	foreach($get_subcats as $k=>$v){
	  $tmp.='<option value="'.$v['exp_type_id'].'">'.$v['exp_name'].'</option>';
	}
	echo $tmp;
	}

	elseif($_REQUEST['action']=='get_userliens'){
	$getusr_lindts=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where user_id='".$_REQUEST['touser_id']."' ");
	$getusrlines=$obj_db->qry("select * from ".TABLE_LINE_NAMES." where line_id in(0".$getusr_lindts[0]['assign_line_ids'].")");
	$tmp.='<option value="">--Select--</option>';
	foreach($getusrlines as $usrlink=>$usrlinev){
	  $tmp.='<option value="'.$usrlinev['line_id'].'">'.$usrlinev['line_name'].'</option>';
	}
	echo $tmp;
	}

	elseif($_REQUEST['action']=='get_linecitys_lstenterdate'){
	$line_lsttamtdt=$obj_db->fetchRow("SELECT date(taken_date) as takdt FROM ".TABLE_LINETAKEN_AMTS."  where line_id='".$_REQUEST['line_id']."' order by date(taken_date) desc limit 1");
		if($line_lsttamtdt['takdt']!='')
		 $takdt=date('d-m-Y',strtotime($line_lsttamtdt['takdt']));
		else $takdt="";
	   $get_liencitys=$obj_db->qry("SELECT * FROM ".TABLE_LINE_CITYS."  where line_id='".$_REQUEST['line_id']."'");
			$ctmp='<option value="">--Select--</option>';
			for($i=0;$i<count($get_liencitys);$i++) { 
					$ctmp.='<option value="'.$get_liencitys[$i]['city_id'].'">'.$get_liencitys[$i]['city_name'].'</option>';
					 }
 		$get_lineusrs=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$_REQUEST['line_id']."', assign_line_ids) > 0 and user_status=1 and user_type_id in(2)");
 		$lieusrdts='<option value="">--Select--</option>';
		foreach($get_lineusrs as $usrky=>$userv){
             $lieusrdts.='<option value="'.$userv['user_id'].'">'.$userv['full_name'].'</option>';
		}
        $numvar="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');";
 		$linusrmapdts='<table class="custom-table">
    <thead>
      <tr>
        <th>User</th>
        <th>Cash</th>
        <th>UPI</th>
      </tr>
    </thead>
    <tbody>';
		
	foreach($get_lineusrs as $usrky=>$userv){
             $linusrmapdts.='<tr>
        <td data-label="User"><input type="hidden" name="linusrs[]" value="'.$userv['user_id'].'"><b>'.$userv['full_name'].'</b></td>
        <td data-label="Cash">
          <input type="text" name="usrptyp_'.$userv['user_id'].'_1" autocomplete="off" inputmode="decimal" oninput="allowOnlyNumbersAndDecimal(this)"  class="input-sm numeric-decimal custpamttypwis">
        </td>
        <td data-label="UPI">
          <input type="text" name="usrptyp_'.$userv['user_id'].'_2" autocomplete="off" inputmode="decimal" oninput="allowOnlyNumbersAndDecimal(this)"  class="input-sm numeric-decimal custpamttypwis">
        </td>
      </tr>';
		}
$linusrmapdts.=' </tbody>
  </table>';
			 echo $ctmp.'^'.$takdt.'^'.$lieusrdts.'^'.$linusrmapdts;
	}
	elseif($_REQUEST['action']=='check_takdate_valid_ornot'){
		$weedtsdys=array(1=>"Sun",2=>"Mon",3=>"Tue",4=>"Wed",5=>"Thu",6=>"Fri",7=>"Sat",);
       $get_liencitys=$obj_db->qry("SELECT * FROM ".TABLE_LINE_CITYS."  where city_id='".$_REQUEST['city_id']."'");
	     $cityweekname=$weedtsdys[$get_liencitys[0]['weekd_id']];
 	   $totdaydt=date('D', strtotime(date('d-m-Y')));
	     $givendtday=date('D', strtotime($_REQUEST['taken_date']));
              
		 $convrtimto_givdtdts=strtotime($_REQUEST['taken_date']);
		 $convrtimto_todaydtdts=strtotime(date('Y-m-d'));

	   if($convrtimto_givdtdts>$convrtimto_todaydtdts){
		$weekstserr=1;
		$weekstsmsg="*Future Date Not allowd,Please Change Date...";
	   }
	   elseif($cityweekname!=$givendtday){
		$weekstserr=1;
		$weekstsmsg="*Selected City Week Day ".$cityweekname.",Please Change Date...";
	   }else{
		$weekstserr=0;
		$weekstsmsg="";
	   }
 	   $todaydate="";
	   if($totdaydt==$givendtday)
		 $todaydate=date("d-m-Y");
  echo $weekstserr.'^'.$weekstsmsg.'^'.$todaydate;

	}

	elseif($_REQUEST['action']=='getline_empusers'){
	
 		$get_lineusrs=$obj_db->qry("select * from ".TABLE_USER_DETAILS." a,".TABLE_EMPUSER_DETAILS." b where FIND_IN_SET('".$_REQUEST['line_id']."', assign_line_ids) > 0 and a.empuser_ids=b.emp_user_id and b.emp_user_id not in(select emp_user_id from ".TABLE_EMPUSER_ATTENDANCELIST." where line_id='".$_REQUEST['line_id']."' and str_to_date(attendance_date,'%Y-%m-%d')=str_to_date('".trim($_REQUEST['gdt'])."','%d-%m-%Y')) and user_status=1 and user_type_id!=1");
  	
		$empusrdts='';
		foreach($get_lineusrs as $usrky=>$userv){
             $empusrdts.='<div class="form-group col-sm-4 m-t-0" style="padding-left:60px;   ">		
										 
 											 <input type="checkbox"  class="form-check-input allcompos" style="color:#d1f2f5ad; !important" name="empuserid[]" id="empusrid_'.$userv['emp_user_id'].'"    value="'.$userv['emp_user_id'].'"   >
 											 <label class="form-check-label" for="empusrid_'.$userv['emp_user_id'].'"> <b  >'.$userv['emp_name'].'</b> </label>
 											 </div>';
			 
		}
		echo $empusrdts;
 	}
	elseif($_REQUEST['action']=='get_linecithdts'){
 		$linedts=$obj_db->qry("select * from ".TABLE_LINECHIT_DETAILS." where line_id='".$_REQUEST['line_id']."' and is_complete=0 and is_delete=0");
 		$linechitdts='<option value="">--Select--</option>';
		foreach($linedts as $linchitky=>$linchitv){
             $linechitdts.='<option value="'.$linchitv['line_chit_id'].'">'.$linchitv['line_chit_name'].' '.$linchitv['chit_amount'].'</option>';
		}

		$get_lineusrs=$obj_db->qry("select * from ".TABLE_USER_DETAILS." where FIND_IN_SET('".$_REQUEST['line_id']."', assign_line_ids) > 0 and user_status=1 and user_type_id!=1");
 		$lieusrdts='<option value="">--Select--</option>';
		foreach($get_lineusrs as $usrky=>$userv){
             $lieusrdts.='<option value="'.$userv['user_id'].'">'.$userv['full_name'].'</option>';
		}
			 echo $linechitdts.'^'.$lieusrdts;
	}

	elseif($_REQUEST['action']=='get_customerno'){
  		$check_customer_no=$obj_db->qry("SELECT a.*,b.line_name,b.city as linecity,c.weekd_id,c.city_name FROM ".TABLE_CUSTOMER_DTS." a,".TABLE_LINE_NAMES." b,".TABLE_LINE_CITYS." c  where b.line_id=a.line_id and b.line_id='".$_REQUEST['line_id']."' and a.customer_givid='".$_REQUEST['customer_no']."' and c.city_id='".$_REQUEST['city_id']."' and a.city_id=c.city_id and b.line_id=c.line_id  ");
 		$custdts=$obj_db->fetchRow("SELECT customer_givid FROM ".TABLE_CUSTOMER_DTS." WHERE customer_id='".(int)$_REQUEST['customer_id']."'");
		 $ercount=0;
 	   if(count($check_customer_no)>0 && $custdts['customer_givid']!=$_REQUEST['customer_no']){
			$ercount=1;
		   }
		 
				   echo "CustomerNo Exists to :".$check_customer_no[0]['customer_name'].'^'.$ercount;
	   }

elseif($_REQUEST['action']=='get_customeraadhar'){
  		$check_customer_no=$obj_db->qry("SELECT * FROM ".TABLE_CUSTOMER_DTS."   where aadhar_no='".$_REQUEST['aadhar_no']."'  ");
 		$custdts=$obj_db->fetchRow("SELECT aadhar_no FROM ".TABLE_CUSTOMER_DTS." WHERE customer_id='".(int)$_REQUEST['customer_id']."'");
		 $ercount=0;
 	   if(count($check_customer_no)>0 && $custdts['aadhar_no']!=$_REQUEST['aadhar_no']){
			$ercount=1;
		   }
		 
				   echo "Aadharno Exists to :".$check_customer_no[0]['customer_name'].'^'.$ercount;
	   }

	   elseif($_REQUEST['action']=='get_customernobkp'){
  		$check_customer_no=$obj_db->qry("SELECT a.*,b.line_name,b.city as linecity,c.weekd_id,c.city_name FROM ".TABLE_CUSTOMER_DTS." a,".TABLE_LINE_NAMES." b,".TABLE_LINE_CITYS." c  where b.line_id=a.line_id and b.line_id='".$_REQUEST['line_id']."' and a.customer_no='".$_REQUEST['customer_no']."' and c.city_id='".$_REQUEST['city_id']."' and a.city_id=c.city_id and b.line_id=c.line_id  ");
 		$custdts=$obj_db->fetchRow("SELECT customer_no FROM ".TABLE_CUSTOMER_DTS." WHERE customer_id='".(int)$_REQUEST['customer_id']."'");
		 $ercount=0;
 	   if(count($check_customer_no)>0 && $custdts['customer_no']!=$_REQUEST['customer_no']){
			$ercount=1;
		   }
		 
				   echo "CustomerNo Exists to :".$check_customer_no[0]['customer_name'].'^'.$ercount;
	   }
?>
