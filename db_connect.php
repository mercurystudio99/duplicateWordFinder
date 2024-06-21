<?php

$conn = mysqli_connect("localhost","root","","digigtkv_duplicate_checker");



// Check connection

if (mysqli_connect_errno()) {

    echo "Failed to connect to MySQL: " . mysqli_connect_error();

    exit();

}



/*

 *

User: dupe_checker_user

Database: digidbdj_dupe_checker

Password: 6kJ7_5+VcF#i











Remaining things:

+ Integrate the dupe check basic version.

+ Add correct IDs to the newly added tables.

    + What happens when they are deleted ?

+ Fix the focus thing.

+ Write the words count functionality.

+ DB creation and connection.

+ Create DB Schema.

+ Create add project functionality.

+ Save the data into the database.

+ Create project switching functionality.

+ Get projectID value from select box.



+ Edit table names as well.

+ Add icons.

+ Save new table in DB when its added.

    + Add the new table ID in the table id attr.

    + Let user change name of the new table as well.

+ Delete new table from DB when delete is clicked.

+ Finally, fix the dupe check script.

    + Make the duplicate word bold as well.

    + Sometimes when space is pressed it removes the color of the dupe word.

    + Check what happens when a dupe word is saved and when we refresh the page.

    + Setting cursor at the end only works for id not data-id or data-id has to be unique or data-id should have id value

    + Don't mark prepositions, only words.









- Manage different color for dupe words.



*/

?>