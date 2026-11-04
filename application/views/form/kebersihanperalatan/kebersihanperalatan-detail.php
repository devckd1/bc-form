<div class="container-fluid">
	<!-- Page Heading -->
	<?php if (empty($is_popup)): ?>
		<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Kebersihan Peralatan</h1>

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search') ?? '') : base_url('kebersihanperalatan'); ?>">
						<i class="fas fa-arrow-left"></i> Daftar Kebersihan Peralatan
					</a>
				</li>
			</ol>
		</nav>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">
					<?php
					$datetime = new DateTime($kebersihanperalatan->date);
					$tanggal = $datetime->format('d-m-Y');
					?>
					<thead class="text-center">
						<tr>
							<th colspan="7" class="text-center font-weight-bold">PEMERIKSAAN KEBERSIHAN PERALATAN</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2"><b>Tanggal:</b> <?= $tanggal; ?></td>
							<td><b>Shift:</b> <?= $kebersihanperalatan->shift; ?></td>
							<td colspan="4"></td>
						</tr>
						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">Detail Pemeriksaan</td>
						</tr>
						<tr class="table-primary text-center">
							<th>No</th>
							<th>Nama Peralatan</th>
							<th>Kondisi</th>
							<th>Problem</th>
							<th>Tindakan Koreksi</th>
							<th colspan="2">Catatan</th>
						</tr>
						<?php
						$peralatan = json_decode($kebersihanperalatan->peralatan);
						if ($peralatan):
							foreach ($peralatan as $i => $item): ?>
								<tr>
									<td class="text-center"><?= $i + 1 ?></td>
									<td><?= $item->nama ?></td>
									<td><?= $item->kondisi ?></td>
									<td><?= $item->problem ?></td>
									<td><?= $item->tindakan ?></td>
									<td colspan="2"><?= !empty($kebersihanperalatan->catatan) ? $kebersihanperalatan->catatan : 'Tidak ada'; ?></td>
								</tr>
							<?php endforeach;
						else: ?>
							<tr>
								<td colspan="7" class="text-center text-muted">Tidak ada data peralatan</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<hr>
			<h5 class="font-weight-bold mt-4">Verifikasi</h5>
			<div class="table-responsive">
				<table class="table table-borderless">
					<tr>
						<th>QC</th>
						<td><?= $kebersihanperalatan->username; ?></td>
					</tr>
					<tr>
						<th>Produksi</th>
						<td><?= !empty($kebersihanperalatan->nama_produksi) ? $kebersihanperalatan->nama_produksi : 'Belum dikoreksi'; ?></td>
					</tr>
					<tr>
						<th>Status Supervisor</th>
						<td>
							<?php
							switch ($kebersihanperalatan->status_spv) {
								case 1:
									echo '<span class="text-success font-weight-bold">Verified</span>';
									break;
								case 2:
									echo '<span class="text-danger font-weight-bold">Revision</span>';
									break;
								default:
									echo '<span class="text-secondary font-weight-bold">Created</span>';
									break;
							}
							?>
						</td>
					</tr>
					<tr>
						<th>Catatan Supervisor</th>
						<td><?= !empty($kebersihanperalatan->catatan_spv) ? $kebersihanperalatan->catatan_spv : 'Tidak ada'; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<!-- CSS -->
<style>
	.breadcrumb {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
	}

	.table {
		width: 100%;
		font-size: 15px;
	}

	.table td,
	.table th {
		padding: 10px 12px;
		vertical-align: middle;
		word-break: break-word;
	}

	@media (max-width: 768px) {

		.table td,
		.table th {
			font-size: 14px;
			padding: 8px;
		}

		h1.h3 {
			font-size: 20px;
		}
	}
</style>
