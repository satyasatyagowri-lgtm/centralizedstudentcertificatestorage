<?php /*$menuviewdt='{
"main_menu":{
"0":{
"menu_name":"Dashboard",
"menu_id":1,
"logo_name":"icon-home",
"firstsub":{
"0":{
"firstmenu_id":3,
"firstmenu_name":"Year",
"controller_name":"year",
"page_name":"year_dts.php"
    },
"1":{
"firstmenu_id":4,
"firstmenu_name":"Organization",
"controller_name":"organization",
"page_name":"organizationdts.php"
    },
"2":{
"firstmenu_id":5,
"firstmenu_name":"Branch",
"controller_name":"branch",
"page_name":"branchdt.php"
    }
            }
	},
"1":{
"menu_name":"Hostel",
"menu_id":2,
"logo_name":"icon-layout",
"firstsub":{
"0":{
"firstmenu_id":6,
"firstmenu_name":"Hostel Name",
"controller_name":"hostel_name",
"page_name":"hostel_nameview.php.php"
    },
"1":{
"firstmenu_id":7,
"firstmenu_name":"Branch Map",
"controller_name":"hostel_branch_map",
"page_name":"hostel_branch_mapdts.php"
    }
            }
     },
"2":{
"menu_name":"Course",
"menu_id":8,
"logo_name":"icon-layers",
"firstsub":{
"0":{
"firstmenu_id":9,
"firstmenu_name":"Course",
"controller_name":"course",
"page_name":"course_dts.php"
    },
"1":{
"firstmenu_id":10,
"firstmenu_name":"Branch Map",
"controller_name":"course_branch_map",
"page_name":"course_branch_map.php"
    }
	       }
	 },
"3":{
"menu_name":"Finance",
"menu_id":11,
"logo_name":"icon-sliders",
"firstsub":{
"0":{
"firstmenu_id":12,
"firstmenu_name":"Fee Course Map",
"controller_name":"fee_course_map",
"page_name":"fee_course_maps.php"
    },
"1":{
"firstmenu_id":13,
"firstmenu_name":"Hostel Fee Map",
"controller_name":"hostel_fee_map",
"page_name":"hostel_fee_maps.php"
    }
	       }
	 },
"4":{
"menu_name":"Subjects",
"menu_id":15,
"logo_name":"icon-book",
"firstsub":{
"0":{
"firstmenu_id":16,
"firstmenu_name":"Subjects",
"controller_name":"subject",
"page_name":"subjects.php"
    },
"1":{
"firstmenu_id":17,
"firstmenu_name":"Course Map",
"controller_name":"sub_tocourse",
"page_name":"sub_tocourse_map.php"
    }
	       }
	},
"5":{
"menu_name":"Users",
"menu_id":18,
"logo_name":"icon-user",
"firstsub":{
"0":{
"firstmenu_id":19,
"firstmenu_name":"User",
"controller_name":"user_details",
"page_name":"user_detail.php"
    },
"1":{
"firstmenu_id":20,
"firstmenu_name":"Category",
"controller_name":"staff_category",
"page_name":"staff_categorys.php"
    },
"2":{
"firstmenu_id":21,
"firstmenu_name":"Sms Users",
"controller_name":"branch_sms_users",
"page_name":"branch_sms_users.php"
    },
"3":{
"firstmenu_id":21,
"firstmenu_name":"Roll Mapping",
"controller_name":"user_rolldt",
"page_name":"user_rolldts.php"
    }
	      }
	}
	}		   
}';*/




$menuviewdt=$_SESSION['user_menus'];
 $menudt=json_decode($menuviewdt,true);
  // echo '<pre>';print_r($menudt);echo '</pre>';
 ?>