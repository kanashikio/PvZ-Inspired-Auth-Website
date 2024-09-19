<?php 
session_start();
include("php/conf.php");
if(!isset($_SESSION['valid'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PVZ Update Profile</title>
    <link rel="stylesheet" href="main.css">
    <link rel="icon" href="img/ulo.png">
</head>
<body>
    <img src="img/jj.jpg" alt="" class="balloon">
    <section id="update">
        <?php
        function encrypt($text, $shift) {
            $encrypted_text = '';
            $shift = $shift % 94; // the range of printable ASCII characters
        
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
         
         function decrypt($text, $shift) {
            $decrypted_text = '';
            $shift = $shift % 94; // Ensure the shift is within the range of printable ASCII characters
        
            for ($i = 0; $i < strlen($text); $i++) {
                $char = $text[$i];
                $ascii = ord($char);
        
                // Only shift printable ASCII characters
                if ($ascii >= 32 && $ascii <= 126) {
                    $new_ascii = $ascii - $shift;
                    if ($new_ascii < 32) {
                        $new_ascii = 127 - (32 - $new_ascii);
                    }
                    $decrypted_text .= chr($new_ascii);
                } else {
                    $decrypted_text .= $char;
                }
            }
        
            return $decrypted_text;
         }
        
        if(!isset($_SESSION['valid'])){
            header("Location: login.php");
            exit();
        }
        
        if(isset($_POST['submit'])){
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $age = $_POST['age'];
            $new_player_name = $_POST['player_name'];
            $password = $_POST['password'];
        
            $current_player_name = $_SESSION['valid'];
            
            // Encrypt the new username and password before updating
            $shift = 9;
            $encrypted_new_player_name = encrypt($new_player_name, $shift);
            $encrypted_password = encrypt($password, $shift);
        
            $edit_query = mysqli_query($con, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', age='$age', player_name='$encrypted_new_player_name', password='$encrypted_password' WHERE player_name='$current_player_name'") or die("Error occurred");
        
            if($edit_query){
                $_SESSION['valid'] = $encrypted_new_player_name;
                echo "<div class='message'>
                        <p>Profile Updated!</p>
                      </div> <br>";
                echo "<a href='home.php'><button class='btn'>Go Home</button></a>";
            }
        } else {
            $current_player_name = $_SESSION['valid'];
            $query = mysqli_query($con, "SELECT * FROM users WHERE player_name='$current_player_name'");
        
            $result = mysqli_fetch_assoc($query);
            if ($result) {
                $res_Fname = $result['first_name'];
                $res_Lname = $result['last_name'];
                $res_Email = $result['email'];
                $res_Age = $result['age'];
                $res_encrypted_player_name = $result['player_name'];
                $res_encrypted_password = $result['password'];
        
                // Decrypt the player name and password
                $shift = 9;
                $res_player_name = decrypt($res_encrypted_player_name, $shift);
                $res_password = decrypt($res_encrypted_password, $shift);
            }
        ?>
        <form action="" method="post">
            <h1>Plants vs. Zombies</h1>
            <p class="update">Update Profile</p>
            <div id="container">
                <div id="personal">
                    <div class="name">
                        <label for="#" class="note">Full Name</label><br>
                        <input type="text" name="first_name" placeholder="First Name" value="<?php echo $res_Fname; ?>" required>
                        <input type="text" name="last_name" placeholder="Last Name" value="<?php echo $res_Lname; ?>" required>
                    </div>
                </div>
                <div id="info">
                    <div class="email">
                        <label for="#" class="note">E-mail</label><br>
                        <input type="email" name="email" placeholder="...@gmail.com" value="<?php echo $res_Email; ?>" required>
                    </div>
                    <div class="age">
                        <label for="" class="note">Age</label><br>
                        <input type="number" name="age" placeholder="Age" value="<?php echo $res_Age; ?>" required>
                    </div>
                </div>
                <div id="create">
                    <label for="" class="note">New Player Name</label><br>
                    <input type="text" name="player_name" placeholder="Player Name" value="<?php echo $res_player_name; ?>" required>     
                </div>
                <div id="passw">
                    <label for="" class="note">New Password</label><br>
                    <input type="password" name="password" placeholder="Password" value="<?php echo $res_password; ?>" pattern=".{8,}" required>     
                </div>
                <div class="submit">
                    <button type="submit" name="submit">Update</button>
                </div>
                <div class="hvacc">
                    <p>Cancel progress? <a href="home.php">Cancel</a></p>
                </div>
            </div>
        </form>
        <?php } ?>
    </section>
</body>
</html>
