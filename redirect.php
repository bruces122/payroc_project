<?php

require_once("functions/Shortener.php");


$urlShortener = new Shortener();

if (isset($_GET['code']))
{
    $longUrl = $urlShortener->getLongSiteLocation($_GET['code']);
    header("Location: $longUrl");
    exit();
}

header("Location: index.php");
die();

?>
