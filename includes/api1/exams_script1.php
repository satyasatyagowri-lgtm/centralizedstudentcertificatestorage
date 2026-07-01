	<script>
 
app.controller('std_compexamdts', function($scope, $http) { 
   $scope.course_id='<?php  echo $std_dts[$_SESSION['std_id']]['std_course'].'^'.$std_dts[$_SESSION['std_id']]['year_id'];?>';
      $scope.get_course=function(course_id){ 
	$scope.exam_types={};
	 $scope.loading = true;
		// Fetch data
		$http.get('includes/api/exam_details.php?action=std_compexamdts&set_promtecourse=yes&course_id='+$scope.course_id).success(function(data) {
		console.log(data);
		
 		 $scope.exam_types=data;
		 $scope.loading = false;
		//$scope.exam_mrks=data.exam_marks;
		//$scope.courseid = $scope.selctedbranch.course[3];
		
		   angular.forEach(data, function(value, key) {
		   amchart_func(value['exam_type_id']);
 			
        });
		
		
		
 		})
	}

//amchart_func();
			 $scope.loading = true;
		// Fetch data
 		$http.get('includes/api/exam_details.php?action=std_compexamdts&course_id='+$scope.course_id).success(function(data) {
		  angular.forEach(data, function(value, key) {
		   amchart_func(value['exam_type_id']);
 			
        }); 
 		 $scope.exam_types=data;
		 $("#stdexam_div").show();
		 $scope.loading = false;
		//$scope.exam_mrks=data.exam_marks;
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})
		
function amchart_func(chartid){//
 $(function() { 
            var options = {
                chart: {
                    height: 500,
                    type: 'line',
                    zoom: {
                        enabled: false
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [5, 7, 5],
                    curve: 'straight',
                    dashArray: [0, 8, 5]
                },
                colors: ["#0e9e4a", "#ffba57", "#ff5252"],
                series: [{
                        name: "Class Topper",
                        data: [55,45,34]
                    },
                    {
                        name: "Student Marks",
                        data: [70,80,60]
                    },
                    {
                        name: 'Class Average',
                        data: [40,30,60]
                    }
                ],
                title: {
                    text: 'Exams Statistics',
                    align: 'left'
                },
                markers: {
                    size: 0,

                    hover: {
                        sizeOffset: 6
                    }
                },
                xaxis: {
                    categories: ['FA1','FA2','FA3'],
                },
                tooltip: {
                    y: [{
                        title: {
                            formatter: function(val) {
                                return val 
                            }
                        }
                    }, {
                        title: {
                            formatter: function(val) {
                                return val  
                            }
                        }
                    }, {
                        title: {
                            formatter: function(val) {
                                return val;
                            }
                        }
                    }]
                },
                grid: {
                    borderColor: '#f1f1f1',
                }
            }
            var chart = new ApexCharts(
                document.querySelector("#line-chart-"+chartid),
                options
            );
            chart.render();
			});
			}
});



app.controller('exam_subwiseavg', function($scope, $http,$window) {	

           $scope.get_examtype = function(i){  
		   $(".examsavgdata").hide();
		   $(".loadproces").show();
		   $scope.loading = true;
	 	   $scope.stdlist = '';//alert(i);
			  var reqapi="includes/api/exam_details.php?action=exam_subwiseavgdts&branch_id="+<?php echo $_SESSION['branhid'];?>+"&course_id="+$scope.course_id+"&exam_type_id="+$scope.exam_type_id;
           $http.get(reqapi)  
           .success(function(data){ //alert(data);
		   $(".examsavgdata").show();
				$scope.extitle=data['extitle'];
				$scope.exam_subavg=data['std_avgmrks'];
		        $scope.exam_subs=data['sub_names'];	
				$scope.noexams=data['no_exams'];	
				$scope.exam_dts=data['exam_dts'];		
				$scope.loading = false;
				$(".loadproces").hide();
           }) 
		   } 
		   
		    $scope.get_course_id = function(i){  
		   $(".examsavgdata").hide();
		    $scope.exam_type_id='';
		   
		   } 
});

app.controller('examwise_estimateavgs', function($scope, $http,$window) {	
            $scope.get_examtype = function(i){  
			if($scope.juni_senior>0){
			$scope.extitle={};
				$scope.sub_names={};
				$scope.exam_subavg={};
		   $(".examsavgdata").hide();
		   $(".loadproces").show();
		   $scope.loading = true;
	 	   $scope.stdlist = '';//alert(i);
			  var reqapi="includes/api/exam_details.php?action=examtypewise_subwiseavgdts&juni_senior="+$scope.juni_senior+"&exam_type_id="+$scope.exam_type_id;
           $http.get(reqapi)  
           .success(function(data){ //alert(data);
		   $(".examsavgdata").show();
				$scope.extitle=data['extitle']['exam_name'];
				$scope.sub_names=data['sub_names'];
				$scope.exam_subavg=data['std_avgmrks'];
		        $scope.exam_names=data['extitle']['exam_name'];	
				$scope.noexams=data['no_exams'];	
				$scope.exam_dts=data['exam_dts'];		
				$scope.loading = false;
				$(".loadproces").hide();
           }) 
		   }
		   } 
		   
		   
		    $scope.gets_junisenior = function(i){   
			if($scope.exam_type_id>0){
			$scope.extitle={};
				$scope.sub_names={};
				$scope.exam_subavg={};
		   $(".examsavgdata").hide();
		   $(".loadproces").show();
		   $scope.loading = true;
	 	   $scope.stdlist = '';//alert(i);
			  var reqapi="includes/api/exam_details.php?action=examtypewise_subwiseavgdts&juni_senior="+$scope.juni_senior+"&exam_type_id="+$scope.exam_type_id;
           $http.get(reqapi)  
           .success(function(data){ //alert(data);
		   $(".examsavgdata").show();
				$scope.extitle=data['extitle']['exam_name'];
				$scope.sub_names=data['sub_names'];
				$scope.exam_subavg=data['std_avgmrks'];
		        $scope.exam_names=data['extitle']['exam_name'];	
				$scope.noexams=data['no_exams'];	
				$scope.exam_dts=data['exam_dts'];		
				$scope.loading = false;
				$(".loadproces").hide();
           }) 
		   }
		   } 
		   
		    $scope.get_course_id = function(i){  
		   $(".examsavgdata").hide();
		    $scope.exam_type_id='';
		   
		   } 
});
</script>