<?php

namespace Modules\App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService{

    /**
     * Export report with summary & detailed data
     *
     * @param string $reportTitle
     * @param array $summaryData
     * @param \Illuminate\Support\Collection $detailedData
     * @param string $format (xlsx, pdf)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(string $reportTitle, array $summaryData, $detailedSections, string $format = 'xlsx')
    {
        if ($format === 'xlsx') {
            return $this->exportToExcel($reportTitle, $summaryData, $detailedSections);
        } elseif ($format === 'pdf') {
            // return $this->exportToPdf($reportTitle, $summaryData, $detailedSections);
        }

        abort(400, 'Unsupported export format');
    }

    public function exportSelected($title = 'export', $items, $columns)
    {
        // Example data - you should replace this with the actual $items data you want to export
        $data = [];
        foreach ($items as $item) {
            $row = [];
            foreach ($columns as $column) {
                // Assuming each $item has a method or attribute that corresponds to the $column name
                $row[] = $item->{$column};
            }
            $data[] = $row;
        }

        // Create a new Spreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row (you can replace with dynamic column names)
        foreach ($columns as $colIndex => $column) {
            $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue("{$columnLetter}1", ucfirst($column));  // Setting headers (e.g., 'ID', 'Name', 'Email')
        }

        // Fill the sheet with data (from the $data array)
        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue("{$columnLetter}" . ($rowIndex + 2), $value); // Fill starting from row 2
            }
        }

        // Create a filename for the exported file
        $fileName = "{$title}_" . now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Define the file path where the export will be stored
        $filePath = storage_path("app/exports/{$fileName}");

        // Ensure the exports directory exists
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0777, true);
        }

        // Create a writer instance and save the spreadsheet to the file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        // Return the file for download, and delete it after sending
        return response()->download($filePath)->deleteFileAfterSend(true);
    }


    /**
     * Export Structured data to Excel
     */
    private function exportToExcel(string $reportTitle, array $summaryData = [], array $detailedSections)
    {

        $spreadsheet = new Spreadsheet();
        // $spreadsheet->removeSheetByIndex(0); // Remove default empty sheet

        // 🎨 Common Styles
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2'], // Light blue background
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sectionTitleStyle = [
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BFBFBF'], // Grey background
            ],
        ];


        // ✅ 1st Sheet: Summary Data (Styled as Cards)
        $summarySheet = $spreadsheet->setActiveSheetIndex(0);
        $summarySheet->setTitle('Summary');

        $rowNumber = 2; // Start from row 2 for spacing
        foreach ($summaryData as $metric => $values) {
            $mergeRange = "A{$rowNumber}:C" . ($rowNumber + 1); // Merge cells for card effect
            $summarySheet->mergeCells($mergeRange);

            // Apply card styling
            $summarySheet->getStyle($mergeRange)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2'], // Light blue background
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '4F81BD'], // Blue border
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
            ]);

            // ✅ Add Rich Text (Metric + Value + Change)
            $richText = new RichText();

            // 🏷 Metric Name (Bold, Larger)
            $boldText = $richText->createTextRun("$metric\n");
            $boldText->getFont()->setBold(true)->setSize(14);

            // 📊 Metric Value (Bold, Darker Text)
            $valueText = $richText->createTextRun("Value: {$values['value']}\n");
            $valueText->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('333333');

            // 🔄 Change Percentage (Conditional Formatting)
            $changeText = $richText->createTextRun("Change: {$values['change']}");
            $changeText->getFont()->setSize(11);

            // Apply Green/Red based on Positive/Negative Change
            if ((float) $values['change'] >= 0) {
                $changeText->getFont()->setColor(new Color('008000')); // Green for positive change
            } else {
                $changeText->getFont()->setColor(new Color('FF0000')); // Red for negative change
            }

            // Set Rich Text in the merged summary cell
            $summarySheet->getCell("A{$rowNumber}")->setValue($richText);

            // Increase row number for spacing
            $rowNumber += 3;
        }

        // Auto-size columns for better layout
        foreach (range('A', 'C') as $column) {
            $summarySheet->getColumnDimension($column)->setAutoSize(true);
        }

        // ✅ Create a sheet for each detailed report section
        $sheetIndex = 1; // Start from the second sheet since the first is Summary

        foreach ($detailedSections as $sectionTitle => $detailedData) {
            if ($detailedData->isEmpty()) {
                continue; // Skip empty sections
            }

            // Create a new sheet
            $detailSheet = $spreadsheet->createSheet($sheetIndex);
            $detailSheet->setTitle($sectionTitle);
            $spreadsheet->setActiveSheetIndex($sheetIndex);

            // ✅ Section Title (Styled)
            $detailSheet->setCellValue("A1", $sectionTitle);
            $detailSheet->mergeCells("A1:Z1"); // Span across multiple columns
            $detailSheet->getStyle("A1")->applyFromArray($sectionTitleStyle);

            // ✅ Table Headers (Styled)
            $headers = array_keys($detailedData->first());
            $columnIndex = 'A';

            foreach ($headers as $header) {
                $detailSheet->setCellValue("{$columnIndex}2", inverseSlug($header));
                $detailSheet->getStyle("{$columnIndex}2")->applyFromArray($headerStyle);
                $columnIndex++;
            }

            // ✅ Table Data
            $rowNumber = 3; // Start filling data from row 3

            foreach ($detailedData as $row) {
                $columnIndex = 'A';
                foreach ($headers as $header) {
                    $detailSheet->setCellValue("{$columnIndex}{$rowNumber}", $row[$header]);
                    $columnIndex++;
                }
                $rowNumber++;
            }

            // ✅ Auto-size columns for readability
            foreach (range('A', $columnIndex) as $col) {
                $detailSheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheetIndex++; // Move to the next sheet
        }

        // ✅ Ensure the "exports" directory exists
        $directory = storage_path('app/exports');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // ✅ Save & Download
        $name = "{$reportTitle}_" . now()->format('Y-m-d') . '.xlsx';
        $filePath = "exports/{$reportTitle}_" . now()->format('Y-m-d') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/' . $filePath));


        // Stream the file for download
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$name.'xlsx"',
        ];

        return response()->download(storage_path('app/' . $filePath), "{$name}.xlsx", $headers);

    }

}
