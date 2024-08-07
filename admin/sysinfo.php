<?php


if (!isset($_SESSION) || !is_array($_SESSION)) {
    session_id('wpsdsession');
    session_start();
    
    unset($_SESSION['PiStarRelease']); // ensures bin. version #'s are refreshed

    include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';        // Translation Code
    checkSessionValidity();
}

// Load the language support
require_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';
include_once('mmdvmhost/tools.php');

$instanceUUID = $_SESSION['PiStarRelease']['Pi-Star']['UUID'];

function system_information() {
    @list($system, $host, $kernel) = preg_split('/[\s,]+/', php_uname('a'), 5);
    $meminfo = false;
    if (@is_readable('/proc/meminfo')) {
        $data = explode("\n", file_get_contents("/proc/meminfo"));
        $meminfo = array();
        foreach ($data as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $val) = explode(":", $line);
                $meminfo[$key] = 1024 * floatval( trim( str_replace( ' kB', '', $val ) ) );
            }
        }
    }
    return array('date' => date('Y-m-d H:i:s T'),
                 'mem_info' => $meminfo,
                 'partitions' => disk_list(),
		 'os' => preg_replace('/\(|\)/','"', trim( exec( 'lsb_release -sd' ) )),
    );
}

function disk_list() {
    $partitions = array();
    // Fetch partition information from df command
    // I would have used disk_free_space() and disk_total_space() here but
    // there appears to be no way to get a list of partitions in PHP?
    $output = array();
    @exec('df --block-size=1', $output);
    foreach($output as $line) {
        $columns = array();
        foreach(explode(' ', $line) as $column) {
            $column = trim($column);
            if($column != '') $columns[] = $column;
        }
        
        // Only process 6 column rows
        // (This has the bonus of ignoring the first row which is 7)
        if(count($columns) == 6) {
            $partition = $columns[5];
            $partitions[$partition]['Temporary']['bool'] = in_array($columns[0], array('tmpfs', 'devtmpfs'));
            $partitions[$partition]['Partition']['text'] = $partition;
            $partitions[$partition]['FileSystem']['text'] = $columns[0];
            if(is_numeric($columns[1]) && is_numeric($columns[2]) && is_numeric($columns[3])) {
                $partitions[$partition]['Size']['value'] = $columns[1];
                $partitions[$partition]['Free']['value'] = $columns[3];
                $partitions[$partition]['Used']['value'] = $columns[2];
            }
            else {
                // Fallback if we don't get numerical values
                $partitions[$partition]['Size']['text'] = $columns[1];
                $partitions[$partition]['Used']['text'] = $columns[2];
                $partitions[$partition]['Free']['text'] = $columns[3];
            }
        }
    }
    return $partitions;
}

function formatSize( $bytes ) {
    $types = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $types[$i] );
}

function timesyncdProc() {
    $cmd = exec('systemctl status systemd-timesyncd.service | grep -o running');
    if (strpos($cmd, "running") !== false) {
	return 1;
    } else {
	return 0;
    }
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
	<title>WPSD - Hardware/Software Details</title>
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/browserdetect.php'; ?>
	<link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
	<script type="text/javascript" src="/js/jquery.min.js?version=<?php echo $versionCmd; ?>"></script>
	<script type="text/javascript" src="/js/jquery-timing.min.js?version=<?php echo $versionCmd; ?>"></script>
	<style>  
         .progress .bar + .bar {
             -webkit-box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.15), inset 0 -1px 0 rgba(0, 0, 0, 0.15);
             -moz-box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.15), inset 0 -1px 0 rgba(0, 0, 0, 0.15);
             box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.15), inset 0 -1px 0 rgba(0, 0, 0, 0.15);
         }
         .progress-info .bar, .progress .bar-info {
             background-color: #347B90;
             background-image: -moz-linear-gradient(top, #3B8BA3, #2D6B7D);
             background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#3B8BA3), to(2D6B7D));
             background-image: -webkit-linear-gradient(top, #3B8BA3, #2D6B7D);
             background-image: -o-linear-gradient(top, #3B8BA3, #2D6B7D);
             background-image: linear-gradient(to bottom, #3B8BA3e, #2D6B7D);
             background-repeat: repeat-x;
             filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff339bb9', GradientType=0);
         }
	</style>
	<script type="text/javascript">
	 function refreshTable () {
	     $("#infotable").load(" #infotable > *");
	 }
	 var timer = setInterval(function(){refreshTable()}, 5000);

	 function refreshTS () {
	     $("#synctable").load(" #synctable > *");
	 }
	 var timer = setInterval(function(){refreshTS()}, 2000);
	</script>
    </head>
    <body>
	<div class="container">
	    <div class="header">
		<div class="SmallHeader shLeft">Hostname: <?php echo exec('cat /etc/hostname'); ?></div>
                <div class="SmallHeader shRight">
                <div id="CheckUpdate">
                <?php
                    include $_SERVER['DOCUMENT_ROOT'].'/includes/checkupdates.php';
                ?>
                </div><br />
                </div>
		<h1>WPSD Hardware/Software Details</h1>
		<p>
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
			<a class="menuconfig" href="/admin/configure.php"><?php echo __( 'Configuration' );?></a>
			<a class="menuupdate" href="/admin/update.php"><?php echo __( 'WPSD Update' );?></a>
			<a class="menupower" href="/admin/power.php"><?php echo __( 'Power' );?></a>
			<a class="menuadmin" href="/admin/"><?php echo __( 'Admin' );?></a>
			<a class="menudashboard" href="/"><?php echo __( 'Dashboard' );?></a>
		    </div> 
		</p>
	    </div>
	    <div class="contentwide">

            <?php
                echo '<script type="text/javascript">'."\n";
                echo 'function reloadSysInfo(){'."\n";
                echo '  $("#sysInfo").load("/includes/system.php",function(){ setTimeout(reloadSysInfo,5000) });'."\n";
                echo '}'."\n";
                echo 'setTimeout(reloadSysInfo,5000);'."\n";
                echo '</script>'."\n";
                echo '<div id="sysInfo">'."\n";
                include $_SERVER['DOCUMENT_ROOT'].'/includes/system.php';
                echo '</div>'."\n";
            ?>

		<h3 style="text-align:left;font-weight:bold;margin:5px 0 2px 0;">System Status</h3>
		<table id="infotable" width="100%" border="0">
		    <?php
		    // Retrieve server information
		    $system = system_information();

		    // Ram information
		    if ($system['mem_info']) {
			echo "  <tr><th align='left'>Memory</th><th align='left'>Stats</th></tr>\n";
			$sysRamUsed = $system['mem_info']['MemTotal'] - $system['mem_info']['MemFree'] - $system['mem_info']['Buffers'] - $system['mem_info']['Cached'];
			$sysRamPercent = sprintf('%.2f',($sysRamUsed / $system['mem_info']['MemTotal']) * 100);
			echo "  <tr><td align=\"left\">RAM</td><td align=\"left\"><div class='progress progress-info' style='margin-bottom: 0;'><div class='bar' style='width: ".$sysRamPercent."%;'>Used&nbsp;".$sysRamPercent."%</div></div>";
			echo "  <b>Total:</b> ".formatSize($system['mem_info']['MemTotal'])."<b> Used:</b> ".formatSize($sysRamUsed)."<b> Free:</b> ".formatSize($system['mem_info']['MemTotal'] - $sysRamUsed)."</td></tr>\n";
		    }
		    // Filesystem Information
		    if (count($system['partitions']) > 0) {
			echo "  <tr><th align='left'>Filesystem Mountpoints</th><th align='left'>Stats</th></tr>\n";
			foreach($system['partitions'] as $fs) {
			    if ($fs['Used']['value'] > 0 && $fs['FileSystem']['text']!= "none" && $fs['FileSystem']['text']!= "udev") {
				$diskFree = $fs['Free']['value'];
				$diskTotal = $fs['Size']['value'];
				$diskUsed = $fs['Used']['value'];
				$diskPercent = sprintf('%.2f',($diskUsed / $diskTotal) * 100);
				
				echo "  <tr><td align=\"left\">".$fs['Partition']['text']."</td><td align=\"left\"><div class='progress progress-info' style='margin-bottom: 0;'><div class='bar' style='width: ".$diskPercent."%;'>Used&nbsp;".$diskPercent."%</div></div>";
				echo "  <b>Total:</b> ".formatSize($diskTotal)."<b> Used:</b> ".formatSize($diskUsed)."<b> Free:</b> ".formatSize($diskFree)."</td></tr>\n";
			    }
			}
		    }
		    // OS Information
		    echo "<tr><th align='left'>Host System</th><th align='left'>Details</th></tr>";
		    echo "<tr><td align='left'>Operating System</td><td align='left'>{$system['os']}, release ver. $osVer</td></tr>";
		    echo "<tr><td align='left'>Hardware &amp; Platform</td><td align='left'>".$_SESSION['PiStarRelease']['Pi-Star']['Hardware']."<br />".$_SESSION['PiStarRelease']['Pi-Star']['Platform']."</td></tr>";
		    echo "<tr><td align='left'>Hardware UUID</td><td align='left'>$instanceUUID</td></tr>";
		    // Binary Information
		    echo "  <tr><th align='left'>WPSD Software Binaries</th><th align='left'>Version</th></tr>\n";
		    if (is_executable('/usr/local/bin/MMDVMHost')) {
			$MMDVMHost_Ver = exec('/usr/local/bin/MMDVMHost -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>MMDVMHost</td><td align=\"left\">".$MMDVMHost_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/DMRGateway')) {
			$DMRGateway_Ver = exec('/usr/local/bin/DMRGateway -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>DMRGateway</td><td align=\"left\">".$DMRGateway_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/DMR2YSF')) {
			$DMR2YSF_Ver = exec('/usr/local/bin/DMR2YSF -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>DMR2YSF</td><td align=\"left\">".$DMR2YSF_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/DMR2NXDN')) {
			$DMR2NXDN_Ver = exec('/usr/local/bin/DMR2NXDN -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>DMR2NXDN</td><td align=\"left\">".$DMR2NXDN_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/YSFGateway')) {
			$YSFGateway_Ver = exec('/usr/local/bin/YSFGateway -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>YSFGateway</td><td align=\"left\">".$YSFGateway_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/YSFParrot')) {
			$YSFParrot_Ver = exec('/usr/local/bin/YSFParrot -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>YSFParrot</td><td align=\"left\">".$YSFParrot_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/DGIdGateway')) {
			$DGIdGateway_Ver = exec('/usr/local/bin/DGIdGateway -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>DGIdGateway</td><td align=\"left\">".$DGIdGateway_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/ircddbgatewayd')) {
			$ircDDBGateway_Ver = $_SESSION['PiStarRelease']['Pi-Star']['ircddbgateway'];
			echo "  <tr><td align='left'>ircDDBGateway</td><td align=\"left\">".$ircDDBGateway_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/YSF2DMR')) {
			$YSF2DMR_Ver = exec('/usr/local/bin/YSF2DMR -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>YSF2DMR</td><td align=\"left\">".$YSF2DMR_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/YSF2P25')) {
			$YSF2P25_Ver = exec('/usr/local/bin/YSF2P25 -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>YSF2P25</td><td align=\"left\">".$YSF2P25_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/YSF2NXDN')) {
			$YSF2NXDN_Ver = exec('/usr/local/bin/YSF2NXDN -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>YSF2NXDN</td><td align=\"left\">".$YSF2NXDN_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/P25Gateway')) {
			$P25Gateway_Ver = exec('/usr/local/bin/P25Gateway -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>P25Gateway</td><td align=\"left\">".$P25Gateway_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/NXDNGateway')) {
			$NXDNGateway_Ver = exec('/usr/local/bin/NXDNGateway -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>NXDNGateway</td><td align=\"left\">".$NXDNGateway_Ver."</td></tr>\n";
		    }
		    if (isDVmegaCast() != 1 ) {
			if (is_executable('/usr/local/bin/M17Gateway')) {
			    $M17Gateway_Ver = exec('/usr/local/bin/M17Gateway -v | cut -d\' \' -f 3-');
			    echo "  <tr><td align='left'>M17Gateway</td><td align=\"left\">".$M17Gateway_Ver."</td></tr>\n";
			}
		    }
		    if (is_executable('/usr/local/bin/DAPNETGateway')) {
			$DAPNETGateway_Ver = exec('/usr/local/bin/DAPNETGateway -v | cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>DAPNETGateway</td><td align=\"left\">".$DAPNETGateway_Ver."</td></tr>\n";
		    }
		    if (is_executable('/usr/local/bin/APRSGateway')) {
			$APRSGateway_Ver = exec('/usr/local/bin/APRSGateway -v| cut -d\' \' -f 3-');
			echo "  <tr><td align='left'>APRSGateway</td><td align=\"left\">".$APRSGateway_Ver."</td></tr>\n";
		    }
                    if (is_executable('/usr/sbin/gpsd')) {
                        $GPSD_Ver = exec('/usr/sbin/gpsd -V | cut -d\' \' -f 2-');
                        echo "  <tr><td align='left'>GPSd</td><td align=\"left\">".$GPSD_Ver."</td></tr>\n";
                    }
		    if (isDVmegaCast() == 0) {
			if (is_executable('/usr/local/bin/NextionDriver')) {
			    $NEXTIONDRIVER_Ver = exec('/usr/local/bin/NextionDriver -V | head -n 2 | cut -d\' \' -f 3');
			    echo "  <tr><td align='left'>NextionDriver</td><td align=\"left\">".$NEXTIONDRIVER_Ver."</td></tr>\n";
			}
		    } else {
			if (is_executable('/usr/local/cast/bin/castudp')) {
			    echo "  <tr><td align='left'>DVMega Cast UDP Service</td><td align=\"left\">DVMega</td></tr>\n";
			}
		    }
?>
		</table>
		<br />
	        <h3 style="text-align:left;font-weight:bold;margin:5px 0 2px 0;">Time Synchronization Status</h3>
		<table id="synctable" width="100%" border="0">
<?php
		    // time sync status
		    echo "<tr>";
		    echo "<td align='left' colspan='2'>";
		    echo "<pre>";
		    system("timedatectl | sed -e 's/^[ \t]*/  /' | sed '/RTC/d'");
		    echo "</pre>";
		    if (timesyncdProc() == "1") {
		    	    echo "<pre>";
			    system("timedatectl timesync-status | sed -e 's/^[ \t]*/  /'");
			    echo "</pre>";
			    echo "</td>";
		    } else {
			    echo "<td align='left' class='inactive-service-cell' colspan='2'>TimeSync Deamon not running!</td>";
		    }
		    echo "</tr>";
		    ?>
		</table>
	    </div>
	    <div class="footer">
		<a href="https://wpsd.radio/">WPSD</a> &copy; <code>W0CHP</code> 2020-<?php echo date("Y"); ?><br />
	    </div>
	</div>
    </body>
</html>
