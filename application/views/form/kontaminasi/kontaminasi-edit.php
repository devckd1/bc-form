<?php
$is_audit = isset($mode) && $mode === 'audit';
$back_url = $is_audit ? 'kontaminasi/audit' : 'kontaminasi';

$form_url = $is_audit
	? 'kontaminasi/edit-audit/' . rawurlencode($kontaminasi->uuid)
	: 'kontaminasi/edit/' . rawurlencode($kontaminasi->uuid);
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Kontaminasi Benda Asing

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
					<i class="fas fa-arrow-left"></i>
					Daftar <?= $is_audit ? 'Audit ' : ''; ?>Laporan Kontaminasi Benda Asing
				</a>
			</li>
			<li class="breadcrumb-item active">Edit</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Kontaminasi Benda Asing.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
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
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $kontaminasi->date)); ?>">
						<div class="invalid-feedback"><?= form_error('date'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Shift</label>
						<select name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>">
							<option value="1" <?= (string)set_value('shift', $kontaminasi->shift) === '1' ? 'selected' : ''; ?>>1</option>
							<option value="2" <?= (string)set_value('shift', $kontaminasi->shift) === '2' ? 'selected' : ''; ?>>2</option>
							<option value="3" <?= (string)set_value('shift', $kontaminasi->shift) === '3' ? 'selected' : ''; ?>>3</option>
						</select>
						<div class="invalid-feedback"><?= form_error('shift'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Pukul</label>
						<input type="time" name="time"
							class="form-control <?= form_error('time') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('time', $kontaminasi->time)); ?>">
						<div class="invalid-feedback"><?= form_error('time'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Jenis Kontaminasi</label>
						<input type="text" name="jenis_kontaminasi"
							class="form-control <?= form_error('jenis_kontaminasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_kontaminasi', $kontaminasi->jenis_kontaminasi)); ?>">
						<div class="invalid-feedback"><?= form_error('jenis_kontaminasi'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Nama Produk</label>
						<input type="text" name="nama_produk"
							class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('nama_produk', $kontaminasi->nama_produk)); ?>">
						<div class="invalid-feedback"><?= form_error('nama_produk'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Kode Produksi</label>
						<input type="text" name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $kontaminasi->kode_produksi)); ?>">
						<div class="invalid-feedback"><?= form_error('kode_produksi'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label for="bukti" class="font-weight-bold">
							<?= $is_audit ? 'Update Bukti Temuan' : 'Bukti Temuan'; ?>
						</label>

						<input type="file" name="bukti" id="bukti"
							class="form-control no-border <?= form_error('bukti') ? 'is-invalid' : ''; ?>"
							accept="image/*,application/pdf" capture="camera">

						<?php if (!$is_audit && !empty($kontaminasi->bukti)): ?>
							<div class="mt-2">
								<a href="<?= base_url('uploads/kontaminasi/' . rawurlencode($kontaminasi->bukti)); ?>"
									target="_blank" rel="noopener">
									<i class="fas fa-paperclip mr-1"></i>
									Lihat Bukti Sebelumnya
								</a>
							</div>
						<?php endif; ?>

						<small class="form-text text-muted">
							<?php if ($is_audit): ?>
								Upload bukti tidak wajib. Kosongkan jika tidak ingin mengganti bukti.
							<?php else: ?>
								Pilih file bukti temuan jika ingin mengunggah atau mengganti bukti.
							<?php endif; ?>
						</small>

						<div class="invalid-feedback <?= form_error('bukti') ? 'd-block' : ''; ?>">
							<?= form_error('bukti'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Jumlah Temuan</label>
						<input type="number" name="jumlah_temuan"
							class="form-control <?= form_error('jumlah_temuan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value(
										'jumlah_temuan',
										$is_audit ? 0 : $kontaminasi->jumlah_temuan
									)); ?>">
						<div class="invalid-feedback"><?= form_error('jumlah_temuan'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Tahapan</label>
						<textarea name="tahapan"
							class="form-control <?= form_error('tahapan') ? 'is-invalid' : ''; ?>"><?= html_escape(set_value(
																										'tahapan',
																										$is_audit
																											? (!empty(trim((string) $kontaminasi->tahapan)) ? '-' : '')
																											: $kontaminasi->tahapan
																									)); ?></textarea>
						<div class="invalid-feedback"><?= form_error('tahapan'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Analisis Temuan</label>
						<textarea name="analisis"
							class="form-control <?= form_error('analisis') ? 'is-invalid' : ''; ?>"><?= html_escape(set_value(
																										'analisis',
																										$is_audit
																											? (!empty(trim((string) $kontaminasi->analisis)) ? '-' : '')
																											: $kontaminasi->analisis
																									)); ?></textarea>
						<div class="invalid-feedback"><?= form_error('analisis'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Tindakan Koreksi</label>
						<textarea name="tindakan"
							class="form-control <?= form_error('tindakan') ? 'is-invalid' : ''; ?>"><?= html_escape(set_value(
																										'tindakan',
																										$is_audit
																											? (!empty(trim((string) $kontaminasi->tindakan)) ? '-' : '')
																											: $kontaminasi->tindakan
																									)); ?></textarea>
						<div class="invalid-feedback"><?= form_error('tindakan'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Keterangan</label>
						<textarea name="keterangan"
							class="form-control <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"><?= html_escape(set_value(
																											'keterangan',
																											$is_audit
																												? (!empty(trim((string) $kontaminasi->keterangan)) ? '-' : '')
																												: $kontaminasi->keterangan
																										)); ?></textarea>
						<div class="invalid-feedback"><?= form_error('keterangan'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Catatan</label>
						<textarea name="catatan"
							class="form-control <?= form_error('catatan') ? 'is-invalid' : ''; ?>"><?= html_escape(set_value('catatan', $kontaminasi->catatan)); ?></textarea>
						<div class="invalid-feedback"><?= form_error('catatan'); ?></div>
					</div>
				</div>

				<div class="mt-3">
					<button type="submit" class="btn btn-success mr-2">
						<i class="fas fa-save mr-1"></i> Simpan
					</button>
					<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
						<i class="fas fa-times mr-1"></i> Batal
					</a>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.badge-mode-audit {
		display: inline-block;
		vertical-align: middle;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
	}

	.alert-audit {
		background: #f1eafb;
		border: 1px solid #5a3298;
		color: #452477;
	}
</style>
