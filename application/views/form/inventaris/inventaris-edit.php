
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'inventaris/audit'
	: 'inventaris';

$form_url = $is_audit
	? 'inventaris/edit-audit/' . rawurlencode($inventaris->uuid)
	: 'inventaris/edit/' . rawurlencode($inventaris->uuid);

$datetime = new DateTime($inventaris->date);
$formattedDate = $datetime->format('Y-m-d');

$equipment = json_decode($inventaris->peralatan, true);

if (!is_array($equipment)) {
	$equipment = [];
}

/*
|--------------------------------------------------------------------------
| Normalisasi data khusus Audit
|--------------------------------------------------------------------------
| - Kondisi awal yang terisi menjadi Baik.
| - Kondisi akhir yang terisi menjadi Baik.
| - Kondisi yang kosong tetap kosong.
| - Keterangan dikosongkan.
| - Jumlah dan nama alat tetap mengikuti data asli.
*/
if ($is_audit) {
	foreach ($equipment as $i => $row) {
		$kondisi_awal = $row['kondisi_awal'] ?? '';
		$kondisi_akhir = trim((string) ($row['kondisi_akhir'] ?? ''));

		if (is_array($kondisi_awal)) {
			foreach ($kondisi_awal as $key => $nilai) {
				if (trim((string) $nilai) !== '') {
					$kondisi_awal[$key] = 'Baik';
				}
			}
		} elseif (trim((string) $kondisi_awal) !== '') {
			$kondisi_awal = 'Baik';
		}

		if ($kondisi_akhir !== '') {
			$kondisi_akhir = 'Baik';
		}

		$equipment[$i]['kondisi_awal'] = $kondisi_awal;
		$equipment[$i]['kondisi_akhir'] = $kondisi_akhir;
		$equipment[$i]['keterangan'] = '';
	}
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Checklist Inventaris Peralatan QC Bread Crumb

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
					<?= $is_audit
						? 'Daftar Audit Verifikasi Inventaris'
						: 'Daftar Checklist Inventaris Peralatan QC Bread Crumb'; ?>
				</a>
			</li>

			<li class="breadcrumb-item active" aria-current="page">
				Edit<?= $is_audit ? ' Audit' : ''; ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi:</strong>
			Halaman ini merupakan menu Edit Audit Inventaris Peralatan QC Bread Crumb.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<?= form_open($form_url); ?>

			<div class="form-row mb-3">

				<div class="col-md-4">
					<label><strong>Tanggal</strong></label>

					<input
						type="date"
						name="date"
						class="form-control form-control-sm <?= form_error('date') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('date', $formattedDate)); ?>"
						required>

					<div class="invalid-feedback">
						<?= form_error('date'); ?>
					</div>
				</div>

				<div class="col-md-4">
					<label><strong>Shift</strong></label>

					<?php $selected_shift = set_value('shift', $inventaris->shift ?? ''); ?>

					<select
						class="form-control form-control-sm <?= form_error('shift') ? 'is-invalid' : ''; ?>"
						name="shift"
						required>

						<option value="">-- Pilih Shift --</option>

						<?php foreach ([1, 2, 3] as $shift): ?>
							<option
								value="<?= $shift; ?>"
								<?= (string) $selected_shift === (string) $shift ? 'selected' : ''; ?>>
								Shift <?= $shift; ?>
							</option>
						<?php endforeach; ?>

					</select>

					<div class="invalid-feedback">
						<?= form_error('shift'); ?>
					</div>
				</div>

			</div>

			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">

					<thead class="text-center">
						<tr>
							<th colspan="6" class="text-center font-weight-bold">
								CHECKLIST INVENTARIS PERALATAN QC BREAD CRUMB
							</th>
						</tr>

						<tr class="table-primary text-center">
							<th>No</th>
							<th>Nama Alat</th>
							<th>Jumlah</th>
							<th>Awal Shift</th>
							<th>Akhir Shift</th>
							<th>Keterangan</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($equipment as $i => $row): ?>
							<?php
							$nama_alat = $row['nama_alat'] ?? '';
							$jumlah = $row['jumlah'] ?? '';
							$kondisi_awal = $row['kondisi_awal'] ?? '';
							$kondisi_akhir = $row['kondisi_akhir'] ?? '';
							$keterangan = $row['keterangan'] ?? '';

							$selected_awal = set_value(
								"kondisi_awal[$i]",
								$kondisi_awal
							);

							$kondisi_awal_options = [
								'Baik',
								'Rusak',
								'Tidak Ada',
								'-'
							];

							$selected_awal_array = is_array($selected_awal)
								? $selected_awal
								: array_map(
									'trim',
									explode(',', (string) $selected_awal)
								);
							?>

							<tr>
								<td class="text-center">
									<?= $i + 1; ?>
								</td>

								<td>
									<?= html_escape($nama_alat); ?>

									<input
										type="hidden"
										name="nama_alat[]"
										value="<?= html_escape($nama_alat); ?>">
								</td>

								<td>
									<input
										type="number"
										name="jumlah[]"
										class="form-control form-control-sm"
										value="<?= html_escape(set_value("jumlah[$i]", $jumlah)); ?>">
								</td>

								<td>
									<?php foreach ($kondisi_awal_options as $option): ?>
										<div class="form-check">
											<input
												type="checkbox"
												class="form-check-input"
												name="kondisi_awal[<?= $i; ?>][]"
												value="<?= html_escape($option); ?>"
												<?= in_array($option, $selected_awal_array, true) ? 'checked' : ''; ?>>

											<label class="form-check-label">
												<?= html_escape($option); ?>
											</label>
										</div>
									<?php endforeach; ?>
								</td>

								<td>
									<input
										type="text"
										name="kondisi_akhir[]"
										class="form-control form-control-sm"
										value="<?= html_escape(set_value("kondisi_akhir[$i]", $kondisi_akhir)); ?>">
								</td>

								<td>
									<input
										type="text"
										name="keterangan[]"
										class="form-control form-control-sm"
										value="<?= html_escape(set_value("keterangan[$i]", $keterangan)); ?>">
								</td>
							</tr>
						<?php endforeach; ?>

						<?php if (empty($equipment)): ?>
							<tr>
								<td colspan="6" class="text-center text-muted">
									Data inventaris tidak tersedia.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>

				</table>
			</div>

			<div class="form-group mt-3">
				<button
					type="submit"
					class="btn btn-success"
					<?= empty($equipment) ? 'disabled' : ''; ?>>
					<i class="fas fa-save mr-1"></i>
					Simpan
				</button>

				<a
					href="<?= base_url($back_url); ?>"
					class="btn btn-danger">
					<i class="fas fa-times mr-1"></i>
					Batal
				</a>
			</div>

			<?= form_close(); ?>

		</div>
	</div>
</div>

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

	.badge-mode-audit {
		display: inline-block;
		vertical-align: middle;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		white-space: nowrap;
	}

	.table {
		width: 100%;
		font-size: 15px;
	}

	.table td,
	.table th {
		padding: 10px 12px;
		vertical-align: middle;
		word-break: break-word;
	}

	@media (max-width: 768px) {

		.table td,
		.table th {
			font-size: 14px;
			padding: 8px;
		}

		h1.h3 {
			font-size: 20px;
		}
	}
</style>
