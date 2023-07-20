<?php

namespace Database\Seeders;

use App\Models\MessageTicket;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ticket::create([
            'id' => '1',
            'user_id' => '2',
            'categories' => '1',
            'status' => '0',
            'subject' => 'Bug fix',
        ]);
        MessageTicket::create([
            'id' => '1',
            'ticket_id' => '1',
            'support_id' => '1',
            'user_id' => '2',
            'type' => '0',
            'message' => 'Help me please, I have a bug',
            'image' => 'storage/support/ticket-1/images/2/capture.jpeg',
        ]);
        MessageTicket::create([
            'id' => '2',
            'ticket_id' => '1',
            'support_id' => '1',
            'user_id' => '1',
            'type' => '1',
            'message' => 'Where is the bug bro?',
            'image' => null,
        ]);
        MessageTicket::create([
            'id' => '3',
            'ticket_id' => '1',
            'support_id' => '1',
            'user_id' => '2',
            'type' => '0',
            'message' => 'ta bien',
            'image' => null,
        ]);

        Ticket::create([
            'id' => '2',
            'user_id' => '2',
            'categories' => '0',
            'status' => '1',
            'subject' => 'How can I use this',
        ]);
        MessageTicket::create([
            'id' => '4',
            'ticket_id' => '2',
            'support_id' => '1',
            'user_id' => '2',
            'type' => '0',
            'message' => 'I do not know how to use this thing',
            'image' => null,
        ]);
    }
}
