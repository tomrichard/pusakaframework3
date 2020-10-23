<?php 
namespace Pusaka\Debugger\Service;

class Debug {

	public static function run( $error, $open = false ) {

		$extension = get_extension();

		if(!$extension->is_active('pusaka.debugger')) {
			return;
		}

		if(is_production()) {
			return;
		}

		if(APPLICATION_TYPE === 'MICROSERVICE') {
			return;
		}

		$open 	= $open ? 'active' : '';

		$logdir = ROOTDIR . 'storage/private/logs/';

		$time 	= microtime(true) - BENCHMARK_START;
		$memory = memory_get_peak_usage() - BENCHMARK_MEMORY;
		$files 	= count(get_included_files());

		$memprc = get_server_memory_usage();
		$memprc = number_format ( $memprc, 1 );
		$cpuprc = get_server_cpu_usage(true);

		// Total Time
		$time 	= number_format ( $time, 5 );
		// Total memory
	    $memory = round($memory / 1024) . ' KB';

	    $explr 	= '';
	    $logs 	= '';

	    $views 	= $GLOBALS['__Views'] ?? [];

	    foreach ( $views as $i => $view ) {
	    	$explr .= 
	    	'
	    	<li style="
	    		padding: 4px; 
	    		border: 1px solid #AAA; 
	    		border-radius: 5px; 
	    		margin-bottom: 4px;
	    		background: #f4f4f4;">
	    		<label style="width: 35px; display: inline-block;">'.($i+1).'.</label> 
	    		<a style="word-break: break-all;" href="subl://'.$view.'">'.$view.'</a>
	    	</li>
	    	';
	    }

		$flogs 		= glob($logdir . "*.log");

		$flogs 		= array_reverse( $flogs );

		$dot_vars 	= dot_vars();

		$debug_port = $dot_vars->debug_port ?? 3080;

		foreach ( $flogs as $i => $file) {

			$logs .= 
	    	'
	    	<li style="
	    		padding: 4px; 
	    		border: 1px solid #AAA; 
	    		border-radius: 5px; 
	    		margin-bottom: 4px;
	    		background: #ee9898;">
	    		<label style="width: 35px; display: inline-block; color: #860e0e;">'.($i+1).'.</label> 
	    		<a style="color: #860e0e; word-break: break-all;" href="subl://'.$file.'">'.$file.'</a>
	    	</li>
	    	';

		}

		echo '

		<style>
		#__pusaka_benchmark_toggle_box {
			width: 0px;
			height: 0px;
			display: none;
		}
		#__pusaka_benchmark_toggle_box.active {
			width: 300px;
			height: calc(100vh);
			display: block;
		}
		</style>

		<div style="
			position: fixed; 
			padding: 0px; 
			display: flex;
			top: 0px; 
			left: 0px; 
			color: #444; 
			text-align: left;
			z-index: 9999999;">
			<div id="__pusaka_benchmark_toggle_button" 
				style="
					padding: 0px;
					margin: 0px;
					cursor: pointer;  
					background: red; 
					width: 10px; height: 10px;">
			</div>
		</div>

		<div style="
			position: fixed; 
			padding: 0px 0px 0px 0px; 
			top: 0px; left: 0px; 
			color: #444; 
			text-align: left; 
			background: rgba(220, 220, 220, 0.5)">

			<div id="__pusaka_benchmark_toggle_box" class="'.$open.'"
				style="padding: 12px 0px 0px 9px; text-align: left;">

				<table style="text-align: left;">
					<tr>
						<td>Version</td>
						<td> : </td>
						<td>'.PUSAKA_VERSION.'</td>
					</tr>
					<tr>
						<td>Execute Time</td>
						<td> : </td>
						<td>'.$time.'</td>
					</tr>
					<tr>
						<td>Memory Usage</td>
						<td> : </td>
						<td>'.$memory.'</td>
					</tr>
					<tr>
						<td>Memory</td>
						<td> : </td>
						<td>'.$memprc.' / 100
							<i></i>
						</td>
					</tr>
					<tr>
						<td>CPU</td>
						<td> : </td>
						<td>'.($cpuprc['load'] ?? '').' / 100 
							<i></i>
						</td>
					</tr>
					<tr>
						<td>Files Load</td>
						<td> : </td>
						<td>'.$files.' 
							<i></i>
						</td>
					</tr>
				</table>

				<div style="padding: 4px; display: flex; flex-direction: column;">
					<div>Explorer :</div>
					<div style="width: 100%; height: 300px; overflow: auto;">
						<ul style="list-style: none; margin: 0px; padding: 4px 0px 0px 0px;">
							'.$explr.'
						</ul>
					</div>
				</div>

				<div style="padding: 4px; display: flex; flex-direction: column;">
					<div style="color: red; font-weight: bold;">Logs :</div>
					<div style="width: 100%; height: 100px; overflow: auto;">
						<ul style="list-style: none; margin: 0px; padding: 4px 0px 0px 0px;">
							'.$logs.'
						</ul>
					</div>
				</div>

			</div>

		</div>

		<script src="'.url('assets/scripts/socket.io.js').'"></script>

		<script>
			(function(){
				var a = document.querySelector("#__pusaka_benchmark_toggle_button");
				var b = document.querySelector("#__pusaka_benchmark_toggle_box");
				a.onclick = function(e) {
					b.classList.toggle("active");
				}
			})();

			(function(){
				
				var isreload 	= false;

				var socket 		= io("http://localhost:'.$debug_port.'");
			
				socket.on("reload", (socket) => {

					if(!isreload) {
						location.reload();
						isreload = true;
					}

				});

			})();
		</script>
		';

		if( !empty($error) ) {

			echo 
			'<style>
			.p-debugger {
				list-style: none;
			}
			.p-debugger li {
				width: 70%;
				background: #DDD;
				margin-bottom: 4px;
				border-radius: 5px;
			}
			.p-debugger .p-key {
				font-weight: bold;
				padding: 4px;
			}
			.p-debugger .p-val {
				background: #EEE;
				border-radius: 5px;
				padding: 4px;
			}
			</style>
			';

			echo '<ul class="p-debugger">';

			foreach($error as $key => $err ) {

				if(is_array($err)) {
					$err = json_encode($err, JSON_PRETTY_PRINT);
				}

				echo 
					'<li>
						<div class="p-key"> - '.$key.'</div>
						<div class="p-val"><pre>'.$err.'</pre></div> 
					</li>';

			}

			echo '</ul>';

		}

		exit;

	}

}