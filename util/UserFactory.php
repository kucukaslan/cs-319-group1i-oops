<?php

class UserFactory{


    //variables
    protected User $user;


    //methods
    public function __construct() {
        $user = new User();

    }




    public function makeUserByRegister($db, $id, $password,$firstname,$lastname,$email) : User
    {
        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setPassword($password);
        $this->user->setFirstname($firstname);
        $this->user->setLastName($lastname);
        $this->user->setEmail($email);

        return $this->user;

        // todo insert to database: muh ekle.


    }


    public function makeUserByLogin($db, $id, $password) : User
    {
        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setPassword($password);


        $pwVerified = $this->user->verifyPassword();
        if (!$pwVerified) {
            throw new Exception("Password is incorrect.");
        }

        return $this->user;




        // todo insert to database: muh ekle.


    }


}
