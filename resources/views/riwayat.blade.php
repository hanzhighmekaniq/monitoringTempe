<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Riwayat Monitoring</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body>

    <!-- NAVBAR -->
    @include('layouts.navbar')

    <!-- CONTENT -->
    <section class="container py-5">

        <h1 class="text-center fw-bold mb-5">
            Riwayat Monitoring
        </h1>

        <!-- DATA LOG -->
        <div class="row justify-content-center mt-5">

            <div class="col-md-10">

                <div class="card monitor-card p-3">

                    <div class="card-body">

                        <h4 class="fw-bold mb-4">
                            📋 Data Logs
                        </h4>

                        <div id="data-logs">

                            @foreach($datas as $data)

                                <div class="log-item mb-3">

                                    <span class="log-time fw-bold">

                                        {{ $data->created_at->format('H:i:s') }}

                                    </span>

                                    <span class="log-data">

                                        Temp:
                                        {{ $data->temperature }}°C

                                        |

                                        Humidity:
                                        {{ $data->humidity }}%

                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script> --}}

    <script>

        async function fetchLogs(){

            const response =
            await fetch('/get-data');

            const result =
            await response.json();

            let html = '';

            result.reverse().forEach(item => {

                html += `

                    <div class="log-item mb-3">

                        <span class="fw-bold">

                            ${
                                new Date(item.created_at)
                                .toLocaleTimeString()
                            }

                        </span>

                        <span>

                            Temp:
                            ${item.temperature} °C

                            |

                            Humidity:
                            ${item.humidity} %

                        </span>

                    </div>

                `;
            });

            document.getElementById(
                'data-logs'
            ).innerHTML = html;
        }

        fetchLogs();

        setInterval(fetchLogs, 5000);

    </script>

</body>
</html>