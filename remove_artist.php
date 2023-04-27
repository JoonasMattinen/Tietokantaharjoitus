<?php

require "error.php";
require "dbcon.php";
$artist_id = 5;

try {
    $dbcon = createDbConnection();
    $dbcon->beginTransaction();

    // Get artist name
    $stmt = $dbcon->prepare('SELECT name FROM artists WHERE ArtistId = :ArtistId');
    $stmt->bindParam(':ArtistId', $artist_id);
    $stmt->execute();
    $artist_name = $stmt->fetchColumn();

    // Delete playlist_track
    $stmt = $dbcon->prepare('DELETE FROM playlist_track
        WHERE TrackId IN (
            SELECT tracks.TrackId FROM tracks
            JOIN albums ON tracks.AlbumId = albums.AlbumId
            WHERE albums.ArtistId = :ArtistId
        )');
    $stmt->bindParam(':ArtistId', $artist_id);
    $stmt->execute();

    // Delete invoice items
    $stmt = $dbcon->prepare('DELETE FROM invoice_items
        WHERE TrackId IN (
            SELECT tracks.TrackId FROM tracks
            JOIN albums ON tracks.AlbumId = albums.AlbumId
            WHERE albums.ArtistId = :ArtistId
        )');
    $stmt->bindParam(':ArtistId', $artist_id);
    $stmt->execute();

    // Delete tracks
    $stmt = $dbcon->prepare('DELETE FROM tracks
        WHERE AlbumId IN (
            SELECT albums.AlbumId FROM albums
            WHERE albums.ArtistId = :ArtistId
        )');
    $stmt->bindParam(':ArtistId', $artist_id);
    $stmt->execute();

    // Delete albums
    $stmt = $dbcon->prepare('DELETE FROM albums WHERE ArtistId = :ArtistId');
    $stmt->bindParam(':ArtistId', $artist_id);
    $stmt->execute();

    // Delete artist
    $stmt = $dbcon->prepare('DELETE FROM artists WHERE ArtistId = :ArtistId');
    $stmt->bindParam(':ArtistId', $artist_id);
    $stmt->execute();


    $dbcon->commit();
} catch (PDOException $pdoex) {
    returnError($pdoex);
}
