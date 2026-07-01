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
 
  
 app.controller('paytype_reports', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
         $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+$scope.branch_id)  
           .success(function(data){
                $scope.branch_courses = data;  
				$scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
           })  
		   $scope.getbranchid = function(i){  
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+i)  
           .success(function(data){
                $scope.branch_courses = data;  
           }) 
		   } 

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=oppaytype_reprot")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['paytype_rws'];
                $scope.brnch_name = data['brnch_name'];
			 	$scope.exceltitle =data['extitle'];
				// $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=oppaytype_reprot&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['paytype_rws'];
                $scope.brnch_name = data['brnch_name'];
			 	$scope.exceltitle =data['extitle'];
				// $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
  app.controller('opcashierwise_report', function($scope,$location,$http,$window,$cookies,$filter) {	
	$scope.fee_id='0';	
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
         $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   $scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
            $http.get("includes/api/finance_details.php?action=get_branch_regcourses&branch_id="+$scope.branch_id)  
           .success(function(data){
                $scope.branch_courses = data;  
           })  
		   
		   $scope.getbranchid = function(i){  
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+i)  
           .success(function(data){
                $scope.branch_courses = data;  
           }) 
		   cashierwise_paydts($scope.orgfee_data,$scope.comrec_typ,$scope.branch_id,$scope.fee_id);
		   } 

		   $scope.get_fees = function(fid){  
 			$scope.branch_id='0';           
		   cashierwise_paydts($scope.orgfee_data,$scope.comrec_typ,$scope.branch_id,$scope.fee_id);
		   } 

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_reprot")  
           .success(function(data){console.log(data);
		      $scope.comrec_typ=0;
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.orgfee_data = data['dailyfees'];

				var festypes=[{fee_id:'0',fee_name:"All Fees"}];
                angular.forEach($scope.orgfee_data, function(v, k) {
				var ftypindx= festypes.findIndex(object => object.fee_id === v['fee_id']);
			    if(ftypindx==-1)
				festypes.push({fee_id:v['fee_id'],fee_name:v['fee_name']});
			});
			$scope.festypes=festypes;

				cashierwise_paydts(data['dailyfees'],$scope.comrec_typ,'0',$scope.fee_id);
				
				$scope.orgyrdts = data['year_dts'];
               // $scope.yramts = data['year_amts'];
			 	$scope.exceltitle =data['extitle'];
				$scope.grdtotal = data['grdtotal'];
				$scope.orgcancel_data = data['cancelfees'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  $scope.get_commonrectype=function(i){
	  $scope.comrec_typ=i;
	   $scope.branch_id='0';
	   $scope.fee_id='0';
	   cashierwise_paydts($scope.orgfee_data,$scope.comrec_typ,'0',$scope.fee_id);
	  }
	  
	  
	  function cashierwise_paydts(paydtrecs,i,brnid,fee_id){
	  //alert(paydtrecs+' '+i+' '+brnid);
				if(paydtrecs!=null){
				if(i>0){
				$scope.paydtrecs=paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.common_receipt ==i;
});
}			else
				$scope.paydtrecs=paydtrecs;

				if(fee_id>0)
				$scope.paydtrecs=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.fee_id ==fee_id;
});
				/*else
				$scope.paydtrecs=$scope.paydtrecss;*/
 				
				if(brnid>0)
				$scope.paydtrecs=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.branch_id ==brnid;
});
				/*else
				$scope.paydtrecs=$scope.paydtrecss;*/
				
				var yrdts=[];var yramts=[];var patyps=[];var stddts=[];var paytypsarr=[];var payamtar=[];var comrectitlesar=[];var comrecpaydtar=[]; 
				angular.forEach($scope.paydtrecs, function(value, key) {///alert(value['year']);
				var yridx=yrdts.findIndex(x=>x.year === value['year']);
				if(yridx<0)
				yrdts.push({year:value['year']});
				var ptypidx=paytypsarr.findIndex(x=>x.paytype === value['pay_name']);
				if(ptypidx<0)
				paytypsarr.push({paytype:value['pay_name'],payment_type:value['payment_type']});
				
				var comrecidx=comrectitlesar.findIndex(x=>x.common_receipt === value['common_receipt']);
				if(comrecidx<0)
				comrectitlesar.push({common_receipt:value['common_receipt'],common_receipt_title:value['common_receipt_title']});
});
$scope.yrdts=yrdts;
$scope.paytype=paytypsarr;
var gyramt=0;
angular.forEach(yrdts, function(value, key) {
			$scope.yramts=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.year ==value['year'];
});
var yramttot=0;
angular.forEach($scope.yramts, function(value, key) {
yramttot +=parseFloat(value['amt']);
});
gyramt +=parseFloat(yramttot);
yramts.push({yramt:yramttot});
});
yramts.push({yramt:gyramt});
$scope.yramtdts=yramts;


var gtptypamts=0;
angular.forEach(paytypsarr, function(value, key) {
			$scope.paytypamts=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.payment_type ==value['payment_type'];
});
var payamttot=0;
angular.forEach($scope.paytypamts, function(value, key) {
payamttot +=parseFloat(value['amt']);
});
gtptypamts +=parseFloat(payamttot);
payamtar.push({payamt:payamttot});
});
payamtar.push({payamt:gtptypamts});
$scope.payamtar=payamtar;



var comretots=0;
angular.forEach(comrectitlesar, function(value, key) {
			$scope.comrecpaydt=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.common_receipt ==value['common_receipt'];
});


var comrecpaytot=0;
angular.forEach($scope.comrecpaydt, function(value, key) {
comrecpaytot +=parseFloat(value['amt']);
});
comrecpaydtar.push({common_receipt:value['common_receipt'],common_receipt_title:value['common_receipt_title'],commrec_paydts:$scope.comrecpaydt,comrecpaytot:comrecpaytot});

});
$scope.loading=false;
$scope.comrecpaydtar=comrecpaydtar;

}
	  }
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_reprot&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){
 		   $scope.exceltitle =data['extitle'];
				$scope.grdtotal = data['grdtotal'];
				$scope.orgcancel_data = data['cancelfees'];
				$scope.cancel_data = data['cancelfees']; 
                $scope.orgfee_data=data['dailyfees'];
				console.log($scope.orgfee_data);
                $scope.branch_id='0';
              var festypes=[{fee_id:0,fee_name:"All Fees"}];
                angular.forEach($scope.orgfee_data, function(v, k) {
					//alert(v['fee_id']);
				var ftypindx= festypes.findIndex(object => object.fee_id == v['fee_id']);
			    if(ftypindx==-1)
				festypes.push({fee_id:v['fee_id'],fee_name:v['fee_name']});
			});
			$scope.festypes=festypes;

		   cashierwise_paydts(data['dailyfees'],$scope.comrec_typ,'0',$scope.fee_id);
		   })   
      }
 });





 app.controller('opcashierwise_longtermreport_obk', function($scope,$location,$http,$window,$cookies,$filter) {	
	$scope.fee_id='0';	
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
         $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   $scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
            $http.get("includes/api/finance_details.php?action=get_branch_regcourses&branch_id="+$scope.branch_id)  
           .success(function(data){
                $scope.branch_courses = data;  
           })  
		   
		   $scope.getbranchid = function(i){  
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+i)  
           .success(function(data){
                $scope.branch_courses = data;  
           }) 
		   cashierwise_paydts($scope.orgfee_data,$scope.comrec_typ,$scope.branch_id,$scope.fee_id);
		   } 

		   $scope.get_fees = function(fid){  
 			$scope.branch_id='0';           
		   cashierwise_paydts($scope.orgfee_data,$scope.comrec_typ,$scope.branch_id,$scope.fee_id);
		   } 

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_longtrmreprot")  
           .success(function(data){
		      $scope.comrec_typ=0;
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.orgfee_data = data['dailyfees'];

				var festypes=[{fee_id:'0',fee_name:"All Fees"}];
                angular.forEach($scope.orgfee_data, function(v, k) {
				var ftypindx= festypes.findIndex(object => object.fee_id === v['fee_id']);
			    if(ftypindx==-1)
				festypes.push({fee_id:v['fee_id'],fee_name:v['fee_name']});
			});
			$scope.festypes=festypes;

				cashierwise_paydts(data['dailyfees'],$scope.comrec_typ,'0',$scope.fee_id);
				
				$scope.orgyrdts = data['year_dts'];
               // $scope.yramts = data['year_amts'];
			 	$scope.exceltitle =data['extitle'];
				$scope.grdtotal = data['grdtotal'];
				$scope.orgcancel_data = data['cancelfees'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  $scope.get_commonrectype=function(i){
	  $scope.comrec_typ=i;
	   $scope.branch_id='0';
	   $scope.fee_id='0';
	   cashierwise_paydts($scope.orgfee_data,$scope.comrec_typ,'0',$scope.fee_id);
	  }
	  
	  
	  function cashierwise_paydts(paydtrecs,i,brnid,fee_id){
	  //alert(paydtrecs+' '+i+' '+brnid);
				if(paydtrecs!=null){
				if(i>0){
				$scope.paydtrecs=paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.common_receipt ==i;
});
}			else
				$scope.paydtrecs=paydtrecs;

				if(fee_id>0)
				$scope.paydtrecs=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.fee_id ==fee_id;
});
				/*else
				$scope.paydtrecs=$scope.paydtrecss;*/
 				
				if(brnid>0)
				$scope.paydtrecs=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.branch_id ==brnid;
});
				/*else
				$scope.paydtrecs=$scope.paydtrecss;*/
				
				var yrdts=[];var yramts=[];var patyps=[];var stddts=[];var paytypsarr=[];var payamtar=[];var comrectitlesar=[];var comrecpaydtar=[]; 
				angular.forEach($scope.paydtrecs, function(value, key) {///alert(value['year']);
				var yridx=yrdts.findIndex(x=>x.year === value['year']);
				if(yridx<0)
				yrdts.push({year:value['year']});
				var ptypidx=paytypsarr.findIndex(x=>x.paytype === value['pay_name']);
				if(ptypidx<0)
				paytypsarr.push({paytype:value['pay_name'],payment_type:value['payment_type']});
				
				var comrecidx=comrectitlesar.findIndex(x=>x.common_receipt === value['common_receipt']);
				if(comrecidx<0)
				comrectitlesar.push({common_receipt:value['common_receipt'],common_receipt_title:value['common_receipt_title']});
});
$scope.yrdts=yrdts;
$scope.paytype=paytypsarr;
var gyramt=0;
angular.forEach(yrdts, function(value, key) {
			$scope.yramts=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.year ==value['year'];
});
var yramttot=0;
angular.forEach($scope.yramts, function(value, key) {
yramttot +=parseFloat(value['amt']);
});
gyramt +=parseFloat(yramttot);
yramts.push({yramt:yramttot});
});
yramts.push({yramt:gyramt});
$scope.yramtdts=yramts;


var gtptypamts=0;
angular.forEach(paytypsarr, function(value, key) {
			$scope.paytypamts=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.payment_type ==value['payment_type'];
});
var payamttot=0;
angular.forEach($scope.paytypamts, function(value, key) {
payamttot +=parseFloat(value['amt']);
});
gtptypamts +=parseFloat(payamttot);
payamtar.push({payamt:payamttot});
});
payamtar.push({payamt:gtptypamts});
$scope.payamtar=payamtar;



var comretots=0;
angular.forEach(comrectitlesar, function(value, key) {
			$scope.comrecpaydt=$scope.paydtrecs.filter(function(item) {//alert(item.user_status);
  return item.common_receipt ==value['common_receipt'];
});


var comrecpaytot=0;
angular.forEach($scope.comrecpaydt, function(value, key) {
comrecpaytot +=parseFloat(value['amt']);
});
comrecpaydtar.push({common_receipt:value['common_receipt'],common_receipt_title:value['common_receipt_title'],commrec_paydts:$scope.comrecpaydt,comrecpaytot:comrecpaytot});

});
$scope.loading=false;
$scope.comrecpaydtar=comrecpaydtar;

}
	  }
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_longtrmreprot&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){
			alert(data);
		   $scope.exceltitle =data['extitle'];
				$scope.grdtotal = data['grdtotal'];
				$scope.orgcancel_data = data['cancelfees'];
				$scope.cancel_data = data['cancelfees']; 
                $scope.orgfee_data=data['dailyfees'];
				console.log($scope.orgfee_data);
                $scope.branch_id='0';
              var festypes=[{fee_id:0,fee_name:"All Fees"}];
                angular.forEach($scope.orgfee_data, function(v, k) {
					//alert(v['fee_id']);
				var ftypindx= festypes.findIndex(object => object.fee_id == v['fee_id']);
			    if(ftypindx==-1)
				festypes.push({fee_id:v['fee_id'],fee_name:v['fee_name']});
			});
			$scope.festypes=festypes;

		   cashierwise_paydts(data['dailyfees'],$scope.comrec_typ,'0',$scope.fee_id);
		   })   
      }
 });
 
 app.controller('opcashierwise_longtermreport_obk', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
         $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   $scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
            $http.get("includes/api/finance_details.php?action=get_branch_neetcourses&branch_id="+$scope.branch_id)  
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
            $http.get("includes/api/finance_details.php?action=opcashierwise_longtrmreprot")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
				$scope.yrdts = data['year_dts'];
                $scope.yramts = data['year_amts'];
			 	$scope.exceltitle =data['extitle'];
				$scope.grdtotal = data['grdtotal'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_longtrmreprot&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
				 $scope.yrdts = data['year_dts'];
                $scope.yramts = data['year_amts'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
 
 app.controller('monthly_daywisereport', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
         $scope.branch_id='<?php echo $_SESSION['branch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=monthly_daywisereport")  
           .success(function(data){
			  // $scope.dt=data.split('^');
			  $scope.fee_data = data['mon_daycoldata'];
				 $scope.totsecondyrcolc = data['scndyrtotamt'];
                $scope.totsecondyrcolc = data['firstyrtotamt'];
				$scope.grdtot = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=monthly_daywisereport&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range)  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
                $scope.fee_data = data['mon_daycoldata'];
				 $scope.totsecondyrcolc = data['scndyrtotamt'];
                $scope.totfirstyrcolc = data['firstyrtotamt'];
				$scope.grdtot = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
 
  app.controller('opcashierwise_totreport', function($scope, $http) {	
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
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
            $http.get("includes/api/finance_details.php?action=opcashierwise_totreprot")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
				$scope.yramts = data['year_amts'];
                $scope.tot_yramt = data['tot_yramt'];
			 	$scope.exceltitle =data['extitle'];
				$scope.grdtotal = data['grdtotal'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_totreprot&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
				 $scope.yramts = data['year_amts'];
                $scope.tot_yramt = data['tot_yramt'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });

app.controller('daily_totcolcamt', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
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
            $http.get("includes/api/finance_details.php?action=daily_totcolcamts")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			 $scope.payment_types = data['pay_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
				
				$scope.oth_amts = data['oth_amts'];
				$scope.othtotal = data['othtotal'];
				
				$scope.longterm_data = data['longterm_data'];
				$scope.longtrmtotal = data['longtrmtotal'];
				
				$scope.all_grdtot = data['all_grdtot'];
				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=daily_totcolcamts&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){//alert(data);
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['pay_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
				
				$scope.oth_amts = data['oth_amts'];
				$scope.othtotal = data['othtotal'];
				
				$scope.longterm_data = data['longterm_data'];
				$scope.longtrmtotal = data['longtrmtotal'];
				
				$scope.all_grdtot = data['all_grdtot'];
				
			 	$scope.exceltitle =data['extitle'];
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
 
 
 
 
 app.controller('daily_totcolcamts', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
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
            $http.get("includes/api/finance_details.php?action=daily_totcolcamts")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			 $scope.payment_types = data['pay_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
				
				$scope.oth_amts = data['oth_amts'];
				$scope.othtotal = data['othtotal'];
				
				$scope.longterm_data = data['longterm_data'];
				$scope.longtrmtotal = data['longtrmtotal'];
				
				$scope.all_grdtot = data['all_grdtot'];
				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=daily_totcolcamts&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){//alert(data);
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['pay_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
				
				$scope.oth_amts = data['oth_amts'];
				$scope.othtotal = data['othtotal'];
				
				$scope.longterm_data = data['longterm_data'];
				$scope.longtrmtotal = data['longtrmtotal'];
				
				$scope.all_grdtot = data['all_grdtot'];
				
			 	$scope.exceltitle =data['extitle'];
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
 
 
 app.controller('monthly_totcolcamts', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
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
            $http.get("includes/api/monthly_overall_colc.php?action=daily_totcolcamts")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			 $scope.payment_types = data['pay_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
				
				$scope.oth_amts = data['oth_amts'];
				$scope.othtotal = data['othtotal'];
				
				$scope.longterm_data = data['longterm_data'];
				$scope.longtrmtotal = data['longtrmtotal'];
				
				$scope.all_grdtot = data['all_grdtot'];
				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/monthly_overall_colc.php?action=daily_totcolcamts&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){//alert(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['pay_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
				
				$scope.oth_amts = data['oth_amts'];
				$scope.othtotal = data['othtotal'];
				
				$scope.longterm_data = data['longterm_data'];
				$scope.longtrmtotal = data['longtrmtotal'];
				
				$scope.all_grdtot = data['all_grdtot'];
				
			 	$scope.exceltitle =data['extitle'];
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
 
 app.controller('datewise_usrwise_feewisecolc', function($scope, $http) {		
   $("#userwisefeewise_collection").show();
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
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
            $http.get("includes/api/finance_details.php?action=datewise_userwise_feewisecollection")  
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
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=datewise_userwise_feewisecollection&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){//alert(data);
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

app.controller('datewise_usrwise_paywisecolc', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
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
            $http.get("includes/api/finance_details.php?action=datewise_userwise_paywisecollection&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
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
		 $scope.loading=true;
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

app.controller('brnwise_academic_monrep', function($scope, $http) {	 
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
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
            $http.get("includes/api/finance_details.php?action=brnwise_academic_monreport")  
           .success(function(data){ 
			  // $scope.dt=data.split('^');
			$scope.brn_names = data['brn_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
			
				$scope.all_grdtot = data['all_grdtot'];
				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=brnwise_academic_monreport&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){// alert(data);
			  // $scope.dt=data.split('^');
			  $scope.brn_names = data['brn_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			
				$scope.all_grdtot = data['all_grdtot'];
				
			 	$scope.exceltitle =data['extitle'];
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
 //fee_duesms_coursewise
 app.controller("feeduesmsapi", function($scope, $http){
      
          $http.get("includes/api/finance_details.php?action=get_branch_sections_terms")  
           .success(function(data){console.log(data);
 				$scope.branchiddefault='<?php echo $_SESSION['branch_id'];?>';
                $scope.branchlist=data['branchlist'];
                  $scope.secdts = data['seclist']; 
				$scope.termlist=data['termdts'];
           })  
 
     
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
	 
	  $scope.loadBranch = function(i){  
	    $(".feeduecolc").hide();
	  $scope.loading=true;
	   $('.glass').fadeIn();	
           $http.get("includes/api/finance_details.php?action=get_branch_course_terms&branch_id="+i)  
           .success(function(data){ 
		   		console.log(data); 
              //  $scope.branchlist=data['branchlist'];
                $scope.branchcourselist = data['courselst']; 
				$scope.termlist=data['termdts']; 
				$scope.loading = false; 
				$('.glass').fadeOut();
				$(".feeduecolc").show();
           })  
      }  
	   
	  
	  
	$scope.loadcourses = function(i){ 
		 $scope.coursesec=$scope.branchlist[$scope.branchiddefault]['course'][i]['secs'];
		 
		  $http.get("includes/api/finance_details.php?action=std_fee_duesms&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&course_id="+$scope.course_id)  
           .success(function(data){ console.log(data);
                $scope.fees = data;
			   $scope.loading = false; 
			   $('.glass').fadeOut();	
			   $(".feeduecolc").show(); 
           })  
       }  
	  
	  $scope.loadsmsTerms = function(i){  
	   $('.glass').fadeIn();	
	  $scope.loading=true;
	  $(".feeduecolc").hide();
           $http.get("includes/api/finance_details.php?action=std_fee_duesms&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&course_id="+$scope.course_id)  
           .success(function(data){ 
		   		console.log(data); 
               $scope.fees = data;
			   $scope.loading = false; 
			   $('.glass').fadeOut();	
			   $(".feeduecolc").show(); 
           })  
      } 
	
 
 
 });
 //fee_duesms_coursewise
 
 
 
 app.controller("feedueapi", function($scope, $http){  
           $http.get("includes/api/finance_details.php?action=get_branch_sections_terms")  
           .success(function(data){console.log(data);
 				$scope.branchiddefault='<?php echo $_SESSION['branch_id'];?>';
                $scope.branchlist=data['branchlist'];
                  $scope.secdts = data['seclist']; 
				$scope.termlist=data['termdts'];
           })  
 
     
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
	 
	  $scope.loadBranch = function(i){  
	    $(".feeduecolc").hide();
	  $scope.loading=true;
	   $('.glass').fadeIn();	
           $http.get("includes/api/finance_details.php?action=get_branch_sections_terms&branch_id="+i)  
           .success(function(data){ 
              //  $scope.branchlist=data['branchlist'];
                $scope.branchseclist = data['seclist']; 
				$scope.termlist=data['termdts']; 
				$scope.loading = false; 
				$('.glass').fadeOut();
				$(".feeduecolc").show();
           })  
      }  
	   
	    $scope.loadcourses = function(i){ 
		 $scope.coursesec=$scope.branchlist[$scope.branchiddefault]['course'][i]['secs'];
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
	  
	   $scope.loadSections = function(i){  
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
	  
	  
	  
	  $scope.loadTerms = function(i){ 
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
      $http.get("includes/api/finance_details.php?action=std_fee_due&branchid="+$scope.branchiddefault+"&ret_tots="+$scope.ret_tots+"&tot_count="+$scope.totalItems+"&fee_ids="+$scope.fee_ids+"&terms="+$scope.term+"&course_id="+$scope.course_id+"&sec_id="+$scope.section+'&limit=' +$scope.pageSize)
        .success(function(data) {console.log(data);
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
	  
	  
	  
	   $scope.loadsmsSections = function(i){  
	     if($scope.term!=''){
		 $(".feeduecolc").hide();
		  $('.glass').fadeIn();	
		 $scope.loading=true;
           $http.get("includes/api/finance_details.php?action=std_fee_duesms&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&sec_id="+$scope.section)  
           .success(function(data){ 
                $scope.fees = data;
				$scope.loading = false; 
				$('.glass').fadeOut();
				$(".feeduecolc").show();
           })
		   }  
      }  
	  
	  $scope.loadsmsTerms = function(i){  
	   $('.glass').fadeIn();	
	  $scope.loading=true;
	  $(".feeduecolc").hide();
           $http.get("includes/api/finance_details.php?action=std_fee_duesms&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&sec_id="+$scope.section)  
           .success(function(data){ 
               $scope.fees = data;
			   $scope.loading = false; 
			   $('.glass').fadeOut();	
			   $(".feeduecolc").show(); 
           })  
      } 
	
 
 });
 
 
  app.controller("management_dashboard", function($scope, $http){  
           $http.get("includes/api/finance_details.php?action=get_managent_dashboard")  
           .success(function(data){ 
		   		console.log(data); 
				$scope.branchiddefault='<?php echo $_SESSION['branch_id'];?>';
                $scope.day_collc=data['day_collection'];
				 $scope.day_expense=data['day_expense'];
                $scope.branchcolcdts = data['branch_collection']; 
				$scope.totstddts=data['tot_stdstdrength'];
				$scope.secattdance=data['stdattdance'];
           }) 
		   });
		   
	 app.controller("incomedts", function($scope, $http){  
	      $('.glass').fadeIn();	
	  $scope.loading=true;
           $http.get("includes/api/finance_details.php?action=income_details")  
           .success(function(data){ 
		   		console.log(data); 
                $scope.incomedts=data;
				$('.glass').fadeOut();	
	            $scope.loading=false;
           }) 
		   
		   
		  $scope.get_branch_ids = function(i){  
		  $(".courseincome").hide();
		   $('.glass').fadeIn();	
		 $scope.loading=true;
           $http.get("includes/api/finance_details.php?action=coursewise_income&branch_id="+i)  
           .success(function(data){ 
		   		console.log(data); 
                $scope.coursewise_income = data;
				$(".courseincome").show();
				$scope.loading = false; 
				$('.glass').fadeOut();
           }) 
      }
		   });
		   
		   
		 app.controller("stdconcession_dts", function($scope, $http){  
	      $('.glass').fadeIn();	
	  $scope.loading=true;
           $http.get("includes/api/finance_details.php?action=get_conces_courses")  
           .success(function(data){ 
		   		console.log(data); 
				$scope.branchiddefault='<?php echo $_SESSION['branch_id'];?>';
                $scope.branchdts=data['branch_dts'];
				$scope.coursedts=data['course_dts'];
				$('.glass').fadeOut();	
	            $scope.loading=false;
           }) 
		   
		   
		  $scope.loadBranch = function(i){  
		  $(".stdconce").hide();
		   $('.glass').fadeIn();	
		 $scope.loading=true;
           $http.get("includes/api/finance_details.php?action=get_conces_courses&branch_id="+i)  
           .success(function(data){ 
		   		console.log(data); 
                $scope.coursedts=data['course_dts'];
				$(".stdconce").show();
				$scope.loading = false; 
				$('.glass').fadeOut();
           }) 
      }
	  
	   $scope.get_course = function(i){  
		  $(".stdconce").hide();
		   $('.glass').fadeIn();	
		 $scope.loading=true;
           $http.get("includes/api/finance_details.php?action=get_coursesconces_dts&branch_id="+$scope.branchiddefault+"&course_id="+$scope.course_id)  
           .success(function(data){ 
		   		console.log(data); 
                $scope.course_feedts=data['myarray'];
				$scope.course_tot=data['course_amttot'];
				$scope.coces_tot=data['conces_tot'];
				$scope.exceltitle=data['extitle'];
				$(".stdconce").show();
				$scope.loading = false; 
				$('.glass').fadeOut();
           }) 
      }
		   });

		   app.controller("income_projection", function($scope, $http){  	       
		   
		   $scope.get_branch_ids = function(i){  //alert(i);
			 $scope.coursestdstrens={};
			 $(".courseincome").hide();
			 $(".coursewisestdpaids").hide();
		  $scope.loading=true;
			$http.get("includes/api/finance_details.php?action=income_prejections&branch_id="+i)  
			.success(function(data){ 
			  $(".courseincome").show();
				$scope.brncoursewisestren=data['brncoursestrentharr'];
				$scope.fewisedts=data['feewise_dtstotsumarr'];
				$scope.coursewisestdstrenthar=data['coursewisestdstrenthar'];
				$scope.coursewisestd_fepaidtotarr=data['coursewisestd_fepaidtotarr'];
				$scope.coursewise_feedtsarr=data['coursewise_feedtsarr'];
				 $scope.coursewise_income = data;
				 $scope.loading = false; 
				 $('.glass').fadeOut();
			}) 
	   }
 
	   $scope.get_brncoruse_stdstrens = function(brn_id,course_id){  //alert(i);
		 $(".coursewisestdpaids").show();
		  $scope.loading=true;
		  var courseidx= $scope.brncoursewisestren.findIndex(object => object.course_id === course_id);
		  $scope.selcourse=$scope.brncoursewisestren[courseidx]['course_name'];
		  $scope.coursefeenamedts=$scope.coursewise_feedtsarr[brn_id][course_id];
		  var fenamekeys=Object.keys($scope.coursefeenamedts);
		  $scope.fenamekeyslen = 3+parseInt(fenamekeys.length);
 
				 $scope.coursestdstrens=$scope.coursewisestdstrenthar[brn_id][course_id];
 				 $scope.coursestdfeetotpaids=$scope.coursewisestd_fepaidtotarr[brn_id][course_id];
 
				 /*var coursewisestd_totfearr=[];
	 
		 angular.forEach($scope.coursestdfeetotpaid, function(value, key) {
			 coursewisestd_totfearr.push({totamt:value.toFixed(2)});
		 });
		 $scope.coursestdfeetotpaids=coursewisestd_totfearr;*/
 
				 $scope.loading = false; 
	   }
			});
</script>