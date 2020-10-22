<?php 
define('DATABASE_ROOT_DIRECTORY', strtr(__DIR__, ['\\' => '/']) . '/');

include(DATABASE_ROOT_DIRECTORY . 'blueprint/column.php');
include(DATABASE_ROOT_DIRECTORY . 'blueprint/value.php');
include(DATABASE_ROOT_DIRECTORY . 'blueprint/table.php');

include(DATABASE_ROOT_DIRECTORY . 'interface/builder.php');
include(DATABASE_ROOT_DIRECTORY . 'interface/driver.php');
include(DATABASE_ROOT_DIRECTORY . 'interface/result.php');
include(DATABASE_ROOT_DIRECTORY . 'manager.php');

$install = ['mysql'];

foreach ($install as $product) :
include(DATABASE_ROOT_DIRECTORY . 'drivers/' . $product . '/driver.php');
include(DATABASE_ROOT_DIRECTORY . 'drivers/' . $product . '/builder.php');
include(DATABASE_ROOT_DIRECTORY . 'drivers/' . $product . '/result.php');
endforeach;
