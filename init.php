<?php
spl_autoload_register( function( $class_name ) {
    $file_name = __DIR__.'/'.$class_name . '.php';
    $file_name = str_replace('\\', '/', $file_name);
    if( file_exists( $file_name ) ) {
        require_once $file_name;
    }
} );