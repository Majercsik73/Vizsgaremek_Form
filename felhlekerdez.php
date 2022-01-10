<?php 
    
    include("dbconnect.php"); 

    //Felhasználó név lekérés ellenőrzéshez
    $sql = "SELECT nev FROM felhasznalo WHERE nev = ?";
    //$result2 = $db->query($sql2);
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_GET['q']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nev);
    $stmt->fetch();
    $stmt->close();
    /*if($result2->num_rows > 0){
        echo "<script>alert('A felhasználónév már létezik, adj meg egy másikat!')</script>";
        header("regisztracio.php");
    }*/



?>