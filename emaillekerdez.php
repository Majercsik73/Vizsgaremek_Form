<?php 
    
    $mysqli = new mysqli("localhost", "root", "", "burgeretterem");
    if($mysqli->connect_error) {
        echo"<script>alert('Could not connect')</script>";
    }

    //Felhasználó név lekérés ellenőrzéshez
    $sql = "SELECT email FROM felhasznalo WHERE email = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_GET['q']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();
    echo $email;
?>