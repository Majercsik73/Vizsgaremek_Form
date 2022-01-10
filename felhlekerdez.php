<?php 
    //include("dbconnect.php");
    $mysqli = new mysqli("localhost", "root", "", "burgeretterem");
    if($mysqli->connect_error) {
        echo"<script>alert('Could not connect')</script>";
    }

    //Felhasználó név lekérés ellenőrzéshez
    $sql = "SELECT nev FROM felhasznalo WHERE nev = ?";
    //$result = $db->query($sql);
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_GET['q']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nev);
    $stmt->fetch();
    $stmt->close();
    echo $nev;
    /*if($result2->num_rows > 0){
        echo "Alert";
    }*/



?>