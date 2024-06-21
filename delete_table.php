<?php

require "db_connect.php";
if(empty($_POST['table_id']))
    return false;

$tableID = $_POST['table_id'];
$query = "DELETE FROM `project_tables` WHERE `id` = '$tableID'";
$deleted = mysqli_query($conn, $query);
if($deleted)
    echo "$tableID has been deleted!";
else
    echo "Oops, something went wrong";