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

//TODO titles
//TODO h1 !

//TODO registration validation

//TODO ??? add real name, photo... to users ???