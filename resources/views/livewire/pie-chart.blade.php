<div>
    <div id="pieChart"></div>

    <script>
        document.addEventListener('livewire:load', function () {
            var ctx = document.getElementById('pieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        data: @json($data),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#F44336'],
                    }]
                },
            });
        });
    </script>
</div>
