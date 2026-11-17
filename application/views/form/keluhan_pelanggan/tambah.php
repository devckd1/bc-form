<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Tambah Keluhan Pelanggan</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('form/keluhan_pelanggan') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Data Keluhan Pelanggan
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>

	<?php if ($this->session->flashdata('error')) : ?>
		<div class="alert alert-danger">
			<?= $this->session->flashdata('error'); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Form Keluhan Pelanggan
			</h6>
		</div>

		<div class="card-body">

			<?= form_open('form/keluhan_pelanggan/simpan'); ?>

			<div class="row">

				<div class="col-md-6">
					<div class="form-group">
						<label>Tanggal Keluhan Pelanggan</label>
						<input type="date"
							name="tanggal_keluhan"
							class="form-control"
							value="<?= date('Y-m-d'); ?>"
							required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Produk</label>
						<select name="produk_uuid"
							id="produk_uuid"
							class="form-control"
							required>
							<option value="">-- Pilih Produk --</option>

							<?php foreach ($produk as $p) : ?>
								<option value="<?= html_escape($p->uuid); ?>">
									<?= html_escape($p->nama_produk); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Kode Produksi</label>
						<input type="text"
							name="kode_produksi"
							id="kode_produksi"
							class="form-control"
							maxlength="10"
							minlength="10"
							placeholder="Contoh: QK11301AB0"
							autocomplete="off"
							required>
						<small class="text-muted">
							Masukkan 10 karakter kode produksi.
						</small>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Best Before</label>
						<input type="text"
							id="best_before"
							class="form-control"
							placeholder="Otomatis dari kode produksi"
							readonly>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Pelanggan</label>
						<input type="text"
							name="pelanggan"
							class="form-control"
							placeholder="Masukkan nama pelanggan"
							required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Jumlah Produk Komplain</label>
						<input type="number"
							name="jumlah_komplain"
							class="form-control"
							min="0.01"
							step="0.01"
							placeholder="Masukkan jumlah produk"
							required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Satuan Unit</label>
						<select name="satuan"
							class="form-control"
							required>
							<option value="">-- Pilih Satuan --</option>
							<option value="Pcs">Pcs</option>
							<option value="Pack">Pack</option>
							<option value="Box">Box</option>
							<option value="Karton">Karton</option>
							<option value="Kg">Kg</option>
							<option value="Gram">Gram</option>
							<option value="Liter">Liter</option>
							<option value="Lainnya">Lainnya</option>
						</select>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group">
						<label>Jenis Ketidaksesuaian</label>
						<textarea name="jenis_ketidaksesuaian"
							class="form-control"
							rows="4"
							placeholder="Jelaskan jenis ketidaksesuaian produk..."
							required></textarea>
					</div>
				</div>

			</div>

			<hr>

			<button type="submit" class="btn btn-success">
				<i class="fas fa-save"></i> Simpan
			</button>

			<a href="<?= base_url('form/keluhan_pelanggan'); ?>"
				class="btn btn-danger">
				Batal
			</a>

			<?= form_close(); ?>

		</div>
	</div>

</div>
<style type="text/css">
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>
<script>
	$(document).ready(function() {

		function hitungBestBefore() {
			var kode = $('#kode_produksi').val().toUpperCase().trim();

			$('#kode_produksi').val(kode);
			$('#best_before').val('');

			// Validasi format kode produksi
			if (!/^[A-Z][A-L][0-9]{2}[0-9][A-Z0-9]{5}$/.test(kode)) {
				return;
			}

			var kodeTahun = kode.charAt(0);
			var kodeBulan = kode.charAt(1);
			var kodeHari = kode.substring(2, 4);

			// Q = 2026, R = 2027, S = 2028, dan seterusnya
			var tahun = 2026 + (kodeTahun.charCodeAt(0) - 'Q'.charCodeAt(0));

			// A = Januari, B = Februari, dan seterusnya
			var bulan = kodeBulan.charCodeAt(0) - 'A'.charCodeAt(0);
			var hari = parseInt(kodeHari, 10);

			if (tahun < 2026) {
				return;
			}

			// Membuat tanggal produksi
			var tanggalProduksi = new Date(Date.UTC(tahun, bulan, hari));

			// Validasi tanggal produksi
			if (
				tanggalProduksi.getUTCFullYear() !== tahun ||
				tanggalProduksi.getUTCMonth() !== bulan ||
				tanggalProduksi.getUTCDate() !== hari
			) {
				return;
			}

			// Best Before = tanggal produksi + 6 bulan
			var bestBefore = new Date(tanggalProduksi);

			bestBefore.setUTCMonth(
				bestBefore.getUTCMonth() + 6
			);

			var hasil = [
				bestBefore.getUTCFullYear(),
				String(bestBefore.getUTCMonth() + 1).padStart(2, '0'),
				String(bestBefore.getUTCDate()).padStart(2, '0')
			].join('-');

			$('#best_before').val(hasil);
		}

		$('#kode_produksi').on('input change', hitungBestBefore);

	});
</script>
