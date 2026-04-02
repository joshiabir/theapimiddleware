<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\response_time;

class ResponseTime extends Component
{
    public $host = '';
    public $ip;
    public $last_modified;
    public $response_time;
    public $start_time;
    public $end_time;
    public $time_frame;
    public $reqs;
    public $time_frame_array =[0];
    public $reqs_array = [0];
    public $domain;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->ip = $route[0]['ip'];
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
                // Logic for when the difference is more than one day
                // Logic for one day or less
                // Generate time slots for the given date range
                // Array to store results
                $data = [];

                // Generate timestamps for 24 hours intervals within the specified range
                $startTime = strtotime($this->start_time . ' 00:00:00');
                $endTime = strtotime($this->end_time . ' 23:59:59');

                // Loop through the timestamps
                for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 86400) { // Increment by 6 hours
                    $startSlot = date('Y-m-d H:i:s', $currentTime);
                    $endSlot = date('Y-m-d H:i:s', $currentTime + 86400);

                    // Execute the query for the current time slot
                    $slotData = response_time::select(
                        DB::raw("AVG(response_time) as response_time"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('host', '=', $this->host)
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                        'time_frame' => $startSlot,
                        'response_time' => $slotData->response_time ?? 0, // Handle null values gracefully
                    ];
                }
                foreach ($data as $tr) {
                    array_push($this->time_frame_array, '"' . date("jS M", strtotime($tr['time_frame'])) . '"');
                    array_push($this->reqs_array,  number_format(((float)$tr['response_time']*100), 1, '', '.'));
                }

                 $this->time_frame = implode(',', $this->time_frame_array);
                $this->reqs = implode(',', $this->reqs_array);
            }
            // Check if the difference is more than one 3 days
            if ($diffInDays == 2) {
                // Logic for when the difference is more than one day
                // Logic for one day or less
                // Generate time slots for the given date range
                // Array to store results
                $data = [];

                // Generate timestamps for 6 hours intervals within the specified range
                $startTime = strtotime($this->start_time . ' 00:00:00');
                $endTime = strtotime($this->end_time . ' 23:59:59');

                // Loop through the timestamps
                for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 21600) { // Increment by 6 hours
                    $startSlot = date('Y-m-d H:i:s', $currentTime);
                    $endSlot = date('Y-m-d H:i:s', $currentTime + 21600);

                    // Execute the query for the current time slot
                    $slotData = response_time::select(
                        DB::raw("AVG(response_time) as response_time"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('host', '=', $this->host)
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                        'time_frame' => $startSlot,
                        'response_time' => $slotData->response_time ?? 0, // Handle null values gracefully
                    ];
                }
                foreach ($data as $tr) {
                    array_push($this->time_frame_array, '"' . date("jS M h A", strtotime($tr['time_frame'])) . '"');
                    array_push($this->reqs_array,  number_format(((float)$tr['response_time']*100), 1, '', '.'));
                }

                 $this->time_frame = implode(',', $this->time_frame_array);
                $this->reqs = implode(',', $this->reqs_array);
            }
            if ($diffInDays <= 1) {
                // Logic for one day or less
                // Generate time slots for the given date range
                // Array to store results
                $data = [];

                // Generate timestamps for 30-minute intervals within the specified range
                $startTime = strtotime($this->start_time . ' 00:00:00');
                $endTime = strtotime($this->end_time . ' 23:59:59');

                // Loop through the timestamps
                for ($currentTime = $startTime; $currentTime <= $endTime; $currentTime += 15*60) { // Increment by 60 minutes
                    $startSlot = date('Y-m-d H:i:s', $currentTime);
                    $endSlot = date('Y-m-d H:i:s', $currentTime + 15*60);

                    // Execute the query for the current time slot
                    $slotData = response_time::select(
                        DB::raw("AVG(response_time) as response_time"),
                        DB::raw("'$startSlot' AS time_frame") // Use the current slot as the time_frame
                    )
                        ->where('created_at', '>=', $startSlot)
                        ->where('created_at', '<', $endSlot)
                        ->where('host', '=', $this->host)
                        ->where('response_time', '>', 0) // Exclude zero values
                        ->first(); // Fetch the result for the slot

                    // Add the result to the final data array
                    $data[] = [
                        'time_frame' => $startSlot,
                        'response_time' => $slotData->response_time ?? 0, // Handle null values gracefully
                    ];
                }

                foreach ($data as $tr) {
                    array_push($this->time_frame_array, '"' . date("h:i A", strtotime($tr['time_frame'])) . '"');
                    array_push($this->reqs_array, number_format(((float)$tr['response_time']*100), 1, '', '.'));
                }
                

                 $this->time_frame = implode(',', $this->time_frame_array);
                $this->reqs = implode(',', $this->reqs_array);
            }







            return view('livewire.dashboard.response-time');
        }
        return '<div>403</div>';
    }
}
