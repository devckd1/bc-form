<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'gosong/audit'
	: 'gosong';

$form_url = $is_audit
	? 'gosong/edit-audit/' . rawurlencode($gosong->uuid)
	: 'gosong/edit/' . rawurlencode($gosong->uuid);
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Laporan Roti Gosong
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
					Daftar Laporan Roti Gosong
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

			Halaman ini merupakan menu Edit Audit Laporan Roti Gosong.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form
				class="user"
				method="post"
				action="<?= base_url($form_url) ?>">

				<div class="form-group row">
					<div class="col-md-6">
						<label class="font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= set_value('date', $gosong->date) ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('date') ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Shift</label>
						<select
							class="form-control <?= form_error('shift_main') ? 'is-invalid' : '' ?>"
							name="shift_main"
							required>
							<option value="">Pilih Shift</option>
							<option value="1" <?= set_select('shift_main', '1', $shift_number == '1') ?>>1</option>
							<option value="2" <?= set_select('shift_main', '2', $shift_number == '2') ?>>2</option>
							<option value="3" <?= set_select('shift_main', '3', $shift_number == '3') ?>>3</option>
						</select>
						<div class="invalid-feedback">
							<?= form_error('shift_main') ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-md-6">
						<label class="form-label font-weight-bold">Grup Shift</label>
						<select
							class="form-control <?= form_error('shift_group') ? 'is-invalid' : '' ?>"
							name="shift_group"
							required>
							<option value="">Pilih Grup</option>
							<option value="A" <?= set_select('shift_group', 'A', $shift_group == 'A') ?>>A</option>
							<option value="B" <?= set_select('shift_group', 'B', $shift_group == 'B',) ?>>B</option>
							<option value="C" <?= set_select('shift_group', 'C', $shift_group == 'C') ?>>C</option>
						</select>
						<div class="invalid-feedback">
							<?= form_error('shift_group') ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Total Berat (Kg)
						</label>
						<input
							type="text"
							name="total_berat"
							id="total_berat"
							class="form-control <?= form_error('total_berat') ? 'is-invalid' : '' ?>"
							value="<?= set_value('total_berat', $gosong->total_berat) ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('total_berat') ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url) ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const totalBerat = document.getElementById('total_berat');

		if (totalBerat) {
			totalBerat.addEventListener('blur', function() {
				let val = this.value.trim().replace(',', '.');

				if (val !== '' && !isNaN(val)) {
					this.value = parseFloat(val).toFixed(3);
				}
			});
		}
	});
</script>

<style>
	.breadcrumb {
		background-color: #2E86C1;
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
