<?php
require "db_connect.php";

$tableID = $_POST['table_id'];
$tableName = $_POST['table_name'];
if(empty($tableID) || empty($tableName))
    return false;

$query = "UPDATE `project_tables` SET `name` = '$tableName' WHERE `id` = '$tableID'";
$updated = mysqli_query($conn, $query);
if($updated)
    return true;

return false;