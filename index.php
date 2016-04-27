<?php
error_reporting (0);
include_once ('core/shortner.class.php');
include_once ('core/template.class.php');
include_once ('config.php');

$template = new UrlTemplate ();

if (array_key_exists ("redirect", $_GET))
{
    try
    {
        $db = new UrlShortner ($database);
        header ("Location: " . $db->get ($_GET['redirect']));
    }
    catch (Exception $e)
    {
        echo $template->error ($e->getMessage ());
    }
}
else if (array_key_exists ("preview", $_GET))
{
    try
    {
        $db = new UrlShortner ($database);
        echo $template->preview ($db->get ($_GET['preview']));
    }
    catch (Exception $e)
    {
        echo $template->error ($e->getMessage ());
    }
}
else if (array_key_exists ("url", $_POST))
{
    try
    {
		$db = new UrlShortner ($database);
        echo $template->link ($db->insert($_POST['url'],$_POST['key'],$_POST['temp']));
    }
    catch (Exception $e)
    {
        echo $template->error ($e->getMessage ());
    }
}
else
{
    echo $template->index ();
}
?>
