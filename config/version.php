<?php

// Enhanced version status; W0CHP

// format = YYYYMMDDnn
$configUpdateRequired = 2023010400;

$gitBranch = exec("git --work-tree=/var/www/dashboard --git-dir=/var/www/dashboard/.git branch | grep '*' | cut -f2 -d ' '");
$versionCmd = exec("git --work-tree=/var/www/dashboard --git-dir=/var/www/dashboard/.git rev-parse --short=10 $gitBranch");
$verNo = "<span title='WPSD Version # $versionCmd'>WPSD Ver.# $versionCmd</span>";

if ($gitBranch !== "master") {
    $version = "$verNo / Branch: $gitBranch";
} else {
    $version = "$verNo";
}

?>
