<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'last_name' => '01',
            'email' => 'admin@gmail.com',
            'password' => 'jdelgado',
            'remember_token' => Str::random(30)
        ]);

        $file = new File(['route' => '/storage/images/users/userDefault.png']);
        $user->file()->save($file);
        $user->assignRole('admin');
    }
}
