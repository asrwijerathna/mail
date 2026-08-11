        </main> <!-- End Main -->
      </div> <!-- End Row -->
    </div> <!-- End Container Fluid -->

<?php
	
	mysqli_close($conn);
	?> 
    
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>    

    <script>
    // Fix: Bootstrap modals inside table <tr>/<td> elements cause invalid HTML.
    // Browsers move the modal divs outside the table automatically, breaking
    // the data-target link. This script moves all .mail-modal divs to <body>
    // so they are at the correct DOM level for Bootstrap to find them.
    $(document).ready(function() {
        $('.mail-modal').each(function() {
            $(this).appendTo('body');
        });
    });
    </script>

    
    
    
</body>
</html>