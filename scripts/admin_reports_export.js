// Robust client-side PDF export using html2canvas + jsPDF
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('export-pdf');
  const printBtn = document.getElementById('print-report');
  const content = document.getElementById('report-content');
  if (!btn || !content) return;

  btn.addEventListener('click', async function () {
    btn.disabled = true;
    btn.textContent = 'Preparing...';
    try {
      // capture at higher scale for better quality
      const canvas = await html2canvas(content, {scale: 2, useCORS: true});

      const imgData = canvas.toDataURL('image/png');
      const { jsPDF } = window.jspdf || {};
      if (!jsPDF) throw new Error('jsPDF not available');

      const pdf = new jsPDF('p', 'pt', 'a4');
      const pageWidth = pdf.internal.pageSize.getWidth();
      const pageHeight = pdf.internal.pageSize.getHeight();
      const margin = 40; // pt

      // Fit image width to printable width
      const imgPropsW = canvas.width;
      const imgPropsH = canvas.height;
      const pdfImgWidth = pageWidth - margin * 2;
      const pdfImgHeight = (imgPropsH * pdfImgWidth) / imgPropsW;

      // If content fits on single page
      if (pdfImgHeight <= (pageHeight - margin * 2)) {
        pdf.addImage(imgData, 'PNG', margin, margin, pdfImgWidth, pdfImgHeight);
        pdf.save('reports.pdf');
        btn.disabled = false;
        btn.textContent = 'Export PDF';
        return;
      }

      // Multi-page: slice canvas into page-sized pieces
      const pxPerPage = Math.floor((pageHeight - margin * 2) * (imgPropsW / pdfImgWidth));
      let renderedHeight = 0;
      while (renderedHeight < imgPropsH) {
        // create temporary canvas for slice
        const sliceCanvas = document.createElement('canvas');
        sliceCanvas.width = imgPropsW;
        sliceCanvas.height = Math.min(pxPerPage, imgPropsH - renderedHeight);
        const sctx = sliceCanvas.getContext('2d');
        sctx.drawImage(canvas, 0, renderedHeight, imgPropsW, sliceCanvas.height, 0, 0, imgPropsW, sliceCanvas.height);
        const sliceData = sliceCanvas.toDataURL('image/png');

        const slicePdfHeight = (sliceCanvas.height * pdfImgWidth) / imgPropsW;
        if (renderedHeight > 0) pdf.addPage();
        pdf.addImage(sliceData, 'PNG', margin, margin, pdfImgWidth, slicePdfHeight);
        renderedHeight += sliceCanvas.height;
      }

      pdf.save('reports.pdf');
    } catch (err) {
      console.error('Export PDF failed', err);
      alert('Export failed: ' + (err && err.message ? err.message : 'see console'));
    } finally {
      btn.disabled = false;
      btn.textContent = 'Export PDF';
    }
  });

  if (printBtn) {
    printBtn.addEventListener('click', function () {
      const w = window.open('', '_blank');
      if (!w) { alert('Popup blocked. Please allow popups to print.'); return; }
      w.document.write('<html><head><title>Report</title>');
      w.document.write('<style>body{font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,Arial;padding:24px;color:#111} .small{color:#666}</style>');
      w.document.write('</head><body>');
      w.document.write(content.innerHTML);
      w.document.write('</body></html>');
      w.document.close();
      w.focus();
      setTimeout(()=> w.print(), 300);
    });
  }
});
});
