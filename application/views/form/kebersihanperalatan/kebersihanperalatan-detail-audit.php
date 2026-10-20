<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">

		Detail Audit Kebersihan Peralatan

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>

	</h1>


	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">

		<ol class="breadcrumb">

			<li class="breadcrumb-item">

				<a
					class="text-white"
					href="<?= base_url('kebersihanperalatan/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Kebersihan Peralatan
				</a>

			</li>

		</ol>

	</nav>


	<!-- Info Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>


	<div class="card shadow mb-4">

		<div class="card-header py-3 header-audit">

			<h6 class="m-0 font-weight-bold">

				<i class="fas fa-clipboard-check mr-2"></i>

				Detail Pemeriksaan Audit \ Detail

			</h6>

		</div>


		<div class="card-body">

			<div class="table-responsive">

				<table
					class="table table-bordered"
					cellspacing="0">

					<?php
					$datetime = new DateTime(
						$kebersihanperalatan->date
					);

					$tanggal = $datetime->format('d-m-Y');
					?>


					<thead class="text-center">

						<tr>

							<th
								colspan="7"
								class="text-center font-weight-bold">
								PEMERIKSAAN KEBERSIHAN PERALATAN
								<span class="text-audit">
									— AUDIT
								</span>
							</th>

						</tr>

					</thead>


					<tbody>

						<!-- TANGGAL -->
						<tr>

							<td colspan="2">

								<b>Tanggal:</b>
								<?= $tanggal; ?>

							</td>

							<td>

								<b>Shift:</b>
								<?= html_escape(
									$kebersihanperalatan->shift
								); ?>

							</td>

							<td colspan="4"></td>

						</tr>


						<!-- JUDUL -->
						<tr class="bg-light text-center">

							<td
								colspan="7"
								class="font-weight-bold">
								Detail Pemeriksaan Audit
							</td>

						</tr>


						<!-- HEADER -->
						<tr class="thead-audit text-center">

							<th>No</th>

							<th>Nama Peralatan</th>

							<th>Kondisi</th>

							<th>Problem</th>

							<th>Tindakan Koreksi</th>

							<th colspan="2">
								Catatan
							</th>

						</tr>


						<?php
						$peralatan = json_decode(
							$kebersihanperalatan->peralatan
						);
						?>


						<?php if ($peralatan): ?>

							<?php foreach ($peralatan as $i => $item): ?>

								<tr>
									<td class="text-center">
										<?= $i + 1; ?>
									</td>

									<td>
										<?= htmlspecialchars($item->nama ?? '', ENT_QUOTES, 'UTF-8'); ?>
									</td>

									<!-- Kondisi selalu Bersih -->
									<td class="text-center">
										<span class="text-success font-weight-bold">
											Bersih
										</span>
									</td>

									<!-- Problem selalu - -->
									<td class="text-center">
										-
									</td>

									<!-- Tindakan selalu - -->
									<td class="text-center">
										-
									</td>

									<td colspan="2">
										<?= !empty($kebersihanperalatan->catatan)
											? htmlspecialchars(
												$kebersihanperalatan->catatan,
												ENT_QUOTES,
												'UTF-8'
											)
											: 'Tidak ada'; ?>
									</td>
								</tr>

							<?php endforeach; ?>

						<?php else: ?>

							<tr>

								<td
									colspan="7"
									class="text-center text-muted">
									Tidak ada data peralatan
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
	/* =========================
       MODE AUDIT
    ========================= */

	.badge-mode-audit {
		display: inline-block;
		margin-left: 8px;
		padding: 6px 12px;
		background-color: #5a3298;
		color: #fff;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}


	.header-audit {
		background-color: #5a3298;
		color: #fff;
	}


	.thead-audit {
		background-color: #2E86C1;
		color: #fff;
	}


	.text-audit {
		color: #5a3298 !important;
	}


	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}


	.btn-audit:hover {
		background-color: #48277c;
		border-color: #48277c;
		color: #fff;
	}


	.border-left-audit {
		border-left: 4px solid #5a3298 !important;
	}


	.breadcrumb {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}


	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}


	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
	}


	.table {
		width: 100%;
		font-size: 15px;
	}


	.table td,
	.table th {
		padding: 10px 12px;
		vertical-align: middle;
		word-break: break-word;
	}


	.table-borderless th {
		color: #444;
	}


	@media (max-width: 768px) {

		.table td,
		.table th {
			font-size: 14px;
			padding: 8px;
		}

		h1.h3 {
			font-size: 20px;
		}

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin: 8px auto 0;
		}

	}
</style>
