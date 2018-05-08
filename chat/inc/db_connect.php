<?php
function DB()
{
    date_default_timezone_set("UTC");
    $ini = parse_ini_file(__DIR__.'/app.ini');
    try {
        $db = new PDO('sqlsrv:server='.$ini['db_host'].';Database='.$ini['db_name'], $ini['db_user'], $ini['db_password']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        return "Error!: " . $e->getMessage();
        die();
    }
}