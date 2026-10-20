<div class="container-fluid">

	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Edit Audit Kebersihan Ruang Produksi

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('kebersihanruang/audit') ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Kebersihan Ruang Produksi
				</a>
			</li>
			<li class="breadcrumb-item active">
				Edit Audit
			</li>
		</ol>
	</nav>

	<!-- INFO AUDIT -->
	<div class="alert audit-info">

		<i class="fas fa-info-circle mr-1"></i>

		<strong>Informasi Audit:</strong>

		Data yang ditampilkan pada halaman ini merupakan data salinan
		khusus untuk keperluan Audit.

		Perubahan yang dilakukan di sini
		<strong>tidak akan mengubah data pemeriksaan aktual.</strong>

	</div>

	<div class="card shadow mb-4">

		<div class="card-header header-audit">
			<h6 class="m-0 font-weight-bold">
				<i class="fas fa-clipboard-check mr-2"></i>
				Form Edit Audit Kebersihan Ruang Produksi
			</h6>
		</div>

		<div class="card-body">

			<form method="post"
				action="<?= base_url('kebersihanruang/edit-audit/' . $kebersihanruang->uuid); ?>">

				<!-- ================= KETERANGAN PEMERIKSAAN ================= -->
				<div class="mb-4">

					<table class="table table-bordered keterangan-table">

						<thead style="background-color: #ADD8E6;">
							<tr>
								<th>Keterangan Pemeriksaan</th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td>1. Berdebu</td>
							</tr>
							<tr>
								<td>2. Basah</td>
							</tr>
							<tr>
								<td>3. Pecah & Retak</td>
							</tr>
							<tr>
								<td>4. Sisa produksi</td>
							</tr>
							<tr>
								<td>5. Noda tinta/karat</td>
							</tr>
							<tr>
								<td>6. Jamur / bau</td>
							</tr>
						</tbody>

					</table>

				</div>

				<!-- ================= TANGGAL & SHIFT ================= -->
				<div class="form-group row">

					<div class="col-md-3">
						<label class="font-weight-bold">
							Tanggal
						</label>

						<input type="date"
							name="date"
							class="form-control"
							value="<?= htmlspecialchars($kebersihanruang->date); ?>">
					</div>

					<div class="col-md-3">
						<label class="font-weight-bold">
							Shift
						</label>

						<select name="shift" class="form-control">

							<option value="1"
								<?= ($kebersihanruang->shift == '1') ? 'selected' : ''; ?>>
								Shift 1
							</option>

							<option value="2"
								<?= ($kebersihanruang->shift == '2') ? 'selected' : ''; ?>>
								Shift 2
							</option>

							<option value="3"
								<?= ($kebersihanruang->shift == '3') ? 'selected' : ''; ?>>
								Shift 3
							</option>

						</select>
					</div>

				</div>

				<!-- ================= LOKASI ================= -->
				<div class="form-group row">

					<div class="col-sm-4">

						<label class="font-weight-bold">
							Lokasi
						</label>

						<input type="text"
							name="lokasi"
							class="form-control"
							value="<?= htmlspecialchars($kebersihanruang->lokasi); ?>"
							readonly>

					</div>

				</div>

				<hr>

				<!-- ================= DETAIL BAGIAN ================= -->

				<?php

				$detail = json_decode(
					$kebersihanruang->detail,
					true
				);

				$kondisiMap = [
					'bersih' => 'Bersih',
					'0' => 'Bersih',
					'1' => 'Berdebu',
					'2' => 'Basah',
					'3' => 'Pecah/Retak',
					'4' => 'Sisa produksi',
					'5' => 'Noda tinta/karat',
					'6' => 'Jamur / bau'
				];

				if (!empty($detail) && is_array($detail)):

					foreach ($detail as $index => $d):

						$kondisi_value = $d['kondisi'] ?? '';

						$kondisi_array = !empty($kondisi_value)
							? explode(',', $kondisi_value)
							: ['bersih'];

				?>

						<div class="form-group row mt-4">

							<!-- BAGIAN -->
							<div class="col-sm-2">

								<label class="font-weight-bold">
									Bagian
								</label>

								<input type="text"
									name="bagian[]"
									class="form-control"
									value="<?= htmlspecialchars($d['bagian'] ?? ''); ?>"
									readonly>

							</div>

							<!-- KONDISI -->
							<div class="col-sm-3">

								<label class="font-weight-bold d-block">
									Kondisi
								</label>

								<?php

								$opsi = [
									'bersih',
									'1',
									'2',
									'3',
									'4',
									'5',
									'6'
								];

								foreach ($opsi as $opt):

									$checked = in_array(
										$opt,
										$kondisi_array
									)
										? 'checked'
										: '';

								?>

									<div class="form-check form-check-inline">

										<input
											class="form-check-input kondisi-group-<?= $index ?>"
											type="checkbox"
											name="kondisi[<?= $index ?>][]"
											value="<?= $opt ?>"
											<?= $checked ?>>

										<label class="form-check-label">
											<?= $kondisiMap[$opt] ?>
										</label>

									</div>

								<?php endforeach; ?>

							</div>

							<!-- PROBLEM -->
							<div class="col-sm-3">

								<label class="font-weight-bold">
									Problem
								</label>

								<input type="text"
									name="problem[]"
									class="form-control"
									value="<?= htmlspecialchars($d['problem'] ?? ''); ?>">

							</div>

							<!-- TINDAKAN -->
							<div class="col-sm-2">

								<label class="font-weight-bold">
									Tindakan
								</label>

								<input type="text"
									name="tindakan[]"
									class="form-control"
									value="<?= htmlspecialchars($d['tindakan'] ?? ''); ?>">

							</div>

							<!-- VERIFIKASI ULANG -->
							<?php

							$isOnlyBersih =
								count($kondisi_array) === 1 &&
								in_array('bersih', $kondisi_array);

							?>

							<div class="col-sm-2 verifikasi-ulang"
								data-index="<?= $index ?>"
								style="<?= $isOnlyBersih ? 'display:none;' : '' ?>">

								<label class="font-weight-bold d-block">
									Verifikasi Ulang
								</label>

								<div class="form-check form-check-inline">

									<input
										type="radio"
										name="verifikasi[<?= $index ?>]"
										value="bersih"
										class="form-check-input"
										<?= ($d['verifikasi'] ?? '') == 'bersih'
											? 'checked'
											: '' ?>>

									<label class="form-check-label">
										Bersih
									</label>

								</div>

								<div class="form-check form-check-inline">

									<input
										type="radio"
										name="verifikasi[<?= $index ?>]"
										value="kotor"
										class="form-check-input"
										<?= ($d['verifikasi'] ?? '') == 'kotor'
											? 'checked'
											: '' ?>>

									<label class="form-check-label">
										Kotor
									</label>

								</div>

							</div>

						</div>

					<?php endforeach; ?>

				<?php else: ?>

					<div class="alert alert-secondary">
						Tidak ada data detail pemeriksaan.
					</div>

				<?php endif; ?>

				<!-- ================= TOMBOL ================= -->

				<div class="mt-4">

					<button type="submit"
						class="btn btn-success">

						<i class="fas fa-save mr-1"></i>
						Simpan

					</button>

					<a href="<?= base_url('kebersihanruang/audit') ?>"
						class="btn btn-danger">

						<i class="fas fa-times mr-1"></i>
						Batal

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

	.breadcrumb a {
		color: #fff;
	}

	.breadcrumb .active {
		color: #e8dff3;
	}

	.badge-mode-audit {
		display: inline-block;
		margin-left: 10px;
		padding: 5px 10px;
		border-radius: 5px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		vertical-align: middle;
	}

	.alert-audit {
		background-color: #f0e9f8;
		border-left: 5px solid #5a3298;
		color: #43266f;
	}

	.header-audit {
		background-color: #5a3298;
		color: #fff;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #43266f;
		border-color: #43266f;
		color: #fff;
	}

	.keterangan-table {
		width: 35%;
		font-size: 12px;
	}

	.keterangan-table thead {
		background-color: #e8dff3;
		color: #080808;
	}

	.keterangan-table th,
	.keterangan-table td {
		padding: 6px 8px;
	}

	.form-check-input[type="radio"] {
		border-radius: 2px !important;
	}

	.form-control:focus {
		border-color: #5a3298;
		box-shadow: 0 0 0 0.2rem rgba(90, 50, 152, 0.15);
	}

	.audit-info {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
	}
</style>

<script>
	$(document).ready(function() {

		function initCheckboxLogic() {

			$('[class^="kondisi-group-"]').each(function() {

				let className = $(this)
					.attr('class')
					.split(' ')[1];

				let group = $('.' + className);

				group.off('change').on('change', function() {

					let bersih = group.filter('[value="bersih"]');

					let selainBersih = group.not('[value="bersih"]');

					if ($(this).val() === 'bersih') {

						if ($(this).is(':checked')) {

							selainBersih
								.prop('checked', false)
								.prop('disabled', true);

						} else {

							selainBersih.prop('disabled', false);

						}

					}

					if ($(this).val() !== 'bersih') {

						bersih
							.prop('checked', false)
							.prop('disabled', true);

						if (
							selainBersih.filter(':checked').length === 0
						) {

							bersih
								.prop('disabled', false)
								.prop('checked', true);

						}

					}

					toggleVerifikasi();

				});

			});

		}


		initCheckboxLogic();

		toggleVerifikasi();

	});


	function toggleVerifikasi() {

		$('[class^="kondisi-group-"]').each(function() {

			let className = $(this)
				.attr('class')
				.split(' ')[1];

			let group = $('.' + className);

			let index = className.split('-')[2];

			let container = $(
				'.verifikasi-ulang[data-index="' + index + '"]'
			);

			let selainBersihChecked =
				group.not('[value="bersih"]:checked').length > 0;

			if (selainBersihChecked) {

				container.show();

			} else {

				container.hide();

				container
					.find('input')
					.prop('checked', false);

			}

		});

	}
</script>
