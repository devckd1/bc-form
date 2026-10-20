<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'penerimaankemasan/audit'
	: 'penerimaankemasan';

$form_action = $is_audit
	? 'penerimaankemasan/edit-audit/' . $penerimaankemasan->uuid
	: 'penerimaankemasan/edit/' . $penerimaankemasan->uuid;

$keterangan = [
	1 => 'Bersih',
	2 => 'Kotor',
	3 => 'Bau',
	4 => 'Bocor',
	5 => 'Basah',
	6 => 'Kering',
	7 => 'Bebas Hama',
];

$fisik = [
	'warna' => 'Warna',
	'panjang' => 'Panjang',
	'diameter' => 'Diameter',
	'lebar' => 'Lebar',
	'tinggi' => 'Tinggi',
	'berat' => 'Berat',
	'delaminasi' => 'Delaminasi',
	'bau' => 'Bau',
	'desain' => 'Desain',
];


$kondisi_mobil = array_values(array_filter(array_map(
	'trim',
	explode(',', $penerimaankemasan->kondisi_mobil ?? '')
), function ($value) {
	return $value !== '';
}));

// Default Audit hanya jika kondisi mobil belum memiliki nilai.
if ($is_audit && empty($kondisi_mobil)) {
	$kondisi_mobil = ['1', '7']; // Bersih dan Bebas Hama
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			<?= $is_audit
				? 'Edit Audit Penerimaan Kemasan dari Supplier'
				: 'Edit Pemeriksaan Penerimaan Kemasan dari Supplier'; ?>

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-custom">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Penerimaan Kemasan'
						: 'Daftar Pemeriksaan Penerimaan Kemasan dari Supplier'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active text-white" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Pemeriksaan Penerimaan Kemasan.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				FORM <?= $is_audit ? 'AUDIT ' : ''; ?>PENERIMAAN KEMASAN
			</h6>
		</div>

		<div class="card-body">
			<form method="post"
				action="<?= base_url($form_action); ?>"
				enctype="multipart/form-data">

				<div class="form-group row">
					<div class="col-md-6">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $penerimaankemasan->date ?? '')); ?>"
							required>
						<div class="invalid-feedback"><?= form_error('date'); ?></div>
					</div>

					<div class="col-md-6">
						<label class="font-weight-bold">Shift</label>
						<?php $selected_shift = (string) set_value('shift', $penerimaankemasan->shift ?? ''); ?>
						<select name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>"
							required>
							<option value="">Pilih Shift</option>
							<?php for ($s = 1; $s <= 3; $s++): ?>
								<option value="<?= $s; ?>" <?= $selected_shift === (string) $s ? 'selected' : ''; ?>>
									Shift <?= $s; ?>
								</option>
							<?php endfor; ?>
						</select>
						<div class="invalid-feedback"><?= form_error('shift'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Jenis Kemasan</label>
						<input type="text" name="jenis_kemasan"
							class="form-control <?= form_error('jenis_kemasan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_kemasan', $penerimaankemasan->jenis_kemasan ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('jenis_kemasan'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Pemasok</label>
						<input type="text" name="pemasok"
							class="form-control <?= form_error('pemasok') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('pemasok', $penerimaankemasan->pemasok ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('pemasok'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Jenis Mobil</label>
						<input type="text" name="jenis_mobil"
							class="form-control <?= form_error('jenis_mobil') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_mobil', $penerimaankemasan->jenis_mobil ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('jenis_mobil'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">No. Polisi</label>
						<input type="text" name="no_polisi"
							class="form-control <?= form_error('no_polisi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_polisi', $penerimaankemasan->no_polisi ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('no_polisi'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Identitas Pengantar</label>
						<input type="text" name="identitas_pengantar"
							class="form-control <?= form_error('identitas_pengantar') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('identitas_pengantar', $penerimaankemasan->identitas_pengantar ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('identitas_pengantar'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">No. PO / DO</label>
						<input type="text" name="no_po"
							class="form-control <?= form_error('no_po') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_po', $penerimaankemasan->no_po ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('no_po'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="font-weight-bold d-block mb-2">Kondisi Mobil</label>
					<small class="text-danger font-italic">*Centang sesuai kondisi</small>

					<div class="table-responsive mt-2">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<?php foreach ($keterangan as $label): ?>
										<th><?= html_escape($label); ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($keterangan as $key => $label): ?>
										<td>
											<input type="checkbox"
												name="kondisi_mobil[]"
												value="<?= $key; ?>"
												<?= in_array((string) $key, $kondisi_mobil, true) ? 'checked' : ''; ?>
												style="transform:scale(1.5);">
										</td>
									<?php endforeach; ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Kode Produksi</label>
						<input type="text" name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $penerimaankemasan->kode_produksi ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('kode_produksi'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Jumlah Datang</label>
						<input type="text" name="jumlah_datang"
							class="form-control <?= form_error('jumlah_datang') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jumlah_datang', $penerimaankemasan->jumlah_datang ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('jumlah_datang'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Sampel (Pcs)</label>
						<input type="text" name="sampel"
							class="form-control <?= form_error('sampel') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('sampel', $penerimaankemasan->sampel ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('sampel'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Jumlah Reject</label>
						<input type="text" name="jumlah_reject"
							class="form-control <?= form_error('jumlah_reject') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jumlah_reject', $penerimaankemasan->jumlah_reject ?? '')); ?>">
						<div class="invalid-feedback"><?= form_error('jumlah_reject'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="font-weight-bold d-block mb-2">Kondisi Fisik</label>
					<small class="text-danger font-italic">*Centang jika sesuai</small>

					<div class="table-responsive mt-2">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<?php foreach ($fisik as $label): ?>
										<th><?= html_escape($label); ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($fisik as $key => $label): ?>
										<td>
											<input type="checkbox"
												name="<?= $key; ?>"
												value="sesuai"

												<?php
												$nilai_fisik = set_value($key, $penerimaankemasan->$key ?? '');

												if ($is_audit && trim((string) $nilai_fisik) === '') {
													$nilai_fisik = 'sesuai';
												}
												?>
												<?= $nilai_fisik === 'sesuai' ? 'checked' : ''; ?>
												style="transform:scale(1.5);">
										</td>
									<?php endforeach; ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-3">
						<label class="font-weight-bold">Segel</label>
						<?php
						$segel = set_value('segel', $penerimaankemasan->segel ?? '');

						if ($is_audit && trim((string) $segel) === '') {
							$segel = 'Sesuai';
						}
						?>
						<div>
							<?php foreach (['Sesuai', 'Tidak Sesuai'] as $val): ?>
								<label class="mr-2">
									<input type="radio" name="segel" value="<?= $val; ?>"
										<?= $segel === $val ? 'checked' : ''; ?>>
									<?= $val; ?>
								</label>
							<?php endforeach; ?>
						</div>
						<small class="text-danger"><?= form_error('segel'); ?></small>
					</div>

					<div class="col-sm-3">
						<label class="font-weight-bold d-block">Penerimaan</label>
						<?php
						$penerimaan = set_value('penerimaan', $penerimaankemasan->penerimaan ?? '');

						if ($is_audit && trim((string) $penerimaan) === '') {
							$penerimaan = 'ok';
						}
						?>
						<?php foreach (['ok' => 'OK', 'tolak' => 'Tolak'] as $val => $label): ?>
							<label class="mr-2">
								<input type="radio" name="penerimaan" value="<?= $val; ?>"
									<?= $penerimaan === $val ? 'checked' : ''; ?>>
								<?= $label; ?>
							</label>
						<?php endforeach; ?>
						<small class="text-danger d-block"><?= form_error('penerimaan'); ?></small>
					</div>

					<div class="col-sm-3">
						<label class="font-weight-bold d-block">COA</label>
						<?php
						$coa = set_value('coa', $penerimaankemasan->coa ?? '');

						if ($is_audit && trim((string) $coa) === '') {
							$coa = 'ada';
						}
						?>
						<?php foreach (['ada' => 'Ada', 'tidak ada' => 'Tidak Ada'] as $val => $label): ?>
							<label class="mr-2">
								<input type="radio" name="coa" value="<?= $val; ?>"
									<?= $coa === $val ? 'checked' : ''; ?>>
								<?= $label; ?>
							</label>
						<?php endforeach; ?>
						<small class="text-danger d-block"><?= form_error('coa'); ?></small>
					</div>

					<div class="col-sm-3">
						<label class="font-weight-bold">Upload Bukti COA</label>
						<input type="file" name="bukti_coa"
							class="form-control <?= form_error('bukti_coa') ? 'is-invalid' : ''; ?>"
							accept="image/*,application/pdf">

						<?php if (!empty($penerimaankemasan->bukti_coa)): ?>
							<small>
								<a href="<?= base_url('uploads/penerimaan_kemasan/' . rawurlencode($penerimaankemasan->bukti_coa)); ?>"
									target="_blank" rel="noopener">
									Lihat bukti COA saat ini
								</a>
							</small>
						<?php endif; ?>

						<input type="hidden" name="bukti_coa_lama"
							value="<?= html_escape($penerimaankemasan->bukti_coa ?? ''); ?>">

						<div class="invalid-feedback"><?= form_error('bukti_coa'); ?></div>
						<small class="text-danger font-italic">*Upload COA jika ada</small>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Keterangan</label>

						<?php
						$keterangan_lama = $penerimaankemasan->keterangan ?? '';
						$default_keterangan = $keterangan_lama;

						if ($is_audit && trim((string) $keterangan_lama) !== '') {
							$default_keterangan = '-';
						}

						$nilai_keterangan = set_value('keterangan', $default_keterangan);
						?>

						<textarea name="keterangan" class="form-control"><?= html_escape($nilai_keterangan); ?></textarea>
						<div class="text-danger"><?= form_error('keterangan'); ?></div>
					</div>
				</div>

				<button type="submit" class="btn <?= $is_audit ? 'btn-success' : 'btn-success'; ?>">
					<i class="fas fa-save mr-1"></i>
					<?= $is_audit ? 'Simpan' : 'Simpan'; ?>
				</button>

				<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
					<i class="fas fa-times mr-1"></i>
					Batal
				</a>

			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 7px 12px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.header-audit {
		background-color: #5a3298;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #452575;
		border-color: #452575;
		color: #fff;
	}

	.breadcrumb-custom {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: .25rem;
	}

	.breadcrumb-custom .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.table th,
	.table td {
		vertical-align: middle;
	}
</style>
