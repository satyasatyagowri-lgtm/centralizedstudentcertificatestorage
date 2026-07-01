const hh = document.getElementById("hh");
const mm = document.getElementById("mm");
const ampm = document.getElementById("ampm");
const output = document.getElementById("output");

// Populate hours (01–12)
for (let i = 1; i <= 12; i++) {
  hh.innerHTML += `<option value="${i}">${String(i).padStart(2, "0")}</option>`;
}

// Populate minutes (00–59)
for (let i = 0; i < 60; i++) {
  mm.innerHTML += `<option value="${i}">${String(i).padStart(2, "0")}</option>`;
}

// Update output
function updateTime() {
  if (hh.value && mm.value) {
    output.textContent =
      `Selected Time: ${String(hh.value).padStart(2, "0")}:` +
      `${String(mm.value).padStart(2, "0")} ${ampm.value}`;
  } else {
    output.textContent = "";
  }
}

hh.addEventListener("change", updateTime);
mm.addEventListener("change", updateTime);
ampm.addEventListener("change", updateTime);
