<?php 
session_start();
include("php/conf.php");
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PVZ Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="icon" href="img/ulo.png">
</head>
<body>
    <img src="img/hand.png" alt="" class="hand">
    <!-- <img src="img/vine.png" alt="" class="dave"> -->

    <div id="nav">
        <div class="logo">
            <img src="img/brain.png" alt="" class="icon">
            <p>Plants vs. Zombies</p>
        </div>

        <?php 
        function encrypt($text, $shift) {
            $encrypted_text = '';
            $shift = $shift % 94; // Ensure the shift is within the range of printable ASCII characters
        
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
            $shift = $shift % 94; //within the range of printable ASCII characters
        
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
        
        if (!isset($_SESSION['valid'])) {
            header("Location: login.php");
            exit();
        }
        
        $player_name = $_SESSION['valid'];
        
        // Fetch user data from the database
        $query = mysqli_query($con, "SELECT * FROM users WHERE player_name='$player_name'");
        
        // Fetch result as an associative array
        $result = mysqli_fetch_assoc($query);
        if ($result) {
            $res_Fname = $result['first_name'];
            $res_Lname = $result['last_name'];
            $res_Email = $result['email'];
            $res_Age = $result['age'];
            $res_encrypted_player_name = $result['player_name'];
        
            // Decrypt the player name
            $shift = 9;
            $res_player_name = decrypt($res_encrypted_player_name, $shift);
        } else {
            echo "User not found.";
            exit();
        }
        ?>

        <div class="link">
            <a href="update.php"><button class="up">Update Profile</button></a>
            <a href="confirm.php"><button>Log Out</button></a>
        </div>
    </div>

    <main>
        <div class="mainbox">
            <div class="top">
                <div class="bx">
                    <p>Welcome to Plants vs. Zombies, <b><?php echo htmlspecialchars($res_player_name); ?></b>!</p>
                </div>

                <div class="bx">
                    <p>Your age: <b><?php echo htmlspecialchars($res_Age); ?></b></p>
                </div>
            </div>

            <div class="bottom">
                <div class="bx">
                    <p>Player Name: <b><?php echo htmlspecialchars($res_player_name); ?></b></p>
                </div>

                <div class="bx">
                    <p>Full Name: <b><?php echo htmlspecialchars($res_Fname) . ' ' . htmlspecialchars($res_Lname); ?></b></p>
                </div>

                <div class="bx">
                    <p>E-mail: <b><?php echo htmlspecialchars($res_Email); ?></b></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
