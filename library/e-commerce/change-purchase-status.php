<?php
session_start();
include('../includes/config.php');
error_reporting(0);

$purchase_id = $_POST['purchase_id'];
$status = $_POST['status'];

$sql_to_update_status = "UPDATE tblpurchases
    SET Status=:status
    WHERE id=:purchase_id";

$query = $dbh->prepare($sql_to_update_status);
$query->bindParam(':purchase_id',$purchase_id,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();

echo "Order successfully ";

switch ($status) {
    case '2':
        echo 'released for delivery';
        break;
    
    case '3':
        echo 'delivered';
        break;
        
    default:
        echo 'cancelled';
        break;
}
?>