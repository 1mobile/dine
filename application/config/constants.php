<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

#DEFINE TERMINAL DETAILS
define('TERMINAL_ID',			'1');
define('PC_ID',					'1');
define('ORDERING_STATION',		false);

#DEFINE BRANCH DETAILS
// define('BRANCH_CODE',			'POINTONE0001');
// MALL 
define('ROBINSON',				false);
define('MALL_ENABLED',			true);
// define('MALL',					'robinsons');
// define('MALL',					'ortigas');
define('MALL',					'araneta');


define('BEVERAGE_ID'			,	'2');

define('SERVICE_CHARGE_ID'		,	'1');


define('BRANCH_CODE',			'POINTONE0001');
define('TENANT_CODE',			'50018284');
define('TERMINAL_NUMBER',			'01');
define('GC',						'8');

define('FREE_SALE',			    '99');
define('SALES_TRANS',			'10');
define('SALES_VOID_TRANS',		'11');
define('RECEIVE_TRANS',			'20');
define('ADJUSTMENT_TRANS',		'30');

#DEFINE BASE_TAX
define('BASE_TAX',0.12); // Please use base tax form (eg. 0.12) not num form (12)

#DEFINE SALES TYPES
define('SALE_TYPES',serialize(array('DINEIN','COUNTER','DELIVERY','PICKUP','TAKEOUT','DRIVETHRU','RETAIL')));

#DEFINE READ TYPE
define('X_READ',1);
define('Z_READ',2);

#DEFINE TIME RANGES
$time = array();
$time[] = array("FTIME"=>"0:00","TTIME"=>"1:00");
$time[] = array("FTIME"=>"1:00","TTIME"=>"2:00");
$time[] = array("FTIME"=>"2:00","TTIME"=>"3:00");
$time[] = array("FTIME"=>"3:00","TTIME"=>"4:00");
$time[] = array("FTIME"=>"4:00","TTIME"=>"5:00");
$time[] = array("FTIME"=>"5:00","TTIME"=>"6:00");
$time[] = array("FTIME"=>"6:00","TTIME"=>"7:00");
$time[] = array("FTIME"=>"7:00","TTIME"=>"8:00");
$time[] = array("FTIME"=>"8:00","TTIME"=>"9:00");
$time[] = array("FTIME"=>"9:00","TTIME"=>"10:00");
$time[] = array("FTIME"=>"10:00","TTIME"=>"11:00");
$time[] = array("FTIME"=>"11:00","TTIME"=>"12:00");
$time[] = array("FTIME"=>"12:00","TTIME"=>"13:00");
$time[] = array("FTIME"=>"13:00","TTIME"=>"14:00");
$time[] = array("FTIME"=>"14:00","TTIME"=>"15:00");
$time[] = array("FTIME"=>"15:00","TTIME"=>"16:00");
$time[] = array("FTIME"=>"16:00","TTIME"=>"17:00");
$time[] = array("FTIME"=>"17:00","TTIME"=>"18:00");
$time[] = array("FTIME"=>"18:00","TTIME"=>"19:00");
$time[] = array("FTIME"=>"19:00","TTIME"=>"20:00");
$time[] = array("FTIME"=>"20:00","TTIME"=>"21:00");
$time[] = array("FTIME"=>"21:00","TTIME"=>"22:00");
$time[] = array("FTIME"=>"22:00","TTIME"=>"23:00");
$time[] = array("FTIME"=>"23:00","TTIME"=>"0:00");
define('TIMERANGES', serialize($time));

/* End of file constants.php */
/* Location: ./application/config/constants.php */