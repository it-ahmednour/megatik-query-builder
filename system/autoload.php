<?php defined('DIRECT') OR exit('Direct Access Not Allowed');

## System Constans Configration
require_once('config.php');

## AutoLoad Registered Classes
spl_autoload_register(function ($className) {
    $file = __DIR__ . '/classes/' . $className .'.Class.php';
    if (file_exists($file)) {
        require $file;
    }else{
        die($className . '.Class.php Not Found !!');
    }
});

?>