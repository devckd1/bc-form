<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail Pemeriksaan Suhu Ruang</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search')) : base_url('suhu'); ?>">
                    <i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Suhu Ruang
                </a>
            </li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" cellspacing="0">
                    <thead class="text-center">
                        <?php 
                        $datetime = (new DateTime($suhu->date))->format('d-m-Y');
                        ?>
                        <tr>
                            <th colspan="7" class="text-center font-weight-bold">PEMERIKSAAN SUHU RUANG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><b>Tanggal:</b> <?= $datetime; ?></td>
                            <td><b>Shift:</b> <?= $suhu->shift; ?></td>
                            <td colspan="4"><b>Pukul:</b> <?= date('H:i', strtotime($suhu->pukul)); ?></td>
                        </tr>
                        <tr class="bg-light">
                            <td colspan="7" class="font-weight-bold text-center">Hasil Pemeriksaan</td>
                        </tr>
                        <tr class="table-primary text-center">
                            <th>Lokasi</th>
                            <th colspan="3">Suhu (°C)</th>
                            <th colspan="3">RH (%)</th>
                        </tr>

                        <?php $lokasi_data = json_decode($suhu->lokasi, true); ?>
                        <?php foreach ($lokasi_data as $row): ?>
                            <tr>
                                <td><?= $row['nama_lokasi'] ?></td>
                                <td colspan="3"><?= $row['suhu'] ?></td>
                                <td colspan="3"><?= !empty($row['rh']) ? $row['rh'] : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <tr class="bg-light">
                            <td><b>Catatan</b></td>
                            <td colspan="6"><?= !empty($suhu->catatan) ? $suhu->catatan : 'Tidak ada'; ?></td>
                        </tr>

                        <tr class="table-primary text-center">
                            <th colspan="7">VERIFIKASI</th>
                        </tr>
                        <tr>
                            <td>QC</td>
                            <td colspan="6"><?= $suhu->username; ?></td>
                        </tr>
                        <tr>
                            <td>Produksi</td>
                            <td colspan="6"><?= !empty($suhu->nama_produksi) ? $suhu->nama_produksi : 'Belum dikoreksi'; ?></td>
                        </tr>
                        <tr>
                            <td>Status Supervisor</td>
                            <td colspan="6">
                                <?php
                                switch ($suhu->status_spv) {
                                    case 1:
                                        echo '<span class="text-success font-weight-bold">Verified</span>';
                                        break;
                                    case 2:
                                        echo '<span class="text-danger font-weight-bold">Revision</span>';
                                        break;
                                    default:
                                        echo '<span class="text-secondary font-weight-bold">Created</span>';
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Catatan Supervisor</td>
                            <td colspan="6"><?= !empty($suhu->catatan_spv) ? $suhu->catatan_spv : 'Tidak ada'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    .breadcrumb {
        background-color: #2E86C1;
        padding: 8px 16px;
        border-radius: 0.25rem;
    }

    .breadcrumb .breadcrumb-item a {
        color: #fff;
        font-weight: 500;
    }

    .breadcrumb .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .table {
        width: 100%;
        font-size: 15px;
    }

    .table td, .table th {
        padding: 10px 12px;
        vertical-align: middle;
        word-break: break-word;
    }

    @media (max-width: 768px) {
        .table td, .table th {
            font-size: 14px;
            padding: 8px;
        }

        h1.h3 {
            font-size: 20px;
        }
    }
</style>
