<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    { 
        header('location:index.php');
    }
else{
    ?>

    
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Online Library Management System | Manage Purchases</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
<?php include('includes/header.php');?>
<div class="content-wrapper">

    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Manage Purchases</h4>
            </div>
        </div>
    </div>

    
    <div class="table-responsive p-4 container">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order Date </th>
                    <th>Tracking Id </th>
                    <th>Buyer </th>
                    <th>Purchase Cost</th>
                    <th>Books Titles </th>
                    <th>Status </th>
                    <th>Action </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql_for_purchases = "SELECT tblpurchases.id, tblpurchases.PurchaseCost, tblpurchases.Status, tblpurchases.StudentEmailId, tblpurchases.OrderDate from  tblpurchases WHERE 1";

                    $query = $dbh->prepare($sql_for_purchases);
                    $query->bindParam(':student_email_id',$_SESSION['login'],PDO::PARAM_STR);
                    $query->execute();

                    $purchases = $query->fetchAll(PDO::FETCH_OBJ);
                    foreach ($purchases as $purchase) { ?>
                        <tr class="odd gradeX">
                            <td class="center"><?php echo htmlentities($purchase->OrderDate);?></td>
                            <td class="center"><?php echo htmlentities($purchase->id);?></td>
                            <td class="center">
                                <?php 
                                
                                    
                                $student_email_id = $purchase -> StudentEmailId;
                                $sql_to_get_buyer_record = "SELECT StudentId, FullName, EmailId, MobileNumber
                                from  tblstudents WHERE EmailId=:student_email_id";

                                $student_query = $dbh->prepare($sql_to_get_buyer_record);
                                $student_query->bindParam(':student_email_id',$student_email_id,PDO::PARAM_STR);
                                $student_query->execute();

                                $student_record_array = $student_query->fetchAll(PDO::FETCH_OBJ);
                                foreach ($student_record_array as $student_record) {
                                    echo "<b>Name: </b> " . $student_record-> FullName . "<br>"; 
                                    echo "<b>Student ID:</b> " . $student_record-> StudentId . "<br>"; 
                                    echo "<b>Email:</b> " . $student_record-> EmailId . "<br>"; 
                                    echo "<b>Phone Number:</b> " . $student_record-> MobileNumber . "<br>"; 
                                }
                                ?>
                            </td>
                            <td class="center"><?php echo htmlentities($purchase->PurchaseCost);?></td>
                            <td class="center">
                                <?php 
                                    $sql_to_get_book_records = "SELECT BookISBNNumber, Quantity
                                    from  tblpurchaseitems WHERE PurchaseId=:purchase_id";

                                    $books_query = $dbh->prepare($sql_to_get_book_records);
                                    $books_query->bindParam(':purchase_id',$purchase->id,PDO::PARAM_STR);
                                    $books_query->execute();

                                    $book_records = $books_query->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($book_records as $book_record) {
                                        echo "- " . $book_record->BookISBNNumber . ": " ;

                                        $sql_to_get_book_titles = "SELECT BookName
                                        from  tblbooksonsale WHERE ISBNNumber=:isbn";

                                        $book_title_query = $dbh->prepare($sql_to_get_book_titles);
                                        $book_title_query->bindParam(':isbn',$book_record->BookISBNNumber,PDO::PARAM_STR);
                                        $book_title_query->execute();

                                        $book_title = $book_title_query->fetchAll();
                                        if($book_record -> Quantity > 1){
                                            echo $book_title[0]["BookName"] . " ( " . $book_record->Quantity . " Copies )";
                                        } else {
                                            echo $book_title[0]["BookName"] . " ( " . $book_record->Quantity . " Copy )";
                                        }

                                        echo "<br> <br>";

                                    }

                                ?>
                            </td>
                            <td class="center">
                                <?php 
                                    switch ($purchase->Status) {
                                        case 1:
                                            echo htmlentities('Pending');
                                            break;
                                        case 2:
                                            echo htmlentities('Released');
                                            break;
                                        case 3:
                                            echo htmlentities('Delivered');
                                            break;
                                        default:
                                            echo htmlentities('Cancelled');
                                            break;
                                    }
                                ?>
                            </td>
                            <td class="center">
                                
                                <i class="fa fa-delete"></i> 
                                <?php 
                                    switch ($purchase->Status) {
                                        case 1: ?>
                                            <button onclick="updatePurchaseStatus(<?php echo htmlentities($purchase -> id);?>, 2)" class="btn btn-success" id="release_order"><i class="fa fa-delete"></i> Release Order</button>
                                            <?php
                                            break;
                                        case 2: ?>
                                            <button onclick="updatePurchaseStatus(<?php echo htmlentities($purchase -> id);?>, 3)" class="btn btn-success" id="release_order"><i class="fa fa-delete"></i> Mark Delivered</button> <br> <br>
                                            <button onclick="updatePurchaseStatus(<?php echo htmlentities($purchase -> id);?>, 4)" class="btn btn-danger mt-4" id="release_order"><i class="fa fa-delete"></i> Cancel Order</button>
                                            <?php
                                            break;
                                        case 3: ?>
                                            Settled
                                            <?php
                                            break;
                                        default: ?>
                                            Cancelled
                                            <?php
                                            break;
                                    }
                                    ?>
                                </button>
                            </td>

                        </td>
                    <?php }
                ?>
            </tbody>    
</table>
</div>

<!-- FOOTER SECTION END-->

    <?php include('includes/footer.php');?>
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <script>
        function updatePurchaseStatus(purchase_id, new_status){
            console.log(purchase_id)
            console.log(new_status)
            fetch("http://localhost/derick/library/e-commerce/change-purchase-status.php", {
                method: "POST",
                headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                },
                body: `purchase_id=${purchase_id}&status=${new_status}`,
            })
            .then((response) => response.text())
            .then((response) => (
                alert(response),
                document.location ='manage-purchases.php'
            ))

        }
    </script>
</body>

</html>
<?php } ?>


