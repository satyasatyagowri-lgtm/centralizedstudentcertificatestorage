<script>
	
 app.controller('monthly_collection', function($scope, $http) {		
 
		  $scope.branch_id='<?php echo $_SESSION['branch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })   


      $scope.getdate = function(){  
		 $scope.loading=true;
		  $(".monthlycolc").hide();
            $http.get("includes/api/finance_details.php?action=monthly_collection&branch_id="+$scope.branch_id+"&yearid="+$scope.yearid+"&month="+$scope.month)  
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
 app.controller('monthly_hostelexpense', function($scope, $http) {		
 
		  $scope.branch_id='<?php echo $_SESSION['branch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })   


      $scope.getdate = function(){  
		 $scope.loading=true;
		  $(".monthlycolc").hide();
            $http.get("includes/api/finance_details.php?action=monthly_headwiseexpense&branch_id="+$scope.branch_id+"&yearid="+$scope.yearid+"&month="+$scope.month)  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
                $scope.headhost_exp = data['hostexpdts'];
			 	$scope.exceltitle =data['extitle'];
				$scope.tot_field = data['Totfields'];  
				 $(".monthlycolc").show();
				$scope.loading = false;
           })   
      }
 });
 
  app.controller('opcashierwise_report', function($scope, $http) {	

var today = new Date(); var dd = today.getDate();  var mm = today.getMonth()+1;  var yyyy = today.getFullYear();
if(dd<10) 
{
    dd='0'+dd;
} 

if(mm<10) 
{
    mm='0'+mm;
} 
  today = dd+'-'+mm+'-'+yyyy;
  $scope.frm_date=today;
  $scope.to_date=today;
  //$scope.dat_range=today+' TO '+today;
         <?php /*?> $scope.line_id='<?php echo $_SESSION['angline_id'];?>';
            $http.get("includes/api/finance_details.php?action=line_dts")  
           .success(function(data){
                $scope.line_info = data;  
           })  
		<?php */?>   
		
		 $http.get('includes/api/customer_details.php?action=linedls').success(function(data) { 
 		$scope.linelist=data['myarray'];
		 $scope.search_line_id =$scope.linelist[0];
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})

		 $scope.loading=true;
		  $(".cashierreport").hide();
  /* $http.get("includes/api/finance_details.php?action=opcashierwise_reprot")  
           .success(function(data){
		      console.log(data);
			  // $scope.dt=data.split('^');
			  $scope.payment_types = data['payment_types'];
                $scope.fee_data = data['dailyfees'];
				 $scope.tdat=data['tdat'];
			 	$scope.exceltitle =data['extitle'];
				$scope.cancel_data = data['cancelfees'];  
				 $(".cashierreport").show();
				$scope.loading = false;

				var totptypsum=0;
                   angular.forEach($scope.payment_types, function(v, k) {
					totptypsum +=v['patypamt'];
				 });
				
				 $scope.totptypsums=totptypsum;
  				 
           })   */
	 // 
	 setTimeout(function(){
	 getdailycollectiondts();
	 }, 1000);
 	  $scope.get_dailycollection = function(){
		  getdailycollectiondts();
	  }


	function getdailycollectiondts(){
 		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=opcashierwise_reprot&line_id="+$scope.search_line_id.lineid+'&city_id='+$scope.search_city_id+"&frm_date="+$scope.frm_date+'&to_date='+$scope.to_date)  
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

				var totptypsum=0;
                   angular.forEach($scope.payment_types, function(v, k) {
					totptypsum +=v['patypamt'];
				 });
				
				 $scope.totptypsums=totptypsum;
           })   
      }
	  
	    $scope.get_confirmdel = function(id,refid){  
			if(confirm("Are you sure wants to delete "+$("#recname_"+id).html()+"...?")){
			  $http.get("includes/api/finance_details.php?action=cancel_cust_billbpay&fpid="+id)  
           .success(function(data){
			$scope.get_dailycollection();
		   });
			}
		//  $("#hid_recid").val(id);
		  //$("#hid_recno").val($("#recname_"+id).html());
 		}
 });

 app.controller('linewise_daily_transaction_details', function($scope, $http) {	 
  	var today = new Date(); var dd = today.getDate();  var mm = today.getMonth()+1;  var yyyy = today.getFullYear();
if(dd<10) 
{
    dd='0'+dd;
} 

if(mm<10) 
{
    mm='0'+mm;
} 
  today = dd+'-'+mm+'-'+yyyy;
 // $scope.dat_range=today+' TO '+today;
  $scope.frm_date=today;
  $scope.to_date=today;
 		/*$http.get('includes/api/customer_details.php?action=linewise_daily_transaction_details&dat_range='+$("#dateRange").val()+'&selline_id=<?php echo $_SESSION['selline_id'];?>').success(function(data) {
  			$scope.lineoveralview=data['lineoveralview'];
			$scope.usrwiseptypavlbal=data['userwiseptypwiseexistbal'];
			$scope.ptypes=data['ptypes'];
                             });*/
getdaylycolcdts();

function getdaylycolcdts(){
	 
$scope.loading=true;
		 // $(".linetransdts").hide();
		  $scope.linewisetransactins={};
           $http.get('includes/api/customer_details.php?action=linewise_daily_transaction_details&frm_date='+$scope.frm_date+'&to_date='+$scope.to_date+'&selline_id=<?php echo $_SESSION['selline_id'];?>').success(function(data) {
 		   $scope.lineoveralview=data['lineoveralview']; 
 			$scope.usrwiseptypavlbal=data['userwiseptypwiseexistbal'];
			$scope.custptypdts=data['custptypcolc'];
			$scope.custcolcusrarr=data['custcolcptypusrdtypdtsarr'];
			$scope.linecolcdt=data['lincolcamtdts'];

			$scope.lineadustmentamtarrs=data['lineadustmentamtarrs'];
			$scope.linecitywisecolcamts=data['linecitywisecolcamts'];
				var citywisegrdtot=0;
			angular.forEach($scope.linecitywisecolcamts, function(value, key) {
                citywisegrdtot=citywisegrdtot+parseFloat(value['citywisamt']);
			});
			$scope.citywisegrdtots=citywisegrdtot;

			$scope.linptypexpdts=data['lineptypexp'];
			$scope.expdtsusrtypptyparr=data['expdtsusrtypptyparr'];			
			$scope.lineexpdts=data['linexpdts'];

			$scope.linptyptakamts=data['linptyptakamt'];
			$scope.emptakamtptypusrtyparr=data['emptakamtptypusrtyparr'];
			$scope.lineemptakamts=data['linemptakamtdts'];

			$scope.linptypcusttakamts=data['lineptypcsusttakamt'];
			$scope.lincusttakamtptypusrtyparr=data['lincusttakamtptypusrtyparr'];

			$scope.linechitamtpaiddts=data['linchitpaiddts'];
			$scope.linechitamtpaidtotamt=data['linechitpaidtot'];

 			$scope.adjustment_incomdtsarr=data['adjustment_incomdtsarr'];
 			//$scope.lincusttakamts=data['lincusttakamtdts'];
			$scope.getcustomergenpayments=data['getcustomergenpayments'];
			var totcustbrowtakamt=0;var totcustbrointrestamt=0;
 				angular.forEach(data['getcustomergenpayments'], function(value, key) {
                totcustbrowtakamt=totcustbrowtakamt+parseFloat(value['total_amount']);
				totcustbrointrestamt=totcustbrointrestamt+parseFloat(value['intrestanddocucharge']);
             });
			 $scope.totTaken=totcustbrowtakamt;$scope.totInterest=totcustbrointrestamt;



			 $scope.grdcredits =(parseFloat($scope.lineoveralview.colc || 0)) +(parseFloat($scope.lineoveralview.emptak || 0))+(parseFloat($scope.lineoveralview.intrestanddocucharge || 0));
             $scope.grddebits=parseFloat($scope.lineoveralview['expamt']  || 0)+parseFloat($scope.lineoveralview['custborrow']  || 0);
 			$scope.ptypes=data['ptypes'];

			$scope.lineprevcolcrembal=data['previous_overalavlbal'];
			$scope.prevamts_frmlinepatypwisear=data['prevamts_frmlinepatypwisear'];
         $scope.loading=false;   
		// setTimeout(() => transliterateTable(), 500);
		//  setTimeout(() => translateuniNames(), 500);
 		   setTimeout(() => translateuniNamesnew(), 500);
		}); 
}
	
// Simple Telugu to Latin transliteration
const teluguMap = {
  'అ':'a','ఆ':'aa','ఇ':'i','ఈ':'ee','ఉ':'u','ఊ':'oo','ఋ':'ru','ఎ':'e','ఏ':'ae','ఐ':'ai','ఒ':'o','ఓ':'oo','ఔ':'au',
  'క':'ka','ఖ':'kha','గ':'ga','ఘ':'gha','ఙ':'nga',
  'చ':'cha','ఛ':'chha','జ':'ja','ఝ':'jha','ఞ':'nya',
  'ట':'ta','ఠ':'tha','డ':'da','ఢ':'dha','ణ':'na',
  'త':'ta','థ':'tha','ద':'da','ధ':'dha','న':'na',
  'ప':'pa','ఫ':'pha','బ':'ba','భ':'bha','మ':'ma',
  'య':'ya','ర':'ra','ల':'la','వ':'va','శ':'sha','ష':'ssa','స':'sa','హ':'ha','ళ':'la','క్ష':'ksha','ఱ':'ra',
  '్':'', 'ం':'m', 'ః':'h', 'ఁ':'n', 'ా':'a','ి':'i','ీ':'ee','ు':'u','ూ':'oo','ృ':'ru','ె':'e','ే':'ee','ై':'ai','ొ':'o','ో':'oo','ౌ':'au'
};

// Transliterate Telugu text to English
/*function transliterateTelugu(text) {
  let result = '';
  for (let char of text) {
    result += teluguMap[char] !== undefined ? teluguMap[char] : char;
  }
  return result;
}

// Update the table
function transliterateTable() {
  const rows = document.querySelectorAll(".exportArea tbody tr");
  rows.forEach(row => {
    if (!row.cells[1]) return;
    let cell = row.cells[1];
    let text = cell.innerText.trim();
    if (text && /[\u0C00-\u0C7F]/.test(text)) { // Telugu detection
      cell.innerText = transliterateTelugu(text);
    }
  });
}*/

async function translateuniNamesnew() {
  const rows = document.querySelectorAll(".adjclass tr ");
  let promises = [];

  rows.forEach(row => {
    const cells = row.cells; // get all cells

    for (let i = 0; i < cells.length; i++) {
      let cell = cells[i];
      let text = cell.innerText.trim();

      // Telugu detection
      if (text && /[\u0C00-\u0C7F]/.test(text)) {
        const promise = fetch(
          "https://translate.googleapis.com/translate_a/single?client=gtx&sl=te&tl=en&dt=t&q=" + encodeURIComponent(text)
        )
        .then(res => res.json())
        .then(data => {
          if (data && data[0] && data[0][0]) {
            let translated = data[0].map(t => t[0]).join(""); // full sentence
            cell.innerText = translated;
          }
        })
        .catch(err => console.log("Translation error:", err));

        promises.push(promise);
      }
    }
  });

  await Promise.all(promises);
  console.log("All Telugu text translated!");
}

	$scope.get_dailycollection = function(){ 
		   getdaylycolcdts();
      }
        });

 app.controller('linewise_daily_transactions', function($scope, $http) {	
	 		var today = new Date(); var dd = today.getDate();  var mm = today.getMonth()+1;  var yyyy = today.getFullYear();
if(dd<10) 
{
    dd='0'+dd;
} 

if(mm<10) 
{
    mm='0'+mm;
} 
 
  today = dd+'-'+mm+'-'+yyyy;
  $scope.dat_range=today+' TO '+today;
 		$http.get('includes/api/customer_details.php?action=linewise_daily_transaction').success(function(data) {
            $scope.linelist=data['linedts'];             
			$scope.scrchline_id=$scope.linelist[0]['line_id'];
 			$scope.linewisetransactins=data['linewisetransdt'];
                             })

			$scope.get_dailycollection = function(){  
			//	$scope.dat_range=today+' TO '+today;
		 $scope.loading=true;
		 // $(".linetransdts").hide();
		  $scope.linewisetransactins={};
            $http.get("includes/api/customer_details.php?action=linewise_daily_transaction&dat_range="+$(".default-date-picker").val())  
           .success(function(data){
		     $scope.linelist=data['linedts'];             
			$scope.scrchline_id=$scope.linelist[0]['line_id'];
 			$scope.linewisetransactins=data['linewisetransdt'];  
 				$scope.loading = false;

				
           })   
      }
        });


		 
 
 app.controller("feedueapi", function($scope, $http){  
 $http.get('includes/api/customer_details.php?action=linedls').success(function(data) {
		console.log(data);
		$scope.linelist=data['myarray'];
		 $scope.search_line_id =$scope.linelist[0];
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})
 
         $scope.dat='<?php echo $_SESSION['dt'];?>';
         $scope.loadlineid='0';
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
	 
	   
	    $scope.get_lines = function(i){  
	     if($scope.term!=''){
		     $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  getfeedueData();
	   $('.glass').fadeIn();
   $scope.loading=true;
		 }  
      } 
	  
	   $scope.get_citydts = function(i){  
	     if($scope.term!=''){
		     $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  getfeedueData();
	   $('.glass').fadeIn();
   $scope.loading=true;
		 }  
      }  
	  
	  $scope.get_dailycollection = function(i){ 
	  $scope.pageSize = 0;
	  $scope.fees = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	  getfeedueData();
	   $('.glass').fadeIn();
   $scope.loading=true;
    
	}  
	 function getfeedueData() { 
     $http.get("includes/api/finance_details.php?action=cust_fee_due&lineid="+$scope.search_line_id.lineid+"&city_id="+$scope.search_city_id+"&ret_tots="+$scope.ret_tots+"&tot_count="+$scope.totalItems+"&dat_range="+$scope.dat_range+'&limit=' +$scope.pageSize)
        .success(function(data) { 
            $scope.totalItems = data.totalCount;
			$scope.fee_ids=data.fee_ids;
			$scope.ret_tots=data.ret_tots;
			if($scope.pageSize==0){
			$scope.theads=data['fetch_heads'];
			}
             if (data.totalCount >$scope.pageSize) {
			$scope.pageSize=$scope.pageSize+1000;
			$scope.sub_tot=data.sub_tot;
            angular.forEach(data.feedue, function(temp){
                $scope.fees.push(temp);
				$(".feeduecolc").show();
             });
			getfeedueData();

			}
			else{//alert($scope.sub_tot);
			$scope.loading=false;
			$('.glass').fadeOut();	
			$scope.sub_tot=$scope.sub_tot;
			  $scope.exceltitle = data.extitle; 
			}
        });
    }
	  
	});
 
 
	 app.controller("duelist_uptodate", function($scope, $http){  

var today = new Date(); var dd = today.getDate();  var mm = today.getMonth()+1;  var yyyy = today.getFullYear();
if(dd<10) 
{
    dd='0'+dd;
} 

if(mm<10) 
{
    mm='0'+mm;
} 
  today = dd+'-'+mm+'-'+yyyy;

 $http.get('includes/api/customer_details.php?action=getall_lines').success(function(data) {
		$scope.linelist=data;
		// $scope.search_line_id =$scope.linelist[0];
		//$scope.courseid = $scope.selctedbranch.course[3];
 		})
 //alert($scope.search_line_id.lineid+' '+$scope.search_city_id);
         $scope.dat=today;
	
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
	 
	   
	    $scope.get_lines = function(i){  
		     $scope.pageSize = 0;
	  $scope.duedts = [];
	  $scope.orgduedts = [];
	  $scope.fee_ids='';
	  $scope.totalItems=0;
	  $scope.ret_tots='';
	 
	  $scope.linid=$scope.lineid;
	  $scope.cityid=$scope.search_city_id;
	  getfeedueData($scope.linid,$scope.cityid);
	   $('.glass').fadeIn();
   $scope.loading=true;
      } 
	  
	   $scope.get_lineweekcitys = function(i){   
		$scope.loading=true;
 		    $scope.linewekctiydtsarr=$scope.linectiydtsarr[$scope.linid][$scope.week_id];
			if(i>0)
			$scope.duedts= $scope.orgduedts.filter(function(item) {//alert(item.user_status);
  return item.week_id ===i;
  });
   else $scope.duedts= $scope.orgduedts;
   
  $scope.weekwisduedts=$scope.duedts;
 
$scope.subtotdts($scope.duedts);
 			$scope.city_id=$scope.linewekctiydtsarr[0]['city_id'];
   
      }  
	  
$scope.get_lineweekcityolc=function(i){
	if(i>0)
   $scope.duedts= $scope.weekwisduedts.filter(function(item) {//alert(item.user_status);
  return item.city_id ===i;
   });
   else $scope.duedts= $scope.weekwisduedts;

   $scope.subtotdts($scope.duedts);
}

	  $scope.get_datetodue = function(i){ 
	  $scope.lineid = '';
	  $scope.duedts = [];
	  $scope.orgduedts = [];
     
	}  

	$scope.subtotdts=function(subotodts){console.log(subotodts);
      var tsubtot=0;var torg=0;var tconcamt=0;var twekdue=0;var tmekpabledue=0;var tpaid=0;var trembal=0;
angular.forEach(subotodts, function(value, key) {
	//alert(value['org_amount']);
			torg=torg+parseFloat(value['org_amount']);			
			tconcamt=tconcamt+parseFloat(value['conces']);
			twekdue=twekdue+parseFloat(value['term_due']);
            tmekpabledue=tmekpabledue+parseFloat(value['monthly_amount']);
			tpaid=tpaid+parseFloat(value['term_due']);
			trembal=trembal+parseFloat(value['remain_balance']);			
});

		$scope.tot_org=torg;
			$scope.tot_conces=tconcamt;
			$scope.tot_wekdue=twekdue;
			$scope.tot_weekpabledue=tmekpabledue;
			$scope.tot_tpaid=tpaid;
			$scope.tot_bal=trembal; 

			 $scope.loading=false;
	}
	 function getfeedueData(l,c) {//alert(l+' '+c);
	 //alert($scope.search_line_id.lineid+' '+$scope.search_city_id);
     $http.get("includes/api/finance_details.php?action=cust_pendingupto_date&lineid="+l+"&city_id="+c+"&week_dts="+angular.toJson($scope.lineweekarr)+"&linectiydtsarr="+angular.toJson($scope.linectiydtsarr)+"&tot_count="+$scope.totalItems+"&dat_range="+$scope.dat+'&limit=' +$scope.pageSize)
        .success(function(data) {console.log(data);//alert(data);
            $scope.totalItems = data.totalCount;
			$scope.lineweekarr = data.lineweekarr;
			//alert(data.lineweekarr[0]['week_id']);
			$scope.week_id =0;
			$scope.linectiydtsarr = data.linectiydtsarr;
  			if($scope.pageSize==0){
			$scope.theads=data['fetch_heads'];
			}
            if (data.totalCount >$scope.pageSize) {
			$scope.pageSize=$scope.pageSize+1000;
			$scope.sub_tot=data.sub_tot;
 			$scope.tot_custamt=data.tot_custamt;
			$scope.tot_conces=data.tot_conces;
			$scope.tot_tpaid=data.tot_tpaid;
			$scope.tot_monweekdue=data.tot_monweekdue;
            angular.forEach(data.feedue, function(temp){
				$scope.orgduedts.push(temp);
                $scope.duedts.push(temp);
				$(".feeduecolc").show();
            });
			getfeedueData(l,c);
			}
			else{//alert($scope.sub_tot);
			$scope.loading=false;
			$('.glass').fadeOut();	
			$scope.subtotdts($scope.duedts);
			/*$scope.sub_tot=$scope.sub_tot;
			$scope.tot_org=$scope.tot_org;
			$scope.tot_custamt=$scope.tot_custamt;
			$scope.tot_conces=$scope.tot_conces;
			$scope.tot_tpaid=$scope.tot_tpaid;
			$scope.tot_monweekdue=$scope.tot_monweekdue;*/ 
			  $scope.exceltitle = data.extitle; 
			}
        });
    }
	  
	});
	
	app.controller('datewise_usrwise_paywisecolc', function($scope, $http) {		
          $scope.branch_id='<?php echo $_SESSION['angbranch_id'];?>';
            $http.get("includes/api/finance_details.php?action=branch_dts")  
           .success(function(data){
                $scope.branch_info = data;  
           })  
		   
		   $scope.course_id='<?php echo $_SESSION['angcourse_id'];?>';
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+$scope.branch_id)  
           .success(function(data){
                $scope.branch_courses = data;  
				
           })  
		   $scope.getbranchid = function(i){  
            $http.get("includes/api/finance_details.php?action=get_branch_courses&branch_id="+i)  
           .success(function(data){
                $scope.branch_courses = data;  
           }) 
		   } 

		 $scope.loading=true;
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=datewise_userwise_paywisecollection&branch_id="+$scope.branch_id+"&dat_range="+$("#dateRange").val())  
           .success(function(data){
			  // $scope.dt=data.split('^');
			 $scope.fee_names = data['fee_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			 	$scope.exceltitle =data['extitle'];
			
				$scope.all_grdtot = data['all_grdtot'];
				
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
	  
	  
	  $scope.get_dailycollection = function(){  
		 $scope.loading=true; 
		  $(".cashierreport").hide();
            $http.get("includes/api/finance_details.php?action=datewise_userwise_paywisecollection&branch_id="+$scope.branch_id+"&dat_range="+$scope.dat_range+"&course_id="+$scope.course_id)  
           .success(function(data){
			  // $scope.dt=data.split('^');
			  $scope.fee_names = data['fee_names'];
                $scope.fee_data = data['payment_amts'];
				$scope.grdtotal = data['grdtotal'];
			
				$scope.all_grdtot = data['all_grdtot'];
				
			 	$scope.exceltitle =data['extitle'];
				 $(".cashierreport").show();
				$scope.loading = false;
           })   
      }
 });
		   
</script>