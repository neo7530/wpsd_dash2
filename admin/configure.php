<?php
session_set_cookie_params(0, "/");
session_name("WPSD_Session");
session_id('wpsdsession');
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/ircddblocal.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';

// Clear session data (page {re}load);
unset($_SESSION['BMAPIKey']);
unset($_SESSION['DAPNETAPIKeyConfigs']);
unset($_SESSION['PiStarRelease']);
unset($_SESSION['MMDVMHostConfigs']);
unset($_SESSION['ircDDBConfigs']);
unset($_SESSION['timeServerConfigs']);
unset($_SESSION['DMRGatewayConfigs']);
unset($_SESSION['YSFGatewayConfigs']);
unset($_SESSION['DGIdGatewayConfigs']);
unset($_SESSION['DAPNETGatewayConfigs']);
unset($_SESSION['YSF2DMRConfigs']);
unset($_SESSION['YSF2NXDNConfigs']);
unset($_SESSION['YSF2P25Configs']);
unset($_SESSION['DMR2YSFConfigs']);
unset($_SESSION['DMR2NXDNConfigs']);
unset($_SESSION['APRSGatewayConfigs']);
unset($_SESSION['NXDNGatewayConfigs']);
unset($_SESSION['P25GatewayConfigs']);
unset($_SESSION['CSSConfigs']);
unset($_SESSION['DvModemFWVersion']);
unset($_SESSION['DvModemTCXOFreq']);
unset($_SESSION['M17GatewayConfigs']);
unset($_SESSION['ModemConfigs']);

checkSessionValidity();

// Load the pistar-release file
$pistarReleaseConfig = '/etc/pistar-release';
$configPistarRelease = parse_ini_file($pistarReleaseConfig, true);

// Load the ircDDBGateway config file
$configs = array();
if ($configfile = fopen($gatewayConfigPath,'r')) {
        while ($line = fgets($configfile)) {
		if (strpos($line, '=') !== false) {
                	list($key,$value) = preg_split('/=/',$line);
                	$value = trim(str_replace('"','',$value));
                	if ($key != 'ircddbPassword' && strlen($value) > 0)
                	$configs[$key] = $value;
		}
        }
        fclose($configfile);
}

// Load the mmdvmhost config file
$mmdvmConfigFile = '/etc/mmdvmhost';
$configmmdvm = parse_ini_file($mmdvmConfigFile, true);

// Load the ysfgateway config file
$ysfgatewayConfigFile = '/etc/ysfgateway';
$configysfgateway = parse_ini_file($ysfgatewayConfigFile, true);

// Load the ysf2dmr config file
if (file_exists('/etc/ysf2dmr')) {
	$ysf2dmrConfigFile = '/etc/ysf2dmr';
	if (fopen($ysf2dmrConfigFile,'r')) { $configysf2dmr = parse_ini_file($ysf2dmrConfigFile, true); }
}

// Load the ysf2nxdn config file
if (file_exists('/etc/ysf2nxdn')) {
	$ysf2nxdnConfigFile = '/etc/ysf2nxdn';
	if (fopen($ysf2nxdnConfigFile,'r')) { $configysf2nxdn = parse_ini_file($ysf2nxdnConfigFile, true); }
}

// Load the ysf2p25 config file
if (file_exists('/etc/ysf2p25')) {
	$ysf2p25ConfigFile = '/etc/ysf2p25';
	if (fopen($ysf2p25ConfigFile,'r')) { $configysf2p25 = parse_ini_file($ysf2p25ConfigFile, true); }
}

// Load the dgidgateway config file
if (file_exists('/etc/dgidgateway')) {
	$dgidgatewayConfigFile = '/etc/dgidgateway';
	if (fopen($dgidgatewayConfigFile,'r')) { $configdgidgateway = parse_ini_file($dgidgatewayConfigFile, true); }
}

// Load the dmr2ysf config file
if (file_exists('/etc/dmr2ysf')) {
	$dmr2ysfConfigFile = '/etc/dmr2ysf';
	if (fopen($dmr2ysfConfigFile,'r')) { $configdmr2ysf = parse_ini_file($dmr2ysfConfigFile, true); }
}

// Load the dmr2nxdn config file
if (file_exists('/etc/dmr2nxdn')) {
	$dmr2nxdnConfigFile = '/etc/dmr2nxdn';
	if (fopen($dmr2nxdnConfigFile,'r')) { $configdmr2nxdn = parse_ini_file($dmr2nxdnConfigFile, true); }
}

// Load the dmr2nxdn config file
if (file_exists('/etc/dmr2m17')) {
	$dmr2m17ConfigFile = '/etc/dmr2m17';
	if (fopen($dmr2m17ConfigFile,'r')) { $configdmr2m17 = parse_ini_file($dmr2m17ConfigFile, true); }
}

// Load the p25gateway config file
if (file_exists('/etc/p25gateway')) {
	$p25gatewayConfigFile = '/etc/p25gateway';
	if (fopen($p25gatewayConfigFile,'r')) { $configp25gateway = parse_ini_file($p25gatewayConfigFile, true); }
}

// Load the nxdngateway config file
if (file_exists('/etc/nxdngateway')) {
	$nxdngatewayConfigFile = '/etc/nxdngateway';
	if (fopen($nxdngatewayConfigFile,'r')) { $confignxdngateway = parse_ini_file($nxdngatewayConfigFile, true); }
}

// Load the M17Gateway config file
if (file_exists('/etc/m17gateway')) {
	$m17gatewayConfigFile = '/etc/m17gateway';
	if (fopen($m17gatewayConfigFile,'r')) { $configm17gateway = parse_ini_file($m17gatewayConfigFile, true); }
}

// Load the nxdn2dmr config file
if (file_exists('/etc/nxdn2dmr')) {
	$nxdn2dmrConfigFile = '/etc/nxdn2dmr';
	if (fopen($nxdn2dmrConfigFile,'r')) { $confignxdn2dmr = parse_ini_file($nxdn2dmrConfigFile, true); }
}

// DAPNet Gateway config
if (file_exists('/etc/dapnetgateway')) {
	$configDAPNetConfigFile = '/etc/dapnetgateway';
	if (fopen($configDAPNetConfigFile,'r')) { $configdapnetgw = parse_ini_file($configDAPNetConfigFile, true); }
}

// APRS Gateway config
if (file_exists('/etc/aprsgateway')) {
	$configAPRSconfigFile = '/etc/aprsgateway';
	if (fopen($configAPRSconfigFile,'r')) { $configaprsgateway = parse_ini_file($configAPRSconfigFile, true); }
}

// Load the dmrgateway config file
$dmrGatewayConfigFile = '/etc/dmrgateway';
if (fopen($dmrGatewayConfigFile,'r')) { $configdmrgateway = parse_ini_file($dmrGatewayConfigFile, true); }

// Load the modem config information
if (file_exists('/etc/dstar-radio.mmdvmhost')) {
	$modemConfigFileMMDVMHost = '/etc/dstar-radio.mmdvmhost';
	if (fopen($modemConfigFileMMDVMHost,'r')) { $configModem = parse_ini_file($modemConfigFileMMDVMHost, true); }
} else { // init on 1st load
	system('sudo touch /etc/dstar-radio.mmdvmhost');
}

//
// Old Mobile GPS conf conversion stuff
//
// Delete the old MobileGPS config file
if (file_exists('/etc/mobilegps'))
{
    exec('sudo rm -f /etc/mobilegps');
}
// Convert MMDVMHost config file
if (isset($configmmdvm['Mobile GPS'])) {
    if (isset($configmmdvm['Mobile GPS']['Enable'])) {
	$configmmdvm['GPSD']['Enable'] = $configmmdvm['Mobile GPS']['Enable'];
	unset($configmmdvm['Mobile GPS']['Enable']);
    }
    
    if (isset($configmmdvm['Mobile GPS']['Address'])) {
	unset($configmmdvm['Mobile GPS']['Address']);
    }
    
    if (isset($configmmdvm['Mobile GPS']['Port'])) {
	unset($configmmdvm['Mobile GPS']['Port']);
    }
    
    unset($configmmdvm['Mobile GPS']);
}

// YSF Gateway config file
if (isset($configysfgateway['Mobile GPS'])) {
    if (isset($configysfgateway['Mobile GPS']['Enable'])) {
	unset($configysfgateway['Mobile GPS']['Enable']);
    }
    
    if (isset($configysfgateway['Mobile GPS']['Address'])) {
	unset($configysfgateway['Mobile GPS']['Address']);
    }
    
    if (isset($configysfgateway['Mobile GPS']['Port'])) {
	unset($configysfgateway['Mobile GPS']['Port']);
    }
    
    unset($configysfgateway['Mobile GPS']);
}
// NXDN Gateway config file
if (isset($confignxdngateway['Mobile GPS'])) {
    if (isset($confignxdngateway['Mobile GPS']['Enable'])) {
	unset($confignxdngateway['Mobile GPS']['Enable']);
    }
    
    if (isset($confignxdngateway['Mobile GPS']['Address'])) {
	unset($confignxdngateway['Mobile GPS']['Address']);
    }
    
    if (isset($confignxdngateway['Mobile GPS']['Port'])) {
	unset($confignxdngateway['Mobile GPS']['Port']);
    }
    
    unset($confignxdngateway['Mobile GPS']);
}

// GPSd
if (!isset($configdmrgateway['GPSD']) || !isset($configdmrgateway['GPSD']['Enable']) || !isset($configdmrgateway['GPSD']['Address']) ||!isset($configdmrgateway['GPSD']['Port'])) {
    $configdmrgateway['GPSD']['Enable'] = 0;
    $configdmrgateway['GPSD']['Address'] = "127.0.0.1";
    $configdmrgateway['GPSD']['Port'] = "2947";
}

if ($configdmrgateway['GPSD']['Enable'] == 1) {
    if (isset($configdmrgateway['GPSD']['Address']) != TRUE) {
	$configdmrgateway['GPSD']['Address'] = "127.0.0.1";
    }
    
    if (isset($configdmrgateway['GPSD']['Port']) != TRUE) {
	$configdmrgateway['GPSD']['Port'] = "2947";
    }
}

//
// GPSD should xmit data to the various GWs instead of MMDVMHost...
//
if (isset($configmmdvm['GPSD'])) {
    if (isset($configmmdvm['GPSD']['Enable'])) {
	unset($configmmdvm['GPSD']['Enable']);
    }
    
    if (isset($configmmdvm['GPSD']['Address'])) {
	unset($configmmdvm['GPSD']['Address']);
    }
    
    if (isset($configmmdvm['GPSD']['Port'])) {
	unset($configmmdvm['GPSD']['Port']);
    }
    
    unset($configmmdvm['GPSD']);
}

//
// Reset ['DMR Network']['Type'](Direct); deprectated with new mmdvmhost
//
if (isset($configmmdvm['DMR Network']['Type'])) {
    unset($configmmdvm['DMR Network']['Type']);
}

// Ensure ircDDBGateway file contains the new APRS configuration
if (isset($configs['aprsHostname'])) {
    exec('sudo sed -i "/mobileGPS.*/d;/aprsPassword.*/d;s/aprsHostname=.*/aprsAddress=127.0.0.1/g;s/aprsPort=.*/aprsPort=8673/g" /etc/ircddbgateway');

    // re-init iscddbgw config
    // Load the ircDDBGateway config file
    unset($configs);
    $configs = array();
    if ($configfile = fopen($gatewayConfigPath,'r')) {
	while ($line = fgets($configfile)) {
	    if (strpos($line, '=') !== false) {
		list($key,$value) = preg_split('/=/',$line);
		$value = trim(str_replace('"','',$value));
		if ($key != 'ircddbPassword' && strlen($value) > 0)
		    $configs[$key] = $value;
	    }
    }
    fclose($configfile);}
    if (!isset($configs['url'])) {
	$configs['url'] = "https://www.qrz.com/db/";
    }
}

if (!isset($configs['aprsEnabled'])) {
    $configs['aprsEnabled'] = "0";
}

// form handler for APRSGateway and the clients that support it.
// grab existing settings if any and store these for later (below form and config handler)
$DMRGatewayAPRS     = $configdmrgateway['APRS']['Enable'];
$IRCDDBGatewayAPRS  = $configs['aprsEnabled'];
$YSFGatewayAPRS     = $configysfgateway['APRS']['Enable'];
$DGIdGatewayAPRS    = $configdgidgateway['APRS']['Enable'];
$NXDNGatewayAPRS    = $confignxdngateway['APRS']['Enable'];
$M17GatewayAPRS     = $configm17gateway['APRS']['Enable'];

// APRS symbol form option handler
$aprs_symbol_map = '/var/www/dashboard/includes/aprs-symbols/aprs_symbols.txt';
// Read the file line by line and create options
$sym_options = '';
$overlay = false; // Initialize the overlay variable as false
$selectedSymbol = $configdmrgateway['APRS']['Symbol']; // Get the selected symbol from the configuration
if (($handle = fopen($aprs_symbol_map, 'r')) !== false) {
    while (($line = fgets($handle)) !== false) {
        // Split the line by tabs
        $parts = explode("\t", $line);
        if (count($parts) >= 3) {
            // Extract the first column (symbol) and third column (description)
            $symbol = trim($parts[0]);
            $description = trim($parts[2]);

            // Check if the symbol starts with a backslash "\" and set $overlay to true
            if (strpos($symbol, '\\') === 0) {
                $overlay = true;
            }

            // Generate the <option> element and check if it should be selected
            $selectedAttribute = ($symbol == $selectedSymbol) ? 'selected' : '';
            $sym_options .= "<option value='$symbol' $selectedAttribute>$description</option>";
        }
    }
    fclose($handle);
}

// form handler for DMR beacon
$DMRBeaconEnable    = $configmmdvm['DMR']['Beacons'];
$DMRBeaconModeNet   = "0" ;

// form handler for XLX DMR slot select
$xlxTimeSlot	    = $configdmrgateway['XLX Network']['Slot'];

// newer MMDVMHost, which by default uses DMRGW
// Convert DMR Network section to use DMRGateway instead of direct access
$configmmdvm['DMR Network']['LocalAddress'] = "127.0.0.1";
$configmmdvm['DMR Network']['RemoteAddress'] = "127.0.0.1";
$configmmdvm['DMR Network']['RemotePort'] = "62031";
$configmmdvm['DMR Network']['LocalPort'] = "62032";
$configmmdvm['DMR Network']['Password'] = "none";

//
// Check for DMRGateway RemoteCommand and enable if it isn't...
// This is needed for DMR network panel status, login issues, etc.
//`
if (!isset($configdmrgateway['Remote Control'])) {
    $configdmrgateway['Remote Control']['Enable'] = "1";
    $configdmrgateway['Remote Control']['Port'] = "7643";
    $configdmrgateway['Remote Control']['Address'] = "127.0.0.1";
}

// Checks for NextionDriver and inits if non-existent...
if (!isset($configmmdvm['NextionDriver'])) {
    $configmmdvm['NextionDriver']['Enable'] = "0";
    $configmmdvm['NextionDriver']['Port'] = "0";
    $configmmdvm['NextionDriver']['DataFilesPath'] = "/usr/local/etc/";
    $configmmdvm['NextionDriver']['LogLevel'] = "2";
    $configmmdvm['NextionDriver']['GroupsFile'] = "groupsNextion.txt";
    $configmmdvm['NextionDriver']['DMRidFile'] = "stripped.csv";
    $configmmdvm['NextionDriver']['ShowModeStatus'] = "0";
    $configmmdvm['NextionDriver']['RemoveDim'] = "0";
    $configmmdvm['NextionDriver']['WaitForLan'] = "1";
    $configmmdvm['NextionDriver']['SleepWhenInactive'] = "0";
    $configmmdvm['NextionDriver']['GroupsFileSrc'] = "https://hostfiles.w0chp.net/groupsNextion.txt";
}
if (!isset($configmmdvm['NextionDriver']['Enable'])) {
    $configmmdvm['NextionDriver']['Enable'] = "0";
}
if (!isset($configmmdvm['Transparent Data'])) {
    $configmmdvm['Transparent Data']['Enable'] = "0";
    $configmmdvm['Transparent Data']['RemoteAddress'] = "127.0.0.1";
    $configmmdvm['Transparent Data']['RemotePort'] = "40094";
    $configmmdvm['Transparent Data']['LocalPort'] = "40095";
}
if (($configmmdvm['General']['Display'] == "Nextion") && ($configmmdvm['NextionDriver']['Enable'] == "1")) {
    if ($configmmdvm['Transparent Data']['Enable'] == "1") {
	$configmmdvm['General']['Display'] = "NextionDriverTrans";
    }
    else {
	$configmmdvm['General']['Display'] = "NextionDriver";
    }
    $configmmdvm['Nextion']['Port'] = $configmmdvm['NextionDriver']['Port'];
}

// special (on init) handling for DV-Mega CAST's display udp service, which uses Transpaent Data from MMDVMhost...
if (isDVmegaCast() == 1) {
    $configmmdvm['Transparent Data']['Enable'] = "1";
}

// New MMDVMHost uart stuff
if (!isset($configmmdvm['Modem']['Protocol']) ||
!isset($configmmdvm['Modem']['UARTPort']) ||
!isset($configmmdvm['Modem']['UARTSpeed'])) {
    $configmmdvm['Modem']['Protocol'] = "uart";
    $configmmdvm['Modem']['UARTPort'] = $configmmdvm['Modem']['Port'];
    $configmmdvm['Modem']['UARTSpeed'] = 115200;
}

// Convert [aprs.fi] sections to new [APRS] format
function clearAprsDotFi(&$cfgFile, $suffix) {
    $cfgAprsEnabled = 0;
    $cfgAprsSuffix = $suffix;
    $cfgAprsDescription = (isset($configysfgateway['APRS']['Description']) && !empty($configysfgateway['APRS']['Description'])) ? $configysfgateway['APRS']['Description'] : "APRS Description";
    
    // Old config if present, get rid of it
    if (isset($cfgFile['aprs.fi']))
    {
	$cfgAprsEnabled = $cfgFile['aprs.fi']['Enable'];
	if (isset($cfgFile['aprs.fi']['Suffix']) && !empty($cfgFile['aprs.fi']['Suffix']))
	{
	    $cfgAprsSuffix = $cfgFile['aprs.fi']['Suffix'];
	}
	$cfgAprsDescription = $cfgFile['aprs.fi']['Description'];
	unset($cfgFile['aprs.fi']);
    }
    
    // Add default APRS config
    $cfgFile['APRS']['Enable'] = "0";
    $cfgFile['APRS']['Address'] = "127.0.0.1";
    $cfgFile['APRS']['Port'] = "8673";
    $cfgFile['APRS']['Suffix'] = $cfgAprsSuffix;
    $cfgFile['APRS']['Description'] = $cfgAprsDescription;
    $cfgFile['APRS']['Symbol'] = "\"Wi\"";
}

// Ensure NXDNGateway file contains the new APRS configuration
if (isset($confignxdngateway['aprs.fi']) || !isset($confignxdngateway['APRS'])) {
    clearAprsDotFi($confignxdngateway, "N");
}
// Ensure YSFGateway file contains the new APRS configuration
if (isset($configysfgateway['aprs.fi']) || !isset($configysfgateway['APRS'])) {
    clearAprsDotFi($configysfgateway, "Y");
}

//
// Handle RemoteControl Port collision between NXDNGateway and M17Gateway
//
if ($configm17gateway['Remote Commands']['Port'] == $confignxdngateway['Remote Commands']['Port']) {
    $configm17gateway['Remote Commands']['Port'] = 6076;
    $confignxdngateway['Remote Commands']['Port'] = 6075;
}

//
// Build APRS password from callsign
//
function aprspass ($callsign) {
	$stophere = strpos($callsign, '-');
	if ($stophere) $callsign = substr($callsign, 0, $stophere);
	$realcall = strtoupper(substr($callsign, 0, 10));
	// initialize hash
	$hash = 0x73e2;
	$i = 0;
	$len = strlen($realcall);
	// hash callsign two bytes at a time
	while ($i < $len) {
		$hash ^= ord(substr($realcall, $i, 1))<<8;
		$hash ^= ord(substr($realcall, $i + 1, 1));
		$i += 2;
	}
	// mask off the high bit so number is always positive
	return $hash & 0x7fff;
}

//
// Ensure Options string is quoted
//
function ensureOptionsIsQuoted(&$opt) {
    if (isset($opt) && !empty($opt) && (strlen($opt) > 1)) {
	if ($opt[0] != '"' && $opt[strlen($opt) - 1] != '"') {
	    $opt = '"'.$opt.'"';
	}
    }
}

$MYCALL=strtoupper($callsign);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en">
<head>
    <meta name="language" content="English" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
    <meta http-equiv="Expires" content="0" />
    <title><?php echo "$MYCALL"." - " . __( 'Digital Voice' ) . " ".__( 'Dashboard' )." - ".__( 'Configuration' );?></title>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css?version=<?php echo $versionCmd; ?>" />
    <?php include_once "../config/browserdetect.php"; ?>
    <script src="/js/jquery.min.js?version=<?php echo $versionCmd; ?>"></script>
    <link href="/js/select2/css/select2.min.css?version=<?php echo $versionCmd; ?>" rel="stylesheet" />
    <script src="/js/select2/js/select2.full.min.js?version=<?php echo $versionCmd; ?>"></script>
    <script src="/js/select2/js/select2-searchInputPlaceholder.js?version=<?php echo $versionCmd; ?>"></script>  
    <script>
        function disableSubmitButtons() {
                var inputs = document.getElementsByTagName('input');
                for (var i = 0; i < inputs.length; i++) {
                        if (inputs[i].type === 'button') {
                                inputs[i].disabled = true;
				inputs[i].value = 'Please Wait...Page will reload once complete.';
                        }
                }
        }

	function submitform() {
		disableSubmitButtons();
		document.getElementById("config").submit();
	}
	function submitPassform() {
		disableSubmitButtons();
		document.getElementById("adminPassForm").submit();
	}
	function submitPskform() {
		disableSubmitButtons();
		document.getElementById("autoApPassForm").submit();
	}
	function factoryReset() {
		if (confirm('WARNING: This will reset all of your settings back to factory defaults. WiFi configuration will be retained to maintain network access to this hotspot.\n\nAre you SURE you want to do this?\n\nPress OK to restore the factory configuration\nPress Cancel to go back.')) {
			document.getElementById("factoryReset").submit();
		} else {
			return false;
		}
	}
	function resizeIframe(obj) {
		var numpix = parseInt(obj.contentWindow.document.body.scrollHeight, 10);
		obj.style.height = numpix + 'px';
	}
	$(document).ready(function() {
          $('.ysfStartupHost').select2({searchInputPlaceholder: 'Search...'});
          $('.ysf2dmrMasterHost').select2({searchInputPlaceholder: 'Search...'});
          $('.dmrMasterHost').select2({searchInputPlaceholder: 'Search...', width: '125px'});
          $('.dmrMasterHost1').select2({searchInputPlaceholder: 'Search...', width: '225px'});
          $('.dmrMasterHost2').select2({searchInputPlaceholder: 'Search...', width: '225px'});
          $('.dmrMasterHost3').select2({searchInputPlaceholder: 'Search...', width: '125px'});
          $('.dmrMasterHost3Startup').select2({searchInputPlaceholder: 'Search...'});
          $('.ysf2nxdnStartupDstId').select2({searchInputPlaceholder: 'Search...'});
          $('.ysf2p25StartupDstId').select2({searchInputPlaceholder: 'Search...'});
          $('.p25StartupHost').select2({searchInputPlaceholder: 'Search...'});
          $('.nxdnStartupHost').select2({searchInputPlaceholder: 'Search...'});
          $('.systemTimezone').select2({searchInputPlaceholder: 'Search...', width: '175px'});
          $('.confHardware').select2({searchInputPlaceholder: 'Search...', width: '500px'});
	  $(".confDefRef").select2({
	    tags: true,
	    width: '125px',
	    dropdownAutoWidth : false,
	    createTag: function (params) {
	      return {
	        id: params.term,
	        text: params.term,
	        newOption: true
	      }
	    },
	    templateResult: function (data) {
	      var $result = $("<span></span>");

	      $result.text(data.text);

	      if (data.newOption) {
	        $result.append(" <em>(Search existing or enter and save custom reflector value)</em>");
	      }

	      return $result;
	    }
	  });
	  $('.ModSel').select2();
	  $('.M17Ref').select2({searchInputPlaceholder: 'Search...',width: '125px'});
	});

	$(document).on('click', '.toggle-bm-password', function() {
	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $("#bmHSSecurity");
	  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
	});
	$(document).on('click', '.toggle-bm-password_YSF', function() {
	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $("#bmHSSecurity_YSF");
	  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
	});
	$(document).on('click', '.toggle-tgif-password', function() {
	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $("#tgifHSSecurity");
	  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
	});
	$(document).on('click', '.toggle-ircddb-password', function() {
	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $("#ircddbPass");
	  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
	});
	$(document).on('click', '.toggle-dapnet-password', function() {
	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $("#pocsagAuthKey");
	  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
	});

	/*
	// Functions to auto-populate DMR and NXDN IDs for an inputted
	// callsign if there's a match(es) in the local DMR and NXDN ID DB's/CSVs.
	*/
	$(document).ready(function () {
	    // Function to handle CSV lookup and populate fields
	    function populateFields(callsign, csvPath, targetField) {
		// Retrieve the text input element
		var inputField = $("#" + targetField);

		// Disable the field during lookup
		inputField.prop("disabled", true);
		inputField.attr("placeholder", "Searching...");

		$.ajax({
		    type: "GET",
		    url: csvPath,
		    dataType: "text",
		    success: function (data) {
			var rows = data.split("\n");
			var matches = [];

			for (var i = 0; i < rows.length; i++) {
			    var columns = rows[i].split(",");
			    if (columns.length >= 2 && columns[1] === callsign) {
				matches.push(columns[0]);
			    }
			}

			// Clear existing options or value
			if (inputField.is("select")) {
			    inputField.empty();
			} else {
			    inputField.val("");
			}

			if (matches.length > 1) {
			    // If there are multiple matches, replace the text input with a select dropdown
			    if (!inputField.is("select")) {
				var selectField = $("<select>", {
				    id: targetField,
				    name: targetField
				});

				// Add a disabled, placeholder default option
				selectField.append($('<option>', {
				    value: '',
				    text: 'Select ID...',
				    disabled: true,
				    selected: true
				}));

				// Add options within the select dropdown
				for (var j = 0; j < matches.length; j++) {
				    selectField.append($('<option>', {
					value: matches[j],
					text: matches[j]
				    }));
				}

				inputField.replaceWith(selectField);
			    }
			} else if (matches.length === 1) {
			    // If there is a single match, update the value of the text input
			    if (inputField.is("select")) {
				inputField.replaceWith($("<input>", {
				    type: "text",
				    id: targetField,
				    name: targetField,
				    value: matches[0]
				}));
			    } else {
				inputField.val(matches[0]);
			    }
			} else {
			    // No match found, keep the text input as-is
			}

			// Enable the field after lookup
			inputField.prop("disabled", false);
			inputField.attr("placeholder", "");
		    }
		});
	    }

	    // Event handler for confCallsign input
	    $("#confCallsign").on("input", function () {
		var callsign = $(this).val();

		// Clear and (re-)populate dmrId fields
		populateFields(callsign, "/includes/user.csv", "dmrId");

		// Clear and (re-)populate nxdnId fields
		populateFields(callsign, "/includes/NXDN.csv", "nxdnId");
	    });
	});

        // Function to enforce valid characters (0-9 and A-Z) and convert to uppercase
        function enforceValidCharsAndConvertToUpper(input) {
            // Remove any characters that are not valid (A-Z and 0-9)
            input.value = input.value.replace(/[^0-9A-Za-z]/g, '');

            // Convert to uppercase
            input.value = input.value.toUpperCase();
        }
    </script>
    <script src="/js/functions.js?version=<?php echo $versionCmd; ?>"></script>
    <link rel="stylesheet" href="/includes/aprs-symbols/aprs-symbols.css?version=<?php echo $versionCmd; ?>"/>
    <script src="/includes/aprs-symbols/aprs-symbols.js?version=<?php echo $versionCmd; ?>"></script>
    <script src="/includes/aprs-symbols/doc-ready.js?version=<?php echo $versionCmd; ?>"></script>
<script>
  // config page unsaved change logic, to well, bug the user and save!
  $(document).ready(function() {
    var formChanged = false;
    var originalFormData; // Store the original form data for reverting

    // Listen for changes in the entire form
    $('#config').on('input change', ':input, select, textarea', function() {
      formChanged = true;
      showUnsavedChanges();
    });

    // Save or apply changes function (call this when the user saves the changes)
    function saveChanges() {
      // Change the content and background color of the unsavedChanges div
      $('#unsavedChanges').html('<strong>Saving and applying changes: page will reload once complete. Please wait... <span class="spinner"></span></strong>');
      $('#unsavedChanges').css('background-color', '#6CBA74');
      $('#unsavedChanges').css('color', '#000');
      $('#unsavedChanges').css('border', '2px solid #406F45');
      submitform();
    }

    // Revert changes function
    function revertChanges() {
      // Restore the original form data
      $('#config').trigger('reset');
      // reset select2
      jQuery('.select2').each( function() {
        jQuery(this).parent().find('select').select2();
      });
      // Trigger the toggleAPRSGatewayCheckbox function to handle checkbox state after reverting
      toggleAPRSGatewayCheckbox();

      formChanged = false;
      hideUnsavedChanges();
    }

    // Show the floating div with the unsaved changes message
    function showUnsavedChanges() {
      $('#unsavedChanges').slideDown();
    }

    // Hide the floating div when changes are saved or discarded
    function hideUnsavedChanges() {
      $('#unsavedChanges').slideUp();
    }

    // Trigger the saveChanges function when the user clicks the apply button
    $('#applyButton').on('click', function() {
      saveChanges();
    });

    // Trigger the revertChanges function when the user clicks the revert button
    $('#revertButton').on('click', function() {
      revertChanges();
    });

    // Store the original form data on page load
    originalFormData = $('#config').serialize();
  });


    // conflicing cross-mode handling
    function toggleDMR2YSFCheckbox() {
        var dmr2ysfCheckbox = document.getElementById('toggle-dmr2ysf');
        var dmr2nxdnCheckbox = document.getElementById('toggle-dmr2nxdn');

        if (dmr2ysfCheckbox.checked) {
            dmr2nxdnCheckbox.disabled = true;
        } else {
            dmr2nxdnCheckbox.disabled = false;
        }
    }

    function toggleDMR2NXDNCheckbox() {
        var dmr2ysfCheckbox = document.getElementById('toggle-dmr2ysf');
        var dmr2nxdnCheckbox = document.getElementById('toggle-dmr2nxdn');

        if (dmr2nxdnCheckbox.checked) {
            dmr2ysfCheckbox.disabled = true;
        } else {
            dmr2ysfCheckbox.disabled = false;
        }
    }
</script>
</head>
<body onload="checkFrequency(); return false;">
<div id="unsavedChanges">
  <strong>Changes pending:</strong> Click <em>"Apply Changes"</em> to save and activate after making <strong>all</strong> necessary changes.
  <button id="applyButton">Apply Changes</button>
  <button id="revertButton">Revert Changes</button>
</div>
<?php
// warn to backup configs, only if this is not a new installation.
$config_dir = "/etc/WPSD_config_mgr";
if (!is_dir($config_dir) || count(glob("$config_dir/*")) < 1) { // no saved configs
    if (file_exists('/etc/dstar-radio.mmdvmhost') && $MYCALL != "M1ABC") { // NOT a new installation , so display message...
?>
<div>
  <table align="center"style="margin: 0px 0px 10px 0px; width: 100%;border-collapse:collapse; table-layout:fixed;white-space: normal!important;">
    <tr>
    <td align="center" valign="top" style="background-color: #ffff90; color: #906000; word-wrap: break-all;padding:20px;">Notice! You do not have any saved configurations / profiles.<br /><br />
    It is recommended that you <b><a href="/admin/profile_manager.php">save your configuration / profile</a>.</b></td>
    </tr>
  </table>
</div>
<?php
    }
}
?>
<?php
$bmAPIkeyFile = '/etc/bmapi.key';
if (file_exists($bmAPIkeyFile) && fopen($bmAPIkeyFile,'r')) {
  $configBMapi = parse_ini_file($bmAPIkeyFile, true);
  $bmAPIkey = $configBMapi['key']['apikey'];
  // Check the BM API Key
  if ( strlen($bmAPIkey) <= 200 ) {
?>
<div>
  <table align="center"style="margin: 0px 0px 10px 0px; width: 100%;border-collapse:collapse; table-layout:fixed;white-space: normal!important;">
    <tr>
    <td align="center" valign="top" style="background-color: #ffff90; color: #906000; word-wrap: break-all;padding:20px;">Notice! You have a legacy Brandmeister API Key, which will not work any longer. Read the announcement on how to migrate: <a href="https://news.brandmeister.network/introducing-user-api-keys/" target="new" alt="BM API Keys">BM API Key Announcement and Migration Instructions</a>; and then <a href="/admin/advanced/fulledit_bmapikey.php">Update your API Key</a> to delete this message and to ensure BM Manager continues to work properly..</td>
    </tr>
  </table>
</div>
<?php } } ?>
<div class="container">
<div class="header">
<div class="SmallHeader shLeft noMob">Hostname: <?php echo exec('cat /etc/hostname'); ?></div>
<div class="SmallHeader shRight noMob">
  <div id="CheckUpdate">
  <?php
    include $_SERVER['DOCUMENT_ROOT'].'/includes/checkupdates.php';
  ?>
  </div><br />
</div>
<h1>WPSD <?php echo __( 'Digital Voice' ) . " - ".__( 'Configuration' );?></h1>
        <div class="navbar">
              <script type= "text/javascript">
               $(document).ready(function() {
                 setInterval(function() {
                   $("#timer").load("/includes/datetime.php");
                   }, 1000);

                 function update() {
                   $.ajax({
                     type: 'GET',
                     cache: false,
                     url: '/includes/datetime.php',
                     timeout: 1000,
                     success: function(data) {
                       $("#timer").html(data); 
                       window.setTimeout(update, 1000);
                     }
                   });
                 }
                 update();
               });
              </script>
              <div class="headerClock">
                <span id="timer"></span>
            </div>
			<a class="noMob menureset" href="javascript:factoryReset();"><?php echo __( 'Factory Reset' );?></a>
			<a class="noMob menubackup" href="/admin/config_backup.php"><?php echo __( 'Backup/Restore' );?></a>
			<a class="noMob menuupdate" href="/admin/update.php"><?php echo __( 'WPSD Update' );?></a>
			<a class="noMob menuadvanced" href="/admin/advanced/">Advanced</a>
			<a class="menupower" href="/admin/power.php"><?php echo __( 'Power' );?></a>
			<a class="menuadmin" href="/admin/"><?php echo __( 'Admin' );?></a>
			<?php if (file_exists("/etc/dstar-radio.mmdvmhost")) { ?>
			<?php } ?>
			<a class="menudashboard" href="/"><?php echo __( 'Dashboard' );?></a>
		    </div>
</div>
<?php
// check that no modes are paused. If so, display form to unpause modes...
$is_paused = glob('/etc/*_paused');
$repl_str = array('/\/etc\//', '/_paused/');
$paused_modes = preg_replace($repl_str, '', $is_paused);

if (!empty($is_paused)) {
    // HTML output starts here
    echo '<div class="contentwide">
              <div class="divTable">
                <div class="divTableBody">
                  <div class="divTableRow">
                    <div class="divTableCellSans larger">';
    echo '		<form method="post" action="/admin/.resume_all_modes.php">';
    echo '		  <h1>IMPORTANT:</h1>';
    echo '		  <p><b>One or more modes have been detected to be "paused"</b>:</p>';
    
    foreach ($paused_modes as $mode) {
        echo "<h2>$mode</h2>";
    }

    echo '<p>To continue onto the Configuration Page, the paused mode(s) must first be resumed. You can pause these again later.</p>';
    // Create a hidden input to store all paused modes
    echo '<input type="hidden" name="paused_modes" value="' . implode(',', $paused_modes) . '">';

    echo '<input type="submit" name="unpause_modes" value="Resume All Modes">';
    echo '</form>';
    
    echo '<br />'."\n";
    echo '        </div>
              </div>
            </div>
          </div>';
    echo '<br />'."\n";
    echo '<br />';
    echo '</div>';
    echo '<div class="footer">';
    echo 'Get WPSD Help: [ <a href="https://w0chp.radio/wpsd-faqs/" target="_new">FAQs</a> ] &bull; [ <a href="https://wpsd-docs.w0chp.net/" target="_new">User Manual</a> ] &bull; [ <a href="https://www.facebook.com/groups/wpsdproject/" target="_new">Facebook Group</a> ] &bull; [ <a href="https://discord.gg/b8Hv5ygPdF" target="_new">Discord Server</a> ]<br />';
    echo '<a href="https://wpsd.radio/">WPSD</a> by <code>W0CHP</code> &copy; 2020-'.date("Y").' -- WPSD Project <a href="https://w0chp.radio/wpsd/#credits" target="_new">Credits</a>';
    echo '<br />';
    echo '</div>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
} else {  // no modes paused, continue on! (end of pause check near the end of the file)
if ($_SERVER["PHP_SELF"] == "/admin/configure.php") {
	//HTML output starts here
     echo '<div class="contentwide">'."\n";
if (!empty($_POST)):

	// Admin Password Change
	if (!empty($_POST['adminPassword'])) {
    	    $adminPassword = escapeshellarg(trim($_POST['adminPassword'])); // Escaping and trimming input

    	    $rollAdminPass0 = "sudo htpasswd -b /var/www/.htpasswd pi-star $adminPassword";

	    $output0 = null;
    	    $retval0 = null;
    	    exec($rollAdminPass0, $output0, $retval0);

    	    error_log("Command 1 output: " . implode("\n", $output0));
    	    error_log("Command 1 return value: " . $retval0);

    	    $rollAdminPass2 = 'sudo echo -e \''.escapeshellarg(trim($_POST['adminPassword'])).'\n'.escapeshellarg(trim($_POST['adminPassword'])).'\' | sudo passwd pi-star';

    	    $output2 = null;
    	    $retval2 = null;
    	    exec($rollAdminPass2, $output2, $retval2);

    	    error_log("Command 2 output: " . implode("\n", $output2));
    	    error_log("Command 2 return value: " . $retval2);

    	    unset($_POST);

    	    echo "<table>\n";
    	    echo "<tr><th>Working...</th></tr>\n";
    	    echo "<tr><td>Applying your configuration changes...</td></tr>\n";
    	    echo "</table>\n";
    	    echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
    	    echo "<br />\n</div>\n";
    	    echo "<br />\n</div>\n</div>\n</body>\n</html>\n";
    	    die();
	}

	// AutoAP PSK Change
	if (empty($_POST['autoapPsk']) != TRUE ) {
	  $rollAutoApPsk = 'sudo sed -i "/wpa_passphrase=/c\\wpa_passphrase='.$_POST['autoapPsk'].'" /etc/hostapd/hostapd.conf';
	  system($rollAutoApPsk);
	  $rollAutoApWPA = 'sudo sed -i "/wpa=/c\\wpa=2" /etc/hostapd/hostapd.conf';
	  system($rollAutoApWPA);
	  unset($_POST);
	  echo "<table>\n";
	  echo "<tr><th>Working...</th></tr>\n";
	  echo "<tr><td>Applying your configuration changes...</td></tr>\n";
	  echo "</table>\n";
	  echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
	  echo "<br />\n</div>\n";
          echo "<br />\n</div>\n</div>\n</body>\n</html>\n";
	  die();
	}

	// Factory Reset Handler Here
	if (empty($_POST['factoryReset']) != TRUE ) {
	  echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>Resetting...</th></tr>\n";
          echo "<tr><td>Loading factory configuration files...</td><tr>\n";
          echo "</table>\n";
          unset($_POST);

	  // Vendor handlers for specific hardware stock configs
	  // we take reset actions based on specific modem vendor devices (and displays) that are already configured
	  $display = exec("awk -F'=' '/\[General\]/{flag=1} flag && /Display/{print $2; flag=0}' /etc/mmdvmhost");
	  $fileContents = @file_get_contents('/etc/dstar-radio.mmdvmhost');
	  preg_match('/^Hardware=(.*)$/m', $fileContents, $matches);
	  if (isset($matches[1])) {
	      $modemValue = trim($matches[1]);
	  } else {
	      // Handle the case when "Hardware=" is not found, will take no-match action below.
	      $modemValue = null;
	  }
	  $vendorHardwareArray = [
	      'dvmpicast',	  // DVMega Cast
	      'dvmpis',		  // DVMega single-band 70cm GPIO HAT (EuroNode, Cast, etc.)
	      'sbhsdualbandgpio', // SkyBridge dual-band GPIO HAT
	      'zumspotgpio',      // ZUMspot single-band GPIO HAT
	      'zumspotusb'  	  // ZUMspot USB stick
	  ];
	  $modemMatch = false;
	  if ($modemValue !== null && in_array($modemValue, $vendorHardwareArray)) {
	      $modemMatch = true;
	  }
	  if ($modemMatch && $modemValue === 'sbhsdualbandgpio') { // SkyBridge+ unit
	      exec('sudo mkdir /tmp/reset/ ; sudo mkdir /tmp/reset-configs ; sudo unzip -o /usr/local/bin/.config_skybridge.zip -d /tmp/reset/; sudo mv /tmp/reset/*.php /tmp/reset-configs/ ; sudo mv /tmp/reset/hostapd.conf /etc/hostapd/ ; sudo mv /tmp/reset/* /etc/ ; sudo rm -rf /tmp/reset ; sudo timedatectl set-timezone America/Chicago');
	  } elseif (isDVmegaCast() == 1) { // DVMega CAST
              exec('sudo mkdir /tmp/reset/ ; sudo mkdir /tmp/reset-configs ; sudo unzip -o /usr/local/bin/.config_dvmega_cast.zip -d /tmp/reset/; sudo mv /tmp/reset/*.php /tmp/reset-configs/ ; sudo mv /tmp/reset/hostapd.conf /etc/hostapd/ ; sudo mv /tmp/reset/* /etc/ ; sudo rm -rf /tmp/reset ; sudo timedatectl set-timezone Europe/Amsterdam ; sudo cp -a /opt/cast/cast-factory-settings/* /usr/local/cast/etc/ ; sudo chmod 775 /usr/local/cast/etc ; sudo chown -R www-data:pi-star /usr/local/cast/etc ; sudo chmod 664 /usr/local/cast/etc/*');
	  } elseif ($modemMatch && $modemValue === 'dvmpis') { // DVMega units
              exec('sudo mkdir /tmp/reset/ ; sudo mkdir /tmp/reset-configs ; sudo unzip -o /usr/local/bin/.config_dvmega_euronode.zip -d /tmp/reset/; sudo mv /tmp/reset/*.php /tmp/reset-configs/ ; sudo mv /tmp/reset/hostapd.conf /etc/hostapd/ ; sudo mv /tmp/reset/* /etc/ ; sudo rm -rf /tmp/reset ; sudo timedatectl set-timezone Europe/Amsterdam');
	  } elseif ($modemMatch && strpos($modemValue, 'zum') === 0 && strpos($modemValue, 'usb') !== false) { // ZUMRadio USB stick
	      exec('sudo mkdir /tmp/reset/ ; sudo mkdir /tmp/reset-configs ; sudo unzip -o /usr/local/bin/.config_zum-usb.zip -d /tmp/reset/; sudo mv /tmp/reset/*.php /tmp/reset-configs/ ; sudo mv /tmp/reset/hostapd.conf /etc/hostapd/ ; sudo mv /tmp/reset/* /etc/ ; sudo rm -rf /tmp/reset ; sudo timedatectl set-timezone America/Chicago');
	  } elseif ($modemMatch && strpos($modemValue, 'zum') === 0 && strpos($modemValue, 'usb') === false) { // ZUMradio GPIO HAT
	      switch ($display) {
	          case "Nextion": // ZUMspot Elite unit
	      	      exec('sudo mkdir /tmp/reset/ ; sudo mkdir /tmp/reset-configs ; sudo unzip -o /usr/local/bin/.config_zumgpio-nx.zip -d /tmp/reset/; sudo mv /tmp/reset/*.php /tmp/reset-configs/ ; sudo mv /tmp/reset/hostapd.conf /etc/hostapd/ ; sudo mv /tmp/reset/* /etc/ ; sudo rm -rf /tmp/reset ; sudo timedatectl set-timezone America/Chicago');
	              break;
	          default: // ZUMspot Mini unit
	      	      exec('sudo mkdir /tmp/reset/ ; sudo mkdir /tmp/reset-configs ; sudo unzip -o /usr/local/bin/.config_zumgpio-oled.zip -d /tmp/reset/; sudo mv /tmp/reset/*.php /tmp/reset-configs/ ; sudo mv /tmp/reset/hostapd.conf /etc/hostapd/ ; sudo mv /tmp/reset/* /etc/ ; sudo rm -rf /tmp/reset ; sudo timedatectl set-timezone America/Chicago');
	              break;
	      }
	  } else { // No-match ($modemValue = null): reset w/Generic hardware/setup configs
	      exec('sudo unzip -o /usr/local/bin/config_clean.zip -d /etc/');
	      exec('sudo rm -rf /etc/dstar-radio.*');
	  }
	  // reset state of d-star time announcements
	  if (file_exists('/etc/timeserver.disable'))
	      system('sudo rm /etc/timeserver.disable');
	  // reset repos
	  system('sudo wpsd-services fullstop > /dev/null 2>/dev/null');
	  exec('sudo git --work-tree=/usr/local/sbin --git-dir=/usr/local/sbin/.git update-index --no-assume-unchanged pistar-upnp.service');
	  exec('sudo git --work-tree=/usr/local/sbin --git-dir=/usr/local/sbin/.git reset --hard origin/master');
	  exec('sudo git --work-tree=/usr/local/bin --git-dir=/usr/local/bin/.git reset --hard origin/master');
	  exec('sudo git --work-tree=/var/www/dashboard --git-dir=/var/www/dashboard/.git reset --hard origin/master');
	  exec('sudo mv /tmp/reset-configs/*.php /var/www/dashboard/config/ ; sudo rm -rf /tmp/reset-configs');
	  // reset logs
	  $log_backup_dir = "/home/pi-star/.backup-mmdvmhost-logs/";
	  $log_dir = "/var/log/pi-star/";
          exec ("sudo rm -rf $log_dir/* $log_backup_dir/* > /dev/null");
	  if (isDVmegaCast() == 1) { // if DVMega cast, reset main board
	      system('sudo /usr/local/cast/bin/cast-reset ; sleep 5 > /dev/null 2>/dev/null');
	  }
	  system('sudo wpsd-services start > /dev/null 2>/dev/null &');
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
	  echo "<br />\n</div>\n";
          echo "<br />\n</div>\n</div>\n</body>\n</html>\n";
	  die();
	}

	// Handle the case where the config is not read correctly
	if (count($configmmdvm) <= 18) {
	  echo "<br />\n";
	  echo "<table>\n";
	  echo "<tr><th>ERROR</th></tr>\n";
	  echo "<tr><td>Unable to read source configuration file(s)...</td><tr>\n";
	  echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
	  echo "</table>\n";
	  unset($_POST);
	  echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
	  echo "<br />\n</div>\n";
          echo "<br />\n</div>\n</div>\n</body>\n</html>\n";
	  die();
	}

	// Change Radio Control Software
	if (empty($_POST['controllerSoft']) != TRUE ) {
	  system('sudo rm -rf /etc/dstar-radio.*');
	  if (escapeshellcmd($_POST['controllerSoft']) == 'MMDVM') { system('sudo touch /etc/dstar-radio.mmdvmhost'); }
	}

	// HostAP
	if (empty($_POST['autoAP']) != TRUE ) {
	  if (escapeshellcmd($_POST['autoAP']) == 'OFF') { system('sudo touch /etc/hostap.off'); }
	  if (escapeshellcmd($_POST['autoAP']) == 'ON') { system('sudo rm -rf /etc/hostap.off'); }
	}

	// Change Dashboard Language
	if (empty($_POST['dashboardLanguage']) != TRUE ) {
	  $rollDashLang = 'sudo sed -i "/pistarLanguage=/c\\$pistarLanguage=\''.escapeshellcmd($_POST['dashboardLanguage']).'\';" /var/www/dashboard/config/language.php';
	  system($rollDashLang);
	}

	// Set the ircDDBGateway Remote Password and Port
	if (empty($_POST['confPassword']) != TRUE ) {
	  $rollConfPassword0 = 'sudo sed -i "/remotePassword=/c\\remotePassword='.escapeshellcmd($_POST['confPassword']).'" /etc/ircddbgateway';
	  $rollConfPassword1 = 'sudo sed -i "/password=/c\\password='.escapeshellcmd($_POST['confPassword']).'" /root/.Remote\ Control';
	  $rollConfRemotePort = 'sudo sed -i "/port=/c\\port='.$configs['remotePort'].'" /root/.Remote\ Control';
	  system($rollConfPassword0);
	  system($rollConfPassword1);
	  system($rollConfRemotePort);
	}

	// Set the ircDDBGateway Defaut Reflector
	if (empty($_POST['confDefRef']) != TRUE ) {
	  if (stristr(strtoupper(escapeshellcmd($_POST['confDefRef'])), strtoupper(escapeshellcmd($_POST['confCallsign']))) != TRUE ) {
	    if (strlen($_POST['confDefRef']) != 7) {
		$targetRef = strtoupper(escapeshellcmd(str_pad($_POST['confDefRef'], 7, " ")));
	        } else {
		$targetRef = strtoupper(escapeshellcmd($_POST['confDefRef']));
	        }
	    $rollconfDefRef = 'sudo sed -i "/reflector1=/c\\reflector1='.$targetRef.escapeshellcmd($_POST['confDefRefLtr']).'" /etc/ircddbgateway';
	    system($rollconfDefRef);
	    }
	}

	// Set the ircDDBGAteway Defaut Reflector Autostart
	if (empty($_POST['confDefRefAuto']) != TRUE ) {
	  if (escapeshellcmd($_POST['confDefRefAuto']) == 'ON') {
	    $rollconfDefRefAuto = 'sudo sed -i "/atStartup1=/c\\atStartup1=1" /etc/ircddbgateway';
	  }
	  if (escapeshellcmd($_POST['confDefRefAuto']) == 'OFF') {
	    $rollconfDefRefAuto = 'sudo sed -i "/atStartup1=/c\\atStartup1=0" /etc/ircddbgateway';
	  }
	  system($rollconfDefRefAuto);
	}

	// Set random (working) CCS host.
	if ($configs['ccsEnabled'] == "1") {
	  $activeCCS = array("CCS701"=>"CCS701","CCS702"=>"CCS702","CCS704"=>"CCS704");
	  shuffle($activeCCS);
	  $rollCCS = 'sudo sed -i "/ccsHost=/c\\ccsHost='.$activeCCS[0].'" /etc/ircddbgateway';
	  system($rollCCS);
	}

	// Set the Latitude
	if (empty($_POST['confLatitude']) != TRUE ) {
	  $newConfLatitude = preg_replace('/[^0-9\.\-]/', '', $_POST['confLatitude']);
	  $rollConfLat0 = 'sudo sed -i "/latitude=/c\\latitude='.$newConfLatitude.'" /etc/ircddbgateway';
	  $rollConfLat1 = 'sudo sed -i "/latitude1=/c\\latitude1='.$newConfLatitude.'" /etc/ircddbgateway';
	  $configmmdvm['Info']['Latitude'] = $newConfLatitude;
	  $configysfgateway['Info']['Latitude'] = $newConfLatitude;
	  $configysf2dmr['Info']['Latitude'] = $newConfLatitude;
	  $configysf2nxdn['Info']['Latitude'] = $newConfLatitude;
	  $configysf2p25['Info']['Latitude'] = $newConfLatitude;
	  $configdgidgateway['Info']['Latitude'] = $newConfLatitude;
	  $configdmrgateway['Info']['Latitude'] = $newConfLatitude;
	  $configm17gateway['Info']['Latitude'] = $newConfLatitude;
	  $confignxdngateway['Info']['Latitude'] = $newConfLatitude;
	  system($rollConfLat0);
	  system($rollConfLat1);
	}

	// Set the Longitude
	if (empty($_POST['confLongitude']) != TRUE ) {
	  $newConfLongitude = preg_replace('/[^0-9\.\-]/', '', $_POST['confLongitude']);
	  $rollConfLon0 = 'sudo sed -i "/longitude=/c\\longitude='.$newConfLongitude.'" /etc/ircddbgateway';
	  $rollConfLon1 = 'sudo sed -i "/longitude1=/c\\longitude1='.$newConfLongitude.'" /etc/ircddbgateway';
	  $configmmdvm['Info']['Longitude'] = $newConfLongitude;
	  $configysfgateway['Info']['Longitude'] = $newConfLongitude;
	  $configysf2dmr['Info']['Longitude'] = $newConfLongitude;
	  $configysf2nxdn['Info']['Longitude'] = $newConfLongitude;
	  $configysf2p25['Info']['Longitude'] = $newConfLongitude;
	  $configdgidgateway['Info']['Longitude'] = $newConfLongitude;
	  $configdmrgateway['Info']['Longitude'] = $newConfLongitude;
	  $configm17gateway['Info']['Longitude'] = $newConfLongitude;
	  $confignxdngateway['Info']['Longitude'] = $newConfLongitude;
	  system($rollConfLon0);
	  system($rollConfLon1);
	}

	// Set GPSd
	if (empty($_POST['GPSD']) != TRUE ) {
	    $gpsdEnabled = (escapeshellcmd($_POST['GPSD']) == 'ON' ) ? "1" : "0";
	    $configdmrgateway['GPSD']['Enable'] = $gpsdEnabled;
	    $configysfgateway['GPSD']['Enable'] = $gpsdEnabled;
	    $configdgidgateway['GPSD']['Enable'] = $gpsdEnabled;
	    $confignxdngateway['GPSD']['Enable'] = $gpsdEnabled;
	    $configm17gateway['GPSD']['Enable'] = $gpsdEnabled;
	    $rollGpsd = 'sudo sed -i "/gpsdSEnabled=/c\\gpsdSEnabled='.$gpsdEnabled.'" /etc/ircddbgateway';
	    system($rollGpsd);

	   if (empty($_POST['gpsdPort']) != TRUE ) {
		$configdmrgateway['GPSD']['Port'] = escapeshellcmd($_POST['gpsdPort']);
	   }

	   if (empty($_POST['gpsdServer']) != TRUE ) {
		$configdmrgateway['GPSD']['Address'] = escapeshellcmd($_POST['gpsdServer']);
	   }

           // Set GPSD daemon On or Off
           if (escapeshellcmd($_POST['GPSD']) == 'ON') { system('sudo systemctl unmask gpsd.service ; sudo systemctl unmask gpsd.socket ; sudo systemctl enable gpsd.service ; sudo systemctl enable gpsd.socket'); }
           if (escapeshellcmd($_POST['GPSD']) == 'OFF')  { system('sudo systemctl stop gpsd.service ; sudo systemctl stop gpsd gpsd.socket ; sudo systemctl disable gpsd.service ; sudo systemctl disable gpsd.socket ; sudo systemctl mask gpsd.service ; sudo systemctl mask gpsd.socket'); }

	    // Port and Address for YSF, DGId, M17 and NXDN gateways
	    $configysfgateway['GPSD']['Port'] = $configdmrgateway['GPSD']['Port'];
	    $configysfgateway['GPSD']['Address'] = $configdmrgateway['GPSD']['Address'];
	    $configdgidgateway['GPSD']['Port'] = $configdmrgateway['GPSD']['Port'];
	    $configdgidgateway['GPSD']['Address'] = $configdmrgateway['GPSD']['Address'];

	    $configm17gateway['GPSD']['Port'] = $configdmrgateway['GPSD']['Port'];
	    $configm17gateway['GPSD']['Address'] = $configdmrgateway['GPSD']['Address'];
	    $confignxdngateway['GPSD']['Port'] = $configdmrgateway['GPSD']['Port'];
	    $confignxdngateway['GPSD']['Address'] = $configdmrgateway['GPSD']['Address'];
	}

	// Set the Town
	if (empty($_POST['confDesc1']) != TRUE ) {
	  $newConfDesc1 = preg_replace('/[^A-Za-z0-9\.\s\,\-]/', '', $_POST['confDesc1']);
	  $rollDesc1 = 'sudo sed -i "/description1=/c\\description1='.$newConfDesc1.'" /etc/ircddbgateway';
	  $rollDesc11 = 'sudo sed -i "/description1_1=/c\\description1_1='.$newConfDesc1.'" /etc/ircddbgateway';
	  $configmmdvm['Info']['Location'] = '"'.$newConfDesc1.'"';
	  $configdmrgateway['Info']['Location'] = '"'.$newConfDesc1.'"';
	  $configysf2dmr['Info']['Location'] = '"'.$newConfDesc1.'"';
	  $configysf2nxdn['Info']['Location'] = '"'.$newConfDesc1.'"';
	  $configysf2p25['Info']['Location'] = '"'.$newConfDesc1.'"';
	  $confignxdngateway['Info']['Name'] = '"'.$newConfDesc1.'"';
	  $configm1ngateway['Info']['Name'] = '"'.$newConfDesc1.'"';
	  system($rollDesc1);
	  system($rollDesc11);
	}

	// Set the Country
	if (empty($_POST['confDesc2']) != TRUE ) {
	  $newConfDesc2 = preg_replace('/[^A-Za-z0-9\.\s\,\-]/', '', $_POST['confDesc2']);
	  $rollDesc2 = 'sudo sed -i "/description2=/c\\description2='.$newConfDesc2.'" /etc/ircddbgateway';
	  $rollDesc22 = 'sudo sed -i "/description1_2=/c\\description1_2='.$newConfDesc2.'" /etc/ircddbgateway';
	  $configmmdvm['Info']['Description'] = '"'.$newConfDesc2.'"';
	  $configdmrgateway['Info']['Description'] = '"'.$newConfDesc2.'"';
	  $configm17gateway['Info']['Description'] = '"'.$newConfDesc2.'"';
	  $configysfgateway['Info']['Description'] = '"'.$newConfDesc2.'"';
	  $configdgidgateway['Info']['Description'] = '"'.$newConfDesc2.'"';
	  $confignxdngateway['Info']['Description'] = '"'.$newConfDesc2.'"';
	  system($rollDesc2);
	  system($rollDesc22);
	}

	// Set the URL
	if (empty($_POST['confURL']) != TRUE ) {
	  $newConfURL = strtolower(preg_replace('/[^A-Za-z0-9\.\s\,\-\/\:\?\=]/', '', $_POST['confURL']));
	  if (escapeshellcmd($_POST['urlAuto']) == 'auto') { $txtURL = "https://www.qrz.com/db/".strtoupper(escapeshellcmd($_POST['confCallsign'])); }
	  if (escapeshellcmd($_POST['urlAuto']) == 'man')  { $txtURL = $newConfURL; }
	  if (escapeshellcmd($_POST['urlAuto']) == 'auto') { $rollURL0 = 'sudo sed -i "/url=/c\\url=https://www.qrz.com/db/'.strtoupper(escapeshellcmd($_POST['confCallsign'])).'" /etc/ircddbgateway';  }
	  if (escapeshellcmd($_POST['urlAuto']) == 'man') { $rollURL0 = 'sudo sed -i "/url=/c\\url='.$newConfURL.'" /etc/ircddbgateway'; }
          $configmmdvm['Info']['URL'] = $txtURL;
	  $configysf2dmr['Info']['URL'] = $txtURL;
	  $configysf2nxdn['Info']['URL'] = $txtURL;
	  $configysf2p25['Info']['URL'] = $txtURL;
	  $configdmrgateway['Info']['URL'] = $txtURL;
	  $configm17gateway['Info']['URL'] = $txtURL;
	  system($rollURL0);
	}

	// Set the APRS Host for ircDDBGateway
	if (empty($_POST['selectedAPRSHost']) != TRUE ) {
	  $rollAPRSHost = 'sudo sed -i "/aprsHostname=/c\\aprsHostname='.escapeshellcmd($_POST['selectedAPRSHost']).'" /etc/ircddbgateway';
	  system($rollAPRSHost);
	  $configaprsgateway['APRS-IS']['Server'] = escapeshellcmd($_POST['selectedAPRSHost']);
	  $configysfgateway['aprs.fi']['Server'] = escapeshellcmd($_POST['selectedAPRSHost']);
	  $configysf2dmr['aprs.fi']['Server'] = escapeshellcmd($_POST['selectedAPRSHost']);
	  $configysf2nxdn['aprs.fi']['Server'] = escapeshellcmd($_POST['selectedAPRSHost']);
	  $configysf2p25['aprs.fi']['Server'] = escapeshellcmd($_POST['selectedAPRSHost']);
	  $configysf2dmr['aprs.fi']['Enable'] = "0";
	  $configysf2nxdn['aprs.fi']['Enable'] = "0";
	  $configysf2p25['aprs.fi']['Enable'] = "0";
	  $rollAPRSGatewayHost = 'sudo sed -i "/Server=/c\\Server='.escapeshellcmd($_POST['selectedAPRSHost']).'" /etc/aprsgateway';
	  system($rollAPRSGatewayHost);
	}

	// grab APRS user prefs from form submission (or current settings) so we can later update the configs.
	if ($_POST['symbol'] == "") { // no user selection; use default symbol
		$symbol = "Wi";
	} else {
		$symbol = $_POST['symbol'];
	}
	if (empty($_POST['DMRGatewayAPRS']) != TRUE) { // checked!
		$DMRGatewayAPRS = "1";
	} else {
		$DMRGatewayAPRS = "0";
	}
	if (empty($_POST['IRCDDBGatewayAPRS']) != TRUE) { // checked!
		$IRCDDBGatewayAPRS = "1";
	} else {
		$IRCDDBGatewayAPRS = "0";
	}
	if (empty($_POST['YSFGatewayAPRS']) != TRUE) { // checked!
		$YSFGatewayAPRS = "1";
	} else {
		$YSFGatewayAPRS = "0";
	}
	if (empty($_POST['DGIdGatewayAPRS']) != TRUE) { // checked!
		$DGIdGatewayAPRS = "1";
	} else {
		$DGIdGatewayAPRS = "0";
	}
	if (empty($_POST['NXDNGatewayAPRS']) != TRUE) { // checked!
		$NXDNGatewayAPRS = "1";
	} else {
		$NXDNGatewayAPRS = "0";
	}
	if (empty($_POST['M17GatewayAPRS']) != TRUE) { // checked!
		$M17GatewayAPRS = "1";
	} else {
		$M17GatewayAPRS = "0";
	}
	if (empty($_POST['DMRBeaconEnable']) != TRUE) { // checked!
		$DMRBeaconEnable = "1";
	} else {
		$DMRBeaconEnable = "0";
	}
	if (empty($_POST['DMRBeaconModeNet']) != TRUE)  { // checked!
		$DMRBeaconModeNet = "1";
	} else {
		$DMRBeaconModeNet = "0";
	}

	// Set ircDDBGateway and TimeServer language
	if (empty($_POST['ircDDBGatewayAnnounceLanguage']) != TRUE) {
	  $ircDDBGatewayAnnounceLanguageArr = explode(',', escapeshellcmd($_POST['ircDDBGatewayAnnounceLanguage']));
	  $rollIrcDDBGatewayLang = 'sudo sed -i "/language=/c\\language='.escapeshellcmd($ircDDBGatewayAnnounceLanguageArr[0]).'" /etc/ircddbgateway';
	  $rollTimeserverLang = 'sudo sed -i "/language=/c\\language='.escapeshellcmd($ircDDBGatewayAnnounceLanguageArr[1]).'" /etc/timeserver';
	  system($rollIrcDDBGatewayLang);
	  system($rollTimeserverLang);
	}

	// Clear timeserver modules
	$rollTimeserverBandA = 'sudo sed -i "/sendA=/c\\sendA=0" /etc/timeserver';
	$rollTimeserverBandB = 'sudo sed -i "/sendB=/c\\sendB=0" /etc/timeserver';
	$rollTimeserverBandC = 'sudo sed -i "/sendC=/c\\sendC=0" /etc/timeserver';
	$rollTimeserverBandD = 'sudo sed -i "/sendD=/c\\sendD=0" /etc/timeserver';
	$rollTimeserverBandE = 'sudo sed -i "/sendE=/c\\sendE=0" /etc/timeserver';
	system($rollTimeserverBandA);
	system($rollTimeserverBandB);
	system($rollTimeserverBandC);
	system($rollTimeserverBandD);
	system($rollTimeserverBandE);

	// Set the POCSAG Frequency
	if (empty($_POST['pocsagFrequency']) != TRUE ) {
	  $newPocsagFREQ = preg_replace('/[^0-9\.]/', '', $_POST['pocsagFrequency']);
	  $newPocsagFREQ = str_pad(str_replace(".", "", $newPocsagFREQ), 9, "0");
	  $newPocsagFREQ = mb_strimwidth($newPocsagFREQ, 0, 9);
	  $configmmdvm['POCSAG']['Frequency'] = $newPocsagFREQ;
	}

	// Set the POCSAG AuthKey
	if (empty($_POST['pocsagAuthKey']) != TRUE ) {
	  $configdapnetgw['DAPNET']['AuthKey'] = escapeshellcmd($_POST['pocsagAuthKey']);
	}

	// Set the POCSAG Callsign
	if (empty($_POST['pocsagCallsign']) != TRUE ) {
	  $configdapnetgw['General']['Callsign'] = strtoupper(escapeshellcmd($_POST['pocsagCallsign']));
	}

	// Set the POCSAG Whitelist
	//if (isset($configdapnetgw['General']['WhiteList'])) { unset($configdapnetgw['General']['WhiteList']); }
	if (empty($_POST['pocsagWhitelist']) != TRUE ) {
	  $configdapnetgw['General']['WhiteList'] = preg_replace('/[^0-9\,]/', '', escapeshellcmd($_POST['pocsagWhitelist']));
	} else {
	    unset($configdapnetgw['General']['WhiteList']);
	}

	// Set the POCSAG Blacklist
	if (isset($configdapnetgw['General']['BlackList'])) { unset($configdapnetgw['General']['BlackList']); }
	if (empty($_POST['pocsagBlacklist']) != TRUE ) {
	  $configdapnetgw['General']['BlackList'] = preg_replace('/[^0-9\,]/', '', escapeshellcmd($_POST['pocsagBlacklist']));
	}

	// Set the POCSAG Server
	if (empty($_POST['pocsagServer']) != TRUE ) {
	  $configdapnetgw['DAPNET']['Address'] = escapeshellcmd($_POST['pocsagServer']);
	}

	// Set the Frequency for Duplex
	if (empty($_POST['confFREQtx']) != TRUE && empty($_POST['confFREQrx']) != TRUE ) {
	  if (empty($_POST['confHardware']) != TRUE ) { $confHardware = escapeshellcmd($_POST['confHardware']); }
	  $newConfFREQtx = preg_replace('/[^0-9\.]/', '', $_POST['confFREQtx']);
	  $newConfFREQrx = preg_replace('/[^0-9\.]/', '', $_POST['confFREQrx']);
	  $newFREQtx = str_pad(str_replace(".", "", $newConfFREQtx), 9, "0");
	  $newFREQtx = mb_strimwidth($newFREQtx, 0, 9);
	  $newFREQrx = str_pad(str_replace(".", "", $newConfFREQrx), 9, "0");
	  $newFREQrx = mb_strimwidth($newFREQrx, 0, 9);
	  $newFREQirc = substr_replace($newFREQtx, '.', '3', 0);
	  $newFREQirc = mb_strimwidth($newFREQirc, 0, 9);
	  $newFREQOffset = ($newFREQrx - $newFREQtx)/1000000;
	  $newFREQOffset = number_format($newFREQOffset, 4, '.', '');
	  $rollFREQirc = 'sudo sed -i "/frequency1=/c\\frequency1='.$newFREQirc.'" /etc/ircddbgateway';
	  $rollGatewayType = 'sudo sed -i "/gatewayType=/c\\gatewayType=0" /etc/ircddbgateway';
	  $rollFREQOffset = 'sudo sed -i "/offset1=/c\\offset1='.$newFREQOffset.'" /etc/ircddbgateway';
	  $configmmdvm['Info']['RXFrequency'] = $newFREQrx;
	  $configmmdvm['Info']['TXFrequency'] = $newFREQtx;
	  $configdmrgateway['Info']['RXFrequency'] = $newFREQrx;
	  $configdmrgateway['Info']['TXFrequency'] = $newFREQtx;
	  $configm17gateway['Info']['RXFrequency'] = $newFREQrx;
	  $configm17gateway['Info']['TXFrequency'] = $newFREQtx;
	  $configysfgateway['Info']['RXFrequency'] = $newFREQrx;
	  $configysfgateway['Info']['TXFrequency'] = $newFREQtx;
	  $configysfgateway['General']['Suffix'] = "Y";
	  $configysf2dmr['Info']['RXFrequency'] = $newFREQrx;
	  $configysf2dmr['Info']['TXFrequency'] = $newFREQrx;
	  $configysf2dmr['YSF Network']['Suffix'] = "Y";
	  $configysf2nxdn['Info']['RXFrequency'] = $newFREQrx;
	  $configysf2nxdn['Info']['TXFrequency'] = $newFREQtx;
	  $configysf2nxdn['YSF Network']['Suffix'] = "N";
	  $configysf2p25['Info']['RXFrequency'] = $newFREQrx;
	  $configysf2p25['Info']['TXFrequency'] = $newFREQtx;
	  $configysf2p25['YSF Network']['Suffix'] = "Y";
	  $configdgidgateway['Info']['RXFrequency'] = $newFREQrx;
	  $configdgidgateway['Info']['TXFrequency'] = $newFREQtx;
	  $configdgidgateway['General']['Suffix'] = "Y";
	  $configdmr2ysf['YSF Network']['Suffix'] = "R";
	  $confignxdngateway['Info']['RXFrequency'] = $newFREQrx;
	  $confignxdngateway['Info']['TXFrequency'] = $newFREQtx;
	  $confignxdngateway['General']['Suffix'] = "N";

	  system($rollFREQirc);
	  system($rollGatewayType);
	  system($rollFREQOffset);

	  // Set RPT1 and RPT2
	  if (empty($_POST['confDStarModuleSuffix'])) {
	    if ($newFREQtx >= 1240000000 && $newFREQtx <= 1300000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$configmmdvm['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    if ($newFREQtx >= 420000000 && $newFREQtx <= 450000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."B";
		$confIRCrepeaterBand1 = "B";
		$configmmdvm['D-Star']['Module'] = "B";
		$rollTimeserverBand = 'sudo sed -i "/sendB=/c\\sendB=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    if ($newFREQtx >= 218000000 && $newFREQtx <= 226000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$configmmdvm['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /etc/timeserver';
		system($rollTimeserverBand);
		}
	    if ($newFREQtx >= 144000000 && $newFREQtx <= 148000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."C";
		$confIRCrepeaterBand1 = "C";
		$configmmdvm['D-Star']['Module'] = "C";
		$rollTimeserverBand = 'sudo sed -i "/sendC=/c\\sendC=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    // Begin DVMega Cast logic...
	    if (isDVmegaCast() == 1) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."E";
		$confIRCrepeaterBand1 = "E";
		$configmmdvm['D-Star']['Module'] = "E";
		$rollTimeserverBand = 'sudo sed -i "/sendE=/c\\sendE=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    // End DVMega Cast logic
	  }
	  else {
	     $confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ").strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $confIRCrepeaterBand1 = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $configmmdvm['D-Star']['Module'] = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $rollTimeserverBand = 'sudo sed -i "/send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=/c\\send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=1" /etc/timeserver';
	     system($rollTimeserverBand);
	  }

	  $newCallsignUpper = strtoupper(escapeshellcmd($_POST['confCallsign']));
	  $confRPT2 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."G";

	  $confRPT1 = strtoupper($confRPT1);
	  $confRPT2 = strtoupper($confRPT2);

	  $rollIRCrepeaterBand1 = 'sudo sed -i "/repeaterBand1=/c\\repeaterBand1='.$confIRCrepeaterBand1.'" /etc/ircddbgateway';
	  $rollIRCrepeaterCall1 = 'sudo sed -i "/repeaterCall1=/c\\repeaterCall1='.$newCallsignUpper.'" /etc/ircddbgateway';

	  system($rollIRCrepeaterBand1);
	  system($rollIRCrepeaterCall1);
	}

	// Set the Frequency for Simplex
	if (empty($_POST['confFREQ']) != TRUE ) {
	  if (empty($_POST['confHardware']) != TRUE ) { $confHardware = escapeshellcmd($_POST['confHardware']); }
	  $newConfFREQ = preg_replace('/[^0-9\.]/', '', $_POST['confFREQ']);
	  $newFREQ = str_pad(str_replace(".", "", $newConfFREQ), 9, "0");
	  $newFREQ = mb_strimwidth($newFREQ, 0, 9);
	  $newFREQirc = substr_replace($newFREQ, '.', '3', 0);
	  $newFREQirc = mb_strimwidth($newFREQirc, 0, 9);
	  $newFREQOffset = "0.0000";
	  $rollFREQirc = 'sudo sed -i "/frequency1=/c\\frequency1='.$newFREQirc.'" /etc/ircddbgateway';
	  $rollGatewayType = 'sudo sed -i "/gatewayType=/c\\gatewayType=1" /etc/ircddbgateway';
	  $rollFREQOffset = 'sudo sed -i "/offset1=/c\\offset1='.$newFREQOffset.'" /etc/ircddbgateway';
	  $configmmdvm['Info']['RXFrequency'] = $newFREQ;
	  $configmmdvm['Info']['TXFrequency'] = $newFREQ;
	  $configdmrgateway['Info']['RXFrequency'] = $newFREQ;
	  $configdmrgateway['Info']['TXFrequency'] = $newFREQ;
	  $configm17gateway['Info']['RXFrequency'] = $newFREQ;
	  $configm17gateway['Info']['TXFrequency'] = $newFREQ;
	  $configysfgateway['Info']['RXFrequency'] = $newFREQ;
	  $configysfgateway['Info']['TXFrequency'] = $newFREQ;
	  $configysfgateway['General']['Suffix'] = "Y";
	  $configysf2dmr['Info']['RXFrequency'] = $newFREQ;
	  $configysf2dmr['Info']['TXFrequency'] = $newFREQ;
	  $configysf2dmr['YSF Network']['Suffix'] = "Y";
	  $configysf2nxdn['Info']['RXFrequency'] = $newFREQ;
	  $configysf2nxdn['Info']['TXFrequency'] = $newFREQ;
	  $configysf2nxdn['YSF Network']['Suffix'] = "Y";
	  $configysf2p25['Info']['RXFrequency'] = $newFREQ;
	  $configysf2p25['Info']['TXFrequency'] = $newFREQ;
	  $configysf2p25['YSF Network']['Suffix'] = "Y";
	  $configdgidgateway['Info']['RXFrequency'] = $newFREQ;
	  $configdgidgateway['Info']['TXFrequency'] = $newFREQ;
	  $configdgidgateway['General']['Suffix'] = "Y";
	  $configdmr2ysf['YSF Network']['Suffix'] = "R";
	  $confignxdngateway['Info']['RXFrequency'] = $newFREQ;
	  $confignxdngateway['Info']['TXFrequency'] = $newFREQ;
	  $confignxdngateway['General']['Suffix'] = "N";

	  system($rollFREQirc);
	  system($rollGatewayType);
	  system($rollFREQOffset);

	  // Set RPT1 and RPT2
	  if (empty($_POST['confDStarModuleSuffix'])) {
	    if ($newFREQ >= 1240000000 && $newFREQ <= 1300000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$configmmdvm['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    if ($newFREQ >= 420000000 && $newFREQ <= 450000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."B";
		$confIRCrepeaterBand1 = "B";
		$configmmdvm['D-Star']['Module'] = "B";
		$rollTimeserverBand = 'sudo sed -i "/sendB=/c\\sendB=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    if ($newFREQ >= 218000000 && $newFREQ <= 226000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$configmmdvm['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    if ($newFREQ >= 144000000 && $newFREQ <= 148000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."C";
		$confIRCrepeaterBand1 = "C";
		$configmmdvm['D-Star']['Module'] = "C";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    // Begin DVMega Cast logic...
	    if (isDVmegaCast() == 1) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."E";
		$confIRCrepeaterBand1 = "E";
		$configmmdvm['D-Star']['Module'] = "E";
		$rollTimeserverBand = 'sudo sed -i "/sendE=/c\\sendE=1" /etc/timeserver';
		system($rollTimeserverBand);
	    }
	    // End DVMega Cast logic
	  }
	  else {
	     $confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ").strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $confIRCrepeaterBand1 = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $configmmdvm['D-Star']['Module'] = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $rollTimeserverBand = 'sudo sed -i "/send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=/c\\send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=1" /etc/timeserver';
	     system($rollTimeserverBand);
	  }

	  $newCallsignUpper = strtoupper(escapeshellcmd($_POST['confCallsign']));
	  $confRPT2 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."G";

	  $confRPT1 = strtoupper($confRPT1);
	  $confRPT2 = strtoupper($confRPT2);

	  $rollIRCrepeaterBand1 = 'sudo sed -i "/repeaterBand1=/c\\repeaterBand1='.$confIRCrepeaterBand1.'" /etc/ircddbgateway';
	  $rollIRCrepeaterCall1 = 'sudo sed -i "/repeaterCall1=/c\\repeaterCall1='.$newCallsignUpper.'" /etc/ircddbgateway';

	  system($rollIRCrepeaterBand1);
	  system($rollIRCrepeaterCall1);
	}

        // ircDDB time annnouncement intervals
        $timeServerInt = escapeshellcmd($_POST['confTimeAnnounceInt']);
        $rollTimeServerInt = 'sudo sed -i "/interval=/c\\interval='.$timeServerInt.'" /etc/timeserver';
        system($rollTimeServerInt);

	// Set Callsign
	if (empty($_POST['confCallsign']) != TRUE ) {
	  $newCallsignUpper = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['confCallsign']));
	  $newCallsignUpperIRC = $newCallsignUpper;

	  $rollGATECALL = 'sudo sed -i "/gatewayCallsign=/c\\gatewayCallsign='.$newCallsignUpper.'" /etc/ircddbgateway';
	  $rollDPLUSLOGIN = 'sudo sed -i "/dplusLogin=/c\\dplusLogin='.str_pad($newCallsignUpper, 8, " ").'" /etc/ircddbgateway';
	  $rollDASHBOARDcall = 'sudo sed -i "/callsign=/c\\$callsign=\''.$newCallsignUpper.'\';" /var/www/dashboard/config/ircddblocal.php';
	  $rollTIMESERVERcall = 'sudo sed -i "/callsign=/c\\callsign='.$newCallsignUpper.'" /etc/timeserver';
	  $rollSTARNETSERVERcall = 'sudo sed -i "/callsign=/c\\callsign='.$newCallsignUpper.'" /etc/starnetserver';
	  $rollSTARNETSERVERirc = 'sudo sed -i "/ircddbUsername=/c\\ircddbUsername='.$newCallsignUpperIRC.'" /etc/starnetserver';

	  // Only roll ircDDBGateway Username if using OpenQuad
	  if (strpos($configs['ircddbHostname'], 'openquad.net') !== false) {
		  $rollIRCUSER = 'sudo sed -i "/ircddbUsername=/c\\ircddbUsername='.$newCallsignUpperIRC.'" /etc/ircddbgateway';
		  system($rollIRCUSER);
	  }

	  $configysfgateway['General']['Callsign'] = $newCallsignUpper;
	  $configmmdvm['General']['Callsign'] = $newCallsignUpper;
	  $configysfgateway['aprs.fi']['Password'] = aprspass($newCallsignUpper);
	  $configysfgateway['aprs.fi']['Description'] = $newCallsignUpper."_WPSD";
	  $configysf2dmr['aprs.fi']['Password'] = aprspass($newCallsignUpper);
	  $configysf2dmr['aprs.fi']['Description'] = $newCallsignUpper."_WPSD";
	  $configysf2dmr['aprs.fi']['AprsCallsign'] = $newCallsignUpper;
	  $configysf2dmr['YSF Network']['Callsign'] = $newCallsignUpper;
	  $configysf2nxdn['aprs.fi']['Password'] = aprspass($newCallsignUpper);
	  $configysf2nxdn['aprs.fi']['Description'] = $newCallsignUpper."_WPSD";
	  $configysf2nxdn['YSF Network']['Callsign'] = $newCallsignUpper;
	  $configysf2p25['aprs.fi']['Password'] = aprspass($newCallsignUpper);
	  $configysf2p25['aprs.fi']['Description'] = $newCallsignUpper."_WPSD";
	  $configysf2p25['YSF Network']['Callsign'] = $newCallsignUpper;
	  $configdmr2ysf['YSF Network']['Callsign'] = $newCallsignUpper;
	  $configp25gateway['General']['Callsign'] = $newCallsignUpper;
	  $confignxdngateway['aprs.fi']['Description'] = $newCallsignUpper."_WPSD";
	  $confignxdngateway['aprs.fi']['Password'] = aprspass($newCallsignUpper);
	  $confignxdngateway['General']['Callsign'] = $newCallsignUpper;
	  $configysfgateway['Info']['Name'] = $newCallsignUpper."_WPSD";
	  $configysf2dmr['Info']['Description'] = $newCallsignUpper."_WPSD";
	  $configysf2nxdn['Info']['Description'] = $newCallsignUpper."_WPSD";
	  $configysf2p25['Info']['Description'] = $newCallsignUpper."_WPSD";
	  $configdgidgateway['General']['Callsign'] = $newCallsignUpper;
	  $configdgidgateway['Info']['Description'] = $newCallsignUpper."_WPSD";
	  $configm17gateway['Info']['Description'] = $newCallsignUpper."_WPSD";
	  $rollAPRSGatewayCallsign = 'sudo sed -i "/Callsign=/c\\Callsign='.$newCallsignUpper.'" /etc/aprsgateway';
	  system($rollAPRSGatewayCallsign);
	  $rollAPRSGatewayPassword = 'sudo sed -i "/Password=/c\\Password='.aprspass($newCallsignUpper).'" /etc/aprsgateway';
	  system($rollAPRSGatewayPassword);
	  $rollircDDBGatewayAprsPort = 'sudo sed -i "/aprsPort=/c\\aprsPort=8673" /etc/ircddbgateway';
	  system($rollircDDBGatewayAprsPort);
	  unset($configs['aprsPassword']);
	  $rollircDDBGatewayAprsPass = 'sudo sed -i "/aprsPassword/d" /etc/ircddbgateway';
	  system($rollircDDBGatewayAprsPass);
	  if (empty($_POST['APRSGatewayEnable']) != TRUE ) {
		if (escapeshellcmd($_POST['APRSGatewayEnable']) == 'ON' )  { $rollAPRSGatewayEnable = 'sudo sed -i "/Enabled=/c\\Enabled=1" /etc/aprsgateway'; }
 		if (escapeshellcmd($_POST['APRSGatewayEnable']) == 'OFF' ) { $rollAPRSGatewayEnable = 'sudo sed -i "/Enabled=/c\\Enabled=0" /etc/aprsgateway'; }
	  }
	  system($rollAPRSGatewayEnable);

	  // If ircDDBGateway config supports APRS Password
	  if (isset($configs['aprsPassword'])) {
		  $rollircDDBGatewayAprsPassword = 'sudo sed -i "/aprsPassword=/c\\aprsPassword='.aprspass($newCallsignUpper).'" /etc/ircddbgateway';
		  system($rollircDDBGatewayAprsPassword);
	  }

	  system($rollGATECALL);
	  system($rollDPLUSLOGIN);
	  system($rollDASHBOARDcall);
	  system($rollTIMESERVERcall);
	  system($rollSTARNETSERVERcall);
	  system($rollSTARNETSERVERirc);
	}

	// Set the ircDDB Callsign routing option
	if (empty($_POST['confircddbEnabled']) != TRUE ) {
		if (escapeshellcmd($_POST['confircddbEnabled']) == 'ON' ) {
			$rollconfircddbEnabled = 'sudo sed -i "/rcddbEnabled=/c\\ircddbEnabled=1" /etc/ircddbgateway';
		}
		if (escapeshellcmd($_POST['confircddbEnabled']) == 'OFF' ) {
			$rollconfircddbEnabled = 'sudo sed -i "/rcddbEnabled=/c\\ircddbEnabled=0" /etc/ircddbgateway';
		}
		if (isset($configs['ircddbHostname']) && $configs['ircddbHostname'] == "rr.openquad.net") {
			$rollconfircddbEnabled = 'sudo sed -i "/rcddbEnabled=/c\\ircddbEnabled=0" /etc/ircddbgateway';
			$rollconfircddbHostname = 'sudo sed -i "/rcddbHostname=/c\\ircddbHostname=ircv4.openquad.net" /etc/ircddbgateway';
			system($rollconfircddbHostname);

		}
		system($rollconfircddbEnabled);
	}

	// Set the P25 Startup Host
	if (empty($_POST['p25StartupHost']) != TRUE ) {
          $newP25StartupHost = strtoupper(escapeshellcmd($_POST['p25StartupHost']));
          if ($newP25StartupHost === "NONE") {
		  unset($configp25gateway['Network']['Startup']);
		  unset($configysf2p25['P25 Network']['StartupDstId']);
		  unset($configp25gateway['Network']['Static']);
	  } else {
		  $configp25gateway['Network']['Startup'] = $newP25StartupHost;
		  $configysf2p25['P25 Network']['StartupDstId'] = $newP25StartupHost;
	  }
	}

	// Set P25 NAC
	if (empty($_POST['p25nac']) != TRUE ) {
	  $p25nacNew = strtolower(escapeshellcmd($_POST['p25nac']));
	  if (preg_match('/[a-f0-9]{3}/', $p25nacNew)) {
	    $configmmdvm['P25']['NAC'] = $p25nacNew;
	  }
	}

	// Set the NXDN Startup Host
	if (empty($_POST['nxdnStartupHost']) != TRUE ) {
	  $newNXDNStartupHost = strtoupper(escapeshellcmd($_POST['nxdnStartupHost']));
	  if (file_exists('/etc/nxdngateway')) {
		if ($newNXDNStartupHost === "NONE") {
			if (isset($confignxdngateway['Network']['Startup'])) { unset($confignxdngateway['Network']['Startup']); }
			if (isset($confignxdngateway['Network']['Static']))  { unset($confignxdngateway['Network']['Static']); }
		} else {
			$confignxdngateway['Network']['Startup'] = $newNXDNStartupHost;
	  	}
	  } else {
		$configmmdvm['NXDN Network']['GatewayAddress'] = $newNXDNStartupHost;
		$configmmdvm['NXDN Network']['GatewayPort'] = "41007";
	  }
	  $configysf2nxdn['NXDN Network']['StartupDstId'] = $newNXDNStartupHost;
	}

	// Set NXDN RAN
	if (empty($_POST['nxdnran']) != TRUE ) {
	  $nxdnranNew = strtolower(escapeshellcmd($_POST['nxdnran']));
	  $nxdnranNew = preg_replace('/[^0-9]/', '', $nxdnranNew);
	  if (($nxdnranNew >= 1) && ($nxdnranNew <= 64)) {
	      $configmmdvm['NXDN']['RAN'] = $nxdnranNew;
	  }
	}

	// Set the M17 Startup Reflector
	if (empty($_POST['m17StartupRef']) != TRUE ) {
		$newM17StartupReflector = strtoupper(escapeshellcmd($_POST['m17StartupRef']));
		if ($newM17StartupReflector === "NONE") {
			if (isset($configm17gateway['Network']['Startup'])) { unset($configm17gateway['Network']['Startup']); }
		} else {
			$newM17StartupModule = strtoupper(escapeshellcmd($_POST['m17StartupModule']));
			$configm17gateway['Network']['Startup'] = "${newM17StartupReflector}_${newM17StartupModule}";
	  }
	}

	// Set M17 CAN
	if (isset($_POST['m17can']) && $_POST['m17can'] !== '') {
	    $m17canNew = strtolower(escapeshellcmd($_POST['m17can']));
	    $m17canNew = (int) preg_replace('/[^0-9]/', '', $m17canNew);
	    if (($m17canNew >= 0) && ($m17canNew <= 15)) {
	        $configmmdvm['M17']['CAN'] = $m17canNew;
	    }
	}

	// Set M17 Callsign Suffix
	if (!empty($_POST['m17CallsignSuffix']) && '' !== $_POST['m17CallsignSuffix'] ) {
		$m17SuffixNew = strtoupper(escapeshellcmd($_POST['m17CallsignSuffix']));
		$m17SuffixNew = preg_replace('/[^A-Z]/', '', $m17SuffixNew);
		if (preg_match('/[A-Za-z]/i', $m17SuffixNew)) {
			$configm17gateway['General']['Suffix'] = $m17SuffixNew;
			$configm17gateway['APRS']['Suffix'] = $m17SuffixNew;
		}
	}

        // Set M17 Callsign Station ID
        if (isset($_POST['m17StationID']) && '' !== $_POST['m17StationID']) {
                $m17StationIDnew = escapeshellcmd($_POST['m17StationID']);
		$m17StationIDnew = str_replace(' ', '', $m17StationIDnew);
                $configm17gateway['General']['Callsign'] = $newCallsignUpper . "-" . $m17StationIDnew;
        } else {
                $configm17gateway['General']['Callsign'] = $newCallsignUpper;
        }

	// Set the DMR2M17 Startup Reflector
	if (empty($_POST['dmr2m17StartupRef']) != TRUE ) {
		$newDMR2M17StartupReflector = strtoupper(escapeshellcmd($_POST['dmr2m17StartupRef']));
		if ($newDMR2M17StartupReflector === "NONE") {
			if (isset($configdmr2m17['M17 Network']['DstName'])) { unset($configdmr2m17['M17 Network']['DstName']); }
		} else {
		    $newDMR2M17StartupModule = strtoupper(escapeshellcmd($_POST['dmr2m17StartupModule']));
		    $configdmr2m17['M17 Network']['DstName'] = "${newdmr2M17StartupReflector} ${newdmr2M17StartupModule}";
		}
	}

	// Set the YSF Startup Host
	if (empty($_POST['ysfStartupHost']) != TRUE ) {
	  $newYSFStartupHostArr = explode(',', escapeshellcmd($_POST['ysfStartupHost']));
	  if (isset($configysfgateway['FCS Network'])) {
		if ($newYSFStartupHostArr[0] == "none") {
			unset($configysfgateway['Network']['Startup']);
			$configdmr2ysf['DMR Network']['DefaultDstTG'] = "9";
		}
	  	else {
			$configysfgateway['Network']['Startup'] = $newYSFStartupHostArr[1];
			if (substr( $newYSFStartupHostArr[0], 0, 3 ) !== "FCS") {
				$configdmr2ysf['DMR Network']['DefaultDstTG'] = $newYSFStartupHostArr[0];
			} else {
				$configdmr2ysf['DMR Network']['DefaultDstTG'] = "9";
			}
		}
	  } else {
	  	if ($newYSFStartupHostArr[0] == "none") {
			unset($configysfgateway['Network']['Startup']);
			$configdmr2ysf['DMR Network']['DefaultDstTG'] = "9";
		}
	  	else {
			$configysfgateway['Network']['Startup'] = $newYSFStartupHostArr[0];
			if (substr( $newYSFStartupHostArr[0], 0, 3 ) !== "FCS") {
				$configdmr2ysf['DMR Network']['DefaultDstTG'] = $newYSFStartupHostArr[0];
			} else {
				$configdmr2ysf['DMR Network']['DefaultDstTG'] = "9";
			}
		}
	  }
	}

	// Set YSFGateway to automatically pass through WiresX
	if (empty($_POST['wiresXCommandPassthrough']) != TRUE ) {
	  if (escapeshellcmd($_POST['wiresXCommandPassthrough']) == 'ON' )  { $configysfgateway['General']['WiresXCommandPassthrough'] = "1"; }
	  if (escapeshellcmd($_POST['wiresXCommandPassthrough']) == 'OFF' ) { $configysfgateway['General']['WiresXCommandPassthrough'] = "0"; }
	}

	// Toggle for the annoying FCS network
        if (empty($_POST['FCSEnable']) != TRUE ) {
                if (escapeshellcmd($_POST['FCSEnable']) == 'ON' )  { $configysfgateway['FCS Network']['Enable'] = "1"; }
                if (escapeshellcmd($_POST['FCSEnable']) == 'OFF' ) { $configysfgateway['FCS Network']['Enable'] = "0"; }
        }

	// Remove hostfiles.ysfupper and use the new YSFGateway Feature
	if (empty($_POST['confHostFilesYSFUpper']) != TRUE ) {
		if (escapeshellcmd($_POST['confHostFilesYSFUpper']) == 'ON' )   { $configysfgateway['General']['WiresXMakeUpper'] = "1"; }
		if (escapeshellcmd($_POST['confHostFilesYSFUpper']) == 'OFF' )  { $configysfgateway['General']['WiresXMakeUpper'] = "0"; }
		if (file_exists('/etc/hostfiles.ysfupper')) { system('sudo rm -rf /etc/hostfiles.ysfupper'); }
	}

        // Enable DGIdGateway
        if (isset($configdgidgateway)) {
            if (empty($_POST['useDGIdGateway']) != TRUE ) {
                if (escapeshellcmd($_POST['useDGIdGateway']) == 'ON' )  {
		    $configdgidgateway['Enabled']['Enabled'] = "1";
		    $configysf2dmr['Enabled']['Enabled'] = "0"; // dgidgateway causes port/comm conflicts with YSF2***
		    $configysf2p25['Enabled']['Enabled'] = "0";
		    $configysf2nxdn['Enabled']['Enabled'] = "0";
		}
                if (escapeshellcmd($_POST['useDGIdGateway']) == 'OFF' ) {
		    $configdgidgateway['Enabled']['Enabled'] = "0";
		}
            }
	}

	// Set the YSFGateway Options for YCS static DG-ID
	if (empty($_POST['ysfgatewayNetworkOptions']) != TRUE ) {
		$ysfOptionsLineStripped = str_replace('"', "", $_POST['ysfgatewayNetworkOptions']);
		$configysfgateway['Network']['Options'] = '"'.$ysfOptionsLineStripped.'"';
	}
	else {
		unset ($configysfgateway['Network']['Options']);
	}

	// Set the YSF2DMR Master
	if (empty($_POST['ysf2dmrMasterHost']) != TRUE ) {
	  $ysf2dmrMasterHostArr = explode(',', escapeshellcmd($_POST['ysf2dmrMasterHost']));
	  $configysf2dmr['DMR Network']['Address'] = $ysf2dmrMasterHostArr[0];
	  $configysf2dmr['DMR Network']['Password'] = '"'.$ysf2dmrMasterHostArr[1].'"';
	  $configysf2dmr['DMR Network']['Port'] = $ysf2dmrMasterHostArr[2];

	 // Set the YSF2DMR Options
	 if (empty($_POST['ysf2dmrNetworkOptions']) != TRUE ) {
	 	$ysf2dmrOptionsLineStripped = str_replace('"', "", $_POST['ysf2dmrNetworkOptions']);
		$configysf2dmr['DMR Network']['Options'] = '"'.$ysf2dmrOptionsLineStripped.'"';
	 }
	 else {
		unset ($configysf2dmr['DMR Network']['Options']);
	 }

	 // Set the YSF2DMR BM pass...
	 if (isset($_POST['bmHSSecurity_YSF'])) {
	     if (empty($_POST['bmHSSecurity_YSF']) != TRUE ) {
		 $configysf2dmr['DMR Network']['Password'] = '"'.$_POST['bmHSSecurity_YSF'].'"';
		 $configModem['BrandMeister']['Password'] = '"'.$_POST['bmHSSecurity_YSF'].'"';
	     } else {
		 unset ($configModem['BrandMeister']['Password']);
	    }
	  }
 	}

	// Set the YSF2DMR Starting TG
	if (empty($_POST['ysf2dmrTg']) != TRUE ) {
	  $ysf2dmrStartupDstId = preg_replace('/[^0-9]/', '', $_POST['ysf2dmrTg']);
	  $configysf2dmr['DMR Network']['StartupDstId'] = $ysf2dmrStartupDstId;
	}

	// Set the YSF2NXDN Master
	if (empty($_POST['ysf2nxdnStartupDstId']) != TRUE ) {
	  $configysf2nxdn['NXDN Network']['StartupDstId'] = escapeshellcmd($_POST['ysf2nxdnStartupDstId']);
	  if (file_exists('/etc/nxdngateway')) {
	    if (escapeshellcmd($_POST['ysf2nxdnStartupDstId']) === "none") {
	      unset($confignxdngateway['Network']['Startup']);
	      unset($confignxdngateway['Network']['Static']);
	    } else {
	      $confignxdngateway['Network']['Startup'] = escapeshellcmd($_POST['ysf2nxdnStartupDstId']);
	    }		  
	  }
	}

	// Set the YSF2P25 Master
	if (empty($_POST['ysf2p25StartupDstId']) != TRUE ) {
	  $newYSF2P25StartupHost = strtoupper(escapeshellcmd($_POST['ysf2p25StartupDstId']));

	  if ($newYSF2P25StartupHost === "NONE") {
		  unset($configp25gateway['Network']['Startup']);
		  unset($configp25gateway['Network']['Static']);
		  unset($configysf2p25['P25 Network']['StartupDstId']);
	  } else {
		  $configp25gateway['Network']['Startup'] = $newYSF2P25StartupHost;
		  $configysf2p25['P25 Network']['StartupDstId'] = $newYSF2P25StartupHost;
	  }
	}

	// Set Duplex
	if (empty($_POST['trxMode']) != TRUE ) {
	  if ($configmmdvm['Info']['RXFrequency'] === $configmmdvm['Info']['TXFrequency'] && $_POST['trxMode'] == "DUPLEX" ) {
	    $configmmdvm['Info']['RXFrequency'] = $configmmdvm['Info']['TXFrequency'] - 1;
	    }
	  if ($configmmdvm['Info']['RXFrequency'] !== $configmmdvm['Info']['TXFrequency'] && $_POST['trxMode'] == "SIMPLEX" ) {
	    $configmmdvm['Info']['RXFrequency'] = $configmmdvm['Info']['TXFrequency'];
	    }
	  if ($_POST['trxMode'] == "DUPLEX") {
	    $configmmdvm['General']['Duplex'] = 1;
	    $configmmdvm['DMR Network']['Slot1'] = '1';
	    $configmmdvm['DMR Network']['Slot2'] = '1';
	  }
	  if ($_POST['trxMode'] == "SIMPLEX") {
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = '0';
	    $configmmdvm['DMR Network']['Slot2'] = '1';
	  }
	}

	// Set DMR / CCS7 ID
	if (empty($_POST['dmrId']) != TRUE ) {
	  $newPostDmrId = preg_replace('/[^0-9]/', '', $_POST['dmrId']);
	  $newPostDmrId = substr($newPostDmrId, 0, 7);

	  $configmmdvm['General']['Id'] = $newPostDmrId;
	  $configmmdvm['DMR']['Id'] = $newPostDmrId;

	  $configysfgateway['General']['Id'] = $newPostDmrId;

	  $configdmrgateway['XLX Network']['Id'] = $newPostDmrId;

	  $configdmr2ysf['DMR Network']['Id'] = $newPostDmrId;
	  $configdmr2nxdn['DMR Network']['Id'] = $newPostDmrId;

	  $configdgidgateway['General']['Id'] = $newPostDmrId;

	  $configysf2dmr['DMR Network']['Id'] = $newPostDmrId;
	  $configysf2p25['P25 Network']['Id'] = $newPostDmrId;
	}

	// Set DMR Extended ID
	if (empty($_POST['dmrExtendedId']) != TRUE ) {
	  $newPostdmrExtendedId = preg_replace('/[^0-9]/', '', $_POST['dmrExtendedId']);
	  $configmmdvm['DMR']['Id'] = $configmmdvm['General']['Id'].$newPostdmrExtendedId;
	}

	// Set NXDN ID
	if (empty($_POST['nxdnId']) != TRUE ) {
	  $newPostNxdnId = preg_replace('/[^0-9]/', '', $_POST['nxdnId']);
	  $configmmdvm['NXDN']['Id'] = $newPostNxdnId;
	  $configysf2nxdn['NXDN Network']['Id'] = $newPostNxdnId;
	  if ($configmmdvm['NXDN']['Id'] > 65535) { unset($configmmdvm['NXDN']['Id']); }
	}

	// Set BrandMeister Extended ID
	if (empty($_POST['bmExtendedId']) != TRUE ) {
	  $newPostbmExtendedId = preg_replace('/[^0-9]/', '', $_POST['bmExtendedId']);
	  $configdmrgateway['DMR Network 1']['Id'] = $configmmdvm['General']['Id'].$newPostbmExtendedId;
	}

	// Set DMR+/FreeDMR/HBLink Extended ID
	if (empty($_POST['dmrPlusExtendedId']) != TRUE ) {
	  $newPostdmrPlusExtendedId = preg_replace('/[^0-9]/', '', $_POST['dmrPlusExtendedId']);
	  $configdmrgateway['DMR Network 2']['Id'] = $configmmdvm['General']['Id'].$newPostdmrPlusExtendedId;
	}

	// Set SystemX Extended ID
	if (empty($_POST['SystemXExtendedId']) != TRUE ) {
	  $newPostSystemXExtendedId = preg_replace('/[^0-9]/', '', $_POST['SystemXExtendedId']);
	  $configdmrgateway['DMR Network 5']['Id'] = $configmmdvm['General']['Id'].$newPostSystemXExtendedId;
	}

	// Set YSF2DMR ID
	if (empty($_POST['ysf2dmrId']) != TRUE ) {
	  $newPostYsf2DmrId = preg_replace('/[^0-9]/', '', $_POST['ysf2dmrId']);
	  $configysf2dmr['DMR Network']['Id'] = $newPostYsf2DmrId;
	}

	// Set DMR Master Server
	if (empty($_POST['dmrMasterHost']) != TRUE ) {
	    $dmrMasterHostArr = explode(',', escapeshellcmd($_POST['dmrMasterHost']));
	    $configmmdvm['DMR Network']['Address'] = $dmrMasterHostArr[0];
	    $configmmdvm['DMR Network']['RemoteAddress'] = $dmrMasterHostArr[0];
	    $configmmdvm['DMR Network']['Password'] = '"'.$dmrMasterHostArr[1].'"';
	    $configmmdvm['DMR Network']['Port'] = $dmrMasterHostArr[2];
	    $configmmdvm['DMR Network']['RemotePort'] = $dmrMasterHostArr[2];

	    if (empty($_POST['bmHSSecurity']) != TRUE ) {
	    	$configModem['BrandMeister']['Password'] = '"'.$_POST['bmHSSecurity'].'"';
		if ($dmrMasterHostArr[0] != '127.0.0.1') {
		    $configmmdvm['DMR Network']['Password'] = '"'.$_POST['bmHSSecurity'].'"';
		}
	    } else if (empty($_POST['bmHSSecurity_YSF']) != TRUE ) {
	    	$configModem['BrandMeister']['Password'] = '"'.$_POST['bmHSSecurity_YSF'].'"';
		if ($dmrMasterHostArr[0] != '127.0.0.1') {
		    $configmmdvm['DMR Network']['Password'] = '"'.$_POST['bmHSSecurity_YSF'].'"';
		}
	    } else { 
		unset($configModem['BrandMeister']['Password']);
	    }
	
	    if (empty($_POST['tgifHSSecurity']) != TRUE ) {
		$configModem['TGIF']['Password'] = '"'.$_POST['tgifHSSecurity'].'"';
		if ($dmrMasterHostArr[0] != '127.0.0.1') {
		    $configmmdvm['DMR Network']['Password'] = '"'.$_POST['tgifHSSecurity'].'"';
		}
	    } else {
		unset ($configModem['TGIF']['Password']);
	    }

	    // DMR Gateway
	    if ($dmrMasterHostArr[0] == '127.0.0.1' && $dmrMasterHostArr[2] == '62031') {
		unset ($configmmdvm['DMR Network']['Options']);
		unset($configmmdvm['DMR Network']['Type']);
		$configmmdvm['DMR Network']['Local'] = "62032";
		$configmmdvm['DMR Network']['LocalPort'] = "62032";
		$configmmdvm['DMR Network']['Type'] = "Gateway";
		unset ($configysf2dmr['DMR Network']['Options']);
		$configysf2dmr['DMR Network']['Local'] = "62032";
		if (isset($configdmr2ysf['DMR Network']['LocalAddress'])) {
		    $configdmr2ysf['DMR Network']['LocalAddress'] = "127.0.0.1";
		}
		if (isset($configdmr2nxdn['DMR Network']['LocalAddress'])) {
		     $configdmr2nxdn['DMR Network']['LocalAddress'] = "127.0.0.1";
		}
	    }
	    else {
		if (!isset($configmmdvm['DMR Network']['Type'])) {
		    $configmmdvm['DMR Network']['Type'] = "Gateway";
		}
	    }

	    // DMR2YSF
	    if ($dmrMasterHostArr[0] == '127.0.0.2' && $dmrMasterHostArr[2] == '62033') {
		unset ($configmmdvm['DMR Network']['Options']);
		$configmmdvm['DMR Network']['Local'] = "62034";
		$configmmdvm['DMR Network']['LocalPort'] = "62034";
		if (isset($configdmr2ysf['DMR Network']['LocalAddress'])) {
		     $configdmr2ysf['DMR Network']['LocalAddress'] = "127.0.0.2";
		}
	    }

	    // DMR2NXDN
	    if ($dmrMasterHostArr[0] == '127.0.0.3' && $dmrMasterHostArr[2] == '62035') {
		unset ($configmmdvm['DMR Network']['Options']);
		$configmmdvm['DMR Network']['Local'] = "62036";
		$configmmdvm['DMR Network']['LocalPort'] = "62036";
		if (isset($configdmr2nxdn['DMR Network']['LocalAddress'])) {
		    $configdmr2nxdn['DMR Network']['LocalAddress'] = "127.0.0.3";
		}
	    }

	    // Set the DMR+ / HBLink / FreeDMR Options= line
	    if ((substr($dmrMasterHostArr[3], 0, 4) == "DMR+") || (substr($dmrMasterHostArr[3], 0, 3) == "HB_") || (substr($dmrMasterHostArr[3], 0, 3) == "FD_") || (substr($dmrMasterHostArr[3], 0, 8) == "FreeDMR_")) {
		unset ($configmmdvm['DMR Network']['Local']);
		unset ($configmmdvm['DMR Network']['LocalPort']);
		unset ($configysf2dmr['DMR Network']['Local']);
		if (empty($_POST['dmrNetworkOptions']) != TRUE ) {
		    $dmrOptionsLineStripped = str_replace('"', "", $_POST['dmrNetworkOptions']);
		    $configmmdvm['DMR Network']['Options'] = '"'.$dmrOptionsLineStripped.'"';
		    $configdmrgateway['DMR Network 2']['Options'] = '"'.$dmrOptionsLineStripped.'"';
		}
		else {
		    unset ($configmmdvm['DMR Network']['Options']);
		    unset ($configdmrgateway['DMR Network 2']['Options']);
		    unset ($configysf2dmr['DMR Network']['Options']);
		}
	    }

	}

        // Set the SystemX (FreeSTAR) Options= line
        if ((substr($dmrMasterHostArr[3], 0, 8) == "SystemX_")) {
            unset ($configmmdvm['DMR Network']['Local']);
            unset ($configmmdvm['DMR Network']['LocalPort']);
            unset ($configysf2dmr['DMR Network']['Local']);
            if (empty($_POST['dmrNetworkOptions5']) != TRUE ) {
                $dmrOptionsLineStripped5 = str_replace('"', "", $_POST['dmrNetworkOptions5']);
                $configmmdvm['DMR Network']['Options'] = '"'.$dmrOptionsLineStripped5.'"';
                $configdmrgateway['DMR Network 5']['Options'] = '"'.$dmrOptionsLineStripped5.'"';
            }
            else {
                unset ($configmmdvm['DMR Network']['Options']);
                unset ($configdmrgateway['DMR Network 5']['Options']);
                unset ($configysf2dmr['DMR Network']['Options']);
            }
        }

	if (empty($_POST['dmrMasterHost']) == TRUE ) {
	    unset ($configmmdvm['DMR Network']['Options']);
	    unset ($configdmrgateway['DMR Network 2']['Options']);
	    unset ($configdmrgateway['DMR Network 5']['Options']);
	}
	if (empty($_POST['dmrMasterHost1']) != TRUE ) {
	    $dmrMasterHostArr1 = explode(',', escapeshellcmd($_POST['dmrMasterHost1']));
	    $configdmrgateway['DMR Network 1']['Address'] = $dmrMasterHostArr1[0];
	    $configdmrgateway['DMR Network 1']['Password'] = '"'.$dmrMasterHostArr1[1].'"';
	    if (empty($_POST['bmHSSecurity']) != TRUE ) {
		$configdmrgateway['DMR Network 1']['Password'] = '"'.$_POST['bmHSSecurity'].'"';
	    }
	    $configdmrgateway['DMR Network 1']['Port'] = $dmrMasterHostArr1[2];
	    $configdmrgateway['DMR Network 1']['Name'] = $dmrMasterHostArr1[3];
	}
	if (empty($_POST['dmrMasterHost2']) != TRUE ) {
	    $dmrMasterHostArr2 = explode(',', escapeshellcmd($_POST['dmrMasterHost2']));
	    $configdmrgateway['DMR Network 2']['Address'] = $dmrMasterHostArr2[0];
	    $configdmrgateway['DMR Network 2']['Password'] = '"'.$dmrMasterHostArr2[1].'"';
	    $configdmrgateway['DMR Network 2']['Port'] = $dmrMasterHostArr2[2];
	    $configdmrgateway['DMR Network 2']['Name'] = $dmrMasterHostArr2[3];
            $configdmrgateway['DMR Network 2']['TGRewrite0'] = "2,9,2,9,1";
            $configdmrgateway['DMR Network 2']['PCRewrite0'] = "2,94000,2,4000,1001";
            $configdmrgateway['DMR Network 2']['TypeRewrite1'] = "1,9990,1,9990";
            $configdmrgateway['DMR Network 2']['TypeRewrite2'] = "2,9990,2,9990";
            $configdmrgateway['DMR Network 2']['PassAllPC1'] = "1";
            $configdmrgateway['DMR Network 2']['PassAllTG1'] = "1";
            $configdmrgateway['DMR Network 2']['PassAllPC2'] = "2";
            $configdmrgateway['DMR Network 2']['PassAllTG2'] = "2";
            $configdmrgateway['DMR Network 2']['SrcRewrite1'] = "1,4000,1,9,1";
	    if (empty($_POST['dmrNetworkOptions']) != TRUE ) {
		$dmrOptionsLineStripped = str_replace('"', "", $_POST['dmrNetworkOptions']);
		unset ($configmmdvm['DMR Network']['Options']);
		$configdmrgateway['DMR Network 2']['Options'] = '"'.$dmrOptionsLineStripped.'"';
	    }
	    else {
		unset ($configdmrgateway['DMR Network 2']['Options']);
	    }
	}
	if (empty($_POST['dmrMasterHost5']) != TRUE ) {
	    $dmrMasterHostArr5 = explode(',', escapeshellcmd($_POST['dmrMasterHost5']));
	    $configdmrgateway['DMR Network 5']['Address'] = $dmrMasterHostArr5[0];
	    $configdmrgateway['DMR Network 5']['Password'] = '"'.$dmrMasterHostArr5[1].'"';
	    $configdmrgateway['DMR Network 5']['Port'] = $dmrMasterHostArr5[2];
	    $configdmrgateway['DMR Network 5']['Name'] = $dmrMasterHostArr5[3];
            $configdmrgateway['DMR Network 5']['TGRewrite0'] = "2,4,2,9,1";
            $configdmrgateway['DMR Network 5']['PCRewrite0'] = "2,44000,2,4000,1001";
            $configdmrgateway['DMR Network 5']['PCRewrite1'] = "1,4009990,1,9990,1";
            $configdmrgateway['DMR Network 5']['PCRewrite2'] = "2,4009990,2,9990,1";
            $configdmrgateway['DMR Network 5']['PCRewrite3'] = "1,4000001,1,1,999999";
            $configdmrgateway['DMR Network 5']['PCRewrite4'] = "2,4000001,2,1,999999";
            $configdmrgateway['DMR Network 5']['TypeRewrite1'] = "1,4009990,1,9990";
            $configdmrgateway['DMR Network 5']['TypeRewrite2'] = "2,4009990,2,9990";
            $configdmrgateway['DMR Network 5']['TGRewrite1'] = "1,4000001,1,1,999999";
            $configdmrgateway['DMR Network 5']['TGRewrite2'] = "2,4000001,2,1,999999";
            $configdmrgateway['DMR Network 5']['SrcRewrite1'] = "1,9990,1,4009990,1";
            $configdmrgateway['DMR Network 5']['SrcRewrite2'] = "2,9990,2,4009990,1";
            $configdmrgateway['DMR Network 5']['SrcRewrite3'] = "1,1,1,4000001,999999";
            $configdmrgateway['DMR Network 5']['SrcRewrite4'] = "2,1,2,4000001,999999";
	    if (empty($_POST['dmrNetworkOptions5']) != TRUE ) {
		$dmrOptionsLineStripped5 = str_replace('"', "", $_POST['dmrNetworkOptions5']);
		unset ($configmmdvm['DMR Network']['Options']);
		$configdmrgateway['DMR Network 5']['Options'] = '"'.$dmrOptionsLineStripped5.'"';
	    }
	    else {
		unset ($configdmrgateway['DMR Network 5']['Options']);
	    }
	}

	if (empty($_POST['dmrMasterHost3']) != TRUE ) {
	    $dmrMasterHostArr3 = explode(',', escapeshellcmd($_POST['dmrMasterHost3']));
	    $configdmrgateway['XLX Network 1']['Address'] = $dmrMasterHostArr3[0];
	    $configdmrgateway['XLX Network 1']['Password'] = '"'.$dmrMasterHostArr3[1].'"';
	    $configdmrgateway['XLX Network 1']['Port'] = $dmrMasterHostArr3[2];
	    $configdmrgateway['XLX Network 1']['Name'] = $dmrMasterHostArr3[3];
	    $configdmrgateway['XLX Network']['Startup'] = substr($dmrMasterHostArr3[3], 4);
	}

	// XLX StartUp TG
	if (empty($_POST['dmrMasterHost3Startup']) != TRUE ) {
	    $dmrMasterHost3Startup = escapeshellcmd($_POST['dmrMasterHost3Startup']);
	    if ($dmrMasterHost3Startup != "None") {
		$configdmrgateway['XLX Network 1']['Startup'] = $dmrMasterHost3Startup;
	    }
	    else {
		unset($configdmrgateway['XLX Network 1']['Startup']);
	    }
	}

	// XLX Module Override
	if (empty($_POST['dmrMasterHost3StartupModule']) != TRUE ) {
	    $dmrMasterHost3StartupModule = escapeshellcmd($_POST['dmrMasterHost3StartupModule']);
	    if ($dmrMasterHost3StartupModule == "Default") {
		unset($configdmrgateway['XLX Network']['Module']);
	    } else {
		$configdmrgateway['XLX Network']['Module'] = $dmrMasterHost3StartupModule;
	    }
	}

	// Set XLX Network TimeSlot for duplex modems
	if (empty($_POST['xlxTimeSlot']) != TRUE ) {
	  if (escapeshellcmd($_POST['xlxTimeSlot']) == '1' ) { $configdmrgateway['XLX Network']['Slot'] = "1"; }
	  if (escapeshellcmd($_POST['xlxTimeSlot']) == '2' ) { $configdmrgateway['XLX Network']['Slot'] = "2"; }
	}

	unset($configmmdvm['DMR Network']['JitterEnabled']);

	// Set Talker Alias Option
	if (empty($_POST['dmrEmbeddedLCOnly']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrEmbeddedLCOnly']) == 'ON' ) { $configmmdvm['DMR']['EmbeddedLCOnly'] = "1"; }
	  if (escapeshellcmd($_POST['dmrEmbeddedLCOnly']) == 'OFF' ) { $configmmdvm['DMR']['EmbeddedLCOnly'] = "0"; }
	}

	// Set Dump TA Data Option for GPS support
	if (empty($_POST['dmrDumpTAData']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrDumpTAData']) == 'ON' ) { $configmmdvm['DMR']['DumpTAData'] = "1"; }
	  if (escapeshellcmd($_POST['dmrDumpTAData']) == 'OFF' ) { $configmmdvm['DMR']['DumpTAData'] = "0"; }
	}

	// Set DMR Beacon option
	if (empty($_POST['DMRBeaconEnable']) != TRUE ) {
	  if (escapeshellcmd($_POST['DMRBeaconEnable']) == 'ON' ) { $configmmdvm['DMR']['Beacons'] = "1"; }
	  if (escapeshellcmd($_POST['DMRBeaconEnable']) == 'OFF' ) { $configmmdvm['DMR']['Beacons'] = "0"; }
	}

	// Set the XLX DMRGateway Master On or Off
	if (empty($_POST['dmrGatewayXlxEn']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrGatewayXlxEn']) == 'ON' ) { $configdmrgateway['XLX Network 1']['Enabled'] = "1"; $configdmrgateway['XLX Network']['Enabled'] = "1"; }
	  if (escapeshellcmd($_POST['dmrGatewayXlxEn']) == 'OFF' ) { $configdmrgateway['XLX Network 1']['Enabled'] = "0"; $configdmrgateway['XLX Network']['Enabled'] = "0"; }
	}

	// Set the DMRGateway Network 2 (DMR+/FreeDMR/HBLink) On or Off
	if (empty($_POST['dmrGatewayNet2En']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrGatewayNet2En']) == 'ON' ) { $configdmrgateway['DMR Network 2']['Enabled'] = "1"; }
	  if (escapeshellcmd($_POST['dmrGatewayNet2En']) == 'OFF' ) { $configdmrgateway['DMR Network 2']['Enabled'] = "0"; }
	}

	// Set the DMRGateway Network 4 (TGIF) On or Off
	if (empty($_POST['dmrGatewayNet4En']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrGatewayNet4En']) == 'ON' ) { $configdmrgateway['DMR Network 4']['Enabled'] = "1"; }
	  if (escapeshellcmd($_POST['dmrGatewayNet4En']) == 'OFF' ) { $configdmrgateway['DMR Network 4']['Enabled'] = "0"; }
	}

	// Set the DMRGateway Network 5 (SystemX) On or Off
	if (empty($_POST['dmrGatewayNet5En']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrGatewayNet5En']) == 'ON' ) { $configdmrgateway['DMR Network 5']['Enabled'] = "1"; }
	  if (escapeshellcmd($_POST['dmrGatewayNet5En']) == 'OFF' ) { $configdmrgateway['DMR Network 5']['Enabled'] = "0"; }
	}

	// Set the DMRGateway Network 1 (BM) On or Off
	if (empty($_POST['dmrGatewayNet1En']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrGatewayNet1En']) == 'ON' ) { $configdmrgateway['DMR Network 1']['Enabled'] = "1"; }
	  if (escapeshellcmd($_POST['dmrGatewayNet1En']) == 'OFF' ) { $configdmrgateway['DMR Network 1']['Enabled'] = "0"; }
	}

	// Remove old settings
	if (isset($configmmdvm['General']['ModeHang'])) { unset($configmmdvm['General']['ModeHang']); }
	if (isset($configdmrgateway['General']['Timeout'])) { unset($configdmrgateway['General']['Timeout']); }
	if (isset($configmmdvm['General']['RFModeHang'])) { $configmmdvm['General']['RFModeHang'] = 300; }
	if (isset($configmmdvm['General']['NetModeHang'])) { $configmmdvm['General']['NetModeHang'] = 300; }

	// Set DMR Hang Timers
	if (empty($_POST['dmrRfHangTime']) != TRUE ) {
	  $configmmdvm['DMR']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dmrRfHangTime']);
	  $configdmrgateway['General']['RFTimeout'] = preg_replace('/[^0-9]/', '', $_POST['dmrRfHangTime']);
	}
	if (empty($_POST['dmrNetHangTime']) != TRUE ) {
	  $configmmdvm['DMR Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dmrNetHangTime']);
	  $configdmrgateway['General']['NetTimeout'] = preg_replace('/[^0-9]/', '', $_POST['dmrNetHangTime']);
	}
	// Set D-Star Hang Timers
	if (empty($_POST['dstarRfHangTime']) != TRUE ) {
	  $configmmdvm['D-Star']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dstarRfHangTime']);
	}
	if (empty($_POST['dstarNetHangTime']) != TRUE ) {
	  $configmmdvm['D-Star Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dstarNetHangTime']);
	}
	// Set YSF Hang Timers
	if (empty($_POST['ysfRfHangTime']) != TRUE ) {
	  $configmmdvm['System Fusion']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['ysfRfHangTime']);
	  $configdgidgateway['General']['RFHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfRfHangTime']);
	  $configdgidgateway['YSF Network']['RFHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfRfHangTime']);
	  $configdgidgateway['FCS Network']['RFHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfRfHangTime']);
	  $configdgidgateway['IMRS Network']['RFHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfRfHangTime']);
	}
	if (empty($_POST['ysfNetHangTime']) != TRUE ) {
	  $configmmdvm['System Fusion Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['ysfNetHangTime']);
	  $configdgidgateway['General']['NetHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfNetHangTime']);
	  $configdgidgateway['YSF Network']['NetHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfNetHangTime']);
	  $configdgidgateway['FCS Network']['NetHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfNetHangTime']);
	  $configdgidgateway['IMRS Network']['NetHangTime'] = preg_replace('/[^0-9]/', '', $_POST['ysfNetHangTime']);
	}
	// Set P25 Hang Timers
	if (empty($_POST['p25RfHangTime']) != TRUE ) {
	  $configmmdvm['P25']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['p25RfHangTime']);
	  $configp25gateway['Network']['RFHangTime'] = "0";
	}
	if (empty($_POST['p25NetHangTime']) != TRUE ) {
	  $configmmdvm['P25 Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['p25NetHangTime']);
	  $configp25gateway['Network']['NetHangTime'] = "0";
	}
	// Set NXDN Hang Timers
	if (empty($_POST['nxdnRfHangTime']) != TRUE ) {
	  $configmmdvm['NXDN']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['nxdnRfHangTime']);
	  $confignxdngateway['Network']['RFHangTime'] = "0";
	}
	if (empty($_POST['nxdnNetHangTime']) != TRUE ) {
	  $configmmdvm['NXDN Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['nxdnNetHangTime']);
	  $confignxdngateway['Network']['NetHangTime'] = "0";
	}
	// Set M17 Hang Timers
	if (empty($_POST['m17RfHangTime']) != TRUE ) {
	  $configmmdvm['M17']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['m17RfHangTime']);
	  $configm17gateway['Network']['RFHangTime'] = "0";
	}
	if (empty($_POST['m17NetHangTime']) != TRUE ) {
	  $configmmdvm['M17 Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['m17NetHangTime']);
	  $configm17gateway['Network']['NetHangTime'] = "0";
	}
	// Set POCSAG Hang Timer
	if (empty($_POST['POCSAGHangTime']) != TRUE ) {
	  $configmmdvm['POCSAG Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['POCSAGHangTime']);
	}

	// Set the hardware type
	if (empty($_POST['confHardware']) != TRUE ) {
	$confHardware = escapeshellcmd($_POST['confHardware']);
	$configModem['Modem']['Hardware'] = $confHardware;
	$confPort = escapeshellcmd($_POST['confPort']);
	$configmmdvm['Modem']['UARTPort'] = $confPort;
	$configmmdvm['Modem']['Protocol'] = "uart";
	$confHardwareSpeed = escapeshellcmd($_POST['confHardwareSpeed']);
	// Set the Start delay
	$rollMMDVMHostStartDelay = 'sudo sed -i "/OnStartupSec=/c\\OnStartupSec=30" /lib/systemd/system/mmdvmhost.timer';
	// Set Standard IP/Port for /MMDVMHost
	$rollRepeaterAddress1 = 'sudo sed -i "/repeaterAddress1=/c\\repeaterAddress1=127.0.0.1" /etc/ircddbgateway';
	$rollRepeaterPort1 = 'sudo sed -i "/repeaterPort1=/c\\repeaterPort1=20011" /etc/ircddbgateway';
	$configmmdvm['Modem']['UARTSpeed'] = $confHardwareSpeed;

	  if ( $confHardware == 'idrp2c' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=1" /etc/ircddbgateway';
	    $rollRepeaterAddress1 = 'sudo sed -i "/repeaterAddress1=/c\\repeaterAddress1=172.16.0.1" /etc/ircddbgateway';
	    $rollRepeaterPort1 = 'sudo sed -i "/repeaterPort1=/c\\repeaterPort1=20000" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $testNetworkConfig = exec('grep "eth0:1" /etc/network/interfaces | wc -l');
	    if (substr($testNetworkConfig, 0, 1) === '0') {
	      system('sudo sed -i "$ a\ \\nauto eth0:1\\nallow-hotplug eth0:1\\niface eth0:1 inet static\\n    address 172.16.0.20\\n    netmask 255.255.255.0" /etc/network/interfaces');
	    }
	  }

	  if ( $confHardware == 'dvmpis' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'dvmpid' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'dvmuadu' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'dvmuada' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'dvmbss' ) {
	    $rollMMDVMHostStartDelay = 'sudo sed -i "/OnStartupSec=/c\\OnStartupSec=60" /lib/systemd/system/mmdvmhost.timer';
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'dvmbsd' ) {
	    $rollMMDVMHostStartDelay = 'sudo sed -i "/OnStartupSec=/c\\OnStartupSec=60" /lib/systemd/system/mmdvmhost.timer';
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'dvmuagmsku' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'dvmuagmska' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'dvrptr1' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'dvrptr2' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'dvrptr3' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'gmsk_modem' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'zumspotlibre' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'zumspotusb' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'lsusb' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'zumspotgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'zumspotdualgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'zumspotduplexgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 1;
	  }

          if ( $confHardware == 'zumradiopiusb' ) {
            $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
            system($rollRepeaterType1);
            $configmmdvm['General']['Duplex'] = 0;
            $configmmdvm['DMR Network']['Slot1'] = 0;
          }

	  if ( $confHardware == 'zumradiopigpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['Modem']['UARTSpeed'] = "460800";
	  }

	  if ( $confHardware == 'zum' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'stm32dvm' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'stm32dvmv3+' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['Modem']['UARTSpeed'] = "460800";
	  }

	  if ( $confHardware == 'stm32usb' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'stm32usbv3+' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['Modem']['UARTSpeed'] = "460800";
	  }

	  if ( $confHardware == 'stm32dvmmtr2kopi' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['Modem']['UARTSpeed'] = "500000";
	  }

	  if ( $confHardware == 'f4mgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	  }

	  if ( $confHardware == 'f4mf7m' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 1;
	  }

	  if ( $confHardware == 'mmdvmhshat' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'lshshatgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'mmdvmhshatambe' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'mmdvmhsdualbandgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'sbhsdualbandgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'mmdvmhsdualhatgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 1;
	  }

	  if ( $confHardware == 'lshsdualhatgpio' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 1;
	  }

	  if ( $confHardware == 'mmdvmhsdualhatusb' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 1;
	  }

	  if ( $confHardware == 'mmdvmrpthat' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 1;
	  }

	  if ( $confHardware == 'mmdvmmdohat' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'mmdvmvyehat' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'mmdvmvyehatdual' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 1;
	  }

	  if ( $confHardware == 'nanodv' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'nanodvusb' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  if ( $confHardware == 'dvmpicast' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	    if (isDVmegaCast() == 1) { // If a CAST, call the Base Station mode script
		$rollCastMode = 'sudo /usr/local/cast/sbin/RMBS.sh conf_page';
	    }
	  }

	  if ( $confHardware == 'dvmpicasths' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	    if (isDVmegaCast() == 1) { // If a CAST, call the HotSpot mode script
		$rollCastMode = 'sudo /usr/local/cast/sbin/RMHS.sh conf_page';
	    }
	  }

	  if ( $confHardware == 'dvmpicasthd' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	    if (isDVmegaCast() == 1) { // If a CAST, call the HotSpot mode script
		$rollCastMode = 'sudo /usr/local/cast/sbin/RMHS.sh conf_page dual';
	    }
	  }
	  
	  if ( $confHardware == 'opengd77' ) {
	    $rollRepeaterType1 = 'sudo sed -i "/repeaterType1=/c\\repeaterType1=0" /etc/ircddbgateway';
	    system($rollRepeaterType1);
	    $configmmdvm['General']['Duplex'] = 0;
	    $configmmdvm['DMR Network']['Slot1'] = 0;
	  }

	  // Set the Service start delay
	  system($rollMMDVMHostStartDelay);
	  // Set Standard IP/Port for ircDDBGateway
	  system($rollRepeaterAddress1);
	  system($rollRepeaterPort1);
	}

	// Set the Dashboard Public
	if (!empty($_POST['dashAccess'])) {
	    $publicDashboard = 'sudo sed -i \'/$ipVar 80 80/c\\    "$DAEMON -u ${igdURL} -e ${hostVar}_Dashboard -a $ipVar 80 80 TCP > /dev/null 2>&1"\' /etc/wpsd-upnp-rules';
	    $privateDashboard = 'sudo sed -i "/$ipVar 80 80/ s/^#*/    #/" /etc/wpsd-upnp-rules';

	    if (escapeshellcmd($_POST['dashAccess']) == 'PUB') {
	        system($publicDashboard);
	    }
	    if (escapeshellcmd($_POST['dashAccess']) == 'PRV') {
	        system($privateDashboard);
	    }
	}

	// Set the ircDDBGateway Remote Public
	if (!empty($_POST['ircRCAccess'])) {
	    $publicRCirc = 'sudo sed -i \'/$ipVar 10022 10022/c\\    "$DAEMON -u ${igdURL} -e ${hostVar}_ircDDBgwRemote -a $ipVar 10022 10022 UDP > /dev/null 2>&1"\' /etc/wpsd-upnp-rules';
	    $privateRCirc = 'sudo sed -i "/$ipVar 10022 10022/ s/^#*/    #/" /etc/wpsd-upnp-rules';

	    if (escapeshellcmd($_POST['ircRCAccess']) == 'PUB') {
	        system($publicRCirc);
	    }
	    if (escapeshellcmd($_POST['ircRCAccess']) == 'PRV') {
	        system($privateRCirc);
	    }
	}

	// Set SSH Access Public
	if (!empty($_POST['sshAccess'])) {
	    $publicSSH = 'sudo sed -i \'/$ipVar 22 22/c\\    "$DAEMON -u ${igdURL} -e ${hostVar}_SSH -a $ipVar 22 22 TCP > /dev/null 2>&1"\' /etc/wpsd-upnp-rules';
	    $privateSSH = 'sudo sed -i "/$ipVar 22 22/ s/^#*/    #/" /etc/wpsd-upnp-rules';

	    if (escapeshellcmd($_POST['sshAccess']) == 'PUB') {
	        system($publicSSH);
	    }
	    if (escapeshellcmd($_POST['sshAccess']) == 'PRV') {
	        system($privateSSH);
	    }
	}

	// Set uPNP On or Off
	if (empty($_POST['uPNP']) != TRUE ) {
	  $uPNPon = 'sudo sed -i \'/pistar-upnp.service/c\\*/5 *\t* * *\troot\t/usr/local/sbin/pistar-upnp.service start > /dev/null 2>&1 &\' /etc/crontab';
	  $uPNPoff = 'sudo sed -i \'/pistar-upnp.service/ s/^#*/#/\' /etc/crontab';
	  $uPNPsvcOn = 'sudo systemctl enable pistar-upnp.timer';
	  $uPNPsvcOff = 'sudo systemctl disable pistar-upnp.timer';
	  $uPNPsvcStart = '(sudo systemctl stop pistar-upnp.service && sudo systemctl start pistar-upnp.service) > /dev/null 2>&1 &';
	  $uPNPsvcStop = '(sudo systemctl stop pistar-upnp.service) > /dev/null 2>&1 &';

	  if (escapeshellcmd($_POST['uPNP']) == 'ON' )  { system($uPNPon); system($uPNPsvcOn); system($uPNPsvcStart); }
	  if (escapeshellcmd($_POST['uPNP']) == 'OFF' ) { system($uPNPoff); system($uPNPsvcStop); system($uPNPsvcOff); }
	}

	// D-Star Time Announce
	if (empty($_POST['confTimeAnnounce']) != TRUE ) {
	  if (escapeshellcmd($_POST['confTimeAnnounce']) == 'ON' )  { system('sudo rm -rf /etc/timeserver.disable'); }
	  if (escapeshellcmd($_POST['confTimeAnnounce']) == 'OFF' )  { system('sudo touch /etc/timeserver.disable'); }
	}

	// Set MMDVMHost DMR Mode
	if (empty($_POST['MMDVMModeDMR']) != TRUE ) {
	  if (escapeshellcmd($_POST['MMDVMModeDMR']) == 'ON' )  { $configmmdvm['DMR']['Enable'] = "1"; $configmmdvm['DMR Network']['Enable'] = "1"; $configysf2dmr['Enabled']['Enabled'] = "0";}
	  if (escapeshellcmd($_POST['MMDVMModeDMR']) == 'OFF' ) { $configmmdvm['DMR']['Enable'] = "0"; $configmmdvm['DMR Network']['Enable'] = "0"; }
	}

	// Set MMDVMHost D-Star Mode
	if (empty($_POST['MMDVMModeDSTAR']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeDSTAR']) == 'ON' )  { $configmmdvm['D-Star']['Enable'] = "1"; $configmmdvm['D-Star Network']['Enable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModeDSTAR']) == 'OFF' ) { $configmmdvm['D-Star']['Enable'] = "0"; $configmmdvm['D-Star Network']['Enable'] = "0"; }
	}

	// Set MMDVMHost Fusion Mode
	if (empty($_POST['MMDVMModeFUSION']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeFUSION']) == 'ON' )  { $configmmdvm['System Fusion']['Enable'] = "1"; $configmmdvm['System Fusion Network']['Enable'] = "1"; $configdmr2ysf['Enabled']['Enabled'] = "0"; }
          if (escapeshellcmd($_POST['MMDVMModeFUSION']) == 'OFF' ) { $configmmdvm['System Fusion']['Enable'] = "0"; $configmmdvm['System Fusion Network']['Enable'] = "0"; $configdgidgateway['Enabled']['Enabled'] = "0"; }
	}

	// Set MMDVMHost P25 Mode
	if (empty($_POST['MMDVMModeP25']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeP25']) == 'ON' )  { $configmmdvm['P25']['Enable'] = "1"; $configmmdvm['P25 Network']['Enable'] = "1"; $configysf2p25['Enabled']['Enabled'] = "0"; }
          if (escapeshellcmd($_POST['MMDVMModeP25']) == 'OFF' ) { $configmmdvm['P25']['Enable'] = "0"; $configmmdvm['P25 Network']['Enable'] = "0"; }
	}

	// Set MMDVMHost NXDN Mode
	if (empty($_POST['MMDVMModeNXDN']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeNXDN']) == 'ON' )  { $configmmdvm['NXDN']['Enable'] = "1"; $configmmdvm['NXDN Network']['Enable'] = "1"; $configysf2nxdn['Enabled']['Enabled'] = "0"; }
          if (escapeshellcmd($_POST['MMDVMModeNXDN']) == 'OFF' ) { $configmmdvm['NXDN']['Enable'] = "0"; $configmmdvm['NXDN Network']['Enable'] = "0"; }
	}

	// Set MMDVMHost M17 Mode
	if (empty($_POST['MMDVMModeM17']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeM17']) == 'ON' )  { $configmmdvm['M17']['Enable'] = "1"; $configmmdvm['M17 Network']['Enable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModeM17']) == 'OFF' ) { $configmmdvm['M17']['Enable'] = "0"; $configmmdvm['M17 Network']['Enable'] = "0"; }
	}

	// Set YSF2DMR Mode
	if (empty($_POST['MMDVMModeYSF2DMR']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeYSF2DMR']) == 'ON' )  {
	      $configysf2dmr['Enabled']['Enabled'] = "1";
	      $configdgidgateway['Enabled']['Enabled'] = "0"; // dgidgateway causes port/comm conflicts with YSF2***
	  }
          if (escapeshellcmd($_POST['MMDVMModeYSF2DMR']) == 'OFF' ) { $configysf2dmr['Enabled']['Enabled'] = "0"; }
	}

	// Set YSF2NXDN Mode
	if (empty($_POST['MMDVMModeYSF2NXDN']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeYSF2NXDN']) == 'ON' )  {
	      $configysf2nxdn['Enabled']['Enabled'] = "1";
	      $configmmdvm['NXDN']['Enable'] = "0";
	      $configmmdvm['NXDN Network']['Enable'] = "0";
	      $configdgidgateway['Enabled']['Enabled'] = "0"; // dgidgateway causes port/comm conflicts with YSF2***
	  }
          if (escapeshellcmd($_POST['MMDVMModeYSF2NXDN']) == 'OFF' ) { $configysf2nxdn['Enabled']['Enabled'] = "0"; }
	}

	// Set YSF2P25 Mode
	if (empty($_POST['MMDVMModeYSF2P25']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeYSF2P25']) == 'ON' )  {
	      $configysf2p25['Enabled']['Enabled'] = "1";
	      $configmmdvm['P25']['Enable'] = "0";
	      $configmmdvm['P25 Network']['Enable'] = "0";
	      $configdgidgateway['Enabled']['Enabled'] = "0"; // dgidgateway causes port/comm conflicts with YSF2***
	  }
          if (escapeshellcmd($_POST['MMDVMModeYSF2P25']) == 'OFF' ) { $configysf2p25['Enabled']['Enabled'] = "0"; }
	  if (escapeshellcmd($_POST['MMDVMModeFUSION']) == 'OFF' ) { $configysf2p25['Enabled']['Enabled'] = "0"; }
	}

	// Set DMR2YSF Mode
	if (empty($_POST['MMDVMModeDMR2YSF']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeDMR2YSF']) == 'ON' )  {
		  $configdmr2ysf['Enabled']['Enabled'] = "1";
		  unset($configdmrgateway['DMR Network 3']);
		  $configdmrgateway['DMR Network 3']['Enabled'] = "0";
		  $configdmrgateway['DMR Network 3']['Name'] = "DMR2YSF_Cross-Mode";
		  $configdmrgateway['DMR Network 3']['Id'] = $configdmrgateway['DMR Network 2']['Id'];
		  $configdmrgateway['DMR Network 3']['Address'] = "127.0.0.1";
		  $configdmrgateway['DMR Network 3']['Port'] = "62033";
		  $configdmrgateway['DMR Network 3']['Local'] = "62034";
		  $configdmrgateway['DMR Network 3']['TGRewrite0'] = "2,7000001,2,1,999998";
		  $configdmrgateway['DMR Network 3']['SrcRewrite0'] = "2,1,2,7000001,999998";
		  $configdmrgateway['DMR Network 3']['PCRewrite0'] = "2,7000001,2,1,999998";
		  $configdmrgateway['DMR Network 3']['Password'] = '"'."PASSWORD".'"';
		  $configdmrgateway['DMR Network 3']['Location'] = "0";
		  $configdmrgateway['DMR Network 3']['Debug'] = "0";
		  $configmmdvm['System Fusion']['Enable'] = "0";
		  $configmmdvm['System Fusion Network']['Enable'] = "0";
	  }
          if (escapeshellcmd($_POST['MMDVMModeDMR2YSF']) == 'OFF' ) {
		  $configdmr2ysf['Enabled']['Enabled'] = "0";
		  $configdmrgateway['DMR Network 3']['Enabled'] = "0";
	  }
	}

	// Set DMR2NXDN Mode
	if (empty($_POST['MMDVMModeDMR2NXDN']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeDMR2NXDN']) == 'ON' )  {
		  if (empty($_POST['MMDVMModeDMR2YSF']) != TRUE ) {
		  	if (escapeshellcmd($_POST['MMDVMModeDMR2YSF']) == 'ON' )  {
				$configdmr2ysf['Enabled']['Enabled'] = "0";
		  	}
	  	  }
		  if (empty($_POST['MMDVMModeYSF2NXDN']) != TRUE ) {
		  	if (escapeshellcmd($_POST['MMDVMModeYSF2NXDN']) == 'ON' )  {
				$configysf2nxdn['Enabled']['Enabled'] = "0";
		  	}
	  	  }
		  if (empty($_POST['MMDVMModeDMR2M17']) != TRUE ) {
		  	if (escapeshellcmd($_POST['MMDVMModeYSFM17']) == 'ON' )  {
				$configysf2m17['Enabled']['Enabled'] = "0";
		  	}
	  	  }
		  $configdmr2nxdn['Enabled']['Enabled'] = "1";
		  unset($configdmrgateway['DMR Network 3']);
		  $configdmrgateway['DMR Network 3']['Enabled'] = "0";
		  $configdmrgateway['DMR Network 3']['Name'] = "DMR2NXDN_Cross-Mode";
		  $configdmrgateway['DMR Network 3']['Id'] = $configdmrgateway['DMR Network 2']['Id'];
		  $configdmrgateway['DMR Network 3']['Address'] = "127.0.0.1";
		  $configdmrgateway['DMR Network 3']['Port'] = "62035";
		  $configdmrgateway['DMR Network 3']['Local'] = "62036";
		  $configdmrgateway['DMR Network 3']['TGRewrite0'] = "2,7000001,2,1,999998";
		  $configdmrgateway['DMR Network 3']['SrcRewrite0'] = "2,1,2,7000001,999998";
		  $configdmrgateway['DMR Network 3']['PCRewrite0'] = "2,7000001,2,1,999998";
		  $configdmrgateway['DMR Network 3']['Password'] = '"'."PASSWORD".'"';
		  $configdmrgateway['DMR Network 3']['Location'] = "0";
		  $configdmrgateway['DMR Network 3']['Debug'] = "0";
		  $configmmdvm['NXDN']['Enable'] = "0";
		  $configmmdvm['NXDN Network']['Enable'] = "0";
	  }
          if (escapeshellcmd($_POST['MMDVMModeDMR2NXDN']) == 'OFF' ) {
		  $configdmr2nxdn['Enabled']['Enabled'] = "0";
		  $configdmrgateway['DMR Network 3']['Enabled'] = "0";
	  }
	}

	// TGIF (Net4) for DMRGW
	if (empty($_POST['dmrMasterHost4']) != TRUE ) {
	    if (escapeshellcmd($_POST['dmrGatewayNet4En']) == 'ON' )  {
		unset($configdmrgateway['DMR Network 4']);
		$configdmrgateway['DMR Network 4']['Enabled'] = "1";
		$configdmrgateway['DMR Network 4']['Address'] = "tgif.network";
		$configdmrgateway['DMR Network 4']['Port'] = "62031";
		$configdmrgateway['DMR Network 4']['Name'] = "TGIF_Network";
		$configdmrgateway['DMR Network 4']['Debug'] = "0";
		$configdmrgateway['DMR Network 4']['Location'] = "0";
		// Set TGIF Extended ID
		if (empty($_POST['tgifExtendedId']) != TRUE ) {
		    $newPosttgifExtendedId = preg_replace('/[^0-9]/', '', $_POST['tgifExtendedId']);
		   $configdmrgateway['DMR Network 4']['Id'] = $configmmdvm['General']['Id'].$newPosttgifExtendedId;
		}
		if (empty($_POST['tgifHSSecurity']) != TRUE ) {
		    $configdmrgateway['DMR Network 4']['Password'] = '"'.$_POST['tgifHSSecurity'].'"';
		} else {
		    $configdmrgateway['DMR Network 4']['Password'] = "passw0rd";
		} 
		$configdmrgateway['DMR Network 4']['Id'] = $configdmrgateway['DMR Network 4']['Id'];
		$configdmrgateway['DMR Network 4']['PCRewrite1'] = "1,5009990,1,9990,1";
		$configdmrgateway['DMR Network 4']['PCRewrite2'] = "2,5009990,2,9990,1";
		$configdmrgateway['DMR Network 4']['TypeRewrite1'] = "1,5009990,1,9990";
		$configdmrgateway['DMR Network 4']['TypeRewrite2'] = "2,5009990,2,9990";
		$configdmrgateway['DMR Network 4']['TGRewrite1'] = "1,5000001,1,1,999999";
		$configdmrgateway['DMR Network 4']['TGRewrite2'] = "2,5000001,2,1,999999";
		$configdmrgateway['DMR Network 4']['SrcRewrite1'] = "1,9990,1,5009990,1";
		$configdmrgateway['DMR Network 4']['SrcRewrite2'] = "2,9990,2,5009990,1";
		$configdmrgateway['DMR Network 4']['SrcRewrite3'] = "1,1,1,5000001,999999";
		$configdmrgateway['DMR Network 4']['SrcRewrite4'] = "2,1,2,5000001,999999";
	    }
	    if (escapeshellcmd($_POST['dmrGatewayNet4En']) == 'OFF' )  {
		//unset($configdmrgateway['DMR Network 4']); // not certain why I originally placed this in here. Will disable and hope it doesn't cause issues. ;)
		$configdmrgateway['DMR Network 4']['Enabled'] = "0";
	    }
	}

	// Work out if DMR Network 3 should be ON or not
	if (empty($_POST['MMDVMModeDMR2YSF']) != TRUE || empty($_POST['MMDVMModeDMR2NXDN']) != TRUE) {
		if (escapeshellcmd($_POST['MMDVMModeDMR2YSF']) == 'ON' || escapeshellcmd($_POST['MMDVMModeDMR2NXDN']) == 'ON') {
			$configdmrgateway['DMR Network 3']['Enabled'] = "1";
		} else {
			$configdmrgateway['DMR Network 3']['Enabled'] = "0";
		}
	}

	// Set POCSAG Mode
	if (empty($_POST['MMDVMModePOCSAG']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModePOCSAG']) == 'ON' )  { $configmmdvm['POCSAG']['Enable'] = "1"; $configmmdvm['POCSAG Network']['Enable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModePOCSAG']) == 'OFF' ) { $configmmdvm['POCSAG']['Enable'] = "0"; $configmmdvm['POCSAG Network']['Enable'] = "0"; }
	}

	// Set the MMDVMHost Display Type
	$configmmdvm['NextionDriver']['Enable'] = "0";
	$configmmdvm['NextionDriver']['Port'] = "0";
	$configmmdvm['Transparent Data']['Enable'] = "0";

	if (empty($_POST['mmdvmDisplayType']) != TRUE ) {
	    if (substr($_POST['mmdvmDisplayType'] , 0, 4 ) === "OLED") {
		$configmmdvm['General']['Display'] = "OLED";
		$configmmdvm['OLED']['Type'] = substr($_POST['mmdvmDisplayType'] , 4, 1);
	    	// Function to disable scrolling on type 6 (1.3") OLED ...
		if ($configmmdvm['OLED']['Type'] == "6") {
		    $configmmdvm['OLED']['Scroll'] = "0";
		}
	    }
	    else if (substr($_POST['mmdvmDisplayType'] , 0, 13) === "NextionDriver") {
		$configmmdvm['General']['Display'] = "Nextion";
		$configmmdvm['NextionDriver']['Enable'] = "1";
	    }
	    else {
		$configmmdvm['General']['Display'] = escapeshellcmd($_POST['mmdvmDisplayType']);
	    }
	}

	// handle NONE display post
	if ((empty($_POST['mmdvmDisplayType']) == TRUE) || (escapeshellcmd($_POST['mmdvmDisplayType']) == "None")) {
	    $configmmdvm['General']['Display'] = "";
	    unset($_POST['mmdvmDisplayType']);
	}

	// special handling for DV-Mega CAST's display udp service, which uses Transpaent Data from MMDVMhost...
	if (isDVmegaCast() == 1) {
	    $configmmdvm['General']['Display'] = "CAST";
	    $configmmdvm['Transparent Data']['Enable'] = "1";
	}

	// Set the MMDVMHost Display Port
	if  (empty($_POST['mmdvmDisplayPort']) != TRUE ) {
	    if ($_POST['mmdvmDisplayType'] == "NextionDriverTrans") {
		$configmmdvm['Nextion']['Port'] = "/dev/ttyNextionDriver";
		$configmmdvm['NextionDriver']['Port'] = "modem";
		$configmmdvm['Transparent Data']['SendFrameType'] = "1";
		$configmmdvm['Transparent Data']['Enable'] = "1";
	    }
	    else if ($_POST['mmdvmDisplayType'] == "NextionDriver") {
		$configmmdvm['Nextion']['Port'] = "/dev/ttyNextionDriver";
		$configmmdvm['NextionDriver']['Port'] = $_POST['mmdvmDisplayPort'];
	    }
	    else {
		if (($_POST['mmdvmDisplayPort'] == "None") || ($_POST['mmdvmDisplayPort'] == "modem")) {
		    $configmmdvm['TFT Serial']['Port'] = $_POST['mmdvmDisplayPort'];
		    $configmmdvm['Nextion']['Port'] = $_POST['mmdvmDisplayPort'];
		}
		else {
		    $configmmdvm['TFT Serial']['Port'] = $_POST['mmdvmDisplayPort'];
		    $configmmdvm['Nextion']['Port'] = $_POST['mmdvmDisplayPort'];
		}
	    }
	}

	// Set the Nextion Display Layout
	if (empty($_POST['mmdvmNextionDisplayType']) != TRUE ) {
	    if (escapeshellcmd($_POST['mmdvmNextionDisplayType']) == "G4KLX") { $configmmdvm['Nextion']['ScreenLayout'] = "0"; }
	    if (escapeshellcmd($_POST['mmdvmNextionDisplayType']) == "ON7LDSL2") { $configmmdvm['Nextion']['ScreenLayout'] = "2"; }
	    if (escapeshellcmd($_POST['mmdvmNextionDisplayType']) == "ON7LDSL3") { $configmmdvm['Nextion']['ScreenLayout'] = "3"; }
	    if (escapeshellcmd($_POST['mmdvmNextionDisplayType']) == "ON7LDSL3HS") { $configmmdvm['Nextion']['ScreenLayout'] = "4"; }
	}

	// Set OLED options
        if (empty($_POST['oledScreenSaverEnable']) != TRUE ) {
                if (escapeshellcmd($_POST['oledScreenSaverEnable']) == 'ON' )  { $configmmdvm['OLED']['LogoScreensaver'] = "1"; }
                if (escapeshellcmd($_POST['oledScreenSaverEnable']) == 'OFF' )  { $configmmdvm['OLED']['LogoScreensaver'] = "0"; }
        }
	if (empty($_POST['oledScrollEnable']) != TRUE ) {
                if (escapeshellcmd($_POST['oledScrollEnable']) == 'ON' && escapeshellcmd($_POST['mmdvmDisplayType']) == 'OLED3') { $configmmdvm['OLED']['Scroll'] = "1"; } // no scrolling for type6
                if (escapeshellcmd($_POST['oledScrollEnable']) == 'OFF' )  { $configmmdvm['OLED']['Scroll'] = "0"; }
        }
	if (empty($_POST['oledRotateEnable']) != TRUE ) {
                if (escapeshellcmd($_POST['oledRotateEnable']) == 'ON' )  { $configmmdvm['OLED']['Rotate'] = "1"; }
                if (escapeshellcmd($_POST['oledRotateEnable']) == 'OFF' )  { $configmmdvm['OLED']['Rotate'] = "0"; }
        }
	if (empty($_POST['oledInvertEnable']) != TRUE ) {
                if (escapeshellcmd($_POST['oledInvertEnable']) == 'ON' )  { $configmmdvm['OLED']['Invert'] = "1"; }
                if (escapeshellcmd($_POST['oledInvertEnable']) == 'OFF' )  { $configmmdvm['OLED']['Invert'] = "0"; }
        }

	// Set MMDVMHost DMR Color Code
	if (isset($_POST['dmrColorCode'])) {
	    $configmmdvm['DMR']['ColorCode'] = (int)$_POST['dmrColorCode'];
	}

	// Set MMDVMHost DMR Access List ; post DMR ID/CCS7 to ACL config if submitted AND ONLY IF 7 digits.
	if (isset($configmmdvm['DMR']['WhiteList'])) { unset($configmmdvm['DMR']['WhiteList']); }
	$validCCS7 = strlen($_POST['confDMRWhiteList']);
	if (empty($_POST['confDMRWhiteList']) != TRUE && $validCCS7 >= 7) { // if not at least 7 digits, do not add to ACL
	  $configmmdvm['DMR']['WhiteList'] = escapeshellcmd(preg_replace('/[^0-9\,]/', '', $_POST['confDMRWhiteList']));
	}

	// Set Node Lock Status; holy fucking use cases to prevent dumb
	// (most? heh) users from causing network loops. UGH!!!! :-/
	// This logic will not allow the "Public" radio button to be selected unless ACL has at least one entry
	if (empty($_POST['nodeMode']) != TRUE ) { // node mode selected/posted
	  if (escapeshellcmd($_POST['nodeMode']) == 'prv' ) { // private node...set is up in mmdvm...
	    $configmmdvm['DMR']['SelfOnly'] = 1;
	    $configmmdvm['D-Star']['SelfOnly'] = 1;
	    $configmmdvm['System Fusion']['SelfOnly'] = 1;
	    $configmmdvm['P25']['SelfOnly'] = 1;
	    $configmmdvm['NXDN']['SelfOnly'] = 1;
	    $configmmdvm['M17']['SelfOnly'] = 1;
	    if (empty($_POST['confDMRWhiteList'] == TRUE)) { // user cleared out ACL, so delete them from mmdvm config and force mode to private
		unset($configmmdvm['DMR']['WhiteList']);
		$configmmdvm['DMR']['SelfOnly'] = 1;
		$configmmdvm['D-Star']['SelfOnly'] = 1;
		$configmmdvm['System Fusion']['SelfOnly'] = 1;
		$configmmdvm['P25']['SelfOnly'] = 1;
 		$configmmdvm['NXDN']['SelfOnly'] = 1;
		$configmmdvm['M17']['SelfOnly'] = 1;
	    }
	  }
	  if (escapeshellcmd($_POST['nodeMode']) == 'pub' ) { // public node
	    if (empty($_POST['confDMRWhiteList'] == TRUE)) {  // user didn't add any DMR ID's to ACL. Force back to private node...
		$configmmdvm['DMR']['SelfOnly'] = 1;
		$configmmdvm['D-Star']['SelfOnly'] = 1;
		$configmmdvm['System Fusion']['SelfOnly'] = 1;
		$configmmdvm['P25']['SelfOnly'] = 1;
		$configmmdvm['NXDN']['SelfOnly'] = 1;
		$configmmdvm['M17']['SelfOnly'] = 1;
	    } else {  // OK we have DMRid(s) in the ACL, open her up...
		$configmmdvm['DMR']['SelfOnly'] = 0;
		$configmmdvm['D-Star']['SelfOnly'] = 0;
		$configmmdvm['System Fusion']['SelfOnly'] = 0;
		$configmmdvm['P25']['SelfOnly'] = 0;
		$configmmdvm['NXDN']['SelfOnly'] = 0;
		$configmmdvm['M17']['SelfOnly'] = 0;
	    }
	  }
	}

	// Set the Hostname
	if (empty($_POST['confHostame']) != TRUE ) {
	  $newHostnameLower = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['confHostame']));
	  $currHostname = exec('cat /etc/hostname');
	  $rollHostname = 'sudo sed -i "s/'.$currHostname.'/'.$newHostnameLower.'/" /etc/hostname';
	  $rollHosts = 'sudo sed -i "s/'.$currHostname.'/'.$newHostnameLower.'/" /etc/hosts';
	  $rollMotd = 'sudo sed -i "s/'.$currHostname.'/'.$newHostnameLower.'/" /etc/motd';
	  system($rollHostname);
	  system($rollHosts);
	  system($rollMotd);
	  if (file_exists('/etc/hostapd/hostapd.conf')) {
		  // Update the Hotspot name to the Hostname
		  $rollApSsid = 'sudo sed -i "/^ssid=/c\\ssid='.$newHostnameLower.'" /etc/hostapd/hostapd.conf';
		  system($rollApSsid);
	  }
	}

	// Add missing values to DMRGateway
	if (!isset($configdmrgateway['General']['RptAddress'])) { $configdmrgateway['General']['RptAddress'] = "128.0.0.1"; }
	if (!isset($configdmrgateway['General']['RptPort'])) { $configdmrgateway['General']['RptPort'] = "62032"; }
	if (!isset($configdmrgateway['General']['LocalAddress'])) { $configdmrgateway['General']['LocalAddress'] ="127.0.0.1"; }
	if (!isset($configdmrgateway['General']['LocalPort'])) { $configdmrgateway['General']['LocalPort'] ="62031"; }
	if (!isset($configdmrgateway['General']['RuleTrace'])) { $configdmrgateway['General']['RuleTrace'] ="0"; }
	if (!isset($configdmrgateway['General']['Daemon'])) { $configdmrgateway['General']['Daemon'] ="1"; }
	if (!isset($configdmrgateway['General']['Debug'])) { $configdmrgateway['General']['Debug'] ="0"; }
	if (!isset($configdmrgateway['General']['Suffix'])) { $configdmrgateway['General']['Suffix'] ="R"; }
	if (!isset($configdmrgateway['Info']['Enabled'])) { $configdmrgateway['Info']['Enabled'] = "0"; }
	if (!isset($configdmrgateway['Info']['Power'])) { $configdmrgateway['Info']['Power'] = $configmmdvm['Info']['Power']; }
	if (!isset($configdmrgateway['Info']['Height'])) { $configdmrgateway['Info']['Height'] = $configmmdvm['Info']['Height']; }
	if (!isset($configdmrgateway['Voice']['Enabled'])) { $configdmrgateway['Voice']['Enabled'] = "1"; }
	if (!isset($configdmrgateway['Voice']['Language'])) { $configdmrgateway['Voice']['Language'] = "en_US"; }
	if (!isset($configdmrgateway['Voice']['Directory'])) { $configdmrgateway['Voice']['Directory'] = "/usr/local/etc/DMR_Audio"; }
	if (!isset($configdmrgateway['XLX Network']['Enabled'])) { $configdmrgateway['XLX Network']['Enabled'] = "0"; }
	if (!isset($configdmrgateway['XLX Network']['File'])) { $configdmrgateway['XLX Network']['File'] = "/usr/local/etc/XLXHosts.txt"; }
	if (!isset($configdmrgateway['XLX Network']['Port'])) { $configdmrgateway['XLX Network']['Port'] = "62030"; }
	if (!isset($configdmrgateway['XLX Network']['Password'])) { $configdmrgateway['XLX Network']['Password'] = "passw0rd"; }
	if (!isset($configdmrgateway['XLX Network']['ReloadTime'])) { $configdmrgateway['XLX Network']['ReloadTime'] = "60"; }
	if (!isset($configdmrgateway['XLX Network']['Slot'])) { $configdmrgateway['XLX Network']['Slot'] = "2"; }
	if (!isset($configdmrgateway['XLX Network']['TG'])) { $configdmrgateway['XLX Network']['TG'] = "6"; }
	if (!isset($configdmrgateway['XLX Network']['Base'])) { $configdmrgateway['XLX Network']['Base'] = "64000"; }
	if (!isset($configdmrgateway['XLX Network']['Startup'])) { $configdmrgateway['XLX Network']['Startup'] = "950"; }
	if (!isset($configdmrgateway['XLX Network']['Relink'])) { $configdmrgateway['XLX Network']['Relink'] = "60"; }
	if (!isset($configdmrgateway['XLX Network']['Debug'])) { $configdmrgateway['XLX Network']['Debug'] = "0"; }
	if (!isset($configdmrgateway['DMR Network 3']['Enabled'])) { $configdmrgateway['DMR Network 3']['Enabled'] = "0"; }
	if (!isset($configdmrgateway['DMR Network 3']['Name'])) { $configdmrgateway['DMR Network 3']['Name'] = "HBLink"; }
	if (!isset($configdmrgateway['DMR Network 3']['Address'])) { $configdmrgateway['DMR Network 3']['Address'] = "1.2.3.4"; }
	if (!isset($configdmrgateway['DMR Network 3']['Port'])) { $configdmrgateway['DMR Network 3']['Port'] = "5555"; }
	if (!isset($configdmrgateway['DMR Network 3']['TGRewrite0'])) { $configdmrgateway['DMR Network 3']['TGRewrite0'] = "2,11,2,11,1"; }
	if (!isset($configdmrgateway['DMR Network 3']['Password'])) { $configdmrgateway['DMR Network 3']['Password'] = "PASSWORD"; }
	if (!isset($configdmrgateway['DMR Network 3']['Location'])) { $configdmrgateway['DMR Network 3']['Location'] = "0"; }
	if (!isset($configdmrgateway['DMR Network 3']['Debug'])) { $configdmrgateway['DMR Network 3']['Debug'] = "0"; }
	if (!isset($configdmrgateway['XLX Network']['UserControl'])) { $configdmrgateway['XLX Network']['UserControl'] = "1"; }
	if (!isset($configdmrgateway['DMR Network 1']['Location'])) { $configdmrgateway['DMR Network 1']['Location'] = "1"; }
	if (!isset($configdmrgateway['DMR Network 2']['Location'])) { $configdmrgateway['DMR Network 2']['Location'] = "0"; }
	if (!isset($configdmrgateway['DMR Network 2']['Debug'])) { $configdmrgateway['DMR Network 2']['Debug'] = "0"; }
	if (!isset($configdmrgateway['DMR Network 5']['Location'])) { $configdmrgateway['DMR Network 5']['Location'] = "0"; }
	if (!isset($configdmrgateway['DMR Network 5']['Debug'])) { $configdmrgateway['DMR Network 5']['Debug'] = "0"; }
	if (isset($configdmrgateway['DMR Network 4'])) {
		if (!isset($configdmrgateway['DMR Network 4']['Location'])) { $configdmrgateway['DMR Network 4']['Location'] = "0"; }
	}
	if (isset($configdmrgateway['DMR Network 5'])) {
		if (!isset($configdmrgateway['DMR Network 5']['Location'])) { $configdmrgateway['DMR Network 5']['Location'] = "0"; }
	}
	if (isset($configdmrgateway['DMR Network 6'])) {
		if (!isset($configdmrgateway['DMR Network 6']['Location'])) { $configdmrgateway['DMR Network 6']['Location'] = "0"; }
	}
	if (!isset($configdmrgateway['GPSD'])) {
		$configdmrgateway['GPSD']['Enable'] = "0";
		$configdmrgateway['GPSD']['Address'] = "127.0.0.1";
		$configdmrgateway['GPSD']['Port'] = "2947";
	}
	if (!isset($configdmrgateway['Dynamic TG Control'])) {
		$configdmrgateway['Dynamic TG Control']['Enabled'] = "1";
		$configdmrgateway['Dynamic TG Control']['Port'] = "3769";
	}
	if (!isset($configdmrgateway['APRS'])) {
		$configdmrgateway['APRS']['Enable'] = $DMRGatewayAPRS;
		$configdmrgateway['APRS']['Address'] = "127.0.0.1";
		$configdmrgateway['APRS']['Port'] = "8673";
		$configdmrgateway['APRS']['Suffix'] = "R";
		$configdmrgateway['APRS']['Symbol'] = "\"$symbol\"";
		$configdmrgateway['APRS']['Description'] = "APRS for DMRGateway";
	}
	if (!isset($configdmrgateway['APRS']['Symbol'])) {
		$configdmrgateway['APRS']['Symbol'] = "\"$symbol\"";
	}
	if (isset($configdmrgateway['APRS']['Symbol'])) {
		$configdmrgateway['APRS']['Symbol'] = "\"$symbol\"";
	}

	// DMRGateway can break the lines with quotes in, when DMRGateway is off...
	if ( isset($configdmrgateway['Info']['Location']) && substr($configdmrgateway['Info']['Location'], 0, 1) !== '"' ) { $configdmrgateway['Info']['Location'] = '"'.$configdmrgateway['Info']['Location'].'"'; }
	if ( isset($configdmrgateway['Info']['Description']) && substr($configdmrgateway['Info']['Description'], 0, 1) !== '"' ) { $configdmrgateway['Info']['Description'] = '"'.$configdmrgateway['Info']['Description'].'"'; }
	if ( isset($configdmrgateway['DMR Network 1']['Password']) && substr($configdmrgateway['DMR Network 1']['Password'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 1']['Password'] = '"'.$configdmrgateway['DMR Network 1']['Password'].'"'; }
	if ( isset($configdmrgateway['DMR Network 1']['Options']) &&  substr($configdmrgateway['DMR Network 1']['Options'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 1']['Options'] = '"'.$configdmrgateway['DMR Network 1']['Options'].'"'; }
	if ( isset($configdmrgateway['DMR Network 2']['Password']) && substr($configdmrgateway['DMR Network 2']['Password'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 2']['Password'] = '"'.$configdmrgateway['DMR Network 2']['Password'].'"'; }
	if ( isset($configdmrgateway['DMR Network 2']['Options']) &&  substr($configdmrgateway['DMR Network 2']['Options'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 2']['Options'] = '"'.$configdmrgateway['DMR Network 2']['Options'].'"'; }
	if ( isset($configdmrgateway['DMR Network 3']['Password']) && substr($configdmrgateway['DMR Network 3']['Password'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 3']['Password'] = '"'.$configdmrgateway['DMR Network 3']['Password'].'"'; }
	if ( isset($configdmrgateway['DMR Network 3']['Options']) &&  substr($configdmrgateway['DMR Network 3']['Options'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 3']['Options'] = '"'.$configdmrgateway['DMR Network 3']['Options'].'"'; }
	if ( isset($configdmrgateway['DMR Network 4']['Password']) && substr($configdmrgateway['DMR Network 4']['Password'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 4']['Password'] = '"'.$configdmrgateway['DMR Network 4']['Password'].'"'; }
	if ( isset($configdmrgateway['DMR Network 4']['Options']) &&  substr($configdmrgateway['DMR Network 4']['Options'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 4']['Options'] = '"'.$configdmrgateway['DMR Network 4']['Options'].'"'; }
	if ( isset($configdmrgateway['DMR Network 5']['Password']) && substr($configdmrgateway['DMR Network 5']['Password'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 5']['Password'] = '"'.$configdmrgateway['DMR Network 5']['Password'].'"'; }
	if ( isset($configdmrgateway['DMR Network 5']['Options']) &&  substr($configdmrgateway['DMR Network 5']['Options'], 0, 1) !== '"' ) { $configdmrgateway['DMR Network 5']['Options'] = '"'.$configdmrgateway['DMR Network 5']['Options'].'"'; }

	// Add missing values to M17Gateway
	$configm17gateway['General']['RptAddress'] = '127.0.0.1';
	$configm17gateway['General']['RptPort'] = '17011';
	$configm17gateway['General']['LocalPort'] = '17010';
	$configm17gateway['General']['Debug'] = '0';
	$configm17gateway['General']['Daemon'] = '1';
	if(!isset($configm17gateway['General']['Suffix'])) { $configm17gateway['General']['Suffix'] = 'H'; }
	$configm17gateway['Info']['Power'] = $configmmdvm['Info']['Power'];
	$configm17gateway['Info']['Height'] = $configmmdvm['Info']['Height'];
	$configm17gateway['Network']['HostsFile1'] = "/usr/local/etc/M17Hosts.txt";
	$configm17gateway['Network']['HostsFile2'] = "/root/M17Hosts.txt";
	$configm17gateway['Network']['Port'] = "17000";
	$configm17gateway['Network']['ReloadTime'] = "0";
	$configm17gateway['Network']['HangTime'] = "240";
	$configm17gateway['Network']['Revert'] = "1";
	$configm17gateway['Network']['Debug'] = "0";
	$configm17gateway['APRS']['Enable'] = $M17GatewayAPRS;
	$configm17gateway['APRS']['Address'] = "127.0.0.1";
	$configm17gateway['APRS']['Port'] = "8673";
	$configm17gateway['APRS']['Description'] = "APRS for M17Gateway";
	$configm17gateway['APRS']['Symbol'] = "\"$symbol\"";
	$configm17gateway['Remote Commands']['Enable'] = "1";
	$configm17gateway['Remote Commands']['Port'] = "6076";
	$configm17gateway['Log']['DisplayLevel'] = "0";
	$configm17gateway['Log']['FileLevel'] = "2";
	$configm17gateway['Log']['FilePath'] = "/var/log/pi-star";
	$configm17gateway['Log']['FileRoot'] = "M17Gateway";
	$configm17gateway['Voice']['Enabled'] = "1";
	$configm17gateway['Voice']['Language'] = "en_US";
	$configm17gateway['Voice']['Directory'] = "/usr/local/etc/M17_Audio";

	// Add missing options to MMDVMHost
	if (!isset($configmmdvm['Modem']['RFLevel'])) { $configmmdvm['Modem']['RFLevel'] = "100"; }
	if (!isset($configmmdvm['Modem']['RXDCOffset'])) { $configmmdvm['Modem']['RXDCOffset'] = "0"; }
	if (!isset($configmmdvm['Modem']['TXDCOffset'])) { $configmmdvm['Modem']['TXDCOffset'] = "0"; }
	if (!isset($configmmdvm['Modem']['CWIdTXLevel'])) { $configmmdvm['Modem']['CWIdTXLevel'] = "50"; }
	if (!isset($configmmdvm['Modem']['NXDNTXLevel'])) { $configmmdvm['Modem']['NXDNTXLevel'] = "50"; }
	if (!isset($configmmdvm['Modem']['M17TXLevel'])) { $configmmdvm['Modem']['M17TXLevel'] = "50"; }
	if (!isset($configmmdvm['Modem']['POCSAGTXLevel'])) { $configmmdvm['Modem']['POCSAGTXLevel'] = "50"; }
	if (!isset($configmmdvm['Modem']['FMTXLevel'])) { $configmmdvm['Modem']['FMTXLevel'] = "50"; }
	if (!isset($configmmdvm['Modem']['AX25TXLevel'])) { $configmmdvm['Modem']['AX25TXLevel'] = "50"; }
	if (!isset($configmmdvm['Modem']['UseCOSAsLockout'])) { $configmmdvm['Modem']['UseCOSAsLockout'] = "0"; }
	if (!isset($configmmdvm['D-Star']['AckReply'])) { $configmmdvm['D-Star']['AckReply'] = "1"; }
	if (!isset($configmmdvm['D-Star']['AckTime'])) { $configmmdvm['D-Star']['AckTime'] = "750"; }
	if (!isset($configmmdvm['D-Star']['AckMessage'])) { $configmmdvm['D-Star']['AckMessage'] = "0"; }
	if (!isset($configmmdvm['D-Star']['RemoteGateway'])) { $configmmdvm['D-Star']['RemoteGateway'] = "0"; }
	if (isset($configmmdvm['DMR']['Beacons'])) { $configmmdvm['DMR']['Beacons'] = $DMRBeaconEnable; }
	if (!isset($configmmdvm['DMR']['BeaconDuration'])) { $configmmdvm['DMR']['BeaconDuration'] = "3"; }
	if (!isset($configmmdvm['DMR']['OVCM'])) { $configmmdvm['DMR']['OVCM'] = "0"; }
	if (!isset($configmmdvm['DMR Network']['Type'])) { $configmmdvm['DMR Network']['Type'] = "Gateway"; }
	if (!isset($configmmdvm['P25']['RemoteGateway'])) { $configmmdvm['P25']['RemoteGateway'] = "0"; }
	if (!isset($configmmdvm['P25']['TXHang'])) { $configmmdvm['P25']['TXHang'] = "5"; }
	if (!isset($configmmdvm['NXDN']['Enable'])) { $configmmdvm['NXDN']['Enable'] = "0"; }
	if (!isset($configmmdvm['NXDN']['RAN'])) { $configmmdvm['NXDN']['RAN'] = "1"; }
	if (!isset($configmmdvm['NXDN']['SelfOnly'])) { $configmmdvm['NXDN']['SelfOnly'] = "1"; }
	if (!isset($configmmdvm['NXDN']['RemoteGateway'])) { $configmmdvm['NXDN']['RemoteGateway'] = "0"; }
	if (!isset($configmmdvm['NXDN']['TXHang'])) { $configmmdvm['NXDN']['TXHang'] = "5"; }
	if (!isset($configmmdvm['M17']['Enable'])) { $configmmdvm['M17']['Enable'] = "0"; }
	if (!isset($configmmdvm['M17']['CAN'])) { $configmmdvm['M17']['CAN'] = "0"; }
	if (!isset($configmmdvm['M17']['SelfOnly'])) { $configmmdvm['M17']['SelfOnly'] = "1"; }
	if (!isset($configmmdvm['M17']['TXHang'])) { $configmmdvm['M17']['TXHang'] = "5"; }
	if (!isset($configmmdvm['M17']['Enable'])) { $configmmdvm['M17']['Enable'] = "0"; }
	if (!isset($configmmdvm['M17']['ModeHang'])) { $configmmdvm['M17']['ModeHang'] = "20"; }
	if (!isset($configmmdvm['AX.25']['Enable'])) { $configmmdvm['AX.25']['Enable'] = "0"; }
	if (!isset($configmmdvm['AX.25']['TXDelay'])) { $configmmdvm['AX.25']['TXDelay'] = "300"; }
	if (!isset($configmmdvm['AX.25']['RXTwist'])) { $configmmdvm['AX.25']['RXTwist'] = "6"; }
	if (!isset($configmmdvm['AX.25']['SlotTime'])) { $configmmdvm['AX.25']['SlotTime'] = "30"; }
	if (!isset($configmmdvm['AX.25']['PPersist'])) { $configmmdvm['AX.25']['PPersist'] = "128"; }
	if (!isset($configmmdvm['AX.25']['Trace'])) { $configmmdvm['AX.25']['Trace'] = "0"; }
	if (!isset($configmmdvm['NXDN Network']['Enable'])) { $configmmdvm['NXDN Network']['Enable'] = "0"; }
	if (!isset($configmmdvm['NXDN Network']['LocalPort'])) { $configmmdvm['NXDN Network']['LocalPort'] = "3300"; }
	if (!isset($configmmdvm['NXDN Network']['GatewayAddress'])) { $configmmdvm['NXDN Network']['GatewayAddress'] = "127.0.0.1"; }
	if (!isset($configmmdvm['NXDN Network']['GatewayPort'])) { $configmmdvm['NXDN Network']['GatewayPort'] = "4300"; }
	if (!isset($configmmdvm['NXDN Network']['Protocol'])) { $configmmdvm['NXDN Network']['Protocol'] = "Icom"; }
	if (!isset($configmmdvm['NXDN Network']['Debug'])) { $configmmdvm['NXDN Network']['Debug'] = "0"; }
	if (!isset($configmmdvm['M17 Network']['Enable'])) { $configmmdvm['M17 Network']['Enable'] = "0"; }
	if (!isset($configmmdvm['M17 Network']['LocalAddress'])) { $configmmdvm['M17 Network']['LocalAddress'] = "127.0.0.1"; }
	if (!isset($configmmdvm['M17 Network']['LocalPort'])) { $configmmdvm['M17 Network']['LocalPort'] = "17011"; }
	if (!isset($configmmdvm['M17 Network']['GatewayAddress'])) { $configmmdvm['M17 Network']['GatewayAddress'] = "127.0.0.1"; }
	if (!isset($configmmdvm['M17 Network']['GatewayPort'])) { $configmmdvm['M17 Network']['GatewayPort'] = "17010"; }
	if (!isset($configmmdvm['M17 Network']['ModeHang'])) { $configmmdvm['M17 Network']['ModeHang'] = "20"; }
	if (!isset($configmmdvm['M17 Network']['Debug'])) { $configmmdvm['M17 Network']['Debug'] = "0"; }
	if (!isset($configmmdvm['AX.25 Network']['Enable'])) { $configmmdvm['AX.25 Network']['Enable'] = "0"; }
	if (!isset($configmmdvm['AX.25 Network']['Port'])) { $configmmdvm['AX.25 Network']['Port'] = "/dev/ttyp7"; }
	if (!isset($configmmdvm['AX.25 Network']['Speed'])) { $configmmdvm['AX.25 Network']['Speed'] = "9600"; }
	if (!isset($configmmdvm['AX.25 Network']['Debug'])) { $configmmdvm['AX.25 Network']['Debug'] = "0"; }
	if (!isset($configmmdvm['NXDN Id Lookup']['File'])) { $configmmdvm['NXDN Id Lookup']['File'] = "/usr/local/etc/NXDN.csv"; }
	if (!isset($configmmdvm['NXDN Id Lookup']['Time'])) { $configmmdvm['NXDN Id Lookup']['Time'] = "24"; }
	if (!isset($configmmdvm['System Fusion']['TXHang'])) { $configmmdvm['System Fusion']['TXHang'] = "3"; }
	if (!isset($configmmdvm['Lock File']['Enable'])) { $configmmdvm['Lock File']['Enable'] = "0"; }
	if (!isset($configmmdvm['Lock File']['File'])) { $configmmdvm['Lock File']['File'] = "/tmp/MMDVMHost.lock"; }
	if (!isset($configdmrgateway['GPSD']['Enable'])) { $configdmrgateway['GPSD']['Enable'] = "0"; }
 	if (!isset($configdmrgateway['GPSD']['Address'])) { $configdmrgateway['GPSD']['Address'] = "127.0.0.1"; }
	if (!isset($configdmrgateway['GPSD']['Port'])) { $configdmrgateway['GPSD']['Port'] = "2947"; }
	if (!isset($configmmdvm['OLED']['Type'])) { $configmmdvm['OLED']['Type'] = "3"; }
	if (!isset($configmmdvm['OLED']['Scroll'])) { $configmmdvm['OLED']['Scroll'] = "0"; }
	if (!isset($configmmdvm['OLED']['LogoScreensaver'])) { $configmmdvm['OLED']['LogoScreensaver'] = "1"; }
	if (!isset($configmmdvm['OLED']['Brightness'])) { $configmmdvm['OLED']['Brightness'] = "0"; }
	if (!isset($configmmdvm['OLED']['Invert'])) { $configmmdvm['OLED']['Invert'] = "0"; }
	if (!isset($configmmdvm['OLED']['Rotate'])) { $configmmdvm['OLED']['Rotate'] = "0"; }
	if (!isset($configmmdvm['OLED']['Cast'])) { $configmmdvm['OLED']['Cast'] = "0"; }
	if (isset($configmmdvm['Remote Control']['Enable'])) { $configmmdvm['Remote Control']['Enable'] = "1"; }
	if (!isset($configmmdvm['Remote Control']['Enable'])) { $configmmdvm['Remote Control']['Enable'] = "1"; }
	if (!isset($configmmdvm['Remote Control']['Port'])) { $configmmdvm['Remote Control']['Port'] = "7642"; }
	if (!isset($configmmdvm['Remote Control']['Address'])) { $configmmdvm['Remote Control']['Address'] = "127.0.0.1"; }
	if (isset($configmmdvm['TFT Serial']['Port'])) {
	    if ( $configmmdvm['TFT Serial']['Port'] == "/dev/modem" ) { $configmmdvm['TFT Serial']['Port'] = "modem"; }
	}
	if (isset($configmmdvm['Nextion']['Port'])) {
	    if ( $configmmdvm['Nextion']['Port'] == "/dev/modem" ) { $configmmdvm['Nextion']['Port'] = "modem"; }
	}
	if (!isset($configmmdvm['FM'])) {
		$configmmdvm['FM']['Enable'] = "0";
		$configmmdvm['FM']['Callsign'] = $newCallsignUpper;
		$configmmdvm['FM']['CallsignSpeed'] = "20";
		$configmmdvm['FM']['CallsignFrequency'] = "1000";
		$configmmdvm['FM']['CallsignTime'] = "10";
		$configmmdvm['FM']['CallsignHoldoff'] = "0";
		$configmmdvm['FM']['CallsignHighLevel'] = "50";
		$configmmdvm['FM']['CallsignLowLevel'] = "20";
		$configmmdvm['FM']['CallsignAtStart'] = "1";
		$configmmdvm['FM']['CallsignAtEnd'] = "1";
		$configmmdvm['FM']['CallsignAtLatch'] = "0";
		$configmmdvm['FM']['RFAck'] = "K";
		$configmmdvm['FM']['ExtAck'] = "N";
		$configmmdvm['FM']['AckSpeed'] = "20";
		$configmmdvm['FM']['AckFrequency'] = "1750";
		$configmmdvm['FM']['AckMinTime'] = "4";
		$configmmdvm['FM']['AckDelay'] = "1000";
		$configmmdvm['FM']['AckLevel'] = "50";
		$configmmdvm['FM']['Timeout'] = "180";
		$configmmdvm['FM']['TimeoutLevel'] = "80";
		$configmmdvm['FM']['CTCSSFrequency'] = "94.8";
		$configmmdvm['FM']['CTCSSThreshold'] = "30";
		$configmmdvm['FM']['CTCSSHighThreshold'] = "30";
		$configmmdvm['FM']['CTCSSLowThreshold'] = "20";
		$configmmdvm['FM']['CTCSSLevel'] = "20";
		$configmmdvm['FM']['KerchunkTime'] = "0";
		$configmmdvm['FM']['HangTime'] = "7";
		$configmmdvm['FM']['AccessMode'] = "1";
		$configmmdvm['FM']['COSInvert'] = "0";
		$configmmdvm['FM']['RFAudioBoost'] = "1";
		$configmmdvm['FM']['MaxDevLevel'] = "90";
		$configmmdvm['FM']['ExtAudioBoost'] = "1";
	}
	
	// Add missing options to DMR2YSF
	if (!isset($configdmr2ysf['YSF Network']['FCSRooms'])) { $configdmr2ysf['YSF Network']['FCSRooms'] = "/usr/local/etc/FCSHosts.txt"; }
	if (!isset($configdmr2ysf['DMR Network']['DefaultDstTG'])) { $configdmr2ysf['DMR Network']['DefaultDstTG'] = "9"; }
	if (!isset($configdmr2ysf['DMR Network']['TGUnlink'])) { $configdmr2ysf['DMR Network']['TGUnlink'] = "4000"; }
	if (!isset($configdmr2ysf['DMR Network']['TGListFile'])) { $configdmr2ysf['DMR Network']['TGListFile'] = "/usr/local/etc/TGList_YSF.txt"; }
	$configdmr2ysf['Log']['DisplayLevel'] = "0";
	$configdmr2ysf['Log']['FileLevel'] = "2";
        $configdmr2ysf['YSF Network']['GatewayPort'] = $configysfgateway['General']['LocalPort'];
        $configdmr2ysf['YSF Network']['LocalPort'] = $configysfgateway['General']['RptPort'];
	if (!isset($configdmr2ysf['YSF Network']['DT1']))   { $configdmr2ysf['YSF Network']['DT1'] = "1,34,97,95,43,3,17,0,0,0"; }
	if (!isset($configdmr2ysf['YSF Network']['DT2']))   { $configdmr2ysf['YSF Network']['DT2'] = "0,0,0,0,108,32,28,32,3,8"; }
	if (!isset($configdmr2ysf['YSF Network']['Debug'])) { $configdmr2ysf['YSF Network']['Debug'] = "0"; }

	// Add missing options to YSFGateway
	if (!isset($configysfgateway['General']['WiresXMakeUpper'])) { $configysfgateway['General']['WiresXMakeUpper'] = "1"; }
	if (!isset($configysfgateway['Network']['Revert'])) { $configysfgateway['Network']['Revert'] = "0"; }
	if (isset($configysfgateway['Network']['YSF2DMRAddress'])) { unset($configysfgateway['Network']['YSF2DMRAddress']); }
	if (isset($configysfgateway['Network']['YSF2DMRPort'])) { unset($configysfgateway['Network']['YSF2DMRPort']); }
	unset($configysfgateway['Network']['DataPort']);
	unset($configysfgateway['Network']['StatusPort']);
	if (!isset($configysfgateway['GPSD']['Enable'])) { $configysfgateway['GPSD']['Enable'] = "0"; }
 	if (!isset($configysfgateway['GPSD']['Address'])) { $configysfgateway['GPSD']['Address'] = "127.0.0.1"; }
	if (!isset($configysfgateway['GPSD']['Port'])) { $configysfgateway['GPSD']['Port'] = "2947"; }
	if ($configdgidgateway['Enabled']['Enabled'] == "1") { // if DGId is enabled by user, use the proper DGId ports, otherwise, use MMDVMHost ports:
	    $configysfgateway['General']['LocalPort'] = "42025";
	    $configysfgateway['Network']['Port'] = "4200"; // needed for DGiDGw
	} else {
	    $configysfgateway['General']['LocalPort'] = "4200";
	    $configmmdvm['System Fusion Network']['GatewayPort'] = "4200"; // ensure MMDVMhost uses new YSFgw port when reverting
	    unset($configysfgateway['Network']['Port']); // Must be removed when not using DGiDgw
	}
	if ($configdgidgateway['Enabled']['Enabled'] == "1") {
	    $configysfgateway['General']['RptPort'] = "42026";
	} else {
	    $configysfgateway['General']['RptPort'] = "3200";
	    $configmmdvm['System Fusion Network']['LocalPort'] = "3200"; // ensure MMDVMhost uses new YSFgw port when reverting
	}

	// Add missing options to YSF2DMR
	if (!isset($configysf2dmr['Info']['Power'])) { $configysf2dmr['Info']['Power'] = "1"; }
	if (!isset($configysf2dmr['Info']['Height'])) { $configysf2dmr['Info']['Height'] = "0"; }
	if (!isset($configysf2dmr['YSF Network']['DstAddress'])) { $configysf2dmr['YSF Network']['DstAddress'] = "127.0.0.1"; }
	if (!isset($configysf2dmr['YSF Network']['DstPort'])) { $configysf2dmr['YSF Network']['DstPort'] = "42000"; }
	if (!isset($configysf2dmr['YSF Network']['LocalAddress'])) { $configysf2dmr['YSF Network']['LocalAddress'] = "127.0.0.1"; }
	if (!isset($configysf2dmr['YSF Network']['LocalPort'])) { $configysf2dmr['YSF Network']['LocalPort'] = "42013"; }
	if (!isset($configysf2dmr['YSF Network']['Daemon'])) { $configysf2dmr['YSF Network']['Daemon'] = "1"; }
	if (!isset($configysf2dmr['YSF Network']['EnableWiresX'])) { $configysf2dmr['YSF Network']['EnableWiresX'] = "1"; }
	if (!isset($configysf2dmr['DMR Network']['StartupDstId'])) { $configysf2dmr['DMR Network']['StartupDstId'] = "3170603"; }
	if (!isset($configysf2dmr['DMR Network']['StartupPC'])) { $configysf2dmr['DMR Network']['StartupPC'] = "0"; }
	if (!isset($configysf2dmr['DMR Network']['Jitter'])) { $configysf2dmr['DMR Network']['Jitter'] = "500"; }
	if (!isset($configysf2dmr['DMR Network']['EnableUnlink'])) { $configysf2dmr['DMR Network']['EnableUnlink'] = "1"; }
	if (!isset($configysf2dmr['DMR Network']['TGUnlink'])) { $configysf2dmr['DMR Network']['TGUnlink'] = "4000"; }
	if (!isset($configysf2dmr['DMR Network']['PCUnlink'])) { $configysf2dmr['DMR Network']['PCUnlink'] = "0"; }
	if (!isset($configysf2dmr['DMR Network']['Debug'])) { $configysf2dmr['DMR Network']['Debug'] = "0"; }
	if ( (!isset($configysf2dmr['DMR Network']['TGListFile'])) && (file_exists('/usr/local/etc/TGList_BM.txt')) ) { $configysf2dmr['DMR Network']['TGListFile'] = "/usr/local/etc/TGList_BM.txt"; }
	if (!isset($configysf2dmr['DMR Id Lookup']['File'])) { $configysf2dmr['DMR Id Lookup']['File'] = "/usr/local/etc/DMRIds.dat"; }
	if (!isset($configysf2dmr['DMR Id Lookup']['Time'])) { $configysf2dmr['DMR Id Lookup']['Time'] = "24"; }
	if (!isset($configysf2dmr['DMR Id Lookup']['DropUnknown'])) { $configysf2dmr['DMR Id Lookup']['DropUnknown'] = "0"; }
	if (isset($configysf2dmr['Log']['DisplayLevel'])) { $configysf2dmr['Log']['DisplayLevel'] = "0"; }
	if (isset($configysf2dmr['Log']['FileLevel'])) { $configysf2dmr['Log']['FileLevel'] = "2"; }
	if (!isset($configysf2dmr['Log']['FilePath'])) { $configysf2dmr['Log']['FilePath'] = "/var/log/pi-star"; }
	if (!isset($configysf2dmr['Log']['FileRoot'])) { $configysf2dmr['Log']['FileRoot'] = "YSF2DMR"; }
	if (!isset($configysf2dmr['aprs.fi']['Enable'])) { $configysf2dmr['aprs.fi']['Enable'] = "0"; }
	if (!isset($configysf2dmr['aprs.fi']['Port'])) { $configysf2dmr['aprs.fi']['Port'] = "14580"; }
	if (!isset($configysf2dmr['aprs.fi']['Refresh'])) { $configysf2dmr['aprs.fi']['Refresh'] = "240"; }
	if (!isset($configysf2dmr['Enabled']['Enabled'])) { $configysf2dmr['Enabled']['Enabled'] = "0"; }
	unset($configysf2dmr['Info']['Enabled']);
	unset($configysf2dmr['DMR Network']['JitterEnabled']);
	$configysf2dmr['Log']['DisplayLevel'] = "0";
	$configysf2dmr['Log']['FileLevel'] = "2";
	if (!isset($configysf2dmr['aprs.fi']['Enable'])) { $configysf2dmr['aprs.fi']['Enable'] = "0"; }
	if (!isset($configysf2dmr['YSF Network']['WiresXMakeUpper'])) { $configysf2dmr['YSF Network']['WiresXMakeUpper'] = "1"; }
	if (!isset($configysf2dmr['YSF Network']['DT1'])) { $configysf2dmr['YSF Network']['DT1'] = "1,34,97,95,43,3,17,0,0,0"; }
	if (!isset($configysf2dmr['YSF Network']['DT2'])) { $configysf2dmr['YSF Network']['DT2'] = "0,0,0,0,108,32,28,32,3,8"; }

	// Add missing options to YSF2NXDN
	$configysf2nxdn['YSF Network']['LocalPort'] = $configysfgateway['YSF Network']['YSF2NXDNPort'];
	$configysf2nxdn['YSF Network']['DstPort'] = $configysfgateway['YSF Network']['Port'];
	$configysf2nxdn['YSF Network']['Daemon'] = "1";
	$configysf2nxdn['YSF Network']['EnableWiresX'] = "1";
	if (!isset($configysf2nxdn['Enabled']['Enabled'])) { $configysf2nxdn['Enabled']['Enabled'] = "0"; }
	$configysf2nxdn['NXDN Id Lookup']['File'] = "/usr/local/etc/NXDN.csv";
	$configysf2nxdn['NXDN Network']['TGListFile'] = "/usr/local/etc/TGList_NXDN.txt";
	$configysf2nxdn['Log']['DisplayLevel'] = "0";
	$configysf2nxdn['Log']['FileLevel'] = "2";
	$configysf2nxdn['Log']['FilePath'] = "/var/log/pi-star";
	$configysf2nxdn['Log']['FileRoot'] = "YSF2NXDN";
	if (!isset($configysf2nxdn['aprs.fi']['Enable'])) { $configysf2nxdn['aprs.fi']['Enable'] = "0"; }
	if (!isset($configysf2nxdn['YSF Network']['WiresXMakeUpper'])) { $configysf2nxdn['YSF Network']['WiresXMakeUpper'] = "1"; }
	if (!isset($configysf2nxdn['YSF Network']['DT1'])) { $configysf2nxdn['YSF Network']['DT1'] = "1,34,97,95,43,3,17,0,0,0"; }
	if (!isset($configysf2nxdn['YSF Network']['DT2'])) { $configysf2nxdn['YSF Network']['DT2'] = "0,0,0,0,108,32,28,32,3,8"; }

	// Add missing options to YSF2P25
	$configysf2p25['YSF Network']['LocalPort'] = $configysfgateway['YSF Network']['YSF2P25Port'];
	$configysf2p25['YSF Network']['DstPort'] = $configysfgateway['YSF Network']['Port'];
	$configysf2p25['YSF Network']['Daemon'] = "1";
	$configysf2p25['YSF Network']['EnableWiresX'] = "1";
	if (!isset($configysf2p25['Enabled']['Enabled'])) { $configysf2p25['Enabled']['Enabled'] = "0"; }
	$configysf2p25['DMR Id Lookup']['File'] = "/usr/local/etc/DMRIds.dat";
	$configysf2p25['P25 Network']['TGListFile'] = "/usr/local/etc/TGList_P25.txt";
	$configysf2p25['Log']['DisplayLevel'] = "0";
	$configysf2p25['Log']['FileLevel'] = "2";
	$configysf2p25['Log']['FilePath'] = "/var/log/pi-star";
	$configysf2p25['Log']['FileRoot'] = "YSF2P25";
	if (isset($configysf2p25['aprs.fi'])) { unset($configysf2p25['aprs.fi']); }
	if (!isset($configysf2p25['YSF Network']['WiresXMakeUpper'])) { $configysf2p25['YSF Network']['WiresXMakeUpper'] = "1"; }
	if (!isset($configysf2p25['YSF Network']['DT1'])) { $configysf2p25['YSF Network']['DT1'] = "1,34,97,95,43,3,17,0,0,0"; }
	if (!isset($configysf2p25['YSF Network']['DT2'])) { $configysf2p25['YSF Network']['DT2'] = "0,0,0,0,108,32,28,32,3,8"; }

	// Defaults for DGIdGateway
	$configdgidgateway['General']['LocalPort'] = $configmmdvm['System Fusion Network']['GatewayPort'];
	$configdgidgateway['General']['RptPort'] =  $configmmdvm['System Fusion Network']['LocalPort'];
	$configdgidgateway['General']['RptAddress'] = "127.0.0.1";
	$configdgidgateway['General']['LocalAddress'] = "127.0.0.1";
	$configdgidgateway['General']['Daemon'] = "1";
	$configdgidgateway['General']['Debug'] = "0";
	$configdgidgateway['General']['Bleep'] = "1";
	$configdgidgateway['Log']['DisplayLevel'] = "0"; 
	$configdgidgateway['Log']['FileLevel'] = "2";
	$configdgidgateway['Log']['FilePath'] = "/var/log/pi-star";
	$configdgidgateway['Log']['FileRoot'] = "DGIdGateway";
	$configdgidgateway['Log']['FileRotate'] = "1";
	$configdgidgateway['YSF Network']['Hosts'] = "/usr/local/etc/YSFHosts.txt";
	$configdgidgateway['DGId=0']['Port'] = $configysfgateway['General']['LocalPort'];
	$configdgidgateway['DGId=0']['Local'] = $configysfgateway['General']['RptPort'];
	$configdgidgateway['DGId=0']['Type'] = "Gateway";
	$configdgidgateway['DGId=0']['Static'] = "1";
	$configdgidgateway['DGId=0']['Address'] = "127.0.0.1";
	$configdgidgateway['DGId=0']['RFHangTime'] = "120";
	$configdgidgateway['DGId=0']['NetHangTime'] = "120";
	$configdgidgateway['DGId=0']['Debug'] = "0";
	$configdgidgateway['APRS']['Enable'] = $DGIdGatewayAPRS;
	$configdgidgateway['APRS']['Address'] = "127.0.0.1";
	$configdgidgateway['APRS']['Port'] = "8673";
	$configdgidgateway['APRS']['Suffix'] = "Y";
	$configdgidgateway['APRS']['Symbol'] = "\"$symbol\"";
	$configdgidgateway['APRS']['Description'] = "APRS for DGIdGateway";

	// Clean up for NXDN Gateway
	if (file_exists('/etc/nxdngateway')) {
		if (isset($confignxdngateway['Network']['HostsFile'])) {
			$confignxdngateway['Network']['HostsFile1'] = $confignxdngateway['Network']['HostsFile'];
			$confignxdngateway['Network']['HostsFile2'] = "/usr/local/etc/NXDNHostsLocal.txt";
			unset($confignxdngateway['Network']['HostsFile']);
			if (!file_exists('/usr/local/etc/NXDNHostsLocal.txt')) { exec('sudo touch /usr/local/etc/NXDNHostsLocal.txt'); }
		}
		$configmmdvm['NXDN Network']['LocalAddress'] = "127.0.0.1";
		$configmmdvm['NXDN Network']['LocalPort'] = "14021";
		$configmmdvm['NXDN Network']['GatewayAddress'] = "127.0.0.1";
		$configmmdvm['NXDN Network']['GatewayPort'] = "14020";
		if(isset($configmmdvm['NXDN']['SelfOnly'])) {
			$nxdnSelfOnlyTmp = $configmmdvm['NXDN']['SelfOnly'];
			unset($configmmdvm['NXDN']['SelfOnly']);
			$configmmdvm['NXDN']['SelfOnly'] = $nxdnSelfOnlyTmp;
		}
		if(isset($configmmdvm['NXDN']['ModeHang'])) {
			$nxdnRfModeHangTmp = $configmmdvm['NXDN']['ModeHang'];
			unset($configmmdvm['NXDN']['ModeHang']);
			$configmmdvm['NXDN']['ModeHang'] = $nxdnRfModeHangTmp;
		}
		if(isset($configmmdvm['NXDN Network']['ModeHang'])) {
			$nxdnNetModeHangTmp = $configmmdvm['NXDN Network']['ModeHang'];
			unset($configmmdvm['NXDN Network']['ModeHang']);
			$configmmdvm['NXDN Network']['ModeHang'] = $nxdnNetModeHangTmp;
		}
		// Add in all the APRS stuff
		if(!isset($confignxdngateway['Info']['Power'])) { $confignxdngateway['Info']['Power'] = "1"; }
		if(!isset($confignxdngateway['Info']['Height'])) { $confignxdngateway['Info']['Height'] = "0"; }
		if(!isset($confignxdngateway['APRS']['Enable'])) { $confignxdngateway['APRS']['Enable'] = $NXDNGatewayAPRS; }
		if(!isset($confignxdngateway['APRS']['Address'])) { $confignxdngateway['APRS']['Server'] = "127.0.0.1"; }
		if(!isset($confignxdngateway['APRS']['Port'])) { $confignxdngateway['APRS']['Port'] = "8673"; }
		if(!isset($confignxdngateway['APRS']['Suffix'])) { $confignxdngateway['APRS']['Suffix'] = "N"; }
		if(!isset($confignxdngateway['APRS']['Symbol'])) { $confignxdngateway['APRS']['Symbol'] = "\"$symbol\""; }
		if(isset($confignxdngateway['APRS']['Symbol'])) { $confignxdngateway['APRS']['Symbol'] = "\"$symbol\""; }
		if(!isset($confignxdngateway['APRS']['Description'])) { $confignxdngateway['APRS']['Description'] = "APRS for NXDNGateway"; }
		// GPSd stuff
		if(!isset($confignxdngateway['GPSD']['Enable'])) { $confignxdngateway['GPSD']['Enable'] = "0"; }
		if(!isset($confignxdngateway['GPSD']['Address'])) { $confignxdngateway['GPSD']['Address'] = "127.0.0.1"; }
		if(!isset($confignxdngateway['GPSD']['Port'])) { $confignxdngateway['GPSD']['Port'] = "2947"; }
	}

	// Clean up legacy options
		unset($configdmrgateway['XLX Network 1']);
		unset($configdmrgateway['XLX Network 2']);

	// Add P25Gateway Options
        $configp25gateway['Network']['InactivityTimeout'] = "1440";
	if (isset($configp25gateway['Remote Commands']['Enable'])) { $configp25gateway['Remote Commands']['Enable'] = "1"; }
	if (isset($configp25gateway['Remote Commands']['Port'])) { $configp25gateway['Remote Commands']['Port'] = "6074"; }
	if (isset($configp25gateway['General']['Announcements'])) { unset($configp25gateway['General']['Announcements']); }
	if (!isset($configp25gateway['Log']['FilePath'])) { $configp25gateway['Log']['FilePath'] = "/var/log/pi-star"; }
	if (!isset($configp25gateway['Log']['FileRoot'])) { $configp25gateway['Log']['FileRoot'] = "P25Gateway"; }
	if (!isset($configp25gateway['Log']['DisplayLevel'])) { $configp25gateway['Log']['DisplayLevel'] = "0"; }
	if (!isset($configp25gateway['Log']['FileLevel'])) { $configp25gateway['Log']['FileLevel'] = "2"; }
	if (!isset($configp25gateway['Network']['P252DMRAddress'])) { $configp25gateway['Network']['P252DMRAddress'] = "127.0.0.1"; }
	if (!isset($configp25gateway['Network']['P252DMRPort'])) { $configp25gateway['Network']['P252DMRPort'] = "42012"; }
	if (isset($configp25gateway['Network']['Startup'])) {
		$configp25gateway['Network']['Static'] = $configp25gateway['Network']['Startup'];
		unset($configp25gateway['Network']['Startup']);
	}

	// Add NXDNGateway Options
	if (isset($confignxdngateway['Remote Commands']['Enable'])) { $confignxdngateway['Remote Commands']['Enable'] = "1"; }
	if (isset($confignxdngateway['Remote Commands']['Port'])) { $confignxdngateway['Remote Commands']['Port'] = "6075"; }
	if (isset($confignxdngateway['aprs.fi'])) { unset($confignxdngateway['aprs.fi']); }
	if (!isset($confignxdngateway['General']['RptProtocol'])) { $confignxdngateway['General']['RptProtocol'] = "Icom"; }
        if (!isset($confignxdngateway['Log']['FilePath'])) { $confignxdngateway['Log']['FilePath'] = "/var/log/pi-star"; }
        if (!isset($confignxdngateway['Log']['FileRoot'])) { $confignxdngateway['Log']['FileRoot'] = "NXDNGateway"; }
	if (!isset($confignxdngateway['Log']['DisplayLevel'])) { $confignxdngateway['Log']['DisplayLevel'] = "0"; }
	if (!isset($confignxdngateway['Log']['FileLevel'])) { $confignxdngateway['Log']['FileLevel'] = "2"; }
	if (!isset($confignxdngateway['APRS']['Enable'])) { $$confignxdngateway['APRS']['Enable'] = $NXDNGatewayAPRS; }
	if (!isset($confignxdngateway['APRS']['Address'])) { $confignxdngateway['APRS']['Address'] = "127.0.0.1"; }
	if (!isset($confignxdngateway['APRS']['Port'])) { $confignxdngateway['APRS']['Port'] = "8673"; }
	if (!isset($confignxdngateway['APRS']['Suffix'])) { $confignxdngateway['APRS']['Suffix'] = "N"; }
	if (!isset($confignxdngateway['APRS']['Symbol'])) { $confignxdngateway['APRS']['Suffix'] = "\"$symbol\""; }
	if (isset($confignxdngateway['APRS']['Symbol'])) { $confignxdngateway['APRS']['Suffix'] = "\"$symbol\""; }
	if (!isset($confignxdngateway['APRS']['Description'])) { $confignxdngateway['APRS']['Description'] = "APRS for NXDNGateway"; }
	if (!isset($confignxdngateway['GPSD']['Enable'])) { $confignxdngateway['GPSD']['Enable'] = "0"; }
	if (!isset($confignxdngateway['GPSD']['Address'])) { $confignxdngateway['GPSD']['Address'] = "127.0.0.1"; }
	if (!isset($confignxdngateway['GPSD']['Port'])) { $confignxdngateway['GPSD']['Port'] = "2947"; }
	if (isset($confignxdngateway['Network']['Startup'])) {
		$confignxdngateway['Network']['Static'] = $confignxdngateway['Network']['Startup'];
		unset($confignxdngateway['Network']['Startup']);
	}

	// Migrate YSFGateway Config
	if (isset($configysfgateway['Network']['Startup'])) { $ysfTmpStartup = $configysfgateway['Network']['Startup']; }
	if (!isset($configysfgateway['aprs.fi']['Enable'])) { $configysfgateway['aprs.fi']['Enable'] = "1"; }
	//unset($configysfgateway['Network']);
	if (isset($ysfTmpStartup)) { $configysfgateway['Network']['Startup'] = $ysfTmpStartup; }
	$configysfgateway['Network']['InactivityTimeout'] = "1440";
	$configysfgateway['Network']['Revert'] = "1";
	$configysfgateway['Network']['Debug'] = "0";
	$configysfgateway['YSF Network']['Enable'] = "1";
	$configysfgateway['YSF Network']['Port'] = "42000";
	$configysfgateway['YSF Network']['Hosts'] = "/usr/local/etc/YSFHosts.txt";
	$configysfgateway['YSF Network']['ReloadTime'] = "60";
	$configysfgateway['YSF Network']['ParrotAddress'] = "127.0.0.1";
	$configysfgateway['YSF Network']['ParrotPort'] = "42012";
	$configysfgateway['YSF Network']['YSF2DMRAddress'] = "127.0.0.1";
	$configysfgateway['YSF Network']['YSF2DMRPort'] = "42013";
	$configysfgateway['YSF Network']['YSF2NXDNAddress'] = "127.0.0.1";
	$configysfgateway['YSF Network']['YSF2NXDNPort'] = "42014";
	$configysfgateway['YSF Network']['YSF2P25Address'] = "127.0.0.1";
	$configysfgateway['YSF Network']['YSF2P25Port'] = "42015";
	//$configysfgateway['FCS Network']['Enable'] = "1"; # Disabled per new 6/2023 toggle sw.
	$configysfgateway['FCS Network']['Port'] = "42001";
	$configysfgateway['FCS Network']['Rooms'] = "/usr/local/etc/FCSHosts.txt";
	$configysfgateway['Remote Commands']['Enable'] = "1";
	$configysfgateway['Remote Commands']['Port'] = "6073";
	if (!isset($configysfgateway['General']['Debug'])) { $configysfgateway['General']['Debug'] = "0"; }
	if (!isset($configysfgateway['GPSD']['Enable'])) { $configysfgateway['GPSD']['Enable'] = "0"; }
	if (!isset($configysfgateway['GPSD']['Address'])) { $configysfgateway['GPSD']['Address'] = "127.0.0.1"; }
	if (!isset($configysfgateway['GPSD']['Port'])) { $configysfgateway['GPSD']['Port'] = "2947"; }
	if (!isset($configysfgateway['APRS']['Enable'])) { $configysfgateway['APRS']['Enable'] = "0"; }
	if (!isset($configysfgateway['APRS']['Address'])) { $configysfgateway['APRS']['Address'] = "127.0.0.1"; }
	if (!isset($configysfgateway['APRS']['Port'])) { $configysfgateway['APRS']['Port'] = "8673"; }
	if (isset($configysfgateway['APRS']['Description'])) { $configysfgateway['APRS']['Description'] = "APRS for YSFGateway"; }
	if (!isset($configysfgateway['APRS']['Suffix'])) { $configysfgateway['APRS']['Suffix'] = "Y"; }
	if (!isset($configysfgateway['APRS']['Symbol'])) { $configysfgateway['APRS']['Symbol'] = "\"$symbol\""; }
	if (isset($configysfgateway['APRS']['Symbol'])) { $configysfgateway['APRS']['Symbol'] = "\"$symbol\""; }
	if (isset($configysfgateway['aprs.fi'])) { unset($configysfgateway['aprs.fi']); }
	if (isset($configysfgateway['APRS']['Enable'])) { $configysfgateway['APRS']['Enable'] = $YSFGatewayAPRS; }

	// Add the DAPNet Config
	if (!isset($configdapnetgw['General']['Callsign'])) { $configdapnetgw['General']['Callsign'] = "M1ABC"; }
	if (!isset($configdapnetgw['General']['RptAddress'])) { $configdapnetgw['General']['RptAddress'] = "127.0.0.1"; }
	if (!isset($configdapnetgw['General']['RptPort'])) { $configdapnetgw['General']['RptPort'] = "3800"; }
	if (!isset($configdapnetgw['General']['LocalAddress'])) { $configdapnetgw['General']['LocalAddress'] = "127.0.0.1"; }
	if (!isset($configdapnetgw['General']['LocalPort'])) { $configdapnetgw['General']['LocalPort'] = "4800"; }
	if (!isset($configdapnetgw['General']['Daemon'])) { $configdapnetgw['General']['Daemon'] = "0"; }
	if (isset($configdapnetgw['Log']['DisplayLevel'])) { $configdapnetgw['Log']['DisplayLevel'] = "0"; }
	if (isset($configdapnetgw['Log']['FileLevel'])) { $configdapnetgw['Log']['FileLevel'] = "2"; }
	if (!isset($configdapnetgw['Log']['FilePath'])) { $configdapnetgw['Log']['FilePath'] = "/var/log/pi-star"; }
	if (!isset($configdapnetgw['Log']['FileRoot'])) { $configdapnetgw['Log']['FileRoot'] = "DAPNETGateway"; }
	if (!isset($configdapnetgw['DAPNET']['Address'])) { $configdapnetgw['DAPNET']['Address'] = "dapnet.afu.rwth-aachen.de"; }
	if (!isset($configdapnetgw['DAPNET']['Port'])) { $configdapnetgw['DAPNET']['Port'] = "43434"; }
	if (!isset($configdapnetgw['DAPNET']['AuthKey'])) { $configdapnetgw['DAPNET']['AuthKey'] = "TOPSECRET"; }
	if (!isset($configdapnetgw['DAPNET']['SuppressTimeWhenBusy'])) { $configdapnetgw['DAPNET']['SuppressTimeWhenBusy'] = "1"; }
	if (!isset($configdapnetgw['DAPNET']['Debug'])) { $configdapnetgw['DAPNET']['Debug'] = "0"; }
	if (!isset($configmmdvm['POCSAG']['Enable'])) { $configmmdvm['POCSAG']['Enable'] = "0"; }
	if (!isset($configmmdvm['POCSAG']['Frequency'])) { $configmmdvm['POCSAG']['Frequency'] = "439987500"; }
	if (!isset($configmmdvm['POCSAG Network']['Enable'])) { $configmmdvm['POCSAG Network']['Enable'] = "0"; }
	if (!isset($configmmdvm['POCSAG Network']['LocalAddress'])) { $configmmdvm['POCSAG Network']['LocalAddress'] = "127.0.0.1"; }
	if (!isset($configmmdvm['POCSAG Network']['LocalPort'])) { $configmmdvm['POCSAG Network']['LocalPort'] = "3800"; }
	if (!isset($configmmdvm['POCSAG Network']['GatewayAddress'])) { $configmmdvm['POCSAG Network']['GatewayAddress'] = "127.0.0.1"; }
	if (!isset($configmmdvm['POCSAG Network']['GatewayPort'])) { $configmmdvm['POCSAG Network']['GatewayPort'] = "4800"; }
	if (!isset($configmmdvm['POCSAG Network']['ModeHang'])) { $configmmdvm['POCSAG Network']['ModeHang'] = "5"; }
	if (!isset($configmmdvm['POCSAG Network']['Debug'])) { $configmmdvm['POCSAG Network']['Debug'] = "0"; }

	// Handle APRSGateway configs for cients that support it.
	$configdmrgateway['APRS']['Enable'] = $DMRGatewayAPRS;
	$configysfgateway['APRS']['Enable'] = $YSFGatewayAPRS;
	$configdgidgateway['APRS']['Enable'] = $DGIdGatewayAPRS;
	$configm17gateway['APRS']['Enable'] = $M17GatewayAPRS;
	$confignxdngateway['APRS']['Enable'] = $NXDNGatewayAPRS;
	if (empty($_POST['IRCDDBGatewayAPRS']) != TRUE ) {
		system('sudo sed -i "/aprsEnabled=/c\\aprsEnabled=1" /etc/ircddbgateway');
	} else {
		system('sudo sed -i "/aprsEnabled=/c\\aprsEnabled=0" /etc/ircddbgateway');
	}

	// config file update notifier vars
	system('sudo sed -i "/ConfUpdReqd = /c\\ConfUpdReqd = '.$configUpdateRequired.'" /etc/pistar-release');

	// Create the hostfiles.nodextra file if required
	if (empty($_POST['confHostFilesNoDExtra']) != TRUE ) {
		if (escapeshellcmd($_POST['confHostFilesNoDExtra']) == 'ON' )  {
			if (!file_exists('/etc/hostfiles.nodextra')) { system('sudo touch /etc/hostfiles.nodextra'); }
		}
		if (escapeshellcmd($_POST['confHostFilesNoDExtra']) == 'OFF' )  {
			if (file_exists('/etc/hostfiles.nodextra')) { system('sudo rm -rf /etc/hostfiles.nodextra'); }
		}
	}

	// MMDVMHost config file wrangling
        //
	// Removes empty sections
 	if (!empty($configModem) && isset($configModem['BrandMeister']) && (count($configModem['BrandMeister'], COUNT_RECURSIVE) == 0))
	{
		unset($configModem['BrandMeister']);
	}
 	if (!empty($configModem) && isset($configModem['TGIF']) && (count($configModem['TGIF'], COUNT_RECURSIVE) == 0))
	{
		unset($configModem['TGIF']);
	}
	//
	if (empty($configp25gateway['Network']['Static']))
	{
		unset($configp25gateway['Network']['Static']);
	}
	if (empty($confignxdngateway['Network']['Static']))
	{
		unset($confignxdngateway['Network']['Static']);
	}
	if (isset($configmmdvm['DMR Network']['Options'])) {
		ensureOptionsIsQuoted($configmmdvm['DMR Network']['Options']);
	}
	if (isset($configysfgateway['Network']['Options'])) {
		ensureOptionsIsQuoted($configysfgateway['Network']['Options']);
	}
	if (isset($configdmrgateway['DMR Network 2']['Options'])) {
		ensureOptionsIsQuoted($configdmrgateway['DMR Network 2']['Options']);
	}
	if (isset($configdmrgateway['DMR Network 5']['Options'])) {
		ensureOptionsIsQuoted($configdmrgateway['DMR Network 5']['Options']);
	}
	if (isset($configysf2dmr['DMR Network']['Options'])) {
		ensureOptionsIsQuoted($configysf2dmr['DMR Network']['Options']);
	}
	if (isset($configmmdvm['Info']['Location'])) {
		ensureOptionsIsQuoted($configmmdvm['Info']['Location']);
	}
	if (isset($configmmdvm['Info']['Description'])) {
		ensureOptionsIsQuoted($configmmdvm['Info']['Description']);
	}

	$mmdvmContent = "";
	foreach($configmmdvm as $mmdvmSection=>$mmdvmValues) {
		// UnBreak special cases
		$mmdvmSection = str_replace("_", " ", $mmdvmSection);
		$mmdvmContent .= "[".$mmdvmSection."]\n";
                // append the values
                foreach($mmdvmValues as $mmdvmKey=>$mmdvmValue) {
			$mmdvmContent .= $mmdvmKey."=".$mmdvmValue."\n";
		}
		$mmdvmContent .= "\n";
	}

	if (!$handleMMDVMHostConfig = fopen('/tmp/bW1kdm1ob3N0DQo.tmp', 'w')) {
		return false;
	}
	if (!is_writable('/tmp/bW1kdm1ob3N0DQo.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
		$success = fwrite($handleMMDVMHostConfig, $mmdvmContent);
		fclose($handleMMDVMHostConfig);
		if (intval(exec('cat /tmp/bW1kdm1ob3N0DQo.tmp | wc -l')) > 140 ) {
        	// handle DMR Beacon modes
        		if ($DMRBeaconModeNet == "1") {
				system('sudo sed -i "/BeaconInterval=.*/d" /tmp/bW1kdm1ob3N0DQo.tmp');
			} else {
				if (!strpos(file_get_contents("/etc/mmdvmhost"),"BeaconInterval=") !== false) {
					system('sudo sed -i "/BeaconDuration=.*/i BeaconInterval=60" /tmp/bW1kdm1ob3N0DQo.tmp');                                                                             
				}
			}
			exec('sudo mv /tmp/bW1kdm1ob3N0DQo.tmp /etc/mmdvmhost');		// Move the file back
			exec('sudo chmod 644 /etc/mmdvmhost');					// Set the correct runtime permissions
			exec('sudo chown root:root /etc/mmdvmhost');				// Set the owner
			exec('sudo /usr/local/sbin/nextion-driver-helper');			// Run the Nextion driver helper based on selected MMDVMHost display type
		}
	}

        // ysfgateway config file wrangling
	$ysfgwContent = "";
        foreach($configysfgateway as $ysfgwSection=>$ysfgwValues) {
                // UnBreak special cases
                $ysfgwSection = str_replace("_", " ", $ysfgwSection);
                $ysfgwContent .= "[".$ysfgwSection."]\n";
                // append the values
                foreach($ysfgwValues as $ysfgwKey=>$ysfgwValue) {
                        $ysfgwContent .= $ysfgwKey."=".$ysfgwValue."\n";
                }
                $ysfgwContent .= "\n";
        }

        if (!$handleYSFGWconfig = fopen('/tmp/eXNmZ2F0ZXdheQ.tmp', 'w')) {
                return false;
        }

	if (!is_writable('/tmp/eXNmZ2F0ZXdheQ.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
	        $success = fwrite($handleYSFGWconfig, $ysfgwContent);
	        fclose($handleYSFGWconfig);
		if (intval(exec('cat /tmp/eXNmZ2F0ZXdheQ.tmp | wc -l')) > 35 ) {
			exec('sudo mv /tmp/eXNmZ2F0ZXdheQ.tmp /etc/ysfgateway');		// Move the file back
			exec('sudo chmod 644 /etc/ysfgateway');					// Set the correct runtime permissions
			exec('sudo chown root:root /etc/ysfgateway');				// Set the owner
		}
	}

	// NXDNGateway config file wrangling
	$nxdngwContent = "";
        foreach($confignxdngateway as $nxdngwSection=>$nxdngwValues) {
                // UnBreak special cases
                $nxdngwSection = str_replace("_", " ", $nxdngwSection);
                $nxdngwContent .= "[".$nxdngwSection."]\n";
                // append the values
                foreach($nxdngwValues as $nxdngwKey=>$nxdngwValue) {
                        $nxdngwContent .= $nxdngwKey."=".$nxdngwValue."\n";
                }
                $nxdngwContent .= "\n";
        }

        if (!$handleNXDNGWconfig = fopen('/tmp/kXKwkDKy793HF5.tmp', 'w')) {
                return false;
        }

	if (!is_writable('/tmp/kXKwkDKy793HF5.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
	        $success = fwrite($handleNXDNGWconfig, $nxdngwContent);
	        fclose($handleNXDNGWconfig);
		if ( (intval(exec('cat /tmp/kXKwkDKy793HF5.tmp | wc -l')) > 30 ) && (file_exists('/etc/nxdngateway')) ) {
			exec('sudo mv /tmp/kXKwkDKy793HF5.tmp /etc/nxdngateway');		// Move the file back
			exec('sudo chmod 644 /etc/nxdngateway');				// Set the correct runtime permissions
			exec('sudo chown root:root /etc/nxdngateway');				// Set the owner
		}
	}

	// M17Gateway config file wrangling
	$m17gwContent = "";
        foreach($configm17gateway as $m17gwSection=>$m17gwValues) {
                // UnBreak special cases
                $m17gwSection = str_replace("_", " ", $m17gwSection);
                $m17gwContent .= "[".$m17gwSection."]\n";
                // append the values
                foreach($m17gwValues as $m17gwKey=>$m17gwValue) {
                        $m17gwContent .= $m17gwKey."=".$m17gwValue."\n";
                }
                $m17gwContent .= "\n";
        }

        if (!$handleM17GWconfig = fopen('/tmp/Edr2FxEdr2FxEdr2Fx.tmp', 'w')) {
                return false;
        }
	if (!is_writable('/tmp/Edr2FxEdr2FxEdr2Fx.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
	        $success = fwrite($handleM17GWconfig, $m17gwContent);
	        fclose($handleM17GWconfig);
		if ( (intval(exec('cat /tmp/Edr2FxEdr2FxEdr2Fx.tmp | wc -l')) > 30 ) ) {
			exec('sudo mv /tmp/Edr2FxEdr2FxEdr2Fx.tmp /etc/m17gateway');		// Move the file back
			exec('sudo chmod 644 /etc/m17gateway');				// Set the correct runtime permissions
			exec('sudo chown root:root /etc/m17gateway');				// Set the owner
		}
	}

	// P25Gateway config file wrangling
	$p25gwContent = "";
        foreach($configp25gateway as $p25gwSection=>$p25gwValues) {
                // UnBreak special cases
                $p25gwSection = str_replace("_", " ", $p25gwSection);
                $p25gwContent .= "[".$p25gwSection."]\n";
                // append the values
                foreach($p25gwValues as $p25gwKey=>$p25gwValue) {
                        $p25gwContent .= $p25gwKey."=".$p25gwValue."\n";
                }
                $p25gwContent .= "\n";
        }

        if (!$handleP25GWconfig = fopen('/tmp/sJSySkheSgrelJX.tmp', 'w')) {
                return false;
        }

	if (!is_writable('/tmp/sJSySkheSgrelJX.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
	        $success = fwrite($handleP25GWconfig, $p25gwContent);
	        fclose($handleP25GWconfig);
		if ( (intval(exec('cat /tmp/sJSySkheSgrelJX.tmp | wc -l')) > 30 ) && (file_exists('/etc/p25gateway')) ) {
			exec('sudo mv /tmp/sJSySkheSgrelJX.tmp /etc/p25gateway');		// Move the file back
			exec('sudo chmod 644 /etc/p25gateway');					// Set the correct runtime permissions
			exec('sudo chown root:root /etc/p25gateway');				// Set the owner
		}
	}

        // ysf2dmr config file wrangling
        $ysf2dmrContent = "";
        foreach($configysf2dmr as $ysf2dmrSection=>$ysf2dmrValues) {
                // UnBreak special cases
                $ysf2dmrSection = str_replace("_", " ", $ysf2dmrSection);
                $ysf2dmrContent .= "[".$ysf2dmrSection."]\n";
                // append the values
                foreach($ysf2dmrValues as $ysf2dmrKey=>$ysf2dmrValue) {
                        $ysf2dmrContent .= $ysf2dmrKey."=".$ysf2dmrValue."\n";
                }
                $ysf2dmrContent .= "\n";
        }

        if (!$handleYSF2DMRconfig = fopen('/tmp/dsWGR34tHRrSFFGA.tmp', 'w')) {
                return false;
        }

        if (!is_writable('/tmp/dsWGR34tHRrSFFGA.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
        else {
                $success = fwrite($handleYSF2DMRconfig, $ysf2dmrContent);
                fclose($handleYSF2DMRconfig);
                if (intval(exec('cat /tmp/dsWGR34tHRrSFFGA.tmp | wc -l')) > 35 ) {
                        exec('sudo mv /tmp/dsWGR34tHRrSFFGA.tmp /etc/ysf2dmr');                 // Move the file back
                        exec('sudo chmod 644 /etc/ysf2dmr');                                    // Set the correct runtime permissions
                        exec('sudo chown root:root /etc/ysf2dmr');                              // Set the owner
                }
        }

	// ysf2nxdn config file wrangling
        $ysf2nxdnContent = "";
        foreach($configysf2nxdn as $ysf2nxdnSection=>$ysf2nxdnValues) {
                // UnBreak special cases
                $ysf2nxdnSection = str_replace("_", " ", $ysf2nxdnSection);
                $ysf2nxdnContent .= "[".$ysf2nxdnSection."]\n";
                // append the values
                foreach($ysf2nxdnValues as $ysf2nxdnKey=>$ysf2nxdnValue) {
                        $ysf2nxdnContent .= $ysf2nxdnKey."=".$ysf2nxdnValue."\n";
                }
                $ysf2nxdnContent .= "\n";
        }
        if (!$handleYSF2NXDNconfig = fopen('/tmp/dsWGR34tHRrSFFGb.tmp', 'w')) {
                return false;
        }
        if (!is_writable('/tmp/dsWGR34tHRrSFFGb.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
        else {
                $success = fwrite($handleYSF2NXDNconfig, $ysf2nxdnContent);
                fclose($handleYSF2NXDNconfig);
                if (intval(exec('cat /tmp/dsWGR34tHRrSFFGb.tmp | wc -l')) > 35 ) {
                        exec('sudo mv /tmp/dsWGR34tHRrSFFGb.tmp /etc/ysf2nxdn');                 // Move the file back
                        exec('sudo chmod 644 /etc/ysf2nxdn');                                    // Set the correct runtime permissions
                        exec('sudo chown root:root /etc/ysf2nxdn');                              // Set the owner
                }
        }

	// ysf2p25 config file wrangling
        $ysf2p25Content = "";
        foreach($configysf2p25 as $ysf2p25Section=>$ysf2p25Values) {
                // UnBreak special cases
                $ysf2p25Section = str_replace("_", " ", $ysf2p25Section);
                $ysf2p25Content .= "[".$ysf2p25Section."]\n";
                // append the values
                foreach($ysf2p25Values as $ysf2p25Key=>$ysf2p25Value) {
                        $ysf2p25Content .= $ysf2p25Key."=".$ysf2p25Value."\n";
                }
                $ysf2p25Content .= "\n";
        }
        if (!$handleYSF2P25config = fopen('/tmp/dsWGR34tHRrSFFGc.tmp', 'w')) {
                return false;
        }
        if (!is_writable('/tmp/dsWGR34tHRrSFFGc.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
        else {
                $success = fwrite($handleYSF2P25config, $ysf2p25Content);
                fclose($handleYSF2P25config);
                if (intval(exec('cat /tmp/dsWGR34tHRrSFFGc.tmp | wc -l')) > 25 ) {
                        exec('sudo mv /tmp/dsWGR34tHRrSFFGc.tmp /etc/ysf2p25');                 // Move the file back
                        exec('sudo chmod 644 /etc/ysf2p25');                                    // Set the correct runtime permissions
                        exec('sudo chown root:root /etc/ysf2p25');                              // Set the owner
                }
        }

	// dgidgateway config file wrangling
	if (isset($configdgidgateway)) {
		$dgidgatewayContent = "";
		foreach($configdgidgateway as $dgidgatewaySection=>$dgidgatewayValues) {
			// UnBreak special cases
			$dgidgatewaySection = str_replace("_", " ", $dgidgatewaySection);
			$dgidgatewayContent .= "[".$dgidgatewaySection."]\n";
			// append the values
			foreach($dgidgatewayValues as $dgidgatewayKey=>$dgidgatewayValue) {
				$dgidgatewayContent .= $dgidgatewayKey."=".$dgidgatewayValue."\n";
			}
			$dgidgatewayContent .= "\n";
		}
		if (!$handleDGIdGatewayConfig = fopen('/tmp/cu0G4tG3CA45Z9B.tmp', 'w')) {
			return false;
		}
		if (!is_writable('/tmp/cu0G4tG3CA45Z9B.tmp')) {
		  echo "<br />\n";
		  echo "<table>\n";
		  echo "<tr><th>ERROR</th></tr>\n";
		  echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
		  echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
		  echo "</table>\n";
		  unset($_POST);
		  echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
		  die();
		}
		else {
			$success = fwrite($handleDGIdGatewayConfig, $dgidgatewayContent);
			fclose($handleDGIdGatewayConfig);
			if (intval(exec('cat /tmp/cu0G4tG3CA45Z9B.tmp | wc -l')) > 25 ) {
				exec('sudo mv /tmp/cu0G4tG3CA45Z9B.tmp /etc/dgidgateway');		// Move the file back
				exec('sudo chmod 644 /etc/dgidgateway');				// Set the correct runtime permissions
				exec('sudo chown root:root /etc/dgidgateway');				// Set the owner
			}
		}
	}

	// dmr2ysf config file wrangling
        $dmr2ysfContent = "";
        foreach($configdmr2ysf as $dmr2ysfSection=>$dmr2ysfValues) {
                // UnBreak special cases
                $dmr2ysfSection = str_replace("_", " ", $dmr2ysfSection);
                $dmr2ysfContent .= "[".$dmr2ysfSection."]\n";
                // append the values
                foreach($dmr2ysfValues as $dmr2ysfKey=>$dmr2ysfValue) {
                        $dmr2ysfContent .= $dmr2ysfKey."=".$dmr2ysfValue."\n";
                }
                $dmr2ysfContent .= "\n";
        }
        if (!$handleDMR2YSFconfig = fopen('/tmp/dhJSgdy7755HGc.tmp', 'w')) {
                return false;
        }
        if (!is_writable('/tmp/dhJSgdy7755HGc.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
        else {
                $success = fwrite($handleDMR2YSFconfig, $dmr2ysfContent);
                fclose($handleDMR2YSFconfig);
                if (intval(exec('cat /tmp/dhJSgdy7755HGc.tmp | wc -l')) > 25 ) {
                        exec('sudo mv /tmp/dhJSgdy7755HGc.tmp /etc/dmr2ysf');		// Move the file back
                        exec('sudo chmod 644 /etc/dmr2ysf');				// Set the correct runtime permissions
                        exec('sudo chown root:root /etc/dmr2ysf');			// Set the owner
                }
        }

	// dmr2nxdn config file wrangling
        $dmr2nxdnContent = "";
        foreach($configdmr2nxdn as $dmr2nxdnSection=>$dmr2nxdnValues) {
                // UnBreak special cases
                $dmr2nxdnSection = str_replace("_", " ", $dmr2nxdnSection);
                $dmr2nxdnContent .= "[".$dmr2nxdnSection."]\n";
                // append the values
                foreach($dmr2nxdnValues as $dmr2nxdnKey=>$dmr2nxdnValue) {
                        $dmr2nxdnContent .= $dmr2nxdnKey."=".$dmr2nxdnValue."\n";
                }
                $dmr2nxdnContent .= "\n";
        }
        if (!$handleDMR2NXDNconfig = fopen('/tmp/nthfheS55HGc.tmp', 'w')) {
                return false;
        }
        if (!is_writable('/tmp/nthfheS55HGc.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
        else {
                $success = fwrite($handleDMR2NXDNconfig, $dmr2nxdnContent);
                fclose($handleDMR2NXDNconfig);
                if (intval(exec('cat /tmp/nthfheS55HGc.tmp | wc -l')) > 25 ) {
                        exec('sudo mv /tmp/nthfheS55HGc.tmp /etc/dmr2nxdn');		// Move the file back
                        exec('sudo chmod 644 /etc/dmr2nxdn');				// Set the correct runtime permissions
                        exec('sudo chown root:root /etc/dmr2nxdn');			// Set the owner
                }
        }

	// DAPNet Gateway Config file wragling
	$dapnetContent = "";
        foreach($configdapnetgw as $dapnetSection=>$dapnetValues) {
                // UnBreak special cases
                $dapnetSection = str_replace("_", " ", $dapnetSection);
                $dapnetContent .= "[".$dapnetSection."]\n";
                // append the values
                foreach($dapnetValues as $dapnetKey=>$dapnetValue) {
                        $dapnetContent .= $dapnetKey."=".$dapnetValue."\n";
                }
                $dapnetContent .= "\n";
        }
        if (!$handledapnetconfig = fopen('/tmp/lsHWie734HS.tmp', 'w')) {
                return false;
        }
        if (!is_writable('/tmp/lsHWie734HS.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
        else {
                $success = fwrite($handledapnetconfig, $dapnetContent);
                fclose($handledapnetconfig);
                if (intval(exec('cat /tmp/lsHWie734HS.tmp | wc -l')) > 19 ) {
                        exec('sudo mv /tmp/lsHWie734HS.tmp /etc/dapnetgateway');		// Move the file back
                        exec('sudo chmod 644 /etc/dapnetgateway');				// Set the correct runtime permissions
                        exec('sudo chown root:root /etc/dapnetgateway');			// Set the owner
                }
        }

	// dmrgateway config file wrangling
	$dmrgwContent = "";
        foreach($configdmrgateway as $dmrgwSection=>$dmrgwValues) {
                // UnBreak special cases
                $dmrgwSection = str_replace("_", " ", $dmrgwSection);
                $dmrgwContent .= "[".$dmrgwSection."]\n";
                // append the values
                foreach($dmrgwValues as $dmrgwKey=>$dmrgwValue) {
                        $dmrgwContent .= $dmrgwKey."=".$dmrgwValue."\n";
                }
                $dmrgwContent .= "\n";
        }
        if (!$handledmrGWconfig = fopen('/tmp/k4jhdd34jeFr8f.tmp', 'w')) {
                return false;
        }
	if (!is_writable('/tmp/k4jhdd34jeFr8f.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
	        $success = fwrite($handledmrGWconfig, $dmrgwContent);
	        fclose($handledmrGWconfig);
		if (fopen($dmrGatewayConfigFile,'r')) {
			if (intval(exec('cat /tmp/k4jhdd34jeFr8f.tmp | wc -l')) > 55 ) {
          			exec('sudo mv /tmp/k4jhdd34jeFr8f.tmp /etc/dmrgateway');	// Move the file back
          			exec('sudo chmod 644 /etc/dmrgateway');				// Set the correct runtime permissions
	 			exec('sudo chown root:root /etc/dmrgateway');			// Set the owner
			}
		}
	}

	// modem config file wrangling
        $configModemContent = "";
        foreach($configModem as $configModemSection=>$configModemValues) {
                // UnBreak special cases
                $configModemSection = str_replace("_", " ", $configModemSection);
                $configModemContent .= "[".$configModemSection."]\n";
                // append the values
                foreach($configModemValues as $modemKey=>$modemValue) {
			if ($modemKey == "Password") { $configModemContent .= $modemKey."=".'"'.str_replace('"', "", $modemValue).'"'."\n"; }
			else { $configModemContent .= $modemKey."=".$modemValue."\n"; }
                }
                $configModemContent .= "\n";
        }

        if (!$handleModemConfig = fopen('/tmp/sja7hFRkw4euG7.tmp', 'w')) {
                return false;
        }

        if (!is_writable('/tmp/sja7hFRkw4euG7.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
	else {
                $success = fwrite($handleModemConfig, $configModemContent);
                fclose($handleModemConfig);
		if (file_exists('/etc/dstar-radio.mmdvmhost')) {
                    if (fopen($modemConfigFileMMDVMHost,'r')) {
                        exec('sudo mv /tmp/sja7hFRkw4euG7.tmp '.$modemConfigFileMMDVMHost);		// Move the file back
                        exec('sudo chmod 644 $modemConfigFileMMDVMHost');				// Set the correct runtime permissions
                        exec('sudo chown root:root $modemConfigFileMMDVMHost');				// Set the owner
			// Vendor-specific hardware/disk images: mark as configured...
			exec('sudo sed -i "s/NewInstall=1/NewInstall=0/g" '.$modemConfigFileMMDVMHost);			// Vendor-specific HW configured
			exec("sudo sed -i 's/OnStartupSec=0/OnStartupSec=120/g' /lib/systemd/system/pistar-ap.timer");	// Vendor-specific HW configured
                    }
		}
        }

	// Setup the DV Mega Cast FW/display
	/*
	// Sample: https://wpsd-swd.w0chp.net/WPSD-SWD/DVMega-Cast/raw/branch/master/cast-factory-settings/settings.txt
	//
	//         * String: SETPE1ABC%GPE1ABC%ECQCQCQ%%PE1ABC%%CAST0204000009
	//         * Dict: SET PE1ABC%G PE1ABC%E CQCQCQ%% PE1ABC%% CAST 0 2040000 09
	//	           Set RPT1     RPT2     URCALL   CALL     INFO 0 DMRID   ESSID
	//
	//         * Every Block is 8 chars and string total is 49 bytes/chars.
	*/
	if (isDVmegaCast() == 1) {
	    $callsignCast = !empty($newCallsignUpper) ? $newCallsignUpper : 'PE1ABC';
	    $dmridCast = !empty($newPostDmrId) ? $newPostDmrId : '2040000';
	    $essidCast = !empty($_POST['bmExtendedId']) && $_POST['bmExtendedId'] !== 'None' ? $_POST['bmExtendedId'] : '00';
	    $modSuffixCast = !empty($_POST['confDStarModuleSuffix']) ? $_POST['confDStarModuleSuffix'] : 'E';
	    $callSuffixCast = !empty($_POST['confDStarCallSuffix']) ? $_POST['confDStarCallSuffix'] : '%%%%'; // no suffix

	    // Calculate rpt1 and rpt2 based on callsign
	    $rpt1Cast = str_replace(' ', '%', substr($callsignCast . '        ', 0, 7)) .  $modSuffixCast;	// always be 8 characters
	    $rpt2Cast = str_replace(' ', '%', substr($callsignCast . '        ', 0, 7)) . 'G';			// always be 8 characters

	    // Adjust callsign length by inserting % if needed
	    $callsignCast = str_pad($callsignCast, 8, '%'); // always 8 chars.

	    // Adjust callsign length by inserting % if needed
	    $callSuffixCast = str_pad($callSuffixCast, 4, '%'); // always 8 chars.

	    // Adjust dmrid length by inserting % if needed
	    $dmridCast = '0'.str_pad($dmridCast, 7, '%'); // always 7 chars.

	    // Edit the settings string
	    $castSettingsString = "SET{$rpt2Cast}{$rpt1Cast}CQCQCQ%%{$callsignCast}${callSuffixCast}{$dmridCast}{$essidCast}";

	    // Ensure the total length is always 49 characters
	    $castSettingsString = substr($castSettingsString, 0, 49);

	    $castSettingsString = str_replace(' ', '%', $castSettingsString); // Replace spaces with %
	    $castSettingsString .= PHP_EOL; // Add a line terminator

	    // Update the file
	    // perms 1st...
	    exec('sudo chmod 775 /usr/local/cast/etc ; sudo chown -R www-data:pi-star /usr/local/cast/etc ; sudo chmod 664 /usr/local/cast/etc/*');
	    $filePathCast = '/usr/local/cast/etc/settings.txt';
	    if (file_put_contents($filePathCast, $castSettingsString) !== false) {
		exec('sudo /usr/local/cast/sbin/RSET.sh  > /dev/null 2>&1 &');
	    }
	    // perms again
	    exec('sudo chmod 775 /usr/local/cast/etc ; sudo chown -R www-data:pi-star /usr/local/cast/etc ; sudo chmod 664 /usr/local/cast/etc/*');
	}

	// Set the system timezone
	if (empty($_POST['systemTimezone']) != TRUE ) {
		$rollTimeZone = 'sudo timedatectl set-timezone '.escapeshellcmd($_POST['systemTimezone']);
		system($rollTimeZone);
		$rollTimeZoneConfig = 'sudo sed -i "/date_default_timezone_set/c\\date_default_timezone_set(\''.escapeshellcmd($_POST['systemTimezone']).'\')\;" /var/www/dashboard/config/config.php';
		system($rollTimeZoneConfig);
	}

	// 12 or 24 hour time?
	if (empty($_POST['systemTimeFormat']) != TRUE ) {
		$rollTimeFormatConfig = 'sudo sed -i "/define(\'TIME_FORMAT\', /c\\\define(\'TIME_FORMAT\', \''.escapeshellcmd($_POST['systemTimeFormat']).'\')\;" /var/www/dashboard/config/config.php';
		system($rollTimeFormatConfig);
	}
    
        // auto-update check?
        if (empty($_POST['autoUpdateCheck']) != TRUE ) {
                $rollUpdateCheckConfig = 'sudo sed -i "/define(\'AUTO_UPDATE_CHECK\', /c\\\define(\'AUTO_UPDATE_CHECK\', \''.escapeshellcmd($_POST['autoUpdateCheck']).'\')\;" /var/www/dashboard/config/config.php';
                system($rollUpdateCheckConfig);
        }

	// Start all services
        if (isDVmegaCast() == 1) { // DVMega Cast mode logic
	    system($rollCastMode);
	}
	system('sudo wpsd-services restart > /dev/null 2>/dev/null &');

	unset($_POST);
	echo '<script type="text/javascript">window.location=window.location;</script>';

else:
	// Output the HTML Form here
	if (file_exists('/etc/dstar-radio.mmdvmhost') && !$configModem['Modem']['Hardware'] && $MYCALL != "M1ABC") { echo "<script type\"text/javascript\">\n\talert(\"NOTE:\\n\\nPlease (re-)select your modem from the 'Radio/Modem Type' drop-down list.\")\n</script>\n"; }
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
		$toggleDMRCheckboxCr			= 'onclick="toggleDMRCheckbox()"';
		$toggleDSTARCheckboxCr			= 'onclick="toggleDSTARCheckbox()"';
		$toggleYSFCheckboxCr			= 'onclick="toggleYSFCheckbox()"';
		$toggleP25CheckboxCr			= 'onclick="toggleP25Checkbox()"';
		$toggleNXDNCheckboxCr			= 'onclick="toggleNXDNCheckbox()"';
		$toggleM17CheckboxCr			= 'onclick="toggleM17Checkbox()"';
		$toggleYSF2DMRCheckboxCr		= 'onclick="toggleYSF2DMRCheckbox()"';
		$toggleYSF2NXDNCheckboxCr		= 'onclick="toggleYSF2NXDNCheckbox()"';
		$toggleYSF2P25CheckboxCr		= 'onclick="toggleYSF2P25Checkbox()"';
		$toggleDMR2YSFCheckboxCr		= 'onclick="toggleDMR2YSFCheckbox()"';
		$toggleDMR2NXDNCheckboxCr		= 'onclick="toggleDMR2NXDNCheckbox()"';
		$togglePOCSAGCheckboxCr			= 'onclick="togglePOCSAGCheckbox()"';
		$toggleAPRSGatewayCheckboxCr		= 'onclick="toggleAPRSGatewayCheckbox()"';
		$toggleGpsdCheckboxCr			= 'onclick="toggleGpsdCheckbox()"';
		$toggleDmrGatewayNet1EnCheckboxCr	= 'onclick="toggleDmrGatewayNet1EnCheckbox()"';
		$toggleDmrGatewayNet2EnCheckboxCr	= 'onclick="toggleDmrGatewayNet2EnCheckbox()"';
		$toggleDmrGatewayNet4EnCheckboxCr	= 'onclick="toggleDmrGatewayNet4EnCheckbox()"';
		$toggleDmrGatewayNet5EnCheckboxCr	= 'onclick="toggleDmrGatewayNet5EnCheckbox()"';
		$toggleDmrGatewayXlxEnCheckboxCr	= 'onclick="toggleDmrGatewayXlxEnCheckbox()"';
		$toggleDmrEmbeddedLCOnlyCr		= 'onclick="toggleDmrEmbeddedLCOnly()"';
		$toggleDmrDumpTADataCr			= 'onclick="toggleDmrDumpTAData()"';
		$toggleHostFilesYSFUpperCr		= 'onclick="toggleHostFilesYSFUpper()"';
		$toggleWiresXCommandPassthroughCr	= 'onclick="toggleWiresXCommandPassthrough()"';
		$toggleDstarTimeAnnounceCr		= 'onclick="toggleDstarTimeAnnounce()"';
		$toggleDstarDplusHostfilesCr		= 'onclick="toggleDstarDplusHostfiles()"';
		$toggleMobilegps_enableCr		= 'onclick="toggleMobilegps_enable()"';
		$toggleircddbEnabledCr			= 'onclick="toggleircddbEnabled()"';
		$toggleDmrBeaconCr			= 'onclick="toggleDmrBeacon()"';
	} else {
		$toggleDMRCheckboxCr			= "";
		$toggleDSTARCheckboxCr			= "";
		$toggleYSFCheckboxCr			= "";
		$toggleP25CheckboxCr			= "";
		$toggleNXDNCheckboxCr			= "";
		$toggleM17CheckboxCr			= "";
		$toggleYSF2DMRCheckboxCr		= "";
		$toggleYSF2NXDNCheckboxCr		= "";
		$toggleYSF2P25CheckboxCr		= "";
		$toggleDMR2YSFCheckboxCr		= "";
		$toggleDMR2NXDNCheckboxCr		= "";
		$togglePOCSAGCheckboxCr			= "";
		$toggleAPRSGatewayCheckboxCr		= "";
		$toggleGpsdCheckboxCr			= "";
		$toggleDmrGatewayNet1EnCheckboxCr	= "";
		$toggleDmrGatewayNet2EnCheckboxCr	= "";
		$toggleDmrGatewayNet4EnCheckboxCr	= "";
		$toggleDmrGatewayNet5EnCheckboxCr	= "";
		$toggleDmrGatewayXlxEnCheckboxCr	= "";
		$toggleDmrEmbeddedLCOnlyCr		= "";
		$toggleDmrDumpTADataCr			= "";
		$toggleHostFilesYSFUpperCr		= "";
		$toggleWiresXCommandPassthroughCr	= "";
		$toggleDstarTimeAnnounceCr		= "";
		$toggleDstarDplusHostfilesCr		= "";
		$toggleMobilegps_enableCr		= "";
		$toggleircddbEnabledCr			= "";
		$toggleDmrBeaconCr			= "";
	}
?>
<form id="factoryReset" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<input type="hidden" name="factoryReset" value="1" />
</form>

<?php
    echo '<form id="config" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">';
?>
    <input type="hidden" name="controllerSoft" value="MMDVM" />
    <h2 class="ConfSec"><?php echo __( 'General Configuration' );?></h2>
    <table>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Hostname:<span><b>System Hostname</b>This is the system hostname, used for access to the dashboard etc.</span></a></td>
    <td align="left" colspan="2" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><input type="text" name="confHostame" size="13" maxlength="15" value="<?php echo exec('cat /etc/hostname'); ?>" /></td>
    <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-exclamation-triangle"></i> Do not add suffixes such as ".local", etc. <strong>Note:</strong> A reboot is required for this change to take effect.</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Node Callsign' );?>:<span><b>Gateway Callsign</b>This is your licenced callsign for use on this gateway. Do not append any suffix.</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confCallsign" id="confCallsign" size="13" maxlength="7" value="<?php echo $configs['gatewayCallsign'] ?>" oninput="enforceValidCharsAndConvertToUpper(this)" /></td>
    <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-exclamation-triangle"></i> Do not add suffixes such as "-G"</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'DMR/CCS7 ID' );?>:<span><b>DMR/CCS7 ID</b>Enter your DMR / CCS7 ID here</span></a></td>
    <td align="left" colspan="2"><input type="text" name="dmrId" id="dmrId" size="13" maxlength="9" value="<?php if (isset($configmmdvm['General']['Id'])) { echo $configmmdvm['General']['Id']; } else { echo $configmmdvm['DMR']['Id']; } ?>" /></td>
    <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-info-circle"></i> Required for DMR Mode &amp; DMR Cross-Modes (If you don't have one, <a href="https://radioid.net/account/register" target="_new">get a DMR ID from RadioID.Net</a>)</td>
    </tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">NXDN ID:<span><b>NXDN ID</b>Enter your NXDN ID here</span></a></td>
      <td align="left" colspan="2"><input type="text" name="nxdnId" id="nxdnId" size="13" maxlength="5" value="<?php if (isset($configmmdvm['NXDN']['Id'])) { echo $configmmdvm['NXDN']['Id']; } ?>" /></td>
      <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-info-circle"></i> Required for NXDN Mode &amp; NXDN Cross-Modes (If you don't have one, <a href="https://radioid.net/account/register" target="_new">get an NXDN ID from RadioID.Net</a>)</td>
    </tr>
    <?php if (isDVmegaCast() == 0) { // Begin DVMega Cast logic... ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Radio Mode' );?>:<span><b>TRX Mode</b>Choose the mode type Simplex node or Duplex repeater.</span></a></td>
    <?php
        if ($configmmdvm['Info']['RXFrequency'] === $configmmdvm['Info']['TXFrequency']) {
                echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"trxMode\" value=\"SIMPLEX\" checked=\"checked\" /> Simplex  <input type=\"radio\" name=\"trxMode\" value=\"DUPLEX\" /> Duplex </td>\n";
                }
        else {
                echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"trxMode\" value=\"SIMPLEX\" /> Simplex  <input type=\"radio\" name=\"trxMode\" value=\"DUPLEX\" checked=\"checked\" /> Duplex</td>\n";
                }
    ?>
     <td align="left" colspan="2" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-question-circle"></i> Duplex mode requires Dual-Hat/Duplex Modems</td>
    </tr>
    <?php } else { // Case when isDVmegaCast() is equal to 1 ?>
    <input type="hidden" name="trxMode" value="SIMPLEX" />
    <?php } // end DVMega Cast logic ?>
    <?php if ($configModem['Modem']['Hardware'] !== 'dvmpicast') {   // Begin DVMega Cast logic...
    if ($configmmdvm['Info']['TXFrequency'] === $configmmdvm['Info']['RXFrequency']) {
	echo "    <tr>\n";
	echo "    <td align=\"left\"><a class=\"tooltip2\" href=\"#\">".__( 'Radio Frequency' ).":<span><b>Radio Frequency</b>This is the Frequency your<br />hotspot radio is on</span></a></td>\n";
	echo "    <td align=\"left\" colspan=\"3\"><input type=\"text\" id=\"confFREQ\" onkeyup=\"checkFrequency(); return false;\" name=\"confFREQ\" size=\"13\" maxlength=\"12\" value=\"".number_format($configmmdvm['Info']['RXFrequency'], 0, '.', '.')."\" /> MHz</td>\n";
	echo "    </tr>\n";
	}
	else {
	echo "    <tr>\n";
	echo "    <td align=\"left\"><a class=\"tooltip2\" href=\"#\">".__( 'Radio Frequency' )." RX:<span><b>Radio Frequency</b>This is the Frequency your<br />repeater will listen on</span></a></td>\n";
	echo "    <td align=\"left\" colspan=\"3\"><input type=\"text\" id=\"confFREQrx\" onkeyup=\"checkFrequency(); return false;\" name=\"confFREQrx\" size=\"13\" maxlength=\"12\" value=\"".number_format($configmmdvm['Info']['RXFrequency'], 0, '.', '.')."\" /> MHz</td>\n";
	echo "    </tr>\n";
	echo "    <tr>\n";
	echo "    <td align=\"left\"><a class=\"tooltip2\" href=\"#\">".__( 'Radio Frequency' )." TX:<span><b>Radio Frequency</b>This is the Frequency your<br />repeater will transmit on</span></a></td>\n";
	echo "    <td align=\"left\" colspan=\"3\"><input type=\"text\" id=\"confFREQtx\" onkeyup=\"checkFrequency(); return false;\" name=\"confFREQtx\" size=\"13\" maxlength=\"12\" value=\"".number_format($configmmdvm['Info']['TXFrequency'], 0, '.', '.')."\" /> MHz</td>\n";
	echo "    </tr>\n";
	}
    } else { // Case when isDVmegaCast() is equal to 1
    ?>
    <input type="hidden" name="confFREQ" value="431150000" />
    <?php } // end DVMega Cast logic ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Radio/Modem Type' );?>:<span><b>Radio/Modem</b>What kind of radio or modem hardware do you have?</span></a></td>
    <td align="left" colspan="3"><select name="confHardware" class="confHardware" onchange="setMmdvmPort(this.options[this.selectedIndex].value);">
		<?php if (isDVmegaCast() == 1) { // Begin DVMega Cast logic... ?>
                <option<?php if ($configModem['Modem']['Hardware'] === 'dvmpicast') {           echo ' selected="selected"';}?> value="dvmpicast">DV-Mega Cast Base Station Mode (Main Unit)</option>
                <option<?php if ($configModem['Modem']['Hardware'] === 'dvmpicasths') {         echo ' selected="selected"';}?> value="dvmpicasths">DV-Mega Cast Hotspot Mode - Single Band Board (70cm)</option>
                <option<?php if ($configModem['Modem']['Hardware'] === 'dvmpicasthd') {         echo ' selected="selected"';}?> value="dvmpicasthd">DV-Mega Cast Hotspot Mode - Dual-Band Board (2m/70cm)</option>
		<?php } else { ?>
		<option<?php if (!$configModem['Modem']['Hardware']) { echo ' selected="selected"';}?> value="">--</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvmpis') {		echo ' selected="selected"';}?> value="dvmpis">DV-Mega Raspberry Pi Hat (GPIO) - Single Band (70cm)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvmpid') {		echo ' selected="selected"';}?> value="dvmpid">DV-Mega Raspberry Pi Hat (GPIO) - Dual Band</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvmuadu') {		echo ' selected="selected"';}?> value="dvmuadu">DV-Mega on Arduino (USB - /dev/ttyUSB0) - Dual Band</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'dvmuada') {		echo ' selected="selected"';}?> value="dvmuada">DV-Mega on Arduino (USB - /dev/ttyACM0) - Dual Band</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvmuagmsku') {		echo ' selected="selected"';}?> value="dvmuagmsku">DV-Mega on Arduino (USB - /dev/ttyUSB0) - GMSK Modem</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvmuagmska') {		echo ' selected="selected"';}?> value="dvmuagmska">DV-Mega on Arduino (USB - /dev/ttyACM0) - GMSK Modem</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvmbss') {		echo ' selected="selected"';}?> value="dvmbss">DV-Mega on Bluestack (USB) - Single Band (70cm)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvmbsd') {		echo ' selected="selected"';}?> value="dvmbsd">DV-Mega on Bluestack (USB) - Dual Band</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'dvrptr1') {		echo ' selected="selected"';}?> value="dvrptr1">DV-RPTR V1 (USB)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvrptr2') {		echo ' selected="selected"';}?> value="dvrptr2">DV-RPTR V2 (USB)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'dvrptr3') {		echo ' selected="selected"';}?> value="dvrptr3">DV-RPTR V3 (USB)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'zum') {			echo ' selected="selected"';}?> value="zum">MMDVM / MMDVM_HS / Teensy / ZUM (USB)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'stm32dvm') {		echo ' selected="selected"';}?> value="stm32dvm">STM32-DVM / MMDVM_HS - Raspberry Pi Hat (GPIO)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'stm32dvmv3+') {		echo ' selected="selected"';}?> value="stm32dvmv3+">RB STM32-DVM (GPIO v3+)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'stm32usb') {		echo ' selected="selected"';}?> value="stm32usb">RB STM32-DVM (USB)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'stm32usbv3+') {		echo ' selected="selected"';}?> value="stm32usbv3+">RB STM32-DVM (USB v3+)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'stm32dvmmtr2kopi') {	echo ' selected="selected"';}?> value="stm32dvmmtr2kopi">RB STM32-DVM-MTR2k (GPIO v3+)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'zumspotlibre') {	echo ' selected="selected"';}?> value="zumspotlibre">ZUMspot - Libre (USB)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'zumspotusb') {		echo ' selected="selected"';}?> value="zumspotusb">ZUMspot - USB Stick</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'zumspotgpio') {		echo ' selected="selected"';}?> value="zumspotgpio">ZUMspot - Single Band Raspberry Pi Hat (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'zumspotdualgpio') {	echo ' selected="selected"';}?> value="zumspotdualgpio">ZUMspot - Dual Band Raspberry Pi Hat (GPIO)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'zumspotduplexgpio') {	echo ' selected="selected"';}?> value="zumspotduplexgpio">ZUMspot - Duplex Raspberry Pi Hat (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'zumradiopigpio') {	echo ' selected="selected"';}?> value="zumradiopigpio">ZUM Radio-MMDVM for Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'zumradiopiusb') {	echo ' selected="selected"';}?> value="zumradiopiusb">ZUM Radio-MMDVM-Nucleo (USB)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'f4mgpio') {		echo ' selected="selected"';}?> value="f4mgpio">MMDVM F4M-GPIO (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'f4mf7m') {		echo ' selected="selected"';}?> value="f4mf7m">MMDVM F4M/F7M (F0DEI) for USB</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmhsdualbandgpio') {	echo ' selected="selected"';}?> value="mmdvmhsdualbandgpio">MMDVM_HS_Dual_Band for Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmhshat') {		echo ' selected="selected"';}?> value="mmdvmhshat">MMDVM_HS_Hat (DB9MAT &amp; DF2ET) for Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmhsdualhatgpio') {	echo ' selected="selected"';}?> value="mmdvmhsdualhatgpio">MMDVM_HS_Dual_Hat (DB9MAT, DF2ET &amp; DO7EN) for Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmhsdualhatusb') {	echo ' selected="selected"';}?> value="mmdvmhsdualhatusb">MMDVM_HS_Dual_Hat (DB9MAT, DF2ET &amp; DO7EN) for Pi (USB)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmhshatambe') {	echo ' selected="selected"';}?> value="mmdvmhshatambe">MMDVM_HS_AMBE (D2RG HS_AMBE) for Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmrpthat') {		echo ' selected="selected"';}?> value="mmdvmrpthat">MMDVM_RPT_Hat (DB9MAT, DF2ET &amp; F0DEI) for Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmmdohat') {		echo ' selected="selected"';}?> value="mmdvmmdohat">MMDVM_HS_MDO Hat (BG3MDO) for Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmvyehat') {		echo ' selected="selected"';}?> value="mmdvmvyehat">MMDVM_HS_NPi Hat (VR2VYE) for Nano Pi (GPIO)</option>
	        <option<?php if ($configModem['Modem']['Hardware'] === 'mmdvmvyehatdual') {	echo ' selected="selected"';}?> value="mmdvmvyehatdual">MMDVM_HS_Hat_Dual Hat (VR2VYE) for Pi (GPIO)</option>
	    	<option<?php if ($configModem['Modem']['Hardware'] === 'lshshatgpio') {		echo ' selected="selected"';}?> value="lshshatgpio">LoneStar - MMDVM_HS_Hat for Pi (GPIO)</option>
	    	<option<?php if ($configModem['Modem']['Hardware'] === 'lshsdualhatgpio') {	echo ' selected="selected"';}?> value="lshsdualhatgpio">LoneStar - MMDVM_HS_Dual_Hat for Pi (GPIO)</option>
	    	<option<?php if ($configModem['Modem']['Hardware'] === 'lsusb') {		echo ' selected="selected"';}?> value="lsusb">LoneStar - USB Stick</option>
	    	<option<?php if ($configModem['Modem']['Hardware'] === 'sbhsdualbandgpio') {	echo ' selected="selected"';}?> value="sbhsdualbandgpio">SkyBridge - MMDVM_HS_Dual_Band for Pi (GPIO)</option>
	    	<option<?php if ($configModem['Modem']['Hardware'] === 'nanodv') {		echo ' selected="selected"';}?> value="nanodv">MMDVM_NANO_DV (BG4TGO) for NanoPi AIR (GPIO)</option>
	    	<option<?php if ($configModem['Modem']['Hardware'] === 'nanodvusb') {		echo ' selected="selected"';}?> value="nanodvusb">MMDVM_NANO_DV (BG4TGO) for NanoPi AIR (USB)</option>
		<option<?php if ($configModem['Modem']['Hardware'] === 'opengd77') {		echo ' selected="selected"';}?> value="opengd77">OpenGD77 DMR hotspot (USB)</option>
		<?php } // End DVMega Cast logic ?>
    </select></td>
    </tr>
    <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Modem Port' );?>:<span><b>Port</b>Which port is the modem connected to?</span></a></td>
	<td align="left" colspan="2"><input type="text" id="confPort" name="confPort" size="13" maxlength="12" value="<?php echo $configmmdvm['Modem']['UARTPort']; ?>"></td>
	<td align="left"><i class="fa fa-exclamation-circle"></i> <small>Typically there is no need to manually change/set this; for advanced settings/usage.</small></td>
    </tr>
<?php if (isDVmegaCast() == 0) {   // Begin DVMega Cast logic... ?>
	<tr id="modem_speed">
	    <td align="left"><a class="tooltip2" href="#">Modem Baud Rate:<span><b>Baudrate</b>Serial speed (most HATS use 115200)</span></a></td>
	    <td align="left" colspan="3"><select name="confHardwareSpeed">
		<?php 
		$modemSpeeds = [500000, 460800, 230400, 115200, 57600, 38400, 19200, 9600, 4800, 2400, 1200];
		foreach($modemSpeeds as $modemSpeed) {
		    if ($configmmdvm['Modem']['UARTSpeed'] == $modemSpeed) {
			echo " <option value=\"$modemSpeed\" selected=\"selected\">$modemSpeed</option>\n";
		    } else {
			if(in_array($modemSpeed, array("500000", "460800", "230400"))) { // little warning for the n00bz who may think their little HS_HAT can go above 115200 baud.
			    echo " <option value=\"$modemSpeed\">$modemSpeed (for select repeaters only!)</option>\n";
			} else {
			    echo " <option value=\"$modemSpeed\">$modemSpeed</option>\n";
			}
		    } 
		}
		?>
		</select>
	    </td>
	</tr>
<?php } // End DVMega Cast logic ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'System Time Zone' );?>:<span><b>System TimeZone</b>Set the system timezone</span></a></td>
    <td style="text-align: left;"><select name="systemTimezone" class="systemTimezone">
<?php
  exec('timedatectl list-timezones', $tzList);
  if (!in_array("UTC", $tzList)) { array_push($tzList, "UTC"); }
    // bookworm no longer relies on /etc/timezone, so we nees to parse the TZ name a bit diff...
    $lsbReleaseOutput = trim(shell_exec('lsb_release -rs | cut -d "." -f 1'));
    if ($lsbReleaseOutput > "11") {
        exec("ls -al /etc/localtime | awk -F'zoneinfo/' '{print $2}'", $tzCurrent);
    } else {
        exec('cat /etc/timezone', $tzCurrent);
    }
    foreach ($tzList as $timeZone) {
      if ($timeZone == $tzCurrent[0]) { echo "      <option selected=\"selected\" value=\"".$timeZone."\">".$timeZone."</option>\n"; }
      else { echo "      <option value=\"".$timeZone."\">".$timeZone."</option>\n"; }
    }
?>
    </select></td>
    <td align="left" colspan="2">Time Format: 
    <input type="radio" name="systemTimeFormat" value="24" <?php if (constant("TIME_FORMAT") == "24") {  echo 'checked="checked"'; } ?> />24 Hour
    <input type="radio" name="systemTimeFormat" value="12" <?php if (constant("TIME_FORMAT") == "12") { echo 'checked="checked"'; } ?> />12 Hour
    </tr>
<?php
    $lang_dir = './lang';
    if (is_dir($lang_dir)) {
	echo '    <tr>'."\n";
	echo '    <td align="left"><a class="tooltip2" href="#">'.__( 'Dashboard Language' ).':<span><b>Dashboard Language</b>Set the language for the dashboard.</span></a></td>'."\n";
	echo '    <td align="left" colspan="3"><select name="dashboardLanguage">'."\n";

	if ($dh = opendir($lang_dir)) {
	while ($files[] = readdir($dh))
		sort($files); // Add sorting for the Language(s)
		foreach ($files as $file){
			if (($file != 'index.php') && ($file != '.') && ($file != '..') && ($file != '')) {
				$file = substr($file, 0, -4);
				if ($file == $pistarLanguage) { echo "      <option selected=\"selected\" value=\"".$file."\">".$file."</option>\n"; }
				else { echo "      <option value=\"".$file."\">".$file."</option>\n"; }
			}
		}
		closedir($dh);
	}
	echo '    </select></td></tr>'."\n";
    }
?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Update Notifier:<span><b>Update Notifier</b>Enables/Disables automatic dashboard software update notifications.</span></a></td>
    <td colspan="2" align="left">
    <input type="radio" name="autoUpdateCheck" value="false" <?php if (constant("AUTO_UPDATE_CHECK") == "false") { echo 'checked="checked"'; } ?> />Disabled
    <input type="radio" name="autoUpdateCheck" value="true" <?php if (constant("AUTO_UPDATE_CHECK") == "true") { echo 'checked="checked"'; } ?> />Enabled
    </td>
    <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'>Enables / Disables automatic dashboard software update notifications.<br>When enabled, software update availability is displayed in the dashboard header.</td>
    </tr>
    </table>

    <br /><br />

    <h2 class="ConfSec">Node Location &amp; Info Settings</h2>
    <input type="hidden" name="APRSGatewayEnable" value="OFF" />
    <table>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Latitude' );?>:<span><b>Gateway Latitude</b>This is the latitude where the gateway is located (positive number for North, negative number for South) - Set to 0 to hide your hotspot location</span></a></td>
    <td align="left" colspan="3"><input type="text" id="confLatitude" name="confLatitude" size="13" maxlength="9" value="<?php echo $configs['latitude'] ?>" /> degrees (positive value for North, negative for South)</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Longitude' );?>:<span><b>Gateway Longitude</b>This is the longitude where the gateway is located (positive number for East, negative number for West) - Set to 0 to hide your hotspot location</span></a></td>
    <td align="left" colspan="3"><input type="text" id="confLongitude" name="confLongitude" size="13" maxlength="9" value="<?php echo $configs['longitude'] ?>" /> degrees (positive value for East, negative for West)</td>
    </tr>
    <tr>
    <td colspan="4" align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-info-circle"></i> Hint: You can use <a href="https://w0chp.radio/get-your-location-coords/" target="_new">this tool to try and calculate your location coordinates.</a></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Town' );?>:<span><b>Gateway City/State</b>The City/State where the gateway is located</span></a></td>
    <td align="left" colspan="3"><input type="text" name="confDesc1" size="30" maxlength="30" value="<?php echo $configs['description1'] ?>" /></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Country' );?>:<span><b>Gateway Country</b>The country where the gateway is located</span></a></td>
    <td align="left" colspan="3"><input type="text" name="confDesc2" size="30" maxlength="30" value="<?php echo $configs['description2'] ?>" /></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'URL' );?>:<span><b>URL</b>Your URL you'd like to be displayed in various networks/gateways, such as Brandmeister, DMR+, etc.<br><br>This does NOT affect your callsign link on the Dashboard page.</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confURL" size="45" maxlength="255" value="<?php echo $configs['url'] ?>" /></td>
    <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'>
    <input type="radio" name="urlAuto" value="auto"<?php if (strpos($configs['url'], 'www.qrz.com/db/'.$configmmdvm['General']['Callsign']) !== FALSE) {echo ' checked="checked"';} ?> />Auto
    <input type="radio" name="urlAuto" value="man"<?php if (strpos($configs['url'], 'www.qrz.com/db/'.$configmmdvm['General']['Callsign']) == FALSE) {echo ' checked="checked"';} ?> />Manual
    <br />
        <small>&nbsp;<i class="fa fa-question-circle"></i> Auto vs. Manual: Auto simply creates a URL to your QRZ.com callsign page. Manual allows you to specify your own custom URL/site.</small>
    </td>
    </tr>
<?php if (file_exists('/etc/aprsgateway')) {
    echo "<tr id='APRSgw'>\n";
    echo "<td align=\"left\"><a class=\"tooltip2\" href=\"#\">APRS Gateway:<span><b>APRS Gateway</b>Enabling this feature will make your location public on the APRS Network.</span></a></td>\n";
    if ( $configaprsgateway['Enabled']['Enabled'] == 1 ) {
        echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-aprsgateway\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"APRSGatewayEnable\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleAPRSGatewayCheckboxCr." onchange='toggleAPRSGatewayCheckbox()' /><label id=\"aria-toggle-aprsgateway\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable APRS Position Reporting\" aria-checked=\"true\" onKeyPress=\"toggleAPRSGatewayCheckbox()\" onclick=\"toggleAPRSGatewayCheckbox()\" for=\"toggle-aprsgateway\"><font style=\"font-size:0px\">Enable APRS Position Reporting</font></label></div></td>\n";
    } else {
        echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-aprsgateway\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"APRSGatewayEnable\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleAPRSGatewayCheckboxCr." onchange='toggleAPRSGatewayCheckbox()' /><label id=\"aria-toggle-aprsgateway\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable APRS Position Reporting\" aria-checked=\"false\" onKeyPress=\"toggleAPRSGatewayCheckbox()\" onclick=\"toggleAPRSGatewayCheckbox()\" for=\"toggle-aprsgateway\"><font style=\"font-size:0px\">Enable APRS Position Reporting</font></label></div></td>\n";
    }
} ?>
    <td align="left">APRS Host Pool:</a>
    <select name="selectedAPRSHost">
<?php
        $aprsHostFile = fopen("/usr/local/etc/APRSHosts.txt", "r");
        $aprsGatewayConfigFile = '/etc/aprsgateway';
        if (fopen($aprsGatewayConfigFile,'r')) { $configaprsgateway = parse_ini_file($aprsGatewayConfigFile, true); }
        $testAPRSHost = $configaprsgateway['APRS-IS']['Server'];
        while (!feof($aprsHostFile)) {
                $aprsHostFileLine = fgets($aprsHostFile);
                $aprsHost = preg_split('/:/', $aprsHostFileLine);
                if ((strpos($aprsHost[0], ';') === FALSE ) && ($aprsHost[0] != '')) {
                        if ($testAPRSHost == $aprsHost[0]) { echo "      <option value=\"$aprsHost[0]\" selected=\"selected\">$aprsHost[0]</option>\n"; }
                        else { echo "      <option value=\"$aprsHost[0]\">$aprsHost[0]</option>\n"; }
                }
        }
        fclose($aprsHostFile);
        ?>
    </select></td>
    <td style='word-wrap: break-word;white-space: normal;padding-left: 5px;' align="left">
      <div style="display:block;text-align:left;">
        <div style="display:block;">
          <div style="display:block;">
          <label style="display: inline-block;">Publish APRS Data for Mode(s):</label>
          <br>
            <div style="display: inline-block;vertical-align: middle;">
                <input name="DMRGatewayAPRS" id="aprsgw-service-selection-0" value="DMRGatewayAPRS" type="checkbox"
                <?php if($DMRGatewayAPRS == '1' && $configmmdvm['DMR Network']['Enable'] == "1") { echo(' checked="checked"'); }
                if ($configmmdvm['DMR Network']['Enable'] !== "1")  { echo(' disabled="disabled"'); }?> >
                <label for="aprsgw-service-selection-0">DMR</label>
            </div>
            <div style="display: inline-block;vertical-align: middle; margin-left:5px;">
                <input name="YSFGatewayAPRS" id="aprsgw-service-selection-1" value="YSFGatewayAPRS" type="checkbox"
                <?php if(($YSFGatewayAPRS == "1" && $configmmdvm['System Fusion Network']['Enable'] == "1") || ($YSFGatewayAPRS == "1" && $configdmr2ysf['Enabled']['Enabled'] == "1")) { echo(' checked="checked"'); }
                if ($configmmdvm['System Fusion Network']['Enable'] !== "1" && $configdmr2ysf['Enabled']['Enabled'] !== "1")  { echo(' disabled="disabled"'); }?> >
                <label for="aprsgw-service-selection-1">YSF</label>
            </div>
            <div style="display: inline-block;vertical-align: middle; margin-left:5px;">
                <input name="DGIdGatewayAPRS" id="aprsgw-service-selection-2" value="DGIdGatewayAPRS" type="checkbox"
                <?php if($DGIdGatewayAPRS == "1" && $configaprsgateway['Enabled']['Enabled'] == "1") { echo(' checked="checked"'); }
                if ($configdgidgateway['Enabled']['Enabled'] !== "1")  { echo(' disabled="disabled"'); }?> >
                <label for="aprsgw-service-selection-2">DGId</label>
            </div>
            <div style="display: inline-block;vertical-align: middle; margin-left:5px;">
                <input name="NXDNGatewayAPRS"  id="aprsgw-service-selection-3" value="NXDNGatewayAPRS" type="checkbox"
                <?php if($NXDNGatewayAPRS == "1" && $configmmdvm['NXDN Network']['Enable'] == "1") { echo(' checked="checked"'); }
                if ($configmmdvm['NXDN Network']['Enable'] !== "1")  { echo(' disabled="disabled"'); }?> >
                <label for="aprsgw-service-selection-3">NXDN</label>
            </div>
            <div style="display: inline-block;vertical-align: middle; margin-left:5px;">
                <input name="M17GatewayAPRS" id="aprsgw-service-selection-4" value="M17GatewayAPRS" type="checkbox"
                <?php if($M17GatewayAPRS == "1" && $configmmdvm['M17 Network']['Enable'] == "1") { echo(' checked="checked"'); }
                if ($configmmdvm['M17 Network']['Enable'] !== "1")  { echo(' disabled="disabled"'); }?> >
                <label for="aprsgw-service-selection-4">M17</label>
            </div>
            <div style="display: inline-block;vertical-align: middle; margin-left:5px;">
                <input name="IRCDDBGatewayAPRS" id="aprsgw-service-selection-5" value="IRCDDBGatewayAPRS" type="checkbox"
                <?php if($IRCDDBGatewayAPRS == "1" && $configs['ircddbEnabled'] == "1" && $configmmdvm['D-Star Network']['Enable'] == "1") { echo(' checked="checked"'); }
                if ($configs['ircddbEnabled'] !== "1" || $configmmdvm['D-Star Network']['Enable'] !== "1")  { echo(' disabled="disabled"'); }?> >
                <label for="aprsgw-service-selection-5">ircDDB (D-Star)</label>
            </div>
            <br /><em><small>(Note: Radio/MMDVM Mode must be enabled to select APRS mode publishing.)</small></em>
          </div>
        </div>
       </div>
       <hr />
    <div class="aprs-preview-container">
	<label for="symbol" style="padding-right:5px;">Select APRS Symbol:</label>
	<select id="symbol" name="symbol" onchange="updateSymbolPreview(this.value);">
	    <?php echo $sym_options; ?>
	</select>
        <div class="aprs-preview-text" style="display: none;">Preview:</div>
        <div class="aprs-symbol-preview" id="aprs-symbol-preview"></div>
    </div>
    </td>
    </tr>
    <tr>
    <td colspan="4" style='word-wrap: break-word;white-space: normal;padding-left: 5px;' align="left"><i class="fa fa-info-circle"></i> APRSGateway will use the location information (Lat./Lon.) you have entered above. However, If you have a GPS device connected and have enabled GPSd (below), it will use the GPS device location informaion.</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">GPSd:<span><b>GPS daemon support</b>Read NMEA data from a serially connected GPS unit and then to make that data available for other programs.</span></a></td>
    <input type="hidden" name="GPSD" value="OFF" />
    <?php
    if ( $configdmrgateway['GPSD']['Enable'] == 1 ) {
        echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-GPSD\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"GPSD\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleGpsdCheckboxCr." /><label id=\"aria-toggle-GPSD\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable GPS Position Reporting\" aria-checked=\"true\" onKeyPress=\"toggleGpsdCheckbox()\" onclick=\"toggleGpsdCheckbox()\" for=\"toggle-GPSD\"><font style=\"font-size:0px\">Enable GPSD Position Reporting</font></label></div></td>\n";
    } else {
        echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-GPSD\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"GPSD\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleGpsdCheckboxCr." /><label id=\"aria-toggle-GPSD\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable APRS Position Reporting\" aria-checked=\"false\" onKeyPress=\"toggleGpsdCheckbox()\" onclick=\"toggleGpsdCheckbox()\" for=\"toggle-GPSD\"><font style=\"font-size:0px\">Enable GPS Position Reporting</font></label></div></td>\n";
    }
    ?>
    </td>
    <td colspan="3" style='word-wrap: break-word;white-space: normal;padding-left: 5px;' align="left"><i class="fa fa-question-circle"></i> Enabling this option, allows an externally-connected GPS device to send your location information to APRS, vs. the location information (Lat./Lon.) you have entered above. This functionality requires that you also enable APRS Gateway (above).</td>
    </tr>
    </table>

    <br /><br />

<?php if (file_exists('/etc/dstar-radio.mmdvmhost')) { ?>
    <input type="hidden" name="MMDVMModeDMR" value="OFF" />
    <input type="hidden" name="MMDVMModeDSTAR" value="OFF" />
    <input type="hidden" name="MMDVMModeFUSION" value="OFF" />
    <input type="hidden" name="MMDVMModeP25" value="OFF" />
    <input type="hidden" name="MMDVMModeNXDN" value="OFF" />
    <input type="hidden" name="MMDVMModeM17" value="OFF" />
    <input type="hidden" name="MMDVMModeYSF2DMR" value="OFF" />
    <input type="hidden" name="MMDVMModeYSF2NXDN" value="OFF" />
    <input type="hidden" name="MMDVMModeYSF2P25" value="OFF" />
    <input type="hidden" name="MMDVMModeDMR2YSF" value="OFF" />
    <input type="hidden" name="MMDVMModeDMR2NXDN" value="OFF" />
    <input type="hidden" name="MMDVMModePOCSAG" value="OFF" />
    <h2 class="ConfSec"><?php echo __( 'Radio/MMDVMHost Modem Configuration' );?></h2>
    <table>
    <tr>
    <th class='config_head' colspan="4">Main Radio Modes</th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'D-Star Mode' );?>:<span><b>D-Star Mode</b>Turn on D-Star Features</span></a></td>
    <?php
	if ( $configmmdvm['D-Star']['Enable'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dstar\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDSTAR\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDSTARCheckboxCr." /><label id=\"aria-toggle-dstar\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DStar Mode\" aria-checked=\"true\" onKeyPress=\"toggleDSTARCheckbox()\" onclick=\"toggleDSTARCheckbox()\" for=\"toggle-dstar\"><font style=\"font-size:0px\">DStar Mode</font></label></div></td>\n";
	}
	else {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dstar\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDSTAR\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDSTARCheckboxCr." /><label id=\"aria-toggle-dstar\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DStar Mode\" aria-checked=\"false\" onKeyPress=\"toggleDSTARCheckbox()\" onclick=\"toggleDSTARCheckbox()\" for=\"toggle-dstar\"><font style=\"font-size:0px\">DStar Mode</font></label></div></td>\n";
	}
    ?>
    <td align="left">RF Hangtime: <input type="text" name="dstarRfHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['D-Star']['ModeHang'])) { echo $configmmdvm['D-Star']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="dstarNetHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['D-Star Network']['ModeHang'])) { echo $configmmdvm['D-Star Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'YSF Mode' );?>:<span><b>YSF Mode</b>Turn on YSF Features</span></a></td>
    <?php
	if ( $configmmdvm['System Fusion']['Enable'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeFUSION\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSFCheckboxCr." /><label id=\"aria-toggle-ysf\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F Mode\" aria-checked=\"true\" onKeyPress=\"toggleYSFCheckbox()\" onclick=\"toggleYSFCheckbox()\" for=\"toggle-ysf\"><font style=\"font-size:0px\">Y S F Mode</font></label></div></td>\n";
	}
	else {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeFUSION\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSFCheckboxCr." /><label id=\"aria-toggle-ysf\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSFCheckbox()\" onclick=\"toggleYSFCheckbox()\" for=\"toggle-ysf\"><font style=\"font-size:0px\">Y S F Mode</font></label></div></td>\n";
	}
    ?>
    <td align="left">RF Hangtime: <input type="text" name="ysfRfHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['System Fusion']['ModeHang'])) { echo $configmmdvm['System Fusion']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="ysfNetHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['System Fusion Network']['ModeHang'])) { echo $configmmdvm['System Fusion Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>

    <?php if (isDVmegaCast() == 0) { // Begin DVMega Cast logic... ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">M17 Mode:<span><b>M17 Mode</b>Turn on M17 Features</span></a></td>
    <?php
	if ( $configmmdvm['M17']['Enable'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-m17\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeM17\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleM17CheckboxCr." /><label id=\"aria-toggle-m17\" role=\"checkbox\" tabindex=\"0\" aria-label=\"M17 Mode\" aria-checked=\"true\" onKeyPress=\"toggleM17Checkbox()\" onclick=\"toggleM17Checkbox()\" for=\"toggle-m17\"><font style=\"font-size:0px\">M17 Mode</font></label></div></td>\n";
	}
	else {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-m17\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeM17\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleM17CheckboxCr." /><label id=\"aria-toggle-m17\" role=\"checkbox\" tabindex=\"0\" aria-label=\"M17 Mode\" aria-checked=\"false\" onKeyPress=\"toggleM17Checkbox()\" onclick=\"toggleM17Checkbox()\" for=\"toggle-m17\"><font style=\"font-size:0px\">M17 Mode</font></label></div></td>\n";
	}
    ?>
    <td align="left">RF Hangtime: <input type="text" name="m17RfHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['M17']['ModeHang'])) { echo $configmmdvm['M17']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="m17NetHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['M17 Network']['ModeHang'])) { echo $configmmdvm['M17 Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>

    <?php } // end DVMega Cast logic ?>

    <tr>
    <td align="left"><a class="tooltip2" href="#">DMR Mode:<span><b>DMR Mode</b>Turn on DMR Features</span></a></td>
    <?php
	if ( $configmmdvm['DMR']['Enable'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMRCheckboxCr." /><label id=\"aria-toggle-dmr\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR Mode\" aria-checked=\"true\" onKeyPress=\"toggleDMRCheckbox()\" onclick=\"toggleDMRCheckbox()\" for=\"toggle-dmr\"><font style=\"font-size:0px\">DMR Mode</font></label></div></td>\n";
	}
	else {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMRCheckboxCr." /><label id=\"aria-toggle-dmr\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR Mode\" aria-checked=\"false\" onKeyPress=\"toggleDMRCheckbox()\" onclick=\"toggleDMRCheckbox()\" for=\"toggle-dmr\"><font style=\"font-size:0px\">DMR Mode</font></label></div></td>\n";
	}
    ?>
    <td align="left">RF Hangtime: <input type="text" name="dmrRfHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['DMR']['ModeHang'])) { echo $configmmdvm['DMR']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="dmrNetHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['DMR Network']['ModeHang'])) { echo $configmmdvm['DMR Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>

    <?php if (isDVmegaCast() == 0) { // Begin DVMega Cast logic... ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'P25 Mode' );?>:<span><b>P25 Mode</b>Turn on P25 Features</span></a></td>
    <?php
	if ( $configmmdvm['P25']['Enable'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeP25\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleP25CheckboxCr." /><label id=\"aria-toggle-p25\" role=\"checkbox\" tabindex=\"0\" aria-label=\"P 25 Mode\" aria-checked=\"true\" onKeyPress=\"toggleP25Checkbox()\" onclick=\"toggleP25Checkbox()\" for=\"toggle-p25\"><font style=\"font-size:0px\">P 25 Mode</font></label></div></td>\n";
	}
	else {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeP25\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleP25CheckboxCr." /><label id=\"aria-toggle-p25\" role=\"checkbox\" tabindex=\"0\" aria-label=\"P 25 Mode\" aria-checked=\"false\" onKeyPress=\"toggleP25Checkbox()\" onclick=\"toggleP25Checkbox()\" for=\"toggle-p25\"><font style=\"font-size:0px\">P 25 Mode</font></label></div></td>\n";
	}
    ?>
    <td align="left">RF Hangtime: <input type="text" name="p25RfHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['P25']['ModeHang'])) { echo $configmmdvm['P25']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="p25NetHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['P25 Network']['ModeHang'])) { echo $configmmdvm['P25 Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'NXDN Mode' );?>:<span><b>NXDN Mode</b>Turn on NXDN Features</span></a></td>
    <?php
	if ( $configmmdvm['NXDN']['Enable'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeNXDN\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleNXDNCheckboxCr." /><label id=\"aria-toggle-nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"NXDN Mode\" aria-checked=\"true\" onKeyPress=\"toggleNXDNCheckbox()\" onclick=\"toggleNXDNCheckbox()\" for=\"toggle-nxdn\"><font style=\"font-size:0px\">NXDN Mode</font></label></div></td>\n";
	}
	else {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeNXDN\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleNXDNCheckboxCr." /><label id=\"aria-toggle-nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"NXDN Mode\" aria-checked=\"false\" onKeyPress=\"toggleNXDNCheckbox()\" onclick=\"toggleNXDNCheckbox()\" for=\"toggle-nxdn\"><font style=\"font-size:0px\">NXDN Mode</font></label></div></td>\n";
	}
    ?>
    <td align="left">RF Hangtime: <input type="text" name="nxdnRfHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['NXDN']['ModeHang'])) { echo $configmmdvm['NXDN']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="nxdnNetHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['NXDN Network']['ModeHang'])) { echo $configmmdvm['NXDN Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>

    <?php if (file_exists('/etc/dapnetgateway')) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">POCSAG Mode:<span><b>POCSAG Mode</b>Turn on POCSAG Features</span></a></td>
    <?php
	if ( $configmmdvm['POCSAG']['Enable'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-pocsag\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModePOCSAG\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$togglePOCSAGCheckboxCr." /><label id=\"aria-toggle-pocsag\" role=\"checkbox\" tabindex=\"0\" aria-label=\"POCSAG Mode\" aria-checked=\"true\" onKeyPress=\"togglePOCSAGCheckbox()\" onclick=\"togglePOCSAGCheckbox()\" for=\"toggle-pocsag\"><font style=\"font-size:0px\">POCSAG Mode</font></label></div></td>\n";
	}
	else {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-pocsag\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModePOCSAG\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$togglePOCSAGCheckboxCr." /><label id=\"aria-toggle-pocsag\" role=\"checkbox\" tabindex=\"0\" aria-label=\"POCSAG Mode\" aria-checked=\"false\" onKeyPress=\"togglePOCSAGCheckbox()\" onclick=\"togglePOCSAGCheckbox()\" for=\"toggle-pocsag\"><font style=\"font-size:0px\">POCSAG Mode</font></label></div></td>\n";
	}
    ?>
    <td align="left">POCSAG Mode Hangtime: <input type="text" name="POCSAGHangTime" size="7" maxlength="3" value="<?php if (isset($configmmdvm['POCSAG Network']['ModeHang'])) { echo $configmmdvm['POCSAG Network']['ModeHang']; } else { echo "5"; } ?>"></td>
    </tr>
    <?php } 
      } // end DVMega Cast logic.
    ?>
    <tr>
    <th class='config_head' colspan="4">Radio Cross-Modes</th>
    </tr>
    <tr colspan="2">
    <td align="left"><a class="tooltip2" href="#">YSF2DMR:<span><b>YSF2DMR Mode</b>Turn on YSF2DMR Features</span></a></td>
    <?php
	if ($configdgidgateway['Enabled']['Enabled'] == 1) {
	    echo "<td colspan=\"1\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2DMR\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2DMRCheckboxCr." disabled=\"disabled\"/><label id=\"aria-toggle-ysf2dmr\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 DMR Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2DMRCheckbox()\" onclick=\"toggleYSF2DMRCheckbox()\" for=\"toggle-ysf2dmr\"><font style=\"font-size:0px\">Y S F 2 DMR Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: YSF2DMR cannot be enabled in conjunction with DGIdGateway</em></td>\n";
	} else if
	    ($configmmdvm['System Fusion']['Enable'] != 1) {
	    echo "<td colspan=\"1\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2DMR\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2DMRCheckboxCr." disabled=\"disabled\"/><label id=\"aria-toggle-ysf2dmr\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 DMR Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2DMRCheckbox()\" onclick=\"toggleYSF2DMRCheckbox()\" for=\"toggle-ysf2dmr\"><font style=\"font-size:0px\">Y S F 2 DMR Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: YSF Mode must be enabled &amp; applied first.</em></td>\n";
	} else {
	    if ( $configysf2dmr['Enabled']['Enabled'] == 1 ) {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2DMR\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2DMRCheckboxCr." /><label id=\"aria-toggle-ysf2dmr\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 DMR Mode\" aria-checked=\"true\" onKeyPress=\"toggleYSF2DMRCheckbox()\" onclick=\"toggleYSF2DMRCheckbox()\" for=\"toggle-ysf2dmr\"><font style=\"font-size:0px\">Y S F 2 DMR Mode</font></label></div></td>\n";
	    } else {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2DMR\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2DMRCheckboxCr." /><label id=\"aria-toggle-ysf2dmr\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 DMR Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2DMRCheckbox()\" onclick=\"toggleYSF2DMRCheckbox()\" for=\"toggle-ysf2dmr\"><font style=\"font-size:0px\">Y S F 2 DMR Mode</font></label></div></td>\n";
	    }
	}
    ?>
    </tr>
    <?php if (file_exists('/etc/ysf2nxdn')) { ?>
    <tr colspan="2">
    <td align="left"><a class="tooltip2" href="#">YSF2NXDN:<span><b>YSF2NXDN Mode</b>Turn on YSF2NXDN Features</span></a></td>
    <?php
	if ($configdgidgateway['Enabled']['Enabled'] == 1) {
	    echo "<td colspan=\"1\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2NXDN\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2NXDNCheckboxCr." disabled=\"disabled\" /><label id=\"aria-toggle-ysf2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 NXDN Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2NXDNCheckbox()\" onclick=\"toggleYSF2NXDNCheckbox()\" for=\"toggle-ysf2nxdn\"><font style=\"font-size:0px\">Y S F 2 NXDN Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: YSF2NXDN cannot be enabled in conjunction with DGIdGateway</em></td>\n";
	} else if
	    ($configmmdvm['System Fusion']['Enable'] != 1) {
	    echo "<td colspan=\"1\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2NXDN\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2NXDNCheckboxCr." disabled=\"disabled\" /><label id=\"aria-toggle-ysf2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 NXDN Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2NXDNCheckbox()\" onclick=\"toggleYSF2NXDNCheckbox()\" for=\"toggle-ysf2nxdn\"><font style=\"font-size:0px\">Y S F 2 NXDN Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: YSF Mode must be enabled &amp; applied first.</em></td>\n";
	} else {
	    if ( $configysf2nxdn['Enabled']['Enabled'] == 1 ) {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2NXDN\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2NXDNCheckboxCr." /><label id=\"aria-toggle-ysf2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 NXDN Mode\" aria-checked=\"true\" onKeyPress=\"toggleYSF2NXDNCheckbox()\" onclick=\"toggleYSF2NXDNCheckbox()\" for=\"toggle-ysf2nxdn\"><font style=\"font-size:0px\">Y S F 2 NXDN Mode</font></label></div></td>\n";
	    } else {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2NXDN\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2NXDNCheckboxCr." /><label id=\"aria-toggle-ysf2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 NXDN Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2NXDNCheckbox()\" onclick=\"toggleYSF2NXDNCheckbox()\" for=\"toggle-ysf2nxdn\"><font style=\"font-size:0px\">Y S F 2 NXDN Mode</font></label></div></td>\n";
	    }
	}
    ?>
    </tr>
    <?php } ?>
    <?php if (file_exists('/etc/ysf2p25')) { ?>
    <tr colspan="2">
    <td align="left"><a class="tooltip2" href="#">YSF2P25:<span><b>YSF2P25 Mode</b>Turn on YSF2P25 Features</span></a></td>
    <?php
	if ($configdgidgateway['Enabled']['Enabled'] == 1) {
	    echo "<td colspan=\"1\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2P25\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2P25CheckboxCr." disabled=\"disabled\"/><label id=\"aria-toggle-ysf2p25\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 P 25 Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2P25Checkbox()\" onclick=\"toggleYSF2P25Checkbox()\" for=\"toggle-ysf2p25\"><font style=\"font-size:0px\">Y S F 2 P 25 Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: YSF2P25 cannot be enabled in conjunction with DGIdGateway</em></td>\n";
        } else if
            ($configmmdvm['System Fusion']['Enable'] != 1) {
	    echo "<td colspan=\"1\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2P25\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2P25CheckboxCr." disabled=\"disabled\"/><label id=\"aria-toggle-ysf2p25\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 P 25 Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2P25Checkbox()\" onclick=\"toggleYSF2P25Checkbox()\" for=\"toggle-ysf2p25\"><font style=\"font-size:0px\">Y S F 2 P 25 Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: YSF Mode must be enabled &amp; applied first.</em></td>\n";
	} else {
	    if ( $configysf2p25['Enabled']['Enabled'] == 1 ) {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2P25\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2P25CheckboxCr." /><label id=\"aria-toggle-ysf2p25\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 P 25 Mode\" aria-checked=\"true\" onKeyPress=\"toggleYSF2P25Checkbox()\" onclick=\"toggleYSF2P25Checkbox()\" for=\"toggle-ysf2p25\"><font style=\"font-size:0px\">Y S F 2 P 25 Mode</font></label></div></td>\n";
	    } else {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2P25\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleYSF2P25CheckboxCr." /><label id=\"aria-toggle-ysf2p25\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Y S F 2 P 25 Mode\" aria-checked=\"false\" onKeyPress=\"toggleYSF2P25Checkbox()\" onclick=\"toggleYSF2P25Checkbox()\" for=\"toggle-ysf2p25\"><font style=\"font-size:0px\">Y S F 2 P 25 Mode</font></label></div></td>\n";
	    }
	}
    ?>
    </tr>
    <?php } ?>
    <?php if (file_exists('/etc/dmr2ysf')) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">DMR2YSF:<span><b>DMR2YSF Mode</b>Turn on DMR2YSF Features</span></a></td>
    <?php
	if ($configmmdvm['DMR']['Enable'] != 1) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2YSF\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2YSFCheckboxCr." disabled='disabled' onchange=\"toggleDMR2YSFCheckbox()\" /><label id=\"aria-toggle-dmr2ysf\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 Y S F Mode\" aria-checked=\"true\" onKeyPress=\"toggleDMR2YSFCheckbox()\" onclick=\"toggleDMR2YSFCheckbox()\" for=\"toggle-dmr2ysf\"><font style=\"font-size:0px\">DMR 2 Y S F Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: DMR Mode must be enabled &amp; applied first.</em></td>\n";
	} else if ( $configdmr2nxdn['Enabled']['Enabled'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2YSF\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2YSFCheckboxCr." disabled='disabled' onchange=\"toggleDMR2YSFCheckbox()\" /><label id=\"aria-toggle-dmr2ysf\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 Y S F Mode\" aria-checked=\"true\" onKeyPress=\"toggleDMR2YSFCheckbox()\" onclick=\"toggleDMR2YSFCheckbox()\" for=\"toggle-dmr2ysf\"><font style=\"font-size:0px\">DMR 2 Y S F Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: Cannot be enabled in conjunction with DMR2NXDN.</em></td>\n";
	} else {
	    if ( $configdmr2ysf['Enabled']['Enabled'] == 1 ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2YSF\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2YSFCheckboxCr." onchange=\"toggleDMR2YSFCheckbox()\" /><label id=\"aria-toggle-dmr2ysf\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 Y S F Mode\" aria-checked=\"true\" onKeyPress=\"toggleDMR2YSFCheckbox()\" onclick=\"toggleDMR2YSFCheckbox()\" for=\"toggle-dmr2ysf\"><font style=\"font-size:0px\">DMR 2 Y S F Mode</font></label></div></td>\n";
		echo '<td align="left" style="word-wrap: break-word;white-space: normal"><i class="fa fa-exclamation-circle"></i> Uses "7" talkgroup prefix in DMR. <em>Note: Cannot be enabled in conjunction with DMR2NXDN.</em></td>';
	    }
	    else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2YSF\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2YSFCheckboxCr." onchange=\"toggleDMR2YSFCheckbox()\" /><label id=\"aria-toggle-dmr2ysf\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 Y S F Mode\" aria-checked=\"false\" onKeyPress=\"toggleDMR2YSFCheckbox()\" onclick=\"toggleDMR2YSFCheckbox()\" for=\"toggle-dmr2ysf\"><font style=\"font-size:0px\">DMR 2 Y S F Mode</font></label></div></td>\n";
		echo '<td align="left" style="word-wrap: break-word;white-space: normal"><i class="fa fa-exclamation-circle"></i> Uses "7" talkgroup prefix in DMR. <em>Note: Cannot be enabled in conjunction with DMR2NXDN.</em></td>';
	    }
	}
    ?>
    </tr>
    <?php } ?>
    <?php if (file_exists('/etc/dmr2nxdn')) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">DMR2NXDN:<span><b>DMR2NXDN Mode</b>Turn on DMR2NXDN Features</span></a></td>
    <?php
	if ($configmmdvm['DMR']['Enable'] != 1) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2NXDN\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2NXDNCheckboxCr." disabled='disabled' onchange=\"toggleDMR2NXDNCheckbox()\" /><label id=\"aria-toggle-dmr2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 NXDN Mode\" aria-checked=\"true\" onKeyPress=\"toggleDMR2NXDNCheckbox()\" onclick=\"toggleDMR2NXDNCheckbox()\" for=\"toggle-dmr2nxdn\"><font style=\"font-size:0px\">DMR 2 NXDN Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: DMR Mode must be enabled &amp; applied first.</em></td>\n";
	} else if ( $configdmr2ysf['Enabled']['Enabled'] == 1 ) {
	    echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2NXDN\" value=\"OFF\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2NXDNCheckboxCr." disabled='disabled' onchange=\"toggleDMR2NXDNCheckbox()\" /><label id=\"aria-toggle-dmr2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 NXDN Mode\" aria-checked=\"true\" onKeyPress=\"toggleDMR2NXDNCheckbox()\" onclick=\"toggleDMR2NXDNCheckbox()\" for=\"toggle-dmr2nxdn\"><font style=\"font-size:0px\">DMR 2 NXDN Mode</font></label></div></td>\n";
	    echo "<td align='left'><em>Note: Cannot be enabled in conjunction with DMR2YSF.</em></td>\n";
	} else {
	    if ( $configdmr2nxdn['Enabled']['Enabled'] == 1 ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2NXDN\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2NXDNCheckboxCr." onchange=\"toggleDMR2NXDNCheckbox()\" /><label id=\"aria-toggle-dmr2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 NXDN Mode\" aria-checked=\"true\" onKeyPress=\"toggleDMR2NXDNCheckbox()\" onclick=\"toggleDMR2NXDNCheckbox()\" for=\"toggle-dmr2nxdn\"><font style=\"font-size:0px\">DMR 2 NXDN Mode</font></label></div></td>\n";
		echo '<td align="left" style="word-wrap: break-word;white-space: normal"><i class="fa fa-exclamation-circle"></i> Uses "7" talkgroup prefix in DMR. <em>Note: Cannot be enabled in conjunction with DMR2YSF.</em></td>';
	    }
	    else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr2nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR2NXDN\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDMR2NXDNCheckboxCr." onchange=\"toggleDMR2NXDNCheckbox()\" /><label id=\"aria-toggle-dmr2nxdn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR 2 NXDN Mode\" aria-checked=\"false\" onKeyPress=\"toggleDMR2NXDNCheckbox()\" onclick=\"toggleDMR2NXDNCheckbox()\" for=\"toggle-dmr2nxdn\"><font style=\"font-size:0px\">DMR 2 NXDN Mode</font></label></div></td>\n";
		echo '<td align="left" style="word-wrap: break-word;white-space: normal"><i class="fa fa-exclamation-circle"></i> Uses "7" talkgroup prefix in DMR. <em>Note: Cannot be enabled in conjunction with DMR2YSF.</em></td>';
	    }
	}
    ?>
    </tr>
    <?php } ?>

    </table>

    <br /><br />

    <h2 class="ConfSec">MMDVMHost/Modem Display Configuration</h2>
    <input type="hidden" name="oledScreenSaverEnable" value="ON" />
    <input type="hidden" name="oledScrollEnable" value="OFF" />
    <input type="hidden" name="oledRotateEnable" value="OFF" />
    <input type="hidden" name="oledInvertEnable" value="OFF" />
    <table>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'MMDVM Display Type' );?>:<span><b>Display Type</b>Choose your display type, if you have one.</span></a></td>
    <td align="left" colspan="2">
	<?php if (isDVmegaCast() == 1) { // Begin DVMega Cast logic... ?>
	    <input type="hidden" name="mmdvmDisplayType" value="CAST" />
	    <div>DVMega-CAST Built-In Display <small>(Cannot be changed)</small></div>
	<?php } else { ?>
	<select name="mmdvmDisplayType">
	    <option <?php if (($configmmdvm['General']['Display'] == "None") || ($configmmdvm['General']['Display'] == "") ) {echo 'selected="selected" ';}; ?>value="None">None</option>
	    <option <?php if (($configmmdvm['General']['Display'] == "OLED") && ($configmmdvm['OLED']['Type'] == "3")) {echo 'selected="selected" ';}; ?>value="OLED3">OLED Type 3 (0.96" screen)</option>
	    <option <?php if (($configmmdvm['General']['Display'] == "OLED") && ($configmmdvm['OLED']['Type'] == "6")) {echo 'selected="selected" ';}; ?>value="OLED6">OLED Type 6 (1.3" screen)</option>
	    <option <?php if ($configmmdvm['General']['Display'] == "Nextion") {echo 'selected="selected" ';}; ?>value="Nextion">Nextion</option>
	    <option <?php if ($configmmdvm['General']['Display'] == "NextionDriver") {echo 'selected="selected" ';}; ?>value="NextionDriver">Nextion (enhanced w/driver)</option>
	    <option <?php if ($configmmdvm['General']['Display'] == "NextionDriverTrans") {echo 'selected="selected" ';}; ?>value="NextionDriverTrans">Nextion (enhanced w/driver, attached to modem)</option>
	    <option <?php if ($configmmdvm['General']['Display'] == "HD44780") {echo 'selected="selected" ';}; ?>value="HD44780">HD44780</option>
	    <option <?php if ($configmmdvm['General']['Display'] == "TFT Serial") {echo 'selected="selected" ';}; ?>value="TFT Serial">TFT Serial</option>
	    <option <?php if ($configmmdvm['General']['Display'] == "LCDproc") {echo 'selected="selected" ';}; ?>value="LCDproc">LCDproc</option>
	    <?php } // End DVMega Cast logic ?>
	</select>
	    <?php if (isDVmegaCast() == 0) { // Begin DVMega Cast logic... ?>
	    <b>Port:</b> <select name="mmdvmDisplayPort">
	    <?php
            if (($configmmdvm['General']['Display'] == "None") || ($configmmdvm['General']['Display'] == "")) {
                echo '      <option selected="selected" value="None">None</option>'."\n";
            } else {
                echo '      <option value="None">None</option>'."\n";
            }

	    if (isset($configmmdvm['Nextion']['Port'])) {
		if ($configmmdvm['Nextion']['Port'] == "modem") {
		    echo '      <option selected="selected" value="modem">modem</option>'."\n";
		}
		else {
		    echo '      <option value="modem">modem</option>'."\n";
		}

		if ( ($configmmdvm['Nextion']['Port'] == "None") || ($configmmdvm['Nextion']['Port'] == "0") || ($configmmdvm['Nextion']['Port'] == "")) {
		    echo '      <option selected="selected" value="None">None</option>'."\n";
		}
		else {
		    if ($configmmdvm['NextionDriver']['Enable'] == "1") {
			echo '      <option selected="selected" value="'.$configmmdvm['NextionDriver']['Port'].'">'.$configmmdvm['NextionDriver']['Port'].'</option>'."\n";
		    }
		    else {
			echo '      <option selected="selected" value="'.$configmmdvm['Nextion']['Port'].'">'.$configmmdvm['Nextion']['Port'].'</option>'."\n";
		    }
		}
	    }

            exec('ls /dev/ | egrep -h "ttyA|ttyUSB"', $availablePorts);
            foreach($availablePorts as $port) {
		 echo "     <option value=\"/dev/$port\">/dev/$port</option>\n";
            }
	    ?>
	    <?php if (file_exists('/dev/ttyS2')) { ?>
	    	<option <?php if ($configmmdvm['Nextion']['Port'] == "/dev/ttyS2") {echo 'selected="selected" ';}; ?>value="/dev/ttyS2">/dev/ttyS2</option>
    	    <?php } ?>
	    </select>

    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Nextion Display Settings:<span><b>Nextion Display Settings</b>If you have a Nextion display, choose your settings here.</span></a></td>
    <td align="left" colspan="2">
	    <b>Layout Type: </b><select name="mmdvmNextionDisplayType">
	    <option <?php if ($configmmdvm['Nextion']['ScreenLayout'] == "0") {echo 'selected="selected" ';}; ?>value="G4KLX">G4KLX</option>
	    <option <?php if ($configmmdvm['Nextion']['ScreenLayout'] == "2") {echo 'selected="selected" ';}; ?>value="ON7LDSL2">ON7LDS L2</option>
	    <option <?php if ($configmmdvm['Nextion']['ScreenLayout'] == "3") {echo 'selected="selected" ';}; ?>value="ON7LDSL3">ON7LDS L3</option>
	    <option <?php if ($configmmdvm['Nextion']['ScreenLayout'] == "4") {echo 'selected="selected" ';}; ?>value="ON7LDSL3HS">ON7LDS L3 HS</option>
	    </select>
    </td></tr>
    <tr>
	<td align="left" rowspan="4"><a class="tooltip2" href="#">OLED Display Options:<span><b>OLED Display Options</b>If you have an OLED display, choose your options here.</span></a></td>
	<td align="left"><strong>Display Always Active:</strong> <small><em>(Displays data even while modem is idle)</em></small></td>
	<td align="left">
    	    <input type="radio" name="oledScreenSaverEnable" value="ON" id="oledScreenSaver1" <?php if ($configmmdvm['OLED']['LogoScreensaver'] == "1") { echo 'checked="checked"'; } ?> />
	    <label for="oledScreenSaver1">Enabled</label>
	    <input type="radio" name="oledScreenSaverEnable" value="OFF" id="oledScreenSaver0" <?php if ($configmmdvm['OLED']['LogoScreensaver'] == "0") { echo 'checked="checked"'; } ?> />
	    <label for="oledScreenSaver0">Disabled</label>
	</td>
    </tr>
    <tr>
	<td align="left"><strong>Scroll Display:</strong> <small><em>(Note: OLED Type-3 [0.96"] displays only)</em></small></td>
	<td align="left">
	    <input type="radio" name="oledScrollEnable" value="ON" id="oledScroll1" <?php if ($configmmdvm['OLED']['Scroll'] == "1") { echo 'checked="checked"'; } if ($configmmdvm['OLED']['Type'] == "6") { echo 'disabled="disabled"'; } ?> />
	    <label for="oledScroll1">Enabled</label>
	    <input type="radio" name="oledScrollEnable" value="OFF" id="oledScroll0" <?php if ($configmmdvm['OLED']['Scroll'] == "0") { echo 'checked="checked"'; } if ($configmmdvm['OLED']['Type'] == "6") { echo 'disabled="disabled"'; } ?> />
	    <label for="oledScroll0">Disabled</label>
	</td>
    </tr>
    <tr>
	<td align="left"><strong>Rotate Display:</strong> <small><em>(Rotates display orientation 180 deg.)</em></small></td>
	<td align="left">
	    <input type="radio" name="oledRotateEnable" value="ON" id="oledRotate1" <?php if ($configmmdvm['OLED']['Rotate'] == "1") { echo 'checked="checked"'; } ?> />
	    <label for="oledRotate1">Enabled</label>
	    <input type="radio" name="oledRotateEnable" value="OFF" id="oledRotate0" <?php if ($configmmdvm['OLED']['Rotate'] == "0") { echo 'checked="checked"'; } ?> />
	    <label for="oledRotate0">Disabled</label>
	</td>
    </tr>
    <tr>
	<td align="left"><strong>Invert Display:</strong> <small><em>(Inverts display background/foreground)</em></small></td>
	<td align="left">
	    <input type="radio" name="oledInvertEnable" value="ON" id="oledInvert1" <?php if ($configmmdvm['OLED']['Invert'] == "1") { echo 'checked="checked"'; } ?> />
	    <label for="oledInvert1">Enabled</label>
	    <input type="radio" name="oledInvertEnable" value="OFF" id="oledInvert0" <?php if ($configmmdvm['OLED']['Invert'] == "0") { echo 'checked="checked"'; } ?> />
	   <label for="oledInvert0">Disabled</label>
	</td>
    </tr>
    </td></tr><?php } // End DVMega Cast logic  ?>
    </table>

    <br /><br />

    <?php } ?>

    <?php if ($configmmdvm['D-Star']['Enable'] == 1) { ?>
	<h2 class="ConfSec"><?php echo __( 'D-Star Configuration' );?></h2>
	<input type="hidden" name="confTimeAnnounce" value="OFF" />
	<input type="hidden" name="confircddbEnabled" value="OFF" />
	<input type="hidden" name="confHostFilesNoDExtra" value="OFF" />
    <table>
    <tr>
    </tr>
    <tr>
    <td align="left" width="30%"><a class="tooltip2" href="#"><?php echo __( 'RPT1 Callsign' );?>:<span><b>RPT1 Callsign</b>This is the RPT1 field for your radio</span></a></td>
    <td align="left" colspan="2" class="divTableCellMono"><?php echo $configs['repeaterCall1']; ?>
	<select name="confDStarModuleSuffix" class="ModSel">
	<?php echo "  <option value=\"".$configs['repeaterBand1']."\" selected=\"selected\">".$configs['repeaterBand1']."</option>\n"; ?>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
        <option>F</option>
        <option>G</option>
        <option>H</option>
        <option>I</option>
        <option>J</option>
        <option>K</option>
        <option>L</option>
        <option>M</option>
        <option>N</option>
        <option>O</option>
        <option>P</option>
        <option>Q</option>
        <option>R</option>
        <option>S</option>
        <option>T</option>
        <option>U</option>
        <option>V</option>
        <option>W</option>
        <option>X</option>
        <option>Y</option>
        <option>Z</option>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'RPT2 Callsign' );?>:<span><b>RPT2 Callsign</b>This is the RPT2 field for your radio</span></a></td>
    <td align="left" colspan="2" class="divTableCellMono"><?php echo $configs['repeaterCall1']; ?>&nbsp; G</td>
    </tr>
    <?php if (isDVmegaCast() == 1) { // Begin DVMega Cast logic...
	$inputString = file_get_contents('/usr/local/cast/etc/settings.txt');
	$extracedDStarCallSuffix = substr($inputString, 35, 4);
	if ($inputString !== false) {
	    // Extract the substring from positions 36 to 39
	    $extractedDStarCallSuffixValue = substr($inputString, 35, 4);
	    if ($extractedDStarCallSuffixValue == "%%%%") { // if none is defined, show a blank in the input field.
		$extractedDStarCallSuffixValue = "";
	    }
	}
    ?>
    <tr>
    <td align="left">
    <span><a class="tooltip2" href="#">D-Star Callsign Suffix Text <small>(DVMega Cast Only</small>):<span><b>D-Star Callsign Suffix Text</b>This allows custom 4-character TEXT after your D-Star callsign. Valid characters are A-Z and 0-9 only.</span></a>
    </td>
    <td align="left" class="divTableCellMono"><?php echo $configs['repeaterCall1']; ?>/<input maxlength="4" size="4" pattern="[0-9A-Z]*" type="text" value="<?php echo $extractedDStarCallSuffixValue; ?>" name="confDStarCallSuffix" oninput="enforceValidCharsAndConvertToUpper(this)" />
    </td>
    <td align="left">This setting is optional</td>
    </tr>
    <?php } // end DVmega Cast logic ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Remote Password' );?>:<span><b>Remote Password</b>Used for ircDDBGateway remote control access</span></a></td>
    <td align="left" colspan="2"><input type="password" name="confPassword" id="ircddbPass" size="30" maxlength="30" value="<?php echo $configs['remotePassword'] ?>" />
    <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-ircddb-password"></span></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Default Reflector' );?>:<span><b>Default Reflector</b>Used for setting the default reflector.</span></a></td>
    <td align="left" colspan="1"><select name="confDefRef" class="confDefRef" 
	onchange="if (this.options[this.selectedIndex].value == 'customOption') {
	  toggleField(this,this.nextSibling);
	  this.selectedIndex='0';
	  } ">
<?php
$dcsFile = fopen("/usr/local/etc/DCS_Hosts.txt", "r");
$dplusFile = fopen("/usr/local/etc/DPlus_Hosts.txt", "r");
$dextraFile = fopen("/usr/local/etc/DExtra_Hosts.txt", "r");

echo "    <option value=\"".substr($configs['reflector1'], 0, 6)."\" selected=\"selected\">".substr($configs['reflector1'], 0, 6)."</option>\n";
//echo "    <option value=\"customOption\">Text Entry</option>\n" // now handled by select2 js

while (!feof($dcsFile)) {
	$dcsLine = fgets($dcsFile);
	if (strpos($dcsLine, 'DCS') !== FALSE && strpos($dcsLine, '#') === FALSE) {
 		echo "	<option value=\"".substr($dcsLine, 0, 6)."\">".substr($dcsLine, 0, 6)."</option>\n";
	}
	if (strpos($dcsLine, 'XLX') !== FALSE && strpos($dcsLine, '#') === FALSE) {
		echo "	<option value=\"".substr($dcsLine, 0, 6)."\">".substr($dcsLine, 0, 6)."</option>\n";
	}
}
fclose($dcsFile);
while (!feof($dplusFile)) {
	$dplusLine = fgets($dplusFile);
	if (strpos($dplusLine, 'REF') !== FALSE && strpos($dplusLine, '#') === FALSE) {
		echo "	<option value=\"".substr($dplusLine, 0, 6)."\">".substr($dplusLine, 0, 6)."</option>\n";
	}
	if (strpos($dplusLine, 'XRF') !== FALSE && strpos($dplusLine, '#') === FALSE) {
		echo "	<option value=\"".substr($dplusLine, 0, 6)."\">".substr($dplusLine, 0, 6)."</option>\n";
	}
}
fclose($dplusFile);
while (!feof($dextraFile)) {
	$dextraLine = fgets($dextraFile);
	if (strpos($dextraLine, 'XRF') !== FALSE && strpos($dextraLine, '#') === FALSE)
		echo "	<option value=\"".substr($dextraLine, 0, 6)."\">".substr($dextraLine, 0, 6)."</option>\n";
}
fclose($dextraFile);

?>
    </select><input name="confDefRef" style="display:none;" disabled="disabled" type="text" size="7" maxlength="7"
            onblur="if(this.value==''){toggleField(this,this.previousSibling);}" />
    <select name="confDefRefLtr" class="ModSel">
	<?php echo "  <option value=\"".substr($configs['reflector1'], 7)."\" selected=\"selected\">".substr($configs['reflector1'], 7)."</option>\n"; ?>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
        <option>F</option>
        <option>G</option>
        <option>H</option>
        <option>I</option>
        <option>J</option>
        <option>K</option>
        <option>L</option>
        <option>M</option>
        <option>N</option>
        <option>O</option>
        <option>P</option>
        <option>Q</option>
        <option>R</option>
        <option>S</option>
        <option>T</option>
        <option>U</option>
        <option>V</option>
        <option>W</option>
        <option>X</option>
        <option>Y</option>
        <option>Z</option>
    </select>
    </td>
    <td align="left" style='word-wrap: break-word;white-space: normal'><strong>Link Type:</strong>&nbsp;&nbsp;
    <input type="radio" name="confDefRefAuto" value="ON"<?php if ($configs['atStartup1'] == '1') {echo ' checked="checked"';} ?> />Auto-Link/Startup
    <input type="radio" name="confDefRefAuto" value="OFF"<?php if ($configs['atStartup1'] == '0') {echo ' checked="checked"';} ?> />Manual Link</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'ircDDBGateway Language' );?>:<span><b>ircDDBGateway Language</b>Set your preferred language here</span></a></td>
    <td colspan="2" style="text-align: left;"><select name="ircDDBGatewayAnnounceLanguage">
<?php
        $testIrcLanguage = $configs['language'];
	if (is_readable("/var/www/dashboard/config/ircddbgateway_languages.inc")) {
	  $ircLanguageFile = fopen("/var/www/dashboard/config/ircddbgateway_languages.inc", "r");
        while (!feof($ircLanguageFile)) {
                $ircLanguageFileLine = fgets($ircLanguageFile);
                $ircLanguage = preg_split('/;/', $ircLanguageFileLine);
                if ((strpos($ircLanguage[0], '#') === FALSE ) && ($ircLanguage[0] != '')) {
			$ircLanguage[2] = rtrim($ircLanguage[2]);
                        if ($testIrcLanguage == $ircLanguage[1]) { echo "      <option value=\"$ircLanguage[1],$ircLanguage[2]\" selected=\"selected\">".htmlspecialchars($ircLanguage[0])."</option>\n"; }
                        else { echo "      <option value=\"$ircLanguage[1],$ircLanguage[2]\">".htmlspecialchars($ircLanguage[0])."</option>\n"; }
                }
        }
          fclose($ircLanguageFile);
	}
        ?>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Time Announcements' );?>:<span><b>Time Announce</b>Announce time hourly</span></a></td>
    <?php
	if ( !file_exists('/etc/timeserver.disable') ) {
		echo "<td align=\"left\" colspan=\"1\"><div class=\"switch\"><input id=\"toggle-timeAnnounce\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confTimeAnnounce\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDstarTimeAnnounceCr." /><label id=\"aria-toggle-timeAnnounce\" role=\"checkbox\" tabindex=\"0\" aria-label=\"D-Star Time Announcements\" aria-checked=\"true\" onKeyPress=\"toggleDstarTimeAnnounce()\" onclick=\"toggleDstarTimeAnnounce()\" for=\"toggle-timeAnnounce\"><font style=\"font-size:0px\">D-Star Time Announcements</font></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\" colspan=\"1\"><div class=\"switch\"><input id=\"toggle-timeAnnounce\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confTimeAnnounce\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDstarTimeAnnounceCr." /><label id=\"aria-toggle-timeAnnounce\" role=\"checkbox\" tabindex=\"0\" aria-label=\"D-Star Time Announcements\" aria-checked=\"false\" onKeyPress=\"toggleDstarTimeAnnounce()\" onclick=\"toggleDstarTimeAnnounce()\" for=\"toggle-timeAnnounce\"><font style=\"font-size:0px\">D-Star Time Announcements</font></label></div></td>\n";
	}
    ?>

    <?php
      $currentTimeInt = $_SESSION['timeServerConfigs']['interval'];
    ?>
    <td style='word-wrap: break-word;white-space: normal' align="left"><strong>Interval:</strong>&nbsp;&nbsp;
	<input type="radio" name="confTimeAnnounceInt" value="2" <?php if ($currentTimeInt == "2") { echo " checked"; }?>/><label>1 Hr.</label> 
	<input type="radio" name="confTimeAnnounceInt" value="1" <?php if ($currentTimeInt == "1") { echo " checked"; }?> /><label>30 Mins.</label> 
	<input type="radio" name="confTimeAnnounceInt" value="0" <?php if ($currentTimeInt == "0") { echo " checked"; }?> /><label>15 Mins.</label>
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Callsign Routing:<span><b>Callsign Routing</b>Do you want callsign routing for D-Star</span></a></td>
    <?php
	if ( isset($configs['ircddbEnabled']) && $configs['ircddbEnabled'] == "1" ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-ircddbEnabled\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confircddbEnabled\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleircddbEnabledCr." /><label id=\"aria-toggle-ircddbEnabled\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Use ircDDB callsign routing\" aria-checked=\"true\" onKeyPress=\"toggleircddbEnabled()\" onclick=\"toggleircddbEnabled()\" for=\"toggle-ircddbEnabled\"><font style=\"font-size:0px\">Enable ircDDB callsign routing</font></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-ircddbEnabled\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confircddbEnabled\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleircddbEnabledCr." /><label id=\"aria-toggle-ircddbEnabled\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Use ircDDB callsign routing\" aria-checked=\"false\" onKeyPress=\"toggleircddbEnabled()\" onclick=\"toggleircddbEnabled()\" for=\"toggle-ircddbEnabled\"><font style=\"font-size:0px\">Enable ircDDB callsign routing</font></label></div></td>\n";
	}
    ?>
    <td align="left">Connect to ircDDB for callsign routing</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Use DPlus for XRF:<span><b>No DExtra</b>Should host files use DPlus Protocol for XRFs</span></a></td>
    <?php
	if ( file_exists('/etc/hostfiles.nodextra') ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dplusHostFiles\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confHostFilesNoDExtra\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDstarDplusHostfilesCr." /><label id=\"aria-toggle-dplusHostFiles\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Use D-Plus for XRF Hosts\" aria-checked=\"true\" onKeyPress=\"toggleDstarDplusHostfiles()\" onclick=\"toggleDstarDplusHostfiles()\" for=\"toggle-dplusHostFiles\"><font style=\"font-size:0px\">Use D-Plus for XRF Hosts</font></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dplusHostFiles\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confHostFilesNoDExtra\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDstarDplusHostfilesCr." /><label id=\"aria-toggle-dplusHostFiles\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Use D-Plus for XRF Hosts\" aria-checked=\"false\" onKeyPress=\"toggleDstarDplusHostfiles()\" onclick=\"toggleDstarDplusHostfiles()\" for=\"toggle-dplusHostFiles\"><font style=\"font-size:0px\">Use D-Plus for XRF Hosts</font></label></div></td>\n";
	}
    ?>
    <td align="left"><em>Note: Update Required if changed</em></td>
    </tr>
    </table>

    <br /><br />

<?php } ?>

<?php if (file_exists('/etc/dstar-radio.mmdvmhost') && ($configmmdvm['System Fusion Network']['Enable'] == 1 || $configdmr2ysf['Enabled']['Enabled'] == 1 )) {
$ysfHosts = fopen("/usr/local/etc/YSFHosts.txt", "r"); ?>
	<input type="hidden" name="confHostFilesYSFUpper" value="OFF" />
        <input type="hidden" name="useDGIdGateway" value="OFF" />
	<input type="hidden" name="wiresXCommandPassthrough" value="OFF" />
	<input type="hidden" name="FCSEnable" value="OFF" />
	<h2 class="ConfSec"><?php echo __( 'Yaesu System Fusion Configuration' );?></h2>
    <table>
<?php if ($configysf2dmr['Enabled']['Enabled'] == 1 || $configysf2nxdn['Enabled']['Enabled'] == 1 || $configysf2p25['Enabled']['Enabled'] == 1) { ?>
    <tr>
    <th class='config_head' colspan="4">Main YSF Settings</th>
    </tr>
<?php } ?>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'YSF Startup Host' );?>:<span><b>YSF Host</b>Set your preferred YSF Host here</span></a></td>
    <td colspan="2" style="text-align: left;"><select name="ysfStartupHost" class="ysfStartupHost">
<?php
        if (isset($configysfgateway['Network']['Startup'])) {
                $testYSFHost = $configysfgateway['Network']['Startup'];
                echo "      <option value=\"none\">None</option>\n";
        	}
        else {
                $testYSFHost = "none";
                echo "      <option value=\"none\" selected=\"selected\">None</option>\n";
    		}
	if ($testYSFHost == "ZZ Parrot")  {
		echo "      <option value=\"00001,ZZ Parrot\" selected=\"selected\">YSF00001 - Parrot</option>\n";
	} else {
		echo "      <option value=\"00001,ZZ Parrot\">YSF00001 - Parrot</option>\n";
	}
	if ($testYSFHost == "YSF2DMR")  {
		echo "      <option value=\"00002,YSF2DMR\"  selected=\"selected\">YSF00002 - Link YSF2DMR</option>\n";
	} else {
		echo "      <option value=\"00002,YSF2DMR\">YSF00002 - Link YSF2DMR</option>\n";
	}
	if ($testYSFHost == "YSF2NXDN") {
		echo "      <option value=\"00003,YSF2NXDN\" selected=\"selected\">YSF00003 - Link YSF2NXDN</option>\n";
	} else {
		echo "      <option value=\"00003,YSF2NXDN\">YSF00003 - Link YSF2NXDN</option>\n";
	}
	if ($testYSFHost == "YSF2P25")  {
		echo "      <option value=\"00004,YSF2P25\"  selected=\"selected\">YSF00004 - Link YSF2P25</option>\n";
	} else {
		echo "      <option value=\"00004,YSF2P25\">YSF00004 - Link YSF2P25</option>\n";
	}
        while (!feof($ysfHosts)) {
                $ysfHostsLine = fgets($ysfHosts);
                $ysfHost = preg_split('/;/', $ysfHostsLine);
                if ((strpos($ysfHost[0], '#') === FALSE ) && ($ysfHost[0] != '')) {
                        if ($testYSFHost == $ysfHost[1]) { echo "      <option value=\"$ysfHost[0],$ysfHost[1]\" selected=\"selected\">YSF$ysfHost[0] - ".htmlspecialchars($ysfHost[1])." - ".htmlspecialchars($ysfHost[2])."</option>\n"; }
			else { echo "      <option value=\"$ysfHost[0],$ysfHost[1]\">YSF$ysfHost[0] - ".htmlspecialchars($ysfHost[1])." - ".htmlspecialchars($ysfHost[2])."</option>\n"; }
                }
        }
        fclose($ysfHosts);
	if ($_SESSION['YSFGatewayConfigs']['FCS Network']['Enable'] == 1) {
	    if (file_exists("/usr/local/etc/FCSHosts.txt")) {
                    $fcsHosts = fopen("/usr/local/etc/FCSHosts.txt", "r");
                    while (!feof($fcsHosts)) {
                            $ysfHostsLine = fgets($fcsHosts);
                            $ysfHost = preg_split('/;/', $ysfHostsLine);
			    if (substr($ysfHost[0], 0, 3) == "FCS") {
                                    if ($testYSFHost == $ysfHost[0]) { echo "      <option value=\"$ysfHost[0],$ysfHost[0]\" selected=\"selected\">$ysfHost[0] - ".htmlspecialchars($ysfHost[1])."</option>\n"; }
                                    else { echo "      <option value=\"$ysfHost[0],$ysfHost[0]\">$ysfHost[0] - ".htmlspecialchars($ysfHost[1])."</option>\n"; }
                            }
                    }
                    fclose($fcsHosts);
            }
	}
        ?>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">UPPERCASE Hostfiles:<span><b>UPPERCASE Hostfiles</b>Should host files use UPPERCASE only - fixes issues with FT-70D radios.</span></a></td>
    <?php
	if ( isset($configysfgateway['General']['WiresXMakeUpper']) ) {
		if ( $configysfgateway['General']['WiresXMakeUpper'] ) {
			echo "<td colspan='2' align=\"left\"><div class=\"switch\"><input id=\"toggle-confHostFilesYSFUpper\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confHostFilesYSFUpper\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleHostFilesYSFUpperCr." /><label id=\"aria-toggle-confHostFilesYSFUpper\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Uppercase Host Files\" aria-checked=\"true\" onKeyPress=\"toggleHostFilesYSFUpper()\" onclick=\"toggleHostFilesYSFUpper()\" for=\"toggle-confHostFilesYSFUpper\"><font style=\"font-size:0px\">Uppercase Host Files</font></label></div></td>\n";
		}
		else {
			echo "<td colspan='2' align=\"left\"><div class=\"switch\"><input id=\"toggle-confHostFilesYSFUpper\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confHostFilesYSFUpper\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleHostFilesYSFUpperCr." /><label id=\"aria-toggle-confHostFilesYSFUpper\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Uppercase Host Files\" aria-checked=\"false\" onKeyPress=\"toggleHostFilesYSFUpper()\" onclick=\"toggleHostFilesYSFUpper()\" for=\"toggle-confHostFilesYSFUpper\"><font style=\"font-size:0px\">Uppercase Host Files</font></label></div></td>\n";
		}
	} else {
		echo "<td colspan='2' align=\"left\"><div class=\"switch\"><input id=\"toggle-confHostFilesYSFUpper\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confHostFilesYSFUpper\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleHostFilesYSFUpperCr." /><label id=\"aria-toggle-confHostFilesYSFUpper\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Uppercase Host Files\" aria-checked=\"false\" onKeyPress=\"toggleHostFilesYSFUpper()\" onclick=\"toggleHostFilesYSFUpper()\" for=\"toggle-confHostFilesYSFUpper\"><font style=\"font-size:0px\">Uppercase Host files</font></label></div></td>\n";
	}
    ?>
    </tr>
        <tr>
        <td align="left"><a class="tooltip2" href="#">FCS Network:<span><b>FCS Network</b>Enable the FCS Network and Hosts</span></a></td>
        <?php
        if ( isset($configysfgateway['FCS Network']['Enable']) ) {
                if ( $configysfgateway['FCS Network']['Enable'] ) {
                        echo "<td colspan='2' align=\"left\"><div class=\"switch\"><input id=\"toggle-FCSEnable\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"FCSEnable\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-FCSEnable\"></label></div></td>\n";
                }
                else {
                        echo "<td colspan='2' align=\"left\"><div class=\"switch\"><input id=\"toggle-FCSEnable\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"FCSEnable\" value=\"ON\" /><label for=\"toggle-FCSEnable\"></label></div></td>\n";
                }
        } else {
                echo "<td colspan='2' align=\"left\"><div class=\"switch\"><input id=\"toggle-FCSEnable\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"FCSEnable\" value=\"ON\" /><label for=\"toggle-FCSEnable\"></label></div></td>\n";
        }
        ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">WiresX Passthrough:<span><b>WiresX Auto Passthrough</b>Use this to automatically send WiresX commands through to YSF2xxx cross-over modes.</span></a></td>
    <?php
	if ( isset($configysfgateway['General']['WiresXCommandPassthrough']) ) {
		if ( $configysfgateway['General']['WiresXCommandPassthrough'] ) {
			echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-confWiresXCommandPassthrough\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"wiresXCommandPassthrough\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleWiresXCommandPassthroughCr." /><label id=\"aria-toggle-confWiresXCommandPassthrough\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Wires-X Command Passthrough\" aria-checked=\"true\" onKeyPress=\"toggleWiresXCommandPassthrough()\" onclick=\"toggleWiresXCommandPassthrough()\" for=\"toggle-confWiresXCommandPassthrough\"><font style=\"font-size:0px\">Wires-X Command Passthrough</font></label></div></td>\n";
		}
		else {
			echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-confWiresXCommandPassthrough\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"wiresXCommandPassthrough\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleWiresXCommandPassthroughCr." /><label id=\"aria-toggle-confWiresXCommandPassthrough\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Wires-X Command Passthrough\" aria-checked=\"false\" onKeyPress=\"toggleWiresXCommandPassthrough()\" onclick=\"toggleWiresXCommandPassthrough()\" for=\"toggle-confWiresXCommandPassthrough\"><font style=\"font-size:0px\">Wires-X Command Passthrough</font></label></div></td>\n";
		}
	} else {
		echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-confWiresXCommandPassthrough\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"wiresXCommandPassthrough\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleWiresXCommandPassthroughCr." /><label id=\"aria-toggle-confWiresXCommandPassthrough\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Wires-X Command Passthrough\" aria-checked=\"false\" onKeyPress=\"toggleWiresXCommandPassthrough()\" onclick=\"toggleWiresXCommandPassthrough()\" for=\"toggle-confWiresXCommandPassthrough\"><font style=\"font-size:0px\">Wires-X Command Passthrough</font></label></div></td>\n";
	}
    ?>
    </tr>
<?php
				if (isset($configdgidgateway) && $configmmdvm['System Fusion']['Enable'] == 1) {
				?>
				<tr>
				    <td align="left"><a class="tooltip2" href="#">Enable DGIdGateway:<span><b>Enable DGIdGateway</b>Enable/Disable DGIdGateway.</span></a></td>
				    <?php
				    if ($configysf2dmr['Enabled']['Enabled'] == 1 || $configysf2p25['Enabled']['Enabled'] == 1 || $configysf2nxdn['Enabled']['Enabled'] == 1 ) {
					echo "<td align=\"left\" colspan=\"1\"><div class=\"switch\"><input id=\"toggle-useDGIdGateway\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"useDGIdGateway\" value=\"OFF\" disabled=\"disabled\" /><label for=\"toggle-useDGIdGateway\"></label></div></td>\n";
					echo "<td align='left'><em>Note: DGIdGateway cannot be enabled in conjunction with YSF2DMR/YSF2NXDN/YSF2P25 modes</em></td>\n";
				    } else {
					if (isset($configdgidgateway['Enabled']['Enabled'])) {
					    if ($configdgidgateway['Enabled']['Enabled']) {
						echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-useDGIdGateway\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"useDGIdGateway\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-useDGIdGateway\"></label></div></td>\n";
					    } else {
						echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-useDGIdGateway\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"useDGIdGateway\" value=\"ON\" /><label for=\"toggle-useDGIdGateway\"></label></div></td>\n";
					    }
					} else {
					    echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-useDGIdGateway\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"useDGIdGateway\" value=\"ON\" /><label for=\"toggle-useDGIdGateway\"></label></div></td>\n";
					}
				    }
				    ?>
				</tr>
				<tr>
    				<td align="left"><a class="tooltip2" href="#">YCS Network Options:<span><b>YCS Network</b>Set your options= for the YCS Network here!</span></a></td>
    				<td align="left" colspan="3">
    				Options=<input type="text" name="ysfgatewayNetworkOptions" size="85" maxlength="250" value="<?php if (isset($configysfgateway['Network']['Options'])) { echo $configysfgateway['Network']['Options']; } ?>" />
    			</td>
    			</tr>
				<?php
				}
				?>
    <?php if (file_exists('/etc/dstar-radio.mmdvmhost') && $configysf2dmr['Enabled']['Enabled'] == 1) {
    $dmrMasterFile = fopen("/usr/local/etc/DMR_Hosts.txt", "r"); ?>
    <tr>
    <th class='config_head' colspan="4">YSF2DMR Cross-Mode Settings</th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">(YSF2DMR)<?php echo __( 'DMR/CCS7 ID' );?>:<span><b>CCS7/DMR ID</b>Enter your CCS7 / DMR ID here</span></a></td>
    <td align="left" colspan="2">
<?php
	if (isset($configysf2dmr['DMR Network']['Id'])) {
		if (strlen($configysf2dmr['DMR Network']['Id']) > strlen($configmmdvm['General']['Id'])) {
			$essidYSF2DMRLen = strlen($configysf2dmr['DMR Network']['Id']) - strlen($configmmdvm['General']['Id']);
			$ysf2dmrESSID = substr($configysf2dmr['DMR Network']['Id'], -$essidYSF2DMRLen);
		} else {
			$ysf2dmrESSID = "None";
		}
	} else {
		$ysf2dmrESSID = "None";
	}

	if (isset($configmmdvm['General']['Id'])) { if ($configmmdvm['General']['Id'] !== "1234567") { echo substr($configmmdvm['General']['Id'], 0, 7); } }
	if (isset($configmmdvm['General']['Id'])) { $ysf2dmrIdBase = substr($configmmdvm['General']['Id'], 0, 7); } else { $ysf2dmrIdBase = "1234567"; }
	echo "<select name=\"ysf2dmrId\">\n";
	if ($ysf2dmrESSID == "None") { echo "      <option value=\"$ysf2dmrIdBase\" selected=\"selected\">None</option>\n"; } else { echo "      <option value=\"None\">None</option>\n"; }
	for ($ysf2dmrESSIDInput = 1; $ysf2dmrESSIDInput <= 99; $ysf2dmrESSIDInput++) {
		$ysf2dmrESSIDInput = str_pad($ysf2dmrESSIDInput, 2, "0", STR_PAD_LEFT);
		if ($ysf2dmrESSID === $ysf2dmrESSIDInput) {
			echo "      <option value=\"$ysf2dmrIdBase$ysf2dmrESSIDInput\" selected=\"selected\">$ysf2dmrESSIDInput</option>\n";
		} else {
			echo "      <option value=\"$ysf2dmrIdBase$ysf2dmrESSIDInput\">$ysf2dmrESSIDInput</option>\n";
		}
	}
	echo "</select>\n";
?>
    </td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'DMR Master' );?>:<span><b>DMR Master (YSF2DMR)</b>Set your preferred DMR master here</span></a></td>
    <td colspan="2" style="text-align: left;"><select name="ysf2dmrMasterHost" class="ysf2dmrMasterHost">
<?php
        $testMMDVMysf2dmrMaster = $configysf2dmr['DMR Network']['Address'];
        while (!feof($dmrMasterFile)) {
                $dmrMasterLine = fgets($dmrMasterFile);
                $dmrMasterHost = preg_split('/\s+/', $dmrMasterLine);
                if ((strpos($dmrMasterHost[0], '#') === FALSE ) && (substr($dmrMasterHost[0], 0, 3) != "XLX") && (substr($dmrMasterHost[0], 0, 4) != "DMRG") && (substr($dmrMasterHost[0], 0, 4) != "DMR2") && ($dmrMasterHost[0] != '')) {
                        if ($testMMDVMysf2dmrMaster == $dmrMasterHost[2]) { echo "      <option value=\"$dmrMasterHost[2],$dmrMasterHost[3],$dmrMasterHost[4],$dmrMasterHost[0]\" selected=\"selected\">$dmrMasterHost[0]</option>\n"; $dmrMasterNow = $dmrMasterHost[0]; }
                        else { echo "      <option value=\"$dmrMasterHost[2],$dmrMasterHost[3],$dmrMasterHost[4],$dmrMasterHost[0]\">$dmrMasterHost[0]</option>\n"; }
                }
        }
        fclose($dmrMasterFile);
        ?>
    </select></td>
    </tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">Hotspot Security:<span><b>DMR Master Password</b>Override the Password for DMR with your own custom password, make sure you already configured this on your chosed DMR Master. Empty the field to use the default.</span></a></td>
      <td align="left" colspan="2">
        <input type="password" name="bmHSSecurity_YSF" id="bmHSSecurity_YSF" size="30" maxlength="30" value="<?php if (isset($configModem['BrandMeister']['Password'])) {echo $configModem['BrandMeister']['Password'];} ?>"></input>
	<span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-bm-password_YSF"></span>
      </td>
    </tr>
    <tr>
	<td align="left"><a class="tooltip2" href="#">DMR Options:<span><b>DMR Options (YSF2DMR)</b>Set your Options= for the DMR master above</span></a></td>
	<td align="left" colspan="2">
	    Options=<input type="text" name="ysf2dmrNetworkOptions" size="85" maxlength="250" value="<?php if (isset($configysf2dmr['DMR Network']['Options'])) { echo $configysf2dmr['DMR Network']['Options']; } ?>" />
	</td>
    </tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">DMR TG:<span><b>YSF2DMR TG</b>Enter your DMR TG here</span></a></td>
      <td align="left" colspan="2"><input type="text" name="ysf2dmrTg" size="13" maxlength="7" value="<?php if (isset($configysf2dmr['DMR Network']['StartupDstId'])) { echo $configysf2dmr['DMR Network']['StartupDstId']; } ?>" /></td>  
    </tr>
    <?php } ?>
    <?php if (file_exists('/etc/dstar-radio.mmdvmhost') && $configysf2nxdn['Enabled']['Enabled'] == 1) { ?>
    <tr>
    <th class='config_head' colspan="4">YSF2NXDN Cross-Mode Settings</th>
    </tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">(YSF2NXDN) NXDN ID:<span><b>NXDN ID</b>Enter your NXDN ID here</span></a></td>
      <td align="left" colspan="2"><?php if (isset($configysf2nxdn['NXDN Network']['Id'])) { echo $configysf2nxdn['NXDN Network']['Id']; } else { echo "Set your DMR/CCS7 ID in the 'General' Section Above"; } ?></td>
    </tr>
    <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo __( 'NXDN Hosts' );?>:<span><b>NXDN Host</b>Set your preferred NXDN Host here</span></a></td>
        <td colspan="2" style="text-align: left;"><select name="ysf2nxdnStartupDstId" class="ysf2nxdnStartupDstId">
<?php
	$nxdnHosts = fopen("/usr/local/etc/NXDNHosts.txt", "r");
	$testNXDNHost = $configysf2nxdn['NXDN Network']['StartupDstId'];
	if ($testNXDNHost == "") { echo "      <option value=\"none\" selected=\"selected\">None</option>\n"; }
        else { echo "      <option value=\"none\">None</option>\n"; }
	if ($testNXDNHost == "10") { echo "      <option value=\"10\" selected=\"selected\">10 - Parrot</option>\n"; }
        else { echo "      <option value=\"10\">10 - Parrot</option>\n"; }
        while (!feof($nxdnHosts)) {
                $nxdnHostsLine = fgets($nxdnHosts);
                $nxdnHost = preg_split('/\s+/', $nxdnHostsLine);
                if ((strpos($nxdnHost[0], '#') === FALSE ) && ($nxdnHost[0] != '')) {
                        if ($testNXDNHost == $nxdnHost[0]) { echo "      <option value=\"$nxdnHost[0]\" selected=\"selected\">$nxdnHost[0] - $nxdnHost[1]</option>\n"; }
                        else { echo "      <option value=\"$nxdnHost[0]\">$nxdnHost[0] - $nxdnHost[1]</option>\n"; }
                }
        }
        fclose($nxdnHosts);
	if (file_exists('/usr/local/etc/NXDNHostsLocal.txt')) {
		$nxdnHosts2 = fopen("/usr/local/etc/NXDNHostsLocal.txt", "r");
		while (!feof($nxdnHosts2)) {
               		$nxdnHostsLine2 = fgets($nxdnHosts2);
               		$nxdnHost2 = preg_split('/\s+/', $nxdnHostsLine2);
               		if ((strpos($nxdnHost2[0], '#') === FALSE ) && ($nxdnHost2[0] != '')) {
               	        	if ($testNXDNHost == $nxdnHost2[0]) { echo "      <option value=\"$nxdnHost2[0]\" selected=\"selected\">$nxdnHost2[0] - $nxdnHost2[1]</option>\n"; }
               	        	else { echo "      <option value=\"$nxdnHost2[0]\">$nxdnHost2[0] - $nxdnHost2[1]</option>\n"; }
               		}
		}
	fclose($nxdnHosts2);
	}
?>
        </select></td>
      </tr>
    <?php } ?>
    <?php if (file_exists('/etc/dstar-radio.mmdvmhost') && $configysf2p25['Enabled']['Enabled'] == 1) { ?>
    <tr>
    <th class='config_head' colspan="4">YSF2P25 Cross-Mode Settings</th>
    </tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">(YSF2P25) <?php echo __( 'DMR/CCS7 ID' );?>:<span><b>DMR ID</b>Enter your CCS7 / DMR ID here</span></a></td>
      <td align="left" colspan="2"><?php if (isset($configysf2p25['P25 Network']['Id'])) { echo $configysf2p25['P25 Network']['Id'];  } else { echo "Set your DMR/CCS7 ID in the 'General' Section Above"; }?></td>
    </tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#"><?php echo __( 'P25 Hosts' );?>:<span><b>P25 Host</b>Set your preferred P25 Host here</span></a></td>
      <td colspan="2" style="text-align: left;"><select name="ysf2p25StartupDstId" class="ysf2p25StartupDstId">
<?php
	$p25Hosts = fopen("/usr/local/etc/P25Hosts.txt", "r");
	if (isset($configysf2p25['P25 Network']['StartupDstId'])) {
		$testP25Host = $configysf2p25['P25 Network']['StartupDstId'];
	} else {
		$testP25Host = "";
	}
	if ($testP25Host == "") { echo "      <option value=\"none\" selected=\"selected\">None</option>\n"; }
        else { echo "      <option value=\"none\">None</option>\n"; }
	if ($testP25Host == "10") { echo "      <option value=\"10\" selected=\"selected\">10 - Parrot</option>\n"; }
        else { echo "      <option value=\"10\">10 - Parrot</option>\n"; }
        while (!feof($p25Hosts)) {
                $p25HostsLine = fgets($p25Hosts);
                $p25Host = preg_split('/\s+/', $p25HostsLine);
                if ((strpos($p25Host[0], '#') === FALSE ) && ($p25Host[0] != '')) {
                        if ($testP25Host == $p25Host[0]) { echo "      <option value=\"$p25Host[0]\" selected=\"selected\">$p25Host[0] - $p25Host[1]</option>\n"; }
                        else { echo "      <option value=\"$p25Host[0]\">$p25Host[0] - $p25Host[1]</option>\n"; }
                }
        }
        fclose($p25Hosts);
        if (file_exists('/usr/local/etc/P25HostsLocal.txt')) {
		$p25Hosts2 = fopen("/usr/local/etc/P25HostsLocal.txt", "r");
		while (!feof($p25Hosts2)) {
                	$p25HostsLine2 = fgets($p25Hosts2);
                	$p25Host2 = preg_split('/\s+/', $p25HostsLine2);
                	if ((strpos($p25Host2[0], '#') === FALSE ) && ($p25Host2[0] != '')) {
                        	if ($testP25Host == $p25Host2[0]) { echo "      <option value=\"$p25Host2[0]\" selected=\"selected\">$p25Host2[0] - $p25Host2[1]</option>\n"; }
                        	else { echo "      <option value=\"$p25Host2[0]\">$p25Host2[0] - $p25Host2[1]</option>\n"; }
                	}
		}
		fclose($p25Hosts2);
	}
        ?>
    </select></td>
    </tr>
    <?php } ?>
 
    </table>

    <br /><br />

<?php } ?>

			<!-- M17 -->
			<?php if (file_exists('/etc/dstar-radio.mmdvmhost') && $configmmdvm['M17 Network']['Enable'] == 1 ) { ?>
			    <h2 class="ConfSec">M17 Configuration</h2>
			    <table>
				<tr>
				</tr>
				<tr>
				    <td align="left"><a class="tooltip2" href="#">M17 Startup Reflector:<span><b>Startup Reflector</b>Set your preferred M17 reflector here</span></a></td>
				    <td style="text-align: left;"><select name="m17StartupRef" class="M17Ref">
					<?php
					if ($m17MasterHandle = @fopen("/usr/local/etc/M17Hosts.txt", 'r'))
					{
					    $m17StartupHostWithModule = (isset($configm17gateway['Network']['Startup']) ? $configm17gateway['Network']['Startup'] : "");
					    $m17StartupHost = "";
					    $m17StartupModule = "A";
					    if ($m17StartupHostWithModule != "") {
						$m17StartupHost = substr($m17StartupHostWithModule, 0, -2);
						$m17StartupModule = substr($m17StartupHostWithModule, -1);
					    }

					    if ($m17StartupHost == "") {
						echo "      <option value=\"NONE\" selected=\"selected\">None</option>\n";
					    }
					    else {
						echo "      <option value=\"NONE\">None</option>\n";
					    }

					    while ($m17MasterLine = fgets($m17MasterHandle)) {
						$m17MasterHost = preg_split('/\s+/', $m17MasterLine);
						if ((strpos($m17MasterHost[0], '#') === FALSE) && ($m17MasterHost[0] != '')) {
						    if ($m17MasterHost[0] == $m17StartupHost) {
							echo "      <option value=\"$m17MasterHost[0]\" selected=\"selected\">$m17MasterHost[0]</option>\n";
						    }
						    else {
							echo "      <option value=\"$m17MasterHost[0]\">$m17MasterHost[0]</option>\n";
						    }
						}
					    }
					    fclose($m17MasterHandle);
					}
					?>
				    </select>
				    
				    &nbsp;Startup Module:<select name="m17StartupModule" class="ModSel">
					<?php
					$m17ModuleList = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
					foreach ($m17ModuleList as $module) {
					    if ($m17StartupModule == $module) {
						echo "  <option value=\"".$module."\" selected=\"selected\">".$module."</option>\n";
					    }
					    else {
						echo "  <option value=\"".$module."\">".$module."</option>\n";
					    }
					}
					?>
				    </select>
				    
				    </td>
				</tr>
				<?php if (isset($configm17gateway['General']['Suffix'])) { ?>
					<tr>
					<td align="left"><a class="tooltip2" href="#">M17 Callsign Suffix:<span><b>Callsign Suffix</b>Set your preferred callsign suffix here. Typical values are "H" for Hotspots, "R" for Repeaters.</span></a></td>
					<td align="left">
					<select name="m17CallsignSuffix">
					<?php 
					$m17SuffixList = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
					foreach ($m17SuffixList as $suffix) {
					    if ($configm17gateway['General']['Suffix'] == $suffix) {
						echo "  <option value=\"".$suffix."\" selected=\"selected\">".$suffix."</option>\n";
					    }
					    else {
						echo "  <option value=\"".$suffix."\">".$suffix."</option>\n";
					    }
					}
					?>
					</td>
					</tr>
				<?php } ?>
				<?php if (isset($configm17gateway['General']['Callsign'])) {
				     $m17SID = substr($configm17gateway['General']['Callsign'], strpos($configm17gateway['General']['Callsign'], '-') + 1);
				     if (preg_match('/^\d$/', $m17SID)) {
					$m17SID = $m17SID;
				     } else {
					$m17SID = "";
				     }
				?>
					<tr>
						<td align="left"><a class="tooltip2" href="#">M17 Station ID:<span><b>Station ID</b>Set your preferred station ID here, if applicable.</span></a></td>
						<td align="left">
						<select name="m17StationID">
						<?php if($m17SID == "") { echo '<option value="" selected="selected">None</option>'; } else { echo "<option value='$m17SID' selected='selected'>$m17SID</option>"; } ?>
						  <?php if($m17SID != "") { ?>
						  <option value="">None</option>
						  <?php } ?>
						  <option value="0">0</option>
						  <option value="1">1</option>
						  <option value="2">2</option>
						  <option value="3">3</option>
						  <option value="4">4</option>
						  <option value="5">5</option>
						  <option value="6">6</option>
						  <option value="7">7</option>
						  <option value="8">8</option>
						  <option value="9">9</option>
						</select>
						</td>
					</tr>
				<?php } ?>
				<?php if (isset($configmmdvm['M17']['CAN'])) { ?>
					<tr>
						<td align="left"><a class="tooltip2" href="#"><?php echo __( 'M17 CAN' );?>:<span><b>M17 CAN</b>Set your CAN (Channel Access Number) code here, sane values are 0-15</span></a></td>
						<td align="left">
						<select name="m17can">
						  <?php echo "<option value=\"".$configmmdvm['M17']['CAN']."\" 'selected='selected'>".$configmmdvm['M17']['CAN']."</option>"; ?>
						  <option value="0">0</option>
						  <option value="1">1</option>
						  <option value="2">2</option>
						  <option value="3">3</option>
						  <option value="4">4</option>
						  <option value="5">5</option>
						  <option value="6">6</option>
						  <option value="7">7</option>
						  <option value="8">8</option>
						  <option value="9">9</option>
						  <option value="10">10</option>
						  <option value="11">11</option>
						  <option value="12">12</option>
						  <option value="13">13</option>
						  <option value="14">14</option>
						  <option value="15">15</option>
						</select>
						</td>
					</tr>
				<?php } ?>


			    </table>

			    <br /><br />

			<?php } ?>

    <?php if (file_exists('/etc/dstar-radio.mmdvmhost') && $configmmdvm['DMR']['Enable'] == 1) {
    $dmrMasterFile = fopen("/usr/local/etc/DMR_Hosts.txt", "r");
    $testMMDVMdmrMaster = $configmmdvm['DMR Network']['Address'];
    $testMMDVMdmrMasterPort = $configmmdvm['DMR Network']['Port'];
    $dmrMasterNow = "DMRGateway";
    $dmrMasterHost = "127.0.0.1,none,62031,$dmrMasterNow"
    ?>
    <h2 class="ConfSec"><?php echo __( 'DMR Configuration' );?></h2>
    <input type="hidden" name="dmrEmbeddedLCOnly" value="OFF" />
    <input type="hidden" name="dmrBeacon" value="OFF" />
    <input type="hidden" name="dmrDumpTAData" value="OFF" />
    <input type="hidden" name="dmrGatewayXlxEn" value="OFF" />
    <input type="hidden" name="dmrGatewayNet1En" value="OFF" />
    <input type="hidden" name="dmrGatewayNet2En" value="OFF" />
    <input type="hidden" name="dmrGatewayNet4En" value="OFF" />
    <input type="hidden" name="dmrGatewayNet5En" value="OFF" />
    <input type="hidden" name="dmrDMRnetJitterBufer" value="OFF" />
    <input type="hidden" name="dmrMasterHost" value="<?php echo $dmrMasterHost; ?>" />
    <table>
    <tr>
    </tr>

    <tr>
    <th class='config_head' colspan="4">BrandMeister Network Settings</th>
    </tr>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'BrandMeister Master' );?>:<span><b>BrandMeister Master</b>Set your preferred DMR master here</span></a></td>
    <td style="text-align: left;" colspan="3"><select name="dmrMasterHost1" class="dmrMasterHost1">
<?php
	$dmrMasterFile1 = fopen("/usr/local/etc/DMR_Hosts.txt", "r");
	$testMMDVMdmrMaster1 = $configdmrgateway['DMR Network 1']['Address'];
	$testMMDVMdmrMaster1Port = $configdmrgateway['DMR Network 1']['Port'];
	while (!feof($dmrMasterFile1)) {
		$dmrMasterLine1 = fgets($dmrMasterFile1);
                $dmrMasterHost1 = preg_split('/\s+/', $dmrMasterLine1);
                if ((strpos($dmrMasterHost1[0], '#') === FALSE ) && (substr($dmrMasterHost1[0], 0, 2) == "BM") && ($dmrMasterHost1[0] != '')) {
                        if (($testMMDVMdmrMaster1 == $dmrMasterHost1[2]) && ($testMMDVMdmrMaster1Port == $dmrMasterHost1[4])) { echo "      <option value=\"$dmrMasterHost1[2],$dmrMasterHost1[3],$dmrMasterHost1[4],$dmrMasterHost1[0]\" selected=\"selected\">$dmrMasterHost1[0]</option>\n"; }
                        else { echo "      <option value=\"$dmrMasterHost1[2],$dmrMasterHost1[3],$dmrMasterHost1[4],$dmrMasterHost1[0]\">$dmrMasterHost1[0]</option>\n"; }
                }
	}
	fclose($dmrMasterFile1);
?>
    </select></td></tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">BM Hotspot Security:<span><b>BrandMeister Password</b>Enter your Security password for BrandMeister, and make sure you already configured this using BM Self Care.</span></a></td>
      <td align="left" colspan="2">
        <input type="password" name="bmHSSecurity" id="bmHSSecurity" size="30" maxlength="30" value="<?php if (isset($configModem['BrandMeister']['Password'])) {echo $configModem['BrandMeister']['Password'];} ?>"></input>
        <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-bm-password"></span>
      </td>
      <td align="left"><a href="https://brandmeister.network/?page=register" target="_new">Register for a Brandmeister Account...</a></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'BrandMeister Network' );?> ESSID:<span><b>BrandMeister Extended ID</b>This is the extended ID, to make your DMR ID 9 digits long</span></a></td>
    <td align="left" colspan="3">
<?php
	if (isset($configdmrgateway['DMR Network 1']['Id'])) {
		if (strlen($configdmrgateway['DMR Network 1']['Id']) > strlen($configmmdvm['General']['Id'])) {
			$brandMeisterESSID = substr($configdmrgateway['DMR Network 1']['Id'], -2);
		} else {
			$brandMeisterESSID = "None";
		}
	} else {
		if (isset($configmmdvm['General']['Id'])) {
			if (strlen($configmmdvm['General']['Id']) == 9) {
				$brandMeisterESSID = substr($configmmdvm['General']['Id'], -2);
			} else {
				$brandMeisterESSID = "None";
			}
		} else {
			$brandMeisterESSID = "None";
		}
	}

	if (isset($configmmdvm['General']['Id'])) { if ($configmmdvm['General']['Id'] !== "1234567") { echo substr($configmmdvm['General']['Id'], 0, 7); } }
	echo "<select name=\"bmExtendedId\">\n";
	if ($brandMeisterESSID == "None") { echo "      <option value=\"None\" selected=\"selected\">None</option>\n"; } else { echo "      <option value=\"None\">None</option>\n"; }
	for ($brandMeisterESSIDInput = 1; $brandMeisterESSIDInput <= 99; $brandMeisterESSIDInput++) {
		$brandMeisterESSIDInput = str_pad($brandMeisterESSIDInput, 2, "0", STR_PAD_LEFT);
		if ($brandMeisterESSID === $brandMeisterESSIDInput) {
			echo "      <option value=\"$brandMeisterESSIDInput\" selected=\"selected\">$brandMeisterESSIDInput</option>\n";
		} else {
			echo "      <option value=\"$brandMeisterESSIDInput\">$brandMeisterESSIDInput</option>\n";
		}
	}
	echo "</select>\n";
?>
    </td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'BrandMeister Network' );?> Enable:<span><b>BrandMeister Network Enable</b>Enable or disable BrandMeister Network</span></a></td>
    <td align="left" colspan="3">
    <?php if ($configdmrgateway['DMR Network 1']['Enabled'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet1En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet1En\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet1EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet1En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable BrandMeister DMR\" aria-checked=\"true\" onKeyPress=\"toggleDmrGatewayNet1EnCheckbox()\" onclick=\"toggleDmrGatewayNet1EnCheckbox()\" for=\"toggle-dmrGatewayNet1En\"><font style=\"font-size:0px\">Enable Brandmeister DMR</font></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet1En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet1En\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet1EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet1En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable BrandMeister DMR\" aria-checked=\"false\" onKeyPress=\"toggleDmrGatewayNet1EnCheckbox()\" onclick=\"toggleDmrGatewayNet1EnCheckbox()\" for=\"toggle-dmrGatewayNet1En\"><font style=\"font-size:0px\">Enable Brandmeister DMR</font></label></div>\n"; } ?>
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'BrandMeister Network' );?>:<span><b>BrandMeister Dashboards</b>Direct links to your BrandMeister Dashboards</span></a></td>
    <td colspan="3" align="left">
    <a href="https://brandmeister.network/?page=device&amp;id=<?php if (isset($configdmrgateway['DMR Network 1']['Id'])) { echo $configdmrgateway['DMR Network 1']['Id']; } else { echo $configmmdvm['General']['Id']; } ?>" target="_new">Hotspot/Repeater Information</a> |
    <a href="https://brandmeister.network/?page=device-edit&amp;id=<?php if (isset($configdmrgateway['DMR Network 1']['Id'])) { echo $configdmrgateway['DMR Network 1']['Id']; } else { echo $configmmdvm['General']['Id']; } ?>" target="_new">Edit Hotspot/Repeater (BrandMeister Selfcare)</a>
    </td>
    </tr>
<?php
$bmAPIkeyFile = '/etc/bmapi.key';
if (!@file_exists($bmAPIkeyFile) && !@fopen($bmAPIkeyFile,'r')) {
?>
    <tr>
    <td align="left"><a href="#" class="tooltip2">Brandmeister Manager:<span><b>Brandmeister Manager</b>BrandMeister Manager API Info</span></a></td>
    <td align="left" colspan="3" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'>
       To use the BrandMeister Manager, you need a <a href="https://brandmeister.network/?page=profile-api" target="_new">BM API Key</a>, and then you need to enter it in the <a href="/admin/advanced/fulledit_bmapikey.php">BM API Key Editor</a>.
    </td>
    <tr>
<?php } ?>
    <th class='config_head' colspan="4">DMR+/FreeDMR/HBlink/Custom Network Settings</th>
    </tr>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">DMR+ / FreeDMR / HBlink / Custom Master:<span><b>DMR+ / FreeDMR / HBlink / Custom Master</b>Set your preferred DMR master here</span></a></td>
    <td style="text-align: left;" colspan="3"><select name="dmrMasterHost2" class="dmrMasterHost2">
<?php
	$dmrMasterFile2 = fopen("/usr/local/etc/DMR_Hosts.txt", "r");
	$testMMDVMdmrMaster2= $configdmrgateway['DMR Network 2']['Address'];
	$testMMDVMdmrMaster2Port = $configdmrgateway['DMR Network 2']['Port'];
	while (!feof($dmrMasterFile2)) {
		$dmrMasterLine2 = fgets($dmrMasterFile2);
                $dmrMasterHost2 = preg_split('/\s+/', $dmrMasterLine2);
	if ((strpos($dmrMasterHost2[0], '#') === FALSE ) &&
	    (substr($dmrMasterHost2[0], 0, 3) != "BM_" &&
	    substr($dmrMasterHost2[0], 0, 4) != "XLX_" &&
	    substr($dmrMasterHost2[0], 0, 8) != "SystemX_" &&
	    $dmrMasterHost2[0] != "DMRGateway" &&
	    $dmrMasterHost2[0] != "DMR2YSF" &&
	    $dmrMasterHost2[0] != "DMR2NXDN" &&
	    substr($dmrMasterHost2[0], 0, 5) != "TGIF_") &&
	    $dmrMasterHost2[0] != '') {
                        if (($testMMDVMdmrMaster2 == $dmrMasterHost2[2]) && ($testMMDVMdmrMaster2Port == $dmrMasterHost2[4])) { echo "      <option value=\"$dmrMasterHost2[2],$dmrMasterHost2[3],$dmrMasterHost2[4],$dmrMasterHost2[0]\" selected=\"selected\">$dmrMasterHost2[0]</option>\n"; }
                        else { echo "      <option value=\"$dmrMasterHost2[2],$dmrMasterHost2[3],$dmrMasterHost2[4],$dmrMasterHost2[0]\">$dmrMasterHost2[0]</option>\n"; }
                }
	}
	fclose($dmrMasterFile2);
?>
    </select></td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Network Options:<span><b>DMR+ / FreeDMR / HBlink / Custom Network</b>Set your options= for DMR+ / FreeDMR / HBlink / Custom Host here</span></a></td>
    <td align="left" colspan="3">
    Options=<input type="text" name="dmrNetworkOptions" size="85" maxlength="250" value="<?php if (isset($configdmrgateway['DMR Network 2']['Options'])) { echo $configdmrgateway['DMR Network 2']['Options']; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">ESSID:<span><b>DMR+ / FreeDMR / HBlink / Custom Host Extended ID</b>This is the extended ID, to make your DMR ID 8 digits long</span></a></td>
    <td align="left" colspan="3">
<?php
	if (isset($configdmrgateway['DMR Network 2']['Id'])) {
		if (strlen($configdmrgateway['DMR Network 2']['Id']) > strlen($configmmdvm['General']['Id'])) {
			$dmrPlusESSID = substr($configdmrgateway['DMR Network 2']['Id'], -2);
		} else {
			$dmrPlusESSID = "None";
		}
	} else {
		if (isset($configmmdvm['General']['Id'])) {
			if (strlen($configmmdvm['General']['Id']) == 9) {
				$dmrPlusESSID = substr($configmmdvm['General']['Id'], -2);
			} else {
				$dmrPlusESSID = "None";
			}
		} else {
			$dmrPlusESSID = "None";
		}
	}

	if (isset($configmmdvm['General']['Id'])) { if ($configmmdvm['General']['Id'] !== "1234567") { echo substr($configmmdvm['General']['Id'], 0, 7); } }
	echo "<select name=\"dmrPlusExtendedId\">\n";
	if ($dmrPlusESSID == "None") { echo "      <option value=\"None\" selected=\"selected\">None</option>\n"; } else { echo "      <option value=\"None\">None</option>\n"; }
	for ($dmrPlusESSIDInput = 1; $dmrPlusESSIDInput <= 99; $dmrPlusESSIDInput++) {
		$dmrPlusESSIDInput = str_pad($dmrPlusESSIDInput, 2, "0", STR_PAD_LEFT);
		if ($dmrPlusESSID === $dmrPlusESSIDInput) {
			echo "      <option value=\"$dmrPlusESSIDInput\" selected=\"selected\">$dmrPlusESSIDInput</option>\n";
		} else {
			echo "      <option value=\"$dmrPlusESSIDInput\">$dmrPlusESSIDInput</option>\n";
		}
	}
	echo "</select>\n";
?>
    </td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">DMR+ / FreeDMR / HBlink / Custom Network Enable:<span><b>DMR+ / FreeDMR / HBlink / Custom Network Enable</b></span></a></td>
    <td align="left" colspan="2">
    <?php if ($configdmrgateway['DMR Network 2']['Enabled'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet2En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet2En\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet2EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet2En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable DMR+ / FreeDMR / HBlink\" aria-checked=\"true\" onKeyPress=\"toggleDmrGatewayNet2EnCheckbox()\" onclick=\"toggleDmrGatewayNet2EnCheckbox()\" for=\"toggle-dmrGatewayNet2En\"><font style=\"font-size:0px\">Enable DMR+ / FreeDMR / HBlink</font></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet2En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet2En\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet2EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet2En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable DMR+ / FreeDMR / HBlink\" aria-checked=\"false\" onKeyPress=\"toggleDmrGatewayNet2EnCheckbox()\" onclick=\"toggleDmrGatewayNet2EnCheckbox()\" for=\"toggle-dmrGatewayNet2En\"><font style=\"font-size:0px\">Enable DMR+ / FreeDMR / HBlink</font></label></div>\n"; } ?>
    </td>
    <td align="left" colspan="1"><i class="fa fa-exclamation-circle"></i> Uses "8" talkgroup prefix</td>
    </tr>

    <tr>
    <th class='config_head' colspan="4">SystemX Network Settings</th>
    </tr>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">SystemX Master:<span><b>SystemX Master</b>Set your preferred DMR master here</span></a></td>
    <td style="text-align: left;" colspan="3"><select name="dmrMasterHost5" class="dmrMasterHost5">
<?php
	$dmrMasterFile5 = fopen("/usr/local/etc/DMR_Hosts.txt", "r");
	$testMMDVMdmrMaster5= $configdmrgateway['DMR Network 5']['Address'];
	$testMMDVMdmrMaster5Port = $configdmrgateway['DMR Network 5']['Port'];
	while (!feof($dmrMasterFile5)) {
		$dmrMasterLine5 = fgets($dmrMasterFile5);
                $dmrMasterHost5 = preg_split('/\s+/', $dmrMasterLine5);
                if ((strpos($dmrMasterHost5[0], '#') === FALSE ) && (substr($dmrMasterHost5[0], 0, 7) == "SystemX") && ($dmrMasterHost5[0] != '')) {
                        if (($testMMDVMdmrMaster5 == $dmrMasterHost5[2]) && ($testMMDVMdmrMaster5Port == $dmrMasterHost5[4])) { echo "      <option value=\"$dmrMasterHost5[2],$dmrMasterHost5[3],$dmrMasterHost5[4],$dmrMasterHost5[0]\" selected=\"selected\">$dmrMasterHost5[0]</option>\n"; }
                        else { echo "      <option value=\"$dmrMasterHost5[2],$dmrMasterHost5[3],$dmrMasterHost5[4],$dmrMasterHost5[0]\">$dmrMasterHost5[0]</option>\n"; }
                }
	}
	fclose($dmrMasterFile5);
?>
    </select></td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Network Options:<span><b>SystemX Network</b>Set your options= for SystemX here</span></a></td>
    <td align="left" colspan="3">
    Options=<input type="text" name="dmrNetworkOptions5" size="85" maxlength="250" value="<?php if (isset($configdmrgateway['DMR Network 5']['Options'])) { echo $configdmrgateway['DMR Network 5']['Options']; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">ESSID:<span><b>SystemX Extended ID</b>This is the extended ID, to make your DMR ID 8 digits long</span></a></td>
    <td align="left" colspan="3">
<?php
	if (isset($configdmrgateway['DMR Network 5']['Id'])) {
		if (strlen($configdmrgateway['DMR Network 5']['Id']) > strlen($configmmdvm['General']['Id'])) {
			$SysXESSID = substr($configdmrgateway['DMR Network 5']['Id'], -2);
		} else {
			$SysXESSID = "None";
		}
	} else {
		if (isset($configmmdvm['General']['Id'])) {
			if (strlen($configmmdvm['General']['Id']) == 9) {
				$SysXESSID = substr($configmmdvm['General']['Id'], -2);
			} else {
				$SysXESSID = "None";
			}
		} else {
			$SysXESSID = "None";
		}
	}

	if (isset($configmmdvm['General']['Id'])) { if ($configmmdvm['General']['Id'] !== "1234567") { echo substr($configmmdvm['General']['Id'], 0, 7); } }
	echo "<select name=\"SystemXExtendedId\">\n";
	if ($SysXESSID == "None") { echo "      <option value=\"None\" selected=\"selected\">None</option>\n"; } else { echo "      <option value=\"None\">None</option>\n"; }
	for ($SysXESSIDInput = 1; $SysXESSIDInput <= 99; $SysXESSIDInput++) {
		$SysXESSIDInput = str_pad($SysXESSIDInput, 2, "0", STR_PAD_LEFT);
		if ($SysXESSID === $SysXESSIDInput) {
			echo "      <option value=\"$SysXESSIDInput\" selected=\"selected\">$SysXESSIDInput</option>\n";
		} else {
			echo "      <option value=\"$SysXESSIDInput\">$SysXESSIDInput</option>\n";
		}
	}
	echo "</select>\n";
?>
    </td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">SystemX Enable:<span><b>SystemX Network Enable</b></span></a></td>
    <td align="left" colspan="2">
    <?php if ($configdmrgateway['DMR Network 5']['Enabled'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet5En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet5En\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet5EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet5En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable SystemX\" aria-checked=\"true\" onKeyPress=\"toggleDmrGatewayNet5EnCheckbox()\" onclick=\"toggleDmrGatewayNet5EnCheckbox()\" for=\"toggle-dmrGatewayNet5En\"><font style=\"font-size:0px\">Enable SystemX</font></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet5En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet5En\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet5EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet5En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable SystemX\" aria-checked=\"false\" onKeyPress=\"toggleDmrGatewayNet5EnCheckbox()\" onclick=\"toggleDmrGatewayNet5EnCheckbox()\" for=\"toggle-dmrGatewayNet5En\"><font style=\"font-size:0px\">Enable SystemX</font></label></div>\n"; } ?>
    </td>
    <td align="left" colspan="1"><i class="fa fa-exclamation-circle"></i> Uses "4" talkgroup prefix</td>
    </tr>
	</tr>
	<tr>
    <td align="left"><a class="tooltip2" href="#">SystemX Network:<span><b>SystemX Tools</b>Direct links to your SystemX tools</span></a></td>
    <td colspan="3" align="left">
    <a href="https://freestar.network/tools/systemx-options-generator.php" target="_new">Options Generator</a>
    </td>
    </tr>

    <tr>
    <th class='config_head' colspan="4">TGIF Network Settings</th>
    </tr>
    <tr>
    </tr>
    <input type="hidden" name="dmrMasterHost4" value="OFF" />
    <tr>
    <td align="left"><a class="tooltip2" href="#">ESSID:<span><b>TGIF Extended ID</b>This is the extended ID, to make your DMR ID 8 digits long</span></a></td>
    <td align="left" colspan="3">
<?php
	if (isset($configdmrgateway['DMR Network 4']['Id'])) {
		if (strlen($configdmrgateway['DMR Network 4']['Id']) > strlen($configmmdvm['General']['Id'])) {
			$tgifESSID = substr($configdmrgateway['DMR Network 4']['Id'], -2);
		} else {
			$tgifESSID = "None";
		}
	} else {
		if (isset($configmmdvm['General']['Id'])) {
			if (strlen($configmmdvm['General']['Id']) == 9) {
				$tgifESSID = substr($configmmdvm['General']['Id'], -2);
			} else {
				$tgifESSID = "None";
			}
		} else {
			$tgifESSID = "None";
		}
	}

	if (isset($configmmdvm['General']['Id'])) { if ($configmmdvm['General']['Id'] !== "1234567") { echo substr($configmmdvm['General']['Id'], 0, 7); } }
	echo "<select name=\"tgifExtendedId\">\n";
	if ($tgifESSID == "None") { echo "      <option value=\"None\" selected=\"selected\">None</option>\n"; } else { echo "      <option value=\"None\">None</option>\n"; }
	for ($tgifESSIDInput = 1; $tgifESSIDInput <= 99; $tgifESSIDInput++) {
		$tgifESSIDInput = str_pad($tgifESSIDInput, 2, "0", STR_PAD_LEFT);
		if ($tgifESSID === $tgifESSIDInput) {
			echo "      <option value=\"$tgifESSIDInput\" selected=\"selected\">$tgifESSIDInput</option>\n";
		} else {
			echo "      <option value=\"$tgifESSIDInput\">$tgifESSIDInput</option>\n";
		}
	}
	echo "</select>\n";
?>
    </td></tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">TGIF Security Key:<span><b>TGIF Security Key</b>Override the default login with your own TGIF security key, Make sure you already configured this using TGIF Self Care. Empty the field to use the default.</span></a></td>
      <td align="left" colspan="2">
        <input type="password" name="tgifHSSecurity" id="tgifHSSecurity" size="30" maxlength="30" value="<?php if (isset($configModem['TGIF']['Password'])) {echo $configModem['TGIF']['Password'];} ?>"></input>
	<span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-tgif-password"></span>
      </td>
      <td align="left"><a href="https://tgif.network/profile.php?tab=Security" target="_new">Get your TGIF Security Key here...</a></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">TGIF Network Enable:<span><b>TGIF Network Enable</b></span></a></td>
    <td align="left" colspan="2">
    <?php if ($configdmrgateway['DMR Network 4']['Enabled'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet4En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet4En\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet4EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet4En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable TGIF\" aria-checked=\"true\" onKeyPress=\"toggleDmrGatewayNet4EnCheckbox()\" onclick=\"toggleDmrGatewayNet4EnCheckbox()\" for=\"toggle-dmrGatewayNet4En\"><font style=\"font-size:0px\">Enable TGIF/font></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayNet4En\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayNet4En\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayNet4EnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayNet4En\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable TGIF\" aria-checked=\"false\" onKeyPress=\"toggleDmrGatewayNet4EnCheckbox()\" onclick=\"toggleDmrGatewayNet4EnCheckbox()\" for=\"toggle-dmrGatewayNet4En\"><font style=\"font-size:0px\">Enable TGIF</font></label></div>\n"; } ?>
    </td>
    <td align="left" colspan="1"><i class="fa fa-exclamation-circle"></i> Uses "5" talkgroup prefix</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">TGIF Network:<span><b>TGIF Dashboards</b>Direct links to your TGIF Dashboard</span></a></td>
    <td colspan="3" align="left">
    <a href="https://tgif.network/profile.php?tab=SelfCare" target="_new">TGIF SelfCare</a>
    </td>
    </tr>

    <tr>
    <th class='config_head' colspan="4">XLX Network Settings</th>
    </tr>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'XLX Master' );?>:<span><b>XLX Master</b>Set your preferred XLX master here</span></a></td>
    <td style="text-align: left;" colspan="3"><select name="dmrMasterHost3" class="dmrMasterHost3">
<?php
	$dmrMasterFile3 = fopen("/usr/local/etc/DMR_Hosts.txt", "r");
	if (isset($configdmrgateway['XLX Network 1']['Address'])) { $testMMDVMdmrMaster3= $configdmrgateway['XLX Network 1']['Address']; }
	if (isset($configdmrgateway['XLX Network']['Startup'])) { $testMMDVMdmrMaster3= $configdmrgateway['XLX Network']['Startup']; }
	while (!feof($dmrMasterFile3)) {
		$dmrMasterLine3 = fgets($dmrMasterFile3);
                $dmrMasterHost3 = preg_split('/\s+/', $dmrMasterLine3);
                if ((strpos($dmrMasterHost3[0], '#') === FALSE ) && (substr($dmrMasterHost3[0], 0, 3) == "XLX") && ($dmrMasterHost3[0] != '')) {
                        if ($testMMDVMdmrMaster3 == $dmrMasterHost3[2]) { echo "      <option value=\"$dmrMasterHost3[2],$dmrMasterHost3[3],$dmrMasterHost3[4],$dmrMasterHost3[0]\" selected=\"selected\">$dmrMasterHost3[0]</option>\n"; }
			if ('XLX_'.$testMMDVMdmrMaster3 == $dmrMasterHost3[0]) { echo "      <option value=\"$dmrMasterHost3[2],$dmrMasterHost3[3],$dmrMasterHost3[4],$dmrMasterHost3[0]\" selected=\"selected\">$dmrMasterHost3[0]</option>\n"; }
                        else { echo "      <option value=\"$dmrMasterHost3[2],$dmrMasterHost3[3],$dmrMasterHost3[4],$dmrMasterHost3[0]\">$dmrMasterHost3[0]</option>\n"; }
                }
	}
	fclose($dmrMasterFile3);
?>
    </select></td></tr>
    <?php if (isset($configdmrgateway['XLX Network 1']['Startup'])) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">XLX Startup TG:<span><b>XLX Startup TG</b></span></a></td>
    <td align="left" colspan="3"><select name="dmrMasterHost3Startup" class="dmrMasterHost3Startup">
<?php
	if (isset($configdmrgateway['XLX Network 1']['Startup'])) {
		echo '      <option value="None">None</option>'."\n";
	}
	else {
		echo '      <option value="None" selected="selected">None</option>'."\n";
	}
	for ($xlxSu = 1; $xlxSu <= 26; $xlxSu++) {
		$xlxSuVal = '40'.sprintf('%02d', $xlxSu);
		if ((isset($configdmrgateway['XLX Network 1']['Startup'])) && ($configdmrgateway['XLX Network 1']['Startup'] == $xlxSuVal)) {
			echo '      <option value="'.$xlxSuVal.'" selected="selected">'.$xlxSuVal.'</option>'."\n";
		}
		else {
			echo '      <option value="'.$xlxSuVal.'">'.$xlxSuVal.'</option>'."\n";
		}
	}
?>
    </select></td></tr>
    <?php } ?>
    <?php if (isset($configdmrgateway['XLX Network']['TG'])) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">XLX Startup Module:<span><b>XLX Startup Module override</b>Default will use the host file option, or override it here.</span></a></td>
    <td align="left" colspan="3"><select class="ModSel" name="dmrMasterHost3StartupModule">
<?php
	if ((isset($configdmrgateway['XLX Network']['Module'])) && ($configdmrgateway['XLX Network']['Module'] != "@")) {
		echo '        <option value="'.$configdmrgateway['XLX Network']['Module'].'" selected="selected">'.$configdmrgateway['XLX Network']['Module'].'</option>'."\n";
		echo '        <option value="Default">Default</option>'."\n";
		echo '        <option value="@">None</option>'."\n";
	} elseif ((isset($configdmrgateway['XLX Network']['Module'])) && ($configdmrgateway['XLX Network']['Module'] == "@")) {
		echo '        <option value="Default">Default</option>'."\n";
		echo '        <option value="@" selected="selected">None</option>'."\n";
	} else {
		echo '        <option value="Default" selected="selected">Default</option>'."\n";
		echo '        <option value=" ">None</option>'."\n";
	}
?>
	<option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
        <option value="E">E</option>
        <option value="F">F</option>
        <option value="G">G</option>
        <option value="H">H</option>
        <option value="I">I</option>
        <option value="J">J</option>
        <option value="K">K</option>
        <option value="L">L</option>
        <option value="M">M</option>
        <option value="N">N</option>
        <option value="O">O</option>
        <option value="P">P</option>
        <option value="Q">Q</option>
        <option value="R">R</option>
        <option value="S">S</option>
        <option value="T">T</option>
        <option value="U">U</option>
        <option value="V">V</option>
        <option value="W">W</option>
        <option value="X">X</option>
        <option value="Y">Y</option>
        <option value="Z">Z</option>
    </select></td>
    </tr>
    <?php } ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Time Slot:<span><b>Time Slot</b>If running duplex, select which timeslot to use for XLX traffic.</span></a></td>
    <td align="left" colspan="3">
    <?php if ($configmmdvm['DMR Network']['Slot1'] == "1") { ?>
      <input type="radio" name="xlxTimeSlot" value="1" id="xlxTS1" <?php if ($configdmrgateway['XLX Network']['Slot'] == "1") {  echo 'checked="checked"'; } ?> />
        <label for="xlxTS1">TS1</label>
    <?php } else { ?>
      <input type="radio" name="xlxTimeSlot" value="1" id="xlxTS1" disabled="disabled" />
        <label for="xlxTS1">TS1</label>
    <?php } ?>
      <input type="radio" name="xlxTimeSlot" value="2" id="xlxTS2" <?php if ($configdmrgateway['XLX Network']['Slot'] == "2") { echo 'checked="checked"'; } ?> />
        <label for="xlxTS2">TS2</label>
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'XLX Master Enable' );?>:<span><b>XLX Master Enable</b>Turn your XLX connection on or off.</span></a></td>
    <td align="left" colspan="3">
    <?php
    if ((isset($configdmrgateway['XLX Network 1']['Enabled'])) && ($configdmrgateway['XLX Network 1']['Enabled'] == 1)) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayXlxEn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayXlxEn\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayXlxEnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayXlxEn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable XLX Network\" aria-checked=\"true\" onKeyPress=\"toggleDmrGatewayXlxEnCheckbox()\" onclick=\"toggleDmrGatewayXlxEnCheckbox()\" for=\"toggle-dmrGatewayXlxEn\"><font style=\"font-size:0px\">Enable XLX via DMR</font></label></div>\n"; }
    else if ((isset($configdmrgateway['XLX Network']['Enabled'])) && ($configdmrgateway['XLX Network']['Enabled'] == 1)) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayXlxEn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayXlxEn\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayXlxEnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayXlxEn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable XLX Network\" aria-checked=\"true\" onKeyPress=\"toggleDmrGatewayXlxEnCheckbox()\" onclick=\"toggleDmrGatewayXlxEnCheckbox()\" for=\"toggle-dmrGatewayXlxEn\"><font style=\"font-size:0px\">Enable XLX via DMR</font></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayXlxEn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayXlxEn\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrGatewayXlxEnCheckboxCr." /><label id=\"aria-toggle-dmrGatewayXlxEn\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable XLX Network\" aria-checked=\"false\" onKeyPress=\"toggleDmrGatewayXlxEnCheckbox()\" onclick=\"toggleDmrGatewayXlxEnCheckbox()\" for=\"toggle-dmrGatewayXlxEn\"><font style=\"font-size:0px\">Enable XLX via DMR</font></label></div>\n"; } ?>
    </td></tr>

    <tr>
    <th class='config_head' colspan="4">General DMR Settings</th>
    </tr>
    <?php if(isWPSDrepeater() == 1) { // repeater-only ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">DMR Roaming Beacon:<span><b>Enable DMR Roaming Beacon</b>Enable DMR Roaming Beacons; Used for repeaters</span></a></td>
    <?php
      if ($configmmdvm['DMR']['Beacons'] == 1) {
        echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-dmrbeacon\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"DMRBeaconEnable\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrBeaconCr." /><label id=\"aria-toggle-dmrbeacon\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable Beaconing\" aria-checked=\"true\" onKeyPress=\"toggleDmrBeacon()\" onclick=\"toggleDmrBeacon()\" for=\"toggle-dmrbeacon\"><font style=\"font-size:0px\">Enable DMR Beaconing</font></label></div>\n";
      } else {
        echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-dmrbeacon\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"DMRBeaconEnable\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrBeaconCr." /><label id=\"aria-toggle-dmrbeacon\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable Beaconing\" aria-checked=\"true\" onKeyPress=\"toggleDmrBeacon()\" onclick=\"toggleDmrBeacon()\" for=\"toggle-dmrbeacon\"><font style=\"font-size:0px\">Enable DMR Beaconing</font></label></div>\n";
      }
   ?>
  </td>
<td align="left" colspan="2">
<div style="display:block;text-align:left;">
    <div style="display:block;">
        <div style="display:block;">
            <div style="display: inline-block;vertical-align: middle;">
                <input name="DMRBeaconModeNet" id="beacon-service-selection" value="DMRBeaconModeNet" type="checkbox"
		      <?php if($configmmdvm['DMR']['BeaconInterval'] == NULL) { echo(' checked="checked"'); } ?>>
        	<label for="beacon-service-selection" style="display: inline-block;"> Use Network Beacon Mode (vs. timed interval mode)</label>
            </div>
        </div>
    </div>
</div>
</td>
</tr>
<?php } ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'DMR Color Code' );?>:<span><b>DMR Color Code</b>Set your DMR Color Code here</span></a></td>
    <td style="text-align: left;" colspan="3"><select name="dmrColorCode">
	<?php for ($dmrColorCodeInput = 0; $dmrColorCodeInput <= 15; $dmrColorCodeInput++) {
		if ($configmmdvm['DMR']['ColorCode'] == $dmrColorCodeInput) { echo "<option selected=\"selected\" value=\"$dmrColorCodeInput\">$dmrColorCodeInput</option>\n"; }
		else {echo "      <option value=\"$dmrColorCodeInput\">$dmrColorCodeInput</option>\n"; }
	} ?>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'DMR EmbeddedLCOnly' );?>:<span><b>DMR EmbeddedLCOnly</b>Turn ON to disable extended message support, including GPS and Talker Alias data. This can help reduce problems with some DMR Radios that do not support such features.</span></a></td>
    <td align="left" colspan="3">
    <?php if ($configmmdvm['DMR']['EmbeddedLCOnly'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrEmbeddedLCOnly\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrEmbeddedLCOnly\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrEmbeddedLCOnlyCr." /><label id=\"aria-toggle-dmrEmbeddedLCOnly\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable DMR Embedded LC Only\" aria-checked=\"true\" onKeyPress=\"toggleDmrEmbeddedLCOnly()\" onclick=\"toggleDmrEmbeddedLCOnly()\" for=\"toggle-dmrEmbeddedLCOnly\"><font style=\"font-size:0px\">Enable DMR Embedded LC only</font></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrEmbeddedLCOnly\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrEmbeddedLCOnly\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrEmbeddedLCOnlyCr." /><label id=\"aria-toggle-dmrEmbeddedLCOnly\" role=\"checkbox\" tabindex=\"0\" aria-label=\"Enable DMR Embedded LC Only\" aria-checked=\"false\" onKeyPress=\"toggleDmrEmbeddedLCOnly()\" onclick=\"toggleDmrEmbeddedLCOnly()\" for=\"toggle-dmrEmbeddedLCOnly\"><font style=\"font-size:0px\">Enable DMR Embedded LC Only</font></label></div>\n"; } ?>
    </td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'DMR DumpTAData' );?>:<span><b>DMR DumpTAData</b>Turn ON to dump GPS and Talker Alias data to MMDVMHost log file.</span></a></td>
    <td align="left" colspan="3">
    <?php if ($configmmdvm['DMR']['DumpTAData'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrDumpTAData\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrDumpTAData\" value=\"ON\" checked=\"checked\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrDumpTADataCr." /><label id=\"aria-toggle-dmrDumpTAData\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR Dump TA Data\" aria-checked=\"true\" onKeyPress=\"toggleDmrDumpTAData()\" onclick=\"toggleDmrDumpTAData()\" for=\"toggle-dmrDumpTAData\"><font style=\"font-size:0px\">DMR Dump T-A Data</font></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrDumpTAData\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrDumpTAData\" value=\"ON\" aria-hidden=\"true\" tabindex=\"-1\" ".$toggleDmrDumpTADataCr." /><label id=\"aria-toggle-dmrDumpTAData\" role=\"checkbox\" tabindex=\"0\" aria-label=\"DMR Dump TA Data\" aria-checked=\"false\" onKeyPress=\"toggleDmrDumpTAData()\" onclick=\"toggleDmrDumpTAData()\" for=\"toggle-dmrDumpTAData\"><font style=\"font-size:0px\">DMR Dump T-A Data</font></label></div>\n"; } ?>
    </td></tr>
    </table>

    <br /><br />

<?php } ?>

<?php if (file_exists('/etc/dstar-radio.mmdvmhost') && $configmmdvm['P25 Network']['Enable'] == 1) {
$p25Hosts = fopen("/usr/local/etc/P25Hosts.txt", "r");
	?>
	<h2 class="ConfSec"><?php echo __( 'P25 Configuration' );?></h2>
    <table>
    <tr>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'P25 Hosts' );?>:<span><b>P25 Host</b>Set your preferred P25 Host here</span></a></td>
    <td style="text-align: left;"><select name="p25StartupHost" class="p25StartupHost">
<?php
    if (isset($configp25gateway['Network']['Startup'])) { $testP25Host = $configp25gateway['Network']['Startup']; }
    elseif (isset($configp25gateway['Network']['Static'])) { $testP25Host = $configp25gateway['Network']['Static']; }
    else { $testP25Host = "none"; }
	if ($testP25Host == "") { echo "      <option value=\"none\" selected=\"selected\">None</option>\n"; }
        else { echo "      <option value=\"none\">None</option>\n"; }
	if ($testP25Host == "10") { echo "      <option value=\"10\" selected=\"selected\">10 - Parrot</option>\n"; }
        else { echo "      <option value=\"10\">10 - Parrot</option>\n"; }
        while (!feof($p25Hosts)) {
                $p25HostsLine = fgets($p25Hosts);
                $p25Host = preg_split('/\s+/', $p25HostsLine);
                if ((strpos($p25Host[0], '#') === FALSE ) && ($p25Host[0] != '')) {
                        if ($testP25Host == $p25Host[0]) { echo "      <option value=\"$p25Host[0]\" selected=\"selected\">$p25Host[0] - $p25Host[1]</option>\n"; }
                        else { echo "      <option value=\"$p25Host[0]\">$p25Host[0] - $p25Host[1]</option>\n"; }
                }
        }
        fclose($p25Hosts);
        if (file_exists('/usr/local/etc/P25HostsLocal.txt')) {
		$p25Hosts2 = fopen("/usr/local/etc/P25HostsLocal.txt", "r");
		while (!feof($p25Hosts2)) {
                	$p25HostsLine2 = fgets($p25Hosts2);
                	$p25Host2 = preg_split('/\s+/', $p25HostsLine2);
                	if ((strpos($p25Host2[0], '#') === FALSE ) && ($p25Host2[0] != '')) {
                        	if ($testP25Host == $p25Host2[0]) { echo "      <option value=\"$p25Host2[0]\" selected=\"selected\">$p25Host2[0] - $p25Host2[1]</option>\n"; }
                        	else { echo "      <option value=\"$p25Host2[0]\">$p25Host2[0] - $p25Host2[1]</option>\n"; }
                	}
		}
		fclose($p25Hosts2);
	}
        ?>
    </select></td>
    </tr>
<?php if ($configmmdvm['P25']['NAC']) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'P25 NAC' );?>:<span><b>P25 NAC</b>Set your NAC code here</span></a></td>
    <td align="left"><input type="text" name="p25nac" size="13" maxlength="3" value="<?php echo $configmmdvm['P25']['NAC'];?>" /></td>
    </tr>
<?php } ?>
    </table>

    <br /><br />

<?php } ?>
	
<?php if (file_exists('/etc/dstar-radio.mmdvmhost') && ($configmmdvm['NXDN Network']['Enable'] == 1 || $configdmr2nxdn['Enabled']['Enabled'] == 1) ) { ?>
	<h2 class="ConfSec"><?php echo __( 'NXDN Configuration' );?></h2>
    <table>
      <tr>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo __( 'NXDN Hosts' );?>:<span><b>NXDN Host</b>Set your preferred NXDN Host here</span></a></td>
        <td style="text-align: left;"><select name="nxdnStartupHost" class="nxdnStartupHost">
<?php
	if (file_exists('/etc/nxdngateway')) {
	   $nxdnHosts = fopen("/usr/local/etc/NXDNHosts.txt", "r");
	if (isset($confignxdngateway['Network']['Startup'])) { $testNXDNHost = $confignxdngateway['Network']['Startup']; }
	elseif (isset($confignxdngateway['Network']['Static'])) { $testNXDNHost = $confignxdngateway['Network']['Static']; }
	else { $testNXDNHost = ""; }
		if ($testNXDNHost == "") { echo "      <option value=\"none\" selected=\"selected\">None</option>\n"; }
	        else { echo "      <option value=\"none\">None</option>\n"; }
		if ($testNXDNHost == "10") { echo "      <option value=\"10\" selected=\"selected\">10 - Parrot</option>\n"; }
	        else { echo "      <option value=\"10\">10 - Parrot</option>\n"; }
	        while (!feof($nxdnHosts)) {
	                $nxdnHostsLine = fgets($nxdnHosts);
	                $nxdnHost = preg_split('/\s+/', $nxdnHostsLine);
	                if ((strpos($nxdnHost[0], '#') === FALSE ) && ($nxdnHost[0] != '')) {
	                        if ($testNXDNHost == $nxdnHost[0]) { echo "      <option value=\"$nxdnHost[0]\" selected=\"selected\">$nxdnHost[0] - $nxdnHost[1]</option>\n"; }
	                        else { echo "      <option value=\"$nxdnHost[0]\">$nxdnHost[0] - $nxdnHost[1]</option>\n"; }
	                }
	        }
	        fclose($nxdnHosts);
		if (file_exists('/usr/local/etc/NXDNHostsLocal.txt')) {
			$nxdnHosts2 = fopen("/usr/local/etc/NXDNHostsLocal.txt", "r");
			while (!feof($nxdnHosts2)) {
                		$nxdnHostsLine2 = fgets($nxdnHosts2);
                		$nxdnHost2 = preg_split('/\s+/', $nxdnHostsLine2);
                		if ((strpos($nxdnHost2[0], '#') === FALSE ) && ($nxdnHost2[0] != '')) {
                	        	if ($testNXDNHost == $nxdnHost2[0]) { echo "      <option value=\"$nxdnHost2[0]\" selected=\"selected\">$nxdnHost2[0] - $nxdnHost2[1]</option>\n"; }
                	        	else { echo "      <option value=\"$nxdnHost2[0]\">$nxdnHost2[0] - $nxdnHost2[1]</option>\n"; }
                		}
			}
		fclose($nxdnHosts2);
		}
	} else {
		echo '<option value="176.9.1.168">D2FET Test Host - 176.9.1.168</option>'."\n";
	}
?>
        </select></td>
      </tr>
    <?php if ($configmmdvm['NXDN']['RAN']) { ?>
      <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo __( 'NXDN RAN' );?>:<span><b>NXDN RAN</b>Set your RAN code here, sane values are 1-64</span></a></td>
        <td align="left"><input type="text" name="nxdnran" size="13" maxlength="2" value="<?php echo $configmmdvm['NXDN']['RAN'];?>" /></td>
      </tr>
    <?php } ?>
    </table>

    <br /><br />

			<?php } ?>

			<!-- GPSd -->
			<?php if ( $configdmrgateway['GPSD']['Enable'] == 1 ) { ?>
			    <h2 class="ConfSec"><?php echo __( 'GPSd Configuration' );?></h2>
			    <table>
				<tr>
				</tr>
				<tr>
				    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'GPSd Port' );?>:<span><b>GPSd Server Port</b>Define the GPSd server port here</span></a></td>
				    <td align="left"><input type="text" name="gpsdPort" size="13" maxlength="8" value="<?php echo $configdmrgateway['GPSD']['Port'];?>" /></td>
				</tr>
				<tr>
				    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'GPSd Address' );?>:<span><b>GPSd Server Address</b>Set the GPSd server address here</span></a></td>
				    <td align="left"><input type="text" name="gpsdAddress" size="13" maxlength="128" value="<?php echo $configdmrgateway['GPSD']['Address'];?>" /></td>
				</tr>
			    </table>

			    <br /><br />

			<?php } ?>	

<?php if ( $configmmdvm['POCSAG']['Enable'] == 1 ) { ?>
	<h2 class="ConfSec"><?php echo __( 'POCSAG Configuration' );?></h2>
    <table>
      <tr>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#">DAPNET Server:<span><b>DAPNET Server</b>Set the DAPNET srver here</span></a></td>
	<td style="text-align: left;"><select name="pocsagServer">
        	<option value="<?php echo $configdapnetgw['DAPNET']['Address'];?>" selected="selected"><?php echo $configdapnetgw['DAPNET']['Address'];?></option>
		<option value="dapnet.afu.rwth-aachen.de">dapnet.afu.rwth-aachen.de</option>
		<option value="db0dbn.ig-funk-siebengebirge.de">db0dbn.ig-funk-siebengebirge.de</option>
		<option value="dapnet.db0sda.ampr.org">dapnet.db0sda.ampr.org (HAMNET)</option>
		<option value="node1.dapnet-italia.it">node1.dapnet-italia.it</option>
		</select></td>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#">POCSAG <?php echo __( 'Node Callsign' );?>:<span><b>POCSAG Callsign</b>Set your paging callsign here</span></a></td>
        <td align="left"><input type="text" name="pocsagCallsign" size="13" maxlength="12" value="<?php echo $configdapnetgw['General']['Callsign'];?>" /></td>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#">POCSAG <?php echo __( 'Radio Frequency' );?>:<span><b>POCSAG Frequency</b>Set your paging frequency here</span></a></td>
        <td align="left"><input type="text" id="pocsagFrequency" onkeyup="checkFrequency(); return false;" name="pocsagFrequency" size="13" maxlength="12" value="<?php echo number_format($configmmdvm['POCSAG']['Frequency'], 0, '.', '.');?>" /></td>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#">DAPNET AuthKey:<span><b>DAPNET AuthKey</b>Set your DAPNET AuthKey here</span></a></td>
        <td align="left"><input type="password" name="pocsagAuthKey" id="pocsagAuthKey" size="30" maxlength="50" value="<?php echo $configdapnetgw['DAPNET']['AuthKey'];?>" />
	<span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-dapnet-password"></span>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#">POCSAG Whitelist:<span><b>POCSAG Whitelist</b>Set your POCSAG RIC Whitelist here, if these are set ONLY these RICs will be transmitted. List is comma seperated.</span></a></td>
        <td align="left"><input type="text" name="pocsagWhitelist" size="60" maxlength="350" value="<?php if (isset($configdapnetgw['General']['WhiteList'])) { echo $configdapnetgw['General']['WhiteList']; } ?>" /></td>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#">POCSAG Blacklist:<span><b>POCSAG Blacklist</b>Set your POCSAG RIC Blacklist here, if these are set any other RIC will be transmitted, but not these. List is comma seperated.</span></a></td>
        <td align="left"><input type="text" name="pocsagBlacklist" size="60" maxlength="350" value="<?php if (isset($configdapnetgw['General']['BlackList'])) { echo $configdapnetgw['General']['BlackList']; } ?>" /></td>
      </tr>
    </table>

    <br /><br />

<?php } ?>

<?php if (file_exists('/etc/dstar-radio.mmdvmhost')) { ?>
    <h2 class="ConfSec">Node Access Control</h2>
    <table>
    <tr>
    </tr>
    <tr>
    <td colspan="4" align="left" style='word-wrap: break-word;white-space: normal;font-size:larger;color:#840C24;padding-left: 5px;'><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>Caution: <em>This section is for advanced multi-user hotspot or repeater usage only!</em></b></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Node Type' );?>:<span><b>Node Lock</b>Set the public/private node type. &quot;Private&quot; limits access to your system to your ID/Callsign only, this may be a licence requirement for your country and helps prevent network loops.</span></a></td>
    <td align="left" colspan="2">
    <input type="radio" name="nodeMode" id="nodePriv" value="prv"<?php if ($configmmdvm['DMR']['SelfOnly'] == 1) {echo ' checked="checked"';} ?> />
      <label for="nodePriv" style="display: inline-block;">Private</label>
<?php if (empty($configmmdvm['DMR']['WhiteList'])) { ?>    <input type="radio" name="nodeMode" id="nodePub" value="pub" disabled="diabled" />
      <label for="nodePub" style="display: inline-block;">Semi-Public</label>
<?php } else { ?>
    <input type="radio" name="nodeMode" id="nodePub" value="pub"<?php if ($configmmdvm['DMR']['SelfOnly'] == 0) {echo ' checked="checked"';} ?> />
      <label for="nodePub" style="display: inline-block;">Semi-Public</label>
<?php } ?>
    </td>
    <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Note:</b> <em>Semi-Public mode cannot be enabled without entering at least one allowed DMR/CCS7 ID in the access list below and applying the changes FIRST.</em></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Access List:<span><b>DMR/CCS7 IDs</b>Set the DMR/CCS7 IDs here that should have access to your hotspot, using a comma seperated list.</span></a></td>
    <td align="left" colspan="2"><input type="text" placeholder="7654321" name="confDMRWhiteList" size="50" maxlength="100" value="<?php if (isset($configmmdvm['DMR']['WhiteList'])) { echo $configmmdvm['DMR']['WhiteList']; } ?>" /></td>
    <td align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-question-circle"></i> Enter one, or a comma-separated list of DMR/CCS7 IDs which are allowed access to this hotspot/repeater (required for public functionality). For fully-public/fully-open access without adding each ID, ignore these settings and <a href="https://w0chp.radio/wpsd-faqs/" target="_new">see the FAQs</a>.</td>
    </tr>
    </table>

    <br /><br />

<?php } ?>

    <h2 class="ConfSec"><?php echo __( 'Firewall Configuration' );?></h2>
    <table>
    <tr>
    </tr>
    <tr>
        <td align="left"><a class="tooltip2" href="#">UPnP:<span><b>UPnP</b>Do you want this device to create its own Firewall rules?</span></a></td>
        <?php
        $testupnp = exec('grep "pistar-upnp.service" /etc/crontab | cut -c 1');
        if (substr($testupnp, 0, 1) === '#') {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"uPNP_enabled\" name=\"uPNP\" value=\"ON\" /> <label for=\"uPNP_enabled\">Enabled</label> <input type=\"radio\" id=\"uPNP_disabled\" name=\"uPNP\" value=\"OFF\" checked=\"checked\" /> <label for=\"uPNP_disabled\">Disabled</label></td>\n";
        } else {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"uPNP_enabled\" name=\"uPNP\" value=\"ON\" checked=\"checked\" /> <label for=\"uPNP_enabled\">Enabled</label> <input type=\"radio\" id=\"uPNP_disabled\" name=\"uPNP\" value=\"OFF\" /> <label for=\"uPNP_disabled\">Disabled</label></td>\n";
        }
        ?>
    </tr>
    <tr>
        <td align="left" colspan="3" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-info-circle" aria-hidden="true"></i> <b>Note:</b> <em>The following options cannot be made Public until UPnP is Enabled.</em></td>
    </tr>
    <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo __( 'Dashboard Access' );?>:<span><b>Dashboard Access</b>Do you want the dashboard access to be publicly available? This modifies the uPNP firewall configuration.</span></a></td>
        <?php
        $testPrvPubDash = exec('sudo grep "80 80" /etc/wpsd-upnp-rules | head -1 | cut -c 5');

        if (substr($testPrvPubDash, 0, 1) === '#') {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"dashAccess_private\" name=\"dashAccess\" value=\"PRV\" checked=\"checked\" /> <label for=\"dashAccess_private\">Private</label> <input type=\"radio\" id=\"dashAccess_public\" name=\"dashAccess\" value=\"PUB\" /> <label for=\"dashAccess_public\">Public</label></td>\n";
        } else {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"dashAccess_private\" name=\"dashAccess\" value=\"PRV\" /> <label for=\"dashAccess_private\">Private</label> <input type=\"radio\" id=\"dashAccess_public\" name=\"dashAccess\" value=\"PUB\" checked=\"checked\" /> <label for=\"dashAccess_public\">Public</label></td>\n";
        }
        ?>
    </tr>
    <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo __( 'ircDDBGateway Remote' );?>:<span><b>ircDDB Remote Command Access</b>Do you want the ircDDB remote command access to be publicly available? This modifies the uPNP firewall Configuration.</span></a></td>
        <?php
        $testPrvPubIRC = exec('sudo grep "10022 10022" /etc/wpsd-upnp-rules | head -1 | cut -c 5');

        if (substr($testPrvPubIRC, 0, 1) === '#') {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"ircRCAccess_private\" name=\"ircRCAccess\" value=\"PRV\" checked=\"checked\" /> <label for=\"ircRCAccess_private\">Private</label> <input type=\"radio\" id=\"ircRCAccess_public\" name=\"ircRCAccess\" value=\"PUB\" /> <label for=\"ircRCAccess_public\">Public</label></td>\n";
        } else {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"ircRCAccess_private\" name=\"ircRCAccess\" value=\"PRV\" /> <label for=\"ircRCAccess_private\">Private</label> <input type=\"radio\" id=\"ircRCAccess_public\" name=\"ircRCAccess\" value=\"PUB\" checked=\"checked\" /> <label for=\"ircRCAccess_public\">Public</label></td>\n";
        }
        ?>
    </tr>
    <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo __( 'SSH Access' );?>:<span><b>SSH Access</b>Do you want access to be publicly available over SSH (used for support issues)? This modifies the uPNP firewall Configuration.</span></a></td>
        <?php
        $testPrvPubSSH = exec('sudo grep "22 22" /etc/wpsd-upnp-rules | head -1 | cut -c 5');

        if (substr($testPrvPubSSH, 0, 1) === '#') {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"sshAccess_private\" name=\"sshAccess\" value=\"PRV\" checked=\"checked\" /> <label for=\"sshAccess_private\">Private</label> <input type=\"radio\" id=\"sshAccess_public\" name=\"sshAccess\" value=\"PUB\" /> <label for=\"sshAccess_public\">Public</label></td>\n";
        } else {
            echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"sshAccess_private\" name=\"sshAccess\" value=\"PRV\" /> <label for=\"sshAccess_private\">Private</label> <input type=\"radio\" id=\"sshAccess_public\" name=\"sshAccess\" value=\"PUB\" checked=\"checked\" /> <label for=\"sshAccess_public\">Public</label></td>\n";
        }
        ?>
    </tr>
    </table>



    <?php if (file_exists('/etc/default/hostapd') && file_exists('/sys/class/net/wlan0') || file_exists('/sys/class/net/wlan1') || file_exists('/sys/class/net/wlan0_ap')) { ?>
    <br /><br />
    <h2 class="ConfSec"><?php _e( 'AccessPoint Mode' ); ?></h2>
    <table>
    <tr>
      <td align="left"><a class="tooltip2" href="#">Auto AP:<span><b>Auto AccessPoint</b>Do you want this device to create its own WiFi AccessPoint if it cannot connect to WiFi within 120 seconds after booting?</span></a></td>
      <?php
        if (file_exists('/etc/hostap.off')) {
          echo "   <td align=\"left\"><input type=\"radio\" name=\"autoAP\" value=\"ON\" />On <input type=\"radio\" name=\"autoAP\" value=\"OFF\" checked=\"checked\" />Off</td>\n";
        }
        else {
          echo "   <td align=\"left\"><input type=\"radio\" name=\"autoAP\" value=\"ON\" checked=\"checked\" />On <input type=\"radio\" name=\"autoAP\" value=\"OFF\" />Off</td>\n";
        }
      ?>
      <td align="left"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <em>Note: Reboot Required if changed</em></td>
    </tr>
  </tr>
  </table>
    <?php } ?>

  </form>
  <br />

<?php
	if ($osVer >= 12) { // Bookworm uses NetworkManager, so use our newer wifi-manager...
	    $wifi_page = '<iframe frameborder="0" scrolling="auto" name="wifi" src="wifi-manager.php" width="100%" onload="javascript:resizeIframe(this);">If you can see this message, your browser does not support iFrames, however if you would like to see the content please click <a href="wifi-manager.php">here</a>.</iframe>';
	} else { // Legacy wpa_supp systems (Bullseye) use the legacy wifi config page...
	    $wifi_page = '<iframe frameborder="0" scrolling="auto" name="wifi" src="wifi.php?page=wlan0_info" width="100%" onload="javascript:resizeIframe(this);">If you can see this message, your browser does not support iFrames, however if you would like to see the content please click <a href="wifi.php?page=wlan0_info">here</a>.</iframe>';
	}	
	if ( file_exists('/sys/class/net/wlan0') || file_exists('/sys/class/net/wlan1') || file_exists('/sys/class/net/wlan0_ap') ) {
echo '
<br />
    <h2 class="ConfSec">'.__( 'Wireless Configuration' ).'</h2>
    <table><tr><td>'.$wifi_page.'
    </td></tr></table>
    <br />
    <form id="autoApPassForm" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
    <table>
    <tr><th class="config_head" width="200">Auto AP SSID</th><th class="config_head" colspan="3">PSK</th></tr>
    <tr>
    <td align="left"><b>'.php_uname('n').'</b></td>
    <td align="left"><label for="psk1">PSK:</label><input type="password" name="autoapPsk" id="psk1" onkeyup="checkPsk(); return false;" size="20" />
    <label for="psk2">Confirm PSK:</label><input type="password" name="autoapPsk" id="psk2" onkeyup="checkPskMatch(); return false;" />
    <br /><span id="confirmMessage" class="confirmMessage"></span></td>
    <td align="right"><input type="button" id="submitpsk" value="Set PSK" onclick="submitPskform()" disabled="disabled" /></td>
    </tr>
    </table>
    </form>';
    }
?>

<br />
    <h2 class="ConfSec"><?php echo __( 'Remote Access Password' );?></h2>
    <form id="adminPassForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <table>
    <tr><th class='config_head' width="200"><?php echo __( 'User Name' );?></th><th class='config_head' colspan="3"><?php echo __( 'Password' );?></th></tr>
    <tr>
    <td align="left"><b>pi-star</b></td>
    <td align="left"><label for="pass1">Password:</label><input type="password" name="adminPassword" id="pass1" onkeyup="checkPass(); return false;" size="20" />
    <label for="pass2">Confirm Password:</label><input type="password" name="adminPassword" id="pass2" onkeyup="checkPass(); return false;" />
    <br /><span id="confirmMessage" class="confirmMessage"></span></td>
    <td align="right"><input type="button" id="submitpwd" value="<?php echo __( 'Set Password' );?>" onclick="submitPassform()" disabled="disabled" /></td>
    </tr>
    <tr><td colspan="3" align="left" style='word-wrap: break-word;white-space: normal;padding-left: 5px;'><i class="fa fa-exclamation-circle"></i> <strong>NOTE:</strong> This changes the password for admin pages, this configuration page AND the '<code>pi-star</code>' SSH account.</td></tr>
    </table>
    </form>
<?php endif; ?>
<br />
</div>
<div class="footer">
Get WPSD Help: [ <a href="https://w0chp.radio/wpsd-faqs/" target="_new">FAQs</a> ] &bull; [ <a href="https://wpsd-docs.w0chp.net/" target="_new">User Manual</a> ] &bull; [ <a href="https://www.facebook.com/groups/wpsdproject/" target="_new">Facebook Group</a> ] &bull; [ <a href="https://discord.gg/b8Hv5ygPdF" target="_new">Discord Server</a> ]<br />
<a href="https://wpsd.radio/">WPSD</a> by <code>W0CHP</code> &copy; 2020-<?php echo date("Y"); ?> -- WPSD Project <a href="https://w0chp.radio/wpsd/#credits" target="_new">Credits</a>
<br />
</div>
</div>
<script>
    function updateSymbolPreview(symbolCode) {
        var symbolPreview = document.getElementById('aprs-symbol-preview');
        var previewText = document.querySelector('.aprs-preview-text');
        var previewContainer = document.querySelector('.aprs-preview-container');
        var symbolImageTag = getAPRSSymbolImageTag(symbolCode, 48);
        
        if (symbolPreview && previewText && previewContainer) { 
            if (symbolCode !== '') {
                // Update the image container with the selected symbol and display "Preview:"
                symbolPreview.innerHTML = symbolImageTag;
                previewText.style.display = 'block';
                previewContainer.classList.add('centered');
            } else {
                // Clear the image container and hide "Preview:" when no symbol is selected
                symbolPreview.innerHTML = '';
                previewText.style.display = 'none';
                previewContainer.classList.remove('centered');
            }
        }
    }

    // Call updateSymbolPreview with the preselected symbol on page load
    var preselectedSymbol = '<?php echo $selectedSymbol; ?>';
    updateSymbolPreview(preselectedSymbol);

    // Function to enable/disable radio buttons based on uPNP selection
    function toggleFwRadioButtons() {
        var uPNPValue = document.querySelector('input[name="uPNP"]:checked').value;
        var dashAccessRadio = document.getElementsByName("dashAccess");
        var ircRCAccessRadio = document.getElementsByName("ircRCAccess");
        var sshAccessRadio = document.getElementsByName("sshAccess");

        if (uPNPValue === "OFF") {
            // Disable radio buttons
            for (var i = 0; i < dashAccessRadio.length; i++) {
                dashAccessRadio[i].disabled = true;
                dashAccessRadio[i].checked = false;
            }
            for (var i = 0; i < ircRCAccessRadio.length; i++) {
                ircRCAccessRadio[i].disabled = true;
                ircRCAccessRadio[i].checked = false;
            }
            for (var i = 0; i < sshAccessRadio.length; i++) {
                sshAccessRadio[i].disabled = true;
                sshAccessRadio[i].checked = false;
            }
        } else {
            // Enable radio buttons
            for (var i = 0; i < dashAccessRadio.length; i++) {
                dashAccessRadio[i].disabled = false;
            }
            for (var i = 0; i < ircRCAccessRadio.length; i++) {
                ircRCAccessRadio[i].disabled = false;
            }
            for (var i = 0; i < sshAccessRadio.length; i++) {
                sshAccessRadio[i].disabled = false;
            }
        }
    }

    // Attach the function to the uPNP radio buttons' change event
    var uPNPRadioButtons = document.getElementsByName("uPNP");
    for (var i = 0; i < uPNPRadioButtons.length; i++) {
        uPNPRadioButtons[i].addEventListener("change", toggleFwRadioButtons);
    }

    // Initial call to set the initial state
    toggleFwRadioButtons();

    var aprsGatewayCheckbox;

    window.onload = function () {
        toggleAPRSGatewayCheckbox();
    };

    function toggleAPRSGatewayCheckbox() {
        aprsGatewayCheckbox = document.getElementById('toggle-aprsgateway');
        var gpsdCheckbox = document.getElementById('toggle-GPSD');
        var dmrCheckbox = document.getElementById('aprsgw-service-selection-0');
        var ysfCheckbox = document.getElementById('aprsgw-service-selection-1');
        var dgIdCheckbox = document.getElementById('aprsgw-service-selection-2');
        var nxdnCheckbox = document.getElementById('aprsgw-service-selection-3');
        var m17Checkbox = document.getElementById('aprsgw-service-selection-4');
        var ircDDBCheckbox = document.getElementById('aprsgw-service-selection-5');

        // Disable or enable GPSD based on the state of APRS Gateway checkbox
        gpsdCheckbox.disabled = !aprsGatewayCheckbox.checked;

        // Uncheck GPSD if APRS Gateway is unchecked
        if (!aprsGatewayCheckbox.checked) {
            gpsdCheckbox.checked = false;
        }

        // Disable or enable other checkboxes based on the state of APRS Gateway checkbox
        dmrCheckbox.disabled = !aprsGatewayCheckbox.checked;
        ysfCheckbox.disabled = !aprsGatewayCheckbox.checked;
        dgIdCheckbox.disabled = !aprsGatewayCheckbox.checked;
        nxdnCheckbox.disabled = !aprsGatewayCheckbox.checked;
        m17Checkbox.disabled = !aprsGatewayCheckbox.checked;
        ircDDBCheckbox.disabled = !aprsGatewayCheckbox.checked;
    }

    // Add an event listener to the toggle-aprsgateway checkbox to call the function when its state changes
    window.addEventListener('load', function () {
        aprsGatewayCheckbox.addEventListener('change', toggleAPRSGatewayCheckbox);
    });
</script>
</body>
</html>

<?php 
} else {
?>
<br />
<br />
</div>
<div class="footer">
Get WPSD Help: [ <a href="https://w0chp.radio/wpsd-faqs/" target="_new">FAQs</a> ] &bull; [ <a href="https://wpsd-docs.w0chp.net/" target="_new">User Manual</a> ] &bull; [ <a href="https://www.facebook.com/groups/wpsdproject/" target="_new">Facebook Group</a> ] &bull; [ <a href="https://discord.gg/b8Hv5ygPdF" target="_new">Discord Server</a> ]<br />
<a href="https://wpsd.radio/">WPSD</a> by <code>W0CHP</code> &copy; 2020-<?php echo date("Y"); ?> -- WPSD Project <a href="https://w0chp.radio/wpsd/#credits" target="_new">Credits</a>
<br />
</div>
</div>
</body>
</html>
<?php
    } 
}
?>
