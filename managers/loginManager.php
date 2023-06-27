<?php
    require "include/PHPMailer-master/src/PHPMailer.php";
    require "include/PHPMailer-master/src/SMTP.php";
    require "include/PHPMailer-master/src/Exception.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class loginManager{
        public static function loginCheck(){
            if(! $_SESSION["user_id"]){
                header("location:login");
            }
        }
        public static function getVerifyCode(){
            $verify_code = rand(100000,999999);
            return $verify_code;
        }
        public static function sendVerifyMail($aToEmailAdress,$aUsername, $aCode){

            $mail = new PHPMailer(true);

            $body = 
"<div>
    <h1 style='color:#D2042D;'>Beste $aUsername,</h1>
    <h2 style='color:#D2042D;'>Uw vertificatie code: <span style='color:#28282B;'>$aCode</span></h2>
    <p style='color:#D2042D;'>Zodra u uw email heeft geverifieerd dan hoeft u dit niet nogmaals te doen.</p>
    <p>Door <a href='#'>Dr-Ho's circulation promoter F1 group project</a></p>
</div>";

            try {
                $mail->isSMTP();
                $mail->Host = "smtp.strato.com";
                $mail->SMTPAuth = true;
                $mail->Username   = 'student@computercampus.nl';
                $mail->Password   = 'Sp@mmenmagniet!';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                //Recipients
                $mail->setFrom('no-reply@ictcampusF1.nl', 'Formule 1');

                $mail->addAddress($aToEmailAdress);

                //Content
                $mail->isHTML(true);
                $mail->Subject = "Email vertificatie";
                $mail->Body    = $body;

                $mail->send();
               
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        public static function selectUserLogin($email){
            global $con;

            $query = "SELECT * ";
            $query .= "FROM user ";
            $query .= "WHERE LOWER(email) = ? ";

            $stmt=$con->prepare($query);
            $stmt->bindValue(1, $email);
            $stmt->execute();

            return $stmt->fetchObject();
        }
        public static function selectUsernameInsert($username){
            global $con;

            $query = "SELECT * ";
            $query .= "FROM user ";
            $query .= "WHERE LOWER(username) = ? ";

            $stmt=$con->prepare($query);
            $stmt->bindValue(1, $username);
            $stmt->execute();

            return $stmt->fetchObject();
        }
        public static function selectMailInsert($email){
            global $con;

            $query = "SELECT * ";
            $query .= "FROM user ";
            $query .= "WHERE LOWER(email) = ? ";

            $stmt=$con->prepare($query);
            $stmt->bindValue(1, $email);
            $stmt->execute();

            return $stmt->fetchObject();
        }
        public static function insert($username, $firstname, $lastname, $email, $nHashedPassword){
            global $con;

            $points = 0;
            $profile_picture = "pictures/user_profile.png";
            $isAdmin = 0;

            $password = password_hash($nHashedPassword, PASSWORD_DEFAULT);

            $query = "INSERT INTO ";
            $query .= "user (username, firstname, lastname, email, password, total_points, profile_picture, is_admin) ";
            $query .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";

            $stmt=$con->prepare($query);
            $stmt->bindValue(1, htmlspecialchars($username));
            $stmt->bindValue(2, htmlspecialchars($firstname));
            $stmt->bindValue(3, htmlspecialchars($lastname));
            $stmt->bindValue(4, htmlspecialchars($email));
            $stmt->bindValue(5, $password);
            $stmt->bindValue(6, $points);
            $stmt->bindValue(7, $profile_picture);
            $stmt->bindValue(8, $isAdmin);
            
            $stmt->execute();
        }
        public static function inserttest(){
            global $con;

            $username = "test";
            $firstname = "test";
            $lastname = "test";
            $email = "test@test.nl";
            $nHashedPassword = "testtest";
            $points = 0;
            $profile_picture = "pfp/user_profile.png";
            $isAdmin = 0;

            $password = password_hash($nHashedPassword, PASSWORD_DEFAULT);

            $query = "INSERT INTO ";
            $query .= "user (username, firstname, lastname, email, password, total_points, profile_picture, is_admin) ";
            $query .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";

            $stmt=$con->prepare($query);
            $stmt->bindValue(1, $username);
            $stmt->bindValue(2, $firstname);
            $stmt->bindValue(3, $lastname);
            $stmt->bindValue(4, $email);
            $stmt->bindValue(5, $password);
            $stmt->bindValue(6, $points);
            $stmt->bindValue(7, $profile_picture);
            $stmt->bindValue(8, $isAdmin);
            
            $stmt->execute();
        }
    }
?>