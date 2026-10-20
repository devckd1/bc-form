<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Penerimaan Kemasan
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
					href="<?= base_url('penerimaankemasan'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Penerimaan Kemasan
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Penerimaan Kemasan.
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

			<i class="fas fa-times mr-1"></i>

			<?= $this->session->flashdata('error_msg'); ?>

		</div>

	<?php endif; ?>


	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>

						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Shift</th>
							<th>Jenis Kemasan</th>
							<th>Pemasok</th>
							<th>Kode Produksi</th>
							<th>Jumlah Datang</th>
							<th>Jumlah Reject</th>
							<th>Penerimaan</th>
							<th>Status SPV</th>
							<th>Action</th>
						</tr>

					</thead>

					<tbody>

						<?php $no = 1; ?>

						<?php foreach ($data as $val): ?>

							<tr>

								<td class="text-center">
									<?= $no; ?>
								</td>

								<td>
									<?= !empty($val->date)
										? date('d-m-Y', strtotime($val->date))
										: '-'; ?>
								</td>

								<td class="text-center">
									<?= htmlspecialchars(
										$val->shift ?? '-'
									); ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->jenis_kemasan ?? '-'
									); ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->pemasok ?? '-'
									); ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->kode_produksi ?? '-'
									); ?>
								</td>

								<td class="text-center">
									<?= htmlspecialchars(
										$val->jumlah_datang ?? '-'
									); ?>
								</td>

								<td class="text-center">
									<?= htmlspecialchars(
										$val->jumlah_reject ?? '-'
									); ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->penerimaan ?? '-'
									); ?>
								</td>

								<td class="text-center">

									<?php if ((int)$val->status_spv === 0): ?>

										<span style="color:#99a3a4;font-weight:bold;">
											Created
										</span>

									<?php elseif ((int)$val->status_spv === 1): ?>

										<span style="color:#28b463;font-weight:bold;">
											Verified
										</span>

									<?php elseif ((int)$val->status_spv === 2): ?>

										<span style="color:red;font-weight:bold;">
											Revision
										</span>

									<?php else: ?>

										<span style="color:#99a3a4;font-weight:bold;">
											-
										</span>

									<?php endif; ?>

								</td>

								<td class="text-center">

									<a href="<?= base_url('penerimaankemasan/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('penerimaankemasan/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Edit</span>
									</a>


								</td>

							</tr>

							<?php $no++; ?>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

			<br>
			<div class="mb-3">
				<form action="<?= base_url('penerimaankemasan/cetak-audit') ?>" method="post" class="form-inline">
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


<script>
	$(document).ready(function() {

		$('#dataTable').DataTable({
			"ordering": false,
			"searching": true
		});

	});
</script>
