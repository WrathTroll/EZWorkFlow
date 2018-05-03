<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of workType
 *
 * @author WrathTroll
 */
class workType {
    public function __construct($wti,$wtv) {
        $this->workTypeID=$wti;
        $this->workTypeValue=$wtv;
    }
    private $workTypeID;
    private $workTypeValue;
    
    public function setWorkTypeID($id){$this->workTypeID=$id;}
    public function setWorkTypeValue($val){$this->workTypeValue=$val;}
    public function getWorkTypeID(){return $this->workTypeID;}
    public function getWorkTypeValue(){return $this->workTypeValue;}
}

class workTypeList{
    private $workTypes = Array();
    
    public function addWorkType($type)
    {
        $this->workTypes[] = $type;
    }
    
    public function getWorkTypes()
    {
        global $connection;
        $query = "select * from worktype ORDER BY WORKTYPEID";
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
            $tempWorkType = new workType(
            $row['WORKTYPEID'],$row['WORKTYPEVALUE']);
            $this->addWorkType($tempWorkType);
        }        
            }catch(PDOException $pde)
            {echo "unable to complete select :".$pde->getMessage();}        
    }    
    
    public function displayWorkTypesDropDown()
    {
        if(!empty($this->workTypes))
        {
            ?><select id="workTypeDD" name="workTypes" size="1" 
                    class="input-group-addon w3-pale-blue"><?php
            foreach($this->workTypes as $wt)
            {
                ?>                    
                <option value="<?php echo $wt->getWorkTypeValue();?>">
                    <?php echo $wt->getWorkTypeValue();?></option>
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

function createWorkType($wtv)
{
        global $connection;
        // function to create a new customer
        $workType=new workType('',$wtv);
        if (validate_workType($workType)!=FALSE
               && workType_exist($workType)!=TRUE)
        {
            $query = "insert into worktype (WORKTYPEVALUE) values (:wtv)";
              try{
                $sql = $connection->prepare($query);                
                $sql->bindParam(':wtv',$workType->getWorkTypeValue());
                $sql->execute();
                messageAlert($workType->getWorkTypeValue()." successfully created!", 'gd');                
                 }catch(PDOException $e)
                {echo "error inserting new Work Type".$e->getMessage();}
        }
}

function updateWorkType($wti,$wtv)
{
        global $connection;
        // function to create a new customer
        $workType=new workType($wti,$wtv);
        if (validate_workType($workType)!=FALSE)
        {
            $query = "update worktype set WORKTYPEVALUE = :wtv where WORKTYPEID = :wti";
              try{
                $sql = $connection->prepare($query);                
                $sql->bindParam(':wtv',$workType->getWorkTypeValue());
                $sql->bindParam(':wti',$workType->getWorkTypeID());                
                $sql->execute();
                messageAlert($workType->getWorkTypeValue()." successfully updated!".$workType->getWorkTypeID(), 'gd');                      
                 }catch(PDOException $e)
                {echo "error inserting new Work Type".$e->getMessage();}
        }
}

function validate_workType(workType $wt)
{
    if($wt->getWorkTypeValue()!='')
    {       
            $wt->setWorkTypeValue(filter_var($wt->getWorkTypeValue(),FILTER_SANITIZE_STRING));
            echo"<br><br><br>".$wt->getWorkTypeID()." is the ID";
            return $wt;
    }
    else
    {
            ?><br><br><?php
                messageAlert("Please enter a WorkType", 'bd');
        return false;
    }
}

function workType_exist(workType $wt)
{
    global $connection;
        $query = "select * from worktype where WORKTYPEVALUE = :wtv limit 1";
        $sql = $connection->prepare($query);
        $sql->bindParam(':wtv',$wt->getWorkTypeValue());
        $sql->execute();
        $row = $sql->fetch();
        if(!empty($row))
        {
            {?><br><br><?php
               messageAlert("work type already exists", 'bd');
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
 *          =>  WorkType population
 *          =>  WorkType dropdown list
 *          =>  create list
 *          =>  create WorkType 
 *          =>  update WorkType
 */