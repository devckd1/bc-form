
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'analisis/audit'
	: 'analisis';

$form_url = $is_audit
	? 'analisis/analis-audit/' . rawurlencode($analisis->uuid)
	: 'analisis/analis/' . rawurlencode($analisis->uuid);

$analisis_values = set_value('analisis', []);

if (isset($larutan->analisis) && $larutan->analisis !== '') {
	$analisis_values = array_map(
		'trim',
		explode(',', $larutan->analisis)
	);
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Permohonan Analisis Sampel Laboratorium
			<?php if ($is_audit): ?>
				<span style="background-color:#5a3298;color:#fff;padding:5px 10px;border-radius:4px;font-size:12px;">
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Permohonan Analisis Sampel Laboratorium
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Analisis<?= $is_audit ? ' Audit' : ''; ?>
			</li>
		</ol>
	</nav>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<?= $this->session->flashdata('success_msg'); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<?= $this->session->flashdata('error_msg'); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<table border="1" cellpadding="8" cellspacing="0"
				style="border-collapse:collapse;width:40%;text-align:left;font-family:Arial,sans-serif;font-size:14px;">
				<thead style="background-color:#f2f2f2;">
					<tr>
						<th style="padding:10px;background-color:#ADD8E6;color:gray;">
							Kimia
						</th>
						<th style="padding:10px;background-color:#ADD8E6;color:gray;">
							Mikrobiologi
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1. Moisture</td>
						<td>5. TPC</td>
					</tr>
					<tr>
						<td>2. Salinity</td>
						<td>6. Enterobacter</td>
					</tr>
					<tr>
						<td>3. pH</td>
						<td>7. Salmonella</td>
					</tr>
					<tr>
						<td>4. Bulk Density</td>
						<td>8. Yeast and Mold</td>
					</tr>
				</tbody>
			</table>

			<hr>

			<form class="user" method="post" action="<?= base_url($form_url); ?>">

				<label class="form-label font-weight-bold">
					Permintaan Analisis
				</label>

				<div class="form-group row">
					<div class="col-sm-12">
						<div class="d-flex flex-wrap gap-3">

							<div class="d-flex align-items-center mr-4">
								<strong style="margin-right:8px;">Kimia:</strong>

								<?php foreach ([1, 2, 3, 4] as $i): ?>
									<div class="form-check form-check-inline me-2">
										<input
											type="checkbox"
											name="analisis[]"
											value="<?= $i; ?>"
											class="form-check-input"
											<?= in_array((string)$i, array_map('strval', (array)$analisis_values), true) ? 'checked' : ''; ?>>

										<label class="form-check-label">
											<?= $i; ?>
										</label>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="d-flex align-items-center mr-4">
								<strong style="margin-right:8px;">Mikrobiologi:</strong>

								<?php foreach ([5, 6, 7, 8] as $i): ?>
									<div class="form-check form-check-inline me-2">
										<input
											type="checkbox"
											name="analisis[]"
											value="<?= $i; ?>"
											class="form-check-input"
											<?= in_array((string)$i, array_map('strval', (array)$analisis_values), true) ? 'checked' : ''; ?>>

										<label class="form-check-label">
											<?= $i; ?>
										</label>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="d-flex align-items-center mr-4">
								<strong style="margin-right:8px;">Lainnya:</strong>

								<?php
								$lainnya = array_diff(
									(array)$analisis_values,
									['1', '2', '3', '4', '5', '6', '7', '8']
								);

								$lainnya = array_values($lainnya);
								?>

								<input
									type="text"
									name="analisis[]"
									class="form-control form-control-sm <?= form_error('analisis') ? 'is-invalid' : ''; ?>"
									placeholder="Masukkan lainnya"
									style="width:200px;"
									value="<?= htmlspecialchars($lainnya[0] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
							</div>

						</div>
					</div>

					<div class="invalid-feedback <?= form_error('analisis') ? 'd-block' : ''; ?>">
						<?= form_error('analisis'); ?>
					</div>
				</div>

				<hr>

				<div class="row form-group">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea
							class="form-control"
							name="catatan"><?= htmlspecialchars(set_value('catatan', $analisis->catatan ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>

						<div class="invalid-feedback <?= form_error('catatan') ? 'd-block' : ''; ?>">
							<?= form_error('catatan'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-md btn-danger">
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

	.breadcrumb a {
		color: #fff;
	}

	.breadcrumb-item.active {
		color: #e9ecef;
	}

	.form-check-inline {
		margin-right: 10px;
	}
</style>
