<?php
class core {
    public $data    = null;
    public $layout  = 'layoutDefault';
    public $mono  = array();
    
    /**
     * Get a reference of the configuration array. This is not placed in the 
     * constructor because it can be overruled by the extending 
     * controller class
     * 
     * @param array $mono Configuration array
     */
    public function setMonoConfiguration(&$mono=null) {
        $this->mono = $mono;
    }
    
    /**
     * Method to load a class into the core class
     * 
     * @param string $class Classname
     * @param string $name Variable to access the class: $this->$name
     * @param array $params Array with parameters for the constructor
     * @param string $path Path to class. Default is models
     * @return boolean
     */
    public function load($class, $name=null, $params=null, $path='models') {
        //If no name is provided -> use class name
        if(!$name) { $name = $class; }
        
        //Check if there is already an object with the same name
        if (isset($this->$name)) {
            trigger_error('An object with the name: '. $name. ' already exists');
            return;
        }
        
        //Define the full path        
        $classFile = $this->mono['siteDir']. '/sites/' . $path. '/'. $class. '.php';
        
        //Check if the file is there
        if (!file_exists($classFile)){
            trigger_error('Unable to locate the file: '. $classFile);
            return;
        }

        //load the file
        require_once($classFile);

        //Check if the class exists
        if (!class_exists($class)) {
            trigger_error('Unable to locate the class: '. $class . ' in file: '. $classFile);
            return;
        }

        //Load the class
        $this->$name = new $class(($params ? $params : ''));
        
        return true;
    }
    
    /**
     * Get a specific view, pass variables to it and return it 
     * as an include or string
     * 
     * @param string $file  Path to the view file (excluding '.php')
     * @param array $variables Variables to pass to the view
     * @param boolean $return true=return output as string, false=include the view
     * @return type
     */
    public function view($file, $variables=null, $return=true) {
        //Define the full path to the view file
        $viewFile = $this->mono['viewDir'].$file. '.php';

        //Check if the view file exists
        if(!file_exists($viewFile)) {
            trigger_error('Unable to load the requested file: '.$viewFile);
            return;
        }

        
        //Define all the variables
        if(is_array($variables)) {
            foreach($variables as $key => $variable) {
                $$key = $variable;
            }
        }
        
        if($return) {
            //Return the view file as a string. 
            //Output buffering is used to make sure that the PHP inside the view file is interpreted
            ob_start();
            include($viewFile);
            $buffer = ob_get_contents();
            ob_end_clean();
            return $buffer;
        } else {
            //include the view file
            include($viewFile);
        }
    }
    
    /**
     * Send an error code in the HTTP header, and load the 
     * corresponding /error/code.php view
     * 
     * Return an error to the browser
     * @param int $type Error code
     */
    public function error($type=404) {
        //Send an error to the browser
        if ($type == 400) {
            header('HTTP/1.0 400 Bad Request');
        } elseif ($type == 401) {
            header('HTTP/1.0 401 Unauthorized');
        } elseif ($type == 403) {
            header('HTTP/1.0 403 Forbidden');
        } elseif ($type == 500) {
            header('HTTP/1.0 500 Internal Server Error');
        } else {
            $type = 404;
            header('HTTP/1.0 404 Not Found');
        }
        $this->data['content'] = $this->view('errors/'. $type);
    }
    
    /**
     * Render the full page, output is echo'd to the screen
     */
    public function render() {
        //Use a different layout file if it is an ajax request
        if($this->mono['ajaxRequest']) {
            $this->layout = 'layoutAjax';
        }
        
        //Get the full html of the requested page
        $output = $this->view($this->layout, $this->data);
        
        //Proceed if the cache is turned on
        if($this->mono['cache']) {
            //Check if it is an ajax request and if ajax cache is enabled
            if(!$this->mono['ajaxRequest'] OR ($this->mono['ajaxRequest'] AND $this->mono['cacheAjax'])){
                //Create the cache file
                $cache = new cache($this->mono);
                $cache->set(md5($this->mono['pageName']. $this->mono['ajaxRequest']), $output);
            }
        }
        
        //Finaly we serve the page
        echo $output;
        
        //If enabled, load the debugger
        if($this->mono['debug']){ 
           echo $this->mono['debugObj']->getDebug();
        }
    }
    
}