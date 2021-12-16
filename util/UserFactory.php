<?php
require_once ("Student.php");
require_once ("AcademicStaff.php");
require_once ("SportsCenterStaff.php");
require_once ("UniversityAdministration.php");

class UserFactory{


    //variables
    protected ?User $user;


    //methods
    private function makeUser(string $usertype)  {
        if(strcmp($usertype, Student::TABLE_NAME) == 0)
            $this->user = new Student();
        else if(strcmp($usertype, AcademicStaff::TABLE_NAME) == 0)
            $this->user = new AcademicStaff();
        else if(strcmp($usertype, UniversityAdministration::TABLE_NAME) == 0)
            $this->user = new UniversityAdministration();

        else if(strcmp($usertype, SportsCenterStaff::TABLE_NAME) == 0)
            $this->user = new SportsCenterStaff();
        else
            throw(new Exception("User type not found"));

    }


    public function makeUserByRegister(PDO $db, string $usertype, int $id, string|int $password, string $firstname, string $lastname,string $email) : User
    {
        $this->makeUser($usertype);
        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setPassword($password);
        $this->user->setFirstname($firstname);
        $this->user->setLastName($lastname);
        $this->user->setEmail($email);

        return $this->user;
    }

    // id session arrayde var.
    public function makeUserForHesCode(PDO $db, string $usertype, int $id) : User{
        $this->makeUser($usertype);

        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);

        return $this->user;
    }


    public function makeUserByLogin(PDO $db, string $usertype, int $id, string|int $password) : User
    {
        $this->makeUser($usertype);

        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setPassword($password);

        echo "id : " . $id . "  $password ";

        $pwVerified = $this->user->verifyPassword();
        if (!$pwVerified) {
            throw new Exception("Password is incorrect.");
        }

        return $this->user;
    }

    public function makeUserById(PDO $db, string $usertype, int $id) : User
    {
        $this->makeUser($usertype);

        $this->user->setId($id);
        $this->user->setDatabaseConnection($db);
        try {
            $query = "SELECT * FROM ".$this->user->getTableName()." WHERE id = :id "; // . $this->id;
            $stmt = $db->prepare($query);
            $stmt->execute(array('id'=>$this->user->getId()));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->user->setFirstname($row['name']);
            $this->user->setLastname($row['lastname']);
            $this->user->setEmail($row['email']);
            $this->user->setHESCode($row['hescode']);

        } catch (PDOException $exception) {

        }
        return $this->user;
    }
}
