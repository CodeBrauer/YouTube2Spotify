<?php
if (!empty($_POST)) {
    $result = shell_exec('php yt2spotify.php ' . trim($_POST['youtubelink']) . ' --only-uri');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>YouTube2Spotify</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        #youtubelink {min-width: 400px;} 
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
        <h1>YouTube2Spotify</h1>      
        </div>
        <form class="form-inline" method="POST">
            <div class="form-group">
                <input name="youtubelink" type="text" class="form-control" id="youtubelink" placeholder="YouTube Playlist ID or URL">
            </div>
            <button type="submit" class="btn btn-primary">Convert to Spotify URIs</button>
        </form>
        <?php if (isset($result)): ?>
        <hr>
<pre><?php echo $result ?></pre>
        <?php endif ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
