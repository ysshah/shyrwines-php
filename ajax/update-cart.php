<?php
include_once(dirname(__DIR__)."/includes/db.php");

$error_msg = "There was an error processing your request.";

if (isset($_POST["type"])) {

if ($_POST["type"] == "add") {
    if (isset($_POST["id"]) && isset($_POST["quantity"])) {
        $id = $_POST["id"];
        $quantity = $_POST["quantity"];
        if (isset($_SESSION["cart"])) {
            if (isset($_SESSION["cart"][$id])) {
                $_SESSION["cart"][$id] += $quantity;
            } else if ($quantity != 0) {
                $_SESSION["cart"][$id] = $quantity;
            }
        } else {
            $_SESSION["cart"] = array($id => $quantity);
        }
    } else {
        echo "$error_msg The selected wine could not be added.";
    }
} else if ($_POST["type"] == "update") {
    if (isset($_POST["id"]) && isset($_POST["quantity"])) {
        $id = $_POST["id"];
        $quantity = $_POST["quantity"];
		if ($quantity == 0) {
			unset($_SESSION["cart"][$id]);
		} else {
			$_SESSION["cart"][$id] = $quantity;
		}
    } else {
        echo "$error_msg The selected wine could not be updated.";
    }
} else if ($_POST["type"] == "remove") {
    if (isset($_POST["id"])) {
        unset($_SESSION["cart"][$_POST["id"]]);
    } else {
        echo "$error_msg The selected wine could not be removed.";
    }
} else if ($_POST["type"] == "order") {

    foreach (array("name", "email", "phone", "address", "city", "state",
                   "zipcode") as $input) {
        if (!isset($_POST[$input]) || !$_POST[$input]) {
            exit("$error_msg '$input' was not set.");
        }
    }

    if (!isset($_POST["agree"]) || !$_POST["agree"]) {
        exit("$error_msg The 21+ age and Terms of Service / Privacy Policy agreement was not agreed to.");
    }

    if (!isset($_SESSION["cart"]) || count($_SESSION["cart"]) == 0) {
        exit("$error_msg The shopping cart is empty.");
    }

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zipcode = $_POST["zipcode"];
    $agree = $_POST["agree"];
    $comment = $_POST["comment"];

//    $to = "acronymsubmissions@gmail.com";
//    $to = "sanjay@shyrwines.com";
    $to = "yash@shyrwines.com";
    $subject = "Order Inquiry from $name";

    $headers = "From: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $pr = "style='padding-right:10px'";
    $tp = "style='padding-right:10px; text-align:right'";
    $ctp = "colspan='4' $tp";

    $message = "Name: $name<br>Email: $email<br>Phone Number: $phone<br>"
                     . "Address: $address, $city, $state $zipcode<br><br>";
    $subTotal = 0.0;
    $message .= "<table>";
    foreach ($_SESSION["cart"] as $id => $quantity) {
        $result = mysql_query("SELECT * FROM WineTable WHERE ID = $id LIMIT 1");
        $wine = mysql_fetch_assoc($result);
        $unitTotalPrice = $wine["Price"] * $quantity;
        $subTotal += $unitTotalPrice;

        $message .= "<tr>";
        $message .= sprintf("<td $pr>%s</td>", $wine["Name"]);
        $message .= "<td $pr>$quantity</td>";
        $message .= "<td $pr>x</td>";
        $message .= sprintf("<td $pr>$%.2f</td>", $wine["Price"]);
        $message .= "<td $pr>=</td>";
        $message .= sprintf("<td $tp>$%.2f</td>", $unitTotalPrice);
        $message .= "</tr>";
    }
    $tax = round($subTotal * 0.0875, 2);
    $total = round($subTotal * 1.0875, 2);

    $message .= sprintf("<tr><td $ctp>Subtotal</td><td $pr>=</td><td $tp>$%.2f</td></tr>", $subTotal);
    $message .= sprintf("<tr><td $ctp>Tax</td><td $pr>=</td><td $tp>$%.2f</td></tr>", $tax);
    $message .= sprintf("<tr><td $ctp>Total</td><td $pr>=</td><td $tp>$%.2f</td></tr>", $total);
    $message .= "</table>";
    $message .= "<br>Comment: $comment";

//    echo $to;
//    echo $subject;
//    echo $message;
//    echo $headers;

    $mailed = mail($to, $subject, $message, $headers);
//    echo $mailed;
    
//    try {
//        $mailed = mail($to, $subject, $message, $headers);
//        echo "mailed? result: ";
//        echo $mailed;
//    } catch (Exception $e) {
//        echo "Exception";
//        exit("$e");
//    }
//    unset($_SESSION["cart"]);
} else if ($_POST["type"] == "addmany") {
    $_SESSION["cart"] = array(1 => 5, 3 => 7, 2 => 1, 5 => 11, 7 => 2);
}

}

?>
