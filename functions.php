<?php

function updateTableData($conn, $data, $projectID)
{
    if(empty($data))
        return true;

    foreach ($data as $id => $values) {
        $title = !empty($values['title']) ? $values['title'] : null;
        $subtitle = !empty($values['subtitle']) ? $values['subtitle'] : null;
        $keywords = !empty($values['keywords']) ? $values['keywords'] : null;

        if(!strpos($id, '-new'))
            $query = "UPDATE project_tables SET title = '$title', subtitle = '$subtitle', keywords = '$keywords' WHERE id = '$id'";
        else
            $query = "INSERT INTO project_tables (`project_id`, `name`, `title`, `subtitle`, `keywords`) VALUES ('$projectID', 'New Table','$title', '$subtitle', '$keywords')";

        mysqli_query($conn, $query);
    }
    return true;
}