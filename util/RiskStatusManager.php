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
        $this->HESCodeStatus = ord($this->HESCode) % 2 == 0;
    }


}