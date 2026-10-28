<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Pemeriksaan Magnet Trap</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search')) : base_url('magnettrap'); ?>">
                    <i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Magnet Trap
                </a>
            </li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" cellspacing="0">
                    <?php 
                        $datetime = new DateTime($magnettrap->date);
                        $formattedDate = $datetime->format('d-m-Y');
                        $formattedTime = (new DateTime($magnettrap->time))->format('H:i');
                    ?>
                    <thead class="text-center">
                        <tr>
                            <th colspan="7" class="text-center font-weight-bold">PEMERIKSAAN MAGNET TRAP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><b>Tanggal:</b> <?= $formattedDate; ?></td>
                            <td><b>Shift:</b> <?= $magnettrap->shift; ?></td>
                            <td colspan="4"><b>Pukul:</b> <?= $formattedTime; ?></td>
                        </tr>
						<tr>
							<td colspan="3">
								<b>Nama Produk:</b>
								<?= !empty($magnettrap->nama_produk) ? $magnettrap->nama_produk : '-'; ?>
							</td>

							<td colspan="4">
								<b>Kode Produksi:</b>
								<?= !empty($magnettrap->kode_produksi) ? $magnettrap->kode_produksi : '-'; ?>
							</td>
						</tr>
                        <tr class="bg-light text-center">
                            <td colspan="7" class="font-weight-bold">Hasil Pemeriksaan</td>
                        </tr>
                        <tr>
                            <td><b>Tahapan</b></td>
                            <td colspan="6"><?= $magnettrap->tahapan; ?></td>
                        </tr>
                        <tr>
                            <td><b>Jenis Kontaminasi</b></td>
                            <td colspan="6"><?= $magnettrap->kontaminasi; ?></td>
                        </tr>
                        <tr>
                            <td><b>Bukti Temuan</b></td>
                            <td colspan="6">
                                <?php if (!empty($magnettrap->bukti)): ?>
                                    <img src="<?= base_url('uploads/' . $magnettrap->bukti); ?>" alt="Bukti Temuan" style="max-width: 200px; max-height: 150px;">
                                <?php else: ?>
                                    <p>Tidak ada gambar</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Analisis Temuan</b></td>
                            <td colspan="6"><?= $magnettrap->analisis; ?></td>
                        </tr>
                        <tr>
                            <td><b>Tindakan Koreksi</b></td>
                            <td colspan="6"><?= $magnettrap->tindakan; ?></td>
                        </tr>
                        <tr>
                            <td><b>Verifikasi</b></td>
                            <td colspan="6"><?= $magnettrap->verifikasi; ?></td>
                        </tr>
                        <tr>
                            <td><b>Keterangan</b></td>
                            <td colspan="6"><?= !empty($magnettrap->keterangan) ? $magnettrap->keterangan : 'Tidak ada'; ?></td>
                        </tr>
                        <tr>
                            <td><b>Catatan</b></td>
                            <td colspan="6"><?= !empty($magnettrap->catatan) ? $magnettrap->catatan : 'Tidak ada'; ?></td>
                        </tr>
                        <tr class="table-primary text-center">
                            <th colspan="7">VERIFIKASI</th>
                        </tr>
                        <tr>
                            <td><b>QC</b></td>
                            <td colspan="6"><?= $magnettrap->username; ?></td>
                        </tr>
                        <tr>
                            <td><b>Engineer</b></td>
                            <td colspan="6"><?= $magnettrap->nama_enginer; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Verifikasi -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" method="post" action="<?= base_url('magnettrap/status/' . $magnettrap->uuid); ?>">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label font-weight-bold">Status Supervisor</label>
                        <select class="form-control <?= form_error('status_spv') ? 'is-invalid' : '' ?>" name="status_spv">
                            <option value="1" <?= set_select('status_spv', '1', $magnettrap->status_spv == 1); ?>>Verified</option>
                            <option value="2" <?= set_select('status_spv', '2', $magnettrap->status_spv == 2); ?>>Revision</option>
                        </select>
                        <div class="invalid-feedback <?= form_error('status_spv') ? 'd-block' : '' ?>">
                            <?= form_error('status_spv'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label font-weight-bold">Catatan Supervisor</label>
                        <textarea class="form-control" name="catatan_spv"><?= $magnettrap->catatan_spv; ?></textarea>
                        <div class="invalid-feedback <?= form_error('catatan_spv') ? 'd-block' : '' ?>">
                            <?= form_error('catatan_spv'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('magnettrap/verifikasi') ?>" class="btn btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- CSS -->
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
