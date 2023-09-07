<?php
session_start();
include('../includes/config.php');
error_reporting(0);
    $student_email = $_SESSION['login'];

    $items_string = $_SESSION['ITEM'];

    $item_and_quantities_array = explode(',', substr_replace($items_string ,"",-1));

    $sql_to_create_purchase_record =  "INSERT INTO `tblpurchases` (`StudentEmailId`, `PurchaseCost`) VALUES  (:student_email, :purchase_cost);";

    $query = $dbh->prepare($sql_to_create_purchase_record);
    $query->bindParam(':student_email',$student_email,PDO::PARAM_STR);
    $query->bindParam(':purchase_cost',$_POST['purchase_cost'],PDO::PARAM_STR);
    $query->execute();

    $sql_for_last_purchase_id = "SELECT tblpurchases.id FROM tblpurchases
    ORDER BY id DESC
    LIMIT 1;";
    $query_for_last_purchase_id = $dbh->prepare($sql_for_last_purchase_id);
    $query_for_last_purchase_id->execute();
    $purchase_id = $query_for_last_purchase_id->fetchAll();

    foreach ($purchase_id as $id_of_purchase) {
        $actual_id = $id_of_purchase["id"];

    }

    foreach ($item_and_quantities_array as $item_and_quantity) {
        $quantity = explode('_', $item_and_quantity);
        
        $BookISBNNumber = $quantity[0];
        $Purchase_Quantity = $quantity[1];

        $sql_to_add_purchase_items =  "INSERT INTO `tblPurchaseItems` (`BookISBNNumber`, `PurchaseId`, `Quantity`) VALUES  (:BookISBNNumber, :PurchaseId, :Quantity);";

        $sql_to_add_purchase_items = $dbh->prepare($sql_to_add_purchase_items);
        $sql_to_add_purchase_items->bindParam(':BookISBNNumber',$BookISBNNumber,PDO::PARAM_STR);
        $sql_to_add_purchase_items->bindParam(':PurchaseId',$actual_id,PDO::PARAM_STR);
        $sql_to_add_purchase_items->bindParam(':Quantity',$Purchase_Quantity,PDO::PARAM_STR);
        $purchase_id = $sql_to_add_purchase_items->execute();
    }
?>