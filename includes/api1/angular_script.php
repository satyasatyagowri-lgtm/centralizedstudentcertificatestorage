	<script>
 
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
		$http.get('includes/api/student_details.php?action=bchcls').success(function(data) {
 		$scope.bchclslist=data['myarray'];
		$scope.search_branch_id ='<?php echo $_SESSION['branhid'];?>';
		$scope.search_course_id='0';
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})
		var searchText_len =0;
            $scope.fetchUsers = function(){
                searchText_len = $scope.searchText.trim().length;
                // Check search text length
                if(searchText_len > 0 || $scope.search_branch_id>0 || $scope.search_course_id>0){  // alert($scope.search_course_id);
				 $(".displayresult").show();
				/* if($scope.search_course_id>0)
				  var course=$scope.search_course_id;
				 else course='';
				 alert(course);*/
                    $http({
                    method: 'post',
                    url: 'includes/api/student_search.php?action=stdid&sbranch_id='+$scope.search_branch_id+'&scourse_id='+$scope.search_course_id,
                    data: {searchText:$scope.searchText}
                    }).then(function successCallback(response) {
					if(response.data!='null')
                         $scope.searchResult = response.data;
                    else $scope.searchResult = {};
                     });
                }else{
                    $scope.searchResult = {};
                }
            }
			
			$scope.get_branchstds = function(){
                // Check search text length
				$scope.search_course_id='0';
                if(searchText_len > 0 || $scope.search_branch_id>0 || $scope.search_course_id>0){
				 $(".displayresult").show();
                    $http({
                    method: 'post',
                    url: 'includes/api/student_search.php?action=stdid&sbranch_id='+$scope.search_branch_id+'&scourse_id='+$scope.search_course_id,
                    data: {searchText:$scope.searchText}
                    }).then(function successCallback(response) {
					//console.log(response);
                       if(response.data!='null')
                         $scope.searchResult = response.data;
                    else $scope.searchResult = {};
                     });
                }else{
                    $scope.searchResult = {};
                }
            }
			
			$scope.get_coursedts = function(){ 
                if(searchText_len > 0 || $scope.search_branch_id>0 || $scope.search_course_id>0){//alert($scope.search_course_id);
				 $(".displayresult").show();
				 if(searchText_len>0)
				   var searchtxt=$scope.searchText;
				 else var searchtxt='';
                   $http.get('includes/api/student_search.php?action=stdid&sbranch_id='+$scope.search_branch_id+'&scourse_id='+$scope.search_course_id+'&searchText='+searchtxt).success(function(data){
                        console.log(data);
						if(data!='null')
                         $scope.searchResult = data;
                    else $scope.searchResult = {};
                    });
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




app.controller('std_prodata', function($scope, $http) {
		$http.get('includes/api/student_details.php?action=stdprofile').success(function(data) {
		//alert('test');
		console.log(data);
		$scope.stdprodata=data.profile;
		$scope.first_name = $scope.stdprodata[0]['first_name'];
		$scope.last_name = $scope.stdprodata[0]['last_name'];
		$scope.father_name = $scope.stdprodata[0]['father_name'];
		$scope.course_name = $scope.stdprodata[0]['course_name'];
		$scope.city = $scope.stdprodata[0]['city'];
		$scope.roll_no = $scope.stdprodata[0]['roll_no'];
		
		$scope.secdata=data.secdetails;
		$scope.feedetails=data.std_feedts;
		
		$scope.uploadFiles = function() {
		$scope.status = "Processing..";
		var prodata = $.param({
                feedetails: $scope.feedetails
            });
			var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
		//console.log(prodata);
		$http.post('includes/api/student_details.php?action=fee_conc',prodata,config).success(function(data) {	
				console.log(data);
				if (data >0){
					window.location.href = 'index.php?p=fee_structure&student_id='+data;
				}
     		});
	  };
	  
		
		
		$scope.courseTotal = function(){
			var total = 0;
			for(count=0;count<$scope.feedetails.length;count++){
                total += parseFloat($scope.feedetails[count].course_amount);
            }
			return total;
		}
		
		$scope.paidTotal = function(){
			var total = 0;
			for(count=0;count<$scope.feedetails.length;count++){
                total += parseFloat($scope.feedetails[count].paid);
            }
			return total;
		}
		
		$scope.dueTotal = function(){
			var total = 0;
			for(count=0;count<$scope.feedetails.length;count++){
                total += parseFloat($scope.feedetails[count].tdue);
            }
			return total;
		}
		
		
 		})


	 $scope.chekconcesion_status = function (id) {
		 
     $("#hiddval").val(id);
    if(isNaN($("#concession_"+id).val())){
	$("#feerr_"+id).html("Must be Number.....");
	 // $('.numalert').show();
	//$('.glass').fadeIn();
	 $("#concession_"+id).val('');
	 $("#concession_"+id).focus();
   }
   else if(parseInt($("#concession_"+id).val())>parseInt($("#dueamt_"+id).val())){
     $('#hamt').html($("#dueamt_"+id).val());
	 $("#feerr_"+id).html("Should not grater than :"+$("#dueamt_"+id).val());
 //$('.mvalalert').show();
 //$('.glass').fadeIn();
	 $("#concession_"+id).val('');
	 $("#concession_"+id).focus()
    }
	setTimeout(function(){
  if ($("#feerr_"+id).length > 0) {
    $("#feerr_"+id).html('');
  }
}, 3000)
        }

	 });	

	 
	app.controller('std_regdata', function($scope, $http,$window) {
	$scope.std_delete = function(id) {
   $window.location.href = 'home.php?p=std_admission&action=delete&id='+id;
  }
		  $('.glass').fadeIn();	
		 $scope.loading=true;
$http.get('includes/api/student_details.php?action=stdreg_dts').success(function(data) {
		 $(function (data) {
          ang_sorting();
		  
        });
		$(".stdregdts").show();
		$scope.stdregdata=data;
		
		
		$scope.loading=false;
		$('.glass').fadeOut();	
	 })
	 }); 
	 
	app.controller('exam_data', function($scope, $http) {
$http.get('includes/api/student_details.php?action=bchcls').success(function(data) {
//$scope.loading=true;
		$scope.bchclslist=data['myarray'];
 		$scope.branchiddefault ='<?php echo $_SESSION['branhid'];?>';
		console.log(data);
		 $http.get("../includes/api/student_details.php?action=get_branch_exams&branch_id="+$scope.branchiddefault)  
           .success(function(data){ 
		   $scope.loading=false;
                $scope.branch_exams = data;  
           })
 		})
		
		
		 $scope.loadExams = function(i){ // alert(i);
		 $scope.loading=true;
		  $(".examdts").html('');
            $http.get("../includes/api/student_details.php?action=get_branch_exams&branch_id="+i)  
           .success(function(data){ 
		   $scope.loading=false;
                $scope.branch_exams = data;  
           })   
      }
	   <?php if($_REQUEST['p']=='exam_sec_view'){?>
		 $scope.loading=true;
		  $(".examdts").html('');
            $http.get("../includes/api/student_details.php?action=exam_view").success(function(data){
			console.log(data['exam_sec_subs']);
		   $scope.loading=false;
                $scope.exam_view = data; 
           })   
		   <?php }?>
	 }); 
	 
app.controller('std_info', function($scope, $http,$window) { 
    $scope.std_delete = function(id,branid,courseid,secid) {
   $window.location.href = 'home.php?p=std_info&action=del_std&id='+id+'&branch_id='+branid+'&course_id='+courseid+'&sec_id='+secid;
  }
  <?php if($_SESSION['angcourse_id']!='emptys'){?>
	   var reqapi="includes/api/student_details.php?action=stdlist&branch_id="+<?php echo $_SESSION['branhid'];?>+"&course_id="+<?php echo $_SESSION['angcourse_id'];?>+"&sec_id="+<?php echo $_SESSION['angsec_id'];?>;
	  
           $http.get(reqapi)  
           .success(function(data){console.log(data);
				$scope.stdlist = data['std_list'];
				$(".stdinfodts").show();
				$(function (data) {
				ang_sorting();
			
        });
				$scope.loading = false;
           })  
		  <?php }?>
	  
	  $scope.loadRoute = function(){  
	 		
           $http.get("../includes/api/student_details.php?action=routeslist")  
           .success(function(data){ 
                 $scope.rtlist = data;  
           })  
      }
	  
	  $scope.loadStop = function(i){  
	 		
           $http.get("../includes/api/student_details.php?action=stoplist&route_id="+i)  
           .success(function(data){ 
                 $scope.stlist = data;  
           })  
      }
	  
	   $scope.stdregform = function(){
	   $scope.status = "Processing..";
			$http.post("../includes/api/student_details.php?action=inserreg", {
						first_name: $scope.first_name,
						last_name: $scope.last_name,
						father_name: $scope.father_name,
						gender: $scope.gender,
						mobile_no: $scope.mobile_no,
						street: $scope.street,
						city: $scope.city,
						pin: $scope.pin,
						selctedbranch: $scope.selctedbranch,
						courseid: $scope.courseid,
						student_way: $scope.student_way,
						route_id: $scope.route_id,
						stop_id: $scope.stop_id,
						is_conc: $scope.is_conc
					}).then(function(response){
					    //alert(response.data);
							//console.log(response);
							//console.log("Data Inserted Successfully");
							//$scope.success = 'Data Inserted Successfully';
							
							if($scope.is_conc == 1)
							window.location.href = 'index.php?p=fee_structure&student_id='+response.data;
							else 
							window.location.href = 'index.php?p=std_admission';
						},function(error){
							alert("Sorry! Data Couldn't be inserted!");
							console.error(error);
		
			
			});
		 }
  
	  
	  
 
});


app.controller('stdadmission_dts', function($scope, $http) {
        $scope.loading=true;
		$http.get('includes/api/angular_fetch.php?action=std_admission').success(function(data) {
		console.log(data);
		$(function (data) {
            $('#ang_sorting').DataTable();
        });
		$scope.stdadmission=data;
		$scope.loading = false; 	 
		});

});

app.controller('out_going_stddata', function($scope, $http,$window) {
	
		//  $('.glass').fadeIn();	
		 $scope.loading=true;
$http.get('includes/api/student_details.php?action=outgoing_stddts').success(function(data) {
		console.log(data);
		 $(function (data) {
          ang_sorting();
		  
        });
		$scope.outgoningstds=data;
		
		
		$scope.loading=false;
		$('.glass').fadeOut();	
	 })
	 });

</script>