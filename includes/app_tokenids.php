<?php session_start();
include "DbConfig.php";
if($_REQUEST['action']=='app_tokendetails_data'){
 
     $chktokdts=$obj_db->qry("select * from  ".TABLE_APP_USER_TOKENDETAILS." where token_id='".$obj_db->real_escape_string($_REQUEST['token_id'])."'");
  
$exp_usrid=explode('^',$_REQUEST['user_name']);
 
      $jsondecode_reqs=json_encode($_REQUEST);
            if(count($chktokdts)==0){
				$upd_logpermis=$obj_db->get_qresult(INSERT_KEYWORD."  ".TABLE_APP_USER_TOKENDETAILS." set user_name='".$obj_db->real_escape_string($exp_usrid[0])."',everytime_applog_id='".$exp_usrid[1]."',
					token_id='".$obj_db->real_escape_string($_REQUEST['token_id'])."',text_content='".$jsondecode_reqs."',create_date='".date('Y-m-d H:i:s')."'");
                    $insrid=$obj_db->insert_id();
                     $upd_stdtok_pirmaryid=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_STUDENTDETAILS." set app_token_primaryid='".$insrid."' where 
                    where parent_mobile='".$obj_db->real_escape_string($_REQUEST['user_name'])."'");
            }
            else {
            
$upd_logpermis=$obj_db->get_qresult(UPDATE_KEYWORD."  ".TABLE_APP_USER_TOKENDETAILS." set user_name='".$obj_db->real_escape_string($exp_usrid[0])."',everytime_applog_id='".$exp_usrid[1]."'  
            where token_id='".$obj_db->real_escape_string($_REQUEST['token_id'])."'"); 
}
}

elseif($_REQUEST['action']=='get_tokenstatus'){ 
     $_SESSION['is_tokgenerate']=1;
}	

elseif($_REQUEST['action']=='get_totapp_nofificatons'){ 
      $gettot_parentappcount=$obj_db->qry("select * from ".TABLE_PARENT_APPNOTIFICATION_COUNT." a,".TABLE_APP_USER_TOKENDETAILS." b where a.token_id=b.token_id and b.everytime_applog_id='".$_SESSION['everytime_applog_id']."'");
    echo count($gettot_parentappcount);
}	?> 