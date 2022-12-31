<?php 
$host="localhost";
$user="root";
$passwd="";
$db="quiz_management_system";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
$mysqli = new mysqli($host, $user, $passwd, $db);
} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}

?>