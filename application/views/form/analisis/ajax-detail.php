<?php
$mode = isset($mode) ? $mode : 'qc';
$is_audit = $mode === 'audit';

$datetime = !empty($analisis->date)
    ? (new DateTime($analisis->date))->format('d-m-Y')
    : '-';

$bb = !empty($analisis->best_before)
    ? (new DateTime($analisis->best_before))->format('d-m-Y')
    : '-';

function tampilkan_analisis($kode)
{
    $map = [
        '1' => 'Moisture',
        '2' => 'Salinity',
        '3' => 'pH',
        '4' => 'Bulk Density',
        '5' => 'TPC',
        '6' => 'Enterobacter',
        '7' => 'Salmonella',
        '8' => 'Yeast and Mold',
    ];

    return $map[$kode] ?? $kode;
}
?>

<div class="container-fluid">
    <!-- DETAIL ANALISIS -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3 font-weight-bold text-primary">🧾 Detail Permohonan Analisis</h5>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Tanggal</div>
                <div class="col-md-9"><?= $datetime ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Tipe Sampel</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->tipe_sampel) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Penyimpanan</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->penyimpanan) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Nama Produk</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->nama_produk) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Kode Produksi</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->kode_produksi) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Best Before</div>
                <div class="col-md-9"><?= $bb ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Jumlah Sampel (g)</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->jumlah_sampel) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Permintaan Analisis</div>
                <div class="col-md-9">
                    <?php
                    $nilai = explode(',', $analisis->analisis);
                    $hasil = array_map('tampilkan_analisis', array_map('trim', $nilai));
                    echo htmlspecialchars(implode(', ', $hasil));
                    ?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Catatan</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->catatan ?: 'Tidak ada') ?></div>
            </div>
        </div>
    </div>

    <!-- VERIFIKASI -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3 font-weight-bold text-primary">🔍 Verifikasi & Status</h5>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">QC</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->username) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Produksi</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->nama_produksi) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Lab</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->nama_lab) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status Lab</div>
                <div class="col-md-9">
                    <?php
                    $status = $analisis->status_lab;
                    if ($status == 1) {
                        echo '<span class="badge badge-success">Accepted</span>';
                    } elseif ($status == 2) {
                        echo '<span class="badge badge-danger">Returned</span>';
                    } else {
                        echo '<span class="badge badge-secondary">Created</span>';
                    }
                    ?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status Supervisor</div>
                <div class="col-md-9">
                    <?php
                    $status = $analisis->status_spv;
                    if ($status == 1) {
                        echo '<span class="badge badge-success">Verified</span>';
                    } elseif ($status == 2) {
                        echo '<span class="badge badge-danger">Revision</span>';
                    } else {
                        echo '<span class="badge badge-secondary">Created</span>';
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 font-weight-bold">Catatan Supervisor</div>
                <div class="col-md-9"><?= htmlspecialchars($analisis->catatan_spv ?: 'Tidak ada') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan CSS -->
<style>
    .badge {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 0.35rem;
    }

    .card h5 {
        border-bottom: 2px solid #ddd;
        padding-bottom: 5px;
    }

    @media (max-width: 768px) {
        .row .col-md-3 {
            width: 100%;
            font-weight: bold;
        }
        .row .col-md-9 {
            width: 100%;
        }
    }
</style>
