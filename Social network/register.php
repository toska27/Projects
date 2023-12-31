<?php
    
    session_start();
    if(isset($_SESSION["id"])){            // Ne dozvoljavamo pristup ovoj stranici LOGOVANIM korisnicima
        header("Location: index.php");
    }

    require_once "connection.php";
    require_once "validation.php";

    $usernameError = "";
    $passwordError = "";
    $retypeError = "";
    $username = $password = $retype = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){       // forma je poslata (kada stisnemo dugme register) treba pokupiti vrednosti iz polja

        $username = $conn->real_escape_string($_POST["username"]);  // OVAKO KUPIMO PODATKE O POLJU KADA DODJEMO POST METODOM
        $password = $conn->real_escape_string($_POST["password"]);
        $retype = $conn->real_escape_string($_POST["retype"]);

        // 1) izvrsiti validaciju za username
        $usernameError = usernameValidation($username, $conn);

        // 2) izvrsiti validaciju za password
        $passwordError = passwordValidation($password);

        // 3) izvrsiti validaciju za retype
        $retypeError = passwordValidation($retype);
        if($password !== $retype){
            $retypeError = "You must enter to same passwords";
        }


        // 4.1) Ako su sva polja validna, onda treba dodati novog korisnika
        // (treba izvrsiti INSERT upit nad tabelom users)
        if($usernameError == "" && $passwordError == "" && $retypeError==""){
            // lozinka treba prvo da se sifruje
            $hash = password_hash($password, PASSWORD_DEFAULT);           // OVAKO SE HESHUJE LOZINKA

            $q = "INSERT INTO `users`(`username`, `password`) VALUE       
            ('$username', '$hash');";                                     // INSERTOVANJE VREDNOSTI U KOLONE USERNAME I LOZINKE U TABELU USERS

            if($conn->query($q)){             // AKO JE PROSAO INSERT 
                header("Location: index.php?p=ok");   // Kreirali smo novog korisnika, vodi ga na stranicu za logovanje
            } else{
                header("Location: error.php?" . http_build_query(['m' => "Greska kod kreiranja usera"])); // GRESKA KOD INSERTOVANJA
            }
        }

        // 4.2) Ako postoji neko polje koje nije validno, ne raditi upit
        // nego vratiti korisnika na stranicu i prikazati poruke
        // OVAKO OSTAVLJENO VRACA KORISNIKA I PRIKAZUJE PORUKE
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register new user</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>    
        <?php require_once "header.php"; ?>
    </header>
    <section class="back-register">
        <div class="register">
            <h1 class="register_h">Register to our site</h1>
            <img src="img/picture_5.jpg" class="card-img-top" alt="p5">
            <form action="register.php" method="POST">
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" value="<?php echo $username ?>"> <br>
                        <span class="error">* <?php echo $usernameError; ?></span> 
                    </div>
                    <div class="col-auto">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" value=""> <br>
                        <span class="error">* <?php echo $passwordError; ?></span>
                    </div>
                    <div class="col-auto">
                        <label for="retype">Retype password:</label>
                        <input type="password" name="retype" id="retype" value=""> <br>
                        <span class="error">* <?php echo $retypeError; ?></span>
                    </div>
                    <div class="col-auto">
                        <input type="submit" value="Register me!" class="submit">
                    </div>
                </div>
            </form>
        </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>