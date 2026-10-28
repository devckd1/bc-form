<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Master Dokumen</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url(); ?>"></a>
				Master Dokumen
				</a>
			</li>
		</ol>
	</nav>

	<!-- Flash Message -->
	<?php if ($this->session->flashdata('success_msg')) : ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?= $this->session->flashdata('success_msg'); ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')) : ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= $this->session->flashdata('error_msg'); ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<!-- Data Table Card -->
	<div class="card shadow mb-4">

		<div class="card-header py-3 d-flex justify-content-between align-items-center">
			<h6 class="m-0 font-weight-bold text-primary">
				Daftar Master Dokumen
			</h6>

			<a href="<?= base_url('master-dokumen/tambah'); ?>"
				class="btn btn-primary btn-sm">
				<i class="fas fa-plus"></i> Tambah
			</a>
		</div>

		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-striped"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead class="thead-light">
						<tr class="text-center">
							<th width="50">No.</th>
							<th>Nomor Dokumen</th>
							<th>Nama Dokumen</th>
							<th width="130">Tanggal Terbit</th>
							<th width="80">Revisi</th>
							<th width="130">Tanggal Revisi</th>
							<th width="120">Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($master_dokumen)) : ?>
							<?php $no = 1; ?>

							<?php foreach ($master_dokumen as $dokumen) : ?>
								<tr>
									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?= html_escape($dokumen->nomor_dokumen); ?>
									</td>

									<td>
										<?= html_escape($dokumen->nama_dokumen); ?>
									</td>

									<td class="text-center">
										<?= !empty($dokumen->tanggal_terbit)
											? date('d-m-Y', strtotime($dokumen->tanggal_terbit))
											: '-'; ?>
									</td>

									<td class="text-center">
										<span class="badge badge-info">
											<?= html_escape($dokumen->revisi); ?>
										</span>
									</td>

									<td class="text-center">
										<?= !empty($dokumen->tanggal_revisi)
											? date('d-m-Y', strtotime($dokumen->tanggal_revisi))
											: '-'; ?>
									</td>

									<td class="text-center">
										<a href="<?= base_url(
														'master-dokumen/edit/' . rawurlencode($dokumen->uuid)
													); ?>" class="btn btn-warning btn-sm">
											Edit
										</a>

										<button type="button"
											class="btn btn-danger btn-sm btn-delete"
											data-url="<?= base_url(
															'master-dokumen/delete/' . rawurlencode($dokumen->uuid)
														); ?>"
											data-nomor="<?= html_escape($dokumen->nomor_dokumen); ?>">
											Delete
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

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>
<script>
	$(document).ready(function() {

		// DataTables
		if ($.fn.DataTable) {
			$('#dataTable').DataTable({
				language: {
					search: 'Cari:',
					lengthMenu: 'Tampilkan _MENU_ data',
					zeroRecords: 'Data dokumen tidak ditemukan',
					info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
					infoEmpty: 'Tidak ada data',
					infoFiltered: '(difilter dari _MAX_ total data)',
					paginate: {
						first: 'Pertama',
						last: 'Terakhir',
						next: 'Berikutnya',
						previous: 'Sebelumnya'
					}
				},
				columnDefs: [{
					targets: [0, 3, 4, 5, 6],
					orderable: false,
					searchable: false
				}]
			});
		}

		// Konfirmasi hapus
		$('.btn-delete').on('click', function() {
			var url = $(this).data('url');
			var nomor = $(this).data('nomor');

			if (typeof Swal !== 'undefined') {
				Swal.fire({
					title: 'Hapus Dokumen?',
					text: 'Dokumen ' + nomor + ' akan dinonaktifkan.',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#858796',
					confirmButtonText: 'Ya, hapus',
					cancelButtonText: 'Batal'
				}).then(function(result) {
					if (result.isConfirmed) {
						window.location.href = url;
					}
				});
			} else if (confirm('Hapus dokumen ' + nomor + '?')) {
				window.location.href = url;
			}
		});

	});
</script>
