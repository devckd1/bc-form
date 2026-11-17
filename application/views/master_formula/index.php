<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Master Formula
		</h1>

		<a href="<?= base_url('master_formula/tambah'); ?>"
			class="btn btn-primary btn-sm shadow-sm">
			<i class="fas fa-plus fa-sm mr-1"></i>
			Tambah
		</a>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success alert-dismissible fade show text-center">
			<i class="fas fa-check-circle mr-1"></i>
			<?= $this->session->flashdata('success_msg'); ?>
			<button type="button" class="close" data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger alert-dismissible fade show text-center">
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
				<i class="fas fa-book mr-1"></i>
				Daftar Master Formula
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
							<th width="5%">No.</th>
							<th>Nama Produk</th>
							<th>Nama Formulasi</th>
							<th>No. Dokumen</th>
							<th>Revisi</th>
							<th>Formula</th>
							<th width="12%">Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($formula)): ?>
							<?php $no = 1; ?>
							<?php foreach ($formula as $row): ?>

								<tr>
									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?= html_escape($row->nama_produk ?? '-'); ?>
									</td>

									<td>
										<?= html_escape($row->nama_formulasi); ?>
									</td>

									<td class="text-center">
										<?= html_escape($row->no_dokumen ?: '-'); ?>
									</td>

									<td class="text-center">
										<?= html_escape($row->revisi ?: '-'); ?>
									</td>

									<td>
										<?= nl2br(html_escape($row->formula ?: '-')); ?>
									</td>

									<td class="text-center text-nowrap">

										<button type="button"
											class="btn btn-info btn-sm btn-detail"
											data-url="<?= base_url('master_formula/detail/' . rawurlencode($row->uuid)); ?>"
											title="Detail">
											<i class="fas fa-eye"></i>
										</button>

										<a href="<?= base_url('master_formula/edit/' . rawurlencode($row->uuid)); ?>"
											class="btn btn-warning btn-sm"
											title="Edit">
											<i class="fas fa-edit"></i>
										</a>

										<button type="button"
											class="btn btn-danger btn-sm btn-hapus"
											data-url="<?= base_url('master_formula/hapus/' . rawurlencode($row->uuid)); ?>"
											data-produk="<?= html_escape($row->nama_produk ?? '-'); ?>"
											data-formulasi="<?= html_escape($row->nama_formulasi ?? '-'); ?>"
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

<div class="modal fade" id="modalDetailFormula" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">
					<i class="fas fa-eye mr-1"></i>
					Detail Master Formula
				</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="detailFormulaContent">
				<div class="text-center py-4">
					<i class="fas fa-spinner fa-spin"></i>
					Memuat data...
				</div>
			</div>

		</div>
	</div>
</div>

<script>
	$(document).ready(function() {

		// DataTables
		if (!$.fn.DataTable.isDataTable('#dataTable')) {
			$('#dataTable').DataTable({
				responsive: true,
				autoWidth: false,
				ordering: true,
				pageLength: 10,
				language: {
					emptyTable: "Belum ada data Master Formula",
					zeroRecords: "Data tidak ditemukan",
					search: "Cari:",
					lengthMenu: "Tampilkan _MENU_ data",
					info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
					infoEmpty: "Tidak ada data",
					paginate: {
						previous: "Sebelumnya",
						next: "Selanjutnya"
					}
				}
			});
		}

		// Konfirmasi hapus
		$(document).on('click', '.btn-hapus', function() {

			const url = $(this).data('url');
			const produk = $(this).data('produk');

			Swal.fire({
				title: 'Hapus Master Formula?',
				html: 'Formula untuk produk <b>' + $('<div>').text(produk).html() + '</b> akan dihapus.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#858796',
				confirmButtonText: 'Ya, Hapus',
				cancelButtonText: 'Batal'
			}).then((result) => {

				if (result.isConfirmed) {

					const form = $('<form>', {
						method: 'POST',
						action: url
					});

					// CSRF CodeIgniter
					form.append(
						$('<input>', {
							type: 'hidden',
							name: '<?= $this->security->get_csrf_token_name(); ?>',
							value: '<?= $this->security->get_csrf_hash(); ?>'
						})
					);

					$('body').append(form);
					form.submit();
				}
			});
		});

	});

	// Detail Master Formula
	$(document).on('click', '.btn-detail', function() {

		const url = $(this).data('url');

		$('#detailFormulaContent').html(`
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin"></i>
            Memuat data...
        </div>
    `);

		$('#modalDetailFormula').modal('show');

		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'html',
			success: function(response) {
				$('#detailFormulaContent').html(response);
			},
			error: function() {
				$('#detailFormulaContent').html(`
                <div class="alert alert-danger mb-0">
                    Gagal memuat detail Master Formula.
                </div>
            `);
			}
		});
	});
</script>
