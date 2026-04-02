<div class="space-y-4">

    {{-- KPI strip --}}
    @php
        $api_stats   = $apis[0] ?? null;
        $total        = $api_stats ? (int) $api_stats->count   : 0;
        $clients_n    = $api_stats ? (int) $api_stats->clients : 0;
        $failed_n     = $api_stats ? (int) $api_stats->failed  : 0;
        $success_rate = $total > 0 ? round((($total - $failed_n) / $total) * 100, 1) : 0;
        $error_rate   = $total > 0 ? round(($failed_n / $total) * 100, 1) : 0;
    @endphp

    <div class="grid grid-cols-4 gap-4">
        <div class="bg-white border border-zinc-200 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Total Requests</p>
            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?start_datetime={{ $start_time }}&end_datetime={{ $end_time }}&url={{ urlencode($url) }}"
               class="text-2xl font-semibold text-zinc-900 hover:text-blue-600 transition-colors">
                {{ number_format($total_requests) }}
            </a>
        </div>
        <div class="bg-white border border-zinc-200 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Unique Clients</p>
            <span class="text-2xl font-semibold text-zinc-900">{{ number_format($clients_n) }}</span>
        </div>
        <div class="bg-white border border-zinc-200 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Success Rate</p>
            <span class="text-2xl font-semibold {{ $success_rate >= 95 ? 'text-green-600' : ($success_rate >= 80 ? 'text-amber-500' : 'text-red-500') }}">
                {{ $success_rate }}<span class="text-sm font-normal text-zinc-400 ml-0.5">%</span>
            </span>
        </div>
        <div class="bg-white border border-zinc-200 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Error Rate</p>
            <span class="text-2xl font-semibold {{ $error_rate > 5 ? 'text-amber-500' : 'text-zinc-900' }}">
                {{ $error_rate }}<span class="text-sm font-normal text-zinc-400 ml-0.5">%</span>
            </span>
        </div>
    </div>

    {{-- Traffic chart --}}
    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="px-5 pt-4 pb-1 flex items-center justify-between">
            <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Requests Over Time</p>
            <span class="text-xs font-mono text-zinc-400 truncate max-w-xs">{{ $url }}</span>
        </div>
        <div class="px-2 pb-2">
            <div id="chart-api-requests"></div>
        </div>
        <script>
            const optsApiTR = {
                series: [{ name: "Requests", color: "#2563eb", data: [{!!$reqs!!}] }],
                chart: { type: "area", height: "220px", toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
                fill: { opacity: 0.07, type: 'solid' },
                stroke: { width: 2, curve: 'smooth' },
                legend: { show: false },
                dataLabels: { enabled: false },
                tooltip: { shared: true, intersect: false },
                xaxis: {
                    categories: [{!!$time_frame!!}],
                    labels: { show: true, rotate: -25, style: { cssClass: 'text-xs fill-zinc-400', fontSize: '10px' } },
                    axisTicks: { show: false },
                    axisBorder: { show: false },
                },
                yaxis: { labels: { show: true, style: { cssClass: 'text-xs fill-zinc-400', fontSize: '10px' } } },
                grid: { show: true, strokeDashArray: 4, borderColor: '#f4f4f5', padding: { left: 2, right: 2, top: -10 } },
            };
            if (document.getElementById("chart-api-requests") && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.getElementById("chart-api-requests"), optsApiTR).render();
            }
        </script>
    </div>

</div>
