<?php
    //Uses session
    session_start();

    //This page should only be accessible if the user is signed in
    include 'sessionAccess.php';

    //Need to see the products in the basket
    include 'getBasketContents.php';

    //We need to use the match controller
    include '../controllers/mathController.php';

    //By default, checkout is enabled
    $canCheckout = true;
?>
<html>
    <head>
        <link rel="stylesheet" href="main.css" type="text/css"/>
        <title>Linkenfest 2019</title>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <img src="https://files.linkenfest.co.uk/logo_png.png" class="main-logo"/>
        <div class="signInWidget">
            <?php include 'signInWidget.php'; ?>
        </div>
        <div class="links" align="right">
            <?php include 'menu.php'; ?>
        </div>
        <div class="mainBodyContainer" align="center">
            <h2 class="noMargin"><u>Please confirm your order</u></h2>
            <br />
            <?php foreach( $basketItems as $key=>$product ){ ?>
                <div class="productParent" align="left">
                    <div class="productImageContainer">
                        <img src="<?= $product['image_url']; ?>" width="100%" height="100%" />
                    </div>
                    <div class="productDetails">
                        <h2 class="noMargin">
                            <?= $product['product_name'];?><br  />
                            £<?= $product['product_price'];?> each<br />
                            <br />
                            Quantity: <?= $product['quantity'];?><br />
                            Total Price ( <?= $product['quantity'];?> X £<?= $product['product_price'];?> ): £<?= $product['quantity'] * $product['product_price'];?>
                        </h2>
                        <br />
                        <?php if( $product['quantity'] > $product['product_max_per_purchase'] ){ ?>
                           <h3 class="warning noMargin">
                               You have selected too many of this item and you will not be able to check out.<br />
                               You must first amend your order in the basket.
                           </h3>
                           <?php
                               //This error should stop the user from being able to check out
                               $canCheckout = false;
                           ?>
                        <?php } ?>
                        <?php if(!$product['in_stock']){ ?>
                            <h3 class="warning noMargin">
                                There is not enough of this product remaining.<br />
                                You will need to decrease your selection before you can check out.<br />
                            </h3>
                            <?php
                                //This error should stop the user from being able to check out
                                $canCheckout = false;
                            ?>
                        <?php } ?>
                    </div>
                </div>
            <?php
               //Work out the processing fee
               $processingCharge = $math->calcProcessingFee( $orderTotal );
            }
            ?> 
            <?php if( sizeof($basketItems) > 0 ){ ?>
                <div class="productParent">
                    <div class="orderOptions" align="right">
                        <h3 class="noMargin">
                            Order SubTotal: £<?= $orderTotal; ?><br />
                            Processing Charge: £<?= $processingCharge ?> <a href="info.php?section=fees">(?)</a><br />
                            <br />
                            Order Total: £<?= number_format( $orderTotal + $processingCharge, 2, '.', '' ); ?><br />
                            <br />
                            <p class="smallPrint">By clicking the button below, you agree to make this purchase, our privacy policy, and cancellation policy.</p>
                            <?php if( $canCheckout ){?>
                                <div class="g-recaptcha" data-sitekey="6LcOKn4UAAAAALBQMY5TPjp-mLoZcPBauPsg4c9I" data-callback="confirmCaptcha"></div>
                                <form action="checkoutSuccess.php" method="POST" style='display: none' id="checkout-form">
                                    <script
                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                        data-key="pk_test_RZIMubVhyxMcq7jNaWpiZGrX"
                                        data-amount="<?= number_format( $orderTotal + $processingCharge, 2, '', '' ); ?>"
                                        data-name="Linkenfest"
                                        data-description="Complete Purchase"
                                        data-email="<?= $_SESSION['email']; ?>"
                                        data-image="https://files.linkenfest.co.uk/logo_png.png"
                                        data-locale="auto"
                                        data-currency="gbp">
                                    </script>
                                </form>
                            <?php } else { ?>
                                <h3 class="warning noMargin">
                                    There is an issue with your basket.<br />
                                    Please correct this issue <a href="basket.php">here</a> to continue</a>
                                </h3>
                            <?php } ?>
                        </h3>
                    </div>
                </div>
            <?php } else { ?>
                <h4 class="noMargin">Your basket is empty</h4>
            <?php } ?>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type='text/javascript'>
            function confirmCaptcha( data ){
                $.post(
                    "verifyCaptcha.php",
                    { response: data }
                ).done(function( data ){
                    if( data.status == 200 ){
                        $('#checkout-form').removeAttr('style');
                    } else {
                        alert( data.message );
                    }
                });
            }
        </script>
    </body>
</html>
