<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of yearClass
 *
 * @author WrathTroll
 */
class yearClass {
    public function __construct($yid,$yval) {
        $this->yearID=$yid;
        $this->yearValue=$yval;
    }
    private $yearID;
    private $yearValue;
    //setter and getter functions
    public function setYearID($yid)
    {$this->yearID=$yid;}
    public function setYearValue($yv)
    {$this->yearValue=$yv;}
    public function getYearID()
    {return $this->yearID;}
    public function getYearValue()
    {return $this->yearValue;}
}

class yearList {
    private $years = array();
    
    public function addYear($y){$this->years[] = $y;}
    
    public function getYears()
    {
        global $connection;
        $query = "select * from year ORDER BY YEARID";
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
            $tempYear = new yearClass(
            $row['YEARID'],$row['YEARVALUE']);
            $this->addYear($tempYear);
        }        
            }catch(PDOException $pde)
            {echo "unable to complete select :".$pde->getMessage();}
    }

    public function displayYearsDropDown()
    {
        if(!empty($this->years))
        {
            ?><select id="yearDD" name="years" size="1" 
                    class="input-group-addon w3-pale-blue"><?php
            foreach($this->years as $y)
            {
                ?>                    
                <option value="<?php echo $y->getYearValue();?>">
                    <?php echo $y->getYearValue();?></option>
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

function createYear($y)
{
        global $connection;
        // function to create a new customer
        $year=new yearClass('',$y);
        if (validate_year($year)!=FALSE
               && year_exist($year)!=TRUE)
        {
            $query = "insert into year (YEARVALUE) values (:yv)";
              try{
                $sql = $connection->prepare($query);                
                $sql->bindParam(':yv',$year->getYearValue());
                $sql->execute();
                 }catch(PDOException $e)
                {echo "error inserting new Year".$e->getMessage();}
             ?>
            <br><br><div class="alert 
            alert-success alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong>Year<?php echo " ".$year->getYearValue();?> successfully created!</strong></div>
            <?php              
            }
}

function validate_year(yearClass $yc)
{
    $min = 2010;
    $max = 2021;
    if($yc->getYearValue()!='')
    {       
        if ($min<$yc->getYearValue()&&$yc->getYearValue()<$max)
        {   
            $yc->setYearValue(filter_var($yc->getYearValue(),FILTER_SANITIZE_NUMBER_INT));                        
            return $yc;
        }
        else
        {?><br><br><?php
            messageAlert("Please enter a Valid Year between".$min." and ".$max, 'bd');
        }
    }
    else
    {
            ?><br><br><?php
                messageAlert("Please enter a Year", 'bd');
        return false;
    }
}

function year_exist(yearClass $yc)
{
    global $connection;
        $query = "select * from year where YEARVALUE = :yv limit 1";
        $sql = $connection->prepare($query);
        $sql->bindParam(':yv',$yc->getYearValue());      
        $sql->execute();
        $row = $sql->fetch();
        if(!empty($row))
        {
            {?><br><br><?php
               messageAlert("Year already exists", 'bd');
            }             
            return 1;
        }
        else
        {
            return 0;
        }
}

/*NOTES
 * tested and working
 *          =>  year population
 *          =>  year dropdown list
 *          =>  create list
 */