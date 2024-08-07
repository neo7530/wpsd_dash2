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

// Load the language support
require_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
	<meta name="language" content="English" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="pragma" content="no-cache" />
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
	<meta http-equiv="Expires" content="0" />
	<title>WPSD - Digital Voice Dashboard - Advanced Editor</title>
	<link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/browserdetect.php'; ?>
    </head>
    <body>
	<div class="container">
	    <?php include './header-menu.inc'; ?>
	    <div class="contentwide">
		
		<?php
		//Do some file wrangling...
		exec('sudo cp '.$configfile.' '.$tempfile);
		exec('sudo chown www-data:www-data '.$tempfile);
		exec('sudo chmod 664 '.$tempfile);
		
		//ini file to open
		$filepath = $tempfile;
		
		//after the form submit
		if($_POST) {
		    $data = $_POST;
		    
		    if (function_exists('process_before_saving')) {
			process_before_saving($data);
		    }
		    
		    //update ini file, call function
		    update_ini_file($data, $filepath);
		}

    		// Read the INI file contents
    		$ini_content = file_get_contents($filepath);
    		// Set the INI scanner option to treat values as literal strings
    		$parsed_ini = parse_ini_string($ini_content, true, INI_SCANNER_RAW);
	
		echo '<form action="" method="post">'."\n";
		foreach($parsed_ini as $section=>$values) {
		    // keep the section as hidden text so we can update once the form submitted
		    echo "<input type=\"hidden\" value=\"$section\" name=\"$section\" />\n";
		    echo "<table>\n";
		    echo "<tr><th colspan=\"2\">$section</th></tr>\n";
		    // print all other values as input fields, so can edit. 
		    // note the name='' attribute it has both section and key
		    foreach($values as $key=>$value) {
			echo "<tr><td align=\"right\" width=\"30%\">$key</td><td align=\"left\"><input type=\"text\" name=\"{$section}[$key]\" value=\"$value\" /></td></tr>\n";
		    }
		    echo "</table>\n";
		    echo '<input type="submit" value="'.__( 'Apply Changes' ).'" />'."\n";
		    echo "<br />\n";
		}
		echo "</form>";
		?>
	    </div>
	    
	    <div class="footer">
		<a href="https://wpsd.radio/">WPSD</a> &copy; <code>W0CHP</code> 2020-<?php echo date("Y"); ?><br />
	    </div>
	</div>
    </body>
</html>
