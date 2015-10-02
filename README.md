# YouTube2Spotify
Converts YouTube Playlists to Spotify URI lists - in your Terminal.

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
$ php yt2spotify.php [YouTube Playlist-Link | YouTube Playlist-ID]
```

Example:
```sh
$ php yt2spotify.php PL6D4C31FFA7EBABB5
```


