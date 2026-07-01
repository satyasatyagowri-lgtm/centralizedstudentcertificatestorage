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

app.controller('std_search', function($scope, $http) {
			
		// Fetch data
		$http.get('../includes/api/student_details.php?action=linedls').success(function(data) {
		console.log(data);
		$scope.linelist=data['myarray'];
		 $scope.search_line_id =$scope.linelist[0];
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})
		var searchText_len =0;
            $scope.fetchUsers = function(){
                searchText_len = $scope.searchText.trim().length;//alert(searchText_len);
                // Check search text length
              //  if(searchText_len > 0 || $scope.search_line_id.lineid>0 || $scope.search_city_id>0){ 
			 // alert($scope.search_line_id.lineid);
				 $(".displayresult").show();
				/* if($scope.search_city_id>0)
				  var course=$scope.search_city_id;
				 else course='';
				 alert(course);*/
                    $http({
                    method: 'post',
                    url: '../includes/api/student_search.php?action=stdid'+'&sline_id='+$scope.search_line_id.lineid+'&scity_id='+$scope.search_city_id,
                    data: {searchText:$scope.searchText}
                    }).then(function successCallback(response) {
					//console.log(response);
                        $scope.searchResult = response.data;
						console.log(response.data);
                    });
                /*}else{
                    $scope.searchResult = {};
                }*/
            }
			
			$scope.get_lines = function(){
                // Check search text length
				if($scope.searchText!=undefined)
				searchText_len = $scope.searchText.trim().length;
                if(searchText_len > 0 || $scope.search_line_id.lineid!='' || $scope.search_city_id>0){
				 $(".displayresult").show();
                    $http({
                    method: 'post',
                    url: '../includes/api/student_search.php?action=stdid'+'&sline_id='+$scope.search_line_id.lineid+'&scity_id='+$scope.search_city_id,
                    data: {searchText:$scope.searchText}
                    }).then(function successCallback(response) {
					//console.log(response);
                        $scope.searchResult = response.data;
						console.log(response.data);
                    });
                }else{
                    $scope.searchResult = {};
                }
            }
			
			$scope.get_citydts = function(){ 
			if($scope.searchText!=undefined)
				searchText_len = $scope.searchText.trim().length;
                if(searchText_len > 0 || $scope.search_line_id.lineid>0 || $scope.search_city_id>0){
				 $(".displayresult").show();
				 if(searchText_len>0)
				   var searchtxt=$scope.searchText;
				 else var searchtxt='';
                   
$http({
                    method: 'post',
                    url: '../includes/api/student_search.php?action=stdid'+'&sline_id='+$scope.search_line_id.lineid+'&scity_id='+$scope.search_city_id,
                    data: {searchText:$scope.searchText}
                    }).then(function successCallback(response) {
					//console.log(response);
                        $scope.searchResult = response.data;
						console.log(response.data);
                    });				   /*$http.get('../includes/api/student_search.php?action=stdid'+'&sline_id='+$scope.search_line_id.lineid+'&scity_id='+$scope.search_city_id+'&searchText='+searchtxt).success(function(data){console.log(data);
                        $scope.searchResult = data;
                    });*/
                }else{
                    $scope.searchResult = {};
                }
            }

            // Set value to search box
            $scope.setValue = function(index,$event){
                $scope.searchText = $scope.searchResult[index].course_short_name;
                $scope.searchResult = {};
                $event.stopPropagation();
            }

            $scope.searchboxClicked = function($event){
                $event.stopPropagation();
            }

            $scope.containerClicked = function(){
                $scope.searchResult = {};
            }

});

app.controller('custpaydts', function($scope, $http) {	 	
$scope.p_upi=1;
     $scope.wekdts=[{weekname:'ALL'},{weekname:'SUB'},{weekname:'MON'},{weekname:'TUE'},{weekname:'WED'},{weekname:'THU'},{weekname:'FRI'},{weekname:'SAT'}];
    $scope.seekdy='ALL';
 		$http.get('includes/api/customer_details.php?action=customer_borrowdetails').success(function(data) {
                          $scope.linelist=data['linedts'];
                          $scope.orgcustpdts=data['paydts'];
                           $scope.scrchline_id=$scope.linelist[0]['line_id'];
                           $scope.lineweedts=data['lineweedts'][$scope.scrchline_id];
                           $scope.week_id=$scope.lineweedts[0]['week_id'];
                           $scope.linecities=data['linectiydts'];
                           $scope.sellinecity=$scope.linecities[$scope.scrchline_id];
                           $scope.city_id=0;
 
                	$scope.orgcustlist=data['getcustdts_arr'];
	$scope.actcustomers=$scope.orgcustlist.filter(function(item) { 
  return item.line_id ==$scope.scrchline_id;
    });
        })


        $scope.get_weekcitydts=function(week_id){  
         //  $scope.actcustomers={}; 
            $scope.sellinecity=$scope.linecities[$scope.scrchline_id][week_id];
            $scope.city_id=0;
            
          if (week_id > 0) {
    $scope.actcustomers = $scope.orgcustlist.filter(function(item) { 
        return item.week_id == week_id;
    });
} else {
    $scope.actcustomers = $scope.orgcustlist;
}

$scope.getlineweekcustomers = $scope.actcustomers;

    
          }
        $scope.get_lines=function(lin_id){
             $scope.week_id=$scope.lineweedts[0]['week_id'];
            $scope.actcustomers=$scope.orgcustlist.filter(function(item) { 
  return item.line_id ==lin_id;
    });
         }

         $scope.get_citycustomers=function(cityid){  
            if(cityid==0)
             $scope.actcustomers=$scope.getlineweekcustomers;
            else
            $scope.actcustomers=$scope.getlineweekcustomers.filter(function(item) { 
  return item.city_id ==cityid;
    });
         }

         

	$scope.get_custduedts=function(cust_id){
       
        $scope.customer_id=cust_id;
        var get_custky= $scope.actcustomers.findIndex(object => object.customer_id === cust_id);
        $scope.custduedts=$scope.actcustomers[get_custky];

        $scope.custloandts=$scope.actcustomers[get_custky]['custduedts'];
        $scope.sborrow_id=$scope.custloandts[0]['borrow_id'];
         $scope.custselbordts=$scope.custloandts[0];

         $scope.custlstpdts=$scope.orgcustpdts[$scope.sborrow_id];

          $http.get('includes/api/customer_details.php?action=cutomer_lineuserdts&cutomer_lineusers='+$scope.actcustomers[get_custky]['line_id']).success(function(data) {
                          $scope.lineusrlst=data;
                           $scope.upayuser_id=$scope.lineusrlst[0].user_id;

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
     }


     $scope.get_amount=function(jval)
{  
  
 var customer_amt=$("#custpableamt"+jval).val();
// alert(customer_amt+' '+f);
 if(parseFloat(customer_amt) < parseFloat($("#customer_pays"+jval).val())){
// $('#hamt').html(customer_amt);
  $("#feerr_"+jval).html("Should not grater than :"+customer_amt);
// $('.mvalalert').show();
// $('.glass').fadeIn();
$("#customer_pays"+jval).val('');
 }

  setTimeout(function(){
  if ($("#feerr_"+jval).length > 0) {
    $("#feerr_"+jval).html('');
  }
}, 3000);

if(parseFloat($("#customer_pays"+jval).val())>0){
    $("#saveBtn").attr('disabled',false);
}else{
 $("#saveBtn").attr('disabled',true);
}
}
        });

app.controller('linewise_accountbook', function($scope, $http) {	 	
 		$http.get('includes/api/customer_details.php?action=getlinewise_paid_pendlists').success(function(data) {
                          $scope.linelist=data['linedts'];
                          $scope.orglinebookdts=data['linebook_dts'];
                           $scope.scrchline_id=$scope.linelist[0]['line_id'];
 
 	$scope.actlinebookdts=$scope.orglinebookdts[$scope.scrchline_id];
         })

        $scope.get_lines=function(lin_id){ 
            $scope.actlinebookdts=$scope.orglinebookdts[lin_id];
          }
        });
</script>