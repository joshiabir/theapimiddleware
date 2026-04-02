<?php

namespace App\Livewire\Logs;

use Livewire\Component;
use App\Models\logger;
use Livewire\WithPagination;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Models\dlp_log;

class Logs extends Component
{
    public $start_time;
    public $end_time;
    public $host = '';
    public $key;
    public $url = 'Any';
    public $method = 'Any';
    public $status = 'Any';
    public $user = 'Any';
    public $dlp;
    public $domain;
    public $route;
    use WithPagination;

    public function mount(Request $request)
    {
        $this->domain;
        $this->route = $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->start_time;
            $this->end_time;
            $this->key;
            if (isset($request['url'])) {
                $this->url = str_replace("%2F", "/", rawurlencode($request['url']));
            }
            if (isset($request['method'])) {
                $this->method = $request['method'];
            }
            if (isset($request['status'])) {
                $this->status = $request['status'];
            }
            if (isset($request['user'])) {
                $this->user = $request['user'];
            }
            if (isset($request['dlp'])) {
                $this->dlp = $request['dlp'];
            }
        }
    }
    public function download()
    {
        if ($this->host == '') return '<div>403</div>';

        $fileName = "tam_logs_$this->start_time-$this->end_time.csv";
        $method = $url = $status = $user = '%';

        if ($this->method != 'Any') $method = $this->method;
        if ($this->url != 'Any') $url = $this->url;
        if ($this->status != 'Any') $status = $this->status;
        if ($this->user != 'Any') $user = $this->user;

        if ($url == '%' && $method == '%' && $status == '%' && $user == '%') {
            $logs = logger::select('key', 'analysis', 'created_at', 'host', 'domain_resolved', 'url', 'request_method', 'response_time', 'middleware_response', 'response_status', 'client')
                ->where('created_at', '>=', $this->start_time . ' 00:00:00')
                ->where('created_at', '<=', $this->end_time . ' 23:59:59')
                ->where('host', '=', $this->host)->orderby('created_at', 'desc')->get();
        }
        if (($url != '%' || $method != '%' || $user != '') && $status == '%') {

            $logs = logger::select('key', 'analysis', 'created_at', 'host', 'domain_resolved', 'url', 'request_method', 'response_time', 'middleware_response', 'response_status', 'client')
                ->where('created_at', '>=', $this->start_time . ' 00:00:00')
                ->where('created_at', '<=', $this->end_time . ' 23:59:59')
                ->where("url", "like", "$url")
                ->where("request_method", "like", "$method")
                ->where("client", "like", "%$user%")
                ->where('host', '=', $this->host)->orderby('created_at', 'desc')->get();
        }
        if ($status != '%') {
            $logs = logger::select('key', 'analysis', 'created_at', 'host', 'domain_resolved', 'url', 'request_method', 'response_time', 'middleware_response', 'response_status', 'client')
                ->where('created_at', '>=', $this->start_time . ' 00:00:00')
                ->where('created_at', '<=', $this->end_time . ' 23:59:59')
                ->where("url", "like", "$url")
                ->where("request_method", "like", "$method")
                ->whereraw("response_status like '$status'")
                ->where("client", "like", "%$user%")
                ->where('host', '=', $this->host)->orderby('created_at', 'desc')->get();
        }

        $response = new StreamedResponse(function () use ($logs) {
            $handle = fopen('php://output', 'w');

            // Add CSV column headers
            fputcsv($handle, [
                'Key',
                'Analysis',
                'Created At',
                'Host',
                'Domain Resolved',
                'URL',
                'Request Method',
                'Response Time',
                'Middleware Response',
                'Response Status',
                'Client'
            ]);

            // Write data rows
            foreach ($logs as $row) {
                fputcsv($handle, [
                    $row->key,
                    $row->analysis,
                    $row->created_at,
                    $row->host,
                    $row->domain_resolved,
                    $row->url,
                    $row->request_method,
                    $row->response_time,
                    $row->middleware_response,
                    $row->response_status,
                    json_decode($row->client,true)[0],
                ]);
            }

            fclose($handle);
        });

        // Set headers for CSV download
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }


    public function render()
    {
        if ($this->host == '') return "<div>403</div> ";

        $method = $url = $status = $user = '%';
        $key_val = '';

        if ($this->method != 'Any') $method = $this->method;
        if ($this->url != 'Any') $url = $this->url;
        if ($this->status != 'Any') $status = $this->status;
        if ($this->user != 'Any') $user = $this->user;
        if ($this->key != '' && $this->dlp == 'false') {
            $key_val =  logger::select('prams', 'analysis', 'count', 'value', 'key', 'response_status', 'response_body', 'response_headers', 'request_headers', 'request_body')
                ->where('host', '=', $this->host)
                ->where('key', '=', base64_decode($this->key)) //please add a strip vaidation
                ->get();
        }
        if ($this->key != '' && $this->dlp == 'true') {
            $key_val = dlp_log::where('log_id', '=', base64_decode($this->key))->get();
        }

        if ($url == '%' && $method == '%' && $status == '%' && $user == '%') {
            $logs = logger::select('key', 'analysis', 'created_at', 'host', 'domain_resolved', 'url', 'request_method', 'response_time', 'middleware_response', 'response_status', 'client')
                ->where('created_at', '>=', $this->start_time . ' 00:00:00')
                ->where('created_at', '<=', $this->end_time . ' 23:59:59')
                ->where('host', '=', $this->host)->orderby('created_at', 'desc')->paginate(15);
        }
        if (($url != '%' || $method != '%' || $user != '') && $status == '%') {

            $logs = logger::select('key', 'analysis', 'created_at', 'host', 'domain_resolved', 'url', 'request_method', 'response_time', 'middleware_response', 'response_status', 'client')
                ->where('created_at', '>=', $this->start_time . ' 00:00:00')
                ->where('created_at', '<=', $this->end_time . ' 23:59:59')
                ->where("url", "like", "$url")
                ->where("request_method", "like", "$method")
                ->where("client", "like", "%$user%")
                ->where('host', '=', $this->host)->orderby('created_at', 'desc')->paginate(15);
        }
        if ($status != '%') {
            $logs = logger::select('key', 'analysis', 'created_at', 'host', 'domain_resolved', 'url', 'request_method', 'response_time', 'middleware_response', 'response_status', 'client')
                ->where('created_at', '>=', $this->start_time . ' 00:00:00')
                ->where('created_at', '<=', $this->end_time . ' 23:59:59')
                ->where("url", "like", "$url")
                ->where("request_method", "like", "$method")
                ->whereraw("response_status like '$status'")
                ->where("client", "like", "%$user%")
                ->where('host', '=', $this->host)->orderby('created_at', 'desc')->paginate(15);
        }


        return view('livewire.logs.logs', ['logs' => $logs, 'key_val' => $key_val, 'url_see' => $this->url, 'status_see' => $this->status, 'method_see' => $this->method, 'user_see' => $this->user, 'dlp' => $this->dlp]);
    }
}
