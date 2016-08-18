<?php $this->load->view('header', array('title' => "Comments" . " Comments History")); ?>

<div class="container">
	<h3>Comments History</h3>
	<?php if(isset($error) && $error == TRUE) { ?>
	<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
	<?php } ?>
	
	<div class="row">
		<!-- Form code begins -->
		<form class="form-inline" role="form" action="<?php echo fb_url(array('idea', 'commentsearch')); ?>" method="post">
			<div class="form-group">
				<label class="sr-only" for="email">Select Date Range</label>
				<input type="text" class="form-control" id="datefilter" name="datefilter" placeholder="Select Date Range" value="<?php echo set_value('datefilter'); ?>">
	   		</div>
	   		<button class="btn btn-primary " name="submit" type="submit">Submit</button>
     	</form>
     	<!-- Form code ends --> 
	</div>
	
	<br />
	
	<?php if(!isset($results) || !isset($wasSearch)) { ?>
		<?php } else if(sizeof($results) == 0) { ?>
			<div class="alert alert-info">No Results</div>
		<?php } else { ?>
			<div class="panel panel-primary">
			<div class="panel-heading">Comment History for date range<?php echo $datefilter; ?></div>
			<table class="table table-bordered">
				<tr>
					<th>Username</th>
					<th>Comment</th>
					<th>Created</th>
					<th>Active</th>
				</tr>
				<?php foreach($results as $result) { ?>
					
					<tr>
						<td> <?php echo $result->user->traits->username; ?></td>
						<td> <?php echo $result->url; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				<?php } ?>
			</table>
			</div>
		<?php } ?>
	
</div>

<?php $this->load->view('footer'); ?>
