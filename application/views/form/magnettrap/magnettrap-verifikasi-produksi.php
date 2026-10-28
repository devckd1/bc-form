<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Verifikasi Produksi Magnet Trap
		</h1>
	</div>

	<div class="card shadow mb-4">

		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Detail Pemeriksaan Magnet Trap
			</h6>
		</div>

		<div class="card-body">

			<div class="row">

				<div class="col-md-3 mb-3">
					<label class="font-weight-bold">Tanggal</label>
					<input type="text"
						class="form-control"
						value="<?= date('d-m-Y', strtotime($magnettrap->date)); ?>"
						readonly>
				</div>

				<div class="col-md-3 mb-3">
					<label class="font-weight-bold">Shift</label>
					<input type="text"
						class="form-control"
						value="<?= $magnettrap->shift; ?>"
						readonly>
				</div>

				<div class="col-md-3 mb-3">
					<label class="font-weight-bold">Nama Produk</label>
					<input type="text"
						class="form-control"
						value="<?= html_escape($magnettrap->nama_produk); ?>"
						readonly>
				</div>

				<div class="col-md-3 mb-3">
					<label class="font-weight-bold">Kode Produksi</label>
					<input type="text"
						class="form-control"
						value="<?= html_escape($magnettrap->kode_produksi); ?>"
						readonly>
				</div>

			</div>

			<div class="row">

				<div class="col-md-3 mb-3">
					<label class="font-weight-bold">Pukul</label>
					<input type="text"
						class="form-control"
						value="<?= date('H:i', strtotime($magnettrap->time)); ?>"
						readonly>
				</div>

				<div class="col-md-3 mb-3">
					<label class="font-weight-bold">Tahapan</label>
					<input type="text"
						class="form-control"
						value="<?= html_escape($magnettrap->tahapan); ?>"
						readonly>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">
						Jenis Kontaminasi
					</label>
					<input type="text"
						class="form-control"
						value="<?= html_escape($magnettrap->kontaminasi); ?>"
						readonly>
				</div>

			</div>

			<div class="row">

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">
						Analisis Temuan
					</label>

					<textarea class="form-control"
						rows="3"
						readonly><?= html_escape($magnettrap->analisis); ?></textarea>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">
						Tindakan Koreksi
					</label>

					<textarea class="form-control"
						rows="3"
						readonly><?= html_escape($magnettrap->tindakan); ?></textarea>
				</div>

			</div>

			<div class="row">

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">
						Verifikasi
					</label>

					<textarea class="form-control"
						rows="3"
						readonly><?= html_escape($magnettrap->verifikasi); ?></textarea>
				</div>

				<div class="col-md-6 mb-3">
					<label class="font-weight-bold">
						Keterangan
					</label>

					<textarea class="form-control"
						rows="3"
						readonly><?= html_escape($magnettrap->keterangan); ?></textarea>
				</div>

			</div>

			<hr>

			<?= form_open(current_url()); ?>

			<div class="form-group">
				<label class="font-weight-bold">
					Status Verifikasi Produksi
				</label>

				<select name="status_enginer"
					class="form-control <?= form_error('status_enginer') ? 'is-invalid' : ''; ?>">

					<option value="">-- Pilih Status --</option>

					<option value="1"
						<?= set_select('status_enginer', '1', $magnettrap->status_enginer == '1'); ?>>
						Diketahui / Disetujui
					</option>

					<option value="2"
						<?= set_select('status_enginer', '2', $magnettrap->status_enginer == '2'); ?>>
						Ditolak / Perlu Perbaikan
					</option>

				</select>

				<div class="invalid-feedback">
					<?= form_error('status_enginer'); ?>
				</div>
			</div>

			<div class="form-group">
				<label class="font-weight-bold">
					Catatan Produksi
				</label>

				<textarea name="catatan_enginer"
					class="form-control"
					rows="4"
					placeholder="Catatan verifikasi produksi..."><?= set_value(
																		'catatan_enginer',
																		$magnettrap->catatan_enginer
																	); ?></textarea>
			</div>

			<div class="text-right">

				<a href="<?= base_url('magnettrap'); ?>"
					class="btn btn-secondary">
					Kembali
				</a>

				<button type="submit"
					class="btn btn-primary">
					<i class="fas fa-check"></i>
					Simpan Verifikasi
				</button>

			</div>

			<?= form_close(); ?>

		</div>

	</div>

</div>
