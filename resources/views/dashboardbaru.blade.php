<!DOCTYPE html>
<html>

<head>

    <title>Monitoring Tempe</title>

    <meta http-equiv="refresh" content="5">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        body{
            font-family: Arial;
            text-align:center;
            margin:40px;
        }

        .card{
            width:300px;
            margin:auto;
            padding:20px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.2);
            margin-bottom:30px;
        }

        canvas{
            max-width:900px;
            margin:auto;
        }

    </style>

</head>

<body>

    <div class="card">

        <h1>Monitoring Tempe</h1>

        <h2>
            Suhu:
            {{ $latest->temperature ?? 0 }} °C
        </h2>

        <h2>
            Kelembapan:
            {{ $latest->humidity ?? 0 }} %
        </h2>

    </div>

    <canvas id="myChart"></canvas>

    <script>

        const labels = [

            @foreach($datas as $data)

                "{{ $data->created_at->format('H:i:s') }}",

            @endforeach

        ];

        const temperatureData = [

            @foreach($datas as $data)

                {{ $data->temperature }},

            @endforeach

        ];

        const humidityData = [

            @foreach($datas as $data)

                {{ $data->humidity }},

            @endforeach

        ];

        const ctx =
        document.getElementById('myChart');

        new Chart(ctx, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [

                    {
                        label: 'Suhu °C',
                        data: temperatureData,
                        borderWidth: 2
                    },

                    {
                        label: 'Kelembapan %',
                        data: humidityData,
                        borderWidth: 2
                    }

                ]
            },

            options: {

                responsive: true,

                scales: {

                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    </script>

</body>
</html>