<?php
//Directory pertaining to root directory, always surrounded by '/'
//If it's located in the root directory use '/'
$mono['sitePath'] = '/Mono-framework/';

//Should be corresponding to a 'sites/view/' and 'sites/controller' sub-directory
//The site name is only for internal use
$mono['siteName'] = 'default';

//The default controller (used if none is selected / frontpage)
$mono['defaultController'] = 'home';

//The default method (used if none is selected / frontpage)
$mono['defaultMethod'] = 'index';

//The URI chars that are allowed
$mono['permittedURIChars'] = 'a-z 0-9~%.:_\-';

//Replace disallowed chars with this one
$mono['replaceURIChars'] = '_';

//Enable or disable Cache (true = enabled, false = disabled)
$mono['cache'] = false;

//Cache lifetime (in seconds)
$mono['cacheTime'] = 10;

//Should ajax pages be cached? (true = yes, false = no)
$mono['cacheAjax'] = false;

//Enable or disable debug (true = enabled, false = disabled)
$mono['debug'] = true;

//Debug for ajax sites (true = enabled, false = disabled)
$mono['debugAjax'] = false;

//true = Console ail be displayd
//false = Console will be invisible (commented out) information will be available trough the 'view-source' functionality of your browser
$mono['debugSilent'] = false;


//Define custom regex routes. These will overrule the /controller/method structure
$mono['routes'][] = array('regex' => '/example\/+([0-9])/',         //the regex route
                                        'controller'    => 'home',    //Controller to be used
                                        'method'        => 'index',   //Method to be used
                                        'parameters'    => array(1)   //Parameter(s) that will be passed to the method 
                                                                      // [0] => 'example', [1] => some number ([0-9])
                                    );


// no need to replace anything below here (unless you know what you are doing)
//---------------------------------------------------------------------------------------------------//
$mono['siteDir']          = rtrim(realpath(dirname(__FILE__). '/../../'), '/\\'). '/';
$mono['viewDir']          = $mono['siteDir']. 'sites/views/'.$mono['siteName'].'/';
$mono['viewPath']         = $mono['sitePath']. 'sites/views/'.$mono['siteName'].'/';
$mono['cacheDir']         = $mono['siteDir']. 'cache/';
$mono['systemDir']        = $mono['siteDir']. 'system/';
$mono['modelsDir']        = $mono['siteDir']. '/sites/models/';
$mono['pageName']         = null;
$mono['ajaxRequest']      = false;
$mono['fromCache']        = false;
$mono['cacheFilename']    = '';
$mono['debugTimeStart']   = 0;
$mono['debugTimeStop']    = 0;
$mono['debugMemUseStart'] = 0;
$mono['debugMemUseStop']  = 0;
?>