<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Update Kondisi Kerja Selama Produksi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('kondisikerja') ?>">
                    <i class="fas fa-arrow-left"></i> Daftar Kondisi Kerja
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" action="<?= base_url('kondisikerja/edit/' . $kondisikerja->uuid) ?>">

                <!-- Informasi Umum -->
                <div class="form-group row">
                    <div class="col-md-3">
                        <label><strong>Tanggal</strong></label>
                        <input type="date" name="date" class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>" value="<?= $kondisikerja->date ?>">
                        <div class="invalid-feedback"><?= form_error('date') ?></div>
                    </div>
                    <div class="col-md-3">
                        <label><strong>Shift</strong></label>
                        <select name="shift" class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>">
                            <option value="1" <?= $kondisikerja->shift == 1 ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= $kondisikerja->shift == 2 ? 'selected' : '' ?>>2</option>
                            <option value="3" <?= $kondisikerja->shift == 3 ? 'selected' : '' ?>>3</option>
                        </select>
                        <div class="invalid-feedback"><?= form_error('shift') ?></div>
                    </div>
                    <div class="col-md-3">
                        <label><strong>Pukul</strong></label>
                        <input type="time" name="waktu" class="form-control <?= form_error('waktu') ? 'is-invalid' : '' ?>" value="<?= $kondisikerja->waktu ?>">
                        <div class="invalid-feedback"><?= form_error('waktu') ?></div>
                    </div>
                    <div class="col-md-3">
                        <label><strong>Area</strong></label>
                        <input type="text" name="area" class="form-control <?= form_error('area') ? 'is-invalid' : '' ?>" value="<?= $kondisikerja->area ?>">
                        <div class="invalid-feedback"><?= form_error('area') ?></div>
                    </div>
                </div>

                <?php
                $options = [
                    '1' => '1. Berdebu',
					'2' => '2. Basah, ada genangan air',
					'3' => '3. Sisa produksi (remah-remah roti, tepung, sisa adonan)',
					'4' => '4. Noda (karat, cat, tinta)',
					'5' => '5. Pertumbuhan Mikroorganisme (jamur, bau busuk, biofilm)',
					'6' => '6. Kontak / kontaminasi material non halal',
					'7' => '7. Higiene karyawan tidak sesuai GMP',
					'✓' => '✓ Ok, Sesuai SSOP, bersih, bebas najis / material non halal',
					'X' => 'X Tidak Ok, tidak sesuai SSOP',
					'-' => '- Tidak ada / Tidak digunakan'
                ];

                $sections = [
                    'higiene' => 'HIGIENE KARYAWAN',
                    'kebersihan' => 'KEBERSIHAN AREA/RUANGAN',
                    'peralatan' => 'KEBERSIHAN PERALATAN'
                ];
                ?>

                <?php foreach ($sections as $key => $title): ?>
                    <hr>
                    <label class="font-weight-bold"><?= $title ?></label>
                    <div class="form-group row align-items-end">
                        <div class="col-md-3">
                            <label>Kondisi</label>
                            <select name="kondisi_<?= $key ?>" class="form-control <?= form_error('kondisi_' . $key) ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih --</option>
                                <?php foreach ($options as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= $kondisikerja->{'kondisi_' . $key} == $val ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= form_error('kondisi_' . $key) ?></div>
                        </div>
                        <div class="col-md-3">
                            <label>Problem</label>
                            <input type="text" name="problem_<?= $key ?>" class="form-control" value="<?= $kondisikerja->{'problem_' . $key} ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Tindakan Koreksi</label>
                            <input type="text" name="tindakan_<?= $key ?>" class="form-control" value="<?= $kondisikerja->{'tindakan_' . $key} ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Verifikasi</label>
                            <input type="text" name="verifikasi_<?= $key ?>" class="form-control" value="<?= $kondisikerja->{'verifikasi_' . $key} ?>">
                        </div>
                    </div>
                <?php endforeach; ?>

                <hr>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><strong>Catatan</strong></label>
                        <input type="text" name="catatan" class="form-control" value="<?= $kondisikerja->catatan ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('kondisikerja') ?>" class="btn btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<style>
    .breadcrumb {
        background-color: #2E86C1;
    }
</style>
