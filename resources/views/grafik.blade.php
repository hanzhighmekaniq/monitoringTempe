<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Grafik Monitoring</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <!-- NAVBAR -->
    @include('layouts.navbar')

    <div class="container py-5">

        <!-- TITLE -->
        <div class="text-center mb-5">
            <h1 class="fw-bold">
                📈 Grafik Monitoring Fermentasi
            </h1>

            <p class="text-muted">
                Monitoring realtime suhu dan kelembapan ruangan fermentasi tempe
            </p>
        </div>

        <!-- GRAFIK -->
        <div class="row g-4">

            <!-- TEMPERATURE -->
            <div class="col-md-6">

                <div class="card monitor-card p-4">

                    <h4 class="fw-bold mb-4">
                        🌡 Grafik Temperature
                    </h4>

                    <canvas id="temperatureChart"></canvas>

                </div>

            </div>

            <!-- HUMIDITY -->
            <div class="col-md-6">

                <div class="card monitor-card p-4">

                    <h4 class="fw-bold mb-4">
                        💧 Grafik Humidity
                    </h4>

                    <canvas id="humidityChart"></canvas>

                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        let temperatureChart;
        let humidityChart;

        async function fetchData() {

            const response =
            await fetch('/get-data');

            const result =
            await response.json();

            const labels = result.map(item => {

                return new Date(item.created_at)
                    .toLocaleTimeString();

            });

            const temperatures = result.map(item => {

                return item.temperature;

            });

            const humidities = result.map(item => {

                return item.humidity;

            });

            updateCharts(
                labels,
                temperatures,
                humidities
            );
        }

        function createCharts() {

            const tempCtx =
            document.getElementById('temperatureChart');

            temperatureChart =
            new Chart(tempCtx, {

                type: 'line',

                data: {

                    labels: [],

                    datasets: [{

                        label: 'Temperature °C',

                        data: [],

                        borderWidth: 3,

                        tension: 0.4

                    }]
                },

                options: {

                    responsive: true

                }
            });

            const humCtx =
            document.getElementById('humidityChart');

            humidityChart =
            new Chart(humCtx, {

                type: 'line',

                data: {

                    labels: [],

                    datasets: [{

                        label: 'Humidity %',

                        data: [],

                        borderWidth: 3,

                        tension: 0.4

                    }]
                },

                options: {

                    responsive: true

                }
            });
        }

        function updateCharts(
            labels,
            temperatures,
            humidities
        ) {

            // UPDATE TEMPERATURE
            temperatureChart.data.labels =
            labels;

            temperatureChart.data.datasets[0].data =
            temperatures;

            temperatureChart.update();

            // UPDATE HUMIDITY
            humidityChart.data.labels =
            labels;

            humidityChart.data.datasets[0].data =
            humidities;

            humidityChart.update();
        }

        // BUAT CHART PERTAMA
        createCharts();

        // LOAD DATA PERTAMA
        fetchData();

        // AUTO REALTIME TIAP 5 DETIK
        setInterval(fetchData, 5000);

    </script>

</body>
</html>