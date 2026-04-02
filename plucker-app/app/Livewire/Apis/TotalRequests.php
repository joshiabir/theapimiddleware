<?php

namespace App\Livewire\Apis;


use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\domain_routing as Router;
use App\Models\response_time;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\logger;

class TotalRequests extends Component
{

    public $start_time;
    public $end_time;
    public $total_requests;
    public $host;
    public $test;
    public $time_frame;
    public $reqs;
    public $url;
    public $domain;
    public $type;
    public $apis;

    public function mount()
    {
        $this->domain;
        $host = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($host) > 0) {
            $this->host = $host[0]['host'];
            $this->time_frame = [];
            $this->reqs = [];
            $this->url;
        }
    }

    public function render()
    {
        if ($this->host == '')
            return '<div>403</div>';
        $start_time = Carbon::parse($this->start_time);
        $end_time = Carbon::parse($this->end_time);
        $startTime = $this->start_time . ' 00:00:00';
        $endTime = $this->end_time . ' 23:59:59';
        // Calculate the difference in days
        $diffInDays = $start_time->diffInDays($end_time);
        $this->total_requests = logger::where('host', '=', $this->host)
            ->where('created_at', '>=', $startTime)
            ->where('created_at', '<', $endTime)
            ->where('url', '=', $this->url)->count();

            
        if ($diffInDays >= 3) {
            // Logic for when the difference is more than one day
            // Logic for one day or less
            // Generate time slots for the given date range
            // Array to store results
            $data = [];
            $this->type = '24hours';

            // Generate timestamps for 24 hours intervals within the specified range
            $startTime = strtotime($this->start_time . ' 00:00:00');
            $endTime = strtotime($this->end_time . ' 23:59:59');

            // Loop through the timestamps
            for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 86400) { // Increment by 6 hours
                $startSlot = date('Y-m-d H:i:s', $currentTime);
                $endSlot = date('Y-m-d H:i:s', $currentTime + 86400);

                // Execute the query for the current time slot
                $slotData = response_time::select(
                    DB::raw("COUNT(id) as total_requests"),
                    DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                )
                    ->where('created_at', '>=', $startSlot)
                    ->where('created_at', '<', $endSlot)
                    ->where('url', '=', $this->url)
                    ->where('host', '=', $this->host)
                    ->first(); // Fetch the result for the slot

                // Add the result to the final data array
                $data[] = [
                    'time_frame' => $startSlot,
                    'total_requests' => $slotData->total_requests ?? 0, // Handle null values gracefully
                ];
            }
            foreach ($data as $tr) {
                array_push($this->time_frame, '"' . date("jS M", strtotime($tr['time_frame'])) . '"');
                array_push($this->reqs, $tr['total_requests']);
            }

            $this->time_frame = implode(',', $this->time_frame);
            $this->reqs = implode(',', $this->reqs);
        }
        // Check if the difference is more than one 3 days
        if ($diffInDays == 2) {
            // Logic for when the difference is more than one day
            // Logic for one day or less
            // Generate time slots for the given date range
            // Array to store results
            $data = [];
            $this->type = '6hours';
            // Generate timestamps for 6 hours intervals within the specified range
            $startTime = strtotime($this->start_time . ' 00:00:00');
            $endTime = strtotime($this->end_time . ' 23:59:59');

            // Loop through the timestamps
            for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 21600) { // Increment by 6 hours
                $startSlot = date('Y-m-d H:i:s', $currentTime);
                $endSlot = date('Y-m-d H:i:s', $currentTime + 21600);

                // Execute the query for the current time slot
                $slotData = response_time::select(
                    DB::raw("COUNT(id) as total_requests"),
                    DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                )
                    ->where('created_at', '>=', $startSlot)
                    ->where('created_at', '<', $endSlot)
                    ->where('url', '=', $this->url)
                    ->where('host', '=', $this->host)
                    ->first(); // Fetch the result for the slot

                // Add the result to the final data array
                $data[] = [
                    'time_frame' => $startSlot,
                    'total_requests' => $slotData->total_requests ?? 0, // Handle null values gracefully
                ];
            }
            foreach ($data as $tr) {
                array_push($this->time_frame, '"' . date("jS M h A", strtotime($tr['time_frame'])) . '"');
                array_push($this->reqs, $tr['total_requests']);
            }

            $this->time_frame = implode(',', $this->time_frame);
            $this->reqs = implode(',', $this->reqs);
        }
        if ($diffInDays <= 1) {
            // Logic for one day or less
            // Generate time slots for the given date range
            // Array to store results
            $data = [];
            $this->type = '15mins';
            // Generate timestamps for 30-minute intervals within the specified range
            $startTime = strtotime($this->start_time . ' 00:00:00');
            $endTime = strtotime($this->end_time . ' 23:59:59');

            // Loop through the timestamps
            for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 15 * 60) { // Increment by 60 minutes
                $startSlot = date('Y-m-d H:i:s', $currentTime);
                $endSlot = date('Y-m-d H:i:s', $currentTime + 15 * 60);

                // Execute the query for the current time slot
                $slotData = response_time::select(
                    DB::raw("COUNT(id) as total_requests"),
                    DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                )
                    ->where('created_at', '>=', $startSlot)
                    ->where('created_at', '<', $endSlot)
                    ->where('url', '=', $this->url)
                    ->where('host', '=', $this->host)
                    ->first(); // Fetch the result for the slot

                // Add the result to the final data array
                $data[] = [
                    'time_frame' => $startSlot,
                    'total_requests' => $slotData->total_requests ?? 0, // Handle null values gracefully
                ];
            }

            foreach ($data as $tr) {
                array_push($this->time_frame, '"' . date("h:i A", strtotime($tr['time_frame'])) . '"');
                array_push($this->reqs, $tr['total_requests']);
            }

            $this->time_frame = implode(',', $this->time_frame);
            $this->reqs = implode(',', $this->reqs);
        }

        $startTime = $this->start_time . ' 00:00:00';
        $endTime = $this->end_time . ' 23:59:59';

        if ($this->host != '') {
            $this->apis = [];
            $this->apis = logger::select(
                DB::RAW('count(id) as count'),
                DB::RAW('count(DISTINCT client) as clients'),
                DB::raw('SUM(CASE WHEN response_status > 300 THEN 1 ELSE 0 END) as failed')
            )
                ->where('host', '=', $this->host)
                ->where('created_at', '>=', $startTime)
                ->where('created_at', '<', $endTime)
                ->where('url', '=', $this->url)
                ->orderByRaw('LENGTH(url) ASC')
                ->get();
        }

        return view('livewire.apis.total-requests');
    }
}
