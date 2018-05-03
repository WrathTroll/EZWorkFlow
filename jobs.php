<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jobs
 *
 * @author WrathTroll
 */
class jobs 
{    
    public function __construct($desc) {
        $this->desc = $desc;
    }
    private $id;
    private $desc;
    private $act;
    
    public function getID(){return $this->id;}
    public function setID($id){$this->id = $id;}
    public function getDesc(){return $this->desc;}
    public function setDesc($desc){$this->desc = $desc;}        
    public function getAct(){return $this->act;}
    public function setAct($act){$this->act = $act;}        

}

class jobList
{
    protected $jobsList = array();
    public function addJob($j){$this->jobsList[]=$j;}
    public function getJobs()
    {
        global $connection;
        $query = "SELECT * FROM job ORDER BY JOBID";
        $sql = $connection->prepare($query);
        $sql->execute();
        $data = $sql->fetchall();
        
        foreach($data as $row)
        {
            if(empty($row))
            {
                messageAlert("empty row", "bd");
                return null;
            }
            else
            {
                $tempJob = new jobs($row['JOBDESC']);
                $tempJob->setID($row['JOBID']);
                $tempJob->setAct($row['JOBACTIVE']);
                $this->addJob($tempJob);
            }
        }
    }
    public function displayJobsDropdown()
    {
        if(!empty($this->jobsList))
        {
            ?><select id="jobsListDD" name="workJobs" size="1" 
                    class="input-group-addon w3-pale-blue"><?php
            foreach($this->jobsList as $jl)
            {
                if($jl->getAct())
                {
                ?>                    
                <option value="<?php echo $jl->getID();?>">
                    <?php echo $jl->getDesc();?></option>
                <?php
                }
            }
            ?></select><?php
        }
        else
        {
            echo "please populate data first";
        }           
    }
    public function displayAllJobs($choice)
    {
        if($choice=="active")
        {
        echo"<table class=\"table table-hover\"><tr><th>Job Description</th>"
        . "<th>Edit Jobs</th><th>Finish Jobs</th><th>Cancel Jobs</th></tr>";            
            foreach($this->jobsList as $job)
            {                    
                if($job->getAct()&&$job->getID()!=1)
                {
                echo "<tr class=\"w3-hover-blue\">"
                    ."<td>".$job->getDesc()."</td>"
                    ."<td style='display:table-cell'>"
                    ."<a class=\"w3-button w3-blue\" href="
                    .$_SERVER['PHP_SELF'],"?jobID=".$job->getID()
                    ."&action=editJob>edit</a></td>"
                    ."<td style='display:table-cell'>";
                if(!workItemExistsJobNumber($job->getID()))
                {
                    echo "<a class=\"w3-button w3-green\" href="
                    .$_SERVER['PHP_SELF'],"?jobID=".$job->getID()
                    ."&action=finishJob>finish</a>";
                }
                echo "</td><td style='display:table-cell'>"
                    . "<a class=\"w3-button w3-red\" href="
                    .$_SERVER['PHP_SELF'],"?jobID=".$job->getID()
                    ."&action=cancelJob>cancel</a></td>";
                    echo "</tr>";
                }
            }
            ?></table><?php
        }
        else if($choice=="inactive")
        {
        echo"<table class=\"table table-hover\"><tr><th>Job Description</th>"
        . "<th>Activate Jobs</th></tr>";            
            foreach($this->jobsList as $job)
            {                    
                if(!$job->getAct())
                {
                echo "<tr class=\"w3-hover-blue\">"
                    ."<td>".$job->getDesc()."</td>"
                    ."<td style='display:table-cell'>"
                    ."<a class=\"w3-button w3-yellow\" href="
                    .$_SERVER['PHP_SELF'],"?jobID=".$job->getID()
                    ."&action=activateJob>activate</a></td></tr>";
                }
            }
            ?></table><?php
        }
    }
    
    //function to test whether there are any active 
    //work items which are linked to the job index
    
}

function createJob($j)
{
        global $connection;
        // function to create a new Job
        $job = new jobs($j);
        if (validate_job($job)!=FALSE)
        {
            $query = "insert into job (JOBDESC,JOBACTIVE) values (:j,'1')";
              try{
                    $sql = $connection->prepare($query);                
                    $sql->bindParam(':j',$job->getDesc());
                    $sql->execute();
                    insertBreaks(2);
                //USER LOG       
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " created Job ".$job->getDesc());
                    messageAlert("New job successfully created!", 'gd');                
                 }catch(PDOException $e)
                {echo "error inserting new Job ".$e->getMessage();}
        }
}

function updateJob($ji, $jd)
{
    global $connection;
    // function to update a Job
    $j = new jobs($jd);
    $j->setID($ji);
    $j->setDesc($jd);
    if(validate_job($j))
    {
        $query = "UPDATE job SET JOBDESC = :jd WHERE JOBID = :ji";
        try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':jd',$j->getDesc());
                $sql->bindParam(':ji',$j->getID());
                $sql->execute();
                insertBreaks(2);
                //USER LOG       
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " updated Job ".$j->getDesc());
                messageAlert($j->getDesc()." successfully updated", "gd");
            }catch(PDOException $e)
            {
                echo "cannot update job".$e->getMessage();
            }
    }
}

function activateJob($ji)
{
    global $connection;
    // function to update a Job

        $query = "UPDATE job SET JOBACTIVE = '1' WHERE JOBID = :ji";
        try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':ji',$ji);
                $sql->execute();
                insertBreaks(2);
                messageAlert("job activated", "gd");
            }catch(PDOException $e)
            {
                echo "cannot update job".$e->getMessage();
            }

}

function finishJob($ji)
{
    global $connection;
    // function to update a Job
        if($ji!=1)
        {    
        $query = "UPDATE job SET JOBACTIVE = '0' WHERE JOBID = :ji";
        try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':ji',$ji);
                $sql->execute();
                insertBreaks(2);
                //USER LOG       
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " finished Job ".$ji);                
                messageAlert("job Finished", "gd");
            }catch(PDOException $e)
            {
                echo "cannot update job".$e->getMessage();
            }
        }
        else
        {
                insertBreaks(2);
                messageAlert("please do not mark the default job as finished", "bd");
        }            
}

function validate_job(jobs $job)
{
    if($job->getDesc()!='')
    {       
            $job->setDesc(filter_var($job->getDesc(),FILTER_SANITIZE_STRING)); 
            return $job;
    }
    else
    {
            ?><br><br><?php
                messageAlert("ubable to edit job as no job desc", 'bd');
        return false;
    }
}
function display_JobInput_form()
{
     ?>
<br>
<body>
	<div class="container" >
	<form action = "<?php $_SERVER['PHP_SELF']?>"
			 method = "POST"
			 class="form-inline">
            <div class="container">
<div class="input-group"><textarea name="jobDesc" id="JobDesc" class="form-control"
cols="80" rows="7"/></textarea>
</div>
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="createJob"
				       type="submit"
				       value="Create Job"
				       class="btn btn-primary btn-lg" />
			</div>
		</div>                
            </div>
	</form>
	</div>
</body>
</html>
	<?php 
}

    function selectJob($num)
{
    global $connection;
    // function to select an existing user
    $query = "select * from job where JOBID = :num limit 1";
    $sql = $connection->prepare($query);
    $sql->bindParam(':num',$num);
    $sql->execute();
    $row = $sql->fetch();
    if(!empty($row))
    {
            $tempJob = new jobs($row['JOBDESC']);
            $tempJob->setID($row['JOBID']);
            $tempJob->setAct($row['JOBACTIVE']);
            return $tempJob;
    }
    else
    {
        echo "select job broken<br>".$num;
    }
}

function display_JobUpdate_form(jobs $job)
{?>
<br>
<body id="bground">
    <br><br><br>
	<div class="container" >
	<form action = "<?php $_SERVER['PHP_SELF']?>"
			 method = "POST"
			 class="form-inline">
            <div class="container">
			<div class="input-group">
                        <span class="input-group-addon">job description</span>
				<textarea name="JobDescUpdate" id="jobDescUpdate" 
                                          class="form-control" cols="80" 
                                rows="7"/><?php echo $job->getDesc();?></textarea>
			</div>
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="changeJob"
				       type="submit"
				       value="Change Job"
				       class="btn btn-primary btn-lg" />
			</div>
		</div>                
		</div>
	</form>
	</div>
</body>
</html>    
	<?php
}