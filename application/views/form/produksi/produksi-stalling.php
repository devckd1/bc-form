<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$form_url = $is_audit
	? base_url('produksi/stalling-audit/' . rawurlencode($produksi->uuid))
	: base_url('produksi/stalling/' . rawurlencode($produksi->uuid));

$back_url = $is_audit
	? base_url('produksi/audit')
	: base_url('produksi');

$tanggal_stall = (!empty($produksi->date_stall) && $produksi->date_stall !== '0000-00-00')
	? $produksi->date_stall
	: date('Y-m-d');
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Proses Stalling
		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">MODE AUDIT</span>
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
				<?= $is_audit ? 'Edit Audit' : 'Edit'; ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert"
			style="background-color:#f3e8ff;border-left:4px solid #6f42c1;color:#4b2a75;">
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

				<div class="form-group row">

					<div class="col-sm-3">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input type="date"
							name="date_stall"
							class="form-control <?= form_error('date_stall') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('date_stall', $tanggal_stall)); ?>">

						<div class="invalid-feedback <?= form_error('date_stall') ? 'd-block' : ''; ?>">
							<?= form_error('date_stall'); ?>
						</div>
					</div>

					<div class="col-sm-3">
						<label class="form-label font-weight-bold">Shift</label>
						<select class="form-control <?= form_error('shift_pack') ? 'invalid' : ''; ?>"
							name="shift_pack">
							<option value="1" <?= (string) set_value('shift_pack', $produksi->shift_pack) === '1' ? 'selected' : ''; ?>>1</option>
							<option value="2" <?= (string) set_value('shift_pack', $produksi->shift_pack) === '2' ? 'selected' : ''; ?>>2</option>
							<option value="3" <?= (string) set_value('shift_pack', $produksi->shift_pack) === '3' ? 'selected' : ''; ?>>3</option>
						</select>

						<div class="invalid-feedback <?= form_error('shift_pack') ? 'd-block' : ''; ?>">
							<?= form_error('shift_pack'); ?>
						</div>
					</div>

				</div>

				<hr>

				<label class="form-label font-weight-bold">STALLING</label>

				<div class="form-group row">

					<div class="col-sm-3">
						<label class="form-label font-weight-bold">Jam Mulai</label>
						<input type="time"
							name="stall_jam_mulai"
							class="form-control <?= form_error('stall_jam_mulai') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('stall_jam_mulai', $produksi->stall_jam_mulai)); ?>">

						<div class="invalid-feedback <?= form_error('stall_jam_mulai') ? 'd-block' : ''; ?>">
							<?= form_error('stall_jam_mulai'); ?>
						</div>
					</div>

					<div class="col-sm-3">
						<label class="form-label font-weight-bold">Jam Berhenti</label>
						<input type="time"
							name="stall_jam_berhenti"
							class="form-control <?= form_error('stall_jam_berhenti') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('stall_jam_berhenti', $produksi->stall_jam_berhenti)); ?>">

						<div class="invalid-feedback <?= form_error('stall_jam_berhenti') ? 'd-block' : ''; ?>">
							<?= form_error('stall_jam_berhenti'); ?>
						</div>
					</div>

					<div class="col-sm-3">
						<label class="form-label font-weight-bold">Kadar Air 32-34 (%)</label>
						<?php
						$stall_kadar_air = $produksi->stall_kadar_air ?? '';

						if (
							$is_audit &&
							trim((string) $stall_kadar_air) !== ''
						) {
							if (
								!is_numeric($stall_kadar_air) ||
								(float) $stall_kadar_air < 32 ||
								(float) $stall_kadar_air > 34
							) {
								$stall_kadar_air = '33';
							}
						}
						?>

						<input type="text"
							name="stall_kadar_air"
							class="form-control <?= form_error('stall_kadar_air') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('stall_kadar_air', $stall_kadar_air)); ?>">

						<div class="invalid-feedback <?= form_error('stall_kadar_air') ? 'd-block' : ''; ?>">
							<?= form_error('stall_kadar_air'); ?>
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
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 6px 9px;
		border-radius: 4px;
		vertical-align: middle;
	}
</style>
