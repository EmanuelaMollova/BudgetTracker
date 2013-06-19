<?php

namespace Acme\BudgetTrackerBundle\Entity;

class Transfer
{
    protected $money;
    
    protected $destination;
        
    public function getMoney()
    {
        return $this->money;
    }
    public function setMoney($money)
    {
        $this->money = $money;
    }
    
    public function getDestination()
    {
        return $this->destination;
    }
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }
}