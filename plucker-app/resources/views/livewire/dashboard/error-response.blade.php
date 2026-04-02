<div class='col-span-2' >
    <div class=" w-full rounded-xl dark:bg-gray-800">
        <div class="flex justify-between mb-2 dark:border-gray-700">
                <div class='pt-2 pl-2'>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white pb-1">Error Responses</h5>
                </div>
               
        </div>
        <div>
            <div class="shadow-xl rounded-xl p-4 bg-orange-600" id="bar-chart_er"></div>
        </div>
        

    </div>
    <script>
        const options_er = {
            series: [{
                    name: "Error Response",
                    color: "#fff",
                    data: [{!!$reqs!!}],
                },
            ],
            chart: {
                sparkline: {
                    enabled: false,
                },
                type: "bar",
                height: "200px",
                toolbar: {
                    show: false,
                }
            },
            fill: {
                opacity: 1,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "70%",
                    borderRadiusApplication: "end",
                    borderRadius: 0,
                    dataLabels: {
                        position: "top",
                    },
                },
            },
            legend: {
                show: false,
                position: "bottom",
            },
            dataLabels: {
                enabled: false,
            },
            tooltip: {
                shared: true,
                intersect: false,
                formatter: function(value) {
                    return value
                }
            },
            xaxis: {
                labels: {
                    show: false,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-white dark:fill-gray-400'
                    },
                    formatter: function(value) {
                        return value
                    }
                },
                categories: [{!!$time_frame!!}],
                axisTicks: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-white dark:fill-gray-400'
                    }
                }
            },
            grid: {
                show: true,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -20
                },
            },
            fill: {
                opacity: 1,
            }
        }

        if (document.getElementById("bar-chart_er") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("bar-chart_er"), options_er);
            chart.render();
        }
    </script>

</div>