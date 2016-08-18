<?php $this->load->view('header', array('title' => "Comments" . " Comments History")); ?>

<div class="container">
	<h3>Comments LastWeek</h3>


	<?php if($results != FALSE) { ?>
		<!-- Table -->
	<div class="panel panel-primary">
		<div class="panel-heading">Comments </div>
		<table class="table table-bordered">
			<tr>
				<th>Username</th>
				<th>Comment</th>
				<th>Created</th>
				<th>Active</th>
			</tr>
			<?php foreach($results as $result) { ?>
			<tr>
				<td><a href="<?php echo wca_url(array('home','idea',$history['homeId'])); ?>"><?php echo $result['']; ?></a></td>
				<td><?php echo $result['status']; ?></td>
				<!--<td><?php echo myformat_date($result['createdAt']); ?></td>-->
			</tr>
			<?php } ?>
		</table>
	</div>
	<?php } else { ?>
			<div class="alert alert-info">No information available.</div>
	<?php } ?>
	</div>
<!-- /container -->

<?php $this->load->view('footer'); ?>
