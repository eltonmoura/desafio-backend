<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('senha123'),
            'identity' => '111.111.111-01',
            'type' => 'person',
        ]);

        User::create([
            'name' => 'João da Silva',
            'email' => 'joao@mail.com',
            'password' => Hash::make('senha123'),
            'identity' => '111.111.111-02',
            'type' => 'person',
        ]);

        User::create([
            'name' => 'Maria Pereira',
            'email' => 'maria@mail.com',
            'password' => Hash::make('senha123'),
            'identity' => '111.111.111-03',
            'type' => 'person',
        ]);

        User::create([
            'name' => 'Padaria Boa',
            'email' => 'padaria@mail.com',
            'password' => Hash::make('senha123'),
            'identity' => '11.111.111/0001-01',
            'type' => 'company',
        ]);

        User::create([
            'name' => 'Mercado Barato',
            'email' => 'mercado@mail.com',
            'password' => Hash::make('senha123'),
            'identity' => '11.111.111/0001-02',
            'type' => 'company',
        ]);
    }
}
