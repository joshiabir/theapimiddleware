<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
    <div class="px-5 pt-4 pb-1 flex items-center justify-between">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Requests Over Time</p>
        @php
            $req_nums = array_filter(explode(',', $reqs), fn($v) => is_numeric(trim($v)));
            $total_p  = (int) array_sum($req_nums);
        @endphp
        <span class="text-xs text-zinc-400">{{ number_format($total_p) }} total</span>
    </div>
    <div class="px-2 pb-2">
        <div id="chart-total-requests"></div>
    </div>
    <script>
        const optsTR = {
            series: [
                { name: "Total", color: "#2563eb", data: [{!!$reqs!!}] },
                { name: "Blocked", color: "#f87171", data: [{!!$reqs_b!!}] }
            ],
            chart: { type: "area", height: "230px", toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
            fill: { opacity: 0.07, type: 'solid' },
            stroke: { width: 2, curve: 'smooth' },
            legend: { show: true, position: "bottom", fontSize: "12px" },
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
        if (document.getElementById("chart-total-requests") && typeof ApexCharts !== 'undefined') {
            new ApexCharts(document.getElementById("chart-total-requests"), optsTR).render();
        }
    </script>
</div>
