<script type='text/javascript'>
var flag=false;
$(document).ready(function(){ 
$("#navbarmenu").show();
//$('#daterange-btn').hide();
 <?php 
	if($_SESSION['user_type']!='parent' && !isset($_SESSION['year_id'])) {?>
	
	popupSelect();	
	<?php } ?>
	popupSearch2();
function popupSelect()
{
  	$('.glass').fadeIn();
	$('.popupSelect').show();
	$('.popupSelect').removeClass('close').addClass('open');
}
function popupSearch2()
{  
	$('#searchButton').click(function(e){ 
		e.preventDefault();
		
		$("#searchButton").hide();
		$('.glass').fadeIn();
		$('.popupSearch').show();
		$("#search_course_id").val('');
		$("#search_query").val('');
		$("#search_branch_id").val('')
		$("#display_results").html('');
		$('.popupSearch').removeClass('close').addClass('open');
		$("#search_query").focus();
		init();	
	
	});
}
 $(".plimenuact<?php echo $pagar[$sel_pagky]['mparent_id'];?>").addClass("active");
 $(".<?php echo $_REQUEST['p'];?>").addClass("  forceColor");
 
 /*$(".<?php //echo $_REQUEST['p'];?>").each(function() {
    this.style.setProperty("color", "#585b70", "important");
});*/
 
  $(".plimenuopn<?php echo $pagar[$sel_pagky]['mparent_id'];?>").addClass("open");


function init()
{

	$("#search_query").focus();
	$("#search_query").keyup(function(event){
		event.preventDefault();
		search_ajax_way();});
		$('.glass').click(closePopup);
		
		$('#popClose').click(closePopup);
		$(document).keyup(function(e){
			if(e.keyCode==27)
			closePopup(e);	
		});	
}

function closePopup(e)
{
			$('.popupSearch,.popupSelect').hide();
			$('.popupSearch,.popupSelect').removeClass('open').addClass('close');
			$('.glass').fadeOut();
			$("#searchButton").show();
			popupSearch2();
}


$(document).on("click", "#exportExcel", function (e) { 
    e.preventDefault();
    exportExcel(); // your excel function here
});

/*$(document).on("click", "#exportPDF", function (e) {
    e.preventDefault();
    exportTableToPDF(); // your pdf function here
});*/

//document.getElementById("exportPDF").addEventListener("click", exportTableToPDF);
 $('#downloadPdf').on('click', async function() {
    const button = $(this);
    const btnText = $('#btnText');
    const btnSpinner = $('#btnSpinner');
    const loading = $('#pdfLoading');
    const progressBar = $('#pdfProgress');
    const statusText = $('#pdfStatus');
    const detailsText = $('#pdfDetails');
    
    // Disable button and show loading
    button.prop('disabled', true);
    btnSpinner.show();
    btnText.text('Generating PDF...');
    loading.show();
    progressBar.css('width', '0%');
    
    // Use setTimeout to prevent UI freezing
    setTimeout(async function() {
        try {
            const { jsPDF } = window.jspdf;
            
            // Get title
            const title = processUnicodeText($('#export_datatitle').first().text() || 'GNT2_Report');
            
            // Create PDF with better print settings
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
                compress: true,
                precision: 16,
                filters: ['ASCIIHexEncode'],
                putOnlyUsedFonts: true,
                // Ensure proper font embedding for printing
                fontFilenames: {
                    helvetica: 'Helvetica',
                    'helvetica-bold': 'Helvetica-Bold',
    styles:{
        font:'NotoSansTelugu-Regular',
        fontSize:9
    }
                }
            });
            
            // Load and embed Unicode-compatible fonts
            await loadUnicodeFont(pdf);
            
            let startY = 15;
            let pageCount = 1;
            
            // Add main title with proper font
            pdf.setFontSize(16);
            pdf.setFont('helvetica', 'bold', 'bold');
            pdf.setFont('NotoSansTelugu-Regular');
            pdf.text(processUnicodeText(title), 14, startY);
            startY += 10;
            
            // Get ALL tables in the document
            const tables = $('#exportArea table').toArray();
            const totalTables = tables.length;
            let processedTables = 0;
            
            statusText.text(`Processing ${totalTables} tables...`);
            detailsText.text('This will take a moment...');
            
            // Configure autoTable with print-optimized settings
            for (let i = 0; i < tables.length; i++) {
                const table = tables[i];
                
                processedTables++;
                const progress = Math.round((processedTables / totalTables) * 100);
                progressBar.css('width', progress + '%');
                progressBar.attr('aria-valuenow', progress);
                statusText.text(`Processing table ${processedTables} of ${totalTables} (${progress}%)`);
                
                // Check for title before table
                const prevElement = $(table).prev();
                let sectionTitle = '';
                
                if (prevElement.length) {
                    if (prevElement.is('div') && prevElement.find('div[align="center"]').length) {
                        sectionTitle = processUnicodeText(prevElement.find('div[align="center"]').text());
                    } else if (prevElement.find('h3').length) {
                        sectionTitle = processUnicodeText(prevElement.find('h3').text());
                    } else if (prevElement.hasClass('payment')) {
                        sectionTitle = processUnicodeText(prevElement.text());
                    }
                }
                
                if (sectionTitle) {
                    if (startY > 250) {
                        pdf.addPage();
                        pageCount++;
                        startY = 15;
                    }
                    
                    pdf.setFontSize(14);
                    pdf.setFont('helvetica', 'bold', 'bold');
                    pdf.text(sectionTitle, 14, startY);
                    startY += 8;
                }
                
                if (startY > 260) {
                    pdf.addPage();
                    pageCount++;
                    startY = 15;
                }
                
                // Generate table with print-optimized settings
                pdf.autoTable({
                    html: table,
                    startY: startY,
                    theme: 'grid',
                    styles: {
                        fontSize: 7,
                        cellPadding: 2,
                        overflow: 'linebreak',
                        
                        valign: 'middle',
                        halign: 'left',
                        lineColor: [0, 0, 0],
                        lineWidth: 0.15,
                        font: 'helvetica',
                        fontStyle: 'normal',
                        minCellHeight: 5,
                        textColor: [0, 0, 0],
                        // Ensure text is sharp when printed
                        charSpace: 0,
                        lineHeightFactor: 1.2
                    },
                    headStyles: {
                        fillColor: [220, 220, 220],
                        textColor: [0, 0, 0],
                        fontSize: 7,
                        fontStyle: 'bold',
                        halign: 'center',
                        lineWidth: 0.2,
                        // Ensure header stands out when printed
                        fillColor: [200, 200, 200]
                    },
                    bodyStyles: {
                       // font: 'helvetica',
                        font:'NotoSansTelugu-Regular',
                        fontSize: 7,
                        lineWidth: 0.1
                    },
                    alternateRowStyles: {
                        fillColor: [245, 245, 245]
                    },
                    margin: { top: 20, right: 10, bottom: 15, left: 10 },
                    tableWidth: 'auto',
                    
                    didDrawPage: function(data) {
                        // Add page number and footer on each page
                        const pageWidth = pdf.internal.pageSize.width;
                        const pageHeight = pdf.internal.pageSize.height;
                        
                        pdf.setFontSize(8);
                        pdf.setTextColor(80, 80, 80);
                        pdf.setFont('helvetica', 'normal', 'normal');
                        
                        // Page number at bottom
                        pdf.text(`Page ${pageCount} of ${pdf.internal.getNumberOfPages()}`, 
                                pageWidth - 30, pageHeight - 10);
                        
                        // Date at bottom left
                        const today = new Date().toLocaleDateString();
                        pdf.text(`Generated: ${today}`, 10, pageHeight - 10);
                    },
                    
                    didParseCell: function(data) {
                        if (data.cell.raw) {
                            // Extract clean Unicode text
                            const $cell = $(data.cell.raw);
                            let text = extractCellText($cell);
                            
                            // Final Unicode processing
                            text = processUnicodeText(text);
                            
                            // Handle different data types for better alignment
                            if (text) {
                                // Check if it's a number/amount
                                if (text.match(/^[₹$]?[\d,]+\.\d{2}$/) || 
                                    text.match(/^[\d,]+\.\d{2}$/) ||
                                    text.match(/^[₹$]?[\d,]+$/)) {
                                    data.cell.styles.halign = 'right';
                                    // Make numbers slightly bolder for better readability
                                    data.cell.styles.fontStyle = 'bold';
                                }
                                // Check if it's a date
                                else if (text.match(/^\d{2}[/-]\d{2}[/-]\d{4}$/)) {
                                    data.cell.styles.halign = 'center';
                                }
                                // Long text might need smaller font
                                else if (text.length > 30) {
                                    data.cell.styles.fontSize = 6;
                                }
                            }
                            
                            data.cell.text = text || '';
                        }
                    },
                    
                    // Ensure table is properly rasterized for printing
                    printHeaders: true,
                    includeHiddenHtml: false,
                    useCss: false
                });
                
                // Update Y position for next table
                if (pdf.lastAutoTable) {
                    startY = pdf.lastAutoTable.finalY + 8;
                }
                
                // Small delay to prevent browser freeze
                await new Promise(resolve => setTimeout(resolve, 10));
            }
            
            statusText.text('Finalizing PDF for printing...');
            
            // Add print-optimized metadata
            pdf.setProperties({
                title: title,
                subject: 'GNT2 Report - Print Optimized',
                author: 'GNT2 System',
                keywords: 'report, tables, data, print',
                creator: 'GNT2 PDF Generator',
                producer: 'jsPDF with Unicode Support'
            });
            
            // Set PDF for high-quality printing
            pdf.setDisplayMode('fullwidth', 'continuous', 'FullScreen');
            
            // Ensure all pages have proper font embedding
            const totalPages = pdf.internal.getNumberOfPages();
            for (let i = 1; i <= totalPages; i++) {
                pdf.setPage(i);
                pdf.setFont('helvetica', 'normal', 'normal');
                
                // Add thin border around each page for better print definition
                pdf.setDrawColor(0, 0, 0);
                pdf.setLineWidth(0.1);
                pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10, 'S');
            }
            
            statusText.text('Saving PDF...');
            
            // Save PDF with print-optimized filename
            const filename = title.replace(/[^a-z0-9]/gi, '_') + '_Print_Ready_Report.pdf';
            pdf.save(filename);
            
            // Show success
            statusText.text('PDF generated successfully! (Print-ready)');
            detailsText.html(`Total pages: ${totalPages}<br>Tables processed: ${processedTables}<br>Optimized for printing`);
            
            setTimeout(function() {
                loading.hide();
            }, 2000);
            
        } catch (error) {
            console.error('PDF Error:', error);
            statusText.text('Error: ' + error.message);
            detailsText.text('Check console for details');
            
            setTimeout(function() {
                alert('Error generating PDF. Please try again.\n\nError: ' + error.message);
                loading.hide();
            }, 500);
        } finally {
            // Reset button
            btnSpinner.hide();
            btnText.text('Download Complete PDF Report');
            button.prop('disabled', false);
        }
    }, 100);
});

async function loadUnicodeFont(pdf) {
    return new Promise((resolve) => {
        // Use built-in Helvetica which prints well
        //pdf.setFont('helvetica');
        pdf.setFont('helvetica');
         // Set language for better Unicode support
        pdf.setLanguage("hi-IN");
        
        // Add document properties for better print rendering
        pdf.setProperties({
            title: 'GNT2 Report',
            subject: 'Telugu Unicode Report - Print Optimized',
            author: 'GNT2',
            keywords: 'telugu, unicode, report, print, high-quality',
            creator: 'GNT2 PDF Generator'
        });
        
        // Ensure font is properly embedded for printing
        if (pdf.getFontList) {
            const fonts = pdf.getFontList();
            if (!fonts['helvetica-bold']) {
                // Fallback to standard font
                console.log('Using standard Helvetica for printing');
            }
        }
        
        resolve();
    });
}

// Enhanced Unicode text processing function
function processUnicodeText(text) {
    if (!text) return '';
    
    let processed = text;
    
    // Try to detect and fix mojibake
    try {
        // Check if text contains common mojibake patterns
        if (text.match(/[à-ÿ]/g) && text.length > 3) {
            // Attempt to fix by re-encoding
            const encoder = new TextEncoder();
            const decoder = new TextDecoder('utf-8');
            
            // First, encode the garbled text as Latin-1
            const latin1Buffer = new Uint8Array(text.length);
            for (let i = 0; i < text.length; i++) {
                latin1Buffer[i] = text.charCodeAt(i) & 0xFF;
            }
            
            // Then decode it as UTF-8
            processed = decoder.decode(latin1Buffer);
        }
    } catch (e) {
        console.warn('Mojibake fix failed:', e);
    }
    
    // Normalize Unicode
    processed = processed.normalize('NFC');
    
    // Remove extra whitespace and zero-width characters
    processed = processed.replace(/\s+/g, ' ').trim();
    processed = processed.replace(/[​‍‌﻿]/g, '');
    
    return processed;
}

// Enhanced cell text extraction
function extractCellText($cell) {
    // Clone to avoid modifying original
    const $clone = $cell.clone();
    
    // Remove unwanted elements
    $clone.find('img, a, button, i, .fa-solid, .fa-regular, .btn, .spinner-border, .fa, .fas, .far, script, style').remove();
    
    // Get text content with proper encoding
    let text = $clone.text();
    
    // Log for debugging (remove in production)
    if (text.includes('à°¤')) {
        console.log('Detected potential mojibake:', text);
    }
    
    // Process Unicode with mojibake detection
    return processUnicodeText(text);
}

// Add this helper function to ensure print quality
function optimizeForPrinting(pdf) {
    // Set print scaling to none
    pdf.setPrintScaling(false);
    
    // Set high-quality rendering
    pdf.setRenderingIntent('AbsoluteColorimetric');
    
    // Ensure all text is vector-based for sharp printing
    pdf.setDrawColor(0, 0, 0);
    pdf.setTextColor(0, 0, 0);
    
    return pdf;
}
    
    
$('#downloadPdfss').on('click', function () {

    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF({
        orientation: 'landscape',   // important for wide tables
        unit: 'mm',
        format: 'a4',
        compress: true
    });

    const titleEl = document.querySelector('#export_datatitle');
    const title = titleEl ? titleEl.innerText.trim() : 'Report';

    let startY = 12;

    // ---- TITLE ----
    pdf.setFontSize(12);
    pdf.text(title, 14, startY);
    startY += 6;

    // ---- GET ALL TABLES BETWEEN TITLE & exportArea ----
    const tables = document
        .getElementById('exportArea')
        .querySelectorAll('table');

    tables.forEach((table, index) => {

        pdf.autoTable({
            html: table,
            startY: startY,

            theme: 'grid',
            tableWidth: 'auto',

            horizontalPageBreak: true,      // ⭐ N columns handled
            horizontalPageBreakRepeat: 0,

            styles: {
                fontSize: 6,                // small = more columns
                cellPadding: 1.5,
                overflow: 'linebreak',
                valign: 'middle'
            },

            headStyles: {
                fillColor: [240, 240, 240],
                textColor: 0,
                fontSize: 6
            },

            didParseCell: function (data) {
                // remove icons/buttons
                if (data.cell.raw instanceof HTMLElement) {
                    data.cell.text = data.cell.raw.innerText.trim();
                }
            }
        });

        // update Y position for next table
        startY = pdf.lastAutoTable.finalY + 8;
    });

    pdf.save(title + '.pdf');
});



$('#downloadPdfbk').on('click', function () {

    const element = document.getElementById('exportArea');

    const options = {
        margin: 8,
        filename: 'GNT2_Report.pdf',

        image: {
            type: 'jpeg',
            quality: 0.75   // ⭐ SMALL SIZE
        },

        html2canvas: {
            scale: 1.2,     // ⭐ FAST
            scrollY: 0,
            useCORS: true,
            logging: false
        },

        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait',
            compressPDF: true
        },

        pagebreak: {
            mode: ['css', 'legacy']
        }
    };

    html2pdf().set(options).from(element).save();
});


function exportExcel() { 
    var wb = XLSX.utils.book_new();

    /* ----------- DIV SECTION SHEET ----------- */
    var divData = [];
    $(".exportArea").each(function () {
        var title = $(this).data("title") || "Section";
        divData.push([title, ""]);
        $(this).find("p").each(function () {
            var txt = $(this).text();
            if (txt.includes(":")) {
                let parts = txt.split(":");
                divData.push([parts[0].trim(), parts[1].trim()]);
            } else {
                divData.push([txt, ""]);
            }
        });
        divData.push([]);
    });
    var divSheet = XLSX.utils.aoa_to_sheet(divData);
    XLSX.utils.book_append_sheet(wb, divSheet, "DIVs");

    /* ----------- TABLES SHEETS ------------- */
    $(".export-table").each(function (i) {
        var sheet = XLSX.utils.table_to_sheet(this);
        XLSX.utils.book_append_sheet(wb, sheet, "Table_" + (i + 1));
    });

    // Download file
    XLSX.writeFile(wb, $("#export_datatitle").html()+".xlsx");
}


/* --------------------- PDF EXPORT ----------------------- */
   // Replace this with the Base64 string of NotoSans or a similar Universal Font
 


function exportTableToPDF() {
    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF({
        orientation: "p",
        unit: "pt",
        format: "a4",
        compress: true
    });

    let startY = 20;
    const pageWidth = pdf.internal.pageSize.getWidth();

    /* ===============================
       TITLE
    =============================== */
    const titleEl = document.getElementById("export_datatitle");
    if (titleEl) {
        pdf.setFontSize(11);
        pdf.setFont(undefined, "bold");
        pdf.text(titleEl.innerText.trim(), pageWidth / 2, startY, { align: "center" });
        startY += 20;
        pdf.setFont(undefined, "normal");
    }

    /* ===============================
       NORMAL DIV TEXT EXPORT
    =============================== */
    document.querySelectorAll("#exportArea .payment, #exportArea h3, #exportArea div[align='center']")
        .forEach(el => {
            const text = el.innerText.trim();
            if (text) {
                pdf.setFontSize(8);
                const lines = pdf.splitTextToSize(text, pageWidth - 40);
                pdf.text(lines, 20, startY);
                startY += (lines.length * 10) + 6;
            }
        });

    /* ===============================
       TABLE EXPORT
    =============================== */
    document.querySelectorAll("#exportArea table.export-table").forEach(table => {

        const clone = table.cloneNode(true);

        clone.querySelectorAll(".no-pdf, .no-pdf_bk, .hide-for-pdf")
             .forEach(el => el.remove());

        pdf.autoTable({
            html: clone,
            startY: startY,
            theme: "grid",
            useCss: false,
            pageBreak: "auto",
            margin: { left: 20, right: 20 },
            styles: {
                fontSize: 7,
                cellPadding: 2,
                overflow: "linebreak"
            },
            headStyles: {
                fillColor: [230, 230, 230],
                textColor: 0,
                fontSize: 7
            }
        });

        startY = pdf.lastAutoTable.finalY + 12;
    });

    /* ===============================
       SAVE
    =============================== */
    const fileName = titleEl ? titleEl.innerText.replace(/\s+/g, "_") : "linewise_report";
    pdf.save(fileName + ".pdf");
}

/*function exportTableToPDF() {
    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF({
        orientation: "p",
        unit: "pt",
        format: "a4",
        compress: true
    });

    const tables = document.querySelectorAll(".export-table");
    let startY = 30;

    tables.forEach((table, index) => {

        // Clone table so Angular DOM is untouched
        const clone = table.cloneNode(true);

        // Remove unwanted columns
        clone.querySelectorAll(".no-pdf, .no-pdf_bk").forEach(el => el.remove());

        pdf.autoTable({
            html: clone,
            startY: startY,
            theme: "grid",
            styles: {
                fontSize: 8,
                cellPadding: 3,
                overflow: "linebreak"
            },
            headStyles: {
                fillColor: [0, 0, 0],
                textColor: 255
            },
            didDrawPage: function () {
                startY = 30;
            }
        });

        startY = pdf.lastAutoTable.finalY + 20;
    });

    // SINGLE title ID FIX
    const titleEl = document.querySelector("#export_datatitle");
    const fileName = titleEl ? titleEl.innerText.trim() : "report";

    pdf.save(fileName + ".pdf");
}*/






async function exportTableToPDFbkp() { 
    const { jsPDF } = window.jspdf;

    const exportElement = document.getElementById("exportArea");

    /* --- HIDE COLUMNS YOU DON'T WANT IN THE PDF --- */
    const columnsToHide = exportElement.querySelectorAll(".no-pdf");
    columnsToHide.forEach(col => col.classList.add("hide-for-pdf"));

    // Wait a little so DOM applies the hide
    await new Promise(r => setTimeout(r, 50));

    /* ----------- TAKE SCREENSHOT WITH html2canvas ---------- */
    await html2canvas(exportElement, {
        scale: 1,
        useCORS: true,
        allowTaint: true,
        scrollY: -window.scrollY,
        
    }).then(canvas => {

        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "pt", "a4");

        const pdfWidth = 595;
        const pdfHeight = 842;

        const imgWidth = pdfWidth;
        const imgHeight = canvas.height * pdfWidth / canvas.width;

        let heightLeft = imgHeight;
        let y = 0;

        pdf.addImage(imgData, "PNG", 0, y, imgWidth, imgHeight);
        heightLeft -= pdfHeight;

        while (heightLeft > 0) {
            pdf.addPage();
            pdf.addImage(imgData, "PNG", 0, y - (imgHeight - heightLeft), imgWidth, imgHeight);
            heightLeft -= pdfHeight;
        }

        
         pdf.save($("#export_datatitle").html());
    });

    /* --- RESTORE HIDDEN COLUMNS BACK TO NORMAL --- */
    columnsToHide.forEach(col => col.classList.remove("hide-for-pdf"));
}



async function exportTableToPDF07_02_26() { 
    const { jsPDF } = window.jspdf;

    const exportElement = document.getElementById("exportArea");
    const table = exportElement.querySelector('table');
    
    /* --- HIDE COLUMNS YOU DON'T WANT IN THE PDF --- */
    const columnsToHide = exportElement.querySelectorAll(".no-pdf");
    columnsToHide.forEach(col => col.classList.add("hide-for-pdf"));

    // Store original table HTML
    const originalTableHTML = table.innerHTML;
    
    // Get table header
    const thead = table.querySelector('thead');
    const headerHTML = thead ? thead.outerHTML : '';
    
    // Get table body rows
    const tbody = table.querySelector('tbody');
    const rows = tbody ? Array.from(tbody.querySelectorAll('tr')) : [];
    
    // Calculate how many rows can fit on a single page
    const pageHeight = 842; // A4 height in points
    const headerHeight = thead ? thead.offsetHeight * (595 / exportElement.offsetWidth) * 1.5 : 0;
    const rowHeight = rows.length > 0 ? (tbody.offsetHeight / rows.length) * (595 / exportElement.offsetWidth) * 1.5 : 20;
    const rowsPerPage = Math.floor((pageHeight - headerHeight - 40) / rowHeight); // 40 for margins
    
    // If rowsPerPage is invalid or too small, use default splitting
    const useCustomSplitting = rowsPerPage > 0 && rowsPerPage < rows.length;
    
    if (useCustomSplitting) {
        // Create new table structure for pagination
        const tableClone = table.cloneNode(false);
        tableClone.innerHTML = '';
        
        // Add header to clone
        if (thead) {
            tableClone.appendChild(thead.cloneNode(true));
        }
        
        // Create temporary container for the first page
        const tempContainer = document.createElement('div');
        tempContainer.style.width = exportElement.offsetWidth + 'px';
        tempContainer.style.position = 'absolute';
        tempContainer.style.left = '-9999px';
        tempContainer.style.top = '0';
        tempContainer.appendChild(tableClone);
        document.body.appendChild(tempContainer);
        
        const pdf = new jsPDF("p", "pt", "a4");
        const pdfWidth = 595;
        
        for (let i = 0; i < rows.length; i += rowsPerPage) {
            // Clear previous rows
            if (tableClone.querySelector('tbody')) {
                tableClone.removeChild(tableClone.querySelector('tbody'));
            }
            
            // Create new tbody for current page
            const newTbody = document.createElement('tbody');
            const pageRows = rows.slice(i, Math.min(i + rowsPerPage, rows.length));
            
            pageRows.forEach(row => {
                newTbody.appendChild(row.cloneNode(true));
            });
            
            tableClone.appendChild(newTbody);
            
            // Take screenshot of current page
            await new Promise(r => setTimeout(r, 50));
            
            await html2canvas(tempContainer, {
                scale: 2, // Higher scale for better quality
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff'
            }).then(canvas => {
                const imgData = canvas.toDataURL("image/png");
                const imgWidth = pdfWidth;
                const imgHeight = (canvas.height * pdfWidth) / canvas.width;
                
                if (i > 0) {
                    pdf.addPage();
                }
                
                pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
            });
        }
        
        // Clean up
        document.body.removeChild(tempContainer);
        
        pdf.save($("#export_datatitle").html());
    } else {
        // Original approach for small tables
        await new Promise(r => setTimeout(r, 50));

        await html2canvas(exportElement, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            scrollY: -window.scrollY,
            backgroundColor: '#ffffff'
        }).then(canvas => {
            const imgData = canvas.toDataURL("image/png");
            const pdf = new jsPDF("p", "pt", "a4");

            const pdfWidth = 595;
            const pdfHeight = 842;

            const imgWidth = pdfWidth;
            const imgHeight = (canvas.height * pdfWidth) / canvas.width;

            let heightLeft = imgHeight;
            let position = 0;
            let pageCount = 1;

            // First page
            pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
            heightLeft -= pdfHeight;

            // Additional pages
            while (heightLeft > 0) {
                position -= pdfHeight;
                pdf.addPage();
                pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
                heightLeft -= pdfHeight;
                pageCount++;
            }

            pdf.save($("#export_datatitle").html());
        });
    }

    /* --- RESTORE ORIGINAL TABLE STRUCTURE --- */
    if (useCustomSplitting) {
        table.innerHTML = originalTableHTML;
    }
    
    /* --- RESTORE HIDDEN COLUMNS BACK TO NORMAL --- */
    columnsToHide.forEach(col => col.classList.remove("hide-for-pdf"));
}

// Alternative simpler approach using html2canvas options
async function exportTableToPDFSimple() {
    const { jsPDF } = window.jspdf;

    const exportElement = document.getElementById("exportArea");
    const table = exportElement.querySelector('table');
    
    /* --- HIDE COLUMNS YOU DON'T WANT IN THE PDF --- */
    const columnsToHide = exportElement.querySelectorAll(".no-pdf");
    columnsToHide.forEach(col => col.classList.add("hide-for-pdf"));

    // Wait for DOM update
    await new Promise(r => setTimeout(r, 50));

    // Use html2canvas's onclone option to repeat headers
    await html2canvas(exportElement, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        onclone: function(clonedDoc) {
            // This function runs on the cloned document
            const clonedTable = clonedDoc.querySelector('#exportArea table');
            if (clonedTable) {
                // Ensure table has proper structure for printing
                clonedTable.style.borderCollapse = 'collapse';
                clonedTable.style.width = '100%';
                
                // Add repeating header styles
                const style = clonedDoc.createElement('style');
                style.innerHTML = `
                    table { 
                        page-break-inside: auto; 
                    }
                    tr { 
                        page-break-inside: avoid; 
                        page-break-after: auto; 
                    }
                    thead { 
                        display: table-header-group; 
                    }
                    tfoot { 
                        display: table-footer-group; 
                    }
                `;
                clonedDoc.head.appendChild(style);
            }
        }
    }).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "pt", "a4");

        const pdfWidth = 595;
        const pdfHeight = 842;

        const imgWidth = pdfWidth;
        const imgHeight = (canvas.height * pdfWidth) / canvas.width;

        let heightLeft = imgHeight;
        let position = 0;

        pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
        heightLeft -= pdfHeight;

        while (heightLeft > 0) {
            position -= pdfHeight;
            pdf.addPage();
            pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
            heightLeft -= pdfHeight;
        }

        pdf.save($("#export_datatitle").html());
    });

    /* --- RESTORE HIDDEN COLUMNS BACK TO NORMAL --- */
    columnsToHide.forEach(col => col.classList.remove("hide-for-pdf"));
}

 /*async function exportTableToPDF() {
    const { jsPDF } = window.jspdf;

    // Select only the content you want exported
    const exportElement = document.getElementById("exportArea");

    // html2canvas options to avoid huge canvas
    await html2canvas(exportElement, {
        scale: 1,        // reduces size
        useCORS: true,
        allowTaint: true,
        scrollY: -window.scrollY // avoid scroll issues
    }).then(canvas => {

        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "pt", "a4");

        const pdfWidth = 595;
        const pdfHeight = 842;

        const imgWidth = pdfWidth;
        const imgHeight = canvas.height * pdfWidth / canvas.width;

        let heightLeft = imgHeight;
        let y = 0;

        pdf.addImage(imgData, "PNG", 0, y, imgWidth, imgHeight);
        heightLeft -= pdfHeight;

        while (heightLeft > 0) {
            pdf.addPage();
            pdf.addImage(imgData, "PNG", 0, y - (imgHeight - heightLeft), imgWidth, imgHeight);
            heightLeft -= pdfHeight;
        }

        pdf.save($("#export_datatitle").html());
    });
}
*/

/*async function exportTableToPDF() {
    const { jsPDF } = window.jspdf;

    const exportElement = document.getElementById("exportArea");

    const canvas = await html2canvas(exportElement, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        scrollY: -window.scrollY
    });

    const imgData = canvas.toDataURL("image/png");
    const pdf = new jsPDF("p", "pt", "a4");

    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = pdf.internal.pageSize.getHeight();

    const imgWidth = pdfWidth;
    const imgHeight = (canvas.height * pdfWidth) / canvas.width;

    let heightLeft = imgHeight;
    let position = 0;

    pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
    heightLeft -= pdfHeight;

    // Add additional pages if needed
    while (heightLeft > 0) {
        position = heightLeft - imgHeight;
        pdf.addPage();
        pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
        heightLeft -= pdfHeight;
    }

    pdf.save(document.getElementById("export_datatitle").innerHTML + ".pdf");
}*/


  flatpickr(".default-date-picker", {
    dateFormat: "d-m-Y", // This is the dd-mm-YYYY format
    allowInput: true     // Allows manual input too
  });

  flatpickr(".default-date-picker", {
  dateFormat: "d-m-Y",
  defaultDate: "",
  minDate: "01-01-2020",
  maxDate: "31-12-2030",
  allowInput: true
});


/*flatpickr(".dateRange", {
    mode: "range",
    dateFormat: "d-m-Y", // dd-mm-YYYY
    allowInput: true,
    onChange: function(selectedDates, dateStr, instance) {
      document.getElementByClass('dateRange').textContent = dateStr;
    }
  });*/

  flatpickr(".dateRange", {
    mode: "range",
    dateFormat: "d-m-Y", // dd-mm-YYYY
    allowInput: true,
    minRange: 0, // ✅ allows same date as from & to
   // defaultDate: [new Date(), new Date()], // Today as both From & To
   /* onReady: function(selectedDates, dateStr, instance) {
      // Pre-fill with today → today
       const today = instance.formatDate(new Date(), "d-m-Y");
    instance._input.value = today + " TO " + today;
    },
    onChange: function(selectedDates, dateStr, instance) {
      if (selectedDates.length === 2) {
        const fromDate = instance.formatDate(selectedDates[0], "d-m-Y");
        const toDate   = instance.formatDate(selectedDates[1], "d-m-Y");

        console.log("From Date:", fromDate);
        console.log("To Date:", toDate);
      }
    }*/
  });



  /*const today = new Date();

  flatpickr(".dateRange", {
    mode: "range",
    dateFormat: "d-m-Y", // dd-mm-yyyy
    defaultDate: [today, today], // From and To = today
    allowInput: true,
    onReady: function(selectedDates, dateStr, instance) {
      document.getElementByClass('dateRange').textContent = dateStr;
    },
    onChange: function(selectedDates, dateStr, instance) {
      document.getElementByClass('dateRange').textContent = dateStr;
    }
  });*/

  
});
</script>

