<?php
require 'vendor/autoload.php';

use Acme\Args;
use Acme\Output;

// init everything!
$config       = Spyc::YAMLLoad('config.yaml');
$args         = new Args();
$googleClient = new Google_Client();
$googleClient->setDeveloperKey($config['google_developer_key']);
$youtube      = new Google_Service_YouTube($googleClient);
$spotifyAPI   = new SpotifyWebAPI\SpotifyWebAPI();

// set those 
date_default_timezone_set($config['timezone']);
set_exception_handler(['Acme\Error', 'handle']);

// for debug
if (gethostname() === 'CodeBrauer.local') {
    include '../ref/ref.php';
}

$args->check();
list($url, $arg2) = $args->process();

// action start

if ($arg2 == 'only-uri') {
    ob_start();
}

$nextPageToken = null;
do {
    $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => rawurlencode($url),
        'maxResults' => 50,
        'pageToken'  => $nextPageToken));

    foreach ($playlistItemsResponse['items'] as $playlistItem) {
        $trackName = str_ireplace($config['ignore_video_title'], '', $playlistItem['snippet']['title']);
        $search = $spotifyAPI->search(trim($trackName), 'track');
        if ($search->tracks->total > 0) {
            $spotifyURI = $spotifyAPI->search(trim($trackName), 'track')->tracks->items[0]->uri;
            $result[]   = $spotifyURI;
            Output::put($trackName, 'success');
        } else {
            Output::put($trackName, 'fail');
        }
    }
    $nextPageToken = $playlistItemsResponse['nextPageToken'];
} while ($nextPageToken != null);

file_put_contents('spotify-uris.txt', implode("\n", $result));

if (stripos(php_uname(), 'darwin') !== false) {
    Output::blankLine();
    if ($arg2 == 'copy') {
        shell_exec('cat spotify-uris.txt | pbcopy');
        Output::put('Done! - Now you have your Spotify-URIs in your clipboard', 'success');
    } else {
        Output::put('Done! - Now run the following command to copy your Spotify-URI-list to your clipboard:');
        Output::put('cat spotify-uris.txt | pbcopy', 'success');
    }
} else {
    Output::put('Done! - Now you have your Spotify-URIs in "spotify-uris.txt"', 'success');
}

Output::blankLine();
Output::put('Just paste them from your clipboard in a spotify playlist. (Does not work with play.spotify.com');
Output::blankLine();

if ($arg2 == 'only-uri') {
    ob_end_clean();
    Output::put(shell_exec('cat spotify-uris.txt'));
}
