<?php
//--------------Landing zone is the Button check area------------------
    session_start();
/*    
*$Status :: indicates current state of webapp    
*current options 
*1: activeS ,2: maintenance ,3: construction
*/   
$_SESSION['status']=1;
/* SETTING A SESSION TIMEOUT 
 */
date_default_timezone_set("Africa/Johannesburg");
/*
 * error reporting section below, set display errors to 1 for show
 * 0 for not show.
 */
    error_reporting(E_ERROR);
    ini_set('display_errors',1);
    //all made files
    include 'connec.php';include 'conn.php';include 'P4s5.php';include 'userClass.php';
    include 'customer.php';include 'userLog.php';include 'landingZone.php';include 'interactiveFunctions.php';
    include 'globalFunctions.php';include 'monthClass.php';include 'yearClass.php';
    include 'workType.php';include 'workStatus.php';include 'work.php';
    include 'jobs.php';include 'reports.php';include 'mailSFSsend.php';include 'calendarInterface.php';
    //end of all made files
$timeS = $_SERVER['REQUEST_TIME'];
/* @param timeout_duration = 1440  24 minutes in seconds */
$timeout_duration = 1440;
/*look for the users LAST_ACTIVITY timestamp. if its set and indicates our
 * $timeout_duration has passed, log the user out
 */
if(isset($_SESSION['LAST_ACTIVITY']))
{
 if(time() - $_SESSION['LAST_ACTIVITY']>$timeout_duration)
{
     logOut();
}
}
$_SESSION['LAST_ACTIVITY']= time();

//------------------------- Password area ---------------------------
/*
/* accept password here from initial screen, setup a user session
 */
   
if(isset($_POST["submitPass"]))    
{
    $userHere = createUser($_POST['uname'],$_POST['pword']);
    if ($userHere->getConnection()!=0)
    {
        $_SESSION['user_access']=$userHere->getUserAuth();
        $_SESSION['logged_in']=$userHere->getConnection(); 
        $_SESSION['user_name']= sanInput("String", $_POST['uname']);
        $_SESSION['user_num']=$userHere->getUserNum();
        $_SESSION['user_email']=$userHere->getUserEmail();
        currentStatus();
        //USER LOG        
        createUserLog($_SESSION['user_num'],$b = $_SESSION['user_name']." Logged in");
        //sendMail("mail@mailer.co.za", $userHere->getUserName(),"log in notification", $userHere->getUserName()." LOGGED IN",$userHere->getUserName()." has now logged in");
        headerOfFile("home");
        showMain();
    }
    else    
    {
        passwordPhasedApproach();  
    }
    //
}
//-----------------Testing Area---------------------
// test function for new stuff here
else if(isset($_POST["testing"]))
{
    headerOfFile("test");
        
    echo "<br><br><br><br>";
    echo $_SESSION['LAST_ACTIVITY'];
    
}

//-----------------Logout Area---------------------
else if(isset($_POST["logOut"]))
{
    if(isset($_SESSION['logged_in']))
    {
        logOut();
    }
    else 
    {
            sleep(1);//delay password screen by @param in seconds
            etGoHome();//password again please !
    }

    
}
// *****************************************************************************
// *****************************Customer area***********************************
// *****************************************************************************
    //--------------Main Customer Area------------------
else if(isset($_POST["customersMain"]))
{
    setupCustomers(); // Customer management interface
}
    //--------------Create customer submitted-----------
else if(isset($_POST["createCustomer"]))
{
    $mc = new ManageCustomer();
    $mc->creatCustomer($_POST["customerName"],$_POST["customerSurname"]);
    setupCustomers(); // Customer management interface    
}

    //--------------Update customer submitted-----------
    // this subset checks old values against new, returns the best fit, 
    // sanitizes information then updates the record in question.

else if(isset($_POST["changeCustomer"]))
{
    $oldName  = $_SESSION['old1'];
    $oldSName = $_SESSION['old2'];
    $newValue1=$_POST["customerNameUpdate"];
    $newValue2=$_POST["customerSurnameUpdate"];
    $name= testChange($oldName, $newValue1);
    $surname= testChange($oldSName, $newValue2);
    $fi = sanInput("int",$_REQUEST["cusID"]);     // sanitize input string
    $MC = new ManageCustomer('','');
    $MC->updateCustomer($name, $surname, $fi);
    setupCustomers(); // Customer management interface                    
}
// *****************************************************************************
//  ******************************User area************************************
// *****************************************************************************
    //------------------------- Main User Area ---------------------------

else if(isset($_POST['usersArea']))
{
    setupUsers(); // User management interface
}

// ********************are to update using new user details********************
else if(isset($_POST["changeUser"]))
{
    // name user pass old values * 3
    $oldName = $_SESSION['old1'];
    $oldAuth = $_SESSION['old2'];
    $oldPass = $_SESSION['old3'];
    $oldEmail= $_SESSION['old4'];
    // update values for name auth and pass
    $newValue1 = $_POST["UserNameUpdate"] ;
    $newValue2 = $_POST["userAuthUpdate"] ;
    $newValue3 = $_POST["userPasswordUpdate"] ; 
    $newValue4 = $_POST["userEmailUpdate"];
    // return either the original value or a new entered value
    $name = testChange($oldName, $newValue1);
    $auth = testChange($oldAuth, $newValue2);
    $pass = testChange($oldPass, $newValue3);
    $email= testChange($oldEmail,$newValue4);
    // filtered input
    $fi = sanInput("int", $_REQUEST['userID']);
    // create user management object to for *.*.U.* ( CRUD )
    $un = new userManagement('','');
    $un->updateUser($name, $pass, $auth, $fi, $email);
    setupUsers(); // User management interface    
}
// ********************Create user submitted****************************
else if(isset($_POST["createUser"]))
{    
    $fn = sanInput("String",$_POST["userName"]);     // sanitize input name
    $fa = sanInput("int",$_POST["userAuth"]);        // sanitize input auth level
    $fp = sanInput("String",$_POST["userPass"]);     // sanitize input password
    $fe = sanInput("email",$_POST["userEmail"]);    // sanitize email
    $um = new userManagement('','');
    $um->createNewUser($fn, $fp, $fa, $fe);
    setupUsers(); // User management interface   
}
// *****************************************************************************
//  ********************************Work area**********************************
// *****************************************************************************
//------------------------------ Main Work Area --------------------------------
else if(isset($_POST["workArea"]))
{    
    headerOfFile("work");    
    setupWork(); // Work interface    
}
//--------------------------- Create new Work Area -----------------------------
else if(isset($_POST['createWork']))
{
    headerOfFile("work");
    echo "<br><br><br>";
    $wt = sanInput("String", $_POST['workTypes']);
    $ci = sanInput("int", $_POST['customers']);
    $wm = sanInput("String", $_POST['months']);    
    $wy = sanInput("int", $_POST['years']);
    $_POST['workTypes']='';$_POST['customers']='';$_POST['months']='';$_POST['years']='';
    if(createWork($wt, $ci, $wm, $wy))
    {
        $c = selectCustomer($ci);
        // working
        $msg = $wt." : work type<br>".
        $c->getName()." : customer name<br>".
        $wm." : ".$wy;
        //activate the below during production winston:TODO#3        
        alertSupervisors("createWork",$msg);
    };
    setupWork(); // Work management interface
}
//--------------------------- Update Work Area -----------------------------
else if(isset($_POST["updateWork"]))
{    
    headerOfFile("work");echo "<br><br><br><br>";$wi = sanInput("int",$_REQUEST['workID']);
    $wt = sanInput("String", $_POST['workTypes']);$ci = sanInput("int", $_POST['customers']);
    $wm = sanInput("String", $_POST['months']);$wy = sanInput("int", $_POST['years']);
    $wu = sanInput("int", $_POST['users']);$wsd= sanInput("date", $_POST['SDate']);
    $wed= sanInput("date", $_POST['EDate']);$wa=0; if(isset($_POST['active'])) {$wa = 1;}    
    $ws = sanInput("String", $_POST['workStatuses']);$wj = sanInput("String", $_POST['workJobs']);

    //setupOriginalWork($w->getWT(), $w->getCID(), $w->getWM(), $w->getWY(), 
    //$w->getUID(), $w->getST(), $w->getET(), $w->getWA());
        
    updateWorkItem($wi, $wt, $ci, $wm, $wy, $wu, $wsd, $wed, $wa, $ws, $wj);
    setupWork(); // Work management interface
}
//--------------------------- Assign Work Area -----------------------------
else if(isset($_POST['assignWork']))
{
    $wi = sanInput("int", $_REQUEST['workID']);
    $wu = sanInput("int", $_POST['users']);
    assignWorkItem($wi, $wu);
    headerOfFile("work");
    setupWork(); // Work management interface    
}
// *****************************************************************************
//  ********************************Jobs area**********************************
// *****************************************************************************
//------------------------------ Main Jobs Area --------------------------------
else if(isset($_POST['jobsArea']))
{
    headerOfFile("job");
    setupJobs(); // Work interface        
    insertBreaks(3);
}
//----------------------------- Create Job Area --------------------------------
else if(isset($_POST['createJob']))
{
    $jdesc = sanInput("String", $_POST['jobDesc']);
    createJob($jdesc);
    headerOfFile("job");
    setupJobs(); // Work interface            
}
else if(isset($_POST['reportJobWork']))
{
    $wu = sanInput("int", $_POST['users']);
    //($wu) function to display all work under the users name
    headerOfFile("job");
    //setupJobWorkAssign(); // Work management interface
}
//----------------------------- Update Job Area --------------------------------
else if(isset($_POST['changeJob']))
{
    $jdesc = sanInput("String", $_POST['JobDescUpdate']);
    $jdi = sanInput("int", $_REQUEST['jobID']);
    updateJob($jdi,$jdesc);
    headerOfFile("job");
    setupJobs(); // Job interface            
}
// *****************************************************************************
//  *******************************Reports area*********************************
// *****************************************************************************
//----------------------------- Main Reports Area ------------------------------
else if(isset($_POST['reportingArea']))
{
    headerOfFile("reports");
    setupReports();
}
// *****************************************************************************
//  *******************************Calendar area*********************************
// *****************************************************************************
//----------------------------- Main Calendar Area ------------------------------

else if(isset($_POST['calendarArea']))
{
headerOfFile("Calendar");
showCalendarPhp();
}
//------------------------- Home button Area ---------------------------
else if(isset($_POST['homeButton']))
{
    currentStatus();
    showMain();
}


// *****************************************************************************
//  *******************************Requests************************************
// *****************************************************************************

else if(isset($_REQUEST['action']))
{
    
//UPDATE CUSTOMER REQUEST
    switch($_REQUEST['action'])
    {
        
        case 'updateCustomer':
        {
//interactiveFunctions.php -> 
//prepare customer update screen interface
            setupCustomerUpdate($_REQUEST['cusID']);
            break;
        }        
        case 'updateUser':
        {            
//interactiveFunctions.php -> 
//prepare user update screen interface
            setupUserUpdate($_REQUEST['userID']);//
            break;
        }
        case 'completeWork':
        {            
//set end time on the work and send email to supervisor;
            ;
            $w = sanInput("int",$_REQUEST['workID']);
                if(completeWorkItem(sanInput("int",$w)))
                {
                    $work = selectWork($w);
                    $c = selectCustomer($work->getCID());                                        
                    "work type".$msg = $work->getWT()." : <br>".
                    "customer name".$c->getName()." : <br>".
                    $work->getWM()." : ".$work->getWY()."<br>by :: ".
                    $_SESSION['user_name'];      
                    alertSupervisors("completeWork",$msg);                    
                }
            headerOfFile("work");
            setupWork(); // Work management interface            
            break;
        }
        case 'cancelWork':
        {            
            cancelWorkItem(sanInput("int",$_REQUEST['workID']));
            headerOfFile("work");
            setupWork(); // Work management interface
            break;
        }                
        case 'checkedWork':
        {            
            $w = sanInput("int",$_REQUEST['workID']);
                if(workItemChecked($w))
                {
                    $work = selectWork($w);
                    $c = selectCustomer($work->getCID());                                        
                    "work type".$msg = $work->getWT()." : <br>".
                    "customer name".$c->getName()." : <br>".
                    $work->getWM()." : ".$work->getWY()."<br>by :: ".
                    $_SESSION['user_name'];     
                    alertSupervisors("checkWork",$msg);
                };
            headerOfFile("work");
            setupWork();
            break;
        }                
        case 'finishWork':
        {
            $w = sanInput("int",$_REQUEST['workID']);
                if(workItemFinished(sanInput("int",$w)))
                {
                    $work = selectWork($w);
                    $c = selectCustomer($work->getCID());                                        
                    "work type".$msg = $work->getWT()." : <br>".
                    "customer name".$c->getName()." : <br>".
                    $work->getWM()." : ".$work->getWY()."<br>by :: ".
                    $_SESSION['user_name'];     
                    //alertSupervisors("finishWork",$msg);                    
                }
            headerOfFile("work");
            setupWork();
            break;                
        }
        case 'assignWork':
        {
            echo "<br><br>";
            setupWorkAssign(sanInput("int",$_REQUEST['workID']));
            break;
        }
       // case 'selectJobWork':
       // {
       //     echo "<br><br>";
      //      setupWorkJob(sanInput("int",$_REQUEST['workID']));
       //     break;
       // }        
        case 'updateWork':
        {            
//interactiveFunctions.php -> 
//prepare user update screen
            setupWorkUpdate(sanInput("int",$_REQUEST['workID']));            
            break;
        }
//delete user ->
        case 'deleteUser':
        {      
            $fid = sanInput("int",$_REQUEST["userID"]);// sanitize input user ID
            $um=new userManagement('','');
            $um->deleteUser($fid);
            setupUsers(); // User management interface               
            break;
        }
// edit Job ->        
        case 'editJob':
        {
            $jid = sanInput("int", $_REQUEST["jobID"]);// sanitize input Job ID
            setupJobUpdate($jid);
            break;
        }
// cancel Job ->
        case 'cancelJob':
        {
            $jid = sanInput("int", $_REQUEST["jobID"]);    
            if(workItemExistsJobNumber($jid))
            {
                cancelWorkByJobNumber($jid);
                finishJob($jid);
            }
            else
            {
                finishJob($jid);
            }
            headerOfFile("job");
            setupJobs();
            break;
        }

// finish Job ->
        case 'finishJob':
        {
            $jid = sanInput("int", $_REQUEST["jobID"]);            
            finishJob($jid);
            headerOfFile("job");
            setupJobs();
            break;
        }

// Activate Job ->        
        case 'activateJob':
        {
            $jid = sanInput("int", $_REQUEST["jobID"]);
            activateJob($jid);
            headerOfFile("job");
            setupJobs();
            break;
        }
        
    }
}
else
{    
    etGoHome();  // password screen thingy
}

//=======================================================
// GENERAL FUNCTIONS ETC                               //
//=======================================================
//Home function -=> this is the start screen landing area
function etGoHome()
{
    global $msg;
    if(currentStatus()&&$msg!="invalid password")
    {
        passwordPhasedApproach();
    }
    else
    {
        showPscreenFail();
        return 0;
    }
}
// FUNCTION TO LOCK OUT USERS IF UNDER MAINTENANCE BACKUP ETC
function currentStatus()
{
    if ($_SESSION['status']==1)
    {
        return 1;
    }
    else if ($_SESSION['status']==2)
    {
        echo "innactive session";
        $_SESSION['logged_in']=0;
        ?><h1 style="color:red;">under maintenance</h1><?php
        return 0;
    }
    else if ($_SESSION['status']==3)
    {
        echo "innactive session";
        $_SESSION['logged_in']=0;
        ?><h1 style="color:blueviolet;">under construction</h1><?php
        return 0;
    }
}