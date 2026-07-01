<?php  	if(!isset($_SESSION['user_id']) || $_SESSION['urlname']!="acccontbookgmd")
	    {
	     header("location: index.php");
         exit;
	    }
	if(isset($_REQUEST['year_id'])){
	$_SESSION['year_id']= $_REQUEST['year_id'];
	$_SESSION['is_promote']= $_REQUEST['is_promote'];
    }

	header("Expires: Mon, 26 Jun 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT"); 

	define('ACCESS_SUBFILES',true);	
     require_once("includes/DbConfig.php");
	require_once("includes/classes/general.php");
   include "menuview.php";
    include "pagesview.php";
	
  if($_REQUEST['action']=='edit')$disab='disabled="disabled"';
$usrdt=$obj_db->qry("SELECT * FROM ".TABLE_USER_DETAILS." WHERE user_name='".$_COOKIE['cokuname']."' and user_password='".decryptIt($_COOKIE['cokpword'])."' and user_status=1");
  if(!$usrdt)
  redirect_page('index.php?logout=logout');
   ?>


<!DOCTYPE html>
<html lang="en">
<?php include "includes/styles.php";?>
<style>
	body{
font-family:'Noto Sans Telugu','Nirmala UI','Gautami',sans-serif;
}
	.hide-for-pdf {
    display: none !important;
}
.form-control{
	border: solid #e7ebeeff !important;
}
</style>

<body class="main-content"   ng-app="schools">

	<!-- [ Pre-loader ] End -->
	<!-- [ navigation menu ] start -->
	<?php include "header.php";?>
	<!-- [ navigation menu ] end -->
	<!-- [ Header ] start -->
	<?php include "menu.php";?>
	<!-- [ Header ] end -->
	
	

<!-- [ Main Content ] start -->


<main class="content-wrap container-fluid mt-3">
     

  <div class="row">




    <div><?php  include "pages/pages.php";?></div>       

  </div>
  </main>
   <?php include "includes/jsfile.php";
     
    include "includes/searchscript.php";?>
   
   
</body>

</html>
