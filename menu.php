 <aside id="appSidebar" class="sidebar ">
  <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3 border-bottom" style="background: #0e45ac;">
    <h5 class="m-0 fw-bold text-white"><i class="fas fa-coins me-2 text-white"></i>FILES STORAGE</h5>
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
      <div class="fw-bold"><?php echo substr($_SESSION['user_fullname'],0,12);?></div>
      <div class="small text-muted"><?php echo substr($_SESSION['user_mobile'],0,12);?></div>
    </div>

    <nav class="">
        <?php $ismenu=1;
					foreach($menudt as $k=>$v){
 					       foreach($v as $kv=>$kvl){
 					if($kvl['ismenuvisable']==1){?>
            
           <div class="menu-item mb-2 plimenu<?php echo $kvl['menu_id'];?>">
        <a href="#" class="d-flex align-items-center justify-content-between toggle-submenu plimenuact<?php echo $kvl['menu_id'];?>">
          <span> <i class="<?php echo $kvl['logo_name'];?>"></i> <?php echo $kvl['menu_name'];?></span>
         
          <i class="fas fa-chevron-down small"></i>
        </a>
<div class="submenu plimenuopn<?php echo $kvl['menu_id'];?> mt-1 <?php echo $kvlkv['controller_name'];?>">

    <?php //print_r($kvl['firstsub']);
     foreach($kvl['firstsub'] as $kvlk=>$kvlkv){
   ?>
 

 						<?php 	  if($kvlkv['is_havescnd_sub']==0 && $kvlkv['is_menu']==1 && $kvlkv['is_excel']==0){?>
                        
                            <a href="home.php?p=<?php echo $kvlkv['controller_name'];?>" class="d-flex align-items-center mb-1 <?php echo $kvlkv['controller_name'];?>">
                                <i class="fa-solid fa-circle-info <?php echo $kvlkv['controller_name'];?>"></i> <?php echo $kvlkv['firstmenu_name'];?>
                            </a>
 							  <?php }
							   elseif($kvlkv['is_havescnd_sub']==0 && $kvlkv['is_menu']==1 && $kvlkv['is_excel']==1){?>
                           <a href="pages/<?php echo $kvlkv['page_name'];?>" class="d-flex align-items-center mb-1">
                              <i class="fa-solid fa-circle-info"></i> <?php echo $kvlkv['firstmenu_name'];?>
                          </a>
                    
 							  
                              <?php }?>   








       
           <?php }?>
             </div>
           </div>
           <?php }
          }
           }?>



      <!--<a href="#" class="d-flex align-items-center mb-2"><i class="fas fa-house me-2"></i> Home</a>
      <a href="#" class="d-flex align-items-center mb-2"><i class="fas fa-users me-2"></i> Customers</a>
      <a href="#" class="d-flex align-items-center mb-2"><i class="fas fa-user-friends me-2"></i> Suppliers</a>
      <a href="#" class="d-flex align-items-center mb-2"><i class="fas fa-chart-line me-2"></i> <i class="fa-solid fa-indian-rupee-sign"></i><i class="fa-solid fa-gauge"></i>

 Reports</a>-->

     <!-- <div class="menu-item mb-2">
        <a href="#" class="d-flex align-items-center justify-content-between toggle-submenu">
          <span><i class="fas fa-cog me-2"></i> Settings</span>
          <i class="fas fa-chevron-down small"></i>
        </a>
        <div class="submenu mt-1">
          <a href="#" class="d-flex align-items-center mb-1">
           <i class="fa-solid fa-circle-info"></i> Profile Settings
          </a>
          <a href="#" class="d-flex align-items-center mb-1">
           <i class="fa-solid fa-bars"></i> User Management
          </a>
          <a href="#" class="d-flex align-items-center">
           <i class="fa-solid fa-bars"></i> Security
          </a>
        </div>
      </div>-->

      <a href="home.php?p=logout" class="d-flex align-items-center text-danger mt-2"><i class="fas fa-right-from-bracket me-2"></i> Logout</a>
    </nav>
  </div>
</aside>

<style>
    /* submenu closed by default */

    nav a.active {
      /* white text */
  border-radius: 6px;          /* rounded edges */
}

.submenu{
  max-height:0;
  overflow:hidden;
  transition:max-height .25s ease;
}
/* opened state */
.submenu.open{ max-height: 600px; } /* just bigger than your tallest submenu */

/* rotate the chevron when active */
.toggle-submenu .fa-chevron-down{ transition:transform .25s ease; }
.toggle-submenu.active .fa-chevron-down{ transform:rotate(180deg); }



</style>


<script>
   
document.addEventListener('click', function (e) {
  // --- submenu toggle handling ---
  const toggle = e.target.closest('.toggle-submenu');
  if (toggle) {
    e.preventDefault();
    const menuItem = toggle.closest('.menu-item');
    const submenu   = menuItem.querySelector('.submenu');

    document.querySelectorAll('.menu-item .submenu').forEach(sm => {
      if (sm !== submenu) {
        sm.classList.remove('open');
        const t = sm.closest('.menu-item').querySelector('.toggle-submenu');
        if (t) t.classList.remove('active');
        sm.style.maxHeight = null;
      }
    });

    const nowOpen = !submenu.classList.contains('open');
    submenu.classList.toggle('open', nowOpen);
    toggle.classList.toggle('active', nowOpen);
    submenu.style.maxHeight = nowOpen ? submenu.scrollHeight + 'px' : null;
    return; // stop here if it was a submenu toggle
  }

  // --- highlight active menu/submenu link ---
  const link = e.target.closest('nav a');
  if (link && !link.classList.contains('toggle-submenu')) {
    // remove active from all links
    document.querySelectorAll('nav a').forEach(a => a.classList.remove('active'));
    // add active to clicked link
    link.classList.add('active');
  }
});


</script>
