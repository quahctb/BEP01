<?php
//session_start();
//Database Server
if($_SERVER['SERVER_NAME'] == "localhost") {
	DEFINE("DB_SERVER_CASE", "localhost");
	DEFINE("PORT", "");
}
else {
	DEFINE("DB_SERVER_CASE", "192.168.1.173");
	DEFINE("PORT", "");

}

//DATABASE CONFIGGURATION: Product
DEFINE("DB_DATABASE_CASE", "case");	//Database name
DEFINE("DB_USER_CASE", "ricoh");		//user
DEFINE("DB_PASS_CASE", "12345");			//password

DEFINE("DB_DATABASE_DB9", "DB9");	//Database name
DEFINE("DB_USER_DB9", "ricoh");		//user
DEFINE("DB_PASS_DB9", "12345");			//password

DEFINE("CASE_TABLE_CUSTOMER", "customer");
DEFINE("CASE_TABLE_CASE", "projectcase");
DEFINE("CASE_TABLE_DEVICE", "device");
DEFINE("CASE_TABLE_FLOORPLAN", "floorplan");
DEFINE("CASE_TABLE_POSITION", "position");

DEFINE("DB9_TABLE_DEVICE", "device");
DEFINE("DB9_TABLE_CONSUMABLE", "consumable");
DEFINE("DB9_TABLE_DEVICECONSUMABLE", "deviceconsumable");
?>