<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$form_url = $is_audit
	? base_url('produksi/packing-audit/' . rawurlencode($produksi->uuid))
	: base_url('produksi/packing/' . rawurlencode($produksi->uuid));

$back_url = $is_audit
	? base_url('produksi/audit')
	: base_url('produksi');
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		PACKING AREA
		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= $back_url; ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Verifikasi Proses Produksi'
						: 'Daftar Laporan Verifikasi Proses Produksi'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit' : 'Edit'; ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert"
			style="background-color:#f3e8ff;border-left:4px solid #6f42c1;color:#4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi:</strong>
			Halaman ini merupakan menu Edit Proses Produksi.
			Perubahan data pada halaman ini hanya berlaku pada data Audit
			dan tidak akan mengubah data aktual pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<?php if (!empty($upload_error)): ?>
		<div class="alert alert-danger">
			<?= html_escape($upload_error); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form class="user" method="post" action="<?= $form_url; ?>"
				enctype="multipart/form-data">

				<label class="form-label font-weight-bold">
					Produk: <?= html_escape($produksi->nama_produk); ?>
				</label>
				<br>

				<label class="form-label font-weight-bold">
					Kode Produksi: <?= html_escape($produksi->kode_produksi); ?>
				</label>

				<hr>

				<label class="form-label font-weight-bold">SENSORI PRODUK</label>

				<div class="form-group row">

					<?php
					$sensori_fields = [
						'produk_hasil'   => 'Hasil',
						'produk_rasa'    => 'Rasa',
						'produk_aroma'   => 'Aroma',
						'produk_tekstur' => 'Tekstur',
						'produk_warna'   => 'Warna',
					];
					?>

					<?php foreach ($sensori_fields as $field => $label): ?>

						<?php
						// Ambil nilai sensori dari data produksi
						$current_value = set_value(
							$field,
							isset($produksi->$field) ? $produksi->$field : ''
						);

						// Khusus Audit: nilai "tidak" diubah menjadi "oke"
						if ($is_audit && $current_value === 'tidak') {
							$current_value = 'oke';
						}
						?>

						<div class="col-sm-2">

							<label class="form-label font-weight-bold mb-2">
								<?= $label; ?>
							</label>

							<?php foreach (['oke' => 'Oke', 'tidak' => 'Tidak'] as $value => $text): ?>

								<div class="form-check">

									<input type="radio"
										name="<?= $field; ?>"
										value="<?= $value; ?>"
										class="form-check-input <?= form_error($field) ? 'is-invalid' : ''; ?>"
										<?= (string) $current_value === $value ? 'checked' : ''; ?>>

									<label class="form-check-label">
										<?= $text; ?>
									</label>

								</div>

							<?php endforeach; ?>

							<?php if (form_error($field)): ?>
								<div class="invalid-feedback d-block">
									<?= form_error($field); ?>
								</div>
							<?php endif; ?>

						</div>

					<?php endforeach; ?>

				</div>

				<hr>

				<label class="form-label font-weight-bold">KEMASAN</label>

				<div class="form-group row">
					<div class="col-sm-4">

						<label class="form-label font-weight-bold"
							for="gambar_kode_kemasan">
							Aktual Nama, Kode, Best Before Kemasan
						</label>

						<div class="custom-file">
							<input type="file"
								name="gambar_kode_kemasan"
								id="gambar_kode_kemasan"
								class="custom-file-input <?= form_error('gambar_kode_kemasan') ? 'is-invalid' : ''; ?>"
								accept="image/jpeg,image/png,application/pdf">

							<label class="custom-file-label" for="gambar_kode_kemasan">
								Pilih Gambar (Maks. 2 MB)...
							</label>
						</div>

						<?php if (form_error('gambar_kode_kemasan')): ?>
							<div class="invalid-feedback d-block">
								<?= form_error('gambar_kode_kemasan'); ?>
							</div>
						<?php endif; ?>

						<?php if (!empty($produksi->gambar_kode_kemasan)): ?>
							<a href="<?= base_url('uploads/' . rawurlencode($produksi->gambar_kode_kemasan)); ?>"
								target="_blank"
								rel="noopener"
								class="d-block mt-2">
								Lihat Gambar Sebelumnya
							</a>
						<?php endif; ?>

						<small class="text-danger font-italic d-block mt-1">
							*Format: JPG, JPEG, PNG, PDF — Maksimal 2 MB.
							Kosongkan jika tidak ingin mengganti file.
						</small>

					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">

						<label class="form-label font-weight-bold">
							Kondisi Kemasan
						</label>

						<select class="form-control <?= form_error('packing_kondisi_kemasan') ? 'invalid' : ''; ?>"
							name="packing_kondisi_kemasan">
							<option value="1"
								<?= (string) set_value('packing_kondisi_kemasan', $produksi->packing_kondisi_kemasan) === '1' ? 'selected' : ''; ?>>
								Oke
							</option>
							<option value="2"
								<?= (string) set_value('packing_kondisi_kemasan', $produksi->packing_kondisi_kemasan) === '2' ? 'selected' : ''; ?>>
								Tidak Oke
							</option>
						</select>

						<div class="invalid-feedback <?= form_error('packing_kondisi_kemasan') ? 'd-block' : ''; ?>">
							<?= form_error('packing_kondisi_kemasan'); ?>
						</div>

					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">

						<label class="form-label font-weight-bold">Catatan</label>

						<textarea class="form-control"
							name="catatan"><?= html_escape(set_value('catatan', $produksi->catatan)); ?></textarea>

						<div class="invalid-feedback <?= form_error('catatan') ? 'd-block' : ''; ?>">
							<?= form_error('catatan'); ?>
						</div>

					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= $back_url; ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>

			</form>

		</div>
	</div>
</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 6px 9px;
		border-radius: 4px;
		vertical-align: middle;
	}
</style>

<script>
	document.querySelectorAll('.custom-file-input').forEach(function(input) {
		input.addEventListener('change', function() {
			const fileName = this.files.length ?
				this.files[0].name :
				'Pilih Gambar (Maks. 2 MB)...';

			this.nextElementSibling.textContent = fileName;
		});
	});
</script>
