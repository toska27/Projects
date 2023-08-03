<?php

    function usernameValidation($u, $c){                // VALIDACIJA USERNAME KOD REGISTROVANJA I LOGINA

        $query = "SELECT * FROM `users` WHERE `username` = '$u'";
        $result = $c->query($query);

        if(empty($u)){
            return "Username cannot be blank";
        } elseif(preg_match('/\s/', $u)){
            return "Username cannot contain spaces";
        } elseif(strlen($u)<5 || strlen($u)>25){
            return "Username must be between 5 and 25 characters";
        } elseif($result->num_rows>0){
            return "Username is reserved, please choose another one";
        } else{
            return "";
        }
    }

    function passwordValidation($u){              // VALIDACIJA SIFRE
        if(empty($u)){
            return "Password cannot be blank";
        } elseif(preg_match('/\s/', $u)){
            return "Password cannot contain spaces";
        } elseif(strlen($u)<5 || strlen($u)>50){
            return "Password must be between 5 and 50 characters";
        } else{
            return "";
        }
    }

    function nameValidation($n){                // VALIDACIJA IMENA KOD PROFILA
        $n = str_replace(' ', '', $n);
        if (empty($n))
        {
            return "Name cannot be empty";
        }
        elseif (strlen($n) > 50)
        {
            return "Name cannot contain more than 50 characters";
        }
        elseif (preg_match("/^[a-zA-ZŠšĐđŽžČčĆć]+$/", $n) == false)
        {
            return "Name must contain only letters";
        }
        else
        {
            return "";
        }
    }

    function genderValidation($g){                     // VALIDACIJA POLA
        if($g != "m" && $g != "f" && $g != "o"){
            return "Unknown gender";
        } else{
            return "";
        }
    }
 
    function dobValidation($d){           // VALIDACIJA GODINE RODJENJA
        if(empty($d)){
            return ""; // ok je da dob bude prazno
        } elseif($d < "1900-01-01"){
            return "Date of birth not valid";
        } else{
            return "";
        }
    }

    function imageValidation($image){                       // VALIDACIJA SLIKE
        $allowedExt = array('png', 'jpg', 'jpeg');
        $imgFileType = pathinfo($image, PATHINFO_EXTENSION);
        if(!in_array($imgFileType, $allowedExt)){
            return "Invalid extension";
        } else{
            return "";
        }
    }

    function contains($image, $default){        // ZA SLIKU
        foreach ($default as $value) {
            if (strpos($image, $value) !== false) {
                return true;
            }
        }
        return false;
    }

    function defaultImage($image, $gender){          // ZA SLIKU
        if ($gender == 'm') {
            $image = 'male.jpg';
        } elseif ($gender == 'f') {
            $image = 'female.jpg';
        } else {
            $image = 'other.jpg';
        }
        return $image;
    }

    function profileExists($id, $conn){                             // FUNKCIJA DA LI POSTOJI PROFIL
        $q = "SELECT * FROM `profiles` WHERE `id_user` = $id";
        $result = $conn->query($q);
        if ($result->num_rows == 0){
            return false;
        } else{
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    

?>