<header class="app-header d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-transparent text-white p-1" id="btnSidebarToggle" aria-label="Open menu">
        <i class="fas fa-bars fs-4"></i>
      </button>
      <div class="d-flex flex-column">
        <span class="app-title">A/C Book</span>
        <small class="small">Manage your accounts</small>
      </div>
    </div>

    <div class="d-flex align-items-center gap-3">
      <div class="coins-badge">
        <i class="fas fa-bell fs-4"></i>
        <span class="badge bg-danger">3</span>
      </div>

      <div class="dropdown">
        <a class="d-inline-block" href="#" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="photo.jpg" alt="profile" class="rounded-circle" width="44" height="44">
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#"><i class="fas fa-gear me-2"></i> Settings</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </header>

<aside id="appSidebar" class="sidebar ">
  <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3 border-bottom" style="background: #0e45ac;">
    <h5 class="m-0 fw-bold text-white"><i class="fas fa-coins me-2 text-white"></i>A/C Book</h5>
    <button class="btn btn-sm btn-light rounded-circle" id="btnSidebarClose">
      <i class="fa-solid fa-xmark fa-lg"></i>
    </button>
  </div>

  <!-- Scrollable area -->
  <div class="sidebar-content ">
    <div class="profile-top text-center py-3 border-bottom">
      <div class="avatar mb-2" >
        <img src="photo.jpg" alt="avatar" class="rounded-circle" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div class="fw-bold">Siva Kumar</div>
      <div class="small text-muted">+91 98765 43210</div>
    </div>

    <nav class="">
      <a href="#" class="d-flex align-items-center mb-2"><i class="fas fa-house me-2"></i> Home</a>
      <a href="userdetails.php" class="d-flex align-items-center mb-2"><i class="fas fa-users me-2"></i> Users</a>
      <a href="line-details.php" class="d-flex align-items-center mb-2"><i class="bi bi-diagram-2 me-2" style="font-size: 18px;"></i> Lines</a>
      <a href="#" class="d-flex align-items-center mb-2"><i class="fas fa-user-friends me-2"></i> Suppliers</a>
      <a href="#" class="d-flex align-items-center mb-2"><i class="fas fa-chart-line me-2"></i> Reports</a>

      <div class="menu-item mb-2">
        <a href="#" class="d-flex align-items-center justify-content-between toggle-submenu">
          <span><i class="fas fa-cog me-2"></i> Settings</span>
          <i class="fas fa-chevron-down small"></i>
        </a>
        <div class="submenu mt-1">
          <a href="#" class="d-flex align-items-center mb-1">
            <i class="fas fa-user-cog me-2 text-secondary"></i> Profile Settings
          </a>
          <a href="#" class="d-flex align-items-center mb-1">
            <i class="fas fa-users-cog me-2 text-secondary"></i> User Management
          </a>
          <a href="#" class="d-flex align-items-center">
            <i class="fas fa-shield-alt me-2 text-secondary"></i> Security
          </a>
        </div>
      </div>

      <a href="#" class="d-flex align-items-center text-danger mt-2"><i class="fas fa-right-from-bracket me-2"></i> Logout</a>
    </nav>
  </div>
</aside>