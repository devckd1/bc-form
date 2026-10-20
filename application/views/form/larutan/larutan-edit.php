<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'larutan/audit'
	: 'larutan';

$form_url = $is_audit
	? 'larutan/edit-audit/' . rawurlencode($larutan->uuid)
	: 'larutan/edit/' . rawurlencode($larutan->uuid);

$bagian_list = json_decode($larutan->nama_bahan ?? '[]', true);

if (!is_array($bagian_list)) {
	$bagian_list = [];
}

$kebutuhan_options = [
	'Cleaning Lantai',
	'Cleaning mesin dan alat',
	'Cuci tangan',
	'Sanitasi Mesin dan alat',
	'Foot Basin',
	'Hand Basin'
];
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Verifikasi Pembuatan Larutan Cleaning dan Sanitasi
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
						? 'Daftar Audit Verifikasi Larutan'
						: 'Daftar Verifikasi Pembuatan Larutan Cleaning dan Sanitasi'; ?>
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

			Halaman ini merupakan menu Edit Audit Pembuatan Larutan Cleaning dan Sanitasi.
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
						value="<?= html_escape(set_value('date', $larutan->date ?? '')); ?>"
						required>
					<div class="invalid-feedback">
						<?= form_error('date'); ?>
					</div>
				</div>

				<div class="col-md-4">
					<label><strong>Shift</strong></label>
					<?php $selected_shift = set_value('shift', $larutan->shift ?? ''); ?>

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
				<table class="table table-bordered table-sm align-middle">
					<thead class="thead-dark text-center">
						<tr>
							<th>Nama Bahan</th>
							<th>Kadar</th>
							<th>Kimia (ml)</th>
							<th>Air (ml)</th>
							<th>Volume</th>
							<th>Kebutuhan</th>
							<th>Keterangan<br>(Sesuai)</th>
							<th>Tindakan Koreksi</th>
							<th>Verifikasi</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($bagian_list as $i => $row): ?>
							<?php
							$bahan = $row['bahan'] ?? '';
							$kadar = $row['kadar'] ?? '';
							$bahan_kimia = $row['bahan_kimia'] ?? '';
							$air_bersih = $row['air_bersih'] ?? '';
							$volume_akhir = $row['volume_akhir'] ?? '';
							$kebutuhan = $row['kebutuhan'] ?? '';
							$keterangan = $row['keterangan'] ?? '';
							$tindakan = $row['tindakan'] ?? '';
							$verifikasi = $row['verifikasi'] ?? '';
							?>

							<tr>
								<td>
									<?= html_escape($bahan); ?>
									<input
										type="hidden"
										name="bahan[]"
										value="<?= html_escape($bahan); ?>">
								</td>

								<td>
									<input
										type="text"
										class="form-control form-control-sm"
										name="kadar[]"
										value="<?= html_escape($kadar); ?>"
										readonly>
								</td>

								<td>
									<input
										type="text"
										class="form-control form-control-sm"
										name="bahan_kimia[]"
										value="<?= html_escape(set_value("bahan_kimia[$i]", $bahan_kimia)); ?>">
								</td>

								<td>
									<input
										type="text"
										class="form-control form-control-sm"
										name="air_bersih[]"
										value="<?= html_escape(set_value("air_bersih[$i]", $air_bersih)); ?>">
								</td>

								<td>
									<input
										type="text"
										class="form-control form-control-sm"
										name="volume_akhir[]"
										value="<?= html_escape(set_value("volume_akhir[$i]", $volume_akhir)); ?>">
								</td>

								<td>
									<?php $selected_kebutuhan = set_value("kebutuhan[$i]", $kebutuhan); ?>

									<select
										name="kebutuhan[]"
										class="form-control form-control-sm">
										<option value="">-- Pilih --</option>

										<?php foreach ($kebutuhan_options as $option): ?>
											<option
												value="<?= html_escape($option); ?>"
												<?= $selected_kebutuhan === $option ? 'selected' : ''; ?>>
												<?= html_escape($option); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>

								<td class="text-center">
									<input
										type="checkbox"
										name="keterangan[<?= $i; ?>]"
										value="Sesuai"
										<?= set_value("keterangan[$i]", $keterangan) === 'Sesuai' ? 'checked' : ''; ?>>
								</td>

								<td>
									<input
										type="text"
										class="form-control form-control-sm"
										name="tindakan[]"
										value="<?= html_escape(set_value("tindakan[$i]", $tindakan)); ?>">
								</td>

								<td>
									<input
										type="text"
										class="form-control form-control-sm"
										name="verifikasi[]"
										value="<?= html_escape(set_value("verifikasi[$i]", $verifikasi)); ?>">
								</td>
							</tr>
						<?php endforeach; ?>

						<?php if (empty($bagian_list)): ?>
							<tr>
								<td colspan="9" class="text-center text-muted">
									Data bahan larutan tidak tersedia.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="form-group mt-3">
				<label>Catatan</label>
				<textarea
					class="form-control form-control-sm"
					name="catatan"
					rows="2"><?= html_escape(set_value('catatan', $larutan->catatan ?? '')); ?></textarea>
			</div>

			<div class="form-group mt-3">
				<button
					type="submit"
					class="btn btn-success"
					<?= empty($bagian_list) ? 'disabled' : ''; ?>>
					<i class="fas fa-save mr-1"></i>
					Simpan<?= $is_audit ? '' : ''; ?>
				</button>

				<a href="<?= base_url($back_url); ?>" class="btn btn-secondary">
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

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 7px 12px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		white-space: nowrap;
	}

	.table th,
	.table td {
		vertical-align: middle;
	}
</style>
