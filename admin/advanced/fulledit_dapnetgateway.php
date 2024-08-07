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

$editorname = 'DAPNetGateway';
$configfile = '/etc/dapnetgateway';
$tempfile = '/tmp/cVKu8oJJKWqe.tmp';
$servicenames = array('dapnetgateway.service');

require_once('fulledit_template.php');

?>
