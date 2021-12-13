<?php
include_once ("Student.php");
class UserFactory{


    //variables
    protected ?User $user;


    //methods
    public function __construct(string $usertype) {
        if(strcmp($usertype, Student::TABLE_NAME) == 0)
            $this->user = new Student();
        else
            $this->user = new User();

    }




    public function makeUserByRegister($db, $id, $password,$firstname,$lastname,$email) : User
    {
        $this->user = new Student();

        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setPassword($password);
        $this->user->setFirstname($firstname);
        $this->user->setLastName($lastname);
        $this->user->setEmail($email);

        return $this->user;

        // todo insert to database: muh ekle.


    }

    // id session arrayde var.
    public function makeUserForHesCode($db,$id) : User{

        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);

        return $this->user;
    }


    public function makeUserByLogin($db, $id, $password) : User
    {

        //$this->user = new Student();

        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setPassword($password);

        echo "id : " . $id . "  $password ";

        $pwVerified = $this->user->verifyPassword();
        if (!$pwVerified) {
            throw new Exception("Password is incorrect.");
        }

        return $this->user;




        // todo insert to database: muh ekle.


    }


}
