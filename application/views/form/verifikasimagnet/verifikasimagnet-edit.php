<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$base_url = $is_audit
	? 'verifikasimagnet/audit'
	: 'verifikasimagnet';

$form_url = $is_audit
	? 'verifikasimagnet/edit-audit/' . rawurlencode($verifikasimagnet->uuid)
	: 'verifikasimagnet/edit/' . rawurlencode($verifikasimagnet->uuid);
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		<?= $is_audit ? 'Edit Audit Verifikasi Magnet Trap' : 'Update Verifikasi Magnet Trap'; ?>

		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($base_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit ? 'Daftar Audit Verifikasi Magnet Trap' : 'Daftar Verifikasi Magnet Trap'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit Audit' : 'Edit'; ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Verifikasi Magnet Trap.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger">
			<?= html_escape($this->session->flashdata('error_msg')); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

			<form class="user"
				method="post"
				action="<?= base_url($form_url); ?>"
				enctype="multipart/form-data">

				<!-- Tanggal & Shift -->
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $verifikasimagnet->date)); ?>"
							required>
						<div class="invalid-feedback"><?= form_error('date'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Shift</label>
						<select
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>"
							name="shift"
							required>
							<option value="">-- Pilih Shift --</option>
							<?php foreach ([1, 2, 3] as $shift): ?>
								<option
									value="<?= $shift; ?>"
									<?= (string) set_value('shift', $verifikasimagnet->shift) === (string) $shift ? 'selected' : ''; ?>>
									<?= $shift; ?>
								</option>
							<?php endforeach; ?>
						</select>
						<div class="invalid-feedback"><?= form_error('shift'); ?></div>
					</div>
				</div>

				<!-- Produk & Kode Produksi -->
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Nama Produk</label>
						<input
							type="text"
							name="nama_produk"
							class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('nama_produk', $verifikasimagnet->nama_produk)); ?>"
							required>
						<div class="invalid-feedback"><?= form_error('nama_produk'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Kode Produksi</label>
						<input
							type="text"
							name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $verifikasimagnet->kode_produksi)); ?>"
							required>
						<div class="invalid-feedback"><?= form_error('kode_produksi'); ?></div>
					</div>
				</div>

				<!-- Jumlah & Bukti Temuan -->
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Jumlah Temuan</label>
						<input
							type="number"
							min="0"
							name="jumlah_temuan"
							class="form-control <?= form_error('jumlah_temuan') ? 'is-invalid' : ''; ?>"
							value="<?= $is_audit
										? '0'
										: html_escape(set_value('jumlah_temuan', $verifikasimagnet->jumlah_temuan)); ?>"
							<?= $is_audit ? 'required' : 'required'; ?>>

						<div class="invalid-feedback">
							<?= form_error('jumlah_temuan'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Bukti Temuan</label>

						<input
							type="file"
							name="bukti_temuan"
							class="form-control-file <?= form_error('bukti_temuan') ? 'is-invalid' : ''; ?>"
							accept=".jpg,.jpeg,.png,.pdf">

						<small class="text-muted d-block">
							Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
							Kosongkan jika tidak ingin mengganti file.
						</small>

						<?php if (!$is_audit && !empty($verifikasimagnet->bukti_temuan)): ?>
							<p class="mt-2">
								<a
									href="<?= base_url('uploads/bukti_temuan/' . rawurlencode($verifikasimagnet->bukti_temuan)); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="text-primary">
									<i class="fas fa-paperclip"></i>
									Lihat Bukti Saat Ini
								</a>
							</p>
						<?php endif; ?>

						<div class="text-danger small">
							<?= form_error('bukti_temuan'); ?>
						</div>
					</div>
				</div>

				<!-- Keterangan & Catatan -->
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Keterangan</label>
						<textarea
							class="form-control <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"
							name="keterangan"
							rows="3"><?= html_escape(set_value('keterangan', $verifikasimagnet->keterangan)); ?></textarea>
						<div class="invalid-feedback"><?= form_error('keterangan'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Catatan</label>
						<textarea
							class="form-control <?= form_error('catatan') ? 'is-invalid' : ''; ?>"
							name="catatan"
							rows="3"><?= html_escape(set_value('catatan', $verifikasimagnet->catatan)); ?></textarea>
						<div class="invalid-feedback"><?= form_error('catatan'); ?></div>
					</div>
				</div>

				<hr>

				<!-- Tombol -->
				<div class="row">
					<div class="col">
						<button type="submit"
							class="btn <?= $is_audit ? 'btn-success' : 'btn-success'; ?> mr-2">
							<i class="fas fa-save"></i>
							<?= $is_audit ? 'Simpan' : 'Simpan'; ?>
						</button>

						<a href="<?= base_url($base_url); ?>"
							class="btn btn-danger">
							<i class="fas fa-times"></i>
							Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		padding: 6px 10px;
		border-radius: 5px;
		vertical-align: middle;
		margin-left: 5px;
	}

	.btn-edit-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-edit-audit:hover {
		background-color: #452577;
		border-color: #452577;
		color: #fff;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb a,
	.breadcrumb .breadcrumb-item.active {
		color: #fff;
	}
</style>
