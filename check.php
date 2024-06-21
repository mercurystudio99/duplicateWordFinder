<?php

require "db_connect.php";
require "functions.php";
require "colors.php";

$postData = $_POST['allData'];
$tableData = $_POST['tableData'];
$projectID = $_POST['projectID'];

echo json_encode(['duplicate' => '', 'data' => '']);
// Now save the changes in the DB as well..
updateTableData($conn, $tableData, $projectID);

die;