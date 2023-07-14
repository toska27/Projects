<?php
    if(isset($_SESSION["username"])){
        echo 
        "
        <div class='container'>
            <div class='row'>
                <div class='col-md-3'>
                    <ul class='list-unstyled'>
                    <li><a href='index.php'>Home</a></li>
                    </ul>
                </div>
                <div class='col-md-3'>
                    <ul class='list-unstyled'>
                    <li><a href='profile.php'>Profile</a></li>
                    </ul>
                </div>
                <div class='col-md-3'>
                    <ul class='list-unstyled'>
                    <li><a href='followers.php'>Connections</a></li>
                    </ul>
                </div>
                <div class='col-md-3'>
                    <ul class='list-unstyled'>
                    <li><a href='logout.php'>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>";
    } else{
        echo 
        "<div class='container'>
            <div class='row'>
                <div class='col-md-4'>
                    <ul class='list-unstyled'>
                        <li><a href='index.php'>Home</a></li>
                    </ul>
                </div>
                <div class='col-md-4'>
                    <ul class='list-unstyled'>
                        <li><a href='register.php'>Register</a></li>
                    </ul>
                </div>
                <div class='col-md-4 '>
                    <ul class='list-unstyled'>
                        <li><a href='login.php'>Login</a></li>
                    </ul>
                </div>
                </div>
        </div>
      ";
    }


 



?>