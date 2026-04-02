<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
    <div class="px-5 pt-4 pb-1 flex items-center justify-between">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Response Time</p>
        @php
            $rt_arr  = array_filter(explode(',', $reqs), fn($v) => is_numeric(trim($v)) && (float) trim($v) > 0);
            $avg_val = count($rt_arr) > 0 ? round(array_sum($rt_arr) / count($rt_arr), 1) : 0;
        @endphp
        <span class="text-xs text-zinc-400">avg {{ $avg_val }} ms</span>
    </div>
    <div class="px-2 pb-2">
        <div id="chart-response-time"></div>
    </div>
    <script>
        const optsRT = {
            series: [{ name: "Response Time (ms)", color: "#16a34a", data: [{!!$reqs!!}] }],
            chart: { type: "area", height: "230px", toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
            fill: { opacity: 0.07, type: 'solid' },
            stroke: { width: 2, curve: 'smooth' },
            legend: { show: false },
            dataLabels: { enabled: false },
            tooltip: { shared: true, intersect: false, y: { formatter: val => val + ' ms' } },
            xaxis: {
                categories: [{!!$time_frame!!}],
                labels: { show: true, rotate: -25, style: { cssClass: 'text-xs fill-zinc-400', fontSize: '10px' } },
                axisTicks: { show: false },
                axisBorder: { show: false },
            },
            yaxis: { labels: { show: true, style: { cssClass: 'text-xs fill-zinc-400', fontSize: '10px' }, formatter: val => val + ' ms' } },
            grid: { show: true, strokeDashArray: 4, borderColor: '#f4f4f5', padding: { left: 2, right: 2, top: -10 } },
        };
        if (document.getElementById("chart-response-time") && typeof ApexCharts !== 'undefined') {
            new ApexCharts(document.getElementById("chart-response-time"), optsRT).render();
        }
    </script>
</div>
