<?php
require_once 'config.php';

// Connect to database securely
function connect_db() {
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (!$link) {
        error_log("Database connection failed: " . mysqli_connect_error());
        die("Internal server error.");
    }
    
    mysqli_set_charset($link, DB_CHARSET);
    return $link;
}

// Sanitize input for column names (only allow letters, numbers and underscores)
function sanitize_column_name($column) {
    return preg_replace('/[^a-zA-Z0-9_]/', '', $column);
}

// Initialize variables
$a = '';  // Primary grouping column
$b = '';  // Secondary grouping column
$g = '';  // Tertiary grouping column

// Sanitize columns
$a = sanitize_column_name($a);
$b = sanitize_column_name($b);
$g = sanitize_column_name($g);

// Default database table
$db = 'diccionario_unesco';

// Ordering and counting parameters
$o = true; // Count by default
$as = false; // Descending order

// Determine ordering logic
$order_by = $o ? "COUNT($a)" : $a;
$order_dir = $as ? "ASC" : "DESC";
$min_count = ($a === '') ? 0 : 0; // Can customize if needed

// Database connection
$link = connect_db();

// Main query
$array = array();
$sql = "SELECT $a, COUNT($a) 
        FROM $db 
        GROUP BY $a 
        HAVING COUNT($a) > $min_count 
        ORDER BY $order_by $order_dir";

$result = $link->query($sql);

foreach($result as $row) {
    if ($b !== '') {
        $c = $row[$a];
        $array2 = array();
        
        $sql2 = "SELECT $b, COUNT($b) 
                 FROM $db 
                 WHERE $a='" . $link->real_escape_string($c) . "'
                 GROUP BY $b 
                 ORDER BY $b ASC";

        $result2 = $link->query($sql2);

        foreach($result2 as $row2) {
            $subitems = '';

            if ($g !== '') {
                $h = $row2[$b];
                $array3 = array();

                $sql3 = "SELECT $g, COUNT($g)
                         FROM $db
                         WHERE $a='" . $link->real_escape_string($c) . "' AND $b='" . $link->real_escape_string($h) . "'
                         GROUP BY $g 
                         ORDER BY $g ASC";

                $result3 = $link->query($sql3);

                foreach($result3 as $row3) {
                    $array3[] = '"' . $row3[$g] . '":' . $row3["COUNT($g)"];
                }

                $subitems = ', "' . $g . '":{' . implode(', ', $array3) . '}';
            }

            $d = $row2[$b];
            $array2[] = '{"' . $d . '":' . $row2["COUNT($b)"] . $subitems . '}';
        }

        $details = ', "' . $b . '":[' . implode(', ', $array2) . ']';
    } else {
        $details = '';
    }

    $f = $row[$a];
    $array[] = '{"' . $a . '":"' . $f . '", "value":' . $row["COUNT($a)"] . $details . '}';
}

// Create JSON structure
$var_data = '[' . implode(",", $array) . ']';
$var_data = str_replace('_', ' ', $var_data);
$data = json_decode($var_data);

// Output the result
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);

// Close database connection
$link->close();
?>