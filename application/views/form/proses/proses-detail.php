<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'proses/audit' : 'proses';

$page_title = $is_audit
	? 'Detail Audit Proses Produksi'
	: 'Verifikasi Proses Produksi';

// Label tiap parameter proses produksi
$label_param = [
	'dokumen' => 'Acuan Dokumen',
	'revisi' => 'Revisi',
	'no_formula' => 'No. Formula',
	'nama_produk' => 'Jenis Produk',
	'kode_produksi' => 'Kode Produksi',
	'tepung_terigu' => 'Tepung Terigu',
	'tepung_tapioka' => 'Tepung Tapioka (2.270 kg)',
	'yeast' => 'Yeast (0.732 kg)',
	'bread_improver' => 'Bread Improver',
	'premix' => 'Premix',
	'n55bc' => 'N55BC',
	'np55bcy_np55bco' => 'NP55BCY / NP55BCO',
	'shortening' => 'Shortening (0.252 kg)',
	'chill_water' => 'Chill Water (14 - 16°C) (std. 18)',
	'waktu_mixing_1' => 'Waktu Mixing 1',
	'waktu_mixing_2' => 'Waktu Mixing 2',
	'sensori' => 'Sensori',
	'suhu_adonan' => 'Suhu Adonan (29 - 31°C)',
	'berat_adonan' => 'Berat Adonan (630 - 670 g/pcs)',
	'jam_mulai' => 'Jam Mulai',
	'jam_selesai' => 'Jam Selesai',
	'suhu_setting' => 'Suhu Setting (34 - 36°C)',
	'suhu_aktual' => 'Suhu Aktual',
	'rh_setting' => 'RH Setting (78 - 82%)',
	'rh_aktual' => 'RH Aktual',
	'durasi_waktu' => 'Durasi Waktu (60 - 70 menit)',
	'hasil_proofing' => 'Hasil Proofing',
	'baking_time_high' => 'Baking Time High',
	'baking_time_low' => 'Baking Time Low',
	'suhu_produk' => 'Suhu Produk (80 - 97°C)',
	'sensori_produk' => 'Sensori Produk'
];

// Decode data proses produksi
$proses_data = $proses->proses_produksi ?? [];

if (is_string($proses_data)) {
	$decoded_proses = json_decode($proses_data, true);
	$proses_data = is_array($decoded_proses) ? $decoded_proses : [];
}

if (!is_array($proses_data)) {
	$proses_data = [];
}

// Ambil metadata jumlah kolom.
if (isset($proses_data['_meta']['jumlah_kolom'])) {
	$jumlah_kolom = (int) $proses_data['_meta']['jumlah_kolom'];
	$jumlah_kolom = max(1, min(10, $jumlah_kolom));
} else {
	// Fallback untuk data lama.
	$jumlah_kolom = 1;

	for ($i = 1; $i <= 10; $i++) {
		$kolom_terisi = false;

		foreach ($proses_data as $kategori => $param_list) {
			if (!is_array($param_list) || $kategori === '_meta') {
				continue;
			}

			foreach ($param_list as $param => $values) {
				// Kode Batch bukan input berdasarkan kolom.
				if (strpos($param, 'kode_batch_') === 0) {
					continue;
				}

				if (!is_array($values) || !array_key_exists($i, $values)) {
					continue;
				}

				$value = trim((string) ($values[$i] ?? ''));

				if ($value !== '' && $value !== '✗') {
					$kolom_terisi = true;
					break 2;
				}
			}
		}

		if ($kolom_terisi) {
			$jumlah_kolom = $i;
		}
	}
}

// Metadata tidak ditampilkan sebagai kategori.
unset($proses_data['_meta']);

$label_shift = [
	'1' => 'Shift 1',
	'2' => 'Shift 2',
	'3' => 'Shift 3'
];

$format_tanggal = function ($tanggal) {
	if (empty($tanggal) || $tanggal === '0000-00-00') {
		return '-';
	}

	$timestamp = strtotime($tanggal);

	if ($timestamp === false) {
		return '-';
	}

	$nama_hari = [
		'Sunday' => 'Minggu',
		'Monday' => 'Senin',
		'Tuesday' => 'Selasa',
		'Wednesday' => 'Rabu',
		'Thursday' => 'Kamis',
		'Friday' => 'Jumat',
		'Saturday' => 'Sabtu'
	];

	$nama_bulan = [
		1 => 'Januari',
		2 => 'Februari',
		3 => 'Maret',
		4 => 'April',
		5 => 'Mei',
		6 => 'Juni',
		7 => 'Juli',
		8 => 'Agustus',
		9 => 'September',
		10 => 'Oktober',
		11 => 'November',
		12 => 'Desember'
	];

	return ($nama_hari[date('l', $timestamp)] ?? date('l', $timestamp))
		. ', ' . date('d', $timestamp)
		. ' ' . ($nama_bulan[(int) date('n', $timestamp)] ?? date('F', $timestamp))
		. ' ' . date('Y', $timestamp);
};
?>

<div class="container-fluid">
	<?php if (empty($is_popup)): ?>
		<!-- Judul halaman -->
		<div class="d-sm-flex align-items-center justify-content-between mb-3">
			<h1 class="h3 mb-2 text-gray-800">
				<?= html_escape($page_title) ?>

				<?php if ($is_audit): ?>
					<span class="badge-mode-audit">MODE AUDIT</span>
				<?php endif; ?>
			</h1>
		</div>

		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="<?= base_url($back_url) ?>">
						<i class="fas fa-arrow-left"></i>
						<?= $is_audit
							? 'Daftar Audit Proses Produksi'
							: 'Daftar Laporan Verifikasi Proses Produksi' ?>
					</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<?= $is_audit ? 'Detail Audit' : 'Detail Verifikasi' ?>
				</li>
			</ol>
		</nav>
	<?php endif; ?>
	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi Audit:</strong>
			Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
			Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<!-- Detail proses produksi -->
	<div class="card shadow mb-4">
		<div class="card-body">

			<h4 class="text-dark font-weight-bold mb-3">
				<?= $is_audit ? 'Detail Audit Proses Produksi' : 'Verifikasi Proses Produksi' ?>
			</h4>

			<table class="table table-sm table-bordered w-50">
				<tr>
					<th style="width: 40%;">Hari / Tanggal</th>
					<td>
						<?= html_escape($format_tanggal($proses->date ?? null)) ?>
					</td>
				</tr>
				<tr>
					<th>Shift</th>
					<td>
						<?= html_escape($label_shift[(string) ($proses->shift ?? '')] ?? '-') ?>
					</td>
				</tr>
				<tr>
					<th>Nama Produk</th>
					<td>
						<?= html_escape($proses->nama_produk ?? '-') ?>
					</td>
				</tr>
			</table>

			<?php if (empty($proses_data)): ?>
				<div class="alert alert-warning">
					Data proses produksi tidak tersedia.
				</div>
			<?php else: ?>

				<div class="table-responsive">
					<table class="table table-bordered" id="tabelDetailProses">

						<thead class="thead-light">
							<tr>
								<th style="min-width:220px;">Jenis Produksi</th>
								<th style="min-width:100px;">Std. Berat</th>
								<th style="min-width:120px;">Kode Batch</th>

								<?php for ($i = 1; $i <= $jumlah_kolom; $i++): ?>
									<th class="text-center" style="min-width:120px;">
										Input ke-<?= $i ?>
									</th>
								<?php endfor; ?>
							</tr>
						</thead>


						<tbody>
							<?php
							$kode_batch_params = [
								'tepung_terigu',
								'tepung_tapioka',
								'yeast',
								'bread_improver',
								'n55bc',
								'np55bcy_np55bco',
								'shortening',
								'chill_water'
							];

							foreach ($proses_data as $kategori => $param_list):
								if (!is_array($param_list) || $kategori === '_meta') {
									continue;
								}
							?>

								<tr style="background-color:#f8f9fc;font-weight:bold;">
									<td colspan="<?= 3 + $jumlah_kolom ?>">
										<?= html_escape(strtoupper(str_replace('_', ' ', $kategori))) ?>
									</td>
								</tr>

								<?php
								// Tampilkan Acuan Dokumen / Revisi / Formulasi satu kali saja.
								if (
									$kategori === 'dough_mixing' &&
									(
										isset($param_list['dokumen']) ||
										isset($param_list['revisi']) ||
										isset($param_list['no_formula'])
									)
								):
								?>
									<tr>
										<td>Acuan Dokumen / Revisi / Formulasi</td>
										<td class="text-center">-</td>
										<td class="text-center">-</td>

										<?php for ($i = 1; $i <= $jumlah_kolom; $i++): ?>
											<td class="text-center">
												<?php
												$acuan = array_filter([
													$param_list['dokumen'][$i] ?? '',
													$param_list['revisi'][$i] ?? '',
													$param_list['no_formula'][$i] ?? ''
												], function ($value) {
													return $value !== '' && $value !== null;
												});

												echo !empty($acuan)
													? html_escape(implode(' / ', $acuan))
													: '-';
												?>
											</td>
										<?php endfor; ?>
									</tr>
								<?php endif; ?>

								<?php foreach ($param_list as $param => $values): ?>

									<?php
									// Lewati parameter yang sudah digabung.
									if (in_array($param, ['dokumen', 'revisi', 'no_formula'], true)) {
										continue;
									}

									// Kode Batch tidak dibuat sebagai baris tersendiri.
									if (strpos($param, 'kode_batch_') === 0) {
										continue;
									}

									if (!is_array($values)) {
										$values = [0 => $values];
									}

									$label = $label_param[$param] ?? ucwords(str_replace('_', ' ', $param));
									$std_value = $values[0] ?? '';

									$kode_batch = '';

									if (
										$kategori === 'kondisi_rm' &&
										in_array($param, $kode_batch_params, true)
									) {
										$kode_batch = $param_list['kode_batch_' . $param] ?? '';
									}
									?>

									<tr>
										<td><?= html_escape($label) ?></td>

										<td class="text-center">
											<?= html_escape((string) $std_value) ?>
										</td>

										<td class="text-center">
											<?= html_escape((string) $kode_batch) ?>
										</td>

										<?php for ($i = 1; $i <= $jumlah_kolom; $i++): ?>
											<td class="text-center">
												<?php
												$nilai = $values[$i] ?? '';

												echo ($nilai === '' || $nilai === null)
													? '-'
													: html_escape((string) $nilai);
												?>
											</td>
										<?php endfor; ?>
									</tr>

								<?php endforeach; ?>

							<?php endforeach; ?>
						</tbody>

					</table>
				</div>

			<?php endif; ?>

			<?php
			// Ambil data packing
			$packing_raw = $proses->proses_packing ?? [];

			if (is_string($packing_raw)) {
				$decoded_packing = json_decode($packing_raw, true);
				$packing_raw = is_array($decoded_packing) ? $decoded_packing : [];
			}

			if (!is_array($packing_raw)) {
				$packing_raw = [];
			}

			$label_param_packing = [
				'jam_mulai' => 'Jam Mulai',
				'jam_selesai' => 'Jam Selesai',
				'lama_aging' => 'Lama Aging (9 - 12 jam)',
				'kadar_air' => 'Kadar Air Produk (32 - 34%)',
				'hasil_grinding' => 'Hasil Grinding',
				'suhu_setting' => 'Suhu Setting (85 - 90°C)',
				'suhu_aktual' => 'Suhu Aktual',
				'dryer_speed' => 'Dryer Speed (4 - 6 rpm)',
				'nama_produk' => 'Nama Produk',
				'kode_produksi' => 'Kode Produksi',
				'best_before' => 'Best Before',
				'suhu_sebelum_packing' => 'Suhu Sebelum Packing (32 - 35°C)',
				'kadar_air_produk' => 'Kadar Air Produk (4 - 8%)',
				'bulk_density' => 'Bulk Density (225 - 325 g/l)',
				'sensori_produk' => 'Sensori Produk',
				'kondisi_kemasan' => 'Kondisi Kemasan',
				'ketepatan_labelisasi' => 'Ketepatan Labelisasi',
				'kode_supplier' => 'Kode Supplier Kemasan',
				'net_weight' => 'Berat Produk (10,010 - 10,110 g/plastic bag)',
				'bukti_labelisasi' => 'Dokumentasi'
			];

			// Struktur proses_packing berupa array kategori.
			// Ambil entri pertama jika data disimpan per indeks.
			$packing_data = !empty($packing_raw) ? reset($packing_raw) : [];

			if (!is_array($packing_data)) {
				$packing_data = [];
			}
			?>

			<?php if (!empty($packing_data)): ?>
				<div class="mt-5">
					<h4 class="text-dark font-weight-bold mb-3">
						<?= $is_audit ? 'Detail Audit Proses Packing' : 'Verifikasi Proses Packing' ?>
					</h4>

					<table class="table table-sm table-bordered w-50">
						<tr>
							<th style="width: 40%;">Hari / Tanggal</th>
							<td>
								<?= html_escape($format_tanggal($proses->date_stall ?? null)) ?>
							</td>
						</tr>
						<tr>
							<th>Shift</th>
							<td>
								<?= html_escape($label_shift[(string) ($proses->shift_pack ?? '')] ?? '-') ?>
							</td>
						</tr>
					</table>

					<div class="table-responsive">
						<table class="table table-bordered">
							<thead class="thead-light">
								<tr>
									<th style="width: 30%;">Jenis Parameter</th>
									<th>Input</th>
								</tr>
							</thead>

							<tbody>
								<?php
								// Mengambil nilai pertama dari data packing
								$ambilNilaiPacking = function ($value) {
									if (is_array($value)) {
										return $value[0] ?? '';
									}

									return $value ?? '';
								};

								foreach ($packing_data as $kategori => $params):
								?>

									<tr style="background-color:#f1f1f1;font-weight:bold;">
										<td colspan="2">
											<?= strtoupper(str_replace('_', ' ', $kategori)) ?>
										</td>
									</tr>

									<?php foreach ($params as $param => $value): ?>

										<?php
										// Gabungkan Kondisi Kemasan dan Ketepatan Labelisasi
										if ($param === 'ketepatan_labelisasi') {
											continue;
										}

										if ($param === 'kondisi_kemasan') {
											$kondisi = $ambilNilaiPacking(
												$params['kondisi_kemasan'] ?? ''
											);

											$labelisasi = $ambilNilaiPacking(
												$params['ketepatan_labelisasi'] ?? ''
											);

											$hasilKondisi = ($kondisi === 'Oke') ? 'V' : '-';
											$hasilLabelisasi = ($labelisasi === 'Oke') ? 'V' : '-';
										?>

											<tr>
												<td>Kondisi Kemasan / Ketepatan Labelisasi</td>
												<td class="text-center">
													<?= $hasilKondisi ?> / <?= $hasilLabelisasi ?>
												</td>
											</tr>

										<?php
											continue;
										}

										// Label khusus
										$label = $label_param_packing[$param] ?? ucfirst(
											str_replace('_', ' ', $param)
										);

										if ($param === 'kode_supplier') {
											$label = 'Kode Supplier Kemasan';
										} elseif ($param === 'net_weight') {
											$label = 'Berat Produk (10,010 - 10,110 g/plastic bag)';
										} elseif ($param === 'bukti_labelisasi') {
											$label = 'Dokumentasi';
										}
										?>

										<tr>
											<td><?= html_escape($label) ?></td>

											<td>
												<?php if ($param === 'bukti_labelisasi'): ?>

													<?php
													$file = $ambilNilaiPacking($value);
													$path = FCPATH . 'uploads/bukti_labelisasi/' . $file;
													?>

													<?php if (!empty($file) && file_exists($path)): ?>
														<a
															href="<?= html_escape(base_url(
																		'uploads/bukti_labelisasi/' . rawurlencode($file)
																	)) ?>"
															target="_blank"
															rel="noopener noreferrer">
															<i class="fas fa-image"></i> Lihat Dokumentasi
														</a>
													<?php else: ?>
														-
													<?php endif; ?>

												<?php elseif ($param === 'net_weight'): ?>

													<?php
													$weights = is_array($value) ? $value : [$value];
													?>

													<div class="d-flex flex-wrap" style="gap:12px;">
														<?php for ($i = 0; $i < 3; $i++): ?>
															<div>
																<strong>Sampel <?= $i + 1 ?>:</strong>
																<?= html_escape($weights[$i] ?? '-') ?>
															</div>
														<?php endfor; ?>
													</div>

												<?php else: ?>

													<?= html_escape($ambilNilaiPacking($value) ?: '-') ?>

												<?php endif; ?>
											</td>
										</tr>

									<?php endforeach; ?>

								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>

	<!-- Informasi QC / Audit -->
	<div class="card shadow mb-4">
		<div class="card-header bg-dark text-white">
			<?= $is_audit ? 'Informasi Audit' : 'Verifikasi' ?>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<tbody>
						<tr>
							<td style="width: 25%;"><strong>Catatan</strong></td>
							<td><?= nl2br(html_escape($proses->catatan ?? '-')) ?></td>
						</tr>

						<tr>
							<td><strong>QC</strong></td>
							<td><?= html_escape($proses->username ?? '-') ?></td>
						</tr>

						<tr>
							<td><strong>Produksi</strong></td>
							<td><?= html_escape($proses->nama_produksi ?? '-') ?></td>
						</tr>

						<?php if (!$is_audit): ?>
							<tr>
								<td><strong>Status SPV</strong></td>
								<td>
									<?php
									$status_spv = $proses->status_spv ?? 0;

									echo $status_spv == 1
										? 'Verified'
										: ($status_spv == 2 ? 'Revision' : 'Created');
									?>
								</td>
							</tr>

							<tr>
								<td><strong>Catatan SPV</strong></td>
								<td>
									<?= nl2br(html_escape(
										!empty($proses->catatan_spv)
											? $proses->catatan_spv
											: 'Tidak ada'
									)) ?>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb a {
		color: white !important;
		text-decoration: none;
	}

	.table {
		font-size: 14px;
	}

	th {
		background-color: #f8f9fc;
	}

	.table td,
	.table th {
		vertical-align: middle;
		font-size: 13px;
		padding: 4px 8px;
	}

	.card-header {
		font-weight: bold;
		font-size: 16px;
	}

	.badge-mode-audit {
		display: inline-block;
		background: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 600;
		padding: 6px 10px;
		border-radius: 4px;
		vertical-align: middle;
		margin-left: 6px;
	}

	.table-responsive {
		margin-bottom: 1rem;
	}

	/* Tabel detail proses produksi */
	.table-responsive {
		overflow-x: auto;
	}

	.table-responsive table {
		min-width: 100%;
		white-space: nowrap;
	}

	.table-responsive table td,
	.table-responsive table th {
		vertical-align: middle;
		padding: 6px 8px;
	}

	.table-responsive table thead th {
		text-align: center;
	}

	#tabelDetailProses {
		width: 100%;
		white-space: nowrap;
	}

	#tabelDetailProses th,
	#tabelDetailProses td {
		vertical-align: middle;
		padding: 6px 8px;
	}

	#tabelDetailProses thead th {
		text-align: center;
	}
</style>
