<!DOCTYPE html><html lang="en"><head>	<meta charset="utf-8" />	<meta http-equiv="X-UA-Compatible" content="IE=edge" />	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />	<meta name="description" content="" />	<meta name="author" content="" />	<title>Welcome to Pusaka</title>	<style type="text/css">	* {		font-family: Calibri;		color: #666;	}	body {		padding: 64px 0px 0px 0px;		margin: 0px;		text-align: center;		background: #EEE;	}	</style></head><body>	<!--------- ============================ -----------------><?php (function( $theme ){ ?><?php $__json = file_get_contents(url('easyui/counter')); ?><?php $__json = json_decode($__json); ?><div 	eui-component="<?php echo 'euic'.strtolower(date('YmdHis').uniqid()) ?>" 	eui-url="<?php echo url('easyui/counter') ?>" 	eui-token="<?php echo $__json->token ?>" ><?php echo $__json->render ?? ''; ?></div><?php })( "white" ); ?> <!--------- ============================ ----------------><!--------- ============================ -----------------><?php (function(  ){ ?><?php $__json = file_get_contents(url('easyui/search')); ?><?php $__json = json_decode($__json); ?><div 	eui-component="<?php echo 'euic'.strtolower(date('YmdHis').uniqid()) ?>" 	eui-url="<?php echo url('easyui/search') ?>" 	eui-token="<?php echo $__json->token ?>" ><?php echo $__json->render ?? ''; ?></div><?php })(  ); ?> <!--------- ============================ ---------------->	<?php $this->scripts(); ?></body></html>