<?php
require_once(__DIR__."/../config.php");
require_once(rootDirectory() . '/vendor/autoload.php');
require_once("UserFactory.php");
require_once("CustomException.php");

use League\OAuth2\Client\Provider\Google;

class PasswordResetHandler {

    const TABLE_NAME = 'password_reset_token';
    private string $domain;
    private PDO $conn;
    public function __construct(PDO $conn, string $domain = "https://forthyhealth.duckdns.org") {
        $this->conn = $conn;
        $this->domain = $domain;
    }

    public function sendPasswordResetEmail(User $user) : bool {
        $token = $this->generateTokenForUser($user->getId());
        
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = 'ThatIsagoalforitsownsake@gmail.com';
        $mail->Password = 'StackPop';
        $mail->setFrom('ThatIsagoalforitsownsake@gmail.com', 'ForThyHealth');
        $mail->addAddress(/*$_SESSION['email']*/ $user->getEmail(), $user->getFirstName() . ' ' .$user->getLastName());
        $mail->Subject = 'Password Reset Request';
        $mail->Body =  'Dear '.$user->getFirstName().' '.$user->getLastName().",\n";
        $mail->Body .= "You have requested a password reset. Please click the link below to a browser to reset your password.\n";
        $mail->Body .=  $this->domain.'/forgot/?pwr=' . $token . "\n";
        $mail->Body .= "If you did not request a password reset, please ignore this email.\n";
        $mail->Body .= "Thank you,\n\n";
        $mail->Body .= "~ ForThyHealth\n";

        return $mail->send();
        


    }
    public function generateTokenForUser(int $user_id) : string {
        $crypt_safe_rand_int = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $hash_of_rand_int = hash("sha256", $crypt_safe_rand_int);

        $sql = "INSERT INTO ".PasswordResetHandler::TABLE_NAME." (user_id, token) VALUES (:user_id, :token) on duplicate key update token = :token, generation_date=now()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":token", $hash_of_rand_int);
        $stmt->execute();
        return $hash_of_rand_int;
    }


    public function verifyExistenceOfToken($token) : bool {
        $sql = "select * from ".PasswordResetHandler::TABLE_NAME." where token = :token";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->rowCount()>0;
    }

    public function updatePassword(string $token, string|int $newPassword) : bool {
        try {
            // update password
            $sql = "select * from ".PasswordResetHandler::TABLE_NAME." where token = :token";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":token", $token);
            $stmt->execute();
            if($stmt->rowCount()>0) {  

                $result = $stmt->fetch();
                $date = new DateTime($result ['generation_date']);
                if($date->diff(new DateTime("now"))->format('%R%a') <= 7) {
                    $user_id =  $result["user_id"];
                    $uf = new UserFactory();
                    $user = $uf->makeUserById($this->conn, id:$user_id);
                    $user->updatePassword($newPassword);
                    
                    // delete the token
                    $sql= " delete from ".PasswordResetHandler::TABLE_NAME." where token = :token";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(":token", $token);
                    $stmt->execute();
                    return true;
                }
                else{
                    
                    // delete the token
                    $sql= " delete from ".PasswordResetHandler::TABLE_NAME." where token = :token";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(":token", $token);
                    $stmt->execute();
                    throw new TokenValidationTimePassedException();
                }
            }
        } catch(Exception $e) {
            echo $e;
            return false;
        }
    }
}