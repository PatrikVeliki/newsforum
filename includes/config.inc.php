<?php
const TESTMODE = true;

const DB = [
    "host" => "localhost",
    "user" => "root",
    "pwd" => "",
    "name" => "db_newsforum",
    "errors" => ["dbConnect" => "errors/dbConnect.html"],
];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (TESTMODE) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
} else {
    error_reporting(0);
    ini_set("display_errors", 0);
}
?>