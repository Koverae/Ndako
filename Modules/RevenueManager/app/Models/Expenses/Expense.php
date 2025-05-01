<?php

namespace Modules\RevenueManager\Models\Expenses;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Modules\App\Traits\Files\HasImportLogic;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;

class Expense extends Model
{
    use HasFactory, HasImportLogic;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'expense_category_id',
        'property_id',
        'property_unit_id',
        'agent_id',
        'title',
        'amount',
        'note',
        'is_recurrent',
        'recurrence',
        'next_due_at',
        'date',
        'status',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Expense::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::parse($model->date)->year;
            $month = Carbon::parse($model->date)->month;
            $model->reference = make_reference_with_month_id('ND/EXP', $number, $year, $month);
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function category() {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id', 'id');
    }

    public function property() {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function unit() {
        return $this->belongsTo(PropertyUnit::class, 'property_unit_id', 'id');
    }

    public function agent() {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    // Import Logic

    public static function processImportRow(array $data): array
    {
        // Property
        if (isset($data['property'])) {

            $allProperties = Property::isCompany(current_company()->id)->get();
            $inputName = $data['property'];

            $property = $allProperties->sortByDesc(function ($property) use ($inputName) {
                similar_text(strtolower($property->name), strtolower($inputName), $percent);
                return $percent;
            })->first();

            if (!$property) {

            }
            $data['property_id'] = $property?->id;
            unset($data['property']);
        }else{
            session()->flash('error', 'The property is missing!');
        }

        // Room
        if (isset($data['room'])) {
            $room = PropertyUnit::where('name', $data['room'])->first();
            if (!$room) {

            }
            $data['property_unit_id'] = $room?->id;
            unset($data['room']);
        }

        // Category
        if (isset($data['category'])) {
            $category = ExpenseCategory::where('name', $data['category'])->first();
            if (!$category) {
                //
            }
            $data['expense_category_id'] = $category?->id;
            unset($data['category']);
        }

        // Date
        if (isset($data['date'])) {
            $date = Carbon::parse($data['date'])->format('Y-m-d');
            $data['date'] = $date;
            Log::info('Date: '. $date);
        }

        return $data;
    }

    public static function processImportPreviewRow(array $row, bool $forImport = false): array
    {
        $allProperties = Property::isCompany(current_company()->id)->get();
        $inputName = $row['property'];

        $property = $allProperties->sortByDesc(function ($property) use ($inputName) {
            similar_text(strtolower($property->name), strtolower($inputName), $percent);
            return $percent;
        })->first();

        $unit = PropertyUnit::where('name', $row['room'])->first();

        if ($forImport) {
            return [
                'company_id' => current_company()->id,
                'reference' => $row['reference'] ?? "N/A",
                'title' => $row['title'] ?? "N/A",
                'category' => $row['category'] ?? "N/A",
                'amount' => $row['amount'] ?? "N/A",
                // 'date' => Carbon::parse($row['date'])->format('m/d/y') ?? "N/A",
                'date' => $row['date'] ?? today(),
            ];
        }

        return [
            'Property' => $property->name ?? 'N/A',
            'Room' => $unit->name ?? 'N/A',
            'Reference' => $row['reference'] ?? '',
            'Title' => $row['title'] ?? '',
            'Category' => $row['category'] ?? '',
            'Date' => Carbon::parse($row['date'])->format('m/d/y') ?? '',
            'Amount' => $row['amount'] ?? '',
        ];
    }

}
