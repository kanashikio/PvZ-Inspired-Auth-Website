<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PVZ Log In</title>
    <link rel="stylesheet" href="main.css">
    <link rel="icon" href="img/ulo.png">
</head>
<body>
    <img src="img/plants.jpg" alt="" class="plants">

    <section id="reg2">
        <?php 
         function encrypt($text, $shift) {
            $encrypted_text = '';
            $shift = $shift % 94; 
        
            for ($i = 0; $i < strlen($text); $i++) {
                $char = $text[$i];
                $ascii = ord($char);
        
                // Only shift printable ASCII characters
                if ($ascii >= 32 && $ascii <= 126) {
                    $new_ascii = $ascii + $shift;
                    if ($new_ascii > 126) {
                        $new_ascii = 32 + ($new_ascii - 127);
                    }
                    $encrypted_text .= chr($new_ascii);
                } else {
                    $encrypted_text .= $char;
                }
            }
        
            return $encrypted_text;
         }
         
         include("php/conf.php");
         
         if (isset($_POST['submit'])) {
             $player_name = mysqli_real_escape_string($con, $_POST['player_name']);
             $password = mysqli_real_escape_string($con, $_POST['password']);
         
             $shift = 9;
         
             // Encrypt input player name and password to compare with stored values
             $encrypted_player_name = encrypt($player_name, $shift);
             $encrypted_password = encrypt($password, $shift);
         
             // Fetch the stored encrypted credentials
             $result = mysqli_query($con, "SELECT * FROM users WHERE player_name='$encrypted_player_name'") or die("Select Error");
             $row = mysqli_fetch_assoc($result);
         
             if ($row) {
                 $stored_encrypted_password = $row['password'];
         
                 // Check if the encrypted passwords match
                 if ($stored_encrypted_password === $encrypted_password) {
                     $_SESSION['valid'] = $row['player_name'];
                     $_SESSION['first_name'] = $row['first_name'];
                     $_SESSION['last_name'] = $row['last_name'];
                     $_SESSION['age'] = $row['age'];
                     $_SESSION['email'] = $row['email'];
                     header("Location: home.php");
                     exit();
                 } else {
                     echo "<div class='message'>
                             <p>Wrong Player Name or Password</p>
                           </div> <br><br>";
                     echo "<a href='login.php'><button class='btn'>Go Back</button></a>";
                 }
             } else {
                 echo "<div class='message'>
                         <p>Wrong Player Name or Password</p>
                       </div> <br><br>";
                 echo "<a href='login.php'><button class='btn'>Go Back</button></a>";
             }
         } else {
        ?>
        <form action="" method="post"> <!-- POST & GET -->
            <header>Plants vs. Zombies</header>
            <p class="acc">Account Log In</p>
  
            <div id="container">
                <div class="username">
                    <label for="player_name" class="note2">Player Name</label><br>
                    <input type="text" name="player_name" id="player_name" placeholder="Player Name" required>
                </div><br>
                <div class="pw">
                    <label for="password" class="note2">Password</label><br>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>

                <div class="login">
                    <button type="submit" name="submit">Log In</button>
                </div>

                <div class="noacc">
                    <p>Don't have an account yet? <a href="reg.php">Create an account now!</a></p>
                </div>
            </div>
        </form>
        <?php } ?>
    </section>
</body>
</html>
