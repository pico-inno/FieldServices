<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    protected $event;
    protected $description;
    protected $log_name;
    protected $status;
    protected $data;

    public function __construct($log_name = 'default')
    {
        $this->log_name = $log_name;
    }

    public function log($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the event type.
     *
     * @param string $event The event type (create, update, delete, restore, login, logout).
     * @return $this
     */
    public function event($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * Set the status of the operation.
     *
     * @param string $status The status of the operation (success, warn, fail).
     * @return $this
     */
    public function status($status)
    {
        $this->status = $status;
        return $this;
    }

    public function properties($data)
    {
        $this->data = $data;
        return $this;
    }

    public function autoEvent()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        if (isset($trace[1]['function'])) {
            $methodName = $trace[1]['function'];

            if (in_array($methodName, ['store', 'update', 'destroy'])) {
                $this->event($methodName);
            }
        }

        return $this;
    }

    public function save()
    {
        return ActivityLog::create([
            'log_name' => $this->log_name,
            'description' => $this->description,
            'event' => $this->event,
            'status' => $this->status,
            'properties' => json_encode($this->data),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function update($id)
    {
        $activityLog = ActivityLog::findOrFail($id);

        $activityLog->update([
            'log_name' => $this->log_name ?? $activityLog->log_name,
            'description' => $this->description ?? $activityLog->description,
            'event' => $this->event ?? $activityLog->event,
            'status' => $this->status ?? $activityLog->status,
            'properties' => $this->data ? json_encode($this->data) : $activityLog->properties,
            'created_by' => Auth::id() ?? $activityLog->created_by,
            'updated_by' => Auth::id() ?? $activityLog->updated_by,
        ]);
    }
}
