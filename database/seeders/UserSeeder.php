<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $christian = \App\Models\User::factory()->create([
            'name' => 'Christian',
            'email' => 'user@example.com',
        ]);
        $christian->contactData()->create([
            'label' => 'Email',
            'type' => \App\Models\ContactData::TYPE_EMAIL,
            'value' => 'christian.pasin.rey@gmail.com',
            'is_main' => true,
        ]);
        $christian->contactData()->create([
            'label' => 'Phone',
            'type' => \App\Models\ContactData::TYPE_PHONE,
            'value' => '+39 333 1234567',
            'is_main' => true,
        ]);
        $christian->contactData()->create([
            'label' => 'Linkedin',
            'type' => \App\Models\ContactData::TYPE_LINKED,
            'value' => 'christian-pasin-rey',
            'is_main' => true,
        ]);
        $christian->contactData()->create([
            'label' => 'Facebook',
            'type' => \App\Models\ContactData::TYPE_FACEBOOK,
            'value' => 'christianpasinrey',
            'is_main' => true,
        ]);
        $christian->contactData()->create([
            'label' => 'Instagram',
            'type' => \App\Models\ContactData::TYPE_INSTAGRAM,
            'value' => 'christianprey',
            'is_main' => true,
        ]);

        \App\Models\User::factory()->count(100)->create()->each(function ($user) {
            $random_phone_number= '+34 '.rand(6000000, 6999999);
            $user->contactData()->create([
                'label' => \Illuminate\Support\Arr::random(['Personal','Trabajo','Otros']),
                'type' => \App\Models\ContactData::TYPE_EMAIL,
                'value' => $user->name.'@example.com',
                'is_main' => true,
            ]);
            $user->contactData()->create([
                'label' => \Illuminate\Support\Arr::random(['Personal','Trabajo','Casa']),
                'type' => \App\Models\ContactData::TYPE_PHONE,
                'value' => $random_phone_number,
                'is_main' => true,
            ]);
            $user->contactData()->create([
                'label' => 'Profesional',
                'type' => \App\Models\ContactData::TYPE_LINKED,
                'value' => $user->name,
                'is_main' => true,
            ]);
            $user->contactData()->create([
                'label' => 'Facebook',
                'type' => \App\Models\ContactData::TYPE_FACEBOOK,
                'value' => $user->name,
                'is_main' => true,
            ]);
            $user->contactData()->create([
                'label' => 'Instagram',
                'type' => \App\Models\ContactData::TYPE_INSTAGRAM,
                'value' => $user->name,
                'is_main' => true,
            ]);
        });

        $christian->contacts()->attach(\App\Models\User::all()->random(50));
    }
}
