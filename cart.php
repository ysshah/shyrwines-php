<?php
$page_title = "Cart";
include_once("includes/nav.php");
?>

<div id="page-title">Cart</div>
<div id="cart-content"><?php include_once("ajax/cart-items.php") ?></div>

<?php if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) { ?>
<form id="order-form" action="javascript:void(0);" novalidate>
    <div id="order-form-title">Order Form</div>
    <div id="form-name" class="form-divs">
        <label for="name">Name</label>
        <input id="name" class="order-form" name="name" type="text" required />
    </div>
    <div id="form-email" class="form-divs">
        <label>Email</label>
        <input id="email" class="order-form" name="email" type="text" required />
    </div>
    <div id="form-phone" class="form-divs">
        <label>Phone Number</label>
        <input id="phone" class="order-form" name="phone" type="text" required />
    </div>
    <div id="form-address" class="form-divs">
        <label>Street Address</label>
        <input id="address" class="order-form" name="address" type="text" required />
    </div>
    <div id="form-city" class="form-divs">
        <label>City</label>
        <input id="city" name="city" class="order-form" type="text" required />
    </div>
    <div id="form-state" class="form-divs">
        <label>State</label>
        <select id="state" name="state" class="order-form" required>
            <option id="default" disabled selected></option>
            <option>AL</option>
            <option>AK</option>
            <option>AZ</option>
            <option>AR</option>
            <option id="CA">CA</option>
            <option>CO</option>
            <option>CT</option>
            <option>DE</option>
            <option>FL</option>
            <option>GA</option>
            <option>HI</option>
            <option>ID</option>
            <option>IL</option>
            <option>IN</option>
            <option>IA</option>
            <option>KS</option>
            <option>KY</option>
            <option>LA</option>
            <option>ME</option>
            <option>MD</option>
            <option>MA</option>
            <option>MI</option>
            <option>MN</option>
            <option>MS</option>
            <option>MO</option>
            <option>MT</option>
            <option>NE</option>
            <option>NV</option>
            <option>NH</option>
            <option>NJ</option>
            <option>NM</option>
            <option>NY</option>
            <option>NC</option>
            <option>ND</option>
            <option>OH</option>
            <option>OK</option>
            <option>OR</option>
            <option>PA</option>
            <option>RI</option>
            <option>SC</option>
            <option>SD</option>
            <option>TN</option>
            <option>TX</option>
            <option>UT</option>
            <option>VT</option>
            <option>VA</option>
            <option>WA</option>
            <option>WV</option>
            <option>WI</option>
            <option>WY</option>
        </select>
    </div>
    <div id="form-zipcode" class="form-divs">
        <label>Zip Code</label>
        <input id="zipcode" name="zipcode" class="order-form" type="text" required />
    </div>
    <div id="form-comment" class="form-divs">
        <label>Include a comment (Optional)</label>
        <textarea id="comment" name="comment" class="order-form"></textarea>
    </div>
    <div id="form-agree" class="form-divs">
        <input id="agree" name="agree" type="checkbox" title="Please agree to our terms of service and privacy policy!" required />
        <label>I confirm that I am at least the age of 21, and have read and agreed to the 
            <a href="terms-of-service" class="link">Terms of Service</a> and 
            <a href="privacy-policy" class="link">Privacy Policy</a>.
        </label>
    </div>
    <button id="submit-order">Submit Order</button>
</form>
<div id="thanks-wrapper">
    <div id="thanks-container">
        <div id="thanks">Thanks for your order!</div>
        <div id="reachout">We will reach out to you shortly.</div>
        <a href="/"><button id="continue-shopping">Continue Shopping</button></a>
    </div>
</div>
<?php } else { ?>
    <div id="empty-cart">Your cart is empty!</div>
<?php } ?>

<?php include_once("includes/footer.php"); ?>
