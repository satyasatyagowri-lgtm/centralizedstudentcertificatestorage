<script>
 app.controller('monthly_collection', function($scope, $http) {		
 
		  $scope.branch_id='<?php echo $_SESSION['branch_id'];?>';
            $http.get("../includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })   


      $scope.getdate = function(){  
		 $scope.loading=true;
		  $(".monthlycolc").hide();
            $http.get("../includes/api/finance_details.php?action=monthly_collection&branch_id="+$scope.branch_id+"&yearid="+$scope.yearid+"&month="+$scope.month)  
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
 
 
  app.controller('opcashierwise_report', function($scope, $http) {		
          $scope.dat_range='<?php echo $_SESSION['dt'];?>';
         $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
            $http.get("../includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   $scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
            $http.get("../includes/api/finance_details.php?action=get_branch_courses&branch_id="+$scope.branch_id)  
           .success(function(data){
                $scope.branch_courses = data;  
				
           })  
		   $scope.getbranchid = function(i){  
            $http.get("../includes/api/finance_details.php?action=get_branch_courses&branch_id="+i)  
           .success(function(data){
                $scope.branch_courses = data;  
           }) 
		   } 

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("../includes/api/finance_details.php?action=opcashierwise_reprot")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
			 	$scope.exceltitle =data['extitle'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("../includes/api/finance_details.php?action=opcashierwise_reprot&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
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
           })   
      }
 });
 
 app.controller("feedueapi", function($scope, $http){  
           $http.get("../includes/api/finance_details.php?action=get_branch_sections_terms")  
           .success(function(data){ 
		   		console.log(data); 
				$scope.branchiddefault='<?php echo $_SESSION['branch_id'];?>';
                $scope.branchlist=data['branchlist'];
                $scope.branchseclist = data['seclist']; 
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
           $http.get("../includes/api/finance_details.php?action=get_branch_sections_terms&branch_id="+i)  
           .success(function(data){ 
		   		console.log(data); 
              //  $scope.branchlist=data['branchlist'];
                $scope.branchseclist = data['seclist']; 
				$scope.termlist=data['termdts']; 
				$scope.loading = false; 
				$('.glass').fadeOut();
				$(".feeduecolc").show();
           })  
      }  
	   
	    $scope.loadSections = function(i){  
	     if($scope.term!=''){
		 $(".feeduecolc").hide();
		  $('.glass').fadeIn();	
		 $scope.loading=true;
           $http.get("../includes/api/finance_details.php?action=std_fee_due&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&sec_id="+$scope.section)  
           .success(function(data){ 
		   		console.log(data); 
                $scope.fees = data['feedue'];
				$scope.exceltitle = data['extitle'];  
				$scope.loading = false; 
				$('.glass').fadeOut();
				$(".feeduecolc").show();
           })
		   }  
      }  
	  
	  $scope.loadTerms = function(i){  
	   $('.glass').fadeIn();	
	  $scope.loading=true;
	  $(".feeduecolc").hide();
           $http.get("../includes/api/finance_details.php?action=std_fee_due&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&sec_id="+$scope.section)  
           .success(function(data){ 
		   		console.log(data); 
               $scope.fees = data['feedue'];
				$scope.exceltitle = data['extitle'];
			   $scope.loading = false; 
			   $('.glass').fadeOut();	
			   $(".feeduecolc").show(); 
           })  
      } 
	  
	  
	  
	  
	   $scope.loadsmsSections = function(i){  
	     if($scope.term!=''){
		 $(".feeduecolc").hide();
		  $('.glass').fadeIn();	
		 $scope.loading=true;
           $http.get("../includes/api/finance_details.php?action=std_fee_duesms&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&sec_id="+$scope.section)  
           .success(function(data){ 
		   		console.log(data); 
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
           $http.get("../includes/api/finance_details.php?action=std_fee_duesms&id="+i+"&branchid="+$scope.branchiddefault+"&terms="+$scope.term+"&sec_id="+$scope.section)  
           .success(function(data){ 
		   		console.log(data); 
               $scope.fees = data;
			   $scope.loading = false; 
			   $('.glass').fadeOut();	
			   $(".feeduecolc").show(); 
           })  
      } 
	
 
 });
 
 
  app.controller("management_dashboard", function($scope, $http){  
           $http.get("../includes/api/finance_details.php?action=get_managent_dashboard")  
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
           $http.get("../includes/api/finance_details.php?action=income_details")  
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
           $http.get("../includes/api/finance_details.php?action=coursewise_income&branch_id="+i)  
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
           $http.get("../includes/api/finance_details.php?action=get_conces_courses")  
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
           $http.get("../includes/api/finance_details.php?action=get_conces_courses&branch_id="+i)  
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
           $http.get("../includes/api/finance_details.php?action=get_coursesconces_dts&branch_id="+$scope.branchiddefault+"&course_id="+$scope.course_id)  
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
</script>