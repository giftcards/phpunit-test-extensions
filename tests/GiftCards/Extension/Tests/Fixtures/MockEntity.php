<?php

namespace GiftCards\Extension\Tests\Fixtures;

class MockEntity
{
    private $myScalar;
    private $myDateTime;

    public function __construct($dateTime = null)
    {
        if (empty($dateTime)) {
            $this->myDateTime = new \DateTime();
        } else {
            $this->myDateTime = $dateTime;
        }
    }

    public function setMyScalar($scalar)
    {
        $this->myScalar = $scalar;
        return $this;
    }

    public function getMyScalar()
    {
        return $this->myScalar;
    }

    public function setMyDateTime($dateTime)
    {
        $this->myDateTime = $dateTime;
        return $this;
    }

    public function getMyDateTime()
    {
        return $this->myDateTime;
    }
}