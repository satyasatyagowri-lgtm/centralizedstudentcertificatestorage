<header class="app-header d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-transparent text-white p-1" id="btnSidebarToggle" aria-label="Open menu">
        <i class="fas fa-bars fs-4"></i>
      </button>
      <div class="d-flex flex-column">
        <span class="app-title"><?php echo SITE_NAME_TITLE;?></span>
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
          <li><a class="dropdown-item" href="home.php?p=settings"><i class="fas fa-gear me-2"></i> Settings</a></li>
          <li><a class="dropdown-item" href="home.php?p=logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </header>