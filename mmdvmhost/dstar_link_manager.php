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

/*
function getActiveLink($linkLine, $linkLogPath) {
    $logContent = file_get_contents($linkLogPath);

    $patterns = array(
        '/DCS link - Type: Repeater Rptr: (\w+)  (\w+) Refl: (\w+) (\w+) Dir: (\w+)/',
        '/DExtra link - Type: Repeater Rptr: (\w+)  (\w+) Refl: (\w+) (\w+) Dir: (\w+)/',
        '/DPlus link - Type: Dongle Rptr: (\w+)  (\w+) Refl: (\w+) (\w+) Dir: (\w+)/'
    );

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $logContent, $matches)) {
            $refLink = isset($matches[3]) ? $matches[3] : '';
            $modLink = isset($matches[4]) ? $matches[4] : '';

            return array(
                'refLink' => $refLink,
                'modLink' => $modLink
            );
        }
    }

    return null;
}
*/

if ($_SERVER["PHP_SELF"] == "/admin/index.php") {
    if (!empty($_POST) && isset($_POST["dstrMgrSubmit"])) { // :
	//if (!empty($_POST)):
	if (preg_match('/[^A-Z]/',$_POST["Link"])) {
	    unset ($_POST["Link"]);
	}
	if ($_POST["Link"] == "LINK") {
	    if (preg_match('/[^A-Za-z0-9 ]/',$_POST["RefName"])) {
		unset ($_POST["RefName"]);
	    }
	    if (preg_match('/[^A-Z]/',$_POST["Letter"])) {
		unset ($_POST["Letter"]);
	    }
	    if (preg_match('/[^A-Z0-9 ]/',$_POST["Module"])) {
		unset ($_POST["Module"]);
	    }
	}

	if ($_POST["Link"] == "UNLINK") {
	    if (preg_match('/[^A-Z0-9 ]/',$_POST["Module"])) { unset ($_POST["Module"]);}
	}
	
	if (empty($_POST["RefName"]) || empty($_POST["Letter"]) || empty($_POST["Module"])) {
	    echo "Somthing wrong with your input, try again";
	}
	else {
	    if (strlen($_POST["RefName"]) != 7) {
		$targetRef = str_pad($_POST["RefName"], 7, " ");
	    }
	    else {
		$targetRef = $_POST["RefName"];
	    }
	    $targetRef = $targetRef.$_POST["Letter"];
	    $targetRef = strtoupper($targetRef);
	    $module = $_POST["Module"];
	    
            if (strlen($module) != 8) {							//Fix the length of the module information
		$moduleFixedCs = strlen($module) - 1;                                   //Length of the string, -1
                $moduleFixedBand = substr($module, -1);                                 //Single Band Letter in the 8th position
                $moduleFixedCallPad = str_pad(substr($module, 0, $moduleFixedCs), 7);   //Pad the callsign area to 7 chars
                $module = $moduleFixedCallPad.$moduleFixedBand;                         //Re add the band information
            };
	    
	    $unlinkCommand = "sudo remotecontrold \"".$module."\" unlink";
	    $linkCommand = "sudo remotecontrold \"".$module."\" link never \"".$targetRef."\"";
	    
	    if ($module != $targetRef && $_POST["Link"] == "LINK") {	// Sanity check that we are not connecting to ourself
		echo "<div style='text-align:left;font-weight:bold;'>D-Star Link Manager</div>\n";
		echo "<table>\n<tr><th>Command Output</th></tr>\n<tr><td><br />";
		echo system($linkCommand);
		echo "<br /><br />Page reloading...<br /><br /></td></tr>\n</table>\n";
	    }
	    if ($module == $targetRef && $_POST["Link"] == "LINK") {	// Sanity Check Failed
		echo "<div style='text-align:left;font-weight:bold;'>D-Star Link Manager</div>\n";
		echo "<table>\n<tr><th>Command Output</th></tr>\n<tr><td>";
		echo "<br />Cannot link to myself - Aborting link request!<br />";
		echo "<br /><br />Page reloading...<br /><br /></td></tr>\n</table>\n";
	    }
	    if ($_POST["Link"] == "UNLINK") {				// Allow Unlink no matter what
		echo "<div style='text-align:left;font-weight:bold;'>D-Star Link Manager</div>\n";
		echo "<table>\n<tr><th>Command Output</th></tr>\n<tr><td><br />";
		echo system($unlinkCommand);
		echo "<br /><br />Page reloading...<br /><br /></td></tr>\n</table>\n";
	    }
	}
	
	unset($_POST);
	echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},2000);</script>';
    }
    else {
?>
        <div style="text-align:left;font-weight:bold;"><?php echo __( 'D-Star Link Manager' );?></div>
	<form action="/admin/?func=ds_man" method="post">
	    <table>
		<tr>
		    <th><a class="tooltip" href="#">Radio Module<span><b>Radio Module</b></span></a></th>
		    <th><a class="tooltip" href="#">Reflector<span><b>Reflector</b></span></a></th>
		    <th><a class="tooltip" href="#">Link / Unlink<span><b>Link or unlink</b></span></a></th>
		    <th><a class="tooltip" href="#">Action<span><b>Action</b></span></a></th>
		</tr>
		<tr>
		    <td>
			<select name="Module">
			    <?php
			    $ci = 0;
			    for($i = 1;$i < 5; $i++) {
				$param="repeaterBand" . $i;
				if((isset($_SESSION['ircDDBConfigs'][$param])) && strlen($_SESSION['ircDDBConfigs'][$param]) == 1) {
				    $ci++;
				    if($ci > 1) {
					$ci = 0;
				    }
				    $module = $_SESSION['ircDDBConfigs'][$param];
				    $rcall = sprintf("%-7.7s%-1.1s",$MYCALL,$module);
				    $param="repeaterCall" . $i;
				    if(isset($_SESSION['ircDDBConfigs'][$param])) {
					$rptrcall=sprintf("%-7.7s%-1.1s",$_SESSION['ircDDBConfigs'][$param],$module);
				    }
				    else {
					$rptrcall = $rcall;
				    }
				    print "<option>$rptrcall</option>\n";
				}
			    } ?>
			</select>
		    </td>
		    <td>
			<select name="RefName" class="RefName"
				onchange="if (this.options[this.selectedIndex].value == 'customOption') {
				      toggleField(this,this.nextSibling);
				      this.selectedIndex='0';
				      } ">
			    <?php
			    /*
			    $result = getActiveLink($linkLine, $linkLogPath);

			    if ($result !== null) {
				$refLink = $result['refLink'];
				$modLink = $result['modLink'];
			    } else {
				$refLink = "None";
				$modLink = "";
			    }
			    */

			    $dcsFile = fopen("/usr/local/etc/DCS_Hosts.txt", "r");
			    $dplusFile = fopen("/usr/local/etc/DPlus_Hosts.txt", "r");
			    $dextraFile = fopen("/usr/local/etc/DExtra_Hosts.txt", "r");
			    
			    echo "    <option value=\"".substr($_SESSION['ircDDBConfigs']['reflector1'], 0, 6)."\" selected=\"selected\">".substr($_SESSION['ircDDBConfigs']['reflector1'], 0, 6)."</option>\n";
 
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
			</select><input name="RefName" style="display:none;" disabled="disabled" type="text" size="7" maxlength="7"
					onblur="if(this.value==''){toggleField(this,this.previousSibling);}" />
			<select name="Letter" class="ModSel">
			    <?php echo "  <option value=\"".substr($_SESSION['ircDDBConfigs']['reflector1'], 7)."\" selected=\"selected\">".substr($_SESSION['ircDDBConfigs']['reflector1'], 7)."</option>\n"; ?>
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
		    <td>
                       <input type="radio" id="link" name="Link" value="LINK" /> <label for="link"/>Link</label>
                       <input type="radio" id="unlink" name="Link" value="UNLINK" checked="checked"  /> <label for="unlink"/>Un-Link</label>
		    </td>
		    <td>
			<input type="submit" name="dstrMgrSubmit" value="Request Change" />
		    </td>
		</tr>
	    </table>
	</form>
<?php
    } //endif;
}
?>
