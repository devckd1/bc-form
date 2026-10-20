<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'pecahbelah/audit'
	: 'pecahbelah';

$form_url = $is_audit
	? 'pecahbelah/edit-audit/' . rawurlencode($pecahbelah->uuid)
	: 'pecahbelah/edit/' . rawurlencode($pecahbelah->uuid);

$benda_pecah = json_decode($pecahbelah->benda_pecah);

if (!is_array($benda_pecah) && !is_object($benda_pecah)) {
	$benda_pecah = [];
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Pemeriksaan Benda Mudah Pecah
		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		<?php endif; ?>			
		</h1>

	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url) ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Pemeriksaan Pecah Belah
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit' : 'Edit' ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Pecah Belah.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form
				method="post"
				action="<?= base_url($form_url); ?>"
				enctype="multipart/form-data">

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control"
							value="<?= htmlspecialchars($pecahbelah->date ?? '', ENT_QUOTES, 'UTF-8') ?>"
							required>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Shift</label>
						<select class="form-control" name="shift" required>
							<option value="1" <?= ($pecahbelah->shift ?? '') == 1 ? 'selected' : '' ?>>
								Shift 1
							</option>
							<option value="2" <?= ($pecahbelah->shift ?? '') == 2 ? 'selected' : '' ?>>
								Shift 2
							</option>
							<option value="3" <?= ($pecahbelah->shift ?? '') == 3 ? 'selected' : '' ?>>
								Shift 3
							</option>
						</select>
					</div>
				</div>

				<hr>

				<h5 class="font-weight-bold mb-3">
					Edit Data Benda Mudah Pecah
				</h5>

				<div class="table-responsive">
					<table class="table table-bordered table-sm align-middle text-center">
						<thead class="thead-dark">
							<tr>
								<th>No</th>
								<th class="text-left">Nama Benda</th>
								<th class="text-left">Area</th>
								<th class="text-left">Pemilik</th>
								<th>Jumlah</th>
								<th>Kondisi Awal</th>
								<th>Kondisi Akhir</th>
								<th>Keterangan</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($benda_pecah as $i => $b): ?>
								<tr>
									<td><?= $i + 1 ?></td>

									<td class="text-left">
										<input
											type="hidden"
											name="nama_barang[]"
											value="<?= htmlspecialchars($b->nama_barang ?? '', ENT_QUOTES, 'UTF-8') ?>">
										<?= htmlspecialchars($b->nama_barang ?? '', ENT_QUOTES, 'UTF-8') ?>
									</td>

									<td class="text-left">
										<input
											type="hidden"
											name="area[]"
											value="<?= htmlspecialchars($b->area ?? '', ENT_QUOTES, 'UTF-8') ?>">
										<?= htmlspecialchars($b->area ?? '', ENT_QUOTES, 'UTF-8') ?>
									</td>

									<td class="text-left">
										<input
											type="hidden"
											name="pemilik[]"
											value="<?= htmlspecialchars($b->pemilik ?? '', ENT_QUOTES, 'UTF-8') ?>">
										<?= htmlspecialchars($b->pemilik ?? '', ENT_QUOTES, 'UTF-8') ?>
									</td>

									<td>
										<input
											type="text"
											name="jumlah[]"
											class="form-control form-control-sm"
											value="<?= htmlspecialchars($b->jumlah ?? '', ENT_QUOTES, 'UTF-8') ?>">
									</td>

									<td>
										<input
											type="checkbox"
											name="kondisi_awal[<?= $i ?>]"
											value="Ok"
											<?= ($b->kondisi_awal ?? '') == 'Ok' ? 'checked' : '' ?>>
									</td>

									<td>
										<input
											type="checkbox"
											name="kondisi_akhir[<?= $i ?>]"
											value="Ok"
											<?= ($b->kondisi_akhir ?? '') == 'Ok' ? 'checked' : '' ?>>
									</td>

									<td>
										<input
											type="text"
											name="keterangan[]"
											class="form-control form-control-sm"
											value="<?= htmlspecialchars($b->keterangan ?? '', ENT_QUOTES, 'UTF-8') ?>">
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-success">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url) ?>" class="btn btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	th,
	td {
		vertical-align: middle !important;
	}

	.table th {
		background-color: #3498DB;
		color: white;
	}

	.badge-mode-audit {
		display: inline-block;
		padding: 7px 12px;
		border-radius: 5px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: bold;
	}
</style>
