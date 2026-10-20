<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Master Sanitasi
		</h1>

		<a href="<?= base_url('master-sanitasi/tambah'); ?>"
			class="btn btn-primary btn-sm shadow-sm">
			<i class="fas fa-plus fa-sm text-white-50"></i>
			Tambah Master
		</a>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success alert-dismissible fade show">
			<i class="fas fa-check-circle mr-1"></i>
			<?= $this->session->flashdata('success_msg'); ?>
			<button type="button" class="close" data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger alert-dismissible fade show">
			<i class="fas fa-exclamation-circle mr-1"></i>
			<?= $this->session->flashdata('error_msg'); ?>
			<button type="button" class="close" data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Daftar Master Sanitasi
			</h6>
		</div>

		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%">
					<thead class="text-center">
						<tr>
							<th width="5%">No</th>
							<th>Area</th>
							<th>Standar PPM</th>
							<th>Standar Suhu</th>
							<th>Keterangan</th>
							<th width="15%">Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($master_sanitasi)): ?>

							<?php $no = 1; ?>

							<?php foreach ($master_sanitasi as $row): ?>

								<tr>
									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td class="text-center">
										<?= htmlspecialchars($row->area); ?>
									</td>

									<td class="text-center">
										<?= htmlspecialchars($row->standar_ppm); ?>
									</td>

									<td class="text-center">
										<?= htmlspecialchars($row->standar_suhu); ?>
									</td>

									<td class="text-center">
										<span class="badge badge-success">
											<?= htmlspecialchars($row->keterangan); ?>
										</span>
									</td>

									<td class="text-center">

										<a href="<?= base_url('master-sanitasi/edit/' . $row->uuid); ?>"
											class="btn btn-warning btn-sm"
											title="Edit">
											<i class="fas fa-edit"></i>
										</a>

										<button type="button"
											class="btn btn-danger btn-sm btn-delete"
											data-url="<?= base_url('master-sanitasi/delete/' . $row->uuid); ?>"
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
	$(document).ready(function() {

		$('#dataTable').DataTable({
			responsive: true,
			pageLength: 10,
			language: {
				search: "Cari:",
				lengthMenu: "Tampilkan _MENU_ data",
				zeroRecords: "Data tidak ditemukan",
				info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
				infoEmpty: "Tidak ada data",
				paginate: {
					first: "Awal",
					last: "Akhir",
					next: "Berikutnya",
					previous: "Sebelumnya"
				}
			}
		});

		$('.btn-delete').on('click', function() {

			const url = $(this).data('url');

			Swal.fire({
				title: 'Hapus Master Sanitasi?',
				text: 'Data akan dihapus dari daftar master.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus',
				cancelButtonText: 'Batal',
				reverseButtons: true
			}).then((result) => {

				if (result.isConfirmed) {
					window.location.href = url;
				}

			});
		});

	});
</script>
