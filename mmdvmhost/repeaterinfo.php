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

include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';	      // Translation Code
require_once($_SERVER['DOCUMENT_ROOT'].'/config/ircddblocal.php');

if (isset($_SESSION['CSSConfigs']['Background']['TableRowBgEvenColor'])) {
    $tableRowEvenBg = $_SESSION['CSSConfigs']['Background']['TableRowBgEvenColor'];
} else {
    $tableRowEvenBg = "inherit";
}
if (isset($_SESSION['CSSConfigs']['ExtraSettings']['TableBorderColor'])) {
    $tableBorderColor = $_SESSION['CSSConfigs']['ExtraSettings']['TableBorderColor'];
} else {
    $tableBorderColor = "inherit";
}

function FillConnectionHosts(&$destArray, $remoteEnabled, $remotePort) {
    if (($remoteEnabled == 1) && ($remotePort != 0)) {
	$remoteOutput = null;
	$remoteRetval = null;
	exec('cd /var/log/pi-star; /usr/local/bin/RemoteCommand '.$remotePort.' hosts', $remoteOutput, $remoteRetval);
	if (($remoteRetval == 0) && (count($remoteOutput) >= 2)) {
	    $expOutput = preg_split('/"[^"]*"(*SKIP)(*F)|\x20/', $remoteOutput[1]);
	    foreach ($expOutput as $entry) {
		$keysValues = explode(":", $entry);
		$destArray[$keysValues[0]] = $keysValues[1];
	    }
	}
    }
}

function FillConnectionStatus(&$destArray, $remoteEnabled, $remotePort) {
    if (($remoteEnabled == 1) && ($remotePort != 0)) {
	$remoteOutput = null;
	$remoteRetval = null;
	exec('cd /var/log/pi-star; /usr/local/bin/RemoteCommand '.$remotePort.' status', $remoteOutput, $remoteRetval);
	if (($remoteRetval == 0) && (count($remoteOutput) >= 2)) {
	    $tok = strtok($remoteOutput[1], " \n\t");
	    while ($tok !== false) {
		$keysValues = explode(":", $tok);
		$destArray[$keysValues[0]] = $keysValues[1];
		$tok = strtok(" \n\t");
	    }
	}
    }
}

function GetActiveConnectionStyle($masterStates, $key) {
    global $tableRowEvenBg;
    if (count($masterStates)) {
	if (isset($masterStates[$key])) {
	    if (getDMRnetStatus("$key") == "disabled") {
		return "class=\"paused-mode-cell\" title=\"User Disabled\"";
	    } else if ($masterStates[$key] !== "conn") {
		return "class=\"error-state-cell\" title=\"Login or Network Issue!\"";
	    }
	}
    }
    return "style='background: $tableRowEvenBg;'";
}

//
// Grab networks status from remote commands
//
$remoteMMDVMResults = [];
$remoteDMRgwResults = [];
$remoteYSFGResults = [];
$remoteP25GResults = [];
$remoteNXDNGResults = [];
$remoteM17GWResults = [];

if (isProcessRunning("MMDVMHost")) {
    $cfgItemEnabled = getConfigItem("Remote Control", "Enable", $_SESSION['MMDVMHostConfigs']);
    $cfgItemPort = getConfigItem("Remote Control", "Port", $_SESSION['MMDVMHostConfigs']);
    FillConnectionStatus($remoteMMDVMResults, (isset($cfgItemEnabled) ? $cfgItemEnabled : 0), (isset($cfgItemPort) ? $cfgItemPort : 0));
}

if (isProcessRunning("DMRGateway")) {
    $remoteCommandEnabled = (isset($_SESSION['DMRGatewayConfigs']['Remote Control']) ? $_SESSION['DMRGatewayConfigs']['Remote Control']['Enable'] : 0);
    $remoteCommandPort = (isset($_SESSION['DMRGatewayConfigs']['Remote Control']) ? $_SESSION['DMRGatewayConfigs']['Remote Control']['Port'] : 0);
    FillConnectionStatus($remoteDMRgwResults, $remoteCommandEnabled, $remoteCommandPort);
}

if (isProcessRunning("YSFGateway")) {
    $remoteCommandEnabled = (isset($_SESSION['YSFGatewayConfigs']['Remote Commands']) ? $_SESSION['YSFGatewayConfigs']['Remote Commands']['Enable'] : 0);
    $remoteCommandPort = (isset($_SESSION['YSFGatewayConfigs']['Remote Commands']) ? $_SESSION['YSFGatewayConfigs']['Remote Commands']['Port'] : 0);
    FillConnectionStatus($remoteYSFGResults, $remoteCommandEnabled, $remoteCommandPort);
}

if (isProcessRunning("P25Gateway")) {
    $remoteCommandEnabled = (isset($_SESSION['P25GatewayConfigs']['Remote Commands']) ? $_SESSION['P25GatewayConfigs']['Remote Commands']['Enable'] : 0);
    $remoteCommandPort = (isset($_SESSION['P25GatewayConfigs']['Remote Commands']) ? $_SESSION['P25GatewayConfigs']['Remote Commands']['Port'] : 0);
    FillConnectionStatus($remoteP25GResults, $remoteCommandEnabled, $remoteCommandPort);
}

if (isProcessRunning("NXDNGateway")) {
    $remoteCommandEnabled = (isset($_SESSION['NXDNGatewayConfigs']['Remote Commands']) ? $_SESSION['NXDNGatewayConfigs']['Remote Commands']['Enable'] : 0);
    $remoteCommandPort = (isset($_SESSION['NXDNGatewayConfigs']['Remote Commands']) ? $_SESSION['NXDNGatewayConfigs']['Remote Commands']['Port'] : 0);
    FillConnectionStatus($remoteNXDNGResults, $remoteCommandEnabled, $remoteCommandPort);
}

if (isProcessRunning("M17Gateway")) {
    $remoteCommandEnabled = (isset($_SESSION['M17GatewayConfigs']['Remote Commands']) ? $_SESSION['M17GatewayConfigs']['Remote Commands']['Enable'] : 0);
    $remoteCommandPort = (isset($_SESSION['M17GatewayConfigs']['Remote Commands']) ? $_SESSION['M17GatewayConfigs']['Remote Commands']['Port'] : 0);
    FillConnectionStatus($remoteM17GResults, $remoteCommandEnabled, $remoteCommandPort);
}

// get number of DMR Masters configged for DMRGw:
$numDMRmasters = exec('cd /var/log/pi-star ; /usr/local/bin/RemoteCommand '.$_SESSION['DMRGatewayConfigs']['Remote Control']['Port']. ' status | grep -o "conn" | wc -l');
?>

<div class="mode_flex" id="rptInfoTable">
  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableHead"><?php echo __( 'Mode Status' );?></div>
    </div>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
	<?php if (isPaused("D-Star")) { echo '<div class="paused-mode-cell" title="Mode Paused">D-Star</div>'; } else {showMode("D-Star", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
    <div class="mode_flex column">
      <div class="divTableCell">
	<?php if (isPaused("DMR")) { echo '<div class="paused-mode-cell" title="Mode Paused">DMR</div>'; } else { showMode("DMR", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
	<?php if (isPaused("YSF")) { echo '<div class="paused-mode-cell" title="Mode Paused">YSF</div>'; } else { showMode("System Fusion", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
<?php if (isDVmegaCast() == 0) { // DVMega Cast logic... ?>
    <div class="mode_flex column">
      <div class="divTableCell">
	<?php if (isPaused("P25")) { echo '<div class="paused-mode-cell" title="Mode Paused">P25</div>'; } else { showMode("P25", $_SESSION['MMDVMHostConfigs']); } ?>
     </div>
    </div>
<?php } ?>
  </div>

<?php if (isDVmegaCast() == 0) { // DVMega Cast logic... ?>
  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
	    <?php if (isPaused("M17")) { echo '<div class="paused-mode-cell" title="Mode Paused">M17</div>'; } else { showMode("M17", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
    <div class="mode_flex column">
      <div class="divTableCell">
	<?php if (isPaused("NXDN")) { echo '<div class="paused-mode-cell" title="Mode Paused">NXDN</div>'; } else { showMode("NXDN", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
  </div>
<?php } ?>
  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
	<?php showMode("DMR X-Mode", $_SESSION['MMDVMHostConfigs']);?>
      </div>
    </div>
    <div class="mode_flex column">
      <div class="divTableCell">
	    <?php showMode("YSF X-Mode", $_SESSION['MMDVMHostConfigs']);?>
      </div>
    </div>
  </div>

<?php if (isDVmegaCast() == 0) { // DVMega Cast logic... ?>
  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
	<?php if (isPaused("POCSAG")) { echo '<div class="paused-mode-cell" title="Mode Paused">POCSAG</div>'; } else { showMode("POCSAG", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
  </div>
<?php } ?>
</div>

<br />

<div class="mode_flex">
  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableHead"><?php echo __( 'Network Status' );?></div>
    </div>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if(isPaused("D-Star")) { echo '<div class="paused-mode-cell" title="Mode Paused">D-Star Net</div>'; } else { showMode("D-Star Network", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if(isPaused("DMR")) { echo '<div class="paused-mode-cell" title="Mode Paused">DMR Net</div>'; } else { showMode("DMR Network", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if(isPaused("YSF")) { echo '<div class="paused-mode-cell" title="Mode Paused">YSF Net</div>'; } else { showMode("System Fusion Network", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>

<?php if (isDVmegaCast() == 0) { // DVMega Cast logic... ?>
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if(isPaused("P25")) { echo '<div class="paused-mode-cell" title="Mode Paused">P25 Net</div>'; } else { showMode("P25 Network", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if(isPaused("M17")) { echo '<div class="paused-mode-cell" title="Mode Paused">M17 Net</div>'; } else { showMode("M17 Network", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>  
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if(isPaused("NXDN")) { echo '<div class="paused-mode-cell" title="Mode Paused">NXDN Net</div>'; } else { showMode("NXDN Network", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
<?php } ?>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php showMode("DMR2NXDN Network", $_SESSION['MMDVMHostConfigs']);?>
      </div>
    </div>
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php showMode("DMR2YSF Network", $_SESSION['MMDVMHostConfigs']);?>
      </div>
    </div>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php showMode("YSF2DMR Network", $_SESSION['MMDVMHostConfigs']);?>
      </div>
    </div>
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php showMode("YSF2NXDN Network", $_SESSION['MMDVMHostConfigs']);?>
      </div>
    </div>
  </div>

  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php showMode("YSF2P25 Network", $_SESSION['MMDVMHostConfigs']);?>
      </div>
    </div>
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if (isPaused("APRS")) { echo '<div class="paused-mode-cell" title="Service Paused">APRS Net</div>'; } else { showMode("APRS Network", $_SESSION['APRSGatewayConfigs']); } ?>
      </div>
    </div>
  </div>

<?php if (isDVmegaCast() == 0) { // DVMega Cast logic... ?>
  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableCell">
        <?php if(isPaused("POCSAG")) { echo '<div class="paused-mode-cell" title="Mode Paused">POCSAG Net</div>'; } else { showMode("POCSAG Network", $_SESSION['MMDVMHostConfigs']); } ?>
      </div>
    </div>
  </div>
<?php } ?>
</div>

<br />

<?php
$testMMDVModeDSTAR = getConfigItem("D-Star", "Enable", $_SESSION['MMDVMHostConfigs']);
if ( $testMMDVModeDSTAR == 1 || isPaused("D-Star") ) { //Hide the D-Star Reflector information when D-Star Network not enabled.
    $linkedTo = getActualLink($reverseLogLinesMMDVM, "D-Star");
?>
<div class="divTable">
  <div class="divTableHead"><?php echo __( 'D-Star Status' );?></div>
  <div class="divTableBody">
    <div class="divTableRow center">
      <div class="divTableHeadCell">RPT1</div>
      <div class="divTableCell cell_content mono" style="background: <?php echo $tableRowEvenBg; ?>;"><?php echo($_SESSION['ircDDBConfigs']['repeaterCall1'] ."&nbsp ".$_SESSION['ircDDBConfigs']['repeaterBand1']); ?></div>
    </div>    
    <div class="divTableRow center">
      <div class="divTableHeadCell">RPT2</div>
      <div class="divTableCell cell_content mono" style="background: <?php echo $tableRowEvenBg; ?>;"><?php echo($_SESSION['ircDDBConfigs']['repeaterCall1'] ."&nbsp G"); ?></div>
    </div>	
  </div>
</div>
<div class="divTable">
  <div class="divTableHead"><?php echo __( 'D-Star Network' ); ?></div>
  <div class="divTableBody">
    <?php
        if (isPaused("D-Star")) {
                echo "<div class='divTableRow center'><div class='divTableCell cell_content' style=\"background: $tableRowEvenBg;\">Mode Paused</div></div>\n";                 
        } else {
                echo "<div class='divTableRow center'><div class='divTableCell cell_content' title=\"".$linkedTo."\">".$linkedTo."</div></div>\n";
        }
        if ($_SESSION['ircDDBConfigs']['aprsEnabled'] == 1) {
		if (substr($_SESSION['ircDDBConfigs']['aprsAddress'], 0, 18) == '127.0.0.1') {
	            echo "<div class='divTableRow center'><div class='divTableHeadCell'>APRS Host</div></div><div class='divTableRow center'><div class='divTableCell cell_content' title=\"Using APRSGateway\">APRSGateway</div></div>\n";
		} else {
	            echo "<div class='divTableRow center'><div class='divTableHeadCell'>APRS Host</div></div><div class='divTableRow center'><div class='divTableCell cell_content' style=\"background: $tableRowEvenBg;\">".substr($_SESSION['ircDDBConfigs']['aprsAddress'], 0, 18)."</div></div>\n";
		}
        }
        if ($_SESSION['ircDDBConfigs']['ircddbEnabled'] == 1) {
	    if (isProcessRunning("ircddbgatewayd")) {
	        echo "<div class='divTableRow center'><div class='divTableHeadCell'>ircDDB Host</div></div><div class='divTableRow center'><div class='divTableCell cell_content' style=\"background: $tableRowEvenBg;\">".substr($_SESSION['ircDDBConfigs']['ircddbHostname'], 0 ,18)."</div></div>\n";
	    } else {
	        echo "<div class='divTableRow center'><div class='divTableHeadCell'>ircDDB Host</div></div><div class='divTableRow center'><div class='divTableCell cell_content'><div class='inactive-mode-cell'>Service Not Started</div></div></div>\n";
	    }
        }
	?>
  </div>
</div>

<br />

<?php 

}
	$testMMDVModeDMR = getConfigItem("DMR", "Enable", $_SESSION['MMDVMHostConfigs']);
	if ( $testMMDVModeDMR == 1 || isPaused("DMR") ) { //Hide the DMR information when DMR mode not enabled.
		if (isPaused("DMR")) {
			$dmrMasterHost = "Mode Paused";
			$dmrMasterHostTooltip = $dmrMasterHost;
		} else {
	    $dmrMasterFile = fopen("/usr/local/etc/DMR_Hosts.txt", "r");
	    $dmrMasterHost = getConfigItem("DMR Network", "Address", $_SESSION['MMDVMHostConfigs']);
	    $dmrMasterPort = getConfigItem("DMR Network", "Port", $_SESSION['MMDVMHostConfigs']);
	    if ($dmrMasterHost == '127.0.0.1') {
		if (isset($_SESSION['DMRGatewayConfigs']['XLX Network 1']['Address'])) {
		    $xlxMasterHost1 = $_SESSION['DMRGatewayConfigs']['XLX Network 1']['Address'];
		}
		else {
		    $xlxMasterHost1 = "";
		}
		$dmrMasterHost1 = $_SESSION['DMRGatewayConfigs']['DMR Network 1']['Address'];
		$dmrMasterHost2 = $_SESSION['DMRGatewayConfigs']['DMR Network 2']['Name'];
		if (isset($_SESSION['DMRGatewayConfigs']['DMR Network 2']['Name'])) {
		    $dmrMasterHost2 = str_replace('_', ' ', $_SESSION['DMRGatewayConfigs']['DMR Network 2']['Name']);
		}
		$dmrMasterHost3 = str_replace('_', ' ', $_SESSION['DMRGatewayConfigs']['DMR Network 3']['Name']);
		if (isset($_SESSION['DMRGatewayConfigs']['DMR Network 4']['Name'])) {
		    $dmrMasterHost4 = str_replace('_', ' ', $_SESSION['DMRGatewayConfigs']['DMR Network 4']['Name']);
		}
		if (isset($_SESSION['DMRGatewayConfigs']['DMR Network 5']['Name'])) {
		    $dmrMasterHost5 = str_replace('_', ' ', $_SESSION['DMRGatewayConfigs']['DMR Network 5']['Name']);
		}
		if (isset($_SESSION['DMRGatewayConfigs']['DMR Network 6']['Name'])) {
		    $dmrMasterHost6 = str_replace('_', ' ', $_SESSION['DMRGatewayConfigs']['DMR Network 6']['Name']);
		}
        if (isset($configdmrgateway['DMR Network 6']['Name'])) {$dmrMasterHost6 = str_replace('_', ' ', $configdmrgateway['DMR Network 6']['Name']);}
		while (!feof($dmrMasterFile)) {
		    $dmrMasterLine = fgets($dmrMasterFile);
		    $dmrMasterHostF = preg_split('/\s+/', $dmrMasterLine);
		    if ((count($dmrMasterHostF) >= 2) && (strpos($dmrMasterHostF[0], '#') === FALSE) && ($dmrMasterHostF[0] != '')) {
			if ((strpos($dmrMasterHostF[0], 'XLX_') === 0) && ($xlxMasterHost1 == $dmrMasterHostF[2])) {
			    $xlxMasterHost1 = str_replace('_', ' ', $dmrMasterHostF[0]);
			}
			if ((strpos($dmrMasterHostF[0], 'BM_') === 0) && ($dmrMasterHost1 == $dmrMasterHostF[2])) {
			    $dmrMasterHost1 = str_replace('_', ' ', $dmrMasterHostF[0]);
			}
			if ((strpos($dmrMasterHostF[0], 'DMR+_') === 0) && ($dmrMasterHost2 == $dmrMasterHostF[2])) {
			    $dmrMasterHost2 = str_replace('_', ' ', $dmrMasterHostF[0]);
			}
		    }
		}
		
		$xlxMasterHost1Tooltip = $xlxMasterHost1;
		$dmrMasterHost1Tooltip = $dmrMasterHost1;
		$dmrMasterHost2Tooltip = $dmrMasterHost2;
		$dmrMasterHost3Tooltip = $dmrMasterHost3;
		if (isset($dmrMasterHost4)) {
		    $dmrMasterHost4Tooltip = $dmrMasterHost4;
		}
		if (isset($dmrMasterHost5)) {
		    $dmrMasterHost5Tooltip = $dmrMasterHost5;
		}
        if (isset($dmrMasterHost6)) {
            $dmrMasterHost6Tooltip = $dmrMasterHost6;
        }
		if (strlen($xlxMasterHost1) > 20) {
		    $xlxMasterHost1 = substr($xlxMasterHost1, 0, 17) . '...';
		}
		if (strlen($dmrMasterHost1) > 20) {
		    $dmrMasterHost1 = substr($dmrMasterHost1, 0, 17) . '...';
		}
		if (strlen($dmrMasterHost2) > 20) {
		    $dmrMasterHost2 = substr($dmrMasterHost2, 0, 17) . '...';
		}
		if (strlen($dmrMasterHost3) > 20) {
		    $dmrMasterHost3 = substr($dmrMasterHost3, 0, 17) . '...';
		}
		if (isset($dmrMasterHost4)) {
		    if (strlen($dmrMasterHost4) > 20) {
			    $dmrMasterHost4 = substr($dmrMasterHost4, 0, 17) . '...';
		    }
		}
		if (isset($dmrMasterHost5)) {
		    if (strlen($dmrMasterHost5) > 20) {
			    $dmrMasterHost5 = substr($dmrMasterHost5, 0, 17) . '...';
		    }
		}
        if (isset($dmrMasterHost6)) { if (strlen($dmrMasterHost6) > 20) { $dmrMasterHost6 = substr($dmrMasterHost6, 0, 17) . '...'; } }
	    }
	    else {
		while (!feof($dmrMasterFile)) {
		    $dmrMasterLine = fgets($dmrMasterFile);
                    $dmrMasterHostF = preg_split('/\s+/', $dmrMasterLine);
		    if ((count($dmrMasterHostF) >= 4) && (strpos($dmrMasterHostF[0], '#') === FALSE) && ($dmrMasterHostF[0] != '')) {
			if (($dmrMasterHost == $dmrMasterHostF[2]) && ($dmrMasterPort == $dmrMasterHostF[4])) {
			    $dmrMasterHost = str_replace('_', ' ', $dmrMasterHostF[0]);
			}
		    }
		}
		$dmrMasterHostTooltip = $dmrMasterHost;
		if (strlen($dmrMasterHost) > 20) {
		    $dmrMasterHost = substr($dmrMasterHost, 0, 15) . '...';
		}
	    }
	    fclose($dmrMasterFile);
	    }
	    ?>
<div class="divTable">
  <div class="divTableHead"><?php echo __( 'DMR Status' );?></div>
  <div class="divTableBody">
   <?php if (getConfigItem("DMR Network", "Slot1", $_SESSION['MMDVMHostConfigs']) == 1) { ?>
    <div class="divTableRow center">
      <div class="divTableHeadCell">TS1</div>
      <?php echo "<div class='divTableCell cell_content middle active-mode-cell' title='Time Slot 1 Enabled' style='border: .5px solid $tableBorderColor;'>Enabled</div>\n"; ?>
    </div>
    <?php } if (getConfigItem("DMR Network", "Slot2", $_SESSION['MMDVMHostConfigs']) == 1) { ?>
    <div class="divTableRow center">
      <div class="divTableHeadCell">TS2</div>
      <?php echo "<div class='divTableCell cell_content middle active-mode-cell' title='Time Slot 2 Enabled' style='border: .5px solid $tableBorderColor;'>Enabled</div>\n"; ?>
    </div>
    <?php } ?>
   
    <?php if(isWPSDrepeater() == 1) { // repeater-only ?>
    <div class="divTableRow center">
      <div class="divTableHeadCell" title="DMR Roaming Beacons">Beacons</div>
           <?php
            if (getConfigItem("DMR", "Beacons", $_SESSION['MMDVMHostConfigs']) == 1 && getConfigItem("DMR", "BeaconInterval", $_SESSION['MMDVMHostConfigs']) != null) {
		echo "<div class='divTableCell cell_content middle;' title='Enabled: Timed Mode'>Timed Mode</div>\n";
	    } elseif  (getConfigItem("DMR", "Beacons", $_SESSION['MMDVMHostConfigs']) == 1 && getConfigItem("DMR", "BeaconInterval", $_SESSION['MMDVMHostConfigs']) == null) {
		echo "<div class='divTableCell cell_content middle;' title='Enabled: Network Mode'>Net. Mode</div>\n";
	    } else {
		echo "<div class='divTableCell cell_content middle'><div style=\"background: $tableRowEvenBg;\">Disabled</div></div>\n";
	    }
	    ?>
      </div>
  <?php } ?>
    </div>
    <div class="divTableRow center">
      <div class="divTableHeadCell">DMR ID</div>
      <div class="divTableCell cell_content" style="background: <?php echo $tableRowEvenBg; ?>;"><?php echo getConfigItem("General", "Id", $_SESSION['MMDVMHostConfigs']); ?></div>
    </div>
    <div class="divTableRow center">
      <div class="divTableHeadCell">DMR CC</div>
      <div class="divTableCell cell_content" style="background: <?php echo $tableRowEvenBg; ?>;"><?php echo getConfigItem("DMR", "ColorCode", $_SESSION['MMDVMHostConfigs']); ?></div>
    </div>
</div>
<div class="divTable">
  <?php if ($numDMRmasters <= 1) { ?>
  <div class="divTableHead"><?php echo __( 'DMR Master' );?></div>
  <?php } else { ?>
  <div class="divTableHead">DMR Masters</div>
  <?php } ?>
  <div class="divTableBody">
	    <?php
	    if (getEnabled("DMR Network", $_SESSION['MMDVMHostConfigs']) == 1) {
		if ($dmrMasterHost == '127.0.0.1') {
		    if (isProcessRunning("DMRGateway")) {
			if ($_SESSION['DMRGatewayConfigs']['DMR Network 1']['Enabled'] == 1) {
			    echo "<div class='divTableRow center'><div class='divTableCell'><div " .GetActiveConnectionStyle($remoteDMRgwResults, "net1")." title=\"".$dmrMasterHost1Tooltip."\">".$dmrMasterHost1."</div></div></div>\n";
			}
			if ($_SESSION['DMRGatewayConfigs']['DMR Network 2']['Enabled'] == 1) {
			    echo "<div class='divTableRow center'><div class='divTableCell'><div ".GetActiveConnectionStyle($remoteDMRgwResults, "net2")." title=\"".$dmrMasterHost2Tooltip."\">".$dmrMasterHost2."</div></div></div>\n";
			}
			if ($_SESSION['DMRGatewayConfigs']['DMR Network 3']['Enabled'] == 1) {
			    echo "<div class='divTableRow center'><div class='divTableCell'><div ".GetActiveConnectionStyle($remoteDMRgwResults, "net3")." title=\"".$dmrMasterHost3Tooltip."\">".$dmrMasterHost3."</div></div></div>\n";
			}
			if (isset($_SESSION['DMRGatewayConfigs']['DMR Network 4']['Enabled'])) {
			    if ($_SESSION['DMRGatewayConfigs']['DMR Network 4']['Enabled'] == 1) {
				echo "<div class='divTableRow center'><div class='divTableCell'><div ".GetActiveConnectionStyle($remoteDMRgwResults, "net4")." title=\"".$dmrMasterHost4Tooltip."\">".$dmrMasterHost4."</div></div></div>\n";
			    }
			}
			if (isset($_SESSION['DMRGatewayConfigs']['DMR Network 5']['Enabled'])) {
			    if ($_SESSION['DMRGatewayConfigs']['DMR Network 5']['Enabled'] == 1) {
				echo "<div class='divTableRow center'><div class='divTableCell'><div ".GetActiveConnectionStyle($remoteDMRgwResults, "net5")." title=\"".$dmrMasterHost5Tooltip."\">".$dmrMasterHost5."</div></div></div>\n";
			    }
			}
			if ( !isset($_SESSION['DMRGatewayConfigs']['XLX Network 1']['Enabled']) && isset($_SESSION['DMRGatewayConfigs']['XLX Network']['Enabled']) && $_SESSION['DMRGatewayConfigs']['XLX Network']['Enabled'] == 1) {
			    $xlxMasterHostLinkState = "";
			    
                            if (file_exists("/var/log/pi-star/DMRGateway-".gmdate("Y-m-d").".log")) {
				$xlxMasterHostLinkState = exec('grep \'XLX, Linking\|XLX, Unlinking\|XLX, Logged\' /var/log/pi-star/DMRGateway-'.gmdate("Y-m-d").'.log | tail -1 | awk \'{print $5 " " $8 " " $9}\'');
				if(empty($xlxMasterHostLinkState)) {
				    $xlxMasterHostLinkState = exec('grep \'XLX, Linking\|XLX, Unlinking\|XLX, Logged\' /var/log/pi-star/DMRGateway-'.gmdate("Y-m-d", time() - 86340).'.log | tail -1 | awk \'{print $5 " " $8 " " $9}\'');
				}
			    } else {
				$xlxMasterHostLinkState = exec('grep \'XLX, Linking\|XLX, Unlinking\|XLX, Logged\' /var/log/pi-star/DMRGateway-'.gmdate("Y-m-d", time() - 86340).'.log | tail -1 | awk \'{print $5 " " $8 " " $9}\'');
			    }
                            if ($xlxMasterHostLinkState != "") {
                                if ( strpos($xlxMasterHostLinkState, 'Linking') !== false ) {
                                    $xlxMasterHost1 = str_replace('Linking ', '', $xlxMasterHostLinkState);
				    $xlxMasterHost1 = str_replace(" ", " Module ", $xlxMasterHost1); 
                                    $xlxMasterHost1Tooltip= $xlxMasterHost1; 
                                }
                                else if ( strpos($xlxMasterHostLinkState, 'Unlinking') !== false ) {
                                    $xlxMasterHost1 = "XLX Not Linked";
                                    $xlxMasterHost1Tooltip= $xlxMasterHost1;
                                }
                                else if ( strpos($xlxMasterHostLinkState, 'Logged') !== false ) {
                                    $xlxMasterHost1 = "XLX Not Linked";
                                    $xlxMasterHost1Tooltip= $xlxMasterHost1;
                                }
                            }
                            else {
                                // There is no trace of XLX in the logfile.
                                $xlxMasterHost1 = "".$xlxMasterHost1." ".$_SESSION['DMRGatewayConfigs']['XLX Network']['Module']."";
                                $xlxMasterHost1Tooltip= $xlxMasterHost1;    
                            }
 
			    echo "<div class='divTableRow center'><div class='divTableCell'><div " .GetActiveConnectionStyle($remoteDMRgwResults, "xlx")." title=\"".$xlxMasterHost1Tooltip."\">".$xlxMasterHost1."</div></div></div>\n";
			}
		    }
		    else {
			echo "<div class='divTableRow center'><div class='divTableCell cell_content'><div class='inactive-mode-cell'>Service Not Started</div></div></div>\n";
		    }
		}
		else {
		    echo "<div class='divTableRow center'><div class='divTableCell'><div ".GetActiveConnectionStyle($remoteDMRgwResults, "dmr")." title=\"".$dmrMasterHostTooltip."\">".$dmrMasterHost."</div></div></div>\n";
		}
	    }
	    else {
		echo "<div class='divTableRow center'><div class='divTableCell cell_content'><div class='inactive-mode-cell'>No DMR Network</div></div></div>\n";
	    }
        ?>
      </div>
    </div>
<br />
<?php
}

	$testMMDVModeYSF = getConfigItem("System Fusion Network", "Enable", $_SESSION['MMDVMHostConfigs']);
	if ( isset($_SESSION['DMR2YSFConfigs']['Enabled']['Enabled']) ) {
	    $testDMR2YSF = $_SESSION['DMR2YSFConfigs']['Enabled']['Enabled'];
	}
	if ( $testMMDVModeYSF == 1 || isPaused("YSF") || (isset($testDMR2YSF) && $testDMR2YSF == 1) ) { //Hide the YSF information when System Fusion Network mode not enabled.
	    if (isPaused("YSF")) {
		$ysfLinkedTo = "Mode Paused";
		$ysfLinkStateTooltip = $ysfLinkedTo;
	    } else {
            	$ysfLinkedTo = getActualLink($reverseLogLinesYSFGateway, "YSF");
	    }
	    if ($ysfLinkedTo == 'Not Linked' || $ysfLinkedTo == 'Service Not Started') {
                $ysfLinkedToTxt = $ysfLinkedTo;
		$ysfLinkState = '';
		$ysfLinkStateTooltip = $ysfLinkedTo;
	    }
	    else {
                $ysfHostFile = fopen("/usr/local/etc/YSFHosts.txt", "r");
                $ysfLinkedToTxt = "null";
                while (!feof($ysfHostFile)) {
                    $ysfHostFileLine = fgets($ysfHostFile);
                    $ysfRoomTxtLine = preg_split('/;/', $ysfHostFileLine);
		    
		    if (empty($ysfRoomTxtLine[0]) || empty($ysfRoomTxtLine[1]))
			continue;
		    
                    if (($ysfRoomTxtLine[0] == $ysfLinkedTo) || ($ysfRoomTxtLine[1] == $ysfLinkedTo)) {
			$ysfRoomNo = "YSF".$ysfRoomTxtLine[0];
                        $ysfLinkedToTxt = $ysfRoomTxtLine[1];
                        break;
                    }
                }
		fclose($ysfHostFile);
                $fcsHostFile = fopen("/usr/local/etc/FCSHosts.txt", "r");
                $ysfLinkedToTxt = "null";
                while (!feof($fcsHostFile)) {
                    $ysfHostFileLine = fgets($fcsHostFile);
                    $ysfRoomTxtLine = preg_split('/;/', $ysfHostFileLine);

                    if (empty($ysfRoomTxtLine[0]) || empty($ysfRoomTxtLine[1]))
                        continue;

                    if (($ysfRoomTxtLine[0] == $ysfLinkedTo) || ($ysfRoomTxtLine[1] == $ysfLinkedTo)) {
                        $ysfLinkedToTxt = $ysfRoomTxtLine[1];
			$ysfRoomNo = $ysfRoomTxtLine[0];
                        break;
                    }
                }
		fclose($fcsHostFile);

		if ($ysfLinkedToTxt != "null") {
		    //$ysfLinkedToTxt = "Room: ".$ysfLinkedToTxt;
		    $ysfLinkState = ' [In Room]';
		    $ysfLinkStateTooltip = 'In Room: ';
		}
		else {
		    //$ysfLinkedToTxt = "Linked to: ".$ysfLinkedTo;
		    $ysfLinkedToTxt = $ysfLinkedTo;
		    $ysfLinkState = ' [Linked]';
		    $ysfLinkStateTooltip = 'Linked to ';
		}
		
                $ysfLinkedToTxt = str_replace('_', ' ', $ysfLinkedToTxt);
            }

            if (empty($ysfRoomNo) || ($ysfRoomNo == "null")) {
	        $ysfTableData = $ysfLinkedToTxt;
            } else {
                $ysfTableData = $ysfLinkedToTxt."<br />(".$ysfRoomNo.")";
	    }
	    if ($ysfLinkedTo == 'Not Linked') {
	    	$ysfLinkedToTooltip = $ysfLinkStateTooltip;
	    } else {
		$ysfLinkedToTooltip = $ysfLinkStateTooltip.$ysfLinkedToTxt;
	    }
            if (strlen($ysfLinkedToTxt) > 20) {
		$ysfLinkedToTxt = substr($ysfLinkedToTxt, 0, 15) . '...';
	    }
	    ?>
<div class="divTable">
<?php
if (isPaused("YSF")) {
?>
  <div class="divTableHead"><?php echo __( 'YSF Status' );?></div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
    echo "<div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\" title=\"YSF Mode Paused\">Mode Paused</div></div>\n";
} else {
?>
  <div class="divTableHead"><?php echo __( 'YSF Status' )." ".$ysfLinkState; ?></div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
	if (isProcessRunning("YSFGateway")) {
	    echo "<div class='divTableCell cell_content' title=\"".$ysfLinkedToTooltip."\">".$ysfTableData."</div>\n";
	} else {
	    echo "<div class='divTableCell cell_content'><div class='inactive-mode-cell'>Service Not Started</div></div>\n";
	}
    }      
?>
    </div>
  </div>
</div>
<br />
<?php
}

if (getServiceEnabled('/etc/dgidgateway') == 1 )  { // Hide DGId GW info when GW not enabled
?>
<div class="divTable">
  <div class="divTableHead">DG-ID Gateway Status</div>
  <div class="divTableHead">Current DG-ID</div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
        if (isPaused("YSF")) {
            echo "<div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\" title=\"YSF Mode Paused\">YSF Mode Paused</div></div>\n";
        }
          else if (isProcessRunning("DGIdGateway")) {
            echo "<div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\" title=\"".str_replace("<br />", " ", getDGIdLinks())."\">".getDGIdLinks()."</div></div>\n";
        } else {
            echo "<div class='divTableCell cell_content'><div class='inactive-mode-cell'>Service Not Started</div></div>\n";
        }
?>
    </div>
  </div>
</div>
<br />
<?php
}

	$testYSF2DMR = 0;
	if ( isset($_SESSION['YSF2DMRConfigs']['Enabled']['Enabled']) ) {
	    $testYSF2DMR = $_SESSION['YSF2DMRConfigs']['Enabled']['Enabled'];
	}
	if ($testYSF2DMR == 1) { //Hide the YSF2DMR information when YSF2DMR Network mode not enabled.
            $dmrMasterFile = fopen("/usr/local/etc/DMR_Hosts.txt", "r");
            $dmrMasterHost = $_SESSION['YSF2DMRConfigs']['DMR Network']['Address'];
            while (!feof($dmrMasterFile)) {
                $dmrMasterLine = fgets($dmrMasterFile);
                $dmrMasterHostF = preg_split('/\s+/', $dmrMasterLine);
                if ((count($dmrMasterHostF) >= 2) && (strpos($dmrMasterHostF[0], '#') === FALSE) && ($dmrMasterHostF[0] != '')) {
                    if ($dmrMasterHost == $dmrMasterHostF[2]) {
			$dmrMasterHost = str_replace('_', ' ', $dmrMasterHostF[0]);
		    }
                }
            }
	    $dmrMasterHostTooltip = $dmrMasterHost;
            if (strlen($dmrMasterHost) > 25) {
		$dmrMasterHost = substr($dmrMasterHost, 0, 23) . '...';
	    }
            fclose($dmrMasterFile);
	    ?>
<div class="divTable">
  <div class="divTableHead">YSF2DMR</div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
	    echo "<div class='divTableHeadCell'>DMR ID</div><div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\">".$_SESSION['YSF2DMRConfigs']['DMR Network']['Id']."</div></div>\n";
	    echo '</div><div class="divTableRow center">';
	    echo "<div class='divTableHeadCell'>YSF2".__( 'DMR Master' )."</div>\n";
            echo "<div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\" title=\"".$dmrMasterHostTooltip."\">".$dmrMasterHost."</div></div>\n";
?>
    </div>
  </div>
</div>
<br />
<?php
	}
	$testMMDVModeP25 = getConfigItem("P25 Network", "Enable", $_SESSION['MMDVMHostConfigs']);
	if ( isset($_SESSION['YSF2P25Configs']['Enabled']['Enabled']) ) { $testYSF2P25 = $_SESSION['YSF2P25Configs']['Enabled']['Enabled']; }
	if ( $testMMDVModeP25 == 1 || $testYSF2P25 || isPaused("P25") ) { //Hide the P25 information when P25 Network mode not enabled.
?>
<div class="divTable">
  <div class="divTableHead"><?php echo __( 'P25 Status' ); ?></div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
	    if (getConfigItem("P25", "NAC", $_SESSION['MMDVMHostConfigs'])) {
		echo "<div class='divTableHeadCell'>NAC</div><div class='divTableCell cell_content mono'><div style=\"background: $tableRowEvenBg;\">".getConfigItem("P25", "NAC", $_SESSION['MMDVMHostConfigs'])."</div></div>\n";
	    }
	    echo "</div>\n<div class='divTableRow center'>\n";
	    echo "<div class='divTableHeadCell'>".__( 'P25 Network' )."</div>\n";
	    if (isPaused("P25")) {
		echo "<div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\">Mode Paused</div></div>\n";
	    } else {
		$P25tg = str_replace("TG", "", getActualLink($logLinesP25Gateway, "P25"));
		if (strpos($P25tg, 'Not Linked') || strpos($P25tg, 'Service Not Started')) {
		    echo "<div class='divTableCell cell_content'>$P25tg</div>\n";
		} else {
		    if (empty($P25tg)) {
			echo "<div class='divTableCell cell_content'>Not Linked</div>\n";
		    } else {
			if (file_exists("/etc/.TGNAMES")) {
			    $P25_target = preg_replace('#\((.*?)\)#', "<br><small>($1)</small>", tgLookup("P25", $P25tg));
			    echo "<div class='divTableCell cell_content'>$P25_target</div>\n";
			} else {
			    echo "<div class='divTableCell cell_content'>TG $P25tg</div>\n";
			}
		    }
		}
	    }
?>
    </div>
  </div>
</div>
<br />
<?php
	}
	
	$testMMDVModeNXDN = getConfigItem("NXDN Network", "Enable", $_SESSION['MMDVMHostConfigs']);
	if ( isset($_SESSION['YSF2NXDNConfigs']['Enabled']['Enabled']) ) {
	    if ($_SESSION['YSF2NXDNConfigs']['Enabled']['Enabled'] == 1) {
		$testYSF2NXDN = 1;
	    }
	}
	if ( isset($_SESSION['DMR2NXDNConfigs']['Enabled']['Enabled']) ) {
	    if ($_SESSION['DMR2NXDNConfigs']['Enabled']['Enabled'] == 1) {
		$testDMR2NXDN = 1;
	    }
	}
	if ( $testMMDVModeNXDN == 1 || isset($testYSF2NXDN) || isset($testDMR2NXDN) || isPaused("NXDN") ) { //Hide the NXDN information when NXDN Network mode not enabled.
if (getConfigItem("NXDN", "RAN", $_SESSION['MMDVMHostConfigs'])) {
?>
<div class="divTable">
  <div class="divTableHead"><?php echo __( 'NXDN Status' ); ?></div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
	echo "<div class='divTableHeadCell'>RAN</div>";
	echo "<div class='divTableCell cell_content mono'><div style=\"background: $tableRowEvenBg;\">".getConfigItem("NXDN", "RAN", $_SESSION['MMDVMHostConfigs'])."</div></div>\n";
	echo "</div>\n<div class='divTableRow center'>";
	echo "<div class='divTableHeadCell'>".__( 'NXDN Network' )."</div>\n";
	if (isPaused("NXDN")) {
	    echo "<div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\">Mode Paused</div></div>\n";
	} else {
	    $NXDNtg = str_replace("TG", "", getActualLink($logLinesNXDNGateway, "NXDN"));
	    if (strpos($NXDNtg, 'Not Linked') || strpos($NXDNtg, 'Service Not Started')) {
		echo "<div class='divTableCell cell_content'>$NXDNtg</div>\n";
	    } else {
		if (empty($NXDNtg)) {
		    echo "<div class='divTableCell cell_content'>Not Linked</div>\n";
		} else {
		    if (file_exists("/etc/.TGNAMES")) {
			$NXDN_target = preg_replace('#\((.*?)\)#', "<br><small>($1)</small>", tgLookup("NXDN", $NXDNtg));
			echo "<div class='divTableCell cell_content'>$NXDN_target</div>\n";
		    } else {
			echo "<div class='divTableCell cell_content'>TG $NXDNtg</div>\n";
		    }
		}
	    }
	}
?>
    </div>
  </div>
</div>
<br />
<?php
  }
}
	$testMMDVModeM17 = getConfigItem("M17", "Enable", $_SESSION['MMDVMHostConfigs']);
	$M17can = getConfigItem("M17", "CAN", $_SESSION['MMDVMHostConfigs']);
        $configm17gateway = $_SESSION['M17GatewayConfigs'];
	if ( $testMMDVModeM17 == 1 || isPaused("M17") ) { //Hide the M17 Reflector information when M17 Network not enabled.
?>
<div class="divTable">
  <div class="divTableHead">M17 Status</div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
	echo "      <div class='divTableHeadCell'>RPT</div>\n";
	echo "        <div class='divTableCell cell_content middle'>\n";
	echo "          <div class='mono' style=\"background: $tableRowEvenBg;\">".str_replace(' ', '&nbsp;', $configm17gateway['General']['Callsign'])."&nbsp;".str_replace(' ', '&nbsp;', $configm17gateway['General']['Suffix'])."</div>\n";
	echo "        </div>\n";
	echo "      </div>\n";
	echo "    <div class='divTableRow center'>\n";
	echo "      <div class='divTableHeadCell'>CAN</div>\n";
	echo "        <div class='divTableCell cell_content middle'>\n";
	echo "          <div class='mono' style=\"background: $tableRowEvenBg;\">$M17can</div>\n";
	echo "        </div>\n";
	echo "      </div>\n";
	echo "    <div class='divTableRow center'>\n";
	echo "      <div class='divTableHeadCell'>Reflector</div>\n";
	if (isPaused("M17")) {
	    echo "        <div class='divTableCell cell_content middle'\n";
	    echo "          <div style=\"background: $tableRowEvenBg;\">Mode Paused</div>\n";
	    echo "        </div>\n";
	} else {
	    echo "        <div class='divTableCell cell_content middle'>\n";
	    echo "          <div>".getActualLink($reverseLogLinesM17Gateway, "M17")."</div>\n";
	    echo "        </div>\n";
	}
?>
    </div>
  </div>
</div>
<br />
<?php
	}
	
	$testMMDVModePOCSAG = getConfigItem("POCSAG Network", "Enable", $_SESSION['MMDVMHostConfigs']);
	if ( $testMMDVModePOCSAG == 1 || isPaused("POCSAG")) { //Hide the POCSAG information when POCSAG Network mode not enabled.
?>
<div class="divTable">
  <div class="divTableHead">POCSAG Status</div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
	echo "<div class='divTableHeadCell'>TX</div>\n<div class='divTableCell cell_content'>\n<div style=\"background: $tableRowEvenBg;\">".getMHZ(getConfigItem("POCSAG", "Frequency", $_SESSION['MMDVMHostConfigs']))."</div></div>\n";
	echo "</div>\n</div>\n</div>\n";
	echo "<div class='divTable'>\n";
	if (isPaused("POCSAG")) {
		$dapnetGatewayRemoteAddr = "Mode Paused";
		$dapnetGatewayRemoteTooltip = $dapnetGatewayRemoteAddr;
	    } else {
		if (isset($_SESSION['DAPNETGatewayConfigs']['DAPNET']['Address'])) {
		    $dapnetGatewayRemoteAddr = $_SESSION['DAPNETGatewayConfigs']['DAPNET']['Address'];
		    $dapnetGatewayRemoteTooltip = $dapnetGatewayRemoteAddr;
		    if (strlen($dapnetGatewayRemoteAddr) > 20) {
		        $dapnetGatewayRemoteAddr = substr($dapnetGatewayRemoteAddr, 0, 15) . ' ...';
		    }
		}
	    }
	    echo "<div class='divTableHead'>DAPNET Server</div>\n";
	    echo "<div class='divTableBody'>\n";
	    echo "<div class='divTableRow center'>\n";
	    if (isProcessRunning("DAPNETGateway")) {
		echo "<div class='divTableCell cell_content'><div style=\"background: $tableRowEvenBg;\" title=\"".$dapnetGatewayRemoteTooltip."\">".$dapnetGatewayRemoteAddr."</div></div>\n";
	    }
	    else {
		echo "<div class='divTableCell cell_content'><div class='inactive-mode-cell'>Service Not Started</div></div>\n";
	    }
?>
    </div>
  </div>
</div>
<br />
<?php
	}
    $testAPRSdmr = $_SESSION['DMRGatewayConfigs']['APRS']['Enable'];
    $testAPRSysf = $_SESSION['YSFGatewayConfigs']['APRS']['Enable'];
    $testAPRSm17 = $_SESSION['M17GatewayConfigs']['APRS']['Enable'];
    $testAPRSnxdn = $_SESSION['NXDNGatewayConfigs']['APRS']['Enable'];
    $testAPRSdgid = $_SESSION['DGIdGatewayConfigs']['APRS']['Enable'];
    $testAPRSircddb = $_SESSION['ircDDBConfigs']['aprsEnabled'];
    if (getServiceEnabled('/etc/aprsgateway') == 1 || isPaused("APRS"))  { // Hide APRS-IS GW info when GW not enabled
?>
<div class="divTable">
  <div class="divTableHead">APRS Gateway Status</div>
  <div class="divTableBody">
    <div class="divTableRow center">
<?php
if (!isProcessRunning("APRSGateway")) {
?>
<?php echo "<div class='divTableCell cell_content'><div class='inactive-mode-cell'>Service Not Started</div></div>\n"; ?>
    </div>
  </div>
</div>
<br />
<?php
} else {
?>
    </div>
  </div>
</div>
<div class="divTable">
  <div class="divTableBody">
    <div class="divTableRow center">
      <div class='divTableHeadCell'>Pool</div> 
<?php echo "<div class='divTableCell cell_content center' title=\"Connected to Pool: ".$_SESSION['APRSGatewayConfigs']['APRS-IS']['Server']."\">".substr($_SESSION['APRSGatewayConfigs']['APRS-IS']['Server'], 0, 23)."</div>\n"; ?>
    </div>
    <div class="divTableRow center">
      <div class="divTableHeadCell">Server</div>
<?php
if(strpos(getAPRSISserver(), 'Not Conn') !== false) {
    echo "<div class='divTableCell cell_content'><div class='inactive-mode-cell'>Not Connected</div></div>\n";
} else {
    echo "<div class='divTableCell cell_content center'><div style=\"background: $tableRowEvenBg;\">".getAPRSISserver()."</div></div>\n";
}
?>
    </div>
  </div>
</div>
<div class="mode_flex">
  <div class="mode_flex row">
    <div class="mode_flex column">
      <div class="divTableHead">Publishing for Modes</div>
    </div>
  </div>
<?php
    if ($testAPRSdmr == 0 && $testAPRSircddb == 0 && $testAPRSysf == 0 && $testAPRSdgid == 0 && $testAPRSnxdn == 0 && $testAPRSm17 == 0) {
?>
  <div class="divTable">
    <div class="divTableBody">
      <div class="divTableRow center">
<?php echo "<div class='divTableCell cell_content center' style=\"background: $tableRowEvenBg;\" title=\"No Mode(s) Selected\"><a href='/admin/configure.php#APRSgw'>No Mode(s) Selected</a></div>\n"; ?>
      </div>
    </div>
  </div>
</div>
<br />
<?php
    } else {
?>
  <div class="mode_flex row">
    <div class="mode_flex column">
<?php	if ($testAPRSdmr == 1) { echo "<div class=\"divTableCell\"><div class=\"active-mode-cell\">DMR</div></div>\n"; } else { echo "<div class=\"divTableCell\"><div class=\"disabled-mode-cell\">DMR</div></div>\n"; } ?>
    </div>
    <div class="mode_flex column">
<?php	if ($testAPRSircddb == 1) { echo "<div class=\"divTableCell\"><div class=\"active-mode-cell\">ircDDB</div></div>\n"; } else { echo "<div class=\"divTableCell\"><div class=\"disabled-mode-cell\">ircDDB</div></div>\n"; } ?>
    </div>
  </div>
  <div class="mode_flex row">
    <div class="mode_flex column">
<?php	if ($testAPRSysf == 1) { echo "<div class=\"divTableCell\"><div class=\"active-mode-cell\">YSF</div></div>\n"; } else { echo "<div class=\"divTableCell\"><div class=\"disabled-mode-cell\">YSF</div></div>\n"; } ?>
      </div>
    <div class="mode_flex column">
<?php	if ($testAPRSdgid == 1) { echo "<div class=\"divTableCell\"><div class=\"active-mode-cell\">DGId</div></div>\n"; } else { echo "<div class=\"divTableCell\"><div class=\"disabled-mode-cell\">DGId</div></div>\n"; } ?>
      </div>
    </div>
  <div class="mode_flex row">
    <div class="mode_flex column">
<?php	if ($testAPRSnxdn == 1) { echo "<div class=\"divTableCell\"><div class=\"active-mode-cell\">NXDN</div></div>\n"; } else { echo "<div class=\"divTableCell\"><div class=\"disabled-mode-cell\">NXDN</div></div>\n"; } ?>
    </div>
    <div class="mode_flex column">
<?php	if ($testAPRSm17 == 1) { echo "<div class=\"divTableCell\"><div class=\"active-mode-cell\">M17</div></div>\n"; } else { echo "<div class=\"divTableCell\"><div class=\"disabled-mode-cell\">M17</div></div>\n"; } ?>
    </div>
  </div>

</div>
<br />
<?php
      }
    }
}
?>
