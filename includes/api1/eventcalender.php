	<script>
 
app.controller('subject_schedule_calenderdts', function($scope, $http) { 
    $scope.stdcourse_id='<?php  echo $std_dts[$_SESSION['std_id']]['std_course'];?>';
    $scope.stdbranch_id='<?php  echo $std_dts[$_SESSION['std_id']]['branch_id'];?>';
      $scope.loading = true;
      var today = new Date();var mm = today.getMonth()+1;
      if(mm<10) 
       {
        mm='0'+mm;
      }
      var colorarrs=['#04a9f5','#f44236','#f4c22b','#3ebfea','#1de9b6','#a389d4'];
      $http.get('includes/api/eventdetails.php?action=month_dts&std_courseid='+$scope.stdcourse_id+'&std_branchid='+$scope.stdbranch_id).success(function(data) {
        $scope.monthdts=data['monthdts'];
        $scope.subdts=data['course_subjs'];
        $scope.month_num=mm;
        <?php if($_REQUEST['sub_id']!=''){?>
            $scope.sub_id='<?php echo $_REQUEST['sub_id'];?>';
        <?php }else{?>
            $scope.sub_id=$scope.subdts[0]['sub_id'];
        <?php }?>
               get_subjectdetails($scope.sub_id,$scope.month_num);
       
 		
        /*    var evnetsdata=[{
                title: 'All Day Event<a onclick="get_event(1);">Get</a>',
                start: '2018-08-01',
                borderColor: '#04a9f5',
                backgroundColor: '#04a9f5',
                textColor: '#fff'
            }, {
                title: 'Long Event',
                start: '2018-08-07',
                end: '2018-08-10',
                borderColor: '#f44236',
                backgroundColor: '#f44236',
                textColor: '#fff'
            }, {
                id: 999,
                title: 'Repeating Event',
                start: '2018-08-09T16:00:00',
                borderColor: '#f4c22b',
                backgroundColor: '#f4c22b',
                textColor: '#fff'
            }, {
                id: 999,
                title: 'Repeating Event',
                start: '2018-08-16T16:00:00',
                borderColor: '#3ebfea',
                backgroundColor: '#3ebfea',
                textColor: '#fff'
            }, {
                title: 'Conference',
                start: '2018-08-11',
                end: '2018-08-13',
                description:'Hellwo world',
                borderColor: '#1de9b6',
                backgroundColor: '#1de9b6',
                textColor: '#fff'
            }, {
                title: 'Lunch',
                start: '2018-08-12T12:00:00',
                borderColor: '#f44236',
                backgroundColor: '#f44236',
                textColor: '#fff'
            }, {
                title: 'Meeting',
                start: '2018-08-12T10:30:00',
                end: '2018-08-12T12:30:00'
            }, {
                title: 'Happy Hour',
                start: '2018-08-12T17:30:00',
                borderColor: '#a389d4',
                backgroundColor: '#a389d4',
                textColor: '#fff'
            }, {
                title: 'Birthday Party',
                start: '2018-08-13T07:00:00'
            }, {
                title: 'Click for Google',
                url: 'http://google.com/',
                start: '2018-08-28',
                borderColor: '#a389d4',
                backgroundColor: '#a389d4',
                textColor: '#fff'
            }];

           // $scope.evnetsdata

 			 eventcalenderdts(evnetsdata);*/
		 $scope.loading = false;
 		})

        function get_subjectdetails(sub_id,month_num){
            $http.get('includes/api/eventdetails.php?action=subsyllabus_details&month_num='+month_num+'&sub_id='+sub_id+'&stdcourse_id='+$scope.stdcourse_id+'&stdbranch_id='+$scope.stdbranch_id).success(function(data) { 
              console.log(data); $("#hiddt").val(data['seldt']);
                var evnetsdata=[];
           /* var evnetsdata=[{
id: 6,
title: "SAKUNUNTALOPAKYANAM",
start: "2023-06-24",
end: "2023-06-26",
borderColor: "#04a9f5",
backgroundColor: "#04a9f5",
textColor: "#fff"
},
{
    id: 7,
    title: "LAGUVU GURUVULU",
    start: "2023-06-23",
    end: "0000-00-00",
    borderColor: "#f44236",
    backgroundColor: "#f44236",
    textColor: "#fff"
    }];*/
      $scope.subsyllabusdts=data['syllabus_schedules'];

         var colorky=0;
            angular.forEach(data['syllabus_schedules'], function(value, key) {
                evnetsdata.push({id:parseInt(value['id']),title:value['topic'],start:value['sdt'],end:value['edt'],borderColor:colorarrs[colorky],backgroundColor:colorarrs[colorky],textColor:'#fff'});
              colorky++;
              if(colorarrs.length==colorky)
              colorky=0;
        });
        //$(".fc-header-toolbar").hide();
        $(function() {
            eventcalenderdts(evnetsdata,data['seldt']);
        });
       
    });
        }
		

        $scope.get_subjectdts=function(subid){
              location.href='home.php?p=subject_schedule_calender&sub_id='+subid;
        }
        $scope.get_monthnum=function(month_num){//alert(month_num);
            $("#hiddt").val('2023-'+month_num+'-24');
             $('#calendar').fullCalendar({});
            get_subjectdetails($scope.sub_id,month_num);
        }

		/*function get_notify(id){alert(id);
            var subky= $scope.subdts.findIndex(object => object.id === id);
            $scope.syllabus_date=$scope.subdts[subky]['topic_date'];
	        $scope.syllabus_topic=$scope.subdts[subky]['topic'];
            $scope.syllabus_description=$scope.subdts[subky]['description'];
            $scope.syllabus_datsyllabus_homeworke=$scope.subdts[subky]['homework_description'];
           
	} */
    
       function eventcalenderdts(evnetsdata,select_dt)
      { // alert(evnetsdata+'  '+select_dt);
        $("#syllabus_detailsview").show();
	 $(function() {
       // $(".fc-header-toolbar").hide();
        $('#external-events .fc-event').each(function() {
            $(this).data('event', {
                title: $.trim($(this).text()),
                stick: true
            });
            $(this).draggable({
                zIndex: 999,
                revert: true,
                revertDuration: 0
            });
        });
       // $(".fc-header-toolbar").hide();
        $('#calendar').fullCalendar({
            
          /*  header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },*/
            defaultDate: select_dt,
            editable: false,
            droppable: false,
            events: evnetsdata,
            drop: function() {
                if ($('#drop-remove').is(':checked')) {
                    $(this).remove();
                }
            }
        });
		 });
		}
		
		
		
});












app.controller('event_schedule_calenderdts', function($scope, $http) { 
    $scope.stdcourse_id='<?php  echo $std_dts[$_SESSION['std_id']]['std_course'];?>';
    $scope.stdbranch_id='<?php  echo $std_dts[$_SESSION['std_id']]['branch_id'];?>';
      $scope.loading = true;
      var today = new Date();var mm = today.getMonth()+1;
      if(mm<10) 
       {
        mm='0'+mm;
      }
      var colorarrs=['#04a9f5','#f44236','#f4c22b','#3ebfea','#1de9b6','#a389d4'];
      
            $http.get('includes/api/eventdetails.php?action=eventschedule_details&branch_id='+$scope.stdbranch_id).success(function(data) { 
                var evnetsdata=[];
         
      $scope.subsyllabusdts=data['event_schedules'];

         var colorky=0;
            angular.forEach(data['event_schedules'], function(value, key) {
                evnetsdata.push({id:parseInt(value['id']),title:value['event_title'],start:value['sdt'],end:value['edt'],borderColor:colorarrs[colorky],backgroundColor:colorarrs[colorky],textColor:'#fff'});
              colorky++;
              if(colorarrs.length==colorky)
              colorky=0;
        });
        //$(".fc-header-toolbar").hide();
        $(function() {
            eventcalenderdts(evnetsdata,data['seldt']);
        });
       
    });
		
    
    function eventcalenderdts(evnetsdata,select_dt)
      { // alert(evnetsdata+'  '+select_dt);
        $("#syllabus_detailsview").show();
	 $(function() {
        $('#external-events .fc-event').each(function() {
            $(this).data('event', {
                title: $.trim($(this).text()),
                stick: true
            });
            $(this).draggable({
                zIndex: 999,
                revert: true,
                revertDuration: 0
            });
        });
        $('#calendar').fullCalendar({
         
            defaultDate: select_dt,
            editable: false,
            droppable: false,
            events: evnetsdata,
            drop: function() {
                if ($('#drop-remove').is(':checked')) {
                    $(this).remove();
                }
            }
        });
		 });
		}
		
		
		
});

</script>