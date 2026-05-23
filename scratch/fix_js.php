<?php
$files = [
    'd:/Project/Laravel/sweet-home/resources/views/admin/tenants/create.blade.php',
    'd:/Project/Laravel/sweet-home/resources/views/admin/tenants/edit.blade.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Find the first occurrence of the script block and remove it
    $pattern = '/<script>\s*function pfFile\(input, nameId\).*?renderChildren\(\);\s*}\);\s*<\/script>\s*/s';
    
    preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
    
    if (count($matches[0]) > 1) {
        // Remove the first one
        $firstMatchOffset = $matches[0][0][1];
        $firstMatchLength = strlen($matches[0][0][0]);
        $content = substr_replace($content, '', $firstMatchOffset, $firstMatchLength);
        file_put_contents($file, $content);
        echo "Fixed $file\n";
    } else {
        echo "No duplicates in $file\n";
    }
}
?>
