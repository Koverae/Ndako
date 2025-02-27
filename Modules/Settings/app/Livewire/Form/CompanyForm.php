<?php

namespace Modules\Settings\Livewire\Form;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Template\SimpleAvatarForm;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\Settings\Models\Currency\Currency;

class CompanyForm extends SimpleAvatarForm
{
    public $company;
    public $name, $address, $city, $department, $country, $zip, $tax, $phone, $phone_2, $currency,
    $email, $website, $tags= [], $image_path,  $photo = null, $companyID, $reference, $note, $bankAccount, $addresses;
    public array $currencyOptions = [];
    
    protected $rules = [
        'name' => 'required|string|max:30',
        'phone' => ['nullable', 'string'], // Phone validation example
        // 'phone' => ['nullable', 'regex:/^[0-9]{10}$/'], // Phone validation example
        'phone_2' => ['nullable', 'string'], // phone_2 validation example
        'email' => 'nullable|email',
        'website' => 'nullable|url', // Validate that the website is a valid URL
        'address' => 'nullable|string|max:40',
        'city' => 'nullable|string|max:20',
        'country' => 'nullable|integer|exists:countries,id',
        'zip' => 'nullable|string|max:9',
        'reference' => 'nullable|string|max:20',
        'note' => 'nullable|string|max:1000',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function mount($company = null){
        $this->default_img = 'default_logo';
        if($company){
            $this->company = $company;
            $this->name = $company->name;
            $this->phone = $company->phone;
            $this->email = $company->email;
            $this->phone_2 = $company->phone_2;
            $this->website = $company->website;
            $this->address = $company->address;
            $this->city = $company->city;
            $this->country = $company->country;
            $this->zip = $company->zip;
            $this->image_path = $company->avatar;
            $this->reference = $company->reference;
            $this->currency = $company->default_currency;
            $this->currencyOptions = toSelectOptions(Currency::isCompany($company->id)->get(), 'code', 'currency_name');
        }
    }

    public function inputs(): array
    {
        return [
            Input::make('name', "Company Name", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Marie Reine Ltd'))->component('app::form.input.ke-title'),
            Input::make('tax-id', __('Tax ID'), 'text', 'tax', 'left', 'none', 'none', __('e.g. KE0466566704')),
            Input::make('email', __('Email'), 'email', 'email', 'right', 'none', 'none', null),
            Input::make('phone', __('Phone'), 'tel', 'phone', 'right', 'none', 'none', null),
            Input::make('phone_2', __('Mobile'), 'tel', 'phone_2', 'right', 'none', 'none', null),
            Input::make('website', __('Website'), 'text', 'website', 'right', 'none', 'none', __('e.g. https://www.koverae.com')),
            Input::make('address', __('Address'), 'select', 'address', 'left', 'none', 'none', "")->component('app::form.input.select.address'),
            Input::make('currency', __('Currency'), 'select', 'currency', 'right', 'none', 'none', "", null, $this->currencyOptions),
        ];
    }

    public function updatedPhoto(){
        // Validate the uploaded file
        // $this->validate();
        if($this->company){
            $company = Company::find($this->company->id);
    
            if(!$this->image_path){
                $this->image_path = $company->id . '_logo.png';
    
                // $this->photo->storeAs('avatars', $this->image_path, 'public');
                $company->update([
                    'avatar' => $this->image_path,
                ]);
            }
    
            $this->photo->storeAs('avatars', $this->image_path, 'public');
    
    
            // Send success message
            session()->flash('message', 'Logo updated successfully!');
        }
    }
    
    #[On('create-company')]
    public function createcompany(){
        
        $company = Company::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'phone_2' => $this->phone_2,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            // 'zip' => $this->zip,
            // 'avatar' => $this->image_path,
            'reference' => $this->reference,
            'default_currency' => $this->currency,
        ]);
        $company->save();
        
        $avatar = $company->id.'_logo.png';
        if($this->photo){
            $this->photo->storeAs('avatars', $avatar, 'public');
        }
        $company->update([
            'avatar' => $avatar,
        ]);

        return $this->redirect(route('settings.companies.show', ['company' => $company->id]), navigate: true);
    }
    
    #[On('update-company')]
    public function updatecompany(){
        $company = Company::find($this->company->id);
        
        $company->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'phone_2' => $this->phone_2,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            // 'zip' => $this->zip,
            // 'image_path' => $this->logo,
            'reference' => $this->reference,
            'default_currency' => $this->currency,
        ]);
        $company->save();
        return $this->redirect(route('settings.companies.show', ['company' => $company->id]), navigate: true);
    }

    public function updated(){
        $this->dispatch('change');
    }
}
