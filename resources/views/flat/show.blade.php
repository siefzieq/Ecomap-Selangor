@extends('layouts.app')
<style>
    .filter-container {
        display: flex;
        margin-left: 10px;
    }

    .custom-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
        padding-left: 0;
    }

    .custom-table td {
        padding: 8px;
        margin-top: 5px;
        height: 55px;
        background-color: #FFFFFF !important;
    }

    .custom-table th {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        height: 55px;
        background-color: #FFFFFF!important;
        color: #2D6C2B !important;
    }

    th {
        padding-top: 25px !important;
        padding-bottom: 25px !important;
    }

    .graph {
        position: relative;
        background: #FFFFFF;
        padding: 20px 30px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
        border-radius: 20px;
        margin: 0 auto; /* Center within the graphBox */
        grid-template-columns: 1fr 1fr;
    }

    .form-select {
        border-color: #2D6C2B !important;
        color: #2D6C2B !important;
    }

    .search-box {
        position: relative;
        width: auto; /* Adjust based on your layout */
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        pointer-events: none; /* Makes the icon non-interactive */
    }

    #searchBox {
        padding-left: 35px; /* Make room for the icon inside the input box */
    }

    .content-wrapper {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        justify-content: flex-start;
        height: 100vh;
        padding-top: 20px; /* Adjust this value if there's a fixed navbar height */
        padding-left: 0;
        padding-right: 4%;
    }

    .progress-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        padding-left: 0;
        width: 100%;
        margin-right: 15%;
    }

    .progress-chart {
        width: 45%; /* Adjust the width as needed */
        height: 400px; /* Adjust height as needed */
        margin: 0 10px; /* Add some margin between charts */
    }

    .chart-container {
        width: 45%; /* Adjust the width as needed */
        height: 400px; /* Adjust height as needed */
    }
    .location-icon {
        font-size: 24px;
        color: inherit;
        text-decoration: none;
    }
</style>

@section('title', 'Flat Information')
@section('caption', 'Flat Information')
@section('content')

    <!-- Flat Information -->
    <div class="graph" style="width: 96%; margin-right: 10%; display: flex; justify-content: space-between; align-items: flex-start; position: relative;">
        <div>
            <div style="font-size: 18px;"><b>{{$flat->name}}</b></div>
            <div>{{$flat->address}}</div>
            <div>{{$flat->city}}</div>
        </div>
        <a href="{{ route('flat.index') }}" class="location-icon" style="position: absolute; top: 15; right: 20;" title="Go back to map">
            <i class="fi fi-rr-marker"></i>
        </a>
    </div>

    <!-- Flat Graph -->
    <div class="progress-container graph" style="width:96%; display: none;">
        <div id="gauge-chart" class="chart-container"></div>
        <div id="timeline-chart" class="chart-container"></div>
    </div>
    <div class="content-wrapper">
        <div class="graph" style="margin-bottom: 5%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="padding-top: 10px;">
                    <strong style="font-size: 20px; font-weight: 700; ">Progress Tracker</strong><br>
                    <small>Monitoring the growth stages and progress of plants</small>
                </div>
            </div>

            <table class="table custom-table">
                <tr>
                    <th>Plant</th>
                    <th>Method</th>
                    <th>Start Date</th>
                    <th>Expected Date</th>
                    <th>Stage</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($progress as $index => $progressItem)
                    @php
                        $expectedDate = \Carbon\Carbon::parse($progressItem->expected_date);
                        $isDisabled = $expectedDate->isPast() ? 'disabled' : '';

                        // Determine progress status based on conditions
                        if ($expectedDate->isPast()) {
                            $progressStatus = 'Completed'; // Update to 'Completed' if expected date is past
                        } else {
                            $progressStatus = ucfirst(strtolower($progressItem->progress_status ?? 'N/A'));
                        }
                    @endphp
                    <tr>
                        <td>{{ ucfirst(strtolower($progressItem->plantation->plant->name ?? 'N/A' )) }}</td>
                        <td>{{ ucfirst(strtolower($progressItem->plantation->planting_type ?? 'N/A')) }}</td>
                        <td>{{ date('d/m/Y', strtotime($progressItem->start_date ?? 'N/A')) }}</td>
                        <td id="expected_date">{{ date('d/m/Y', strtotime($progressItem->expected_date ?? 'N/A')) }}</td>
                        <td>{{ ucfirst(strtolower($progressItem->stage ?? 'N/A')) }}</td>
                        <td id="progressStatus">{{ $progressStatus }}</td> <!-- Updated dynamically -->
                        <td><button class="btn btn-primary" data-id="{{ $index }}" onclick="viewProgress({{ $index }})" {{ $isDisabled }}>View Progress</button></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/timeline.js"></script>

    <script>

        document.addEventListener("DOMContentLoaded", function () {
            // Data for the charts, this should come from your backend
            const progressData = @json($progress);
            console.log(progressData);

            function calculateProgress(startDate, endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const today = new Date();

                console.log('Start Date:', start);
                console.log('End Date:', end);
                console.log('Today:', today);

                const totalDuration = Math.abs(end - start) / (1000 * 60 * 60 * 24); // Calculate in days
                const elapsedTime = Math.floor(Math.abs(today - start) / (1000 * 60 * 60 * 24)); // Calculate in days and round down to nearest whole day

                console.log('Total Duration (days):', totalDuration);
                console.log('Elapsed Time (days):', elapsedTime);

                if (totalDuration === 0) {
                    return 0;
                }

                const progress = (elapsedTime / totalDuration) * 100;

                return progress > 100 ? 100 : progress;
            }

            function renderIcons() {
                this.series.forEach(series => {
                    if (!series.icon) {
                        series.icon = this.renderer
                            .text(
                                `<i class="fi fi-rr-${series.options.custom.icon}"></i>`,
                                0,
                                0,
                                true
                            )
                            .attr({
                                zIndex: 10
                            })
                            .css({
                                color: series.options.custom.iconColor,
                                fontSize: '1em'
                            })
                            .add(this.series[2].group);
                    }
                    series.icon.attr({
                        x: this.chartWidth / 2 - 10,
                        y: this.plotHeight / 2 -
                            series.points[0].shapeArgs.innerR -
                            (
                                series.points[0].shapeArgs.r -
                                series.points[0].shapeArgs.innerR
                            ) / 2 +
                            5
                    });
                });
            }

            const trackColors = Highcharts.getOptions().colors.map(color =>
                new Highcharts.Color(color).setOpacity(0.3).get()
            );

            function createGaugeChart(seedingProgress, harvestingProgress, completionProgress) {
                Highcharts.chart('gauge-chart', {
                    chart: {
                        type: 'solidgauge',
                        height: '80%', // Adjust height
                        events: {
                            render: renderIcons
                        }
                    },

                    title: {
                        text: 'Plant Growth Progress',
                        style: {
                            fontSize: '20px' // Adjust font size
                        }
                    },

                    tooltip: {
                        borderWidth: 0,
                        backgroundColor: 'none',
                        shadow: false,
                        style: {
                            fontSize: '12px' // Adjust font size
                        },
                        valueSuffix: '%',
                        pointFormat: '{series.name}<br>' +
                            '<span style="font-size: 1.5em; color: {point.color}; ' +
                            'font-weight: bold">{point.y}</span>',
                        positioner: function (labelWidth) {
                            return {
                                x: (this.chart.chartWidth - labelWidth) / 2,
                                y: (this.chart.plotHeight / 2) + 10
                            };
                        }
                    },

                    pane: {
                        startAngle: 0,
                        endAngle: 360,
                        background: [{ // Track for Seeding
                            outerRadius: '112%',
                            innerRadius: '88%',
                            backgroundColor: trackColors[0],
                            borderWidth: 0
                        }, { // Track for Harvesting
                            outerRadius: '87%',
                            innerRadius: '63%',
                            backgroundColor: trackColors[1],
                            borderWidth: 0
                        }, { // Track for Completion
                            outerRadius: '62%',
                            innerRadius: '38%',
                            backgroundColor: trackColors[2],
                            borderWidth: 0
                        }]
                    },

                    yAxis: {
                        min: 0,
                        max: 100,
                        lineWidth: 0,
                        tickPositions: []
                    },

                    plotOptions: {
                        solidgauge: {
                            dataLabels: {
                                enabled: false
                            },
                            linecap: 'round',
                            stickyTracking: false,
                            rounded: true
                        }
                    },

                    series: [{
                        name: 'Seeding',
                        data: [{
                            color: Highcharts.getOptions().colors[0],
                            radius: '112%', // Adjust radius
                            innerRadius: '88%',
                            y: seedingProgress
                        }],
                        custom: {
                            icon: 'leaf',
                            iconColor: '#303030'
                        }
                    }, {
                        name: 'Harvesting',
                        data: [{
                            color: Highcharts.getOptions().colors[1],
                            radius: '87%', // Adjust radius
                            innerRadius: '63%',
                            y: harvestingProgress
                        }],
                        custom: {
                            icon: 'shovel',
                            iconColor: '#ffffff'
                        }
                    }, {
                        name: 'Completion',
                        data: [{
                            color: Highcharts.getOptions().colors[2],
                            radius: '62%', // Adjust radius
                            innerRadius: '38%',
                            y: completionProgress
                        }],
                        custom: {
                            icon: 'check-circle',
                            iconColor: '#303030'
                        }
                    }]
                });
            }

            function createTimelineChart(progressItem, phases) {
                const today = new Date();
                const todayLabel = `${today.getDate()}/${today.getMonth() + 1}/${today.getFullYear()}`;

                Highcharts.chart('timeline-chart', {
                    chart: {
                        type: 'timeline'
                    },
                    title: {
                        text: 'Plant Growth Timeline'
                    },
                    xAxis: {
                        type: 'datetime',
                        tickInterval: 24 * 3600 * 1000, // One day in milliseconds
                        labels: {
                            format: '{value:%e %b}' // Format the labels to show day and month
                        },
                        dateTimeLabelFormats: {
                            day: '%e %b' // Format for the tick interval
                        },
                        min: Date.parse(progressItem.start_date)
                    },
                    yAxis: {
                        gridLineWidth: 1,
                        title: {
                            text: 'Progress'
                        },
                        labels: {
                            formatter: function() {
                                return progressItem.plantation.plant.name;
                            }
                        }
                    },
                    series: [{
                        data: phases.map(phase => ({
                            name: phase.name,
                            x: phase.start,
                            label: phase.status,
                            description: `${phase.name} phase from ${new Date(phase.start).toLocaleDateString()} to ${new Date(phase.end).toLocaleDateString()}`,
                            color: phase.completed ? 'green' : 'blue'
                        }))
                    }, {
                        name: 'Today',
                        data: [{
                            name: todayLabel,
                            x: today.getTime(),
                            coords:40,
                            color: 'red',
                            marker: {
                                symbol: 'circle'
                            }
                        }]
                    }]
                });
            }

            window.viewProgress = function(index) {
                const item = progressData[index];
                console.log(`Progress Item: ${JSON.stringify(item)}`);

                var seedingProgress = item.stage.toLowerCase() === 'seeding' ? calculateProgress(item.start_date, item.expected_date) : 100;
                var harvestingProgress = item.stage.toLowerCase() === 'harvesting' ? calculateProgress(item.start_date, item.expected_date) : 0;
                var completionProgress = item.stage.toLowerCase() === 'completion' ? calculateProgress(item.start_date, item.expected_date) : 0;

                // Ensure Seeding is 100% when in Harvesting stage
                if (item.stage.toLowerCase() === 'harvesting') {
                    seedingProgress = 100;
                }
                if (item.stage.toLowerCase() === 'completion') {
                    harvestingProgress = 100;
                }


                console.log(`Seeding Progress: ${seedingProgress}`);
                console.log(`Harvesting Progress: ${harvestingProgress}`);
                console.log(`Completion Progress: ${completionProgress}`);

                const phases = [
                    {
                        name: 'Seeding',
                        start: Date.parse(item.start_date),
                        end: Date.parse(item.expected_date),
                        status: seedingProgress > 0 ? 'In Progress' : 'Not Started Yet',
                        completed: seedingProgress === 100
                    },
                    {
                        name: 'Harvesting',
                        start: Date.parse(item.expected_date),
                        end: Date.parse(item.expected_date) + 10 * 24 * 3600 * 1000, // example duration
                        status: harvestingProgress > 0 ? 'In Progress' : 'Not Started Yet',
                        completed: harvestingProgress === 100
                    },
                    {
                        name: 'Completion',
                        start: Date.parse(item.expected_date) + 10 * 24 * 3600 * 1000,
                        end: Date.parse(item.expected_date) + 20 * 24 * 3600 * 1000, // example duration
                        status: completionProgress > 0 ? 'In Progress' : 'Not Started Yet',
                        completed: completionProgress === 100
                    }
                ];
                var expectedDate = Date.parse(item.expected_date);
                console.log('expected date: ',expectedDate);

                createGaugeChart(seedingProgress, harvestingProgress, completionProgress);
                createTimelineChart(item, phases);

                document.querySelector('.progress-container').style.display = 'flex';
            }






        });

    </script>


@endsection

