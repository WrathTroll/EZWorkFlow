<?php
session_start();
include 'connec.php';
include 'conn.php';
/*
 *  this php page encompasses the user management functionality
 *  CRUD - needed for particular tables
 * USER
 *  each element -> Create, View/Update user, Delete user.
 *  has an interactive dropdown script ( JScript ) that has the fields 
 *  necessary for the above information
 * 
 * Customer
 *  
 * 
 */

//--------------USER SECTION !!!!!!-----------------------------------------

function userHeader(){
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Users</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link rel="stylesheet" href="css/myStyles.css">        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">        
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>        
    </head>
<?php }

function userMiddle(){
?>

    <body id="bground">
        <div class="w3-padding-16"></div>
        <?php    userItemList()?>
    </body>
<?php }

function userFooter()
{
    ?>
    </html>  
<?php Scripting();}

function userItemList()
{
    ?>
    <div class="container">
        <h2>User Options</h2>
        <div class="panel-group">
            
            <div class ="panel panel-info" name="createUser">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block"> 
                    Create a new User</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Create a new user by providing name, Auth level and password</h4>
                    <?php display_UserInput_form();?>
                </div>
            </div>
            <div class ="flip panel panel-info" name="updateUserButton">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">  
                    Review and Update Users</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Listing of all Users click update to amend their details</h4>
                    <?php  
                        $ul=new userList();
                        $ul->getUsersData();
                        $ul->displayUsers();
                    ?>
                </div>
            </div>
<!--            <div class ="flip panel panel-info" name="userWorkItems">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block"> 
                    User's work items</button></div>
                <div style="display:none" class="panel-body">work viewing functions</div>
            </div>
-->
        </div>
    </div>
<?php }

//-------------User Section->combination

function setupUsers()
{
    headerOfFile("user"); //  --> std menu   
    userHeader();
    userMiddle();
    userFooter();    
}

function setupUserUpdate($uc)
{
    $um = new userManagement('','');
    $um->selectUser($uc);
    headerOfFile("user"); //  --> std menu   
    userHeader();
    display_UserUpdate_form($um);
    
}

//--------------CUSTOMER SECTION !!!!!!-----------------------------------------

function customerHeader(){
?>
    <!DOCTYPE html>
    <html>
        <head>
        <title>Customers</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
            <link rel="stylesheet" href="css/myStyles.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        </head>
    <?php }

function customerMiddle(){
?>

    <body id="bground">
        <div class="w3-padding-16"></div>
        <?php    customerItemList()?>
    </body>
<?php }

function customerFooter()
{
    ?>
    </html>  
<?php Scripting();}

function customerItemList()
{
    global $connection;
    ?>
    <div class="container">
        <h2>Customer Options</h2>
        <div class="panel-group">     
            <?php
            if($_SESSION['user_access']>1){
            ?>
            <div class ="panel panel-info" name="createCustomer">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Create a new Customer</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Create a new customer by providing a valid name and surname</h4>
                    <?php display_CustomerInput_form();?>
                </div>
            </div>
            <div class ="panel panel-info" name="readUpdateCustomer">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Review and Update Customers</button></div>                
                <div style="display:none" class="panel-body">
                    <h4>Listing of all customers click details to view or update to amend naming</h4>
                    <?php  
                        $mc=new ManageCustomer('','');
                        $mc->getCustomersData($connection);
                        $mc->displayCustomers();
                    ?>
                </div>
            </div>
            <?php
            }else{?>
            <div class ="panel panel-info" name="readUpdateCustomer">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Review existing Customers</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Listing of all customers</h4>
                    <?php  
                        $mc=new ManageCustomer('','');
                        $mc->getCustomersData($connection);
                        $mc->displayCustomers();
                    ?>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
<?php }

//-------------Customer Section->combination

function setupCustomers()
{
    headerOfFile("customer"); //  --> std menu   
    customerHeader();
    customerMiddle();
    customerFooter();    
}

function setupCustomerUpdate($uc)
{
    $c = selectCustomer($uc);
    headerOfFile("customer"); //  --> std menu   
    customerHeader();
    display_CustomerUpdate_form($c);
    
}

//--------------WORK SECTION !!!!!!-----------------------------------------
function setupWork()
{
    headerOfFile("work");   //  --> tested and working
    workHeader();           
    workMiddle();           
    workFooter();    
}    

function setupWorkUpdate($wi)
{
    $wu = selectWork($wi);
    headerOfFile("work");
    workHeader();
    display_WorkUpdate_form($wu);
}
 
function setupWorkAssign($wi)
{
    headerOfFile("work");
    workHeader();
    display_assignWork_form($wi);
}

function workHeader(){
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Work Items</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link rel="stylesheet" href="css/myStyles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     </head>
<?php }

function workMiddle(){
?>

    <body id="bground">
        <div class="w3-padding-16"></div>
        <?php workItemList()?>
    </body>
<?php }

function workItemList()
{
    global $connection;
    ?>
    <div class="container">
        <h2>Work Options</h2>
        <div class="panel-group">     
            <?php
            if($_SESSION['user_access']>2)
            {
            ?>
            <div class ="panel panel-info" name="createWorkItem">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Create a new Work Item</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Create a new Work Item</h4>
                    <?php display_WorkInput_form();?>
                </div>
            </div>
            <?php } ?>
            <div class ="panel panel-info" name="readUpdateWorkItems">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Review and Update <?php if($_SESSION['user_access']>4) 
                        { ?>ACTIVE<?php }?> Work Items</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Listing of all work items click details to view or update</h4>
                    <?php 
                    $wls = new workListSelection();
                    $wls->getWorkItems($_SESSION['user_access']);
                    $wls->displayAllWork("active");
                    ?>                    
                </div>
            </div>
    <?php if($_SESSION['user_access']>4)
            {
            ?>            
            <div class ="panel panel-info" name="readUpdateWorkItems">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Review and Update FINISHED / CANCELLED Work Items</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Listing of all work items click details to view or update</h4>
                    <?php 
                    $wls = new workListSelection();
                    $wls->getWorkItems($_SESSION['user_access']);
                    $wls->displayAllWork("cancelled");
                    ?>                    
                </div>
            </div>
       <?php } ?>            
        </div>
    </div>
<?php 
}    

function workFooter()
{
    ?>
    </html>  
<?php Scripting();}

//--------------JOB SECTION !!!!!!-----------------------------------------
function setupJobs()
{
    headerOfFile("job");   //  --> tested and working
    jobHeader();           
    jobMiddle();           
    jobFooter();    
}    

function setupJobUpdate($ji)
{
    $j = selectJob($ji);
    headerOfFile("job");
    jobHeader();
    display_JobUpdate_form($j);
}

function jobHeader(){
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Job Items</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link rel="stylesheet" href="css/myStyles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     </head>
<?php }

function JobMiddle(){
?>

    <body id="bground">
        <div class="w3-padding-16"></div>
        <?php jobItemList()?>
    </body>
<?php }

function jobItemList()
{
    global $connection;
    ?>
    <div class="container">
        <h2>Job Options</h2>
        <div class="panel-group">     
            <div class ="panel panel-info" name="createWorkItem">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Create a new Job Item</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Create a new Job Item</h4>
                    <?php display_JobInput_form();?>
                </div>
            </div>
            <div class ="panel panel-info" name="readJobItems">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Review Active Jobs</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Listing of all job items</h4>
                    <?php 
                        $list = new jobList();
                        $list->getJobs();
                        $list->displayAllJobs("active");
                    ?>                    
                </div>
            </div>
            <div class ="panel panel-info" name="readInactiveJobItems">
                <div class= "flip panel-heading"><button class="btn btn-info btn-block">
                    Review Inactive Jobs</button></div>
                <div style="display:none" class="panel-body">
                    <h4>Listing of all Inactive job items</h4>
                    <?php 
                        $list = new jobList();
                        $list->getJobs();
                        $list->displayAllJobs("inactive");
                    ?>                    
                </div>
            </div>            
        </div>
    </div>
<?php 
}    

function jobFooter()
{
    ?>
    </html>  
<?php Scripting();}
//--------------REPORTS SECTION !!!!!!-----------------------------------------
function setupReports()
{
    headerOfFile("reports");   
    reportsHeader();           
    reportsMiddle();           
    reportsFooter();    
}    

function setupReportsUpdate($ri)
{

}

function setupJobWorkAssign()
{
    display_reportJobWork_form();
}

function reportsHeader(){
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Reports Items</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link rel="stylesheet" href="css/myStyles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     </head>
<?php }

function ReportsMiddle(){
?>

    <body id="bground">
        <div class="w3-padding-16"></div>
        <?php reportsItemList()?>
    </body>
<?php }

function reportsItemList()
{
    global $connection;
    ?>
    <br><br>
    <div class="container w3-light-grey">
        <h2>Reports Options</h2>
        <div class="panel-group">     
                <div class="panel-body">
                    <h4>Report type<?php display_reportChoices(); ?></h4>
                    <?php display_report_choices(); ?>
                </div>
        </div>
    </div>
<?php 
}    

function reportsFooter()
{
    ?>
    </html>  
<?php Scripting();}

// scripting functions used for interactive UI
function Scripting()
{
    ?>
        <script>
        $('.flip button').click(function(e){
            e.preventDefault(); // cancel the default click
            $(this).closest('.flip').next('div.panel-body').slideToggle();
        });
        </script>
    <?php
}