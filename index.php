<?php
    require __DIR__.'/cr-include/error-report.php';
    require __DIR__.'/cr-include/autoloader.php'; 
    require __DIR__.'/cr-include/altorouter.php';

    ob_start();
    session_start();

    $host         = "$_SERVER[HTTP_HOST]";
    $explode_url  = explode('/', $_SERVER[REQUEST_URI]);
    $router       = new AltoRouter();
    // Online Upload with subfolder
    // if($host == 'localhost' || $host == getHostByName(getHostName()) || isset($explode_url[1])) {
    // Online without subfolder or offline (localhost)
    // if($host == 'localhost' || $host == getHostByName(getHostName())) {
    /*
    if($host == 'localhost' || $host == getHostByName(getHostName())) {
        $router->setBasePath('/'.$explode_url[1]);
    }
    */
    $server_name  = $_SERVER['SERVER_NAME'];
    if($server_name == 'localhost') {
        $explode_url  = explode('/', $_SERVER['REQUEST_URI']);
        $subfolder    = $explode_url[1];
        $router->setBasePath('/'.$subfolder);
    }
    else { 
        if(dirname($_SERVER['PHP_SELF']) == '/') {
            $router->setBasePath('');
        }
        else {
            $subfolder = str_replace("/", "", dirname($_SERVER['PHP_SELF']));
            $router->setBasePath('/'.$subfolder);
        }
    }
    require __DIR__.'/cr-include/routes.php';
    $match = $router->match();
    if( $match && is_callable( $match['target'] ) ) {
        call_user_func_array( $match['target'], $match['params'] ); 
    } else {
        // no route was matched
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }
    ob_end_flush();
?>
