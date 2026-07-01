<script>
		jQuery(function($) {
				//initiate dataTables plugin
				var oTable1 = 
				$('#editable-sample')
				.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					bAutoWidth: false,
					/*"aoColumns": [
					  { "bSortable": false },
					  null, null,null, null, null,
					  { "bSortable": false }*/
					  "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
					],
					"aaSorting": [],
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
				     "sScrollX": "100%",
					//"sScrollXInner": "120%"
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50
					 "aLengthMenu": [
                    [-1, 20, 50, 100],
                    ["All", 20, 50, 100] // change per page values here
                ],
					"iDisplayLength": -1,
			    });
				
				
				$('#editable1-sample')
				.DataTable( {
					bAutoWidth: false,
					/*"aoColumns": [
					  { "bSortable": false },
					  null, null,null, null, null,
					  { "bSortable": false }*/
					  "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
					],
					"aaSorting": [],
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
				     "sScrollX": "100%",
					//"sScrollXInner": "120%"
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50
					 "aLengthMenu": [
                    [-1, 20, 50, 100],
                    ["All", 20, 50, 100] // change per page values here
                ],
					"iDisplayLength": -1,
			    });
				$('#daterange-btn').daterangepicker({
					'applyClass' : 'btn-sm btn-success',
					'cancelClass' : 'btn-sm btn-default',
					'format':'YYYY-MM-DD',
					'separator':' TO ',
					locale: {
						applyLabel: 'Apply',
						cancelLabel: 'Cancel',
					}
				});
						
				$('.input-daterange').datepicker({autoclose:true,
												 format:"dd-mm-yyyy"
												 });
				
				$('.default-date-picker').datepicker({
					autoclose: true,
					format:"dd-mm-yyyy",
					todayHighlight: true
				})
				
				$(".select2").select2();
				$("[data-mask]").inputmask();
				
				var characters = $("#max_chars").val();
    $("#counter").append("You have <strong>"+  characters+"</strong> characters remaining");
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
	
	var $overflow = '';
	var colorbox_params = {
		rel: 'colorbox',
		reposition:true,
		scalePhotos:true,
		scrolling:false,
		previous:'<i class="ace-icon fa fa-arrow-left"></i>',
		next:'<i class="ace-icon fa fa-arrow-right"></i>',
		close:'&times;',
		current:'{current} of {total}',
		maxWidth:'100%',
		maxHeight:'100%',
		onOpen:function(){
			$overflow = document.body.style.overflow;
			document.body.style.overflow = 'hidden';
		},
		onClosed:function(){
			document.body.style.overflow = $overflow;
		},
		onComplete:function(){
			$.colorbox.resize();
		}
	};

	$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
	$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");//let's add a custom loading icon
	
	
	$(document).one('ajaxloadstart.page', function(e) {
		$('#colorbox, #cboxOverlay').remove();
   });
				
				});
				
</script>		
				
				
   
	