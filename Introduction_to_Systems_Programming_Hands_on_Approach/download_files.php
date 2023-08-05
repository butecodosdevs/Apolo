<?php

define("URL_LINK", "https://www.cs.purdue.edu/homes/grr/SystemsProgrammingBook");

function findList($html)
{
    preg_match("/<ul.+>[\S\s]*<\/ul>/", $html, $matches);
    return $matches[0];
}

function extractItensFromListString($text)
{
    preg_match_all("/<li><a href=\"(.*)\">(.*)<\/a>/", $text, $matches);
    [
        1 => $urlPath,
        2 => $pdfName
    ] = $matches;

    $urlPath = array_map(function($path) {
        return URL_LINK."/$path";
    }, $urlPath);

    $arr = [];

    for($i = 0; $i < count($urlPath); $i++) {
        $url = $urlPath[$i];
        $name = preg_replace("/\s/", "_", $pdfName[$i]).".pdf";
        $arr[
            $name
        ] = $url;
    }

    return $arr;
}

function execAsyncDownload($pdfs) 
{
    $path = __DIR__.DIRECTORY_SEPARATOR."thread.php";
    foreach($pdfs as $pdfName => $link) {
        echo "Executing php \"$path\" \"$link\" \"$pdfName\" 4096".PHP_EOL;
        exec("php $path $link $pdfName 4096 > /dev/null 2>&1 &");
    }
}

$html = file_get_contents(URL_LINK);

$pdfs = extractItensFromListString(
    findList($html)
);

execAsyncDownload($pdfs);