<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Monitoring & Riwayat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    @include('layouts.navbar')

    <div class="container py-5">

        <h1 class="text-center fw-bold mb-5">
            Dashboard Monitoring & Analytics
        </h1>

        <div class="row g-4 justify-content-center">
            
            <div class="col-lg-6 d-flex flex-column gap-4">
                
                <div class="card monitor-card p-4 text-center">
                    <h4 class="mb-4">⚙️ Mode Control</h4>
                    <div class="btn-group w-100">
                        <form action="/system/auto" method="POST" class="w-50">
                            @csrf
                            <button type="submit" id="autoMode" class="btn w-100 {{ $control->system_mode == 'auto' ? 'btn-success' : 'btn-outline-success' }}">
                                Otomatis
                            </button>
                        </form>

                        <form action="/system/manual" method="POST" class="w-50">
                            @csrf
                            <button type="submit" id="manualMode" class="btn w-100 {{ $control->system_mode == 'manual' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                Manual
                            </button>
                        </form>
                    </div>

                    <div class="mt-3">
                        <strong>Mode Saat Ini :</strong>
                        <span class="badge {{ $control->system_mode == 'auto' ? 'bg-success' : 'bg-secondary' }}">
                            {{ strtoupper($control->system_mode) }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card monitor-card text-center p-3">
                            <div class="card-body">
                                <h5 class="mb-3">🌡 Temperature</h5>
                                <div class="monitor-value text-danger fs-2 fw-bold">
                                    <span id="temperature">{{ $latest->temperature ?? 0 }}</span> °C
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card monitor-card text-center p-3">
                            <div class="card-body">
                                <h5 class="mb-3">💧 Humidity</h5>
                                <div class="monitor-value text-primary fs-2 fw-bold">
                                    <span id="humidity">{{ $latest->humidity ?? 0 }}</span> %
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card monitor-card text-center p-3">
                            <div class="card-body">
                                <h5 class="mb-2">🌀 Kipas</h5>
                                <div class="monitor-value text-primary fw-bold mb-5">
                                    Status: <span id="fan">Mati</span>
                                </div>
                                <div class="mb-3 text-muted small">
                                    PWM: <span id="fanPwm">0</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <button id="fanMati" class="btn btn-sm btn-success control-btn" onclick="setFan('off')">Mati</button>
                                    <button id="fanLambat" class="btn btn-sm btn-warning control-btn" onclick="setFan('slow')">Lambat</button>
                                    <button id="fanCepat" class="btn btn-sm btn-danger control-btn" onclick="setFan('fast')">Cepat</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card monitor-card text-center p-3">
                            <div class="card-body">
                                <h5 class="mb-2">💡 Heater & Spread</h5>
                                <div class="monitor-value text-primary fw-bold mb-5">
                                    Status: <span id="heaterSpread">Mati</span>
                                </div>
                                <div class="mb-3 text-muted small">
                                    PWM: <span id="heaterPwm">0</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <button id="heaterMati" class="btn btn-sm btn-success control-btn" onclick="setHeaterSpread('off')">Mati</button>
                                    <button id="heaterLambat" class="btn btn-sm btn-warning control-btn" onclick="setHeaterSpread('slow')">Lambat</button>
                                    <button id="heaterCepat" class="btn btn-sm btn-danger control-btn" onclick="setHeaterSpread('fast')">Cepat</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6 d-flex flex-column gap-4">
                
                <div class="card monitor-card p-4">
                    <h4 class="mb-4">📊 Grafik Rata-Rata Per Jam (36 Jam Terakhir)</h4>
                    <div style="position: relative; height:250px; width:100%">
                        <canvas id="sensorChart"></canvas>
                    </div>
                </div>

                <div class="card monitor-card p-4">
                    <h4 class="mb-3">📋 Data Logs Rata-Rata Per Jam (36 Jam Terakhir)</h4>
                    <div id="data-logs" style="max-height: 290px; overflow-y: auto;">
                        <div class="text-center text-muted py-3">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Memproses data riwayat...
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">Berhasil</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variables for elements and chart object
        const autoBtn = document.getElementById('autoMode');
        const manualBtn = document.getElementById('manualMode');
        const controlButtons = document.querySelectorAll('.control-btn');
        let sensorChart;

        // ======================
        // TOAST NOTIFICATION
        // ======================
        function showToast(message) {
            document.getElementById('toastMessage').innerText = message;
            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            toast.show();
        }

        // ======================
        // HELPERS
        // ======================
        function getModeText(mode) {
            switch (mode) {
                case 'slow': return 'Lambat';
                case 'fast': return 'Cepat';
                default: return 'Mati';
            }
        }

        function setManualState(isManual) {
            controlButtons.forEach(button => {
                button.disabled = !isManual;
            });
        }

        // ======================
        // DEVICE INTERACTION INTERFACE
        // ======================
        async function setDeviceMode(url, mode, elementId, label) {
            try {
                await fetch(`${url}/${mode}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const text = getModeText(mode);
                document.getElementById(elementId).innerText = text;
                showToast(`${label}: ${text}`);
            } catch (error) {
                console.error(error);
                showToast('Gagal mengubah mode');
            }
        }

        function setFan(mode) { setDeviceMode('/fan', mode, 'fan', 'Fan'); }
        function setHeaterSpread(mode) { setDeviceMode('/heater-spread', mode, 'heaterSpread', 'Heater'); }

        // ======================
        // REALTIME POLLING DATA ENGINE (Every 5 seconds)
        // ======================
        async function loadLatestSensor() {
            try {
                const response = await fetch('/latest-data');
                const data = await response.json();
                document.getElementById('temperature').innerText = data.temperature;
                document.getElementById('humidity').innerText = data.humidity;
            } catch(error) { console.error(error); }
        }

        async function loadPwm() {
            try {
                const response = await fetch('/pwm');
                const data = await response.json();
                document.getElementById('fanPwm').innerText = data.fan_pwm ?? 0;
                document.getElementById('heaterPwm').innerText = data.heater_pwm ?? 0;
            } catch(error) { console.error(error); }
        }

        async function loadDeviceStates() {
            try {
                const sysRes = await fetch('/system-mode');
                const sysData = await sysRes.json();
                setManualState(sysData.system_mode === 'manual');

                const fanRes = await fetch('/fan-mode');
                const fanData = await fanRes.json();
                document.getElementById('fan').innerText = getModeText(fanData.fan_mode);

                const heatRes = await fetch('/heater-spread-mode');
                const heatData = await heatRes.json();
                document.getElementById('heaterSpread').innerText = getModeText(heatData.heater_spread_mode);
            } catch(error) { console.error(error); }
        }

        // ======================
        // MATH COMPRESSION & ANALYTICS (Every 1 minute)
        // ======================
        async function processHistoricalLogsAndCharts() {
            try {
                const response = await fetch('/get-data');
                const rawData = await response.json();
                
                const dataPerJam = {};

                rawData.forEach(item => {
                    const date = new Date(item.created_at.replace(" ", "T"));
                    const formatJamKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} ${String(date.getHours()).padStart(2, '0')}:00`;

                    if (!dataPerJam[formatJamKey]) {
                        dataPerJam[formatJamKey] = {
                            totalTemp: 0, totalHum: 0, jumlahData: 0,
                            labelTampilan: `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')} - ${String(date.getHours()).padStart(2, '0')}:00`
                        };
                    }

                    dataPerJam[formatJamKey].totalTemp += parseFloat(item.temperature || 0);
                    dataPerJam[formatJamKey].totalHum += parseFloat(item.humidity || 0);
                    dataPerJam[formatJamKey].jumlahData += 1;
                });

                const sortedKeys = Object.keys(dataPerJam).sort();
                const limitedKeysForChart = sortedKeys.slice(-36);
                const reversedKeysForLogs = [...sortedKeys].reverse().slice(0, 36);

                // 1. Render Table History List
                let htmlLogs = '';
                if (reversedKeysForLogs.length === 0) {
                    htmlLogs = '<div class="text-center text-muted py-3">Belum ada data monitoring.</div>';
                } else {
                    reversedKeysForLogs.forEach(key => {
                        const group = dataPerJam[key];
                        htmlLogs += `
                            <div class="p-2 border-bottom d-flex justify-content-between align-items-center small">
                                <span class="fw-bold text-primary">📅 ${group.labelTampilan}</span>
                                <span>
                                    <span class="badge bg-danger">T: ${(group.totalTemp / group.jumlahData).toFixed(1)} °C</span>
                                    <span class="badge bg-info text-dark ms-1">H: ${(group.totalHum / group.jumlahData).toFixed(1)} %</span>
                                </span>
                            </div>`;
                    });
                }
                document.getElementById('data-logs').innerHTML = htmlLogs;

                // 2. Render Chart Update
                const labels = [];
                const tempDataset = [];
                const humDataset = [];

                limitedKeysForChart.forEach(key => {
                    const group = dataPerJam[key];
                    labels.push(group.labelTampilan.split(' - ')[1]); // Ambil jam-nya saja untuk label chart
                    tempDataset.push((group.totalTemp / group.jumlahData).toFixed(1));
                    humDataset.push((group.totalHum / group.jumlahData).toFixed(1));
                });

                sensorChart.data.labels = labels;
                sensorChart.data.datasets[0].data = tempDataset;
                sensorChart.data.datasets[1].data = humDataset;
                sensorChart.update();

            } catch (error) {
                console.error("Gagal memproses data visualisasi:", error);
                document.getElementById('data-logs').innerHTML = '<div class="text-center text-danger py-3">Gagal memuat log data dari server.</div>';
            }
        }

        // ======================
        // INITIALIZER ENGINE
        // ======================
        function buildChartEngine() {
            const ctx = document.getElementById('sensorChart').getContext('2d');
            sensorChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        { label: 'Suhu (°C)', data: [], borderColor: '#dc3545', tension: 0.2, yAxisID: 'y' },
                        { label: 'Kelembapan (%)', data: [], borderColor: '#0d6efd', tension: 0.2, yAxisID: 'y1' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { type: 'linear', position: 'left', title: { display: true, text: 'Suhu (°C)' } },
                        y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Kelembapan (%)' } }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            buildChartEngine();
            
            // First Execution Call
            loadLatestSensor();
            loadPwm();
            loadDeviceStates();
            processHistoricalLogsAndCharts();

            // Setup Intervals Loops
            setInterval(() => {
                loadLatestSensor();
                loadPwm();
                loadDeviceStates();
            }, 5000);

            setInterval(processHistoricalLogsAndCharts, 60000);
        });
    </script>
</body>
</html>