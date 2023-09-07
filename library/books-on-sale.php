<?php 
session_start();
include('includes/config.php');
error_reporting(0);
// $_SESSION['ITEM'] = array();
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
<!-- MENU SECTION END-->
    <!-- DB query -->

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Books On Sale</h4>
                </div>
            </div>
        </div>
        <div class="row container">
            <div class="col-md-5 container col-sm-5 col-xs-6">
                <h5 class="header-line">My Cart</h5>
                <?php include('cart.php');?>

            </div>
            <div class=" col-md-7 container col-sm-7 col-xs-6"> 
                <?php 
                $sql = "SELECT * from  tblbooksonsale
                join  tblauthors on tblauthors.id=tblbooksonsale.AuthorId";
                $query = $dbh -> prepare($sql);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $cnt=1;
                if($query->rowCount() > 0)
                {?>    
                    <div class="row center">

                    <?php foreach($results as $result)
                    {?>     
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="alert alert-info back-widget-set text-center">
                                <img src="assets/img/user2.png" alt="" style="min-height:50px;"/> <br>
                                <b> Title: </b>  <?php echo htmlentities($result->BookName);?> <br> 
                                <b> By: </b>  <?php echo htmlentities($result->AuthorName);?> <br> 
                                <b> ISBN: </b>  <?php echo htmlentities($result->ISBNNumber);?> <br> 
                                <b> Price: </b>  <?php echo htmlentities($result->BookPrice);?> <br> 
                                <b> Available Units: </b>  <?php echo htmlentities($result->BookQuantity);?> <br> 
                                <a onclick="addToCart(<?php echo htmlentities($result -> ISBNNumber); ?>)">  <button class="btn btn-success"><i class="fa fa-plus"></i> Add To Cart</button> </a>

                            </div>
                        </div>
                        <?php $cnt=$cnt+1;
                    }
                }?>     
                </div>  
            </div>
        </div>
    
                </div>
    
    
     <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <script>
        function addToCart(id){
            let quantity = prompt('How many of these books do you want to buy?', 1);
            $.ajax({
                type: "POST",
                url: "e-commerce/pass-values-to-cart.php",
                data: 'ID=' + id + "&Quantity=" + quantity,
                dataType: 'json',
                cache: false,
            });

            let alert_message = quantity + (quantity > 1 ? " books " : " book ") + "added to cart successfully"

            alert(alert_message)

            window.location.reload();

        }
    </script>
</body>
</html>
