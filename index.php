<?php
//Start with a null-set for the debugger
$debugTime = microtime(true);
$debugMemUse = memory_get_usage();

//Define server name
$serverName = $_SERVER['SERVER_NAME'];

//Load the config file
require_once('sites/config/'.$serverName.'.php');

//Registering the null-set 
$mono['debugTimeStart'] = $debugTime;
$mono['debugMemUseStart'] = $debugMemUse;

//Set the pageName
$mono['pageName'] = $_SERVER['REQUEST_URI'];

//Detect if the request is an AJAX request
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $mono['ajaxRequest'] = true;
}

//Load system files
if ($systemDir = opendir($mono['systemDir'])) {
    //Load only the *.php files in the system folder
    while (($file = readdir($systemDir)) !== false) {
        if(preg_match('/.php/', $file)) {
            require_once($mono['systemDir'].$file);
        }
    }
    closedir($systemDir);
}else{
    //Trigger error of the directory if unavailable
    trigger_error('Couldn\'t load the system files');
}

//Load the URI class and define controller, method an parameters
$uri        = new uri($mono);
$controller = $uri->segments[0];
$method     = $uri->segments[1];
$parameters = array_slice($uri->segments, 2);

//Check if cache need to be forced
foreach($mono['cacheForce'] as $cacheForce){
    if($cacheForce[0] == $controller && $cacheForce[1] == $method){
        $mono['cache'] = true;
    }
}

//Create the debug obect
//(everything above this line is not included in the debug console)
$debug = new debug($mono);
$mono['debugObj'] = $debug;

//Lets see if the cache is turned on
if($mono['cache']){
    //Chech if it is an ajax request and if ajax cache is enabled
    if(!$mono['ajaxRequest'] OR ($mono['ajaxRequest'] AND $mono['cacheAjax'])){
        //Check if the requested page is available in cache
        $cache = new cache($mono);
        $mono['cacheFilename'] = md5($mono['pageName']. $mono['ajaxRequest']);
        if($cache->get($mono['cacheFilename'])) {
            if($mono['debug']){
                //Registering that this file is retrieved from cache
                $mono['fromCache'] = true;

                //Start the debugger                
                echo( $mono['debugObj']->getDebug() );
           }
           //Stop everything
           die();
        }
    }
}

//Load the controller
$controllerFile = $mono['siteDir']. 'sites/controllers/'.$mono['siteName'].'/'. $controller. '.php';
if(!file_exists($controllerFile)) {
    $controller = 'core';
}else{
    require_once($controllerFile);
}

//Check if the method exists
if(($method !== 'error' AND method_exists('core', $method)) OR !method_exists($controller, $method) OR !is_callable(array($controller, $method))) {
    $method = 'error';
}

//Sanitize the GET POST and COOKIE input
array_walk_recursive($_GET, 'sanitze');
array_walk_recursive($_POST, 'sanitze');
array_walk_recursive($_COOKIE, 'sanitze');

//Call the controller class and selected method
$controllerObj = new $controller();
$controllerObj->setMonoConfiguration($mono);
call_user_func_array(array($controllerObj, $method), $parameters);
$controllerObj->render();

?>