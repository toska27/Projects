<?php
    session_start();
    if(empty($_SESSION['id'])){
        header("Location: index.php");
    }
    $id = $_SESSION["id"];
    require_once "connection.php";

    $q = "SELECT `u`.`id`, `u`.`username`, CONCAT(`p`.`first_name`, ' ',  `p`.`last_name`) AS `full_name`, `p`.`image`, `p`.`gender` 
        FROM `users` AS `u` 
        LEFT JOIN `profiles` AS `p`
        ON `u`.`id` = `p`.`id_user`
        WHERE `u`.`id` != $id
        ORDER BY `full_name`;
        ";
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
                    echo "<tr><td>";
                    if($row['full_name'] !== NULL){
                        echo $row['full_name'];
                    } else{
                        echo $row['username'];
                    }
                    echo "</td><td colspan='2' class='action'>";
                    $friendId = $row['id'];
                    echo "<a href='follow.php?friend_id=$friendId' class='change-color'>Follow</a>";
                    echo "<a href='unfollow.php?friend_id=$friendId' class='change-color'>Unfollow</a>";
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