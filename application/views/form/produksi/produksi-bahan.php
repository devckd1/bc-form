<?php
defined('BASEPATH') or exit('No direct script access allowed');

$mode = $mode ?? 'qc';
$isAudit = ($mode === 'audit');

$title = $isAudit
	? 'Audit Raw Material'
	: 'Raw Material';

$formAction = $isAudit
	? 'produksi/bahan-audit/' . rawurlencode($produksi->uuid)
	: 'produksi/bahan/' . rawurlencode($produksi->uuid);

$backUrl = $isAudit
	? 'produksi/audit'
	: 'produksi';
?>
<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		<?= html_escape($title); ?>

		<?php if ($isAudit): ?>
			<span class="badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($backUrl); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $isAudit
						? 'Daftar Audit Produksi'
						: 'Daftar Laporan Verifikasi Proses Produksi'; ?>
				</a>
			</li>

			<li class="breadcrumb-item active" aria-current="page">
				<?= $isAudit ? 'Edit' : 'List Raw Material'; ?>
			</li>
		</ol>
	</nav>

	<!-- Info Audit -->
	<?php if ($isAudit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Proses Produksi.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" method="post" action="<?= base_url($formAction); ?>">
				<label class="form-label font-weight-bold">Produk : <?= $produksi->nama_produk; ?></label><br>
				<hr>
				<div class="form-group row">
					<div class="col-sm-4">
						<div class="d-flex align-items-center">
							<label class="form-label font-weight-bold mb-0 me-3">Kode Produksi</label>
							<input type="text" name="kode_produksi" class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : '' ?>" value="<?= $produksi->kode_produksi; ?>">
						</div>
						<div class="invalid-feedback <?= !empty(form_error('kode_produksi')) ? 'd-block' : ''; ?>">
							<?= form_error('kode_produksi') ?>
						</div>
					</div>
				</div>
				<hr>
				<label class="form-label font-weight-bold">TEPUNG TERIGU</label>
				<div class="form-group row">
					<!-- Kode Input -->
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Kode</label>
						<input type="text" name="tegu_kode" class="form-control <?= form_error('tegu_kode') ? 'is-invalid' : '' ?>" value="<?= $produksi->tegu_kode; ?>">
						<div class="invalid-feedback <?= !empty(form_error('tegu_kode')) ? 'd-block' : ''; ?>">
							<?= form_error('tegu_kode') ?>
						</div>
					</div>
					<br>
					<!-- Berat Input -->
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Berat</label>
						<input type="text" name="tegu_berat" class="form-control <?= form_error('tegu_berat') ? 'is-invalid' : '' ?>" value="<?= $produksi->tegu_berat; ?>">
						<div class="invalid-feedback <?= !empty(form_error('tegu_berat')) ? 'd-block' : ''; ?>">
							<?= form_error('tegu_berat') ?>
						</div>
					</div>

					<!-- Sensori Radio Buttons -->
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Sensori</label>
						<div class="form-check">
							<input type="radio" name="tegu_sens" value="oke" class="form-check-input <?= form_error('tegu_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->tegu_sens == 'oke') ? 'checked' : ''; ?>>
							<label class="form-check-label">Oke</label>
						</div>
						<div class="form-check">
							<input type="radio" name="tegu_sens" value="tidak" class="form-check-input <?= form_error('tegu_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->tegu_sens == 'tidak') ? 'checked' : ''; ?>>
							<label class="form-check-label">Tidak</label>
						</div>
						<div class="invalid-feedback <?= !empty(form_error('tegu_sens')) ? 'd-block' : ''; ?>">
							<?= form_error('tegu_sens') ?>
						</div>
					</div>
				</div>
				<hr>
				<label class="form-label font-weight-bold">TAPIOKA STRACTH</label>
				<div class="form-group row">
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Kode</label>
						<input type="text" name="tapioka_kode" class="form-control <?= form_error('tapioka_kode') ? 'is-invalid' : '' ?>" value="<?= $produksi->tapioka_kode; ?>">
						<div class="invalid-feedback <?= !empty(form_error('tapioka_kode')) ? 'd-block' : ''; ?>">
							<?= form_error('tapioka_kode') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Berat</label>
						<input type="text" name="tapioka_berat" class="form-control <?= form_error('tapioka_berat') ? 'is-invalid' : '' ?>" value="<?= $produksi->tapioka_berat; ?>">
						<div class="invalid-feedback <?= !empty(form_error('tapioka_berat')) ? 'd-block' : ''; ?>">
							<?= form_error('tapioka_berat') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Sensori</label>
						<div class="form-check">
							<input type="radio" name="tapioka_sens" value="oke" class="form-check-input <?= form_error('tapioka_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->tapioka_sens == 'oke') ? 'checked' : ''; ?>>
							<label class="form-check-label">Oke</label>
						</div>
						<div class="form-check">
							<input type="radio" name="tapioka_sens" value="tidak" class="form-check-input <?= form_error('tapioka_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->tapioka_sens == 'tidak') ? 'checked' : ''; ?>>
							<label class="form-check-label">Tidak</label>
						</div>
						<div class="invalid-feedback <?= !empty(form_error('tapioka_sens')) ? 'd-block' : ''; ?>">
							<?= form_error('tapioka_sens') ?>
						</div>
					</div>
				</div>
				<hr>
				<label class="form-label font-weight-bold">RAGI</label>
				<div class="form-group row">
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Kode</label>
						<input type="text" name="ragi_kode" class="form-control <?= form_error('ragi_kode') ? 'is-invalid' : '' ?>" value="<?= $produksi->ragi_kode; ?>">
						<div class="invalid-feedback <?= !empty(form_error('ragi_kode')) ? 'd-block' : ''; ?>">
							<?= form_error('ragi_kode') ?>
						</div>
					</div>

					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Berat</label>
						<input type="text" name="ragi_berat" class="form-control <?= form_error('ragi_berat') ? 'is-invalid' : '' ?>" value="<?= $produksi->ragi_berat; ?>">
						<div class="invalid-feedback <?= !empty(form_error('ragi_berat')) ? 'd-block' : ''; ?>">
							<?= form_error('ragi_berat') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Sensori</label>
						<div class="form-check">
							<input type="radio" name="ragi_sens" value="oke" class="form-check-input <?= form_error('ragi_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->ragi_sens == 'oke') ? 'checked' : ''; ?>>
							<label class="form-check-label">Oke</label>
						</div>
						<div class="form-check">
							<input type="radio" name="ragi_sens" value="tidak" class="form-check-input <?= form_error('ragi_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->ragi_sens == 'tidak') ? 'checked' : ''; ?>>
							<label class="form-check-label">Tidak</label>
						</div>
						<div class="invalid-feedback <?= !empty(form_error('ragi_sens')) ? 'd-block' : ''; ?>">
							<?= form_error('ragi_sens') ?>
						</div>
					</div>
				</div>
				<hr>
				<label class="form-label font-weight-bold">BREAD IMPROVER</label>
				<div class="form-group row">
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Kode</label>
						<input type="text" name="bread_kode" class="form-control <?= form_error('bread_kode') ? 'is-invalid' : '' ?>" value="<?= $produksi->bread_kode; ?>">
						<div class="invalid-feedback <?= !empty(form_error('bread_kode')) ? 'd-block' : ''; ?>">
							<?= form_error('bread_kode') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Berat</label>
						<input type="text" name="bread_berat" class="form-control <?= form_error('bread_berat') ? 'is-invalid' : '' ?>" value="<?= $produksi->bread_berat; ?>">
						<div class="invalid-feedback <?= !empty(form_error('bread_berat')) ? 'd-block' : ''; ?>">
							<?= form_error('bread_berat') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Sensori</label>
						<div class="form-check">
							<input type="radio" name="bread_sens" value="oke" class="form-check-input <?= form_error('bread_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->bread_sens == 'oke') ? 'checked' : ''; ?>>
							<label class="form-check-label">Oke</label>
						</div>
						<div class="form-check">
							<input type="radio" name="bread_sens" value="tidak" class="form-check-input <?= form_error('bread_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->bread_sens == 'tidak') ? 'checked' : ''; ?>>
							<label class="form-check-label">Tidak</label>
						</div>
						<div class="invalid-feedback <?= !empty(form_error('bread_sens')) ? 'd-block' : ''; ?>">
							<?= form_error('bread_sens') ?>
						</div>
					</div>
				</div>

				<!-- PREMIX FORM AREA -->
				<hr>
				<div class="form-area" id="form-produksi-wrapper">
					<label class="form-label font-weight-bold">Premix</label>

					<?php
					$produksi_data = json_decode($produksi->premix, true);
					if (!is_array($produksi_data)) {
						echo "<div class='alert alert-danger'>Data Premix tidak valid.</div>";
						$produksi_data = [];
					}

					if (empty($produksi_data)) {
						echo "<div class='text-muted mb-3'>Belum ada data premix. Klik tombol <strong>+ Tambah Premix</strong> untuk menambahkan.</div>";
					}

					foreach ($produksi_data as $i => $detail):
						$nama_premix = $detail['nama_premix'] ?? '';
						$kode = $detail['kode'] ?? '';
						$berat = $detail['berat'] ?? '';
						$sens = $detail['sens'] ?? '';
					?>
						<div class="produksi-group border p-3 mb-3 rounded bg-light" data-index="<?= $i ?>">
							<div class="form-group row">
								<div class="col-sm-3">
									<label>Nama Premix</label>
									<input type="text" name="nama_premix[<?= $i ?>]" class="form-control" value="<?= $nama_premix ?>">
								</div>
								<div class="col-sm-2">
									<label>Kode Produksi</label>
									<input type="text" name="kode[<?= $i ?>]" class="form-control" value="<?= $kode ?>">
								</div>
								<div class="col-sm-2">
									<label>Berat</label>
									<input type="text" name="berat[<?= $i ?>]" class="form-control" value="<?= $berat ?>">
								</div>
								<div class="col-sm-3">
									<label>Sensori</label><br>
									<div class="form-check form-check-inline">
										<input type="radio" name="sens[<?= $i ?>]" value="oke" class="form-check-input" <?= ($sens === 'oke') ? 'checked' : '' ?>>
										<label class="form-check-label">Oke</label>
									</div>
									<div class="form-check form-check-inline">
										<input type="radio" name="sens[<?= $i ?>]" value="tidak" class="form-check-input" <?= ($sens === 'tidak') ? 'checked' : '' ?>>
										<label class="form-check-label">Tidak</label>
									</div>
								</div>
								<div class="col-sm-2 d-flex align-items-end">
									<button type="button" class="btn btn-danger btn-remove">Hapus</button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<button type="button" class="btn btn-primary mt-2" id="add-produksi">+ Tambah Premix</button>

				<div class="produksi-group border p-3 mb-3 rounded bg-light d-none" id="produksi-template">
					<div class="form-group row">
						<div class="col-sm-3">
							<label>Nama Premix</label>
							<input type="text" name="nama_premix[]" class="form-control">
						</div>
						<div class="col-sm-2">
							<label>Kode Produksi</label>
							<input type="text" name="kode[]" class="form-control">
						</div>
						<div class="col-sm-2">
							<label>Berat</label>
							<input type="text" name="berat[]" class="form-control">
						</div>
						<div class="col-sm-3">
							<label>Sensori</label><br>
							<div class="form-check form-check-inline">
								<input type="radio" value="oke" class="form-check-input">
								<label class="form-check-label">Oke</label>
							</div>
							<div class="form-check form-check-inline">
								<input type="radio" value="tidak" class="form-check-input">
								<label class="form-check-label">Tidak</label>
							</div>
						</div>
						<div class="col-sm-2 d-flex align-items-end">
							<button type="button" class="btn btn-danger btn-remove">Hapus</button>
						</div>
					</div>
				</div>
				<hr>

				<label class="form-label font-weight-bold">SHORTENING</label>
				<div class="form-group row">
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Kode</label>
						<input type="text" name="shortening_kode" class="form-control <?= form_error('shortening_kode') ? 'is-invalid' : '' ?>" value="<?= $produksi->shortening_kode; ?>">
						<div class="invalid-feedback <?= !empty(form_error('shortening_kode')) ? 'd-block' : ''; ?>">
							<?= form_error('shortening_kode') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Berat</label>
						<input type="text" name="shortening_berat" class="form-control <?= form_error('shortening_berat') ? 'is-invalid' : '' ?>" value="<?= $produksi->shortening_berat; ?>">
						<div class="invalid-feedback <?= !empty(form_error('shortening_berat')) ? 'd-block' : ''; ?>">
							<?= form_error('shortening_berat') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Sensori</label>
						<div class="form-check">
							<input type="radio" name="shortening_sens" value="oke" class="form-check-input <?= form_error('shortening_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->shortening_sens == 'oke') ? 'checked' : ''; ?>>
							<label class="form-check-label">Oke</label>
						</div>
						<div class="form-check">
							<input type="radio" name="shortening_sens" value="tidak" class="form-check-input <?= form_error('shortening_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->shortening_sens == 'tidak') ? 'checked' : ''; ?>>
							<label class="form-check-label">Tidak</label>
						</div>
						<div class="invalid-feedback <?= !empty(form_error('shortening_sens')) ? 'd-block' : ''; ?>">
							<?= form_error('shortening_sens') ?>
						</div>
					</div>
				</div>
				<hr>
				<label class="form-label font-weight-bold">CHILL WATER (15 ± 1°C)</label>
				<div class="form-group row">
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Kode</label>
						<input type="text" name="chill_water_kode" class="form-control <?= form_error('chill_water_kode') ? 'is-invalid' : '' ?>" value="<?= $produksi->chill_water_kode; ?>">
						<div class="invalid-feedback <?= !empty(form_error('chill_water_kode')) ? 'd-block' : ''; ?>">
							<?= form_error('chill_water_kode') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Berat</label>
						<input type="text" name="chill_water_berat" class="form-control <?= form_error('chill_water_berat') ? 'is-invalid' : '' ?>" value="<?= $produksi->chill_water_berat; ?>">
						<div class="invalid-feedback <?= !empty(form_error('chill_water_berat')) ? 'd-block' : ''; ?>">
							<?= form_error('chill_water_berat') ?>
						</div>
					</div>
					<div class="col-sm-3">
						<label class="form-label font-weight-bold mb-2">Sensori</label>
						<div class="form-check">
							<input type="radio" name="chill_water_sens" value="oke" class="form-check-input <?= form_error('chill_water_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->chill_water_sens == 'oke') ? 'checked' : ''; ?>>
							<label class="form-check-label">Oke</label>
						</div>
						<div class="form-check">
							<input type="radio" name="chill_water_sens" value="tidak" class="form-check-input <?= form_error('chill_water_sens') ? 'is-invalid' : '' ?>" <?= ($produksi->chill_water_sens == 'tidak') ? 'checked' : ''; ?>>
							<label class="form-check-label">Tidak</label>
						</div>
						<div class="invalid-feedback <?= !empty(form_error('chill_water_sens')) ? 'd-block' : ''; ?>">
							<?= form_error('chill_water_sens') ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url($backUrl); ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<style type="text/css">
	.breadcrumb {
		background-color: #2E86C1;
	}

	.badge-mode-audit {
		display: inline-block;
		padding: 5px 10px;
		margin-left: 5px;
		color: #fff;
		background-color: #5a3298;
		border-radius: 4px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}
</style>
<script>
	$(document).ready(function() {
		let index = $('#form-produksi-wrapper .produksi-group').length;

		const spesifikasi = {
			'BC MIX': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Yellow',
						berat: '0.070'
					},
					{
						nama: 'Premix Orange',
						berat: '0.060'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'BC ORANGE': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Orange',
						berat: '0.213'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'BC YELLOW': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Yellow',
						berat: '0.070'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'BC WHITE INSTITUSI': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
					nama: 'Premix Gula & Garam',
					berat: '7.440'
				}]
			},
			'BC CRISPY MIX': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Yellow',
						berat: '0.070'
					},
					{
						nama: 'Premix Orange',
						berat: '0.060'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'BREADCRUMBS MIX SINTETIS (CPI)': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Yellow',
						berat: '0.070'
					},
					{
						nama: 'Premix Orange',
						berat: '0.060'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'BREADCRUMBS ORANGE SINTETIS (CPI)': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Orange',
						berat: '0.213'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'BREADCRUMBS YELLOW SINTETIS (CPI)': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Yellow',
						berat: '0.070'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'CPI BREADCRUMBS WHITE (INSTITUSI)': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
					nama: 'Premix Gula & Garam',
					berat: '7.440'
				}]
			},
			'FIESTA CRISPY CRUMBS MIX': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				premix: [{
						nama: 'Premix Yellow',
						berat: '0.070'
					},
					{
						nama: 'Premix Orange',
						berat: '0.060'
					},
					{
						nama: 'Premix Gula & Garam',
						berat: '7.440'
					}
				]
			},
			'BREADCRUMBS MIX SINTETIS HALUS (CPI)': {
				tegu_berat: '329.240',
				tapioka_berat: '22.700',
				ragi_berat: '7.320',
				bread_berat: '0.380',
				shortening_berat: '2.520',
				chill_water_berat: '180',
				/*premix: [
				    { nama: 'Premix Yellow', berat: '0.070' },
				    { nama: 'Premix Orange', berat: '0.060' },
				    { nama: 'Premix Gula & Garam', berat: '7.440' }
				]*/
			}
		};

		const namaProduk = "<?= strtoupper($produksi->nama_produk) ?>";
		const premixDataFromPHP = <?= json_encode($produksi_data) ?>;

		function generatePremixRow(item = {
			nama: '',
			berat: '',
			kode: ''
		}) {
			const template = $('#produksi-template').clone();
			template.removeClass('d-none').removeAttr('id');
			template.find('[name="nama_premix[]"]').attr('name', `nama_premix[${index}]`).val(item.nama);
			template.find('[name="kode[]"]').attr('name', `kode[${index}]`).val(item.kode || '');
			template.find('[name="berat[]"]').attr('name', `berat[${index}]`).val(item.berat);
			template.find('input[type="radio"]').each(function() {
				const val = $(this).val();
				$(this).attr('name', `sens[${index}]`);
				if (val === 'oke') {
					$(this).prop('checked', true);
				}
			});
			$('#form-produksi-wrapper').append(template);
			index++;
		}

		if ((!premixDataFromPHP || premixDataFromPHP.length === 0) && spesifikasi[namaProduk]) {
			const data = spesifikasi[namaProduk];
			$('[name="tegu_berat"]').val(data.tegu_berat);
			$('[name="tapioka_berat"]').val(data.tapioka_berat);
			$('[name="ragi_berat"]').val(data.ragi_berat);
			$('[name="bread_berat"]').val(data.bread_berat);
			$('[name="shortening_berat"]').val(data.shortening_berat);
			$('[name="chill_water_berat"]').val(data.chill_water_berat);
			$('#form-produksi-wrapper .produksi-group').remove();
			data.premix.forEach(p => generatePremixRow(p));
		}

		// Tambah premix manual
		$('#add-produksi').on('click', function() {
			generatePremixRow();
		});

		// Hapus baris premix
		$(document).on('click', '.btn-remove', function() {
			$(this).closest('.produksi-group').remove();
		});
	});
</script>
