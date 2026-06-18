<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Grafik Monitoring</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    @include('layouts.navbar')

    <div class="container py-5">

        <div class="text-center mb-5">
            <h1 class="fw-bold">
                📈 Grafik Monitoring Fermentasi
            </h1>

            <p class="text-muted">
                Monitoring rata-rata suhu dan kelembapan per jam selama siklus fermentasi tempe (Maksimal 36 Jam Terakhir)
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-6">

                <div class="card monitor-card p-4">

                    <h4 class="fw-bold mb-4">
                        🌡 Grafik Temperature
                    </h4>

                    <canvas id="temperatureChart"></canvas>

                </div>

            </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        let temperatureChart;
        let humidityChart;

        // FUNGSI UNTUK MENGELOMPOKKAN DAN MERATA-RATA DATA TIAP 1 JAM
        function prosesDataPerJam(rawData) {
            const dataPerJam = {};

            rawData.forEach(item => {
                // const date = new Date(item.created_at);

                const date = new Date(item.created_at.replace(" ", "T"));

                // Membuat key unik berdasarkan "Tahun-Bulan-Hari Jam:00"
                const tahun = date.getFullYear();
                const bulan = String(date.getMonth() + 1).padStart(2, '0');
                const hari = String(date.getDate()).padStart(2, '0');
                const jam = String(date.getHours()).padStart(2, '0');
                const formatJamKey = `${tahun}-${bulan}-${hari} ${jam}:00`;

                if (!dataPerJam[formatJamKey]) {
                    dataPerJam[formatJamKey] = {
                        totalTemp: 0,
                        totalHum: 0,
                        jumlahData: 0,
                        labelWaktu: `${hari}/${bulan} ${jam}:00` // Tampilan label di grafik (Contoh: 18/06 21:00)
                    };
                }

                // Ambil field penamaan dari JSON data kamu (menyesuaikan database property)
                const tempValue = parseFloat(item.temperature || item.suhu || 0);
                const humValue = parseFloat(item.humidity || item.kelembapan || 0);

                dataPerJam[formatJamKey].totalTemp += tempValue;
                dataPerJam[formatJamKey].totalHum += humValue;
                dataPerJam[formatJamKey].jumlahData += 1;
            });

            // Urutkan waktu dari yang terlama ke terbaru
            const sortedKeys = Object.keys(dataPerJam).sort();
            
            // Batasi hanya mengambil maksimal 36 titik jam terakhir
            const limitedKeys = sortedKeys.slice(-36);

            const labels = [];
            const temperatures = [];
            const humidities = [];

            limitedKeys.forEach(key => {
                const group = dataPerJam[key];
                const avgTemp = group.totalTemp / group.jumlahData;
                const avgHum = group.totalHum / group.jumlahData;

                labels.push(group.labelWaktu);
                temperatures.push(avgTemp.toFixed(1)); // Dibulatkan 1 angka di belakang koma
                humidities.push(avgHum.toFixed(1));
            });

            return { labels, temperatures, humidities };
        }

        async function fetchData() {

            const response = await fetch('/get-data');
            const result = await response.json();

            // Memproses data mentah 30-detikan menjadi ringkasan per 1 jam
            const dataTerkompresi = prosesDataPerJam(result);

            updateCharts(
                dataTerkompresi.labels,
                dataTerkompresi.temperatures,
                dataTerkompresi.humidities
            );
        }

        function createCharts() {

            const tempCtx = document.getElementById('temperatureChart');

            temperatureChart = new Chart(tempCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Temperature °C',
                        data: [],
                        borderWidth: 3,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { suggestedMin: 20, suggestedMax: 50 }
                    }
                }
            });

            const humCtx = document.getElementById('humidityChart');

            humidityChart = new Chart(humCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Humidity %',
                        data: [],
                        borderWidth: 3,
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { suggestedMin: 10, suggestedMax: 100 }
                    }
                }
            });
        }

        function updateCharts(labels, temperatures, humidities) {

            // UPDATE TEMPERATURE
            temperatureChart.data.labels = labels;
            temperatureChart.data.datasets[0].data = temperatures;
            temperatureChart.update();

            // UPDATE HUMIDITY
            humidityChart.data.labels = labels;
            humidityChart.data.datasets[0].data = humidities;
            humidityChart.update();
        }

        // BUAT CHART PERTAMA
        createCharts();

        // LOAD DATA PERTAMA
        fetchData();

        // AUTO REALTIME TIAP 1 MENIT (60000 ms) UNTUK CEK REFRESH DATA BARU
        setInterval(fetchData, 60000);

    </script>

</body>
</html>