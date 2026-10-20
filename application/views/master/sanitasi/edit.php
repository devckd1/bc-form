<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Edit Master Sanitasi
		</h1>

		<a href="<?= base_url('master-sanitasi'); ?>"
			class="btn btn-secondary btn-sm">
			<i class="fas fa-arrow-left"></i>
			Kembali
		</a>
	</div>

	<div class="card shadow mb-4">

		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Form Edit Master Sanitasi
			</h6>
		</div>

		<div class="card-body">

			<form method="post"
				action="<?= base_url('master-sanitasi/edit/' . $master->uuid); ?>">

				<div class="form-group">
					<label>
						Area
						<span class="text-danger">*</span>
					</label>

					<input type="text"
						name="area"
						class="form-control"
						value="<?= htmlspecialchars($master->area); ?>"
						required>
				</div>

				<div class="form-group">
					<label>
						Standar PPM
						<span class="text-danger">*</span>
					</label>

					<input type="text"
						name="standar_ppm"
						class="form-control"
						value="<?= htmlspecialchars($master->standar_ppm); ?>"
						required>
				</div>

				<div class="form-group">
					<label>
						Standar Suhu
						<span class="text-danger">*</span>
					</label>

					<input type="text"
						name="standar_suhu"
						class="form-control"
						value="<?= htmlspecialchars($master->standar_suhu); ?>"
						required>
				</div>

				<div class="form-group">
					<label>Keterangan</label>

					<input type="text"
						class="form-control"
						value="OK"
						readonly>

					<small class="form-text text-muted">
						Keterangan otomatis ditetapkan sebagai OK.
					</small>
				</div>

				<div class="text-right">

					<a href="<?= base_url('master-sanitasi'); ?>"
						class="btn btn-secondary">
						Batal
					</a>

					<button type="submit"
						class="btn btn-primary">
						<i class="fas fa-save"></i>
						Update
					</button>

				</div>

			</form>

		</div>

	</div>

</div>
