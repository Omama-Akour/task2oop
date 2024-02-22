<?php
namespace App\Controllers;

use App\DB;
use App\Models\TransactionModel;
use App\View;

class HomeController
{
    public function index()
    {
        return View::make('index');
    }

    public function uploadTransaction()
    {
        // Check if any file is uploaded
        if (!isset($_FILES['files']['error'])) {
            return "No files uploaded!";
        }

        // Process each uploaded file
        $successMessages = [];
        $errorMessages = [];

        foreach ($_FILES['files']['error'] as $key => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $fileName = $_FILES['files']['name'][$key];
                $tmpName = $_FILES['files']['tmp_name'][$key];

                // Check if the uploaded file is a CSV file
                if (pathinfo($fileName, PATHINFO_EXTENSION) === 'csv') {
                    $conn = DB::getConnection(require_once __DIR__ . '/../config.php');
                    $transactionModel = new TransactionModel($conn);

                    try {
                        $transactionModel->processAndSaveFile($tmpName);
                        $successMessages[] = "File '{$fileName}' uploaded and processed successfully!";
                    } catch (\Exception $e) {
                        $errorMessages[] = "Error processing file '{$fileName}': " . $e->getMessage();
                    }
                } else {
                    $errorMessages[] = "Only CSV files are allowed! (File '{$fileName}')";
                }
            } elseif ($error !== UPLOAD_ERR_NO_FILE) {
                $errorMessages[] = "Error uploading file '{$fileName}'!";
            }
        }

        // Prepare the data to pass to the view
        $data = [
            'successMessages' => $successMessages,
            'errorMessages' => $errorMessages
        ];

        // Render the view and pass the data
        return View::make('transactions', $data);
    }
}
