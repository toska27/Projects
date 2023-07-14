<?php 
    session_start();
    require_once "connection.php";
    require_once "validation.php";

    $poruka = "";
    if (isset($_GET["p"]) && $_GET["p"] == "ok"){
        $poruka = "You have successfully registred, please login to continue";
    }

    $username = "anonymus";
    if(isset($_SESSION["username"])){ // da li je logovan korisnik
        $username = $_SESSION["username"];
        $id = $_SESSION["id"]; // id logovanog korisnika
        $row = profileExists($id, $conn);
        $m = "";
        if($row === false){
            // Logovani korisnik nema profil
            $m = "Create";
        } else{
            // Logovani korisnik ima profil
            $m = "Edit";
            $username = $row["first_name"] . " " . $row["last_name"];
        }
                      
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" version="2.1">
</head>
<body>
    <header>
        <?php require_once "header.php"; ?>
    </header>
    <div class="back-index">
        <div class="text-index">
            <h1 class="bold">Welcome, <?php echo $username ?>, to our Social Network!</h1>
            <?php if(!isset($_SESSION["username"])){?>
                <p class="bold">New to our site? <br> <a href="register.php" class="line-color">Register here</a> to access our site</p>                        <p class="bold">Already have the account? <br> <a href="login.php" class="line-color">Login here</a> to continue to our site</p>
            <?php } else{ ?>
                <p class="bold"><?php echo $m ?> a <a href="profile.php" class="line-color">profile</a></p>
                <p class="bold">See other members <a href="followers.php" class="line-color">here</a></p>
                <p class="bold"><a href="logout.php" class="line-color">Logout</a> from our site.</p>
            <?php } ?>
            <div class="alert alert-success" role="alert">
                <?php echo $poruka ?>
            </div> 
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>