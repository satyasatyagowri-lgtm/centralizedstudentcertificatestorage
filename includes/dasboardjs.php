<script>
$(window).on("load", function () {
 // line chart widget 1 configuration Starts
  $('#sorting')
				.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.dataTable( {
					bAutoWidth: false,
					  "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
					],
					"aaSorting": [],
				     "sScrollX": "100%",
					 "aLengthMenu": [
                    [-1, 20, 50, 100],
                    ["All", 20, 50, 100] // change per page values here
                ],
					"iDisplayLength": -1,
			    });

				
 
 
//Calender
$('.default-date-picker').datepicker({
					autoclose: true,
					format:"dd-mm-yyyy",
					todayHighlight: true
				})
				
				
				$('#daterange-btn').daterangepicker({
					'applyClass' : 'btn-sm btn-success',
					'cancelClass' : 'btn-sm btn-default',
					'format':'YYYY-MM-DD',
					'separator':' TO ',
					autoclose: true,
					locale: {
						applyLabel: 'Apply',
						cancelLabel: 'Cancel',
					}
					
				});
				
				 $('.timepicker').timepicker({
					minuteStep: 1,
					showSeconds: true,
					showMeridian: true
				}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				}); 



   var characters = $("#max_chars").val();
    $("#counter").append("You have <strong>"+  characters+"</strong> characters remaining");
	//alert(characters);
    $("#messag").keyup(function(){
        if($(this).val().length > characters){
       // $(this).val($(this).val().substr(0, characters));
        }
    var remaining = characters -  $(this).val().length;
    $("#counter").html("You have <strong>"+  remaining+"</strong> characters remaining");
    if(remaining <= 10)
    {
        $("#counter").css("color","red");
    }
    else
    {
        $("#counter").css("color","black");
    }
    });
    // bar Chart Ends




});
</script>