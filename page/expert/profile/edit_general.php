<?php 
    session_start();
    if (!isset($_SESSION["expertID"]))
        exit("An error occured: user not defined");

    if (!isset($_SESSION["pageID"]))
        exit("An error occured: page not defined");

    require_once "../../../utility/dbconn.php";
    $dbconn = connect();

   $pageID = $_SESSION["pageID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mentor Program Edit Page</title>
    <link rel="stylesheet" href="../../../css/general/general.css">
    <link rel="stylesheet" href="../../../css/general/owner/navbar.css">
    <link rel="stylesheet" href="../../../css/expert/profile/edit_general.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo-wrapper" style="display: inline-block">LOGO</div>
            <div class="middle-column">
                <a href="../search/searchasmentor.php">Find Business Problem</a>
                <a href="create_page.php">Create Mentoring Program</a>
            </div>
            <div class="right-column">
                <a href="profile.php"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
                <button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
            </div>
        </nav>
    </header>
    <br>
    <main>
        <?php 
            $query = "SELECT * FROM general_prog_page WHERE id = $pageID";
            $result = $dbconn->query($query);
            $row = $result->fetch_assoc();
        ?>
        <form>
            <input type="hidden" id="page-id" value="<?php echo $pageID ?>">

            <section id="edit-program-info">
                <h1>Edit General Program Page</h1>
                <div class="short-input">
                    <div class="left">
                        <div class="program-name">
                            <label for="program-name">Program Name:</label>
                            <input type="text" id="program-name" value="<?php echo $row['program_name']?>" size="50">
                        </div>

                        <div class="program-category">
                            <label for="ajax-category">Category:</label>
                            <select id="ajax-category" data-init-category-id="<?php echo $row["category_id"] ?>"></select>
                        </div>

                        <div class="program-viewlevel">
                            <label for="view-level">View Level:</label>
                            <select id="view-level">
                                <?php 
                                    if ($row["view_level"] == "public") {
                                        echo "
                                        <option value='public' selected>public</option>
                                        <option value='private'>private</option>";
                                    } else {
                                        echo "
                                        <option value='public'>public</option>
                                        <option value='private' selected>private</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="right">
                        <div class="program-mincost">
                            <label for="min-cost">Minimum Cost:</label>
                            <input type="number" id="min-cost" value="<?php echo $row['min_cost']?>">
                        </div>

                        <div class="program-maxcost">
                            <label for="max-cost">Maximum Cost:</label>
                            <input type="number" id="max-cost" value="<?php echo $row['max_cost']?>">
                        </div>

                        <div class="program-duration">
                            <label for="program-duration">Program Duration:</label>
                            <input type="number" id="program-duration" value="<?php echo $row['program_duration']?>">
                        </div>
                    </div>
                </div>

                <div class="program-focus">
                    <label for="focus-problem-desc">Focus Problem Description:</label><br>
                    <span class="textarea" role="textbox" contenteditable id="focus-problem-desc"><?php echo $row['focus_problem_desc']?></span>
                </div>
            </section>

            <section class="program-about">
                <label for="about">About:</label><br>
                <span class="textarea" role="textbox" contenteditable id="about"><?php echo $row['about']?></span>
            </section>

            <section class="program-outcome">
                <label for="expected-outcome">Expected Outcome:</label><br>
                <span class="textarea" role="textbox" contenteditable id="expected-outcome"><?php echo $row['expected_outcome']?></span>
            </section>

            <section class="program-proposal">
                <label for="file">Mentoring Program Proposal:</label><br>
                <input type="file" id="file" style="display:none">
                <input type="hidden" id='init-file-path' value='<?php echo $row["proposal_path"] ?>'>

                <div id="file-btn-container">
                    <button id="choose-file-btn">Choose File</button>
                    <button id="remove-file-btn" style="display:none">Remove File</button>
                    <button id="remove-init-file-btn" style="display:none">Remove File</button>
                    <span id="display-text">no file chosen</span>
                </div>
            </section>

            <input type="submit" id="update-btn" value="update">
        </form>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/expert/profile/edit_general.js?v=<?php echo time(); ?>"></script>
</body>
</html>



