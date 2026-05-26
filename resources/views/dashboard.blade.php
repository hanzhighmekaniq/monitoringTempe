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
        <div class="row justify-content-center mb-4">

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

        const autoBtn =
        document.getElementById(
            'autoMode'
        );

        const manualBtn =
        document.getElementById(
            'manualMode'
        );

        const controlButtons =
        document.querySelectorAll(
            '.control-btn'
        );

        // ======================
        // TOAST
        // ======================

        function showToast(message){

            document.getElementById(
                'toastMessage'
            ).innerText = message;

            const toast =
            new bootstrap.Toast(
                document.getElementById(
                    'liveToast'
                )
            );

            toast.show();
        }

        // ======================
        // SAVE MODE
        // ======================

        function saveControlMode(mode){

            localStorage.setItem(
                'controlMode',
                mode
            );
        }

        // ======================
        // LOAD MODE
        // ======================

        function loadControlMode(){

            const mode =
            localStorage.getItem(
                'controlMode'
            );

            // ======================
            // MANUAL
            // ======================

            if(mode == 'manual'){

                manualBtn.classList.add(
                    'btn-success'
                );

                manualBtn.classList.remove(
                    'btn-outline-secondary'
                );

                autoBtn.classList.add(
                    'btn-outline-secondary'
                );

                autoBtn.classList.remove(
                    'btn-success'
                );

                controlButtons.forEach(
                    button => {

                        button.disabled = false;

                    }
                );
            }

            // ======================
            // AUTO
            // ======================

            else{

                autoBtn.classList.add(
                    'btn-success'
                );

                autoBtn.classList.remove(
                    'btn-outline-secondary'
                );

                manualBtn.classList.add(
                    'btn-outline-secondary'
                );

                manualBtn.classList.remove(
                    'btn-success'
                );

                controlButtons.forEach(
                    button => {

                        button.disabled = true;

                    }
                );
            }
        }

        // ======================
        // AUTO MODE
        // ======================

        autoBtn.addEventListener(
            'click',
            () => {

                saveControlMode('auto');

                loadControlMode();

                showToast(
                    'Mode Otomatis'
                );
            }
        );

        // ======================
        // MANUAL MODE
        // ======================

        manualBtn.addEventListener(
            'click',
            () => {

                saveControlMode('manual');

                loadControlMode();

                showToast(
                    'Mode Manual'
                );
            }
        );

        // ======================
        // FAN
        // ======================

        async function setFan(mode){

            await fetch('/fan/' + mode, {

                method: 'POST',

                headers: {

                    'X-CSRF-TOKEN':
                    '{{ csrf_token() }}'
                }
            });

            let text = 'Mati';

            if(mode == 'slow'){

                text = 'Lambat';
            }

            else if(mode == 'fast'){

                text = 'Cepat';
            }

            document.getElementById(
                'fan'
            ).innerText = text;

            showToast(
                'Fan: ' + text
            );
        }

        // ======================
        // HEATER
        // ======================

        async function setHeaterSpread(mode){

            await fetch(
                '/heater-spread/' + mode,
                {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN':
                        '{{ csrf_token() }}'
                    }
                }
            );

            let text = 'Mati';

            if(mode == 'slow'){

                text = 'Lambat';
            }

            else if(mode == 'fast'){

                text = 'Cepat';
            }

            document.getElementById(
                'heaterSpread'
            ).innerText = text;

            showToast(
                'Heater: ' + text
            );
        }

        // ======================
        // INIT
        // ======================

        document.addEventListener(
            'DOMContentLoaded',
            () => {

                loadControlMode();

                loadFanMode();

                loadHeaterMode();

            }
        );

        async function loadFanMode(){

            const response =
            await fetch('/fan-mode');

            const data =
            await response.json();

            const mode =
            data.fan_mode;

            let text = 'Mati';

            if(mode == 'slow'){

                text = 'Lambat';
            }

            else if(mode == 'fast'){

                text = 'Cepat';
            }

            document.getElementById(
                'fan'
            ).innerText = text;
        }

        async function loadHeaterMode(){

            const response =
            await fetch(
                '/heater-spread-mode'
            );

            const data =
            await response.json();

            const mode =
            data.heater_spread_mode;

            let text = 'Mati';

            if(mode == 'slow'){

                text = 'Lambat';
            }

            else if(mode == 'fast'){

                text = 'Cepat';
            }

            document.getElementById(
                'heaterSpread'
            ).innerText = text;
        }

    </script>

</body>
</html>