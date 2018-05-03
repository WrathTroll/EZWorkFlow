<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monthClass
 *
 * @author WrathTroll
 */
class monthClass {
    
    public function __construct($mid,$mval) {
        $this->monthID=$mid;
        $this->monthValue=$mval;
    }    
    private $monthID;
    private $monthValue;
    // getter and setter functions
    public function getMonthID()
    {return $this->monthID;}
    public function getMonthValue()
    {return $this->monthValue;}    
    public function setMonthID($mid)
    {$this->monthID=$mid;}
    public function setMonthValue($mv)
    {$this->monthValue=$mv;}
}

class monthList {
    private $months = array();
    
    public function addMonth($m){$this->months[]=$m;}
    
    public function getMonths()
    {
        global $connection;
        $query = "select * from month ORDER BY MONTHID";
        try{
            $sql = $connection->prepare($query);
            $sql->execute();
            $data = $sql->fetchAll();
        foreach($data as $row)
        {
            if(empty($row))
            {
                echo 'empty row';
                return null;
            }
            $tempMonth = new monthClass(
            $row['MONTHID'],$row['MONTHVALUE']);
            $this->addMonth($tempMonth);
        }        
            }catch(PDOException $pde)
            {echo "unable to complete select :".$pde->getMessage();}
    }
    
    public function displayMonthsDropDown()
    {
        if(!empty($this->months))
        {
            ?><select id="monthDD" name="months" size="1" 
                    class="input-group-addon w3-pale-blue"><?php
            foreach($this->months as $m)
            {
                ?>                    
                <option value="<?php echo $m->getMonthValue();?>">
                    <?php echo $m->getMonthValue();?></option>
                <?php
            }
            ?></select><?php
        }
        else
        {
            echo "please populate data first";
        }
    }    
}

/*NOTES
 * tested and working
 *          =>  month population
 *          =>  month dropdown list
 */