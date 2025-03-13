<div>
    <div class="row">
        @php $index = 0; @endphp
        @foreach (json_decode($chartData, true) as $channel => $data)
            <div class="col-md-3 mb-6">
                {{-- <h3 class="text-xl font-bold">{{ $channel }}</h3> --}}
                <div id="donutchart-{{ $index }}"></div>
            </div>
            @php $index++; @endphp
        @endforeach
    </div>
  
</div>
@section('scripts')
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            var chartData = @this.chartData;
            chartData = JSON.parse(chartData);

            Object.keys(chartData).forEach((channel, index) => {
                var data = google.visualization.arrayToDataTable(chartData[channel]);

                var options = {
                    title:  channel + ' Leakages',
                    pieHole: 0.3, // Donut effect
                    chartArea: { width: '80%', height: '80%' },
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
