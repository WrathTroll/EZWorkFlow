<?php
    session_start();
    include 'connec.php';
    include 'conn.php'; 

class Customers
{ 
    public function __construct($n,$s)
    {
        $this->customerName=$n;
        $this->customerSurname=$s;
    }
    
    private $customerID;
    private $customerName;
    private $customerSurname;
    private $customerBusiness;
  
    public function getID()
    {return $this->customerID;}
    public function getName()
    {return $this->customerName;}
    public function getSurname()
    {return $this->customerSurname;}
    public function getBusiness()
    {return $this->customerBusiness;}
    
    public function setID($id)
    {$this->customerID=$id;}
    public function setName($name)
    {$this->customerName=$name;}
    public function setSurname($surName)
    {$this->customerSurname=$surName;}
    public function setBusiness($business)
    {$this->customerBusiness=$business;}
}

class CustomerTableData
{
    protected $customers = array();
    public function addCustomer(Customers $customer)
    {$this->customers[]=$customer;}
    
    public function getCustomersData()
    {
        global $connection;
        $query = "select * from customer ORDER BY CUSTOMERNAME";
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
            $tempCustomer = new Customers(
            $row['CUSTOMERNAME'],$row['CUSTOMERSURNAME']);
            $tempCustomer->setID($row['CUSTOMERID']);
            $this->addCustomer($tempCustomer);
        }
    }
}    

class ManageCustomer extends CustomerTableData
{
    public function displayCustomers()
    {
        if(!empty($this->customers))
        {
        echo"<table class=\"table table-hover\"><tr><th>Name</th><th>Surname</th></tr>";
        foreach($this->customers as $cust)
        {
            echo "<tr class=\"w3-hover-blue\"><td>".$cust->getName()."</td><td>"
                    .$cust->getSurname()
                    ."</td>"
                    ."<td style='display:table-cell'>";
            // check user access, if 1 or 2 then only show read , no update
            if($_SESSION['user_access']<3){
            echo  "</td></tr>";
            }
            else
            {
                echo  "<a class=\"w3-button w3-black\" href=".$_SERVER['PHP_SELF'],"?cusID="
                    .$cust->getID()."&action=updateCustomer>update</a></td></tr>";
            }
        }
        echo "</table>";
        }
        else
        {
            echo "No Entries found";
        }
    }
    
    public function displayCustomersDropDown()
    {
        if(!empty($this->customers))
        {
            ?><select id="customerDD" name="customers" size="1" 
                    class="input-group-addon w3-pale-blue"><?php
            foreach($this->customers as $cu)
            {
                ?>                    
                <option value="<?php echo $cu->getID();?>">
                    <?php echo $cu->getName()." ".$cu->getSurname();?></option>
                <?php
            }
            ?></select><?php
        }
        else
        {
            echo "please populate data first";
        }       
        
    }

    public function updateCustomer($name,$surname,$cnum)
    {
        // function to update a customers name/surname
        global $msg, $connection;
        $cus=new customers($name,$surname);
        if(validate_customer($cus))
        {            
            if(!(compareOldToNew($_SESSION['old1'], $cus->getName()))||
            !(compareOldToNew($_SESSION['old2'], $cus->getSurname())))
            {
            $query = "update customer set CUSTOMERNAME = :cn, CUSTOMERSURNAME = :cs "
                    . " where CUSTOMERID = :cnum";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':cn',$cus->getName());
                $sql->bindParam(':cs',$cus->getSurname());                              
                $sql->bindParam(':cnum',$cnum);
                $sql->execute();
                //USER LOG       
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " updated customer ".$name." ".$surname." cnumber ".$cnum);                
            {?><br><br><div class="alert 
                alert-success alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong>Customer Successfully updated</strong></div>
            <?php          
            }
                 }catch(PDOException $e)
                {echo "error updating the customers info!".$e->getMessage();}            
            }else
            {?><br><br><div class="alert 
                alert-success alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong>no changes were made- reverting to original.</strong></div>                
            <?php    
        }
        }
    }
    
    public function creatCustomer($name,$surname)
    {
        global $connection;
        // function to create a new customer
        $c=new customers($name,$surname);
        if (validate_customer($c)!=FALSE
                && customer_exist($c)!=TRUE)
        {
            $query = "insert into customer (CUSTOMERNAME,CUSTOMERSURNAME) values (:cn,:cs)";
              try{
                $sql = $connection->prepare($query);
                $sql->bindParam(':cn',$c->getName());
                $sql->bindParam(':cs',$c->getSurname());
                $sql->execute();
                //USER LOG       
                createUserLog($_SESSION['user_num'],$_SESSION['user_name'].
                " created customer ".$name." ".$surname);
                 }catch(PDOException $e)
                {echo "error inserting new User".$e->getMessage();}
             ?>
            <br><br><div class="alert 
            alert-success alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong>Client<?php echo " ".$c->getName()." ".$c->getSurname();?> successfully created!</strong></div>
            <?php              
        }        
    }
}

function validate_customer(Customers $customer)
{
    global $msg;
    if($customer->getName()!='')
    {
        if($customer->getSurname()!='')
        {
            $customer->setName(filter_var($customer->getName(),FILTER_SANITIZE_STRING));            
            $customer->setSurname(filter_var($customer->getSurname(),FILTER_SANITIZE_STRING));            
            return $customer;
        }
        else
        {
            ?><br><br><div class="alert 
            alert-danger alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong>Please enter a Surname</strong></div>
            <?php
            return false;
        }
    }
    else
    {
            ?><br><br><div class="alert 
            alert-danger alert-dismissable fade in">
                <a href="#" class="close" 
                data-dismiss="alert" aria-label="close">&times;</a>    
                <strong>Please enter a Name</strong></div>
            <?php
        return false;
    }
}

function customer_exist(Customers $customer)
{
    global $msg, $connection;
    
    $query = "SELECT * FROM customer WHERE CUSTOMERNAME = "
            .$connection->quote($customer->getName())
            ."AND CUSTOMERSURNAME = ".$connection->quote($customer->getSurname())
            . " LIMIT 1";
    $stmt = $connection->query($query);
    $row = $stmt->fetch();
    if($stmt == false)
    {
        echo'cant run query';
        $msg = 'Error Querying Customer Table';
        return NULL;
    }
    
    if (!empty($row))
    {
        $msg = "Customer with name {$customer->getName()}"
        . " {$customer->getSurname()} already exists.";
        echo 'found';
        return true;
    } 
    else 
    {
        echo 'not found';
        return false;//false
    }
}

function display_CustomerInput_form()
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
				       name="customerName"
				       id="department"
				       class="form-control"
				       placeholder="Enter Customer name" />
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Surname</span>
				<input type="text"
				       name="customerSurname"
				       id="department"
				       class="form-control"
				       placeholder="Enter Customer Surname" />
			</div>
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="createCustomer"
				       type="submit"
				       value="Create Customer"
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
// @param - $customer -> customer number used to retrieve info from the database
// displays customer update form with populated customer details as placeholders

function display_CustomerUpdate_form(Customers $customer)
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
				       name="customerNameUpdate"
				       id="cusNameUpd"
				       class="form-control"
                                       placeholder=<?php echo $customer->getName();?> />
			</div>
			<div class="input-group">
                            <span class="input-group-addon">Surname</span>
				<input type="text"
				       name="customerSurnameUpdate"
				       id="cusSurnUpd"
				       class="form-control"
                                       placeholder=<?php echo $customer->getSurname();?> />
			</div>
		<div class="control-group" style="margin-top:10px">
			<div class="controls">
				<input name="changeCustomer"
				       type="submit"
				       value="Change Customer"
				       class="btn btn-primary btn-lg" />
			</div>
		</div>                
		</div>
	</form>
	</div>
</body>
</html>    
	<?php
setupOriginal($customer->getName(),$customer->getSurname(),'');
}
// @Param - $cnumber customer number used to retrieve info from database
// returns a customer with name / surname and customer ID all set.

        function selectCustomer($cnumber)
    {
        global $connection;
        // function to select an existing user
        $query = "select * from customer where CUSTOMERID = :cusnum limit 1";
        $sql = $connection->prepare($query);
        $sql->bindParam(':cusnum',$cnumber);
        $sql->execute();
        $row = $sql->fetch();
        if(!empty($row))
        {
            $c=new Customers($row['CUSTOMERNAME'],$row['CUSTOMERSURNAME']);
            $c->setID($cnumber);
            return $c;
        }
        else
        {
            echo"this user is broken";
        }
    }