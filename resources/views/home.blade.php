@extends('layouts.app')

@section('title', 'Dashboard')

<style>

    h4{
        padding-top: 10px;
        padding-bottom: 10px;

    }

    .graphBox {
        display: grid;
        grid-template-columns: 1fr 2fr; /* Three columns of equal width */
        gap: 30px; /* Space between the graphs */
        padding-left: 0;
        padding-right: 5%;
        padding-bottom: 4vh;
        height: 370px;
    }

    .graph {
        background: #FFFFFF;
        padding: 20px;
        box-shadow: 0 7px 25px rgba(0,0,0,0.08);
        border-radius: 20px;
        height: 100%; /* Ensures that all graph containers have the same height */
    }

</style>
@section('small', 'ECOMAP SELANGOR')
@section('caption', 'Dashboard')
@section('content')
    <div class="graphBox">
        <div class="graph">
            <div id="pie" style="height: 100%;"></div> <!-- Full height for the chart container -->
        </div>
        <div class="graph">
            <div id="bar" style="height: 100%;"></div> <!-- Unique ID for the first bar chart -->
        </div>
    </div>
    <div class="graphBox" style="grid-template-columns: 1fr 1fr !important;">
        <div class="graph">
            <div id="line" style="height: 100%;"></div>
        </div>
        <div class="graph">
            <div id="stacked_bar" style="height: 100%;"></div> <!-- Unique ID for the first bar chart -->
        </div>
    </div>



    <!-- Planting Method -->
    <script>
        console.log(@json($plants))
        document.addEventListener('DOMContentLoaded', function () {

            Highcharts.chart('pie', {
                colors: ['#b1dd9e', '#f6fa4b', '#FAA74B'],
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Planting Method'
                },
                subtitle: {
                    text: 'Types of planting method used'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false,
                        },
                        showInLegend: true,
                    }
                },
                series: [{
                    name: 'Percentage',
                    colorByPoint: true,
                    data: @json($plants)

                }]
            });
        });
    </script>

    <!-- Total Expenses by month -->
    <script>
        Highcharts.chart('line', {
            chart: {
                type: 'spline',
                zoomType: 'xy'
            },
            title: {
                text: 'Monthly Expenses'
            },
            subtitle: {
                text: 'Insights in monthly spending'
            },
            xAxis: {
                categories: @json($expenses->pluck('name')),
                title: {
                    text: 'Month'
                }
            },
            yAxis: {
                labels: {
                    format: 'RM {value} '
                },
                title: {
                    text: 'Total Expenses (RM)'
                }
            },
            tooltip: {
                valueDecimals: 2, // Ensure tooltip values are to two decimal places
                valuePrefix: 'RM',
                valueSuffix: ' '
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: true
                    }
                },
            },
            series: [{
                name: 'Total Expenses',
                data: @json($expenses->pluck('y')),
                tooltip: {
                    valuePrefix: ' RM'
                },
                color:'#FAA74B'

            }]
        });
    </script>

    <!-- Top 10 high expenses -->
    <script>
        console.log(@json($expensesFlats->pluck('flat_name')));
        console.log(@json($expensesFlats->pluck('total_expenses')));
        Highcharts.chart('bar', {
            chart: {
                type: 'column',
                zooming: {
                    type: 'y'
                }
            },
            title: {
                text: 'Top 10 flats with highest expenses'
            },
            subtitle: {
                text: 'An overview of which flats are spending the most.'
            },
            xAxis: {
                categories: @json($expensesFlats->pluck('flat_name')),  // Use flat names from the database
                title: {
                    text: 'Flats'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Expenses (RM)'
                },
                labels: {
                    overflow: 'justify',
                    format: 'RM {value:.2f}'  // Formats yAxis labels to two decimal places
                }
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: 'RM {y:.2f}'  // Formats the label to two decimal places
                    }
                }
            },
            tooltip: {
                valuePrefix: ' RM',
                valueDecimals: 2,
                stickOnContact: true,
                backgroundColor: 'rgba(255, 255, 255, 0.93)'
            },
            legend: {
                enabled: false
            },
            series: [{
                name: 'Total Expenses by Flat',
                data: @json($expensesFlats->pluck('total_expenses')),  // Use expense data from the database
                borderColor: '#b1dd9e',
                color:'#b1dd9e',
            }]
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stagesByFlats = @json($stagesByFlats); // Make sure this variable correctly receives the data from PHP.

            const categories = [...new Set(stagesByFlats.map(item => item.plant_name))]; // Unique plant names.
            const stages = [...new Set(stagesByFlats.map(item => item.stage))]; // All stages.

            // Organize data for Highcharts
            const series = stages.map(stage => ({
                name: stage,
                data: categories.map(plantName => {
                    const entry = stagesByFlats.find(item => item.plant_name === plantName && item.stage === stage);
                    return entry ? entry.flats_count : 0; // Return the count or 0 if no data available.
                })
            }));

            Highcharts.chart('stacked_bar', {
                chart: {
                    type: 'bar'
                },
                colors: ['#b1dd9e', '#f6fa4b', '#FAA74B'], // Custom colors for each stage
                title: {
                    text: 'Plant Progress'
                },
                subtitle: {
                    text: 'Distribution of Flats Across Plant Types in Each Phase'
                },
                xAxis: {
                    categories: categories,
                    title: {
                        text: 'Type of Plants'
                    }
                },
                yAxis: {
                    min: 0,
                    allowDecimals: false,
                    title: {
                        text: 'Number of Flats'
                    }
                },
                tooltip: {
                    formatter: function() {
                        return this.y + ' flats'; // this.y is the value of the data point where the tooltip is shown
                    }
                },
                legend: {
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                series: series
            });
        });



    </script>




@endsection
