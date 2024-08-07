<?php

if (!isset($_SESSION) || !is_array($_SESSION)) {
    session_id('wpsdsession');
    session_start();
    
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';        // Translation Code
    checkSessionValidity();
}

$configfile = '/etc/aprsgateway';
$service = 'aprsgateway.service';

$tempfile = '/tmp/oDFuttgksHSRb8.tmp';

//this is the function going to update your ini file
function update_ini_file($data, $filepath) {
    $content = "";
   
    // Read the INI file contents
    $ini_content = file_get_contents($filepath);
    // Set the INI scanner option to treat values as literal strings
    $parsed_ini = parse_ini_string($ini_content, true, INI_SCANNER_RAW); 
    
    foreach($data as $section=>$values) {
	// UnBreak special cases
	$section = str_replace("_", " ", $section);
	$content .= "[".$section."]\n";
	//append the values
	foreach($values as $key=>$value) {
	    $content .= $key."=".$value."\n";
	}
	$content .= "\n";
    }
    
    // write it into file
    if (!$handle = fopen($filepath, 'w')) {
	return false;
    }
    
    $success = fwrite($handle, $content);
    fclose($handle);

    // Updates complete - copy the working file back to the proper location
    exec('sudo mount -o remount,rw /');
    exec('sudo cp /tmp/oDFuttgksHSRb8.tmp /etc/aprsgateway');
    exec('sudo chmod 644 /etc/dapnetgateway');
    exec('sudo chown root:root /etc/dapnetgateway');

    // Reload the affected daemon
    exec('sudo systemctl restart aprsgateway.service');
    return $success;    
}

require_once('edit_template.php');

?>
