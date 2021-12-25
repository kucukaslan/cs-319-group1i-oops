<?php
require_once(__DIR__."/../config.php");
require_once("UserFactory.php");
require_once("CustomException.php");

class PasswordResetHandler {
    const TABLE_NAME = 'password_reset_token';

    private PDO $conn;
    public function __construct(PDO $conn) {
        $this->conn = $conn;
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

    public function updatePassword($token, $newPassword) : bool {
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
                    return true;
                }
                else{
                    throw new TokenValidationTimePassedException();
                }
                // delete the token
                $sql= " delete from ".PasswordResetHandler::TABLE_NAME." where token = :token";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":token", $token);
                $stmt->execute();
            }
        } catch(Exception $e) {
            echo $e;
            return false;
        }
    }
}