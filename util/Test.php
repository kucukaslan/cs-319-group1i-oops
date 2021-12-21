<?php

class Test
{
    // CONSTANTS
    const TABLE_NAME = "pcr_test";

    // object properties
    private int $testId;
    protected ?string $testDate;
    protected int $result;
    protected bool $testPerformed;


    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(?int $id): void
    {
        $this->testId = $id;
    }

    /**
     * @return int|null
     */
    public function getTestId(): ?int
    {
        return $this->testId;
    }

    /**
     * @param int|null $id
     */
    public function setTestId(?int $id): void
    {
        $this->testId = $id;
    }


    public function getTestDate(): ?string
    {
        return $this->testDate;
    }

    public function setTestDate(?string $date ) : void
    {
        $this->testDate = $date;
    }


    public function __construct() //string $date, int $cvx_code, string $name, string $vaccineType,int $correspondingUserid) 
    {
        
    }



    public function getTableName(): string {
        return get_called_class()::TABLE_NAME;
    }






}
