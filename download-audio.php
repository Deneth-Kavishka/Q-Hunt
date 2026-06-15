<?php
$context = stream_context_create([
    "http" => [
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
    ]
]);
$html = file_get_contents('https://pixabay.com/music/orchestral-award-award-music-507993/', false, $context);

if (preg_match('/https:\/\/cdn\.pixabay\.com\/audio\/[a-zA-Z0-9\/_-]+\.mp3/', $html, $matches)) {
    $url = $matches[0];
    echo "Found URL: " . $url . "\n";
    if (!is_dir('public/audio')) {
        mkdir('public/audio', 0777, true);
    }
    file_put_contents('public/audio/award.mp3', file_get_contents($url, false, $context));
    echo "Downloaded successfully.\n";
} else {
    echo "URL not found in HTML. Snippet:\n";
    echo substr($html, 0, 1000);
}
?>
