<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<title>Welcome to Pusaka</title>

	<style type="text/css">
	* {
		font-family: Calibri;
		color: #666;
	}
	body {
		padding: 64px 0px 0px 0px;
		margin: 0px;
		text-align: center;
		background: #EEE;
	}
	</style>

	<?php $this->scripts(); ?>

</head>
<body>
	
	<h1><?php echo $greeting ?></h1>

	<?php $this->view('extend') ?>

	<?php debug(true) ?>

</body>
</html>