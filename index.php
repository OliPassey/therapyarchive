<?php
require_once 'vendor/autoload.php';
$getID3 = new getID3;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapy Sessions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: no-repeat url(./grid_0.png);
            background-size: 100%;
            background-position: top;
            background-color: black;
            background-attachment: fixed;
        }
        h1 {
            text-align: center;
            margin-bottom: 1rem;
            color: red;
            font-family: "Lucida Console", "Courier New", monospace;
            font-size: 4rem;
        }
        h2 {
            text-align: center;
            color: white;
            font-family: "Lucida Console", "Courier New", monospace;
            font-size: 2rem;
        }
        #recording-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
        }
        .recording {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            width: 400px;
            height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            box-sizing: border-box;
            color: white;
            background-size: cover;
            background-position: center;
            position: relative;
            font-style: bold;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            padding: 1rem;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .filename, .metadata {
            text-align: center;
            inline-size: 375px;
            /* overflow: hidden; 
            overflow-wrap: break-word;*/
        }
        audio {
            width: 100%;
        }
        .download-btn {
            background-color: #830d0d;
            border: none;
            border-radius: 5px;
            color: white;
            padding: 0.5rem 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 0 1rem;
            cursor: pointer;
        }
        .flier {
            margin-bottom: 1rem;
        }
        header {
            position: relative;
            text-align: center;
            padding: 1rem;
        }
        .header-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        footer {
            position: relative;
            text-align: center;
            padding: 1rem;
        }
        .footer-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .setlist, a:link, a:visited, a:hover, a:active {
            color: white;
        }
    </style>
</head>
<body>
<header> 
<div class="header-overlay"></div>   
    <h1>Therapy Sessions Archive</h1>
    <h2>Therapy Sessions is a drum n bass event run by Anger Management / Robyn Chaos</h2>
    <h2>This archive is unofficial, and exists to archive a great night we had in the early 2000's</h2>
    <h2>If you have more Therapy sets, set lists or fliers - please get in touch!</h2>
</header>
    <div id="recording-container">
        <?php
        $audio_dir = "audio/";
        $fliers_dir = "fliers/";
        $setlists_dir = "setlists/";
        $flier_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        function find_matching_setlist($filename, $setlists_dir) {
            $possible_setlist = $setlists_dir . str_replace('.mp3', '.txt', $filename);
            if (file_exists($possible_setlist)) {
                return $possible_setlist;
            }
            return null;
        }

        function find_matching_flier($filename, $fliers_dir, $flier_extensions) {
            foreach ($flier_extensions as $ext) {
                $possible_flier = $fliers_dir . str_replace('.mp3', ".$ext", $filename);
                if (file_exists($possible_flier)) {
                    return $possible_flier;
                }
            }
            return null;
        }

        if (is_dir($audio_dir)) {
            $files = glob($audio_dir . "*.mp3");
            foreach ($files as $file) {
                $file_name = basename($file);
                $flier_path = find_matching_flier($file_name, $fliers_dir, $flier_extensions);
                $default_flier = "therapy.jpg";
                $flier = $flier_path ? "style=\"background-image: url('$flier_path');\"" : "style=\"background-image: url('$default_flier');\"";
                
                $setlist_path = find_matching_setlist($file_name, $setlists_dir);
                
                $file_info = $getID3->analyze($file);
        
                $artist = isset($file_info['tags']['id3v2']['artist'][0]) ? $file_info['tags']['id3v2']['artist'][0] : (isset($file_info['tags']['id3v1']['artist'][0]) ? $file_info['tags']['id3v1']['artist'][0] : 'Unknown');
                $title = isset($file_info['tags']['id3v2']['title'][0]) ? $file_info['tags']['id3v2']['title'][0] : (isset($file_info['tags']['id3v1']['title'][0]) ? $file_info['tags']['id3v1']['title'][0] : 'Unknown');
                $album = isset($file_info['tags']['id3v2']['album'][0]) ? $file_info['tags']['id3v2']['album'][0] : (isset($file_info['tags']['id3v1']['album'][0]) ? $file_info['tags']['id3v1']['album'][0] : 'Unknown');
                $year = isset($file_info['tags']['id3v2']['year'][0]) ? $file_info['tags']['id3v2']['year'][0] : (isset($file_info['tags']['id3v1']['year'][0]) ? $file_info['tags']['id3v1']['year'][0] : 'Unknown');
                
                $setlist_link = $setlist_path ? "<a href=\"$setlist_path\" download>Download Setlist</a>" : '';
                
                $content = <<<HTML
        <div class="filename">$file_name</div>
        <div class="metadata">
            <p>Artist: $artist</p>
            <p>Title: $title</p>
            <p>Event: $album</p>
            <p>Year: $year</p>
        </div>
        <audio controls src="$file"></audio>
        <a class="download-btn" href="$file" download>Download</a>
        <div class="setlist">$setlist_link</div>
        HTML;
        
                echo "<div class=\"recording\" $flier>
                        <div class=\"overlay\">
                            $content
                        </div>
                      </div>";
            }
        } else {
            echo "<p>Error: the 'audio' directory does not exist.</p>";
        }
        ?>
    </div>
<footer>
<div class="footer-overlay"></div>  
    <h2>Site provided free of advertising for the community, all sets are over 10 years old and no copyright infringement is intended.</h2>
    <h2>Copyright owners wanting content removed should contact the email below.</h2>
    <h2>oli at olipassey dot me dot uk</h2>
</footer>
</body>
</html>
