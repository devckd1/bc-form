<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-0 text-gray-800">
			Tambah Master Data Suhu
		</h1>

		<a href="<?= base_url('master-suhu'); ?>"
			class="btn btn-secondary btn-sm">

			<i class="fas fa-arrow-left mr-1"></i>
			Kembali

		</a>

	</div>

	<div class="card shadow">

		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Form Master Data Suhu
			</h6>
		</div>

		<div class="card-body">

			<?= form_open('master-suhu/tambah'); ?>

			<div class="form-group">

				<label>
					Nama Lokasi
					<span class="text-danger">*</span>
				</label>

				<input type="text"
					name="nama_lokasi"
					class="form-control"
					value="<?= set_value('nama_lokasi'); ?>"
					placeholder="Contoh: Ruang Produksi"
					required>

				<?= form_error(
					'nama_lokasi',
					'<small class="text-danger">',
					'</small>'
				); ?>

			</div>

			<div class="form-group">

				<label>
					Standar Suhu
					<span class="text-danger">*</span>
				</label>

				<input type="text"
					name="standar_suhu"
					class="form-control"
					value="<?= set_value('standar_suhu'); ?>"
					placeholder="Contoh: 25-35"
					required>

				<small class="form-text text-muted">
					Masukkan dalam bentuk range, contoh: 25-35.
				</small>

				<?= form_error(
					'standar_suhu',
					'<small class="text-danger">',
					'</small>'
				); ?>

			</div>

			<div class="form-group">

				<label>
					Standar RH
				</label>

				<input type="text"
					name="standar_rh"
					class="form-control"
					value="<?= set_value('standar_rh'); ?>"
					placeholder="Contoh: 65-80">

				<small class="form-text text-muted">
					Kosongkan jika lokasi tidak memiliki standar RH.
				</small>

				<?= form_error(
					'standar_rh',
					'<small class="text-danger">',
					'</small>'
				); ?>

			</div>

			<div class="form-group">

				<label>
					Keterangan
				</label>

				<textarea name="keterangan"
					class="form-control"
					rows="3"
					placeholder="Keterangan tambahan"><?= set_value('keterangan'); ?></textarea>

			</div>

			<hr>

			<div class="text-right">

				<a href="<?= base_url('master-suhu'); ?>"
					class="btn btn-secondary">

					Batal

				</a>

				<button type="submit"
					class="btn btn-primary">

					<i class="fas fa-save mr-1"></i>
					Simpan

				</button>

			</div>

			<?= form_close(); ?>

		</div>

	</div>

</div>
