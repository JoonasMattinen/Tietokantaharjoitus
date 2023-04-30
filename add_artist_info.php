<?php
require "error.php";
require "dbcon.php";

// Get the request data
$data = json_decode(file_get_contents('php://input'), true);

// Extract the artist, album and tracks data from the request
if (!isset($data['artist']) || !isset($data['album']) || !isset($data['tracks'])) {
    http_response_code(400);
    echo 'Artist info not defined';
    exit();
}
$artist = $data['artist'];
$album = $data['album'];
$tracks = $data['tracks'];

try {
    $dbcon = createDbConnection();

    $dbcon->beginTransaction();

    // Insert the artist
    $stmt = $dbcon->prepare("INSERT INTO artists (Name) VALUES (:Name)");
    $stmt->bindParam(':Name', $artist['name']);
    $stmt->execute();
    $artistId = $dbcon->lastInsertId();

    // Insert the album
    $stmt = $dbcon->prepare("INSERT INTO albums (ArtistId, Title) VALUES (:ArtistId, :Title)");
    $stmt->bindParam(':ArtistId', $artistId);
    $stmt->bindParam(':Title', $album['title']);
    $stmt->execute();
    $albumId = $dbcon->lastInsertId();

    // Insert the tracks
    foreach ($tracks as $track) {
        $stmt = $dbcon->prepare("INSERT INTO tracks (AlbumId, Name, MediaTypeID, Milliseconds, UnitPrice) VALUES (:AlbumId, :Name, 1, 345932, 0.99)");
        $stmt->bindParam(':AlbumId', $albumId);
        $stmt->bindParam(':Name', $track['name']);
        $stmt->execute();
    }

    $dbcon->commit();

    // Output
    http_response_code(200);
    echo json_encode(array('message' => 'Artist, album and tracks added successfully'));
} catch (PDOException $pdoex) {
    $dbcon->rollback();
    returnError($pdoex);
}

