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

$configfile = '/etc/ysf2dmr';
$tempfile = '/tmp/MNhQn9HUvpNPgp.tmp';

// this is the function going to update your ini file
function update_ini_file($data, $filepath) {
    $content = "";
    
    // Read the INI file contents
    $ini_content = file_get_contents($filepath);
    // Set the INI scanner option to treat values as literal strings
    $parsed_ini = parse_ini_string($ini_content, true, INI_SCANNER_RAW);
 
    foreach($data as $section=>$values) {
	// UnBreak special cases
	if (strpos($section, 'aprs') !== false) { $section = str_replace("_", ".", $section); }
	else { $section = str_replace("_", " ", $section); $section = str_replace(".", " ", $section); }
	$content .= "[".$section."]\n";
	//append the values
	foreach($values as $key=>$value) {
	    if ( (($section == "DMR Network" && $key == "Options") ||
		  ($section == "DMR Network" && $key == "Password") ||
		  ($section == "Info" && (($key == "Location") || ($key == "Description"))))
	      && $value) {
		$value = str_replace('"', "", $value);
		$content .= $key."=\"".$value."\"\n";
	    }
	    else {
		$content .= $key."=".$value."\n";
	    }
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
    exec('sudo mount -o remount,rw /');				// Make rootfs writable
    exec('sudo cp /tmp/MNhQn9HUvpNPgp.tmp /etc/ysf2dmr');	// Move the file back
    exec('sudo chmod 644 /etc/ysf2dmr');				// Set the correct runtime permissions
    exec('sudo chown root:root /etc/ysf2dmr');			// Set the owner
    
    // Reload the affected daemon
    exec('sudo systemctl restart ysf2dmr.service');		// Reload the daemon
    return $success;
}

require_once('edit_template.php');

?>
