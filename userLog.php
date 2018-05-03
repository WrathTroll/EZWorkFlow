<?php
/**
 * Description of userLog
 *
 * @author WrathTroll
 */
class userLog {
    public function __construct($ul,$ui) {
    $this->ulog = $ul;
    $this->uid =$ui;
}
private $ulid;
private $uid;
private $ulog;
private $uLogTime;

public function getUlID(){return $this->ulid;}
public function setUlID($ui){$this->ulid = $ui;}
public function getUID(){return $this->uid;}
public function setUID($ui){$this->uid = $ui;}
public function getUlog(){return $this->ulog;}
public function setUlog($ul){$this->ulog = $ul;}
public function getULogTime(){return $this->ulog;}
public function setULogTime($ult){$this->uLogTime = $ult;}

 //put your code here
}

function createUserLog($ui,$ul){
    global $connection;
    $t = date('Y/m/d H:i:s');
    $ul.=" at ".$t;
    $query = "INSERT INTO userlog (USERLOGVAL,USERLOGTIME,USER_ID) values (:ul,:ult,:ui)";
try{
    $sql = $connection->prepare($query);
    $sql->bindParam(':ul',$ul);
    $sql->bindParam(':ult',$t);
    $sql->bindParam(':ui',$ui);
    $sql->execute();
    return 1;
}catch(PDOException $e){echo "unable to add log to userlogs".$e->getMessage();return 0;}
}

class userLogList{
    protected $userLogList = array();
    public function addUserLog($i){$this->userLogList[] = $i;}
    public function getUserLogList(){
        global $connection;
        
        $query = "SELECT * FROM userlog";
try{        
        $sql = $connection->prepare($query);
        $sql->execute();
        $data = $sql->fetchAll();
        foreach($data as $row)
        {
            if(empty($row))
            {
                messageAlert("no data found", "bd");
                return null;
            }
            $ul = new userLog($row['USERLOGVAL']);
            $ul->setUID($row['USERLOGID']);
            $ul->setULogTime($row['USERLOGTIME']);
            $ul->setUID($row['USER_ID']);
            $this->addUserLog($ul);
        }
        return 1;
}catch(PDOException $e){echo "cannot get userlogs".$e->getMessage();return 0;}
    }
}