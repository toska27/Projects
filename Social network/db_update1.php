<?php
    require_once "connection.php";

    $sql = "ALTER TABLE `profiles` ADD `bio` TEXT;";

    if($conn->query($sql)){
        echo "Kolona bio uspesno dodata";
    } else{
        echo "Greska prilikom dodavanja kolone bio " . $conn->error;
    }



?>