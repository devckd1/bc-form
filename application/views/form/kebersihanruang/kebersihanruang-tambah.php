<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Tambah Kebersihan Ruang Produksi</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('kebersihanruang') ?>">
					<i class="fas fa-arrow-left">
					</i> Daftar Kebersihan Ruang Produksi</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>
	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" method="post" action="<?= base_url('kebersihanruang/tambah'); ?>" enctype="multipart/form-data">
				<div style="display: flex; gap: 20px;">
					<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 30%; text-align: left; font-family: Arial, sans-serif; font-size: 12px;">
						<thead style="background-color: #f2f2f2;">
							<tr>
								<th colspan="2" style="padding: 5px; background-color: #ADD8E6; color: gray;">Keterangan Pemeriksaan</th>
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
								<td>4. Sisa produksi seperti sisa terigu/produk</td>
							</tr>
							<tr>
								<td>5. Noda seperti tinta, karat</td>
							</tr>
							<tr>
								<td>6. Pertumbuhan mikroorganisme, seperti jamur dan bau busuk</td>
							</tr>
						</tbody>
					</table>
				</div>
				<br>
				<?php
				$produksi_data = $this->session->userdata('produksi_data');
				$tanggal_sess = $produksi_data['tanggal'] ?? date('Y-m-d');
				$shift_sess = $produksi_data['shift'] ?? '';
				?>
				<div class="form-group row">
					<div class="col-md-3">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date" class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= set_value('date', $tanggal_sess) ?>">
						<div class="invalid-feedback"><?= form_error('date') ?></div>
					</div>
					<div class="col-md-3">
						<label class="font-weight-bold">Shift</label>
						<select name="shift" class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>">
							<option disabled <?= empty($shift_sess) ? 'selected' : '' ?>>Pilih Shift</option>
							<option value="1" <?= set_select('shift', '1', $shift_sess == '1') ?>>Shift 1</option>
							<option value="2" <?= set_select('shift', '2', $shift_sess == '2') ?>>Shift 2</option>
							<option value="3" <?= set_select('shift', '3', $shift_sess == '3') ?>>Shift 3</option>
						</select>
						<div class="invalid-feedback"><?= form_error('shift') ?></div>
					</div>
				</div>
				<?php
				$plant_uuid = $this->session->userdata('plant');
				$plant_map = [
					'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Cikande',
					'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Salatiga'
				];
				$plant_name = isset($plant_map[$plant_uuid]) ? $plant_map[$plant_uuid] : '-';
				?>

				<div class="form-group row">
					<div class="col-sm-3">
						<label class="form-label font-weight-bold">Plant</label>
						<input type="text" class="form-control" value="<?= $plant_name ?>" readonly>
						<input type="hidden" name="plant" id="plantHidden" value="<?= $plant_name ?>">
					</div>

					<div class="col-sm-3">
						<label class="form-label font-weight-bold">Lokasi</label>
						<select name="lokasi" id="lokasiDropdown" class="form-control <?= form_error('lokasi') ? 'is-invalid' : '' ?>">
							<option value="">-- Pilih Lokasi --</option>
						</select>
						<div class="invalid-feedback <?= form_error('lokasi') ? 'd-block' : '' ?>">
							<?= form_error('lokasi') ?>
						</div>
					</div>
				</div>

				<hr>

				<!-- area pengayakan -->
				<div id="form-pengayakan" class="form-area d-none">
					<label class="form-label font-weight-bold">PENGAYAKAN</label>
					<?php
					$bagianpengayakan = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Mesin Ayakan', 'Rak Kabel', 'Exhaust Fan'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>

					<?php foreach ($bagianpengayakan as $indexpengayakan => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpengayakan;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form pengayakan -->

				<!-- area premix -->
				<div id="form-premix" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PREMIX</label>
					<?php
					$bagianpremix = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Rak Penyimpanan', 'AC'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpremix as $indexpremix => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpremix;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form premix -->

				<!-- area mixing -->
				<div id="form-mixing" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA MIXING</label>
					<?php
					$bagianmixing = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Mixer 1,2,3 & 4', 'Mesin Dough Moulder 1 & 2', 'Saluran air buangan & Cover', ' Pipa air dingin & Filter', 'Tulisan Area Mixer'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianmixing as $indexmixing => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexmixing;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form mixing -->

				<!-- area proofing -->
				<div id="form-proofing" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PROOFING</label>
					<?php
					$bagianproofing = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Ruang Proofing', 'Rak Kabel'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianproofing as $indexproofing => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexproofing;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form proofing -->

				<!-- area electric -->
				<div id="form-electric" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA ELECTRIC BAKING</label>
					<?php
					$bagianelectric = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Electric Baking 1,2,3 & 4', 'Pipa Exhaust', 'Rak Kabel', 'Air Bag Cooler'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianelectric as $indexelectric => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexelectric;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form electric -->

				<!-- area aging -->
				<div id="form-aging" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA AGING</label>
					<?php
					$bagianaging = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Aging room 1 & 2', 'Rak Kabel', 'Air Bag Cooler', 'Tulisan Area Aging'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianaging as $indexaging => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexaging;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form aging -->

				<!-- area grinder -->
				<div id="form-grinder" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA GRINDER</label>
					<?php
					$bagiangrinder = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Mesin Grinder 1 & 2', 'Transfer Conveyor 1 & 2'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagiangrinder as $indexgrinder => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexgrinder;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form grinder -->

				<!-- area packing -->
				<div id="form-packing" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PACKING</label>
					<?php
					$bagianpacking = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Tulisan Area Packing', 'Feeding Conveyor', 'Burner', 'Mesin Rotary Dryer', 'Dischard Conveyor', 'Separator Magnet', 'Ayakan', 'Cooling Conveyor', 'Hooper Finish Good', 'Mesin Coding', 'Mesin Metal Detector', 'Rejector Metal Detector', 'Sealer Box', 'Mesin Sachet Tepung'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpacking as $indexpacking => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpacking;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form packing -->

				<!-- area pencucian -->
				<div id="form-pencucian" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PENCUCIAN</label>
					<?php
					$bagianpencucian = ['Lantai', 'Dinding', 'Pintu & Curtain', 'Langit-langit', 'Lampu & Cover', 'Softener Filter', 'Bak Pencucian'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpencucian as $indexpencucian => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpencucian;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form pencucian -->

				<!-- area buffer -->
				<div id="form-buffer-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA BUFFER TEPUNG</label>
					<?php
					$bagianbuffer = ['Ruangan', 'Pintu dan Tirai Plastik', 'Pintu Transfer', 'Blower/Exhaust', 'Lampu+Cover', 'Palet'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianbuffer as $indexbuffer => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexbuffer;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form buffer -->

				<!-- area pengayakan -->
				<div id="form-pengayakan-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PENGAYAKAN</label>
					<?php
					$bagianpengayakan = ['Ruangan', 'Pintu dan Tirai Plastik', 'Lampu+Cover', 'Palet', 'Hand Palet', 'Saklar/panel lampu', 'Exhaust fan 1', 'Exhaust fan 2', 'Dust Collector', 'Akrilik penutup blower', 'Mesin Sieving', 'Box tampungan tepung terigu (box kuning besar', ' Box tampungan tepung tapioka (box biru besar)', 'Penutup box tepung terigu', 'Box tampungan tepung terigu (box kuning kecil)', 'Box tampungan tepung tapioka (box biru kecil)', 'Penutup box tepung tapioka', 'Baskom Tepung Tapioka', 'Sekop tepung terigu', 'Sekop tepung tapioka', 'Meja timbangan', 'Timbangan tepung terigu', 'Timbangan tepung tapioka', 'Gunting', 'Trolley', 'Tempat sampah'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpengayakan as $indexpengayakan => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpengayakan;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form pengayakan -->

				<!-- area premix -->
				<div id="form-premix-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PREMIX</label>
					<?php
					$bagianpremix = ['Ruangan', 'Pintu dan Tirai Plastik', 'Pendingin', 'Lampu+Cover', 'Akrilik', 'Palet', 'Rak Penyimpanan 1', 'Rak Penyimpanan 2', 'Rak Penyimpanan 3', 'Rak Penyimpanan Ragi', 'Mangkok ragi', 'Chiller 1', 'Chiller 2', 'Chiller 3', 'Chiller 4', 'Chiller 5', 'Chiller 6', 'Meja Penimbangan', 'Meja Timbangan', ' Timbangan', 'Scrapper/pisau', 'Gunting', 'Sekop pewarna', 'Box Bahan', 'Tangga kecil stainless'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpremix as $indexpremix => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpremix;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form premix -->

				<!-- area preparasi -->
				<div id="form-preparasi-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PREPARASI</label>
					<?php
					$bagianpreparasi = ['Ruangan', 'Lampu+Cover', 'Panel listrik', 'Area idle', ' Meja preparasi', 'Trolley', 'Telepon', 'Tempat sampah', 'Alas minyak goreng', 'Fly catcher', 'Meja Timbangan'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpreparasi as $indexpreparasi => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpreparasi;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form preparasi -->

				<!-- area mixing -->
				<div id="form-mixing-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA MIXING ADONAN</label>
					<?php
					$bagianmixing = ['Ruangan', 'Pintu dan tirai plastik', 'Selokan', 'Lampu+Cover', 'Blower/Exhaust', 'Panel listrik', 'Panel Chill water', 'Display jam', 'Dough Mixer 1', 'Dough Mixer 2', 'Dough Mixer 3', 'Dough Mixer 4', 'Box tampungan air', 'Rak box tampungan air', 'Mangkok pewarna', 'Rak Mangkok pewarna', 'Hand whisker', 'Filter air', 'Pipa Air', 'Selang chill water', 'Selang air cleaning', 'Meja adonan 1', 'Meja adonan 2', 'Meja adonan 3', 'Scrapper', 'Mesin sheeting dan moulding', 'Dough divider', 'Box reject adonan', 'Box Rework Adonan', 'Alas box tampungan tepung (box kecil)', 'Tempat sampah'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianmixing as $indexmixing => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexmixing;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form mixing -->

				<!-- area fermentasi -->
				<div id="form-fermentasi-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA FERMENTASI</label>
					<?php
					$bagianfermentasi = ['Ruangan', 'Pintu', 'Lampu+Cover', 'Blower AHU', 'Panel'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianfermentasi as $indexfermentasi => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexfermentasi;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form fermentasi -->

				<!-- area pemasakan -->
				<div id="form-pemasakan-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PEMASAKAN</label>
					<?php
					$bagianpemasakan = ['Ruangan', 'Pintu dan tirai plastik', 'Blower AHU', 'Ventilasi udara', 'Lampu&Cover', 'Kipas angin', 'Display jam', 'Electric baking 1', 'Electric baking 2', 'Electric baking 3', 'Electric baking 4', 'Panel Baking', 'Baking cart', 'Titanium plat', 'Pisau trimming', 'Scrapper', 'Meja Transfer roti after baking', 'Meja tampungan roti gosong', 'Box reject', 'Tampungan dan spryer minyak', 'Fly Catcher', 'Meja Laporan'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpemasakan as $indexpemasakan => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpemasakan;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form pemasakan -->

				<!-- area aging -->
				<div id="form-aging-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA AGING</label>
					<?php
					$bagianaging = ['Ruangan', 'Pintu', 'Exhaust Fan', 'Fan Heater', 'Ventilasi udara', 'Panel listrik', 'Lampu&Cover', 'Cooling Rak', 'Nampan',];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianaging as $indexaging => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexaging;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form aging -->

				<!-- area cutting -->
				<div id="form-cutting-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA CUTTING</label>
					<?php
					$bagiancutting = ['Ruangan', 'Blower AHU', 'Lampu&Cover', 'Kipas Angin', 'Display suhu', 'Mesin Cutting 1', 'Mesin Cutting 2', 'Meja tampungan roti 1', 'Meja tampungan roti 2', 'Meja tampungan roti gosong', 'Loyang Reject', 'Helm K3', 'Pintu emergency'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagiancutting as $indexcutting => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexcutting;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form cutting -->

				<!-- area grinding,drying,packing -->
				<div id="form-grinding-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA GRINDING, DRYING, DAN PACKING</label>
					<?php
					$bagiangrinding = ['Ruangan', 'Pintu dan Tirai Plastik', 'Blower AHU', 'Pipa sirkulasi udara', 'Panel listrik', 'Lampu&Cover', 'Kipas Angin', 'Meja tampungan roti', 'Meja tampungan roti reject', 'Box reject roti', 'Infeed Conveyor grinding 1', 'Infeed Conveyor grinding 2', 'Mesin Grinding 1', 'Mesin Grinding 2', 'Feeding Conveyor Dryer', 'Burner Rotary Dryer', 'Mesin Rotary Dryer', 'Transfer Conveyor Dryer', 'Separator Magnet after dryer', 'Mesin Sieving', 'Cooling Conveyor', 'Hopper Finshed Good', 'Separator Magnet Hopper', 'Meja Timbangan', 'Timbangan', 'Meja Packing', 'Mesin Jahit', 'Mesin Zanasi', 'Metal Detector', 'Box reject MD', 'Conveyor', 'Hand Palet', 'Palet', 'Alas WIP', 'Tempat Laporan', 'Rak kemasan 1', 'Rak Kemasan 2', 'Earmuff', 'Jam Dinding', 'Box Sortir', 'Box alas tampungan produk tidak lolos ayakan'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagiangrinding as $indexgrinding => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexgrinding;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form grinding,drying,packing -->

				<!-- area anteroom -->
				<div id="form-anteroom-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA ANTEROOM PACKING</label>
					<?php
					$bagiananteroom = ['Pintu Transfer dan Tirai Plastik'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagiananteroom as $indexanteroom => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexanteroom;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form anteroom -->

				<!-- area pencucian -->
				<div id="form-pencucian-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA PENCUCIAN</label>
					<?php
					$bagianpencucian = ['Ruangan', 'Pintu dan tirai plastik', 'Selokan', 'Lampu&Cover', 'Peralatan sanitasi', 'Alas Chemical'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpencucian as $indexpencucian => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpencucian;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form pencucian -->

				<!-- area koridor -->
				<div id="form-koridor-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA KORIDOR</label>
					<?php
					$bagiankoridor = ['Ruangan', 'Toilet', 'Loker sepatu luar', 'Loker seragam', 'Loker sepatu boot', 'Loker Kunci', 'Kotak P3K', 'Dispenser & Galon', 'Alas Galon', 'Lampu & Cover', 'Panel listrik', 'Wastafel', 'Hand Basin', 'Foot Basin', 'Foot Brush', 'Hand Dryer', 'Cermin Loker', 'Cermin Hand Basin', 'Hanger Seragam', 'Tempat Sampah'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagiankoridor as $indexkoridor => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexkoridor;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form koridor -->

				<!-- area ruang pewarna -->
				<div id="form-pewarna-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA RUANG PEWARNA</label>
					<?php
					$bagianpewarna = ['Ruangan', 'Pintu', 'Pendingin', 'Lampu & Cover', 'Panel Listrik', 'Palet'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianpewarna as $indexpewarna => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexpewarna;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form pewarna -->

				<!-- area ruang shortening -->
				<div id="form-shortening-ruang" class="form-area d-none">
					<label class="form-label font-weight-bold">AREA RUANG SHORTENING</label>
					<?php
					$bagianshortening = ['Ruangan', 'Pintu', 'Pendingin', 'Lampu & Cover', 'Palet'];
					$kondisi_options = array_merge(['bersih'], range(1, 6));
					?>
					<?php foreach ($bagianshortening as $indexshortening => $bagian): ?>
						<div class="form-group row">
							<div class="col-sm-2">
								<label class="form-label font-weight-bold">Bagian</label>
								<input type="text" name="bagian[]" class="form-control <?= form_error('bagian[]') ? 'invalid' : '' ?>" value="<?= $bagian ?>" readonly>
								<div class="invalid-feedback <?= !empty(form_error('bagian[]')) ? 'd-block' : '' ?>">
									<?= form_error('bagian[]') ?>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="form-label font-weight-bold d-block">Kondisi</label>
								<?php foreach ($kondisi_options as $opt): ?>
									<?php
									$value = (string) $opt;
									$id = 'kondisi-' . $value . '-' . $indexshortening;
									$checked = in_array($value, set_value('kondisi', [])) ? 'checked' : '';
									?>
									<div class="form-check form-check-inline">
										<input class="form-check-input kondisi-checkbox"
											type="checkbox"
											name="kondisi[]"
											value="<?= $value ?>"
											id="<?= $id ?>"
											<?= $checked ?>>
										<label class="form-check-label" for="<?= $id ?>"><?= ucfirst($value) ?></label>
									</div>
								<?php endforeach; ?>
								<div class="invalid-feedback d-block">
									<?= form_error('kondisi[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Problem</label>
								<input type="text" name="problem[]" class="form-control <?= form_error('problem[]') ? 'invalid' : '' ?>" value="<?= set_value('problem[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('problem[]')) ? 'd-block' : '' ?>">
									<?= form_error('problem[]') ?>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="form-label font-weight-bold">Tindakan</label>
								<input type="text" name="tindakan[]" class="form-control <?= form_error('tindakan[]') ? 'invalid' : '' ?>" value="<?= set_value('tindakan[]'); ?>">
								<div class="invalid-feedback <?= !empty(form_error('tindakan[]')) ? 'd-block' : '' ?>">
									<?= form_error('tindakan[]') ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- batas form shortening -->


				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('kebersihanruang') ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<style type="text/css">
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>

<script>
	const lokasiOptions = {
		Cikande: [
			"Area Pengayakan", "Area Premix", "Area Mixing", "Area Proofing",
			"Area Electric Baking", "Area Aging", "Area Grinder",
			"Area Packing", "Area Pencucian"
		],
		Salatiga: [
			"Ruang Buffer Tepung", "Ruang Pengayakan", "Ruang Premix", "Ruang Preparasi",
			"Ruang Mixing Adonan", "Ruang Fermentasi", "Ruang Pemasakan", "Ruang Aging",
			"Ruang Cutting", "Ruang Grinding, Drying dan Packing",
			"Ruang Anteroom Packing", "Ruang Pencucian", "Ruang Koridor", "Ruang Pewarna", "Ruang Shortening"
		]
	};

	const plantHiddenInput = document.getElementById('plantHidden'); // input hidden
	const lokasiDropdown = document.getElementById('lokasiDropdown');

	function updateLokasiOptions() {
		const selectedPlant = plantHiddenInput.value.trim();
		const lokasiList = lokasiOptions[selectedPlant] || [];

		lokasiDropdown.innerHTML = '<option value="">-- Pilih Lokasi --</option>';

		lokasiList.forEach(lokasi => {
			const option = document.createElement('option');
			option.value = lokasi;
			option.textContent = lokasi;
			if ("<?= set_value('lokasi') ?>" === lokasi) {
				option.selected = true;
			}
			lokasiDropdown.appendChild(option);
		});
	}

	window.addEventListener('DOMContentLoaded', updateLokasiOptions);
</script>

<script>
	$(document).ready(function() {
		$('#lokasiDropdown').change(function() {
			$('.form-area').addClass('d-none');
			var selected = $(this).val();

			// Lokasi Cikande
			if (selected === 'Area Pengayakan') {
				$('#form-pengayakan').removeClass('d-none');
			} else if (selected === 'Area Premix') {
				$('#form-premix').removeClass('d-none');
			} else if (selected === 'Area Mixing') {
				$('#form-mixing').removeClass('d-none');
			} else if (selected === 'Area Proofing') {
				$('#form-proofing').removeClass('d-none');
			} else if (selected === 'Area Electric Baking') {
				$('#form-electric').removeClass('d-none');
			} else if (selected === 'Area Aging') {
				$('#form-aging').removeClass('d-none');
			} else if (selected === 'Area Grinder') {
				$('#form-grinder').removeClass('d-none');
			} else if (selected === 'Area Packing') {
				$('#form-packing').removeClass('d-none');
			} else if (selected === 'Area Pencucian') {
				$('#form-pencucian').removeClass('d-none');

				// Lokasi Salatiga (gunakan ID -ruang agar tidak bentrok)
			} else if (selected === 'Ruang Buffer Tepung') {
				$('#form-buffer-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Pengayakan') {
				$('#form-pengayakan-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Premix') {
				$('#form-premix-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Preparasi') {
				$('#form-preparasi-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Mixing Adonan') {
				$('#form-mixing-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Fermentasi') {
				$('#form-fermentasi-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Pemasakan') {
				$('#form-pemasakan-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Aging') {
				$('#form-aging-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Cutting') {
				$('#form-cutting-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Grinding, Drying dan Packing') {
				$('#form-grinding-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Anteroom Packing') {
				$('#form-anteroom-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Pencucian') {
				$('#form-pencucian-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Koridor') {
				$('#form-koridor-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Pewarna') {
				$('#form-pewarna-ruang').removeClass('d-none');
			} else if (selected === 'Ruang Shortening') {
				$('#form-shortening-ruang').removeClass('d-none');
			}
		});
	});
</script>


<script>
	document.addEventListener("DOMContentLoaded", function() {
		const lokasiDropdown = document.getElementById("lokasiDropdown");

		// Daftar semua form Area (Cikande) dan Ruang (Salatiga)
		const forms = {
			// Form Cikande
			"Area Pengayakan": document.getElementById("form-pengayakan"),
			"Area Premix": document.getElementById("form-premix"),
			"Area Mixing": document.getElementById("form-mixing"),
			"Area Proofing": document.getElementById("form-proofing"),
			"Area Electric Baking": document.getElementById("form-electric"),
			"Area Aging": document.getElementById("form-aging"),
			"Area Grinder": document.getElementById("form-grinder"),
			"Area Packing": document.getElementById("form-packing"),
			"Area Pencucian": document.getElementById("form-pencucian"),

			// Form Salatiga
			"Ruang Buffer Tepung": document.getElementById("form-buffer-ruang"),
			"Ruang Pengayakan": document.getElementById("form-pengayakan-ruang"),
			"Ruang Premix": document.getElementById("form-premix-ruang"),
			"Ruang Preparasi": document.getElementById("form-preparasi-ruang"),
			"Ruang Mixing Adonan": document.getElementById("form-mixing-ruang"),
			"Ruang Fermentasi": document.getElementById("form-fermentasi-ruang"),
			"Ruang Pemasakan": document.getElementById("form-pemasakan-ruang"),
			"Ruang Aging": document.getElementById("form-aging-ruang"),
			"Ruang Cutting": document.getElementById("form-cutting-ruang"),
			"Ruang Grinding, Drying dan Packing": document.getElementById("form-grinding-ruang"),
			"Ruang Anteroom Packing": document.getElementById("form-anteroom-ruang"),
			"Ruang Pencucian": document.getElementById("form-pencucian-ruang"),
			"Ruang Koridor": document.getElementById("form-koridor-ruang"),
			"Ruang Pewarna": document.getElementById("form-pewarna-ruang"),
			"Ruang Shortening": document.getElementById("form-shortening-ruang"),
		};

		function toggleForms() {
			const selected = lokasiDropdown.value;

			// Sembunyikan & disable semua form
			Object.values(forms).forEach(form => {
				if (form) {
					form.classList.add("d-none");
					const elements = form.querySelectorAll("input, select, textarea");
					elements.forEach(el => el.disabled = true);
				}
			});

			// Tampilkan & aktifkan form terpilih
			const targetForm = forms[selected];
			if (targetForm) {
				targetForm.classList.remove("d-none");
				const elements = targetForm.querySelectorAll("input, select, textarea");
				elements.forEach(el => el.disabled = false);
			}
		}

		lokasiDropdown.addEventListener("change", toggleForms);
		toggleForms();
	});
</script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Seleksi semua bagian form area
		document.querySelectorAll('.form-group.row').forEach(function(row) {
			const checkboxes = row.querySelectorAll('.kondisi-checkbox');

			// ===============================
			// Langsung centang checkbox "bersih" saat load
			const bersihCheckbox = Array.from(checkboxes).find(cb => cb.value.toLowerCase() === 'bersih');
			if (bersihCheckbox) {
				bersihCheckbox.checked = true;
				// Disable checkbox lain karena "bersih" dicentang
				checkboxes.forEach(function(cb) {
					if (cb.value.toLowerCase() !== 'bersih') {
						cb.checked = false;
						cb.disabled = true;
					}
				});
			}
			// ===============================

			checkboxes.forEach(function(checkbox) {
				checkbox.addEventListener('change', function() {
					const value = checkbox.value.toLowerCase();

					if (value === 'bersih' && checkbox.checked) {
						// Jika "bersih" dicentang, disable yang lain
						checkboxes.forEach(function(cb) {
							if (cb.value !== 'bersih') {
								cb.checked = false;
								cb.disabled = true;
							}
						});
					} else if (value === 'bersih' && !checkbox.checked) {
						// Jika "bersih" tidak dicentang, aktifkan kembali semua
						checkboxes.forEach(function(cb) {
							cb.disabled = false;
						});
					} else if (value !== 'bersih') {
						// Jika checkbox selain "bersih" dicentang, uncheck dan disable "bersih"
						if (checkbox.checked) {
							bersihCheckbox.checked = false;
							bersihCheckbox.disabled = true;
						}

						// Jika semua checkbox selain "bersih" tidak dipilih, aktifkan kembali "bersih"
						const otherChecked = Array.from(checkboxes).some(cb => cb.value !== 'bersih' && cb.checked);
						if (!otherChecked) {
							bersihCheckbox.disabled = false;
						}
					}
				});
			});
		});
	});
</script>
