<?php defined('ACCESS_SUBFILES') or die('Restricted access');

			$page_url="home.php?p=linewise_dailytransactionoverview";

			 if($_REQUEST['branch_id']==''){
			  $brn_id=$_SESSION['assign_branch_ids'];
			  $selbrnid=$_SESSION['branch_id'];
			  }
			 else {
			 $brn_id=$_REQUEST['branch_id'];
			 $selbrnid=$_REQUEST['branch_id'];
			 }
				
	          $branch_name=$obj_db->fetchRow("select branch_short_name,branch_city,branch_name from ".TABLE_BRANCH." where branch_id='".$_SESSION['branch_id']."'");
			   $linedts=$obj_db->fetchRow("select * from ".TABLE_LINE_NAMES." where line_id='".$_REQUEST['selline_id']."'");
		      $branch_name_city=$branch_name['branch_short_name'].'-'.$branch_name['branch_city'];
	
	$_SESSION['selline_id']=$_REQUEST['selline_id'];
	?>		

  <script>
   function getdate()
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&dt='+dt+'&branch_id='+$("#branch_id").val()+'&course_id='+$("#course_id").val();
	}
	function getcourseid(id)
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&dt='+dt+'&branch_id='+$("#branch_id").val()+'&course_id='+$("#course_id").val();
	}
	function get_branches(id)
    { var dt=$("#daterange-btn").val();
	 location.href='<?php echo $page_url;?>&dt='+dt+'&branch_id='+$("#branch_id").val();
	}
  </script>
    
<?php		

			
  $explode_dt=explode("TO", $_GET['dt']); 
	//echo  $explode_dt[0];
 $dt1= $explode_dt[0];
 $dt2= $explode_dt[1];
// echo date('Y-d-m');
 if($_GET['dt']==''){
 $dt1=date('d-m-Y');
 $dt2=date('d-m-Y');
 }
 
 if($_GET['dt']==''){
 $dt=date('d-m-Y').' TO '.date('d-m-Y');
 }
 else {
  $dt=$_GET['dt'];
 }
 $_SESSION['dt']=$dt;
?>			
<script type="text/javascript">
function printTable(obj) {
$(".hide_inprint").remove();
content = document.getElementById(obj).innerHTML;
newwin = window.open('');
newwin.document.write('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"\n',
'"http://www.w3.org/TR/html4/strict.dtd">\n',
'<html>\n',
'<head>\n',
'<title>Printing...</title>\n',
'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">\n',
'<body style="font:12px Arial, Helvetica, sans-serif;">\n',
''+content+'\n',
'</body>\n',
'</html>');
newwin.print();
newwin.close();
}

</script>



<style>
  

  .calculator {
    width: 600px;
    margin: auto;
    border: 1px solid #ccc;
    background-color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .header {
    background-color: #8bc34a;
    color: #3a4a24;
    font-size: 24px;
    font-weight: bold;
    padding: 15px;
  }

  .payment {
    background-color: #689f38;
    color: white;
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    padding: 15px 10px;
  }

  .section {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid #ccc;
  }

  .section div {
    width: 50%;
  }

  .box {
    background-color: #e0f2f1;
    border-right: 1px solid #ccc;
  }

  .box2 {
    background-color: #0097a7;
    color: white;
  }

  .box h3, .box2 h3 {
    background-color: #0288d1;
    color: white;
    margin: 0;
    padding: 10px;
    font-size: 16px;
  }

  .box table, .box2 table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }

  .box td, .box2 td {
    padding: 8px 10px;
    border-bottom: 1px solid #ddd;
  }

  .box td:nth-child(2),
  .box2 td:nth-child(2) {
    text-align: right;
  }

</style>

<script>
/* ---------- PDF Download ---------- */
function downloadPDFss() { const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation: "portrait", unit: "pt", format: "a4" });
  const div = document.getElementById("calculator");
  
  // Title
  doc.setFontSize(14);
  doc.text("Line Summary Report", 40, 40);

  // Convert each table in div
  const tables = div.getElementsByTagName("table");
  let currentY = 70;

  for (let i = 0; i < tables.length; i++) {
    doc.autoTable({
      html: tables[i],
      startY: currentY,
      theme: "grid",
      styles: { fontSize: 10, cellPadding: 4 },
      headStyles: { fillColor: [0, 102, 204] }
    });
    currentY = doc.lastAutoTable.finalY + 20;
  }

  // Capture text summary
  const textElements = div.querySelectorAll("h3, .footer-note, .payment");
  textElements.forEach(el => {
    const txt = el.innerText.trim();
    if (txt) {
      doc.text(txt, 40, currentY);
      currentY += 20;
    }
  });

  
  doc.save("line_summary.pdf");
}

   function downloadPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation: "portrait", unit: "pt", format: "a4" });
  const div = document.getElementById("calculator");
  const margin = 40;
  let currentY = margin + 20;

  // ===== Title =====
  doc.setFont("helvetica", "bold");
  doc.setFontSize(16);
  doc.setTextColor(0, 51, 102);
  doc.text("Line Summary Report", margin, currentY);
  currentY += 30;

  // ===== Payments Row =====
  const payments = div.querySelectorAll(".payment");
  doc.setFontSize(11);
  doc.setFont("helvetica", "normal");
  doc.setTextColor(0, 0, 0);

  payments.forEach((el) => {
    doc.text(el.innerText.trim(), margin, currentY);
    currentY += 18;
  });

  currentY += 10;

  // ===== Tables =====
  const tables = div.getElementsByTagName("table");
  for (let i = 0; i < tables.length; i++) {
    const sectionTitle = tables[i].closest(".box")?.querySelector("h3")?.innerText || "";
    if (sectionTitle) {
      doc.setFont("helvetica", "bold");
      doc.setFontSize(13);
      doc.setTextColor(0, 102, 204);
      doc.text(sectionTitle, margin, currentY);
      currentY += 15;
    }

    doc.autoTable({
      html: tables[i],
      startY: currentY,
      theme: "grid",
      tableWidth: "auto",
      styles: {
        fontSize: 10,
        cellPadding: 6,
        textColor: [0, 0, 0],
        halign: "left",
        valign: "middle",
        lineWidth: 0.2,
        lineColor: [100, 100, 100],
      },
      headStyles: {
        fillColor: [0, 102, 204],
        textColor: 255,
        fontStyle: "bold",
      },
      alternateRowStyles: { fillColor: [245, 245, 245] },
    });

    currentY = doc.lastAutoTable.finalY + 20;

    // ===== Footer notes below each table =====
    const notes = tables[i].closest(".box").querySelectorAll(".footer-note");
    notes.forEach((n) => {
      const txt = n.innerText.trim();
      if (txt) {
        if (currentY > doc.internal.pageSize.getHeight() - 60) {
          doc.addPage();
          currentY = margin;
        }
        doc.setFontSize(10);
        doc.setFont("helvetica", "italic");
        doc.setTextColor(80, 80, 80);
        doc.text(txt, margin, currentY, { maxWidth: 500 });
        currentY += 18;
      }
    });

    // ===== Closing Balance =====
    const closing = tables[i].closest(".box").querySelector("div[style*='Closing Balance']");
    if (closing) {
      if (currentY > doc.internal.pageSize.getHeight() - 60) {
        doc.addPage();
        currentY = margin;
      }
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.setTextColor(38, 6, 83); // purple
      doc.text(closing.innerText.trim(), margin, currentY);
      currentY += 25;
    }
  }

  // ===== Save PDF =====
  doc.save("line_summary.pdf");
}

/* ---------- Excel Download ---------- */
function downloadExcel() {
  var div = document.getElementById("exportContent");
  var tables = div.getElementsByTagName("table");
  if (tables.length === 0) {
    alert("No tables found to export!");
    return;
  }
  var wb = XLSX.utils.book_new();

  // Add each table as a sheet
  Array.from(tables).forEach((table, i) => {
    var ws = XLSX.utils.table_to_sheet(table);
    XLSX.utils.book_append_sheet(wb, ws, "Sheet" + (i + 1));
  });

  XLSX.writeFile(wb, "customer_report.xlsx");
}

/* ---------- CSV Download ---------- */
function downloadCSV() {
  var table = document.querySelector("#exportContent table");
  if (!table) {
    alert("No table found for CSV export!");
    return;
  }

  var rows = Array.from(table.querySelectorAll("tr"));
  var csv = rows.map(row => {
    var cols = Array.from(row.querySelectorAll("th, td"));
    return cols.map(col => `"${col.innerText}"`).join(",");
  }).join("\n");

  var blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  var link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "customer_report.csv";
  link.click();
}
</script>
<div class="tab-header">
	linewise Transactions Overview
  <div style="display: <?php if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='management'){?> block; <?php }else{?> none; <?php }?>">
	<div class="action-icons">
               <button id="exportPDF" class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
             </div>

             <div class="action-dropdown dropdown">
              <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-download"></i>
              </button>
            <ul class="dropdown-menu dropdown-menu-end">
  

    <li id="exportPDF">
        <a class="dropdown-item text-danger">
            <i class="fa-solid fa-file-pdf me-2"></i> PDF
        </a>
    </li>
</ul>

            </div>
</div>
</div>
<div class="tab-content">
<div class="row" ng-controller="linewise_daily_transaction_details" ng-model="exceltitle">
	
 				
		<div class="row  " >
				<div class="col-md-4" >
                <label class="form-label">From Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="dateRange"  autocomplete="off"  ng-model="frm_date"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>

          <div class="col-md-4" >
                <label class="form-label">To Date</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
 				<input type="text"   id="dateRange"  autocomplete="off"  ng-model="to_date"  class="form-control form-control-sm valid default-date-picker " title="Exp Date"  >
				</div>
					</div>
		
<!--<div class="col-md-4" >
                <label class="form-label"> Line</label>
                 <div class="input-group">
                   <span class="input-group-text"><i class="fa-solid fa-ruler-horizontal"></i></span>
 					<?php /*?><select ng-options="x as x.linename for x in linelist"  class="form-control form-control-sm" id="search_line_id" ng-model="search_line_id" ng-change="get_lines(search_city_id = search_line_id)">
                      </select><?php */?>
				<select ng-options="x.line_id as x.line_name for x in linelist"  class="form-control form-control-sm" id="scrchline_id" ng-model="scrchline_id" ng-change="get_lines(scrchline_id)">
                      </select>		
				</div>
					</div>-->

					<div class="col-md-4" >
                <label class="form-label"> &nbsp;</label>
                 <div class="input-group">
				    <button type="button" class="btn btn-success rounded-pill px-4" ng-click="get_dailycollection()">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                  </button>
 					</div>
	                </div>
					</div>			
 				  
 <div id="exportArea" class="exportArea">
				       <div class="calculator" id="calculator">
   <div class="row">
    <div style="display: none;" id="export_datatitle"><?php echo $linedts['line_name'].'_From_';?>{{frm_date}}_To_{{to_date}}</div>
  <div class="payment col-md-6" ><?php echo $linedts['line_name'];?> <h6>From {{frm_date}} To {{to_date}}</h6></div>
  <div class="payment col-md-6">Opening Balance <br>{{lineprevcolcrembal}}</div>
  </div>

  <div class="section">
    <div class="box">
      <h3>CREDITS</h3>
      <table> 
        <tr><td>Collections</td><td>{{lineoveralview.colc | number:2}}</td></tr>
        <tr><td>Investment</td><td>{{lineoveralview.emptak | number:2}}</td></tr>
         
        <tr><td>IntrestAmount</td><td>{{lineoveralview.intrestanddocucharge | number:2}}</td></tr>
<tr ng-repeat-start="(key, value) in adjustment_incomdtsarr['adustcolcname']">
    <td colspan="2" style="text-align: center;"><strong>{{value['adjname']}}</strong></td>
</tr>

<tr ng-repeat-end ng-repeat="q in lineadustmentamtarrs[value.adjtyp]">
    <td>{{ q.reason }}</td>
    <td>{{ q.adjustment_amt }}</td>
</tr>
        <!--  <tr ng-repeat="(key,value) in adjustment_incomdtsarr['adjamttyp']">  
            <tr ng-repeat="q in lineadustmentamtarrs[value]">  <td> {{q.reason}}</td> <td>{{q.adjustment_amt}}</td></tr>-->

        <!-- <tr ><td>{{value.adjname}}</td><td>{{adjustment_incomdtsarr['adustcolamt'][key] | number:2}}</td></tr>-->
</tr>
       </table>
      <div class="footer-note" style="margin-top:95px;"> &nbsp;&nbsp; Total : {{ (isNaN(parseFloat(grdcredits)) || grdcredits == 'NaN' || grdcredits == null || grdcredits === '') ? 0 : grdcredits }}</div>
  
	<div class="footer-note" style="margin-top:120px;"> &nbsp;&nbsp; <a href="home.php?p=linewise_daily_transaction_detail&selline_id=<?php echo $_REQUEST['selline_id'];?>">Get Deails</a></div>
	</div>

    <div class="box">
      <h3>DEBITS</h3>
      <table>
		<tr><td>Payments</td><td>{{lineoveralview.custborrow | number:2}}</td></tr>
        <tr><td>Expense</td><td>{{lineoveralview.expamt | number:2}}</td></tr>
        <tr ng-repeat-start="(key, value) in adjustment_incomdtsarr['adustexpname']">
    <td colspan="2" style="text-align: center;"><strong>{{value['adjname']}}</strong></td>
  </tr>

<tr ng-repeat-end ng-repeat="q in lineadustmentamtarrs[value.adjtyp]">
    <td>{{ q.reason }}</td>
    <td>{{ q.adjustment_amt }}</td>
</tr>
        <!-- <tr ng-repeat="(key,value) in adjustment_incomdtsarr['adustexpname']"><td>{{value}}</td><td>{{adjustment_incomdtsarr['adustexpamt'][key] | number:2}}</td></tr>-->
          <tr ng-show="linechitamtpaidtotamt>0"><td>Chit Payments</td><td>{{linechitamtpaidtotamt | number:2}}</td></tr>
       </table>
      <div class="footer-note" style="margin-top:95px;">&nbsp;&nbsp;Total : {{ isNaN(parseFloat(grddebits)) ? 0 : grddebits | number:2}} </div>

	   <div  style="margin-top:125px;width:100%;color:#260653;">&nbsp;&nbsp;Closing Balance : {{ isNaN(parseFloat(lineoveralview['rembal'])) ? 0 : lineoveralview['rembal'] | number:2}} </div>
    </div>
  </div>
</div>
 </div>		

	</div>
	</div>
