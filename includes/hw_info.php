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

if (isset($_SESSION['CSSConfigs']['Text']['TextColor'])) {
    $textContent = $_SESSION['CSSConfigs']['Text']['TextColor'];
}

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
                 'os' => preg_replace('/\(|\)/','"', trim( exec( 'lsb_release -sd' ) )),
                 'os_ver' => trim( exec( 'cat /etc/debian_version' ) ),
    );
}

// Retrieve server information
$system = system_information();

function formatSize( $bytes ) {
    $types = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $types[$i] );
}

// root fs info
$diskUsed = @exec("df --block-size=1 / | tail -1 | awk {'print $3'}");
$diskTotal = @exec("df --block-size=1 / | tail -1 | awk {'print $2'}");
$diskPercent = sprintf('%.2f',($diskUsed / $diskTotal) * 100);
$rootfs_free = $diskTotal - $diskUsed;
$rootfs_stats = formatSize($diskUsed). " of " .formatSize($diskTotal);
$rootfsTip = "<strong>Used:</strong> $diskPercent%<br><strong>Free:</strong> ".formatSize($rootfs_free);

// Get the CPU temp and colour the box accordingly...
// Values/thresholds gathered from: 
// <https://www.rs-online.com/designspark/how-does-raspberry-pi-deal-with-overheating>
if (file_exists('/sys/class/thermal/thermal_zone0/temp')) {
    $cpuTempCRaw = exec('cat /sys/class/thermal/thermal_zone0/temp');
    if ($cpuTempCRaw > 1000) { $cpuTempC = sprintf('%.0f',round($cpuTempCRaw / 1000, 1)); } else { $cpuTempC = sprintf('%.0f',round($cpuTempCRaw, 1)); }
    $cpuTempF = sprintf('%.0f',round(+$cpuTempC * 9 / 5 + 32, 1));
    if ($cpuTempC <= 59) { $cpuTempHTML = "<div class=\"divTableCell cell_content\" style=\"background: inherit\">".$cpuTempF."&deg;F / ".$cpuTempC."&deg;C</div>\n"; }
    if ($cpuTempC >= 60) { $cpuTempHTML = "<div class=\"divTableCell cell_content\" style=\"background: #fa0;color:black;\">".$cpuTempF."&deg;F / ".$cpuTempC."&deg;C</div>\n"; }
    if ($cpuTempC >= 80) { $cpuTempHTML = "<div class=\"divTableCell cell_content\" style=\"background: #f00;color:black;font-weight:bold;\">".$cpuTempF."&deg;F / ".$cpuTempC."&deg;C</div>\n"; }
}

$loads = sys_getloadavg();
$core_nums = trim(shell_exec("grep -c '^processor' /proc/cpuinfo"));
$load = number_format(round($loads[0]/($core_nums + 1)*100, 2));

// get ram
$sysRamUsed = $system['mem_info']['MemTotal'] - $system['mem_info']['MemFree'] - $system['mem_info']['Buffers'] - $system['mem_info']['Cached'];
$sysRamPercent = sprintf('%.2f',($sysRamUsed / $system['mem_info']['MemTotal']) * 100); 
$ramDeetz = formatSize($sysRamUsed). " of ".formatSize($system['mem_info']['MemTotal']);
$ramTip = "<strong>Used:</strong> $sysRamPercent%<br><strong>Free:</strong> ".formatSize($system['mem_info']['MemTotal'] - $sysRamUsed);

// inet traffic
$iface = $_SESSION['PiStarRelease']['Pi-Star']['iface'];
$VNStatGetData = exec("vnstat -i $iface | grep today | sed 's/today//g' | awk '{print $1\" \"$2\" \"$4\" \"$5\" \"$7\" \"$8\" \"$10\" \"$11}'"); // fields: rx[0] unit[1] tx[2] unit[3] total[4] unit[5] rate[6] unit[7]
if (empty($VNStatGetData) == false) {
    $Data = explode(" ", $VNStatGetData);
    $NetworkTraffic = "$Data[0] $Data[1] &darr; / $Data[2] $Data[3] &uarr;";
    $NetTrafficTotal = "$Data[4] $Data[5] combined<br />";
    $NetTrafficAvg = "$Data[6] $Data[7] avg. rate<br />";
} else {
    $NetworkTraffic = "Collecting data, please wait.";
    $NetTrafficTotal = "Collecting data, please wait.";
    $NetTrafficAvg = "<br>";
}
?>
<div class="divTable" id="hwInfoTable">
  <div class="divTableBody">
    <div class="divTableRow">
      <div class="divTableHeadCell"><a class="tooltip" href="#"><?php echo __( 'CPU Load' );?><span><strong><?php echo __( 'CPU Load' );?></strong></a></span></div>
      <div class="divTableHeadCell"><a class="tooltip" href="#"><?php echo __( 'CPU Temp' );?><span><strong>CPU Temp</strong></a><span></div>
      <div class="divTableHeadCell"><a class="tooltip" href="#">Memory Usage<span><strong>Memory Usage</strong></a></span></div>
      <div class="divTableHeadCell"><a class="tooltip" href="#">Disk Usage<span><strong>Disk Usage</strong></a></span></div>
      <div class="divTableHeadCell"><a class="tooltip" href="#">Network Traffic<span><strong>Total Network Traffic Today</strong></a></span></div>
    </div>
    <div class="divTableRow">
    <?php if (file_exists('/sys/class/thermal/thermal_zone0/temp')) { ?>
      <div class="divTableCell cell_content middle"><a class="tooltip" href="#" style="border-bottom:1px solid; color:<?php echo $textContent; ?>;"><?php echo $load; ?>%<span><strong>Hardware:</strong> <?php echo $_SESSION['PiStarRelease']['Pi-Star']['Hardware'];?><br /><strong>Platform:</strong> <?php echo $_SESSION['PiStarRelease']['Pi-Star']['Platform'];?><br /><strong><?php echo 'OS:</strong> ' . $system['os'] . " (release ver. " . $system['os_ver']; ?>)<br /><strong>Linux Kernel:</strong> <?php echo php_uname('r');?><br /><strong>Uptime:</strong> <?php  echo(str_replace("up", "", exec('uptime -p')));?></a></span></div>
      <?php echo $cpuTempHTML; ?>
    <?php } ?>
      <div class="divTableCell cell_content middle"><a class="tooltip" href="#" style="border-bottom:1px dotted;color: <?php echo $textContent;?>;"><?php echo $ramDeetz; ?><span><?php echo $ramTip; ?></span></a></div>
      <div class="divTableCell cell_content middle;"><a class="tooltip" href="#" style="border-bottom:1px dotted;color: <?php echo $textContent;?>;"><?php echo $rootfs_stats;?><span><?php echo $rootfsTip; ?></span></a></div>
      <div class="divTableCell cell_content middle;"><a class="tooltip" href="#" style="border-bottom:1px dotted;color: <?php echo $textContent;?>;"><?php echo $NetworkTraffic;?><span><strong>Total Network Traffic</strong><br /><?php echo "$NetTrafficTotal $NetTrafficAvg"; ?>(Interface: <?php echo($iface); ?>)</a></span></div>
    </div>
  </div>
</div>
