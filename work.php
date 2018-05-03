<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of work
 *
 * @author WrathTroll
 */
class work {
    private $workid;    //AI PK -> int
    private $workType;  // work type(table) string
    private $custID;    // customer id(table) int
    private $userID;    // user id(table) int
    private $startTime; // date work is started by user
    private $endTime;   // date work is finished 
    private $workMonth; // month of work(table) string
    private $workYear;  // year of work(table) int
    private $workActive;// is work active bool    
    private $workStatus;// what is the status of the work in progress
    private $worksJob;  // the job that the work is attached to
    
    public function __construct($wt,$ci,$wm,$wy) {
        $this->workMonth=   $wm;
        $this->workYear =   $wy;
        $this->workType =   $wt;
        $this->custID   =   $ci;
    }
    
    public function setWID($wi){$this->workid=$wi;}
    public function setWT($wt){$this->workType=$wt;}
    public function setCID($ci){$this->custID=$ci;}
    public function setUID($ui){$this->userID=$ui;}
    public function setST($st){$this->startTime=$st;}
    public function setET($et){$this->endTime=$et;}
    public function setWM($wm){$this->workMonth=$wm;}
    public function setWY($wy){$this->workYear=$wy;}
    public function setWA($wa){$this->workActive=$wa;}
    public function setWS($ws){$this->workStatus=$ws;}
    public function setWJ($wj){$this->worksJob=$wj;}
    
    public function getWID(){return $this->workid;}
    public function getWT(){return $this->workType;}
    public function getCID(){return $this->custID;}
    public function getUID(){return $this->userID;}
    public function getST(){return $this->startTime;}
    public function getET(){return $this->endTime;}
    public function getWM(){return $this->workMonth;}
    public function getWY(){return $this->workYear;}
    public function getWA(){return $this->workActive;}
    public function getWS(){return $this->workStatus;}
    public function getWJ(){return $this->worksJob;}    
}

class workList
{
    protected $workListing = array();
    public function addWorkItem($item){$this->workListing[]=$item;}
    
    // Setup work items from DB
    public function getWorkItems($choice)
    {
        if($choice <= 2)
        {
        global $connection;
        $query = "select * from work WHERE WUSERID = :uid ORDER BY WORKID ";
        try{
            $sql = $connection->prepare($query);
            $sql->bindParam(':uid',$_SESSION['user_num']);
            $sql->execute();
            $data = $sql->fetchAll();
        foreach($data as $row)
        {
            if(empty($row))
            {
                echo 'empty row';
                return null;
            }// setup initial work items
            $tempWorkItem = new work($row['WORKTYPE'],$row['WCUSTID'],$row['WORKMONTH'],$row['WORKYEAR']);
             // extend work items with other fields
            $tempWorkItem->setWID($row['WORKID']);
            $tempWorkItem->setUID($row['WUSERID']);
            $tempWorkItem->setST($row['WSTARTTIME']);
            $tempWorkItem->setET($row['WENDTIME']);
            $tempWorkItem->setWA($row['WORKACTIVE']);
            $tempWorkItem->setWS($row['WORKSTATUS']);
            $tempWorkItem->setWJ($row['WORKJOB']);
            
            $this->addWorkItem($tempWorkItem);
        }        
            }catch(PDOException $pde)
            {echo "unable to complete select :".$pde->getMessage();}
        }
        else
        {
        global $connection;
        $query = "select * from work ORDER BY WORKID";
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
            }// setup initial work items
            $tempWorkItem = new work($row['WORKTYPE'],$row['WCUSTID'],$row['WORKMONTH'],$row['WORKYEAR']);
             // extend work items with other fields
            $tempWorkItem->setWID($row['WORKID']);
            $tempWorkItem->setUID($row['WUSERID']);
            $tempWorkItem->setST($row['WSTARTTIME']);
            $tempWorkItem->setET($row['WENDTIME']);
            $tempWorkItem->setWA($row['WORKACTIVE']);
            $tempWorkItem->setWS($row['WORKSTATUS']);
            $tempWorkItem->setWJ($row['WORKJOB']);

            $this->addWorkItem($tempWorkItem);
        }        
            }catch(PDOException $pde)
            {echo "unable to complete select :".$pde->getMessage();}            
        }
        
    }
        public function testWorkJobNumber($ji)
    {
        global $connection;
    
        $query = "SELECT * FROM work WHERE WORKJOB = :ji AND WORKACTIVE = '1'";
        try{
            $sql = $connection->prepare($query);
            $sql->bindParam(':ji',$ji);
            $sql->execute();
            $data = $sql->fetchAll();
            foreach ($data as $row)
            {
            if(empty($row))
            {
                echo 'empty row';
                return null;
            }// setup initial work items
            $tempWorkItem = new work($row['WORKTYPE'],
            $row['WCUSTID'],$row['WORKMONTH'],$row['WORKYEAR']            
            );
             // extend work items with other fields
            $tempWorkItem->setWID($row['WORKID']);
            $tempWorkItem->setUID($row['WUSERID']);
            $tempWorkItem->setST($row['WSTARTTIME']);
            $tempWorkItem->setET($row['WENDTIME']);
            $tempWorkItem->setWA($row['WORKACTIVE']);
            $tempWorkItem->setWS($row['WORKSTATUS']);
            $tempWorkItem->setWJ($row['WORKJOB']);
            
            $this->addWorkItem($tempWorkItem);                         
            }
            
            }catch(PDOException $e)
    {echo "unable to select items from work queue".$e->getMessage();}
    
    }
}

class workListSelection extends workList
{
// Display work items from populated array 
// based on $select = user/month etc
//-----------------------------------------------------ALL WORK-----------------
    public function displayAllWork($type)
    {
        if(!empty($this->workListing))
        {
            if($_SESSION['user_access']>4)
            {
            echo"<table class=\"table table-hover\"><tr><th>Work Type"
            . "</th><th>Customer</th><th>User</th><th>Month</th><th>Year</th>"
            . "<th>Start Date</th><th>End date</th><th>Active</th>"
            . "<th>Status</th><th>Job</th>"
            . "<th>Update work</th><th>Cancel work</th><th>Finish work</th></tr>";            
            if($type=="active")
            {
                foreach($this->workListing as $workItem)
                {                 
                $c = selectCustomer($workItem->getCID());
                $um = new userManagement('','');
                $um->selectUser($workItem->getUID());
                $j = selectJob($workItem->getWJ());
               
                $active = testTrue($workItem->getWA());
                if($workItem->getWA()){
                echo "<tr class=\"w3-hover-blue\">"
                ."<td>".$workItem->getWT()."</td>"
                ."<td>".$c->getName()." ".$c->getSurname()."</td>"
                ."<td>".$um->getUserName()."</td>"
                ."<td>".$workItem->getWM()."</td>"
                ."<td>".$workItem->getWY()."</td>"
                ."<td>".$workItem->getST()."</td>"
                ."<td>".$workItem->getET()."</td>"
                ."<td>".$active."</td>"
                ."<td>".$workItem->getWS()."</td>"
                ."<td>".$j->getDesc()."</td>"
                ."<td style='display:table-cell'>"
                ."<a class=\"w3-button w3-black\" href="
                .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                ."&action=updateWork>update</a></td>"
                . "<td style='display:table-cell'>"
                ."<a class=\"w3-button w3-red\" href="
                .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                ."&action=cancelWork>cancel work</a></td>";
                if($workItem->getWS()=="finalizing")
                {
                echo "<td style='display:table-cell'>"
                ."<a class=\"w3-button w3-green\" href="
                .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                ."&action=finishWork>finish work</a></td>";
                }
                echo "</tr>";
                }}
            }
            else if($type=="cancelled")
            {
                foreach($this->workListing as $workItem)
                {                 
                $c = selectCustomer($workItem->getCID());
                $um = new userManagement('','');
                $um->selectUser($workItem->getUID());
                $j = selectJob($workItem->getWJ());
               
                $active = testTrue($workItem->getWA());
                if(!$workItem->getWA()){
                echo "<tr class=\"w3-hover-blue\">"
                ."<td>".$workItem->getWT()."</td>"
                ."<td>".$c->getName()." ".$c->getSurname()."</td>"
                ."<td>".$um->getUserName()."</td>"
                ."<td>".$workItem->getWM()."</td>"
                ."<td>".$workItem->getWY()."</td>"
                ."<td>".$workItem->getST()."</td>"
                ."<td>".$workItem->getET()."</td>"
                ."<td>".$active."</td>"
                ."<td>".$workItem->getWS()."</td>"
                ."<td>".$j->getDesc()."</td>"
                ."<td style='display:table-cell'>"
                ."<a class=\"w3-button w3-black\" href="
                .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                ."&action=updateWork>update</a></td></tr>";
                }}
            }
            echo "</table>";    
            }
            else if (($_SESSION['user_access']>1)&&($_SESSION['user_access']<=3))
            {
            echo"<table class=\"table table-hover\"><tr><th>Work Type"
            . "</th><th>Customer</th><th>User</th><th>Month</th><th>Year</th>"
            . "<th>Start Date</th><th>End date</th><th>Active</th>"
            . "<th>Status</th><th>Job</th>"
            . "<th>Assign work</th></tr>";            
                foreach($this->workListing as $workItem)
                {       
                    $c = selectCustomer($workItem->getCID());
                    $um = new userManagement('','');
                    $um->selectUser($workItem->getUID());
                    $active = testTrue($workItem->getWA());
                    $j = selectJob($workItem->getWJ());
                    
                    if($workItem->getWA())
                    {
                        if($workItem->getWS()=="issued")
                        {
                        echo "<tr class=\"w3-hover-blue\">"
                            ."<td>".$workItem->getWT()."</td>"
                            ."<td>".$c->getName()." ".$c->getSurname()."</td>"
                            ."<td>".$um->getUserName()."</td>"
                            ."<td>".$workItem->getWM()."</td>"
                            ."<td>".$workItem->getWY()."</td>"
                            ."<td>".$workItem->getST()."</td>"
                            ."<td>".$workItem->getET()."</td>"
                            ."<td>".$active."</td>"
                            ."<td>".$workItem->getWS()."</td>"
                            ."<td>".$j->getDesc()."</td>"
                            ."<td style='display:table-cell'>"
                            ."<a class=\"w3-button w3-blue\" href="
                            .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                            ."&action=assignWork>assign</a></td></tr>";
                        }
                        else if($workItem->getWS()=="checking")
                        {
                        echo "<tr class=\"w3-hover-blue\">"
                            ."<td>".$workItem->getWT()."</td>"
                            ."<td>".$c->getName()." ".$c->getSurname()."</td>"
                            ."<td>".$um->getUserName()."</td>"
                            ."<td>".$workItem->getWM()."</td>"
                            ."<td>".$workItem->getWY()."</td>"
                            ."<td>".$workItem->getST()."</td>"
                            ."<td>".$workItem->getET()."</td>"
                            ."<td>".$active."</td>"
                            ."<td>".$workItem->getWS()."</td>"
                            ."<td>".$j->getDesc()."</td>"
                            ."<td style='display:table-cell'>"
                            ."<a class=\"w3-button w3-green\" href="
                            .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                            ."&action=checkedWork>checked</a></td></tr>";
                        }//adding items for completion under supervisors queue
                        else if($workItem->getUID()==$_SESSION['user_num'])
                        {
                
                echo "<tr class=\"w3-hover-blue\">"
                ."<td>".$workItem->getWT()."</td>"
                ."<td>".$c->getName()." ".$c->getSurname()."</td>"
                ."<td>".$um->getUserName()."</td>"
                ."<td>".$workItem->getWM()."</td>"
                ."<td>".$workItem->getWY()."</td>"
                ."<td>".$workItem->getST()."</td>"
                ."<td>".$workItem->getET()."</td>"
                ."<td>".$active."</td>"
                ."<td>".$workItem->getWS()."</td>"
                ."<td>".$j->getDesc()."</td>"
                ."<td style='display:table-cell'>"
                ."<a class=\"w3-button w3-black\" href="
                .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                ."&action=completeWork>complete</a></td>";                            
                        }
                    }
                }
            echo "</table>";    
            }
            else
            {
            $msg="<table class=\"table table-hover\"><tr><th>Work Type";
            $msg.= "</th><th>Customer</th><th>User</th><th>Month</th><th>Year</th>";
            $msg.= "<th>Start Date</th><th>End date</th><th>Active</th>";
            $msg.= "<th>Status</th><th>Job</th>";
            $msg.= "<th>Complete work</th></tr>";
            echo $msg;
                foreach($this->workListing as $workItem)
                {
                if($workItem->getWS()=="capturing")
                {//work problem is here                   
                $c = selectCustomer($workItem->getCID());
                $um = new userManagement('','');
                $um->selectUser($workItem->getUID());
                $j = selectJob($workItem->getWJ());
                $active = testTrue($workItem->getWA());
                echo "<tr class=\"w3-hover-blue\">"
                ."<td>".$workItem->getWT()."</td>"
                ."<td>".$c->getName()." ".$c->getSurname()."</td>"
                ."<td>".$um->getUserName()."</td>"
                ."<td>".$workItem->getWM()."</td>"
                ."<td>".$workItem->getWY()."</td>"
                ."<td>".$workItem->getST()."</td>"
                ."<td>".$workItem->getET()."</td>"
                ."<td>".$active."</td>"
                ."<td>".$workItem->getWS()."</td>"
                ."<td>".$j->getDesc()."</td>"
                ."<td style='display:table-cell'>"
                ."<a class=\"w3-button w3-black\" href="
                .$_SERVER['PHP_SELF'],"?workID=".$workItem->getWID()
                ."&action=completeWork>complete</a></td><tr>";
                }
                }
            echo "</table>";    
            }
            
        }
        else
        {
            echo"no work entries found.";
        }
    }
//------------------------------------------------------WORK BASED ON criteria--
//--- not used as yet --- not tested
public function displayWork($choice)
{
    switch($choice)
    {
        case "user":
        {
            echo"<table class=\"table table-hover\"><tr><th>Work Type"
            . "</th><th>Customer</th><th>User</th><th>Month</th><th>Year</th>"
            . "<th>Start Date</th><th>End date</th><th>Active</th>"
            . "<th>Status</th><th>Job</th></tr>";
                foreach($this->workListing as $workItem)
                {
                $c = selectCustomer($workItem->getCID());
                $um = new userManagement('','');
                $um->selectUser($workItem->getUID());
                $j = selectJob($workItem->getWJ());
                
                $active = testTrue($workItem->getWA());
                echo "<tr class=\"w3-hover-blue\">"
                ."<td>".$workItem->getWT()."</td>"
                ."<td>".$c->getName()." ".$c->getSurname()."</td>"
                ."<td>".$um->getUserName()."</td>"
                ."<td>".$workItem->getWM()."</td>"
                ."<td>".$workItem->getWY()."</td>"
                ."<td>".$workItem->getST()."</td>"
                ."<td>".$workItem->getET()."</td>"
                ."<td>".$active."</td>"
                ."<td>".$workItem->getWS()."</td>"
                ."<td>".$j->getDesc()."</td></tr>";
                }
        }
            echo "</table>";    
    }
}
}

function validateWorkItem($wt,$ci,$wm,$wy)
{
    $workItem = new work($wt,$ci,$wm,$wy);
    if($workItem->getWT()!='')
    {
        if($workItem->getCID()!='')
        {
            if($workItem->getWM()!='')
            {
                if($workItem->getWY()!='')
                {
                    $workItem->setWT(filter_var($workItem->getWT(),FILTER_SANITIZE_STRING));  
                    $workItem->setCID(filter_var($workItem->getCID(),FILTER_SANITIZE_NUMBER_INT));                    
                    $workItem->setWM(filter_var($workItem->getWM(),FILTER_SANITIZE_STRING));  
                    $workItem->setWY(filter_var($workItem->getWY(),FILTER_SANITIZE_NUMBER_INT));
                    return $workItem;
                }
                else
                {
                {?><br><br><?php
                    messageAlert("Please select a year for the work", 'bd');
                } 
                return false;
                }
            }
            else
            {
            {?><br><br><?php
                messageAlert("Please select a month for the work", 'bd');
            } 
            return false;
            }
        }
        else
        {
            {?><br><br><?php
                messageAlert("Please select a customer", 'bd');
            } 
            return false;
        }
    }
    else
    {
        {?><br><br><?php
            messageAlert("Please select a workType", 'bd');
        } 
        return false;
    }
}
 
function workItemExists(work $workItem)
{
    global $connection;
    
        $query = "select * from work where WORKTYPE = :wt AND WCUSTID = :wcid"
                ." AND WORKMONTH = :wm AND WORKYEAR = :wy limit 1";
        $sql = $connection->prepare($query);
        $sql->bindParam(':wt',$workItem->getWT());
        $sql->bindParam(':wcid',$workItem->getCID());
        $sql->bindParam(':wm',$workItem->getWM());
        $sql->bindParam(':wy',$workItem->getWY());
        $sql->execute();
        $row = $sql->fetch();
        if(!empty($row))
        {
            {?><br><br><?php
               messageAlert("user name already exists please".
                       " choose another user name", 'bd');
            }             
            return 1;
        }
        else
        {
            return 0;
        }
}

function workItemExistsJobNumber($ji)
{
    global $connection;
    
            $query = "SELECT * FROM work WHERE WORKJOB = :ji "
                    . "AND WORKACTIVE = '1' LIMIT 1";
            try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':ji',$ji);
                $sql->execute();
                
                $row = $sql->fetch();
                
            if(!empty($row)) 
            {
                return 1;
            }
            else
            {
                return 0;
            }
            }catch(PDOException $e){echo $e->getMessage();}
}

function display_WorkInput_form()
{
     ?>
<br>
<body>
	<div class="container" >
	<form action = "<?php $_SERVER['PHP_SELF']?>"
			 method = "POST"
			 class="form-inline">
            <div class="container">
			<div class="input-group">
                        <span class="input-group-addon">Work Type</span>
                        <?php 
                            $wtl = new workTypeList();
                            $wtl->getWorkTypes();
                            $wtl->displayWorkTypesDropDown();
                            ?>
			</div>
                <br>                
			<div class="input-group">
                            <span class="input-group-addon">Customer ID</span>
                            <?php
                            $mcl = new ManageCustomer();
                            $mcl->getCustomersData();
                            $mcl->displayCustomersDropDown();                            
                            ?>
			</div>
                <br>
			<div class="input-group">
                            <span class="input-group-addon">Month</span>
                            <?php
                            $m = new monthList();
                            $m->getMonths();
                            $m->displayMonthsDropDown();
                            ?>
			</div>
                <br>
			<div class="input-group">
                            <span class="input-group-addon">Year</span>
                            <?php                            
                            $y = new yearList();
                            $y->getYears();
                            $y->displayYearsDropDown();
                            ?>
			</div>               
                <br>
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="createWork"
				       type="submit"
				       value="Create Work"
				       class="btn btn-primary btn-lg" />
			</div>
		</div>                
		</div>
	</form>
	</div>
</body>
	<?php 
}

function display_assignWork_form()
{
     ?>
<br>
<body id="bground">
	<div class="container" >
	<form action = "<?php $_SERVER['PHP_SELF']?>"
			 method = "POST"
			 class="form-inline">
            <div class="container">
			<div class="input-group">
                        <span class="input-group-addon">User Name</span>
                        <?php 
                            $ul = new userList();
                            $ul->getUsersData();
                            $ul->displayUsersDropDown();
                            ?>
			</div>
                <br><br><!--user list name = users-->           
                <br>
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="assignWork"
				       type="submit"
				       value="Assign Work"
				       class="btn btn-primary btn-lg" />
			</div>
		</div>                
		</div>
	</form>
	</div>
</body>
	<?php 
}

function createWork($wt,$ci,$wm,$wy) 
{
        global $connection;
        // function to create a new customer
        $w = new work($wt,$ci,$wm,$wy);
        
        if (validateWorkItem($wt,$ci,$wm,$wy)!=FALSE
               && workItemExists($w)!=TRUE)
        {
            $query = "insert into work (WORKTYPE,WCUSTID,WORKMONTH,WORKYEAR,WUSERID,WORKSTATUS,WORKJOB,WORKACTIVE) "
                    . "values (:wt,:wcid,:wm,:wy,'1000','issued','1','1')";
              try{
                $sql = $connection->prepare($query);                
                $sql->bindParam(':wt',$wt);
                $sql->bindParam(':wcid',$ci);
                $sql->bindParam(':wm',$wm);
                $sql->bindParam(':wy',$wy);
                $sql->execute();
                messageAlert(" successfully created!", 'gd');                
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " created ".$wi." ".$wm.$wy." ".$wt);
                return 1;
                 }catch(PDOException $e)
                {echo "error inserting new Work Item".$e->getMessage();return 0;}
        }
}

function updateWorkItem($wi,$wt,$ci,$wm,$wy,$wui,$ws,$we,$wa,$wstat,$wj)
{
        global $connection;
        // function to create a new customer
       
        if (validateWorkItem($wt,$ci,$wm,$wy)!=FALSE)
        {
            $query = "update work set WORKTYPE = :wt, WCUSTID = :wcid,"
                    ."WUSERID = :wui, WORKMONTH = :wm, WORKYEAR = :wy,"
                    ."WSTARTTIME = :ws, WENDTIME = :we, WORKACTIVE = :wa,"
                    ."WORKSTATUS = :wstat, WORKJOB = :wj where WORKID = :wi";
              try{
                $sql = $connection->prepare($query);$sql->bindParam(':wt',$wt);
                $sql->bindParam(':wcid',$ci);$sql->bindParam(':wui',$wui);
                $sql->bindParam(':wm',$wm);$sql->bindParam(':wy',$wy);
                $sql->bindParam(':ws',$ws);$sql->bindParam(':we',$we);
                $sql->bindParam(':wi',$wi);$sql->bindParam(':wa',$wa);
                $sql->bindParam(':wstat',$wstat);$sql->bindParam(':wj',$wj);
                $sql->execute();messageAlert("successfully updated!", 'gd');
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " updated ".$wi." ".$wm.$wy." ".$ws);                
                return 1;
                 }catch(PDOException $e)
                {echo "error editing the Work item".$e->getMessage();return 0;}
        }
}

function completeWorkItem($wi)
{
        global $connection;
        // function to mark the work as completed
            $w = selectWork($wi);
            $ws = "checking";
            $ct = date("Y-m-d");
            $query = "update work set WENDTIME = :wet, WORKSTATUS = :ws "
                    ."where WORKID = :wi";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':wet',$ct);
                $sql->bindParam(':ws',$ws);
                $sql->bindParam(':wi',$wi);                                
                $sql->execute();
                insertBreaks(2);
                messageAlert("successfully completed!", 'gd');
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " completed ".$wi." ".$w->getWM().$w->getWY()." ".$w->getWT());
                return 1;
                 }catch(PDOException $e){
                     echo "error marking the work as complete ".$e->getMessage();return 0;
                 }
}

function cancelWorkItem($wi)
{
        global $connection;
        // email portion
            // email portion below
                $w = selectWork($wi);
                $um = new userManagement('','');
                $um->selectUser($w->getUID());
                $c = selectCustomer($w->getCID());
                $j= selectJob($w->getWJ());
            // email body
                $body = "The following work has been cancelled by :: ". 
                $_SESSION['user_name'].
                "<br>===================================================<br>".
                "Job : ".$j->getDesc().":<br>Type :".
                $w->getWT().":<br>Period :".$w->getWM()." ".
                $w->getWY().":<br>Customer :".$c->getName();     
            // end of email body
            // end of email portion
                
        
        // end email portion                                
        // function to mark the work as completed
            $ws = "cancelled";
            $ct = date("Y-m-d");
            $s = 1000;
            $query = "update work set WENDTIME = :wet, WORKSTATUS = :ws, "
                    ."WUSERID = :s, WORKACTIVE = '0' where WORKID = :wi";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':wet',$ct);
                $sql->bindParam(':ws',$ws);
                $sql->bindParam(':s',$s);
                $sql->bindParam(':wi',$wi);                                
                $sql->execute();
                insertBreaks(2);
                messageAlert("successfully completed!", 'gd');
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " cancelled ".$wi." ".$w->getWM().$w->getWY()." ".$w->getWT());
                
sendMail($um->getUserEmail(),
$um->getUserName(),
"work cancellation",
"work in your queue has been cancelled",
$body
);           
                 }catch(PDOException $e){
                     echo "error marking the work as complete ".$e->getMessage();
                 }
}

function workItemChecked($wi)
{
    global $connection;
    // function to mark the work as checked
            $ws = "finalizing";
            $w = selectWork($wi);
            $query = "update work set WORKSTATUS = :ws where WORKID = :wi";
            try {
               $sql = $connection->prepare($query);
               $sql->bindParam(':ws',$ws);
               $sql->bindParam(':wi',$wi);
               $sql->execute();
               insertBreaks(2);
               messageAlert("successfully updated", "gd");
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " checked ".$wi." ".$w->getWM().$w->getWY()." ".$w->getWT());
               return 1;
            }catch(PDOException $e){echo "unable to mark work as checked ".$e->getMessage();return 0;}
}

function workItemFinished($wi)
{
    global $connection;
    
           $ws = "finished";
           $w = selectWork($wi);
           $query = "update work set WORKSTATUS = :ws, WORKACTIVE = '0' WHERE WORKID = :wi";
           try{
           $sql = $connection->prepare($query);
           $sql->bindParam(':ws',$ws);
           $sql->bindParam(':wi',$wi);
           $sql->execute();
           insertBreaks(2);
           messageAlert("status successfully changed to finished", "gd");
           //USER LOG        
           createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
           " finished ".$wi." ".$w->getWM().$w->getWY()." ".$w->getWT());           
           return 1;
           }catch(PDOException $e){echo "cannot mark work item as finished".$e->getMessage();return 0;}
}

function assignWorkItem($wi,$wui)
{
        global $connection;
        // function to assign work to a user with start time -> if not exists
            $ws = "capturing";
            $ct = date("Y-m-d");
            $w = selectWork($wi);
            
            // email portion below
                $um = new userManagement('','');
                $um->selectUser($wui);
                //$um->getUserEmail();
                //$um->getUserName();                
                $c = selectCustomer($w->getCID());
                $c->getName();
                $j= selectJob($w->getWJ());
                $j->getDesc();            
            // email body
                $body = "The following work has been assigned to you by :: ". 
                $_SESSION['user_name'].
                "<br>===================================================<br>".
                "Job : ".$j->getDesc().":<br>Type :".
                $w->getWT().":<br>Period :".$w->getWM()." ".
                $w->getWY().":<br>Customer :".$c->getName();     
            // end of email body
            // end of email portion
            if($w->getST()==0)
            {
            $query = "update work set WSTARTTIME = :wst, WUSERID = :wui, "
                    ."WORKSTATUS = :ws where WORKID = :wi";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':wst',$ct);
                $sql->bindParam(':wui',$wui);
                $sql->bindParam(':ws',$ws);
                $sql->bindParam(':wi',$wi);
                $sql->execute();
//activate the below during production winston:TODO#3
                //send the email
                
sendMail($um->getUserEmail(),
$um->getUserName(),
"work assignment",
"work has been assigned to you",
$body
);                                
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " assigned ".$wi." ".$w->getWM().$w->getWY()." ".$w->getWT());
                
                messageAlert("<br><br>successfully completed!", "gd");
                 }catch(PDOException $e){
                     echo "error assigning the work ".$e->getMessage();
                 }
            }
            else
            {
            $query = "update work set WUSERID = :wui, WORKSTATUS = :ws "
                    ."where WORKID = :wi";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':wui',$wui);
                $sql->bindParam(':ws',$ws);
                $sql->bindParam(':wi',$wi);
                $sql->execute();
//activate the below during production winston:TODO#3                
                //send the email
sendMail($um->getUserEmail(),
$um->getUserName(),
"work assignment",
"work has been assigned to you",
$body
); 
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " assigned ".$wi." ".$w->getWM().$w->getWY()." ".$w->getWT());
                
                messageAlert("<br><br>successfully completed!", "gd");
                 }catch(PDOException $e){
                     echo "error assigning the work ".$e->getMessage();
                 }               
            }
}

    function selectWork($wnumber)
{
    global $connection;
    // function to select an existing user
    $query = "select * from work where WORKID = :wnum limit 1";
    $sql = $connection->prepare($query);
    $sql->bindParam(':wnum',$wnumber);
    $sql->execute();
    $row = $sql->fetch();
    if(!empty($row))
    {
            $tempWorkItem = new work($row['WORKTYPE'],
            $row['WCUSTID'],$row['WORKMONTH'],$row['WORKYEAR']            
            );
            $tempWorkItem->setWID($row['WORKID']);
            $tempWorkItem->setUID($row['WUSERID']);
            $tempWorkItem->setST($row['WSTARTTIME']);
            $tempWorkItem->setET($row['WENDTIME']);
            $tempWorkItem->setWA($row['WORKACTIVE']);
            $tempWorkItem->setWS($row['WORKSTATUS']);
            $tempWorkItem->setWJ($row['WORKJOB']);
            return $tempWorkItem;
    }
    else
    {
        echo "this work item is broken";
    }
}

function cancelWorkByJobNumber($ji)
{
    global $connection;
    
            $query = "SELECT * FROM work WHERE WORKJOB = :ji "
                    . "AND WORKACTIVE = '1'";
            try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':ji',$ji);
                $sql->execute();               
                $data = $sql->fetchAll();
            foreach($data as $row)
            {
            if(empty($row))
            {
                echo 'empty row';
                return null;
            }// update the items to be cancelled.
                cancelWorkItem($row['WORKID']);
            }
            }catch(PDOException $e){echo $e->getMessage();}
}

function display_WorkUpdate_form(work $w)
{
     ?>
<?php 
//--------------------------------setup items to populate default on dropdowns--
    if($w->getUID()==0){$w->setUID(1000);}?>
<br>
<body id="bground">
    <br><br><br>
	<div class="container" >
	<form action = "<?php $_SERVER['PHP_SELF']?>"
			 method = "POST"
			 class="form-inline">
            <div class="container">
			<div class="input-group">
                        <span class="input-group-addon">Work Type</span>
                        <?php 
                            $wtl = new workTypeList();
                            $wtl->getWorkTypes();
                            $wtl->displayWorkTypesDropDown();
                            ?>
			</div>
                <br><br><!--work type list name = workTypes-->                
			<div class="input-group">
                            <span class="input-group-addon">Customer Name</span>
                            <?php
                            $mcl = new ManageCustomer();
                            $mcl->getCustomersData();
                            $mcl->displayCustomersDropDown();                            
                            ?>
			</div>
                <br><br><!--customer list name = customers-->
			<div class="input-group">
                            <span class="input-group-addon">Month</span>
                            <?php
                            $m = new monthList();
                            $m->getMonths();
                            $m->displayMonthsDropDown();
                            ?>
			</div>
                <br><br><!--month list name = months-->
			<div class="input-group">
                            <span class="input-group-addon">Year</span>
                            <?php                            
                            $y = new yearList();
                            $y->getYears();
                            $y->displayYearsDropDown();
                            ?>
			</div>
                <br><br><!--year list name = years-->
			<div class="input-group">
                        <span class="input-group-addon">User Name</span>
                        <?php 
                            $ul = new userList();
                            $ul->getUsersData();
                            $ul->displayUsersDropDown();
                            ?>
			</div>
                <br><br><!--user list name = users-->
			<div class="input-group">
                            <span class="input-group-addon">Start Date</span>
                            <input class=" w3-pale-blue" type="date" name="SDate" 
                                   value=<?php echo $w->getST();?>>
			</div>
                <br><br>
			<div class="input-group">
                            <span class="input-group-addon">End Date</span>
                            <input class=" w3-pale-blue" type="date" name="EDate" 
                                   value=<?php echo $w->getET();?>>
			</div>
                <br><br>
			<div class="input-group">
                            <span class="input-group-addon">Active?</span>
                            <input class=" w3-pale-blue" type="checkbox" name="active" 
                                   <?php if($w->getWA()){echo "checked";}?>>
			</div>
                <br><br>
                
			<div class="input-group">
                            <span class="input-group-addon">Work Status</span>
                            <?php 
                                $wsl = new workStatusList();
                                $wsl->getWorkStatuss();
                                $wsl->displayWorkStatusesDropDown();
                            ?>
			</div>
                <br><br>
			<div class="input-group">
                            <span class="input-group-addon">The work's Job</span>
                            <?php 
                                $jl = new jobList();
                                $jl->getJobs();
                                $jl->displayJobsDropdown();
                            ?>
			</div>
                <br><br>                
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="updateWork"
				       type="submit"
				       value="Update Work"
				       class="btn btn-primary btn-lg" />
			</div>
		</div>
		</div>
	</form>
	</div>
<script> 
        $('#workTypeDD option[value="<?php echo $w->getWT();?>"]').prop('selected',true);
        $('#yearDD option[value="<?php echo $w->getWY();?>"]').prop('selected',true);
        $('#monthDD option[value="<?php echo $w->getWM();?>"]').prop('selected',true);
        $('#userDD option[value="<?php echo $w->getUID();?>"]').prop('selected',true);
        $('#customerDD option[value="<?php echo $w->getCID();?>"]').prop('selected',true);
        $('#statusDD option[value="<?php echo $w->getWS();?>"]').prop('selected',true);
        $('#jobsListDD option[value="<?php echo $w->getWJ();?>"]').prop('selected',true);
</script>

</body>
	<?php 
}