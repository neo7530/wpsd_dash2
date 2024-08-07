<?php

if (!isset($_SESSION) || !is_array($_SESSION)) {
    session_id('wpsdsession');
    session_start();
}

// Load the language support
require_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';

// Sanity Check that this file has been opened correctly
if ($_SERVER["PHP_SELF"] == "/admin/advanced/upgrade.php") {
    
    if (!isset($_GET['ajax'])) {
	system('sudo touch /var/log/pi-star/pi-star_upgrade.log > /dev/null 2>&1 &');
	system('sudo echo "" > /var/log/pi-star/pi-star_upgrade.log > /dev/null 2>&1 &');
	system('sudo /usr/local/sbin/pistar-upgrade > /dev/null 2>&1 &');
    }
    
    // Sanity Check Passed.
    header('Cache-Control: no-cache');
    
    if (!isset($_GET['ajax'])) {
	//unset($_SESSION['update_offset']);
	if (file_exists('/var/log/pi-star/pi-star_upgrade.log')) {
	    $_SESSION['update_offset'] = filesize('/var/log/pi-star/pi-star_upgrade.log');
	}
	else {
	    $_SESSION['update_offset'] = 0;
	}
    }
    
    if (isset($_GET['ajax'])) {
	if (!file_exists('/var/log/pi-star/pi-star_upgrade.log')) {
	    exit();
	}
	
	if (($handle = fopen('/var/log/pi-star/pi-star_upgrade.log', 'rb')) != FALSE) {
	    if (isset($_SESSION['update_offset'])) {
		fseek($handle, 0, SEEK_END);
		if ($_SESSION['update_offset'] > ftell($handle)) { //log rotated/truncated
		    $_SESSION['update_offset'] = 0; //continue at beginning of the new log
		}
		$data = stream_get_contents($handle, -1, $_SESSION['update_offset']);
		$_SESSION['update_offset'] += strlen($data);
		echo nl2br($data);
	    }
	    else {
		fseek($handle, 0, SEEK_END);
		$_SESSION['update_offset'] = ftell($handle);
	    }
	    fclose($handle);
	}
	exit();
    }
    
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
	<title>WPSD <?php echo __( 'Digital Voice' ) . " ".__( 'Dashboard' )." - ".__( 'WPSD Update' );?></title>
	<link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/browserdetect.php'; ?>
	<link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
	<script type="text/javascript" src="/js/jquery.min.js?version=<?php echo $versionCmd; ?>"></script>
	<script type="text/javascript" src="/js/jquery-timing.min.js?version=<?php echo $versionCmd; ?>"></script>
	<script type="text/javascript">
	 $(function() {
	     $.repeat(1000, function() {
		 $.get('/admin/advanced/upgrade.php?ajax', function(data) {
		     if (data.length < 1) return;
		     var objDiv = document.getElementById("tail");
		     var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
		     $('#tail').append(data);
		     if (isScrolledToBottom)
			 objDiv.scrollTop = objDiv.scrollHeight;
		 });
	     });
	 });
	</script>
    </head>
    <body>
	<div class="container">
	    <?php include './header-menu.inc'; ?>
	    <div class="contentwide">
		<table width="100%">
		    <tr><th>Upgrade is Running</th></tr>
		    <tr><td align="left"><div id="tail">Starting upgrade, please wait...<br /></div></td></tr>
		</table>
	    </div>
	    <div class="footer">
		<a href="https://wpsd.radio/">WPSD</a> &copy; <code>W0CHP</code> 2020-<?php echo date("Y"); ?><br />
	    </div>
	</div>
    </body>
</html>

<?php
}
?>
