<?php
require_once("User.php");
require_once("UserFactory.php");

class RiskStatusManager {
    // properties
    protected ?int $userId;
    protected ?PDO $conn;
    protected ?string $HESCode;
    protected ?bool $HESCodeStatus;
    protected ?User $user;
    protected ?string $userType;

    // constructor with user id
    public function __construct(PDO $conn, string $userType, int $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
        $this->userType = $userType;

        $uf = new UserFactory();
        $this->user = $uf->makeUserById($conn, $userType, $this->userId);
        $this->HESCode = $this->user->getHESCode();
    }
    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return PDO|null
     */
    public function getConn(): ?PDO
    {
        return $this->conn;
    }

    /**
     * @param PDO|null $conn
     */
    public function setConn(?PDO $conn): void
    {
        $this->conn = $conn;
    }

    /**
     * @return bool|null
     */
    public function getHESCodeStatus(): ?bool {
        $this->checkHESCode();
        return $this->HESCodeStatus;
    }

    /**
     * @param bool|null $HESCodeStatus
     */
    public function setHESCodeStatus(?bool $HESCodeStatus): void
    {
        $this->HESCodeStatus = $HESCodeStatus;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }


    /**
     * We imitate as if we are able to get the HES Code status
     * from an external system.
     */
    public function checkHESCode(): void {
        if (isset($this->HESCode))
            $this->HESCodeStatus = crc32($this->HESCode) % 7 != 0;
        else
            $this->HESCodeStatus = false;
    }

    /**
     * Checks if the given string is of the form of a HES code
     * Values of the form XXXXXXXXXX or XXXX-XXXX-XX is accepted
     * where X is an alphanumeric character.
     * Other values won't be considered as a HES code. If the input is in
     * the correct form, remove separators and return only alphanumeric characters of the
     * HES code.
     */
    public static function formatHESCode(?string $hesCode): ?string {
        if (strlen($hesCode) < 10) {
            return "";
        }

        $returnHes = '';
        for($i = 0; $i < strlen($hesCode); $i++) {
            if ($hesCode[$i] != "-" && !ctype_alnum($hesCode[$i])) {
                return "";
            }

            if ($hesCode[$i] != "-") {
                $returnHes .= $hesCode[$i];
            }
        }

        if (strlen($returnHes) != 10)
            return "";

        return $returnHes;
    }
}