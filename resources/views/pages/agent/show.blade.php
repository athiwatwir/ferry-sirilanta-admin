@extends('layouts.default')


@section('content')
<x-agent.header :agent="$agent" />



<div class="row">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card-title mb-0">
                            <h5 class="mb-1">Booking sumary</h5>
                            <p class="card-subtitle">Total sales of booking</p>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 text-end">
                        <div class="btn-group me-3">
                            <button type="button" class="btn btn-label-primary">2025</button>
                            <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">2024</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">2025</a></li>

                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-label-primary">January</button>
                            <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">January</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">February</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">March</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">April</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">May</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">June</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">July</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">August</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">September</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">October</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">November</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">December</a></li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-body">
                <div id="shipmentStatisticsChart"></div>
            </div>
        </div>
    </div>
</div>

@stop


@section('script')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>


<script>
    $(document).ready(function() {
        let borderColor, labelColor, headingColor, legendColor;

        if (isDarkStyle) {
            borderColor = config.colors_dark.borderColor;
            labelColor = config.colors_dark.textMuted;
            headingColor = config.colors_dark.headingColor;
            legendColor = config.colors_dark.bodyColor;
        } else {
            borderColor = config.colors.borderColor;
            labelColor = config.colors.textMuted;
            headingColor = config.colors.headingColor;
            legendColor = config.colors.bodyColor;
        }


        const chartColors = {
            donut: {
                series1: config.colors.success
                , series2: '#53D28C'
                , series3: '#7EDDA9'
                , series4: '#A9E9C5'
            }
            , line: {
                series1: config.colors.warning
                , series2: config.colors.primary
                , series3: '#7367f029'
            }
        };

        const shipmentEl = document.querySelector('#shipmentStatisticsChart')
            , shipmentConfig = {
                series: [{
                        name: 'Booking Sumary'
                        , type: 'column'
                        , data: [50000, 68565, 25355, 9889, 78525, 55640, 68300, 42500, 65400, 85100]
                    }

                ]
                , chart: {
                    height: 420
                    , type: 'line'
                    , stacked: false
                    , parentHeightOffset: 0
                    , toolbar: {
                        show: false
                    }
                    , zoom: {
                        enabled: false
                    }
                }
                , markers: {
                    size: 5
                    , colors: [config.colors.white]
                    , strokeColors: chartColors.line.series2
                    , hover: {
                        size: 6
                    }
                    , borderRadius: 4
                }
                , stroke: {
                    curve: 'smooth'
                    , width: [0, 3]
                    , lineCap: 'round'
                }
                , legend: {
                    show: true
                    , position: 'bottom'
                    , markers: {
                        width: 8
                        , height: 8
                        , offsetX: -3
                    }
                    , height: 40
                    , itemMargin: {
                        horizontal: 10
                        , vertical: 0
                    }
                    , fontSize: '15px'
                    , fontFamily: 'Public Sans'
                    , fontWeight: 400
                    , labels: {
                        colors: headingColor
                        , useSeriesColors: false
                    }
                    , offsetY: 10
                }
                , grid: {
                    strokeDashArray: 8
                    , borderColor
                }
                , colors: [chartColors.line.series1, chartColors.line.series2]
                , fill: {
                    opacity: [1, 1]
                }
                , plotOptions: {
                    bar: {
                        columnWidth: '30%'
                        , startingShape: 'rounded'
                        , endingShape: 'rounded'
                        , borderRadius: 4
                    }
                }
                , dataLabels: {
                    enabled: false
                }
                , xaxis: {
                    tickAmount: 10
                    , categories: ['1 Jan', '2 Jan', '3 Jan', '4 Jan', '5 Jan', '6 Jan', '7 Jan', '8 Jan', '9 Jan', '10 Jan']
                    , labels: {
                        style: {
                            colors: labelColor
                            , fontSize: '13px'
                            , fontWeight: 400
                        }
                    }
                    , axisBorder: {
                        show: false
                    }
                    , axisTicks: {
                        show: false
                    }
                }
                , yaxis: {
                    tickAmount: 4
                    , min: 0
                    , max: 100000
                    , labels: {
                        style: {
                            colors: labelColor
                            , fontSize: '13px'
                            , fontFamily: 'Public Sans'
                            , fontWeight: 400
                        }
                        , formatter: function(val) {
                            return val + 'THB';
                        }
                    }
                }
                , responsive: [{
                        breakpoint: 1400
                        , options: {
                            chart: {
                                height: 320
                            }
                            , xaxis: {
                                labels: {
                                    style: {
                                        fontSize: '10px'
                                    }
                                }
                            }
                            , legend: {
                                itemMargin: {
                                    vertical: 0
                                    , horizontal: 10
                                }
                                , fontSize: '13px'
                                , offsetY: 12
                            }
                        }
                    }
                    , {
                        breakpoint: 1025
                        , options: {
                            chart: {
                                height: 415
                            }
                            , plotOptions: {
                                bar: {
                                    columnWidth: '50%'
                                }
                            }
                        }
                    }
                    , {
                        breakpoint: 982
                        , options: {
                            plotOptions: {
                                bar: {
                                    columnWidth: '30%'
                                }
                            }
                        }
                    }
                    , {
                        breakpoint: 480
                        , options: {
                            chart: {
                                height: 250
                            }
                            , legend: {
                                offsetY: 7
                            }
                        }
                    }
                ]
            };
        if (typeof shipmentEl !== undefined && shipmentEl !== null) {
            const shipment = new ApexCharts(shipmentEl, shipmentConfig);
            shipment.render();
        }
    });

</script>
@stop
