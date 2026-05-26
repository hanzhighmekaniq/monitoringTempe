<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>

  <body>
    <div class="container py-5">
      <h1 class="text-center mb-3">Monitoring Tempe</h1>
      <div class="row justify-content-center">
        <div class="card col-3">
            <div class="card-body">
                <h5>Temperature</h5>
                <p><span id="temperature"></span> *C</p>
            </div>
        </div>
        <div class="card col-3">
            <div class="card-body">
                <h5>Humidity</h5>
                <p><span id="humidity"></span> %</p>
            </div>
        </div>
      </div>

      <div class="row justify-content-center mt-4">
        <div class="card col-6">
            <div class="card-body">
              <h5 class="card-title">Data Logs</h5>
              <p class="card-text" id="data-logs"></p>
            </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            function getData() {
                $.ajax({
                    type: "GET",
                    url: "/get-data",
                    success: function (response) {
                        let temperature = response.temperature;
                        let humidity = response.humidity;
                        $('#temperature').text(temperature);
                        $('#humidity').text(humidity);
                    }
                });
            }
            
            setInterval(() => {
                getData();
            }, 2000);
        });
    </script>

  </body>

</html>