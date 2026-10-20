<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'retain/audit' : 'retain';

$form_url = $is_audit
	? 'retain/edit-audit/' . rawurlencode($retain->uuid)
	: 'retain/edit/' . rawurlencode($retain->uuid);

$plant_map = [
	'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Bread Crumb Cikande',
	'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Bread Crumb Salatiga'
];

$plant_name = $plant_map[$retain->plant] ?? 'Unknown Plant';

$descList = json_decode($retain->description ?? '[]', true);
if (!is_array($descList)) {
	$descList = [];
}

$deskripsiIndex = 0;
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Edit Retain Sample Report

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $is_audit ? 'breadcrumb-qc' : 'breadcrumb-qc'; ?>">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit ? 'Daftar Audit Retain Sample Report' : 'Daftar Retain Sample Report'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Peneraan Retain.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				FORM RETAIN SAMPLE REPORT
			</h6>
		</div>

		<div class="card-body">
			<form
				class="user"
				method="post"
				action="<?= base_url($form_url); ?>"
				enctype="multipart/form-data">
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control"
							value="<?= !empty($retain->date) ? date('Y-m-d', strtotime($retain->date)) : ''; ?>">
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Plant</label>
						<input
							type="text"
							class="form-control"
							value="<?= html_escape($plant_name); ?>"
							readonly>
						<input
							type="hidden"
							name="plant"
							value="<?= html_escape($retain->plant); ?>">
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Sample Type</label>
						<input
							type="text"
							name="sample_type"
							class="form-control"
							value="<?= html_escape($retain->sample_type ?? ''); ?>">
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Sample Storage</label>
						<select name="sample_storage" class="form-control">
							<option value="Dry" <?= ($retain->sample_storage ?? '') === 'Dry' ? 'selected' : ''; ?>>
								Dry
							</option>
							<option value="Other" <?= ($retain->sample_storage ?? '') === 'Other' ? 'selected' : ''; ?>>
								Other
							</option>
						</select>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="font-weight-bold">Deskripsi Produk</label>

					<div id="description-group">
						<?php foreach ($descList as $desc): ?>
							<div class="row mb-3 description-entry">
								<div class="col-md-2">
									<label>Nama Produk</label>
									<input
										type="text"
										name="description[<?= $deskripsiIndex; ?>][nama_produk]"
										class="form-control"
										value="<?= html_escape($desc['nama_produk'] ?? ''); ?>">
								</div>

								<div class="col-md-2">
									<label>Kode Produksi</label>
									<input
										type="text"
										name="description[<?= $deskripsiIndex; ?>][kode_produksi]"
										class="form-control"
										value="<?= html_escape($desc['kode_produksi'] ?? ''); ?>">
								</div>

								<div class="col-md-2">
									<label>Best Before</label>
									<input
										type="date"
										name="description[<?= $deskripsiIndex; ?>][best_before]"
										class="form-control"
										value="<?= html_escape($desc['best_before'] ?? ''); ?>">
								</div>

								<div class="col-md-2">
									<label>Quantity (g)</label>
									<input
										type="text"
										name="description[<?= $deskripsiIndex; ?>][quantity]"
										class="form-control"
										value="<?= html_escape(
													$is_audit
														? '200'
														: ($desc['quantity'] ?? '')
												); ?>">
								</div>

								<div class="col-md-3">
									<label>Remarks</label>
									<input
										type="text"
										name="description[<?= $deskripsiIndex; ?>][remarks]"
										class="form-control"
										value="<?= html_escape($desc['remarks'] ?? ''); ?>">
								</div>

								<div class="col-md-1 d-flex align-items-end">
									<button
										type="button"
										class="btn btn-danger btn-sm remove-description"
										title="Hapus">
										<i class="fas fa-trash"></i>
									</button>
								</div>
							</div>
							<?php $deskripsiIndex++; ?>
						<?php endforeach; ?>
					</div>

					<button type="button" class="btn btn-primary btn-sm mt-2" id="add-description">
						<i class="fas fa-plus mr-1"></i> Tambah Deskripsi
					</button>
				</div>

				<hr>

				<div class="form-group">
					<label class="font-weight-bold">Catatan</label>
					<textarea
						class="form-control"
						name="catatan"
						rows="3"><?= html_escape($retain->catatan ?? ''); ?></textarea>
				</div>

				<div class="mt-4">
					<button type="submit" class="btn btn-success mr-2">
						<i class="fas fa-save mr-1"></i> Simpan
					</button>

					<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
						<i class="fas fa-times mr-1"></i> Batal
					</a>
				</div>
			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 4px;
		font-size: 12px;
		vertical-align: middle;
		letter-spacing: .5px;
	}

	.breadcrumb {
		padding: 10px 16px;
		border-radius: 4px;
	}

	.breadcrumb-qc {
		background-color: #2E86C1;
	}

	.breadcrumb-audit,
	.header-audit {
		background-color: #5a3298;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.border-left-audit {
		border-left: 5px solid #5a3298;
	}

	.description-entry label {
		font-size: 12px;
		font-weight: 600;
	}

	@media (max-width: 768px) {
		.description-entry>div {
			margin-bottom: 8px;
		}
	}
</style>

<script>
	let deskripsiIndex = <?= (int) $deskripsiIndex; ?>;

	document.getElementById('add-description').addEventListener('click', function() {
		const container = document.getElementById('description-group');
		const newEntry = document.createElement('div');

		newEntry.className = 'row mb-3 description-entry';

		newEntry.innerHTML = `
            <div class="col-md-2">
                <label>Nama Produk</label>
                <input type="text" name="description[${deskripsiIndex}][nama_produk]" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Kode Produksi</label>
                <input type="text" name="description[${deskripsiIndex}][kode_produksi]" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Best Before</label>
                <input type="date" name="description[${deskripsiIndex}][best_before]" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Quantity (g)</label>
                <input type="text" name="description[${deskripsiIndex}][quantity]" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Remarks</label>
                <input type="text" name="description[${deskripsiIndex}][remarks]" class="form-control">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-description" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

		container.appendChild(newEntry);
		deskripsiIndex++;
	});

	document.addEventListener('click', function(e) {
		const button = e.target.closest('.remove-description');

		if (button) {
			button.closest('.description-entry').remove();
		}
	});
</script>
