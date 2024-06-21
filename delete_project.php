<?php

require "db_connect.php";
if(empty($_POST['project_id']))
    return false;

$projectID = $_POST['project_id'];
$query = "DELETE FROM `projects` WHERE `id` = '$projectID'";
$deleted = mysqli_query($conn, $query);
if($deleted) {
    $versions = '';
    $sql = "SELECT * FROM versions WHERE project_id = $projectID";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $versions .= $row['id'] . ',';
    }
    if(!empty($versions)) {
        $versions = substr($versions, 0, strlen($versions) - 1);
        $query = "DELETE FROM `versions` WHERE `project_id` = '$projectID'";
        $deleted = mysqli_query($conn, $query);
        if($deleted) {
            $query = "DELETE FROM `project_tables` WHERE `version_id` IN ($versions)";
            $deleted = mysqli_query($conn, $query);
            echo json_encode(['message' => "Project, Version and tables deleted"]);die;
        }
    } else {
        echo json_encode(['message' => "Project, Version and tables deleted"]);die;
    }
}
echo json_encode(['message' => "Something went wrong!"]);die;
