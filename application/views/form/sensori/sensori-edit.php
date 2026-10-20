<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'sensori/audit' : 'sensori';
$form_action = $is_audit
	? 'sensori/edit-audit/' . $sensori->uuid
	: 'sensori/edit/' . $sensori->uuid;

$produk_data = json_decode($sensori->produk ?? '[]', true);

if (!is_array($produk_data)) {
	$produk_data = [];
}

if (empty($produk_data)) {
	$produk_data = [[
		'kode_produksi' => '',
		'best_before' => date('Y-m-d'),
		'warna' => '',
		'tekstur' => '',
		'rasa' => '',
		'aroma' => '',
		'kenampakan' => ''
	]];
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			<?= $is_audit ? 'Edit Audit Sensori Finish Good' : 'Update Sensori Finish Good'; ?>

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
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit ? 'Daftar Audit Sensori Finish Good' : 'Daftar Sensori Finish Good'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
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

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger">
			<?= html_escape($this->session->flashdata('error_msg')); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form class="user" method="post" action="<?= base_url($form_action); ?>">

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $sensori->date ?? '')); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Nama Produk</label>
						<input
							type="text"
							name="nama_produk"
							class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('nama_produk', $sensori->nama_produk ?? '')); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('nama_produk'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-area" id="form-sensori-wrapper">
					<label class="form-label font-weight-bold">Sensori</label>

					<?php foreach ($produk_data as $index => $item): ?>
						<?php
						$kode_produksi = $item['kode_produksi'] ?? '';
						$best_before = $item['best_before'] ?? '';
						$atribut = ['warna', 'tekstur', 'rasa', 'aroma', 'kenampakan'];
						?>

						<div class="sensori-group border p-3 mb-3 rounded bg-light"
							data-index="<?= (int) $index; ?>">

							<div class="form-group row">
								<div class="col-sm-3">
									<label>Kode Produksi</label>
									<input
										type="text"
										name="kode_produksi[]"
										class="form-control"
										value="<?= html_escape($kode_produksi); ?>">
								</div>

								<div class="col-sm-3">
									<label>Best Before</label>
									<input
										type="date"
										name="best_before[]"
										class="form-control"
										value="<?= html_escape($best_before); ?>">
								</div>
							</div>

							<div class="form-group row">
								<?php foreach ($atribut as $nama_atribut): ?>
									<?php $nilai = $item[$nama_atribut] ?? ''; ?>

									<div class="col-sm-2 mb-3">
										<label class="font-weight-bold">
											<?= ucfirst($nama_atribut); ?>
										</label>
										<br>

										<label>
											<input
												type="radio"
												name="<?= $nama_atribut; ?>[<?= (int) $index; ?>]"
												value="Ok"
												<?= ($is_audit || $nilai === 'Ok') ? 'checked' : ''; ?>>
											Ok
										</label>
										<br>

										<label>
											<input
												type="radio"
												name="<?= $nama_atribut; ?>[<?= (int) $index; ?>]"
												value="Tidak ok"
												<?= $nilai === 'Tidak ok' ? 'checked' : ''; ?>>
											Tidak ok
										</label>
									</div>
								<?php endforeach; ?>

								<div class="col-sm-2 d-flex align-items-end mb-3">
									<button type="button" class="btn btn-danger btn-remove">
										<i class="fas fa-trash"></i> Hapus
									</button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<button type="button" class="btn btn-primary mt-2" id="add-sensori">
					<i class="fas fa-plus"></i> Tambah Kode
				</button>

				<hr>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tindakan Koreksi</label>
						<textarea
							class="form-control <?= form_error('tindakan') ? 'is-invalid' : ''; ?>"
							name="tindakan"
							rows="3"><?= html_escape(set_value('tindakan', $sensori->tindakan ?? '')); ?></textarea>
						<div class="invalid-feedback">
							<?= form_error('tindakan'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Catatan</label>
						<textarea
							class="form-control <?= form_error('catatan') ? 'is-invalid' : ''; ?>"
							name="catatan"
							rows="3"><?= html_escape(set_value('catatan', $sensori->catatan ?? '')); ?></textarea>
						<div class="invalid-feedback">
							<?= form_error('catatan'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success mr-2">
							<i class="fas fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
							<i class="fas fa-times"></i> Batal
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

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.sensori-group {
		border-left: 4px solid #5a3298 !important;
	}

	.sensori-group label {
		cursor: pointer;
	}
</style>

<script>
	$(document).ready(function() {
		let index = 0;

		$('.sensori-group').each(function() {
			const currentIndex = parseInt($(this).attr('data-index'), 10);
			if (!isNaN(currentIndex) && currentIndex >= index) {
				index = currentIndex + 1;
			}
		});

		$('#add-sensori').on('click', function() {
			const $source = $('.sensori-group').first();
			const $newGroup = $source.clone(false);

			$newGroup.attr('data-index', index);

			$newGroup.find('input[type="text"], input[type="date"]').val('');
			$newGroup.find('input[type="radio"]').prop('checked', false);

			if (<?= $is_audit ? 'true' : 'false'; ?>) {
				$newGroup.find('input[type="radio"][value="Ok"]')
					.prop('checked', true);
			}

			$newGroup.find('input[type="radio"]').each(function() {
				const name = $(this).attr('name');
				const baseName = name.split('[')[0];
				$(this).attr('name', baseName + '[' + index + ']');
			});

			$('#form-sensori-wrapper').append($newGroup);
			index++;
		});

		$(document).on('click', '.btn-remove', function() {
			if ($('.sensori-group').length > 1) {
				$(this).closest('.sensori-group').remove();
			} else {
				alert('Minimal satu baris sensori harus ada.');
			}
		});
	});
</script>
