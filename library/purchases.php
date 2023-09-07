<?php
session_start();
include('includes/config.php');
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
    <title>Online Library Management System | Book Sale</title>
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
<!------MENU SECTION START-->
<?php include('includes/header.php');?>

<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">My Purchases</h4>
            </div>
        </div>
    </div>

    <div class="table-responsive p-4 container">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order Date </th>
                    <th>Tracking Id </th>
                    <th>Purchase Cost</th>
                    <th>Books Titles </th>
                    <th>Status </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql_for_purchases = "SELECT tblpurchases.id, tblpurchases.PurchaseCost, tblpurchases.Status, tblpurchases.OrderDate from  tblpurchases WHERE StudentEmailId=:student_email_id";

                    $query = $dbh->prepare($sql_for_purchases);
                    $query->bindParam(':student_email_id',$_SESSION['login'],PDO::PARAM_STR);
                    $query->execute();

                    $purchases = $query->fetchAll(PDO::FETCH_OBJ);

                    foreach ($purchases as $purchase) { ?>
                        <tr class="odd gradeX">
                            <td class="center"><?php echo htmlentities($purchase->OrderDate);?></td>
                            <td class="center"><?php echo htmlentities($purchase->id);?></td>
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
                                        echo $book_record->BookISBNNumber . ": " ;

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

                                        echo "<br>";

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
                        </td>
                    <?php }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php');?>

</body>
</html>