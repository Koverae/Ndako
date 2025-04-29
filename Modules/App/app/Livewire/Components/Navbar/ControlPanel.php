<?php

namespace Modules\App\Livewire\Components\Navbar;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Modules\App\Services\ReportExportService;

use function Pest\Laravel\instance;

abstract class ControlPanel extends Component
{
    #[Url(as: 'view_type', keep: true)]
    public $view_type = 'lists';

    public $search = '';
    public bool $change = false, $showBreadcrumbs = true, $hasSelection = false, $showCreateButton = true, $showPagination = false, $showIndicators= false, $isForm = false;

    // Configurable options
    public $separator = '/', $urlPrefix = '', $currentPage, $new, $newModal, $add, $event, $createButtonLabel = 'Nouveau';

    public array $breadcrumbs = [], $selected = [];
    public array $filters = [], $filterTypes = [];
    public array $groupBy = [];

    // public $view_type = 'lists';


    public function mount(){
        $this->view_type = $this->view;
    }

    public function render()
    {
        return view('app::livewire.components.navbar.control-panel');
    }

    public function switchButtons() : array{
        return [];
    }

    #[On('updatedSelected')]
    public function updatedSelectedItems($selected)
    {
        $this->selected = $selected;
        $this->hasSelection = count($this->selected) >= 1;
    }

    public function emptyArray()
    {
        // Empty the $ids array
        $this->selected = [];
        $this->hasSelection = count($this->selected) >= 1;
        $this->dispatch('emptyArray');
    }

    public function generateBreadcrumbs()
    {
        // Get all the URL segments from the current request
        $segments = request()->segments();

        // Initialize an empty array to hold the breadcrumbs
        $breadcrumbs = [];

        // Start from the root and loop through each segment in the URL
        $currentUrl = ''; // Initialize an empty string to store the accumulated URL

        foreach ($segments as $index => $segment) {
            // Append the current segment to the accumulated URL
            $currentUrl .= '/' . $segment;

            // If a URL prefix is set, add it before the URL
            $fullUrl = $this->urlPrefix ? "{$this->urlPrefix}{$currentUrl}" : $currentUrl;

            // Append each breadcrumb as an associative array with 'url' and 'label'
            $breadcrumbs[] = [
                'url' => url($fullUrl), // Generate the full URL for this segment
                'label' => Str::of($segment) // Format the segment into a human-readable label
                            ->replace(['-', '_'], ' ') // Replace hyphens and underscores with spaces
                            ->title() // Capitalize the first letter of each word
                            ->toString(), // Convert the result to a string
            ];
        }

        // Debugging the output
        Log::debug('Breadcrumbs: ',$breadcrumbs); // Log breadcrumbs

        // Store the generated breadcrumbs in the object's breadcrumbs property
        $this->breadcrumbs = $breadcrumbs;
    }


    public function updatedSearch($value)
    {
        // Update guests based on search term
        $this->dispatch('update-search', search: $this->search);
    }


    #[On('change')]
    public function changeDetected(){
        $this->change = true;
    }

    public function saveUpdate(){
        $this->dispatch($this->event);
        // $this->dispatch('saveChange');
    }

    public function resetForm(){
        $this->dispatch('reset-form');
    }


    public function actionDropdowns() : array{
        return [];
    }

    public  function actionButtons() : array{
        return [];
    }

    public function switchView($view){
        $this->dispatch('switch-view', view: $view);
        return $this->view_type = $view;
    }

    /**
     * Delete items from a given model using an array or collection of IDs.
     *
     * @param $model  Fully qualified class name (e.g. App\Models\User::class)
     * @param  array  $ids          Array of IDs to delete
     * @return int                  Number of deleted items
     */
    public function deleteItems($model, array $ids): int
    {
        return DB::transaction(function () use ($model, $ids) {
            return $model->whereIn('id', $ids)->delete();
        });
    }


    public function archive(){
        //
    }

    public function unarchive(){
        //
    }

    public function toggleFilter($group, $option)
    {
        // Handle boolean, int, and string filter types properly
        if (array_key_exists($option, $this->filterTypes[$group])) {
            // For boolean or int keys, use the value as the actual filter key
            $value = $option;
        } else {
            // Otherwise, treat it as a string
            $value = $this->filterTypes[$group][$option];
        }

        // Toggle the selected filter
        if (in_array($value, $this->filters[$group] ?? [])) {
            // If the option is already selected, remove it
            $this->filters[$group] = array_filter($this->filters[$group], fn($filter) => $filter !== $value);
        } else {
            // Otherwise, add it
            $this->filters[$group][] = $value;
        }

        $this->dispatch('updateFilters', $this->filters);
    }

    public function removeFilter(string $key): void
    {
        unset($this->filters[$key]);

        $this->dispatch('updateFilters', $this->filters);
    }

}
