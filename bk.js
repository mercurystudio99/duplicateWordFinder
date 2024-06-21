/*$(document).ready(function() {
    $(document).on('keyup', '.input-check-dupe', function (){
        var text = $(this).val();
        var words = text.split(' ');
        var duplicateWords = [];

        // Reset the input field
        $(this).removeClass('highlight');

        // Find duplicate words
        for (var i = 0; i < words.length; i++) {
            for (var j = i + 1; j < words.length; j++) {
                if (words[i].toLowerCase() === words[j].toLowerCase() && !duplicateWords.includes(words[i].toLowerCase())) {
                    // $(this).css("color", "red");
                    duplicateWords.push(words[i].toLowerCase());
                }
            }
        }

        // Highlight duplicate words
        if (duplicateWords.length > 0) {
            for (var k = 0; k < words.length; k++) {
                if (duplicateWords.includes(words[k].toLowerCase())) {
                    words[k] = '<span class="highlight">' + words[k] + '</span>';
                }
            }
            $(this).val(words.join(' '));
        }
    });
});*/

$(document).on('keyup', '.input-check-dupe',function () {

    mainIndex = $(".input-check-dupe").index($(this));

    // To ignore the last word
    // We need to first check if its the last word of the current keyup field
    // ignore spaces
    if($(this).text() === '') {
        console.log('space detected - continue');
        return true;
    }
    var text = $(this).text();
    var currentWord = text.split(" ").pop();
    currentWord = currentWord.trim();
    if(currentWord === " ") {
        return true;
    }

    console.log('till here');
    $.each($('.input-check-dupe'), function (index1, item) {

        var currentInput = $(item).text();
        console.log(index1 + ' field has: ' + currentInput);
        var words = currentInput.split(' '); // In words we have input value from each fields one by one
        console.log(index1+' splitted: ' + words);
        if(index1 === mainIndex) {
            // In this case we need to skip the last word of the current field
            words.pop();
        }
        console.log(index1+' after popping: ' + words);
        for (var k = 0; k < words.length; k++) { // Iterating through each word from inputs one by one
            if(words[k] === '')
                continue;

            if(words[k].trim() === currentWord) {

                console.log("found duplicate of " + currentWord);
                console.log('Word: ' + words[k]);
                console.log('Current: ' + currentWord);
                var replaceWith = "<span class='red'>"+words[k]+"</span>";
                var updatedText = currentInput.replace(words[k], replaceWith)
                // var check = "what does <span class='red'>this</span> do " + index1;
                $(this).html(updatedText);
                //$('.input-check-dupe').eq(index1).html(check);
            }

        }
    });
});


$(document).on('keyupssss', '.input-check-dupe',function () {

    mainIndex = $(".input-check-dupe").index($(this));
    /*var textArea = $('#second');
    var text = textArea.val();
    console.log(text);
    text = text.replace(/specific/g, '<span class="red">specific</span>');
    console.log(text);
    //text = text.replace(/words/g, '<span class="blue">words</span>');
    textArea.html(text);
    return;*/

    // To ignore the last word
    // We need to first check if its the last word of the current keyup field
    // ignore spaces
    if($(this).text() === '') {
        console.log('space detected - continue');
        return true;
    }
    var text = $(this).text();
    var currentWord = text.split(" ").pop();
    currentWord = currentWord.trim();
    if(currentWord === " ") {
        return true;
    }

    console.log('till here');
    $.each($('.input-check-dupe'), function (index1, item) {

        var currentInput = $(item).text();
        console.log(index1 + ' field has: ' + currentInput);
        var words = currentInput.split(' '); // In words we have input value from each fields one by one
        console.log(index1+' splitted: ' + words);
        if(index1 === mainIndex) {
            // In this case we need to skip the last word of the current field
            words.pop();
        }
        console.log(index1+' after popping: ' + words);
        for (var k = 0; k < words.length; k++) { // Iterating through each word from inputs one by one
            if(words[k] === '')
                continue;

            if(words[k].trim() === currentWord) {

                console.log("found duplicate of " + currentWord);
                console.log('Word: ' + words[k]);
                console.log('Current: ' + currentWord);
                var replaceWith = "<span class='red'>"+words[k]+"</span>";
                var updatedText = currentInput.replace(words[k], replaceWith)
                // var check = "what does <span class='red'>this</span> do " + index1;
                $(this).html(updatedText);
                //$('.input-check-dupe').eq(index1).html(check);
            }

        }
        /*$.each($('input[type="text"]').not(this), function (index2, item2) {

            console.log("item1: ");
            console.log($(item1).val());
            console.log("item2: ");
            console.log($(item2).val());
            if ($(item1).val() == $(item2).val()) {
                $(item1).css("border-color", "red");
                valid = false;
            }

        });*/
    });
});