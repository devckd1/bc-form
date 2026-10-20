<?php
$sanitasi_data = json_decode($sanitasi->area, true);

if (!is_array($sanitasi_data)) {
    echo "<div class='alert alert-danger'>Data area tidak valid.</div>";
    $sanitasi_data = [];
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Update Pemeriksaan Sanitasi</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-primary text-white">
            <li class="breadcrumb-item">
                <a class="text-white" href="<?= base_url('sanitasi')?>"><i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Sanitasi</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" action="<?= base_url('sanitasi/edit/'.$sanitasi->uuid); ?>" enctype="multipart/form-data">
                <!-- Tanggal, Shift, Pukul -->
                <div class="form-group row">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Tanggal</label>
                        <input type="date" name="date" class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>" value="<?= $sanitasi->date ?>">
                        <div class="invalid-feedback"><?= form_error('date') ?></div>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Shift</label>
                        <select class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>" name="shift">
                            <option value="1" <?= $sanitasi->shift == '1' ? 'selected' : '' ?>>Shift 1</option>
                            <option value="2" <?= $sanitasi->shift == '2' ? 'selected' : '' ?>>Shift 2</option>
                            <option value="3" <?= $sanitasi->shift == '3' ? 'selected' : '' ?>>Shift 3</option>
                        </select>
                        <div class="invalid-feedback"><?= form_error('shift') ?></div>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Pukul</label>
                        <input type="time" name="waktu" class="form-control <?= form_error('waktu') ? 'is-invalid' : '' ?>" value="<?= $sanitasi->waktu ?>">
                        <div class="invalid-feedback"><?= form_error('waktu') ?></div>
                    </div>
                </div>

                <hr>
                <label class="font-weight-bold">Hasil Pemeriksaan</label>

                <!-- Tabel Hasil Pemeriksaan -->
                <div class="table-responsive">
                    <table class="table table-bordered small">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Area</th>
                                <th>Standar (ppm)</th>
                                <th>Aktual</th>
                                <th>Suhu Air</th>
                                <th>Keterangan</th>
                                <th>Tindakan</th>
                                <th>Gambar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sanitasi_data as $i => $detail): ?>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="sub_area[]" value="<?= $detail['sub_area'] ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="standar[]" value="<?= $detail['standar'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="aktual[]" value="<?= $detail['aktual'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="suhu_air[]" value="<?= $detail['suhu_air'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="keterangan[]" value="<?= $detail['keterangan'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="tindakan[]" value="<?= $detail['tindakan'] ?>">
                                    </td>
                                    <td>
                                        <input type="file" class="form-control-file" name="gambar[]">
                                        <?php if (!empty($detail['gambar'])): ?>
                                            <small><a href="<?= base_url('uploads/sanitasi/' . $detail['gambar']) ?>" target="_blank">Lihat Gambar Sebelumnya</a></small>
                                            <input type="hidden" name="gambar_lama[]" value="<?= $detail['gambar'] ?>">
                                        <?php else: ?>
                                            <input type="hidden" name="gambar_lama[]" value="">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Catatan -->
                <div class="form-group">
                    <label class="font-weight-bold">Catatan</label>
                    <textarea class="form-control <?= form_error('catatan') ? 'is-invalid' : '' ?>" name="catatan"><?= $sanitasi->catatan ?></textarea>
                    <div class="invalid-feedback"><?= form_error('catatan') ?></div>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('sanitasi') ?>" class="btn btn-danger">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Styling -->
<style>
    .breadcrumb {
        background-color: #2E86C1;
    }

    .breadcrumb a {
        color: white;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .table td input[type="text"],
    .table td input[type="file"] {
        height: 30px;
        font-size: 0.85rem;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 13px;
        }
    }
</style>
