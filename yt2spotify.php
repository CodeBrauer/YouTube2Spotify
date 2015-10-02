<?php
error_reporting(E_ERROR);
require 'vendor/autoload.php';
require 'config.php';
if (gethostname() === 'CodeBrauer.local') {
    include '../ref/ref.php';
}
$spotifyAPI   = new SpotifyWebAPI\SpotifyWebAPI();

$googleClient = new Google_Client();
$googleClient->setDeveloperKey($config['google-developer-key']);
$youtube      = new Google_Service_YouTube($googleClient);

if (php_sapi_name() !== 'cli') {
    die('Run this script with your terminal. This not a php application for your webserver.' . PHP_EOL);
}

if (!isset($argv[1])) {
    die('Please provide a YouTube Playlist ID or URL' . PHP_EOL);
}

if (strpos($argv[1], 'youtube.com/playlist') === false) {
    $url = $argv[1];
} else {
    $urlParts = parse_url($argv[1], PHP_URL_QUERY);
    $url      = explode('=', $urlParts)[1];
}

do {
    $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => rawurlencode($url),
        'maxResults' => 50,
        'pageToken' => $nextPageToken));
    foreach ($playlistItemsResponse['items'] as $playlistItem) {
        $trackName = str_ireplace($config['ignore-video-title'], '', $playlistItem['snippet']['title']);
        $spotifyURI = $spotifyAPI->search(trim($trackName), 'track')->tracks->items[0]->uri;
        if (is_string($spotifyURI)) {
            echo "\033[32m ✔ \033[0m" . $trackName . PHP_EOL;
            $result[] = $spotifyURI;
        } else {
            echo "\033[31m ✖ \033[0m" . $trackName . PHP_EOL;
        }
    }
    $nextPageToken = $playlistItemsResponse['nextPageToken'];
} while ($nextPageToken != NULL);

file_put_contents('spotify-uris.txt', implode("\n", $result));

if (stripos(php_uname(), 'darwin') !== false) {
    echo PHP_EOL . "Done! - Now run the following command to copy your Spotify-URI-list to your clipboard: ";
    echo PHP_EOL . PHP_EOL . "\033[32m cat spotify-uris.txt | pbcopy \033[0m" . PHP_EOL . PHP_EOL;
} else {
    echo PHP_EOL . "Done! - Now you have your Spotify-URIs in 'spotify-uris.txt'";
}

echo "Just paste them (from your clipboard) into your empty spotify playlist (must be a client, not the webplayer)";
echo PHP_EOL;

# https://www.youtube.com/playlist?list=PLhzswTGE9_z92Pm5HF-_A-YYR1dOuuUL-