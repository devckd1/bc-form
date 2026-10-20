<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$form_url = $is_audit
	? base_url('produksi/mixing-audit/' . rawurlencode($produksi->uuid))
	: base_url('produksi/mixing/' . rawurlencode($produksi->uuid));

$back_url = $is_audit
	? base_url('produksi/audit')
	: base_url('produksi');
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Proses Mixing
		<?php if ($is_audit): ?>
			<span class="badge badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= $back_url; ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Verifikasi Proses Produksi'
						: 'Daftar Laporan Verifikasi Proses Produksi'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit' : 'Edit'; ?>
			</li>
		</ol>
	</nav>

	<!-- Info Audit -->
	<?php if ($is_audit): ?>
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

			<form class="user" method="post" action="<?= $form_url; ?>">

				<label class="form-label font-weight-bold">
					Produk: <?= html_escape($produksi->nama_produk); ?>
				</label>
				<br>

				<label class="form-label font-weight-bold">
					Kode Produksi: <?= html_escape($produksi->kode_produksi); ?>
				</label>

				<hr>

				<label class="form-label font-weight-bold">MIXING DOUGH</label>

				<div class="form-group row">


					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Waktu Mixing (11 Menit)
						</label>

						<?php
						$mix_dough_waktu_1 = $produksi->mix_dough_waktu_1 ?? '';

						if ($is_audit && trim((string) $mix_dough_waktu_1) !== '') {
							if (
								!is_numeric($mix_dough_waktu_1) ||
								(float) $mix_dough_waktu_1 != 11
							) {
								$mix_dough_waktu_1 = '11';
							}
						}
						?>

						<input type="text"
							name="mix_dough_waktu_1"
							class="form-control <?= form_error('mix_dough_waktu_1') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('mix_dough_waktu_1', $mix_dough_waktu_1)); ?>">

						<div class="invalid-feedback <?= form_error('mix_dough_waktu_1') ? 'd-block' : ''; ?>">
							<?= form_error('mix_dough_waktu_1'); ?>
						</div>
					</div>


					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Hasil Mixing</label>
						<select class="form-control <?= form_error('mix_dough_hasil') ? 'invalid' : ''; ?>"
							name="mix_dough_hasil">
							<option value="1"
								<?= (string) set_value('mix_dough_hasil', $produksi->mix_dough_hasil) === '1' ? 'selected' : ''; ?>>
								Oke
							</option>
							<option value="2"
								<?= (string) set_value('mix_dough_hasil', $produksi->mix_dough_hasil) === '2' ? 'selected' : ''; ?>>
								Tidak Oke
							</option>
						</select>
						<div class="invalid-feedback <?= form_error('mix_dough_hasil') ? 'd-block' : ''; ?>">
							<?= form_error('mix_dough_hasil'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Nomor Mesin</label>
						<input type="text"
							name="mix_dough_mesin"
							class="form-control <?= form_error('mix_dough_mesin') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('mix_dough_mesin', $produksi->mix_dough_mesin)); ?>">
						<div class="invalid-feedback <?= form_error('mix_dough_mesin') ? 'd-block' : ''; ?>">
							<?= form_error('mix_dough_mesin'); ?>
						</div>
					</div>

				</div>

				<div class="form-group row">


					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Dough Cutting (630 - 670 g)
						</label>

						<?php
						$mix_dough_cutting = $produksi->mix_dough_cutting ?? '';

						if ($is_audit && trim((string) $mix_dough_cutting) !== '') {
							if (
								!is_numeric($mix_dough_cutting) ||
								(float) $mix_dough_cutting < 630 ||
								(float) $mix_dough_cutting > 670
							) {
								$mix_dough_cutting = '650';
							}
						}
						?>

						<input type="text"
							name="mix_dough_cutting"
							class="form-control <?= form_error('mix_dough_cutting') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('mix_dough_cutting', $mix_dough_cutting)); ?>">

						<div class="invalid-feedback <?= form_error('mix_dough_cutting') ? 'd-block' : ''; ?>">
							<?= form_error('mix_dough_cutting'); ?>
						</div>
					</div>


					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Suhu Adonan (29 - 31°C)
						</label>

						<?php
						$mix_dough_suhu_adonan = $produksi->mix_dough_suhu_adonan ?? '';

						if ($is_audit && trim((string) $mix_dough_suhu_adonan) !== '') {
							if (
								!is_numeric($mix_dough_suhu_adonan) ||
								(float) $mix_dough_suhu_adonan < 29 ||
								(float) $mix_dough_suhu_adonan > 31
							) {
								$mix_dough_suhu_adonan = '30';
							}
						}
						?>

						<input type="text"
							name="mix_dough_suhu_adonan"
							class="form-control <?= form_error('mix_dough_suhu_adonan') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('mix_dough_suhu_adonan', $mix_dough_suhu_adonan)); ?>">

						<div class="invalid-feedback <?= form_error('mix_dough_suhu_adonan') ? 'd-block' : ''; ?>">
							<?= form_error('mix_dough_suhu_adonan'); ?>
						</div>
					</div>

				</div>

				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Suhu Ruang (°C)</label>
						<input type="text"
							name="mix_dough_suhu_ruang"
							class="form-control <?= form_error('mix_dough_suhu_ruang') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('mix_dough_suhu_ruang', $produksi->mix_dough_suhu_ruang)); ?>">
						<div class="invalid-feedback <?= form_error('mix_dough_suhu_ruang') ? 'd-block' : ''; ?>">
							<?= form_error('mix_dough_suhu_ruang'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">RH Ruang (%)</label>
						<input type="text"
							name="mix_dough_rh_ruang"
							class="form-control <?= form_error('mix_dough_rh_ruang') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('mix_dough_rh_ruang', $produksi->mix_dough_rh_ruang)); ?>">
						<div class="invalid-feedback <?= form_error('mix_dough_rh_ruang') ? 'd-block' : ''; ?>">
							<?= form_error('mix_dough_rh_ruang'); ?>
						</div>
					</div>

				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= $back_url; ?>" class="btn btn-md btn-danger">
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

	.badge-mode-audit {
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 6px 9px;
		border-radius: 4px;
		vertical-align: middle;
	}
</style>
