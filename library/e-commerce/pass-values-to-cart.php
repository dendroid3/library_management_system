<?php
    session_start();
    if(isset($_SESSION['ITEM'])){
        $_SESSION['ITEM'].=$_POST['ID'] . '_' . $_POST['Quantity'].",";
    } else {
        $_SESSION['ITEM']=$_POST['ID'] . '_' . $_POST['Quantity'].",";
    }

    return $_POST['ID'];
?>