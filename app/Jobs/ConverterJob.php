<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ConverterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jsonFilePath;
    protected $excelFile;

    public function __construct($jsonFilePath, $excelFile)
    {
        $this->jsonFilePath = $jsonFilePath;
        $this->excelFile = $excelFile;
    }

    public function handle()
{
    // Read JSON file contents
    $jsonContents = file_get_contents($this->jsonFilePath);

    // Decode JSON data
    $jsonData = json_decode($jsonContents, true);

    // Create a new Spreadsheet instance
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Add JSON data to Excel sheet
    foreach ($jsonData as $rowIndex => $row) {
        $columnIndex = 1;
        foreach ($row as $cellValue) {
            $cellCoordinate = $this->getCellCoordinate($columnIndex, $rowIndex + 1);
            $sheet->setCellValue($cellCoordinate, $cellValue);
            $columnIndex++;
        }
    }

    // Save Excel file
    $writer = new Xlsx($spreadsheet);
    $writer->save($this->excelFile);
}

private function getCellCoordinate($columnIndex, $rowIndex)
{
    // Convert column index to letter (A, B, C, ...)
    $columnLetter = chr(65 + ($columnIndex - 1));

    return $columnLetter . $rowIndex; // Example: "A1", "B2", ...
}

}
