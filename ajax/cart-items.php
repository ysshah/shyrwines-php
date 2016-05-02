<?php
include_once(dirname(__DIR__)."/includes/db.php");

if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {
    $tax = 0.0875;
    $subTotal = 0.0;
    $total_num_items = 0;

    echo "<div id='table'>\n";
    foreach ($_SESSION["cart"] as $id => $quantity) {
        $total_num_items += $quantity;
        $result = mysql_query("SELECT * FROM WineTable WHERE ID = $id LIMIT 1");
        $wine = mysql_fetch_assoc($result);

        $unitTotal = $quantity * $wine["Price"];
        $subTotal += $unitTotal;

        echo "<div class='cart-wine'>\n";
            echo "<div class='cart-name'><a class='cart-name-link' href='view?id=$id'>"
                . $wine["Name"] . "</a></div>\n";
            echo "<div class='cart-quantity'><input id='$id' pattern='\d*' type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$quantity' /></div>\n";
            echo "<div class='cart-x'>&times;</div>\n";
            echo sprintf("<div class='cart-unit-price'>$%s</div>\n", number_format($wine["Price"], 2, ".", ","));
            echo "<div class='cart-equals'>=</div>\n";
            echo sprintf("<div class='cart-unit-totalprice'>$%s</div>\n", number_format($unitTotal, 2, ".", ","));
            echo "<div class='cart-remove'><button class='remove' id='$id'>Remove</button></div>\n";
        echo "</div>\n";
    }

    $taxValue = round($subTotal * $tax, 2);
    $total = round($subTotal * (1 + $tax), 2);
//    if ($total_num_items > 11) {
//        $discount = round($subTotal * 0.1, 2);
//        $total = round($subTotal * (1 + $tax) - $discount, 2);
//    } else {
//        $total = round($subTotal * (1 + $tax), 2);
//    }

    echo sprintf("<div class='cart-totals-row'><div class='cart-totals-titles'>Subtotal</div><div class='cart-equals'>=</div><div id='subtotal' class='cart-totals'>$%s</div></div>", number_format($subTotal, 2, ".", ","));
//    if ($total_num_items > 11) {
//        echo sprintf("<div class='cart-totals-row'><div class='cart-totals-titles'>Case Discount (-10%%)</div><div class='cart-equals'>=</div><div id='discount' class='cart-totals'>- $%s</div></div>", number_format($discount, 2, ".", ","));
//    }
    echo sprintf("<div class='cart-totals-row'><div class='cart-totals-titles'>Estimated Tax</div><div class='cart-equals'>=</div><div id='tax' class='cart-totals'>$%s</div></div>", number_format($taxValue, 2, ".", ","));
    echo sprintf("<div class='cart-totals-row' style='font-size:20px'><div class='cart-totals-titles'>Total</div><div class='cart-equals'>=</div><div id='total' class='cart-totals'>$%s</div></div>", number_format($total, 2, ".", ","));

    echo "</div>\n";

}

?>
