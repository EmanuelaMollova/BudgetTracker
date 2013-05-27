<?php

namespace Acme\BudgetTrackerBundle\Entity;

class Report
{
    protected $categories;
    
    protected $start_date;
    
    protected $end_date;

    
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

    public function getStartDate()
    {
        return $this->start_date;
    }
    
    public function setStartDate($start_date = null)
    {
        $this->start_date = $start_date;
    }
    
    public function getEndDate()
    {
        return $this->end_date;
    }
    
    public function setEndDate($end_date = null)
    {
        $this->end_date = $end_date;
    }
}
