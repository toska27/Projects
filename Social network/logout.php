<?php
    session_start();
    session_unset();   // $_SESSION = array(); 
    session_destroy();  // OVAKO SE ODJAVLJUJEMO KADA UNISTIMO SESIJU

    header("Location: index.php");  // DA VRATI NA INDEX.PHP

?>