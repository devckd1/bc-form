<?php
defined('BASEPATH') or exit('No direct script access allowed');

$mode = $mode ?? 'qc';
$isAudit = ($mode === 'audit');

$editUrl = $isAudit
	? base_url('loading/edit-audit/' . $loading->uuid)
	: base_url('loading/edit/' . $loading->uuid);

$listUrl = $isAudit
	? base_url('loading/audit')
	: base_url('loading');

$title = $isAudit
	? 'Edit Audit Pemeriksaan Loading Produk'
	: 'Edit Pemeriksaan Loading Produk';

$kondisi_map = json_decode($loading->kondisi_mobil ?? '{}', true);
$kondisi_map = is_array($kondisi_map) ? $kondisi_map : [];

if ($isAudit) {
	foreach ($kondisi_mobil_list ?? [] as $key => $label) {
		if (!isset($kondisi_map[$key]) || trim((string) $kondisi_map[$key]) === '') {
			$kondisi_map[$key] = 'Ok';
		}
	}
}

$kondisi_mobil_list = [
	'bersih'                    => 'Bersih',
	'bocor'                     => 'Bocor',
	'bebas_dari_hama'            => 'Bebas dari Hama',
	'tidak_berdebu'              => 'Tidak Berdebu',
	'tidak_ada_sampah'           => 'Tidak ada Sampah',
	'kering'                     => 'Kering',
	'basah'                      => 'Basah',
	'tidak_berbau'               => 'Tidak Berbau',
	'tidak_ada_produk_non_halal' => 'Tidak ada produk Non Halal',
	'tidak_ada_aktivitas_binatang' => 'Tidak ada Aktivitas Binatang'
];

$keterangan_options = ['Ok', 'Tidak', '1', '2', '3'];

$loading_data = json_decode($loading->loading ?? '[]', true);
$loading_data = is_array($loading_data) ? $loading_data : [];

if (empty($loading_data)) {
	$loading_data = [[
		'nama_produk'     => '',
		'kondisi_produk'  => '',
		'kondisi_kemasan' => '',
		'kode_produksi'   => '',
		'expired'         => date('Y-m-d'),
		'keterangan'      => ''
	]];
}
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		<?= html_escape($title); ?>

		<?php if ($isAudit): ?>
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $isAudit ? 'breadcrumb-audit' : ''; ?>">
			<li class="breadcrumb-item">
				<a href="<?= $listUrl; ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $isAudit
						? 'Daftar Audit Pemeriksaan Loading Produk'
						: 'Daftar Pemeriksaan Loading Produk'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<?php if ($isAudit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Loading Produk.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4 <?= $isAudit ? 'card-audit' : ''; ?>">
		<div class="card-body">

			<form method="post" action="<?= $editUrl; ?>">

				<!-- Keterangan Pemeriksaan -->
				<div class="mb-4">
					<table class="table table-bordered table-keterangan">
						<thead>
							<tr>
								<th>Keterangan Pemeriksaan</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1. Noda (karat, cat, tinta)</td>
							</tr>
							<tr>
								<td>2. Bekas oli di lantai, di dinding</td>
							</tr>
							<tr>
								<td>3. Pallet rusak/pecah</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- Informasi Loading -->
				<div class="form-group row">

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $loading->date ?? '')); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">Shift</label>
						<select
							name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>"
							required>
							<?php for ($i = 1; $i <= 3; $i++): ?>
								<option
									value="<?= $i; ?>"
									<?= (string) set_value('shift', $loading->shift ?? '') === (string) $i ? 'selected' : ''; ?>>
									<?= $i; ?>
								</option>
							<?php endfor; ?>
						</select>
						<div class="invalid-feedback">
							<?= form_error('shift'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">No. Polisi Mobil</label>
						<input
							type="text"
							name="no_pol"
							class="form-control <?= form_error('no_pol') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_pol', $loading->no_pol ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('no_pol'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">Start Loading</label>
						<input
							type="time"
							name="start_loading"
							class="form-control <?= form_error('start_loading') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('start_loading', $loading->start_loading ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('start_loading'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">Finish Loading</label>
						<input
							type="time"
							name="finish_loading"
							class="form-control <?= form_error('finish_loading') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('finish_loading', $loading->finish_loading ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('finish_loading'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">Nama Sopir</label>
						<input
							type="text"
							name="nama_supir"
							class="form-control <?= form_error('nama_supir') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('nama_supir', $loading->nama_supir ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('nama_supir'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">Ekspedisi</label>
						<input
							type="text"
							name="ekspedisi"
							class="form-control <?= form_error('ekspedisi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('ekspedisi', $loading->ekspedisi ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('ekspedisi'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">Tujuan</label>
						<input
							type="text"
							name="tujuan"
							class="form-control <?= form_error('tujuan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('tujuan', $loading->tujuan ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('tujuan'); ?>
						</div>
					</div>

					<div class="col-sm-4 mb-3">
						<label class="font-weight-bold">No. Segel</label>
						<input
							type="text"
							name="no_segel"
							class="form-control <?= form_error('no_segel') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_segel', $loading->no_segel ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('no_segel'); ?>
						</div>
					</div>

				</div>

				<hr>

				<!-- Kondisi Mobil -->
				<div id="form-rm" class="form-area mb-4">
					<label class="font-weight-bold mb-3">Kondisi Mobil</label>

					<?php foreach (array_chunk($kondisi_mobil_list, 5, true) as $row): ?>
						<div class="row mb-3">
							<?php foreach ($row as $key => $label): ?>
								<?php
								$selected_value = (string) set_value(
									'keterangan_kondisi[' . $key . ']',
									$kondisi_map[$key] ?? ''
								);
								?>
								<div class="col-lg-2 col-md-4 col-sm-6 mb-3">
									<label class="form-label">
										<?= html_escape($label); ?>
									</label>

									<select
										class="form-control"
										name="keterangan_kondisi[<?= $key; ?>]">
										<option value="">Pilih</option>

										<?php foreach ($keterangan_options as $opt): ?>
											<option
												value="<?= $opt; ?>"
												<?= $selected_value === $opt ? 'selected' : ''; ?>>
												<?= $opt; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<hr>

				<!-- Detail Produk Loading -->
				<div class="form-area" id="form-loading-wrapper">
					<label class="font-weight-bold mb-3">LOADING</label>

					<?php foreach ($loading_data as $item): ?>
						<div class="loading-group border p-3 mb-3 rounded bg-light">

							<div class="form-group row">

								<div class="col-md-2 mb-2">
									<label>Nama Produk</label>
									<input
										type="text"
										name="nama_produk[]"
										class="form-control form-control-sm"
										value="<?= html_escape(set_value('nama_produk[]', $item['nama_produk'] ?? '')); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Kondisi Produk</label>
									<input
										type="text"
										name="kondisi_produk[]"
										class="form-control form-control-sm"
										value="<?= html_escape(
													$isAudit && trim((string) ($item['kondisi_produk'] ?? '')) === ''
														? 'Ok'
														: ($item['kondisi_produk'] ?? '')
												); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Kondisi Kemasan</label>
									<input
										type="text"
										name="kondisi_kemasan[]"
										class="form-control form-control-sm"
										value="<?= html_escape(
													$isAudit && trim((string) ($item['kondisi_kemasan'] ?? '')) === ''
														? 'Ok'
														: ($item['kondisi_kemasan'] ?? '')
												); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Kode Produksi</label>
									<input
										type="text"
										name="kode_produksi[]"
										class="form-control form-control-sm"
										value="<?= html_escape($item['kode_produksi'] ?? ''); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Expired</label>
									<input
										type="date"
										name="expired[]"
										class="form-control form-control-sm"
										value="<?= html_escape($item['expired'] ?? date('Y-m-d')); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Keterangan</label>
									<input
										type="text"
										name="keterangan[]"
										class="form-control form-control-sm"
										value="<?= html_escape(
													$isAudit && trim((string) ($item['keterangan'] ?? '')) !== ''
														? '-'
														: ($item['keterangan'] ?? '')
												); ?>">
								</div>

							</div>

							<div class="text-right">
								<button type="button" class="btn btn-danger btn-sm btn-remove">
									<i class="fas fa-trash mr-1"></i>
									Hapus
								</button>
							</div>

						</div>
					<?php endforeach; ?>
				</div>

				<button type="button" class="btn btn-primary mt-2" id="add-loading">
					<i class="fas fa-plus mr-1"></i>
					Tambah Produk
				</button>

				<hr>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success mr-2">
							<i class="fas fa-save mr-1"></i>
							Simpan
						</button>

						<a href="<?= $listUrl; ?>" class="btn btn-danger">
							<i class="fas fa-times mr-1"></i>
							Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		padding: 6px 10px;
		margin-left: 8px;
		border-radius: 5px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.breadcrumb {
		background-color: #2E86C1;
		padding: 10px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb a {
		color: #fff;
		font-weight: 500;
		text-decoration: none;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.card-audit {
		border-left: 4px solid #5a3298;
	}

	.table-keterangan {
		width: 100%;
		max-width: 450px;
		font-size: 14px;
	}

	.table-keterangan thead th {
		background-color: #ADD8E6;
		color: #495057;
	}

	.loading-group {
		background-color: #f8f9fc !important;
	}

	@media (max-width: 768px) {
		.badge-mode-audit {
			margin: 6px 0 0;
		}
	}
</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const wrapper = document.getElementById('form-loading-wrapper');
		const addButton = document.getElementById('add-loading');

		addButton.addEventListener('click', function() {
			const firstGroup = wrapper.querySelector('.loading-group');
			if (!firstGroup) return;

			const newGroup = firstGroup.cloneNode(true);

			newGroup.querySelectorAll('input').forEach(function(input) {
				input.value = input.name === 'expired[]' ?
					new Date().toISOString().slice(0, 10) :
					'';
			});

			wrapper.appendChild(newGroup);
		});

		wrapper.addEventListener('click', function(event) {
			const button = event.target.closest('.btn-remove');
			if (!button) return;

			const groups = wrapper.querySelectorAll('.loading-group');

			if (groups.length > 1) {
				button.closest('.loading-group').remove();
			} else {
				alert('Minimal satu produk harus ada.');
			}
		});
	});
</script>
