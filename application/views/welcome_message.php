<?php $this->load->view('header'); ?>

	<div class="container">
		<!-- Main component for a primary marketing message or call to action -->
		<br />
		<div class="jumbotron">
			<h1>FB Analytics</h1>
			<p>Welcome to Firstbuild's Analytics tool.</p>
			<br/>
			<div class="container">
			<div class="col-md-6">
				<h3>Firstbuild Statistics</h3>
				<?php if(sizeof($stats) > 0) { ?>
					<p>In the past 1 week...</p>
					<ul>
					<?php foreach($stats as $stat) { ?>
						<li><?php echo $stat['text'] . " " . $stat['number'] ; ?></li>
					<?php } ?>
					</ul>
				<?php } else { ?>
					<p>Sorry, no information is available at this time.</p>
				<?php } ?>
			</div>
			</div>	
		</div>
	</div> <!-- /container -->

<?php $this->load->view('footer'); ?>
