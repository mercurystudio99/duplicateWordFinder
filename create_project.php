<?php

require "db_connect.php";

$data = $_POST;

if(empty($data['project_name']))
    echo "Oops, project name is required!";

$projectName = $data['project_name'];
$sql = "INSERT INTO projects (`name`) VALUES ('$projectName')";
$result = mysqli_query($conn, $sql);
header("Location: index.php");