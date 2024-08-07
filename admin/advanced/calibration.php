<?php

// Load the language support
require_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';

// Load the Pi-Star Release file
$pistarReleaseConfig = '/etc/pistar-release';
$configPistarRelease = array();
$configPistarRelease = parse_ini_file($pistarReleaseConfig, true);
// Load the Version Info

// Sanity Check that this file has been opened correctly
if ($_SERVER["PHP_SELF"] == "/admin/advanced/calibration.php") {

  if (isset($_GET['action'])) {
    if ($_GET['action'] === 'start') {
      system('sudo fuser -k 33273/udp > /dev/null 2>&1');
      system('nc -ulp 33273 | sudo -i script -qfc "/usr/local/sbin/wpsd-modemcalibrate" /tmp/mmdvmcal.log > /dev/null 2>&1 &');
    }
    else if (($_GET['action'] === 'saveoffset')) {
      if (isset($_GET['param']) && strlen($_GET['param'])) {
        system('sudo mount -o remount,rw /');
        system('sudo sed -i "/RXOffset=/c\\RXOffset='.intval($_GET['param']).'" /etc/mmdvmhost');
        system('sudo sed -i "/TXOffset=/c\\TXOffset='.intval($_GET['param']).'" /etc/mmdvmhost');
      }
    }
    exit();
  }

  if (isset($_GET['cmd']) && strlen($_GET['cmd'])) {
    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_bind($sock, '127.0.0.1', 33272) || exit();
    socket_sendto($sock, $_GET['cmd'], strlen($_GET['cmd']), 0, '127.0.0.1', 33273);
    if (isset($_GET['param']) && strlen($_GET['param'])) {
      usleep(500*1000);
      socket_sendto($sock, $_GET['param']."\n", strlen($_GET['param'])+1, 0, '127.0.0.1', 33273);
    }
    if ($_GET['cmd'] === 'q') {
      sleep(1);
      socket_sendto($sock, "\n", 1, 0, '127.0.0.1', 33273); //send something to kill the pipe, also \n may be useful if something went wrong and mmdvmcal is waiting some param input
    }
    socket_close($sock);
    exit();
  }

  // Sanity Check Passed.
  header('Cache-Control: no-cache');
  session_start();

  if (!isset($_GET['ajax'])) {
    //unset($_SESSION['mmdvmcal_offset']);
    if (file_exists('/tmp/mmdvmcal.log')) {
      $_SESSION['mmdvmcal_offset'] = filesize('/tmp/mmdvmcal.log');
    } else {
      $_SESSION['mmdvmcal_offset'] = 0;
    }
  }
  
  if (isset($_GET['ajax'])) {
    //session_start();
    if (!file_exists('/tmp/mmdvmcal.log')) {
      exit();
    }
    
    $handle = fopen('/tmp/mmdvmcal.log', 'rb');
    if (isset($_SESSION['mmdvmcal_offset'])) {
      fseek($handle, 0, SEEK_END);
      if ($_SESSION['mmdvmcal_offset'] > ftell($handle)) //log rotated/truncated
        $_SESSION['mmdvmcal_offset'] = 0; //continue at beginning of the new log
      $data = stream_get_contents($handle, -1, $_SESSION['mmdvmcal_offset']);
      $_SESSION['mmdvmcal_offset'] += strlen($data);
      echo nl2br($data);
      }
    else {
      fseek($handle, 0, SEEK_END);
      $_SESSION['mmdvmcal_offset'] = ftell($handle);
      } 
  exit();
  }

  $RXFrequency = exec('grep "RXFrequency" /etc/mmdvmhost | awk -F "=" \'{print $2}\'');
  $RXOffset = exec('grep "RXOffset" /etc/mmdvmhost | awk -F "=" \'{print $2}\'');
  
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
        <title>WPSD - Digital Voice Dashboard - MMDVM Calibration</title>
        <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/browserdetect.php'; ?>
        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/jquery-timing.min.js"></script>
        <script type="text/javascript" src="/js/plotly-basic.min.js"></script>
        <script type="text/javascript">

    var rxoffset = ~~'<?php echo $RXOffset; ?>';

    function sendaction(action='', param='') {
      if (action === 'start') { document.getElementById("btnStart").disabled = true; }
      if (action === 'saveoffset') { rxoffset = ~~param }
      $.ajax({
        url: 'calibration.php',
        type: 'GET',
        data: {
          'action': action,
          'param': param
        },
        cache: false,
        success: function(msg) {}
      });
      return false;
    }
    
    var sendcmd_lock=false;

    function sendcmd(cmd='', param='') {
      if (sendcmd_lock) { return false; }
      if (param !== '') { sendcmd_lock = true; } //if we have param, lock to prevent cmd overlap while waiting param
      $.ajax({
        url: 'calibration.php',
        type: 'GET',
        data: {
          'cmd': cmd,
          'param': param
        },
        cache: false,
        success: function(msg) {},
        complete: function() { sendcmd_lock = false; }
      });
      return false;
    }
    
    var cnt=0; tcnt=0;
    var cfrms=0; cbits=0, cberr=0;
    var tfrms=0; tbits=0, tberr=0;
    var eot=false;

    function formatFreq(input) {
      const inputString = String(input);
      const digitsOnly = inputString.replace(/\D/g, '');
      const groups = digitsOnly.match(/(\d{1,3})/g);
      if (!groups || groups.length === 0) {
        return '';
      }
      return groups.join('.');
    }


    $(function() {
      $.repeat(1000, function() {
        $.get('/admin/advanced/calibration.php?ajax', function(data) {
         if (data.length > 0) {
<?php if (isset($_GET['verbose'])) { ?>
          var objDiv = document.getElementById("tail");
          var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
          $('#tail').append(data);
          if (isScrolledToBottom)
            objDiv.scrollTop = objDiv.scrollHeight;
<?php } ?>
          
          if (("\n"+data).includes("Version:")) {
            setTimeout(function(){ sendcmd('e', (~~'<?php echo $RXFrequency; ?>'+rxoffset).toString() ); }, 1000);
          }

          if (("\n"+data).includes("Starting")) {
            $('#ledStart').attr("class", 'red_dot');
            $('#ledDMR').attr("class", 'red_dot');
            $('#ledYSF').attr("class", 'red_dot');
            $('#ledP25').attr("class", 'red_dot');
            $('#ledNXDN').attr("class", 'red_dot');
            document.getElementById("btnStart").disabled = false;
          }

          if (("\n"+data).includes("Complete...")) {
            $('#ledDStar').attr("class", 'red_dot');
            $('#ledDMR').attr("class", 'red_dot');
            $('#ledYSF').attr("class", 'red_dot');
            $('#ledP25').attr("class", 'red_dot');
            $('#ledNXDN').attr("class", 'red_dot');
            document.getElementById("btnStart").disabled = false;
          }

          if (("\n"+data).includes("\nBER Test Mode (FEC) for D-Star")) {
            $('#ledStart').attr("class", 'green_dot');
            $('#ledDStar').attr("class", 'green_dot');
            $('#ledDMR').attr("class", 'red_dot');
            $('#ledYSF').attr("class", 'red_dot');
            $('#ledP25').attr("class", 'red_dot');
            $('#ledNXDN').attr("class", 'red_dot');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for DMR Simplex")) {
            $('#ledStart').attr("class", 'green_dot');
            $('#ledDStar').attr("class", 'red_dot');
            $('#ledDMR').attr("class", 'green_dot');
            $('#ledYSF').attr("class", 'red_dot');
            $('#ledP25').attr("class", 'red_dot');
            $('#ledNXDN').attr("class", 'red_dot');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for YSF")) {
            $('#ledStart').attr("class", 'green_dot');
            $('#ledDStar').attr("class", 'red_dot');
            $('#ledDMR').attr("class", 'red_dot');
            $('#ledYSF').attr("class", 'green_dot');
            $('#ledP25').attr("class", 'red_dot');
            $('#ledNXDN').attr("class", 'red_dot');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for P25")) {
            $('#ledStart').attr("class", 'green_dot');
            $('#ledDStar').attr("class", 'red_dot');
            $('#ledDMR').attr("class", 'red_dot');
            $('#ledYSF').attr("class", 'red_dot');
            $('#ledP25').attr("class", 'green_dot');
            $('#ledNXDN').attr("class", 'red_dot');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for NXDN")) {
            $('#ledStart').attr("class", 'green_dot');
            $('#ledDStar').attr("class", 'red_dot');
            $('#ledDMR').attr("class", 'red_dot');
            $('#ledYSF').attr("class", 'red_dot');
            $('#ledP25').attr("class", 'red_dot');
            $('#ledNXDN').attr("class", 'green_dot');
          }
          
          if (data.includes("voice end received,")) {
            eot=true;
          }

	  var regex = / frequency: (\d+)/g;
	  while (match = regex.exec(data)) {
	    $('#ledStart').attr("class", 'green_dot');
	    $("#lblOffset").text(~~match[1] - ~~'<?php echo $RXFrequency; ?>');
	    const inputString = match[1];
	    const formattedString = formatFreq(inputString) + ' MHz';
	    $("#lblFrequency").text(formattedString);
	  }

          var regex = /\% \((\d+)\/(\d+)\)/g
          while (match = regex.exec(data)) {
            cfrms += 1;
            cberr += ~~match[1];
            cbits += ~~match[2];
            tfrms += 1;
            tberr += ~~match[1];
            tbits += ~~match[2];
          }
         }

          if (cbits > 0) {
            cnt++; tcnt++;
            var updfrq = $('#sltUpdFrq').val();
            if ((tcnt % updfrq == 0) || eot) {
                //$('#tail').append(cfrms +' , '+ cberr +' / '+ cbits +' , '+ (cberr/cbits*100).toFixed(2) + '%<br>');
                $("#lblFrames").text(cfrms);
                $("#lblBits").text(cbits);
                $("#lblErrors").text(cberr);
                $("#lblBER").text((cberr/cbits*100).toFixed(2)+'%');
                Plotly.extendTraces('chart', { x:[[cnt]], y:[[cberr/cbits*100]] }, [0]);
                if(cnt > 60*3) {
                    Plotly.relayout('chart', {
                        xaxis: {range: [cnt-60*3,cnt]}
                    });
                }
                cfrms=0; cbits=0; cberr=0;

                //$('#tail').append('total: ' + tfrms +' , '+ tberr +' / '+ tbits +' , '+ (tberr/tbits*100).toFixed(2) + '%<br>');
                $("#lblTFrames").text(tfrms);
                $("#lblTBits").text(tbits);
                $("#lblTErrors").text(tberr);
                $("#lblTBER").text((tberr/tbits*100).toFixed(2)+'%');
                $("#lblTSec").text(tcnt);
                if (eot) {
                  eot=false;
                  tfrms=0; tbits=0; tberr=0; tcnt=0;
                }
            }
          }

        });
      });
    });
    jQuery(document).ready(function() {
      jQuery('#help_details').click(function(){
        jQuery('#help_info').slideToggle('slow');
        if(jQuery(this).text() == 'Hide Help...'){
          jQuery(this).text('Display Calibration Help...');
        } else {
          jQuery(this).text('Hide Help...');
        }
      });
    });
    </script>
  </head>
  <body>
  <div class="container">
<?php include './header-menu.inc'; ?>
  </div>
  <div class="contentwide">
  <div style="text-align:left;"><a style="color:#bebebe;text-decoration:underline;" href="#help_details" id="help_details">Display Calibration Help...</a>
  <div id="help_info" style="display:none;text-align:left;"><br />
    First, click the "Start" button, then wait until the Start Status indicator turns to green (approx. 10-30 secs.). Then, select the mode you wish to calibrate,
    and then wait until the Mode Status indicator turns green.<br /><br />TX from your radio until the BER% reaches its lowest value, while adjusting the offset by clicking  the "<code>+/-</code>"
    offset buttons. You can increase/decrease the Steps (in Hz) if you'd like (default is 50 Hz).<br /><br />
    Once happy with the value, click "Save Offset" and then click "Stop" and wait for the LED to turn red, then you're done.<br /><br />
    NOTE: The tests operate in simplex only; program your radio accordingly.
  </div></div>
  <h2 class="ConfSec center">MMDVM Calibration Tool</h2>
  <table width="100%">
  <tr><td align="left">
<table border="0" cellspacing="0">
  <tr>
    <td align="center" valign="top" width="15%"><table border="0" cellspacing="0">
      <tr>
	<th>Main Operation</th>
	<th>Status</th>
      </tr>

      <tr>
	<td style="white-space:normal" align="left"><input name="btnStart" type="button" id="btnStart" onclick="sendaction('start');" value="Start" /><p><small><i class="fa fa-question-circle"></i> Click Start ONCE, and wait 10-30 seconds until the Status indicator turns green.</small></p></td>
        <td width="30"><span class="red_dot" name="ledStart" width="20" height="20" id="ledStart"></span></td>
      </tr>
      <tr>
        <td align="left" colspan="2"><input name="btnStop" type="button" id="btnStop" onclick="sendcmd('q');" value="Stop" /></td>
      </tr>
    </table></td>

    <td align="center" valign="top"><table border="0" cellspacing="0">
      <tr>
        <th>Select Mode</th>
	<th>Status</th>
      </tr>
      <tr>
        <td><input name="btnDStar" type="button" id="btnDStar" onclick="sendcmd('k');" value="D-Star" /></td>
        <td><span class="red_dot" name="ledDStar" width="20" height="20" id="ledDStar"></span></td>
        </tr>
      <tr>
        <td><input name="btnDMR" type="button" id="btnDMR" onclick="sendcmd('b');" value="DMR" /></td>
        <td><span class="red_dot" name="ledDMR" width="20" height="20" id="ledDMR"></span></td>
        </tr>
      <tr>
        <td><input name="btnYSF" type="button" id="btnYSF" onclick="sendcmd('J');" value="YSF" /></td>
        <td><span class="red_dot" name="ledYSF" width="20" height="20" id="ledYSF"></span></td>
        </tr>
      <tr>
        <td><input name="btnP25" type="button" id="btnP25" onclick="sendcmd('j');" value="P25" /></td>
        <td><span class="red_dot" name="ledP25" width="20" height="20" id="ledP25"></span></td>
        </tr>
      <tr>
        <td><input name="btnNXDN" type="button" id="btnNXDN" onclick="sendcmd('n');" value="NXDN" /></td>
        <td><span class="red_dot" name="ledNXDN" width="20" height="20" id="ledNXDN"></span></td>
        </tr>
    </table></td>

    <td align="center" valign="top"><table border="0" cellspacing="0">
      <tr>
        <th colspan="4">Calibration Parameters</th>
      </tr>
      <tr>
        <td align="left">Base Freq.:</td>
        <td colspan="3" id="lblBaseFreq"><?php echo number_format($RXFrequency, 0, '.', '.'); ?> MHz</td>
      </tr>
      <tr>
        <td align="left">Freq. (with offset):</td>
	<?php $freqWithOffsetRaw = $RXFrequency + $RXOffset; ?>
        <td colspan="3" id="lblFrequency"><?php echo number_format($freqWithOffsetRaw, 0, '.', '.'); ?> MHz</td>
      </tr>
      <tr>
        <td align="left">Offset:</td>
        <td><input name="btnFreqM" type="button" id="btnFreqM" onclick="sendcmd('f');" value="-" /></td>
        <td id="lblOffset"><?php echo $RXOffset; ?> Hz</td>
        <td><input name="btnFreqP" type="button" id="btnFreqP" onclick="sendcmd('F');" value="+" /></td>
      </tr>
      <tr>
        <td align="left">Step: (Hz)</td>
        <td colspan="3"><input type="button" onclick="sendcmd('z','25');" value="25" /> <input type="button" onclick="sendcmd('z','50');" value="50" /> <input type="button" onclick="sendcmd('z','100');" value="100" /></td>
      </tr>
      <tr>
        <td colspan="4"><input name="button8" type="button" onclick="sendaction('saveoffset',$('#lblOffset').text());" value="Save Offset" /></td>
      </tr>
    </table></td>

    <td align="center" valign="top"><table border="0" cellspacing="0">
      <tr>
        <th style="width:8ch">Calibration Results:</th>
        <th style="width:9ch">Current</th>
        <th style="width:9ch">Total</th>
      </tr>
      <tr>
        <td align="left">Frames:</td>
        <td id="lblFrames">&nbsp;</td>
        <td id="lblTFrames">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">Bits:</td>
        <td id="lblBits">&nbsp;</td>
        <td id="lblTBits">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">Errors:</td>
        <td id="lblErrors">&nbsp;</td>
        <td id="lblTErrors">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">BER:</td>
        <td id="lblBER">&nbsp;</td>
        <td id="lblTBER">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">Sampling Rate:</td>
        <td id="lblSec" style="padding:0;"><select name="sltUpdFrq" id="sltUpdFrq" style="margin:0;">
                          <option value="1" selected="selected">1 Sec.</option>
                          <option value="2">2 Secs.</option>
                          <option value="3">3 Secs.</option>
                          <option value="5">5 Secs.</option>
                          <option value="10">10 Secs.</option>
                          <option value="30">30 Secs.</option>
                        </select>
        </td>
        <td id="lblTSec">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>  

  </td></tr>
  <tr><td align="left">
        <div id="chart"></div>
<script type="text/javascript">
    Plotly.newPlot('chart', [{
        x: [0],
        y: [0],
        type: 'scatter',
        mode: 'lines',
        fill: 'tozeroy',
        line: {
            color: 'cyan'
        },
        fillcolor: 'rgba(0, 139, 139, 0.3)'
    }], {
        title: {
            text: 'Bit Error Rate (BER) in Percent',
            font: { color: 'white' }
        },
        xaxis: {
            title: 'Seconds',
            rangemode: 'tozero',
            tickfont: { color: 'white' },
            titlefont: { color: 'white' }
        },
        yaxis: {
            title: 'BER %',
            rangemode: 'tozero',
            range: [0, 5],
            tickfont: { color: 'white' },
            titlefont: { color: 'white' }
        },
        paper_bgcolor: 'black',
        plot_bgcolor: 'black',
    }, { staticPlot: true });
</script>

      </td></tr>
<?php if (isset($_GET['verbose'])) { ?>
  <tr><td align="left"><div id="tail"></div></td></tr>
<?php } ?>
  </table>
  </div>
            <div class="footer">
                <a href="https://wpsd.radio/">WPSD</a> &copy; <code>W0CHP</code> 2020-<?php echo date("Y"); ?><br />
            </div>
  </div>
  </div>
  </body>
  </html>

<?php
}
?>
