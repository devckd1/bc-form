<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'magnettrap/audit'
	: 'magnettrap';

$form_url = $is_audit
	? 'magnettrap/edit-audit/' . rawurlencode($magnettrap->uuid)
	: 'magnettrap/edit/' . rawurlencode($magnettrap->uuid);

$bukti = $magnettrap->bukti ?? null;
$bukti_url = null;

if (!empty($bukti)) {
	if (file_exists(FCPATH . 'uploads/magnettrap/' . $bukti)) {
		$bukti_url = base_url('uploads/magnettrap/' . $bukti);
	} elseif (file_exists(FCPATH . 'uploads/' . $bukti)) {
		$bukti_url = base_url('uploads/' . $bukti);
	}
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			<?= $is_audit ? 'Edit Audit Pemeriksaan Magnet Trap' : 'Update Pemeriksaan Magnet Trap'; ?>
			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit ? 'Daftar Audit Magnet Trap' : 'Daftar Pemeriksaan Magnet Trap'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit' : 'Edit'; ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Loading Produk.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<?= $this->session->flashdata('error_msg'); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user"
				method="post"
				action="<?= base_url($form_url); ?>"
				enctype="multipart/form-data">

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $magnettrap->date)); ?>"
							required>

						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Shift</label>
						<?php $shift_value = set_value('shift', $magnettrap->shift); ?>

						<select name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>"
							required>
							<option value="1" <?= (string)$shift_value === '1' ? 'selected' : ''; ?>>1</option>
							<option value="2" <?= (string)$shift_value === '2' ? 'selected' : ''; ?>>2</option>
							<option value="3" <?= (string)$shift_value === '3' ? 'selected' : ''; ?>>3</option>
						</select>

						<div class="invalid-feedback">
							<?= form_error('shift'); ?>
						</div>
					</div>

					<!-- NAMA PRODUK -->
						<div class="col-sm-6">
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
						<div class="col-sm-6">
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
						<input type="time"
							name="time"
							class="form-control <?= form_error('time') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('time', $magnettrap->time)); ?>"
							required>

						<div class="invalid-feedback">
							<?= form_error('time'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tahapan</label>
						<input type="text"
							name="tahapan"
							class="form-control <?= form_error('tahapan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('tahapan', $magnettrap->tahapan)); ?>">

						<div class="invalid-feedback">
							<?= form_error('tahapan'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Jenis Kontaminasi</label>
						<input type="text"
							name="kontaminasi"
							class="form-control <?= form_error('kontaminasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('kontaminasi', $magnettrap->kontaminasi)); ?>">

						<div class="invalid-feedback">
							<?= form_error('kontaminasi'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold" for="bukti">
							<?= $is_audit ? 'Bukti Audit (Opsional)' : 'Update Bukti Temuan (Opsional)'; ?>
						</label>

						<input type="file"
							name="bukti"
							id="bukti"
							class="form-control <?= form_error('bukti') ? 'is-invalid' : ''; ?>"
							accept="image/jpeg,image/png,application/pdf">

						<small class="form-text text-muted">
							Format JPG, JPEG, PNG atau PDF. Maksimal 2 MB.
						</small>

						<?php if ($bukti_url): ?>
							<div class="mt-2">
								<a href="<?= html_escape($bukti_url); ?>"
									target="_blank"
									rel="noopener noreferrer">
									<i class="fas fa-paperclip mr-1"></i>
									Lihat Bukti Sebelumnya
								</a>
							</div>
						<?php elseif (!empty($bukti)): ?>
							<div class="text-muted mt-2">
								File bukti sebelumnya tidak ditemukan di folder upload.
							</div>
						<?php endif; ?>

						<div class="invalid-feedback <?= form_error('bukti') ? 'd-block' : ''; ?>">
							<?= form_error('bukti'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Analisis Temuan</label>
						<input type="text"
							name="analisis"
							class="form-control <?= form_error('analisis') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('analisis', $magnettrap->analisis)); ?>">

						<div class="invalid-feedback">
							<?= form_error('analisis'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tindakan Koreksi</label>
						<input type="text"
							name="tindakan"
							class="form-control <?= form_error('tindakan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('tindakan', $magnettrap->tindakan)); ?>">

						<div class="invalid-feedback">
							<?= form_error('tindakan'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Verifikasi</label>
						<input type="text"
							name="verifikasi"
							class="form-control <?= form_error('verifikasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('verifikasi', $magnettrap->verifikasi)); ?>">

						<div class="invalid-feedback">
							<?= form_error('verifikasi'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Keterangan</label>
						<textarea name="keterangan"
							class="form-control <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"
							rows="3"><?= html_escape(set_value('keterangan', $magnettrap->keterangan)); ?></textarea>

						<div class="invalid-feedback">
							<?= form_error('keterangan'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Catatan</label>
						<textarea name="catatan"
							class="form-control <?= form_error('catatan') ? 'is-invalid' : ''; ?>"
							rows="3"><?= html_escape(set_value('catatan', $magnettrap->catatan)); ?></textarea>

						<div class="invalid-feedback">
							<?= form_error('catatan'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success mr-2">
							<i class="fas fa-save mr-1"></i>
							Simpan <?= $is_audit ? 'Audit' : ''; ?>
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
							<i class="fas fa-times mr-1"></i>
							Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.breadcrumb a,
	.breadcrumb .breadcrumb-item.active {
		color: #fff;
	}
th {
		background-color: #f8f9fc;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}
	th {
		background-color: #f8f9fc;
	}
	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/* Label Mode Audit */
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}
</style>
