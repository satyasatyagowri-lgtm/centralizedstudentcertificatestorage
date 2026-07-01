<?php defined('ACCESS_SUBFILES') or die('Restricted access');
 		 $pg_scrchkey = array_search($_REQUEST['p'], array_column($pageviewdts['pages'], 'p'));
		if(is_numeric($pg_scrchkey)){
  		     include($pageviewdts['pages'][$pg_scrchkey]['page']); 
			 }
		else{ include($pageviewdts['defalut_page']); 
		}
 		
?>