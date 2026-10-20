<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-0 text-gray-800">
			Master Data Suhu
		</h1>

		<a href="<?= base_url('master-suhu/tambah'); ?>"
			class="btn btn-primary btn-sm">
			<i class="fas fa-plus mr-1"></i>
			Tambah Master Suhu
		</a>

	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>

		<div class="alert alert-success alert-dismissible fade show">
			<i class="fas fa-check-circle mr-1"></i>
			<?= $this->session->flashdata('success_msg'); ?>

			<button type="button"
				class="close"
				data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>

	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>

		<div class="alert alert-danger alert-dismissible fade show">
			<i class="fas fa-exclamation-circle mr-1"></i>
			<?= $this->session->flashdata('error_msg'); ?>

			<button type="button"
				class="close"
				data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>

	<?php endif; ?>

	<div class="card shadow mb-4">

		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Daftar Standar Suhu
			</h6>
		</div>

		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered table-hover"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead class="text-center">
						<tr>
							<th width="5%">No</th>
							<th>Lokasi</th>
							<th>Standar Suhu</th>
							<th>Standar RH</th>
							<th>Keterangan</th>
							<th width="10%">Status</th>
							<th width="18%">Aksi</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($master_suhu)): ?>

							<?php $no = 1; ?>

							<?php foreach ($master_suhu as $row): ?>

								<tr>

									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?= html_escape($row->nama_lokasi); ?>
									</td>

									<td class="text-center">
										<span class="badge badge-info">
											<?= html_escape($row->standar_suhu); ?> °C
										</span>
									</td>

									<td class="text-center">

										<?php if (!empty($row->standar_rh)): ?>

											<span class="badge badge-secondary">
												<?= html_escape($row->standar_rh); ?> %
											</span>

										<?php else: ?>

											<span class="text-muted">
												-
											</span>

										<?php endif; ?>

									</td>

									<td>
										<?= !empty($row->keterangan)
											? html_escape($row->keterangan)
											: '-'; ?>
									</td>

									<td class="text-center">

										<?php if ((int) $row->is_active === 1): ?>

											<span class="badge badge-success">
												Aktif
											</span>

										<?php else: ?>

											<span class="badge badge-danger">
												Tidak Aktif
											</span>

										<?php endif; ?>

									</td>

									<td class="text-center">

										<a href="<?= base_url('master-suhu/edit/' . $row->uuid); ?>"
											class="btn btn-warning btn-sm"
											title="Edit">

											<i class="fas fa-edit"></i>
										</a>

										<button type="button"
											class="btn btn-danger btn-sm btn-delete"
											data-url="<?= base_url('master-suhu/delete/' . $row->uuid); ?>"
											title="Hapus">

											<i class="fas fa-trash"></i>
										</button>

									</td>

								</tr>

							<?php endforeach; ?>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

		</div>

	</div>

</div>

<script>
	$(document).on('click', '.btn-delete', function() {

		const url = $(this).data('url');

		Swal.fire({
			title: 'Hapus Master Suhu?',
			text: 'Data master ini akan dihapus.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {

			if (result.isConfirmed) {
				window.location.href = url;
			}

		});

	});
</script>
