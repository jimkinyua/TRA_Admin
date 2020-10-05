<?php 

define("UID", "server1\cosmas.ngeno");

define("PASWD", "Attain12345!!");

define("SERVICE_URL", "http://localhost/ReportServer");

define("REVENUEDB", "Revenue");

define("DS_UID", "ugrev");

define("DS_PWD", "ugrev");

$dataSourceCredentials = array();

$dataSourceCredentials[0] = new DataSourceCredentials();

$dataSourceCredentials[0]->DataSourceName = REVENUEDB;

$dataSourceCredentials[0]->UserName = DS_UID;

$dataSourceCredentials[0]->Password = DS_PWD;