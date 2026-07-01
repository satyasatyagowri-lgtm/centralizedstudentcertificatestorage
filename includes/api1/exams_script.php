<script>
 
 app.controller('std_compexamdts', function($scope, $http) { 
     $scope.course_id='<?php  echo $std_dts[$_SESSION['std_id']]['std_course'].'^'.$std_dts[$_SESSION['std_id']]['year_id'];?>';
       $scope.get_course=function(course_id){  //alert(course_id);
     $scope.exam_types={};
      $scope.loading = true;
         // Fetch data
         $http.get('includes/api/exam_details.php?action=std_compexamdts&set_promtecourse=yes&course_id='+$scope.course_id).success(function(data) {
              setTimeout(function(){
                 $(".linechardiv").show();
          angular.forEach(data, function(value, key) {
              if(value['exam_type_name']!=''){
               barchart_fun(value['exam_type_name'],value['exam_type_id'],value['typstdmrks'],value['typtopmrks'],value['typminmrks'],value['exam_names']);
             }
         }); 
     }, 1000);
           $scope.exam_types=data;
          $scope.loading = false; 
          });
     } 
 
 //linechart_fun();
              $scope.loading = true;
          $http.get('includes/api/exam_details.php?action=std_compexamdts&course_id='+$scope.course_id).success(function(data) { 
                setTimeout(function(){
                 
                 $(".linechardiv").show();
                 angular.forEach(data, function(value, key) {
             if(value['exam_type_name']!=''){
               barchart_fun(value['exam_type_name'],value['exam_type_id'],value['typstdmrks'],value['typtopmrks'],value['typminmrks'],value['exam_names']);
             }
         }); 
         $scope.loading = false;
       }, 1000);
 
           
           $scope.exam_types=data;
          $("#stdexam_div").show();
           $scope.loading = false;
          })
         
         
        function barchart_fun(exam_type,chartid,stdmrks,topmrks,minmrks,exmname)
       {   
      $(function() {//alert(chartid);
  //	  console.log(stdmrks_arry);
     /* var stdmrks_arry=new Array();
       stdmrks_arry = stdmrks.split(",");
       console.log(stdmrks_arry);
       var topmrks_arry=new Array();
       topmrks_arry = topmrks.split(",");
       
       var avgmrks_arry=new Array();
       avgmrks_arry = avgmrks.split(",");
       
        var exmname_arry=new Array();
       exmname_arry = exmname.split(",");*/
      
         Highcharts.chart('chart-highchart-bar'+chartid, {
             chart: {
                 type: 'column'
             },
             colors: ['#009900', '#FFCC66', '#FF0000'],
             title: {
                 text: exam_type 
             },
             xAxis: {
                 categories: exmname,
                 crosshair: true
             },
             tooltip: {
                 headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                 pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                     '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                 footerFormat: '</table>',
                 shared: true,
                 useHTML: true
             },
             plotOptions: {
                 column: {
                     pointPadding: 0.2,
                     borderWidth: 0
                 }
             },
             series: [{
                 name: 'Class Topper(%)',
                 data: topmrks,
                 color: '#009900'
 
             }, {
                 name: 'Student Marks(%)',
                 data: stdmrks,
                 color: '#FFCC66'
             }, {
                 name: 'Class Average(%)',
                 data: minmrks,
                 color: '#FF0000',
             }]
         });
          });
         }
         
         
         
 function linechart_fun(exam_type,chartid,stdmrks,topmrks,avgmrks,exmname){//
 
  
  $(function() { /*var stdmrks_arry=new Array();
       stdmrks_arry = stdmrks.split(",");
       console.log(stdmrks_arry);
       var topmrks_arry=new Array();
       topmrks_arry = topmrks.split(",");
       
       var avgmrks_arry=new Array();
       avgmrks_arry = avgmrks.split(",");
       
       
       var exmname_arry=new Array();
       exmname_arry = exmname.split(",");*/
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
                         data: topmrks
                     },
                     {
                         name: "Student Marks",
                         data: stdmrks
                     },
                     {
                         name: 'Class Average',
                         data: avgmrks
                     }
                 ],
                 title: {
                     text: exam_type,
                     align: 'center'
                 },
                 markers: {
                     size: 0,
 
                     hover: {
                         sizeOffset: 6
                     }
                 },
                 xaxis: {
                     categories: exmname,
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
 
 
 app.controller('std_exams_subwiseperformanance', function($scope, $http) {  
     $scope.course_id='<?php  echo $std_dts[$_SESSION['std_id']]['std_course'].'^'.$std_dts[$_SESSION['std_id']]['year_id'];?>';
       $scope.get_course=function(course_id){  //alert(course_id);
     $scope.orgexam_typesdts={};$scope.exam_typesdts={};
      $scope.loading = true;
      
         // Fetch data
         $http.get('includes/api/exam_details.php?action=std_subwise_performnance&set_promtecourse=yes&course_id='+$scope.course_id).success(function(data) {
            $scope.exmtypedts=data['exmtypedts'];
              $scope.exam_type_id=$scope.exmtypedts[0]['exam_type_id'];
                
 $scope.orgexam_typesdts=data['myarray'];
            $scope.exam_typesdts=data['myarray'];
           $scope.examtypedts=$scope.exam_typesdts[$scope.exam_type_id];
         
 
           setTimeout(function(){
                 
                 $(".linechardiv").show();
                 angular.forEach($scope.examtypedts.exam_subs, function(subv, subk) {
              if(subv['sub_name']!=''){
                 linechart_fun(subv['sub_name'],subv['sub_id'],$scope.examtypedts[subv['sub_id']]['typstdmrks'],$scope.examtypedts[subv['sub_id']]['typtopmrks'],$scope.examtypedts[subv['sub_id']]['typminmrks'],$scope.examtypedts[subv['sub_id']]['exam_names']);
             }
      }); 
         $scope.loading = false;
       }, 1000);

          $scope.exam_types=data;
          $scope.loading = false;
          
 
          })
     } 

     $scope.get_examtypedts=function(exam_type_id){
        $scope.exam_typesdts={};
        $scope.exam_typesdts=$scope.orgexam_typesdts;
           $scope.examtypedts=$scope.exam_typesdts[exam_type_id];
           setTimeout(function(){
                 $(".linechardiv").show();
                 angular.forEach($scope.examtypedts.exam_subs, function(subv, subk) {
              if(subv['sub_name']!=''){
                 linechart_fun(subv['sub_name'],subv['sub_id'],$scope.examtypedts[subv['sub_id']]['typstdmrks'],$scope.examtypedts[subv['sub_id']]['typtopmrks'],$scope.examtypedts[subv['sub_id']]['typminmrks'],$scope.examtypedts[subv['sub_id']]['exam_names']);
             }
      }); 
         $scope.loading = false;
       }, 1000);
     }
 
 //linechart_fun();
              $scope.loading = true;
          $http.get('includes/api/exam_details.php?action=std_subwise_performnance&course_id='+$scope.course_id).success(function(data) { 
             $scope.exmtypedts=data['exmtypedts'];
              $scope.exam_type_id=$scope.exmtypedts[0]['exam_type_id'];
                
 $scope.orgexam_typesdts=data['myarray'];
            $scope.exam_typesdts=data['myarray'];
           $scope.examtypedts=$scope.exam_typesdts[$scope.exam_type_id];
         
 
           setTimeout(function(){
                 
                 $(".linechardiv").show();
                 angular.forEach($scope.examtypedts.exam_subs, function(subv, subk) {
             //    angular.forEach($scope.examtypedts, function(value, key) {
             if(subv['sub_name']!=''){
                 linechart_fun(subv['sub_name'],subv['sub_id'],$scope.examtypedts[subv['sub_id']]['typstdmrks'],$scope.examtypedts[subv['sub_id']]['typtopmrks'],$scope.examtypedts[subv['sub_id']]['typminmrks'],$scope.examtypedts[subv['sub_id']]['exam_names']);
             }
        // }); 
     }); 
         $scope.loading = false;
       }, 1000);
 
 
          $("#stdexam_div").show();
           $scope.loading = false;
          })
         
          function linechart_fun(subject,chartid,stdmrks,topmrks,avgmrks,exmname){//
           // alert(subject+'  '+chartid+'  '+stdmrks+'  '+topmrks+'  '+avgmrks+'  '+exmname);
            $(function() { 
             var options = {
                 chart: {
                     height: 300,
                     zoom: {
                         enabled: false
                     },
                 },
                 dataLabels: {
                     enabled: true
                 },
                 showInLegend: true,
                 stroke: {
                     width: [3, 3, 3],
                     curve: 'smooth'
                 },
                 colors: ["#0e9e4a", "#ffba57", "#ff5252"],
                 series: [{
                         name: "Class Topper(%)",
                         data: topmrks
                     },
                     {
                         name: "Student Marks(%)",
                         data: stdmrks
                     },
                     {
                         name: 'Class Average(%)',
                         data: avgmrks
                     }
                 ],
                 title: {
                     text: subject,
                     align: 'center'
                 },
                 markers: {
                     size: 0,
 
                     hover: {
                         sizeOffset: 6
                     }
                 },
                 xaxis: {
                     categories: exmname,
                 },                 
                 tooltip: {
                     y: [{
                         title: {
                             formatter: function(val) {
                                 return val 
                             }
                         },
                         pointStart: 0
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



 
     // alert(exam_type+'  '+chartid+'  '+stdmrks+'  '+topmrks+'  '+avgmrks+'  '+exmname);
 /*$(function() { 
     Highcharts.chart('chart-highchart-line'+chartid, {
             chart: {
                 type: 'spline',
             },
             colors: ['#00bcd4', '#4680ff', '#536dfe'],
             title: {
                 text: subject
             },
             subtitle: {
                 text: ' '
             },
             yAxis: {
                 title: {
                     text: 'Student MarksMarks'
                 }
             },
             plotOptions: {
                 series: {
                     label: {
                         connectorAllowed: true
                     },
                     pointStart: 2010
                 }
             },
             series: [{
                 name: 'Student Marks',
                 data: stdmrks
             }, {
                 name: 'Class Topper',
                 data: topmrks
             }, {
                 name: 'Class Average',
                 data: avgmrks
             }],
             responsive: {
                 rules: [{
                     condition: {
                         maxWidth: 500
                     },
                     chartOptions: {
                         legend: {
                             layout: 'horizontal',
                             align: 'center',
                             verticalAlign: 'bottom'
                         }
                     }
                 }]
             }
         });
         // [ line-basic-chart ] end
            });*/
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