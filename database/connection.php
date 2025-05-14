<?php
  declare(strict_types = 1);

  function getDatabaseConnection() : PDO {
    // Set the database file path relative to the current script's directory
    $dbFile = __DIR__ . '/database.db';  // __DIR__ will refer to the directory of connection.php

    // Create and return a new PDO connection to the SQLite database
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
  }
  
?>