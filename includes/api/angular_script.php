	<script>
 var app = angular.module("schools", []);
 app.directive('ngConfirmClick', [
        function() {
            return {
                link: function (scope, element, attr) {
                    var msg = attr.ngConfirmClick || "Are you sure?";
                    var clickAction = attr.confirmedClick;
                    element.bind('click', function (event) {
                        if (window.confirm(msg)) {
                            scope.$eval(clickAction)
                        }
                    });
                }
            };
    }])



app.controller('custpaydts', function($scope, $http,$timeout) {	  
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
  $scope.g_date=today;	
  
  $scope.weeknames={1:'Sun',2:'Mon',3:'Tue',4:'Wed',5:'Thu',6:'Fri',7:'Sat'};
$scope.getDayName = function(dateStr) {
    $scope.userpaymnentvalidpermis='<?php echo $_SESSION['is_feepaydate_permission'];?>';
   var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
   var p = dateStr.split('-');
   var pdt=days[new Date(p[2], p[1]-1, p[0]).getDay()];
      let inputVal = $("#g_date").val();
    
    var spttoday = today.split('-');
    var sptgdt = $("#g_date").val().split('-');
    var convrtoday = spttoday[2] + '' + spttoday[1] + '' + spttoday[0];
    var convrgdt = sptgdt[2] + '' + sptgdt[1] + '' + sptgdt[0];
    let selectedDate = new Date(inputVal);
    let todays = new Date();

let lastSunday = new Date(todays);
    lastSunday.setDate(todays.getDate() - todays.getDay());

    let day = String(lastSunday.getDate()).padStart(2, '0');
let month = String(lastSunday.getMonth() + 1).padStart(2, '0');
let year = lastSunday.getFullYear();
lastSundays=year+''+month+''+day;
//alert(convrgdt+' '+convrtoday);
    if (convrgdt > convrtoday){//alert('k');
  $scope.paymentsuccessmsg="*Should not go future Date...";
          $scope.csutdtsts=1;
          
}
else if($scope.userpaymnentvalidpermis==1){
         if(pdt!=$scope.weeknames[$scope.custweekid]){
          $scope.paymentsuccessmsg="*Customer Week Day "+$scope.weeknames[$scope.custweekid]+",Please Change Date...";
          $scope.csutdtsts=1;
        }else{
          $scope.paymentsuccessmsg="";
          $scope.csutdtsts=0;
        }
    }else{ 
        if(convrgdt < lastSundays){
             $scope.paymentsuccessmsg="*Cannot select Below last Sunday Dates.";
          $scope.csutdtsts=1;
        }
       else if(pdt!=$scope.weeknames[$scope.custweekid]){
          $scope.paymentsuccessmsg="*Customer Week Day "+$scope.weeknames[$scope.custweekid]+",Please Change Date...";
          $scope.csutdtsts=1;
        }else{
          $scope.paymentsuccessmsg="";
          $scope.csutdtsts=0;
        }
    }

       if(parseFloat($scope.custpatot)>0 && $scope.csutdtsts==0){
    $("#saveBtn").attr('disabled',false);
}else{
 $("#saveBtn").attr('disabled',true);
}
}

  $scope.getDayNamebkp = function(dateStr) {
   var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
   var p = dateStr.split('-');
   var pdt=days[new Date(p[2], p[1]-1, p[0]).getDay()];

   // return date.toLocaleDateString('en-US', { weekday: 'long' });
   if(pdt!=$scope.weeknames[$scope.custweekid]){
          $scope.paymentsuccessmsg="*Customer Week Day "+$scope.weeknames[$scope.custweekid]+",Please Change Date...";
          $scope.csutdtsts=1;
        }else{
          $scope.paymentsuccessmsg="";
          $scope.csutdtsts=0;
        }
   //  alert($scope.custweekerr+'   '+$scope.csutdtsts);

      if(parseFloat($scope.custpatot)>0 && $scope.csutdtsts==0){
    $("#saveBtn").attr('disabled',false);
}else{
 $("#saveBtn").attr('disabled',true);
}

}

$scope.p_upi=1;
     $scope.wekdts=[{weekname:'ALL'},{weekname:'SUN'},{weekname:'MON'},{weekname:'TUE'},{weekname:'WED'},{weekname:'THU'},{weekname:'FRI'},{weekname:'SAT'}];
    $scope.seekdy='ALL';
  		$http.get('includes/api/customer_details.php?action=customer_borrowdetails').success(function(data) {
        
                          $scope.linelist=data['linedts'];
                          $scope.orgcustpdts=data['paydts'];
                           $scope.scrchline_id=$scope.linelist[0]['line_id'];
                           $scope.orglineweedts=data['lineweedts'];
                           $scope.orglinectiydts=data['linectiydts'];
                           $scope.lineweedts=data['lineweedts'][$scope.scrchline_id];
                           $scope.week_id=$scope.lineweedts[0]['week_id'];
                           $scope.linecities=data['linectiydts'];
                           $scope.selweklinecity=$scope.linecities[$scope.scrchline_id];
                           $scope.city_id=0;
 
                	$scope.orgcustlist=data['getcustdts_arr'];
                  $scope.allCustomers = data['getcustdts_arr'];
	$scope.actcustomers=$scope.orgcustlist.filter(function(item) { 
  return item.line_id ==$scope.scrchline_id;
    });

    $scope.actlinecusomers=$scope.actcustomers;
       
        })

     /*    $scope.get_weekcitydts=function(week_id){  
            $scope.actcustomers=[]; 
            $scope.selweklinecity=$scope.linecities[$scope.scrchline_id][week_id];
            
          if (week_id > 0) { 
    $scope.actcustomers = $scope.actlinecusomers.filter(function(item) { 
        return item.week_id == week_id;
    });
} else {
     $scope.actcustomers = $scope.actlinecusomers;
}

   $scope.getlineweekcustomers = $scope.actcustomers;    
          }

        $scope.get_lines=function(lin_id){
          $scope.lineweedts=$scope.orglineweedts[$scope.scrchline_id];
             $scope.week_id=$scope.lineweedts[0]['week_id'];
            $scope.actcustomers=$scope.orgcustlist.filter(function(item) { 
  return item.line_id ==$scope.scrchline_id;
    });
     $scope.actlinecusomers=$scope.actcustomers;
         }

         $scope.get_citycustomers=function(cityid,i){   
        if(i>1){ 
             if($scope.city_id==0)
             $scope.actcustomers=$scope.getlineweekcustomers;
            else
            $scope.actcustomers=$scope.getlineweekcustomers.filter(function(item) { 
  return item.city_id ==$scope.city_id;
    });
  }
          }*/

$scope.$watchGroup(['scrchline_id','week_id','city_id'], function () {
    $scope.filterCustomers();
});

$scope.filterCustomers = function () {

    $scope.actcustomers = $scope.allCustomers.filter(function (item) {

        var lineMatch = !$scope.scrchline_id || item.line_id == $scope.scrchline_id;
        var weekMatch = !$scope.week_id || $scope.week_id == 0 || item.week_id == $scope.week_id;
        var cityMatch = !$scope.city_id || $scope.city_id == 0 || item.city_id == $scope.city_id;

        return lineMatch && weekMatch && cityMatch;
    });

};


$scope.get_lines = function () {
    $scope.lineweedts = $scope.orglineweedts[$scope.scrchline_id];
    $scope.selweklinecity = $scope.linecities[$scope.scrchline_id];
    $scope.week_id = 0;
    $scope.city_id = 0;
};

$scope.get_weekcitydts = function (week_id) {
   console.log($scope.linecities);
    $scope.selweklinecity = $scope.linecities[$scope.scrchline_id][week_id];
     $scope.city_id = 0;selweklinecity
};


	/* This is Old Code
  $scope.get_custduedts=function(cust_id){
       
        $scope.customer_id=cust_id;
        var get_custky= $scope.actcustomers.findIndex(object => object.customer_id === cust_id);
        $scope.custduedts=$scope.actcustomers[get_custky];

        $scope.custloandts=$scope.actcustomers[get_custky]['custduedts'];
        $scope.sborrow_id=$scope.custloandts[0]['borrow_id'];
         $scope.custselbordts=$scope.custloandts[0];
        $scope.custborrodts=[{customer_id:cust_id,borrow_id:$scope.sborrow_id,customer_cashpays:'',rbal:$scope.custloandts[0]['rbal']}];
         $scope.custlstpdts=$scope.orgcustpdts[$scope.sborrow_id];

          $http.get('includes/api/customer_details.php?action=cutomer_lineuserdts&cutomer_lineusers='+$scope.actcustomers[get_custky]['line_id']+'&customer_id='+cust_id).success(function(data) {
                          $scope.lineusrlst=data;
                           $scope.upayuser_id=$scope.lineusrlst[0].user_id;

        });

        $(".customerpys").val('');
     }*/



        $scope.get_custduedts=function(cust_id){
       $scope.custborrodts=[];
       var custborrodtsar=[];
        $scope.customer_id=cust_id;
        var get_custky= $scope.actcustomers.findIndex(object => object.customer_id === cust_id);
        $scope.custduedts=$scope.actcustomers[get_custky];


          $http.get('includes/api/customer_details.php?action=cutomer_lineuserdts&cutomer_lineusers='+$scope.actcustomers[get_custky]['line_id']+'&customer_id='+cust_id).success(function(data) {
                          $scope.lineusrlst=data['usrdts'];
                        
                          $scope.lousrid = '<?php echo $_SESSION['user_id']; ?>';
                          if (!$scope.lineusrlst.some(function(u) { 
                               return u.user_id == $scope.lousrid; 
                          })) {
                               $scope.upayuser_id = $scope.lineusrlst[0].user_id;
                              } else {
                               $scope.upayuser_id = $scope.lousrid;
                              }
                 
                         
                       $scope.custloandts=data['getcustdts_arr']['custduedts'];
                       $scope.custweekid=data['getcustdts_arr']['week_id'];
        $scope.sborrow_id=$scope.custloandts[0]['borrow_id'];
         $scope.custselbordts=$scope.custloandts[0];

       $scope.getDayName($scope.g_date);
        
        $scope.custborrodts=[{customer_id:cust_id,borrow_id:$scope.sborrow_id,customer_cashpays:'',customer_upipays:'',rbal:$scope.custloandts[0]['rbal']}];
        // alert($scope.custborrodts.length);
          $scope.custlstpdts=data['paydts'][$scope.sborrow_id];
         });

        $(".customerpys").val('');
       
     }

     $scope.get_paytype=function(i){
       $scope.upayuser_id=$scope.lineusrlst[0].user_id;
      if(i==2)
        $("#upayuser_id").addClass('valid');
      else $("#upayuser_id").removeClass('valid');
     }

    $scope.get_selborrow=function(selborrow_id){
        $(".customerpys").val('');
         var get_cusborrowtky= $scope.custloandts.findIndex(object => object.borrow_id === selborrow_id);
       $scope.custselbordts=$scope.custloandts[get_cusborrowtky];
       $scope.custlstpdts=$scope.orgcustpdts[selborrow_id];
       $scope.custborrodts=[{customer_id:$scope.customer_id,borrow_id:selborrow_id,customer_cashpays:'',customer_upipays:'',rbal:$scope.custloandts[get_cusborrowtky]['rbal']}];
     }


     $scope.get_amount=function(jval,idx)
{  
 
 var customer_amt=$("#custpableamt"+jval).val();
// alert(customer_amt+' '+f);
var custtotcashupipays= (parseFloat($("#customer_cashpays"+jval).val()) || 0) +
                    (parseFloat($("#customer_upipays"+jval).val())  || 0);
 if(parseFloat(customer_amt) < custtotcashupipays){
// $('#hamt').html(customer_amt);
  $("#feerr_"+jval).html("Should not grater than :"+customer_amt);
// $('.mvalalert').show();
// $('.glass').fadeIn();
$("#customer_cashpays"+jval).val('');
 $scope.custborrodts[idx].customer_cashpays='';
 $scope.custborrodts[idx].customer_upipays='';
 }

  setTimeout(function(){
  if ($("#feerr_"+jval).length > 0) {
    $("#feerr_"+jval).html('');
  }
}, 3000);
$scope.custpatot=0;
 angular.forEach($scope.custborrodts, function (x) {
        $scope.custpatot += (parseFloat(x.customer_cashpays) || 0) +
                    (parseFloat(x.customer_upipays)  || 0);
    });
 
    if(parseFloat($scope.custpatot)>0 && $scope.csutdtsts==0){
    $("#saveBtn").attr('disabled',false);
}else{
 $("#saveBtn").attr('disabled',true);
}
}




$scope.submittrborrow_frm=function(){
  $('.valid').css('border','1px solid red');
	  var flag=1;
	       $("#customerpayfrm").find('.valid').each(function(){
			var error=$(this).parent().find("#error");
	      //    var error=$(this).find("#error");
			  if($(this).val()=='')
			  {
			  flag=0; error.html('Missing '+$(this).attr('title')+'<br>')
			  $(this).css('border','1px solid red');
			  }
			  else
			  {
			    error.html('');
			    $(this).css('border','1px solid #ccc');
			  }
		});
		//console.log($scope.add_fixdata);
   		 if(flag==1){ 
			 $scope.flagsuccess=1;
       $scope.loading = true;
 $(".glass").fadeIn();$("#formload").show();
// $(".submtbtn").hide();
$("#saveBtn").attr('disabled',true);
  var url = 'includes/api/customer_details.php?action=submit_customer_payments&access_tokenid=<?php echo $_SESSION['lograndval']?>'; 
  var fd = new FormData();
   fd.append("custbrowdata", angular.toJson($scope.custborrodts));
 	fd.append("customer_id", $scope.customer_id);
 	fd.append("tot_paidamt", $scope.customer_cashpays);
	fd.append("g_date", $scope.g_date);
     fd.append("sborrow_id", $scope.sborrow_id);
     fd.append("sel_user_id", $scope.upayuser_id);
	fd.append("upayuser_id", $scope.upayuser_id);
	fd.append("payment_type", $scope.p_upi);
  	  

    var config = { headers: {'Content-Type': undefined},
                   transformRequest: angular.identity
                 }
 
 $http.post(url, fd, config).then(function (response) { 
  console.log(response);  
 	  if(response.data['sessionsts']==1){			
      $scope.paymentprocesssts=response.data['sessionsts'];
      $scope.loading = false;
       $scope.paymentsuccessmsg="Payment Completed Successfully...";	 
    //  $("#paymentsuccess").html("Payment Completed Successfully...");
      setTimeout(function(){
        $(".glass").fadeOut();$("#formload").hide();
       
   $scope.paymentsuccessmsg="";	 
 // $("#paymentsuccess").html("");
   $scope.get_custduedts($scope.customer_id);
   // $("#saveBtn").attr('disabled',false);
}, 3000);
				  //alert($window.sessionStorage.getItem('ord_num')+'  '+response.data['ord_num'])
       //  location.replace('home.php?p=accountswise_rep'); 
		 }else{
      $(".confirmdiv").show();
       $scope.paymentprocesssts=response.data['sessionsts'];
      $scope.loading = false;
       $scope.paymentsuccessmsg="Payment Not Completed Please Refresh...";	
     } 
  	});
 
	}
    }
        });

app.controller('linewise_accountbook', function($scope, $http) {	
    $http.get('includes/api/customer_details.php?action=getline_citys').success(function(data) {
            $scope.linedts=data['linedts'];
            $scope.lineweedts=data['lineweedts'];
            $scope.linectiydts=data['linectiydts'];
     }); 	
 		
$scope.manualweeks=8;
        $scope.get_lines=function(lin_id){ 
            $scope.custduelist={};
         /*   $http.get('includes/api/customer_details.php?action=getlinewise_paid_pendlists&line_id='+$scope.line_id).success(function(data) {
                          $scope.line_weekdts=data['lineweedts'];
                          $scope.orglinewekcitys=data['linectiydtsarr'];
                          $scope.customerduelists=data['customerduelists'];
                          $scope.linweekdtars=data['lineweekdudtsarr'];
                           $scope.orglinwiseweekdtsar=data['duedtsarr'];

          });*/
           }
          $scope.get_lineweeks=function(weekid){ 
         $scope.custduelist={};  
        }

           $scope.get_lineweekcitys=function(cityid){
            $('.glass').fadeIn();	
             $scope.loading=true;
            $http.get('includes/api/customer_details.php?action=getlinewise_paid_pendlists&line_id='+$scope.line_id+'&week_id='+$scope.week_id+'&city_id='+$scope.city_id+'&manualweeks='+$scope.manualweeks).success(function(data) {
                $('.glass').fadeOut();	    $scope.loading=false;   
                $scope.customerduelists=data['customerduelists'];
                          $scope.custduelist=data['customerduelists'];
                            $scope.orglinwiseweekdtsar=data['duedtsarr'];
                            $scope.linweekdtar=$scope.orglinwiseweekdtsar[$scope.week_id][cityid];
                            
                
          });
            }

            $scope.getmanualweek=function(manualweeks){
            $('.glass').fadeIn();	
             $scope.loading=true;
            $http.get('includes/api/customer_details.php?action=getlinewise_paid_pendlists&line_id='+$scope.line_id+'&week_id='+$scope.week_id+'&city_id='+$scope.city_id+'&manualweeks='+$scope.manualweeks).success(function(data) {
                 $('.glass').fadeOut();	  $scope.loading=false;     
                $scope.customerduelists=data['customerduelists'];
                          $scope.custduelist=data['customerduelists'];
                            $scope.orglinwiseweekdtsar=data['duedtsarr'];
                            $scope.linweekdtar=$scope.orglinwiseweekdtsar[$scope.week_id][cityid];
                           
               
          });
            }
        });
</script>