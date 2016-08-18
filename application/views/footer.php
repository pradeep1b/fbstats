	<!--
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
	-->
	<!-- FOOTER -->
	<div class="container">
		<hr class="featurette-divider">
		<footer>
			<p class="pull-right"><a href="#">Back to top</a></p>
		</footer>
	</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="<?php echo base_url(); ?>static/js/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="<?php echo base_url(); ?>static/js/bootstrap.min.js"></script>
	<!-- Include js for datepicker -->
	<script src="<?php echo base_url(); ?>static/js/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>static/js/daterangepicker.js"></script>
	<!-- Activate tooltips -->
	<script type="text/javascript">
			$(function () {
				$("[data-toggle='tooltip']").tooltip();
				
				$('input[name="datefilter"]').daterangepicker({
				      autoUpdateInput: false,
				      locale: {
				          cancelLabel: 'Clear'
				      }
				});
				
				$('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
				      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
				});
				
				$('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
				      $(this).val('');
				});

			});
			
			
	</script>
</div>

</body>
</html>
