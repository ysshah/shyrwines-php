$(document).ready(function () {

    /* Autocomplete function. */
    $("#nav-search").keyup(function () {
        if ($("#nav-search").val()) {
            $.ajax({
                url: "ajax/autocomplete.php",
                type: "POST",
                data: {"q": $("#nav-search").val()},
                success: function (data) {
                    if (data) {
                        $("#auto-container").html(data);
                        $("#auto-container").show();
                    } else {
                        $("#auto-container").hide();
                    }
                }
            });
        } else {
            $("#auto-container").hide();
        }
    });

    /* Expand or contract sorting options upon arrow click. */
    $(".sort-arrow").click(function () {
        var window = $(this).siblings("div.select-window.sort").get(0);
        if ($(this).hasClass("left")) {
            $(this).data("original", window.firstChild.style.marginTop);
            var child = $(window.firstChild);
            $(window).css({"height": Math.min(407, child.height()),
                           "overflow-y": "scroll",
                           "box-shadow": "0px 0px 5px #8e0606"});
            $(this).removeClass("left");
            $(this).addClass("down");
            child.css("margin-top", "0px");
        } else {
            $(window).css({"height": "50px",
                           "overflow-y": "hidden",
                           "box-shadow": "none"});
            $(window).animate({scrollTop: "0px"});
            $(this).removeClass("down");
            $(this).addClass("left");
            $(window.firstChild).css("margin-top", $(this).data("original"));
            $(this).data("clicked", false);
        }
    });

    /* Expand or contract sorting options upon hover. */
    $(".select-window.sort").hover(function () {
        $(this).siblings(".sort-arrow").removeClass("left");
        $(this).siblings(".sort-arrow").addClass("down");
        var child = $(this.firstChild);
        $(this).data("original", this.firstChild.style.marginTop);
        $(this).css({"height": Math.min(407, child.height()),
                           "overflow-y": "scroll",
                           "box-shadow": "0px 0px 5px #8e0606"});
        child.css("margin-top", "0px");
    }, function () {
        $(this).siblings(".sort-arrow").removeClass("down");
        $(this).siblings(".sort-arrow").addClass("left");
        $(this).css({"height": "50px",
                           "overflow-y": "hidden",
                           "box-shadow": "none"});
        $(this).animate({scrollTop: "0px"});
        $(this.firstChild).css("margin-top", $(this).data("original"));
    });

    /* Adds a wine to the cart from the VIEW page. */
    $(".view-add").click(function () {
        var quantity = Number($(this).siblings("input#view-quantity").val());
        if (quantity > 0) {
            var id = $(this).attr("id");
            var name = $("div#view-title").text();
            $("#wine-added-name").text(name + " (" + quantity + ")");
            $.ajax({
                type:    "POST",
                url:     "ajax/update-cart.php",
                data:    "type=add&id=" + id + "&quantity=" + quantity,
                success: function (msg) {
                    if (msg) {
                        alert(msg);
                    } else {
                        $("div#popover-content").slideDown().delay(1000).slideUp();
                    }
                }
            });
        }
    });

    /* Remove a wine bottle from the cart. */
    function removeWine(wine, id) {
        $.ajax({
            type:    "POST",
            url:     "ajax/update-cart.php",
            data:    "type=remove&id=" + id,
            success: function (msg) {
                wine.fadeOut(function () {
                    $("div#cart-content").load("ajax/cart-items.php");
                });
            }
        });
    }

    /* Update a wine's quantity in the cart upon its quantity focusout. */
    $(document).on("focusout", "div.cart-quantity input", function () {
        var id = $(this).attr("id");
        var wine = $(this).parent().parent();
        var quantity = $(this).val();
        if (quantity == 0) {
            removeWine(wine, id);
        } else {
            $.ajax({
                type:    "POST",
                url:     "ajax/update-cart.php",
                data:    "type=update&id=" + id + "&quantity=" + quantity,
                success: function (msg) {
                    $("div#cart-content").load("ajax/cart-items.php");
                }
            });
        }
    });

    /* Remove a wine from the cart upon clicking the "remove" button. */
    $(document).on("click", "button.remove", function () {
        var id = $(this).attr("id");
        var wine = $(this).parent().parent();
        removeWine(wine, id);
    });

    /* Validate an email address. */
    function validate_email(passed) {
        var email = $("input#email");
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!email.val()) {
            email.css("border-color", "red");
            email.parent().css("color", "red");
            email.attr("placeholder", "Required");
            return false;
        } else if (!regex.test(email.val())) {
            $("div#form-email label").text("Invalid email address.");
            email.css("border-color", "red");
            email.parent().css("color", "red");
            return false;
        } else {
            $("div#form-email label").text("Email");
            email.parent().css("color", "black");
            email.css("border-color", "rgba(142, 6, 6, 0.5)");
            return passed;
        }
    }

    /* Validate a zipcode. Either 55555 or 55555-5555. */
    function validate_zipcode(passed) {
        var zipcode = $("input#zipcode");
        if (!zipcode.val()) {
            zipcode.css("border-color", "red");
            zipcode.parent().css("color", "red");
            zipcode.attr("placeholder", "Required");
            return false;
        } else if (!/^\d{5}(-\d{4})?$/.test(zipcode.val())) {
            $("div#form-zipcode label").text("Invalid zip.");
            zipcode.css("border-color", "red");
            zipcode.parent().css("color", "red");
            return false;
        } else {
            $("div#form-zipcode label").text("Zip Code");
            zipcode.parent().css("color", "black");
            zipcode.css("border-color", "rgba(142, 6, 6, 0.5)");
            return passed;
        }
    }

    /* Validate the order form on the cart page. */
    function validate() {
        var passed = true;
        $("input.order-form:not(input#email, input#zipcode),"
            + "select#state").each(function () {
            if (!$(this).val()) {
                $(this).css("border-color", "red");
                $(this).parent().css("color", "red");
                $(this).attr("placeholder", "Required");
                passed = false;
            }
        });
        if (!$("input#agree").prop("checked")) {
            $("div#form-agree label").css("color", "red");
            passed = false;
        }
        passed = validate_email(passed);
        passed = validate_zipcode(passed);
        return passed;
    }

    /* Change the agreement text to red or black depending on agreement. */
    $("input#agree").change(function () {
        if (!$(this).prop("checked")) {
            $("div#form-agree label").css("color", "red");
        } else {
            $("div#form-agree label").css("color", "black");
        }
    });

    /* Validate input fields upon focusout. */
    $("input.order-form:not(input#email, input#zipcode),"
        + "select#state").focusout(function () {
        if ($(this).val()) {
            $(this).parent().css("color", "black");
            $(this).css("border-color", "rgba(142, 6, 6, 0.5)");
        } else {
            $(this).css("border-color", "red");
            $(this).parent().css("color", "red");
            $(this).attr("placeholder", "Required");
        }
    });

    /* Validate email upon focusout. */
    $("input#email").focusout(function () {
        validate_email(true);
    });

    /* Validate zipcode upon focusout. */
    $("input#zipcode").focusout(function () {
        validate_zipcode(true);
    });

    /* Validate order form upon "Submit Order" button click. */
    $("button#submit-order").click(function () {
        var form = $("#order-form");
        var dat = form.find("#name, #email, #phone, #address, "
                          + "#city, #state, #zipcode, #comment, "
                          + "#agree").serialize();
        if (validate()) {
            $.ajax({
                type:    "POST",
                url:    "ajax/update-cart.php",
                data:    "type=order&" + dat,
                success: function (msg) {
                    if (msg) {
                        alert(msg);
                    } else {
                        $("div#cart-content").slideUp();
                        $("form#order-form").slideUp(function () {
                            $("div#dimmer").fadeIn(function () {
                                $("div#thanks-wrapper").fadeIn();
                            });
                        });
                    }
                }
            });
        }
    });

});
