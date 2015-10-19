# YouTube2Spotify
Converts YouTube Playlists to Spotify URI lists - in your Terminal.

[![Code Climate](https://codeclimate.com/github/CodeBrauer/YouTube2Spotify/badges/gpa.svg)](https://codeclimate.com/github/CodeBrauer/YouTube2Spotify)

# Installation

**This is currently in development stage!**

1. Download the master version
2. Get a DeveloperKey from the [Google Developers Console](https://console.developers.google.com)
3. Paste the API-Key in `config.php`
4. Activate the YouTube Data API v3 for this key/project
5. Run `composer install` to load the required packages
6. Should be done now.

# Usage

```sh
$ php yt2spotify.php [YouTube playlist-URL | YouTube playlist-ID] [--copy] [--only-uri]
```

- `--copy`: Copies the spotify URIs to your clipboard
- `--only-uri`: Prints only fetched URIs (one per line)

**Note:** `--copy` works only on Mac OS X with the [`pbcopy`](https://developer.apple.com/library/mac/documentation/Darwin/Reference/ManPages/man1/pbcopy.1.html)-binary

Example:
```sh
$ php yt2spotify.php PL6D4C31FFA7EBABB5
```

# How does it work?

It's very simple - The scripts loads all video-titles (= name of the track) of the provided YouTube-playlist with the YouTube Data API v3 and searches this tracknames with Spotifys WebAPI - if the track was found the script will show you a green checkmark next to the title, if not - a red cross. All found Spotify-URIs (like `spotify:track:3GfOAdcoc3X5GPiiXmpBjK` will be written per line in `spotify-uris.txt`) – Now you can just copy the contents of this file and paste it (from your clipboard) in an playlist in your spotify-client.

**The problems:**
- Some YouTube videos are not named very well - so some words must be ignored for the spotify search (like "lyrics", "HD", etc. - look at the `config.yml`)
- Some special remixes/edits are not at the first search position on spotifys web api - so you get the official instead an radio edit.
