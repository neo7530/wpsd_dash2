<?php
include_once('css-base.php');
?>

.container {
    width: 100%;
    text-align: left;
    margin: auto;
    background : <?php echo $backgroundContent; ?>;
}

body, font {
    font: <?php echo $bodyFontSize; ?>px 'Source Sans Pro', sans-serif;
    color: #ffffff;
    -webkit-text-size-adjust: none;
    -moz-text-size-adjust: none;
    -ms-text-size-adjust: none;
    text-size-adjust: none;
}

.center {
    text-align: center !important;
}

.middle {
    vertical-align: middle;
}

.header {
    background : <?php echo $backgroundBanners; ?>;
    text-decoration : none;
    color : <?php echo $textBanners; ?>;
    font-family : 'Source Sans Pro', sans-serif;
    text-align : left;
    padding : 5px 0 0 0;
    margin: 0 10px;
}

.header h1 {
    margin-top:-10px;
    font-size: <?php echo $headerFontSize; ?>px;
}

.headerClock {
    font-size: 0.9em;
    text-align: left;
    padding-left: 8px;
    padding-top: 5px;
    float: left;
}

.nav {
    float: left;
    margin : -12px 0 0 0;
    padding : 0 3px 3px 10px;
    width : 230px;
    background : <?php echo $backgroundNavPanel; ?>;
    font-weight : normal;
    min-height : 100%;
}

.content {
    margin : 0 0 0 250px;
    padding : 0 10px 5px 3px;
    color : <?php echo $textSections; ?>;
    background : <?php echo $backgroundContent; ?>;
    text-align: center;
}

.contentwide {
    padding: 10px;
    color: <?php echo $textSections; ?>;
    background: <?php echo $backgroundContent; ?>;
    text-align: center;
    margin: 5px 0 10px;
}

.contentwide h2 {
    color: <?php echo $textSections; ?>;
    font: 1em 'Source Sans Pro', sans-serif;
    text-align: center;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}

.divTableCellSans h2 {
    color: <?php echo $textContent; ?>;
}

.divTableCellMono {
    font: 1.3em 'Inconsolata', monospace !important;
}

td.divTableCellMono a:hover {
    text-decoration: underline !important;
}

h2.ConfSec {
    font-size: 1.6em;
    text-align: left;
    padding-bottom: 1rem;
}

.left {
    text-align: left;
}

.footer {
    background : <?php echo $backgroundBanners; ?>;
    text-decoration : none;
    color : <?php echo $textBanners; ?>;
    font-family : 'Source Sans Pro', sans-serif;
    font-size : .9rem;
    text-align : center;
    padding : 10px 0 10px 0;
    clear : both;
    margin: 10px;
}

.footer a {
    text-decoration: underline !important;
    color : <?php echo $textBanners; ?> !important;
}

tt, code, kbd, pre {
        font-family: 'Inconsolata', monospace !important;
}

.mono {
    font: <?php echo $mainFontSize; ?>px 'Inconsolata', monospace !important;
}

.SmallHeader {
    font-family: 'Inconsolata', monospace !important;
    font-size: 12px; 
}
.shRight {
    text-align: right;
    padding-right: 8px;
}
.shLeft {
    text-align: left;
    padding-left: 8px;
    float: left;
}

#tail {
    font-family: 'Inconsolata', monospace;
    height: 640px;
    overflow-y: scroll;
    overflow-x: scroll;
    color: #4DEEEA;
    background: #000000;
    font-size: 18px;
    padding: 1em;
}

table {
    vertical-align: middle;
    text-align: center;
    empty-cells: show;
    padding: 0px;
    border-collapse:collapse;
    border-spacing: 5px;
    border: .5px solid <?php echo $tableBorderColor; ?>;
    text-decoration: none;
    background: #000000;
    font-family: 'Source Sans Pro', sans-serif;
    width: 100%;
    white-space: nowrap;
}

table th {
    font-family:  'Source Sans Pro', sans-serif;
    border: .5px solid <?php echo $tableBorderColor; ?>;
    font-weight: 600;
    text-decoration: none;
    color : <?php echo $textBanners; ?>;
    background: <?php echo $backgroundBanners; ?>;
    padding: 5px;
}

table tr:nth-child(even) {
    background: <?php echo $tableRowEvenBg; ?>;
}

table tr:nth-child(odd) {
    background: <?php echo $tableRowOddBg; ?>;
}

table td {
    color: <?php echo $textContent; ?>;
    text-decoration: none;
    border: .5px solid <?php echo $tableBorderColor; ?>;
    padding: 5px;
    font-size: <?php echo "$mainFontSize"; ?>px;
}

#ccsConns table td, #activeLinks table td, #starNetGrps table td, #infotable td, table.poc-lh-table td {
    color: <?php echo $textContent; ?>;
    font-family: 'Inconsolata', monospace;
    font-weight: 500;
    text-decoration: none;
    border: .5px solid <?php echo $tableBorderColor; ?>;
    padding: 5px;
    font-size: <?php echo "$mainFontSize"; ?>px;
}

#liveCallerDeets table tr:hover td, #localTxs table tr:hover td, #lastHeard table tr:hover td, #bmLinks table tr:hover td,
#liveCallerDeets table tr:hover td a, #localTxs table tr:hover td a, #lastHeard table tr:hover td a, #bmLinks table tr:hover td a {
     background-color: <?php echo $backgroundDropdownHover; ?>; 
     color: <?php echo $textDropdownHover; ?>; 
}

.divTable{
    font-family:  'Source Sans Pro', sans-serif;
    display: table;
    border-collapse: collapse;
    width: 100%;
}

.divTableRow {
    display: table-row;
    width: auto;
    clear: both;
}

.divTableHead, .divTableHeadCell {
    color : <?php echo $textBanners; ?>;
    background: <?php echo $backgroundBanners; ?>;
    border: .5px solid <?php echo $tableBorderColor; ?>;
    font-weight: 600;
    text-decoration: none;
    padding: 5px;
    caption-side: top;
    display: table-caption; 
    text-align: center;
    vertical-align: middle;
}

.divTableCellSans {
    font-size: <?php echo "$bodyFontSize"; ?>px;
    color: <?php echo $textContent; ?>;
}

.divTableCell {
    font-size: <?php echo "$bodyFontSize"; ?>px;
    border: .5px solid <?php echo $tableBorderColor; ?>;
    color: <?php echo $textContent; ?>;
}

.divTableCell, .divTableHeadCell {
    display: table-cell;
}

.divTableBody {
    display: table-row-group;
}

.divTableBody .divTableRow {
    background: <?php echo $tableRowEvenBg; ?>;
}

.divTableCell.cell_content {
    padding: 5px;
}

body {
    background: <?php echo $backgroundPage; ?>;
    color: <?php echo $textContent; ?>;
}

a {
    text-decoration:none;
    
}

a:link, a:visited {
    text-decoration: none;
    color: <?php echo $textLinks; ?>
}

a.tooltip, a.tooltip:link, a.tooltip:visited, a.tooltip:active  {
    text-decoration: none;
    position: relative;
    color: <?php echo $textBanners; ?>;
}

a.tooltip:hover {
    text-decoration: none;
    background: transparent;
    color: <?php echo $textBanners; ?>;
}

a.tooltip span {
    text-decoration: none;
    display: none;
    font-size: <?php echo "$bodyFontSize"; ?>px;
    font-family:  'Source Sans Pro', sans-serif;
}

a.tooltip:hover span {
    font-size: <?php echo "$bodyFontSize"; ?>px;
    font-family:  'Source Sans Pro', sans-serif;
    text-decoration: none;
    display: block;
    position: absolute;
    top: 20px;
    left: 0;
    z-index: 100;
    text-align: left;
    white-space: nowrap;
    border: none;
    color: #e9e9e9;
    background: rgba(0, 0, 0, .9);
    padding: 8px;
}

th:last-child a.tooltip:hover span {
    left: auto;
    right: 0;
}

a.tooltip span b {
    text-decoration: none;
    display: block;
    margin: 0;
    font-weight: bold;
    border: none;
    color: #e9e9e9;
    padding: 0px;
}

a.tooltip2, a.tooltip2:link, a.tooltip2:visited, a.tooltip2:active  {
    text-decoration: none;
    position: relative;
    font-weight: bold;
    color: <?php echo $textContent; ?>;
}

a.tooltip2:hover {
    text-decoration: none;
    background: transparent;
    color: <?php echo $textContent; ?>;
}

a.tooltip2 span {
    text-decoration: none;
    display: none;
}

a.tooltip2:hover span {
    text-decoration: none;
    display: block;
    position: absolute;
    top: 20px;
    left: 0;
    width: 202px;
    z-index: 100;
    font: 16px 'Source Sans Pro', sans-serif; 
    text-align: left;
    white-space: normal;
    border: none;
    color: #e9e9e9;
    background: rgba(0, 0, 0, .9);
    padding: 8px;
}

a.tooltip2 span b {
    text-decoration: none;
    font: 16px 'Source Sans Pro', sans-serif;
    display: block;
    margin: 0;
    font-weight: bold;
    border: none;
    color: #e9e9e9;
    padding: 0px;
}

ul {
    padding: 5px;
    margin: 10px 0;
    list-style: none;
    float: left;
}

ul li {
    float: left;
    display: inline; /*For ignore double margin in IE6*/
    margin: 0 10px;
}

ul li a {
    text-decoration: none;
    float:left;
    color: #999;
    cursor: pointer;
    font: 600 14px/22px 'Source Sans Pro', sans-serif;
}

ul li a span {
    margin: 0 10px 0 -10px;
    padding: 1px 8px 5px 18px;
    position: relative; /*To fix IE6 problem (not displaying)*/
    float:left;
}

ul.mmenu li a.current, ul.mmenu li a:hover {
    color: #0d5f83;
}

ul.mmenu li a.current span, ul.mmenu li a:hover span {
    color: #0d5f83;
}

h1 {
    text-align: center;
    font-weight: 600;
}

/* CSS Toggle Code here */
.toggle {
    position: absolute;
    margin-left: -9999px;
    z-index: 0;
}

.toggle + label {
    display: block;
    position: relative;
    cursor: pointer;
    outline: none;
}

input.toggle-round-flat + label {
    padding: 1px;
    margin: 3px;
    width: 33px;
    height: 20px;
    background-color: #5C5C5C;
    border-radius: 5px;
    transition: background 0.4s;
}

input.toggle-round-flat + label:before,
input.toggle-round-flat + label:after {
    display: block;
    position: absolute;
    content: "";
}

input.toggle-round-flat + label:before {
    top: 1px;
    left: 1px;
    bottom: 1px;
    right: 1px;
    background: <?php echo $backgroundContent; ?>;
    border-radius: 5px;
    transition: background 0.4s;
}

input.toggle-round-flat + label:after {
    top: 2px;
    left: 2px;
    bottom: 2px;
    width: 16px;
    background: #999;
    border-radius: 5px;
    transition: margin 0.4s, background 0.4s;
}

input.toggle-round-flat:checked + label {
    background: #5C5C5C;
}

input.toggle-round-flat:checked + label:after {
    margin-left: 14px;
    background: <?php echo $backgroundServiceCellActiveColor; ?>;;
}

input.toggle-round-flat:focus + label {
    box-shadow: 0 0 1px <?php echo $backgroundServiceCellActiveColor; ?>;;
    padding: 1px;
    z-index: 5;
}

.mode_flex .row {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  width: 100%;
}

.mode_flex .column {
  display: flex;
  flex-direction: column;
  flex-basis: 100%;
  flex: 1;
}

.mode_flex button {
    background: <?php echo $backgroundNavbar ?>;
    color: <?php echo $textNavbar ?>;
    flex-basis: 25%;
    flex-shrink: 0;
    text-align: center;
    justify-content: center;
    flex-grow: 1;
    font-family: 'Source Sans Pro', sans-serif;
    border: 2px solid <?php echo $tableBorderColor; ?>;
    padding: 3px;
}

.mode_flex button > span  {
    align-items: center; 
    flex-wrap: wrap;
    display: flex; 
    justify-content: center;
    margin: 5px;
    text-align: center;
}

textarea, input[type='text'], input[type='password'] {
        font-size: <?php echo $bodyFontSize; ?>px;
        font-family: 'Inconsolata', monospace;
        border: 1px solid <?php echo $tableBorderColor; ?>;
        padding: 5px;
        margin 3px;
        background: #e2e2e2;
}

textarea.fulledit {
    display: inline-block;
    margin: 0;
    padding: .2em;
    width: auto;
    min-width: 70%;
    max-width: 100%;
    height: auto;
    min-height: 600px;
    cursor: text;
    overflow: auto;
    resize: both;
}

input[type=button], input[type=submit], input[type=reset], input[type=radio], button {
    font-size: <?php echo $bodyFontSize; ?>px;
    font-family: 'Source Sans Pro', sans-serif;
    border: 1px solid <?php echo $tableBorderColor; ?>;
    padding: 5px;
    text-decoration: none;
    margin: 3px;
    cursor: pointer;
    background: <?php echo $backgroundNavbar ?>;
    color: <?php echo $textNavbar ?>;
}

input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover, button:hover {
    color: <?php echo $textNavbarHover; ?>;
    background-color: <?php echo $backgroundNavbarHover; ?>;
}

input:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

button:disabled {
    cursor: not-allowed;
    color: <?php echo $textModeCellDisabledColor; ?>;
    background: <?php echo $backgroundModeCellDisabledColor; ?>;
}

input:disabled + label {
    color: #000;
    opacity: 0.6;
    cursor: not-allowed;
}

select {
    background: #e2e2e2;
    font-family: 'Inconsolata', monospace;
    font-size: <?php echo $bodyFontSize; ?>px;
    border: 1px solid <?php echo $tableBorderColor; ?>;
    color: black;
    padding: 5px;
    text-decoration: none;
}

.select2-selection__rendered {
  font-family: 'Inconsolata', monospace;
  color: black !important;
  font-size: <?php echo $bodyFontSize; ?>px !important;
  background: #e2e2e2;
}

.select2-results__options{
  color: black;
  font-size:<?php echo $bodyFontSize; ?>px !important;
  font-family: 'Inconsolata', monospace;
  background: #e2e2e2;
}

[class^='select2'] {
  border-radius: 0px !important;
}

.select2-results__option {
  color: black !important;
}

.navbar {
    overflow: hidden;
    background-color: <?php echo $backgroundNavbar; ?>;
    padding: 10px 10px 10px  2px;
}

.navbar a {
    float: right;
    font-family : 'Source Sans Pro', sans-serif;
    font-size: <?php echo $bodyFontSize; ?>px;
    color: <?php echo $textNavbar; ?>;
    text-align: center;
    padding: 5px 8px;
    text-decoration: none;
}

.dropdown .dropbutton {
    font-size: <?php echo $bodyFontSize; ?>px;
    border: none;
    outline: none;
    color: <?php echo $textNavbar; ?>;
    padding: 5px 8px;
    background-color: <?php echo $backgroundNavbar; ?>;
    font-family: inherit;
    margin: 0;
}

.navbar a:hover, .dropdown:hover .dropbutton {
    color: <?php echo $textNavbarHover; ?>;
    background-color: <?php echo $backgroundNavbarHover; ?>;
}

.lnavbar {
    overflow: hidden;
    background-color: <?php echo $backgroundNavbar; ?>;
    padding-bottom: 10px;
    margin-top: -0.6rem;
}

/* Advanced menus */
.mainnav {
    display: inline-block;
    list-style: none;
    padding: 0;
    margin: 0 auto;
    width: 100%;
    background: <?php echo $backgroundNavbar; ?>;
    overflow: hidden;
}

.dropdown {
    position: absolute;
    top: 134px;
    width: 270px;
    opacity: 0;
    visibility: hidden;
}

.mainnav ul {
    padding: 0;
    list-style: none;
}

.mainnav li {
    display: block;
    float: left;
    font-size: 0;
    margin: 0;
    background: <?php echo $backgroundNavbar; ?>;
}

.mainnav li a {
    list-style: none;
    padding: 0;
    display: inline-block;
    padding: 1px 10px;
    font-family : 'Source Sans Pro', sans-serif;
    font-size: <?php echo $bodyFontSize; ?>px;
    color: <?php echo $textNavbar; ?>;
    text-align: center;
    text-decoration: none;
}

.mainnav .has-subs a:after {
    content: "\f0d7";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-left: 1em;
}

.mainnav .has-subs .dropdown .subs a:after {
    content: "";
}

.mainnav li:hover {
    background: <?php echo $backgroundNavbarHover; ?>;
}

.mainnav li:hover a {
    color: <?php echo $textNavbarHover; ?>;
    background-color: <?php echo $backgroundNavbarHover; ?>;
}

/* First Level */
.subs {
    position: relative;
    width: 270px;
}

.has-subs:hover .dropdown,
.has-subs .has-subs:hover .dropdown {
    opacity: 1;
    visibility: visible;
}

.mainnav ul li,
.mainav ul li ul li  a {
    color: <?php echo $textDropdown; ?>;
    background-color: <?php echo $backgroundDropdown; ?>;
}

.mainnav li:hover ul a,
.mainnav li:hover ul li ul li a {
    color: <?php echo $textDropdown; ?>;
    background-color: <?php echo $backgroundDropdown; ?>;
}

.mainnav li ul li:hover,
.mainnav li ul li ul li:hover {
    background-color: <?php echo $backgroundDropdownHover; ?>;
}

.mainnav li ul li:hover a,
.mainnav li ul li ul li:hover a {
    color: <?php echo $textDropdownHover; ?>;
    background-color: <?php echo $backgroundDropdownHover; ?>;
}

.mainnav .has-subs .dropdown .has-subs a:after {
    content: "\f0da";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    position: absolute;
    top: 1px;
    right: 9px;
}

/* Second Level */
.has-subs .has-subs .dropdown .subs {
    position: relative;
    top: -144px;
    width: 270px;
    border-style: none none none solid;
    border-width: 1px;
    border-color: <?php echo $backgroundDropdownHover; ?>;
}

.has-subs .has-subs .dropdown .subs a:after {
    content:"";
}

.has-subs .has-subs .dropdown {
    position: absolute;
    width: 270px;
    left: 270px;
    opacity: 0;
    visibility: hidden;
}

.menuhwinfo, .menuprofile, .menuconfig, .menuadmin, .menudashboard, .menusimple,
.menucaller, .menulive, .menuupdate, .menupower, .menulogs,
.menubackup, .menuadvanced, .menureset, .menusysinfo, .menuradioinfo,
.menuappearance {
    position: relative;
}

.menuprofile:before {
    content: "\f0c0";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menuappearance:before {
    content: "\f1fc";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menucastmemory:before {
    content: "\f0cb";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menuradioinfo:before {
    content: "\f012";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menuconfig:before {
    content: "\f1de";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menuadmin:before {
    content: "\f023";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menuupdate:before {
    content: "\f0ed";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menupower:before {
    content: "\f011";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menulogs:before {
    content: "\f06e";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menudashboard:before {
    content: "\f0e4";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menusimple:before {
    content: "\f0ce";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menulive:before {
    content: "\f2a0";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menucaller:before {
    content: "\f098";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.grid-item.filter-activity:before {
    content: "\f131";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

tr.good-activity.even {
  background: <?php echo $tableRowEvenBg; ?>;
}
tr.good-activity.odd {
  background: <?php echo $tableRowOddBg; ?>;
}

input.filter-activity-max {
  background-color: <?php echo $tableRowEvenBg; ?>;
  color: <?php echo $textContent; ?>;
  border: 2px solid <?php echo $backgroundContent; ?>;
  border-radius: 5px;
  height: 19px;
}

.filter-activity-max-wrap {
  display: inline-block;
  position: relative;
  top: -3px;
}

.menutgnames:before {
    content: "\f0e6";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menuhwinfo:before {
    content: "\f03a";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menubackup:before {
    content: "\f187";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menuadvanced:before {
    content: "\f013";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menureset:before {
    content: "\f1cd";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.menusysinfo:before {
    content: "\f080";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    padding-right: 0.2em;
}

.disabled-service-cell {
    color: <?php echo $textModeCellDisabledColor; ?>;
    background: <?php echo $backgroundModeCellDisabledColor; ?>;
}

.active-service-cell {
    color: <?php echo $textServiceCellActiveColor; ?>;
    background: <?php echo $backgroundServiceCellActiveColor; ?>;
}

.inactive-service-cell {
    color: <?php echo $textServiceCellInactiveColor; ?>;
    background: <?php echo $backgroundServiceCellInactiveColor; ?>;
}

.disabled-mode-cell {
    color: <?php echo $textModeCellDisabledColor; ?>;
    padding:2px;
    text-align: center;
    border:0;
    background: <?php echo $backgroundModeCellDisabledColor; ?>;
}

.active-mode-cell {
    color: <?php echo $textModeCellActiveColor; ?>;
    border:0;
    text-align: center;
    padding:2px;
    background: <?php echo $backgroundModeCellActiveColor; ?>;
}

.inactive-mode-cell {
    color: <?php echo $textModeCellInactiveColor; ?>;
    border:0;
    text-align: center;
    padding:2px;
    background: <?php echo $backgroundModeCellInactiveColor; ?>;
}

.paused-mode-cell {
    color: <?php echo $textModeCellActiveColor; ?>;
    border:0;
    text-align: center;
    padding:2px;
    background: <?php echo $backgroundModeCellPausedColor; ?>;
}

.paused-mode-span {
    background: <?php echo $backgroundModeCellPausedColor; ?>;
}

.error-state-cell {
    color: <?php echo $textModeCellInactiveColor; ?>;
    text-align: center;
    border:0;
    background: <?php echo $backgroundModeCellInactiveColor; ?>;
}

.table-container {
    position: relative;
}

.config_head {
    font-size: 1.5em;
    font-weight: normal;
    text-align: left;
}

/* Tame Firefox Buttons */
@-moz-document url-prefix() {
    select,
    input {
        margin : 0;
        padding : 0;
        border-width : 1px;
        font : 14px 'Inconsolata', monospace;
    }
    input[type="button"], button, input[type="submit"] {
        padding : 0px 3px 0px 3px;
        border-radius : 3px 3px 3px 3px;
        -moz-border-radius : 3px 3px 3px 3px;
    }
}

hr {
  display: block;
  height: 1px;
  border: 0;
  border-top: 1px solid <?php echo $tableBorderColor; ?>;
  margin: 1em 0;
  padding: 0; 
}

.status-grid {
  display: grid;
  grid-template-columns: auto auto auto auto auto auto;
  grid-template-rows: auto auto auto auto auto;
  margin:0;
  padding:0;
}


.status-grid .grid-item {
  padding: 1px;
  border: .5px solid <?php echo $tableBorderColor; ?>;
  text-align: center;
}

@-webkit-keyframes Pulse {
  from {
    opacity: 0;
  }

  50% {
    opacity: 1;
  }

  to {
    opacity: 0;
  }
}

@keyframes Pulse {
  from {
    opacity: 0;
  }

  50% {
    opacity: 1;
  }

  to {
    opacity: 0;
  }
}

td.lookatme {
  display: table-cell;
}

a.lookatme {
  color: steelblue;
  opacity: 1;
  position: relative;
  display: inline-block;
  font-weight:bold;
  font-size:10px;
  padding:1px;
  margin: 0 0 0 1px;
}

/* this pseudo element will be faded in and out in front /*
/* of the lookatme element to create an efficient animation. */
.lookatme:after {
  color: white;
  text-shadow: 0 0 5px #e33100;
  /* in the html, the lookatme-text attribute must */
  /* contain the same text as the .lookatme element */
  content: attr(lookatme-text);
  padding: inherit;
  position: absolute;
  inset: 0 0 0 0;
  z-index: 1;
  /* 20 steps / 2 seconds = 10fps */
  -webkit-animation: 2s infinite Pulse steps(20);
  animation: 2s infinite Pulse steps(20);
}

#hwInfoTable {
  margin-top: -2px;
}

/* indicators */

.red_dot {
    height: 15px;
    width: 15px;
    background-color: red;
    border-radius: 50%;
    display: inline-block;
}

.green_dot {
    height: 15px;
    width: 15px;
    background-color: limegreen;
    border-radius: 50%;
    display: inline-block;
}

/* RSSI meters */
meter {
  --background: #999;
  --optimum: limegreen;
  --sub-optimum: orange;
  --sub-sub-optimum: crimson;
  border-radius: 3px;
}

/* The gray background in Chrome, etc. */
meter::-webkit-meter-bar {
  background: var(--background);
  border-radius: 3px;
  height: 10px;
}

/* The green (optimum) bar in Firefox */
meter:-moz-meter-optimum::-moz-meter-bar {
  background: var(--optimum);
}

/* The green (optimum) bar in Chrome etc. */
meter::-webkit-meter-optimum-value {
  background: var(--optimum);
}

/* The yellow (sub-optimum) bar in Firefox */
meter:-moz-meter-sub-optimum::-moz-meter-bar {
  background: var(--sub-optimum);
}

/* The yellow (sub-optimum) bar in Chrome etc. */
meter::-webkit-meter-suboptimum-value {
  background: var(--sub-optimum);
}

/* The red (even less good) bar in Firefox */
meter:-moz-meter-sub-sub-optimum::-moz-meter-bar {
  background: var(--sub-sub-optimum);
}

/* The red (even less good) bar in Chrome etc. */
meter::-webkit-meter-even-less-good-value {
  background: var(--sub-sub-optimum);
}

.aprs-preview-container {
    display: flex;
    align-items: center;
    text-align: center;
    margin-top: 10px;
    margin-bottom: 10px;
}

.aprs-preview-text {
    margin: 0 10px 0 5px;
}

.aprs-symbol-preview {
    /* add'l/ any futureg styles for the symbol preview? */
}

/* Spinner animation for config pagei */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top: 4px solid #666666;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  animation: spin 1s linear infinite;
  display: inline-block;
  margin-left: 8px;
}

/* Config page unsaved changes alert stuff */
#unsavedChanges {
  display: none;
  position: fixed;
  top: 20px; /* Add top margin */
  left: 50%;
  transform: translateX(-50%); /* Center the div horizontally */
  width: calc(100% - 40px);
  height: 80px;
  overflow: hidden;
  background-color: #000;
  color: #fff;
  padding: 34px 10px 0px 10px;
  text-align: center;
  z-index: 1000;
  font-size: 1.4rem;
  border: 1px solid #fff;
  border-radius: 10px;
  max-width: 95%;
}

#applyButton {
  background-color: #37803A;
  border: 2px solid #73A675;
  margin-left: 10px;
  color: #fff;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
  transition: color 0.3s;
  font-weight: bold;
}

#applyButton:hover {
  background-color: #4caf50;
  border: 2px solid #37803A;
  color: black;
}

#revertButton {
  background-color: #e65100;
  border: 2px solid #ffab40;
  margin-left: 10px;
  color: #fff;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
  transition: color 0.3s;
  font-weight: bold;
}

#revertButton:hover {
  background-color: #ff9800;
  border: 2px solid #e65100;
  color: black;
}

.smaller {
    font-size: smaller;
}

.larger {
    font-size: larger;
}
