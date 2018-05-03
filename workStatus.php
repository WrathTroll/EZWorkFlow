<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of workStatus
 *
 * @author WrathTroll
 */
class workStatus {
    public function __construct($wti,$wtv) {
        $this->workStatusID=$wti;
        $this->workStatusValue=$wtv;
    }
    private $workStatusID;
    private $workStatusValue;
    
    public function setWorkStatusID($id){$this->workStatusID=$id;}
    public function setWorkStatusValue($val){$this->workStatusValue=$val;}
    public function getWorkStatusID(){return $this->workStatusID;}
    public function getWorkStatusValue(){return $this->workStatusValue;}
}

class workStatusList{
    private $workStatuses = Array();
    
    public function addWorkStatus($type)
    {
        $this->workStatuses[] = $type;
    }
    
    public function getWorkStatuss()
    {
        global $connection;
        $query = "select * from workstatus ORDER BY WORKSTATUSID";
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
            $tempWorkStatus = new workStatus(
            $row['WORKSTATUSID'],$row['WORKSTATUSVALUE']);
            $this->addWorkStatus($tempWorkStatus);
        }        
            }catch(PDOException $pde)
            {echo "unable to complete select :".$pde->getMessage();}        
    }    
    
    public function displayWorkStatusesDropDown()
    {
        if(!empty($this->workStatuses))
        {
            ?><select id="statusDD"  name="workStatuses" size="1"
                    class="input-group-addon w3-pale-blue"><?php
            foreach($this->workStatuses as $wt)
            {
                ?>                    
                <option value="<?php echo $wt->getWorkStatusValue();?>">
                    <?php echo $wt->getWorkStatusValue();?></option>
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

function createWorkStatus($wtv)
{
        global $connection;
        // function to create a new customer
        $workStatus=new workStatus('',$wtv);
        if (validate_workStatus($workStatus)!=FALSE
               && workStatus_exist($workStatus)!=TRUE)
        {
            $query = "insert into workstatus (WORKSTATUSVALUE) values (:wsv)";
              try{
                $sql = $connection->prepare($query);                
                $sql->bindParam(':wsv',$workStatus->getWorkStatusValue());
                $sql->execute();
                messageAlert($workStatus->getWorkStatusValue()." successfully created!", 'gd');
                 }catch(PDOException $e)
                {echo "error inserting new Work Status".$e->getMessage();}
        }
}

function updateWorkStatus($wti,$wtv)
{
        global $connection;
        // function to create a new customer
        $workStatus=new workStatus($wti,$wtv);
        if (validate_workStatus($workStatus)!=FALSE)
        {
            $query = "update workstatus set WORKSTATUSVALUE = :wsv where WORKSTATUSID = :wsi";
              try{
                $sql = $connection->prepare($query);                
                $sql->bindParam(':wsv',$workStatus->getWorkStatusValue());
                $sql->bindParam(':wsi',$workStatus->getWorkStatusID());                
                $sql->execute();
                messageAlert($workStatus->getWorkStatusValue()." successfully updated!".$workStatus->getWorkStatusID(), 'gd');                      
                 }catch(PDOException $e)
                {echo "error inserting new Work Status".$e->getMessage();}
        }
}

function validate_workStatus(workStatus $wt)
{
    if($wt->getWorkStatusValue()!='')
    {       
            $wt->setWorkStatusValue(filter_var($wt->getWorkStatusValue(),FILTER_SANITIZE_STRING));
            return $wt;
    }
    else
    {
            ?><br><br><?php
                messageAlert("Please enter a WorkStatus", 'bd');
        return false;
    }
}

function workStatus_exist(workStatus $wt)
{
    global $connection;
        $query = "select * from workstatus where WORKSTATUSVALUE = :wsv limit 1";
        $sql = $connection->prepare($query);
        $sql->bindParam(':wsv',$wt->getWorkStatusValue());
        $sql->execute();
        $row = $sql->fetch();
        if(!empty($row))
        {
            {?><br><br><?php
               messageAlert("work status already exists", 'bd');
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
 *          =>  WorkStatus population
 *          =>  WorkStatus dropdown list
 *          =>  create list
 *          =>  create WorkStatus 
 *          =>  update WorkStatus
 */