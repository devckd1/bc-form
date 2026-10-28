<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$is_audit = isset($mode) && $mode === 'audit';

$judul_halaman = $is_audit
	? 'Edit Audit Verifikasi Proses Produksi'
	: 'Edit Verifikasi Proses Produksi';

$url_daftar = $is_audit
	? 'proses/audit'
	: 'proses';

$url_simpan = $is_audit
	? 'proses/audit/packing/' . rawurlencode($proses->uuid)
	: 'proses/packing/' . rawurlencode($proses->uuid);
?>

<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">
		<?= $judul_halaman ?>
		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($url_daftar) ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Proses Produksi'
						: 'Daftar Verifikasi Proses Produksi' ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit Audit' : 'Edit' ?>
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

			<?php
			// Decode JSON safely
			$data_packing = [];

			if (!empty($proses->proses_packing)) {
				$decoded = json_decode($proses->proses_packing, true);

				if (is_array($decoded)) {
					$data_packing = $decoded;
				}
			}

			$data_produksi = [];

			if (!empty($proses->proses_produksi)) {
				$decoded = json_decode($proses->proses_produksi, true);

				if (is_array($decoded)) {
					$data_produksi = $decoded;
				}
			}

			// Helper untuk mengambil nilai packing
			// Prioritas:
			// 1. $data[0][$kategori][$param][0]
			// 2. $data[$param]
			// 3. $proses->$param
			if (!function_exists('getPackingValue')) {
				function getPackingValue(
					$data,
					$kategori,
					$param,
					$proses = null,
					$default = ''
				) {
					if (
						isset($data[0][$kategori][$param][0]) &&
						$data[0][$kategori][$param][0] !== ''
					) {
						return $data[0][$kategori][$param][0];
					}

					if (isset($data[$param])) {
						if (is_array($data[$param])) {
							return $data[$param][0] ?? $default;
						}

						return $data[$param];
					}

					if (
						$proses &&
						isset($proses->{$param}) &&
						$proses->{$param} !== ''
					) {
						return $proses->{$param};
					}

					return $default;
				}
			}

			// Menghitung Best Before dari kode produksi
			if (!function_exists('calc_best_before_from_kode')) {
				function calc_best_before_from_kode($kode)
				{
					if (empty($kode) || strlen($kode) < 4) {
						return '';
					}

					$yearCode = strtoupper(substr($kode, 0, 1));
					$monthCode = strtoupper(substr($kode, 1, 1));
					$day = intval(substr($kode, 2, 2));

					$monthMap = [
						'A' => 1,
						'B' => 2,
						'C' => 3,
						'D' => 4,
						'E' => 5,
						'F' => 6,
						'G' => 7,
						'H' => 8,
						'I' => 9,
						'J' => 10,
						'K' => 11,
						'L' => 12
					];

					if (!isset($monthMap[$monthCode]) || $day <= 0) {
						return '';
					}

					$month = $monthMap[$monthCode];
					$year = 2010 + (ord($yearCode) - 65);

					$d = DateTime::createFromFormat(
						'Y-n-j',
						"$year-$month-$day"
					);

					if (!$d) {
						return '';
					}

					$d->modify('+6 months');

					return $d->format('d.m.Y');
				}
			}

			$label_param = [
				'jam_mulai' => 'Jam Mulai',
				'jam_selesai' => 'Jam Selesai',
				'lama_aging' => 'Lama Aging (9 - 12 Jam)',
				'kadar_air' => 'Kadar Air (32 - 34%)',
				'hasil_grinding' => 'Hasil Grinding',
				'suhu_setting' => 'Suhu Setting (85 - 90°C)',
				'suhu_aktual' => 'Suhu Aktual',
				'dryer_speed' => 'Dryer Speed (4 - 6 rpm)',
				'nama_produk' => 'Nama Produk',
				'kode_produksi' => 'Kode Produksi',
				'best_before' => 'Best Before',
				'suhu_sebelum_packing' => 'Suhu Produk Sebelum Packing (32 - 35°C)',
				'kadar_air_produk' => 'Kadar Air Produk (4 - 8%)',
				'bulk_density' => 'Bulk Density (225 - 325 g/l)',
				'sensori_produk' => 'Sensori Produk',
				'kondisi_kemasan' => 'Kondisi Kemasan',
				'ketepatan_labelisasi' => 'Ketepatan Labelisasi',
				'kode_supplier' => 'Kode Supplier',
				'net_weight' => 'Nett Weight (9,850 - 10,100 g/plastic bag)',
			];

			$proses_packing = [
				'stalling_aging' => [
					[
						'label' => 'Jam Mulai / Selesai',
						'params' => ['jam_mulai', 'jam_selesai']
					],
					'lama_aging',
					'kadar_air'
				],
				'grinding' => [
					'hasil_grinding'
				],
				'drying' => [
					[
						'label' => 'Suhu Setting / Aktual (85 - 90°C)',
						'params' => ['suhu_setting', 'suhu_aktual']
					],
					'dryer_speed'
				],
				'pemeriksaan_finished_product' => [
					'nama_produk',
					'kode_produksi',
					'best_before',
					'suhu_sebelum_packing',
					'kadar_air_produk',
					'bulk_density',
					'sensori_produk',
					[
						'label' => 'Kemasan / Labelisasi',
						'params' => [
							'kondisi_kemasan',
							'ketepatan_labelisasi'
						]
					],
					'kode_supplier',
					'net_weight',
					'bukti_labelisasi'
				],
			];

			// Hidden initial values
			$date_stall_val = set_value(
				'date_stall',
				(
					!empty($proses->date_stall) &&
					$proses->date_stall !== '0000-00-00'
				)
					? $proses->date_stall
					: date('Y-m-d')
			);

			$shift_pack_val = set_value(
				'shift_pack',
				$proses->shift_pack ?? '1'
			);
			?>

			<form
				class="user"
				method="post"
				action="<?= base_url($url_simpan) ?>"
				enctype="multipart/form-data">

				<!-- Hidden Input -->
				<input
					type="hidden"
					name="date_stall"
					value="<?= html_escape($date_stall_val) ?>">

				<input
					type="hidden"
					name="shift_pack"
					value="<?= html_escape($shift_pack_val) ?>">

				<div class="table-responsive mt-4">
					<table class="table table-bordered">
						<thead class="thead-light">
							<tr>
								<th style="width: 30%;">Jenis Produksi</th>
								<th style="width: 70%;" class="text-center">
									Input
								</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($proses_packing as $kategori => $params): ?>

								<tr style="background-color:#f1f1f1;font-weight:bold;">
									<td colspan="2">
										<?= strtoupper(
											str_replace('_', ' ', $kategori)
										) ?>
									</td>
								</tr>

								<?php foreach ($params as $param): ?>

									<?php
									if (is_array($param)) {
										$label = $param['label'];
										$subParams = $param['params'];
									} else {
										$label = $label_param[$param]
											?? ucfirst(
												str_replace('_', ' ', $param)
											);

										$subParams = [$param];
									}
									?>

									<tr>
										<td><?= $label ?></td>

										<td>
											<?php if (count($subParams) > 1): ?>

												<div class="d-flex justify-content-center">

													<?php foreach ($subParams as $i => $sub): ?>

														<?php
														$v = getPackingValue(
															$data_packing,
															$kategori,
															$sub,
															$proses,
															''
														);

														$isCheckbox = in_array(
															$sub,
															[
																'kondisi_kemasan',
																'ketepatan_labelisasi'
															]
														);
														?>

														<?php if ($isCheckbox): ?>

															<?php
															$checked = ($v === 'Oke')
																? 'checked'
																: '';
															?>

															<input
																type="checkbox"
																name="packing[0][<?= $kategori ?>][<?= $sub ?>][0]"
																value="Oke"
																<?= $checked ?>>

															<?php if ($i == 0): ?>
																<span class="slash">/</span>
															<?php endif; ?>

														<?php else: ?>

															<?php
															$inputType = in_array(
																$sub,
																[
																	'jam_mulai',
																	'jam_selesai'
																]
															)
																? 'time'
																: 'text';
															?>

															<input
																type="<?= $inputType ?>"
																name="packing[0][<?= $kategori ?>][<?= $sub ?>][0]"
																class="form-control form-control-sm <?= ($i == 0) ? 'mr-1' : '' ?>"
																value="<?= html_escape($v) ?>">

														<?php endif; ?>

													<?php endforeach; ?>

												</div>

											<?php else: ?>

												<?php
												$paramName = $subParams[0];

												// Nama produk
												if ($paramName === 'nama_produk') {
													$val = getPackingValue(
														$data_packing,
														$kategori,
														'nama_produk',
														$proses,
														''
													);

													// Kode produksi
												} elseif ($paramName === 'kode_produksi') {
													$val = getPackingValue(
														$data_packing,
														$kategori,
														'kode_produksi',
														$proses,
														''
													);

													// Jika kosong, ambil dari data produksi
													if (empty($val)) {
														if (
															isset(
																$data_produksi['dough_mixing']['kode_produksi'][1]
															)
														) {
															$val = $data_produksi['dough_mixing']['kode_produksi'][1];
														}
													}

													// Best Before
												} elseif ($paramName === 'best_before') {
													$bb = getPackingValue(
														$data_packing,
														$kategori,
														'best_before',
														$proses,
														''
													);

													if (!empty($bb)) {
														$val = $bb;
													} else {
														$kodeForBb = getPackingValue(
															$data_packing,
															$kategori,
															'kode_produksi',
															$proses,
															''
														);

														if (
															empty($kodeForBb) &&
															isset(
																$data_produksi['dough_mixing']['kode_produksi'][1]
															)
														) {
															$kodeForBb = $data_produksi['dough_mixing']['kode_produksi'][1];
														}

														$val = calc_best_before_from_kode(
															$kodeForBb
														);
													}
												} else {
													$val = getPackingValue(
														$data_packing,
														$kategori,
														$paramName,
														$proses,
														''
													);
												}

												$isCheckbox = in_array(
													$paramName,
													[
														'hasil_grinding',
														'sensori_produk'
													]
												);
												?>

												<?php if ($isCheckbox): ?>

													<?php
													$checked = ($val === 'Oke')
														? 'checked'
														: '';
													?>

													<input
														type="checkbox"
														name="packing[0][<?= $kategori ?>][<?= $paramName ?>][0]"
														value="Oke"
														<?= $checked ?>>

												<?php elseif ($paramName === 'bukti_labelisasi'): ?>

													<?php
													$imgName = $val;

													if (
														empty($imgName) &&
														isset(
															$data_packing[0][$kategori]['bukti_labelisasi'][0]
														)
													) {
														$imgName = $data_packing[0][$kategori]['bukti_labelisasi'][0];
													}

													$imgSrc = !empty($imgName)
														? base_url(
															'uploads/bukti_labelisasi/' . $imgName
														)
														: '';
													?>

													<input
														type="file"
														name="packing[0][<?= $kategori ?>][bukti_labelisasi][0]"
														accept="image/*"
														class="form-control form-control-sm">

													<?php if ($imgSrc): ?>
														<div style="margin-top:6px;">
															<a
																href="<?= $imgSrc ?>"
																target="_blank"
																style="text-decoration:none;color:#007bff;">
																<i class="fas fa-image"></i>
																Lihat Label
															</a>
														</div>
													<?php endif; ?>

												<?php else: ?>

													<input
														type="text"
														name="packing[0][<?= $kategori ?>][<?= $paramName ?>][0]"
														class="form-control form-control-sm"
														value="<?= html_escape($val) ?>">

												<?php endif; ?>

											<?php endif; ?>
										</td>
									</tr>

								<?php endforeach; ?>

							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div class="form-group row mt-3">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea
							class="form-control"
							name="catatan_packing"><?= html_escape($proses->catatan_packing ?? '') ?></textarea>

						<div class="invalid-feedback <?= !empty(form_error('catatan_packing')) ? 'd-block' : ''; ?>">
							<?= form_error('catatan_packing') ?>
						</div>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col">

						<button type="submit" class="btn btn-success">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a
							href="<?= base_url($url_daftar) ?>"
							class="btn btn-danger">
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

	.d-flex {
		display: flex;
	}

	.mr-1 {
		margin-right: 0.25rem;
	}

	input[type="checkbox"] {
		transform: scale(1.6);
		margin: 4px auto;
		display: block;
		cursor: pointer;
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
</style>

<script>
	document.addEventListener("DOMContentLoaded", function() {

		function calculateBestBefore(kode) {
			if (!kode || kode.length < 4) return '';

			const yearCode = kode.charAt(0).toUpperCase();
			const monthCode = kode.charAt(1).toUpperCase();
			const day = parseInt(kode.substring(2, 4), 10);

			const monthMap = {
				A: 1,
				B: 2,
				C: 3,
				D: 4,
				E: 5,
				F: 6,
				G: 7,
				H: 8,
				I: 9,
				J: 10,
				K: 11,
				L: 12
			};

			if (isNaN(day) || !monthMap[monthCode]) return '';

			const month = monthMap[monthCode];
			const yearBase = 2010;
			const year = yearBase + (yearCode.charCodeAt(0) - 65);

			// Tanggal produksi awal
			const originalDate = new Date(year, month - 1, day);

			// Tambah 6 bulan
			const targetMonthIndex = originalDate.getMonth() + 6;
			const targetYear = originalDate.getFullYear() +
				Math.floor(targetMonthIndex / 12);
			const targetMonth = targetMonthIndex % 12;

			// Hari terakhir bulan tujuan
			const lastDayOfMonth = new Date(
				targetYear,
				targetMonth + 1,
				0
			).getDate();

			const finalDay = Math.min(day, lastDayOfMonth);

			const finalDate = new Date(
				targetYear,
				targetMonth,
				finalDay
			);

			const d = String(finalDate.getDate()).padStart(2, '0');
			const m = String(finalDate.getMonth() + 1).padStart(2, '0');
			const y = finalDate.getFullYear();

			return `${d}.${m}.${y}`;
		}

		// Auto hitung Best Before ketika kode produksi berubah
		document.querySelectorAll('input[name*="[kode_produksi]"]').forEach((input) => {

			input.addEventListener('input', function() {
				const kode = this.value.trim();
				const name = this.name;

				const match = name.match(
					/packing\[(\d+)\]\[(.*?)\]\[kode_produksi\]\[0\]/
				);

				if (!match) return;

				const col = match[1];
				const kategori = match[2];

				const bestBeforeInput = document.querySelector(
					`input[name="packing[${col}][${kategori}][best_before][0]"]`
				);

				if (bestBeforeInput) {
					bestBeforeInput.value = calculateBestBefore(kode);
				}
			});

			// Inisialisasi saat halaman dibuka
			const kode = input.value.trim();

			if (kode) {
				const match = input.name.match(
					/packing\[(\d+)\]\[(.*?)\]\[kode_produksi\]\[0\]/
				);

				if (match) {
					const col = match[1];
					const kategori = match[2];

					const bestBeforeInput = document.querySelector(
						`input[name="packing[${col}][${kategori}][best_before][0]"]`
					);

					if (bestBeforeInput) {
						bestBeforeInput.value = calculateBestBefore(kode);
					}
				}
			}
		});

		// Auto hitung lama aging
		function hitungLamaAging(jamMulai, jamSelesai) {
			if (!jamMulai || !jamSelesai) return '';

			const [mulaiJam, mulaiMenit] = jamMulai.split(':').map(Number);
			const [selesaiJam, selesaiMenit] = jamSelesai.split(':').map(Number);

			const mulai = new Date(0, 0, 0, mulaiJam, mulaiMenit);
			const selesai = new Date(0, 0, 0, selesaiJam, selesaiMenit);

			if (selesai < mulai) {
				selesai.setDate(selesai.getDate() + 1);
			}

			const diff = selesai - mulai;
			const jam = Math.floor(diff / (1000 * 60 * 60));
			const menit = Math.floor((diff / (1000 * 60)) % 60);

			return `${jam} jam${menit > 0 ? ' ' + menit + ' menit' : ''}`;
		}

		document.querySelectorAll('input[name*="[jam_mulai]"]').forEach((mulaiInput) => {

			mulaiInput.addEventListener('change', function() {
				const match = this.name.match(
					/packing\[(\d+)\]\[(.*?)\]\[jam_mulai\]\[0\]/
				);

				if (!match) return;

				const col = match[1];
				const kategori = match[2];

				const selesaiInput = document.querySelector(
					`input[name="packing[${col}][${kategori}][jam_selesai][0]"]`
				);

				const agingInput = document.querySelector(
					`input[name="packing[${col}][${kategori}][lama_aging][0]"]`
				);

				if (selesaiInput && agingInput) {
					agingInput.value = hitungLamaAging(
						this.value,
						selesaiInput.value
					);
				}
			});
		});

		document.querySelectorAll('input[name*="[jam_selesai]"]').forEach((selesaiInput) => {

			selesaiInput.addEventListener('change', function() {
				const match = this.name.match(
					/packing\[(\d+)\]\[(.*?)\]\[jam_selesai\]\[0\]/
				);

				if (!match) return;

				const col = match[1];
				const kategori = match[2];

				const mulaiInput = document.querySelector(
					`input[name="packing[${col}][${kategori}][jam_mulai][0]"]`
				);

				const agingInput = document.querySelector(
					`input[name="packing[${col}][${kategori}][lama_aging][0]"]`
				);

				if (mulaiInput && agingInput) {
					agingInput.value = hitungLamaAging(
						mulaiInput.value,
						this.value
					);
				}
			});
		});

	});
</script>
