<?php

namespace Acme\BudgetTrackerBundle\Entity;

class Report
{
    protected $categories;
    
    protected $from_date;
    
    protected $to_date;

    
    public function getReport()
    {
        return $this->report;
    }
    public function setReport($report)
    {
        $this->report = $report;
    }
    
    public function getCategories()
    {
        return $this->categories;
    }
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    public function getFromDate()
    {
        return $this->from_date;
    }
    
    public function setFromDate($from_date = null)
    {
        $this->from_date = $from_date;
    }
    
    public function getToDate()
    {
        return $this->to_date;
    }
    
    public function setToDate($to_date = null)
    {
        $this->to_date = $to_date;
    }
}
