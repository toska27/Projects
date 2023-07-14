<?php
    require_once "connection.php";
    require_once "validation.php";
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }

    $id = $_SESSION['id'];
    $firstName = $lastName = $dob = $gender = $image =  "";
    $firstNameError = $lastNameError = $dobError = $genderError = $imageError = "";

    $sucMessage = "";
    $errMessage = "";

    $profileRow = profileExists($id, $conn);
    // $profileRow = false, ako profil ne postoji
    // $profileRow = asocijativni niz, ako profil postoji

    $default = array('male','female','other');

    if($profileRow !== false){
        $firstName = $profileRow['first_name'];
        $lastName = $profileRow['last_name'];
        $gender = $profileRow['gender'];
        $dob = $profileRow['dob'];
        $image = $profileRow['image'];
    }

    /* -- Dodaje slike, ali izbaca i gresku
    if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $temp_name = $_FILES["image"]["tmp_name"];
        $image_name = $_FILES["image"]["name"];
        $destination = "images/" . $image_name;
        
        if (move_uploaded_file($temp_name, $destination)) {
            $image = $image;
        } else {
            $imageError = "Error uploading image";
        }
    }
    */

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $firstName = $conn->real_escape_string($_POST['first_name']); // umesto post ide ovo - $_FILES["image"]["name"]
        $lastName = $conn->real_escape_string($_POST['last_name']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $dob = $conn->real_escape_string($_POST['dob']);

        if(strlen($conn->real_escape_string($_POST['image'])) > 0)
        {
            $image = $conn->real_escape_string($_POST['image']);
            } elseif(strlen($image) > 0){ 
                if(!contains($image, $default)){
                    $image = $image;
                } else{
                    $image = defaultImage($image, $gender);
                }
        } else{
            $image = defaultImage($image, $gender);
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
            if($profileRow === false){

                $q = "INSERT INTO `profiles`(`first_name`, `last_name`, `gender`, `dob`, `id_user`, `image`) VALUE 
                ('$firstName', '$lastName', '$gender', '$dob', $id, '$image');";

            } else{

                $q = "UPDATE `profiles` SET 
                `first_name` = '$firstName',
                `last_name` = '$lastName',
                `gender` = '$gender',
                `dob` = '$dob',
                `image` = '$image'
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
            <form action="#" method="post"> <!-- enctype="multipart/form-data"  ovo u post-->
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