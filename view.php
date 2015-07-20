<?php
if (!isset($_GET["id"])) {
    $page_title = "No wine found!";
    include_once("includes/nav.php");
    echo "<div id='page-title'>No wine found!</div>";
} else {
    include_once("includes/db.php");
    $id = (int) mysql_real_escape_string($_GET['id']);
    $result = mysql_query("SELECT * FROM WineTable WHERE ID = $id");
    $wine = mysql_fetch_assoc($result);

    $page_title = $wine["Name"];
    include_once("includes/nav.php");
    ?>

    <div id="view-title"><?php echo $wine["Name"]; ?></div>

    <div id="view-content">
        <div id="view-pic-wrapper">
        <?php
        if (file_exists("assets/images/wines/".$wine["SKU"].".jpg")) {
            echo "<img id='view-pic' src='assets/images/wines/".$wine["SKU"].".jpg' alt='Wine Bottle Picture'>";
        } else {
            echo "<img id='view-default-pic' src='assets/images/bottle.svg' alt='Default Wine Bottle Picture'>";
        }
        ?>
        </div>

        <div id="view-info-wrapper">
            <div id="view-ratings">
            <?php
            $ratings = array(
                "AG" => "Antonio Galloni", "D" => "Decanter",
                "JH" => "James Halliday", "JS" => "James Suckling",
                "RP" => "Robert Parker", "ST" => "Stephen Tanzer",
                "WandS" => "Wine and Spirits", "WA" => "Wine Advocate",
                "WE" => "Wine Enthusiast", "WS" => "Wine Spectator");
            foreach ($ratings as $rater_abbv => $rater) {
                if ($wine[$rater_abbv] != "0") {
                    echo "<div class='view-rating'>$wine[$rater_abbv] points</div>";
                    echo "<div class='view-rater'>$rater</div><br>";
                }
            }
            ?>
            </div>
            <?php if ($wine["Description"]) { ?>
                <div id="view-description-title" class="view-info-title">Winemaker's Notes</div>
                <div class="view-info" id="view-description"><?php echo $wine["Description"]; ?></div>
            <?php } ?>


            <div class="view-subinfo">
                <div class="view-info-title">Country</div>
                <a href="all-wines?c=<?php echo $wine["Country"]; ?>" class="view-info" id="view-country"><?php echo $wine["Country"]; ?></a>
            </div>
            <?php if ($wine["Region"]) { ?>
            <div class="view-subinfo">
                <div class="view-info-title">Region</div>
                <a class="view-info" id="view-region" href="all-wines?r=<?php echo $wine["Region"]; ?>"><?php echo $wine["Region"]; ?></a>
            </div>
            <?php } ?>
            <?php if ($wine["Appellation"]) { ?>
            <div class="view-subinfo">
                <div class="view-info-title">Appellation</div>
                <a class="view-info" id="view-appellation" href="all-wines?a=<?php echo $wine["Appellation"]; ?>"><?php echo $wine["Appellation"]; ?></a>
            </div>
            <?php } ?>
            <div class="view-subinfo">
                <div class="view-info-title">Winery</div>
                <div class="view-info" id="view-winery"><?php echo $wine["Winery"]; ?></div>
            </div>
            <div class="view-subinfo">
                <div class="view-info-title">Varietal</div>
                <a class="view-info" id="view-varietal" href="all-wines?v=<?php echo $wine["Varietal"]; ?>"><?php echo $wine["Varietal"]; ?></a>
            </div>
            <div class="view-subinfo">
                <div class="view-info-title">Type</div>
                <div class="view-info" id="view-type"><?php echo $wine["Type"]; ?></div>
            </div>
        </div>
        <div id="view-purchase-wrapper">
            <div id="view-price">$<?php echo $wine["Price"]; ?></div>
            <div id="add-container">
                <div id="view-quantity-label">Quantity:</div>
                <input onkeypress='return event.charCode >= 48 && event.charCode <= 57' id='view-quantity' value='1' type="text"></input>
                <button id="<?php echo $id; ?>" class="view-add">Add to Cart</button>
            </div>
        </div>
    </div>
<?php } ?>

<?php include_once("includes/footer.php"); ?>
