<?php

namespace Modules\Properties\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;

class PricingModal extends ModalComponent
{

    public $unitType, $leaseTerms, $unitPrices;
    public $prices = 1;

    public function mount(PropertyUnitType $unitType){
        $this->unitType = $unitType;
        // $this->prices = count($unitType->prices()->get());

        $this->leaseTerms = toSelectOptions(LeaseTerm::isCompany(current_company()->id)->get(), 'id', 'name');
        // Load existing prices if they exist
        $unitPrices = $unitType->prices()->get();

        if ($unitPrices) {
            $this->unitPrices = $unitType->prices->map(function ($price) {
                return [
                    'id' => $price->id, // Keep track of existing prices
                    'rate_type' => $price->lease->id,
                    'rate' => $price->price,
                    'default' => (bool) $price->is_default,
                ];
            })->toArray();
        } else {
            // If no existing prices, initialize with an empty entry
            $this->unitPrices = [
                ['id' => null, 'rate_type' => '', 'rate' => null, 'default' => false],
            ];
        }
    }
    public function addPricing()
    {
        $this->unitPrices[] = ['id' => null, 'rate_type' => '', 'rate' => null, 'default' => false];
    }

    public function removePricing($index)
    {
        // If it's an existing price, remove from DB
        if ($this->unitPrices[$index]['id']) {
            PropertyUnitTypePricing::destroy($this->unitPrices[$index]['id']);
        }

        unset($this->unitPrices[$index]);
        $this->unitPrices = array_values($this->unitPrices); // Re-index array

        session()->flash('message', __('Pricing removed successfully!'));

        $this->dispatch('updated-pricing');
    }

    public function setDefault($index)
    {
        foreach ($this->unitPrices as $i => &$price) {
            $price['default'] = ($i === $index);
        }
    }


    public function render()
    {
        return view('properties::livewire.modal.pricing-modal');
    }

    public function save()
    {
        $this->validate([
            'unitPrices.*.rate_type' => 'required',
            'unitPrices.*.rate' => 'required|numeric|min:0',
        ]);

        foreach ($this->unitPrices as $price) {
            if ($price['id']) {
                // Update existing price
                PropertyUnitTypePricing::where('id', $price['id'])->update([
                    'lease_term_id' => $price['rate_type'],
                    'price' => $price['rate'],
                    'is_default' => $price['default'],
                ]);
            } else {
                // Create new price
                PropertyUnitTypePricing::create([
                    'company_id' => current_company()->id,
                    'property_id' => $this->unitType->property->id,
                    'property_unit_type_id' => $this->unitType->id,
                    'lease_term_id' => $price['rate_type'],
                    'name' => $this->unitType->name.' '. lease_term($price['rate_type'])->name,
                    'price' => $price['rate'],
                    'is_default' => $price['default'],
                ]);
            }
        }


        $this->unitPrices = $this->unitType->prices->map(function ($price) {
            return [
                'id' => $price->id, // Keep track of existing prices
                'rate_type' => $price->lease->id,
                'rate' => $price->price,
                'default' => (bool) $price->is_default,
            ];
        })->toArray();

        session()->flash('message', __('Pricing saved successfully!'));

        $this->dispatch('updated-pricing');
    }
}
