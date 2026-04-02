<div class='col-span-9'>
    <div class='grid grid-cols-1 md:grid-cols-6 gap-4'>
        <div class="col-span-3 rounded-2xl bg-[#F9F9F9] dark:bg-gray-800">
            <div class="flex justify-between mb-2 dark:border-gray-700">
                <div class='p-5 pl-8'>
                    <h5 class="text-2xl font-regular text-gray-800 dark:text-white pb-1">Requests</h5>
                </div>
            </div>
            <div class='pr-8 pl-4'>
                <div class="" id="bar-chart_tr"></div>
            </div>
        </div>
        <script>
            const options_tr = {
                series: [{
                    name: "Total Requests",
                    color: "#3D89FA",
                    data: [{!!$reqs!!}],
                }, ],
                chart: {
                    sparkline: {
                        enabled: false,
                    },
                    type: "area",
                    height: "200px",
                    toolbar: {
                        show: false,
                    }
                },
                fill: {
                    opacity: 1,
                },
                stroke: {
                    width: 3,
                },
                plotOptions: {
                    area: {
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
                    show: true,
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
                            cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
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
                            cssClass: 'text-xs mr-2 font-bold fill-gray-500 dark:fill-gray-400'
                        }
                    }
                },
                grid: {
                    show: true,
                    strokeDashArray: 2,
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

            if (document.getElementById("bar-chart_tr") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("bar-chart_tr"), options_tr);
                chart.render();
            }
        </script>
        <div class='col-span-3 grid grid-cols-1 md:grid-cols-3 gap-3'>
            <div class='col-span-3 w-full bg-[#585858] rounded-xl '>
                <div class="w-full justify-between mb-2 dark:border-gray-700">
                    <div class='pt-5 pl-8 w-full'>
                        <h5 class="text-lg font-bold text-white pb-1">Hits</h5>
                    </div>

                    <div clas=''>
                        <div class='flex flex-wrap p-2 max-h-32 overflow-y-auto pl-4'>
                            @php
                            $req_a = explode(",",$reqs);
                            $time_frame_a = explode(",",$time_frame);
                            $p=0;
                            $t=1;
                            @endphp
                            @foreach($req_a as $key=>$r)
                            @if($r != 0)
                            <a href="#">
                                <div class=' text-white h-14 w-14 justify-center items-center rounded-sm text-center'>
                                    <div class='mt-2 font-semibold text-md'>{{$r}}</div>
                                    <div class="mt-2 text-[8px] font-semibold text-lime-500">{{str_replace('"','',$time_frame_a[$key])}}</div>
                                </div>
                            </a>
                            @endif
                            @php $p=$r+$p;$t=$t+1; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-span-1 bg-[#858585] rounded-xl h-32 justify-between'>
                <div class="p-8">
                    <a href="{{ route('logs',['domain'=>base64_encode($domain),'start_datetime'=>$start_time,'end_datetime'=>$end_time,'url'=>urldecode($url)]) }}">
                    <h4 class='text-5xl font-semibold text-white'>{{$total_requests }}</h4>
                    <p class='text-white text-sm mt-2'>Total Requests</p>
                    </a>
                </div>
            </div>
            <div class='col-span-1 bg-[#BEBEBE] rounded-xl h-32 justify-between'>
                <div class="p-8">
                    @if($type=='24hours')
                    <h4 class='text-5xl font-semibold text-white'>{{number_format(($p/$t),1)}}</h4>
                    <p class='text-white text-sm mt-2'>Request per day</p>
                    @endif
                    @if($type=='6hours')
                    <h4 class='text-5xl font-semibold text-white'>{{number_format(($p/($t*6)),1)}}</h4>
                    <p class='text-white text-sm mt-2'>Request per hour</p>
                    @endif
                    @if($type=='15mins')
                    <h4 class='text-5xl font-semibold text-white'>{{number_format(($p/($t*15)),2)}}</h4>
                    <p class='text-white text-sm mt-2'>Request per minute</p>
                    @endif
                </div>
            </div>
            <div class='col-span-1 bg-[#585858] rounded-xl h-32 justify-between'>
                <div class="p-8">
                    <h4 class='text-5xl font-semibold text-white'>
                    @if(isset($apis[0]->count) && $apis[0]->count >0 )    
                    {{number_format((($apis[0]->count-$apis[0]->failed)*100/$apis[0]->count),0)}}%
                    @else
                    -
                    @endif
                    </h4>
                    <p class='text-white text-sm mt-2'>Success Rate</p>
                </div>
            </div>
        </div>
    </div>
</div>