<?php

require __DIR__ . '/config.php';
require __DIR__ . '/utils.php';
require __DIR__ . '/version.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    
    if($_SERVER['REQUEST_URI'] == "/api/script/execute") {
           
        Utils::AuthenticateReq();
        
        Utils::ExecuteScript();
    }
    elseif($_SERVER['REQUEST_URI'] == "/api/version") {
        
        header('Content-type: application/json');
        echo json_encode(["version" => $apiVersion]);
        exit;
    }
    elseif($_SERVER['REQUEST_URI'] == "/api/script") {
        
        Utils::AuthenticateReq();
        
        header('Content-type: application/json');
        echo json_encode($config['scripts']);
        exit;
    }
    elseif($_SERVER['REQUEST_URI'] == "/api/login") {
        
        Utils::Login();
      
    }
    else {
        
        readfile('index.html');
        exit;
    }
    
} catch (\Exception $ex) {
    
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-type: application/json');
    echo json_encode(["message" => $ex->getMessage()]);
    exit;
}