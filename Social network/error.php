<?php
$poruka = "";
    if($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET['m'])) {  // AKO NIJE USPELO REGISTROVANJE (AKO NIJE IZVRSEN INSERT U TABELU USERS)
        $poruka = $_GET['m'];       // KUPIMO PODATKE IZ GET METODE
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ooops! An error occured!</h1>
    <div class="error">
        <?php echo $poruka; ?>
    </div>
    Return to <a href="index.php">home page</a>.
</body>
</html>