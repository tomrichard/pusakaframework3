<!--------- ============================ ----------------->
<?php (function( {param} ){ ?>
<?php $__json = file_get_contents(url('easyui/{component_url}')); ?>
<?php $__json = json_decode($__json); ?>

<div 
	eui-component="<?php echo 'euic'.strtolower(date('YmdHis').uniqid()) ?>" 
	eui-url="<?php echo url('easyui/{component_url}') ?>" 
	eui-token="<?php echo $__json->token ?>" >
<?php echo $__json->render ?? ''; ?>
</div>

<?php })( {value} ); ?> 
<!--------- ============================ ---------------->