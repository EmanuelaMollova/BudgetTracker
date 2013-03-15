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