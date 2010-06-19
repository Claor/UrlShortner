<?php
error_reporting (0);
header ("Content-Type: text/plain");
if (!isset ($_GET['url']) or
        empty ($_GET['url']))
    die (json_encode (array ("Error" => "No link Detected")));

include_once ('config.php');
include_once ('UrlShortner.class.php');

$_GET['url'] = preg_replace ('/^(http|https|ftp):\//', '\1://', $_GET['url']);

try
{
    $db = new UrlShortner ($database);
    echo json_encode (array ("short_link" => $db->insert ($_GET['url'])));
}
catch (Exception $e)
{
    die (json_encode (array ("Error" => $e->getMessage ())));
}
?>
