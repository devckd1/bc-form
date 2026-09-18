<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">Tambah Kebersihan Ruang Produksi</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('kebersihanruang') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Kebersihan Ruang Produksi
				</a>
			</li>
			<li class="breadcrumb-item active">Tambah</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form method="post" action="<?= base_url('kebersihanruang/tambah'); ?>" enctype="multipart/form-data">
				<div style="display: flex; gap: 20px;">
					<table border="1" cellpadding="5" cellspacing="0"
						style="border-collapse: collapse; width: 35%; font-size: 12px;">
						<thead style="background-color: #ADD8E6;">
							<tr>
								<th>Keterangan Pemeriksaan</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1. Berdebu</td>
							</tr>
							<tr>
								<td>2. Basah</td>
							</tr>
							<tr>
								<td>3. Pecah & Retak</td>
							</tr>
							<tr>
								<td>4. Sisa produksi</td>
							</tr>
							<tr>
								<td>5. Noda tinta/karat</td>
							</tr>
							<tr>
								<td>6. Jamur / bau</td>
							</tr>
						</tbody>
					</table>
				</div>

				<br>

				<?php
				$produksi_data = $this->session->userdata('produksi_data');
				$tanggal_sess = $produksi_data['tanggal'] ?? date('Y-m-d');
				$shift_sess   = $produksi_data['shift'] ?? '';
				?>

				<!-- ================= TANGGAL & SHIFT ================= -->
				<div class="form-group row">
					<div class="col-md-3">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= set_value('date', $tanggal_sess) ?>">
						<div class="invalid-feedback"><?= form_error('date') ?></div>
					</div>

					<div class="col-md-3">
						<label class="font-weight-bold">Shift</label>
						<select name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>">
							<option value="" disabled <?= empty($shift_sess) ? 'selected' : '' ?>>
								Pilih Shift
							</option>
							<option value="1" <?= set_select('shift', '1', $shift_sess == '1') ?>>
								Shift 1
							</option>
							<option value="2" <?= set_select('shift', '2', $shift_sess == '2') ?>>
								Shift 2
							</option>
							<option value="3" <?= set_select('shift', '3', $shift_sess == '3') ?>>
								Shift 3
							</option>
						</select>
						<div class="invalid-feedback"><?= form_error('shift') ?></div>
					</div>
				</div>

				<!-- ================= PLANT & LOKASI ================= -->
				<div class="form-group row">
					<div class="col-sm-3">
						<label class="font-weight-bold">Lokasi</label>
						<select name="lokasi" id="lokasiDropdown"
							class="form-control <?= form_error('lokasi') ? 'is-invalid' : '' ?>">
							<option value="">-- Pilih Lokasi --</option>
							<?php foreach ($area_list as $area): ?>
								<option value="<?= $area->area ?>"
									<?= set_select('lokasi', $area->area) ?>>
									<?= $area->area ?>
								</option>
							<?php endforeach; ?>
						</select>
						<div class="invalid-feedback">
							<?= form_error('lokasi') ?>
						</div>
					</div>
				</div>

				<hr>

				<!-- ================= FORM DINAMIS ================= -->
				<div id="dynamicFormArea"></div>

				<div class="form-group row">
					<div class="col-md-4">
						<label class="font-weight-bold">Foto 1 (Opsional)</label>
						<input type="file" name="foto1" class="form-control" accept="image/*">
					</div>

					<div class="col-md-4">
						<label class="font-weight-bold">Foto 2 (Opsional)</label>
						<input type="file" name="foto2" class="form-control" accept="image/*">
					</div>
				</div>

				<br>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('kebersihanruang') ?>"
							class="btn btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>

			</form>
		</div>

	</div>
</div>
</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>
<script>
	$(document).ready(function() {

		$('#lokasiDropdown').on('change', function() {

			let area = $(this).val();

			if (area === "") {
				$('#dynamicFormArea').html('');
				return;
			}

			$.ajax({
				url: "<?= base_url('form/kebersihanruang/get_bagian_ajax') ?>",
				type: "POST",
				data: {
					area: area
				},
				success: function(response) {

					let bagianList = JSON.parse(response);

					if (bagianList.length === 0) {
						$('#dynamicFormArea').html('<div class="alert alert-warning">Data bagian tidak ditemukan</div>');
						return;
					}

					let kondisiOptions = ['bersih', '1', '2', '3', '4', '5', '6'];
					let html = '';

					html += '<label class="font-weight-bold">' + area.toUpperCase() + '</label>';
					
					html += `
					<hr>
					<div class="form-group row">
						<div class="col-md-4">
							<label class="font-weight-bold">Foto 1 (Opsional)</label>
							<input type="file" name="foto1" class="form-control" accept="image/*">
						</div>
						<div class="col-md-4">
							<label class="font-weight-bold">Foto 2 (Opsional)</label>
							<input type="file" name="foto2" class="form-control" accept="image/*">
						</div>
					</div>
					`;

					bagianList.forEach(function(bagian, index) {

						html += `
                    <div class="form-group row mt-3">

                        <div class="col-sm-2">
                            <label class="font-weight-bold">Bagian</label>
                            <input type="text" name="bagian[]" 
                                   class="form-control"
                                   value="${bagian}" readonly>
                        </div>

                        <div class="col-sm-4">
                            <label class="font-weight-bold d-block">Kondisi</label>
                        `;

						kondisiOptions.forEach(function(opt) {

							// AUTO CHECK BERSIH DI AWAL
							let checked = (opt === 'bersih') ? 'checked' : '';

							html += `
                        <div class="form-check form-check-inline">
                            <input class="form-check-input kondisi-group-${index}" 
                                   type="checkbox" 
                                   name="kondisi[${index}][]" 
                                   value="${opt}" ${checked}>
                            <label class="form-check-label">${opt}</label>
                            </div>`;
						});

						html += `
                        </div>

                        <div class="col-sm-3">
                            <label class="font-weight-bold">Problem</label>
                            <input type="text" name="problem[]" 
                                   class="form-control">
                        </div>

                        <div class="col-sm-3">
                            <label class="font-weight-bold">Tindakan</label>
                            <input type="text" name="tindakan[]" 
                                   class="form-control">
                        </div>

                        </div>`;
					});

					

					$('#dynamicFormArea').html(html);

					// PENTING: jalankan logic setelah html terpasang
					initCheckboxLogic();
				}
			});
		});


		function initCheckboxLogic() {

			$('[class^="kondisi-group-"]').each(function() {

				let className = $(this).attr('class').split(' ')[1];
				let group = $('.' + className);

				group.off('change').on('change', function() {

					let bersih = group.filter('[value="bersih"]');
					let selainBersih = group.not('[value="bersih"]');

					// Jika klik bersih
					if ($(this).val() === 'bersih') {
						if ($(this).is(':checked')) {
							selainBersih.prop('checked', false);
							selainBersih.prop('disabled', true);
						} else {
							selainBersih.prop('disabled', false);
						}
					}

					// Jika klik selain bersih
					if ($(this).val() !== 'bersih') {
						bersih.prop('checked', false);
						bersih.prop('disabled', true);

						if (selainBersih.filter(':checked').length === 0) {
							bersih.prop('disabled', false);
							bersih.prop('checked', true);
						}
					}
				});
			});
		}

	});
</script>
