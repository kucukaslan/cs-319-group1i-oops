<?php
require_once("VaccineManager.php");
require_once("RiskStatusManager.php");
require_once("VaccineManager.php");
require_once("UserFactory.php");
require_once("Test.php");

class AllowanceFacade {
    const MIN_NO_OF_VACCINES = 2;
    const MIN_NO_OF_DAYS_FOR_TEST = 3;

    protected ?PDO $conn;
    protected ?RiskStatusManager $rsm;
    protected ?VaccineManager $vm;
    protected ?array $tests;
    protected ?User $user;
    protected ?int $userId;
    protected ?string $userType;
    protected ?bool $isAllowed;

    public function __construct(PDO $conn, string $userType, int $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->isAllowed = false;

        $this->tests = Test::getTestsOfUserPast($this->userId,$conn);
        // print_r($this->tests);


        $this->rsm = new RiskStatusManager($conn, $userType, $userId);
        $this->vm = new VaccineManager($conn, $userId);
    }

    public function getIsAllowed(): ?bool {
        echo "in is allowed ";
        $this->isAllowed = $this->checkAllowance();
        return $this->isAllowed;
    }

    // TODO: hasta olup iyilestikten sonra 30 gun asidan muaf
    // TODO: son 180 gunde hastaysa tek asi yeterli
    public function checkAllowance(): ?bool {
        // echo "in check allowance ";
        if ($this->rsm->getHESCodeStatus() == false) {
            echo "false since HES CODE ";
            return false;
        }
        // echo "in check allowance  2 ";
        if (sizeof($this->vm->getUserVaccines()) < AllowanceFacade::MIN_NO_OF_VACCINES) {
            // check if he has a negative test result in specified # of days
            foreach ($this->tests as $test) {
                $time_difference_in_days = intval($test->getTestDate()->diff(new DateTime("now"))->format('%R%a'));
                echo $time_difference_in_days . "-" . $test->getTestId() . " ";
                if ($time_difference_in_days <= AllowanceFacade::MIN_NO_OF_DAYS_FOR_TEST && $time_difference_in_days > 0
                    && $test->getResult() == "NEGATIVE") {
                    // echo "true since has a negative test";
                    return true;
                }
            }
            // echo "false since no negative test given";
            return false;
        }

        // echo "true since vaccinated ";
        return true;
    }


}