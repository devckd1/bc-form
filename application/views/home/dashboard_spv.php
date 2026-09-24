<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Dashboard QC SPV</title>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

	<div class="container-fluid dashboard-wrapper">
		<?php
		$shift = $this->input->get('shift');

		if ($shift === null || $shift === '') {
			$shift = 'All';
		}
		?>

		<div class="container-fluid dashboard-wrapper">
			<!-- =====================================================
				FILTER RANGE TANGGAL
			===================================================== -->
			<div class="card shadow-sm mb-4"
				style="border:0; border-radius:15px;">

				<div class="card-body">

					<form method="GET" action="<?= current_url(); ?>">

						<div class="row align-items-end">

							<!-- DARI TANGGAL -->
							<div class="col-md-3 mb-3 mb-md-0">

								<label class="font-weight-bold">
									<i class="fas fa-calendar-alt mr-1"></i>
									Dari Tanggal
								</label>

								<input
									type="date"
									name="from_date"
									class="form-control"
									value="<?= htmlspecialchars($from_date); ?>"
									required>

							</div>


							<!-- SAMPAI TANGGAL -->
							<div class="col-md-3 mb-3 mb-md-0">

								<label class="font-weight-bold">
									<i class="fas fa-calendar-alt mr-1"></i>
									Sampai Tanggal
								</label>

								<input
									type="date"
									name="to_date"
									class="form-control"
									value="<?= htmlspecialchars($to_date); ?>"
									required>

							</div>


							<!-- SHIFT -->
							<div class="col-md-2 mb-3 mb-md-0">

								<label class="font-weight-bold">
									<i class="fas fa-clock mr-1"></i>
									Shift
								</label>

								<select name="shift" class="form-control">

									<option value="All" <?= $shift === 'All' ? 'selected' : ''; ?>>
										Semua Shift
									</option>

									<option value="1" <?= $shift === '1' ? 'selected' : ''; ?>>
										Shift 1
									</option>

									<option value="2" <?= $shift === '2' ? 'selected' : ''; ?>>
										Shift 2
									</option>

									<option value="3" <?= $shift === '3' ? 'selected' : ''; ?>>
										Shift 3
									</option>

								</select>

							</div>


							<!-- TAMPILKAN -->
							<div class="col-md-2 mb-3 mb-md-0">

								<button
									type="submit"
									class="btn btn-primary btn-block">

									<i class="fas fa-filter mr-1"></i>
									Tampilkan

								</button>

							</div>


							<!-- RESET -->
							<div class="col-md-2">

								<a
									href="<?= current_url(); ?>"
									class="btn btn-light btn-block">

									<i class="fas fa-sync-alt mr-1"></i>
									Reset

								</a>

							</div>

						</div>

					</form>

				</div>

			</div>

			<!-- =====================================================
				KPI
			===================================================== -->
			<div class="row section-gap">

				<!-- KADAR AIR -->
				<div class="col-lg-4 mb-4">
					<div class="kpi-card grafana-kpi-card">

						<div class="kpi-title">
							<img
								src="<?= base_url('assets/dashboard/icons/Kadar Air.png') ?>"
								class="kpi-icon">
							KADAR AIR FINISH GOOD (%)
						</div>

						<div class="grafana-kpi-wrapper">
							<iframe
								src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&panelId=panel-21&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&kiosk=true&hideLogo=true&fullscreen=true&theme=light&transparent=true&timezone=browser&refresh=1m"
								loading="lazy">
							</iframe>
						</div>
						<div class="grafana-kpi-menu-cover"></div>
					</div>
				</div>


				<!-- SUHU PUSAT -->
				<div class="col-lg-4 mb-4">
					<div class="kpi-card grafana-kpi-card">

						<div class="kpi-title">
							<img
								src="<?= base_url('assets/dashboard/icons/Suhu Pusat.png') ?>"
								class="kpi-icon">
							SUHU PUSAT PRODUK (°C)
						</div>

						<div class="grafana-kpi-wrapper">
							<iframe
								src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&panelId=panel-22&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&kiosk=true&hideLogo=true&fullscreen=true&theme=light&transparent=true&timezone=browser&refresh=1m"
								loading="lazy">
							</iframe>
						</div>
						<div class="grafana-kpi-menu-cover"></div>
					</div>
				</div>


				<!-- BULK DENSITY -->
				<div class="col-lg-4 mb-4">
					<div class="kpi-card grafana-kpi-card">

						<div class="kpi-title">
							<img
								src="<?= base_url('assets/dashboard/icons/Sensori FG.png') ?>"
								class="kpi-icon">
							BULK DENSITY (g/L)
						</div>

						<div class="grafana-kpi-wrapper">
							<iframe
								src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&panelId=panel-23&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&kiosk=true&hideLogo=true&fullscreen=true&theme=light&transparent=true&timezone=browser&refresh=1m"
								loading="lazy">
							</iframe>
						</div>

						<div class="grafana-kpi-menu-cover"></div>

					</div>
				</div>

			</div>


			<!-- =====================================================
				MONITORING
			===================================================== -->
			<div class="row section-gap">

				<!-- ROTI GOSONG -->
				<?php if ($plant_uuid !== '651ac623-5e48-44cc-b2f6-5d622603f53c'): ?>

					<div class="col-lg-4 mb-4">
						<div class="monitor-card">

							<div class="monitor-title">
								<img
									src="<?= base_url('assets/dashboard/icons/Roti Gosong.png') ?>"
									class="kpi-icon">
								TOTAL ROTI GOSONG (Kg)
							</div>

							<div class="grafana-wrapper">
								<iframe
									src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&panelId=panel-24&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&kiosk=true&hideLogo=true&fullscreen=true&theme=light&transparent=true&timezone=browser&refresh=1m"
									loading="lazy">
								</iframe>
							</div>

							<div class="grafana-monitor-menu-cover"></div>

						</div>
					</div>

				<?php else: ?>

					<!-- KONTAMINASI MENGISI SLOT ROTI GOSONG -->
					<div class="col-lg-4 mb-4">
						<div class="monitor-card">

							<div class="monitor-title">
								KONTAMINASI
							</div>

							<div class="grafana-wrapper">
								<iframe
									src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&panelId=panel-27&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&kiosk=true&hideLogo=true&fullscreen=true&theme=light&transparent=true&timezone=browser&refresh=1m"
									loading="lazy">
								</iframe>

								<div class="kontaminasi-menu-cover"></div>
							</div>

						</div>
					</div>

				<?php endif; ?>

				<!-- KETIDAKSESUAIAN MD -->
				<div class="col-lg-4 mb-4">
					<div class="monitor-card">

						<div class="monitor-title">
							KETIDAKSESUAIAN MD
						</div>

						<div class="grafana-wrapper">
							<iframe
								src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&timezone=browser&refresh=1m&theme=light&panelId=panel-19&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&kiosk=true&hideLogo=true&fullscreen=true&transparent=true"
								loading="lazy">
							</iframe>
						</div>

						<div class="grafana-monitor-menu-cover"></div>

					</div>
				</div>

				<!-- ELECTRICAL BAKING -->
				<div class="col-lg-4 mb-4">
					<div class="monitor-card">

						<div class="monitor-title">
							ELECTRICAL BAKING
						</div>

						<?php if ($plant_uuid === '651ac623-5e48-44cc-b2f6-5d622603f53c'): ?>

							<!-- CIKANDE : BELUM TERSEDIA -->
							<div class="monitor-no-data">
								<div class="monitor-no-data-icon">
									<i class="fas fa-info-circle"></i>
								</div>

								<div class="monitor-no-data-title">
									Data belum tersedia
								</div>

								<div class="monitor-no-data-text">
									Monitoring Electrical Baking<br>
									belum tersedia untuk Plant Cikande
								</div>
							</div>

						<?php else: ?>

							<!-- SALATIGA : GRAFANA -->
							<div class="grafana-wrapper">
								<iframe
									src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&panelId=20&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&kiosk=true&hideLogo=true&fullscreen=true&theme=light&transparent=true&timezone=browser&refresh=1m"
									loading="lazy">
								</iframe>
							</div>

							<div class="grafana-monitor-menu-cover"></div>

						<?php endif; ?>

					</div>
				</div>

			</div>


			<!-- =====================================================
    KONTAMINASI + REKAP SUHU
===================================================== -->
			<div class="row">

				<?php if ($plant_uuid !== '651ac623-5e48-44cc-b2f6-5d622603f53c'): ?>

					<!-- KONTAMINASI - SALATIGA -->
					<div class="col-lg-4 mb-4 d-flex">
						<div class="monitor-card kontaminasi-card">

							<div class="monitor-title">
								KONTAMINASI
							</div>

							<div class="grafana-wrapper">

								<iframe
									src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&panelId=panel-27&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&kiosk=true&hideLogo=true&fullscreen=true&theme=light&transparent=true&timezone=browser&refresh=1m"
									loading="lazy">
								</iframe>

								<div class="kontaminasi-menu-cover"></div>

							</div>

						</div>
					</div>

				<?php endif; ?>


				<!-- REKAP SUHU -->
				<div class="<?= ($plant_uuid === '651ac623-5e48-44cc-b2f6-5d622603f53c') ? 'col-lg-12' : 'col-lg-8' ?> mb-4 d-flex">
					<div class="breadcrumb-card">

						<div class="breadcrumb-title">
							REKAP SUHU RUANG BREADCRUMB
						</div>

						<div class="grafana-breadcrumb-wrapper">
							<iframe
								src="http://10.71.3.27:3001/d-solo/adpn5jz/paperless-bc?orgId=1&timezone=browser&refresh=1m&theme=light&panelId=panel-2&var-id_plant=<?= urlencode($plant_uuid) ?>&var-Shift=<?= urlencode($shift) ?>&from=<?= urlencode($from_date . ' 00:00:00') ?>&to=<?= urlencode($to_date . ' 23:59:59') ?>&kiosk=true&hideLogo=true&fullscreen=true&transparent=true"
								loading="lazy">
							</iframe>
						</div>

						<div class="grafana-breadcrumb-menu-cover"></div>

					</div>
				</div>

			</div>


</body>
<script>
	document.addEventListener('DOMContentLoaded', function() {

		const grafanaIframes = document.querySelectorAll(
			'.grafana-kpi-wrapper iframe, ' +
			'.grafana-wrapper iframe, ' +
			'.grafana-breadcrumb-wrapper iframe'
		);

		grafanaIframes.forEach(function(iframe) {

			const wrapper = iframe.parentElement;

			wrapper.style.position = 'relative';

			// Buat loading line
			const loadingLine = document.createElement('div');
			loadingLine.className = 'grafana-loading-line';

			wrapper.appendChild(loadingLine);

			// Waktu mulai loading
			const startTime = Date.now();

			iframe.addEventListener('load', function() {

				const elapsed = Date.now() - startTime;

				// Minimal tampil 1000ms
				const remaining = Math.max(1000 - elapsed, 0);

				setTimeout(function() {

					loadingLine.classList.add('loaded');

					setTimeout(function() {
						loadingLine.remove();
					}, 500);

				}, remaining);

			}, {
				once: true
			});

		});

	});
</script>
<style>
	/* =====================================================
	   GLOBAL
	===================================================== */

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

	canvas {
		max-width: 100%;
	}


	/* =====================================================
	   KPI CARD
	===================================================== */

	.kpi-card {
		background: #fff;
		border-radius: 28px;
		padding: 20px;
		height: 240px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
		position: relative;
	}

	.kpi-title {
		text-align: center;
		font-size: 18px;
		font-weight: 700;
		margin-bottom: 15px;
		color: #333;
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

	.min {
		color: #d32f2f;
	}

	.max {
		color: #2e7d32;
	}

	.avg {
		color: #222;
	}


	/* =====================================================
	   GRAFANA KPI
	===================================================== */

	.grafana-kpi-card {
		overflow: hidden;
	}

	.grafana-kpi-wrapper {
		position: relative;
		width: 100%;
		height: 110px;
		overflow: hidden;
		background: #fff;
	}

	.grafana-kpi-wrapper iframe {
		position: absolute;
		top: -5px;
		left: -5px;
		width: calc(100% + 10px);
		height: calc(100% + 10px);
		border: none;
	}

	.grafana-menu-cover {
		position: absolute;
		top: 0;
		right: 0;
		width: 60px;
		height: 40px;
		background: #fff;
		z-index: 20;
		pointer-events: none;
	}

	/* Tutup titik tiga / menu Grafana KPI */
	.grafana-kpi-menu-cover {
		position: absolute;
		top: 75px;
		right: 0;
		width: 65px;
		height: 35px;
		background: #fff;
		z-index: 20;
		pointer-events: none;
	}


	/* =====================================================
	   MONITORING CARD
	===================================================== */

	.monitor-card {
		background: #fff;
		border-radius: 20px;
		padding: 20px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
		height: 320px;
		overflow: hidden;
	}

	.monitor-title {
		text-align: center;
		font-size: 18px;
		font-weight: 700;
		margin-bottom: 15px;
		color: #333;
	}

	.monitor-card canvas {
		max-height: 170px;
	}

	.monitor-no-data {
		height: 220px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 20px;
	}

	.monitor-no-data-icon {
		font-size: 32px;
		margin-bottom: 12px;
		opacity: 0.6;
	}

	.monitor-no-data-title {
		font-size: 16px;
		font-weight: 600;
		margin-bottom: 6px;
	}

	.monitor-no-data-text {
		font-size: 13px;
		line-height: 1.6;
		opacity: 0.65;
	}

	.monitor-no-data {
		height: 220px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 20px;
	}

	.monitor-no-data-icon {
		font-size: 32px;
		margin-bottom: 12px;
		opacity: 0.6;
	}

	.monitor-no-data-title {
		font-size: 16px;
		font-weight: 600;
		margin-bottom: 6px;
	}

	.monitor-no-data-text {
		font-size: 13px;
		line-height: 1.6;
		opacity: 0.65;
	}

	/* =====================================================
   GRAFANA MONITORING
===================================================== */

	.grafana-wrapper {
		position: relative;
		width: 100%;
		height: 250px;
		overflow: hidden;
		border-radius: 10px;
		background: #fff;
	}

	.grafana-wrapper iframe {
		position: absolute;
		top: -5px;
		left: -5px;
		width: calc(100% + 10px);
		height: calc(100% + 10px);
		border: none;
		display: block;
	}

	.grafana-monitor-menu-cover {
		position: absolute;
		top: 62px;
		right: 12px;
		width: 65px;
		height: 90px;
		background: #ffffff;
		border-radius: 0 0 0 5px;
		z-index: 20;
		pointer-events: none;
	}

	/* =====================================================
   KONTAMINASI
===================================================== */

	.kontaminasi-card {
		height: 100%;
		min-height: 570px;
		width: 100%;
		overflow: hidden;
	}

	.kontaminasi-card .grafana-wrapper {
		position: relative;
		width: 100%;
		height: calc(100% - 55px);
		min-height: 480px;
		overflow: hidden;
		border-radius: 10px;
		background: #fff;
	}

	.kontaminasi-card .grafana-wrapper iframe {
		position: absolute;
		top: -5px;
		left: -5px;

		/* PENTING */
		width: calc(100% + 10px);
		height: calc(100% + 10px);

		border: none;
		display: block;
	}

	.kontaminasi-menu-cover {
		position: absolute;
		top: 0;
		right: 0;
		width: 65px;
		height: 45px;
		background: #fff;
		z-index: 20;
		pointer-events: none;
	}


	/* =====================================================
      ELECTRICAL BAKING - RESPONSIVE
    ===================================================== */

	.electrical-card {
		background: #fff;
		border-radius: 20px;
		padding: 20px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
		height: 100%;
		min-height: 320px;
		overflow: hidden;
		display: flex;
		flex-direction: column;
	}

	.electrical-card .monitor-title {
		text-align: center;
		font-size: 18px;
		font-weight: 700;
		margin-bottom: 15px;
		color: #333;
		flex-shrink: 0;
	}

	.electrical-card .grafana-wrapper {
		position: relative;
		width: 100%;
		flex: 1;
		min-height: 240px;
		overflow: hidden;
		border-radius: 10px;
	}

	.electrical-card .grafana-wrapper iframe {
		width: 100%;
		height: 100%;
		min-height: 240px;
		border: none;
		display: block;
	}


	/* =====================================================
   RESPONSIVE TABLET
===================================================== */

	@media (max-width: 991px) {

		.electrical-card {
			min-height: 320px;
		}

		.electrical-card .grafana-wrapper {
			min-height: 250px;
		}

		.electrical-card .grafana-wrapper iframe {
			min-height: 250px;
		}

	}


	/* =====================================================
   RESPONSIVE MOBILE
===================================================== */

	@media (max-width: 576px) {

		.electrical-card {
			min-height: 300px;
			padding: 15px;
		}

		.electrical-card .monitor-title {
			font-size: 16px;
			margin-bottom: 10px;
		}

		.electrical-card .grafana-wrapper {
			min-height: 230px;
		}

		.electrical-card .grafana-wrapper iframe {
			min-height: 230px;
		}

	}


	/* =====================================================
	   METAL DETECTOR
	===================================================== */

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


	/* =====================================================
	   REKAP
	===================================================== */

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


	/* =====================================================
	   BREADCRUMB / REKAP SUHU
	===================================================== */
	.breadcrumb-title {
		text-align: center;
		font-weight: 700;
		font-size: 18px;
		margin-bottom: 20px;
		color: #333;
	}

	.breadcrumb-card {
		background: #efefef;
		border-radius: 25px;
		padding: 25px;
		width: 100%;
		height: 100%;
		min-height: 570px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
	}

	.grafana-breadcrumb-wrapper {
		position: relative;
		width: 100%;
		height: 500px;
		overflow: hidden;
		border-radius: 12px;
	}

	.grafana-breadcrumb-wrapper iframe {
		width: 100%;
		height: 100%;
		border: none;
		display: block;
	}

	.chart-footer {
		text-align: center;
		margin-top: 15px;
		font-weight: bold;
	}

	.grafana-breadcrumb-menu-cover {
		position: absolute;
		top: 75px;
		right: 40px;
		width: 65px;
		height: 45px;
		background: #ffffff;
		z-index: 20;
		pointer-events: none;
	}


	/* =====================================================
	   CHART
	===================================================== */

	#keluhanChart {
		width: 100%;
		height: 250px !important;
	}

	#kontaminasiChart {
		width: 100% !important;
		height: 240px !important;
	}


	/* =====================================================
	   MONITORING GRID
	===================================================== */

	.monitoring-wrapper {
		margin: 20px 0;
	}

	.monitoring-grid {
		display: grid;
		grid-template-columns: repeat(8, 1fr);
		gap: 16px;
		margin: 25px 0;
	}

	.monitoring-card {
		background: #fff;
		border-radius: 32px;
		min-height: 205px;

		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: flex-start;

		padding: 12px 10px;

		font-size: 11px;
		font-weight: 700;

		box-shadow: 0 1px 4px rgba(0, 0, 0, .12);

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


	/* =====================================================
	   RESPONSIVE
	===================================================== */

	@media (max-width: 991px) {

		.kpi-card,
		.monitor-card,
		.electrical-card {
			margin-bottom: 20px;
		}

		.grafana-breadcrumb-wrapper {
			height: 400px;
		}

	}

	@media (max-width: 576px) {

		.dashboard-wrapper {
			padding: 10px;
		}

		.grafana-breadcrumb-wrapper {
			height: 300px;
		}

		.monitor-card,
		.electrical-card {
			height: 320px;
		}

	}

	.kontaminasi-card {
		height: 100%;
		min-height: 570px;
	}

	.kontaminasi-card .grafana-wrapper {
		height: calc(100% - 55px);
		min-height: 480px;
	}

	.kontaminasi-card .grafana-wrapper iframe {
		width: 100%;
		height: 100%;
	}


	/* REKAP SUHU */

	.breadcrumb-card {
		width: 100%;
		height: 100%;
		min-height: 570px;
	}

	.grafana-breadcrumb-wrapper {
		width: 100%;
		height: 500px;
	}

	/* =====================================================
	GRAFANA LAZY LOADING - BLUE FLOW LINE
	===================================================== */

	.grafana-loading-line {
		position: absolute !important;

		top: 0 !important;
		left: 0 !important;

		width: 100% !important;
		height: 5px !important;

		background: #70a3f0 !important;

		z-index: 99999 !important;

		overflow: hidden !important;

		pointer-events: none !important;

		opacity: 1;

		transition: opacity 0.5s ease;
	}


	/* Cahaya biru yang mengalir */
	.grafana-loading-line::before {
		content: "";

		position: absolute;

		top: 0;
		left: -40%;

		width: 40%;
		height: 100%;

		background: linear-gradient(90deg,
				transparent 0%,
				#5dade2 20%,
				#ffffff 50%,
				#5dade2 80%,
				transparent 100%);

		animation: grafana-loading-flow 1s linear infinite;
	}


	/* Saat Grafana selesai */
	.grafana-loading-line.loaded {
		opacity: 0;
	}


	/* Animasi cahaya berjalan kiri -> kanan */
	@keyframes grafana-loading-flow {

		0% {
			transform: translateX(0);
		}

		100% {
			transform: translateX(350%);
		}

	}
</style>

</html>
