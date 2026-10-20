<?php
defined('BASEPATH') or exit('No direct script access allowed');

$nama_bulan = [
	1  => 'Januari',
	2  => 'Februari',
	3  => 'Maret',
	4  => 'April',
	5  => 'Mei',
	6  => 'Juni',
	7  => 'Juli',
	8  => 'Agustus',
	9  => 'September',
	10 => 'Oktober',
	11 => 'November',
	12 => 'Desember'
];
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Penggunaan Reagen Klorin
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
					href="<?= base_url('reagen'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Release Packing
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan Release Packing.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check mr-1"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-exclamation-circle mr-1"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Daftar Periode Audit Reagen
			</h6>
		</div>

		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-hover" width="100%">
					<thead class="thead-light">
						<tr>
							<th width="60" class="text-center">No</th>
							<th>Bulan</th>
							<th width="120" class="text-center">Tahun</th>
							<th width="180" class="text-center">Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($bulan_tahun)): ?>

							<?php $no = 1; ?>

							<?php foreach ($bulan_tahun as $row): ?>
								<tr>
									<td class="text-center">
										<?= $no++ ?>
									</td>

									<td>
										<?= isset($nama_bulan[(int) $row->bulan])
											? $nama_bulan[(int) $row->bulan]
											: '-' ?>
									</td>

									<td class="text-center">
										<?= html_escape($row->tahun) ?>
									</td>

									<td class="text-center">
										<a href="<?= base_url('reagen/detail-audit/' .(int) $row->bulan . '/' .(int) $row->tahun) ?>"
											class="btn btn-success btn-sm">
											Detail
										</a>
									</td>
								</tr>
							<?php endforeach; ?>

						<?php else: ?>

							<tr>
								<td colspan="4" class="text-center text-muted">
									Belum ada data Audit Reagen.
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