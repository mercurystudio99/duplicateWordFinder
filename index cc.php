<?php
require "db_connect.php";

/*$str = '<span class="red">Should</span> <span class="red">show</span> up!';
$new = strip_tags($str);
echo mb_strlen($new);
die;*/


$selectedProject = '';

// Get the project and tables data
$sql = "SELECT * FROM projects";
$result = mysqli_query($conn, $sql);
$projects = [];
while ($row = mysqli_fetch_assoc($result)) {
    $projects[$row['id']] = $row['name'];
}

$selectedVersion = '';
$versions = [];
if(!empty($_GET['project'])) {
    $selectedProject = $_GET['project'];
    // Get the version
    $sql = "SELECT * FROM versions WHERE project_id = $selectedProject";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $versions[$row['id']] = $row['name'];
    }
}

if(!empty($_GET['version'])) {
    $selectedVersion = $_GET['version'];
    $sql = "SELECT * FROM project_tables WHERE version_id = '$selectedVersion'";
    $result = mysqli_query($conn, $sql);
    $currentProject = [];
    $n = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $currentProject[$n]['project_id'] = $selectedProject;
        $currentProject[$n]['version_id'] = $row['version_id'];
        $currentProject[$n]['table_id'] = $row['id'];
        $currentProject[$n]['table_name'] = $row['name'];
        $currentProject[$n]['table_subtitle'] = $row['subtitle'];
        $currentProject[$n]['table_title'] = $row['title'];
        $currentProject[$n]['table_keywords'] = $row['keywords'];

        $subTitleCount = mb_strlen(strip_tags(trim($row['subtitle'])));
        $currentProject[$n]['table_subtitle_count'] = $subTitleCount;
        $currentProject[$n]['table_subtitle_class'] = ($subTitleCount > 30) ? 'red' : 'green';

        $titleCount = mb_strlen(strip_tags(trim($row['title'])));
        $currentProject[$n]['table_title_count'] = $titleCount;
        $currentProject[$n]['table_title_class'] = ($titleCount > 30) ? 'red' : 'green';

        $keywordsCount = mb_strlen(strip_tags(trim($row['keywords'])));
        $currentProject[$n]['table_keywords_count'] = $keywordsCount;
        $currentProject[$n]['table_keywords_class'] = ($keywordsCount > 100) ? 'red' : 'green';

        $n++;
    }
}

// Get the apps

$countryCode = 'US';
if (!empty($_GET['countryCode'])) {
    $countryCode = $_GET['countryCode'];
}
$offset = 0;
$limit = 10;
$currentPage = $offset + 1;
if (!empty($_GET['offset'])) {
    $offset = $_GET['offset'] * $limit;
    $currentPage = $_GET['offset'] + 1;
}

$sql = "SELECT * FROM apps WHERE countryCode = '$countryCode' ORDER BY position LIMIT $offset, $limit";
$sql2 = "SELECT COUNT(id) AS total FROM apps WHERE countryCode = '$countryCode'";
$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);
$apps = [];
while ($row = mysqli_fetch_assoc($result)) {
    array_push($apps, $row);
}
$total = 0;
while ($row = mysqli_fetch_assoc($result2)) {
    $total = $row['total'];
}

?>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel=stylesheet href="https://unpkg.com/bootstrap-select@1.13.8/dist/css/bootstrap-select.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-select@1.13.8/dist/js/bootstrap-select.min.js"></script>


    <script src="resources/js/colors.js"></script>
    <script src="resources/js/countries.js"></script>
    <script src="resources/js/main.js"></script>
    <link rel="stylesheet" href="resources/css/style.css" />
    <link rel="stylesheet" href="resources/fa/css/font-awesome.min.css">
</head>
<body>
<div>
    <div class="main-wrapper">
	    <div class="inner-wrapper px-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="project-dropdown-wrapper">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="pd-wrap d-flex align-items-center justify-content-end">
                                    <div class="pd-add">
                                        <span>Version</span>
                                    </div>
                                    <div class="pd-add">
                                        <select class="form-control" id="project-version-selection">
                                            <option selected disabled>Select..</option>
                                            <?php
                                                foreach ($versions as $key => $name) {
                                                    $state = ($key == $selectedVersion) ? "selected" : "";
                                                    echo "<option value='$key' $state>$name</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <?php
                                        if(!empty($selectedProject)) {
                                    ?>
                                    <div class="pd-add">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#addVersionModal">New <i class="fa fa-plus"></i></button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="pd-wrap d-flex align-items-center justify-content-center">
                                    <div class="pd-list">
                                        <select class="form-control" id="project-selection">
                                            <option selected disabled>Select Project</option>
                                            <?php
                                                foreach ($projects as $key => $name) {
                                                    $state = ($key == $selectedProject) ? "selected" : "";
                                                    echo "<option value='$key' $state>$name</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="pd-add">
                                        <button class="btn btn-danger" id="project-delete-btn"><i class="fa fa-trash-o"></i> Delete</button>
                                    </div>
                                    <div class="pd-add">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#addProjectModal"><i class="fa fa-plus-circle"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php
                    if(!empty($selectedVersion)) {
                    ?>
                        <div class="table-container">

                            <div class="add-table-wrap">
                            
                                <div class="add-table-inner d-flex align-items-center justify-content-center">
                                    <button class="btn btn-primary" id="add-table-btn"><i class="fa fa-plus"></i> Add a Table</button>
                                </div>
                            </div>

                            <div class="all-tables">
                                <div class="row all-tables-inner">
                                    <?php
                                    foreach ($currentProject as $project) {
                                    ?>
                                        <div class="col-md-4 custom-table-col">
                                            <div class="main-table">
                                                <div class="main-table-un-class">
                                                    <div class="tab-name-d"><p class="table-name-input" data-id="<?php echo $project['table_id']; ?>" contenteditable="true"><?php echo $project['table_name']?></p></div>
                                                </div>
                                                <div class="table-main-content">
                                                    <div class="table-upper">
                                                        <div class="table-upper-wrap">
                                                            <div class="table-title">Title</div>
                                                            <div class="table-count">
                                                                <div class="char-current <?php echo $project['table_title_class']?>"><?php echo $project['table_title_count']?></div>
                                                                <span>/</span>
                                                                <div class="char-allowed">30</div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                    <div class="title-content">
                                                        <p class="form-control display-check-dupe" data-id="<?php echo $project['table_id']."-title"; ?>"></p>
                                                        <p contenteditable="true" class="form-control input-check-dupe" data-type="title" data-id="<?php echo $project['table_id']; ?>" id="<?php echo $project['table_id']."-title"; ?>"><?php echo $project['table_title']; ?></p>
                                                    </div>

                                                    <div class="table-upper custom-mt">
                                                        <div class="table-upper-wrap">
                                                            <div class="table-title">Subtitle</div>
                                                            <div class="table-count">
                                                                <div class="char-current <?php echo $project['table_subtitle_class']?>"><?php echo $project['table_subtitle_count']?></div>
                                                                <span>/</span>
                                                                <div class="char-allowed">30</div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                    <div class="title-content">
                                                        <p class="form-control display-check-dupe" data-id="<?php echo $project['table_id']."-subtitle"; ?>"></p>
                                                        <p contenteditable="true" class="form-control input-check-dupe" data-type="subtitle" data-id="<?php echo $project['table_id']; ?>" id="<?php echo $project['table_id']."-subtitle"; ?>"><?php echo $project['table_subtitle']; ?></p>
                                                    </div>
                                                    <div class="table-upper custom-mt">
                                                        <div class="table-upper-wrap">
                                                            <div class="table-title">Keywords</div>
                                                            <div class="table-count">
                                                                <div class="char-current <?php echo $project['table_keywords_class']?>"><?php echo $project['table_keywords_count']?></div>
                                                                <span>/</span>
                                                                <div class="char-allowed">100</div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                    <div class="title-content title-content-keywords">
                                                        <p class="form-control display-check-dupe table-keywords" data-id="<?php echo $project['table_id']."-keywords"; ?>"></p>
                                                        <textarea class="form-control input-check-dupe table-keywords" data-type="keywords" data-id="<?php echo $project['table_id']; ?>" id="<?php echo $project['table_id']."-keywords"; ?>"><?php echo $project['table_keywords']; ?></textarea>
                                                        <!-- <div contenteditable="true" class="form-control input-check-dupe table-keywords" data-type="keywords" data-id="<?php echo $project['table_id']; ?>" id="<?php echo $project['table_id']."-keywords"; ?>"><?php echo $project['table_keywords']; ?></div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <?php
                     if ($total > 0) {
                        $count = ceil($total / $limit);
                        $maxVisiblePages = 5;
                        echo "<nav>";
                        echo "    <ul class='pagination justify-content-center'>";
                        echo "        <li class='page-item'><a class='page-link border-0' data-page='previous'><</a></li>";
                        if ($count > $maxVisiblePages) {
                            $start = max(1, min($count - $maxVisiblePages + 1, $currentPage - floor($maxVisiblePages / 2)));
                            $end = min($count, $start + $maxVisiblePages - 1);
                            if ($start > 1) {
                                if ($currentPage == 1) echo "        <li class='page-item'><a class='page-link border-0 text-primary' data-page='1'>1</a></li>";
                                if ($currentPage != 1) echo "        <li class='page-item'><a class='page-link border-0' data-page='1'>1</a></li>";
                                if ($start > 2) {
                                    echo "        <li class='page-item disabled'><span class='page-link border-0'>...</span></li>";
                                }
                            }
                            for ($i = $start; $i <= $end; $i++) {
                                if ($currentPage == $i && $i != $end) echo "        <li class='page-item'><a class='page-link border-0 text-primary' data-page='$i'>$i</a></li>";
                                if ($currentPage != $i && $i != $end) echo "        <li class='page-item'><a class='page-link border-0' data-page='$i'>$i</a></li>";
                                if ($currentPage == $i && $i == $end) echo "        <li class='page-item'><a class='page-link border-0 text-primary' data-page='$i' data-max='$i'>$i</a></li>";
                                if ($currentPage != $i && $i == $end) echo "        <li class='page-item'><a class='page-link border-0' data-page='$i' data-max='$i'>$i</a></li>";
                            }
                            if ($end < $count) {
                                if ($end < $count - 1) {
                                    echo "        <li class='page-item disabled'><span class='page-link border-0'>...</span></li>";
                                }
                                if ($currentPage == $count) echo "        <li class='page-item'><a class='page-link border-0 text-primary' data-page='$count' data-max='$count'>$count</a></li>";
                                if ($currentPage != $count) echo "        <li class='page-item'><a class='page-link border-0' data-page='$count' data-max='$count'>$count</a></li>";
                            }
                        } else {
                            for ($i = 1; $i <= $count; $i++) {
                                if ($currentPage == $i && $i != $count) echo "        <li class='page-item'><a class='page-link border-0 text-primary' data-page='$i'>$i</a></li>";
                                if ($currentPage != $i && $i != $count) echo "        <li class='page-item'><a class='page-link border-0' data-page='$i'>$i</a></li>";
                                if ($currentPage == $i && $i == $count) echo "        <li class='page-item'><a class='page-link border-0 text-primary' data-page='$i' data-max='$i'>$i</a></li>";
                                if ($currentPage != $i && $i == $count) echo "        <li class='page-item'><a class='page-link border-0' data-page='$i' data-max='$i'>$i</a></li>";
                            }
                        }
                        echo "        <li class='page-item'><a class='page-link border-0' data-page='next'>></a></li>";
                        echo "    </ul>";
                        echo "</nav>";
                     }
                    ?>
                    <div class="pd-wrap d-flex align-items-center justify-content-start px-4">
                        <div style="width: 250px;">
                            <select title="Select Country" class="country-selectpicker">
                            </select>
                        </div>
                        <div class="pd-add">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addKeywordModal"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="pd-add">
                            <button class="btn btn-primary" id="refreshBtn"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                    <div class="p-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-0 text-secondary">Keywords</th>
                                    <th scope="col" class="border-0 text-secondary text-center">Rank</th>
                                    <th scope="col" class="border-0 text-secondary text-center">Vol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($apps as $app) {
                                        echo "<tr>";
                                        echo "<th scope='row' class='border-0'>" . $app['name'] . "</th>";
                                        echo "<td class='border-0 text-center'>" . ($app['position'] + 1) . "</td>";
                                        echo "<td class='border-0 text-center'></td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="countryCode" value="<?php echo $countryCode; ?>"/>

    <!-- Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="create_project.php">
                    <div class="modal-body">
                        <label><b>Project Name:</b></label>
                        <input type="text" class="form-control" name="project_name" required />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addVersionModal" tabindex="-1" role="dialog" aria-labelledby="addVersionModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Version</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="create_version.php">
                    <div class="modal-body">
                        <label><b>Project Version:</b></label>
                        <input type="text" class="form-control" name="project_version" required />
                        <input type="hidden" class="form-control" name="project_id" value="<?php echo $selectedProject; ?>" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addKeywordModal" tabindex="-1" role="dialog" aria-labelledby="addKeywordModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="create_keyword.php">
                    <div class="modal-body p-4">
                        <div class="row my-3">
                            <div class="col-md-2 text-right">
                                <label>Country:</label>
                            </div>
                            <div class="col-md-10">
                                <select title="Select Country" class="form-control country2-selectpicker" name="country">
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2 text-right">
                                <label>Keywords:</label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control" name="keywords" required></textarea>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary px-4">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--<h1>Duplication Check</h1>
<div class="row">
    <div class="col-md-4">
        <p>Table one</p>
        <div class="table-wrapper">
            <p contenteditable="true" class="form-control input-check-dupe" id="1"></p>
        </div>
    </div>
    <div class="col-md-4">
        <p>Table two</p>
        <div class="table-wrapper">
            <p contenteditable="true" class="form-control input-check-dupe" id="2"></p>
        </div>
    </div>
</div>-->

</body>
</html>
