<?php
include_once(dirname(__DIR__)."/includes/db.php");

if (isset($_POST["q"])) {
    $query = $_POST["q"];
    $q = mysql_real_escape_string(str_replace(
        array("|", "%", "_", "*"), array("||", "|%", "|_", "|*"), $query));

    $name_results = mysql_query("SELECT * FROM WineTable WHERE Name LIKE '%$q%' ESCAPE '|'");
    while ($wine = mysql_fetch_assoc($name_results)) {
        echo "<a href='view?id=".$wine["ID"]."' class='auto-item'><div class='center'>".preg_replace("/$query/i", "<div class='match'>\$0</div>", $wine["Name"])."</div></a>";
    }

    $others = array("Country", "Varietal", "Region", "Appellation");

    foreach ($others as $var) {
        $var_results = mysql_query("SELECT DISTINCT($var) FROM WineTable WHERE $var LIKE '%$q%' ESCAPE '|'");
        while ($res = mysql_fetch_assoc($var_results)) {
            echo "<a href='all-wines?".strtolower(substr($var, 0, 1))."=".$res[$var]."' class='auto-item'><div class='center'>$var: ".preg_replace("/$query/i", "<div class='match'>\$0</div>", $res[$var])."</div></a>";
        }
    }
}

?>
