<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Riwayat Monitoring</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    @include('layouts.navbar')

    <section class="container py-5">

        <h1 class="text-center fw-bold mb-5">
            Riwayat Monitoring
        </h1>

        <div class="row justify-content-center mt-5">

            <div class="col-md-10">

                <div class="card monitor-card p-3">

                    <div class="card-body">

                        <h4 class="fw-bold mb-4">
                            📋 Data Logs Rata-Rata Per Jam (36 Jam Terakhir)
                        </h4>

                        <div id="data-logs">
                            <div class="text-center text-muted py-3">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Memproses data riwayat...
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // FUNGSI UTAMA UNTUK MENGELOMPOKKAN DATA MENJADI RATA-RATA PER JAM
        function prosesRiwayatPerJam(rawData) {
            const dataPerJam = {};

            rawData.forEach(item => {
                const date = new Date(item.created_at.replace(" ", "T"));
                
                const tahun = date.getFullYear();
                const bulan = String(date.getMonth() + 1).padStart(2, '0');
                const hari = String(date.getDate()).padStart(2, '0');
                const jam = String(date.getHours()).padStart(2, '0');
                
                // Key penentu kelompok jam
                const formatJamKey = `${tahun}-${bulan}-${hari} ${jam}:00`;

                if (!dataPerJam[formatJamKey]) {
                    dataPerJam[formatJamKey] = {
                        totalTemp: 0,
                        totalHum: 0,
                        jumlahData: 0,
                        labelTampilan: `${hari}/${bulan} - Jam ${jam}:00`
                    };
                }

                const tempValue = parseFloat(item.temperature || 0);
                const humValue = parseFloat(item.humidity || 0);

                dataPerJam[formatJamKey].totalTemp += tempValue;
                dataPerJam[formatJamKey].totalHum += humValue;
                dataPerJam[formatJamKey].jumlahData += 1;
            });

            // Urutkan dari waktu yang paling baru ke waktu yang lama (descending untuk susunan riwayat teks)
            const sortedKeys = Object.keys(dataPerJam).sort().reverse();
            
            // Batasi tampilan maksimal 36 jam terakhir
            const limitedKeys = sortedKeys.slice(0, 36);

            let html = '';

            if (limitedKeys.length === 0) {
                html = '<div class="text-center text-muted py-3">Belum ada data monitoring yang terekam.</div>';
            } else {
                limitedKeys.forEach(key => {
                    const group = dataPerJam[key];
                    const avgTemp = (group.totalTemp / group.jumlahData).toFixed(1);
                    const avgHum = (group.totalHum / group.jumlahData).toFixed(1);

                    html += `
                        <div class="log-item mb-3 p-2 border-bottom d-flex justify-content-between align-items-center">
                            <span class="log-time fw-bold text-primary">
                                📅 ${group.labelTampilan}
                            </span>
                            <span class="log-data">
                                <span class="badge bg-danger">Temp: ${avgTemp} °C</span>
                                <span class="badge bg-info text-dark ms-2">Humidity: ${avgHum} %</span>
                            </span>
                        </div>
                    `;
                });
            }

            document.getElementById('data-logs').innerHTML = html;
        }

        async function fetchLogs() {
            try {
                const response = await fetch('/get-data');
                const result = await response.json();

                // Kirim data ke fungsi pemroses untuk ditampilkan
                prosesRiwayatPerJam(result);
            } catch (error) {
                console.error("Gagal memuat data log:", error);
                document.getElementById('data-logs').innerHTML = 
                    '<div class="text-center text-danger py-3">Gagal memperbarui riwayat data dari server.</div>';
            }
        }

        // Jalankan fungsi saat halaman pertama kali dibuka
        fetchLogs();

        // Singkronisasi realtime otomatis dikendurkan menjadi setiap 1 menit (60000 ms) 
        // agar browser tidak lag akibat memproses kalkulasi matematika terlalu sering.
        setInterval(fetchLogs, 60000);
    </script>

</body>
</html>