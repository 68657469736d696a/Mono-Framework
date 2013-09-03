<?php
class debug{    
    private $mono;

    /**
     * Get a reference of the configuration array
     * 
     * @param array $mono configuration array
     */
    public function __construct(&$mono) {
        $this->mono = $mono;
    }
    
    /**
     * Start the debug console
     * 
     * @return string Full HTML of the debug console
     */
    public function getDebug(){
        //First thing is to set the end values for all the meters
        //This prevents the debug process from being measured
        $this->setEndValues();
        
        $console = '';

        //Lets see if it is an ajax request and if debugging is still necessary
        if(!$this->mono['ajaxRequest'] OR ($this->mono['ajaxRequest'] AND $this->mono['debugAjax'])){
            $console .= $this->getHTML();
        }
        
        //Comment out in silent mode
        if($this->mono['debugSilent']){
          $console = $this->setCommentOut($console);
        }
        
        //pass the console back
        return $console;
    }
    
    /**
     * Stop all timers
     */
    private function setEndValues(){
        //Set the end value for time and memory usage
        $this->mono['debugTimeStop'] = microtime(true);
        $this->mono['debugMemUseStop'] = memory_get_usage();
    }
    
    /**
     * Returns the memory usage in a given unit. Default is bytes
     * 
     * @param string $unit Units for the output (b, kb, mb)
     * @return int
     */
    private function getMemoryUsed($unit = 'b'){
        //Return the used memory
        if($unit == 'kb'){
            //selected unit: kilobytes
            $mem = round(($this->mono['debugMemUseStop'] - $this->mono['debugMemUseStart']) / 1024, 2);
        }elseif($unit == 'mb'){
            //selected unit: kilobytes
            $mem = round((($this->mono['debugMemUseStop'] - $this->mono['debugMemUseStart']) / 1024) / 1024, 2);
        }else{
            //Default unit: bytes
            $mem = ($this->mono['debugMemUseStop'] - $this->mono['debugMemUseStart']);
        }
        return $mem;
    }
    
    /**
     * Returns the render time in seconds
     * 
     * @return int
     */
    private function getRenderTime(){
        //return the render time in seconds
        $time = round(($this->mono['debugTimeStop'] - $this->mono['debugTimeStart']), 5);
        return $time;
    }
    
    /**
     * Place comment markers around the input string
     * 
     * @param string $value String to comment out
     * @return string
     */
    private function setCommentOut($value){
        return '<!--'. $value .'-->';
    }
    
    /**
     * Returns a string with the HTML of the debug console
     * 
     * @return string
     */
    private function getHTML(){
        $console = '<div style="background:#000000; color:#00FF00; position: fixed; right: 10px; bottom:10px; width: 600px; height: 200px; overflow: scroll; font-family: courier; font-size: 13px; line-height: 16px; padding: 3px;">';
        $console .= '<p>Render time: '. $this->getRenderTime() .' seconds <br>';
        $console .= 'Memory usage '. $this->getMemoryUsed('mb') .'MB ';
        $console .= '('. $this->getMemoryUsed('kb') . 'KB';
        $console .= ' - '. $this->getMemoryUsed() . 'B).</p>';
        
        if($this->mono['fromCache']){
            $console .= '<p>This page is loaded from the cache. Original file: '.$this->mono['cacheFilename'] .'.php</p>';
        }else{
            $console .= '<p>This page is not loaded from the cache</p>';
        }
        
        $console .= '<b>Files included:</b>';
        $console .= '<ul>';
        $console .= ' <li>'. implode("</li>\n<li>", get_included_files()) .'</li>';
        $console .= '</ul>';
        
        $console .= '</div>';
        
        return $console;
    }
    
}
?>