<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Master Tahapan Magnet Trap
		</h1>

		<a href="<?= base_url('master_tahapan_magnettrap/tambah') ?>"
			class="btn btn-primary btn-sm">
			<i class="fas fa-plus"></i> Tambah
		</a>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<?= $this->session->flashdata('success_msg') ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<?= $this->session->flashdata('error_msg') ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-hover"
					id="dataTable" width="100%" cellspacing="0">

					<thead class="text-center">
						<tr>
							<th width="7%">No</th>
							<th>Nama Tahapan</th>
							<th width="20%">Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($master_tahapan)): ?>
							<?php $no = 1; ?>
							<?php foreach ($master_tahapan as $row): ?>
								<tr>
									<td class="text-center">
										<?= $no++ ?>
									</td>

									<td>
										<?= html_escape($row->nama_tahapan) ?>
									</td>

									<td class="text-center">
										<a href="<?= base_url('master_tahapan_magnettrap/edit/' . $row->uuid) ?>"
											class="btn btn-warning btn-sm">
											Edit
										</a>

										<form method="post"
											action="<?= base_url('master_tahapan_magnettrap/hapus/' . $row->uuid) ?>"
											class="d-inline form-hapus">

											<?php if ($this->config->item('csrf_protection')): ?>
												<input type="hidden"
													name="<?= $this->security->get_csrf_token_name() ?>"
													value="<?= $this->security->get_csrf_hash() ?>">
											<?php endif; ?>

											<button type="submit" class="btn btn-danger btn-sm">
												Delete
											</button>
										</form>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="3" class="text-center">
									Belum ada data tahapan.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>

				</table>
			</div>

		</div>
	</div>

</div>

<script>
	$(document).on('submit', '.form-hapus', function(e) {
		e.preventDefault();

		const form = this;

		Swal.fire({
			title: 'Hapus Tahapan?',
			text: 'Data tahapan yang dihapus tidak dapat dikembalikan.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#858796',
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				form.submit();
			}
		});
	});
</script>
