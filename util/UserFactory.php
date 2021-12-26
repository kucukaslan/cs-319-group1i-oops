<?php
require_once("Student.php");
require_once("AcademicStaff.php");
require_once("SportsCenterStaff.php");
require_once("UniversityAdministration.php");
require_once("CustomException.php");

class UserFactory
{
    //variables
    protected ?User $user;

    //methods

    // ! does not insert the user into the database
    public function makeUserByRegister(PDO $db, string $usertype, int $id, string|int $password, string $firstname, string $lastname,string $email) : User
    {
        $this->makeUserByInformation($db, $usertype, $id, $firstname, $lastname, $email);
        $this->user->setPassword($password);
        return $this->user;
    }

    // ! does not inserts the user into the database 

    public function makeUserByInformation(PDO $db, string $usertype, int $id, string $firstname, string $lastname, string $email): User
    {
        $this->makeUser($usertype);
        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setFirstname($firstname);
        $this->user->setLastName($lastname);
        $this->user->setEmail($email);

        return $this->user;
    }

    // make user object
    public function makeUser(string $usertype): User
    {
        if (strcmp($usertype, Student::TABLE_NAME) == 0)
            $this->user = new Student();
        else if (strcmp($usertype, AcademicStaff::TABLE_NAME) == 0)
            $this->user = new AcademicStaff();
        else if (strcmp($usertype, UniversityAdministration::TABLE_NAME) == 0)
            $this->user = new UniversityAdministration();
        else if (strcmp($usertype, SportsCenterStaff::TABLE_NAME) == 0)
            $this->user = new SportsCenterStaff();
        else
            throw(new Exception("User type not found"));
        return $this->user;
    }

    // id session arrayde var.

    public function makeUserForHesCode(PDO $db, string $usertype, int $id): User
    {
        $this->makeUser($usertype);

        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);

        return $this->user;
    }


    public function makeUserByLogin(PDO $db, string $usertype, int $id, string|int $password): ?User
    {
        $this->makeUser($usertype);

        $this->user->setDatabaseConnection($db);
        $this->user->setId($id);
        $this->user->setPassword($password);

        $pwVerified = $this->user->verifyPassword();
        if (!$pwVerified) {
            throw new Exception("Password is incorrect.");
        }

        return $this->user;
    }

    public function makeUserById(PDO $db, string $user_type = User::TABLE_NAME, int $id): User
    {
        if (strcmp($user_type, User::TABLE_NAME) != 0) {
            try {

                return $this->makeUserByDBRow($user_type, $this->getRow($db, $user_type, $id), $db);

            } catch (Exception $e) {
                // getConsoleLogger()->error($e->getMessage());
            }

        }

        // is User an Administrator
        try {

            return $this->makeUserByDBRow(UniversityAdministration::TABLE_NAME, $this->getRow($db, UniversityAdministration::TABLE_NAME, $id), $db);
        } catch (Exception $e) {
            // getConsoleLogger()->error($e->getMessage());

        }
        // is User a Academic Staff
        try {
            return $this->makeUserByDBRow(AcademicStaff::TABLE_NAME, $this->getRow($db, AcademicStaff::TABLE_NAME, $id), $db);
        } catch (Exception $e) {
            // getConsoleLogger()->error($e->getMessage());
        }

        // is User a Sports Center Staff
        try {
            return $this->makeUserByDBRow(SportsCenterStaff::TABLE_NAME, $this->getRow($db, SportsCenterStaff::TABLE_NAME, $id), $db);
        } catch (Exception $e) {
            // getConsoleLogger()->error($e->getMessage());
        }
        // is User a Student
        try {
            return $this->makeUserByDBRow(Student::TABLE_NAME, $this->getRow($db, Student::TABLE_NAME, $id), $db);
        } catch (Exception $e) {
            // getConsoleLogger()->error($e->getMessage());
        }
        // last resort is User a User!!!

        try {
            return $this->makeUserByDBRow(User::TABLE_NAME, $this->getRow($db, User::TABLE_NAME, $id), $db);
        } catch (Exception $e) {
            // getConsoleLogger()->error($e->getMessage());
        }

        throw new UserDoesNotExistsException();

    }

    private function makeUserByDBRow(string $user_type, array $row, PDO $conn): ?User
    {
        $this->makeUser(0 == strcmp($user_type, User::TABLE_NAME) ? Student::TABLE_NAME : $user_type);
        $this->user->setDatabaseConnection($conn);
        $this->user->setId($row['id']);
        $this->user->setFirstname($row['name']);
        $this->user->setLastname($row['lastname']);
        $this->user->setEmail($row['email']);
        $this->user->setHESCode($row['hescode']);
        $this->user->setHescodeStatus($row['hescode_status']);
        return $this->user;
    }

    private function getRow(PDO $conn, string $user_type, int $user_id): ?array
    {
        $query = "SELECT * FROM " . $user_type . " WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute(array('id' => $user_id));
        if ($stmt->rowCount() == 0) {
            throw new UserDoesNotExistsException();
            return null;
        } else
            return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
