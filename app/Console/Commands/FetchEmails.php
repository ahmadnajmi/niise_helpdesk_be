<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Mail;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Mail::raw('Hello World!', function($msg) {$msg->to('myemail@gmail.com')->subject('Test Email'); });

        // $client = Client::account('gmail');
        $client = Client::account('default');  // uses config/imap.php or .env

        $client->connect();
        // $folder = $client->getFolder('INBOX');

        // $messages = $folder->query()
        //             // ->from('najmi@gmail.com')
        //             ->since(today())
        //             ->limit(1) 
        //             ->get();

        // foreach ($messages as $message) {

        //     $emailData[] = [
        //         'from' => $message->getFrom()[0]->mail,
        //         'subject' => $message->getSubject(),
        //         'body' => $message->getHTMLBody(true) ?? $message->getTextBody(),
        //         'message_id' => $message->getMessageId()
        //     ];

        // }

        $folders = $client->getFolders();

        foreach ($folders as $folder) {
            echo $folder->name . "\n";
            
            $messages = $folder->messages()->all()->get();

            foreach ($messages as $message) {
                echo $message->getSubject() . "\n";
            }
        }

        // dd($emailData,'ya');
    }
}
