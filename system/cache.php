<?php
class cache {
    private $mono = array();

    /**
     * Get a reference of the configuration array
     * 
     * @param array $mono configuration array
     */
    public function __construct(&$mono=null) {
        $this->mono = $mono;
    }

    /**
     * Echo the cache file to the screen (if it exists)
     * 
     * @param string $file file name
     * @return boolean
     */
    public function get($file='') {        
        //Define the path for the cache file
        $cacheFile = $this->mono['cacheDir']. $file. '.php';
        
        if(file_exists($cacheFile) AND ((time() - filemtime($cacheFile)) < $this->mono['cacheTime'])) {
            //Echo the content if the file exists and isn't expired
            echo(file_get_contents($cacheFile));
            return true;
        }else{
            //File doesn't exist or is expired
            return false;
        }
    }
    
    /**
     * Create a new (version of the) cache file
     * 
     * @param string $file File name
     * @param string $contents Page content
     * @return boolean
     */
    public function set($file=null, $contents=null) {
        //Stop inmidiatily if there is no file or content
        if(!$file OR !$contents) { return; }
        
        //Define the full cache file path
        $path = $this->mono['siteDir']. 'cache/'. $file. '.php';
        
        //Open the file or create a new file if it doesn't exist
        if (!$handle = fopen($path, 'w')) {
            //trigger an error if the rights aren't set properly
            trigger_error('Cannot open/create cache file ('. $path. ')');
            return;
        }
        
        //Write the cache file
        if (fwrite($handle, $contents) === FALSE) {
            //trigger an error if the rights aren't set properly
            trigger_error('Cannot write to cache file: '.$path);
            return;
        }
        
        fclose($handle);
        return true;
    }
}
