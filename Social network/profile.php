<?php
    require_once "connection.php";
    require_once "validation.php";
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: index.php");      // Ne dozvoljavamo pristup ovoj stranici NE LOGOVANIM korisnicima
    }


    // ID_USER IZ TABELE PROFILES == ID IZ TABELE USERS

    $id = $_SESSION['id'];       // UZIMA ID IZ TABELE USERS

    $firstName = $lastName = $dob = $gender = $image = $bio = "";
    $firstNameError = $lastNameError = $dobError = $genderError = $imageError = $bioError = "";

    $sucMessage = "";
    $errMessage = "";

    $profileRow = profileExists($id, $conn);   // DA LI PROFIL POSTOJI (PROVEREVA DA LI JE ID_USER(profiles) = ID(users))
    // $profileRow = false, ako profil ne postoji
    // $profileRow = asocijativni niz, ako profil postoji

    $default = array('male','female','other');   // ZA SLIKU

    if($profileRow !== false){                 // AKO PROFIL POSTOJI, KUPI PODATKE IZ TABELE PROFILES
        $firstName = $profileRow['first_name'];
        $lastName = $profileRow['last_name'];
        $gender = $profileRow['gender'];
        $dob = $profileRow['dob'];
        $image = $profileRow['image'];
        $bio = $profileRow['bio'];
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $firstName = $conn->real_escape_string($_POST['first_name']); 
        $lastName = $conn->real_escape_string($_POST['last_name']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $dob = $conn->real_escape_string($_POST['dob']);
        $bio = $conn->real_escape_string($_POST['bio']);

        if (isset($_FILES['image'])) {
            $imgName = $_FILES['image']['name'];
            $imgSize = $_FILES['image']['size'];
            $tmpName = $_FILES['image']['tmp_name'];
            $imgError = $_FILES['image']['error'];

            if ($imgError === 0) {
                if ($imgSize > 1048576) {
                    $imageError = "Sorry, your file is to large. <br>Maximum image size is 1mb.";
                } else {
                    $imgEx = pathinfo($imgName, PATHINFO_EXTENSION);
                    $imgExLc = strtolower($imgEx);
    
                    $allowedExs = array("png", "jpg", "jpeg");
    
                    if (in_array($imgExLc, $allowedExs)) {
                        $imageNewName = uniqid("IMG-", false) . '.' . $imgExLc;
                        $imgUploadPath = "images/" . $imageNewName;
                        move_uploaded_file($tmpName, $imgUploadPath);
                    } else {
                        $imageError = "Can't upload this type of file.";
                    }
                }
            } else {
                // header("Location: error.php?m=Upload error.");
            }
        }

        // Vrsimo validaciju polja
        $firstNameError = nameValidation($firstName); 
        $lastNameError = nameValidation($lastName);
        $genderError = genderValidation($gender);
        $dobError = dobValidation($dob);
        $imageError = imageValidation($image);



        // Ako je sve u redu, ubacujemo novi red u tabelu `profiles`
        if($firstNameError == "" && $lastNameError == "" && $genderError == "" && $dobError == "" && $imageError == ""){

            $q = "";
            if($profileRow === false){      // AKO NE POSTOJI PROFIL, UBACIVANJE NOVOG REDA U TABELU PROFILES

                $q = "INSERT INTO `profiles`(`first_name`, `last_name`, `gender`, `dob`, `id_user`, `image`, `bio`) VALUE 
                ('$firstName', '$lastName', '$gender', '$dob', $id, '$imageNewName', '$bio');";   

            } else{        // AKO POSTOJI PROFIL, MENJANJE POLJA U REDU U TABELI PROFILES

                $q = "UPDATE `profiles` SET 
                `first_name` = '$firstName',
                `last_name` = '$lastName',
                `gender` = '$gender',
                `dob` = '$dob',
                `image` = '$imageNewName',
                `bio` = '$bio'
                WHERE `id_user` = $id
                ";
            }  

            if($conn->query($q)){
                // Uspesno kreiran ili editovan profil
                if($profileRow !== false){
                    $sucMessage = "You have edited your profile";
                } else{
                    $sucMessage = "You have created your profile";
                }
            } else{
                // Desila se greska u upitu
                $errMessage = "Error creating profile: " . $conn->error;
            }
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php require_once "header.php"; ?>
    </header>
    <section class="back-profile">
        <div class="form-profile">
            <h1>Please fill the profile details</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="first_name">First name:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <input type="text" name="first_name" id="first_name" value="<?php echo $firstName ?>"> <br>
                        <span class="error">* <?php echo $firstNameError ?></span>
                    </div>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="last_name">Last name:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <input type="last_name" name="last_name" id="last_name" value="<?php echo $lastName ?>"> <br>
                        <span class="error">* <?php echo $lastNameError ?></span>
                    </div>
                </div>
                <div class="radio-profile">
                    <label for="gender">Gender:</label>
                    <input type="radio" name="gender" id="m" value="m" <?php if($gender == "m") {echo "checked";} ?>> Male
                    <input type="radio" name="gender" id="f" value="f" <?php if($gender == "f") {echo "checked";} ?>> Female
                    <input type="radio" name="gender" id="o" value="o" <?php if($gender == "o" || $gender == "") {echo "checked";} ?>> Other <br>
                    <span class="error"><?php echo $genderError; ?></span>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="dob">Date of birth:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <input type="date" name="dob" id="dob" value="<?php echo $dob; ?>"> <br>
                        <span class="error"><?php echo $dobError; ?></span>
                    </div>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="image">Select image:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <input type="file" name="image" id="image" accept="image/*"> <br>
                        <span class="error"><?php echo $imageError ?></span>
                    </div>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="bio">Biography:</label>
                    </div>
                    <div class="col-auto name-profile">
                        <textarea name="bio" id="bio"><?php echo $bio; ?></textarea><br>
                        <span class="error"><?php echo $bioError ?></span>
                    </div>
                </div>
                <div>
                    <?php 
                        $poruka;
                        if($profileRow === false){
                            $poruka = "Create profile";
                        } else{
                            $poruka = "Edit profile";
                        }
                    ?>
                    <input type="submit" class="submit" value="<?php echo $poruka ?>">
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