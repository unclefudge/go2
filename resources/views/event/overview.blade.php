@extends('layouts/main')

@section('content')
    @include('event/_header')

    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <style type="text/css">
        .legendColour {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0px 10px 0px 20px;
            padding-left: 4px;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    {{-- Rego Form Info --}}
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-10">
                            <h4>Weekly Totals</h4>
                        </div>
                        <div class="col-2">
                            @if ($event->status)
                                <a href="#" class="pull-right" data-toggle="modal" data-target="#modal_personal">Edit</a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="chart-weekly-totals" style="height: 250px;"></div>
                            <div id="legend2" align="center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    {{-- Rego Form Info --}}
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-10">
                            <h4>Weekly Totals2</h4>
                        </div>
                        <div class="col-2">
                            @if ($event->status)
                                <a href="#" class="pull-right" data-toggle="modal" data-target="#modal_personal">Edit</a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="chart-weekly-totals2" style="height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $event->displayUpdatedBy() !!}
        </div>
    </div>
@stop


@section('vendor-scripts')
    {{--}}
    <script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>--}}
    <script src="//www.amcharts.com/lib/4/core.js"></script>
    <script src="//www.amcharts.com/lib/4/charts.js"></script>
    <script src="//www.amcharts.com/lib/4/maps.js"></script>
@stop

@section('page-styles')
    <link href="//www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css"/>
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/event-shared-functions.js" type="text/javascript"></script>

<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});


    // Fire off an AJAX request to load the data
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "/stats/event/weekly-totals",
        data: {eid: 2, days: 7}
    }).done(function (data) {
        weekly_totals = new Morris.Bar({
            element: 'chart-weekly-totals',
            barGap: 3,    // sets the space between bars in a single bar group. Default 3:
            barSizeRatio: 0.9, // proportion of the width of the entire graph given to bars. Default 0.75
            stacked: true,
            resize: true,
            data: [0, 0, 0],
            xkey: 'y',
            ykeys: ['a', 'b'],
            barColors: ["#5867dd", "#f4516c",],
            labels: ['Student', 'New Student']
        });
        // When the response to the AJAX request comes back render the chart with new data
        weekly_totals.setData(data);

        weekly_totals.options.labels.forEach(function (label, i) {
            //var legendItem = $('<span align="center"></span> ').text(label).css('color', weekly_totals.options.barColors[i])
            console.log(label + ' ' + i + ' c:' + weekly_totals.options.barColors[i]);
            console.log(legendItem);
            var legendLabel = $('<span>'+label+'</span>');
            var legendItem = $('<span class="legendColour"></span>').css('background-color', weekly_totals.options.barColors[i]);

            $('#legend2').append(legendItem);
            $('#legend2').append(legendLabel);
        })
    }).fail(function () {
        // If there is no communication between the server, show an error
        alert("error occured");
    });

    var chart = am4core.create(
            "chart-weekly-totals2",
            am4charts.PieChart
    );
</script>


{{--}}
<script>
    var amChartsChartsDemo = function () {
        var e = function () {
            var e = {
                1995: [{sector: "Agriculture", size: 6.6}, {sector: "Mining and Quarrying", size: .6}, {sector: "Manufacturing", size: 23.2}, {sector: "Electricity and Water", size: 2.2}, {sector: "Construction", size: 4.5}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 14.6
                }, {sector: "Transport and Communication", size: 9.3}, {sector: "Finance, real estate and business services", size: 22.5}],
                1996: [{sector: "Agriculture", size: 6.4}, {sector: "Mining and Quarrying", size: .5}, {sector: "Manufacturing", size: 22.4}, {sector: "Electricity and Water", size: 2}, {sector: "Construction", size: 4.2}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 14.8
                }, {sector: "Transport and Communication", size: 9.7}, {sector: "Finance, real estate and business services", size: 22}],
                1997: [{sector: "Agriculture", size: 6.1}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 20.9}, {sector: "Electricity and Water", size: 1.8}, {sector: "Construction", size: 4.2}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 13.7
                }, {sector: "Transport and Communication", size: 9.4}, {sector: "Finance, real estate and business services", size: 22.1}],
                1998: [{sector: "Agriculture", size: 6.2}, {sector: "Mining and Quarrying", size: .3}, {sector: "Manufacturing", size: 21.4}, {sector: "Electricity and Water", size: 1.9}, {sector: "Construction", size: 4.2}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 14.5
                }, {sector: "Transport and Communication", size: 10.6}, {sector: "Finance, real estate and business services", size: 23}],
                1999: [{sector: "Agriculture", size: 5.7}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 20}, {sector: "Electricity and Water", size: 1.8}, {sector: "Construction", size: 4.4}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 15.2
                }, {sector: "Transport and Communication", size: 10.5}, {sector: "Finance, real estate and business services", size: 24.7}],
                2000: [{sector: "Agriculture", size: 5.1}, {sector: "Mining and Quarrying", size: .3}, {sector: "Manufacturing", size: 20.4}, {sector: "Electricity and Water", size: 1.7}, {sector: "Construction", size: 4}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 16.3
                }, {sector: "Transport and Communication", size: 10.7}, {sector: "Finance, real estate and business services", size: 24.6}],
                2001: [{sector: "Agriculture", size: 5.5}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 20.3}, {sector: "Electricity and Water", size: 1.6}, {sector: "Construction", size: 3.1}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 16.3
                }, {sector: "Transport and Communication", size: 10.7}, {sector: "Finance, real estate and business services", size: 25.8}],
                2002: [{sector: "Agriculture", size: 5.7}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 20.5}, {sector: "Electricity and Water", size: 1.6}, {sector: "Construction", size: 3.6}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 16.1
                }, {sector: "Transport and Communication", size: 10.7}, {sector: "Finance, real estate and business services", size: 26}],
                2003: [{sector: "Agriculture", size: 4.9}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 19.4}, {sector: "Electricity and Water", size: 1.5}, {sector: "Construction", size: 3.3}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 16.2
                }, {sector: "Transport and Communication", size: 11}, {sector: "Finance, real estate and business services", size: 27.5}],
                2004: [{sector: "Agriculture", size: 4.7}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 18.4}, {sector: "Electricity and Water", size: 1.4}, {sector: "Construction", size: 3.3}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 16.9
                }, {sector: "Transport and Communication", size: 10.6}, {sector: "Finance, real estate and business services", size: 28.1}],
                2005: [{sector: "Agriculture", size: 4.3}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 18.1}, {sector: "Electricity and Water", size: 1.4}, {sector: "Construction", size: 3.9}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 15.7
                }, {sector: "Transport and Communication", size: 10.6}, {sector: "Finance, real estate and business services", size: 29.1}],
                2006: [{sector: "Agriculture", size: 4}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 16.5}, {sector: "Electricity and Water", size: 1.3}, {sector: "Construction", size: 3.7}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 14.2
                }, {sector: "Transport and Communication", size: 12.1}, {sector: "Finance, real estate and business services", size: 29.1}],
                2007: [{sector: "Agriculture", size: 4.7}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 16.2}, {sector: "Electricity and Water", size: 1.2}, {sector: "Construction", size: 4.1}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 15.6
                }, {sector: "Transport and Communication", size: 11.2}, {sector: "Finance, real estate and business services", size: 30.4}],
                2008: [{sector: "Agriculture", size: 4.9}, {sector: "Mining and Quarrying", size: .3}, {sector: "Manufacturing", size: 17.2}, {sector: "Electricity and Water", size: 1.4}, {sector: "Construction", size: 5.1}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 15.4
                }, {sector: "Transport and Communication", size: 11.1}, {sector: "Finance, real estate and business services", size: 28.4}],
                2009: [{sector: "Agriculture", size: 4.7}, {sector: "Mining and Quarrying", size: .3}, {sector: "Manufacturing", size: 16.4}, {sector: "Electricity and Water", size: 1.9}, {sector: "Construction", size: 4.9}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 15.5
                }, {sector: "Transport and Communication", size: 10.9}, {sector: "Finance, real estate and business services", size: 27.9}],
                2010: [{sector: "Agriculture", size: 4.2}, {sector: "Mining and Quarrying", size: .3}, {sector: "Manufacturing", size: 16.2}, {sector: "Electricity and Water", size: 2.2}, {sector: "Construction", size: 4.3}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 15.7
                }, {sector: "Transport and Communication", size: 10.2}, {sector: "Finance, real estate and business services", size: 28.8}],
                2011: [{sector: "Agriculture", size: 4.1}, {sector: "Mining and Quarrying", size: .3}, {sector: "Manufacturing", size: 14.9}, {sector: "Electricity and Water", size: 2.3}, {sector: "Construction", size: 5}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 17.3
                }, {sector: "Transport and Communication", size: 10.2}, {sector: "Finance, real estate and business services", size: 27.2}],
                2012: [{sector: "Agriculture", size: 3.8}, {sector: "Mining and Quarrying", size: .3}, {sector: "Manufacturing", size: 14.9}, {sector: "Electricity and Water", size: 2.6}, {sector: "Construction", size: 5.1}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 15.8
                }, {sector: "Transport and Communication", size: 10.7}, {sector: "Finance, real estate and business services", size: 28}],
                2013: [{sector: "Agriculture", size: 3.7}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 14.9}, {sector: "Electricity and Water", size: 2.7}, {sector: "Construction", size: 5.7}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 16.5
                }, {sector: "Transport and Communication", size: 10.5}, {sector: "Finance, real estate and business services", size: 26.6}],
                2014: [{sector: "Agriculture", size: 3.9}, {sector: "Mining and Quarrying", size: .2}, {sector: "Manufacturing", size: 14.5}, {sector: "Electricity and Water", size: 2.7}, {sector: "Construction", size: 5.6}, {
                    sector: "Trade (Wholesale, Retail, Motor)",
                    size: 16.6
                }, {sector: "Transport and Communication", size: 10.5}, {sector: "Finance, real estate and business services", size: 26.5}]
            }, a = 1995;
        };
        return {
            init: function () {
                AmCharts.makeChart("m_amcharts_1", {
                    rtl: mUtil.isRTL(),
                    type: "serial",
                    theme: "light",
                    dataProvider: [{country: "USA", visits: 2025}, {country: "China", visits: 1882}, {country: "Japan", visits: 1809}, {country: "Germany", visits: 1322}, {country: "UK", visits: 1122}, {country: "France", visits: 1114}, {country: "India", visits: 984}, {
                        country: "Spain",
                        visits: 711
                    }, {country: "Netherlands", visits: 665}, {country: "Russia", visits: 580}, {country: "South Korea", visits: 443}, {country: "Canada", visits: 441}, {country: "Brazil", visits: 395}],
                    valueAxes: [{gridColor: "#FFFFFF", gridAlpha: .2, dashLength: 0}],
                    gridAboveGraphs: !0,
                    startDuration: 1,
                    graphs: [{balloonText: "[[category]]: <b>[[value]]</b>", fillAlphas: .8, lineAlpha: .2, type: "column", valueField: "visits"}],
                    chartCursor: {categoryBalloonEnabled: !1, cursorAlpha: 0, zoomable: !1},
                    categoryField: "country",
                    categoryAxis: {gridPosition: "start", gridAlpha: 0, tickPosition: "start", tickLength: 20},
                    export: {enabled: !0}
                }), e()
            }
        }
    }();
    jQuery(document).ready(function () {
        amChartsChartsDemo.init()
    });
</script>
--}}
@stop
