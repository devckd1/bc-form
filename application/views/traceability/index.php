<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid">

	<!-- PAGE HEADER -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<div>
			<h1 class="h3 mb-1 text-gray-800">
				Traceability
			</h1>

			<p class="mb-0 text-muted">
				Telusuri data berdasarkan kode produksi, produk, atau tanggal.
			</p>
		</div>

	</div>


	<!-- ERROR -->
	<?php if ($this->session->flashdata('error_msg')): ?>

		<div class="alert alert-danger alert-dismissible fade show">

			<i class="fas fa-exclamation-circle mr-2"></i>

			<?= html_escape($this->session->flashdata('error_msg')); ?>

			<button
				type="button"
				class="close"
				data-dismiss="alert">

				<span>&times;</span>

			</button>

		</div>

	<?php endif; ?>


	<!-- SEARCH -->
	<div class="card shadow mb-4">

		<div class="card-header py-3">

			<h6 class="m-0 font-weight-bold text-primary">
				Pencarian Traceability
			</h6>

		</div>


		<div class="card-body">

			<form
				method="get"
				action="<?= site_url('traceability/search'); ?>">

				<div class="row align-items-end">

					<!-- KODE / PRODUK -->
					<div class="col-md-6">

						<div class="form-group mb-md-0">

							<label class="font-weight-bold">
								Kode Produksi / Nama Produk
							</label>

							<input
								type="text"
								name="production_code"
								class="form-control"
								placeholder="Contoh: PI 24 119 B"
								value="<?= html_escape(
											isset($production_code)
												? $production_code
												: ''
										); ?>">

						</div>

					</div>


					<!-- TANGGAL -->
					<div class="col-md-3">

						<div class="form-group mb-md-0">

							<label class="font-weight-bold">
								Tanggal
							</label>

							<input
								type="date"
								name="date"
								class="form-control"
								value="<?= html_escape(
											isset($date)
												? $date
												: ''
										); ?>">

						</div>

					</div>

					<!-- BUTTON RESET -->
					<div class="col-md-3">

						<div class="d-flex">

							<button
								type="submit"
								class="btn btn-primary mr-2">

								<i class="fas fa-search mr-1"></i>
								Cari Data

							</button>

							<a
								href="<?= site_url('traceability'); ?>"
								class="btn btn-secondary">

								<i class="fas fa-sync-alt mr-1"></i>
								Reset

							</a>

						</div>

					</div>


					<!-- BUTTON 
					<div class="col-md-3">

						<button
							type="submit"
							class="btn btn-primary btn-block">

							<i class="fas fa-search mr-1"></i>
							Cari Data

						</button>

					</div>-->

				</div>

			</form>

		</div>

	</div>


	<?php if (!empty($results)): ?>

		<?php

		$totalForms = 0;

		foreach ($results as $group) {
			$totalForms += count($group['forms']);
		}

		?>

		<!-- HASIL -->
		<div class="d-flex align-items-center justify-content-between mb-3">

			<h5 class="mb-0 font-weight-bold text-gray-800">
				Hasil Traceability
			</h5>

			<span class="badge badge-primary px-3 py-2">
				<?= $totalForms; ?> Data
			</span>

		</div>


		<!-- SEMUA CARD DALAM SATU GRID -->
		<div class="row">

			<?php foreach ($results as $group): ?>

				<?php foreach ($group['forms'] as $index => $form): ?>

					<?php

					$detailId = 'detail_' .
						preg_replace(
							'/[^A-Za-z0-9_]/',
							'_',
							$group['module'] .
								'_' .
								$form['form_key'] .
								'_' .
								$index
						);

					?>

					<div class="col-xl-3 col-lg-4 col-md-6 mb-4">

						<div class="trace-card h-100">

							<!-- CARD HEADER -->
							<div class="trace-card-header">

								<div class="trace-card-icon">
									<i class="fas fa-folder-open"></i>
								</div>

								<div class="trace-card-number">

									<?= html_escape($group['label']); ?>

								</div>

							</div>


							<!-- CARD BODY -->
							<div class="trace-card-body">

								<?php if (
									isset($form['kode_produksi']) &&
									$form['kode_produksi'] !== null &&
									$form['kode_produksi'] !== ''
								): ?>

									<div class="trace-info mb-3">

										<div class="trace-label">
											Kode Produksi
										</div>

										<div class="trace-value-main">

											<?= html_escape(
												$form['kode_produksi']
											); ?>

										</div>

									</div>

								<?php endif; ?>


								<?php if (
									isset($form['nama_produk']) &&
									$form['nama_produk'] !== null &&
									$form['nama_produk'] !== ''
								): ?>

									<div class="trace-info mb-3">

										<div class="trace-label">
											Nama Produk
										</div>

										<div class="trace-product">

											<?= html_escape(
												$form['nama_produk']
											); ?>

										</div>

									</div>

								<?php endif; ?>


								<div class="trace-meta">

									<?php if (!empty($form['date'])): ?>

										<div class="trace-meta-item">

											<i class="far fa-calendar-alt"></i>

											<span>
												<?= html_escape(
													$form['date']
												); ?>
											</span>

										</div>

									<?php endif; ?>


									<?php if (
										isset($form['shift']) &&
										$form['shift'] !== null &&
										$form['shift'] !== ''
									): ?>

										<div class="trace-meta-item">

											<i class="fas fa-clock"></i>

											<span>
												Shift
												<?= html_escape(
													$form['shift']
												); ?>
											</span>

										</div>

									<?php endif; ?>

								</div>

							</div>


							<!-- CARD FOOTER -->
							<div class="trace-card-footer">

								<button
									type="button"
									class="btn btn-sm btn-outline-primary btn-detail btn-block"
									data-module="<?= html_escape($group['module']); ?>"
									data-form-key="<?= html_escape($form['form_key']); ?>"
									data-detail-id="<?= html_escape($detailId); ?>">

									<i class="fas fa-eye mr-1"></i>
									Lihat Detail

								</button>

							</div>

						</div>

					</div>

				<?php endforeach; ?>

			<?php endforeach; ?>

		</div>


	<?php elseif (
		isset($production_code) &&
		isset($date) &&
		(
			$production_code !== '' ||
			$date !== ''
		)
	): ?>

		<div class="card shadow">

			<div class="card-body text-center py-5">

				<i class="fas fa-search fa-3x text-gray-300 mb-3"></i>

				<h5 class="font-weight-bold text-gray-700">
					Data tidak ditemukan
				</h5>

				<p class="text-muted mb-0">
					Tidak ada data yang sesuai dengan pencarian.
				</p>

			</div>

		</div>

	<?php endif; ?>

</div>
<!-- =========================================================
	 MODAL DETAIL TRACEABILITY
========================================================= -->
<div
	class="modal fade"
	id="traceabilityDetailModal"
	tabindex="-1"
	role="dialog"
	aria-labelledby="traceabilityDetailModalLabel"
	aria-hidden="true">

	<div
		class="modal-dialog modal-lg modal-dialog-scrollable"
		role="document">

		<div class="modal-content">

			<!-- MODAL HEADER -->
			<div class="modal-header">

				<div>

					<h5
						class="modal-title font-weight-bold text-primary"
						id="traceabilityDetailModalLabel">

						Detail Traceability

					</h5>

					<div
						id="traceabilityDetailSubtitle"
						class="small text-muted mt-1">

					</div>

				</div>

				<button
					type="button"
					class="close"
					data-dismiss="modal"
					aria-label="Close">

					<span aria-hidden="true">
						&times;
					</span>

				</button>

			</div>


			<!-- MODAL BODY -->
			<div class="modal-body">

				<div
					id="traceabilityDetailContent"
					class="trace-modal-content">

					<div class="text-center text-muted py-5">

						<i class="fas fa-spinner fa-spin mr-1"></i>

						Memuat detail...

					</div>

				</div>

			</div>


			<!-- MODAL FOOTER -->
			<div class="modal-footer">

				<button
					type="button"
					class="btn btn-secondary btn-sm"
					data-dismiss="modal">

					<i class="fas fa-times mr-1"></i>

					Tutup

				</button>

			</div>

		</div>

	</div>

</div>

<style>
	/* =========================================================
	   TRACEABILITY CARD
	========================================================= */

	.trace-card {
		background: #fff;
		border: 1px solid #e3e6f0;
		border-radius: 8px;
		box-shadow: 0 2px 8px rgba(58, 59, 69, 0.08);
		overflow: hidden;
		transition: all 0.2s ease;
		height: 100%;
	}

	.trace-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 5px 15px rgba(58, 59, 69, 0.12);
	}

	.trace-card-header {
		display: flex;
		align-items: center;
		padding: 14px 16px;
		border-bottom: 1px solid #e3e6f0;
		background: #f8f9fc;
	}

	.trace-card-icon {
		width: 34px;
		height: 34px;
		min-width: 34px;
		border-radius: 6px;

		display: flex;
		align-items: center;
		justify-content: center;

		background: #eef2ff;
		color: #4e73df;

		margin-right: 10px;
	}

	.trace-card-icon i {
		font-size: 13px;
	}

	.trace-card-number {
		font-size: 12px;
		font-weight: 700;
		color: #5a5c69;
	}

	.trace-card-body {
		padding: 16px;
		min-height: 145px;
	}

	.trace-info {
		margin-bottom: 14px;
	}

	.trace-label {
		font-size: 11px;
		font-weight: 600;
		color: #858796;
		margin-bottom: 3px;
		text-transform: uppercase;
		letter-spacing: 0.2px;
	}

	.trace-value-main {
		font-size: 14px;
		font-weight: 700;
		color: #3a3b45;
		line-height: 1.4;
		word-break: break-word;
		overflow-wrap: anywhere;
	}

	.trace-product {
		font-size: 13px;
		color: #5a5c69;
		line-height: 1.4;
		word-break: break-word;
		overflow-wrap: anywhere;
	}

	.trace-meta {
		border-top: 1px solid #eaecf4;
		padding-top: 10px;
		margin-top: 5px;
	}

	.trace-meta-item {
		display: flex;
		align-items: center;
		font-size: 12px;
		color: #858796;
		margin-bottom: 5px;
	}

	.trace-meta-item:last-child {
		margin-bottom: 0;
	}

	.trace-meta-item i {
		width: 18px;
		min-width: 18px;
		color: #4e73df;
		margin-right: 3px;
	}

	.trace-card-footer {
		padding: 0 16px 16px;
	}

	.trace-card-footer .btn {
		width: 100%;
		font-size: 12px;
		font-weight: 600;
		border-radius: 5px;
	}

	/* =========================================================
	   DETAIL MODAL
	========================================================= */

	#traceabilityDetailModal .modal-dialog {
		max-width: 760px;
		width: calc(100% - 30px);
		margin: 30px auto;
	}

	#traceabilityDetailModal .modal-content {
		border: 0;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
	}

	/* HEADER */

	#traceabilityDetailModal .modal-header {
		padding: 13px 18px;
		border-bottom: 1px solid #e3e6f0;
		background: #fff;
	}

	#traceabilityDetailModal .modal-title {
		font-size: 16px;
		font-weight: 700;
		color: #4e73df;
	}

	#traceabilityDetailSubtitle {
		font-size: 12px;
		color: #858796;
	}

	/* BODY */

	#traceabilityDetailModal .modal-body {
		padding: 12px;
		background: #f8f9fc;
	}

	/* FOOTER */

	#traceabilityDetailModal .modal-footer {
		padding: 9px 15px;
		border-top: 1px solid #e3e6f0;
		background: #fff;
	}

	/* =========================================================
	   DETAIL CONTENT
	========================================================= */

	.trace-modal-content {
		width: 100%;
	}

	/* SECTION */

	.trace-modal-content .detail-section {
		background: #fff;
		border: 1px solid #e0e4ec;
		border-radius: 6px;
		padding: 10px;
		margin-bottom: 10px;
	}

	.trace-modal-content .detail-section:last-child {
		margin-bottom: 0;
	}

	/* SECTION TITLE */

	.trace-modal-content .detail-section-title {
		display: flex;
		align-items: center;

		font-size: 12px;
		font-weight: 700;

		color: #4e73df;

		margin-bottom: 7px;
		padding-bottom: 6px;

		border-bottom: 1px solid #eaecf4;
	}

	.trace-modal-content .detail-section-title i {
		font-size: 11px;
		margin-right: 6px;
	}

	/* =========================================================
	   DETAIL TABLE
	========================================================= */

	.trace-modal-content .detail-table {
		width: 100%;
		margin: 0;
		border-collapse: collapse;
		table-layout: fixed;
		background: #fff;
	}

	.trace-modal-content .detail-table td {
		padding: 7px 9px;

		border: 1px solid #e1e5ec;

		font-size: 12px;
		line-height: 1.4;

		vertical-align: top;
	}

	/* LABEL */

	.trace-modal-content .detail-table .detail-field {
		width: 180px;

		background: #f8f9fc;

		color: #5a5c69;

		font-weight: 600;

		vertical-align: top;
	}

	/* VALUE */

	.trace-modal-content .detail-table .detail-value {
		width: auto;

		color: #3a3b45;

		word-break: break-word;
		overflow-wrap: anywhere;
	}

	/* =========================================================
	   VALUE BIASA
	========================================================= */

	.trace-value {
		font-size: 12px;
		line-height: 1.45;

		color: #3a3b45;

		word-break: break-word;
		overflow-wrap: anywhere;
	}

	/* =========================================================
	   JSON TABLE
	========================================================= */

	.trace-json-table-wrapper {
		width: 100%;
		max-width: 100%;

		overflow-x: auto;

		border: 1px solid #dfe3eb;
		border-radius: 4px;

		background: #fff;
	}

	.trace-json-table {
		width: 100%;
		border-collapse: collapse;
		margin: 0;

		font-size: 11px;
	}

	.trace-json-table th {
		padding: 6px 8px;

		background: #f1f3f7;

		border: 1px solid #dfe3eb;

		font-size: 10px;
		font-weight: 700;

		color: #5a5c69;

		white-space: nowrap;
		text-align: left;
	}

	.trace-json-table td {
		padding: 6px 8px;

		border: 1px solid #dfe3eb;

		font-size: 11px;

		color: #3a3b45;

		vertical-align: top;

		line-height: 1.35;

		word-break: break-word;
		overflow-wrap: anywhere;
	}

	.trace-json-table tbody tr:nth-child(even) {
		background: #fafbfc;
	}

	.trace-json-table tbody tr:hover {
		background: #f5f7fb;
	}

	/* =========================================================
	   NESTED OBJECT
	========================================================= */

	.trace-object {
		border: 1px solid #e1e5ec;
		border-radius: 4px;
		background: #fafbfc;
		overflow: hidden;
	}

	.trace-object-row {
		display: flex;
		border-bottom: 1px solid #e1e5ec;
	}

	.trace-object-row:last-child {
		border-bottom: 0;
	}

	.trace-object-key {
		width: 130px;
		min-width: 130px;

		padding: 5px 7px;

		background: #f1f3f7;

		font-size: 10px;
		font-weight: 600;

		color: #5a5c69;
	}

	.trace-object-value {
		flex: 1;

		padding: 5px 7px;

		font-size: 10px;

		color: #3a3b45;

		word-break: break-word;
		overflow-wrap: anywhere;
	}

	/* =========================================================
	   LIST
	========================================================= */

	.trace-list {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.trace-list-item {
		padding: 5px 7px;

		background: #f8f9fc;

		border: 1px solid #e1e5ec;
		border-radius: 4px;

		font-size: 11px;
	}

	/* =========================================================
	   EMPTY / ERROR
	========================================================= */

	.detail-error {
		color: #e74a3b;
		font-size: 13px;
	}

	/* =========================================================
	   SCROLLBAR
	========================================================= */

	.trace-json-table-wrapper::-webkit-scrollbar {
		width: 6px;
		height: 6px;
	}

	.trace-json-table-wrapper::-webkit-scrollbar-track {
		background: #f1f1f1;
	}

	.trace-json-table-wrapper::-webkit-scrollbar-thumb {
		background: #c5c8d0;
		border-radius: 5px;
	}

	/* =========================================================
	   MOBILE
	========================================================= */

	@media (max-width: 767.98px) {

		#traceabilityDetailModal .modal-dialog {
			width: calc(100% - 20px);
			margin: 10px auto;
		}

		#traceabilityDetailModal .modal-header {
			padding: 11px 14px;
		}

		#traceabilityDetailModal .modal-body {
			padding: 8px;
		}

		#traceabilityDetailModal .modal-footer {
			padding: 8px 10px;
		}

		.trace-modal-content .detail-section {
			padding: 8px;
			margin-bottom: 8px;
		}

		.trace-modal-content .detail-section-title {
			font-size: 11px;
		}

		.trace-modal-content .detail-table td {
			padding: 6px 7px;
			font-size: 11px;
		}

		.trace-modal-content .detail-table .detail-field {
			width: 120px;
		}

		.trace-modal-content .detail-table .detail-value {
			width: auto;
		}

		.trace-json-table {
			min-width: 450px;
		}

		.trace-json-table th,
		.trace-json-table td {
			padding: 5px 6px;
			font-size: 10px;
		}

		.trace-object-key {
			width: 100px;
			min-width: 100px;
		}
	}

	/* =========================================================
	   SMALL MOBILE
	========================================================= */

	@media (max-width: 575.98px) {

		#traceabilityDetailModal .modal-dialog {
			width: calc(100% - 10px);
			margin: 5px auto;
		}

		.trace-modal-content .detail-table .detail-field {
			width: 105px;
		}

		.trace-json-table {
			min-width: 400px;
		}

		.trace-object-row {
			display: block;
		}

		.trace-object-key {
			width: 100%;
			min-width: 0;
			border-bottom: 1px solid #e1e5ec;
		}
	}
</style>


<script>
	(function() {

		/* =========================================================
		   CACHE DETAIL
		========================================================= */

		const detailCache = {};


		/* =========================================================
		   ESCAPE HTML
		========================================================= */

		function escapeHtml(value) {

			if (
				value === null ||
				value === undefined
			) {
				return '';
			}

			return String(value)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}


		/* =========================================================
		   FORMAT LABEL
		========================================================= */

		function formatLabel(value) {

			if (!value) {
				return '';
			}

			return String(value)
				.replace(/_/g, ' ')
				.replace(/\b\w/g, function(letter) {
					return letter.toUpperCase();
				});
		}


		/* =========================================================
		   CEK JSON STRING
		========================================================= */

		function parseJson(value) {

			if (
				typeof value !== 'string' ||
				value.trim() === ''
			) {
				return null;
			}

			const text = value.trim();

			if (
				!(
					text.startsWith('{') ||
					text.startsWith('[')
				)
			) {
				return null;
			}

			try {

				return JSON.parse(text);

			} catch (error) {

				return null;

			}
		}


		/* =========================================================
		   RENDER VALUE
		========================================================= */

		function renderValue(value) {

			/* VALUE KOSONG */

			if (
				value === null ||
				value === undefined ||
				value === ''
			) {

				return `
					<span class="text-muted">-</span>
				`;
			}


			/* OBJECT / ARRAY */

			if (typeof value === 'object') {

				return renderStructuredData(value);

			}


			/* STRING YANG BERISI JSON */

			if (typeof value === 'string') {

				const parsed =
					parseJson(value);

				if (parsed !== null) {

					return renderStructuredData(
						parsed
					);

				}

				return `
					<div class="trace-value">
						${escapeHtml(value)}
					</div>
				`;
			}


			/* VALUE BIASA */

			return `
				<div class="trace-value">
					${escapeHtml(String(value))}
				</div>
			`;
		}


		/* =========================================================
		   RENDER STRUCTURED DATA
		========================================================= */

		function renderStructuredData(data) {

			/* ARRAY */

			if (Array.isArray(data)) {

				if (data.length === 0) {

					return `
						<span class="text-muted">-</span>
					`;
				}


				/* ARRAY OF OBJECT */

				const objectArray =
					data.every(function(item) {

						return (
							item &&
							typeof item === 'object' &&
							!Array.isArray(item)
						);

					});


				if (objectArray) {

					return renderObjectTable(data);
				}


				/* ARRAY BIASA */

				return `
					<div class="trace-list">

						${data.map(function(item) {

							return `
								<div class="trace-list-item">
									${renderValue(item)}
								</div>
							`;

						}).join('')}

					</div>
				`;
			}


			/* OBJECT */

			if (
				data &&
				typeof data === 'object'
			) {

				return renderObjectTable([
					data
				]);
			}


			return escapeHtml(
				String(data)
			);
		}


		/* =========================================================
		   OBJECT -> TABLE
		========================================================= */

		function renderObjectTable(rows) {

			if (
				!Array.isArray(rows) ||
				rows.length === 0
			) {

				return `
					<span class="text-muted">-</span>
				`;
			}


			/* =====================================================
			   AMBIL COLUMN
			===================================================== */

			const columns = [];


			rows.forEach(function(row) {

				if (
					!row ||
					typeof row !== 'object'
				) {
					return;
				}


				Object.keys(row).forEach(
					function(key) {

						if (
							columns.indexOf(key) === -1
						) {

							columns.push(key);

						}

					}
				);

			});


			if (columns.length === 0) {

				return `
					<span class="text-muted">-</span>
				`;
			}


			/* =====================================================
			   TABLE HEADER
			===================================================== */

			let html = `
				<div class="trace-json-table-wrapper">

					<table class="trace-json-table">

						<thead>
							<tr>
			`;


			columns.forEach(function(column) {

				html += `
					<th>
						${escapeHtml(
							formatLabel(column)
						)}
					</th>
				`;

			});


			html += `
							</tr>
						</thead>

						<tbody>
			`;


			/* =====================================================
			   TABLE BODY
			===================================================== */

			rows.forEach(function(row) {

				if (
					!row ||
					typeof row !== 'object'
				) {
					return;
				}


				html += '<tr>';


				columns.forEach(function(column) {

					const hasValue =
						Object.prototype.hasOwnProperty.call(
							row,
							column
						);


					const value =
						hasValue ?
						row[column] :
						null;


					html += '<td>';


					if (
						value === null ||
						value === undefined ||
						value === ''
					) {

						html += `
							<span class="text-muted">-</span>
						`;

					} else {

						html +=
							renderCellValue(value);

					}


					html += '</td>';

				});


				html += '</tr>';

			});


			html += `
						</tbody>

					</table>

				</div>
			`;


			return html;
		}


		/* =========================================================
		   RENDER CELL VALUE
		========================================================= */

		function renderCellValue(value) {

			/* OBJECT / ARRAY */

			if (
				value &&
				typeof value === 'object'
			) {

				return renderNestedValue(value);

			}


			/* STRING JSON */

			if (typeof value === 'string') {

				const parsed =
					parseJson(value);

				if (parsed !== null) {

					return renderNestedValue(
						parsed
					);
				}

				return escapeHtml(value);
			}


			return escapeHtml(
				String(value)
			);
		}


		/* =========================================================
		   RENDER NESTED VALUE
		========================================================= */

		function renderNestedValue(value) {

			/* ARRAY */

			if (Array.isArray(value)) {

				if (value.length === 0) {
					return '-';
				}


				/* ARRAY OBJECT */

				const isObjectArray =
					value.every(function(item) {

						return (
							item &&
							typeof item === 'object' &&
							!Array.isArray(item)
						);

					});


				if (isObjectArray) {

					return renderObjectTable(value);
				}


				/* ARRAY BIASA */

				return `
					<div class="trace-list">

						${value.map(function(item) {

							return `
								<div class="trace-list-item">
									${renderCellValue(item)}
								</div>
							`;

						}).join('')}

					</div>
				`;
			}


			/* OBJECT */

			if (
				value &&
				typeof value === 'object'
			) {

				return renderNestedObject(value);
			}


			return escapeHtml(
				String(value)
			);
		}


		/* =========================================================
		   NESTED OBJECT
		========================================================= */

		function renderNestedObject(object) {

			const keys =
				Object.keys(object);


			if (keys.length === 0) {
				return '-';
			}


			let html = `
				<div class="trace-object">
			`;


			keys.forEach(function(key) {

				const value =
					object[key];


				html += `
					<div class="trace-object-row">

						<div class="trace-object-key">
							${escapeHtml(
								formatLabel(key)
							)}
						</div>

						<div class="trace-object-value">
							${renderCellValue(value)}
						</div>

					</div>
				`;

			});


			html += `
				</div>
			`;


			return html;
		}


		/* =========================================================
		   RENDER DETAIL
		========================================================= */

		function renderDetail(
			container,
			details
		) {

			if (
				!Array.isArray(details) ||
				details.length === 0
			) {

				container.innerHTML = `
					<div class="detail-error text-center py-4">
						Detail data tidak tersedia.
					</div>
				`;

				return;
			}


			/* =====================================================
			   GROUP SECTION
			===================================================== */

			const sections = {};


			details.forEach(function(item) {

				if (
					!item ||
					!item.field
				) {
					return;
				}


				const section =
					item.section ||
					'Informasi';


				if (!sections[section]) {

					sections[section] = [];

				}


				sections[section].push(item);

			});


			/* =====================================================
			   BUILD HTML
			===================================================== */

			let html = '';


			Object.keys(sections).forEach(
				function(section) {

					html += `
						<div class="detail-section">

							<div class="detail-section-title">

								<i class="fas fa-folder-open"></i>

								${escapeHtml(section)}

							</div>

							<table class="detail-table">

								<tbody>
					`;


					sections[section].forEach(
						function(item) {

							html += `
								<tr>

									<td class="detail-field">
										${escapeHtml(
											item.field
										)}
									</td>

									<td class="detail-value">
										${renderValue(
											item.value
										)}
									</td>

								</tr>
							`;

						}
					);


					html += `
								</tbody>

							</table>

						</div>
					`;

				}
			);


			/* =====================================================
			   EMPTY
			===================================================== */

			if (html === '') {

				container.innerHTML = `
					<div class="detail-error text-center py-4">
						Detail data tidak tersedia.
					</div>
				`;

				return;
			}


			container.innerHTML = html;
		}


		/* =========================================================
		   BUTTON DETAIL
		========================================================= */

		document
			.querySelectorAll('.btn-detail')
			.forEach(function(button) {

				button.addEventListener(
					'click',
					function() {

						/* =============================================
						   MODULE
						============================================= */

						const module =
							this.getAttribute(
								'data-module'
							);


						/* =============================================
						   FORM KEY
						============================================= */

						const formKey =
							this.getAttribute(
								'data-form-key'
							);


						/* =============================================
						   ELEMENT MODAL
						============================================= */

						const modal =
							$('#traceabilityDetailModal');


						const content =
							document.getElementById(
								'traceabilityDetailContent'
							);


						const subtitle =
							document.getElementById(
								'traceabilityDetailSubtitle'
							);


						if (!content) {
							return;
						}


						/* =============================================
						   MODULE LABEL
						============================================= */

						const moduleLabel =
							this
							.closest('.trace-card')
							.querySelector(
								'.trace-card-number'
							);


						if (moduleLabel) {

							subtitle.textContent =
								moduleLabel
								.textContent
								.trim();

						}


						/* =============================================
						   SHOW MODAL
						============================================= */

						modal.modal('show');


						/* =============================================
						   CACHE KEY
						============================================= */

						const cacheKey =
							module +
							'|' +
							formKey;


						/* =============================================
						   CHECK CACHE
						============================================= */

						if (
							Object.prototype.hasOwnProperty.call(
								detailCache,
								cacheKey
							)
						) {

							renderDetail(
								content,
								detailCache[cacheKey]
							);

							return;
						}


						/* =============================================
						   LOADING
						============================================= */

						content.innerHTML = `

							<div class="text-center text-muted py-4">

								<i
									class="fas fa-spinner fa-spin fa-lg mb-2">
								</i>

								<div>
									Memuat detail...
								</div>

							</div>

						`;


						/* =============================================
						   SEARCH VALUE
						============================================= */

						const searchValue =
							<?= json_encode(
								isset($production_code)
									? $production_code
									: ''
							); ?>;


						const dateValue =
							<?= json_encode(
								isset($date)
									? $date
									: ''
							); ?>;


						/* =============================================
						   AJAX URL
						============================================= */

						const url =
							'<?= site_url('traceability/form-details'); ?>' +

							'?module=' +
							encodeURIComponent(module) +

							'&form_key=' +
							encodeURIComponent(formKey) +

							'&search=' +
							encodeURIComponent(searchValue) +

							'&date=' +
							encodeURIComponent(dateValue);


						/* =============================================
						   FETCH
						============================================= */

						fetch(
								url, {
									method: 'GET',

									headers: {
										'X-Requested-With': 'XMLHttpRequest'
									}
								}
							)

							.then(function(response) {

								if (!response.ok) {

									throw new Error(
										'Gagal mengambil detail.'
									);

								}

								return response.json();

							})

							.then(function(data) {

								const details =
									data &&
									Array.isArray(
										data.details
									) ?
									data.details :
									[];


								/* =========================================
								   CACHE
								========================================= */

								detailCache[cacheKey] =
									details;


								/* =========================================
								   RENDER
								========================================= */

								renderDetail(
									content,
									details
								);

							})

							.catch(function(error) {

								console.error(
									'Traceability Detail:',
									error
								);


								content.innerHTML = `

								<div
									class="detail-error text-center py-4">

									<i
										class="fas fa-exclamation-circle mr-1">
									</i>

									Detail data gagal dimuat.

								</div>

							`;

							});

					}
				);

			});

	})();
</script>
