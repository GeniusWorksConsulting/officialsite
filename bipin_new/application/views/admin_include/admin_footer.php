</div>

       	<script type="text/javascript" src="<?= base_url() ?>public/assets/js/jquery-ui.min.js"></script>
  
        <script src="<?= base_url() ?>public/assets/js/bootstrap.min.js"></script>
        <script src="<?= base_url() ?>public/assets/js/detect.js"></script>
        <script src="<?= base_url() ?>public/assets/js/fastclick.js"></script>

        <script src="<?= base_url() ?>public/assets/js/jquery.slimscroll.js"></script>
        <script src="<?= base_url() ?>public/assets/js/jquery.blockUI.js"></script>
        <script src="<?= base_url() ?>public/assets/js/waves.js"></script>
        <script src="<?= base_url() ?>public/assets/js/wow.min.js"></script>
        <script src="<?= base_url() ?>public/assets/js/jquery.nicescroll.js"></script>
        <script src="<?= base_url() ?>public/assets/js/jquery.scrollTo.min.js"></script>

        <script src="<?= base_url() ?>public/assets/plugins/peity/jquery.peity.min.js"></script>

        <!-- jQuery  -->
        <script src="<?= base_url() ?>public/assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="<?= base_url() ?>public/assets/plugins/counterup/jquery.counterup.min.js"></script>



     <!--   <script src="<?= base_url() ?>public/assets/plugins/morris/morris.min.js"></script> -->
        <script src="<?= base_url() ?>public/assets/plugins/raphael/raphael-min.js"></script>

        <script src="<?= base_url() ?>public/assets/plugins/jquery-knob/jquery.knob.js"></script>

        <script src="<?= base_url() ?>public/assets/pages/jquery.dashboard.js"></script> 

        <script src="<?= base_url() ?>public/assets/js/jquery.core.js"></script>
     <!--   <script src="<?= base_url() ?>public/assets/js/jquery.app.js"></script> -->
      
	  <!-- Sweet Alert -->
		<script src="<?= base_url() ?>public/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="<?= base_url() ?>public/assets/pages/jquery.sweet-alert2.init.js"></script>
		 	    <!-- Examples -->
	    <script src="<?= base_url() ?>public/assets/plugins/magnific-popup/js/jquery.magnific-popup.min.js"></script>
	    <script src="<?= base_url() ?>public/assets/plugins/jquery-datatables-editable/jquery.dataTables.js"></script> 
	    <script src="<?= base_url() ?>public/assets/plugins/datatables/dataTables.bootstrap.js"></script>
	    <script src="<?= base_url() ?>public/assets/plugins/tiny-editable/mindmup-editabletable.js"></script>
	    <script src="<?= base_url() ?>public/assets/plugins/tiny-editable/numeric-input-example.js"></script>
	    
	    
	    <script src="<?= base_url() ?>public/assets/pages/datatables.editable.init.js"></script>
	    
		
		 <!--Form Wizard-->
        <script src="<?= base_url() ?>public/assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?= base_url() ?>public/assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
	    
		
		 <!--Bootstrap Table-->
		   <script src="<?= base_url() ?>public/assets/plugins/bootstrap-table/js/bootstrap-table.min.js"></script>

        <script src="<?= base_url() ?>public/assets/pages/jquery.bs-table.js"></script>

		 
		 
		 
		 <!-- Invoice Calculation -->
		
		 <script type="text/javascript" src="<?= base_url() ?>public/assets/js/auto.js"></script>
		  <script type="text/javascript" src="<?= base_url() ?>public/assets/js/print.js"></script>
		  
		  <!-- FILTER -->
	<!--	  <script type="text/javascript" src="<?= base_url() ?>public/assets/js/filter.js"></script>	-->

      <script>
		$( document ).ready(function() {
			$('.button-menu-mobile').click(function(){
				if($('#wrapper').hasClass('enlarged'))
				{
					$('#wrapper').removeClass('enlarged');
				}else{
					$('#wrapper').addClass('enlarged');
				}
			});
			
			$('.opensub').click(function(){
				var openmenu = $(this).attr('data-id');
				$(this).addClass('active');
				$(this).addClass('subdrop');
				
				$('#'+openmenu).css('display','block');
			});
			
			$('.checkpercentage').click(function(){
				var id = $(this).attr('data-id');
				$('.checkpercentage_toggle'+id).toggle();
			});
		});
	  </script>
	  
	  <script>
		// jQuery('.button-menu-mobile').click(function(){
		// 	jQuery('.side-menu').toggle();
		// });
		</script>

	<script type="text/javascript">
	// var idleTime = 0;
	// $(document).ready(function () {
		// //Increment the idle time counter every minute.
		// var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

		// //Zero the idle timer on mouse movement.
		// $(this).mousemove(function (e) {
			// idleTime = 0;
		// });
		// $(this).keypress(function (e) {
			// idleTime = 0;
		// });
	// });

	// function timerIncrement() {
		// idleTime = idleTime + 1;
		// if (idleTime > 5) { // 20 minutes
			// window.location.href = "<?php echo base_url(); ?>admin/logout";
		// }
	// }
	</script>   
	
</body>
</html>