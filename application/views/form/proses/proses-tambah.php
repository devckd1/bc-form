<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">Tambah Verifikasi Proses Produksi</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('proses') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Laporan Verifikasi Proses Produksi
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" method="post" action="<?= base_url('proses/tambah'); ?>">
				<input type="hidden" name="jumlah_kolom" id="jumlah_kolom" value="1">
				<?php
				$produksi_data = $this->session->userdata('produksi_data');
				$tanggal_sess = $produksi_data['tanggal'] ?? date('Y-m-d');
				$shift_sess = $produksi_data['shift'] ?? '';

				$plant_sess = $this->session->userdata('plant');

				$kode_plant = '';

				if ($plant_sess === '651ac623-5e48-44cc-b2f6-5d622603f53c') {
					$kode_plant = '1'; // Cikande
				} elseif ($plant_sess === '1eb341e0-1ec4-4484-ba8f-32d23352b84d') {
					$kode_plant = '3'; // Salatiga
				}

				$proses_produksi = [
					'dough_mixing' => [
						[
							'label' => 'Acuan No. Dokumen / Revisi / Formula',
							'params' => ['dokumen', 'revisi', 'no_formula']
						],
						'nama_produk',
						'kode_produksi'
					],
					'kondisi_rm' => [
						'tepung_terigu',
						'tepung_tapioka',
						'yeast',
						'bread_improver',
						'premix',
						[
							'label' => 'N55BC',
							'params' => ['n55bc']
						],
						[
							'label' => 'NP55BCY / NP55BCO',
							'params' => ['np55bcy_np55bco']
						],
						'shortening',
						'chill_water'
					],
					'mixing' => [
						['label' => 'Waktu Mixing (menit speed 1 / speed 2)', 'params' => ['waktu_mixing_1', 'waktu_mixing_2']],
						'sensori',
						'suhu_adonan',
						'berat_adonan'
					],
					'proofing' => [
						['label' => 'Jam Mulai / Selesai', 'params' => ['jam_mulai', 'jam_selesai']],
						['label' => 'Suhu Setting / Aktual (34 - 36°C)', 'params' => ['suhu_setting', 'suhu_aktual']],
						['label' => 'RH Setting / Aktual (78 - 82%)', 'params' => ['rh_setting', 'rh_aktual']],
						'durasi_waktu',
						'hasil_proofing'
					],
					'electric_baking' => [
						['label' => 'Baking Time (High / Low)', 'params' => ['baking_time_high', 'baking_time_low']],
					],
					'hasil_baking' => ['suhu_produk', 'sensori_produk']
				];

				$label_param = [
					'dokumen' => 'Acuan Dokumen',
					'revisi' => 'Revisi',
					'no_formula' => 'No. Formula',
					'nama_produk' => 'Jenis Produk',
					'kode_produksi' => 'Kode Produksi',
					'tepung_terigu' => 'Tepung Terigu',
					'tepung_tapioka' => 'Tepung Tapioka',
					'yeast' => 'Yeast',
					'bread_improver' => 'Bread Improver',
					'premix' => 'Premix',
					'n55bc_sensori' => 'Sensori',
					'n55bc_formulasi' => 'Formulasi',
					'np55bcy_np55bco_sensori' => 'Sensori',
					'np55bcy_np55bco_formulasi' => 'Formulasi',
					'shortening' => 'Shortening',
					'chill_water' => 'Chill Water (14 - 16°C)',
					'waktu_mixing_1' => 'Waktu Mixing 1',
					'waktu_mixing_2' => 'Waktu Mixing 2',
					'sensori' => 'Sensori',
					'suhu_adonan' => 'Suhu Adonan (29 - 31°C)',
					'berat_adonan' => 'Berat Adonan (630 - 670 g/pcs)',
					'jam_mulai' => 'Jam Mulai',
					'jam_selesai' => 'Jam Selesai',
					'suhu_setting' => 'Suhu Setting (34 - 36°C)',
					'suhu_aktual' => 'Suhu Aktual',
					'rh_setting' => 'RH Setting (78 - 82%)',
					'rh_aktual' => 'RH Aktual',
					'durasi_waktu' => 'Durasi Waktu (60 - 70 menit)',
					'hasil_proofing' => 'Hasil Proofing',
					'baking_time_high' => 'Baking Time High',
					'baking_time_low' => 'Baking Time Low',
					'suhu_produk' => 'Suhu Produk (80 - 97°C)',
					'sensori_produk' => 'Sensori Produk'
				];

				$standar_berat = [
					'tepung_terigu' => '32.960',
					'tepung_tapioka' => '2.270',
					'yeast' => '0.732',
					'bread_improver' => '0.038',
					'premix' => ['default' => '0.7468', 'col8' => '0.7653'],
					'shortening' => '0.252',
					'chill_water' => '18'
				];

				$default_mixing = ['waktu_mixing_1' => 3, 'waktu_mixing_2' => 8];
				$default_baking = ['baking_time_high' => 5, 'baking_time_low' => 7];
				$default_suhu = ['suhu_setting' => 35, 'rh_setting' => 80];
				?>

				<!-- Form Header -->
				<div class="form-group row">
					<div class="col-md-3">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date" id="tanggal_mixing" class="form-control" data-kode-plant="<?= $kode_plant ?>" value="<?= set_value('date', $tanggal_sess) ?>">
					</div>
					<div class="col-md-3">
						<label class="font-weight-bold">Shift</label>
						<select name="shift" class="form-control">
							<option disabled <?= empty($shift_sess) ? 'selected' : '' ?>>Pilih Shift</option>
							<?php for ($s = 1; $s <= 3; $s++): ?>
								<option value="<?= $s ?>" <?= set_select('shift', $s, $shift_sess == $s) ?>>Shift <?= $s ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label class="font-weight-bold">Nama Produk</label>
						<select name="nama_produk" id="select_nama_produk" class="form-control">
							<option value="">-- Pilih Produk --</option>
							<?php foreach ($produk_list as $p): ?>
								<option
									value="<?= html_escape($p->nama_produk) ?>"
									data-uuid="<?= html_escape($p->uuid) ?>">
									<?= html_escape($p->nama_produk) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<!-- Table -->
				<div class="d-flex justify-content-end align-items-center mt-3 mb-2">
					<button type="button" id="btnTambahInput" class="btn btn-primary btn-sm mr-1">
						<i class="fas fa-plus"></i> Tambah
					</button>

					<button type="button" id="btnHapusInput" class="btn btn-danger btn-sm" disabled>
						<i class="fas fa-trash"></i> Hapus
					</button>
				</div>

				<div class="table-responsive" style="overflow-x:auto;">
					<table id="tabelProsesProduksi" class="table table-bordered table-sm">
						<thead class="thead-light">
							<tr>
								<th>Jenis Produksi</th>
								<th>Std. Berat</th>
								<th>Kode Batch</th>
								<?php for ($i = 1; $i <= 10; $i++): ?>
									<th class="text-center kolom-input kolom-input-<?= $i ?>">
										Input ke-<?= $i ?>
									</th>
								<?php endfor; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($proses_produksi as $kategori => $params): ?>
								<tr style="background:#f1f1f1;font-weight:bold;">
									<td class="baris-kategori" colspan="13">
										<?= ucwords(str_replace('_', ' ', $kategori)) ?>
									</td>
								</tr>

								<?php foreach ($params as $param):
									if (is_array($param)) {
										$label = $param['label'];
										$subParams = $param['params'];
									} else {
										$label = $label_param[$param] ?? ucwords(str_replace('_', ' ', $param));
										$subParams = [$param];
									}
								?>
									<tr>
										<td><?= $label ?></td>

										<!-- Kolom Standar Berat / Kode Batch -->
										<td class="text-center">
											<?php if ($kategori == 'kondisi_rm'): ?>

												<?php if (in_array($subParams[0], [
													'n55bc_sensori',
													'np55bcy_np55bco_sensori'
												])): ?>

													QH14101AA0

												<?php elseif ($subParams[0] != 'premix'): ?>

													<?php
													$rawStd = $standar_berat[$subParams[0]] ?? '';
													$stdVal = is_array($rawStd)
														? $rawStd['default']
														: $rawStd;
													?>

													<input type="text"
														name="proses_produksi[<?= $kategori ?>][<?= $subParams[0] ?>][0]"
														class="form-control form-control-sm"
														value="<?= $stdVal ?>">

												<?php endif; ?>

											<?php endif; ?>
										</td>

										<!-- Kolom Kode Batch -->
										<td class="text-center">
											<?php if (
												$kategori === 'kondisi_rm' &&
												in_array($subParams[0], ['tepung_terigu', 'tepung_tapioka', 'yeast', 'bread_improver', 'n55bc', 'np55bcy_np55bco', 'shortening', 'chill_water'], true)
											): ?>
												<input type="text"
													name="proses_produksi[<?= $kategori ?>][kode_batch_<?= $subParams[0] ?>]"
													class="form-control form-control-sm"
													placeholder="Kode Batch">
											<?php endif; ?>
										</td>

										<!-- Kolom Input Dinamis -->
										<?php for ($col = 1; $col <= 10; $col++): ?>

											<td class="text-center kolom-input kolom-input-<?= $col ?>">

												<?php
												// Khusus Acuan Dokumen / Revisi / Formula
												$isAcuanFormula = (
													$kategori === 'dough_mixing' &&
													$subParams === ['dokumen', 'revisi', 'no_formula']
												);

												if ($isAcuanFormula):
													$fieldAcuan = [
														'dokumen',
														'revisi',
														'no_formula'
													];
												?>

													<div class="d-flex align-items-center" style="gap: 4px;">
														<?php foreach ($fieldAcuan as $field): ?>

															<?php
															$classAcuan = '';

															if ($field === 'dokumen') {
																$classAcuan = 'acuan-dokumen';
															} elseif ($field === 'revisi') {
																$classAcuan = 'acuan-revisi';
															} elseif ($field === 'no_formula') {
																$classAcuan = 'acuan-formula';
															}
															?>

															<input type="text"
																name="proses_produksi[<?= $kategori ?>][<?= $field ?>][<?= $col ?>]"
																class="form-control form-control-sm <?= $classAcuan ?>"
																placeholder="<?= $label_param[$field] ?>">

														<?php endforeach; ?>
													</div>

												<?php else: ?>

													<?php foreach ($subParams as $sub):

														$value = '';

														if (isset($default_mixing[$sub])) {
															$value = $default_mixing[$sub];
														}

														if (isset($default_baking[$sub])) {
															$value = $default_baking[$sub];
														}

														if (isset($default_suhu[$sub])) {
															$value = $default_suhu[$sub];
														}

														$subLower = strtolower($sub);

														$isCheckbox = false;
														$isNumber = false;

														// Premix tetap kosong
														if ($kategori == 'kondisi_rm' && $subLower == 'premix') {
															continue;
														}

														// Kondisi RM
														if (
															$kategori == 'kondisi_rm' &&
															$subLower !== 'chill_water'
														) {
															$isCheckbox = true;
														}

														// Checkbox Sensori dan Hasil
														elseif (in_array($subLower, [
															'sensori',
															'hasil_proofing',
															'sensori_produk'
														])) {
															$isCheckbox = true;
														}

														// Input angka
														elseif (
															$subLower === 'chill_water' ||
															strpos($subLower, 'suhu') !== false
														) {
															$isNumber = true;
														}

														$isKodeProduksi = (
															$kategori == 'dough_mixing' &&
															$sub == 'kode_produksi'
														);

														$label_checkbox = '';

														if (strpos($subLower, '_sensori') !== false) {
															$label_checkbox = 'Sensori';
														} elseif (strpos($subLower, '_formulasi') !== false) {
															$label_checkbox = 'Formulasi';
														}
													?>

														<?php if ($isCheckbox): ?>

															<div class="d-inline-flex flex-column align-items-center mx-2">
																<?php if ($label_checkbox): ?>
																	<small class="mb-1">
																		<?= $label_checkbox ?>
																	</small>
																<?php endif; ?>

																<input type="checkbox"
																	name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
																	value="✗"
																	class="checkbox-lg toggle-check">
															</div>

														<?php elseif ($isNumber): ?>

															<input type="number"
																step="0.01"
																name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
																value="<?= $value ?>"
																class="form-control form-control-sm"
																placeholder="0.00">

														<?php else: ?>

															<input type="<?= in_array($sub, ['jam_mulai', 'jam_selesai']) ? 'time' : 'text' ?>"
																name="proses_produksi[<?= $kategori ?>][<?= $sub ?>][<?= $col ?>]"
																value="<?= $value ?>"
																class="form-control form-control-sm <?= $isKodeProduksi ? 'kode_produksi_field' : '' ?>"
																id="<?= $isKodeProduksi && $col == 1 ? 'kode_produksi_1' : '' ?>">

														<?php endif; ?>

													<?php endforeach; ?>

												<?php endif; ?>

											</td>

										<?php endfor; ?>
									</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div class="form-group row mt-3">
					<div class="col-sm-6">
						<label class="font-weight-bold">Catatan</label>
						<textarea class="form-control" name="catatan"></textarea>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-success mr-2"><i class="fa fa-save"></i> Simpan</button>
						<a href="<?= base_url('proses') ?>" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<script>
	
$(document).ready(function() {

    // =========================================
    // AUTOFILL JENIS PRODUK
    // =========================================
    $('#select_nama_produk').on('change', function() {
        const selectedProdukRaw = $(this).val() || '';
        const selectedProduk = selectedProdukRaw.trim().toLowerCase();

        const jenisProdukInputs = $(
            'input[name^="proses_produksi[dough_mixing][nama_produk]"]'
        );

        jenisProdukInputs.val('');

        if (selectedProduk === 'bc orange sintetis') {
            jenisProdukInputs.val('BC Orange');
        } else if (selectedProduk.includes('bc mix')) {
            jenisProdukInputs.each(function(index) {
                $(this).val(index < 7 ? 'BC Yellow' : 'BC Orange');
            });
        } else {
            jenisProdukInputs.val(selectedProdukRaw);
        }
    });


    // =========================================
    // AUTOFILL ACUAN MASTER FORMULA
    // =========================================
    let formulaRequest = null;

    $('#select_nama_produk').on('change', function() {

        const produkUuid = $(this).find(':selected').data('uuid');

        // Batalkan request sebelumnya.
        if (formulaRequest && formulaRequest.readyState !== 4) {
            formulaRequest.abort();
        }

        // Kosongkan acuan sebelumnya.
        $('.acuan-dokumen').val('');
        $('.acuan-revisi').val('');
        $('.acuan-formula').val('');

        if (!produkUuid) {
            return;
        }

        formulaRequest = $.ajax({
            url: '<?= site_url("form/proses/get_master_formula") ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                produk_uuid: produkUuid
            },

            success: function(response) {

                if (!response.success) {
                    console.warn(response.message);
                    return;
                }

                const data = response.data;

                // Isi seluruh kolom Dokumen.
                $('.acuan-dokumen').val(data.no_dokumen || '');

                // Isi seluruh kolom Revisi.
                $('.acuan-revisi').val(data.revisi || '');

                // Isi seluruh kolom Formula.
                $('.acuan-formula').val(data.formula || '');
            },

            error: function(xhr, status) {
                if (status !== 'abort') {
                    console.error(
                        'Gagal mengambil Master Formula:',
                        xhr.responseText
                    );
                }
            }
        });

    });

    // Jalankan autofill jika produk sudah terpilih saat halaman dibuka.
    if ($('#select_nama_produk').val()) {
        $('#select_nama_produk').trigger('change');
    }


    // =========================================
    // AUTOFILL PREFIX KODE PRODUKSI
    // =========================================
    let prefixKodeProduksi = '';

    function buatPrefixKodeProduksi() {
        const tanggal = $('#tanggal_mixing').val();
        const kodePlant = $('#tanggal_mixing').data('kode-plant');

        if (!tanggal || !kodePlant) {
            return '';
        }

        const [tahun, bulan, hari] = tanggal.split('-').map(Number);

        // Tahun 2026 = Q, 2027 = R, dan seterusnya.
        const kodeTahun = String.fromCharCode(65 + (tahun - 2010));

        // Januari = A, Februari = B, ..., November = K.
        const kodeBulan = String.fromCharCode(64 + bulan);

        // Format: kode tahun + kode bulan + tanggal + kode plant.
        return kodeTahun + kodeBulan +
            String(hari).padStart(2, '0') +
            kodePlant;
    }

    function perbaruiPrefixKodeProduksi() {
        const prefixBaru = buatPrefixKodeProduksi();

        if (!prefixBaru) {
            return;
        }

        $('.kode_produksi_field').each(function() {
            let nilai = $(this).val().trim();

            // Pertahankan bagian belakang yang sudah diisi user.
            if (prefixKodeProduksi && nilai.startsWith(prefixKodeProduksi)) {
                nilai = nilai.substring(prefixKodeProduksi.length);
            } else if (nilai.startsWith(prefixBaru)) {
                nilai = nilai.substring(prefixBaru.length);
            }

            $(this).val(prefixBaru + nilai);
        });

        prefixKodeProduksi = prefixBaru;
    }

    // Jalankan saat tanggal berubah.
    $('#tanggal_mixing').on('change', function() {
        perbaruiPrefixKodeProduksi();
    });

    // Jalankan saat halaman pertama kali dibuka.
    perbaruiPrefixKodeProduksi();


    // =========================================
    // KOLOM INPUT DINAMIS
    // =========================================
    let jumlahKolomAktif = 1;
    const maksimalKolom = 10;

    function perbaruiKolomInput() {

        for (let i = 1; i <= maksimalKolom; i++) {
            $('.kolom-input-' + i).toggleClass(
                'aktif',
                i <= jumlahKolomAktif
            );
        }

        // Simpan jumlah kolom aktif untuk dikirim ke model.
        $('#jumlah_kolom').val(jumlahKolomAktif);

        // Sesuaikan colspan baris kategori.
        $('.baris-kategori').attr(
            'colspan',
            3 + jumlahKolomAktif
        );

        // Atur tombol tambah dan hapus.
        $('#btnTambahInput').prop(
            'disabled',
            jumlahKolomAktif >= maksimalKolom
        );

        $('#btnHapusInput').prop(
            'disabled',
            jumlahKolomAktif <= 1
        );
    }

    // Tombol tambah kolom.
    $('#btnTambahInput').on('click', function() {
        if (jumlahKolomAktif < maksimalKolom) {
            jumlahKolomAktif++;
            perbaruiKolomInput();
        }
    });

    // Tombol hapus kolom terakhir.
    $('#btnHapusInput').on('click', function() {

        if (jumlahKolomAktif <= 1) {
            return;
        }

        const kolomDihapus = jumlahKolomAktif;

        if (!confirm(
            'Hapus Input ke-' + kolomDihapus +
            '? Data pada kolom ini akan dikosongkan.'
        )) {
            return;
        }

        $('.kolom-input-' + kolomDihapus)
            .find('input')
            .each(function() {
                if ($(this).is(':checkbox')) {
                    $(this).prop('checked', false).val('✗');
                } else {
                    $(this).val('');
                }
            });

        jumlahKolomAktif--;
        perbaruiKolomInput();
    });


    // =========================================
    // PERHITUNGAN DURASI PROOFING
    // =========================================
    function hitungDurasi(idx) {

        const jamMulai = $(
            'input[name="proses_produksi[proofing][jam_mulai][' + idx + ']"]'
        ).val();

        const jamSelesai = $(
            'input[name="proses_produksi[proofing][jam_selesai][' + idx + ']"]'
        ).val();

        const durasiInput = $(
            'input[name="proses_produksi[proofing][durasi_waktu][' + idx + ']"]'
        );

        if (!jamMulai || !jamSelesai) {
            durasiInput.val('');
            return;
        }

        const mulai = jamMulai.split(':').map(Number);
        const selesai = jamSelesai.split(':').map(Number);

        let totalMulai = mulai[0] * 60 + mulai[1];
        let totalSelesai = selesai[0] * 60 + selesai[1];

        // Jika selesai melewati tengah malam.
        if (totalSelesai < totalMulai) {
            totalSelesai += 24 * 60;
        }

        durasiInput.val(totalSelesai - totalMulai);
    }

    $(document).on(
        'change',
        'input[name*="[jam_mulai]"], input[name*="[jam_selesai]"]',
        function() {

            const match = (this.name || '').match(/\[(\d+)\]$/);

            if (match) {
                hitungDurasi(parseInt(match[1], 10));
            }
        }
    );


    // =========================================
    // CHECKBOX ✓ / ✗
    // =========================================
    $(document).on('change', '.toggle-check', function() {
        $(this).val($(this).is(':checked') ? '✓' : '✗');
    });


    // =========================================
    // INISIALISASI
    // =========================================
    perbaruiKolomInput();

});

</script>


<style>
	.checkbox-lg {
		transform: scale(1.4);
		display: block;
		margin: 0 auto;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	/* Tombol kolom dinamis */
	#btnTambahInput,
	#btnHapusInput {
		padding: 4px 9px;
		font-size: 11px;
		line-height: 1.4;
		border-radius: 4px;
		box-shadow: none;
	}

	#btnTambahInput:disabled,
	#btnHapusInput:disabled {
		opacity: 0.45;
		cursor: not-allowed;
	}

	/* Kontainer tabel */
	.table-responsive {
		width: 100%;
		overflow-x: auto;
	}

	/* Tabel memenuhi ruang yang tersedia */
	#tabelProsesProduksi {
		width: 100% !important;
		table-layout: fixed;
		margin-bottom: 0;
		border-collapse: collapse;
	}

	#tabelProsesProduksi th,
	#tabelProsesProduksi td {
		vertical-align: middle;
		box-sizing: border-box;
		padding: 5px;
	}

	/* Lebar kolom tetap */
	#tabelProsesProduksi th:first-child,
	#tabelProsesProduksi td:first-child {
		width: 220px;
	}

	#tabelProsesProduksi th:nth-child(2),
	#tabelProsesProduksi td:nth-child(2) {
		width: 100px;
	}

	/* Kolom input disembunyikan secara default */
	#tabelProsesProduksi .kolom-input {
		display: none;
	}

	#tabelProsesProduksi .kolom-input.aktif {
		display: table-cell;
	}

	/* Input memenuhi lebar sel */
	#tabelProsesProduksi input.form-control {
		display: block;
		width: 100%;
		min-width: 0;
		max-width: 100%;
		box-sizing: border-box;
		padding: 4px 6px;
		font-size: 12px;
	}

	/* Baris judul kategori */
	#tabelProsesProduksi .baris-kategori td {
		background-color: #f1f1f1;
		font-weight: bold;
	}

	/* Responsif */
	@media (max-width: 768px) {

		#tabelProsesProduksi th:first-child,
		#tabelProsesProduksi td:first-child {
			width: 160px;
		}

		#tabelProsesProduksi th:nth-child(2),
		#tabelProsesProduksi td:nth-child(2) {
			width: 85px;
		}

		#tabelProsesProduksi input.form-control {
			font-size: 11px;
		}
	}

	/* Checkbox Sensori dan Formulasi */
	#tabelProsesProduksi .kolom-input .d-inline-flex {
		min-width: 65px;
		vertical-align: middle;
	}

	#tabelProsesProduksi .kolom-input small {
		font-size: 10px;
		white-space: nowrap;
	}

	#tabelProsesProduksi .checkbox-lg {
		margin: 3px auto;
	}

	#tabelProsesProduksi th:nth-child(3),
	#tabelProsesProduksi td:nth-child(3) {
		width: 120px;
	}
</style>
