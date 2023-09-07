<?php 
session_start();
include('includes/config.php');
error_reporting(0);
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
    <title>Online Library Management System | My Cart</title>
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
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ISBN </th>
                        <th>Book Name</th>
                        <th>Item Price </th>
                        <th>Quantity </th>
                        <th>Total Price </th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $GrandTotal = 0;

                    foreach (explode(',', $_SESSION["ITEM"]) as $item) {
                        $Data_Array = explode('_', $item);
                        $ISBNNumber = $Data_Array[0];
                        $Quantity = $Data_Array[1];

                        $sql = "SELECT * from  tblbooksonsale WHERE ISBNNumber=$ISBNNumber";

                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        foreach ($results as $result) { 
                            $SubGrandTotal = $result->BookPrice * $Quantity;
                            $GrandTotal += $SubGrandTotal;
                        ?>
                                    
                        <tr class="odd gradeX">
                            <td class="center"><?php echo htmlentities($result->ISBNNumber);?></td>
                            <td class="center"><?php echo htmlentities($result->BookName);?></td>
                            <td class="center"><?php echo htmlentities($result->BookPrice);?></td>
                            <td class="center"><?php echo htmlentities($Quantity);?></td>
                            <td class="center"><?php echo htmlentities($SubGrandTotal);?></td>
                        </tr>
                        <?php }
                    }
                ?>
                <tr class="odd gradeX">
                    <th class="center font-weight-bold"> Grand Total </th>
                    <td class="center"> - </td>
                    <td class="center"> - </td>
                    <td class="center"> - </td>
                    <th class="center"><?php echo htmlentities($GrandTotal);?></th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

<a onclick="emptyCart()"><button class="btn btn-danger"><i class="fa fa-delete"></i> Empty Cart</button> </a>
<button onclick="sendOrder(<?php echo htmlentities($GrandTotal);?>)" class="btn btn-success" id="send_order"><i class="fa fa-delete"></i> Send Order</button>

<script>
        function emptyCart(){
            let confirmation_message = "You will empty this whole cart and start shopping again. Do you want to proceed?"
            if(!confirm(confirmation_message)) {return}
            $.ajax({
                type: "GET",
                url: "e-commerce/empty-cart.php",
                cache: false,
            });
            window.location.reload();
        }

        function sendOrder(grand_total){

            let btn = document.getElementById("send_order");

            btn.addEventListener("click", function(){
                
                let confirmation_message = "You are about to make an order worth KES " + grand_total

                if(!confirm(confirmation_message)){ return }
                fetch("http://localhost/derick/library/e-commerce/make-order.php", {
                    method: "POST",
                    headers: {
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                    },
                    body: `purchase_cost=${grand_total}`,
                })
                .then((response) => response.text())
                .then((res) => (
                    alert('Order sent successufully'),
                    fetch("http://localhost/derick/library/e-commerce/empty-cart.php", {
                        method: "GET",
                        headers: {
                        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                        },
                    }),
                    document.location ='purchases.php'

                ));
            })
        }
</script>
</body>