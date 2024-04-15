<?php

namespace App\Http\Controllers;

use App\Jobs\ConverterJob;
use Illuminate\Http\Request;

class ConverterController extends Controller
{
    public function show()
    {
        return view('upload');
    }




    public function convert(Request $request)
    {
        // $request->validate([
        //     'json_file' => 'required|file|mimes:json|max:2048', // Max file size 2MB
        // ]);

        $jsonFilePath = $request->file('upload_file')->getRealPath();
        // Define the Excel file path
        $excelFile = storage_path('app/public/excel/output.xlsx');

        // Dispatch the job for JSON to Excel conversion
        ConverterJob::dispatch($jsonFilePath, $excelFile)->onQueue('excel-conversion');

        // Generate a temporary download link
        $downloadLink = route('download-excel');

        return redirect($downloadLink)->with('success', 'JSON to Excel conversion job dispatched. Downloading...');
    }

    public function downloadExcel()
    {
        $excelFile = storage_path('app/public/excel/output.xlsx');

        if (file_exists($excelFile)) {
            // Return the Excel file as a download response
            return response()->download($excelFile)->deleteFileAfterSend(true);
        } else {
            abort(404);
        }
    }
}
