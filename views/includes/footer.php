<!--Footer-->
<footer class="bg-light text-lg-start">

    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
     <a href="?page=login"> 2025 Copyright:
      <a class="text-dark" href="https://sunucode.com/">Sunucode</a>
    </div>
    <!-- Copyright -->
  </footer>
  <!--Footer-->
    <!-- MDB -->
    <script type="text/javascript" src="js/mdb.umd.min.js"></script>
    
    <!-- Initialisation des composants MDB -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser tous les tooltips
            document.querySelectorAll('[data-mdb-toggle="tooltip"]').forEach(function(element) {
                new mdb.Tooltip(element);
            });
            
            // Initialiser tous les popovers
            document.querySelectorAll('[data-mdb-toggle="popover"]').forEach(function(element) {
                new mdb.Popover(element);
            });
            
            // Initialiser tous les dropdowns
            document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
                new mdb.Dropdown(element);
            });
            
            // Initialiser toutes les alertes
            document.querySelectorAll('.alert').forEach(function(element) {
                new mdb.Alert(element);
            });
        });
    </script>
</body>
</html>