<script>
 app.controller('datewisepaywisecolc', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
         $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
 $scope.exp_type_id='0';
           $http.get("../includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   $scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
            $http.get("../includes/api/finance_details.php?action=get_branch_courses&branch_id="+$scope.branch_id)  
           .success(function(data){
                $scope.branch_courses = data;  
				
           })  
		   
		   $http.get("../includes/api/datewise_paymentwise_colc.php?action=get_banks")  
           .success(function(data){
                $scope.bankdts = data;  
				
           }) 
		   
		   $scope.getbranchid = function(i){  
            $http.get("../includes/api/finance_details.php?action=get_branch_courses&branch_id="+i)  
           .success(function(data){
                $scope.branch_courses = data;  
           }) 
		   }  

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("../includes/api/datewise_paymentwise_colc.php?action=datewise_paywisecollection")  
           .success(function(data){ console.log(data);
			  // $scope.dt=data.split('^');
			 $scope.paymt_names = data['paymt_names'];
                $scope.paytypeamts = data['paytypeamts'];
				$scope.grdtotal = data['gotot'];
				$scope.std_grdtot = data['gotot'];
			 	$scope.exceltitle =data['extitle'];
				
				$scope.upayfees =data['upayfees'];
				$scope.upaytypes = data['upaytypesss'];
				var stdpamts=[];
				 angular.forEach(data['payment_stdamts'], function(value, key) {
				 
				 var upayidx = $scope.upayfees.findIndex(object => object.upay_id === value['upay_id']);
			 	 var upaytypidx = $scope.upaytypes.findIndex(object => object.upay_type === value['upay_type']);
				 if (upayidx === -1) {
				  var upay=value['pay_name'];
				  var upaytyp="";
				  }
				 else {
				 var upay=$scope.upayfees[upayidx].upay_name;
			 	    var upaytyp=$scope.upaytypes[upaytypidx].upay_name;
				 }
				  stdpamts.push({pdts:value['pdts'],stdname:value['first_name']+'  '+value['last_name'],recno:value['recno'],user_name:value['user_name'],bank_name:value['bank_name'],pay_name:upay+'  '+upaytyp,transnum:value['transnum'],transdt:value['transdt'],pamt:value['pamt']});
				 });
				
				
				 var stringfy_stdpamts=JSON.stringify(stdpamts);
$scope.orgpayment_stdamts= JSON.parse(stringfy_stdpamts);
$scope.payment_stdamts= JSON.parse(stringfy_stdpamts);
				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("../includes/api/datewise_paymentwise_colc.php?action=datewise_paywisecollection&branch_id="+$scope.branch_id+"&bank_id="+$scope.exp_type_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){ 
			  // $scope.dt=data.split('^');
			 $scope.paymt_names = data['paymt_names'];
                $scope.paytypeamts = data['paytypeamts'];
				$scope.grdtotal = data['gotot'];
				$scope.std_grdtot = data['gotot'];
			 	$scope.exceltitle =data['extitle'];
				$scope.upayfees =data['upayfees'];
				$scope.upaytypes = data['upaytypesss'];
				var stdpamts=[];
				 angular.forEach(data['payment_stdamts'], function(value, key) {
				 
				 var upayidx = $scope.upayfees.findIndex(object => object.upay_id === value['upay_id']);
			 	 var upaytypidx = $scope.upaytypes.findIndex(object => object.upay_type === value['upay_type']);
				 if (upayidx === -1) {
				  var upay=value['pay_name'];
				  var upaytyp="";
				  }
				 else {
				 var upay=$scope.upayfees[upayidx].upay_name;
			 	    var upaytyp=$scope.upaytypes[upaytypidx].upay_name;
				 }
				  stdpamts.push({pdts:value['pdts'],stdname:value['first_name']+'  '+value['last_name'],recno:value['recno'],user_name:value['user_name'],bank_name:value['bank_name'],pay_name:upay+'  '+upaytyp,transnum:value['transnum'],transdt:value['transdt'],pamt:value['pamt']});
				 });
				
				
				 var stringfy_stdpamts=JSON.stringify(stdpamts);
$scope.orgpayment_stdamts= JSON.parse(stringfy_stdpamts);
$scope.payment_stdamts= JSON.parse(stringfy_stdpamts);
 				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
	  
	  $scope.get_paytypename=function(pay_type_id,pay_name){
	  $scope.exceltitle =pay_name;
	  var stot=0;
	  if(pay_type_id>0){
	  $scope.payment_stdamts= $scope.orgpayment_stdamts.filter(function(item) {//alert(item.user_status);
  return item.pay_type_id ===pay_type_id;
}); 
}else  $scope.payment_stdamts= $scope.orgpayment_stdamts;
angular.forEach($scope.payment_stdamts, function(value, key) {
stot =parseFloat(stot)+parseFloat(value['pamt']);
});
$scope.std_grdtot = stot;
	  }
	  
	  
	  /*$scope.getbank=function(exp_type_id){
	  $scope.get_dailycollection();
	  }*/
 });


</script>