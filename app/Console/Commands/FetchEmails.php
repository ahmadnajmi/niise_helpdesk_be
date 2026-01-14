<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        Log::info('Test Email IMAP ' . now());

        try {

            $client = Client::account('default');  // uses config/imap.php or .env

            $client->connect();

            Log::info('Success Connect IMAP ' . now());

            $folder = $client->getFolder('INBOX');

            Log::info('Success Get Folder IMAP ' . now());

            $count = $folder->messages()->all()->count();
            Log::info('Message count: ' . $count);

            $messages = $folder->messages()->all()->get();

            foreach ($messages as $message) {
                Log::info($message->getSubject());
            }
        } 
        catch (\Throwable $e) {
            Log::critical("Unexpected scheduler failure: " . $e->getMessage());
            return Command::FAILURE;
        }

        Log::info('Test Email IMAP done at ' . now());
    }
}
