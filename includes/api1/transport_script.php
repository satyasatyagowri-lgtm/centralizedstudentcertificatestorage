	<script>
 
 app.controller('transport_details', function($scope, $http) {		
		$scope.get_stopdts = function(i){ // alert(i);
		  $(".stopdts").html('');
            $http.get("../includes/api/transport_details.php?action=stop_dts&route_id="+i)  
           .success(function(data){
                $scope.busstop_dts = data;  
           })   
      }

 });
</script>