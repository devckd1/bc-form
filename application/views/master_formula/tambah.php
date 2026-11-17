<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Tambah Master Formula</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('master_formula') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Data Keluhan Pelanggan
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<?= $this->session->flashdata('error_msg'); ?>
		</div>
	<?php endif; ?>

	<form action="<?= base_url('master_formula/simpan'); ?>"
		method="POST"
		id="formMasterFormula">

		<input type="hidden"
			name="<?= $this->security->get_csrf_token_name(); ?>"
			value="<?= $this->security->get_csrf_hash(); ?>">

		<!-- INFORMASI FORMULA -->
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
						placeholder="Masukkan nama formulasi"
						required>
				</div>

				<div class="form-group">
					<label>Nama Produk <span class="text-danger">*</span></label>

					<select name="produk_uuid"
						class="form-control"
						id="produk_uuid"
						required>

						<option value="">-- Pilih Nama Produk --</option>

						<?php foreach ($produk as $row): ?>
							<option value="<?= html_escape($row->uuid); ?>">
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
							placeholder="Contoh: FRM/PROD/001">
					</div>

					<div class="form-group col-md-4">
						<label>Revisi</label>

						<input type="text"
							name="revisi"
							class="form-control"
							placeholder="Contoh: 00">
					</div>

					<div class="form-group col-md-4">
						<label>Formula</label>

						<input type="text"
							name="formula"
							class="form-control"
							placeholder="Masukkan formula">
					</div>

				</div>

			</div>
		</div>

		<!-- FORMULASI BAHAN -->
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

					<table class="table table-bordered"
						id="tableBahan">

						<thead class="text-center">
							<tr>
								<th width="7%">No.</th>
								<th>Nama Bahan</th>
								<th width="30%">Standar Berat</th>
								<th width="12%">Aksi</th>
							</tr>
						</thead>

						<tbody id="bodyBahan">

							<tr>
								<td class="text-center nomor-bahan">1</td>

								<td>
									<select name="nama_bahan[]"
										class="form-control"
										required>

										<option value="">-- Pilih Nama Bahan --</option>
										<option value="Tepung Terigu">Tepung Terigu</option>
										<option value="Tepung Tapioka">Tepung Tapioka</option>
										<option value="Yeast">Yeast</option>
										<option value="Bread Improve N55BC">Bread Improve N55BC</option>
										<option value="NP55BCY / NP55BCO">NP55BCY / NP55BCO</option>
										<option value="Shortening">Shortening</option>
										<option value="Chill Water (14 - 16°C)">Chill Water (14 - 16°C)</option>

									</select>
								</td>

								<td>
									<input type="number"
										name="standar_berat[]"
										class="form-control"
										placeholder="Masukkan angka"
										min="0"
										step="any"
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

						</tbody>

					</table>

				</div>

				<small class="text-muted">
					Tambahkan bahan sesuai urutan formulasi produk.
				</small>

			</div>
		</div>

		<!-- TOMBOL SIMPAN -->
		<div class="text-right mb-4">

			<a href="<?= base_url('master_formula'); ?>"
				class="btn btn-danger">
				Batal
			</a>

			<button type="submit"
				class="btn btn-success"
				id="btnSimpan">

				<i class="fas fa-save mr-1"></i>
				Simpan
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
	$(document).ready(function() {

		// Tambah baris bahan
		$('#btnTambahBahan').on('click', function() {

			const row = `
            <tr>
                <td class="text-center nomor-bahan"></td>

                <td>
                    <select name="nama_bahan[]"
                        class="form-control"
                        required>

                        <option value="">-- Pilih Nama Bahan --</option>
                        <option value="Tepung Terigu">Tepung Terigu</option>
                        <option value="Tepung Tapioka">Tepung Tapioka</option>
                        <option value="Yeast">Yeast</option>
                        <option value="Bread Improve N55BC">Bread Improve N55BC</option>
                        <option value="NP55BCY / NP55BCO">NP55BCY / NP55BCO</option>
                        <option value="Shortening">Shortening</option>
                        <option value="Chill Water (14 - 16°C)">Chill Water (14 - 16°C)</option>

                    </select>
                </td>

                <td>
                    <input type="number"
                        name="standar_berat[]"
                        class="form-control"
                        placeholder="Masukkan angka"
                        min="0"
                        step="any"
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

		// Hapus baris bahan
		$(document).on('click', '.btnHapusBahan', function() {

			$(this).closest('tr').remove();

			updateNomorBahan();
		});

		// Update nomor urut
		function updateNomorBahan() {

			$('#bodyBahan tr').each(function(index) {

				$(this).find('.nomor-bahan').text(index + 1);

				$(this).find('.btnHapusBahan')
					.prop('disabled', $('#bodyBahan tr').length <= 1);
			});
		}

		// Validasi sebelum simpan
		$('#formMasterFormula').on('submit', function(e) {

			if (!$('#produk_uuid').val()) {
				e.preventDefault();

				Swal.fire(
					'Perhatian',
					'Silakan pilih nama produk terlebih dahulu.',
					'warning'
				);

				return false;
			}

			if ($('#bodyBahan tr').length < 1) {
				e.preventDefault();

				Swal.fire(
					'Perhatian',
					'Minimal harus ada satu bahan formulasi.',
					'warning'
				);

				return false;
			}

			$('#btnSimpan').prop('disabled', true);

			$('#btnSimpan').html(
				'<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...'
			);
		});

		updateNomorBahan();

	});
</script>
