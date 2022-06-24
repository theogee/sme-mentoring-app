<?php 
    session_start();
    if (!isset($_SESSION["expertID"]))
        exit("An error occured: user not defined");

    require_once "../../../utility/dbconn.php";
    $dbconn = connect();

    $_SESSION["pageID"] = $pageID = $_GET["pageID"];
    $pageTitle = $_GET["pageTitle"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../../../css/general/general.css">
    <link rel="stylesheet" href="../../../css/general/owner/navbar.css">
    <link rel="stylesheet" href="../../../css/expert/profile/general_page.css">
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
    <main>
        <?php
            $query = "SELECT gpp.*, bc.name as category_name FROM general_prog_page gpp, business_category bc WHERE gpp.category_id = bc.id AND gpp.id = $pageID ";

            $result = $dbconn->query($query);


            $row = $result->fetch_assoc();

            $min = $row['min_cost'];
            $max = $row['max_cost'];
        ?>
        
        <section id="notification">
            <div id="ajax-notif" style="display:none"></div>
        </section>

        <section id="program-info">
            <h2><?php echo $row['program_name'] . " <span class='program-category'>" . $row["category_name"] . "</span> <span class='program-viewlevel'> " . $row["view_level"] . "</span>" ?></h2>
            <p>Focus problem:<br><?php echo $row['focus_problem_desc']?></p>  
            <a class="edit-page-link" href="edit_general.php">Edit Page</a>
           <button id="delete-btn">Delete Page</button>
        </section>

        <section id="program-desc">
            <h2>Cost</h2>
            <p> <?php echo "Rp. ".number_format($min , 0, ',', '.')." - "."Rp. ".number_format($max , 0, ',', '.') ?></p>
            <h2>Duration</h2>
            <p><?php echo $row['program_duration']. " ". "Month"?></p>
            <h2>About</h2>
            <p> <?php echo $row['about'] ?></p>
            <h2> Expected Outcome </h2>
            <p> <?php echo $row['expected_outcome'] ?></p>
            <h2>Mentoring Program Proposal</h2>
            <div id="mentoring-proposal" data-file-path="<?php echo $row['proposal_path']; ?>" data-program-name="<?php echo $row["program_name"] ?>"></div>
        </section>

        <section id="interested-business">
            <h2>Interested Business</h2>
            <div id="ajax-interested-business" data-page-id="<?php echo $row["id"]; ?>"></div>
        </section>

    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/expert/profile/general_page.js?v=<?php echo time(); ?>"></script>
</body>
</html>