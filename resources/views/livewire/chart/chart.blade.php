<div>
    <div class="row">
        @php $index = 0; @endphp
        @foreach (json_decode($pieChartData, true) as $channel => $data)
            <div class="col-md-3 mb-6">
                <div id="donutchart-{{ $index }}"></div>
            </div>
            @php $index++; @endphp
        @endforeach
    </div>
    <div class="row mt-2">
        <div class="col-md-6" id="groupedBarChart"></div>
    </div>

   
</div>

@section('scripts')
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawBarChart();
            drawPieCharts();
        }

        function drawBarChart() {
            let rawData = @json($barChartData);
            let parsedData = JSON.parse(rawData);

            if (!Array.isArray(parsedData)) {
                console.error("Invalid data format for Google Charts:", parsedData);
                return;
            }
            for (let i = 1; i < parsedData.length; i++) {
        parsedData[i][1] = parsedData[i][1] / 1000000; // Detected
        parsedData[i][2] = parsedData[i][2] / 1000000; // Prevented
        parsedData[i][3] = parsedData[i][3] / 1000000; // Recovered
    }
            var data = google.visualization.arrayToDataTable(parsedData);

            var options = {
                title: "Leakages Per Channel",
                chartArea: { width: "80%", height: "80%" },
                hAxis: { 
                    title: "Channel",
                    slantedText: true,
                    slantedTextAngle: 30
                },
                vAxis: { title: "Total Amount (Millions)", minValue: 0 },
                legend: { position: "top", maxLines: 3 },
                isStacked: false,
                colors: ["#FF5733", "#33B5E5", "#66BB6A"]
            };

            var chart = new google.visualization.ColumnChart(document.getElementById("groupedBarChart"));
            chart.draw(data, options);
        }

        function drawPieCharts() {
            var chartData = @json($pieChartData);
            chartData = JSON.parse(chartData);

            Object.keys(chartData).forEach((channel, index) => {
                var data = google.visualization.arrayToDataTable(chartData[channel]);

                var options = {
                    title: channel + ' Leakages',
                    pieHole: 0.3,
                    chartArea: { width: '80%', height: '80%' },
                    // legend: none
                    legend: { position: 'right' }
                };

                var chartDiv = document.getElementById('donutchart-' + index);
                if (chartDiv) {
                    var chart = new google.visualization.PieChart(chartDiv);
                    chart.draw(data, options);
                }
            });
        }

        document.addEventListener("livewire:load", function() {
            Livewire.hook('message.processed', (message, component) => {
                drawCharts();
            });
        });
    </script>
@endsection
