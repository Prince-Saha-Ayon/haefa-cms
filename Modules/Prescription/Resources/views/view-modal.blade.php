<div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">

      <!-- Modal Content -->
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <!-- /modal header -->
        <!-- Modal Body -->
        <div class="modal-body">
            <div class="row details">

            </div>
        </div>
        <!-- /modal body -->

        <!-- Modal Footer -->
        <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        <button type="button" id="printButton" class="btn btn-success btn-sm btn-print"  data-dismiss="modal">Print</button>
        </div>
        <!-- /modal footer -->
      </div>
      <!-- /modal content -->

    </div>
  </div>
    <script src="js/pdfmake.min.js"></script>
    <script src="js/vfs_fonts.js"></script>
    <script src="js/html2canvas.min.js"></script>
    <script src="js/jspdf.min.js"></script>
    <script src="js/html2pdf.bundle.min.js"></script>
    <script>
   
  document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.btn-print').addEventListener('click', function() {

   
        let pdfName = $('#order_id').text();
        pdfName = pdfName.replace('#','');
        pdfName = pdfName.trim(); // Remove the '#' character


        const pdf = html2pdf().set({
            margin: [0, 0, 0, 0],
            filename: `prescription.pdf`, // Using the cleaned order ID in the filename
            image: { type: 'png', quality: 1 },
            html2canvas: { scale: 2},
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
            }).from($('#prescription-print')[0]);

        pdf.output('bloburl').then(function(pdfData) {
            // Open the PDF file in a new window
            window.open(pdfData, '_blank');

            // Optionally, you can handle any completion logic here
        });



    });


});


    </script>