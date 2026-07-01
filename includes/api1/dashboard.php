	<script>
 
 
app.controller('dashboard_dts', function($scope, $http) {
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
	
	$scope.gdt=today;
	$scope.usrcolcgdt=today;
	$scope.expgdt=today;
  $scope.actsts=1;
  $scope.actstsscnd=2;
 dashboardts($scope.gdt);
 overalincome();
  $scope.dashboardli_menu=function(i){
   
		$scope.actsts=i;
		$(".alliacttab").removeClass('active');
 		if(i==1){
		$("#todaycolc_exp").addClass('active');
		}
	   else if(i==2){
		 $("#todayusrpaywise_colc").addClass('active');
		//overalincome();
		}
 	else if(i==3){
	//overalincome();
		 $("#today_exp").addClass('active');
		}
 	
 }
 
 $scope.dashboardli_menuscnd=function(i){
   
		$scope.actstsscnd=i;
		$(".alliacttabscnd").removeClass('active');
 		if(i==1){
		$("#overcolc_tab").addClass('active');
		}
	   else if(i==2){
		$("#income_tab").addClass('active');
		overalincome();
		}
 	else if(i==3){
	overalincome();
		$("#fewisecolc-tab").addClass('active');
		}
 	
 }
 $scope.get_datewisedashboard=function(gdt){ 
  dtwise_dashboardts(gdt);
 }
 function overalincome(){ 
  $scope.loading5=true;
$http.get('includes/api/dashboarddts.php?action=overal_income').success(function(data) {$scope.loading5=false;
console.log(data);
		$scope.overalincom=data['incomedts'];
		$scope.fewiseoveralincom=data['fewiseincome'];
		var torgamt=0;var tconcamt=0;var tcomitamt=0;var tpaidamt=0;var tbal=0;
		angular.forEach(data['incomedts'], function(value, key) {
		torgamt += parseFloat(value['orgamt']);
		tconcamt += parseFloat(value['coness']);
		tcomitamt += parseFloat(value['commitment']);
		tpaidamt += parseFloat(value['tpaid']);
		tbal += parseFloat(value['tbal']);
		});
		$scope.torgamt=torgamt;
		$scope.tconcamt=tconcamt;
		$scope.tcomitamt=tcomitamt;
		$scope.tpaidamt=tpaidamt;
		$scope.tbal=tbal;
		
		var tfepaid=0;var tfebal=0;
		angular.forEach(data['fewiseincome'], function(value, key) {
 		tfepaid += parseFloat(value['tpaid']);
		tfebal += parseFloat(value['tbal']);
		});
		$scope.tfepaid=tfepaid;
		$scope.tfebal=tfebal;
 	}); 
 }
 
 function dashboardts(gdt){ 
$scope.loading2=true;
$http.get('includes/api/dashboarddts.php?action=dwisebrn_colc&gdt='+gdt).success(function(data) { console.log(data);
	   $scope.loading=false;
		$scope.branch_fecolc=data['branch_fecolc'];
		$scope.branch_exps=data['branch_exps'];
		$scope.brnfecolc_exp=data['brnfecolc_exp'];
		$scope.branchdts=data['brndts'];
		$scope.dtarrdts=data['dtarrdts'];
		
		$scope.dtwisepays=data['dtwisepays'];
		$scope.dtwiseusrs=data['dtwiseusrs'];
		$scope.dtusrwisecolc_exp=data['dtusrwisecolc_exp'];
		
		$scope.seldt_brncolc=data['brnfecolc_exp'].filter(function(item) {//alert(item.user_status);
  return item.dt ==gdt;
});

var tcolcsum=0;var texpsum=0;
var overalincm_exp=[];
angular.forEach($scope.branchdts, function(v, k) {
$scope.overalbrancolcdts=$scope.branch_fecolc.filter(function(item) {//alert(item.user_status);
  return item.branch_id ==v['branch_id'];
});
var brnoveralcolc=0;
angular.forEach($scope.overalbrancolcdts, function(bv, bk) {
tcolcsum +=parseFloat(bv['tpaids']);
brnoveralcolc +=parseFloat(bv['tpaids']);
});
$scope.overalbrancexpdts=$scope.branch_exps.filter(function(item) {//alert(item.user_status);
  return item.branch_id ==v['branch_id'];
});
var brnoveralcexp=0;
angular.forEach($scope.overalbrancexpdts, function(bv, bk) {
texpsum +=parseFloat(bv['expamt']);
brnoveralcexp +=parseFloat(bv['expamt']);
});
var brnbal=brnoveralcolc-brnoveralcexp;
overalincm_exp.push({branch:v['branch_short_name'],colc:brnoveralcolc,exps:brnoveralcexp,bal:brnbal});
});
$scope.overalincm_exp=overalincm_exp;
$scope.overalcolc=tcolcsum;$scope.overaltexpsum=texpsum;$scope.overaltbal=tcolcsum-texpsum;
 

/*$scope.seldt_usrs=data['dtwiseusrs'][gdt]['user_id'];
 $scope.dtwisedusrpaycolc=data['dtusrwisecolc_exp'][gdt];*/
 var dtidx= $scope.dtarrdts.findIndex(object => object.dt === gdt);
 if(dtidx!=-1){
 $scope.seldt_pays=data['dtwisepays'][gdt]['payment_type'];
 $scope.dtwiseexp=$scope.branch_exps.filter(function(item) {//alert(item.user_status);
  return item.edt ==gdt;
  });
 
 
 
var dtwiseusrpaycolc=[];
angular.forEach(data['dtwiseusrs'][gdt]['user_id'], function(v, k) {
angular.forEach(data['dtusrwisecolc_exp'][gdt][k], function(value, key) { 
  dtwiseusrpaycolc.push(value);
});
}); 
$scope.dtwiseusrpaycolc=dtwiseusrpaycolc;
$scope.dtwiseusrpaycolctamt=data['dtusrwisecolc_exp'][gdt]['tamt'];
}
 var dtwisebrncolc_exp=[];
 var totcolcamt=0;var totexpamt=0;var totbalamt=0;
if(dtidx!=-1){
angular.forEach($scope.brnfecolc_exp[dtidx][gdt], function(value, key) { 
  dtwisebrncolc_exp.push({branch:value['branch_short_name'],incm:value['incm'],expen:value['exp'],bal:value['bal']});
  totcolcamt +=value['incm'];
  totexpamt +=value['exp'];
  totbalamt +=value['bal'];
});
$scope.tcolcamt=totcolcamt;
$scope.texpamt=totexpamt;
$scope.tbalamt=totbalamt;
}
$scope.dtwisebrnfecolc_exp=dtwisebrncolc_exp;
		
		$(".dashboard_tabslist").show();
	}); 
	$scope.loading2=false;
 }
 
 function dtwise_dashboardts(gdt){ 
 var dtidx= $scope.dtarrdts.findIndex(object => object.dt === gdt);
  var dtwisebrncolc_exp=[];
   var totcolcamt=0;var totexpamt=0;var totbalamt=0;
  if(dtidx!=-1){
angular.forEach($scope.brnfecolc_exp[dtidx][gdt], function(value, key) { 
   dtwisebrncolc_exp.push({branch:value['branch_short_name'],incm:value['incm'],expen:value['exp'],bal:value['bal']});
   totcolcamt +=value['incm'];
  totexpamt +=value['exp'];
  totbalamt +=value['bal'];
});
$scope.tcolcamt=totcolcamt;
$scope.texpamt=totexpamt;
$scope.tbalamt=totbalamt;
}
$scope.dtwisebrnfecolc_exp=dtwisebrncolc_exp;
 }
 
 
 $scope.get_datewiseusrwisecolc=function(usrgdt){
 $scope.seldt_pays=$scope.dtwisepays[usrgdt]['payment_type'];
$scope.seldt_usrs=$scope.dtwiseusrs[usrgdt]['user_id'];
 $scope.dtwisedusrpaycolc=$scope.dtusrwisecolc_exp[usrgdt];

dtwiseusrpaycolc=[];
angular.forEach($scope.dtwiseusrs[usrgdt]['user_id'], function(v, k) {
angular.forEach($scope.dtusrwisecolc_exp[usrgdt][k], function(value, key) { 
  dtwiseusrpaycolc.push(value);
});
}); 

$scope.dtwiseusrpaycolc=dtwiseusrpaycolc;
$scope.dtwiseusrpaycolctamt=$scope.dtusrwisecolc_exp[usrgdt]['tamt'];
 }
 
 $scope.get_expdatewisedashboard=function(expgdt){
  $scope.loading=true;
  $scope.dtwiseexp=$scope.branch_exps.filter(function(item) {//alert(item.user_status);
  return item.edt ==expgdt;
  });
   $scope.loading=false;
 }
});

</script>