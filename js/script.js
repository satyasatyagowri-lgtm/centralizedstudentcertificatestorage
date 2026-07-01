
  $(document).ready(function() {
    $('.select2-single').select2({
      placeholder: "Select an option",
      allowClear: true
    });

    $('.select2-multiple').select2({
      placeholder: "Select multiple options",
      allowClear: true
    });
  });

  
    const sidebar = document.getElementById('appSidebar');
    const btnToggle = document.getElementById('btnSidebarToggle');
    const btnClose = document.getElementById('btnSidebarClose');
    const ftMenu = document.getElementById('ftMenu');
    const ftHome = document.getElementById('ftHome');
    const ftProfile = document.getElementById('ftProfile'); // may be null


    // Set initial state for desktop
    document.addEventListener('DOMContentLoaded', () => {
      if (window.innerWidth >= 992) {
        document.body.classList.add('sidebar-open');
      }
    });

    if (btnToggle) {
      btnToggle.addEventListener('click', () => {
        if (window.innerWidth >= 992) {
          document.body.classList.toggle('sidebar-open');
          document.body.classList.toggle('sidebar-closed');
        } else {
          sidebar.classList.toggle('show');
        }
      });
    }

    if (btnClose) {
      btnClose.addEventListener('click', () => {
        if (window.innerWidth >= 992) {
          document.body.classList.remove('sidebar-open');
          document.body.classList.add('sidebar-closed');
        } else {
          sidebar.classList.remove('show');
        }
      });
    }

    if (ftMenu && sidebar) {
      ftMenu.addEventListener('click', (e) => { e.preventDefault(); sidebar.classList.toggle('show'); });
    }

     // Footer nav active highlight (guard for missing ftProfile)
    [ftHome, ftProfile, ftMenu].filter(Boolean).forEach(el => {
      el.addEventListener('click', (e)=> {
        [ftHome, ftProfile, ftMenu].filter(Boolean).forEach(x=>x.classList.remove('active'));
        el.classList.add('active');
      });
    });

     // Open filter modal
    const btnFilterOpener = document.getElementById('btnFilterOpener');
    const filterModalEl = document.getElementById('filterModal');
    const filterModal = filterModalEl ? new bootstrap.Modal(filterModalEl) : null;
    if (btnFilterOpener && filterModal) {
      btnFilterOpener.addEventListener('click', ()=> filterModal.show());
    }

    // Add modal
    const addModalEl = document.getElementById('addModal');
    const addModal = addModalEl ? new bootstrap.Modal(addModalEl) : null;
    const btnAdd = document.getElementById('btnAdd');
    if (btnAdd && addModal) {
      btnAdd.addEventListener('click', ()=> addModal.show());
    }

    // Search / Filter logic
    const searchInput = document.getElementById('searchInput');
    const customersList = document.getElementById('customersList');
    const suppliersList = document.getElementById('suppliersList');
    const filterForm = document.getElementById('filterForm');

    // Utility: filter items in a list by dataset attributes
    function filterList(listEl, { q='', from='', to='', status='' } = {}) {
      if (!listEl) return;
      const items = Array.from(listEl.querySelectorAll('.list-group-item'));
      items.forEach(item => {
        const name = (item.dataset.name || '').toLowerCase();
        const statusVal = item.dataset.status || '';
        const dateVal = item.dataset.date || '';
        const amount = item.querySelector('.fw-bold')?.textContent || '';

        let visible = true;
        if (q && ! (name.includes(q) || amount.includes(q)) ) visible = false;
        if (status && statusVal !== status) visible = false;
        if (from && dateVal && dateVal < from) visible = false;
        if (to && dateVal && dateVal > to) visible = false;
        item.style.display = visible ? '' : 'none';
      });
    }

    function runFilters() {
      const q = searchInput?.value.trim().toLowerCase() || '';
      const form = filterForm ? new FormData(filterForm) : null;
      const from = form ? (form.get('from') || '') : '';
      const to = form ? (form.get('to') || '') : '';
      const status = form ? (form.get('status') || '') : '';

      filterList(customersList, { q, from, to, status });
      filterList(suppliersList, { q, from, to, status });
    }

    // Live search
    if (searchInput) searchInput.addEventListener('input', runFilters);

    // Apply filter modal
    if (filterForm && filterModal) {
      filterForm.addEventListener('submit', (e)=> {
        e.preventDefault();
        filterModal.hide();
        runFilters();
      });
    }

    // Clear filter
    const btnClearFilter = document.getElementById('btnClearFilter');
    if (btnClearFilter) {
      btnClearFilter.addEventListener('click', () => {
        if (filterForm) filterForm.reset();
        runFilters();
      });
    }

    // Add item (simple append to appropriate list)
    const addForm = document.getElementById('addForm');
    if (addForm) {
      addForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const name = fd.get('name') || 'Unknown';
        const which = fd.get('which') || 'customer'; // customer / supplier
        const amount = fd.get('amount') || '0';
        const list = which === 'supplier' ? suppliersList : customersList;

        // create element
        const item = document.createElement('div');
        item.className = 'list-group-item person-item';
        item.dataset.name = (name).toLowerCase();
        item.dataset.status = 'open';
        item.dataset.date = (new Date()).toISOString().slice(0,10);

        const letter = (name.trim().charAt(0).toUpperCase()) || 'U';
        item.innerHTML = `
          <div class="person-left">
            <div class="mini-avatar" style="background:linear-gradient(135deg,#f953c6,#b91cfe)">${letter}</div>
            <div>
              <div class="fw-semibold">${name}</div>
              <div class="small-muted">Just now</div>
            </div>
          </div>
          <div class="text-end">
            <div class="fw-bold text-danger">₹ ${amount}</div>
          </div>
        `;
        if (list) list.prepend(item);
        if (addModal) addModal.hide();
        e.target.reset();
      });
    }

    // Auto close on resize from mobile to desktop
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        document.body.classList.add('sidebar-open');
        document.body.classList.remove('sidebar-closed');
        sidebar.classList.remove('show');
      }
    });

      // Initialize submenu 
  document.querySelectorAll('.toggle-submenu').forEach(toggle => {
    toggle.addEventListener('click', e => {
      e.preventDefault();
      const submenu = toggle.nextElementSibling;
      const arrow = toggle.querySelector('.fa-chevron-down');

      if (submenu.style.maxHeight && submenu.style.maxHeight !== "0px") {
        submenu.style.maxHeight = "0";
        arrow.classList.remove("rotate");
      } else {
        submenu.style.maxHeight = submenu.scrollHeight + "px";
        arrow.classList.add("rotate");
      }
    });
  });


 // Initialize table sorting 
(function () {
  const tables = document.getElementsByClassName('tablesortsearchable');

  Array.from(tables).forEach((table) => {
    const tbody = table.querySelector('tbody');
    let sortState = { index: -1, dir: 'asc' };

    table.querySelectorAll('thead th').forEach((th, idx) => {
      const type = th.dataset.sort || 'none';
      if (type === 'none') return;

      th.addEventListener('click', () => {
        const dir = (sortState.index === idx && sortState.dir === 'asc') ? 'desc' : 'asc';
        sortState = { index: idx, dir };

        const rows = Array.from(tbody.querySelectorAll('tr'));
        const getCell = (tr) => tr.children[idx].textContent.trim();

        const parseDate = (ddmmyyyy) => {
          const [d, m, y] = ddmmyyyy.split('/').map(Number);
          return new Date(y, m - 1, d).getTime();
        };

        rows.sort((a, b) => {
          let va = getCell(a);
          let vb = getCell(b);

          switch (type) {
            case 'number':
              va = parseInt(va.replace(/\D+/g, ''), 10) || 0;
              vb = parseInt(vb.replace(/\D+/g, ''), 10) || 0;
              break;

            case 'date':
              va = parseDate(va);
              vb = parseDate(vb);
              break;

            case 'week':
              va = parseInt(va.replace(/\D+/g, ''), 10) || 0;
              vb = parseInt(vb.replace(/\D+/g, ''), 10) || 0;
              break;

            default:
              va = va.toLowerCase();
              vb = vb.toLowerCase();
          }

          if (va < vb) return dir === 'asc' ? -1 : 1;
          if (va > vb) return dir === 'asc' ? 1 : -1;
          return 0;
        });

        rows.forEach(r => tbody.appendChild(r));

        // update icons
        table.querySelectorAll('.sort-icon').forEach(i => {
          i.classList.remove('fa-sort-up', 'fa-sort-down', 'sort-active');
          i.classList.add('fa-sort');
        });

        const icon = th.querySelector('.sort-icon');
        if (icon) {
          icon.classList.remove('fa-sort');
          icon.classList.add(dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down', 'sort-active');
        }
      });
    });
  });
})();


   
  const seachtable = document.getElementById("tableSearch");
  const clearBtn = document.getElementById("clearSearch");

  function filterTable() {
    let value = seachtable.value.toLowerCase();
    document.querySelectorAll(".tablesortsearchable tbody tr").forEach(function (row) {
      row.style.display = row.textContent.toLowerCase().includes(value) ? "" : "none";
    });
    clearBtn.style.display = value ? "block" : "none"; // show × only if text entered
  }

  seachtable.addEventListener("keyup", filterTable);

  clearBtn.addEventListener("click", function () {
    seachtable.value = "";
    filterTable();
    seachtable.focus();
  });

  // Hide × button initially
  clearBtn.style.display = "none";
 
  
  // Form Validation start
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
  
 