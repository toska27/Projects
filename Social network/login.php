<?php
    // Cim treba nekako da koristimo sesiju, mora ova f-ja da se pozove
    session_start(); // Ova f-ja treba na pocetku (kao prva)  da se pozove

    if(isset($_SESSION["id"])){
        header("Location: index.php");            // Ne dozvoljavamo pristup ovoj stranici LOGOVANIM korisnicima
    }
    
    require_once "connection.php";

    $usernameError = "*";
    $passwordError = "*";
    $username = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Korisnik je poslao username i password i pokusava logovanje
        $username = $conn->real_escape_string($_POST["username"]);
        $password = $conn->real_escape_string($_POST["password"]);

        // Validacije
        if(empty($username)){
            $usernameError = "Username cannot be blank!";       // AKO JE PRAZNO POLJE USERNAME
        }

        if(empty($password)){
            $passwordError = "Password cannot be blank!";      // AKO JE PRAZNO POLJE PASSWORD
        }

        if($usernameError == "*" && $passwordError == "*"){   // IMA ZVEZDICA DA JE OBAVEZNO POLJE

            // Ovde mozemo da pokusamo da logujemo korisnika
            // (ako svi kredincijali za logovanje se podudaraju)
            $q = "SELECT * FROM `users` WHERE `username`='$username'"; // UZIMAMO CEO RED IZ TABELE USERS IZ BAZE ZA DAT USERNAME
            $result = $conn->query($q);        // REZULTAT OVOG SELECTA

            if($result->num_rows == 0){        // AKO NE POSTOJI NI JEDNO POLJE IZ SELECTA
                $usernameError = "This username doesn't exist!";
            } else{
                // Postoji takav USERNAME, proveriti lozinke

                $row = $result->fetch_assoc();  // VRACA ASOCIJATIVNI NIZ

                $dbPassword = $row["password"];         // LOZINKA IZ BAZE KOJA JE HESHIRANA
                if(!password_verify($password, $dbPassword)){       // VERIFIKACIJA LOZINKE IZ BAZE I NOVE LOZINKE

                    // Poklopili su se username, ali lozinka nije dobra
                    $passwordError = "Wrong password, try again!";

                } else{
                    // Dobri su i username i password, izvrsi logovanje

                    $_SESSION["id"] = $row["id"];            // DA BI ID BIO DOSTUPAN SVUDA GDE SE OTVORI SESSION, Kasnije, na drugim stranicama
                    $_SESSION["username"] = $row["username"]; // , možete pristupiti vrednosti "id" pomoću $_SESSION["id"].

                    header("Location: index.php");         // DA VRATI NA INDEX PHP
                }
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>        
        <?php require_once "header.php"; ?>
    </header>
    <section class="back-login">
        <div class="login">
            <div class="card mb-3" style="max-width: 560px;">
                <div class="row g-0">
                    <div class="col-md-4 picture-login">
                    <img src="img/picture_4.jpg" class="img-fluid rounded-start" alt="p4">
                    </div>
                    <div class="col-md-8">
                    <div class="card-body">
                        <form action="#" method="post" class="form-login">
                            <h1>Please login</h1>
                            <div>
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username" value="<?php echo $username; ?>">
                                <span class="error"><?php echo $usernameError; ?></span>
                            </div>
                            <div>
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password">
                                <span class="error"><?php echo $passwordError; ?></span>
                            </div>
                            <div>
                                <input type="submit" value="Login" class="submit">
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>