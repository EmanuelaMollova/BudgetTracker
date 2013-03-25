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

//TODO Unique names FOR USER!
//TODO This value should be the user current password.

//-------
//TODO User Profile ??? add real name, photo... to users ???