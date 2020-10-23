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

</head>
<body>

	<?php $this->scripts(); ?>

	<?php (function($vars) { ?>

		<?php $__id = strtoupper('ID'.date('YmdHis').uniqid()); ?>

		<?php extract($vars) ?>

		<div id="<?=$__id?>">
			
			<div style="text-align: center">
			    <h1><?php echo $count ?></h1>
			    <button x-click="increment">+</button>
			</div>

		</div>

		<script>
		(function(){

			var id 		= '<?php echo $__id ?>';
			var url 	= '<?php echo url("easyui/counter/") ?>';
			var vars 	= '<?php echo json_encode($vars) ?>';

			vars		= JSON.parse(vars);

			// render event
			var renderEvent			= function() {

				var dom 		= document.getElementById(id);

				try {

					var evtClick 	= document.querySelectorAll('[x-click]');

					evtClick.forEach(function( evt ){

						evt.addEventListener('click', function(e){
							
							getResponseXmlHttp(evt.getAttribute('x-click'));

						});

					});

				}catch(e){}

			};

			var getResponseXmlHttp = function(action) {

				var xmlhttp = new XMLHttpRequest();

				xmlhttp.onreadystatechange = function() {
					
					if (this.readyState == 4 && this.status == 200) {
						
						var response = JSON.parse(this.responseText);

						vars 		 = response.vars;

						document.getElementById(id).innerHTML = response.render;

						renderEvent();

					}

				};

				xmlhttp.open("GET", url + "?action=" + action + '&vars=' + btoa(JSON.stringify(vars)), true);
				xmlhttp.send();

			};

			renderEvent(); // init event

		})();
		</script> 

	<?php })(['count' => 1]) ?>

</body>
</html>