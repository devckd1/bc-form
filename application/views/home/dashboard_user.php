<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Dashboard QC SPV</title>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<style>
		body {
			background: #FFF5A5;
			font-family: 'Segoe UI', sans-serif;
		}

		.dashboard-wrapper {
			padding: 20px;
		}

		.section-gap {
			margin-bottom: 20px;
		}

		/* KPI */

		.kpi-card {
			background: #fff;
			border-radius: 28px;
			padding: 20px;
			height: 180px;
			box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
			position: relative;
		}

		.kpi-title {
			text-align: center;
			font-weight: 700;
			font-size: 14px;
			margin-bottom: 15px;
		}

		.kpi-grid {
			display: flex;
			justify-content: space-around;
			text-align: center;
		}

		.kpi-label {
			font-size: 13px;
			color: #666;
		}

		.kpi-value {
			font-size: 32px;
			font-weight: 700;
		}

		.min {
			color: #d32f2f;
		}

		.max {
			color: #2e7d32;
		}

		.avg {
			color: #222;
		}

		/* monitoring */

		.monitor-card {
			background: white;
			border-radius: 25px;
			padding: 15px;
			height: 320px;
			box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
		}

		.monitor-title {
			text-align: center;
			font-weight: 700;
			margin-bottom: 15px;
		}

		/* electrical */

		.electrical-card {
			background: white;
			border-radius: 25px;
			padding: 15px;
			height: 320px;
			box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
		}

		.electrical-grid {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 15px;
			margin-top: 20px;
		}

		.electrical-item {
			text-align: center;
		}

		.electrical-no {
			width: 40px;
			height: 40px;
			line-height: 40px;
			border: 2px solid #333;
			border-radius: 8px;
			margin: auto;
			margin-bottom: 10px;
			font-weight: bold;
		}

		.release {
			background: #52d44c;
			color: white;
			border-radius: 30px;
			padding: 8px;
			font-size: 12px;
			font-weight: bold;
		}

		.not-release {
			background: #ff4b4b;
			color: white;
			border-radius: 30px;
			padding: 8px;
			font-size: 12px;
			font-weight: bold;
		}

		/* md */

		.md-card {
			background: white;
			border-radius: 25px;
			padding: 20px;
			box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
			margin-bottom: 20px;
		}

		.md-title {
			text-align: center;
			font-weight: bold;
			margin-bottom: 20px;
		}

		.md-row {
			margin-bottom: 15px;
		}

		.md-bar {
			height: 22px;
			border-radius: 6px;
		}

		.bar-green {
			background: #69bf45;
		}

		.bar-dark {
			background: #3f8f2f;
		}

		/* rekap */

		.rekap-card {
			background: white;
			border-radius: 35px;
			padding: 25px;
			box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
		}

		.rekap-title {
			text-align: center;
			font-weight: bold;
			margin-bottom: 20px;
		}

		.rekap-item {
			display: flex;
			justify-content: space-between;
			margin-bottom: 15px;
		}

		.rekap-badge {
			background: #ffe0e0;
			padding: 5px 10px;
			border-radius: 5px;
			font-weight: bold;
		}

		/* breadcrumb */

		.breadcrumb-card {
			background: #efefef;
			border-radius: 25px;
			padding: 25px;
			height: 100%;
		}

		.breadcrumb-title {
			text-align: center;
			font-weight: bold;
			margin-bottom: 20px;
		}

		.chart-footer {
			text-align: center;
			margin-top: 15px;
			font-weight: bold;
		}

		canvas {
			max-width: 100%;
		}

		@media(max-width:991px) {

			.kpi-card {
				margin-bottom: 20px;
			}

			.monitor-card,
			.electrical-card {
				margin-bottom: 20px;
			}

		}

		.monitoring-wrapper {
			margin: 20px 0;
		}

		.monitoring-grid {
			display: grid;
			grid-template-columns: repeat(8, 1fr);
			gap: 8px;
		}

		.monitoring-card {
			background: #fff;
			border-radius: 10px;
			height: 75px;

			display: flex;
			align-items: center;
			justify-content: center;

			font-size: 11px;
			font-weight: 700;

			box-shadow: 0 1px 4px rgba(0, 0, 0, .12);

			transition: .2s;
		}

		.monitoring-card:hover {
			transform: translateY(-2px);
		}

		.kpi-icon {
			width: 42px;
			height: 42px;
			object-fit: contain;
			margin-right: 10px;
			flex-shrink: 0;
		}

		.kpi-product {
			font-size: 11px;
			color: #666;
			margin-top: 8px;
			line-height: 1.2;
		}

		.kpi-code {
			font-size: 10px;
			color: #777;
			line-height: 1.2;
		}

		.monitoring-grid {
			display: grid;
			grid-template-columns: repeat(8, 1fr);
			gap: 16px;
			margin: 25px 0;
		}

		.monitoring-card {
			border-radius: 32px;

			min-height: 205px;

			display: flex;
			flex-direction: column;

			align-items: center;
			justify-content: flex-start;

			padding: 12px 10px;

			transition: .2s;
			cursor: pointer;
		}

		.monitoring-card:hover {
			transform: translateY(-3px);
		}

		.monitoring-card img {
			width: 140px;
			height: 120px;
			object-fit: contain;
			margin-bottom: 12px;
		}

		.monitoring-card span {
			text-align: center;
			font-size: 18px;
			line-height: 1.1;
			font-weight: 400;
			color: #4b4b4b;
		}

		.monitor-card {
			background: white;
			border-radius: 15px;
			padding: 20px;
			height: 260px;
		}


		.monitor-title {
			text-align: center;
			font-size: 20px;
			font-weight: bold;
			color: #333;
			padding-bottom: 12px;
			border-bottom: 2px solid #ddd;
			margin-bottom: 15px;
		}


		.monitor-card canvas {
			max-height: 170px;
		}

		.monitor-card {
			background: #fff;
			border-radius: 16px;
			padding: 20px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
			height: 100%;
		}

		.monitor-title {
			font-weight: 700;
			font-size: 15px;
			margin-bottom: 15px;
			color: #444;
		}

		#keluhanChart {
			width: 100%;
			height: 250px !important;
		}
	</style>
</head>

<body>

	<div class="container-fluid dashboard-wrapper">
		<?php if ($kadar_air_min === null): ?>

			<div class="alert alert-warning">

				Belum ada data Mixing untuk tanggal

				<strong>
					<?= date('d-m-Y', strtotime($tanggal_dipilih)); ?>
				</strong>

			</div>

		<?php endif; ?>

		<form method="GET" class="mb-4">

			<div class="row align-items-end">

				<div class="col-md-3">

					<label>Pilih Tanggal</label>

					<input
						type="date"
						name="tanggal"
						class="form-control"
						value="<?= $tanggal_dipilih ?>">

				</div>

				<div class="col-md-2">

					<button class="btn btn-primary">
						Tampilkan
					</button>

				</div>

			</div>

		</form>

		<!-- KPI -->
		<div class="row section-gap">

			<div class="col-lg-3 mb-4">
				<div class="kpi-card">

					<div class="kpi-title">
						<img src="<?= base_url('assets/dashboard/icons/Kadar Air.png') ?>" class="kpi-icon">
						KADAR AIR FINISH GOOD (%)
					</div>

					<div class="kpi-grid">

						<div>

							<div class="kpi-label">Min</div>

							<div class="kpi-value min">
								<?= $kadar_air_min !== null ? number_format($kadar_air_min, 2) : '-' ?>
							</div>

							<div class="kpi-product">
								<?= $kadar_air_min_produk ?: '-' ?>
							</div>

							<div class="kpi-code">
								(<?= $kadar_air_min_kode ?: '-' ?>)
							</div>

						</div>

						<div>

							<div class="kpi-label">Max</div>

							<div class="kpi-value max">
								<?= $kadar_air_max !== null ? number_format($kadar_air_max, 2) : '-' ?>
							</div>

							<div class="kpi-product">
								<?= $kadar_air_max_produk ?: '-' ?>
							</div>

							<div class="kpi-code">
								(<?= $kadar_air_max_kode ?: '-' ?>)
							</div>

						</div>

						<div>

							<div class="kpi-label">Average</div>

							<div class="kpi-value avg">
								<?= $kadar_air_avg !== null ? number_format($kadar_air_avg, 2) : '-' ?>
							</div>

						</div>

					</div>

				</div>
			</div>

			<div class="col-lg-3 mb-4">
				<div class="kpi-card">

					<div class="kpi-title">
						<img src="<?= base_url('assets/dashboard/icons/Suhu Pusat.png') ?>" class="kpi-icon">
						SUHU PUSAT PRODUK (°C)
					</div>

					<div class="kpi-grid">

						<div>

							<div class="kpi-label">
								Min
							</div>

							<div class="kpi-value min">
								<?= $suhu_produk_min !== null ? number_format($suhu_produk_min, 2) : '-' ?>
							</div>

							<div class="kpi-product">
								<?= $suhu_produk_min_produk ?: '-' ?>
							</div>

							<div class="kpi-code">
								(<?= $suhu_produk_min_kode ?: '-' ?>)
							</div>

						</div>

						<div>

							<div class="kpi-label">Max</div>

							<div class="kpi-value max">
								<?= $suhu_produk_max !== null ? number_format($suhu_produk_max, 2) : '-' ?>
							</div>

							<div class="kpi-product">
								<?= $suhu_produk_max_produk ?: '-' ?>
							</div>

							<div class="kpi-code">
								(<?= $suhu_produk_max_kode ?: '-' ?>)
							</div>

						</div>

						<div>

							<div class="kpi-label">Average</div>

							<div class="kpi-value avg">
								<?= $suhu_produk_avg !== null ? number_format($suhu_produk_avg, 2) : '-' ?>
							</div>

						</div>

					</div>

				</div>
			</div>

			<div class="col-lg-3 mb-4">
				<div class="kpi-card">

					<div class="kpi-title">
						<img src="<?= base_url('assets/dashboard/icons/Sensori FG.png') ?>" class="kpi-icon">
						BULK DENSITY (g/L)
					</div>

					<div class="kpi-grid">

						<div>

							<div class="kpi-label">Min</div>

							<div class="kpi-value min">
								<?= $bulk_density_min !== null ? number_format($bulk_density_min, 2) : '-' ?>
							</div>

							<div class="kpi-product">
								<?= $bulk_density_min_produk ?: '-' ?>
							</div>

							<div class="kpi-code">
								(<?= $bulk_density_min_kode ?: '-' ?>)
							</div>

						</div>

						<div>

							<div class="kpi-label">Max</div>

							<div class="kpi-value max">
								<?= $bulk_density_max !== null ? number_format($bulk_density_max, 2) : '-' ?>
							</div>

							<div class="kpi-product">
								<?= $bulk_density_max_produk ?: '-' ?>
							</div>

							<div class="kpi-code">
								(<?= $bulk_density_max_kode ?: '-' ?>)
							</div>

						</div>

						<div>

							<div class="kpi-label">Average</div>

							<div class="kpi-value avg">
								<?= $bulk_density_avg !== null ? number_format($bulk_density_avg, 2) : '-' ?>
							</div>

						</div>

					</div>

				</div>
			</div>

			<div class="col-lg-3 mb-4">
				<div class="kpi-card">

					<div class="kpi-title">
						<img src="<?= base_url('assets/dashboard/icons/Roti Gosong.png') ?>" class="kpi-icon">
						TOTAL ROTI GOSONG (Kg)
					</div>

					<div class="kpi-grid">

						<?php
						$roti_shift = [
							'1A' => 0,
							'2B' => 0,
							'3C' => 0
						];

						if (!empty($roti_gosong)) {
							foreach ($roti_gosong as $row) {
								$roti_shift[$row->shift] = $row->total_kg;
							}
						}
						?>


						<div>
							<div class="kpi-label">
								Shift A
							</div>

							<div class="kpi-value min">
								<?= number_format($roti_shift['1A'], 2) ?>
							</div>
						</div>


						<div>
							<div class="kpi-label">
								Shift B
							</div>

							<div class="kpi-value min">
								<?= number_format($roti_shift['2B'], 2) ?>
							</div>
						</div>


						<div>
							<div class="kpi-label">
								Shift C
							</div>

							<div class="kpi-value min">
								<?= number_format($roti_shift['3C'], 2) ?>
							</div>
						</div>


					</div>

				</div>
			</div>

		</div>

		<div class="monitoring-wrapper">
			<div class="monitoring-grid">

				<a href="<?= base_url('sanitasi') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/icons/Pemeriksaan Sanitasi.png') ?>">
					<span>Pemeriksaan Sanitasi</span>
				</a>

				<a href="<?= base_url('kebersihankaryawan') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/icons/Kebersihan Karyawan.png') ?>">
					<span>Kebersihan Karyawan</span>
				</a>

				<a href="<?= base_url('kebersihanruang') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Kebersihan Ruang.png') ?>">
					<span>Kebersihan Ruang</span>
				</a>

				<a href="<?= base_url('thermometer') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Thermometer.png') ?>">
					<span>Pemeriksaan Thermometer</span>
				</a>

				<a href="<?= base_url('timbangan') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Timbangan.png') ?>">
					<span>Pemeriksaan Timbangan</span>
				</a>

				<!-- Khusus Salatiga -->
				<?php if ($this->session->userdata('plant') == '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>
					<a href="<?= base_url('inventaris') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/Checklist.png') ?>">
						<span>Checklist Inventaris</span>
					</a>

					<a href="<?= base_url('pecahbelah') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/Benda Pecah.png') ?>">
						<span>Pemeriksaan Benda Pecah</span>
					</a>
				<?php endif; ?>

				<a href="<?= base_url('suhu') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Suhu Ruang.png') ?>">
					<span>Pemeriksaan Suhu Ruang</span>
				</a>

				<!-- Khusus Cikande -->
				<?php if ($this->session->userdata('plant') == '651ac623-5e48-44cc-b2f6-5d622603f53c'): ?>
					<a href="<?= base_url('produksi') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/verifikasi Produksi.png') ?>">
						<span>Verifikasi Produksi</span>
					</a>
				<?php endif; ?>

				<!-- Khusus Salatiga -->
				<?php if ($this->session->userdata('plant') == '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>
					<a href="<?= base_url('proses') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/verifikasi Produksi.png') ?>">
						<span>Verifikasi Produksi</span>
					</a>
				<?php endif; ?>

				<a href="<?= base_url('metal') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Metal Detector.png') ?>">
					<span>Metal Detector</span>
				</a>

				<a href="<?= base_url('sensori') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Sensori FG.png') ?>">
					<span>Sensori Finish Good</span>
				</a>

				<a href="<?= base_url('verifikasimagnet') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Pemeriksaan Magnet.png') ?>">
					<span>Pemeriksaan Magnet</span>
				</a>

				<a href="<?= base_url('kontaminasi') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Icon (2).png') ?>">
					<span>Kontaminasi Benda Asing</span>
				</a>

				<a href="<?= base_url('retain') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Retain Sample.png') ?>">
					<span>Retain Sample</span>
				</a>

				<!-- Khusus Salatiga -->
				<?php if ($this->session->userdata('plant') == '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>
					<a href="<?= base_url('analisis') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/Sampel Lab.png') ?>">
						<span>Sampel Lab</span>
					</a>
				<?php endif; ?>

				<a href="<?= base_url('kondisikerja') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Kondisi Kerja.png') ?>">
					<span>Pemeriksaan Kondisi Kerja</span>
				</a>

				<!-- Khusus Salatiga -->
				<?php if ($this->session->userdata('plant') == '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>

					<a href="<?= base_url('residu') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/Residu Klorin.png') ?>">
						<span>Pemeriksaan Residu Klorin</span>
					</a>

					<a href="<?= base_url('reagen') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/Penggunaan Reagen.png') ?>">
						<span>Penggunaan Reagen Klorin</span>
					</a>

					<a href="<?= base_url('larutan') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/Larutan Cleaning.png') ?>">
						<span>Larutan Cleaning</span>
					</a>

				<?php endif; ?>

				<a href="<?= base_url('ketidaksesuaian') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Ketidaksesuaian Produk.png') ?>">
					<span>Produk Tidak Sesuai</span>
				</a>

				<a href="<?= base_url('sanitasi') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Sanitasi Perbaikan.png') ?>">
					<span>Sanitasi Perbaikan</span>
				</a>

				<a href="<?= base_url('kekuatanmagnet') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Kekuatan Magnet.png') ?>">
					<span>Kekuatan Magnet</span>
				</a>

				<!-- Khusus Salatiga -->
				<?php if ($this->session->userdata('plant') == '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>
					<a href="<?= base_url('gosong') ?>" class="monitoring-card">
						<img src="<?= base_url('assets/dashboard/Icons/Total Roti Gosong.png') ?>">
						<span>Total Roti Gosong</span>
					</a>
				<?php endif; ?>

				<a href="<?= base_url('pemusnahan') ?>" class="monitoring-card">
					<img src="<?= base_url('assets/dashboard/Icons/Pemusnahan Produk.png') ?>">
					<span>Pemusnahan Produk</span>
				</a>

			</div>
		</div>

		<!-- MONITORING -->
		<div class="row section-gap">

			<div class="col-lg-3 mb-4">
				<div class="monitor-card">
					<div class="monitor-title">
						KONTAMINASI
					</div>

					<canvas id="kontaminasiChart"></canvas>
				</div>
			</div>

			<div class="col-lg-3 mb-4">
				<div class="monitor-card">
					<div class="monitor-title">
						KELUHAN PELANGGAN
					</div>

					<canvas id="keluhanChart"></canvas>
				</div>
			</div>

			<div class="col-lg-3 mb-4">
				<div class="monitor-card">

					<div class="monitor-title">
						KETIDAKSESUAIAN MD
					</div>

					<canvas id="mdChart"></canvas>

				</div>
			</div>

			<div class="col-lg-3 mb-4">

				<div class="electrical-card">

					<div class="monitor-title">
						ELECTRICAL BAKING
					</div>

					<div class="electrical-grid">

						<div class="electrical-item">
							<div class="electrical-no">1</div>
							<div class="not-release">NOT RELEASE</div>
						</div>

						<div class="electrical-item">
							<div class="electrical-no">2</div>
							<div class="not-release">NOT RELEASE</div>
						</div>

						<div class="electrical-item">
							<div class="electrical-no">3</div>
							<div class="release">RELEASE</div>
						</div>

						<div class="electrical-item">
							<div class="electrical-no">4</div>
							<div class="release">RELEASE</div>
						</div>

					</div>

				</div>

			</div>

		</div>

		<!-- BOTTOM -->
		<div class="row">

			<div class="col-lg-3">

				<div class="rekap-card">

					<div class="rekap-title">
						REKAP KETIDAKSESUAIAN PRODUK
					</div>

					<div class="rekap-item">
						<span>Juli</span>
						<span class="rekap-badge">0.00%</span>
					</div>

					<div class="rekap-item">
						<span>Agustus</span>
						<span class="rekap-badge">2.12%</span>
					</div>

					<div class="rekap-item">
						<span>September</span>
						<span class="rekap-badge">0.00%</span>
					</div>

					<div class="rekap-item">
						<span>Oktober</span>
						<span class="rekap-badge">1.85%</span>
					</div>

					<div class="rekap-item">
						<span>November</span>
						<span class="rekap-badge">0.74%</span>
					</div>

					<div class="rekap-item">
						<span>Desember</span>
						<span class="rekap-badge">0.00%</span>
					</div>

					<div class="text-center mt-4">
						<button class="btn btn-outline-secondary btn-sm">
							◀
						</button>

						<b class="mx-3">
							Kadar Air
						</b>

						<button class="btn btn-outline-secondary btn-sm">
							▶
						</button>
					</div>

				</div>

			</div>

			<div class="col-lg-9">

				<div class="breadcrumb-card">

					<div class="breadcrumb-title">
						REKAP SUHU RUANG BREADCRUMB
					</div>

					<div style="height:520px;">
						<canvas id="breadcrumbChart"></canvas>
					</div>

					<div class="chart-footer">

						<button id="btnPrev" class="btn btn-outline-secondary btn-sm">
							◀
						</button>

						<span id="namaLokasi" class="mx-3">
							Ruang Produksi
						</span>

						<button id="btnNext" class="btn btn-outline-secondary btn-sm">
							▶
						</button>

					</div>

				</div>

			</div>

			<!-- grafana -->
			<!-- <div class="col-lg-9">

				<div class="breadcrumb-card">

					<div class="breadcrumb-title">
						REKAP SUHU RUANG BREADCRUMB
					</div>

					<iframe
						src="<?= $grafana_suhu ?>"
						width="100%"
						height="520"
						frameborder="0"
						style="border:0;">
					</iframe>

				</div>

			</div> -->

		</div>

	</div>


</body>

<script>
	const suhuData = <?= json_encode($suhu_data); ?>;

	// Urutkan berdasarkan jam
	suhuData.sort((a, b) => a.pukul.localeCompare(b.pukul));

	// Ambil semua nama lokasi
	const lokasiList = suhuData.length ?
		suhuData[0].lokasi.map(item => item.nama_lokasi) : [];

	const colors = [
		'#2E86C1',
		'#28B463',
		'#E67E22',
		'#8E44AD',
		'#16A085',
		'#C0392B',
		'#D4AC0D',
		'#5D6D7E'
	];

	let lokasiIndex = 0;
	let chart = null;

	function renderChart() {

		const lokasiAktif = lokasiList[lokasiIndex];

		const labels = [];
		const values = [];

		// Ambil hanya data yang memiliki suhu
		suhuData.forEach(item => {

			const dataLokasi = item.lokasi.find(x => x.nama_lokasi === lokasiAktif);

			if (dataLokasi && dataLokasi.suhu !== '-' && dataLokasi.suhu !== '') {

				labels.push(item.pukul.substring(0, 5));
				values.push(parseFloat(dataLokasi.suhu));

			}

		});

		document.getElementById('namaLokasi').innerHTML = lokasiAktif;

		const datasets = lokasiList.map((nama, index) => {

			if (nama === lokasiAktif) {

				return {

					label: nama,
					data: values,
					borderColor: colors[index % colors.length],
					backgroundColor: colors[index % colors.length],
					borderWidth: 3,
					pointRadius: 4,
					pointHoverRadius: 6,
					tension: 0.35,
					fill: false

				};

			}

			return {

				label: nama,
				data: [],
				borderColor: '#d0d0d0',
				backgroundColor: '#d0d0d0',
				borderWidth: 2

			};

		});

		if (chart) {
			chart.destroy();
		}

		chart = new Chart(document.getElementById('breadcrumbChart'), {

			type: 'line',

			data: {
				labels: labels,
				datasets: datasets
			},

			options: {

				responsive: true,
				maintainAspectRatio: false,

				interaction: {
					mode: 'index',
					intersect: false
				},

				plugins: {

					legend: {
						position: 'bottom',
						labels: {
							usePointStyle: true,
							boxWidth: 12
						}
					}

				},

				scales: {

					x: {
						title: {
							display: true,
							text: 'Jam'
						}
					},

					y: {
						min: 20,
						max: 50,
						ticks: {
							stepSize: 5
						},
						title: {
							display: true,
							text: 'Suhu (°C)'
						}
					}

				}

			}

		});

	}

	document.getElementById('btnPrev').onclick = function() {

		lokasiIndex--;

		if (lokasiIndex < 0) {
			lokasiIndex = lokasiList.length - 1;
		}

		renderChart();

	}

	document.getElementById('btnNext').onclick = function() {

		lokasiIndex++;

		if (lokasiIndex >= lokasiList.length) {
			lokasiIndex = 0;
		}

		renderChart();

	}

	renderChart();

	const kontaminasiData = <?= json_encode($kontaminasi_chart); ?>;


	new Chart(
		document.getElementById('kontaminasiChart'), {
			type: 'doughnut',

			data: {

				labels: kontaminasiData.map(
					item => item.jenis_kontaminasi
				),

				datasets: [{

					data: kontaminasiData.map(
						item => item.jumlah
					),

					borderWidth: 0

				}]

			},

			options: {

				responsive: true,

				cutout: '55%',


				plugins: {

					legend: {
						position: 'right',

						labels: {
							usePointStyle: true,
							boxWidth: 8
						}
					},


					tooltip: {

						callbacks: {

							label: function(context) {

								let total = context.dataset.data
									.reduce((a, b) => a + b, 0);

								let value = context.raw;

								let persen = (
									value / total * 100
								).toFixed(1);


								return value +
									' (' + persen + '%)';

							}

						}

					}

				}

			}

		}
	);

	const mdData = <?= json_encode($ketidaksesuaian_md); ?>;

	new Chart(document.getElementById('mdChart'), {

		type: 'bar',

		data: {

			labels: mdData.map(item => item.shift),

			datasets: [{

				data: mdData.map(item => item.jumlah),

				borderWidth: 0,

				borderRadius: 5,

				backgroundColor: [
					'#7ED957',
					'#43A31A',
					'#2F7F0F'
				]

			}]

		},

		options: {

			indexAxis: 'y',

			responsive: true,

			maintainAspectRatio: false,

			plugins: {

				legend: {
					display: false
				}

			},

			scales: {

				x: {

					beginAtZero: true,

					grid: {
						display: false
					}

				},

				y: {

					grid: {
						display: false
					}

				}

			}

		}

	});

	const ctxKeluhan = document.getElementById('keluhanChart');

	new Chart(ctxKeluhan, {
		type: 'doughnut',
		data: {
			labels: [
				'Rasa',
				'Kemasan',
				'Tekstur',
				'Warna',
				'Lainnya'
			],
			datasets: [{
				data: [8, 5, 3, 2, 1],
				backgroundColor: [
					'#FF6384',
					'#36A2EB',
					'#FFCE56',
					'#4BC0C0',
					'#9966FF'
				],
				borderWidth: 1
			}]
		},
		options: {

			responsive: true,
			maintainAspectRatio: false,

			plugins: {

				legend: {
					position: 'bottom',
					labels: {
						boxWidth: 12,
						font: {
							size: 11
						}
					}
				},

				tooltip: {
					callbacks: {
						label: function(context) {
							return context.label + ' : ' + context.parsed;
						}
					}
				}

			}

		}
	});
</script>

</html>
