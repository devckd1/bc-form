<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Sanitasi Warehouse</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
		<br>
	<?php endif ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif ?>

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('sanitasiwarehouse/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('sanitasiwarehouse/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Area</th>
							<th>Hasil Pemeriksaan</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($sanitasiwarehouse as $val) {
							$datetime = new DateTime($val->date);
							$tanggalFormatted = $datetime->format('d-m-Y');

							$result = json_decode($val->detail, true);

							$kondisiMap = [
								'0' => 'Bersih',
								'1' => 'Berdebu',
								'2' => 'Basah',
								'3' => 'Sampah (sisa lakban, kertas, remah produk/bahan baku, plastik, kardus bekas)',
								'4' => 'Pertumbuhan mikroorganisme (jamur dan bau busuk)',
								'5' => 'Pallet rusak/pecah',
								'6' => 'Terdapat aktifitas binatang (tikus, kecoa, lalat, ulat, belatung)',
								'7' => 'Sarang laba-laba',
							];

							// $bagianList = '';
							// $kondisiList = '';
							// $problemList = '';
							// $tindakanList = '';

							// if (is_array($details)) {
							//     foreach ($details as $d) {
							//         $bagianList .= '<li>' . htmlspecialchars($d['bagian']) . '</li>';
							//         $kondisiList .= '<li>' . ($kondisiMap[$d['kondisi']] ?? htmlspecialchars($d['kondisi'])) . '</li>';
							//         $problemList .= '<li>' . (!empty($d['problem']) ? htmlspecialchars($d['problem']) : '-') . '</li>';
							//         $tindakanList .= '<li>' . (!empty($d['tindakan']) ? htmlspecialchars($d['tindakan']) : '-') . '</li>';
							//     }
							// }

						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $tanggalFormatted; ?></td>
								<td><?= htmlspecialchars($val->area); ?></td>
								<!-- <td><ul><?= $bagianList ?></ul></td>
                                <td><ul><?= $kondisiList ?></ul></td>
                                <td><ul><?= $problemList ?></ul></td>
                                <td><ul><?= $tindakanList ?></ul></td> -->
								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead style="background-color:#2E86C1; color:gray; text-align:center;">
											<tr>
												<th width="30%">Titik Pemeriksaan</th>
												<th width="20%">Kondisi</th>
												<th width="20%">Masalah</th>
												<th width="30%">Tindakan Koreksi</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($result) && is_array($result)): ?>
												<?php foreach ($result as $row): ?>
													<tr>
														<td><?= htmlspecialchars($row['bagian'] ?? '-'); ?></td>
														<td style="text-align:center;"><?= ($kondisiMap[$row['kondisi']] ?? htmlspecialchars($row['kondisi']) ?? '-'); ?></td>
														<td style="text-align:center;"><?= htmlspecialchars($row['problem'] ?? '-'); ?></td>
														<td style="text-align:center;"><?= htmlspecialchars($row['tindakan'] ?? '-'); ?></td>
													</tr>
												<?php endforeach ?>
											<?php else: ?>
												<tr>
													<td colspan="3" class="text-center">Tidak ada data</td>
												</tr>
											<?php endif ?>
										</tbody>
									</table>
								</td>
								<td class="text-center">
									<?php
									if ($val->status_spv == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_spv == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
									} elseif ($val->status_spv == 2) {
										echo '<span style="color: red; font-weight: bold;">Revision</span>';
									}
									?>
								</td>
								<td class="text-center">
									<a href="<?= base_url('sanitasiwarehouse/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('sanitasiwarehouse/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
										<span class="text">Detail</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('sanitasiwarehouse/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
									<!-- <a href="<?= base_url('sanitasiwarehouse/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <span class="text">Delete</span>
                                </a> -->
								</td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<div class="mb-3">
					<form action="<?= base_url('sanitasiwarehouse/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>
						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<style>
	th {
		background-color: #f8f9fc;
	}
</style>