<?php
include_once("includes/nav.php");

function panel($sel, $adj, $src) {
    echo "<a id='$adj' class='panel' href='all-wines?$sel'>";
        echo "<div class='panel-text-wrapper'>";
            echo "<div class='panel-text'>$adj</div>";
        echo "</div>";
        echo "<img src='assets/images/$src' />";
        echo "<div class='panel-dimmer'></div>";
    echo "</a>";
}

?>

<div id="content">
    
<div class="header">Browse by Varietal</div>
<div class="panel-container" id="varietals">
    <?php
    panel("v=Cabernet Sauvignon", "Cabernet Sauvignon", "cabernet.jpg");
    panel("v=Syrah", "Syrah", "syrah.jpg");
    panel("v=Zinfandel", "Zinfandel", "zinfandel.jpg");
    panel("v=Pinot Noir", "Pinot Noir", "pinot.jpg");
    ?>
</div>

<div class="header">Browse by Country</div>
<div class="panel-container" id="countries">
    <?php
    panel("c=USA", "American", "usa.jpg");
    panel("c=Italy", "Italian", "italy.jpg");
    panel("c=Spain", "Spanish", "spain.jpg");
    panel("c=France", "French", "france.jpg");
    ?>
</div>

</div>

<?php include_once("includes/footer.php"); ?>
