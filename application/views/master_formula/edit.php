<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Edit Master Formula</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('master_formula') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Data Keluhan Pelanggan
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger alert-dismissible fade show">
			<?= html_escape($this->session->flashdata('error_msg')); ?>
			<button type="button" class="close" data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<form action="<?= base_url('master_formula/update/' . rawurlencode($formula->uuid)); ?>"
		method="POST"
		id="formMasterFormula">

		<input type="hidden"
			name="<?= $this->security->get_csrf_token_name(); ?>"
			value="<?= $this->security->get_csrf_hash(); ?>">

		<!-- Informasi Formulasi -->
		<div class="card shadow mb-4">

			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">
					<i class="fas fa-file-alt mr-1"></i>
					Informasi Formulasi
				</h6>
			</div>

			<div class="card-body">

				<div class="form-group">
					<label>Nama Formulasi <span class="text-danger">*</span></label>

					<input type="text"
						name="nama_formulasi"
						class="form-control"
						value="<?= html_escape($formula->nama_formulasi); ?>"
						required>
				</div>

				<div class="form-group">
					<label>Nama Produk <span class="text-danger">*</span></label>

					<select name="produk_uuid"
						id="produk_uuid"
						class="form-control"
						required>

						<option value="">-- Pilih Nama Produk --</option>

						<?php foreach ($produk as $row): ?>
							<option value="<?= html_escape($row->uuid); ?>"
								<?= $row->uuid == $formula->produk_uuid ? 'selected' : ''; ?>>
								<?= html_escape($row->nama_produk); ?>
							</option>
						<?php endforeach; ?>

					</select>
				</div>

				<div class="form-row">

					<div class="form-group col-md-4">
						<label>No. Dokumen</label>
						<input type="text"
							name="no_dokumen"
							class="form-control"
							value="<?= html_escape($formula->no_dokumen ?? ''); ?>">
					</div>

					<div class="form-group col-md-4">
						<label>Revisi</label>
						<input type="text"
							name="revisi"
							class="form-control"
							value="<?= html_escape($formula->revisi ?? ''); ?>">
					</div>

					<div class="form-group col-md-4">
						<label>Formula</label>
						<input type="text"
							name="formula"
							class="form-control"
							value="<?= html_escape($formula->formula ?? ''); ?>">
					</div>

				</div>

			</div>
		</div>

		<!-- Formulasi Bahan -->
		<div class="card shadow mb-4">

			<div class="card-header py-3 d-flex justify-content-between align-items-center">

				<h6 class="m-0 font-weight-bold text-primary">
					<i class="fas fa-flask mr-1"></i>
					Formulasi Bahan
				</h6>

				<button type="button"
					class="btn btn-success btn-sm"
					id="btnTambahBahan">
					<i class="fas fa-plus mr-1"></i>
					Tambah Bahan
				</button>

			</div>

			<div class="card-body">

				<div class="table-responsive">

					<table class="table table-bordered" id="tableBahan">

						<thead class="text-center">
							<tr>
								<th width="5%">No.</th>
								<th>Nama Bahan</th>
								<th width="25%">Standar Berat</th>
								<th width="10%">Aksi</th>
							</tr>
						</thead>

						<tbody id="bodyBahan">

							<?php if (!empty($detail)): ?>

								<?php foreach ($detail as $index => $row): ?>

									<tr>
										<td class="text-center nomor-bahan">
											<?= $index + 1; ?>
										</td>

										<td>
											<select name="nama_bahan[]"
												class="form-control"
												required>

												<option value="">-- Pilih Nama Bahan --</option>

												<?php foreach ($material as $bahan): ?>
													<option value="<?= html_escape($bahan->material); ?>"
														<?= $row->nama_bahan == $bahan->material ? 'selected' : ''; ?>>
														<?= html_escape($bahan->material); ?>
													</option>
												<?php endforeach; ?>

												<?php
												// Pertahankan bahan lama jika sudah tidak tersedia di tabel material
												$material_tersedia = array_column(
													array_map(function ($item) {
														return (array) $item;
													}, $material),
													'material'
												);
												?>

												<?php if (!in_array($row->nama_bahan, $material_tersedia, true)): ?>
													<option value="<?= html_escape($row->nama_bahan); ?>" selected>
														<?= html_escape($row->nama_bahan); ?> (Material tidak tersedia)
													</option>
												<?php endif; ?>

											</select>
										</td>

										<td>
											<input type="number"
												name="standar_berat[]"
												class="form-control"
												step="0.0001"
												min="0"
												value="<?= html_escape($row->standar_berat ?? ''); ?>"
												placeholder="Masukkan standar berat"
												required>
										</td>

										<td class="text-center">
											<button type="button"
												class="btn btn-danger btn-sm btnHapusBahan">
												<i class="fas fa-trash"></i>
											</button>
										</td>
									</tr>

								<?php endforeach; ?>

							<?php else: ?>

								<tr>
									<td class="text-center nomor-bahan">1</td>

									<td>
										<select name="nama_bahan[]"
											class="form-control"
											required>

											<option value="">-- Pilih Nama Bahan --</option>

											<?php foreach ($material as $bahan): ?>
												<option value="<?= html_escape($bahan->material); ?>">
													<?= html_escape($bahan->material); ?>
												</option>
											<?php endforeach; ?>

										</select>
									</td>

									<td>
										<input type="number"
											name="standar_berat[]"
											class="form-control"
											step="0.0001"
											min="0"
											placeholder="Masukkan standar berat"
											required>
									</td>

									<td class="text-center">
										<button type="button"
											class="btn btn-danger btn-sm btnHapusBahan"
											disabled>
											<i class="fas fa-trash"></i>
										</button>
									</td>
								</tr>

							<?php endif; ?>

						</tbody>

					</table>

				</div>

				<small class="text-muted">
					Tambahkan atau hapus bahan sesuai kebutuhan formulasi.
				</small>

			</div>
		</div>

		<!-- Tombol -->
		<div class="text-right mb-4">

			<a href="<?= base_url('master_formula'); ?>"
				class="btn btn-secondary">
				Batal
			</a>

			<button type="submit"
				class="btn btn-primary"
				id="btnSimpan">
				<i class="fas fa-save mr-1"></i>
				Simpan Perubahan
			</button>

		</div>

	</form>

</div>
<style type="text/css">
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>
<script>
	// Data material dari database
	const materialOptions = `
		<option value="">-- Pilih Nama Bahan --</option>
		<?php foreach ($material as $bahan): ?>
			<option value="<?= html_escape($bahan->material); ?>">
				<?= html_escape($bahan->material); ?>
			</option>
		<?php endforeach; ?>
	`;

	$(document).ready(function() {

		// Tambah bahan
		$('#btnTambahBahan').on('click', function() {

			const row = `
				<tr>
					<td class="text-center nomor-bahan"></td>

					<td>
						<select name="nama_bahan[]"
							class="form-control"
							required>
							${materialOptions}
						</select>
					</td>

					<td>
						<input type="number"
							name="standar_berat[]"
							class="form-control"
							step="0.0001"
							min="0"
							placeholder="Masukkan standar berat"
							required>
					</td>

					<td class="text-center">
						<button type="button"
							class="btn btn-danger btn-sm btnHapusBahan">
							<i class="fas fa-trash"></i>
						</button>
					</td>
				</tr>
			`;

			$('#bodyBahan').append(row);

			updateNomorBahan();
		});


		// Hapus bahan
		$(document).on('click', '.btnHapusBahan', function() {

			if ($('#bodyBahan tr').length <= 1) {
				return;
			}

			$(this).closest('tr').remove();

			updateNomorBahan();
		});


		// Update nomor dan tombol hapus
		function updateNomorBahan() {

			const jumlah = $('#bodyBahan tr').length;

			$('#bodyBahan tr').each(function(index) {

				$(this).find('.nomor-bahan').text(index + 1);

				$(this).find('.btnHapusBahan')
					.prop('disabled', jumlah <= 1);
			});
		}


		// Inisialisasi nomor
		updateNomorBahan();


		// Validasi form
		$('#formMasterFormula').on('submit', function(e) {

			const produk = $('#produk_uuid').val();
			const jumlah = $('#bodyBahan tr').length;

			if (!produk || jumlah < 1) {

				e.preventDefault();

				Swal.fire(
					'Perhatian',
					'Nama produk dan minimal satu bahan wajib diisi.',
					'warning'
				);

				return false;
			}

			let valid = true;

			$('#bodyBahan tr').each(function() {

				const bahan = $(this)
					.find('[name="nama_bahan[]"]')
					.val();

				const berat = $(this)
					.find('[name="standar_berat[]"]')
					.val();

				if (
					!bahan ||
					berat === '' ||
					!Number.isFinite(Number(berat)) ||
					Number(berat) < 0
				) {
					valid = false;
					return false;
				}
			});

			if (!valid) {

				e.preventDefault();

				Swal.fire(
					'Perhatian',
					'Nama bahan dan standar berat wajib diisi dengan benar.',
					'warning'
				);

				return false;
			}


			// Cegah submit berulang
			$('#btnSimpan')
				.prop('disabled', true)
				.html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...'
				);
		});

	});
</script>
