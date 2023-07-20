<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Document::create([
            'title'=> 'First doc',
            'description'=> 'Amet consectetur adipisicing elit. Repellat officiis, in fugiat cum commodi reiciendis illo magnam cupiditate provident deleniti ratione incidunt quos nihil ab tempore! Placeat provident earum nesciunt!',
            'file_name'=> 'test.pdf',
            'path'=> 'storage/documents/test.pdf',
            'downloads'=> 230,
        ]);
        Document::create([
            'title'=> 'Traders Guide',
            'description'=> 'Ipsum dgiat incidunt quos nihil ab tempore! Placeat provident earum nesciunt!',
            'file_name'=> 'test.pdf',
            'path'=> 'storage/documents/test.pdf',
            'downloads'=> 112,
        ]);
        Document::create([
            'title'=> 'Best optioms',
            'description'=> 'Lorem, ipsum dolor eleniti ratione incidunt quos nihil ab tempore! Placeat provident earum nesciunt!',
            'file_name'=> 'test.pdf',
            'path'=> 'storage/documents/test.pdf',
            'downloads'=> 24,
        ]);
    }
}
