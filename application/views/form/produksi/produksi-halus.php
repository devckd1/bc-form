<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'produksi/audit'
	: 'produksi';

$form_url = $is_audit
	? 'produksi/halus-audit/' . rawurlencode($produksi->uuid)
	: 'produksi/halus/' . rawurlencode($produksi->uuid);
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		PACKING AREA

		<?php if ($is_audit): ?>
			<span class="badge ml-2"
				style="background:#5a3298;color:#fff;font-size:12px;">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Verifikasi Packing Area'
						: 'Daftar Laporan Verifikasi Proses Produksi'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Halus
			</li>
		</ol>
	</nav>

	<!-- Info Audit -->
	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Proses Produksi.
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

				<label class="form-label font-weight-bold">
					Produk: <?= html_escape($produksi->nama_produk); ?>
				</label>
				<br>

				<label class="form-label font-weight-bold">
					Kode Produksi: <?= html_escape($produksi->kode_produksi); ?>
				</label>

				<hr>

				<!-- SPEED ROTASI DAN KADAR AIR -->
				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Speed Rotasi (4-6 RPM)
						</label>


						<?php
						$dry_rotasi = $produksi->dry_rotasi ?? '';

						if (
							$is_audit &&
							trim((string) $dry_rotasi) !== ''
						) {
							if (
								!is_numeric($dry_rotasi) ||
								(float) $dry_rotasi < 4 ||
								(float) $dry_rotasi > 6
							) {
								$dry_rotasi = '5';
							}
						}
						?>

						<input type="number"
							name="dry_rotasi"
							class="form-control <?= form_error('dry_rotasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('dry_rotasi', $dry_rotasi)); ?>">

						<div class="invalid-feedback">
							<?= form_error('dry_rotasi'); ?>
						</div>

					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Kadar Air 4 - 8 (%)
						</label>

						<?php
						$dry_kadar_air = $produksi->dry_kadar_air ?? '';

						if (
							$is_audit &&
							trim((string) $dry_kadar_air) !== ''
						) {
							if (
								!is_numeric($dry_kadar_air) ||
								(float) $dry_kadar_air < 4 ||
								(float) $dry_kadar_air > 8
							) {
								$dry_kadar_air = '6';
							}
						}
						?>

						<input type="text"
							name="dry_kadar_air"
							class="form-control <?= form_error('dry_kadar_air') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('dry_kadar_air', $dry_kadar_air)); ?>">

						<div class="invalid-feedback">
							<?= form_error('dry_kadar_air'); ?>
						</div>

					</div>

				</div>

				<hr>

				<!-- SENSORI PRODUK -->
				<label class="form-label font-weight-bold">
					SENSORI PRODUK
				</label>

				<?php
				$sensori_fields = [
					'produk_hasil'   => 'Hasil',
					'produk_rasa'    => 'Rasa',
					'produk_aroma'   => 'Aroma',
					'produk_tekstur' => 'Tekstur',
					'produk_warna'   => 'Warna',
				];
				?>

				<div class="form-group row">

					
					<?php foreach ($sensori_fields as $field => $label): ?>

						<?php
						$current_value = set_value(
							$field,
							isset($produksi->$field) ? $produksi->$field : ''
						);

						if ($is_audit && $current_value === 'tidak') {
							$current_value = 'oke';
						}
						?>
						

						<div class="col-sm-2">
							<label class="form-label font-weight-bold mb-2">
								<?= $label; ?>
							</label>

							<div class="form-check">
								<input type="radio"
									name="<?= $field; ?>"
									value="oke"
									class="form-check-input <?= form_error($field) ? 'is-invalid' : ''; ?>"
									<?= $current_value === 'oke' ? 'checked' : ''; ?>>

								<label class="form-check-label">Oke</label>
							</div>

							<div class="form-check">
								<input type="radio"
									name="<?= $field; ?>"
									value="tidak"
									class="form-check-input <?= form_error($field) ? 'is-invalid' : ''; ?>"
									<?= $current_value === 'tidak' ? 'checked' : ''; ?>>

								<label class="form-check-label">Tidak</label>
							</div>

							<div class="invalid-feedback <?= form_error($field) ? 'd-block' : ''; ?>">
								<?= form_error($field); ?>
							</div>
						</div>

					<?php endforeach; ?>

				</div>

				<hr>

				<!-- KEMASAN -->
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
								class="custom-file-input <?= (!empty($upload_error) || form_error('gambar_kode_kemasan')) ? 'is-invalid' : ''; ?>"
								accept="image/jpeg,image/png,application/pdf">

							<label class="custom-file-label"
								for="gambar_kode_kemasan">
								Pilih Gambar (Max 2MB)...
							</label>
						</div>

						<?php if (!empty($upload_error)): ?>
							<div class="invalid-feedback d-block">
								<?= $upload_error; ?>
							</div>
						<?php endif; ?>

						<?php if (form_error('gambar_kode_kemasan')): ?>
							<div class="invalid-feedback d-block">
								<?= form_error('gambar_kode_kemasan'); ?>
							</div>
						<?php endif; ?>

						<?php if (!empty($produksi->gambar_kode_kemasan)): ?>
							<a href="<?= base_url('uploads/' . rawurlencode($produksi->gambar_kode_kemasan)); ?>"
								target="_blank"
								rel="noopener noreferrer"
								class="d-block mt-2">
								Lihat Gambar Sebelumnya
							</a>
						<?php endif; ?>

						<small class="text-danger font-italic d-block mt-1">
							*Format: JPG, JPEG, PNG, PDF — Maksimal 2 MB
						</small>

					</div>
				</div>

				<!-- KONDISI KEMASAN -->
				<div class="form-group row">
					<div class="col-sm-4">

						<label class="form-label font-weight-bold">
							Kondisi Kemasan
						</label>

						<select class="form-control <?= form_error('packing_kondisi_kemasan') ? 'is-invalid' : ''; ?>"
							name="packing_kondisi_kemasan">

							<option value="1"
								<?= (string) set_value(
									'packing_kondisi_kemasan',
									$produksi->packing_kondisi_kemasan
								) === '1' ? 'selected' : ''; ?>>
								Oke
							</option>

							<option value="2"
								<?= (string) set_value(
									'packing_kondisi_kemasan',
									$produksi->packing_kondisi_kemasan
								) === '2' ? 'selected' : ''; ?>>
								Tidak Oke
							</option>

						</select>

						<div class="invalid-feedback">
							<?= form_error('packing_kondisi_kemasan'); ?>
						</div>

					</div>
				</div>

				<hr>

				<!-- CATATAN -->
				<div class="form-group row">
					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea class="form-control"
							name="catatan"><?= html_escape(set_value('catatan', $produksi->catatan)); ?></textarea>

						<div class="invalid-feedback <?= form_error('catatan') ? 'd-block' : ''; ?>">
							<?= form_error('catatan'); ?>
						</div>

					</div>
				</div>

				<!-- BUTTON -->
				<div class="row">
					<div class="col">

						<button type="submit"
							class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url); ?>"
							class="btn btn-md btn-danger">
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
</style>

<script>
	$('.custom-file-input').on('change', function() {
		const fileName = $(this).val().split('\\').pop();

		$(this)
			.next('.custom-file-label')
			.addClass('selected')
			.html(fileName || 'Pilih Gambar (Max 2MB)...');
	});
</script>

<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.min.js"></script>

<script>
	document.querySelectorAll('input[type="file"]').forEach(input => {
		input.addEventListener('change', async function(e) {
			const file = e.target.files[0];

			if (!file || !file.type.startsWith('image/')) {
				return;
			}

			const options = {
				maxSizeMB: 0.5,
				maxWidthOrHeight: 800,
				useWebWorker: true
			};

			try {
				const compressedFile = await imageCompression(file, options);
				const dataTransfer = new DataTransfer();

				dataTransfer.items.add(compressedFile);
				e.target.files = dataTransfer.files;
			} catch (err) {
				console.error('Gagal mengompres gambar:', err);
			}
		});
	});
</script>
