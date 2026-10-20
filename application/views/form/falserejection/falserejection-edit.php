
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$back_url = 'falserejection/audit';
$form_url = 'falserejection/edit-audit/' . rawurlencode($falserejection->uuid);

$tanggal = $falserejection->date_false_rejection ?? '';
if ($tanggal === '0000-00-00') {
	$tanggal = '';
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Update Audit False Rejection
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit False Rejection \ Edit
				</a>
			</li>
		</ol>
	</nav>

	<?php if ($this->session->flashdata('error_msg')) : ?>
		<div class="alert alert-danger">
			<?= html_escape($this->session->flashdata('error_msg')); ?>
		</div>
	<?php endif; ?>

	<div class="alert alert-audit">
		<i class="fas fa-info-circle mr-1"></i>
		Perubahan pada halaman ini hanya memperbarui snapshot Audit, tidak mengubah data False Rejection utama.
	</div>

	<div class="card shadow mb-4 card-audit">
		<div class="card-body">

			<form method="post" action="<?= base_url($form_url); ?>">

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Nama Produk</label>
						<div class="form-control-plaintext">
							<?= html_escape($falserejection->nama_produk ?? '-'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Kode Produksi</label>
						<div class="form-control-plaintext">
							<?= html_escape($falserejection->kode_produksi ?? '-'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal Monitoring</label>
						<input
							type="date"
							name="date_false_rejection"
							class="form-control <?= form_error('date_false_rejection') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date_false_rejection', $tanggal)); ?>">
						<div class="invalid-feedback">
							<?= form_error('date_false_rejection'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Shift</label>
						<select
							class="form-control <?= form_error('shift_monitoring') ? 'is-invalid' : ''; ?>"
							name="shift_monitoring">
							<option value="">Pilih Shift</option>
							<?php for ($shift = 1; $shift <= 3; $shift++) : ?>
								<option value="<?= $shift; ?>"
									<?= (string) set_value('shift_monitoring', $falserejection->shift_monitoring ?? '') === (string) $shift ? 'selected' : ''; ?>>
									<?= $shift; ?>
								</option>
							<?php endfor; ?>
						</select>
						<div class="invalid-feedback">
							<?= form_error('shift_monitoring'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Mesin</label>
						<input
							type="text"
							name="no_mesin"
							class="form-control <?= form_error('no_mesin') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_mesin', $falserejection->no_mesin ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('no_mesin'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Jumlah Pack/Bag yang Tidak Lolos</label>
						<input
							type="number"
							name="jumlah_tidak_lolos"
							class="form-control <?= form_error('jumlah_tidak_lolos') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jumlah_tidak_lolos', $falserejection->jumlah_tidak_lolos ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('jumlah_tidak_lolos'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Jumlah Pack/Bag yang Terdapat Kontaminan</label>
						<input
							type="number"
							name="jumlah_kontaminasi"
							class="form-control <?= form_error('jumlah_kontaminasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jumlah_kontaminasi', $falserejection->jumlah_kontaminasi ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('jumlah_kontaminasi'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Jenis Kontaminan</label>
						<input
							type="text"
							name="jenis_kontaminasi"
							class="form-control <?= form_error('jenis_kontaminasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_kontaminasi', $falserejection->jenis_kontaminasi ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('jenis_kontaminasi'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Posisi Kontaminasi</label>
						<input
							type="text"
							name="posisi_kontaminasi"
							class="form-control <?= form_error('posisi_kontaminasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('posisi_kontaminasi', $falserejection->posisi_kontaminasi ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('posisi_kontaminasi'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">False Rejection</label>
						<input
							type="text"
							name="false_rejection"
							class="form-control <?= form_error('false_rejection') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('false_rejection', $falserejection->false_rejection ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('false_rejection'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Catatan</label>
						<input
							type="text"
							name="catatan"
							class="form-control <?= form_error('catatan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('catatan', $falserejection->catatan ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('catatan'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success">
							<i class="fas fa-save mr-1"></i>
							Simpan
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
							<i class="fas fa-times mr-1"></i>
							Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		padding: 6px 10px;
		border-radius: 5px;
		vertical-align: middle;
		margin-left: 5px;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.breadcrumb-audit a {
		color: #fff;
	}

	.card-audit {
		border: 1px solid #e3d7f5;
		border-radius: 8px;
	}

	.alert-audit {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
	}

	.btn-edit-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-edit-audit:hover {
		background-color: #452577;
		border-color: #452577;
		color: #fff;
	}
</style>