<?php
session_start();
    include 'connec.php';
    include 'conn.php';

/**
 * this will be the main landing page for the user
 * where they will be able to view their options. 
 */
?>
<!DOCTYPE html>
<!--
license header
-->
<?php 
function showMain()
{
    if($_SESSION['logged_in']==true){
    ?>
    <?php headerOfFile("home")?>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wizzit WorkFlow</title>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link rel="stylesheet" href="css/myStyles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">    </head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <body id="bground">
        <div class="w3-padding-32"></div>        
        <div class="w3-padding-32"></div>        
        <form method="post">
            <div class="row">
                <div class="col-sm-6">    
<!-- customer area -->                    
                    <button class="btn btn-primary btn-block" name="customersMain"
                            type="submit"><h2>Customers</h2></button>
                </div>
<!-- user area -->
<?php if($_SESSION['user_access']>4){ ?>   
                <div class="col-sm-6">    
                    <button class="btn btn-primary btn-block" type="submit" 
                        name="usersArea"><h2>User Management</h2></button>
                </div>
            </div>
<?php } ?>
<!-- work area -->
<?php if($_SESSION['user_access']>4){echo "<br>";} ?>           
            <div class="row">
                <div class="col-sm-6">    
                    <button class="btn btn-primary btn-block" name="workArea" type="submit"><h2>Work</h2></button>
                </div>
<?php if($_SESSION['user_access']>4){ ?>   
                <div class="col-sm-6">    
                    <button class="btn btn-primary btn-block" type="submit" 
                        name="jobsArea"><h2>Job listing</h2></button>
                </div>
            </div>
<?php } ?>
<!-- reports area -->
<?php if($_SESSION['user_access']>4){    insertBreaks(1); ?>           
            <div class="row">
                <div class="col-sm-6">    
                    <button class="btn btn-primary btn-block" name="reportingArea" type="submit"><h2>Reporting</h2></button>
                </div>
            </div>
<?php } ?>     

        </form>
    </body>    
    </html>
<?php }
 else {
        pleaseLogIn(); 
      }
}
    
  
// a function to create the Menu header
// home
// Customer -> customer listing etc
// User -> user listing etc
// test area
// logout
function headerOfFile($choice)
{
  ?> 
    
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wizzit WorkFlow</title>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">    </head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <form method="post">
    <div class="w3-top">
    <div class="w3-bar w3-border w3-card-4 w3-light-grey w3-large">
        <button class="w3-bar-item w3-button w3-mobile <?php if($choice=="home"){amIactive(TRUE);} ?> w3-padding-8" 
                name="homeButton" type="submit">Home</button>
        <button class="w3-bar-item w3-button w3-mobile <?php if($choice=="customer"){amIactive(TRUE);} ?> w3-padding-8" 
                name="customersMain" type="submit">Customer</button>
<?php 
// check user access, if it is > 4 then show user area
    if($_SESSION['user_access']>4){ ?>
        <button class="w3-bar-item w3-button w3-mobile w3-padding-8
            <?php if($choice=="user"){amIactive(TRUE);}?>" 
                name="usersArea" type="submit">Users</button>
<?php } ?>
        <button class="w3-bar-item w3-button w3-mobile <?php if($choice=="work"){amIactive(TRUE);} ?> w3-padding-8" 
                name="workArea" type="submit">Work</button>
<?php 
// check user access, if it is > 4 then show job area
    if($_SESSION['user_access']>4){ ?>
        <button class="w3-bar-item w3-button w3-mobile w3-padding-8
            <?php if($choice=="job"){amIactive(TRUE);}?>" 
                name="jobsArea" type="submit">Jobs</button>
    <?php }
// check user access, if it is programmer then this area ( for testing purposes )
    if($_SESSION['user_access']>9){ ?>
        
        <button class="w3-bar-item w3-button w3-mobile <?php if($choice=="Calendar"){amIactive(TRUE);} ?> w3-padding-8" 
                name="calendarArea" type="submit">Calendar</button>
<?php } ?>        
        
<!-- testing and logout area !-->
        <button class="w3-bar-item w3-button w3-mobile w3-right"
                name="logOut" type="submit"><?php if($_SESSION['user_name']>''){echo $_SESSION['user_name']." ";} ?>Logout
        </button>     
<?php    if($_SESSION['user_access']>5){ ?>
        <button class="w3-bar-item w3-button w3-mobile <?php if($choice=="test"){amIactive(TRUE);}?> w3-right"
                name="testing" type="submit">
            testing area<?php // area for testing under index ?>
        </button>
<?php } ?>
<?php    if($_SESSION['user_access']>4){ ?>
        <button class="w3-bar-item w3-button w3-mobile <?php if($choice=="reports"){amIactive(TRUE);}?> w3-right"
                name="reportingArea" type="submit">
            Reports
        </button>     
<?php } ?>
    </div>
    </div>
    </form>
    </html>
<?php
}

// a function to advise the use to log ...
function pleaseLogIn()
{
    ?>
    
    <h1 style="color:red;">please try log in later</h1>
    <h1 style="color:darkred;">or email the Master</h1>   
    <a href="#">email</a>
    <?php   
}

function shouldIShow()
{
    
}

function amIactive($yn)
{
        if($yn)
        {
            echo "w3-blue";
        }
}