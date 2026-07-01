<div class="glass"></div>
	<div class="loadimg" style="display:none;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"  ></i></div>
	<input type="hidden" id="max_chars" value="<?php echo $max_char;?>">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Libraries -->





	<!--<script src="js/jquery-3.6.0.min.js"></script>-->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SheetJS for Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

<!-- jsPDF + html2canvas for PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
 
 

    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


 
<script src="js/bootstrap.bundle.min.js"></script> 
<script src="js/selectbox.min.js"></script>
<script src="js/select2.full.min.js"></script>
<script src="js/script.js"></script>
<script src="js/NotoSansTelugu-Regular-normal.js"></script>

<script>

async function translateNames(){ 
 const rows = document.querySelectorAll(".exportArea tbody tr");

for(let row of rows){

let cell = row.cells[1]; // Full Name column

let text = cell.innerText.trim();

if(text && /[\u0C00-\u0C7F]/.test(text)){ // Telugu detection

let url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=te&tl=en&dt=t&q="+encodeURIComponent(text);

try{

let res = await fetch(url);
let data = await res.json();

cell.innerText = data[0][0][0];

}catch(err){
console.log(err);
}

}

}

}

//window.onload = translateuniNames;





async function translateuniNames() { 
  const rows = document.querySelectorAll(".exportArea tbody tr");
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


async function translateuniNamesnew() { 
  const rows = document.querySelectorAll(".adjclass  tr");
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
 	<script src="includes/js/excel.js"></script>
	<script type="text/javascript" src="includes/jsapi.js"></script>
	<script>

    
		
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


 /* Multi Select box JS  */

 
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
 

  
   </script>