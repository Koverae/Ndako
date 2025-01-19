<?php

namespace Modules\RevenueManager\Livewire\Settings;

use Modules\App\Livewire\Components\Settings\AppSetting;
use Modules\App\Livewire\Components\Settings\Block;
use Modules\App\Livewire\Components\Settings\Box;
use Modules\App\Livewire\Components\Settings\BoxAction;
use Modules\App\Livewire\Components\Settings\BoxInput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Modules\RevenueManager\Models\Accounting\FiscalPackage;
use Modules\RevenueManager\Models\Tax\Tax;
use Modules\Settings\Models\Currency\Currency;

class RevenueManagerSetting extends AppSetting
{
    public $setting;
    public $fiscal_localization='kenya', $fiscal_country = 'kenya', $default_currency = 'kenyan_shilling', $default_term_note, $digitize_customer_invoices = 'digitize_on_demand';
    public array $localizations = [], $fiscalCountriesOptions = [], $currenciesOptions = [], $digitizationOptions = [], $salesTaxes = [];

    public function mount($setting){
        $this->setting = $setting;
        $this->fiscal_localization = $setting->fiscal_localization;
        $this->fiscal_country = $setting->fiscal_country;
        $this->default_currency = $setting->default_currency_id;

        $this->localizations = toSelectOptions(FiscalPackage::isCompany(current_company()->id)->get(), 'id', 'name');

        $this->currenciesOptions = toSelectOptions(Currency::isCompany(current_company()->id)->get(), 'id', 'currency_name');

        $this->salesTaxes = toSelectOptions(Tax::isCompany(current_company()->id)->isType('sales')->get(), 'id', 'name');

        $countries = [
            ['id' => 'uganda', 'label' => 'ᴜɢ Uganda'],
            ['id' => 'kenya', 'label' => 'ᴋᴇ Kenya'],
            ['id' => 'ethiopia', 'label' => 'ᴇᴛʜ Ethiopia'],
            ['id' => 'rwanda', 'label' => 'ʀᴡ Rwanda'],
            ['id' => 'tanzania', 'label' => 'ᴛᴀɴ Tanzania'],
            ['id' => 'south_africa', 'label' => 'sᴀ South Africa'],
            ['id' => 'nigeria', 'label' => 'ɴɢ Nigeria'],
            ['id' => 'egypt', 'label' => 'ᴇɢʏ Egypt'],
            ['id' => 'ghana', 'label' => 'ɢʜ Ghana'],
            ['id' => 'morocco', 'label' => 'ᴍᴀʀ Morocco'],
            ['id' => 'algeria', 'label' => 'ᴀʟɢ Algeria'],
            ['id' => 'libya', 'label' => 'ʟʏ Libya'],
            ['id' => 'angola', 'label' => 'ᴀɴɢ Angola'],
            ['id' => 'mozambique', 'label' => 'ᴍᴏᴢ Mozambique'],
            ['id' => 'namibia', 'label' => 'ɴᴀᴍ Namibia'],
            ['id' => 'zambia', 'label' => 'ᴢᴀᴍ Zambia'],
            ['id' => 'zimbabwe', 'label' => 'ᴢɪᴍ Zimbabwe'],
            ['id' => 'botswana', 'label' => 'ʙᴡᴛ Botswana'],
            ['id' => 'malawi', 'label' => 'ᴍᴡɪ Malawi'],
            ['id' => 'senegal', 'label' => 'sᴇɴ Senegal'],
            ['id' => 'mali', 'label' => 'ᴍʟɪ Mali'],
            ['id' => 'burkina_faso', 'label' => 'ʙғᴀ Burkina Faso'],
            ['id' => 'niger', 'label' => 'ɴᴇʀ Niger'],
            ['id' => 'mauritius', 'label' => 'ᴍᴜs Mauritius'],
            ['id' => 'seychelles', 'label' => 'sʏᴄ Seychelles'],
            ['id' => 'sudan', 'label' => 'sᴜᴅ Sudan'],
            ['id' => 'eritrea', 'label' => 'ᴇʀᴇ Eritrea'],
            ['id' => 'somalia', 'label' => 'sᴏᴍ Somalia'],
            ['id' => 'djibouti', 'label' => 'ᴅᴊɪ Djibouti'],
            ['id' => 'comoros', 'label' => 'ᴄᴏᴍ Comoros'],
            ['id' => 'equatorial_guinea', 'label' => 'ᴇɢɴ Equatorial Guinea'],
            ['id' => 'gabon', 'label' => 'ɢᴀʙ Gabon'],
            ['id' => 'congo_brazzaville', 'label' => 'ᴄɢᴏ Congo-Brazzaville'],
            ['id' => 'congo_kinshasa', 'label' => 'ᴄᴏᴅ Congo-Kinshasa'],
            ['id' => 'central_african_republic', 'label' => 'ᴄᴀʀ Central African Republic'],
            ['id' => 'cameroon', 'label' => 'ᴄᴍʀ Cameroon'],
            ['id' => 'ivory_coast', 'label' => 'ᴄɪᴠ Ivory Coast'],
            ['id' => 'sierra_leone', 'label' => 'sʟᴇ Sierra Leone'],
            ['id' => 'liberia', 'label' => 'ʟɪʙ Liberia'],
            ['id' => 'benin', 'label' => 'ʙᴇɴ Benin'],
            ['id' => 'togo', 'label' => 'ᴛɢᴏ Togo'],
            ['id' => 'burundi', 'label' => 'ʙᴜʀ Burundi'],
            ['id' => 'lesotho', 'label' => 'ʟsᴏ Lesotho'],
            ['id' => 'cape_verde', 'label' => 'ᴄᴠᴇ Cape Verde'],
            ['id' => 'swaziland', 'label' => 'sᴡᴢ Eswatini'],
            ['id' => 'gambia', 'label' => 'ɢᴍʙ Gambia'],
            ['id' => 'guinea', 'label' => 'ɢɪɴ Guinea'],
            ['id' => 'guinea_bissau', 'label' => 'ɢɴʙ Guinea-Bissau'],
            ['id' => 'madagascar', 'label' => 'ᴍᴅɢ Madagascar'],
            ['id' => 'mauritania', 'label' => 'ᴍʀᴛ Mauritania'],
            ['id' => 'sao_tome_principe', 'label' => 'sᴛᴘ Sao Tome & Principe']
        ];
        $this->fiscalCountriesOptions = toSelectOptions($countries, 'id', 'label');
    }

    public function blocks() : array
    {
        return [
            Block::make('local-taxes', __('Local Tax Adaptation')),
            Block::make('taxes', __('Taxes')),
            Block::make('currencies', __('Currencies')),
            Block::make('digitization', __('Digitization')),
        ];
    }

    public function boxes() : array
    {
        return [
            Box::make('fiscal-localization', "Fiscal Localization", 'fiscal_localization', "Taxes, fiscal positions, chart of accounts & legal statements for your country", 'local-taxes', false, "https://ndako.koverae.com/docs", null),
            Box::make('default-taxes', "Default Taxes", 'default_taxes', "Default taxes applied to local transactions", 'taxes', false, "", null),
            Box::make('fiscal-country', "Fiscal Country", 'fiscal_country', "Domestic country of your accounting", 'taxes', false, "", null),
            Box::make('main-currency', "Main Currency", 'fiscal_localization', "Main currency of your company", 'currencies', false, "", null),
            Box::make('document-digitization', __('Document Digitization'), 'has_digitize_document', "Digitize your PDF or scanned documents with Artificial Intelligence and OCR", 'digitization', true, "", null),
        ];
    }

    public function inputs() : array
    {
        return [
            BoxInput::make('fiscal-localization-package', __('Package'), 'select', 'fiscal_localization', 'fiscal-localization', '', false, $this->localizations),
            BoxInput::make('sales-tax', __('Sales Tax'), 'select', 'sales_tax', 'default-taxes', '', false, $this->salesTaxes),
            // BoxInput::make('purchase-tax', __('Purchase Tax'), 'select', 'purchase_tax', 'default-taxes', '', false, $this->localizations),
            BoxInput::make('fiscal-country', null, 'select', 'fiscal_country', 'fiscal-country', '', false, $this->fiscalCountriesOptions),
            BoxInput::make('main-currency', null, 'select', 'default_currency', 'main-currency', '', false, $this->currenciesOptions),
        ];
    }

    #[On('save')]
    public function save(){
        $setting = $this->setting;

        $setting->update([
            'fiscal_localization' => $this->fiscal_localization,
            'fiscal_country' => $this->fiscal_country,
            'default_currency_id' => $this->default_currency,
        ]);
        $setting->save();

        cache()->forget('settings');

        // notify()->success('Updates saved!');

        $this->dispatch('undo-change');

    }
    public function updated(){
        $this->dispatch('change');
    }
}
