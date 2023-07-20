<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::create([
            'amount'=> '1000',
            'order_id'=> '1',
            'status'=> '0',
            'phase1'=> '1',
        ]);

        Project::create([
            'amount'=> '2000',
            'order_id'=> '2',
            'status'=> '1',
            'phase1'=> '1',
            'phase2'=> '1',
        ]);

        Project::create([
            'amount'=> '360',
            'order_id'=> '3',
            'status'=> '2',
        ]);

        Project::create([
            'amount'=> '3000',
            'order_id'=> '4',
            'status'=> '3',
        ]);

        Project::create([
            'amount'=> '1000',
            'order_id'=> '5',
            'status'=> '3',
        ]);

        Project::create([
            'amount'=> '80',
            'order_id'=> '6',
            'status'=> '3',
        ]);

        Project::create([
            'amount'=> '200',
            'order_id'=> '7',
            'status'=> '3',
        ]);
    }
}
