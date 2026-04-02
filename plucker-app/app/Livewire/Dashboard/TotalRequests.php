<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\domain_routing as Router;
use App\Models\logger;
use App\Models\response_time;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;

class TotalRequests extends Component
{
    public $start_time;
    public $end_time;
    public $total_requests;
    public $host = '';
    public $test;
    public $time_frame;
    public $reqs;
    public $time_frame_array = [];
    public $reqs_array = [];
    public $blocked_array = [];
    public $domain;
    public $type;
    public $reqs_b;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->time_frame = [];
            $this->reqs = [];
        }
    }

    public function render()
    {
        if ($this->host != '') {


            $start_time = Carbon::parse($this->start_time);
            $end_time = Carbon::parse($this->end_time);

            // Calculate the difference in days
            $diffInDays = $start_time->diffInDays($end_time);

            if ($diffInDays >= 3) {
               
                $data = [];
                $this->type= '24hours';

               
                $startTime = strtotime($this->start_time . ' 00:00:00');
                $endTime = strtotime($this->end_time . ' 23:59:59');

                // Loop through the timestamps
                for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 86400) { // Increment by 6 hours
                    $startSlot = date('Y-m-d H:i:s', $currentTime);
                    $endSlot = date('Y-m-d H:i:s', $currentTime + 86400);

                    // Execute the query for the current time slot
                    $slotData = logger::select(
                        DB::raw("COUNT(id) as total_requests"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('host', '=', $this->host)
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                        'time_frame' => $startSlot,
                        'total_requests' => $slotData->total_requests ?? 0, // Handle null values gracefully
                    ];
                }
                foreach ($data as $tr) {
                    array_push($this->time_frame_array, '"' . date("jS M", strtotime($tr['time_frame'])) . '"');
                    array_push($this->reqs_array, $tr['total_requests']);
                }

                $this->time_frame = implode(',', $this->time_frame_array);
                $this->reqs = implode(',', $this->reqs_array);
            }
            // Check if the difference is more than one 3 days
            if ($diffInDays == 2) {
               
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
                    $slotData = logger::select(
                        DB::raw("COUNT(id) as total_requests"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('host', '=', $this->host)
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                        'time_frame' => $startSlot,
                        'total_requests' => $slotData->total_requests ?? 0, // Handle null values gracefully
                    ];
                }
                foreach ($data as $tr) {
                    array_push($this->time_frame_array, '"' . date("jS M h A", strtotime($tr['time_frame'])) . '"');
                    array_push($this->reqs_array, $tr['total_requests']);
                }

                $this->time_frame = implode(',', $this->time_frame_array);
                $this->reqs = implode(',', $this->reqs_array);
            }
            if ($diffInDays <= 1) {
                // Logic for one day or less
                // Generate time slots for the given date range
                // Array to store results
                $data = [];
                $this->type = '15mins';
                // Generate timestamps for 15-minute intervals within the specified range
                $startTime = strtotime($this->start_time . ' 00:00:00');
                $endTime = strtotime($this->end_time . ' 23:59:59');

                // Loop through the timestamps
                for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 15*60) { // Increment by 15 minutes
                    $startSlot = date('Y-m-d H:i:s', $currentTime);
                    $endSlot = date('Y-m-d H:i:s', $currentTime + 15*60);

                    // Execute the query for the current time slot
                    $slotData = logger::select(
                        DB::raw("COUNT(id) as total_requests"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('host', '=', $this->host)
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                        'time_frame' => $startSlot,
                        'total_requests' => $slotData->total_requests ?? 0, // Handle null values gracefully
                    ];
                }

                foreach ($data as $tr) {
                    array_push($this->time_frame_array, '"' . date("h:i A", strtotime($tr['time_frame'])) . '"');
                    array_push($this->reqs_array, $tr['total_requests']);
                }

                $this->time_frame = implode(',', $this->time_frame_array);
                $this->reqs = implode(',', $this->reqs_array);
            }

            //Blocked Requests in the same period

            if ($diffInDays >= 3) {
               
                $data = [];
                $this->type= '24hours';
               
                $startTime = strtotime($this->start_time . ' 00:00:00');
                $endTime = strtotime($this->end_time . ' 23:59:59');

                // Loop through the timestamps
                for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 86400) { // Increment by 24 hours
                    $startSlot = date('Y-m-d H:i:s', $currentTime);
                    $endSlot = date('Y-m-d H:i:s', $currentTime + 86400);

                    // Execute the query for the current time slot
                    $slotData = logger::select(
                        DB::raw("COUNT(id) as blocked_requests"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('host', '=', $this->host)
                        ->where('analysis','=','Blocked')
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                        'blocked_requests' => $slotData->blocked_requests ?? 0, // Handle null values gracefully
                    ];
                }
                foreach ($data as $tr) {
                    array_push($this->blocked_array, $tr['blocked_requests']);
                }
                $this->reqs_b = implode(',', $this->blocked_array);
            }
            // Check if the difference is more than one 3 days
            if ($diffInDays == 2) {
               
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
                    $slotData = logger::select(
                        DB::raw("COUNT(id) as blocked_requests"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('analysis','=','Blocked')
                        ->where('host', '=', $this->host)
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                      
                        'blocked_requests' => $slotData->blocked_requests ?? 0, // Handle null values gracefully
                    ];
                }
                foreach ($data as $tr) {
                  
                    array_push($this->blocked_array, $tr['blocked_requests']);
                }

               
                $this->reqs_b = implode(',', $this->blocked_array);
            }
            if ($diffInDays <= 1) {
                // Logic for one day or less
                // Generate time slots for the given date range
                // Array to store results
                $data = [];
                $this->type = '15mins';
                // Generate timestamps for 15-minute intervals within the specified range
                $startTime = strtotime($this->start_time . ' 00:00:00');
                $endTime = strtotime($this->end_time . ' 23:59:59');

                // Loop through the timestamps
                for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 15*60) { // Increment by 15 minutes
                    $startSlot = date('Y-m-d H:i:s', $currentTime);
                    $endSlot = date('Y-m-d H:i:s', $currentTime + 15*60);

                    // Execute the query for the current time slot
                    $slotData = logger::select(
                        DB::raw("COUNT(id) as blocked_requests"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('analysis','=','Blocked')
                        ->where('host', '=', $this->host)
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                       
                        'blocked_requests' => $slotData->blocked_requests ?? 0, // Handle null values gracefully
                    ];
                }

                foreach ($data as $tr) {
               
                    array_push($this->blocked_array, $tr['blocked_requests']);
                }
                $this->reqs_b = implode(',', $this->blocked_array);
            }

            return view('livewire.dashboard.total-requests');
        }
        return '<div>403</div>';
    }
}
