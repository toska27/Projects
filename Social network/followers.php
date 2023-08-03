<?php
    session_start();
    if(empty($_SESSION['id'])){
        header("Location: index.php");    // Ne dozvoljavamo pristup ovoj stranici NE LOGOVANIM korisnicima
    }
    $id = $_SESSION["id"];     // UZIMA ID IZ TABELE USERS

    require_once "connection.php";

    if(isset($_GET['friend_id'])){   // KADA STISNEMO NA FOLLOW U TABELI DODJEMO OVDE GET METODOM, koga prati

        $friendId = $conn->real_escape_string($_GET['friend_id']);

        $q = "SELECT * FROM `followers` WHERE 
        `id_sender` = $id AND `id_receiver` = $friendId
        ";
        $result = $conn->query($q);
        if($result->num_rows == 0){
            $upit = "INSERT INTO `followers`(`id_sender`, `id_receiver`) VALUE  
            ($id, $friendId)";                                                   // DODAJEMO U TABELU FOLLOWERS KO JE KOGA ZAPRATIO
            $result1 = $conn->query($upit); 
        }
    }

    if(isset($_GET['unfriend_id'])){ // // KADA STISNEMO NA UNFOLLOW U TABELI DODJEMO OVDE GET METODOM, koga odprati
        $friendId = $conn->real_escape_string($_GET['unfriend_id']);
 
        $q = "DELETE FROM `followers`
        WHERE `id_sender` = $id AND `id_receiver` = $friendId";      // BRISEMO U TABELI FOLLOWERS KADA NEKO ODPRATI NEKOG
        $conn->query($q);
    }

    // Odredimo koje druge korisnike prati logovan korisnik
    $upit1 = "SELECT `id_receiver` FROM `followers` WHERE `id_sender` = $id";
    $res1 = $conn->query($upit1);
    $niz1 = [];
    while($row = $res1->fetch_array(MYSQLI_NUM)){
        $niz1[] = $row[0];                          // KADA IMAMO NIZ PISEMO OVO FETCH_ARRAY(MYSQLI_NUM)
    } 

    // Odrediti koji drugi korisnici prate logovanog korisnika
    $upit2 = "SELECT `id_sender` FROM `followers` WHERE `id_receiver` = $id";
    $res2 = $conn->query($upit2);
    $niz2 = [];
    while($row = $res2->fetch_array(MYSQLI_NUM)){
        $niz2[] = $row[0];
    } 

    $q = "SELECT `u`.`id`, `u`.`username`, CONCAT(`p`.`first_name`, ' ',  `p`.`last_name`) AS `full_name`, `p`.`image`, `p`.`gender` 
        FROM `users` AS `u` 
        LEFT JOIN `profiles` AS `p`
        ON `u`.`id` = `p`.`id_user`
        WHERE `u`.`id` != $id
        ORDER BY `full_name`;
        ";                                     // SELEKTUJEMO STA NAM SVE TREBA IZ TABELI USERS I PROFILES
    $result = $conn->query($q);
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members of Social Network</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>    
        <?php require_once "header.php"; ?>
    </header>
    <section class="back-followers">
        <div class="table-followers">
            <h1>See other members from our site</h1>
            
            <?php
            if($result->num_rows == 0){
            ?>
                <div class="error">No other users in database</div>
            <?php    
            } else{
                echo "<table class='table'> <thead class='table-dark'> <tr>";
                echo "<th scope='col'>Name</th>";
                echo "<th scope='col' colspan='2'>Action</th>";
                echo "<th scope='col'>Image</th>"; 
                echo "</tr> </thead> <tbody class='table-group-divider'>";
                while($row = $result->fetch_assoc()){
                    echo "<tr><td class='name-table'>";
                    if($row['full_name'] !== NULL){
                        $friendId = $row['id'];
                        $imePrezime = $row['full_name'];
                        echo "<a href='show_profile.php?id=$friendId' class='link-followers'>$imePrezime</a>";  // IZ TABELE PROFILI UZIMA IME I PREZIME
                    } else{
                        $friendId = $row['id'];
                        $userName = $row['username'];
                        echo "<a href='show_profile.php?id=$friendId' class='link-followers'>$userName</a>";
                           // IZ TABELE USERS UZIMA USERNAME, AKO NEMA IME I PREZIME U TABELI PROFILI
                    }
                    echo "</td><td colspan='2' class='action'>";

                    $friendId = $row['id'];   // IZ TABELE USERS ID

                    if(!in_array($friendId, $niz1)){     // proverava da li vrednost $friendId prisutna u $niz1
                        if(!in_array($friendId, $niz2)){
                            $text = "Follow";
                        } else{
                            $text = "Follow back";
                        }
                        echo "<a href='followers.php?friend_id=$friendId' class='change-color'>$text</a>";  // ODAVDE SE IDE NA ISTU STRANICU GET METODOM
                    } else{
                        echo "<a href='followers.php?unfriend_id=$friendId' class='change-color'>Unfollow</a>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if($row['image'] !== NULL){
                        echo "<img src='images/".$row['image']."' alt='image' class='profile-photo'>";
                    } else{
                        if($row['gender']=='m'){
                            echo "<img src='images/male.jpg' alt='avatar_m' class='profile-photo'>";
                        } elseif($row['gender']=='f') {
                            echo "<img src='images/female.jpg' alt='avatar_f' class='profile-photo'>";
                        } else{
                            echo "<img src='images/other.jpg' alt='avatar_o' class='profile-photo'>";
                        }
                    }
                    echo "</td>";
                }
                echo "</tr> </tbody> </table>"; 
            }
            
            ?>
        </div>
    </section>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>