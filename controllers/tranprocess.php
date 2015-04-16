
<?php

require_once 'DbConnector.php';
$db = new DbConnector;
$values = array();
$values1= array();
$valuesx= array();
$t = time();
$timestamp = date('Y-m-d H:i:s', $t);

 
$sql = "UPDATE transactions SET is_approved =1,approval_date='$timestamp' where transaction_id in(";
$sqldecline = "UPDATE transactions SET is_approved =2,approval_date='$timestamp' where transaction_id in(";


//print_r($_POST);
foreach ($_POST as $key => $value){
    if ($_POST[$key] == "Accept")
    {   
        
        $sqlselectTID = "SELECT source_account,destination_account,amount from transactions where transaction_id='$key';";
        $result=$db->execQuery($sqlselectTID); 
        echo $key;
        while($t = mysqli_fetch_array($result))
        {
            $srcacc=$t[0];
            $destacc=$t[1];
            $amount =$t[2];
            
            //echo $amount;
            $bal=$db->execQuery("SELECT balance from accounts where account_num=$srcacc;");
            while ($balance = mysqli_fetch_array($bal))
            {
                if($balance[0]>$amount)
                {
                    //echo $balance[0];
                    $sql1 = "UPDATE accounts SET balance=balance-$amount where account_num=$srcacc;";
                    $sql2 = "UPDATE accounts SET balance=balance+$amount where account_num=$destacc;";
                    $db->execQuery($sql1);
                    $db->execQuery($sql2);
                    $values[] = $key;
                    echo "<br>";
                    echo $sql1;
                    echo "<br>";
                    echo $sql2;
                }
                else
                {
                    $sql3 = "UPDATE transactions SET is_approved =2,approval_date='$timestamp' where transaction_id = $key;";
                    echo $sql3;
                    $db->execQuery($sql3);
                }
            }
        }
    }
    elseif ($_POST[$key] == "Decline")
    {
           $values1[] = $key;
    
    }
}

$values = implode(',',$values);
$values .= ')';

$values1 = implode(',',$values1);
$values1 .= ')';

$sql .= $values;

echo "<br>";
echo $sql;
$sqldecline .=$values1;
echo $sqldecline;
$db->execQuery($sql);
$db->execQuery($sqldecline);


$db -> closeConnection();
header('Location: employeelanding.php');

?>