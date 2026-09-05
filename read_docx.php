<?php
$zip = new ZipArchive;
if ($zip->open('C:\laragon\www\AmiraUSS\docs\Berita Acara RTM SPMI USS.docx') === TRUE) {
    $content = $zip->getFromName('word/document.xml');
    $zip->close();
    $text = strip_tags(str_replace(['<w:p', '</w:p>'], ["\n<w:p", "\n</w:p>"], $content));
    echo trim($text);
} else {
    echo 'Failed to open docx';
}
