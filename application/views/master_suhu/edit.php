<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-0 text-gray-800">
			Edit Master Data Suhu
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
				Edit Standar Suhu
			</h6>

		</div>

		<div class="card-body">

			<?= form_open('master-suhu/edit/' . $master->uuid); ?>

			<div class="form-group">

				<label>
					Nama Lokasi
					<span class="text-danger">*</span>
				</label>

				<input type="text"
					name="nama_lokasi"
					class="form-control"
					value="<?= set_value(
								'nama_lokasi',
								$master->nama_lokasi
							); ?>"
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
					value="<?= set_value(
								'standar_suhu',
								$master->standar_suhu
							); ?>"
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
					value="<?= set_value(
								'standar_rh',
								$master->standar_rh
							); ?>"
					placeholder="Contoh: 65-80">

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
					rows="3"><?= set_value(
									'keterangan',
									$master->keterangan
								); ?></textarea>

			</div>

			<div class="form-group">

				<label>Status</label>

				<div>

					<?php if ((int) $master->is_active === 1): ?>

						<span class="badge badge-success">
							Aktif
						</span>

					<?php else: ?>

						<span class="badge badge-danger">
							Tidak Aktif
						</span>

					<?php endif; ?>

				</div>

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
					Update

				</button>

			</div>

			<?= form_close(); ?>

		</div>

	</div>

</div>
