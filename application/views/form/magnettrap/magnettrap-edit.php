<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Update Pemeriksaan Magnet Trap</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('magnettrap') ?>">
					<i class="fas fa-arrow-left">
					</i> Daftar Pemeriksaan Magnet Trap</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>
	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" method="post" action="<?= base_url('magnettrap/edit/' . $magnettrap->uuid); ?>" enctype="multipart/form-data">
					<div class="form-group row">

						<!-- TANGGAL -->
						<div class="col-sm-3">
							<label class="form-label font-weight-bold">Tanggal</label>
							<input type="date"
								name="date"
								class="form-control <?= form_error('date') ? 'invalid' : '' ?>"
								value="<?= set_value('date', $magnettrap->date); ?>">

							<div class="invalid-feedback <?= !empty(form_error('date')) ? 'd-block' : '' ?>">
								<?= form_error('date') ?>
							</div>
						</div>

						<!-- SHIFT -->
						<div class="col-sm-3">
							<label class="form-label font-weight-bold">Shift</label>
							<select class="form-control <?= form_error('shift') ? 'invalid' : '' ?>" name="shift">
								<option value="1" <?= set_select('shift', '1', $magnettrap->shift == 1); ?>>
									1
								</option>
								<option value="2" <?= set_select('shift', '2', $magnettrap->shift == 2); ?>>
									2
								</option>
								<option value="3" <?= set_select('shift', '3', $magnettrap->shift == 3); ?>>
									3
								</option>
							</select>

							<div class="invalid-feedback <?= !empty(form_error('shift')) ? 'd-block' : '' ?>">
								<?= form_error('shift') ?>
							</div>
						</div>

						<!-- NAMA PRODUK -->
						<div class="col-sm-3">
							<label class="form-label font-weight-bold">Nama Produk</label>
							<input type="text"
								name="nama_produk"
								class="form-control <?= form_error('nama_produk') ? 'invalid' : '' ?>"
								value="<?= set_value('nama_produk', $magnettrap->nama_produk); ?>"
								placeholder="Nama Produk">

							<div class="invalid-feedback <?= !empty(form_error('nama_produk')) ? 'd-block' : '' ?>">
								<?= form_error('nama_produk') ?>
							</div>
						</div>

						<!-- KODE PRODUKSI -->
						<div class="col-sm-3">
							<label class="form-label font-weight-bold">Kode Produksi</label>
							<input type="text"
								name="kode_produksi"
								class="form-control <?= form_error('kode_produksi') ? 'invalid' : '' ?>"
								value="<?= set_value('kode_produksi', $magnettrap->kode_produksi); ?>"
								placeholder="Kode Produksi">

							<div class="invalid-feedback <?= !empty(form_error('kode_produksi')) ? 'd-block' : '' ?>">
								<?= form_error('kode_produksi') ?>
							</div>
						</div>

					</div>
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Pukul</label>
						<input type="time" name="time" class="form-control <?= form_error('time') ? 'invalid' : '' ?> " value="<?= $magnettrap->time; ?>">
						<div class="invalid-feedback <?= !empty(form_error('time')) ? 'd-block' : ''; ?> ">
							<?= form_error('time') ?>
						</div>
					</div>
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tahapan</label>
						<input type="text" name="tahapan" class="form-control <?= form_error('tahapan') ? 'invalid' : '' ?> " value="<?= $magnettrap->tahapan; ?>">
						<div class="invalid-feedback <?= !empty(form_error('tahapan')) ? 'd-block' : ''; ?> ">
							<?= form_error('tahapan') ?>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Jenis Kontaminasi</label>
						<input type="text" name="kontaminasi" class="form-control <?= form_error('kontaminasi') ? 'invalid' : '' ?> " value="<?= $magnettrap->kontaminasi; ?>">
						<div class="invalid-feedback <?= !empty(form_error('kontaminasi')) ? 'd-block' : ''; ?> ">
							<?= form_error('kontaminasi') ?>
						</div>
					</div>
					<div class="col-sm-6">
						<label class="form-label" for="bukti">Update Bukti Temuan</label>
						<br>
						<input type="file" name="bukti" id="bukti" class="form-control no-border <?= form_error('bukti') ? 'is-invalid' : '' ?>" accept="image/*,application/pdf" capture="camera">

						<?php if (!empty($magnettrap->bukti) && file_exists(FCPATH . 'uploads/magnettrap/' . $magnettrap->bukti)): ?>
							<a href="<?= base_url('uploads/magnettrap/' . $magnettrap->bukti); ?>" target="_blank">Lihat Bukti Sebelumnya</a>
							<br>
						<?php endif; ?>

						<br>
						<div class="invalid-feedback <?= form_error('bukti') ? 'd-block' : ''; ?>">
							<?= form_error('bukti') ?>
						</div>
					</div>
				</div>
				<hr>
				<div class="row form-group">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Analisis Temuan</label>
						<input type="text" name="analisis" class="form-control <?= form_error('analisis') ? 'invalid' : '' ?> " value="<?= $magnettrap->analisis; ?>">
						<div class="invalid-feedback <?= !empty(form_error('analisis')) ? 'd-block' : ''; ?> ">
							<?= form_error('analisis') ?>
						</div>
					</div>
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tindakan Koreksi</label>
						<input type="text" name="tindakan" class="form-control <?= form_error('tindakan') ? 'invalid' : '' ?> " value="<?= $magnettrap->tindakan; ?>">
						<div class="invalid-feedback <?= !empty(form_error('tindakan')) ? 'd-block' : ''; ?> ">
							<?= form_error('tindakan') ?>
						</div>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Verifikasi</label>
						<input type="text" name="verifikasi" class="form-control <?= form_error('verifikasi') ? 'invalid' : '' ?> " value="<?= $magnettrap->verifikasi; ?>">
						<div class="invalid-feedback <?= !empty(form_error('verifikasi')) ? 'd-block' : ''; ?> ">
							<?= form_error('verifikasi') ?>
						</div>
					</div>
				</div>
				<hr>
				<div class="row form-group">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Keterangan</label>
						<textarea class="form-control" name="keterangan"><?= $magnettrap->keterangan; ?></textarea>
						<div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block' : ''; ?> ">
							<?= form_error('keterangan') ?>
						</div>
					</div>
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Catatan</label>
						<textarea class="form-control" name="catatan"><?= $magnettrap->catatan; ?></textarea>
						<div class="invalid-feedback <?= !empty(form_error('catatan')) ? 'd-block' : ''; ?> ">
							<?= form_error('catatan') ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('magnettrap') ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<style type="text/css">
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>
