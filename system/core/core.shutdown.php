<?php 
function shutdown()
{
    $error      = error_get_last();

    $terminate  = function(){};

    /** 
     *
     * catch error
     *
	 */
    if( is_array($error) ) {
    	
    	ob_get_clean();

		writelog($error);

        $terminate = function() use ($error) {

            header('Content-Type: application/json');

            $error['message'] = explode('#', $error['message']);

            echo json_encode($error);

            exit;

        };

    }

    if(isset($GLOBALS['extension'])) {
            
        $extension = $GLOBALS['extension'];

        if( $extension->shutdown instanceof Closure ) {
            $shutdown = $extension->shutdown;
            $shutdown($terminate, $error);
        }

    }

    $terminate();

}

register_shutdown_function('shutdown');