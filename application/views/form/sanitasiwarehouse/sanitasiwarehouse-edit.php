<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'sanitasiwarehouse/audit'
	: 'sanitasiwarehouse';

$form_action = $is_audit
	? 'sanitasiwarehouse/edit-audit/' . $sanitasiwarehouse->uuid
	: 'sanitasiwarehouse/edit/' . $sanitasiwarehouse->uuid;

$detail = json_decode($sanitasiwarehouse->detail ?? '[]', true);

if (!is_array($detail)) {
	$detail = [];
}

$detail = array_slice($detail, 0, 100);

$kondisiMap = [
	'1' => 'Berdebu',
	'2' => 'Basah',
	'3' => 'Sampah (sisa lakban, kertas, remah produk/bahan baku, plastik, kardus bekas)',
	'4' => 'Pertumbuhan mikroorganisme (jamur dan bau busuk)',
	'5' => 'Pallet rusak/pecah',
	'6' => 'Terdapat aktifitas binatang (tikus, kecoa, lalat, ulat, belatung)',
	'7' => 'Sarang laba-laba',
];
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			<?= $is_audit
				? 'Edit Audit Pemeriksaan Sanitasi Warehouse'
				: 'Update Pemeriksaan Sanitasi Warehouse'; ?>

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-custom">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Sanitasi Warehouse'
						: 'Daftar Pemeriksaan Sanitasi Warehouse'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active text-white" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Sensori Finish Good.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				FORM <?= $is_audit ? 'AUDIT ' : ''; ?>SANITASI WAREHOUSE
			</h6>
		</div>

		<div class="card-body">

			<?php if (empty($detail)): ?>
				<div class="alert alert-danger">
					Data detail kebersihan tidak tersedia atau tidak valid.
				</div>
			<?php endif; ?>

			<form method="post" action="<?= base_url($form_action); ?>">

				<div class="form-row">
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $sanitasiwarehouse->date ?? '')); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="form-group col-md-3">
						<label class="font-weight-bold">Area</label>
						<input
							type="text"
							class="form-control"
							value="<?= html_escape($sanitasiwarehouse->area ?? '-'); ?>"
							readonly>
					</div>
				</div>

				<hr>

				<h5 class="font-weight-bold mb-3">Detail Kebersihan</h5>

				<div id="detail-section">
					<?php foreach ($detail as $i => $row): ?>
						<div class="row mb-3 detail-row">

							<div class="col-md-3">
								<label>Bagian</label>
								<input
									type="text"
									name="bagian[]"
									class="form-control"
									value="<?= html_escape($row['bagian'] ?? ''); ?>"
									readonly>
							</div>

							<div class="col-md-3">
								<label>Kondisi</label>
								<select name="kondisi[]" class="form-control" required>
									<option
										value="0"
										<?= (string) ($row['kondisi'] ?? '0') === '0' ||
											($row['kondisi'] ?? '') === ''
											? 'selected'
											: ''; ?>>
										Bersih
									</option>

									<?php foreach ($kondisiMap as $key => $label): ?>
										<option
											value="<?= $key; ?>"
											<?= (string) ($row['kondisi'] ?? '') === (string) $key
												? 'selected'
												: ''; ?>>
											<?= $key . ' - ' . html_escape($label); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="col-md-3">
								<label>Problem</label>
								<input
									type="text"
									name="problem[]"
									class="form-control"
									value="<?= html_escape($row['problem'] ?? ''); ?>">
							</div>

							<div class="col-md-3">
								<label>Tindakan</label>
								<input
									type="text"
									name="tindakan[]"
									class="form-control"
									value="<?= html_escape($row['tindakan'] ?? ''); ?>">
							</div>

						</div>
					<?php endforeach; ?>
				</div>

				<hr>

				<button type="submit" class="btn <?= $is_audit ? 'btn-success' : 'btn-success'; ?>">
					<i class="fas fa-save mr-1"></i>
					<?= $is_audit ? 'Simpan' : 'Simpan'; ?>
				</button>

				<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
					Batal
				</a>

			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 7px 12px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.header-audit {
		background-color: #5a3298;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #452575;
		border-color: #452575;
		color: #fff;
	}

	.breadcrumb-custom {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb-custom .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb-custom .breadcrumb-item a:hover {
		text-decoration: underline;
	}

	.detail-row {
		border-bottom: 1px solid #e3e6f0;
		padding-bottom: 12px;
	}

	@media (max-width: 768px) {
		h1.h3 {
			font-size: 20px;
		}
	}
</style>
