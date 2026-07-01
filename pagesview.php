<?php 
/*$x=array(
    0 => 'foo',
    1 => 'bar',
    2 => 'foobar'
);

$y='foobar'

switch($y) {
    foreach($x as $i) {
        case $i:
            print 'Variable $y tripped switch: '.$i.'<br>';
            break;
    }
}*/

$pgview=$_SESSION['user_pages'];

$pageviewdts=json_decode($pgview,true);
$i=0;$pagar_view=array();$pagar=array();
foreach($pageviewdts['pages'] as $k=>$v){
 $pagar[$i]=$v;
 $i++;
 }
  $sel_pagky=array_search($_REQUEST['p'], array_column($pagar,'p'));
 $pageviewdts=array('pages'=>$pagar,'defalut_page'=>$pageviewdts['defalut_page']);
/*echo '<pre>';print_r($pagar);echo '</pre>';
$pageviewdt='{
"pages":{
"0":{
"p":"year",
"page":"year_dts.php"
	},
"1":{
"p":"organization",
"page":"organizationdts.php"
	},
"2":{
"p":"branch",
"page":"branchdt.php"
	},
"3":{
"p":"hostel_name",
"page":"hostel_nameview.php"
	},
	"4":{
"p":"user_rolldt",
"page":"user_rolldts.php"
	}
	   },
"defalut_page":"year_dts.php"
}';


 $pageviewdts=json_decode($pagar_view,true);*/

 ?>