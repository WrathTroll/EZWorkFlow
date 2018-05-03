<?php
session_start();
//reusable functions
//@param $type = type of input, 
//IE:: string/int etc - > 
//                          maybe use RegEx here, not sure yet.
//@param $input = input variable to be sanitized.

function sanInput($type,$input)
{
    switch($type)
    {
        case"String" :
        {
            return filter_var($input,FILTER_SANITIZE_STRING);
            break;
        }
        case"int":
        {
            return filter_var($input,FILTER_SANITIZE_NUMBER_INT);
            break;
        }
        case"date":
        {
            $input = preg_replace("([^0-9\-])","",$input);            
            return $input;
            break;
        }        
        case"email":
        {
            return filter_var($input,FILTER_SANITIZE_EMAIL);
            break;
        }        
        
    }
}

// function to test whether an old value has been changed or not, 
// @Param $oldValue = initial value, 
// @Param $newValue = new value.

function setupOriginal($value1,$value2,$value3,$value4)
{
    $_SESSION['old1']=$value1;
    $_SESSION['old2']=$value2;
    $_SESSION['old3']=$value3;    
    $_SESSION['old4']=$value4;        
}

function setupOriginalWork($v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8)
{
    $_SESSION['old1']=$v1;
    $_SESSION['old2']=$v2;
    $_SESSION['old3']=$v3;
    $_SESSION['old4']=$v4;
    $_SESSION['old5']=$v5;
    $_SESSION['old6']=$v6;
    $_SESSION['old7']=$v7;
    $_SESSION['old8']=$v8;
}
// function to test whether to retail original info ( ie nothing entered)
// or to use new info entered.

function testChange($oldValue,$newValue)
{
    if($newValue=='')
    {
       return $oldValue;       
    }
    else
    {
       return $newValue;
    }
}

function compareOldToNew($old,$new)
{
    if($old==$new)
    {
        return true;
    }
    else
    {    
        return false;
    }
}

// function to clear all oldvalue placeholders
function clearOldValues()
{
    $_SESSION['old1']='';
    $_SESSION['old2']='';
    $_SESSION['old3']='';    
    $_SESSION['old4']='';
    $_SESSION['old5']='';
    $_SESSION['old6']='';    
    $_SESSION['old7']='';
    $_SESSION['old8']='';    
}
//@param1 Message = message to display
//@param2 Type = type of message 
//(g)ood (b)ad (gd) good dismiss (bd) bad dismiss
function messageAlert($message,$type)
{
    if($type=='b')
    {
       ?>       
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
                <div class="alert 
                alert-danger">
                <strong><?php echo $message?></strong></div><?php         
    }
    if($type=='bd')
    {
       ?>       
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
                <div class="alert 
                alert-danger alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong><?php echo $message?></strong></div><?php         
    }    
    else if($type=='g')
    {
       ?>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">        
                <div class="alert 
                alert-success">
                <strong><?php echo $message?></strong></div><?php   
    }
    else if($type=='gd')
    {
       ?>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">        
                <div class="alert 
                alert-success alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong><?php echo $message?></strong></div><?php   
    }    
}

function insertTab(){ ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php }
function insertBreaks($num){$i = 0;while($i!=$num){echo"<br>";++$i;}}
function testTrue($v){if($v==1){return "true";}else{return "false";}}
function logOut(){
        sleep(1);//delay password screen by @param in seconds
    
    etGoHome();//password again please !
    //USER LOG        
    createUserLog($_SESSION['user_num'],$b = $_SESSION['user_name']." Logged out");
    //sendMail("mail@mailer.com", $_SESSION['user_name'],"log out notification", $_SESSION['user_name']." LOGGED OUT",$_SESSION['user_name']." has now logged out.");

        $_SESSION = array();

        if(session_id()!=""||isset($_COOKIE[session_name()]))
        {
            setcookie(session_name(),'',time()-2592000,'/');
        }
        session_unset();
        session_destroy();
    messageAlert("successfully logged out", 'b');
    die();
    }