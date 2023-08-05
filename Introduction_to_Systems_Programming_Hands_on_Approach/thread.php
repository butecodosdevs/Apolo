<?php

define("DOWNLOAD_SPEED", $argv[3]);

function createBaseDir()
{
    $destiny = __DIR__.DIRECTORY_SEPARATOR."pdfs";
    
    if(!file_exists($destiny)) {
        mkdir($destiny);
    }

    return $destiny;
}

function downloadPdf($link, $filename)
{
    $baseDir = createBaseDir();
    $stream = fopen($link, "rb");
    $file = fopen($baseDir.DIRECTORY_SEPARATOR.$filename, "wb");
    while(!feof($stream)) {
        fwrite(
            $file,
            fread($stream, DOWNLOAD_SPEED),
            DOWNLOAD_SPEED
        );
    }
    fclose($file);
    fclose($stream);
}

downloadPdf($argv[1], $argv[2]);
