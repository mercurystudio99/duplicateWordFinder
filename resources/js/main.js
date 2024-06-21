// On each key up
// Get the current value
// Add spaces around it
// Get values for all classes
// Loop through each word and compare it with the current word
// If these matches then assign a color to it
// Make sure that color is not assigned to anything else
$(document).ready(function() {
    init();
    mainEngine();

    $("select#project-selection").change(function(){
        var projectID = $(this).val();
        window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + "?project="+projectID;
    });

    $("select#project-version-selection").change(function(){
        var projectID = $("select#project-selection").val();
        var versionID = $(this).val();
        window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + "?project="+projectID + "&version="+versionID;
    });

	$(document).on("click", '#project-delete-btn', function() {
        var isConfirmed = confirm("Are you sure you want to delete the selected project and all its tables ?");
        if(isConfirmed) {
            // Get the selected project id
            var projectID = $("select#project-selection").val();
            $.ajax({
                url: "/delete_project.php",
                data: {project_id: projectID},
                type: "POST",
                dataType: 'JSON',
                async: false,
                success: function (result) {
                    console.log('not here');
                    window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname;
                }
            });
        }
    });

    $(document).on('click', '#add-table-btn', function() {

        // First create the IDs for this
        var numItems = $('.main-table-un-class').length;
        var titleID = numItems+1 + "-title";
        var subtitleID = numItems+1 + "-subtitle";
        var keywordsID = numItems+1 + "-keywords";
        var dataID = numItems+1 + "-new";

        var versionID = $('#project-version-selection').val();
        // Add the new table in the DB first
        $.ajax({
            url: "/add_table.php",
            data: {version_id: versionID},
            type: "POST",
            dataType: 'JSON',
            async: false,
            success: function (result) {
                dataID = result.table_id;
                console.log('table id is ' + dataID);
                titleID = dataID + "-title";
                subtitleID = dataID + "-subtitle";
                keywordsID = dataID + "-keywords";
            }
        });

        // Add new table to the existing ones
        var newTable = '<div class="col-md-4 custom-table-col">\n' +
            '                        <div class="main-table">\n' +
            '                            <div class="main-table-un-class">\n' +
            '                                <div class="tab-name"><p class="table-name-input" data-id="'+dataID+'" contenteditable="true">New Table</p></div>\n' +
            '                                <div class="delete-wrap"><button data-id="'+dataID+'" class="del-tbl-btn"><i class="fa fa-trash-o tab-del-icon"></i></button></div>\n' +
            '                                <div class="clear"></div>\n' +
            '                            </div>\n' +
            '                            <div class="table-main-content">\n' +
            '                                <div class="table-upper">\n' +
            '                                    <div class="table-upper-wrap">\n' +
            '                                        <div class="table-title">Title</div>\n' +
            '                                        <div class="table-count">\n' +
            '                                            <div class="char-current green">0</div>\n' +
            '                                            <span>/</span>\n' +
            '                                            <div class="char-allowed">30</div>\n' +
            '                                        </div>\n' +
            '                                        <div class="clear"></div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="title-content">\n' +
            '                                    <p class="form-control display-check-dupe" data-id="'+titleID+'"></p>' +
            '                                    <textarea class="form-control input-check-dupe" rows="1" data-id="'+dataID+'" data-type="title" id="'+titleID+'"></textarea>\n' +
            '                                </div>\n' +
            '\n' +
            '                                <div class="table-upper custom-mt">\n' +
            '                                    <div class="table-upper-wrap">\n' +
            '                                        <div class="table-title">Subtitle</div>\n' +
            '                                        <div class="table-count">\n' +
            '                                            <div class="char-current green">0</div>\n' +
            '                                            <span>/</span><div class="char-allowed">30</div>\n' +
            '                                        </div>\n' +
            '                                        <div class="clear"></div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="title-content">\n' +
            '                                    <p class="form-control display-check-dupe" data-id="'+subtitleID+'"></p>' +
            '                                    <textarea class="form-control input-check-dupe" rows="1" data-id="'+dataID+'" data-type="subtitle" id="'+subtitleID+'"></textarea>\n' +
            '                                </div>\n' +
            '                                <div class="table-upper custom-mt">\n' +
            '                                    <div class="table-upper-wrap">\n' +
            '                                        <div class="table-title">Keywords</div>\n' +
            '                                        <div class="table-count">\n' +
            '                                            <div class="char-current green">0</div>\n' +
            '                                            <span>/</span>\n' +
            '                                            <div class="char-allowed">100</div>\n' +
            '                                        </div>\n' +
            '                                        <div class="clear"></div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="title-content">\n' +
            '                                    <p class="form-control display-check-dupe table-keywords" data-id="'+keywordsID+'"></p>' +
            '                                    <textarea class="form-control input-check-dupe table-keywords" style="height: 190px;" data-id="'+dataID+'" data-type="keywords" id="'+keywordsID+'"></textarea>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>';
        $(".all-tables-inner").append(newTable);
    });

    $(document).on('click', '.del-tbl-btn', function() {

        // First delete this table from DB
        var tableID = $(this).attr('data-id');
        $.ajax({
            url: "/delete_table.php",
            data: {table_id: tableID},
            type: "POST",
            dataType: 'JSON',
            async: false,
            success: function (result) {
                console.log(result);
            }
        });

        $(this).parent().parent().parent().parent().remove();
    });

	$(document).on("click", '#project-search-btn', function() {
        var projectCountryCode = $(".country3-selectpicker").val();
        var projectName = $(".project_name").val();
        if (projectName == '') { alert('Project name is empty!'); return; }
        $(".appList").empty();
        $.ajax({
            url: "/search_project.php",
            data: {project_countrycode: projectCountryCode, project_name: projectName},
            type: "POST",
            dataType: 'JSON',
            async: false,
            success: function (result) {
                var apps = result.apps;

                var newDiv = "";
                if (apps.length > 0) {
                    for (let index = 0; index < apps.length; index++) {
                        const element = apps[index];
                        newDiv += '<div class="col-md-2 text-center">\n' +
                            '<img src="'+ element.logo +'" alt="'+ element.trackName +'" />\n' +
                        '</div>\n'+
                        '<div class="col-md-8 my-3">\n' +
                            '<span>'+ element.trackName +'</span>\n' +
                        '</div>\n'+
                        '<div class="col-md-2 my-3 text-center">\n' +
                            '<button class="btn btn-primary create-project-btn" data-trackId="'+ element.trackId +'" data-name="'+ element.trackName +'" data-bundleId="'+ element.bundleId +'"><i class="fa fa-plus"></i></button>\n' +
                        '</div>';
                    }
                    $(".appList").append(newDiv);
                    $(".appList").css('height', '200px');
                    $(".appList").css('overflow-y', 'scroll');
                }
            }
        });
    });

    $(document).on('click', '.create-project-btn', function() {
        var trackId = $(this).attr('data-trackId');
        var trackName = $(this).attr('data-trackName');
        var bundleId = $(this).attr('data-bundleId');
        $(".appList").empty();
    });

    $(document).on('focusout', '.table-name-input', function () {

        var tableID = $(this).attr('data-id');
        var newName = $(this).text();
        $.ajax({
            url: "/change_table_name.php",
            data: {table_id: tableID, table_name: newName},
            type: "POST",
            dataType: 'JSON',
            async: false,
            success: function (result) {
                console.log("Table name updated");
            }
        });
    });

    $(document).on('focusout', '.input-check-dupe', function(e) {

        if (e.originalEvent.inputType === "insertText" && e.originalEvent.data === " ") {
            console.log('Space key pressed, exiting');
            return;
        }

		var allData = [];
        var elemID = $(this).attr('id');
        var editableDiv = document.getElementById(elemID);

        var twoDJsonObject = {};

        $.each($('.input-check-dupe'), function (index1, item) {
            setCharacterCount($(this));
            var tableID = $(item).attr('data-id');
            var fieldType = $(item).attr('data-type');

            if(tableID in twoDJsonObject)
                console.log('no need');
            else
                twoDJsonObject[tableID] = {};
            twoDJsonObject[tableID][fieldType] = $(item).val();
        });
        var projectID = $('#project-selection').val();
        console.log(twoDJsonObject);
        $.ajax({
            url: "/check.php",
            data: {allData: allData, tableData: twoDJsonObject, projectID: projectID},
            type: "POST",
            dataType: 'JSON',
            async: false,
            success: function (result) {
                console.log('success')
            }
        });
    });

    $(document).on('input', '.input-check-dupe', function(e) {
        mainEngine();
        {
            var id = $(this).attr('id');
            var scrollTop = $(this).scrollTop();
            $(".display-check-dupe").each(function(){
                if ($(this).attr('data-id') == id) {
                    $(this).scrollTop(scrollTop);
                }
            });
        }
    });

    $('.input-check-dupe').scroll(function(){
        var id = $(this).attr('id');
        var scrollTop = $(this).scrollTop();
        $(".display-check-dupe").each(function(){
            if ($(this).attr('data-id') == id) {
                $(this).scrollTop(scrollTop);
            }
        });
    });

    function setCursorToEnd(editableDiv) {
        // Set the focus to the end of the div
        var range = document.createRange();
        range.selectNodeContents(editableDiv);
        range.collapse(false);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }

    function setCharacterCount(currentElement) {

        var charCount = currentElement.val().length;
        var currentCharProps = currentElement.parent().prev().children('.table-upper-wrap').children('.table-count');
        currentCharProps.children(".char-current").text(charCount);
        var allowedChars = currentCharProps.children(".char-allowed").text();
        if(charCount > allowedChars) {
            currentCharProps.children(".char-current").css({"color": "darkred"});
        }else {
            currentCharProps.children(".char-current").css({"color": "green"});
        }
    }

    function isPreposition(word) {
        const prepositions = ['-', 'about', 'above', 'across', 'after', 'against', 'along', 'among', 'around', 'as', 'at', 'before', 'behind', 'below', 'beneath', 'beside', 'between', 'beyond', 'but', 'by', 'concerning', 'despite', 'down', 'during', 'except', 'for', 'from', 'in', 'inside', 'into', 'like', 'near', 'of', 'off', 'on', 'onto', 'out', 'outside', 'over', 'past', 'regarding', 'round', 'since', 'through', 'throughout', 'till', 'to', 'toward', 'under', 'underneath', 'until', 'up', 'upon', 'with', 'within', 'without'];
    
        return prepositions.includes(word);
    }

    function getDuplicateWordColor(allColors, usedColors) {
        if (usedColors.length === 0) {
            return allColors[0];
        }
    
        for (let currentColor of allColors) {
            if (!usedColors.includes(currentColor)) {
                usedColors.push(currentColor);
                return currentColor;
            }
        }
    }
    
    function containsSpecialCharacters(word) {
        // Use a regular expression to check for special characters
        return /[!@#$%^&*(),.?":{}|<>]/.test(word);
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function findDuplicatesIgnoreCase(arr) {
        let lowerCaseArr = arr.map(item => (typeof item === 'string' ? item.toLowerCase() : item));
        let duplicates = lowerCaseArr.filter((item, index) => lowerCaseArr.indexOf(item) !== index);
        return [...new Set(duplicates)];
    }

    function mainEngine() {
		var allData = [];

        $.each($('.input-check-dupe'), function (index1, item) {
            setCharacterCount($(this));
            allData[index1] = $(item).val();
        });

        {
            let allWords = [];
            let allIgnoreWords = [];
            let keyWords = [];
            let allDuplicateWords = [];
            let usedColors = [];
            let wordColor = {};
            let duplicateFound = false;
            let postData = [];
            let regex;

            for (let i in allData) {
                postData.push(allData[i]);
            }

            for (let inputIndex in postData) {
                let inputValue = postData[inputIndex];
                if (inputValue === "") {
                    continue;
                }

                // inputValue = inputValue.replace(/[\s\u2028\u2029]+$/, "");
                // regex = new RegExp("&nbsp;", 'g');
                // inputValue = inputValue.replace(regex, " ");
                // regex = new RegExp("&lt;", 'g');
                // inputValue = inputValue.replace(regex, "<");
                // regex = new RegExp("&gt;", 'g');
                // inputValue = inputValue.replace(regex, ">");
                // regex = new RegExp("<div>", 'g');
                // inputValue = inputValue.replace(regex, "");
                // regex = new RegExp("</div>", 'g');
                // inputValue = inputValue.replace(regex, "");
                // inputValue = inputValue.replace(/<[^>]+>/g, '');

                let words = inputValue.split(/[,\s]+/);

                for (let wordIndex in words) {
                    let word = words[wordIndex];
                    if (word === "") {
                        continue;
                    }
                    let lowerCaseWord = word.toLowerCase();
                    if (isPreposition(lowerCaseWord)) {
                        continue;
                    }

                    if (!allWords.includes(word)) {
                        allWords.push(word);
                    }
                    if (allIgnoreWords.includes(lowerCaseWord) && !keyWords.includes(lowerCaseWord)) {
                        keyWords.push(lowerCaseWord);
                    }
                    if (!allIgnoreWords.includes(lowerCaseWord)) {
                        allIgnoreWords.push(lowerCaseWord);
                    }
                }
            }

            for (let i in keyWords) {
                for (let j in allWords) {
                    if (keyWords[i] == allWords[j].toLowerCase()) allDuplicateWords.push(allWords[j]);
                }
            }

            for (let index in postData) {
                let text = postData[index];
                if (text === "") {
                    continue;
                }

                regex = new RegExp("\n", 'g');
                text = text.replace(regex, "<br>");
                text = text.replace(/( {2})/g, (match, p1) => "&nbsp;".repeat(p1.length));

                console.log(text)
                for (let wordIndex in allDuplicateWords) {
                    let word = allDuplicateWords[wordIndex];
                    let lowerCaseWord = word.toLowerCase();
                    let color;
                    if (wordColor[lowerCaseWord] !== undefined) {
                        color = wordColor[lowerCaseWord];
                    } else {
                        color = getDuplicateWordColor(allColors, usedColors);
                        wordColor[lowerCaseWord] = color;
                        usedColors.push(color);
                    }
                    let coloredWord = "<span style=\"color:" + color + "\">" + word + "</span>";
                    if (containsSpecialCharacters(word)) {
                        let pattern = new RegExp('\\b' + escapeRegExp(word) + '\\b', 'g');
                        text = text.replace(pattern, ' ' + coloredWord);
                    } else {
                        let pattern = new RegExp('\\b' + escapeRegExp(word) + '\\b', 'g');
                        text = text.replace(pattern, coloredWord);
                    }
                    duplicateFound = true;
                    postData[index] = text;
                }
            }

            var result = {'duplicate': duplicateFound, 'data': postData};
            $.each(result.data, function (index, value) {
                $(".display-check-dupe:eq("+index+")").html(value);
            });
            
        }
    }

    function init() {
        const BASE_URL = 'https://img.mobiscroll.com/';

        var selectElement = document.querySelector('.country-selectpicker');
        for (let country of allCountries) {
            var newOption = document.createElement('option');
            newOption.text = country.name;
            newOption.setAttribute('data-thumbnail', "demos/flags/"+country.code+".png");
            newOption.setAttribute('value', country.code);
            if (country.code == $('#countryCode').val()) newOption.setAttribute('selected', 'selected');
            selectElement.add(newOption);
        }

        var selectElement2 = document.querySelector('.country2-selectpicker');
        for (let country of allCountries) {
            var newOption = document.createElement('option');
            newOption.text = country.name;
            newOption.setAttribute('data-thumbnail', "demos/flags/"+country.code+".png");
            newOption.setAttribute('value', country.code);
            if (country.code == $('#countryCode').val()) newOption.setAttribute('selected', 'selected');
            selectElement2.add(newOption);
        }

        var selectElement3 = document.querySelector('.country3-selectpicker');
        for (let country of allCountries) {
            var newOption = document.createElement('option');
            newOption.text = country.name;
            newOption.setAttribute('data-thumbnail', "demos/flags/"+country.code+".png");
            newOption.setAttribute('value', country.code);
            if (country.code == $('#countryCode').val()) newOption.setAttribute('selected', 'selected');
            selectElement3.add(newOption);
        }

        const $_SELECT_PICKER = $('.country-selectpicker');
        $_SELECT_PICKER.find('option').each((idx, elem) => {
            const $OPTION = $(elem);
            const IMAGE_URL = $OPTION.attr('data-thumbnail');
            if (IMAGE_URL) {
                $OPTION.attr('data-content', "<img src='%i'/> %s".replace(/%i/, BASE_URL + IMAGE_URL).replace(/%s/, $OPTION.text()))
            }
        });
        $_SELECT_PICKER.selectpicker();

        const $_SELECT_PICKER2 = $('.country2-selectpicker');
        $_SELECT_PICKER2.find('option').each((idx, elem) => {
            const $OPTION = $(elem);
            const IMAGE_URL = $OPTION.attr('data-thumbnail');
            if (IMAGE_URL) {
                $OPTION.attr('data-content', "<img src='%i'/> %s".replace(/%i/, BASE_URL + IMAGE_URL).replace(/%s/, $OPTION.text()))
            }
        });
        $_SELECT_PICKER2.selectpicker();

        const $_SELECT_PICKER3 = $('.country3-selectpicker');
        $_SELECT_PICKER3.find('option').each((idx, elem) => {
            const $OPTION = $(elem);
            const IMAGE_URL = $OPTION.attr('data-thumbnail');
            if (IMAGE_URL) {
                $OPTION.attr('data-content', "<img src='%i'/> %s".replace(/%i/, BASE_URL + IMAGE_URL).replace(/%s/, $OPTION.text()))
            }
        });
        $_SELECT_PICKER3.selectpicker();

        $(document).on('click', '.pagination li a', function() {
            var currentpage = 1;
            var maxpage = 1;
            $('.pagination').find('a').each((idx, elem) => {
                if ($(elem).hasClass('text-primary')) currentpage = $(elem).attr('data-page');
                if ($(elem).is('[data-max]')) maxpage = $(elem).attr('data-page');
            });

            if ($(this).attr('data-page') == 'previous') {
                if (currentpage != 1) currentpage--;
            }
            else if ($(this).attr('data-page') == 'next') {
                if (currentpage != maxpage) currentpage++;
            }
            else {
                currentpage = $(this).attr('data-page');
            }
            currentpage--;
            if ($('#project-selection').val() == null) location.assign(window.location.protocol+"//"+window.location.host+window.location.pathname+"?offset="+currentpage+"&countryCode="+$('.country-selectpicker').val());
            if ($('#project-selection').val() != null) location.assign(window.location.protocol+"//"+window.location.host+window.location.pathname+"?offset="+currentpage+"&countryCode="+$('.country-selectpicker').val()+"&project="+$('#project-selection').val());
        });
        $(document).on('click', '#refreshBtn', function() {
            if ($('#project-selection').val() == null) location.assign(window.location.protocol+"//"+window.location.host+window.location.pathname+"?offset=0&countryCode="+$('.country-selectpicker').val());
            if ($('#project-selection').val() != null) location.assign(window.location.protocol+"//"+window.location.host+window.location.pathname+"?offset=0&countryCode="+$('.country-selectpicker').val()+"&project="+$('#project-selection').val());
        });
    }
});