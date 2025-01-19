<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use App\Models\Team\Team;
use App\Models\Company\Company;
use App\Models\User;
use Modules\App\Handlers\AppManagerHandler;
use Modules\Properties\Handlers\PropertiesAppHandler;
use Modules\ChannelManager\Handlers\ChannelManagerAppHandler;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = Team::create([
            'uuid' => Uuid::uuid4(),
        ]);
        $team->save();

        $user = User::factory()->create([
            'team_id' => $team->id,
            'name' => 'Arden BOUET',
            'email' => 'laudbouetoumoussa@gmail.com',
            'password' => Hash::make('koverae'),
            'email_verified_at' => now(),
        ]);
        $user->save();
        // $user->generateAvatar();
        
        $team->update([
            'user_id' => $user->id
        ]);

        $company = Company::create([
            'team_id' => $team->id,
            'owner_id' => $user->id,
            'name' => "Lantern",
            'reference' => 'Lantern',
            'personal_company' => true,
            'domain_name' => "lantern",
            'website_url' => "lantern.".env('APP_DOMAIN'),
            'enabled' => 1,
            'email' => 'contact@lantern.co.ke',
            'phone' => 254745908026,
            'address' => 'Parklands Rd',
            'city' => 'Nairobi',
            'country' => 'Republic of Kenya',
            'industry' => 'serviced-apartment',
            'size' => 'small',
            'primary_interest' => 'manage_my_business',
            'default_currency' => 'KES',
        ]);
        $company->save();

        // Install Modules
        $appManager = new AppManagerHandler;
        $appManager->installModules($company->id, $user->id);

        $user->update([
            'company_id' => $company->id,
            'current_company_id' => $company->id,
            'language_id' => $company->languages()->first()->id
        ]);
        $user->save();
        // // Install Properties Module
        // $propertyManager = new PropertiesAppHandler;
        // $propertyManager->install($company->id, $user->id);

        // $channelManager = new ChannelManagerAppHandler;
        // $channelManager->install($company->id, $user->id);
    }
}
