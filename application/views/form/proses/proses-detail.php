<?php
// Label tiap parameter
$label_param = [
  'dokumen' => 'Acuan Dokumen',
  'revisi' => 'Revisi',
  'no_formula' => 'No. Formula',
  'nama_produk' => 'Jenis Produk',
  'kode_produksi' => 'Kode Produksi',
  'tepung_terigu' => 'Tepung Terigu',
  'tepung_tapioka' => 'Tepung Tapioka (2.270 kg)',
  'yeast' => 'Yeast (0.732 kg)',
  'bread_improver' => 'Bread Improver',
  'premix' => 'Premix',
  'shortening' => 'Shortening (0.252 kg)',
  'chill_water' => 'Chill Water (14 - 16°C) (std. 18)',
  'waktu_mixing_1' => 'Waktu Mixing 1',
  'waktu_mixing_2' => 'Waktu Mixing 2',
  'sensori' => 'Sensori',
  'suhu_adonan' => 'Suhu Adonan (29 - 31°C)',
  'berat_adonan' => 'Berat Adonan (630 - 670 g/pcs)',
  'jam_mulai' => 'Jam Mulai',
  'jam_selesai' => 'Jam Selesai',
  'suhu_setting' => 'Suhu Setting (34 - 36°C)',
  'suhu_aktual' => 'Suhu Aktual',
  'rh_setting' => 'RH Setting (78 - 82%)',
  'rh_aktual' => 'RH Aktual',
  'durasi_waktu' => 'Durasi Waktu (60 - 70 menit)',
  'hasil_proofing' => 'Hasil Proofing',
  'baking_time_high' => 'Baking Time High',
  'baking_time_low' => 'Baking Time Low',
  'suhu_produk' => 'Suhu Produk (80 - 97°C)',
  'sensori_produk' => 'Sensori Produk'
];

// Ambil data proses dari controller
$proses_data = $proses->proses_produksi ?? [];
if (is_string($proses_data)) {
  $proses_data = json_decode($proses_data, true);
}
?>

<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search')) : base_url('produksi'); ?>">
          <i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Produksi
        </a>
      </li>
    </ol>
  </nav>

  <div class="card shadow mb-4">
    <div class="card-body">

      <h4 class="text-dark font-weight-bold mb-3">Verifikasi Proses Produksi</h4>

      <?php
      $label_shift = [
        '1' => 'Shift 1',
        '2' => 'Shift 2',
        '3' => 'Shift 3'
      ];
      ?>

      <table class="table table-sm table-bordered w-50">
        <tr>
          <th style="width: 40%;">Hari / Tanggal</th>
          <td>
            <?php
            if (!empty($proses->date) && $proses->date !== '0000-00-00') {
              setlocale(LC_TIME, 'id_ID.utf8');
              $tanggal = strtotime($proses->date);
              echo date('l, d F Y', $tanggal);
            } else {
              echo '-';
            }
            ?>
          </td>
        </tr>
        <tr>
          <th>Shift</th>
          <td><?= $label_shift[$proses->shift] ?? '-' ?></td>
        </tr>
      </table>

      <?php if (empty($proses_data)): ?>
        <div class="alert alert-warning">Data proses produksi tidak tersedia.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th style="width: 20%;">Jenis Parameter</th>
                <th></th> <!-- Empty header column -->
                <?php for ($i = 1; $i <= 10; $i++): ?>
                  <th>Input ke-<?= $i ?></th>
                <?php endfor; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($proses_data as $kategori => $param_list): ?>
                <tr style="background-color: #f8f9fc; font-weight: bold;">
                  <td colspan="12"><?= strtoupper(str_replace('_', ' ', $kategori)) ?></td>
                </tr>
                <?php foreach ($param_list as $param => $values): ?>
                  <tr>
                    <td><?= $label_param[$param] ?? $param ?></td>
                    <td><?= htmlspecialchars($values[0] ?? '') ?></td> <!-- empty / old input ke-1 -->
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                      <td><?= htmlspecialchars($values[$i] ?? '') ?></td>
                    <?php endfor; ?>
                  </tr>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

      <?php
      // Ambil data packing
      $packing_raw = $proses->proses_packing ?? [];

      if (is_string($packing_raw)) {
        $decoded = json_decode($packing_raw, true);
        $packing_raw = is_array($decoded) ? $decoded : [];
      }

      if (!is_array($packing_raw)) {
        $packing_raw = [];
      }

      // Label parameter packing
      $label_param_packing = [
        'jam_mulai' => 'Jam Mulai',
        'jam_selesai' => 'Jam Selesai',
        'lama_aging' => 'Lama Aging (9 - 12 jam)',
        'kadar_air' => 'Kadar Air Produk (32 - 34%)',
        'hasil_grinding' => 'Hasil Grinding',
        'suhu_setting' => 'Suhu Setting (85 - 90°C)',
        'suhu_aktual' => 'Suhu Aktual',
        'dryer_speed' => 'Dryer Speed (4 - 6 rpm)',
        'nama_produk' => 'Nama Produk',
        'kode_produksi' => 'Kode Produksi',
        'best_before' => 'Best Before',
        'suhu_sebelum_packing' => 'Suhu Sebelum Packing (32 - 35°C)',
        'kadar_air_produk' => 'Kadar Air Produk (4 - 8%)',
        'bulk_density' => 'Bulk Density (225 - 325 g/l)',
        'sensori_produk' => 'Sensori Produk',
        'kondisi_kemasan' => 'Kondisi Kemasan',
        'ketepatan_labelisasi' => 'Ketepatan Labelisasi',
        'kode_supplier' => 'Kode Supplier',
        'net_weight' => 'Nett Weight (9,850 - 10,100 g/plastic bag)',
        'bukti_labelisasi' => 'Bukti Labelisasi'
      ];

      $packing_data = !empty($packing_raw) ? reset($packing_raw) : [];
      ?>

      <?php if (!empty($packing_data)): ?>
        <div class="mt-5">
          <h4 class="text-dark font-weight-bold mb-3">Verifikasi Proses Packing</h4>

          <table class="table table-sm table-bordered w-50">
            <tr>
              <th style="width: 40%;">Hari / Tanggal</th>
              <td>
                <?= (!empty($proses->date_stall) && $proses->date_stall !== '0000-00-00') ? date('l, d F Y', strtotime($proses->date_stall)) : '-' ?>
              </td>
            </tr>
            <tr>
              <th>Shift</th>
              <td><?= $label_shift[$proses->shift_pack] ?? '-' ?></td>
            </tr>
          </table>

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="thead-light">
                <tr>
                  <th style="width: 20%;">Jenis Parameter</th>
                  <th>Input</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($packing_data as $kategori => $param_list): ?>
                  <tr style="background-color: #f8f9fc; font-weight: bold;">
                    <td colspan="2"><?= strtoupper(str_replace('_', ' ', $kategori)) ?></td>
                  </tr>
                  <?php foreach ($param_list as $param => $value): ?>
                    <tr>
                      <td><?= $label_param_packing[$param] ?? $param ?></td>
                      <td>
                        <?php
                        if ($param === 'bukti_labelisasi' && !empty($value)) {
                          $file = is_array($value) ? ($value[0] ?? '') : $value;
                          if ($file && file_exists(FCPATH . 'uploads/bukti_labelisasi/' . $file)) {
                            echo '<a href="' . base_url('uploads/bukti_labelisasi/' . $file) . '" target="_blank">Lihat Gambar</a>';
                          } else {
                            echo '-';
                          }
                        } else {
                          echo is_array($value) ? ($value[0] ?? '-') : ($value ?? '-');
                        }
                        ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Verifikasi -->
  <div class="card shadow mb-4">
    <div class="card-header bg-dark text-white">Verifikasi</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <tbody>
            <tr>
              <td><strong>Catatan</strong></td>
              <td><?= $proses->catatan; ?></td>
            </tr>
            <tr>
              <td><strong>QC</strong></td>
              <td><?= $proses->username; ?></td>
            </tr>
            <tr>
              <td><strong>Produksi</strong></td>
              <td><?= $proses->nama_produksi; ?></td>
            </tr>
            <tr>
              <td><strong>Status SPV</strong></td>
              <td><?= $proses->status_spv == 1 ? 'Verified' : ($proses->status_spv == 2 ? 'Revision' : 'Created'); ?></td>
            </tr>
            <tr>
              <td><strong>Catatan SPV</strong></td>
              <td><?= !empty($proses->catatan_spv) ? $proses->catatan_spv : 'Tidak ada'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
  .breadcrumb {
    background-color: #2E86C1;
  }

  .breadcrumb a {
    color: white !important;
    text-decoration: none;
  }

  .table {
    font-size: 14px;
  }

  th {
    background-color: #f8f9fc;
  }

  .table td,
  .table th {
    vertical-align: middle;
  }

  .card-header {
    font-weight: bold;
    font-size: 16px;
  }

  .table th,
  .table td {
    font-size: 13px;
    padding: 4px 8px;
  }

  .card .row>div {
    font-size: 14px;
  }

  .badge-status {
    font-size: 12px;
    padding: 4px 8px;
  }
</style>
