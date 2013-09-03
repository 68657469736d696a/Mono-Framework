<?php
class uri{
    public  $segments   = array();
    private $fullString = '';
    private $mono     = array();
    
    /**
     * Invokes all methods and retrieves the controller, method and parameters 
     * for the page
     * 
     * @param array $mono configurtion array
     */
    public function __construct(&$mono) {
        //Load the mono array
        $this->mono = $mono;
        
        //Set the default controller and method
        $this->set($this->mono['defaultController'], $this->mono['defaultMethod']);
        
        //Get the URI string
        $this->get();

        //If there is an regex for this page. Overrule the URI string
        $this->regexRoute();
        
        //Cut the string in segments
        $this->segment();
    }
    
    /**
     * Check is there is a regex match for the given URI
     */
    private function regexRoute(){
        if(isset($this->mono['routes'])){
            //loop trough the array of regex routes
            foreach($this->mono['routes'] as $route){

                //Check if we have a match
                if(preg_match($route['regex'],$this->fullString, $matches)){

                    //Fake the URI string
                    $this->fullString = $route['controller'].'/'.$route['method'];

                    //Take care of the parameters
                    foreach($route['parameters'] as $param){
                        $this->fullString .= '/'.$matches[$param];
                    }                
                }
            }
        }
    }

    /**
     * Get the URI, clean it and store it
     * 
     * @return  boolean
     */
    private function get() {
        //Try these server vars to get the URI string
        $server = array('PATH_INFO', 'REQUEST_URI', 'ORIG_PATH_INFO');
        
        foreach($server as $item) {
           
            //Check if the server var holds a value
            if(isset($_SERVER[$item]) AND trim($_SERVER[$item])) {

                //Clean the value
                $string = trim($_SERVER[$item], '\\/');
                
                //If the site path (http://example.com/) is still there, remove it
                if($this->mono['sitePath'] != '/') {
                    $string = preg_replace('/^'. preg_quote(trim($this->mono['sitePath'], '\\/'), '/'). '(.+)?/i', '', $string, 1);
                }
                
                //Remove the index.php from the string
                $string = str_replace('index.php', '', $string);
                
                //If there is something left of the URI string -> Store it!
                if(trim($string) != '') {
                    $this->fullString = $string;
                    return true;
                }
            }
        }
    }

    /**
     * Set the controller and method for this page
     * 
     * @param string $controller Controller name
     * @param string $method Method name
     */
    private function set($controller=null, $method=null) {
        //Set the controller if there is one
        if(empty($this->segments[0]) AND $controller) {
            $this->segments[0] = $controller;
        }
        
        //Set the method if there is one
        if(empty($this->segments[1]) AND $method) {
            $this->segments[1] = $method;
        }
        
    }

    /**
     * Segmentate the URI to get the controller, method and parameters
     */
    private function segment() {
        //Explode the URI string into segments
        $segments = explode('/', $this->fullString);
        
        //loop trough the segments
        foreach($segments as $key => $segment) {

            //Replace the not permitted chars
            $segment = preg_replace('/[^'. $this->mono['permittedURIChars']. ']+/i', $this->mono['replaceURIChars'], $segment);
            
            //Store the segment if it holds a value
            if(!empty($segment)){
                $this->segments[$key] = $segment;
            }
        }
    }
}
?>