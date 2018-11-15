<?php

namespace Rebing\Timber\Requests\Events;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Rebing\Timber\Requests\LogLine;

abstract class AbstractEvent implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    protected $userContext;

    public function handle()
    {
        $this->send();
    }

    public function send()
    {
        $log = new LogLine();
        $log->json(
            $this->getMessage(),
            $this->getContext(),
            $this->getEvent(),
            $this->getLogLevel()
        );
    }

    public function getLogLevel(): string
    {
        return LogLine::LOG_LEVEL_INFO;
    }

    abstract public function getMessage(): string;

    abstract public function getEvent(): array;

    abstract public function getContext(): array;

    protected function getSystemContext(): array
    {
        $hostName = gethostname();

        return [
            'hostname' => $hostName,
            'ip'       => gethostbyname($hostName),
            'pid'      => getmypid(),
        ];
    }

    protected function getUserContext(): ?array
    {
        if ($this->userContext) {
            return $this->userContext;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'id' => Auth::id(),
            ];

            if (isset($user->name)) {
                $data['name'] = $user->name;
            }
            if (isset($user->email)) {
                $data['email'] = $user->email;
            }

            return $data;
        }

        return null;
    }

    public function setUserContext(array $data): void
    {
        $this->userContext = $data;
    }
}