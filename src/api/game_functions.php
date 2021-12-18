<?php
session_start();
include('../db/db_conn.php');

// Headers
header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

global $conn;

