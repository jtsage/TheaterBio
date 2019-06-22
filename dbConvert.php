<?php

$MYSQL_SERVER = "mysql.jtsage.com";
$MYSQL_USER = "tdtrac";
$MYSQL_PASS = "tdtrac";
$MYSQL_DATABASE = "tdtrac";
$MYSQL_PREFIX = "hear_";

$DEFAULT_NEW_PASSWORD = "$2y$10$2cCza5MzZ77RCb2QVUname7.6tGusUCgNz7GetsosHwGpp4C047a6";

$ONLY_ACTIVE = true;

$mysqli = new mysqli($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DATABASE);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$users = array();

echo "-- TDTrac DataBase Updater v.4.0.0\n\n";
echo "/*\nDANGER DANGER DANGER DANGER\n";
echo "\nThis utility DOES NOT SET PASSWORDS correctly.  Best idea is not to overwrite your admin\n";
echo "user. (User no 1 (default behavior)).  Other passwords need to be hand updated. They are *probably*\n";
echo "set to \"password\" by default.\n*/\n\n";
echo "/*\nNOTE NOTE NOTE\n\n";
echo "This utility just outputs SQL.  It does not alter your new database in any way.\n*/ \n\n";



$res = $mysqli->query("SELECT * FROM {$MYSQL_PREFIX}users WHERE active = 1 AND userid > 1 ");

echo "\n\n-- USERS Table\n";
echo "\nINSERT INTO `users` ( `id`, `username`, `password`, `first`, `last`, `phone`, `is_active`, `is_password_expired`, `created_at` ) VALUES ";
$next = false;
while ( $row = $res->fetch_assoc()) {
	if ( $next ) { echo ","; } $next = true;
	echo "\n( {$row['userid']}, '{$row['email']}', '{$DEFAULT_NEW_PASSWORD}', '{$row['first']}', '{$row['last']}', {$row['phone']}, 1, 1, '{$row['since']}' )";
	$users[] = array($row['email'], $row['password']);
}
echo ";\n";

$res = $mysqli->query("SELECT * FROM {$MYSQL_PREFIX}shows WHERE closed = 0");
$today = date("Y-m-d");

echo "\n\n-- SHOWS Table\n";
echo "-- NOTE: End date will automatically be *TODAY*\n";
echo "\nINSERT INTO `shows` ( `id`, `name`, `location`, `is_active`, `end_date` ) VALUES ";
$next = false;
while ( $row = $res->fetch_assoc()) {
	if ( $next ) { echo ","; } $next = true;
	echo "\n( {$row['showid']}, '" . $mysqli->real_escape_string($row['showname']) . "', '" . $mysqli->real_escape_string($row['company']) . " @ " . $mysqli->real_escape_string($row['venue']) . "', 1, '{$today}' )";
}
echo ";\n";

$res = $mysqli->query("SELECT * FROM {$MYSQL_PREFIX}budget WHERE showid IN (SELECT showid FROM {$MYSQL_PREFIX}shows WHERE closed = 0)");

echo "\n\n-- BUDGETS Table\n";
echo "\nINSERT INTO `budgets` ( `vendor`, `category`, `date`, `description`, `price`, `show_id` ) VALUES ";
$next = false;
while ( $row = $res->fetch_assoc()) {
	if ( $next ) { echo ","; } $next = true;
	echo "\n( '{$row['vendor']}', '{$row['category']}', '{$row['date']}', '{$row['dscr']}', {$row['price']}, {$row['showid']} )";
}
echo ";\n";

$res = $mysqli->query("SELECT * FROM {$MYSQL_PREFIX}hours WHERE showid IN (SELECT showid FROM {$MYSQL_PREFIX}shows WHERE closed = 0) AND userid IN (SELECT userid FROM {$MYSQL_PREFIX}users WHERE active = 1)");

echo "\n\n-- PAYROLLS Table\n";
echo "\nINSERT INTO `payrolls` ( `date_worked`, `start_time`, `end_time`, `is_paid`, `notes`, `user_id`, `show_id` ) VALUES ";
$next = false;
while ( $row = $res->fetch_assoc()) {
	if ( $next ) { echo ","; } $next = true;
	$starttime = new DateTime("00:00:00");
	$workies = $row['worked'];

	if ( $workies > 24 ) { $workies = 23.99; }
	if ( floor($workies) != $workies ) { 
		$timeamount = new DateInterval("PT". floor($workies) . "H" . floor( 60 * ($workies - floor($workies))) . "M");
	} else {
		$timeamount = new DateInterval("PT".$workies."H");
	}

	$endtime = new DateTime("00:00:00");
	$endtime->add($timeamount);
	$noty = (is_null($row['note']) ? " " : $row['note']);
	$noty = $mysqli->real_escape_string($noty);

	echo "\n( '{$row['date']}', '" . $starttime->format("H:i:00") . "', '" . $endtime->format("H:i:00") . "', {$row['submitted']}, '{$noty}', {$row['userid']}, {$row['showid']} )";
}
echo ";\n";

echo "\n/*\nUser Password Fixes:\n\n";
foreach ( $users as $user ) {
	echo "./bin/cake tdtrac resetpass {$user[0]} {$user[1]}\n";
}
echo "*/\n";


?>
