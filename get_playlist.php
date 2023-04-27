<?php

require 'error.php';
require 'dbcon.php';

$playlist_id = 1;

try {
    $dbcon = createDbConnection();
    $sql= "SELECT Name, Composer
    FROM tracks
    INNER JOIN playlist_track ON tracks.TrackId = playlist_track.TrackId
    WHERE playlist_track.PlaylistId = $playlist_id";

$rows = $dbcon->query($sql);

// Tulosta tulokset
foreach ($rows as $row) {
    echo $row['Name'] . '<br>' . ' ( ' . $row['Composer'] . ' ) ' . '<br>' . '<br>';
}

// Sulje tietokantayhteys
$db = null;

} catch (PDOException $pdoex) {
    returnError($pdoex);
}