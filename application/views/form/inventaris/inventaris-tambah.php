<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">Checklist Inventaris Peralatan QC Bread Crumb</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('inventaris') ?>"><i class="fas fa-arrow-left"></i> Kembali</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form method="post" action="<?= base_url('inventaris/tambah'); ?>">
				<?php
				$produksi_data = $this->session->userdata('produksi_data');
				$tanggal_sess = $produksi_data['tanggal'] ?? date('Y-m-d');
				$shift_sess = $produksi_data['shift'] ?? '';
				?>
				<div class="form-group row">
					<div class="col-md-4">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date" class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= set_value('date', $tanggal_sess) ?>">
						<div class="invalid-feedback"><?= form_error('date') ?></div>
					</div>
					<div class="col-md-4">
						<label class="font-weight-bold">Shift</label>
						<select name="shift" class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>">
							<option disabled <?= empty($shift_sess) ? 'selected' : '' ?>>Pilih Shift</option>
							<option value="1" <?= set_select('shift', '1', $shift_sess == '1') ?>>Shift 1</option>
							<option value="2" <?= set_select('shift', '2', $shift_sess == '2') ?>>Shift 2</option>
							<option value="3" <?= set_select('shift', '3', $shift_sess == '3') ?>>Shift 3</option>
						</select>
						<div class="invalid-feedback"><?= form_error('shift') ?></div>
					</div>
				</div>

				<hr>
				<label class="font-weight-bold">Checklist Alat</label>
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead-light">
							<tr class="text-center">
								<th style="width: 30%">Nama Alat</th>
								<th style="width: 10%">Jumlah</th>
								<th style="width: 30%">Kondisi Awal</th>
								<th style="width: 30%">Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$opsi_kondisi = ['Tidak Tersedia', 'Baik', 'Rusak', 'Hilang', 'Bersih', 'Kotor', 'Habis', 'Baik Bersih Masih'];
							foreach ($alat_list as $index => $alat): ?>
								<tr>
									<td>
										<?= htmlspecialchars($alat->nama_alat) ?>
										<input type="hidden" name="nama_alat[]" value="<?= htmlspecialchars($alat->nama_alat) ?>">
									</td>
									<td class="text-center">
										<input type="hidden" name="jumlah[]" value="<?= htmlspecialchars($alat->jumlah) ?>">
										<?= htmlspecialchars($alat->jumlah) ?>
									</td>
									<td>
										<select name="kondisi_awal[<?= $index ?>]" class="form-control form-control-sm">
											<?php foreach ($opsi_kondisi as $val): ?>
												<option value="<?= htmlspecialchars($val) ?>"
													<?= $val === 'Bersih' ? 'selected' : '' ?>>
													<?= htmlspecialchars($val) ?>
												</option>
											<?php endforeach; ?>
										</select>
									</td>
									<td>
										<input type="text" name="keterangan[]" class="form-control form-control-sm">
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div class="mt-3">
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
					<a href="<?= base_url('inventaris') ?>" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
				</div>
			</form>
		</div>
	</div>
</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.table td,
	.table th {
		vertical-align: middle;
	}
</style>
