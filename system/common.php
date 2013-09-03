<?php
//Library of commonly-used functions.
//Add more functions as you like, they will be available through the entire site

/**
 * Sanitizes the (reference) input string
 * 
 * @param string $value Input string (reference)
 */
function sanitze(&$value) {
    //Add or remove filtering as you like
    $value = htmlspecialchars($value);
    $value = stripslashes($value);
    //$value = mysql_real_escape_string($value);
}
    
?>