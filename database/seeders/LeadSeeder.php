<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Lead;
use App\Models\User;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userIds = User::pluck('id')->toArray();


        foreach (range(1, 10) as $i) {
            Lead::create([
                'id' => Str::uuid(),
                'user_id' => $userIds[array_rand($userIds)],
                'title' => 'Lead ' . $i,
                'email' => 'lead' . $i . '@example.com',
                'phone' => '99999999' . $i,
                'status' => collect(['lead', 'contacted', 'proposal_sent', 'won'])->random(),
            ]);
        }
    }
}
