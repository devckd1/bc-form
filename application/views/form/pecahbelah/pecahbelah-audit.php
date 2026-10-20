<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Data Audit Pecah Belah
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('pecahbelah'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pecah Belah
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pecah Belah.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered table-hover"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Shift</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($pecahbelah)): ?>

							<?php $no = 1; ?>

							<?php foreach ($pecahbelah as $val): ?>

								<?php
								$benda_pecah_display = '-';

								if (!empty($val->benda_pecah)) {

									$benda_pecah_json = json_decode(
										$val->benda_pecah,
										true
									);

									if (
										json_last_error() === JSON_ERROR_NONE &&
										is_array($benda_pecah_json)
									) {

										$items = [];

										foreach ($benda_pecah_json as $item) {

											if (!is_array($item)) {
												continue;
											}

											$nama_barang = $item['nama_barang'] ?? '-';
											$area = $item['area'] ?? '-';
											$pemilik = $item['pemilik'] ?? '-';
											$jumlah = $item['jumlah'] ?? '-';
											$kondisi_awal = $item['kondisi_awal'] ?? '-';
											$kondisi_akhir = $item['kondisi_akhir'] ?? '-';
											$keterangan = $item['keterangan'] ?? '-';

											$items[] =
												'<strong>' . htmlspecialchars($nama_barang) . '</strong>' .
												' | Area: ' . htmlspecialchars($area) .
												' | Pemilik: ' . htmlspecialchars($pemilik) .
												' | Jumlah: ' . htmlspecialchars($jumlah) .
												' | Awal: ' . htmlspecialchars($kondisi_awal) .
												' | Akhir: ' . htmlspecialchars($kondisi_akhir) .
												' | Ket: ' . htmlspecialchars($keterangan);
										}

										if (!empty($items)) {
											$benda_pecah_display = implode('<br>', $items);
										}
									}
								}
								?>

								<tr>

									<td><?= $no++; ?></td>

									<td>
										<?= !empty($val->date)
											? date('d-m-Y', strtotime($val->date))
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->shift ?? '-'); ?>
									</td>

									<td>
										<?= !empty($val->modified_at)
											? date('d-m-Y H:i', strtotime($val->modified_at))
											: '-'; ?>
									</td>

									<td>
										<?= !empty($val->tgl_update_spv)
											? date('d-m-Y H:i', strtotime($val->tgl_update_spv))
											: '-'; ?>
									</td>

									<td class="text-center">

										<a href="<?= base_url('pecahbelah/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('pecahbelah/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Edit</span>
										</a>


									</td>

								</tr>

							<?php endforeach; ?>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

			<hr>
			<div class="form-group">
				<label>Pilih Data yang akan dicetak:</label>
				<br>
				<form action="<?= base_url('pecahbelah/cetak') ?>" method="post">

					<div class="row">

						<div class="col-md-3">
							<input
								type="date"
								name="tanggal"
								class="form-control"
								required>
						</div>

						<div class="col-md-2">
							<button class="btn btn-success">
								<i class="fas fa-print"></i>
								Cetak PDF
							</button>
						</div>

					</div>

				</form>
			</div>

		</div>

	</div>

</div>
<style>
	th {
		background-color: #f8f9fc;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	th {
		background-color: #f8f9fc;
	}

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/* Label Mode Audit */
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}
</style>
