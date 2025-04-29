<?php
require_once 'config.php';

// Secure database connection
function connect_db() {
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (!$link) {
        error_log("Database connection failed: " . mysqli_connect_error());
        die("Internal server error.");
    }

    mysqli_set_charset($link, DB_CHARSET);
    return $link;
}

// Sanitize input string
function sanitize_input($input) {
    $input = mb_strtolower(trim($input));
    $input = preg_replace('/[ºª!|·#¢%∞&¬÷(“)”=≠?´¡¿‚`^}´¨{,-_–.:…,;„≥≤~@/$*"\'<>\\[\\]+]/', ' ', $input);
    $input = preg_replace('/\\s+/', '_', $input);
    return $input;
}

// Initialize variables
$palabra = 'tirar'; // Default input
$buscar = '0';      // 0 = word -> meaning, 1 = meaning -> word
$filtro = 'genero'; // Filter by
$contar = false;
$ascendente = false;

// Connect to database
$link = connect_db();

// Sanitize variables
$palabra = sanitize_input($palabra);

// Validate
if (empty($palabra)) {
    echo json_encode(["error" => "Please enter a search term."]);
    $link->close();
    exit;
}

// Determine columns based on search direction
if ($buscar === "0") {
    $a = 'palabra';
    $g = 'significado';
} elseif ($buscar === "1") {
    $a = 'significado';
    $g = 'palabra';
} else {
    echo json_encode(["error" => "Invalid search type."]);
    $link->close();
    exit;
}

$b = $filtro;
$order_by = $contar ? "COUNT($a)" : $a;
$order_direction = $ascendente ? "ASC" : "DESC";
$min_count = ($a === 'palabra' || $a === 'significado') ? 2 : 0;

$array = [];

$palabra_esc = $link->real_escape_string($palabra);
$sql = "SELECT $a, COUNT($a) 
        FROM data_base 
        WHERE $a = '$palabra_esc' 
        GROUP BY $a 
        HAVING COUNT($a) > $min_count 
        ORDER BY $order_by $order_direction 
        LIMIT 1";

$result = $link->query($sql);

foreach ($result as $row) {
    $c = $row[$a];
    $array2 = [];

    if (!empty($b)) {
        $sql2 = "SELECT $b, COUNT($b) 
                FROM data_base 
                WHERE $a = '" . $link->real_escape_string($c) . "' 
                GROUP BY $b 
                ORDER BY $b ASC";
        $result2 = $link->query($sql2);

        foreach ($result2 as $row2) {
            $array3 = [];

            if (!empty($g)) {
                $h = $row2[$b];
                $sql3 = "SELECT $g, COUNT($g) 
                        FROM data_base 
                        WHERE $a = '" . $link->real_escape_string($c) . "' 
                          AND $b = '" . $link->real_escape_string($h) . "' 
                        GROUP BY $g 
                        ORDER BY COUNT($g) DESC 
                        LIMIT 1";
                $result3 = $link->query($sql3);

                foreach ($result3 as $row3) {
                    $array3[] = '"' . $row3[$g] . '":' . $row3["COUNT($g)"];
                }
            }

            $d = $row2[$b];
            $array2[] = '{"' . $d . '":' . $row2["COUNT($b)"] . (!empty($array3) ? ', "' . $g . '":{' . implode(', ', $array3) . '}' : '') . '}';
        }
    }

    $array[] = '{"' . $a . '":"' . $c . '", "value":' . $row["COUNT($a)"] . (!empty($array2) ? ', "' . $b . '":[' . implode(', ', $array2) . ']' : '') . '}';
}

$var_data = '[' . implode(", ", $array) . ']';

// Final output
if ($var_data === '[]') {
    echo json_encode(["error" => "No results found. Please try another search term."]);
} else {
    $var_data = str_replace('_', ' ', $var_data);
    $data = json_decode($var_data);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

$link->close();
?>