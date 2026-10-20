<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">
		Update Audit Pengayakan

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('pengayakan/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Pengayakan
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<!-- Info Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>

		<strong>Informasi:</strong>

		Halaman ini merupakan menu Edit Audit Pengayakan.
		Perubahan data pada halaman ini tidak akan mengubah data aktual
		pada menu pemeriksaan.
	</div>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form
				class="user"
				method="post"
				action="<?= base_url('pengayakan/edit-audit/' . $pengayakan->uuid); ?>">

				<!-- Tanggal & Shift -->
				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->date); ?>">

						<div class="invalid-feedback <?= !empty(form_error('date')) ? 'd-block' : '' ?>">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Shift
						</label>

						<select
							class="form-control <?= form_error('shift') ? 'invalid' : '' ?>"
							name="shift">
							<option value="1"
								<?= set_select('shift', '1', $pengayakan->shift == 1); ?>>
								1
							</option>

							<option value="2"
								<?= set_select('shift', '2', $pengayakan->shift == 2); ?>>
								2
							</option>

							<option value="3"
								<?= set_select('shift', '3', $pengayakan->shift == 3); ?>>
								3
							</option>
						</select>

						<div class="invalid-feedback <?= !empty(form_error('shift')) ? 'd-block' : '' ?>">
							<?= form_error('shift'); ?>
						</div>
					</div>

				</div>


				<!-- Nama Barang & Kode Produksi -->
				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Nama Barang
						</label>

						<input
							type="text"
							name="nama_barang"
							class="form-control <?= form_error('nama_barang') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->nama_barang); ?>">

						<div class="invalid-feedback <?= !empty(form_error('nama_barang')) ? 'd-block' : '' ?>">
							<?= form_error('nama_barang'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Kode Produksi
						</label>

						<input
							type="text"
							name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->kode_produksi); ?>">

						<div class="invalid-feedback <?= !empty(form_error('kode_produksi')) ? 'd-block' : '' ?>">
							<?= form_error('kode_produksi'); ?>
						</div>
					</div>

				</div>


				<!-- Expired Date & Jumlah -->
				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Expired Date
						</label>

						<input
							type="date"
							name="expired_date"
							class="form-control <?= form_error('expired_date') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->expired_date); ?>">

						<div class="invalid-feedback <?= !empty(form_error('expired_date')) ? 'd-block' : '' ?>">
							<?= form_error('expired_date'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Jumlah Barang
						</label>

						<input
							type="text"
							name="jumlah_barang"
							class="form-control <?= form_error('jumlah_barang') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->jumlah_barang); ?>">

						<div class="invalid-feedback <?= !empty(form_error('jumlah_barang')) ? 'd-block' : '' ?>">
							<?= form_error('jumlah_barang'); ?>
						</div>
					</div>

				</div>


				<hr>


				<!-- Kontaminasi -->
				<label class="form-label font-weight-bold">
					Kontaminasi Benda Asing
				</label>

				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Screen Mess
						</label>

						<input
							type="text"
							name="kba_screenmess"
							class="form-control <?= form_error('kba_screenmess') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->kba_screenmess); ?>">

						<div class="invalid-feedback <?= !empty(form_error('kba_screenmess')) ? 'd-block' : '' ?>">
							<?= form_error('kba_screenmess'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Kerikil
						</label>

						<input
							type="text"
							name="kba_kerikil"
							class="form-control <?= form_error('kba_kerikil') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->kba_kerikil); ?>">

						<div class="invalid-feedback <?= !empty(form_error('kba_kerikil')) ? 'd-block' : '' ?>">
							<?= form_error('kba_kerikil'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Benang
						</label>

						<input
							type="text"
							name="kba_benang"
							class="form-control <?= form_error('kba_benang') ? 'invalid' : '' ?>"
							value="<?= html_escape($pengayakan->kba_benang); ?>">

						<div class="invalid-feedback <?= !empty(form_error('kba_benang')) ? 'd-block' : '' ?>">
							<?= form_error('kba_benang'); ?>
						</div>
					</div>

				</div>


				<hr>


				<!-- Kondisi & Catatan -->
				<div class="row form-group">

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Kondisi Screen Ayakan
						</label>

						<textarea
							class="form-control"
							name="kondisi"
							rows="4"><?= html_escape($pengayakan->kondisi); ?></textarea>

						<div class="invalid-feedback <?= !empty(form_error('kondisi')) ? 'd-block' : '' ?>">
							<?= form_error('kondisi'); ?>
						</div>

					</div>

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea
							class="form-control"
							name="catatan"
							rows="4"><?= html_escape($pengayakan->catatan); ?></textarea>

						<div class="invalid-feedback <?= !empty(form_error('catatan')) ? 'd-block' : '' ?>">
							<?= form_error('catatan'); ?>
						</div>

					</div>

				</div>
	
			</form>

		</div>
	</div>

</div>


<style type="text/css">
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		color: #fff;
		text-decoration: underline;
	}

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 6px 11px;
		border-radius: 20px;
		vertical-align: middle;
		margin-left: 8px;
		letter-spacing: 0.3px;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #452477;
		border-color: #452477;
		color: #fff;
	}

	.card {
		border-radius: 0.5rem;
	}

	@media (max-width: 768px) {

		h1.h3 {
			font-size: 20px;
		}

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin: 8px 0 0 0;
		}

	}
</style>
