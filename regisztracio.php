
<?php
    include("dbconnect.php");
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if(isset($_POST["nev"]) && isset($_POST["lak"]) && isset($_POST["tel"]) &&
            isset($_POST["email"]) && isset($_POST["pw1"]) && isset($_POST["pw2"]))
            {
                $nev = $_POST["nev"];
                $lak = $_POST["lak"];
                $tel = $_POST["tel"];
                $email = $_POST["email"];
                $pw1 = $_POST["pw1"];
                $pw2 = $_POST["pw2"];
                
                //Felhasználó név lekérés ellenőrzéshez
                $sql2 = "SELECT * FROM felhasznalo WHERE nev = '$nev'";
                $result2 = $db->query($sql2);
                
                //Email cím lekérés ellenőrzéshez
                $sql3 = "SELECT * FROM felhasznalo WHERE email = '$email'";
                $result3 = $db->query($sql3);

                //Itt megyünk végig a tényleges ellenőrzéseken
                // Név
                if($result2->num_rows > 0){
                    echo "<script>alert('A felhasználónév már létezik, adj meg egy másikat!')</script>";
                    header("regisztracio.php");
                }
                // Email cím
                elseif($result3->num_rows > 0){
                    echo "<script>alert('Ezzel az e-mail címmel már regisztráltak, adj meg egy másikat!')</script>";
                    header("regisztracio.php");
                }
                //Ha minden rendben, az új felhasználót felvesszük a db-be
                else{   
                    //Jelszó md5 hash
                    $hashpw = md5($pw1);
                    // Új felhasználó regisztálása
                    $sql4 = "INSERT INTO felhasznalo (azon, nev, lak, tel, email, jog, pw) VALUES
                    (null, '$nev','$lak','$tel','$email', 0 ,'$hashpw'); ";
                    
                    $request = $db->query($sql4);
                    
                    echo "<script>alert('Köszönjük a regisztrációt!')</script>";

                    header("Refresh:0");  //header("index.php");  // Ne ragadjonak be az adatok!!!!
                    echo "<script>location.href='index.php'</script>";
                }  
            }
    }
    
    //Lekérdezés

    $sql1 = "SELECT * FROM felhasznalo ORDER BY azon DESC LIMIT 4 ";
    $request = $db->query($sql1);
?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="reg.css"> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <title>Burgeretterem-Regisztráció</title>
    </head>
    <body>  
        <table>
            <thead style="font-weight:bold">
                <tr>
                    <td>Azonosító</td>
                    <td>Név</td>
                    <td>Lakhely</td>
                    <td>Telefonszám</td>
                    <td>E-mail cím</td>
                    <td>Jogosultság</td>
                    <td>Jelszó</td>
                </tr>
            </thead>
            <?php
                while ($sor = $request->fetch_assoc())
                    echo "  <tr>
                                <td>".$sor["azon"]."</td>
                                <td>".$sor["nev"]."</td>
                                <td>".$sor["lak"]."</td>
                                <td>".$sor["tel"]."</td>
                                <td>".$sor["email"]."</td>
                                <td>".$sor["jog"]."</td>
                                <td>".$sor["pw"]."</td>
                            </tr>";
            ?>
        </table>    
        <br />
        <form name="regist" method = "POST" action="" onsubmit="return formEllenorzes()">
            <table class = "ujfelhasznalo">
                <tr><td><strong>Regisztráció:</strong></td></tr>
                <!--<tr class="hidden">
                    <td>Azonosító:</td>
                    <td><input type="text" name="azon" value="55" ></td>
                </tr>-->
                <tr>
                    <td>Név:</td>
                    <td><input type="text" name="nev" id="nev" style="width: 240px;" placeholder="teljes név"></td>
                </tr>
                <tr>
                    <td>Lakhely:</td>
                    <td><input type="text" name="lak" id="lak" style="width: 240px;" placeholder="teljes lakcím"></td>
                </tr>
                <tr>
                    <td>Telefonszám:</td>
                    <td><input type="text" name="tel" id="tel" style="width: 240px;" placeholder="pl.:+36801111111"></td>
                </tr>
                <tr>
                    <td>E-mail cím:</td>
                    <td><input type="email" name="email" id="email" style="width: 240px;" placeholder="valami@valami.com"></td>
                </tr>
                <tr>
                    <td>Jelszó:</td>
                    <td><input type="password" name="pw1" id="pw1" style="width: 240px;"placeholder="min. 6 karakter; kibetű, nagybetű, szám"></td>
                </tr>
                <tr>
                    <td>Jelszó újra:</td>
                    <td><input type="password" name="pw2" id="pw2" style="width: 240px;" placeholder="jelszó megerősítése"></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Regisztrálok"></td>
                </tr>
            </table>
        </form>
    </body>
</html>
<script>
    //Felhasználó név lekérés ellenőrzéshez
    /*function nevLekerdezes(a){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
                            if(this.responseText != "" || this.resposeText != null){
                                alert("A megadott felhasználónévvel már van regisztráció! \n Kérem adjon másikat!");
                                return false;
                            }
                        }
        xhttp.open("GET", "felhlekerdez.php?q="+a);
        xhttp.send();
        alert("this.responseText +     + a +    JS");
    }*/
    function formEllenorzes() {
        // Névmező kitöltésének ellenőrzése
        let a = document.forms["regist"]["nev"].value;
        if (a == "" || a == null) {
            alert("Név megadása kötelező!");
            return false;
        }

        //A megadott felhasználónév AJAX lekérdezése a db-ből

        let nev = "";
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
                            nev = this.responseText;
                        }
        xhttp.open("GET", "felhlekerdez.php?q="+a);
        xhttp.send();
        // Majd a válasz ellenőrzése
        if (nev == "" || nev == null) {
            alert("A megadott felhasználónévvel már van regisztráció! \n Kérem adjon másikat!");
            return false;
        }
        
        // Lakcím ellenőrzése
        let b = document.forms["regist"]["lak"].value;
        if (b == "" || b == null) {
            alert("Lakcím megadása kötelező!");
            return false;
        }
        // Telefonszám ellenőrzés
        let c = document.forms["regist"]["tel"].value;
        if (c == "" || c == null) {
            alert("Telefonszám megadása kötelező!");
            return false;
        }
        // Telefonszám hossz ellenőrzés
        if (c.length < 10) {
            alert("Telefonszám túl rövid! Kérem használjon előhívó tagot és körzetszámot is!");
            return false;
        }
        // Telefonszám karakterellenőrzés
        let tel1 = c.match(/[A-z]/g);
        let tel2 = c.match(/[§'"!@#$%^&*]/g);
        if (tel1 != null || tel2 != null) {
            alert("Telefonszám csak '+' előtagot és számokat tartalmazhat!");
            return false;
        }
        // Email cím ellenőrzés
        let d = document.forms["regist"]["email"].value;
        if (d == "" || d == null) {
            alert("Email cím megadása kötelező!");
            return false;
        }
        // Jelszómezők ellenőrzése
        // Első mező
        let e = document.forms["regist"]["pw1"].value;
        if (e == "" || e == null) {
            alert("Az első jelszómező nincs kitöltve");
            return false;
        }
        //Második mező
        let f = document.forms["regist"]["pw2"].value;
        if(f == "" || f == null) {
            alert("A második jelszómező nincs kitöltve");
            return false;
        }
        //Jelszavak összehasonlítás
        if (e!=f) {
            alert("A két jelszómező nem egyezik meg! JS");
            return false;
        }
        // Jelszó hosszának ellenőrzése
        if (e.length < 6) {
            alert("A jelszónak minimum 6 karakter hosszúnak kell lennie! JS");
            return false;
        }
        // Jelszó karakterellenőrzés
        let jelszo1 = e.match(/[a-z]/g);
        let jelszo2 = e.match(/[A-Z]/g);
        let jelszo3 = e.match(/[0-9]/g);
        //alert(jelszo1 + "    " + jelszo2 + "     " + jelszo3);
        if (jelszo1 == null || jelszo2 == null || jelszo3 == null) {
            alert("A jelszónak tartalmaznia kell kisbetűt, nagybetűt és számot!  JS");
            return false;
        }
    }
</script>