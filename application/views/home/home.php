<!DOCTYPE html>
<html>
<head> 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if ($this->session->flashdata('success_msg')): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $this->session->flashdata('success_msg'); ?>',
        showConfirmButton: false,
        timer: 2000
      });
    </script>
  <?php endif; ?>

  <?php if ($this->session->flashdata('error_msg')): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '<?= $this->session->flashdata('error_msg'); ?>'
      });
    </script>
  <?php endif; ?>

</head>

<body>
  <div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <?php
      date_default_timezone_set('Asia/Jakarta');
      $days = array(
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
      );
      $months = array(
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
      );
      $day = date("l"); 
      $date = date("j"); 
      $month = date("F"); 
      $year = date("Y"); 
      $today = $days[$day];
      $now_month = $months[$month];
      ?>
      <h3 class="mb-0">Update Hari Ini <?= $today ?>, <?= $date ?> <?= $now_month ?> <?= $year ?></h3>
    </div>

    <!-- Ringkasan Produksi -->
    <?php if ($this->session->userdata('plant') == '651ac623-5e48-44cc-b2f6-5d622603f53c'): ?>
      <div class="row">
        <div class="col-md-3">
          <div class="card border-left-primary shadow py-2">
            <div class="card-body">
              <div class="card-title"><b>CURRENT PRODUCT</b></div>
              <?php if (!empty($latest_today)): ?>
                <h4 class="text-primary"><?php echo $latest_today['nama_produk']; ?></h4>
              <?php else: ?>
                <p>No Process Today</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-left-success shadow py-2">
            <div class="card-body">
              <div class="card-title"><b>LAST PRODUCT CODE</b></div>
              <?php if (!empty($latest_today)): ?>
                <h4 class="text-success"><?php echo $latest_today['kode_produksi']; ?></h4>
              <?php else: ?>
                <p>No Process Today</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-left-warning shadow py-2">
            <div class="card-body">
              <div class="card-title"><b>TOTAL BATCH TODAY</b></div>
              <?php if (!empty($latest_today)): ?>
                <h4 class="text-warning"><?php echo $count_batch; ?></h4>
              <?php else: ?>
                <p>No Process Today</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-left-danger shadow py-2">
            <div class="card-body">
              <div class="card-title"><b>PRODUCTION TIME PROCESS</b></div>
              <?php if (!empty($latest_today)): ?>
                <h4 class="text-danger">
                  <?php
                  echo date('H:i', strtotime($latest_today['created_at'])) . ' - ' . date('H:i', strtotime($latest_today['modified_at']));
                  ?>
                </h4>
              <?php else: ?>
                <p>No Process Today</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($this->session->userdata('plant') === '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>
      <div class="card shadow mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
          <?php
          setlocale(LC_TIME, 'id_ID.utf8'); 
          ?>
          <h6 class="m-0 font-weight-bold">
            Data Informasi Produksi (<?= strftime('%A, %d %B %Y', strtotime($tanggal_dipilih)) ?>)
          </h6>
          <form method="get" class="form-inline">
            <label for="tanggal" class="mr-2 mb-0 text-white">Pilih Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" value="<?= $tanggal_dipilih ?>" class="form-control mr-2">
            <button type="submit" class="btn btn-light btn-sm">Tampilkan</button>
          </form>
        </div>

        <div class="card-body">
          <div class="row">

            <!-- Kadar Air -->
            <div class="col-md-4 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <h6 class="text-primary font-weight-bold">
                    <i class="fas fa-tint"></i> Kadar Air Produk Jadi
                  </h6>
                  <p class="text-success mb-1">
                    <strong>Tertinggi:</strong> <?= $kadar_air_max ?? 'N/A' ?>%
                    <br><small><?= $kadar_air_max_produk ?? '-' ?> (<?= $kadar_air_max_kode ?? '-' ?>)</small>
                  </p>
                  <p class="text-danger">
                    <strong>Terendah:</strong> <?= $kadar_air_min ?? 'N/A' ?>%
                    <br><small><?= $kadar_air_min_produk ?? '-' ?> (<?= $kadar_air_min_kode ?? '-' ?>)</small>
                  </p>
                </div>
              </div>
            </div>

            <!-- Suhu Produk -->
            <div class="col-md-4 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <h6 class="text-warning font-weight-bold">
                    <i class="fas fa-thermometer-half"></i> Suhu Pusat Produk
                  </h6>
                  <p class="text-success mb-1">
                    <strong>Tertinggi:</strong> <?= $suhu_produk_max ?? 'N/A' ?>°C
                    <br><small><?= $suhu_produk_max_produk ?? '-' ?> (<?= $suhu_produk_max_kode ?? '-' ?>)</small>
                  </p>
                  <p class="text-danger">
                    <strong>Terendah:</strong> <?= $suhu_produk_min ?? 'N/A' ?>°C
                    <br><small><?= $suhu_produk_min_produk ?? '-' ?> (<?= $suhu_produk_min_kode ?? '-' ?>)</small>
                  </p>
                </div>
              </div>
            </div>

            <!-- Bulk Density -->
            <div class="col-md-4 mb-4">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <h6 class="text-danger font-weight-bold">
                    <i class="fas fa-balance-scale"></i> Bulk Density
                  </h6>
                  <p class="text-success mb-1">
                    <strong>Tertinggi:</strong> <?= $bulk_density_max ?? 'N/A' ?>
                    <br><small><?= $bulk_density_max_produk ?? '-' ?> (<?= $bulk_density_max_kode ?? '-' ?>)</small>
                  </p>
                  <p class="text-danger">
                    <strong>Terendah:</strong> <?= $bulk_density_min ?? 'N/A' ?>
                    <br><small><?= $bulk_density_min_produk ?? '-' ?> (<?= $bulk_density_min_kode ?? '-' ?>)</small>
                  </p>
                </div>
              </div>
            </div>

          </div> <!-- End row -->
        </div> <!-- End card-body -->
      </div> <!-- End card -->
    <?php else: ?>
      <!-- <div class="alert alert-info shadow-sm">
        Dashboard khusus kadar air, suhu, dan bulk density hanya tersedia untuk <strong>Plant Salatiga</strong>.
      </div> -->
    <?php endif; ?>

    <div class="card shadow mb-4 mt-4">
      <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">GRAFIK PEMANTAUAN SUHU RUANGAN (<?= date('d M Y', strtotime($tanggal_dipilih)) ?>)</h6>

        <!-- Filter Tanggal -->
        <form method="get" class="form-inline">
          <label for="tanggal" class="mr-2 font-weight-bold text-white">Pilih Tanggal:</label>
          <input type="date" id="tanggal" name="tanggal" class="form-control mr-2"
          value="<?= $tanggal_dipilih ?>">
          <button type="submit" class="btn btn-light btn-sm">Tampilkan</button>
        </form>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <canvas id="chartSuhu" style="max-height: 400px;"></canvas>
        </div>
      </div>
    </div>

    <br>
    <div class="card mb-4">
      <?php
      $bulanIndo = [
        '01' => 'JANUARI', '02' => 'FEBRUARI', '03' => 'MARET', '04' => 'APRIL',
        '05' => 'MEI', '06' => 'JUNI', '07' => 'JULI', '08' => 'AGUSTUS',
        '09' => 'SEPTEMBER', '10' => 'OKTOBER', '11' => 'NOVEMBER', '12' => 'DESEMBER'
      ];
      $bulanSekarang = $bulanIndo[date('m')] . ' ' . date('Y');
      ?>

      <div class="card-header font-weight-bold">TEMUAN KONTAMINASI BENDA ASING – <?= $bulanSekarang; ?></div>
      <div class="card-body">
        <div class="row">
          <!-- Grafik -->
          <div class="col-md-6">
            <canvas id="kontaminasiChart" height="120"></canvas>
          </div>

          <!-- Tabel Temuan -->
          <div class="col-md-6">
            <table class="table table-bordered table-sm">
              <thead class="thead-light">
                <tr>
                  <th>Jenis Kontaminasi</th>
                  <th>Nama Produk</th>
                  <th>Kode Produksi</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($temuan as $row): ?>
                  <tr>
                    <td><?= $row['jenis_kontaminasi']; ?></td>
                    <td><?= $row['nama_produk']; ?></td>
                    <td><?= $row['kode_produksi']; ?></td>
                    <td><?= $row['jumlah_temuan']; ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <br>

<!-- Status Loading -->
<div class="row">
  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header"><b>Pemeriksaan Loading Produk</b></div>
      <div class="card-body">
        <table class="table table-bordered table-sm">
          <thead class="thead-light">
            <tr>
              <th>Tanggal</th>
              <th>Jam Mulai</th>
              <th>Jam Selesai</th>
              <th>Tujuan</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($loading)): ?>
              <?php foreach ($loading as $k): ?>
                <tr>
                  <td><?= date('d-m-Y', strtotime($k['date'])); ?></td>
                  <td><?= $k['start_loading']; ?></td>
                  <td><?= $k['finish_loading']; ?></td>
                  <td><?= $k['tujuan']; ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header"><b>Penerimaan Kemasan dari Supplier</b></div>
      <div class="card-body">
        <table class="table table-bordered table-sm">
          <thead class="thead-light">
            <tr>
              <th>Nama Kemasan</th>
              <th>Kode Produksi</th>
              <th>Pemasok</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($packaging)): ?>
              <?php foreach ($packaging as $k): ?>
                <tr>
                  <td><?= $k['jenis_kemasan']; ?></td>
                  <td><?= $k['kode_produksi']; ?></td>
                  <td><?= $k['pemasok']; ?></td>
                  <td><?= $k['jumlah_datang']; ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header"><b>Pemeriksaan Seasoning dari Pemasok</b></div>
      <div class="card-body">
        <table class="table table-bordered table-sm">
          <thead class="thead-light">
            <tr>
              <th>Nama Bahan</th>
              <th>Kode Produksi</th>
              <th>Pemasok</th>
              <th>Jumlah Barang</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($seasoning as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['jenis_seasoning']) ?></td>
                <td><?= htmlspecialchars($row['kode_produksi']) ?></td>
                <td><?= htmlspecialchars($row['pemasok']) ?></td>
                <td><?= htmlspecialchars($row['jumlah_barang']) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($seasoning)): ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Tidak ada data.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header"><b>Pemeriksaan Chemical dari Supplier</b></div>
      <div class="card-body">
        <table class="table table-bordered table-sm">
          <thead class="thead-light">
            <tr>
              <th>Nama Chemical</th>
              <th>Kode Produksi</th>
              <th>Pemasok</th>
              <th>Jumlah Datang</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($chemical as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['jenis_chemical']) ?></td>
                <td><?= htmlspecialchars($row['kode_produksi']) ?></td>
                <td><?= htmlspecialchars($row['pemasok']) ?></td>
                <td><?= htmlspecialchars($row['jumlah_barang']) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($chemical)): ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Tidak ada data.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
</div>
</div>

<canvas id="chartSuhu" height="100"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const suhuData = <?= json_encode($suhu_data) ?>;

  const groupedData = {};
  const labels = [];

  // Warna acak per lokasi
  function getRandomColor() {
    const r = Math.floor(Math.random() * 200);
    const g = Math.floor(Math.random() * 200);
    const b = Math.floor(Math.random() * 200);
    return `rgb(${r}, ${g}, ${b})`;
  }

  // Proses data suhu
  suhuData.forEach(entry => {
    const jam = entry.pukul?.substring(0, 5);
    if (!jam) return;

    if (!labels.includes(jam)) {
      labels.push(jam);
    }

    if (Array.isArray(entry.lokasi)) {
      entry.lokasi.forEach(item => {
        const nama_lokasi = item.nama_lokasi;
        const suhu = parseFloat(item.suhu);
        const rh = item.rh || '-';

        if (isNaN(suhu)) return;

        if (!groupedData[nama_lokasi]) {
          groupedData[nama_lokasi] = {
            label: nama_lokasi,
            data: [],
            borderColor: getRandomColor(),
            backgroundColor: 'transparent',
            fill: false,
            tension: 0.3
          };
        }

        groupedData[nama_lokasi].data.push({
          x: jam,
          y: suhu,
          info: {
            lokasi: nama_lokasi,
            jam: jam,
            suhu: item.suhu,
            rh: rh
          }
        });
      });
    }
  });

  labels.sort();
  const datasets = Object.values(groupedData);

  const ctx = document.getElementById('chartSuhu').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: datasets
    },
    options: {
      responsive: true,
      interaction: {
        mode: 'nearest',
        intersect: true
      },
      plugins: {
        tooltip: {
          mode: 'nearest',
          intersect: true,
          callbacks: {
            label: function(context) {
              const info = context.raw.info;
              return [
                `Lokasi : ${info.lokasi}`,
                `Jam    : ${info.jam}`,
                `Suhu   : ${info.suhu} °C`,
                `RH     : ${info.rh} %`
              ];
            }
          }
        },
        legend: {
          position: 'bottom'
        },
        title: {
          display: true,
          text: 'Grafik Suhu Tiap Lokasi'
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Jam'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Suhu (°C)'
          },
          suggestedMin: 0
        }
      }
    }
  });
</script>


<script>
  const ctx = document.getElementById('lineChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: [1,2,3,4,5,6,7,8,9,10],
      datasets: [{
        label: 'Produksi',
        data: [4, 5, 6, 6, 7, 7, 8, 9, 10, 11],
        borderColor: '#4e73df',
        borderWidth: 2,
        fill: false
      }]
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

<script>
  const rawData = <?= json_encode($jumlah_temuan) ?>;

  // Buat label tanggal 1 - 31
  const labels = Array.from({ length: 31 }, (_, i) => (i + 1).toString());
  const data = [];
  const detailMap = {};

  // Petakan data ke tanggal
  rawData.forEach(item => {
    const tgl = parseInt(item.tanggal.split('-')[2]);
    data[tgl - 1] = parseInt(item.jumlah_temuan);
    detailMap[tgl] = {
      jumlah: item.jumlah_temuan,
      produk: item.nama_produk,
      kontaminasi: item.jenis_kontaminasi
    };
  });

  // Pastikan semua 31 hari terisi
  for (let i = 0; i < 31; i++) {
    if (!data[i]) data[i] = 0;
    if (!detailMap[i + 1]) {
      detailMap[i + 1] = {
        jumlah: 0,
        produk: '-',
        kontaminasi: '-'
      };
    }
  }

  new Chart(document.getElementById('kontaminasiChart'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Jumlah Temuan',
        data: data,
        fill: true,
        borderColor: '#e74a3b',
        backgroundColor: 'rgba(231, 74, 59, 0.1)',
        tension: 0.3,
        pointRadius: 5,
        pointHoverRadius: 6,
        pointBackgroundColor: '#e74a3b',
        pointBorderColor: '#fff',
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          min: 0,
          max: 10,
          ticks: { stepSize: 1 }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              const day = parseInt(context.label);
              const d = detailMap[day];
              return [
                `Jumlah: ${d.jumlah}`,
                `Produk: ${d.produk}`,
                `Kontaminasi: ${d.kontaminasi}`
              ];
            }
          }
        }
      }
    } 
  });
</script>

<?php if ($show_modal): ?>
<!-- Modal Input Produksi -->
<div class="modal fade" id="produksiModal" tabindex="-1" role="dialog" aria-labelledby="produksiModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('home/set_produksi_data') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Input Data Produksi</h5>
        </div> 
        <div class="modal-body">
          <div class="form-group">
            <label for="tanggal">Tanggal Produksi</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
          </div>
          <div class="form-group">
            <label for="shift">Shift</label>
            <select name="shift" id="shift" class="form-control" required>
              <option value="">-- Pilih Shift --</option>
              <option value="1">Shift 1</option>
              <option value="2">Shift 2</option>
              <option value="3">Shift 3</option>
            </select>
          </div>
          <div class="form-group">
            <label for="nama_produksi">Nama Produksi</label>
            <select name="nama_produksi" id="nama_produksi" class="form-control" required>
              <option value="">-- Pilih Nama Produksi --</option>
              <?php foreach ($pegawai_produksi as $pegawai): ?>
                <option value="<?= $pegawai->nama ?>"><?= $pegawai->nama ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Trigger modal otomatis -->
<script>
  $(document).ready(function() {
    $('#produksiModal').modal('show');
  });
</script>
<?php endif; ?>

</body>
</html>

<style type="text/css">
  table {
    border-collapse: collapse;
    width: 100%;
  }

  .text-xs {
    font-size: 17px;
  }
  p {
    font-size: 17px;
  }
  li {
    font-size: 17px;
  }
  .h3{
    font-size: 23px;
    font-weight: bold;
    font-style: italic;
  }
  .chart2 {
    padding: 5px;
  }
  #chart {
    width: 100%;
  }
  canvas {
    cursor: pointer;
  }

  .table-limited {
    width: 100%;
    max-width: 100%;
    table-layout: fixed;
    overflow-x: auto; 
  }

  .table-limited thead, .table-limited tbody {
    display: block; 
  }

  .table-limited thead {
    overflow-y: auto; 
    width: calc(100% - 1em); 
  }

  .table-limited tbody {
    max-height: 185px; 
    overflow-y: auto; 
  }

  .table-limited th, .table-limited td {
    padding: 2px 10px;
    text-align: left;
  }
  #chartSuhu {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
  }
  .transition {
    transition: transform 0.2s ease-in-out;
  }
  .transition:hover {
    transform: scale(1.02);
  }
</style>