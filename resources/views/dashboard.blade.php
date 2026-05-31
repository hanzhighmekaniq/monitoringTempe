<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>
        Dashboard Monitoring
    </title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link
        href="{{ asset('css/style.css') }}"
        rel="stylesheet">

</head>

<body>

    <!-- NAVBAR -->
    @include('layouts.navbar')

    <!-- CONTENT -->
    <div class="container py-5">

        <h1 class="text-center fw-bold mb-5">
            Dashboard Monitoring
        </h1>

        <!-- MODE CONTROL -->
        {{-- <div class="row justify-content-center mb-4">

            <div class="col-md-4">

                <div class="card monitor-card p-4 text-center">

                    <h4 class="mb-4">
                        ⚙️ Mode Control
                    </h4>

                    <div class="btn-group w-100">

                        <button
                            id="autoMode"
                            class="btn btn-success">

                            Otomatis

                        </button>

                        <button
                            id="manualMode"
                            class="btn btn-outline-secondary">

                            Manual

                        </button>

                    </div>

                </div>

            </div>

        </div> --}}

        <div class="row justify-content-center mb-4">

            <div class="col-md-4">

                <div class="card monitor-card p-4 text-center">

                    <h4 class="mb-4">
                        ⚙️ Mode Control
                    </h4>

                    <div class="btn-group w-100">

                        <form action="/system/auto"
                            method="POST"
                            class="w-50">

                            @csrf

                            <button
                                type="submit"
                                id="autoMode"
                                class="btn w-100
                                {{ $control->system_mode == 'auto'
                                    ? 'btn-success'
                                    : 'btn-outline-success' }}">

                                Otomatis

                            </button>

                        </form>

                        <form action="/system/manual"
                            method="POST"
                            class="w-50">

                            @csrf

                            <button
                                type="submit"
                                id="manualMode"
                                class="btn w-100
                                {{ $control->system_mode == 'manual'
                                    ? 'btn-secondary'
                                    : 'btn-outline-secondary' }}">

                                Manual

                            </button>

                        </form>

                    </div>

                    <div class="mt-3">

                        <strong>
                            Mode Saat Ini :
                        </strong>

                        <span
                            class="badge
                            {{ $control->system_mode == 'auto'
                                ? 'bg-success'
                                : 'bg-secondary' }}">

                            {{ strtoupper($control->system_mode) }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- SENSOR -->
        <div class="row justify-content-center g-4">

            <!-- TEMPERATURE -->
            <div class="col-md-5">

                <div class="card monitor-card text-center p-3">

                    <div class="card-body">

                        <h5 class="mb-3">
                            🌡 Temperature
                        </h5>

                        <div class="monitor-value text-danger">

                            <span id="temperature">
                                {{ $latest->temperature ?? 0 }}
                            </span>

                            °C

                        </div>

                    </div>

                </div>

            </div>

            <!-- HUMIDITY -->
            <div class="col-md-5">

                <div class="card monitor-card text-center p-3">

                    <div class="card-body">

                        <h5 class="mb-3">
                            💧 Humidity
                        </h5>

                        <div class="monitor-value text-primary">

                            <span id="humidity">
                                {{ $latest->humidity ?? 0 }}
                            </span>

                            %

                        </div>

                    </div>

                </div>

            </div>

            <!-- FAN -->
            <div class="col-md-5">

                <div class="card monitor-card text-center p-4">

                    <div class="card-body">

                        <h5 class="mb-3">
                            🌀 Kipas
                        </h5>

                        <div class="monitor-value text-primary mb-4">

                            <span id="fan">
                                Mati
                            </span>

                        </div>

                        <div class="mb-4">

                            PWM :
                            <span id="fanPwm">
                                0
                            </span>

                        </div>

                        <div class="d-flex justify-content-center gap-3">

                            <button
                                id="fanMati"
                                class="btn btn-success control-btn"
                                onclick="setFan('off')">

                                Mati

                            </button>

                            <button
                                id="fanLambat"
                                class="btn btn-warning control-btn"
                                onclick="setFan('slow')">

                                Lambat

                            </button>

                            <button
                                id="fanCepat"
                                class="btn btn-danger control-btn"
                                onclick="setFan('fast')">

                                Cepat

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- HEATER -->
            <div class="col-md-5">

                <div class="card monitor-card text-center p-4">

                    <div class="card-body">

                        <h5 class="mb-3">
                            💡 Heater & Spread Fan
                        </h5>

                        <div class="monitor-value text-primary mb-4">

                            <span id="heaterSpread">
                                Mati
                            </span>

                        </div>

                        <div class="mb-4">

                            PWM :
                            <span id="heaterPwm">
                                0
                            </span>

                        </div>

                        <div class="d-flex justify-content-center gap-3">

                            <button
                                id="heaterMati"
                                class="btn btn-success control-btn"
                                onclick="setHeaterSpread('off')">

                                Mati

                            </button>

                            <button
                                id="heaterLambat"
                                class="btn btn-warning control-btn"
                                onclick="setHeaterSpread('slow')">

                                Lambat

                            </button>

                            <button
                                id="heaterCepat"
                                class="btn btn-danger control-btn"
                                onclick="setHeaterSpread('fast')">

                                Cepat

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TOAST -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">

        <div
            id="liveToast"
            class="toast align-items-center text-bg-success border-0"
            role="alert">

            <div class="d-flex">

                <div
                    class="toast-body"
                    id="toastMessage">

                    Berhasil

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast">

                </button>

            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        // ======================
        // ELEMENT
        // ======================

        const autoBtn = document.getElementById('autoMode');
        const manualBtn = document.getElementById('manualMode');
        const controlButtons = document.querySelectorAll('.control-btn');

        // ======================
        // TOAST
        // ======================

        function showToast(message) {
            document.getElementById('toastMessage').innerText = message;

            const toast = new bootstrap.Toast(
                document.getElementById('liveToast')
            );

            toast.show();
        }

        // ======================
        // HELPER
        // ======================

        function getModeText(mode) {
            switch (mode) {
                case 'slow':
                    return 'Lambat';

                case 'fast':
                    return 'Cepat';

                default:
                    return 'Mati';
            }
        }

        function saveControlMode(mode) {
            localStorage.setItem('controlMode', mode);
        }

        function setManualState(isManual) {

            manualBtn.classList.toggle('btn-success', isManual);
            manualBtn.classList.toggle('btn-outline-secondary', !isManual);

            autoBtn.classList.toggle('btn-success', !isManual);
            autoBtn.classList.toggle('btn-outline-secondary', isManual);

            controlButtons.forEach(button => {
                button.disabled = !isManual;
            });
        }

        function loadControlMode() {
            const mode = localStorage.getItem('controlMode');

            setManualState(mode === 'manual');
        }

        // ======================
        // MODE BUTTON
        // ======================

        autoBtn.addEventListener('click', () => {

            saveControlMode('auto');

            setManualState(false);

            showToast('Mode Otomatis');
        });

        manualBtn.addEventListener('click', () => {

            saveControlMode('manual');

            setManualState(true);

            showToast('Mode Manual');
        });

        // ======================
        // DEVICE CONTROL
        // ======================

        async function setDeviceMode(url, mode, elementId, label) {

            try {

                await fetch(`${url}/${mode}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const text = getModeText(mode);

                document.getElementById(elementId).innerText = text;

                showToast(`${label}: ${text}`);

            } catch (error) {

                console.error(error);

                showToast('Gagal mengubah mode');
            }
        }

        async function loadDeviceMode(url, field, elementId) {

            try {

                const response = await fetch(url);

                const data = await response.json();

                document.getElementById(elementId).innerText =
                    getModeText(data[field]);

            } catch (error) {

                console.error(error);
            }
        }

        async function loadLatestSensor()
        {
            try {

                const response =
                await fetch('/latest-data');

                const data =
                await response.json();

                document.getElementById(
                    'temperature'
                ).innerText =
                data.temperature;

                document.getElementById(
                    'humidity'
                ).innerText =
                data.humidity;

            }
            catch(error)
            {
                console.error(error);
            }
        }

        async function loadPwm()
        {
            try {

                const response =
                await fetch('/pwm');

                const data =
                await response.json();

                document.getElementById(
                    'fanPwm'
                ).innerText =
                data.fan_pwm;

                document.getElementById(
                    'heaterPwm'
                ).innerText =
                data.heater_pwm;

            }
            catch(error)
            {
                console.error(error);
            }
        }

        // ======================
        // FAN
        // ======================

        function setFan(mode) {

            setDeviceMode(
                '/fan',
                mode,
                'fan',
                'Fan'
            );
        }

        // ======================
        // HEATER
        // ======================

        function setHeaterSpread(mode) {

            setDeviceMode(
                '/heater-spread',
                mode,
                'heaterSpread',
                'Heater'
            );
        }

        // ======================
        // INIT
        // ======================

        document.addEventListener('DOMContentLoaded', () => {

            loadControlMode();

            loadDeviceMode(
                '/fan-mode',
                'fan_mode',
                'fan'
            );

            loadDeviceMode(
                '/heater-spread-mode',
                'heater_spread_mode',
                'heaterSpread'
            );

            loadlatestSensor();

            loadPwm();

        });

        setInterval(() => {

            loadLatestSensor();

            loadPwm();

            loadDeviceMode(
                '/fan-mode',
                'fan_mode',
                'fan'
            );

            loadDeviceMode(
                '/heater-spread-mode',
                'heater_spread_mode',
                'heaterSpread'
            );

        }, 5000);

    </script>

</body>
</html>