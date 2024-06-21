<?php

require "db_connect.php";
require "functions.php";
require "colors.php";

$postData = $_POST['allData'];
$tableData = $_POST['tableData'];
$projectID = $_POST['projectID'];
//echo "<pre>";print_r($postData);echo "</pre>";

$allWords = $newData = $usedColors = $wordColor = [];
$duplicateFound = false;


// First I loop through each input one by one
// In current loop, I explode the text ro words array
// Then I loop through each of word of the current input
// and check if it has already added in allWords array or not
// if its not already there then I add it and move to the next word
// now if the word is already there in allWords then it means that it is a duplicate

foreach ($postData as $inputIndex => $inputValue) {
    // echo "index is $inputIndex and value is $inputValue";
    if(empty($inputValue))
        continue;

    //echo "Before Whole input text is: '$inputValue' <br>";
    $inputValue = mb_ereg_replace('[\s\p{Zl}\p{Zp}]+$', '', $inputValue);
    //echo "Whole input text is: '$inputValue' <br>";

    // $words = explode(' ', $inputValue);
    $words = preg_split('/(\s|,)/', $inputValue);

    //echo "<pre>";print_r($words);
    //echo "<pre>";print_r($chunks);
    foreach ($words as $word) {

        if(isPreposition($word))
            continue;

        //echo "Checking word '$word' in allWords <br>";
        if(!in_array($word, $allWords)) { // First occurrence of the word
            $allWords[] = $word;
            continue;
        }

        //echo "Checking word '$word' <br>";
        // Check in which index does is it repeated in
        foreach ($postData as $index => &$text) {

            if(!empty($wordColor[$word]))
                $color = $wordColor[$word];
            else{
                $color = getDuplicateWordColor($allColors, $usedColors);
                $wordColor[$word] = $color;
                $usedColors[] = $color;
            }

            // echo "setting color $color for $word <br>";
            $coloredWord = "<span style='font-weight: bold;color: $color'>$word</span>";
            // $postData[$index] = str_replace($word, $coloredWord, $text);
            $text = str_replace($word, $coloredWord, $text);
            $duplicateFound = true;
            //print_r($newData)
        }
        unset($text);
        /*if(!isset($allWords[$word]))
            $allWords[$word] = 1;
        else
            $allWords[$word] = $allWords[$word] + 1;*/
    }
}
// unset($alreadyAssignedColor);
//echo "<pre>";print_r($postData);
echo json_encode(['duplicate' => $duplicateFound, 'data' => $postData]);

// Now save the changes in the DB as well..
updateTableData($conn, $tableData, $projectID);

function isPreposition($word) {
    $prepositions = array('about', 'above', 'across', 'after', 'against', 'along', 'among', 'around', 'as', 'at', 'before', 'behind', 'below', 'beneath', 'beside', 'between', 'beyond', 'but', 'by', 'concerning', 'despite', 'down', 'during', 'except', 'for', 'from', 'in', 'inside', 'into', 'like', 'near', 'of', 'off', 'on', 'onto', 'out', 'outside', 'over', 'past', 'regarding', 'round', 'since', 'through', 'throughout', 'till', 'to', 'toward', 'under', 'underneath', 'until', 'up', 'upon', 'with', 'within', 'without');

    if(in_array($word, $prepositions))
        return true;

    return false;
}

function getDuplicateWordColor($allColors, &$usedColors) {

    if(empty($usedColors))
        return $allColors[0];

    foreach ($allColors as $currentColor) {
        if(!in_array($currentColor, $usedColors)){
            $usedColors[] = $currentColor;
            return $currentColor;
        }
    }
}

die;