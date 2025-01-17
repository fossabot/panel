<?php

namespace Convoy\Jobs\Servers;

use Convoy\Enums\Servers\Status;
use Convoy\Facades\Activity;
use Convoy\Facades\LogRunner;
use Convoy\Facades\LogTarget;
use Convoy\Models\ActivityLog;
use Convoy\Models\Objects\Server\ServerDeploymentObject;
use Convoy\Models\Server;
use Convoy\Models\Template;
use Convoy\Services\Activity\ActivityLogBatchService;
use Convoy\Services\Servers\BuildService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Webmozart\Assert\Assert;

class ProcessBuild implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1000;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    protected ActivityLog $activity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Server $server, protected ServerDeploymentObject $deployment, protected ?string $batchUuid)
    {
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new WithoutOverlapping($this->server->id)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ActivityLogBatchService $batch, BuildService $builder)
    {
        Assert::isInstanceOf($this->server, Server::class);

        LogTarget::setSubject($this->server);

        $this->activity = Activity::event('server:build')->runner()->log();

        try {
            $batch->transaction(function () use ($builder) {
                $this->server->update(['status' => Status::INSTALLING->value]);

                $builder->setServer($this->server)->build(Template::findOrFail($this->deployment->template_id));

                $this->server->update(['status' => null]);
                LogRunner::setActivity($this->activity)->end();
            }, $this->batchUuid);
        } catch (\Exception $e) {
            LogRunner::setActivity($this->activity)->error();

            throw $e;
        }
    }
}
