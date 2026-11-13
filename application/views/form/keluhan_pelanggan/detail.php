<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Detail Keluhan Pelanggan</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('form/keluhan_pelanggan') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Data Keluhan Pelanggan
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Detail</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Informasi Keluhan Pelanggan
			</h6>
		</div>

		<div class="card-body">

			<div class="row">

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Tanggal Keluhan Pelanggan</label>
					<div>
						<?= date('d-m-Y', strtotime($keluhan->tanggal_keluhan)); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Nama Produk</label>
					<div>
						<?= html_escape($keluhan->nama_produk); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Kode Produksi</label>
					<div>
						<?= html_escape($keluhan->kode_produksi); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Tanggal Produksi</label>
					<div>
						<?= date('d-m-Y', strtotime($keluhan->tanggal_produksi)); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Best Before</label>
					<div>
						<?= date('d-m-Y', strtotime($keluhan->best_before)); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Pelanggan</label>
					<div>
						<?= html_escape($keluhan->pelanggan); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Jumlah Produk Komplain</label>
					<div>
						<?= number_format((float) $keluhan->jumlah_komplain, 2, ',', '.'); ?>
						<?= html_escape($keluhan->satuan); ?>
					</div>
				</div>

				<div class="col-md-12 mb-3">
					<label class="font-weight-bold">Jenis Ketidaksesuaian</label>
					<div class="border rounded p-3">
						<?= nl2br(html_escape($keluhan->jenis_ketidaksesuaian)); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Diinput Oleh</label>
					<div>
						<?= html_escape($keluhan->username); ?>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">Waktu Input</label>
					<div>
						<?= date('d-m-Y H:i', strtotime($keluhan->created_at)); ?>
					</div>
				</div>

			</div>

		</div>
	</div>

</div>
<style type="text/css">
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>
