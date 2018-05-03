<?php
require 'conn.php';

if(isset($_POST['reportButton']))
{
    switch($_POST['reportChoice'])
    {
    case '2':{getReport('customer');}
    case '3':{getReport('user');}
    case '4':{getReport('work');}
    case '5':{getReport('job');}
    case '6':{getReport('userlog');}
    }
}


function getReport($val)
{
            global $connection;
        $query = "SELECT * FROM $val";
        $sql = $connection->prepare($query);
        try{
        $sql->execute();
        $data = $sql->fetchall(PDO::FETCH_ASSOC);
        $q = $connection->prepare("DESCRIBE $val");
        $q->execute();
        $headers = $q->fetchAll(PDO::FETCH_COLUMN);   
        //$filename = $job.date(" Y-m-d_H-i ",time());
        $fp = fopen('php://output', 'w');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="export.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        fputcsv($fp, $headers);
        $i=0;
        foreach($data as $row)
        {            
            if(empty($row))
            {
                messageAlert("empty row", "bd");
                return null;
                die;
            }
            else
            {  
                fputcsv($fp, $s = array_values($row));        
            }
        
        }}catch(PDOException $e){echo "problem here".$e;}
        die;
}