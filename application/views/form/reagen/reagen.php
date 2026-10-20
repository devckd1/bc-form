
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Penggunaan Reagen Klorin</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-exclamation-circle"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
	<?php endif; ?>

	<?php $tipe_user = $this->session->userdata('tipe_user'); ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('reagen/tambah') ?>"
					class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>

				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('reagen/audit') ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">
						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit
					</a>
				<?php endif; ?>
			</div>

			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th width="50" class="text-center">No</th>
							<th>Bulan</th>
							<th>Tahun</th>
							<th width="300" class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$nama_bulan = [
							1 => 'Januari',
							2 => 'Februari',
							3 => 'Maret',
							4 => 'April',
							5 => 'Mei',
							6 => 'Juni',
							7 => 'Juli',
							8 => 'Agustus',
							9 => 'September',
							10 => 'Oktober',
							11 => 'November',
							12 => 'Desember'
						];
						?>

						<?php foreach ($bulan_tahun as $row): ?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $nama_bulan[$row->bulan] ?></td>
								<td><?= $row->tahun ?></td>
								<td class="text-center">
									<a href="<?= base_url('reagen/detail/' . $row->bulan . '/' . $row->tahun) ?>"
										class="btn btn-success btn-sm">
										Detail
									</a>

									<button type="button"
										class="btn btn-info btn-sm"
										data-toggle="modal"
										data-target="#verifikasiModal"
										data-bulan="<?= $row->bulan ?>"
										data-tahun="<?= $row->tahun ?>">
										Verifikasi
									</button>

									<a href="<?= base_url('reagen/cetak/' . $row->bulan . '/' . $row->tahun) ?>"
										class="btn btn-danger btn-sm">
										PDF
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal konfirmasi verifikasi -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog"
	aria-labelledby="verifikasiModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="verifikasiForm" method="post" action="">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="verifikasiModalLabel">
						Konfirmasi Verifikasi
					</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					Apakah yakin ingin verifikasi semua data bulan
					<strong><span id="modalBulan"></span></strong>
					tahun
					<strong><span id="modalTahun"></span></strong>?
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal">
						Batal
					</button>
					<button type="submit" class="btn btn-success">
						Ya, Verifikasi
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$('#verifikasiModal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget);
		var bulan = button.data('bulan');
		var tahun = button.data('tahun');
		var modal = $(this);

		modal.find('#modalBulan').text(bulan);
		modal.find('#modalTahun').text(tahun);

		modal.find('#verifikasiForm').attr(
			'action',
			'<?= base_url("reagen/verifikasi/") ?>' + bulan + '/' + tahun
		);
	});
</script>
