<?php

require "db_connect.php";

$data = $_POST;

if(empty($data['project_version']))
    echo "Oops, project version is required!";
if(empty($data['project_id']))
    echo "Oops, project ID is required!";

$versionName = $data['project_version'];
$projectId = $data['project_id'];
$sql = "INSERT INTO versions (`name`, `project_id`) VALUES ('$versionName', $projectId)";
$result = mysqli_query($conn, $sql);
if($result) {
    $versionID = mysqli_insert_id($conn);
    // Need to create 3 new tables for it as well
    $insert = "INSERT INTO project_tables (`version_id`, `name`, `title`, `subtitle`, `keywords`)
        VALUES 
        ('$versionID', 'Table 1',null, null, null),
        ('$versionID', 'Table 2',null, null, null),
        ('$versionID', 'Table 3',null, null, null)
    ";
    mysqli_query($conn, $insert);
}
header("Location: index.php");