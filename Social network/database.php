<?php


        // FAJL ZA KREIRANJE NOVIH TABELA

    require_once "connection.php";

    $sql = " CREATE TABLE IF NOT EXISTS `users`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        PRIMARY KEY(`id`)
    ) ENGINE=InnoDB; ";

    $sql .= " CREATE TABLE IF NOT EXISTS `profiles`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `first_name` VARCHAR(255) NOT NULL,
        `last_name` VARCHAR(255) NOT NULL,
        `gender` CHAR(1),
        `dob` DATE,
        `id_user` INT UNSIGNED NOT NULL UNIQUE,
        `image` VARCHAR(255)
        PRIMARY KEY(`id`),
        FOREIGN KEY(`id_user`) REFERENCES `users`(`id`)
            ON UPDATE CASCADE ON DELETE NO ACTION   
    ) ENGINE=InnoDB; ";

    $sql .= "CREATE TABLE IF NOT EXISTS `followers`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `id_sender` INT UNSIGNED NOT NULL,
        `id_receiver` INT UNSIGNED NOT NULL,
        PRIMARY KEY(`id`),
        FOREIGN KEY(`id_sender`) REFERENCES `users`(`id`)
            ON UPDATE CASCADE ON DELETE NO ACTION,
        FOREIGN KEY(`id_receiver`) REFERENCES `users`(`id`)
            ON UPDATE CASCADE ON DELETE NO ACTION
    ) ENGINE=InnoDB; ";

    if($conn->multi_query($sql)) {          // MULTI_QUERY JER KREIRAMO VISE TABELA
        echo "<p>Tables created successfully</p>";
    } else{
        header("Location: error.php?m=" . $conn->error);
    }

?>