<script>
 app.controller('monthly_collection', function($scope, $http) {		
 
		  $scope.branch_id='<?php echo $_SESSION['branch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })   


      $scope.getdate = function(){  
		 $scope.loading=true;
		  $(".monthlycolc").hide();
            $http.get("includes/api/finance_details.php?action=monthly_collection&branch_id="+$scope.branch_id+"&yearid="+$scope.yearid+"&month="+$scope.month)  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
                $scope.month_collection = data['Feedata'];
			 	$scope.exceltitle =data['extitle'];
				$scope.tot_field = data['Totfields'];  
				 $(".monthlycolc").show();
				$scope.loading = false;
           })   
      }
 });
 app.controller('monthly_hostelexpense', function($scope, $http) {		
 
		  $scope.branch_id='<?php echo $_SESSION['branch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })   


      $scope.getdate = function(){  
		 $scope.loading=true;
		  $(".monthlycolc").hide();
            $http.get("includes/api/finance_details.php?action=monthly_headwiseexpense&branch_id="+$scope.branch_id+"&yearid="+$scope.yearid+"&month="+$scope.month)  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
                $scope.headhost_exp = data['hostexpdts'];
			 	$scope.exceltitle =data['extitle'];
				$scope.tot_field = data['Totfields'];  
				 $(".monthlycolc").show();
				$scope.loading = false;
           })   
      }
 });
 
  app.controller('opcashierwise_report', function($scope, $http) {	

var today = new Date(); var dd = today.getDate();  var mm = today.getMonth()+1;  var yyyy = today.getFullYear();
if(dd<10) 
{
    dd='0'+dd;
} 

if(mm<10) 
{
    mm='0'+mm;
} 
  today = dd+'-'+mm+'-'+yyyy;
  $scope.dat_range=today+' TO '+today;
         <?php /*?> $scope.line_id='<?php echo $_SESSION['angline_id'];?>';
            $http.get("includes/api/finance_details.php?action=line_dts")  
           .success(function(data){
                $scope.line_info = data;  
           })  
		<?php */?>   
		
		 $http.get('includes/api/customer_details.php?action=linedls').success(function(data) { 
 		$scope.linelist=data['myarray'];
		 $scope.search_line_id =$scope.linelist[0];
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_reprot")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
				 $scope.tdat=data['tdat'];
			 	$scope.exceltitle =data['extitle'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;

				var totptypsum=0;
                   angular.forEach($scope.payment_types, function(v, k) {
					totptypsum +=v['patypamt'];
				 });
				
				 $scope.totptypsums=totptypsum;
  				 
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_reprot&line_id="+$scope.search_line_id.lineid+'&city_id='+$scope.search_city_id+"&dat_range="+$("#dateRange").val())  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;

				var totptypsum=0;
                   angular.forEach($scope.payment_types, function(v, k) {
					totptypsum +=v['patypamt'];
				 });
				
				 $scope.totptypsums=totptypsum;
           })   
      }
	  
	    $scope.get_confirmdel = function(id){ 
		  $("#hid_recid").val(id);
		  $("#hid_recno").val($("#recname_"+id).html());
	$('.glass').fadeIn();
	   $(".confirmdiv").show();
            $(".confirmboxmsg").html("Are you sure wants to delete "+$("#recname_"+id).html()+"...!");
		}
 });
 
 app.controller("feedueapi", function($scope, $http){  
 $http.get('includes/api/customer_details.php?action=linedls').success(function(data) {
		console.log(data);
		$scope.linelist=data['myarray'];
		 $scope.search_line_id =$scope.linelist[0];
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})
 
         $scope.dat='<?php echo $_SESSION['dt'];?>';
         $scope.loadlineid='0';
	  $scope.chk_singlestd = function(id){
	    if($("#sinlestd_"+id).is(':checked')){
	  $("#stdid_"+id).addClass('actstdid');
	  $("#stdname_"+id).addClass('actstdname');
	  $("#dueamt_"+id).addClass('actdamt');
	  $("#mobno_"+id).addClass('actmob');
	  
	  $("#stdid_"+id).attr('disabled',false);
	  $("#stdname_"+id).attr('disabled',false);
	  $("#dueamt_"+id).attr('disabled',false);
	  $("#mobno_"+id).attr('disabled',false);
	 }
	 else{
	    
	   $("#stdid_"+id).removeClass('actstdid');
	  $("#stdname_"+id).removeClass('actstdname');
	  $("#dueamt_"+id).removeClass('actdamt');
	  $("#mobno_"+id).removeClass('actmob');
	  
	  $("#stdid_"+id).attr('disabled',true);
	  $("#stdname_"+id).attr('disabled',true);
	  $("#dueamt_"+id).attr('disabled',true);
	  $("#mobno_"+id).attr('disabled',true);	 
	 }
	  }
	 
	   
	    $scope.get_lines = function(i){  
	     if($scope.term!=''){
		     $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  getfeedueData();
	   $('.glass').fadeIn();
   $scope.loading=true;
		 }  
      } 
	  
	   $scope.get_citydts = function(i){  
	     if($scope.term!=''){
		     $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  getfeedueData();
	   $('.glass').fadeIn();
   $scope.loading=true;
		 }  
      }  
	  
	  $scope.get_dailycollection = function(i){ 
	  $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  getfeedueData();
	   $('.glass').fadeIn();
   $scope.loading=true;
    
	}  
	 function getfeedueData() { 
     $http.get("includes/api/finance_details.php?action=cust_fee_due&lineid="+$scope.search_line_id.lineid+"&city_id="+$scope.search_city_id+"&ret_tots="+$scope.ret_tots+"&tot_count="+$scope.totalItems+"&dat_range="+$scope.dat_range+'&limit=' +$scope.pageSize)
        .success(function(data) { 
            $scope.totalItems = data.totalCount;
			$scope.fee_ids=data.fee_ids;
			$scope.ret_tots=data.ret_tots;
			if($scope.pageSize==0){
			$scope.theads=data['fetch_heads'];
			}
             if (data.totalCount >$scope.pageSize) {
			$scope.pageSize=$scope.pageSize+1000;
			$scope.sub_tot=data.sub_tot;
            angular.forEach(data.feedue, function(temp){
                $scope.fees.push(temp);
				$(".feeduecolc").show();
             });
			getfeedueData();

			}
			else{//alert($scope.sub_tot);
			$scope.loading=false;
			$('.glass').fadeOut();	
			$scope.sub_tot=$scope.sub_tot;
			  $scope.exceltitle = data.extitle; 
			}
        });
    }
	  
	});
 
 
	 app.controller("duelist_uptodate", function($scope, $http){  

var today = new Date(); var dd = today.getDate();  var mm = today.getMonth()+1;  var yyyy = today.getFullYear();
if(dd<10) 
{
    dd='0'+dd;
} 

if(mm<10) 
{
    mm='0'+mm;
} 
  today = dd+'-'+mm+'-'+yyyy;

 $http.get('includes/api/customer_details.php?action=linedls').success(function(data) {
		console.log(data);
		$scope.linelist=data['myarray'];
		 $scope.search_line_id =$scope.linelist[0];
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})
 //alert($scope.search_line_id.lineid+' '+$scope.search_city_id);
         $scope.dat=today;
		 // alert($scope.dat);
		 $scope.pageSize = 0;
		  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
		   getfeedueData(0,0);
	  $scope.chk_singlestd = function(id){
	    if($("#sinlestd_"+id).is(':checked')){
	  $("#stdid_"+id).addClass('actstdid');
	  $("#stdname_"+id).addClass('actstdname');
	  $("#dueamt_"+id).addClass('actdamt');
	  $("#mobno_"+id).addClass('actmob');
	  
	  $("#stdid_"+id).attr('disabled',false);
	  $("#stdname_"+id).attr('disabled',false);
	  $("#dueamt_"+id).attr('disabled',false);
	  $("#mobno_"+id).attr('disabled',false);
	 }
	 else{
	    
	   $("#stdid_"+id).removeClass('actstdid');
	  $("#stdname_"+id).removeClass('actstdname');
	  $("#dueamt_"+id).removeClass('actdamt');
	  $("#mobno_"+id).removeClass('actmob');
	  
	  $("#stdid_"+id).attr('disabled',true);
	  $("#stdname_"+id).attr('disabled',true);
	  $("#dueamt_"+id).attr('disabled',true);
	  $("#mobno_"+id).attr('disabled',true);	 
	 }
	  }
	 
	   
	    $scope.get_lines = function(i){  
		     $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	 
	  $scope.linid=$scope.search_line_id.lineid;
	  $scope.cityid=$scope.search_city_id;
	  getfeedueData($scope.linid,$scope.cityid);
	   $('.glass').fadeIn();
   $scope.loading=true;
      } 
	  
	   $scope.get_citydts = function(i){  
		     $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  $scope.linid=$scope.search_line_id.lineid;
	  $scope.cityid=$scope.search_city_id;
	  getfeedueData($scope.linid,$scope.cityid);
	   $('.glass').fadeIn();
   $scope.loading=true;
      }  
	  
	  $scope.get_datetodue = function(i){ 
	  $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  $scope.linid=$scope.search_line_id.lineid;
	  $scope.cityid=$scope.search_city_id;
	  getfeedueData($scope.linid,$scope.cityid);
	   $('.glass').fadeIn();
   $scope.loading=true;
    
	}  
	 function getfeedueData(l,c) {//alert(l+' '+c);
	 //alert($scope.search_line_id.lineid+' '+$scope.search_city_id);
     $http.get("includes/api/finance_details.php?action=cust_pendingupto_date&lineid="+l+"&city_id="+c+"&ret_tots="+$scope.ret_tots+"&tot_count="+$scope.totalItems+"&dat_range="+$scope.dat+'&limit=' +$scope.pageSize)
        .success(function(data) {console.log(data);//alert(data);
            $scope.totalItems = data.totalCount;
			$scope.fee_ids=data.fee_ids;
			$scope.ret_tots=data.ret_tots;
			if($scope.pageSize==0){
			$scope.theads=data['fetch_heads'];
			}
            if (data.totalCount >$scope.pageSize) {
			$scope.pageSize=$scope.pageSize+1000;
			$scope.sub_tot=data.sub_tot;
			$scope.tot_org=data.tot_org;
			$scope.tot_custamt=data.tot_custamt;
			$scope.tot_conces=data.tot_conces;
			$scope.tot_tpaid=data.tot_tpaid;
			$scope.tot_monweekdue=data.tot_monweekdue;
            angular.forEach(data.feedue, function(temp){
                $scope.fees.push(temp);
				$(".feeduecolc").show();
            });
			getfeedueData(l,c);
			}
			else{//alert($scope.sub_tot);
			$scope.loading=false;
			$('.glass').fadeOut();	
			$scope.sub_tot=$scope.sub_tot;
			$scope.tot_org=$scope.tot_org;
			$scope.tot_custamt=$scope.tot_custamt;
			$scope.tot_conces=$scope.tot_conces;
			$scope.tot_tpaid=$scope.tot_tpaid;
			$scope.tot_monweekdue=$scope.tot_monweekdue; 
			  $scope.exceltitle = data.extitle; 
			}
        });
    }
	  
	});
	
	app.controller('datewise_usrwise_paywisecolc', function($scope, $http) {		
          $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   $scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+$scope.branch_id)  
           .success(function(data){
                $scope.branch_courses = data;  
				
           })  
		   $scope.getbranchid = function(i){  
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+i)  
           .success(function(data){
                $scope.branch_courses = data;  
           }) 
		   } 

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=datewise_userwise_paywisecollection&branch_id="+$scope.branch_id+"&dat_range="+$("#dateRange").val())  
           .success(function(data){
			  // $scope.dt=data.split('^');
			 $scope.fee_names = data['fee_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
			
				$scope.all_grdtot = data['all_grdtot'];
				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;alert($scope.dat_range);
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=datewise_userwise_paywisecollection&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){
			  // $scope.dt=data.split('^');
			  $scope.fee_names = data['fee_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			
				$scope.all_grdtot = data['all_grdtot'];
				
			 	$scope.exceltitle =data['extitle'];
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
		   
</script>