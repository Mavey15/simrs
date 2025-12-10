            </div>
        </div>
     </div>
            <!-- Load core JS at footer for better performance -->
            <script src="<?=base_url('_assets/js/jquery.js');?>"></script>
            <script src="<?=base_url('_assets/js/bootstrap.min.js');?>"></script>
     <!-- DataTables JS -->
     <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap.min.js"></script>
     <!-- Export Libraries -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.0/pdfmake.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.0/vfs_fonts.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
     <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>
</body>
</html>
