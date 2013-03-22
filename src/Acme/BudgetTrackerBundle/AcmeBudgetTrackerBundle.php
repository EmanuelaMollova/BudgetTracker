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

//TODO not to be able to change username
//Password validation
//Unique names FOR USER!

//TODO ??? add real name, photo... to users ???