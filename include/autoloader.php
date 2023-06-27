<?php
    session_start();

    require_once "database/database.php";

    function my_autoloader($class){
        $file = "managers/$class.php";

        if (file_exists($file)){
            require_once "$file";
        }
    }

    spl_autoload_register('my_autoloader');
?>