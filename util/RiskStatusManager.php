<?php
require_once("User.php");
require_once("UserFactory.php");

class RiskStatusManager {
    // properties
    protected ?int $userId;
    protected ?PDO $conn;
    protected ?string $HESCode;
    protected ?bool $HESCodeStatus;
    protected ?UserFactory $uf;
    protected ?User $user;
    protected ?bool $isAllowed;
    protected ?bool $userType;

    // constructor with user id
    public function __construct(PDO $conn, int $userId, string $userType) {
        $this->conn = $conn;
        $this->userId = $userId;
        $this->isAllowed = false;
        $this->userType = $userType;
        $this->HESCode =

        $uf = new UserFactory();
        $this->user = $uf->makeUserById($conn, $userType, $this->userId);
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
        checkHESCode();
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
     * @return UserFactory|null
     */
    public function getUf(): ?UserFactory
    {
        return $this->uf;
    }

    /**
     * @param UserFactory|null $uf
     */
    public function setUf(?UserFactory $uf): void
    {
        $this->uf = $uf;
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
     * @return bool|null
     */
    public function getIsAllowed(): ?bool
    {
        return $this->isAllowed;
    }

    /**
     * @param bool|null $isAllowed
     */
    public function setIsAllowed(?bool $isAllowed): void
    {
        $this->isAllowed = $isAllowed;
    }

    /**
     * We imitate as if we are able to get the HES Code status
     * from an external system.
     */
    public function checkHESCode() {
        return ord($this->HESCode) % 2 == 0;
    }


}