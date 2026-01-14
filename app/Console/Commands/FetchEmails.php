<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Services\IncidentServices;
use App\Models\Incident;
use App\Models\User;

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
        $log = [];
        $log['step 1'] = 'Email IMAP Start Now At : ' . now();

        try {
            $client = Client::account('default');  

            $client->connect();

            $log['step 2'] = 'Connected IMAP';

            $folder = $client->getFolder('INBOX');

            $log['step 3'] = 'INBOX loaded';

            $query = $folder->messages()->all();

            $count = $query->count();

            $log["step 4"] = 'Message count: ' . $count;

            $messages = $query->get();
            $idx = 0;

            foreach ($messages as $message) {
                $idx++;

                $step = 'step 5.' . ($idx);

                $firstSender = $message->getFrom()->first();

                $log[$step . '.1']  = 'From Email: ' . $firstSender->mail;

                $create_incident = $this->createIncident($firstSender,$message);

                if(isset($create_incident['data'])){
                    $incident = $create_incident['data'];

                    $log[$step . '.2'] = 'Incident created for '.$incident->incident_no;
                }
                else{
                    $log[$step . '.2'] = 'Incident created failed';
                }

                
            }

            $log["step 6"] = 'IMAP done';
        } 
        catch (\Throwable $e) {
            $log['Error'] = 'ERROR: ' . $e->getMessage();

            Log::channel('scheduler')->error('IMAP job', [
                'time'  => now()->toDateTimeString(),
                'steps' => $log,
            ]);

            return Command::FAILURE;
        }

        Log::channel('scheduler')->info('IMAP job', [
            'time'  => now()->toDateTimeString(),
            'steps' => $log,
        ]);

    }

    public function createIncident($firstSender,$message){
        $get_user = User::where('email',$firstSender->mail)->first();

        if($get_user){
            $data['complaint_user_id'] = $get_user?->id;
        }
        else{
            $data['name'] = $firstSender->personal;
            $data['email'] = $firstSender->mail;
        }

        $data['received_via'] = Incident::RECIEVED_EMAIL;
        $data['information'] = $message->getTextBody(); 
        // $data['information'] = $message->getHTMLBody(true) ?? $message->getTextBody();

        $incident = IncidentServices::createIncident($data);
        
        return $incident;
    }
    
}
