<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
    <div class="px-5 pt-4 pb-1">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">HTTP Methods</p>
    </div>
    @php
        $method_labels = $request_method->map(fn($m) => '"' . $m->request_method . '"')->implode(',');
        $method_counts = $request_method->pluck('count')->implode(',');
    @endphp
    <div id="chart-methods"></div>
    <script>
        const optsMethods = {
            series: [{!! $method_counts ?: '0' !!}],
            chart: { type: 'donut', height: 260, toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
            labels: [{!! $method_labels ?: '"No Data"' !!}],
            colors: ['#2563eb', '#16a34a', '#f59e0b', '#9333ea', '#ef4444', '#06b6d4', '#f97316'],
            legend: { position: 'bottom', fontSize: '12px' },
            dataLabels: { enabled: false },
            plotOptions: { pie: { donut: { size: '60%' } } },
            stroke: { width: 0 },
            tooltip: { style: { fontFamily: "Inter, sans-serif" } },
        };
        if (document.getElementById('chart-methods') && typeof ApexCharts !== 'undefined') {
            new ApexCharts(document.getElementById('chart-methods'), optsMethods).render();
        }
    </script>
</div>
