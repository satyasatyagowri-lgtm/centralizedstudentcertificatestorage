<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard A/C Book-style UI (Bootstrap) — Fixed</title>

  <!-- Bootstrap CSS -->
  <link href="css/boostrapmin.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">


<link href="css/bootstrap-icons.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css">
<link href="css/select2.min.css" rel="stylesheet">
<style>
.select2-container{width:100%!important}
.select2-container--default .select2-selection--multiple{border:1px solid #ced4da!important;border-radius:.375rem!important;min-height:38px!important;display:flex;align-items:center;padding:2px 4px}
.input-group .select2-container--default .select2-selection--multiple{border-top-left-radius:0!important;border-bottom-left-radius:0!important}
.select2-container--default .select2-selection--multiple .select2-selection__choice{background:#0d6efd!important;color:#fff!important;border:0!important;border-radius:.25rem!important;padding:0 .5rem!important}
.select2-container--open .select2-dropdown{z-index:2000!important}
</style>

   <!--tabbar styles-->  
<style>
    /*tabvar styles */
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

   
    
</style>


</head>
<body class=" main-content">

  <!-- HEADER -->
<?php  include 'menu-header.php'; ?>

  

  <!-- MAIN CONTENT WRAPPER -->
<main class="content-wrap container-fluid mt-3">
  
 
     

  <div class="row">

<div class="tab-header">
    <!-- Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#home"><i class="fa-solid fa-user-friends"></i> User List</button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile"><i class="fa-solid fa-clipboard-user"></i> User Form</button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#contact"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#settings"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#about"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#extra"></button></li>
              <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#more"></button></li>
            </ul>

            <!-- Action icons (Desktop) -->
            <div class="action-icons">
              <button class="excel"><i class="fa-solid fa-file-excel"></i></button>
              <button class="pdf"><i class="fa-solid fa-file-pdf"></i></button>
              <button class="print"><i class="fa-solid fa-print"></i></button>
            </div>

            <!-- Action dropdown (Mobile) -->
            <div class="action-dropdown dropdown">
              <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-download"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-success" href="#"><i class="fa-solid fa-file-excel me-2"></i> Excel</a></li>
                <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-file-pdf me-2"></i> PDF</a></li>
                <li><a class="dropdown-item text-primary" href="#"><i class="fa-solid fa-print me-2"></i> Print</a></li>
              </ul>
            </div>
  </div>

                <div class="tab-content">
                      <div class="tab-pane fade show active" id="home">
                          <div class="card-body">

                          <div class="table-responsive">
                          <!-- 🔍 Search Box -->
                          
                              <div class="d-flex justify-content-between align-items-center  bg-light p-1 mb-2 rounded">
                                 <!-- Table Title -->
                                  <h5 class="mb-0 fw-bold">Users List</h5>
                                    <!-- Search bar with clear button -->
                                  <div class="position-relative">
                                    <input type="text" id="tableSearch" class="form-control w-auto pe-5" placeholder="Search...">
                                    <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-1" style="border:none;">
                                      <i class="fa-solid fa-xmark"></i>
                                    </button>
                                  </div>
                            </div>
                            <table id="patientTable" class="table align-middle text-dark small">
                              <thead>
                                <tr>
                                  <th data-sort="number">
                                    <span class="sort-handle"># <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Full Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="number">
                                    <span class="sort-handle">User Name <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="date">
                                    <span class="sort-handle">Mobile <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">ID Proof <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Address <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="string">
                                    <span class="sort-handle">Status <i class="fa-solid fa-sort sort-icon"></i></span>
                                  </th>
                                  <th data-sort="none" class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>001</td>
                                  <td><img src="https://randomuser.me/api/portraits/men/32.jpg" alt=""> Willian Mathews</td>
                                  <td>Siva</td>
                                  <td>7729915999</td>
                                  <td>7887788778787</td>
                                  <td>Sampara akfja;</td>
                                  <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                  <td class="action-btns">
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#delRow">
                                      <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#delRow">
                                      <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                  </td>
                                </tr>

                                <tr>
                                  <td>002</td>
                                  <td><img src="https://randomuser.me/api/portraits/men/32.jpg" alt=""> David Johnson</td>
                                  <td>Ravi</td>
                                  <td>9876543210</td>
                                  <td>566677889988</td>
                                  <td>Vizag City</td>
                                  <td><span class="badge bg-danger-subtle text-danger">In Active</span></td>
                                  <td class="action-btns">
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#delRow">
                                      <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#delRow">
                                      <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>

                          <div class="d-flex justify-content-between align-items-center mt-2">
                          <small>Showing Page 1 of 1</small>
                          <ul class="pagination pagination-sm mb-0">
                          <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                          <li class="page-item active"><a class="page-link" href="#">1</a></li>
                          <li class="page-item"><a class="page-link" href="#">Next</a></li>
                          </ul>
                          </div>
                      </div>                    
                       </div>
                        
                      <div class="tab-pane fade" id="profile">
                      

                        <div class="card p-4">
    <form class="needs-validation" novalidate>

      <!-- Basic Info -->
      <h5 class="mb-3 border-bottom pb-2">Basic Information</h5>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Line Name/Code *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
            <input type="text" class="form-control" placeholder="Enter line name or code" required>
            <div class="invalid-feedback">Please enter a line name.</div>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Assign Users</label>
          <div class="input-group" style="flex-wrap: nowrap !important;">
            <span class="input-group-text"><i class="bi bi-person-fill-add"></i></span>
           <select class=" select2-multiple" multiple>
              <option>Siva</option>
              <option>Swamy</option>
              <option>Seshu</option>
              <option>Sandeep</option>
              <option>Raju</option>
               
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">User Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" placeholder="Enter user name" required>
            <div class="invalid-feedback">Please enter a username.</div>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Password *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" placeholder="Enter password" required minlength="6">
            <div class="invalid-feedback">Password must be at least 6 characters.</div>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Full Name *</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control" placeholder="Enter full name" required>
            <div class="invalid-feedback">Please enter full name.</div>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
            <select class="form-select">
              <option selected disabled value="">Select</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>
        </div>
      </div>

      

    

      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success rounded-pill px-4">
          <i class="bi bi-check2-circle me-1"></i> Submit
        </button>
      </div>

    </form>
  </div>

                      </div>
                     
                </div>


    
     


        

  </div>
  </main>

  <!-- Floating Add Button (has id now) -->
 
  <!-- MOBILE FOOTER -->
  <nav class="mobile-footer d-flex  p-2 d-lg-none d-xl-none" style="background: linear-gradient(90deg, #d1ecf5, #fcfcfc);">
    <a class="nav-link text-center text-blue " href="#" id="ftHome" style="padding-left: 0px;">
      <i class="fas fa-house"></i>
      <div style="font-size:.75rem">Home</div>
    </a>
     <a class="nav-link text-center text-blue " href="#" id="ftHome" style="padding-left: 0px;">
      
      <div style="font-size:.75rem"></div>
    </a>
    

    <a href="#" class="center-btn text-white" id="centerLoans">
       <i class="fas fa-sack-dollar"></i>
        <span style="font-size:10px; color: aliceblue;">Loans</span>
    </a>

    <a class="nav-link text-center text-blue" href="#" id="ftMenu">
       <i class="fas fa-bars fs-4"></i>
      <div style="font-size:.75rem">Menu</div>
    </a>
  </nav>

  <!-- FILTER MODAL -->
  

  <!-- Bootstrap JS -->

 

<script src="js/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/select2.full.min.js"></script>
<script>
$(function(){
  function initAssignUsers(){
    $('#profile .select2-multiple').each(function(){
      if(!$(this).hasClass('select2-hidden-accessible')){
        $(this).select2({width:'100%',placeholder:'Select multiple options',allowClear:true});
      }else{
        $(this).select2('destroy').select2({width:'100%',placeholder:'Select multiple options',allowClear:true});
      }
    });
  }
  $(document).on('shown.bs.tab','button[data-bs-toggle="tab"]',function(e){
    if($(e.target).attr('data-bs-target')==='#profile'){ initAssignUsers(); }
  });
});
</script>
<script src="js/script.js"></script>

<script>
  // Form Validation
  (() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })();

  // Image Preview
  document.getElementById('userImage').addEventListener('change', function (event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    } else {
      preview.style.display = 'none';
    }
  });
</script>

</body>
</html>
