<?php
require "db_connect.php";

if(empty($_POST['version_id']))
    return "Error!";

$versionID = $_POST['version_id'];

$query = "INSERT INTO project_tables (`version_id`, `name`, `title`, `subtitle`, `keywords`) VALUES ('$versionID', 'New Table',null, null, null)";
$result = mysqli_query($conn, $query);
$tableID = mysqli_insert_id($conn);
print_r(json_encode(['table_id' => $tableID]));