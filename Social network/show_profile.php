<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php

    require_once "connection.php";
    require_once "validation.php";
    session_start();

    if(empty($_SESSION['id'])){
        header("Location: index.php");    
    }

    if(isset($_GET['id'])){
        $id = $conn->real_escape_string($_GET['id']);
        $p = "SELECT * FROM `users` WHERE `id` = $id";
        $result1 = $conn->query($p);
        if($result1->num_rows == 0){
            return false;
        } else {
            $row1 = $result1->fetch_assoc();
            $username = $row1['username'];
        }
    }

    if(isset($_SESSION["username"])){                // da li je logovan korisnik

        $userName = $_SESSION["username"];
        $id2 = $_SESSION["id"];               // id logovanog korisnika iz tabele users

        $row2 = profileExists($id2, $conn);       // PROVERA DA LI IMA PROFILA AKO IMA PROFIL ROW JE TRUE
        if($row2 === false){
            // Mora da ima profil ako je na ovoj stranici 
        } else{
            // Profil korisnika koji je prijavljen
            $userName = $row2["first_name"] . " " . $row2["last_name"];  // OVAKO PRISTUPAMO PODACIMA IZ TABELE PROFIL
        }
    
    }

        $q = "SELECT * FROM `profiles` WHERE `id_user` = $id";
        $result = $conn->query($q);
    ?>
    
    <header>
    <?php require_once "header.php"; ?>
    </header>

    <div class="back-show">

        <div class='name_show'>
            <p class="animate__animated animate__rubberBand"><?php echo "Hello " . $userName; ?></p>
        </div>

        <section class="show">
            <div class="table-show">
                <?php 
                
                if($result->num_rows == 0){
                    echo "<p class='error'>User don't have profile</p>";
                } else{
                    $row = $result->fetch_assoc();
                    $name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $gender = $row['gender'];
                    $about = $row['bio'];
                    $dob = $row['dob'];

                    $color = '';
                    if($gender == 'm'){
                        $color = 'blue';
                    } elseif($gender == 'f'){
                        $color = 'red';
                    } else{
                        $color = 'black';
                    }

                    ?>
                    <table style="color: <?php echo $color; ?>">
                        <tr>
                            <td>First Name</td>
                            <td><?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <td>Last Name</td>
                            <td><?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td><?php echo $username; ?></td>
                        </tr>
                        <tr>
                            <td>Date of birth</td>
                            <td><?php echo $dob; ?></td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td><?php echo $gender; ?></td>
                        </tr>
                        <tr>
                            <td>About me</td>
                            <td><?php echo $about; ?></td>
                        </tr>
                    </table>
                <?php } ?>
            </div>
        
            <div class="link">
                <p class='animate__animated animate__backInUp'>Go to <a href='followers.php'>Followers</a></p>
            </div>
        </section>
    </div>

    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>