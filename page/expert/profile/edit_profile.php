<?php 
    session_start();
    if (!isset($_SESSION["expertID"]))
        exit("An error occured: user not defined");

    require_once "../../../utility/dbconn.php";
    $dbconn = connect();

    $expertID = $_SESSION["expertID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Program Edit Page</title>
    <link rel="stylesheet" href="../../../css/general/general.css">
    <link rel="stylesheet" href="../../../css/general/owner/navbar.css">
    <link rel="stylesheet" href="../../../css/expert/profile/edit_profile.css">
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
            $query = "SELECT e.*, c.province_id FROM expert e, city c WHERE e.city_id = c.id AND e.id = $expertID";
            $result = $dbconn->query($query);
            
            $row = $result->fetch_assoc();
        ?>
        <form action="backend/profileedit.php" method="POST">
            <h1>Edit Profile</h1>

            <div class="short-input">
                <div class="left">
                    <div class="expert-name">
                        <label for="expert-name">Name</label>
                        <input type="text" id="expert-name" name="name" value="<?php echo $row['name']?>" required>
                    </div>

                    <div class="expert-email">
                        <label for="expert-email">Email</label>
                        <input type="text" id="expert-email" name="email" value="<?php echo $row['email']?>" required>
                    </div>
                    
                    <div class="expert-password">
                        <label for="expert-password">Password: <button id="show-pwd">show</button></label>
                        <input type="password" id="expert-password" name="password" value="<?php echo $row['password']?>" required>
                    </div>

                    <div class="expert-province">
                        <label for="ajax-province">Province:</label>
                        <select id="ajax-province" data-init-province-id="<?php echo $row["province_id"] ?>"></select>
                    </div>

                </div>

                <div class="right">
                    <div class="expert-city">
                        <label for="ajax-city">City:</label>
                        <select name="city-id" id="ajax-city" data-init-city-id="<?php echo $row["city_id"] ?>"></select>
                    </div>

                    <div class="expert-company">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" value="<?php echo $row['company']?>" required>
                    </div>

                    <div class="expert-profession">
                        <label for="profession">Profession</label>
                        <input type="text" id="profession" name="profession" value="<?php echo $row['profession']?>" required>
                    </div>
                </div>
            </div>
            <input type="submit" value="Update">
        </form>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/expert/profile/edit_profile.js?v=<?php echo time(); ?>"></script>
</body>
</html>