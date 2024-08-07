<?php

if (!isset($_SESSION) || !is_array($_SESSION)) {
    session_id('wpsdsession');
    session_start();
}

function format_time($seconds) {
	$secs = intval($seconds % 60);
	$mins = intval($seconds / 60 % 60);
	$hours = intval($seconds / 3600 % 24);
	$days = intval($seconds / 86400);
	$uptimeString = "";

	if ($days > 0) {
		$uptimeString .= $days;
		$uptimeString .= (($days == 1) ? "&nbsp;day" : "&nbsp;days");
	}
	if ($hours > 0) {
		$uptimeString .= (($days > 0) ? ", " : "") . $hours;
		$uptimeString .= (($hours == 1) ? "&nbsp;hr" : "&nbsp;hrs");
	}
	if ($mins > 0) {
		$uptimeString .= (($days > 0 || $hours > 0) ? ", " : "") . $mins;
		$uptimeString .= (($mins == 1) ? "&nbsp;min" : "&nbsp;mins");
	}
	if ($secs > 0) {
		$uptimeString .= (($days > 0 || $hours > 0 || $mins > 0) ? ", " : "") . $secs;
		$uptimeString .= (($secs == 1) ? "&nbsp;s" : "&nbsp;s");
	}
	return $uptimeString;
}

function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
	return true;
    }
    return (strcasecmp(substr($haystack, -$length), $needle) == 0);
}

function getMHZ( $freq ) {
	$freq = number_format($freq, 0, '.', '.') ;
	return preg_replace( '/\.000$/', '', $freq ) . " MHz";
}

function isProcessRunning($processName, $full = false, $refresh = false) {
  if ($full) {
    static $processes_full = array();
    if ($refresh) $processes_full = array();
    if (empty($processes_full))
      exec('ps -eo args', $processes_full);
  } else {
    static $processes = array();
    if ($refresh) $processes = array();
    if (empty($processes))
      exec('ps -eo comm', $processes);
  }
  foreach (($full ? $processes_full : $processes) as $processString) {
    if (strpos($processString, $processName) !== false)
      return true;
  }
  return false;
}

function createConfigLines() { 
	$out ="";
	foreach($_GET as $key=>$val) { 
		if($key != "cmd") {
			$out .= "define(\"$key\", \"$val\");"."\n";
		}
	}
	return $out;
} 

function getSize($filesize, $precision = 2) {
	$units = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
	foreach ($units as $idUnit => $unit) {
		if ($filesize > 1024)
			$filesize /= 1024;
		else
			break;
	}
	return round($filesize, $precision).' '.$units[$idUnit].'B';
}

function encode($hex) {
    $validchars = " abcdefghijklmnopqrstuvwxyzäöüßABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÜ0123456789";
    $str        = '';
    $chrval     = hexdec($hex);
    $str        = chr($chrval);
    if (strpos($validchars, $str)>=0)
      return $str;
    else
      return "";
}

/**
 * Show time ago in a nice way
 */
function timeago( $date, $now ) {
  $timestamp   = $date;
  $strTime     = array( "sec", "min", "hr", "day", "month", "year" );
  $length      = array( "60","60","24","30","12","10" );
  $currentTime = $now;
  if( $currentTime >= $timestamp ) {
    $diff = $currentTime - $timestamp;
    for( $i = 0; $diff >= $length[$i] && $i < count( $length ) - 1; $i++ ) {
      $diff = $diff / $length[$i];
    }
    $diff = round($diff);
    return sprintf( ngettext( "%d %s", "%d %ss", $diff ), $diff, $strTime[$i] ) . ' ago';
  }
}

/**
 * Fix ALL CAPS names in callsigns (e.g. clubs, trustees, etc.)
*/
function sentence_cap($impexp, $sentence_split) {
    $textbad=explode($impexp, $sentence_split);
    $newtext = array();
    foreach ($textbad as $sentence) {
        $sentencegood=ucfirst(strtolower($sentence));
        $newtext[] = $sentencegood;
    }
    $textgood = implode($impexp, $newtext);
    return $textgood;
}

/**
 * is_countable polyfill for users on the old buster images
 */
if ( ! function_exists( 'is_countable' ) ) :
  /**
   * Verify that the content of a variable is an array or an object
   * implementing Countable
   *
   * @param mixed $var The value to check.
   * @return bool Returns TRUE if var is countable, FALSE otherwise.
   */
  function is_countable( $var ) {
    return is_array( $var )
      || $var instanceof \Countable
      || $var instanceof \SimpleXMLElement
      || $var instanceof \ResourceBundle;
  }
endif;

// lang stuffs
function __( $string ) {
  global $lang;
  if ( isset( $lang[ $string ] ) ) {
    return $lang[ $string ];
  }
  return $string;
}
// m0ar lang stuffs
function _e( $string ) {
  global $lang;
  if ( isset( $lang[ $string ] ) ) {
    echo $lang[ $string ];
  }
  echo $string;
}

function get_os_name() {
    $osReleaseFile = '/etc/os-release';
    if (file_exists($osReleaseFile)) {
        $osReleaseContents = file_get_contents($osReleaseFile);
        $pattern = '/VERSION_CODENAME=(\w+)/';
        if (preg_match($pattern, $osReleaseContents, $matches)) {
            $debianCodename = $matches[1];
            return $debianCodename;
        }
    }
    return null; // Return null if the codename is not found
}
$osName = get_os_name();

function get_os_ver() {
    $osVersionFile = '/etc/debian_version';
    if (file_exists($osVersionFile)) {
    	$os_ver = trim( exec( "cat $osVersionFile" ) );
	return $os_ver;
    }
    return null; // Return null if the version is not found
}
$osVer = get_os_ver();

function isRaspberryPi5() {
    $output = shell_exec('.wpsd-platform-detect');
    return strpos($output, 'Raspberry Pi 5') !== false;
}
$isPi5 = isRaspberryPi5();

?>

