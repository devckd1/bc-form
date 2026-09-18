<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">Edit Pemeriksaan Suhu Ruang</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('suhu') ?>"><i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Suhu Ruang</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<?php
			$plant_uuid = $this->session->userdata('plant');
			$plant_map = [
				'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Cikande',
				'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Salatiga'
			];
			$plant_name = isset($plant_map[$plant_uuid]) ? $plant_map[$plant_uuid] : 'Unknown';

			$lokasi_map = [
				'Cikande' => [
					'Ruang Produksi' => ['suhu' => '25 - 35', 'rh' => '65 - 80'],
					'Gudang Premix' => ['suhu' => '15 - 22', 'rh' => '45 - 55'],
					'Gudang Raw Material' => ['suhu' => '25 - 35', 'rh' => '60 - 75'],
					'Gudang Finish Good' => ['suhu' => '28 - 36', 'rh' => '60 - 75'],
					'Proofing Room' => ['suhu' => '34 - 36', 'rh' => '78 - 82'],
					'Aging Room 1' => ['suhu' => '35 - 45', 'rh' => '50 - 70'],
					'Aging Room 2' => ['suhu' => '35 - 45', 'rh' => '50 - 70'],
					'Ruang Produksi (Bubble)' => ['suhu' => '25 - 35', 'rh' => '65 - 80']
				],
				'Salatiga' => [
					'Ruang Pengayakan' => ['suhu' => '25 - 35', 'rh' => ''],
					'Ruang RM' => ['suhu' => '15 - 22', 'rh' => ''],
					'Chiller 1' => ['suhu' => '0 - 4', 'rh' => ''],
					'Chiller 2' => ['suhu' => '0 - 4', 'rh' => ''],
					'Chiller 3' => ['suhu' => '0 - 4', 'rh' => ''],
					'Chiller 4' => ['suhu' => '0 - 4', 'rh' => ''],
					'Chiller 5' => ['suhu' => '0 - 4', 'rh' => ''],
					'Chiller 6' => ['suhu' => '0 - 4', 'rh' => ''],
					'Ruang Mixing' => ['suhu' => '25 - 35', 'rh' => ''],
					'Area Baking' => ['suhu' => '25 - 35', 'rh' => ''],
					'Area Cutting & Grinding' => ['suhu' => '25 - 35', 'rh' => ''],
					'Ruang Aging' => ['suhu' => '35 - 45', 'rh' => '50 - 70'],
					'Area Packing' => ['suhu' => '25 - 35', 'rh' => '']
				]
			];

			$sensor_map = [
				'Ruang Produksi'          => 'RT0202',
				'Gudang Premix'           => 'RT0203',
				'Gudang Raw Material'     => 'RT0204',
				'Gudang Finish Good'      => 'RT0205',
				'Proofing Room'           => 'RT0206',
				'Aging Room 1'            => 'RT0207',
				'Aging Room 2'            => 'RT0208',
				'Ruang Produksi (Bubble)' => 'RT0209',
			];

			$lokasi_isi = json_decode($suhu->lokasi ?? '[]', true);
			$lokasi_data = [];
			foreach ($lokasi_isi as $row) {
				$lokasi_data[$row['nama_lokasi']] = $row;
			}
			?>

			<form method="post" action="<?= base_url('suhu/edit/' . $suhu->uuid); ?>">

				<div class="form-group row">
					<div class="col-md-4">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date" class="form-control" value="<?= set_value('date', $suhu->date) ?>">
					</div>
					<div class="col-md-4">
						<label class="font-weight-bold">Shift</label>
						<select name="shift" class="form-control">
							<option disabled>Pilih Shift</option>
							<option value="1" <?= set_select('shift', '1', $suhu->shift == '1') ?>>Shift 1</option>
							<option value="2" <?= set_select('shift', '2', $suhu->shift == '2') ?>>Shift 2</option>
							<option value="3" <?= set_select('shift', '3', $suhu->shift == '3') ?>>Shift 3</option>
						</select>
					</div>
					<div class="col-md-4">
						<label class="font-weight-bold">Pukul</label>
						<input type="time" name="pukul" class="form-control" value="<?= set_value('pukul', $suhu->pukul) ?>">
					</div>
				</div>

				<hr>
				<div class="row">

					<?php
					$total = count($lokasi_map[$plant_name]);
					$i = 0;

					foreach ($lokasi_map[$plant_name] as $nama_lokasi => $standar):

						$nilai = $lokasi_data[$nama_lokasi] ?? [
							'suhu' => '',
							'rh'   => ''
						];

						$is_last = ($i == $total - 1);

						$col = ($is_last && $total % 2 == 1)
							? 'col-lg-12'
							: 'col-lg-6';
					?>

						<div class="<?= $col ?> mb-3 sensor-item">

							<div class="card shadow-sm border-left-primary h-100">

								<div class="card-body py-3">

									<input
										type="hidden"
										name="lokasi[<?= $i ?>][nama_lokasi]"
										value="<?= $nama_lokasi ?>">

									<div class="d-flex justify-content-between align-items-center mb-2">

										<div class="font-weight-bold text-dark">
											<?= $nama_lokasi ?>
										</div>

										<?php if (isset($sensor_map[$nama_lokasi])): ?>

											<span
												class="badge badge-secondary status-sensor"
												data-sensor="<?= $sensor_map[$nama_lokasi] ?>">
												Connecting...
											</span>

										<?php endif; ?>

									</div>

									<label class="small font-weight-bold mb-1">
										Suhu
										<small class="text-muted">
											Standar (<?= $standar['suhu'] ?> °C)
										</small>
									</label>

									<div class="input-group mb-2">

										<input
											type="text"
											name="lokasi[<?= $i ?>][suhu]"
											class="form-control suhu-input"
											data-sensor="<?= $sensor_map[$nama_lokasi] ?? '' ?>"
											value="<?= set_value("lokasi[$i][suhu]", $nilai['suhu']) ?>"
											placeholder="Contoh: 28.5">

										<?php if (isset($sensor_map[$nama_lokasi])): ?>

											<div class="input-group-append">

												<button
													type="button"
													class="btn btn-outline-primary btn-refresh-suhu"
													data-toggle="tooltip"
													title="Sinkronisasi data sensor"
													data-sensor="<?= $sensor_map[$nama_lokasi] ?>">

													<i class="fas fa-sync-alt icon-refresh"></i>

												</button>

											</div>

										<?php endif; ?>

									</div>

									<?php if ($standar['rh']): ?>

										<label class="small font-weight-bold mb-1">
											RH
											<small class="text-muted">
												Standar (<?= $standar['rh'] ?> %)
											</small>
										</label>

										<input
											type="text"
											name="lokasi[<?= $i ?>][rh]"
											class="form-control"
											value="<?= set_value("lokasi[$i][rh]", $nilai['rh']) ?>"
											placeholder="Contoh: 70.2">

									<?php endif; ?>

								</div>

							</div>

						</div>

					<?php
						$i++;
					endforeach;
					?>

				</div>

				<div class="form-group">
					<label class="font-weight-bold">Catatan</label>

					<textarea
						name="catatan"
						class="form-control"
						rows="3"
						placeholder="Tambahkan catatan bila diperlukan..."><?= set_value('catatan', $suhu->catatan) ?></textarea>
				</div>

				<button type="submit" class="btn btn-success mr-2"><i class="fa fa-save"></i> Update</button>
				<a href="<?= base_url('suhu') ?>" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>

			</form>
		</div>
	</div>
</div>

<script>
	const WS_URL = "ws://10.68.1.220:1882/ws/temperature";
	const PLANT_CODE = "<?= $plant_name == 'Cikande' ? '07' : '04' ?>";

	let socket = null;
	let loadingSensor = {};

	// ======================================
	// LOAD SENSOR VIA API
	// ======================================

	function loadSensor(btn, input, status, sensor) {

		let icon = btn.find('.icon-refresh');

		if (icon.length) {
			icon.addClass('fa-spin');
			btn.prop('disabled', true);
		}

		input
			.val('⟳ Loading...')
			.prop('readonly', true);

		loadingSensor[sensor] = true;

		setTimeout(function() {

			$.ajax({

				url: "<?= base_url('iot/suhu') ?>",

				type: "GET",

				data: {
					sensor: sensor
				},

				dataType: "json",

				success: function(res) {

					setTimeout(function() {

						if (res.success) {

							input
								.val(res.suhu)
								.prop('readonly', false);

							status
								.removeClass('badge-secondary badge-danger')
								.addClass('badge-success')
								.text('Connected');

						} else {

							input
								.val('--')
								.prop('readonly', false);

							status
								.removeClass('badge-secondary badge-success')
								.addClass('badge-danger')
								.text('Disconnect');

						}

						loadingSensor[sensor] = false;

					}, 300);

				},

				error: function() {

					setTimeout(function() {

						input
							.val('--')
							.prop('readonly', false);

						status
							.removeClass('badge-secondary badge-success')
							.addClass('badge-danger')
							.text('Disconnect');

						loadingSensor[sensor] = false;

					}, 300);

				},

				complete: function() {

					input.prop('readonly', false);

					if (icon.length) {
						icon.removeClass('fa-spin');
						btn.prop('disabled', false);
					}

				}

			});

		}, 300);

	}

	// ======================================
	// REFRESH BUTTON
	// ======================================

	$(document).on('click', '.btn-refresh-suhu', function() {

		let btn = $(this);

		let col = btn.closest('.sensor-item');

		loadSensor(

			btn,

			col.find('.suhu-input'),

			col.find('.status-sensor'),

			btn.data('sensor')

		);

	});

	// ======================================
	// WEBSOCKET
	// ======================================

	function connectWebSocket() {

		if (socket !== null) {

			socket.close();

			socket = null;

		}

		socket = new WebSocket(WS_URL);

		socket.onopen = function() {

			console.log("WebSocket Connected");

			// Langsung ubah badge menjadi Connected
			$('.status-sensor')
				.removeClass('badge-secondary badge-danger')
				.addClass('badge-success')
				.text('Connected');

		};

		socket.onmessage = function(event) {

			let data;

			try {

				data = JSON.parse(event.data);

			} catch (e) {

				console.log("Invalid JSON");

				return;

			}

			if (data.plant_code && data.plant_code != PLANT_CODE) {
				return;
			}

			let sensorCode = data.sensor_code ?? data.sensor ?? '';

			let badge = $('.status-sensor[data-sensor="' + sensorCode + '"]');

			if (badge.length) {

				badge
					.removeClass('badge-secondary badge-danger')
					.addClass('badge-success')
					.text('Connected');

			}

			// Saat refresh manual jangan ditimpa websocket
			if (loadingSensor[sensorCode]) {
				return;
			}

			// Di halaman EDIT nilai suhu tidak diupdate otomatis
			// Hanya badge yang realtime

		};

		socket.onerror = function(error) {

			console.log("WebSocket Error", error);

		};

		socket.onclose = function() {

			console.log("WebSocket Disconnect");

			$('.status-sensor')
				.removeClass('badge-success badge-secondary')
				.addClass('badge-danger')
				.text('Disconnect');

			socket = null;

			setTimeout(connectWebSocket, 5000);

		};

	}

	// ======================================
	// PAGE READY
	// ======================================

	$(function() {

		$('[data-toggle="tooltip"]').tooltip();

		// EDIT:
		// Tidak auto refresh sensor.
		// Nilai lama tetap ditampilkan.

		connectWebSocket();

	});
</script>
<style>
	.breadcrumb {
		background: #006db6;
	}

	.card.border-left-primary {
		border-left: 4px solid #4e73df !important;
	}

	.card-body {
		padding: 1rem;
	}

	.status-sensor {
		font-size: 11px;
		padding: 5px 10px;
	}

	.btn-refresh-suhu {
		width: 42px;
	}

	.input-group {
		margin-bottom: .5rem;
	}

	label.small {
		margin-bottom: .25rem;
	}
</style>
