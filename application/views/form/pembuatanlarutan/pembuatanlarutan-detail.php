<div class="container-fluid">
	<?php if (empty($is_popup)): ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 font-weight-bold text-center">
        Detail Pemeriksaan Pembuatan Larutan
    </h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search')) : base_url('pembuatanlarutan'); ?>">
                    <i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Larutan
                </a>
            </li>
        </ol>
    </nav>
<?php endif; ?>
    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <?php 
                    $tanggal = (new DateTime($pembuatanlarutan->date))->format('d-m-Y');
                    $expired = (new DateTime($pembuatanlarutan->expired))->format('d-m-Y');
                    $jam = (new DateTime($pembuatanlarutan->pukul))->format('H:i');
                ?>
                <table class="table table-bordered table-style">
                    <thead class="text-center font-weight-bold">
                        <tr>
                            <th colspan="7">PEMERIKSAAN PEMBUATAN LARUTAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td colspan="2"><?= $tanggal; ?></td>
                            <td><strong>Jam</strong></td>
                            <td colspan="3"><?= $jam; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Area</strong></td>
                            <td colspan="6"><?= htmlspecialchars($pembuatanlarutan->area); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Chemical</strong></td>
                            <td colspan="6"><?= htmlspecialchars($pembuatanlarutan->nama_chemical); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Expired</strong></td>
                            <td colspan="6"><?= $expired; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Konsentrasi Larutan (ppm)</strong></td>
                            <td colspan="6"><?= htmlspecialchars($pembuatanlarutan->konsentrasi); ?></td>
                        </tr>
                        <tr class="text-center bg-light font-weight-bold">
                            <td></td>
                            <td>Larutan Beku</td>
                            <td colspan="5">Air</td>
                        </tr>
                        <tr class="text-center">
                            <td><strong>Pengenceran</strong></td>
                            <td><?= htmlspecialchars($pembuatanlarutan->larutan_beku); ?></td>
                            <td colspan="5"><?= htmlspecialchars($pembuatanlarutan->air); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Catatan</strong></td>
                            <td colspan="6"><?= !empty($pembuatanlarutan->catatan) ? htmlspecialchars($pembuatanlarutan->catatan) : 'Tidak ada'; ?></td>
                        </tr>
                        <tr class="table-primary text-center font-weight-bold">
                            <td colspan="7">VERIFIKASI</td>
                        </tr>
                        <tr>
                            <td><strong>QC</strong></td>
                            <td colspan="6"><?= htmlspecialchars($pembuatanlarutan->username); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Disetujui Supervisor</strong></td>
                            <td colspan="6">
                                <?php
                                    switch ($pembuatanlarutan->status_spv) {
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
                            <td><strong>Catatan Supervisor</strong></td>
                            <td colspan="6"><?= !empty($pembuatanlarutan->catatan_spv) ? htmlspecialchars($pembuatanlarutan->catatan_spv) : 'Tidak ada'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Custom CSS -->
<style>
    .breadcrumb {
        background-color: #2E86C1;
        padding: 10px 16px;
        border-radius: 0.25rem;
    }

    .breadcrumb a {
        color: #fff;
        font-weight: 500;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .table-style {
        font-size: 15px;
        width: 100%;
        border-collapse: collapse;
    }

    .table-style th,
    .table-style td {
        padding: 10px 12px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
        word-break: break-word;
    }

    .table-style thead th {
        background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
        .table-style th, .table-style td {
            font-size: 13px;
        }

        h1.h3 {
            font-size: 20px;
        }
    }
</style>
