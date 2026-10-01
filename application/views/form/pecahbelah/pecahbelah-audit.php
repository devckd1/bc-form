<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Data Audit Pecah Belah
		</h1>
	</div>

	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="form-group text-right">

				<a href="<?= base_url('pecahbelah/verifikasi'); ?>"
					class="btn btn-secondary btn-sm">
					<i class="fas fa-arrow-left mr-1"></i>
					Kembali
				</a>

			</div>
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

										<a href="<?= base_url('pecahbelah/status/' . $val->uuid); ?>"
											class="btn btn-info btn-sm">
											Verifikasi
										</a>
										<a href="<?= base_url('pecahbelah/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('pecahbelah/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
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
