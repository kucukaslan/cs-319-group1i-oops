<?php
require_once("VaccineManager.php");
require_once("RiskStatusManager.php");
require_once("VaccineManager.php");
require_once("UserFactory.php");
require_once("Diagnosis.php");
require_once("Test.php");

class AllowanceFacade {
    const MIN_NO_OF_VACCINES = 2;
    const MAX_NO_OF_DAYS_FOR_TEST = 3;

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

        $uf = new UserFactory();
        $this->user = $uf->makeUserById($conn, $userType, $userId);

        $this->tests = Test::getTestsOfUserPast($this->userId,$conn);

        $this->rsm = new RiskStatusManager($conn, $userType, $userId);
        $this->vm = new VaccineManager($conn, $userId);
    }

    public function getIsAllowed(): ?bool {
        $this->isAllowed = $this->checkAllowance();
        return $this->isAllowed;
    }

    /**
     * @return bool false if the user is not allowed on campus
     * If HES is risky, then does not allow user
     * If the user has 2 vaccines and have a not risky status, allow him
     * If the user is infected, after 180 days, 1 vaccine is enough
     * After the recovery from the disease, for 30 days, no vaccine is required
     * If the vaccine is given and no past diagnosis, in that case look for negative
     * tests that are given in 3 days
     */
    public function checkAllowance(): ?bool {
       if ($this->rsm->getHESCodeStatus() === false) {
            return false;
        }
        $requiredNoOfVaccines = AllowanceFacade::MIN_NO_OF_VACCINES;

        $diagnoses = Diagnosis::getDiagnosisesOfUser($this->userId, $this->conn);

        $recentDiagnosisDate = 0;
        $firstNegTestAfterDiag = new DateTime();
        $firstNegTestAfterDiag->setDate(9999, 0, 0);

        // if there is a past diagnosis, look for negative tests
        // and determine the # of vaccines required
        if (sizeof($diagnoses) > 0) {
            // find the recent diagnosis
            $recentDiagnosisDate = $diagnoses[0]->getDiagnosisDate();

            for ($i = 0; $i < sizeof($diagnoses); $i++) {
                if ($diagnoses[$i]->getDiagnosisDate() > $recentDiagnosisDate) {
                    $recentDiagnosisDate = $diagnoses[$i]->getDiagnosisDate();
                }
            }


            $tests = Test::getTestsOfUserPast($this->userId, $this->conn);

            if (sizeof($tests) > 0) {
                for ($i = 0; $i < sizeof($tests); $i++) {
                    if ($tests[$i]->getTestDate() < $firstNegTestAfterDiag
                        && $tests[$i]->getResult() == "NEGATIVE" && $tests[$i]->getTestDate() > $recentDiagnosisDate)
                        $firstNegTestAfterDiag = $tests[$i]->getTestDate();
                }

                if ( intval($firstNegTestAfterDiag->diff(new DateTime("now"))->format('%R%a')) > 0
                    && intval($firstNegTestAfterDiag->diff(new DateTime("now"))->format('%R%a')) <= 30)
                    $requiredNoOfVaccines = 0;

            }

            if (intval($recentDiagnosisDate->diff(new DateTime("now"))->format('%R%a')) < 180
            && $requiredNoOfVaccines != 0) {
                $requiredNoOfVaccines = 1;
            }
        }

        // if the user does not have enough # of vaccines
        // look for past tests
        if (sizeof($this->vm->getUserVaccines()) < $requiredNoOfVaccines) {
            // check if he has a negative test result in specified # of days
            foreach ($this->tests as $test) {
                $time_difference_in_days = intval(date_diff($test->getTestDate(), new DateTime("now"))->format('%R%a'));
                if ($time_difference_in_days <= AllowanceFacade::MAX_NO_OF_DAYS_FOR_TEST && $time_difference_in_days >= 0
                    && $test->getResult() == "NEGATIVE") {
                   return true;
                }
            }
            // not vaccinated and no negative test in 3 days
            return false;
        }

        // user is vaccinated and has a positive HES
        return true;
    }

}