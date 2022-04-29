<?php

require_once("functions/Shortener.php");

$urlShortener = new Shortener();

if (isset($_POST["url"]))
{
    $shortUrl = $urlShortener->validateReturnCode($_POST["url"]);
}
echo "past:  $shortUrl";
?>