<?php
require_once 'vendor/autoload.php'; // Adjust the path to the autoload file if needed
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
            background-color: black;
        }
        h1 {
            text-align: center;
            margin-bottom: 1rem;
            color: white;
        }
        #recording-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
        }
        .recording {
            background-color: darkred;
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
        }
        .filename, .metadata {
            text-align: center;
            word-wrap: break-word;
        }
        audio {
            width: 100%;
        }
        .download-btn {
            background-color: #007bff;
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
    </style>
</head>
<body>
<h1>Therapy Sessions Archive</h1>
        <div id="recording-container">
            <?php
            $audio_dir = "audio/";

            if (is_dir($audio_dir)) {
                $files = glob($audio_dir . "*.mp3");
                foreach ($files as $file) {
                    $file_name = basename($file);
                    $file_info = $getID3->analyze($file);

                    $artist = isset($file_info['tags']['id3v2']['artist'][0]) ? $file_info['tags']['id3v2']['artist'][0] : (isset($file_info['tags']['id3v1']['artist'][0]) ? $file_info['tags']['id3v1']['artist'][0] : 'Unknown');
                    $title = isset($file_info['tags']['id3v2']['title'][0]) ? $file_info['tags']['id3v2']['title'][0] : (isset($file_info['tags']['id3v1']['title'][0]) ? $file_info['tags']['id3v1']['title'][0] : 'Unknown');
                    $album = isset($file_info['tags']['id3v2']['album'][0]) ? $file_info['tags']['id3v2']['album'][0] : (isset($file_info['tags']['id3v1']['album'][0]) ? $file_info['tags']['id3v1']['album'][0] : 'Unknown');
                    $year = isset($file_info['tags']['id3v2']['year'][0]) ? $file_info['tags']['id3v2']['year'][0] : (isset($file_info['tags']['id3v1']['year'][0]) ? $file_info['tags']['id3v1']['year'][0] : 'Unknown');


                    echo "<div class=\"recording\">
                            <div class=\"filename\">$file_name</div>
                            <div class=\"metadata\">
                                <p>Artist: $artist</p>
                                <p>Title: $title</p>
                                <p>Album: $album</p>
                                <p>Year: $year</p>
                            </div>
                            <audio controls src=\"$file\"></audio>
                            <button class=\"download-btn\" onclick=\"location.href='$file'\">Download</button>
                            <span>0 downloads</span>
                          </div>";
                }
            } else {
                echo "<p>Error: the 'audio' directory does not exist.</p>";
            }
            ?>
        </div>
    </body>
    </html>
