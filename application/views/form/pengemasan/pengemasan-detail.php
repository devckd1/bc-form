<div class="container-fluid">
	<?php if (empty($is_popup)): ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Pemeriksaan Proses Pengemasan</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search')) : base_url('pengemasan'); ?>">
                    <i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Proses Pengemasan
                </a>
            </li>
        </ol>
    </nav>
<?php endif; ?>
    <!-- Detail Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" cellspacing="0">
                    <thead class="text-center">
                        <?php 
                        $datetime = (new DateTime($pengemasan->date))->format('d-m-Y');
                        ?>
                        <tr>
                            <th colspan="7" class="font-weight-bold">DATA PEMERIKSAAN PROSES PENGEMASAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Tanggal</b>: <?= $datetime; ?></td>
                            <td><b>Shift</b>: <?= $pengemasan->shift; ?></td>
                            <td colspan="5"><b>Pukul</b>: <?= date('H:i', strtotime($pengemasan->waktu)); ?></td>
                        </tr>
                        <tr><td>Nama Produk</td><td colspan="6"><?= $pengemasan->nama_produk; ?></td></tr>
                        <tr><td>Kode Produksi</td><td colspan="6"><?= $pengemasan->kode_produksi; ?></td></tr>
                        <tr><td>Best Before</td><td colspan="6"><?= $pengemasan->best_before; ?></td></tr>
                        <tr><td>Kadar Air</td><td colspan="6"><?= $pengemasan->kadar_air; ?></td></tr>
                        <tr><td>Kondisi Produk</td><td colspan="6"><?= $pengemasan->kondisi_produk; ?></td></tr>
                        <tr><td>Kondisi Seal Kemasan</td><td colspan="6"><?= $pengemasan->kondisi_seal; ?></td></tr>
                        <tr><td>Berat Kotor per pack (gram)</td><td colspan="6"><?= $pengemasan->berat_pack; ?></td></tr>
                        <tr><td>Berat Kotor per carton (Kg)</td><td colspan="6"><?= $pengemasan->berat_carton; ?></td></tr>
                        <tr><td>Labelisasi</td><td colspan="6"><?= $pengemasan->labelisasi; ?></td></tr>
                        <tr><td>Kondisi Seal Karton Box</td><td colspan="6"><?= $pengemasan->kondisi_karton; ?></td></tr>
                        <tr><td>Keterangan</td><td colspan="6"><?= $pengemasan->keterangan; ?></td></tr>

                        <!-- VERIFIKASI -->
                        <tr class="table-primary text-center">
                            <th colspan="7">VERIFIKASI</th>
                        </tr>
                        <tr><td>QC</td><td colspan="6"><?= $pengemasan->username; ?></td></tr>
                        <tr><td>Produksi</td><td colspan="6"><?= $pengemasan->nama_produksi; ?></td></tr>
                        <tr>
                            <td>Disetujui Supervisor</td>
                            <td colspan="6">
                                <?php
                                if ($pengemasan->status_spv == 1) {
                                    echo '<span class="text-success font-weight-bold">Verified</span>';
                                } elseif ($pengemasan->status_spv == 2) {
                                    echo '<span class="text-danger font-weight-bold">Revision</span>';
                                } else {
                                    echo '<span class="text-secondary font-weight-bold">Created</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr><td>Catatan Supervisor</td><td colspan="6"><?= !empty($pengemasan->catatan_spv) ? $pengemasan->catatan_spv : 'Tidak ada'; ?></td></tr>
                    </tbody>
                </table>    
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
        border-bottom: 1px solid #ddd;
        word-break: break-word;
        white-space: normal !important;
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
