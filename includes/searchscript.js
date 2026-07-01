$(document).ready(function(){   
						//   var tbody = document.getElementById("adminbody");

// This handler will be executed only once when the cursor
// moves over the unordered list
/*tbody.addEventListener("mouseenter", function( event ) { 
 $.ajax({	  
					  url:'../includes/ajax.php',  
					  type:'POST',  
					  data:{'action':'get_session_posssion'},
					 success:function(data)
					  {   
						 if(data>0) var sp='';
						 else location.replace("index.php?p=logout");
					  }
			});
}, false);*/
						   
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
$('.default-date-picker').datepicker({//alert('k');
					autoclose: true,
					format:"dd-mm-yyyy",
					todayHighlight: true
				});						   
});