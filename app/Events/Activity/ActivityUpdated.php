<?php

namespace Convoy\Events\Activity;

use Convoy\Models\ActivityLog;
use Convoy\Models\Server;
use Convoy\Models\User;
use Convoy\Transformers\Client\ActivityLogTransformer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use LogicException;

class ActivityUpdated extends Activity implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return fractal($this->model, new ActivityLogTransformer)->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->isServerEvent()) {
            return new PrivateChannel('server.'.$this->model->subjects()->firstWhere('subject_type', (new Server)->getMorphClass())?->subject_id);
        } elseif ($this->isUserEvent()) {
            return new PrivateChannel('user.'.$this->model->subjects()->firstWhere('subject_type', (new User)->getMorphClass())?->subject_id);
        }

        throw new LogicException('Event subject is invalid');
    }
}
