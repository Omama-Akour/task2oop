<?php

declare(strict_types = 1); // Declaring strict types for type safety

function getTransactionFiles(string $dirPath): array
{
    $files = []; // Initializing an empty array to store file names

    // Looping through files in the directory
    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) { // Skipping directories
            continue;
        }

        $files[] = $dirPath . $file; // Adding file path to the array
    }

    return $files; // Returning array of file paths
}

function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
    if (! file_exists($fileName)) { // Checking if the file exists
        trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR); // Triggering an error if the file does not exist
    }

    

    $file = fopen($fileName, 'r'); // Opening the file in read mode
    fgetcsv($file); // Ignoring the first line (assuming it's a header)

    $transactions = []; // Initializing an empty array to store transactions

    // Looping through each line of the file
    while (($transaction = fgetcsv($file)) !== false) {
        if ($transactionHandler !== null) { // Checking if a transaction handler function is provided
            $transaction = $transactionHandler($transaction); // Applying the transaction handler function to the transaction
        }

        $transactions[] = $transaction; // Adding the transaction to the array
    }

    return $transactions; // Returning array of transactions
}

function extractTransaction(array $transactionRow): array
{
    // Extracting data from the transaction row
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    // Converting amount to float after removing any '$' and ',' characters
    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        'date'        => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount'      => $amount,
    ]; // Returning an array with extracted transaction data
}

function calculateTotals(array $transactions): array
{
    $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0]; // Initializing totals array

    // Looping through transactions to calculate totals
    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount']; // Adding transaction amount to net total

        if ($transaction['amount'] >= 0) {
            $totals['totalIncome'] += $transaction['amount']; // Adding positive amounts to total income
        } else {
            $totals['totalExpense'] += $transaction['amount']; // Adding negative amounts to total expense
        }
    }

    return $totals; // Returning array with calculated totals
}
function formatDollarAmount(float $amount): string {
    return '$' . number_format($amount, 2);
}

function formatDate(string $date): string {
    return date('M j, Y', strtotime($date));
}
