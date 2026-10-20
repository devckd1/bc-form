<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Edit Pemeriksaan Suhu Ruang

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('suhu/audit') ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Pemeriksaan Suhu Ruang
				</a>
			</li>

			<li class="breadcrumb-item active" aria-current="page">
				Edit Audit
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>

		<strong>Informasi:</strong>

		Halaman ini merupakan menu Edit Audit Pemeriksaan Suhu Ruang.
		Perubahan data pada halaman ini tidak akan mengubah data aktual
		pada menu pemeriksaan.
	</div>


	<div class="card shadow mb-4 card-audit">

		<div class="card-body">

			<?php

			/*
             * ============================================================
             * PLANT AKTIF
             * ============================================================
             */

			$plant_uuid = $this->session->userdata('plant');

			$plant_map = [
				'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Cikande',
				'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Salatiga'
			];

			$plant_name = isset($plant_map[$plant_uuid])
				? $plant_map[$plant_uuid]
				: 'Unknown';


			/*
             * ============================================================
             * LOKASI TETAP
             * ============================================================
             *
             * Lokasi dan urutan tetap menggunakan lokasi_map.
             *
             * Master Suhu hanya digunakan sebagai sumber standar.
             */

			$lokasi_map = [

				'Cikande' => [

					'Ruang Produksi' => [
						'suhu' => '25 - 35',
						'rh'   => '65 - 80'
					],

					'Gudang Premix' => [
						'suhu' => '15 - 22',
						'rh'   => '45 - 55'
					],

					'Gudang Raw Material' => [
						'suhu' => '25 - 35',
						'rh'   => '60 - 75'
					],

					'Gudang Finish Good' => [
						'suhu' => '28 - 36',
						'rh'   => '60 - 75'
					],

					'Proofing Room' => [
						'suhu' => '34 - 36',
						'rh'   => '78 - 82'
					],

					'Aging Room 1' => [
						'suhu' => '35 - 45',
						'rh'   => '50 - 70'
					],

					'Aging Room 2' => [
						'suhu' => '35 - 45',
						'rh'   => '50 - 70'
					],

					'Area Packing' => [
						'suhu' => '25 - 35',
						'rh'   => ''
					],

					'Ruang Produksi (Bubble)' => [
						'suhu' => '25 - 35',
						'rh'   => '65 - 80'
					]

				],

				'Salatiga' => [

					'Ruang Pengayakan' => [
						'suhu' => '25 - 35',
						'rh'   => ''
					],

					'Ruang RM' => [
						'suhu' => '15 - 22',
						'rh'   => ''
					],

					'Chiller 1' => [
						'suhu' => '0 - 4',
						'rh'   => ''
					],

					'Chiller 2' => [
						'suhu' => '0 - 4',
						'rh'   => ''
					],

					'Chiller 3' => [
						'suhu' => '0 - 4',
						'rh'   => ''
					],

					'Chiller 4' => [
						'suhu' => '0 - 4',
						'rh'   => ''
					],

					'Chiller 5' => [
						'suhu' => '0 - 4',
						'rh'   => ''
					],

					'Chiller 6' => [
						'suhu' => '0 - 4',
						'rh'   => ''
					],

					'Ruang Mixing' => [
						'suhu' => '25 - 35',
						'rh'   => ''
					],

					'Area Baking' => [
						'suhu' => '25 - 35',
						'rh'   => ''
					],

					'Area Cutting & Grinding' => [
						'suhu' => '25 - 35',
						'rh'   => ''
					],

					'Ruang Aging' => [
						'suhu' => '35 - 45',
						'rh'   => '50 - 70'
					],

					'Area Packing' => [
						'suhu' => '25 - 35',
						'rh'   => ''
					]

				]

			];


			/*
             * ============================================================
             * AMBIL MASTER SUHU
             * ============================================================
             */

			$master_suhu = $this->db
				->where('plant', $plant_uuid)
				->where('is_active', 1)
				->get('master_suhu')
				->result();


			/*
             * ============================================================
             * MAPPING MASTER BERDASARKAN NAMA LOKASI
             * ============================================================
             */

			$master_map = [];

			foreach ($master_suhu as $master) {

				$master_map[$master->nama_lokasi] = $master;
			}


			/*
             * ============================================================
             * AMBIL DATA LOKASI DARI DATA ASLI
             * ============================================================
             *
             * Data lokasi diasumsikan tersimpan dalam field:
             *
             * $suhu->lokasi
             *
             * dalam format JSON.
             */

			$data_lokasi = [];

			if (
				isset($suhu->lokasi) &&
				$suhu->lokasi !== null &&
				trim((string) $suhu->lokasi) !== ''
			) {

				$decoded_lokasi = json_decode(
					$suhu->lokasi,
					true
				);

				if (is_array($decoded_lokasi)) {

					$data_lokasi = $decoded_lokasi;
				}
			}


			/*
             * ============================================================
             * FUNGSI MENGAMBIL NILAI TENGAH
             * ============================================================
             *
             * Contoh:
             *
             * 25 - 35 = 30
             * 65 - 80 = 72.5
             * 15 - 22 = 18.5
             * 0 - 4   = 2
             * 35 - 45 = 40
             */

			$nilai_tengah = function ($range) {

				if (
					$range === null ||
					trim((string) $range) === ''
				) {

					return '';
				}


				$range = trim((string) $range);


				/*
                 * Hilangkan satuan
                 */

				$range = str_replace(
					['°C', '%'],
					'',
					$range
				);


				/*
                 * Rapikan spasi
                 */

				$range = preg_replace(
					'/\s+/',
					' ',
					$range
				);

				$range = trim($range);


				/*
                 * Format range:
                 *
                 * 25 - 35
                 * 65 - 80
                 * 15 - 22
                 * 0 - 4
                 */

				if (
					preg_match(
						'/^(-?\d+(?:\.\d+)?)\s*-\s*(-?\d+(?:\.\d+)?)$/',
						$range,
						$match
					)
				) {

					$min = (float) $match[1];

					$max = (float) $match[2];

					return ($min + $max) / 2;
				}


				/*
                 * Jika Master berisi angka langsung
                 */

				if (is_numeric($range)) {

					return (float) $range;
				}


				return '';
			};

			?>


			<form
				method="post"
				action="<?= base_url('suhu/edit-audit/' . $suhu->uuid); ?>">


				<!-- =====================================================
                     TANGGAL / SHIFT / PUKUL
                ====================================================== -->

				<div class="form-group row">

					<div class="col-md-4">

						<label class="font-weight-bold">
							Tanggal
						</label>

						<input
							type="date"
							name="date"
							class="form-control"
							value="<?= set_value('date', $suhu->date) ?>">

					</div>


					<div class="col-md-4">

						<label class="font-weight-bold">
							Shift
						</label>

						<select
							name="shift"
							class="form-control">

							<option disabled>
								Pilih Shift
							</option>

							<option
								value="1"
								<?= set_select(
									'shift',
									'1',
									$suhu->shift == '1'
								) ?>>

								Shift 1

							</option>

							<option
								value="2"
								<?= set_select(
									'shift',
									'2',
									$suhu->shift == '2'
								) ?>>

								Shift 2

							</option>

							<option
								value="3"
								<?= set_select(
									'shift',
									'3',
									$suhu->shift == '3'
								) ?>>

								Shift 3

							</option>

						</select>

					</div>


					<div class="col-md-4">

						<label class="font-weight-bold">
							Pukul
						</label>

						<input
							type="time"
							name="pukul"
							class="form-control"
							value="<?= set_value('pukul', $suhu->pukul) ?>">

					</div>

				</div>


				<hr>


				<!-- =====================================================
                     SUHU & RH
                ====================================================== -->

				<h5 class="mb-3 font-weight-bold text-primary">
					Update Suhu & RH Tiap Lokasi
				</h5>


				<?php

				$i = 0;

				/*
                 * Pastikan plant mempunyai mapping lokasi
                 */

				$lokasi_aktif = isset($lokasi_map[$plant_name])
					? $lokasi_map[$plant_name]
					: [];


				foreach (
					$lokasi_aktif
					as $nama_lokasi => $standar
				):


					/*
                     * ==================================================
                     * CARI MASTER BERDASARKAN NAMA LOKASI
                     * ==================================================
                     */

					$master = $master_map[$nama_lokasi] ?? null;


					/*
                     * Default kosong
                     */

					$isi_suhu = '';

					$isi_rh = '';


					/*
                     * ==================================================
                     * CARI DATA ASLI LOKASI
                     * ==================================================
                     */

					$data_asli = null;


					foreach (
						$data_lokasi
						as $item
					) {

						if (
							isset($item['nama_lokasi']) &&
							$item['nama_lokasi'] === $nama_lokasi
						) {

							$data_asli = $item;

							break;
						}
					}


					/*
                     * ==================================================
                     * ISI DARI MASTER HANYA JIKA DATA ASLI TERISI
                     * ==================================================
                     *
                     * Jika data asli kosong:
                     *
                     *     Audit tetap kosong.
                     *
                     * Jika data asli memiliki nilai:
                     *
                     *     Audit mengambil nilai tengah Master.
                     */

					/*
 * ============================================================
 * LOGIKA NILAI AUDIT
 * ============================================================
 *
 * 1. Data asli ada + Master ada
 *    → gunakan nilai tengah Master
 *
 * 2. Data asli ada + Master tidak ada
 *    → gunakan nilai asli
 *
 * 3. Data asli kosong
 *    → tetap kosong
 */

					if ($data_asli) {

						/*
     * ========================================================
     * SUHU
     * ========================================================
     */

						if (
							isset($data_asli['suhu']) &&
							$data_asli['suhu'] !== null &&
							trim((string) $data_asli['suhu']) !== ''
						) {

							if (
								$master &&
								isset($master->standar_suhu) &&
								trim((string) $master->standar_suhu) !== ''
							) {

								// Ada Master → gunakan nilai tengah Master
								$isi_suhu = $nilai_tengah(
									$master->standar_suhu
								);
							} else {

								// Tidak ada Master → gunakan nilai asli
								$isi_suhu = $data_asli['suhu'];
							}
						}


						/*
     * ========================================================
     * RH
     * ========================================================
     */

						if (
							isset($data_asli['rh']) &&
							$data_asli['rh'] !== null &&
							trim((string) $data_asli['rh']) !== ''
						) {

							if (
								$master &&
								isset($master->standar_rh) &&
								trim((string) $master->standar_rh) !== ''
							) {

								// Ada Master → gunakan nilai tengah Master
								$isi_rh = $nilai_tengah(
									$master->standar_rh
								);
							} else {

								// Tidak ada Master → gunakan nilai asli
								$isi_rh = $data_asli['rh'];
							}
						}
					}

				?>


					<div class="form-group row align-items-end mb-3">


						<!-- =================================================
                             NAMA LOKASI
                        ================================================== -->

						<input
							type="hidden"
							name="lokasi[<?= $i ?>][nama_lokasi]"
							value="<?= html_escape($nama_lokasi) ?>">


						<!-- =================================================
                             SUHU
                        ================================================== -->

						<div class="col-md-<?= $standar['rh'] ? '5 offset-md-1' : '10 offset-md-1' ?>">

							<label class="font-weight-bold">

								<?= html_escape($nama_lokasi) ?>

								- Suhu

								<small>
									(<?= html_escape($standar['suhu']) ?> °C)
								</small>

							</label>


							<input
								type="text"
								name="lokasi[<?= $i ?>][suhu]"
								class="form-control"
								value="<?= html_escape($isi_suhu) ?>">

						</div>


						<!-- =================================================
                             RH
                        ================================================== -->

						<?php if ($standar['rh']): ?>

							<div class="col-md-5">

								<label class="font-weight-bold">

									<?= html_escape($nama_lokasi) ?>

									- RH

									<small>
										(<?= html_escape($standar['rh']) ?> %)
									</small>

								</label>


								<input
									type="text"
									name="lokasi[<?= $i ?>][rh]"
									class="form-control"
									value="<?= html_escape($isi_rh) ?>">

							</div>

						<?php endif; ?>


					</div>


				<?php

					$i++;

				endforeach;

				?>


				<hr>


				<!-- =====================================================
                     CATATAN
                ====================================================== -->

				<div class="form-group">

					<label class="font-weight-bold">
						Catatan
					</label>

					<textarea
						name="catatan"
						class="form-control"><?= set_value(
													'catatan',
													$suhu->catatan
												) ?></textarea>

				</div>


				<!-- =====================================================
                     BUTTON
                ====================================================== -->

				<button
					type="submit"
					class="btn btn-success mr-2">

					<i class="fa fa-save"></i>

					Update

				</button>


				<a
					href="<?= base_url('suhu') ?>"
					class="btn btn-danger">

					<i class="fa fa-times"></i>

					Batal

				</a>


			</form>

		</div>

	</div>

</div>


<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/*
     * Label Mode Audit
     */

	.badge-mode-audit {

		display: inline-block;

		background-color: #5a3298;

		color: #fff;

		padding: 7px 14px;

		margin-left: 8px;

		border-radius: 20px;

		font-size: 12px;

		font-weight: 700;

		letter-spacing: .5px;

		vertical-align: middle;

		box-shadow:
			0 3px 8px rgba(90, 50, 152, 0.35);

	}
</style>
