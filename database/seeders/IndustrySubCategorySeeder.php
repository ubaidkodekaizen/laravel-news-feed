<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class IndustrySubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the Excel file (root directory)
        $filePath = base_path('industries.xlsx');

        // Load the spreadsheet file
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        // Define the row numbers that indicate new industries
        $industryRows = [1, 21, 27, 31, 42, 219, 239, 288, 300, 325, 337, 346, 356, 360, 390, 408, 439, 449, 465, 496];

        $industry = null;

        // Loop through each row of the spreadsheet
        foreach ($worksheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            
            // Get the cell value from the first column (e.g., A1, A2, A3, etc.)
            $cellValue = $worksheet->getCell('A' . $rowIndex)->getValue();

            // Check if the current row is one of the "industry" rows
            if (in_array($rowIndex, $industryRows)) {
                // Create the new Industry entry
                $industry = Industry::create([
                    'name' => $cellValue,
                ]);
            } elseif ($industry) {
                // Insert subcategory for the current industry
                SubCategory::create([
                    'industry_id' => $industry->id,
                    'name' => $cellValue,
                ]);
            }
        }
    }
}
