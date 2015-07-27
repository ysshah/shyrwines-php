<?php
$page_title = "All Wines";
include_once("includes/nav.php");

$ratings = array("JH", "JS", "RP", "ST", "AG", "D", "WA", "WE", "WS", "WandS");

/* Get conditions from URL. */
$country = isset($_GET['c']) ? mysql_real_escape_string($_GET['c']) : NULL;
$varietal = isset($_GET['v']) ? mysql_real_escape_string($_GET['v']) : NULL;
$region = isset($_GET['r']) ? mysql_real_escape_string($_GET['r']) : NULL;
$appellation = isset($_GET['a']) ? mysql_real_escape_string($_GET['a']) : NULL;
$columns = array("Country" => $country, "Varietal" => $varietal,
                 "Region" => $region, "Appellation" => $appellation);

/* Get search query but replace special characters to avoid MySQL injection. */
if (isset($_GET["q"])) {
    $searchQuery = $_GET["q"];
    $search = mysql_real_escape_string(str_replace(
        array("|", "%", "_"), array("||", "|%", "|_"), $searchQuery));
} else {
    $search = null;
}

/* Generate conditions for MySQL query from column options and search query. */
$conds = "";
foreach ($columns as $col_name => $col_val) {
    if ($col_val) {
        $conds .= " $col_name = '$col_val' AND";
    }
}
/* Escape | since we defined that to be our escape character above. */
if ($search) {
    $conds .= " Name LIKE '%$search%' ESCAPE '|' AND";
}
/* Remove the last " AND" from the conditions. */
$conds = !$conds ?: substr($conds, 0, -4);

/* Append sorting options to end of MySQL query. _GET["s"] is in the form of
 * (v|p|a)(a|d) where the first letter describes what to sort by, and the
 * second letter decides between ascending and descending. */
$s = isset($_GET["s"]) ? mysql_real_escape_string($_GET["s"]) : NULL;
switch ($s[0]) {
    case 'v':
        $sort = " ORDER BY Year ";
        break;
    case 'p':
        $sort = " ORDER BY Price ";
        break;
    case 'a':
    default:
        $sort = " ORDER BY IF(Year = 2100, Name, SUBSTR(Name, 6)) ";
        break;
}
switch ($s[1]) {
    case 'a':
        $sort .= "ASC";
        break;
    case 'd':
        $sort .= "DESC";
        break;
    default:
        break;
}

function createOptions($colname, $key, $col) {
    global $columns, $conds;
    $query = "SELECT DISTINCT($colname) FROM WineTable WHERE";
    if (!$col) {
        $query .= $conds;
    } else {
        $new_conds = "";
        foreach ($columns as $col_name => $col_val) {
            if ($col_name != $colname && $col_val) {
                $new_conds .= " $col_name = '$col_val' AND";
            }
        }
        $query = $new_conds ? $query.substr($new_conds, 0, -4) : substr($query, 0, -6);
    }
    $result = mysql_query("$query ORDER BY $colname");
    $options = "";
    $count = 51;
    $offset = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $this_col = $row[$colname];
        if (($col && $this_col && $col == $this_col) || mysql_num_rows($result) == 1) {
            $options .= '<div class="option selected">'.$this_col.'</div>'."\n";
            $offset = $count;
        } else if ($this_col) {
            $options .= '<a href="?'.build_query($key, $this_col).'" class="option">'.$this_col.'</a>'."\n";
            $count += 51;
        }
    }
    $any = array_filter(array_merge($_GET, array("p" => 1)), function($k) use($key){
        return $k != $key;
    }, ARRAY_FILTER_USE_KEY);
    echo "<div class='sort-option-wrapper'>";
    echo "<div class='sort-option-title'>$colname:</div>";

    echo '<div class="select-window sort">';
        echo '<div class="select" style="margin-top:-'.$offset.'px">';
            echo '<a href="?'.http_build_query($any).'" class="option">Any</a>';
            echo $options;
        echo "</div>\n";
    echo "</div>\n";
    echo "<div class='sort-arrow left'></div>";
    echo "</div>";
}

/* Append or replace the existing http query with $KEY => $VALUE, and reset
 * the page number back to 1. */
function build_query($key, $value) {
    return http_build_query(array_merge($_GET, array($key => $value, "p" => 1)));
}

/* Create the list of page numbers, and give the page that we are on an id
 * of "selected". */
function createPages($page, $num_wines, $per_page) {
    $num_pages = ceil($num_wines / $per_page);
    $html = "<div class='pages'>";

    if ($num_pages <= 3) {
        for ($i = 1; $i < 4 && $i <= $num_pages; $i++) {
            if ($i == $page) {
                $html .= "<a class='page-num' id='selected'>$i</a>";
            } else {
                $html .= "<a class='page-num' href='?"
                    .http_build_query(array_merge($_GET, array("p" => $i)))
                    ."'>$i</a>";
            }
        }
    } else {
        if ($page < 3) {
            $sp = 1;
        } else if ($page == $num_pages) {
            $sp = $num_pages - 2;
        } else if ($page >= 3) {
            $sp = $page - 1;
        }
        if ($page >= 3) {
            $html .= "<a class='page-num' href='?"
                .http_build_query(array_merge($_GET, array("p" => 1)))
                ."'>1</a>";
        }
        if ($page > 3) {
            $html .= "<div class='page-dots'>...</div>";
        }
        for ($i = $sp; $i <= ($sp + 2); $i++) {
            if ($i == $page) {
                $html .= "<a class='page-num' id='selected'>$i</a>";
            } else {
                $html .= "<a class='page-num' href='?"
                .http_build_query(array_merge($_GET, array("p" => $i)))
                ."'>$i</a>";
            }
        }
        if ($page < $num_pages - 2) {
            $html .= "<div class='page-dots'>...</div>";
        }
        if ($page < $num_pages - 1) {
            $html .= "<a class='page-num' href='?"
                .http_build_query(array_merge($_GET, array("p" => $num_pages)))
                ."'>$num_pages</a>";
        }
    }

    $html .= "</div>";
    return $html;
}

?>

<div id="content">

<div id="sort-bar">
    <?php createOptions("Country", "c", $country); ?>
    <?php createOptions("Varietal", "v", $varietal); ?>
    <?php createOptions("Region", "r", $region); ?>
    <?php createOptions("Appellation", "a", $appellation); ?>
    <div class="sort-option-wrapper">
        <div class="sort-option-title">Sort By:</div>
        <?php
        /* Array to determine the margin-top offset depending on the option. */
        $sort_ops = array("aa" => 0, "ad" => 1, "va" => 2, "vd" => 3, "pa" => 4, "pd" => 5);

        echo "<div class='select-window sort'><div class='select' style='margin-top:-"
            .(isset($_GET["s"]) ? $sort_ops[$_GET["s"]] * 51 : "0px")."px'>";
        $sort_ops_str = array("aa" => "Alphabetical: A to Z", "ad" => "Alphabetical: Z to A",
                              "va" => "Vintage: Old to New", "vd" => "Vintage: New to Old",
                              "pa" => "Price: Low to High", "pd" => "Price: High to Low");
        foreach ($sort_ops_str as $s_abv => $s_str) {
            if ($s_abv == $s) {
                echo "<div class='option selected'>$s_str</div>";
            } else {
                echo '<a href="?'.build_query("s", $s_abv).'" class="option">'.$s_str.'</a>';
            }
        }
        echo "</div></div>";
        ?>
        <div class="sort-arrow left"></div>
    </div>
</div>

<div id="listed-wines">
    <?php
    $query = "SELECT * FROM WineTable WHERE $conds $sort";
    $per_page = 15;

    $result = mysql_query($query);
    $num_wines = mysql_num_rows($result);
    if ($num_wines == 0) {
        echo "<div id='info-bar'><div id='num-wines'>No wines found!</div></div>";
    } else {
        $wine_label = $num_wines == 1 ? "wine" : "wines";
        echo "<div id='info-bar'>";
        echo "<div id='num-wines'>$num_wines $wine_label</div>";
        if (isset($searchQuery)) {
            echo "<div id='search-info'>Results for \"<div id='search-query'>$searchQuery</div>\"</div>";
        }
        $page = isset($_GET["p"]) ? (int) mysql_real_escape_string($_GET["p"]) : 1;
        $pages_html = createPages($page, $num_wines, $per_page);
        echo $pages_html;
        echo "</div>";

        mysql_data_seek($result, ($page - 1) * $per_page);

        $count = 0;
        while (($wine = mysql_fetch_assoc($result)) && $count < 15) { ?>
        <a href="view?id=<?php echo $wine["ID"]; ?>" class="list-wine">
            <?php if (file_exists("assets/images/wines/".$wine["SKU"].".jpg")) { ?>
            <div class="list-pic-container">
            <img class="list-pic" src="assets/images/wines/<?php echo $wine["SKU"]; ?>.jpg" alt="Wine Bottle Picture" />
            </div>
            <?php } else { ?>
            <img class="default-pic" src="assets/images/bottle.svg" alt="Default Wine Bottle Picture" />
            <?php } ?>

            <div class="list-info">
                <div class="list-name"><?php echo $wine["Name"]; ?></div>
                <?php if ($wine["Winery"]) { ?>
                <div class="list-label">Winery</div>
                <div class="list-winery"><?php echo $wine["Winery"]; ?></div>
                <?php } ?>
                <div class="list-ratings">
                    <?php
                    foreach ($ratings as $rat) {
                        if ($wine[$rat] != "0") {
                            if ($rat == "WandS") {
                                echo "<div class='list-rater'>W&S</div>";
                            } else {
                                echo "<div class='list-rater'>$rat</div>";
                            }
                            echo "<div class='list-rating'>$wine[$rat]</div>";
                        }
                    }
                    ?>
                </div>
                <div class="list-price">$<?php echo $wine["Price"]; ?></div>
            </div>
        </a>
    <?php $count++; }
    echo "<div id='pages-bottom'>$pages_html</div>";
    } ?>
</div>

</div>

<?php include_once("includes/footer.php"); ?>
