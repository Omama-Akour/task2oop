<?php

declare(strict_types=1);

namespace App\Models;

use mysqli;

class TransactionModel
{
    protected mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * Process and save data from a CSV file into the database.
     *
     * @param string $filePath Path to the CSV file.
     * @throws \Exception If unable to open file or prepare SQL statement.
     */
    public function processAndSaveFile(string $filePath)
    {
        // Open the CSV file for reading
        $file = fopen($filePath, 'r');

        if (!$file) {
            throw new \Exception("Failed to open file.");
        }

        // Skip the header row
        fgetcsv($file);

        // Prepare SQL statement for inserting transactions
        $stmt = $this->db->prepare("INSERT INTO transactions (date, check_number, description, amount) VALUES (?, ?, ?, ?)");

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement.");
        }

        // Bind parameters
        $stmt->bind_param("sssd", $date, $checkNumber, $description, $amount);

        // Read each line of the CSV file and insert into the database
        while (($row = fgetcsv($file)) !== false) {
            $date = $row[0];
            $checkNumber = !empty($row[1]) ? $row[1] : null;
            $description = $row[2];
            $amount = $this->convertAmount($row[3]); // Convert amount to appropriate format

            // Execute the prepared statement
            $stmt->execute();
        }

        // Close statement and connection
        $stmt->close();
        fclose($file);
    }

    /**
     * Convert the amount to the appropriate format (e.g., remove $ and ,).
     *
     * @param string $amount The amount string to convert.
     * @return float The converted amount as a float.
     */
    private function convertAmount(string $amount): float
    {
        return (float) str_replace(['$', ','], '', $amount);
    }
}
