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

include_once $_SERVER['DOCUMENT_ROOT'].'/config/ircddblocal.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';	      // Translation Code

if (isset($_SESSION['CSSConfigs']['Background'])) {
    $backgroundModeCellActiveColor = $_SESSION['CSSConfigs']['Background']['ModeCellActiveColor'];
    $backgroundModeCellPausedColor = $_SESSION['CSSConfigs']['Background']['ModeCellPausedColor'];
    $backgroundModeCellInactiveColor = $_SESSION['CSSConfigs']['Background']['ModeCellInactiveColor'];
}
?>
<div style="text-align:left;font-weight:bold;"><?php echo __( 'D-Star Link Information' );?></div>
<table id="activeLinks">
    <tr>
	<th><a class="tooltip" href="#">Radio<span><b>Radio Module</b></span></a></th>
	<th><a class="tooltip" href="#">Default Ref.<span><b>Default Link Destination</b></span></a></th>
	<th><a class="tooltip" href="#">Auto-Link<span><b>Automatic Link Connection</b></span></a></th>
	<th><a class="tooltip" href="#">Timer<span><b>Reset/Restart Timer</b></span></a></th>
	<th><a class="tooltip" href="#">Link Status<span><b>Link Status</b></span></a></th>
	<th><a class="tooltip" href="#">Linked to<span><b>Linked Destination</b></span></a></th>
	<th><a class="tooltip" href="#">Mode<span><b>Mode or Protocol used</b></span></a></th>
	<th><a class="tooltip" href="#">Direction<span><b>Direction of Connection</b></span></a></th>
	<th><a class="tooltip" href="#">Last Change (<?php echo date('T')?>)<span><b>Timestamp of last change</b>Time of last change in <?php echo date('T')?> time zone</span></a></th>
    </tr>
    
    <?php
    $tot = array(0=>"Never",1=>"Fixed",2=>"5min",3=>"10min",4=>"15min",5=>"20min",6=>"25min",7=>"30min",8=>"60min",9=>"90min",10=>"120min",11=>"180min",12=>"&nbsp;");
    $ci = 0;
    $tr = 0;
    for($i = 1;$i < 5; $i++) {
	$param="repeaterBand" . $i;
	if((isset($_SESSION['ircDDBConfigs'][$param])) && strlen($_SESSION['ircDDBConfigs'][$param]) == 1) {
	    $ci++;
	    if($ci > 1) {
		$ci = 0;
	    }
	    print "<tr>";
	    $tr = 1;
	    $module = $_SESSION['ircDDBConfigs'][$param];
	    $rcall = sprintf("%-7.7s%-1.1s", $_SESSION['MYCALL'], $module);
	    $param="repeaterCall" . $i;
	    if(isset($_SESSION['ircDDBConfigs'][$param])) {
		$rptrcall=sprintf("%-7.7s%-1.1s",$_SESSION['ircDDBConfigs'][$param],$module);
	    }
	    else {
		$rptrcall = $rcall;
	    }
	    print "<td class='mono'>".str_replace(' ', '&nbsp;', substr($rptrcall,0,8))."</td>";
	    $param="reflector" . $i;
	    if(isset($_SESSION['ircDDBConfigs'][$param])) {
		print "<td>".str_replace(' ', '&nbsp;', substr($_SESSION['ircDDBConfigs'][$param],0,8))."</td>";
	    }
	    else {
		print "<td>&nbsp;</td>";
	    }
	    $param="atStartup" . $i;
	    if($_SESSION['ircDDBConfigs'][$param] == 1) {
		print "<td><span title='Yes' class='green_dot style='font-weight:bold;'></span></td>";
	    }
	    else {
		print "<td><span title='No' class='red_dot' style='font-weight:bold;'></span></td>";
	    }
	    $param="reconnect" . $i;
	    if(isset($_SESSION['ircDDBConfigs'][$param])) {
		$t = $_SESSION['ircDDBConfigs'][$param];
	    }
	    else {
		$t = 0;
	    }
	    if($t > 12) {
		$t = 12;
	    }
	    print "<td>$tot[$t]</td>";
	    $j = 0;
	    if (file_exists($linkLogPath) && (($linkLog = fopen($linkLogPath,'r')))) {
		while ($linkLine = fgets($linkLog)) {
		    $statimg = "Down";
		    $color    = "red_dot";
                    $linkDate = "&nbsp;";
                    $protocol = "&nbsp;";
                    $linkType = "&nbsp;";
                    $linkRptr = "&nbsp;";
                    $linkRefl = "&nbsp;";
		    // Reflector-Link, sample:
		    // 2011-09-22 02:15:06: DExtra link - Type: Repeater Rptr: DB0LJ  B Refl: XRF023 A Dir: Outgoing
		    // 2012-10-12 17:15:45: DCS link - Type: Repeater Rptr: DB0LJ  B Refl: DCS001 L Dir: Outgoing
		    // 2012-10-12 17:56:10: DCS link - Type: Repeater Rptr: DB0RPL B Refl: DCS015 B Dir: Outgoing
                    if(preg_match_all('/^(.{19}).*(D[A-Za-z]*).*Type: ([A-Za-z]*).*Rptr: (.{8}).*Refl: (.{8}).*Dir: Outgoing$/',$linkLine,$linx) > 0) {
			$statimg = "Up";
			$color = "green_dot";
			$linkDate = date("d-M-Y H:i:s", strtotime(substr($linx[1][0],0,19)));
                        $protocol = $linx[2][0];
                        $linkType = $linx[3][0];
                        $linkRptr = $linx[4][0];
                        $linkRefl = $linx[5][0];
			if($linkRptr == $rptrcall) {
			    print "<td><span title='$statimg' class='$color' style='font-weight:bold;'></span></td>";
			    print "<td>".str_replace(' ', '&nbsp;', substr($linkRefl,0,8))."</td>";
			    print "<td>$protocol</td>";
			    print "<td>Outgoing</td>";
			    $utc_time = $linkDate;
                            $utc_tz =  new DateTimeZone('UTC');
                            $local_tz = new DateTimeZone(date_default_timezone_get ());
                            $dt = new DateTime($utc_time, $utc_tz);
                            $dt->setTimeZone($local_tz);
                            if (constant("TIME_FORMAT") == "24") {
                                $local_time = date('H:i:s M j');
                            } else {
                                $local_time = date('h:i:s A M j');
                            }
			    print "<td>$local_time</td>";
			    print "</tr>\n";
                    	    $tr = 0;
			}
		    }
		}
		fclose($linkLog);
	    }
	    
	    if ($tr == 1) {
		print"<td><span title='Down' class='red_dot' style='font-weight:bold;'></span></td><td>Not Linked</td><td>--</td><td>----</td><td>----</td></tr>\n";
	    }
	    // 00000000001111111111222222222233333333334444444444555555555566666666667777777777888888888899999999990000000000111111111122
	    // 01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901
	    // 2012-05-08 21:16:31: DExtra link - Type: Repeater Rptr: DB0LJ  A Refl: DB0MYK B Dir: Incoming
	    // 2012-05-08 21:16:31: DPlus link - Type: Dongle User: W1CDG  H Dir: Incoming
	    if (file_exists($linkLogPath)  && ($linkLog = fopen($linkLogPath,'r'))) {
		while ($linkLine = fgets($linkLog)) {
		    $statimg = "Down";
		    $color   = "red_dot";
                    $linkDate = "&nbsp;";
                    $protocol = "&nbsp;";
                    $linkType = "&nbsp;";
                    $linkRptr = "&nbsp;";
                    $linkRefl = "&nbsp;";
                    if(preg_match_all('/^(.{19}).*(D[A-Za-z]*).*Type: ([A-Za-z]*).*Rptr: (.{8}).*Refl: (.{8}).*Dir: Incoming$/',$linkLine,$linx) > 0) {
			$statimg = "Up";
			$color = "green_dot";
			$linkDate = date("d-M-Y H:i:s", strtotime(substr($linx[1][0],0,19)));
                        $protocol = $linx[2][0];
                        $linkType = $linx[3][0];
                        $linkRptr = $linx[4][0];
                        $linkRefl = $linx[5][0];
			if($linkRptr == $rptrcall) {
			    $ci++;
			    if($ci > 1) {
				$ci = 0;
			    }
			    print "<tr>";
			    print "<td class='mono'>".str_replace(' ', '&nbsp;', substr($rptrcall,0,8))."</td>";
			    print "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			    print "<td><span title='$statimg' class='$color' style='font-weight:bold;'></span></td>";
			    print "<td>".str_replace(' ', '&nbsp;', substr($linkRefl,0,8))."</td>";
			    print "<td>$protocol</td>";
			    print "<td>Incoming</td>";
			    $utc_time = $linkDate;
                            $utc_tz =  new DateTimeZone('UTC');
                            $local_tz = new DateTimeZone(date_default_timezone_get ());
                            $dt = new DateTime($utc_time, $utc_tz);
                            $dt->setTimeZone($local_tz);
                            if (constant("TIME_FORMAT") == "24") {
                                $local_time = date('H:i:s M j');
                            } else {
                                $local_time = date('h:i:s A M j');
                            }
			    print "<td>$local_time</td>";
			    print "</tr>\n";
                    	    //$tr = 0;
            		}
            	    }
		}
		fclose($linkLog);
	    }
	    // 00000000001111111111222222222233333333334444444444555555555566666666667777777777888888888899999999990000000000111111111122
	    // 01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901
	    // 2012-05-08 21:16:31: DExtra link - Type: Repeater Rptr: DB0LJ  A Refl: DB0MYK B Dir: Incoming
	    // 2012-05-08 21:16:31: DPlus link - Type: Dongle User: W1CDG  H Dir: Incoming
            if (file_exists($linkLogPath) && ($linkLog = fopen($linkLogPath,'r'))) {
                while ($linkLine = fgets($linkLog)) {
                    $statimg = "Down";
		    $color   = "red_dot";
                    $linkDate = "&nbsp;";
                    $protocol = "&nbsp;";
                    $linkType = "&nbsp;";
                    $linkRptr = "&nbsp;";
                    $linkRefl = "&nbsp;";
                    if(preg_match_all('/^(.{19}).*(D[A-Za-z]*).*Type: ([A-Za-z]*).*User: (.[^\s]+).*Dir: Incoming$/',$linkLine,$linx) > 0) {
                        $statimg = "Up";
			$color = "green_dot";
                        $linkDate = date("d-M-Y H:i:s", strtotime(substr($linx[1][0],0,19)));
                        $protocol = $linx[2][0];
                        $linkType = $linx[3][0];
                        $linkRptr = $linx[4][0];
                        $ci++;
                        if($ci > 1) {
			    $ci = 0;
			}
			print "<tr>";
                        print "<td class='mono'>".str_replace(' ', '&nbsp;', substr($rptrcall,0,8))."</td>";
                        print "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
                        print "<td><span title='$statimg' class='$color' style='font-weight:bold;'></span></td>";
                        print "<td>".str_replace(' ', '&nbsp;', substr($linkRptr,0,8))."</td>";
                        print "<td>$protocol</td>";
                        print "<td>Incoming</td>";
                        $utc_time = $linkDate;
                        $utc_tz =  new DateTimeZone('UTC');
                        $local_tz = new DateTimeZone(date_default_timezone_get ());
                        $dt = new DateTime($utc_time, $utc_tz);
                        $dt->setTimeZone($local_tz);
                        if (constant("TIME_FORMAT") == "24") {
                            $local_time = date('H:i:s M j');
                        } else {
                            $local_time = date('h:i:s A M j');
                        }
                        print "<td>$local_time</td>";
                        print "</tr>\n";
                    }
                }
                fclose($linkLog);
            }
	    // End
	}
    }
    ?>
    
</table>
<br />
