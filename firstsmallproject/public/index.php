<?php

declare(strict_types = 1); // Declaring strict types for type safety
require_once __DIR__ . '/../app/helpers.php'; // Use require_once to include helpers.php

// Getting the root directory path and setting it to $root
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
 
// Defining constant paths for the application, transaction files, and views directories
define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

// Requiring the main App class file
require_once APP_PATH . "App.php";

// Retrieving file paths of all transaction files
$files = getTransactionFiles(FILES_PATH);

// Initializing an empty array to store all transactions
$transactions = [];

// Looping through each file and merging its transactions into $transactions array
foreach($files as $file) {
    // Using 'extractTransaction' function as a transaction handler while retrieving transactions
    $transactions = array_merge($transactions, getTransactions($file, 'extractTransaction'));
}

// Calculating totals from the transactions array
$totals = calculateTotals($transactions);

// Requiring the transactions view file to display the data
require_once VIEWS_PATH . 'transactions.php';
