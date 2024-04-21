<?php
require '../vendor/autoload.php';
session_start();

try{
    $dotenv = Dotenv\Dotenv::createImmutable('../');
    $dotenv->load();
    Com\Daw2\Core\FrontController::main();    
} catch (Exception $e) {
    if($_ENV['app.debug']){
        throw $e;
    }
    else{
        echo $e->getMessage();
    }
}