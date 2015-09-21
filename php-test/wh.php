<?php

$text = (isset($_POST['search'])) ? $_POST['search'] : '';

if ('' === $text) die();

define('DB_HOST', "localhost");     // db host or ip
define('DB_USER', "root");          // user
define('DB_PASS', "");         // password
define('DB_NAME', "wallethub");     // schema name

$data = searchDB($text);
echo json_encode($data);

/**
 * Use PDO to open a connection to DB
 */
function connectDB() {
    $db = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

/**
 * Queries the DB to search for $text
 *
 * @param $text
 *
 * @return array
 */
function searchDB($text) {
    $db = connectDB();
    $stmt = $db->prepare("SELECT location, slug FROM population WHERE location LIKE ? ORDER BY population DESC LIMIT 10");

    $results = array();

    $text .= '%';
    $stmt->bindParam(1, $text, PDO::PARAM_STR, 100);

    if ($stmt->execute()) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        trigger_error('Error running query.', E_USER_ERROR);
    }

    $db = null; // close db connection

    return $results;
}
