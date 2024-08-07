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

// Make the bare config if we dont have one
if (! file_exists('/etc/dapnetapi.key')) {
    exec('sudo touch /tmp/jsADGHwf9sj294.tmp');
    exec('sudo chown www-data:www-data /tmp/jsADGHwf9sj294.tmp');
    exec('echo "[DAPNETAPI]" > /tmp/jsADGHwf9sj294.tmp');
    exec('echo "USER=" >> /tmp/jsADGHwf9sj294.tmp');
    exec('echo "PASS=" >> /tmp/jsADGHwf9sj294.tmp');
    exec('echo "TRXAREA=" >> /tmp/jsADGHwf9sj294.tmp');
    exec('echo "MY_RIC=" >> /tmp/jsADGHwf9sj294.tmp');
    exec('sudo chmod 664 /tmp/jsADGHwf9sj294.tmp');

    exec('sudo mount -o remount,rw /');
    exec('sudo mv /tmp/jsADGHwf9sj294.tmp /etc/dapnetapi.key');
    exec('sudo chmod 644 /etc/dapnetapi.key');
    exec('sudo chown root:root /etc/dapnetapi.key');
}

$configfile = '/etc/dapnetapi.key';
$tempfile = '/tmp/jsADGHwf9sj294.tmp';

// This is the function going to update your ini file
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
            if (strcmp($key, 'TRXAREA') == 0)
                $content .= $key."=\"".$value."\"\n";
            else
                $content .= $key."=".$value."\n";
	}
	$content .= "\n";
    }
    
    //write it into file
    if (!$handle = fopen($filepath, 'w')) {
	return false;
    }
    
    $success = fwrite($handle, $content);
    fclose($handle);
    
    // Updates complete - copy the working file back to the proper location
    exec('sudo mount -o remount,rw /');                         // Make rootfs writable
    exec('sudo cp /tmp/jsADGHwf9sj294.tmp /etc/dapnetapi.key'); // Move the file back
    exec('sudo chmod 644 /etc/dapnetapi.key');                  // Set the correct runtime permissions
    exec('sudo chown root:root /etc/dapnetapi.key');            // Set the owner
    
    return $success;
}

require_once('edit_template.php');

?>
