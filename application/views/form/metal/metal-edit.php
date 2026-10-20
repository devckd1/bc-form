<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'metal/audit' : 'metal';

$form_url = $is_audit
	? 'metal/edit-audit/' . rawurlencode($metal->uuid)
	: 'metal/edit/' . rawurlencode($metal->uuid);

$plant_map = [
	'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Cikande 2 Bread Crumb',
	'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Salatiga Bread Crumb'
];

$plant_uuid = $metal->plant ?? $this->session->userdata('plant');
$plant_name = $plant_map[$plant_uuid] ?? 'Unknown Plant';

$std_fe = $plant_uuid === '651ac623-5e48-44cc-b2f6-5d622603f53c' ? '2.5 mm' : '1.5 mm';
$std_nonfe = $plant_uuid === '651ac623-5e48-44cc-b2f6-5d622603f53c' ? '2.5 mm' : '2.0 mm';
$std_sus304 = $plant_uuid === '651ac623-5e48-44cc-b2f6-5d622603f53c' ? '3.0 mm' : '2.5 mm';

$deteksi = [
	'fe' => 'Deteksi FE',
	'nonfe' => 'Deteksi Non-FE',
	'sus' => 'Deteksi SUS 304'
];

$posisi = [
	'd' => 'Depan',
	't' => 'Tengah',
	'b' => 'Belakang'
];
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 text-dark mb-2">
			Update Pemeriksaan Metal Detector

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $is_audit ? 'breadcrumb-qc' : 'breadcrumb-qc'; ?>">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Pemeriksaan Metal Detector'
						: 'Daftar Pemeriksaan Metal Detector'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Metal Detector.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow-sm border-0 mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				FORM PEMERIKSAAN METAL DETECTOR
			</h6>
		</div>

		<div class="card-body">
			<form method="post" action="<?= base_url($form_url); ?>">

				<table class="table table-bordered table-striped align-middle">
					<tbody>
						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="3">
								<input
									type="date"
									name="date_metal"
									class="form-control <?= form_error('date_metal') ? 'is-invalid' : ''; ?>"
									value="<?= html_escape($metal->date_metal ?? ''); ?>">
								<div class="invalid-feedback"><?= form_error('date_metal'); ?></div>
							</td>
						</tr>

						<tr>
							<td><strong>Plant</strong></td>
							<td colspan="3">
								<input type="text" class="form-control" value="<?= html_escape($plant_name); ?>" readonly>
							</td>
						</tr>

						<tr>
							<td><strong>Shift</strong></td>
							<td>
								<select name="shift" class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>">
									<option value="1" <?= ($metal->shift ?? '') == '1' ? 'selected' : ''; ?>>Shift 1</option>
									<option value="2" <?= ($metal->shift ?? '') == '2' ? 'selected' : ''; ?>>Shift 2</option>
									<option value="3" <?= ($metal->shift ?? '') == '3' ? 'selected' : ''; ?>>Shift 3</option>
								</select>
								<div class="invalid-feedback"><?= form_error('shift'); ?></div>
							</td>
							<td><strong>Pukul</strong></td>
							<td>
								<input
									type="time"
									name="time"
									class="form-control <?= form_error('time') ? 'is-invalid' : ''; ?>"
									value="<?= html_escape($metal->time ?? ''); ?>">
								<div class="invalid-feedback"><?= form_error('time'); ?></div>
							</td>
						</tr>

						<tr>
							<td><strong>Nama Produk</strong></td>
							<td>
								<input
									type="text"
									name="nama_produk"
									class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>"
									value="<?= html_escape($metal->nama_produk ?? ''); ?>">
								<div class="invalid-feedback"><?= form_error('nama_produk'); ?></div>
							</td>
							<td><strong>Kode Produksi</strong></td>
							<td>
								<input
									type="text"
									name="kode_produksi"
									class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
									value="<?= html_escape($metal->kode_produksi ?? ''); ?>">
								<div class="invalid-feedback"><?= form_error('kode_produksi'); ?></div>
							</td>
						</tr>

						<tr>
							<td><strong>No. Program</strong></td>
							<td>
								<input
									type="text"
									name="no_program"
									class="form-control <?= form_error('no_program') ? 'is-invalid' : ''; ?>"
									value="<?= html_escape($metal->no_program ?? ''); ?>">
								<div class="invalid-feedback"><?= form_error('no_program'); ?></div>
							</td>
							<td><strong>Deteksi NG</strong></td>
							<td>
								<select
									name="deteksi_ng"
									class="form-control <?= form_error('deteksi_ng') ? 'is-invalid' : ''; ?>">

									<option value="-" <?= ($is_audit || ($metal->deteksi_ng ?? '') == '-') ? 'selected' : ''; ?>>
										Tidak Ada
									</option>

									<option value="1" <?= !$is_audit && ($metal->deteksi_ng ?? '') == '1' ? 'selected' : ''; ?>>
										Belt Conveyor Berhenti
									</option>

									<option value="2" <?= !$is_audit && ($metal->deteksi_ng ?? '') == '2' ? 'selected' : ''; ?>>
										Rejector
									</option>

								</select>
								<div class="invalid-feedback"><?= form_error('deteksi_ng'); ?></div>
							</td>
						</tr>

						<tr class="text-center <?= $is_audit ? 'header-audit text-white' : 'table-light'; ?>">
							<th></th>
							<th>Fe</th>
							<th>Non Fe</th>
							<th>SUS 304</th>
						</tr>

						<tr>
							<td><strong>Standar</strong></td>
							<td>
								<input type="text" name="std_fe" readonly class="form-control text-center" value="<?= $std_fe; ?>">
							</td>
							<td>
								<input type="text" name="std_nonfe" readonly class="form-control text-center" value="<?= $std_nonfe; ?>">
							</td>
							<td>
								<input type="text" name="std_sus304" readonly class="form-control text-center" value="<?= $std_sus304; ?>">
							</td>
						</tr>

						<tr class="text-center <?= $is_audit ? 'header-audit text-white' : 'table-light'; ?>">
							<th></th>
							<?php foreach ($posisi as $pos_label): ?>
								<th><?= $pos_label; ?></th>
							<?php endforeach; ?>
						</tr>

						<?php foreach ($deteksi as $key => $label): ?>
							<tr>
								<td><strong><?= $label; ?></strong></td>

								<?php foreach ($posisi as $pos => $pos_label): ?>
									<?php $field = $key . '_' . $pos; ?>
									<td class="text-center">
										<input
											type="checkbox"
											class="metal-checkbox"
											name="<?= $field; ?>"
											value="terdeteksi"
											<?= (
												$is_audit ||
												($metal->{$field} ?? '') === 'terdeteksi'
											) ? 'checked' : ''; ?>>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>

						<tr>
							<td><strong>Keterangan</strong></td>
							<td colspan="3">
								<textarea name="keterangan" class="form-control" rows="3"><?= html_escape($metal->keterangan ?? ''); ?></textarea>
							</td>
						</tr>

						<tr>
							<td><strong>Catatan</strong></td>
							<td colspan="3">
								<textarea name="catatan_metal" class="form-control" rows="3"><?= html_escape($metal->catatan_metal ?? ''); ?></textarea>
							</td>
						</tr>
					</tbody>
				</table>

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
	.badge-mode-audit {
		display: inline-block;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 4px;
		font-size: 12px;
		vertical-align: middle;
		letter-spacing: .5px;
	}

	.breadcrumb {
		padding: 10px 16px;
		border-radius: 4px;
	}

	.breadcrumb-qc {
		background-color: #2E86C1;
	}

	.breadcrumb-audit,
	.header-audit {
		background-color: #5a3298;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.border-left-audit {
		border-left: 5px solid #5a3298;
	}

	.table td,
	.table th {
		vertical-align: middle;
	}

	.metal-checkbox {
		width: 25px;
		height: 25px;
		cursor: pointer;
	}

	@media (max-width: 768px) {
		h1.h3 {
			font-size: 20px;
		}

		.table {
			min-width: 650px;
		}
	}
</style>
