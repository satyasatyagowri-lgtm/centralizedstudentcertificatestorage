  <meta charset="utf-8" />
  <!--<meta name="viewport" content="width=device-width, initial-scale=1" />-->
 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title><?php echo SITE_NAME;?></title>

  <!-- Bootstrap CSS -->
  <link href="css/boostrapmin.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Telugu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="js/datefilckers/dateflicker.css">
<!-- Flatpickr JS -->
<script src="js/datefilckers/dateflicker.js"></script>


  <link href="css/bootstrap-icons.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css">
     <link href="css/selectbox.min.css" rel="stylesheet" />
 
 
<style>
body {
    background: #f4f4f4;
    padding: 15px;
}
#exportArea, #exportArea table, #exportArea td {
    font-family: 'Noto Sans Telugu', 'Nirmala UI', 'Segoe UI', 'Gautami', 'Vijaya', sans-serif;
}
/* ===== EXPORT AREA ===== */
.exportArea {
    background: #fff;
    padding: 15px;
    max-width: 100%;
    overflow-x: auto;
}

/* ===== TABLE RULES ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
    font-family: 'Noto Sans Telugu', 'Nirmala UI', 'Segoe UI', 'Gautami', 'Vijaya', sans-serif;
}

thead {
    display: table-header-group;
}

tbody {
    display: table-row-group;
}

tr {
    page-break-inside: avoid;
}

th, td {
    border: 1px solid #ccc;
    padding: 8px 5px;
    font-size: 12px;
    font-family: 'Noto Sans Telugu', 'Nirmala UI', 'Segoe UI', 'Gautami', 'Vijaya', sans-serif;
    vertical-align: middle;
}

th {
    background: #f2f2f2;
    font-weight: bold;
}

/* Loading indicator */
#pdfLoading {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.95);
    color: white;
    padding: 30px 40px;
    border-radius: 20px;
    z-index: 9999;
    font-size: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    min-width: 400px;
    text-align: center;
}

.progress-container {
    margin: 20px 0;
    width: 100%;
    background: #444;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    height: 10px;
    background: linear-gradient(90deg, #4CAF50, #8BC34A);
    width: 0%;
    transition: width 0.3s ease;
}

#pdfStatus {
    font-size: 14px;
    color: #ddd;
    margin: 15px 0;
    line-height: 1.5;
}

#pdfDetails {
    font-size: 12px;
    color: #aaa;
    margin-top: 15px;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #4CAF50;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Method selector */
.method-selector {
    margin: 20px 0;
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.method-selector label {
    margin-right: 20px;
    font-weight: normal;
}

.method-selector input[type="radio"] {
    margin-right: 5px;
}
</style>

<style>
	.glass,.prdglass{
   background: #5733330a;
    width: 100%;
    height: 100%;
    position: fixed;
    left: 0px;
    top: 0px;
    z-index: 5000;
	display:none;
	}

.loadimg{
	background: #fff;
	top:270px;
	position: fixed;
		height:auto ;
	left:50%;
	z-index:5000;
	
}



   .forceColor {
    color: #0e45ad !important;
}

 /* Make multi select box match Bootstrap height */
    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
      height: auto !important;
      min-height: 38px;
      padding: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 28px;
    }
    .select2-container {
      width:100%!important;
    box-sizing: border-box;
    display: block !important;
    /* margin: 0; */
    position: relative;
    /* vertical-align: middle; */
    }

.select2-container--default .select2-selection--multiple .select2-selection__clear {
    cursor: pointer;
    font-weight: bold;
    height: 20px;
    margin-right: 10px;
   margin-top: 0px !important;
    position: absolute;
    right: 0;
    padding: 1px;
}


 

    body, html {
  margin: 0;
  padding: 0;
  width: 100%;
  overflow-x: hidden; /* avoids unwanted scrollbars */
}

.main-content {
  margin: 0 !important;
  /*padding: 0 10px;    optional spacing */
  width: 100% !important;
  box-sizing: border-box;
}

    .tab-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      background: #0000ff0f;
    }

    /* Tabs styling */
    .nav-tabs {
     /* background: #f1f3f5;
      padding: 0.25rem 0.5rem; */
      border-radius: 0.75rem;
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
      border: none;
    }
    .nav-tabs::-webkit-scrollbar { display: none; }

    .nav-tabs .nav-item { flex: 0 0 auto; margin: 0 0.25rem; }
    .nav-tabs .nav-link {
      border: none;
      color: #117fed;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover { background: #e9ecef; color: #1904a4; }
    .nav-tabs .nav-link.active {
      background: #0d6efd;
      color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    /* Action icons */
    .action-icons button {
      border: none;
      background: transparent;
      font-size: 1.2rem;
      margin-left: 0.5rem;
      transition: transform 0.2s;
    }
    .action-icons button:hover { transform: scale(1.2); }

    /* Colors */
    .excel { color: #28a745; }
    .pdf { color: #dc3545; }
    .print { color: #0d6efd; }

    /* Mobile dropdown */
    .action-icons { display: flex; }
    .action-dropdown { display: none; }

    @media (max-width: 768px) {
      .action-icons { display: none; }
      .action-dropdown { display: block; }
    }

    .tab-content {
      background: #ffffff;
      border: 1px solid #dee2e6;
      padding: 1.25rem;
      border-radius: 0.75rem;
      margin-top: 0.5rem;
    }
    
    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .preview-img {
      max-width: 150px;
      max-height: 150px;
      margin-top: 10px;
      border-radius: 8px;
      display: none;
    }
    #error{
  color:#FF0000;
  }
  #cerror{
  color:#FF0000;
  }


  .modalview {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1040;
    -webkit-overflow-scrolling: touch;
    outline: 0;
}
.modalview-dialog {
    width: 600px;
    margin: 30px auto;
}
.modal-content {
    position: relative;
    display: flex
;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.25rem;
    outline: 0;
}

button:not(:disabled), [type="button"]:not(:disabled), [type="reset"]:not(:disabled), [type="submit"]:not(:disabled) {
    cursor: pointer;
}
button.close {
    padding: 0;
    background-color: transparent;
    border: 0;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
  </style>

					<div class=" modalview modalview-dialog confirmdiv" style="z-index:100000; display:none;  position:fixed;" align="center" >
<div class="modal-content"><div class="modal-body">
<button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;" id="del_cancel" onclick="confirm_cancel()">x</button>
<div class="bootbox-body confirmboxmsg"></div>
</div>
<div class="modal-footer"><button type="button" data-bb-handler="success" id="del_confirm"  class="btn btn-sm btn-primary" onclick="confirm_box()" >OK</button><button data-bb-handler="success" type="button" class="btn btn-sm btn-danger" id="del_cancel" onclick="confirm_cancel()">Cancel</button></div>
</div>
</div>



<div class=" modalview modalview-dialog successbox" style="z-index:100000; display:none;  position:fixed;" align="center" >
<div class="modal-content"><div class="modal-body">
<button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;" id="del_cancel" onclick="success_ok()">x</button>
<div class="bootbox-body confirmboxmsg"></div>
</div>
<div class="modal-footer"><button type="button" data-bb-handler="success" id="del_confirm"  class="btn btn-sm btn-primary" onclick="success_ok()" >OK</button>
</div>
</div>
</div>