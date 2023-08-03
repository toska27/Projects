<?php

    require_once "connection.php";
    require_once "validation.php";  
    
    session_start();
    if(!isset($_SESSION["id"]))
    {
        header("Location: index.php");
    }

    $oldPassError = $newPassError = $retypeNewPassError = "";
    $sucMessage = "";
    $errMessage = "";

    $id = $_SESSION["id"];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $oldPass = $conn->real_escape_string($_POST['old_password']);
        $newPass = $conn->real_escape_string($_POST['new_password']);
        $retypeNewPass = $conn->real_escape_string($_POST['retype_new_password']);

        $oldPassError = passwordValidation($oldPass);
        $newPassError = passwordValidation($newPass);
        $retypeNewPassError = passwordValidation($retypeNewPass);

        $q = "SELECT `password` FROM `users` WHERE `id` = $id;";
        $result = $conn->query($q);
        $row = $result->fetch_assoc();
        $basePassword = $row['password'];

        
        if(!password_verify($oldPass, $basePassword)){
            $oldPassError = "Wrong password try again";
        }
        if($newPass !== $retypeNewPass){
            $retypeNewPassError = "You must enter to same passwords";
        }

        
        if($oldPassError == "" && $newPassError == "" && $retypeNewPassError == ""){
            $q = "";
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $q = "UPDATE `users` SET `password` = '$hash' WHERE `id` = $id;";
            if($conn->query($q)){
                $sucMessage = "Your password has been successfully reset";
            } else{
                $errMessage = "We're sorry, but we were unable to reset your password";
            }
        }

    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
        <?php require_once "header.php"; ?>
</header>
<section class="back-reset">
        <div class="form-reset">
            <h1>Please reset the current password</h1>
            <form action="#" method="post" class="reset"> 
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="old_password">Old password:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <input type="password" name="old_password" id="old_password"> <br>
                        <span class="error"><?php echo $oldPassError ?></span>
                    </div>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="new_password">New password:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <input type="password" name="new_password" id="new_password"> <br>
                        <span class="error"><?php echo $newPassError ?></span>
                    </div>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="retype_new_password">Retype new password:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <input type="password" name="retype_new_password" id="retype_new_password"> <br>
                        <span class="error"><?php echo $retypeNewPassError ?></span>
                    </div>
                </div>
                <div class="row g-3 justify-content-center">
                    <input type="submit" class="submit" value="Edit password">
                </div>
            </form>
            <div class="success row justify-content-center">
                <?php echo $sucMessage; ?>
            </div>
            <div class="error row justify-content-center">
                <?php echo $errMessage; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>