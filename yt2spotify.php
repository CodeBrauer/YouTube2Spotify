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
if (gethostname() === 'CodeBrauer.local') { include '../ref/ref.php'; }

$args->check();
list($url, $copy) = $args->process();

// action start

$nextPageToken = NULL;
do {
    $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => rawurlencode($url),
        'maxResults' => 50,
        'pageToken'  => $nextPageToken));

    foreach ($playlistItemsResponse['items'] as $playlistItem) {
        $trackName = str_ireplace($config['ignore_video_title'], '', $playlistItem['snippet']['title']);
        $spotifyURI = $spotifyAPI->search(trim($trackName), 'track')->tracks->items[0]->uri;
        if (is_string($spotifyURI)) {
            Output::put($trackName, 'success');
            $result[] = $spotifyURI;
        } else {
            Output::put($trackName, 'fail');
        }
    }
    $nextPageToken = $playlistItemsResponse['nextPageToken'];
} while ($nextPageToken != NULL);

file_put_contents('spotify-uris.txt', implode("\n", $result));

if (stripos(php_uname(), 'darwin') !== false) {
    Output::blankLine();
    if ($copy) {
        shell_exec('cat spotify-uris.txt | pbcopy');
        Output::put('Done! - Now you have your Spotify-URIs in your clipboard', 'success');
        Output::put('cat spotify-uris.txt | pbcopy', 'success');
    } else {
        Output::put('Done! - Now run the following command to copy your Spotify-URI-list to your clipboard:');
    }
} else {
    Output::put('Done! - Now you have your Spotify-URIs in "spotify-uris.txt"', 'success');
}

Output::blankLine();
Output::put('Just paste them (from your clipboard) into your empty spotify playlist (must be a client, not the webplayer)');
Output::blankLine();

# debug URL
# https://www.youtube.com/playlist?list=PLhzswTGE9_z92Pm5HF-_A-YYR1dOuuUL-