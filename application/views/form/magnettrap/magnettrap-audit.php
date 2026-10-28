<?php
defined('BASEPATH') or exit('No direct script access allowed');

$tipe_user = $this->session->userdata('tipe_user');
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Pemeriksaan Magnet Trap
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
					href="<?= base_url('magnettrap'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Loading
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan Loading.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check mr-1"></i>
			<?= $this->session->flashdata('success_msg'); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-exclamation-circle mr-1"></i>
			<?= $this->session->flashdata('error_msg'); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable"
					width="100%" cellspacing="0">

					<thead>
						<tr>
							<th width="40px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Waktu</th>
							<th>Tahapan</th>
							<th>Jenis Kontaminasi</th>
							<th>Bukti</th>
							<th>Keterangan</th>
							<th class="text-center">Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($magnettrap)): ?>
							<?php $no = 1; ?>

							<?php foreach ($magnettrap as $val): ?>
								<?php
								$datetime = !empty($val->date)
									? date('d-m-Y', strtotime($val->date))
									: '-';

								$bukti = $val->bukti ?? null;
								$img_url = null;
								$file_path = null;

								if (!empty($bukti)) {
									$path1 = FCPATH . 'uploads/' . $bukti;
									$path2 = FCPATH . 'uploads/magnettrap/' . $bukti;

									if (file_exists($path1)) {
										$img_url = base_url('uploads/' . $bukti);
										$file_path = $path1;
									} elseif (file_exists($path2)) {
										$img_url = base_url('uploads/magnettrap/' . $bukti);
										$file_path = $path2;
									}
								}

								$is_pdf = $file_path
									&& strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) === 'pdf';
								?>

								<tr>
									<td class="text-center"><?= $no++; ?></td>

									<td>
										<?= html_escape($datetime . ' / ' . ($val->shift ?? '-')); ?>
									</td>

									<td><?= html_escape($val->nama_produk?? '-'); ?></td>
									<td><?= html_escape($val->kode_produksi?? '-'); ?></td>

									<td>
										<?= html_escape($val->time ?? '-'); ?>
									</td>

									<td>
										<?= html_escape($val->tahapan ?? '-'); ?>
									</td>

									<td>
										<?= html_escape($val->kontaminasi ?? '-'); ?>
									</td>

									<td class="text-center">
										<?php if ($img_url && $is_pdf): ?>
											<a href="<?= html_escape($img_url); ?>"
												target="_blank"
												rel="noopener noreferrer"
												class="btn btn-danger btn-sm">
												<i class="fas fa-file-pdf mr-1"></i>
												Lihat PDF
											</a>
										<?php elseif ($img_url): ?>
											<a href="<?= html_escape($img_url); ?>"
												target="_blank"
												rel="noopener noreferrer">
												<img src="<?= html_escape($img_url); ?>"
													alt="Bukti Temuan"
													style="max-width:150px; max-height:100px;">
											</a>
										<?php else: ?>
											<span class="text-muted">Tidak ada bukti</span>
										<?php endif; ?>
									</td>

									<td>
										<?= html_escape($val->keterangan ?? '-'); ?>
									</td>

									<td class="text-center">
										<?php
										if (($val->status_spv ?? null) == 0) {
											echo '<span class="text-secondary font-weight-bold">Created</span>';
										} elseif (($val->status_spv ?? null) == 1) {
											echo '<span class="text-success font-weight-bold">Verified</span>';
										} elseif (($val->status_spv ?? null) == 2) {
											echo '<span class="text-danger font-weight-bold">Revision</span>';
										} else {
											echo '<span class="text-muted">-</span>';
										}
										?>
									</td>

									<td class="text-center" style="min-width:150px;">
										<a href="<?= base_url('magnettrap/edit-audit/' . rawurlencode($val->uuid)); ?>"
											class="btn btn-warning btn-sm mb-1"
											title="Edit Audit">

											Edit
										</a>

										<a href="<?= base_url('magnettrap/detail-audit/' . rawurlencode($val->uuid)); ?>"
											class="btn btn-success btn-sm mb-1"
											title="Detail Audit">

											Detail
										</a>
									</td>
								</tr>
							<?php endforeach; ?>

						<?php endif; ?>
					</tbody>
				</table>

				<?php if (empty($magnettrap)): ?>
					<div class="text-center text-muted py-4">
						Belum ada data Audit Magnet Trap untuk plant ini.
					</div>
				<?php endif; ?>
			</div>

			<br>
			<hr>
			<div class="form-group">
				<form action="<?= base_url('magnettrap/cetak-audit') ?>" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>

					<label for="shift" class="mr-2 font-weight-bold">Shift:</label>
					<select name="shift" class="form-control mr-2" required>
						<option value="">-- Pilih Shift --</option>
						<option value="1">Shift 1</option>
						<option value="2">Shift 2</option>
						<option value="3">Shift 3</option>
					</select>

					<button type="submit" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
					</button>
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
