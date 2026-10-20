<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Verifikasi Magnet Trap
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
					href="<?= base_url('verifikasimagnet'); ?>">
					<i class="fas fa-arrow-left"></i>
					Kembali ke Verifikasi Magnet Trap
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert"
		style="background-color:#f3e8ff; border-left:4px solid #6f42c1; color:#4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Verifikasi Magnet Trap.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah
		data aktual pada menu pemeriksaan.
	</div>

	<?php if ($this->session->flashdata('success_msg')) : ?>
		<div class="alert alert-success alert-dismissible fade show">
			<i class="fas fa-check-circle mr-1"></i>
			<?= html_escape($this->session->flashdata('success_msg')); ?>

			<button type="button" class="close"
				data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')) : ?>
		<div class="alert alert-danger alert-dismissible fade show">
			<i class="fas fa-times-circle mr-1"></i>
			<?= html_escape($this->session->flashdata('error_msg')); ?>

			<button type="button" class="close"
				data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4 card-audit">
		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered table-hover"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th class="text-center" width="40">No.</th>
							<th>Tanggal</th>
							<th class="text-center">Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th class="text-center">Jumlah Temuan</th>
							<th class="text-center">Status SPV</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php
						$no = 1;

						if (!empty($verifikasimagnet)) :
							foreach ($verifikasimagnet as $row) :
						?>
								<tr>
									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?php
										if (!empty($row->date)) {
											$timestamp = strtotime($row->date);
											echo $timestamp
												? date('d-m-Y', $timestamp)
												: html_escape($row->date);
										} else {
											echo '-';
										}
										?>
									</td>

									<td class="text-center">
										<?= !empty($row->shift)
											? html_escape($row->shift)
											: '-'; ?>
									</td>

									<td>
										<?= !empty($row->nama_produk)
											? html_escape($row->nama_produk)
											: '-'; ?>
									</td>

									<td>
										<?= !empty($row->kode_produksi)
											? html_escape($row->kode_produksi)
											: '-'; ?>
									</td>

									<td class="text-center">
										<?= isset($row->jumlah_temuan)
											? html_escape($row->jumlah_temuan)
											: '-'; ?>
									</td>

									<td class="text-center">
										<?php
										$status_spv = isset($row->status_spv)
											? (string) $row->status_spv
											: '0';

										if ($status_spv === '1') {
											echo '<span class="badge badge-success">Disetujui</span>';
										} elseif ($status_spv === '2') {
											echo '<span class="badge badge-danger">Ditolak</span>';
										} else {
											echo '<span class="badge badge-secondary">Belum Diverifikasi</span>';
										}
										?>
									</td>

									<td class="text-center" style="min-width:150px;">

										<a href="<?= base_url(
														'verifikasimagnet/detail-audit/' . rawurlencode($row->uuid)
													); ?>"
											class="btn btn-success btn-sm mb-1">
											Detail
										</a>

										<a href="<?= base_url(
														'verifikasimagnet/edit-audit/' . rawurlencode($row->uuid)
													); ?>"
											class="btn btn-warning btn-sm mb-1">
											Edit
										</a>
									</td>
								</tr>

						<?php
							endforeach;
						endif;
						?>
					</tbody>

				</table>

				<?php if (empty($verifikasimagnet)) : ?>
					<div class="text-center text-muted py-4">
						<i class="fas fa-inbox fa-2x mb-2"></i>
						<p class="mb-0">
							Belum ada data Audit Verifikasi Magnet Trap.
						</p>
					</div>
				<?php endif; ?>

			</div>

			<br>
			<hr>
			<div class="form-group">
				<form action="<?= base_url('verifikasimagnet/cetak-audit') ?>" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>
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
		vertical-align: middle !important;
	}

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

	.card-audit .table td {
		vertical-align: middle;
	}
</style>