<?php

    //We need the databaser
    include '../config/database.php';

    //Check the user is signed in
    //only do this if the access class hasn't already been called on this page.
    if( !$access ){
        include '../controllers/accessController.php';
        $access->checkAuth();
    }

    //Prepare the query that we will use to get the user's basket
    $basketQuery = $db->prepare(
            "SELECT
               b.product_id,
               SUM( b.quantity ) as 'quantity',
               ROUND( SUM( b.quantity * p.product_price ), 2) as 'item_total',
               p.product_name,
               p.product_price,
               p.product_description,
               p.product_max_per_purchase,
               p.product_stock_level,
               pi.image_url
             FROM baskets b
             JOIN products p
               ON b.product_id=p.id
             JOIN product_images pi
               ON p.product_image_id=pi.id 
             WHERE
               user_id=:user_id
             GROUP BY p.id"
        );

    //Bind parameters
    $basketQuery->bindParam( ":user_id", $_SESSION['id'] );

    //Execute the query
    $basketQuery->execute();

    //Get the items from the basket
    $basketItems = $basketQuery->fetchAll( PDO::FETCH_ASSOC );

    //The order total starts at 0
    $orderTotal = 0;

    //Will this put the product out of stock
    foreach( $basketItems as $id=>$item ){
        //Set the product stock flag
        if( ( $item['product_stock_level'] - $item['quantity']) < 0 ){
            $basketItems[ $id ]['in_stock'] = false;
        } else {
            $basketItems[ $id ]['in_stock'] = true;
        }

        //Add the item total to the order total
        $orderTotal += $item['item_total'];
    }

?>
