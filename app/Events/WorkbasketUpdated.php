<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WorkbasketUpdated implements ShouldBroadcast
{
    public $incident;
    public $trigger;

    public function __construct($incident,$trigger)
    {
        $this->incident = $incident;
        $this->trigger = $trigger;
    }

    public function broadcastAs(){
        return 'WorkbasketUpdated';
    }

    public function broadcastOn()
    {
        $channels = [];

        Log::info('Trigger WorkbasketUpdated event for incident ' . $this->incident->incident_no . ' with trigger ' . json_encode($this->trigger));

        if($this->trigger['frontliner']){
            $channels[] =  new PrivateChannel('workbasket.frontliner');
        }

        if($this->trigger['contractor']){
            $channels[] =  new PrivateChannel('workbasket.contractor.' . $this->incident->assign_group_id);
        }

        if($this->trigger['btmr'] || $this->trigger['jim']){
            $id = $this->incident->created_by ;
            
            $channels[] = new PrivateChannel('workbasket.user.' . $id);
        }

        return $channels;
    }
}