<?php 
$datetime = (new DateTime($analisis->date))->format('d-m-Y');
$bb = (new DateTime($analisis->best_before))->format('d-m-Y');
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Permohonan Analisis Sampel</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search')) : base_url('analisis'); ?>">
                    <i class="fas fa-arrow-left"></i> Daftar Permohonan Analisis Sampel
                </a>
            </li>
        </ol>
    </nav>

    <!-- Data Detail -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th colspan="6" class="font-weight-bold">ANALISIS SAMPLE REPORT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><b>Tanggal</b></td>
                            <td colspan="4"><?= $datetime; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Tipe Sampel</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->tipe_sampel); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Penyimpanan</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->penyimpanan); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Nama Produk</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->nama_produk); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Kode Produksi</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->kode_produksi); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Best Before</b></td>
                            <td colspan="4"><?= $bb; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Jumlah Sampel (g)</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->jumlah_sampel); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Permintaan Analisis</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->analisis?? ''); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Catatan</b></td>
                            <td colspan="4"><?= !empty($analisis->catatan) ? htmlspecialchars($analisis->catatan) : 'Tidak ada'; ?></td>
                        </tr>

                        <tr class="bg-light text-center font-weight-bold">
                            <td colspan="6">VERIFIKASI</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>QC</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->username); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Produksi</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->nama_produksi); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>LAB</b></td>
                            <td colspan="4"><?= htmlspecialchars($analisis->nama_lab); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Verifikasi -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" action="<?= base_url('analisis/status/' . $analisis->uuid); ?>">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="font-weight-bold">Status</label>
                        <select class="form-control <?= form_error('status_spv') ? 'is-invalid' : '' ?>" name="status_spv">
                            <option value="1" <?= set_select('status_spv', '1', $analisis->status_spv == 1); ?>>Verified</option>
                            <option value="2" <?= set_select('status_spv', '2', $analisis->status_spv == 2); ?>>Revision</option>
                        </select>
                        <div class="invalid-feedback"><?= form_error('status_spv') ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="font-weight-bold">Catatan Revisi</label>
                        <textarea class="form-control" name="catatan_spv"><?= htmlspecialchars($analisis->catatan_spv); ?></textarea>
                        <div class="invalid-feedback <?= form_error('catatan_spv') ? 'd-block' : '' ?>">
                            <?= form_error('catatan_spv') ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('analisis/verifikasi') ?>" class="btn btn-danger">
                    <i class="fa fa-times"></i> Batal
                </a>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Styling -->
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
        font-size: 15px;
    }
    .table th, .table td {
        padding: 10px 12px;
        vertical-align: middle;
        word-break: break-word;
    }
    .table th {
        background-color: #f8f9fc;
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
