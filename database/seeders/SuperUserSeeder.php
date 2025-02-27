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

        // Install Modules
        $appManager = new AppManagerHandler;
        $appManager->configure();

        // $user = User::factory()->create([
        //     'name' => 'Arden BOUET',
        //     'email' => 'laudbouetoumoussa@gmail.com',
        //     'phone' => '',
        //     'password' => Hash::make('koverae'),
        // ]);
        // $user->save();

    }
}
