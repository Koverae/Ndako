<?php

namespace Modules\App\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportFile extends Component
{
    use WithFileUploads;

    public $file;
    public string $model, $modelSlug, $modelName;
    public bool $updateExisting = true, $skipDuplicates  = true;
    public array $previewData = [];

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
    ];

    public function mount($model)
    {
        $this->modelSlug = $model;

        // 🔄 Convert slug to actual class (e.g., mod_units => Modules\Properties\Models\Property\PropertyUnit)
        $this->modelName = $this->resolveModelNameFromSlug($model);
        $this->model = $this->resolveModelFromSlug($model);
    }

    public function resolveModelNameFromSlug($slug): string
    {
        return match($slug) {
            'mod_properties' => "Properties",
            'mod_unit_types' => "Unit Types",
            'mod_units' => "Units",
            'mod_floors' => "Floors",
            'mod_guests' => "Guests",
            'mod_expenses' => "Expenses",
            'mod_bookings' => "Bookings",
            // Add more here
            default => abort(404, 'Invalid model'),
        };
    }

    public function resolveModelFromSlug($slug): string
    {
        return match($slug) {
            'mod_properties' => \Modules\Properties\Models\Property\Property::class,
            'mod_units' => \Modules\Properties\Models\Property\PropertyUnit::class,
            'mod_floors' => \Modules\Properties\Models\Property\PropertyFloor::class,
            'mod_guests' => \Modules\ChannelManager\Models\Guest\Guest::class,
            'mod_expenses' => \Modules\RevenueManager\Models\Expenses\Expense::class,
            'mod_bookings' => \Modules\ChannelManager\Models\Booking\Booking::class,
            // Add more here
            default => abort(404, 'Invalid model'),
        };
    }

    public function resolveModelRouteFromSlug($slug): string
    {
        return match($slug) {
            'mod_properties' => "properties.lists",
            'mod_property_types' => "properties.types.lists",
            'mod_unit_types' => "properties.unit-types.lists",
            'mod_units' => "properties.units.lists",
            'mod_floors' => "dashboard",
            'mod_guests' => "guests.lists",
            'mod_expenses' => "expenses.lists",
            'mod_bookings' => "bookings.lists",
            // Add more here
            default => abort(404, 'Invalid model'),
        };
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function import()
    {
        $this->validate();

        try {
            $spreadsheet = IOFactory::load($this->file->getRealPath());
            // Get all sheets (support for multi-sheet import)
            $sheets = $spreadsheet->getAllSheets();
            // $sheet = $spreadsheet->getActiveSheet();

            // Begin a database transaction for safe rollback on failure
            // DB::beginTransaction();

            // Loop through each sheet in the workbook
            foreach ($sheets as $sheet) {
                // Convert sheet data to array
                $rows = $sheet->toArray();

                // Skip if there are not enough rows (e.g., empty sheet)
                if (count($rows) < 2) continue;

                // if (count($rows) < 2) {
                //     session()->flash('error', 'The uploaded file is empty or improperly formatted.');
                //     return;
                // }

                array_shift($rows); // Skip the first row (e.g., "Units" title)
                $columns = array_map('trim', array_shift($rows)); // Actual column headers on 2nd row

                $modelClass = $this->model;

                foreach ($rows as $rowIndex => $row) {
                    if (empty(array_filter($row))) {
                        continue; // Skip empty rows
                    }

                    $data = array_combine($columns, $row);

                    if (!$data) {
                        Log::warning("Skipping row {$rowIndex} — column mismatch", [
                            'columns' => $columns,
                            'row' => $row,
                        ]);
                        continue;
                    }

                    // Inject default company_id if needed
                    if (!isset($data['company_id']) || empty($data['company_id'])) {
                        $data['company_id'] = current_company()->id ?? null;
                    }

                    // ✨ Process with model-specific logic
                    if (method_exists($modelClass, 'processImportRow')) {
                        $data = $modelClass::processImportRow($data);
                    }

                    // Remove null values from columns that might have been left empty
                    $data = array_filter($data, fn ($value) => $value !== null && $value !== '');

                    // Create record
                    $model = $modelClass::create($data);

                    // ✨Process the after creation
                    if (method_exists($modelClass, 'processAfterImportRow')) {
                        $modelClass::processAfterImportRow($model);
                    }

                }
            }

            session()->flash('message', "$this->modelName imported successfully!");
        } catch (Exception $e) {
            Log::error('Import error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'There was an error importing the file.');
        }

        $this->reset('file', 'previewData');
        return $this->redirect(route($this->resolveModelRouteFromSlug($this->modelSlug), true));
    }

    public function updatedFile()
    {
        $this->preview(); // Automatically call preview when file is updated
    }

    public function preview()
    {
        $this->validate();

        try {
            $spreadsheet = IOFactory::load($this->file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (count($rows) < 2) {
                session()->flash('error', 'The uploaded file is empty or improperly formatted.');
                return;
            }

            $modelClass = $this->model;

            array_shift($rows); // Skip the first row (e.g., "Units" title)
            $columns = array_map('trim', array_shift($rows)); // Actual column headers on 2nd row

            $previewRows = [];
            foreach ($rows as $index => $row) {
                $data = array_combine($columns, $row);
                if (!$data) continue;

                if (method_exists($modelClass, 'processImportPreviewRow')) {
                    $processed = $modelClass::processImportPreviewRow($data);
                    $previewRows[] = $processed;
                } else {
                    $previewRows[] = $data;
                }
            }

            $this->previewData = $previewRows;

        } catch (\Throwable $e) {
            Log::error('Preview error: ' . $e->getMessage());
            session()->flash('error', 'There was an error previewing the file.');
        }
    }

    public function downloadTemplate()
    {
        $filePath = storage_path("app/public/imports/{$this->modelSlug}.xlsx");

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            session()->flash('error', 'File not found.');
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('app::livewire.import-file')
        ->extends('layouts.app');
    }
}
