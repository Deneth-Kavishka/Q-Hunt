<?php
$urls = [
    'https://assets.mixkit.co/active_storage/sfx/2011/2011-preview.mp3',
    'https://assets.mixkit.co/active_storage/sfx/2000/2000-preview.mp3',
    'https://assets.mixkit.co/active_storage/sfx/2003/2003-preview.mp3',
    'https://assets.mixkit.co/active_storage/sfx/1435/1435-preview.mp3',
    'https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3'
];
foreach($urls as $u) {
    $headers = @get_headers($u);
    echo $u . " -> " . ($headers ? substr($headers[0], 9, 3) : 'FAIL') . "\n";
}
