<?php

namespace Acme\BudgetTrackerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeBudgetTrackerBundle extends Bundle
{
    public function getParent() 
    {
        return 'FOSUserBundle';
    }
}

//TODO email activation of accounts
//TODO reset of passwords