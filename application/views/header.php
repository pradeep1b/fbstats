<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>FB Analytics<?php if(isset($title)) { echo  ": " . $title; } ?></title>
	<link href="<?php echo base_url(); ?>static/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/css/fb.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/css/daterangepicker.css" rel="stylesheet">
	<!--[if lt IE 9]>
		<script src="<?php echo base_url(); ?>static/js/html5.js"></script>
		<script src="<?php echo base_url(); ?>static/js/respond.src.js"></script>
	<![endif]-->
</head>
<body>

<div id="container">

	<!-- Fixed navbar -->
	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<!--
			<div>
				<img id="headerimg" src="<?php echo base_url() . 'static/images/logo_ge_blue_greybg.gif'; ?>"/>
			</div>
			-->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo fb_url(); ?>">FB Analytics</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Reports <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<!-- Ideas -->
							<li class="dropdown-header">Ideas</li>
							<li><a href="<?php echo fb_url(array('idea')); ?>">Comments</a></li>
						</ul>
					</li>
					

				</ul>

				<ul class="nav navbar-nav navbar-right">
					
					<li class="dropdown">
						<?php
						switch (fb_env()) {
								case "prd":
									$color = "label-success";
								break;
								case "fld":
									$color = "label-warning";
								break;
								case "int":
									$color = "label-danger";
								break;
								default:
									$color = "";
						}
						?>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="label <?php echo $color; ?>">Environment: <?php echo strtoupper(fb_env()); ?><span class="caret"></span></span></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo fb_url_env('prd'); ?>">Set to: Production</a></li>
						</ul>
					</li>
				</ul>
				
				<div class="hidden-xs hidden-sm">
					<form class="navbar-form navbar-right" action="<?php echo fb_url(array('quicksearch')); ?>" method="post">
						<input type="text" class="form-control" id="searchField" name="searchField"  placeholder="Quick Search...">
					</form>
				</div>
			</div><!--/.nav-collapse -->
		</div>
	</div>

	<div id="body">
