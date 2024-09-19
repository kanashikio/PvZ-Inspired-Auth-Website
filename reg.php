<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PVZ Registration</title>
    <link rel="stylesheet" href="main.css">
    <script src="main.js"></script>
    <link rel="icon" href="img/ulo.png">
</head>
<body>
    <img src="img/balloon.jpg" alt="" class="brand">
    <section id="reg">

        <?php 
         function encrypt($text, $shift) {
            $encrypted_text = '';
            $shift = $shift % 94; // within the range of printable ASCII characters
        
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
        
        if (isset($_POST['submit'])) {
            include("php/conf.php");
        
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $age = $_POST['age'];
            $player_name = $_POST['player_name'];
            $password = $_POST['password'];
        
            $shift = 9; 
        
            // Encrypt player name and password
            $encrypted_player_name = encrypt($player_name, $shift);
            $encrypted_password = encrypt($password, $shift);
        
            $verify_query = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");
            if (mysqli_num_rows($verify_query) != 0) {
                echo "<div class='message'>
                          <p>This email is used, Try another One Please!</p>
                      </div> <br><br>";
                echo "<a href='reg.php'><button class='btn'>Go Back</button></a>";
            } else {
                mysqli_query($con, "INSERT INTO users (first_name, last_name, email, age, player_name, password) VALUES ('$first_name', '$last_name', '$email', '$age', '$encrypted_player_name', '$encrypted_password')") or die("Error Occurred");
        
                echo "<div class='message'>
                          <p>Registration successful!</p>
                      </div> <br>";
                echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
            }
        } else {
        ?>

    <form action="" method="post">
        <h1>Plants vs. Zombies</h1>
        <p class="reg">Account Registration</p>
  
        <div id="container">
            <div id="personal">
                <div class="name">
                    <label for="#" class="note">Full Name</label><br>
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
            </div>

            <div id="info">
                <div class="email">
                    <label for="#" class="note">E-mail</label><br>
                    <input type="email" name="email" placeholder="...@gmail.com" required>
                </div>

                <div class="age">
                    <label for="" class="note">Age</label><br>
                    <input type="number" name="age" placeholder="Age" required>
                </div>
            </div>

            <div id="create">
                <label for="" class="note">Create Player Name</label><br>
                <input type="text" name="player_name" placeholder="Player Name" required>     
            </div>

            <div id="passw">
                <label for="" class="note">Create Password</label><br>
                <input type="password" name="password" placeholder="Password (should be atleast 8 characters)" pattern=".{8,}" required>     
            </div>

            <div class="submit">
                <button type="submit" name="submit">Register</button>
            </div>

            <div class="hvacc">
                <p>Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </div>
    </form>
    <?php } ?>
    </section>
</body>
</html>
