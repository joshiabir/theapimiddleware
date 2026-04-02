<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden h-full flex flex-col">
    <div class="px-5 pt-4 pb-1">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Security Breakdown</p>
    </div>
    <div class="flex-1" id="chart-security-donut"></div>
    <script>
        const optsDonut = {
            series: [{{ $clean }}, {{ $blocked }}, {{ $dlp }}, {{ $rate_limited }}, {{ $errors }}],
            chart: { type: 'donut', height: '100%', toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
            labels: ['Clean', 'Blocked', 'DLP', 'Rate Limited', 'Errors'],
            colors: ['#16a34a', '#ef4444', '#9333ea', '#f59e0b', '#94a3b8'],
            legend: { position: 'bottom', fontSize: '12px' },
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '12px',
                                color: '#71717a',
                                fontWeight: 400,
                                formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString()
                            }
                        }
                    }
                }
            },
            stroke: { width: 0 },
            tooltip: { style: { fontFamily: "Inter, sans-serif" } },
        };
        if (document.getElementById('chart-security-donut') && typeof ApexCharts !== 'undefined') {
            new ApexCharts(document.getElementById('chart-security-donut'), optsDonut).render();
        }
    </script>
</div>
