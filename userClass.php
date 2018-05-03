<?php
session_start();
    include 'connec.php';
    include 'conn.php';
/*
 * 
 */
class user {
    
    public function __construct($un,$pw) {
       $this->userName = $un ;
       $this->password = $pw;
    }
    protected $userName;
    protected $Number;
    protected $userAuth;
    protected $password;
    protected $email;


    public function getUserNum(){return $this->Number;}
    public function getUserName(){return $this->userName;}
    public function getUserAuth(){return $this->userAuth;}
    public function setUserAuth($a){$this->userAuth=$a;}    
    public function getUserPass(){return $this->password;}
    public function setUserName($un){$this->userName=$un;}    
    public function setUserPass($pa){$this->password=$pa;}
    public function setUserNum($n){$this->Number=$n;}
    public function getUserEmail(){return $this->email;}
    public function setUserEmail($e){$this->email = $e;}

}

class userConnected extends user
{
    public function __construct($un, $pw) {
        parent::__construct($un, $pw);
        $this->connected=0;
    }
    private $connected;
    //return the state of the connection
    
        public function testUser()
    {
       global $connection;
    // use PDO to test username and password
        $query = "select * from user where USERNAME = ".
                $connection->quote($this->userName).
                " and USERPASS = password(".$connection->quote($this->password).") limit 1";
        try{
        $sql = $connection->query($query);}catch (PDOException $e){echo $e->getMessage();}
        try{
        $row = $sql->fetch();
        }
        catch (PDOException $e)
        {
            echo "problem with sql fetch in user : ".$e->getMessage();
        }
        if(!empty($row))
        {
            $this->setConnection(1);
            $this->setUserAuth($row['USERAUTH']);
            $this->setUserNum($row['USERID']);
            $this->setUserEmail($row['USEREMAIL']);
        }
        else
        {
            $this->setConnection(0);
            $this->setUserAuth(0);
            $this->setUserNum(0);
            echo "not found";
        }
    }
    public function getConnection()
    {
        return $this->connected;
    }
    
    //@Param $C = Boolean either connected or not
    public function setConnection($C)
    {
        $this->connected=$C;
    }
}

function createUser($user,$pw)
{
    $u = new userConnected($user,$pw);
    $u->testUser();
    return $u;
}

class userManagement extends user
{
    
    public function __construct($un, $pw) {
        parent::__construct($un, $pw);
    }
    /*
     * function to select a user from the DB and populate the selected user into 
     * a user
     */
    public function selectUser($unumber)
    {
        global $connection;
        // function to select an existing user
        $query = "select * from user where USERID = :usernum limit 1";
        $sql = $connection->prepare($query);
        $sql->bindParam(':usernum',$unumber);
        $sql->execute();
        $row = $sql->fetch();
        if(!empty($row))
        {
            $this->setUserPass($row['USERPASS']);
            $this->setUserName($row['USERNAME']);
            $this->setUserNum($row['USERID']);
            $this->setUserAuth($row['USERAUTH']);
            $this->setUserEmail($row['USEREMAIL']);
        }
        else
        {
            echo"this user is broken";
        }
    }
    //@param 1 / 2 -> we know what goes here
    // C tested without page passing - working
    public function createNewUser($uname,$pword,$auth,$email)
    {
        global $connection;
        // function to create a new user
        if (validateUser($uname, $pword, $auth,$email)!=FALSE
                &&userExists($uname)!=FALSE)
        {
            $query = "insert into user (USERNAME,USERPASS,USERAUTH,USEREMAIL) values (:un,password(:up),:ua,:ue)";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':un',$uname);
                $sql->bindParam(':up',$pword);
                $sql->bindParam(':ua',$auth);        
                $sql->bindParam(':ue',$email);
                $sql->execute();         
                //USER LOG        
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " created user ".$uname." auth ".$auth." email ".$email);
               {?><br><br><?php 
                   messageAlert($uname." : successfully created", 'gd');}
                 }catch(PDOException $e)
                {echo "error inserting new User".$e->getMessage();}
        }        
    }
    //@param - user deleted from Users list by usernumber entered.
    //if user is deleted, a log entry is made with rollback option.
    // delete tested without page passing - working    
    public function deleteUser($usNum)
    {
        global $connection;        
        if(preg_match("/^[0-9]*$/",$usNum))
        {
            $query = "delete from user where :unum = USERID";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':unum',$usNum);
                $sql->execute();
                //USER LOG       
                $um = new userManagement('','');
                $um->selectUser($usNum);
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " deleted user ".$um->getUserName()." auth ".$um->getUserAuth()." email ".$um->getUserEmail());
               {?><br><br><?php 
                   messageAlert("User was successfully deleted", 'gd');}                
                 }catch(PDOException $e)
                {echo "error deleting the user!".$e->getMessage();}
        }
    }
    
    //@param1 - update username with this parameter
    //@param2 - update userpassword with this parameter
    //@param3 - update the user's authorisation level with this parameter
    // update tested without page passing - working
    // update tested with page passing - > old password lost if not changed, 
    
    public function updateUser($uname,$passw,$authl,$unum,$email)
    {
    global $connection;
    //testing here
   
    //end of testing
    if(!(compareOldToNew($_SESSION['old3'], $passw)))
    {
        if(validateUser($uname,$passw,$authl,$email))
        {
            $query = "update user set USERNAME = :un, USERPASS = password(:up),"
                    . " USERAUTH = :ua, USEREMAIL = :ue where USERID = :unum";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':un',$uname);
                $sql->bindParam(':up',$passw);
                $sql->bindParam(':ua',$authl);
                $sql->bindParam(':unum',$unum);
                $sql->bindParam(':ue',$email);
                $sql->execute();
                //USER LOG       
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " updated user ".$uname." auth ".$authl." email ".$email);
                {?><br><br><?php
                   messageAlert("user successfully updated", 'gd');
                }                
                 }catch(PDOException $e)
                {echo "error updating the users info!".$e->getMessage();}
        }
    }
    else
    {
        if(validateUser($uname,$passw,$authl,$email))
        {
            if(!(compareOldToNew($_SESSION['old1'], $uname))||
               !(compareOldToNew($_SESSION['old2'], $authl))||
               !(compareOldToNew($_SESSION['old4'], $email)))
            {
            $query = "update user set USERNAME = :un, USERAUTH = :ua, USEREMAIL = :ue where USERID = :unum";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':un',$uname);
                $sql->bindParam(':ua',$authl);
                $sql->bindParam(':unum',$unum);
                $sql->bindParam(':ue',$email);
                $sql->execute();            
                //USER LOG       
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " updated user ".$uname." auth ".$authl." email ".$email);
                {?><br><br><?php
                   messageAlert("user successfully updated", 'gd');
                }                        
                 }catch(PDOException $e)
                {echo "error updating the users info!".$e->getMessage();}                
            }
            else 
            {
                {?><br><br><?php               
                messageAlert("no changes were made- reverting to original", 'gd');
            }
            }

        }
    }
    }
}

function validateUser($un,$pw,$auth,$email)
{
    $min = 1;
    $max = 10;
    $user = new user($un,$pw);
    $user->setUserAuth($auth);
    $user->setUserEmail($email);
    if($user->getUserName()!='')
    {
        if($user->getUserPass()!='')
        {
            if(filter_var($auth,FILTER_VALIDATE_INT)
                    &&(($min <= $auth)&&($auth<=$max)))
            {
                if($user->getUserEmail()!='')
                {
                    $user->setUserName(filter_var($user->getUserName(),FILTER_SANITIZE_STRING));            
                    $user->setUserPass(filter_var($user->getUserPass(),FILTER_SANITIZE_STRING));  
                    $user->setUserAuth(filter_var($user->getUserAuth(),FILTER_SANITIZE_NUMBER_INT));
                    $user->setUserEmail(filter_var($user->getUserEmail(),FILTER_SANITIZE_EMAIL));
                    return $user;
                }
                else{insertBreaks(2);messageAlert("please enter a valid email", "bd");}
            }
            else
            {
                {?><br><br><?php
                   messageAlert("Please enter a valid auth level 1-6", 'bd');
                }                
                return false;
            }
        }
        else
        {
            {?><br><br><?php
                messageAlert("Please enter a password", 'bd');
            } 
            return false;
        }
    }
    else
    {
        {?><br><br><?php
            messageAlert("Please enter a name", 'bd');
        } 
        return false;
    }
}
 
function userExists($u)
{
    global $connection;
    
        $query = "select * from user where USERNAME = :un limit 1";
        $sql = $connection->prepare($query);
        $sql->bindParam(':un',$u);      
        $sql->execute();
        $row = $sql->fetch();
        if(!empty($row))
        {
            {?><br><br><?php
               messageAlert("user name already exists please".
                       " choose another user name", 'bd');
            }             
            return 0;
        }
        else
        {
            return 1;
        }
}

class userList
{
    protected $userList = array();
    public function addUser($user){$this->userList[]=$user;}
    public function fetchUserList(){return $this->userList;}
    
    public function getUsersData()
    {
        global $connection;
        $query = "select * from user ORDER BY USERNAME";
        try{       
            $sql = $connection->prepare($query);
            $sql->execute();
            $data = $sql->fetchAll();
            }catch(PDOException $e){
             echo"problem performing query : ".$e->getMessage();
            }
            foreach($data as $row)
            {
                if(empty($row))
                {
                    echo 'empty row';
                    return null;
                }
                $tempUser = new user($row['USERNAME'],$row['USERPASS']);
                $tempUser->setUserAuth($row['USERAUTH']);
                $tempUser->setUserNum($row['USERID']);
                $tempUser->setUserEmail($row['USEREMAIL']);
                $this->addUser($tempUser);
        }
    }
    
    public function displayUsers()
    {
        if(!empty($this->userList)&&($_SESSION['user_access']>4))
        {
        echo"<table class=\"table table-hover\"><tr><th>Name</th>"
            . "<th>Auth Level</th><th>Email</th></tr>";
            foreach($this->userList as $user)
            {
                if($user->getUserAuth()<=$_SESSION['user_access'])
                {
                echo "<tr class=\"w3-hover-blue\"><td>".$user->getUserName()."</td>"
                . "<td>".$user->getUserAuth()."</td>"."<td>".$user->getUserEmail()."</td>"
                . "<td style='display:table-cell'>"
                ."<a class=\"w3-button w3-black\" href=".
                $_SERVER['PHP_SELF'],"?userID=".$user->getUserNum().
                "&action=updateUser>update</a></td>"
                                . "<td style='display:table-cell'>"
                        
                ."<a class=\"w3-button w3-red\" href=".
                $_SERVER['PHP_SELF'],"?userID=".$user->getUserNum().
                "&action=deleteUser onclick=\"return deleteConfirm()\">delete</a></td></tr>"
                . "<script> function deleteConfirm(){"
                . "return confirm(\"Are you sure you want to delete the user?\");}"
                . "</script>";
                }
            }
            echo "</table>";
        }
        else
        {
            echo "No Entries found";
        }
        
    }
    
        public function displayUsersDropDown()
    {
        if(!empty($this->userList))
        {
            ?><select id="userDD" name="users" size="1"
                    class="input-group-addon w3-pale-blue"><?php
            foreach($this->userList as $cu)
            {
                ?>                    
                <option value="<?php echo $cu->getUserNum();?>">
                    <?php echo $cu->getUserName();?></option>
                <?php
            }
            ?></select><?php
        }
        else
        {
            echo "please populate data first";
        }       
        
    }
        public function userReport()
    {
        // function that displays all work items based on user,
        // loop through user, display one, 
        // create a table for each user, displaying all work items for them
    }
    
}

function display_UserUpdate_form(userManagement $userM)
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
                        <span class="input-group-addon">Name</span>
				<input type="text"
				       name="UserNameUpdate"
				       id="userNameUpd"
				       class="form-control"
                                       placeholder=<?php echo $userM->getUserName();?> />
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Authorisation level</span>
				<input type="number"
				       name="userAuthUpdate"
                                       min="1" max="10"
				       id="userAuthUpd"
				       class="form-control"
                                       value=<?php echo $userM->getUserAuth();?> />
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Enter New Password</span>
				<input type="text"
				       name="userPasswordUpdate"
				       id="userPassUpd"
				       class="form-control"
                                       placeholder="********"/>
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Enter New Email</span>
				<input type="text"
				       name="userEmailUpdate"
				       id="userEmailUpd"
				       class="form-control"
                                       placeholder= <?php echo $userM->getUserEmail();?> />
			</div>                
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="changeUser"
				       type="submit"
				       value="Change User"
				       class="btn btn-primary btn-lg" />
			</div>
		</div>                
		</div>
	</form>
	</div>
</body>
</html>    
	<?php
        setupOriginal($userM->getUserName(), $userM->getUserAuth(),$userM->getUserPass(),$userM->getUserEmail());
}

function display_UserInput_form()
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
                        <span class="input-group-addon">Name</span>
				<input type="text"
				       name="userName"
				       id="UserName"
				       class="form-control"
                                       placeholder="User Name" />
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Authorisation level</span>
				<input type="number"
				       name="userAuth"
                                       min="1" max="6"
				       id="UserAuth"
				       class="form-control"
                                       placeholder="Auth level" />
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Enter a valid Password</span>
				<input type="text"
				       name="userPass"
				       id="UserPass"
				       class="form-control"
                                       placeholder="Password"/>
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Enter a valid Email</span>
				<input type="text"
				       name="userEmail"
				       id="UserEmail"
				       class="form-control"
                                       placeholder="Email"/>
			</div>                
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="createUser"
				       type="submit"
				       value="Create User"
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