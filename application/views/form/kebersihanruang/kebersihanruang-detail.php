<div class="container-fluid">
	<!-- Heading -->
	<?php if (empty($is_popup)): ?>
		<h1 class="h3 mb-3 text-gray-800 font-weight-bold">Detail Kebersihan Ruang Produksi</h1>

		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search') ?? '') : base_url('kebersihanruang'); ?>">
						<i class="fas fa-arrow-left"></i> Daftar Kebersihan Ruang Produksi
					</a>
				</li>
			</ol>
		</nav>
	<?php endif; ?>

	<!-- Card -->
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<?php
				$tanggal = (new DateTime($kebersihanruang->date))->format('d-m-Y');
				$details = json_decode($kebersihanruang->detail, true);

				$kondisiMap = [
					'0' => 'Bersih',
					'1' => 'Berdebu',
					'2' => 'Basah',
					'3' => 'Pecah/Retak',
					'4' => 'Sisa produksi (terigu/produk)',
					'5' => 'Noda (tinta, karat)',
					'6' => 'Pertumbuhan mikroorganisme (jamur/bau busuk)',
				];
				?>

				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<th colspan="6">KEBERSIHAN RUANG PRODUKSI</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2"><strong>Tanggal</strong></td>
							<td colspan="4"><?= $tanggal; ?></td>
						</tr>
						<tr>
							<td colspan="2"><strong>Shift</strong></td>
							<td colspan="4"><?= $kebersihanruang->shift; ?></td>
						</tr>
						<tr>
							<td colspan="2"><strong>Lokasi</strong></td>
							<td colspan="4"><?= htmlspecialchars($kebersihanruang->lokasi); ?></td>
						</tr>
						<tr class="bg-light text-center">
							<th colspan="6">DETAIL PEMERIKSAAN</th>
						</tr>
						<tr class="text-center" style="background-color:#2E86C1; color:#fff;">
							<th>No</th>
							<th colspan="2">Bagian</th>
							<th>Kondisi</th>
							<th>Problem</th>
							<th>Tindakan</th>
						</tr>

						<?php if (!empty($details) && is_array($details)) : ?>
							<?php foreach ($details as $i => $row) : ?>
								<tr>
									<td class="text-center"><?= $i + 1; ?></td>
									<td colspan="2"><?= htmlspecialchars($row['bagian']); ?></td>
									<td class="text-center">
										<?= isset($kondisiMap[$row['kondisi']]) ? $kondisiMap[$row['kondisi']] : htmlspecialchars($row['kondisi']); ?>
									</td>
									<td><?= !empty($row['problem']) ? htmlspecialchars($row['problem']) : '-'; ?></td>
									<td><?= !empty($row['tindakan']) ? htmlspecialchars($row['tindakan']) : '-'; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="6" class="text-center">Tidak ada data detail</td>
							</tr>
						<?php endif; ?>

						<tr class="bg-light text-center">
							<th colspan="6">VERIFIKASI</th>
						</tr>
						<tr>
							<td colspan="2"><strong>QC</strong></td>
							<td colspan="4"><?= htmlspecialchars($kebersihanruang->username); ?></td>
						</tr>
						<tr>
							<td colspan="2"><strong>Produksi</strong></td>
							<td colspan="4"><?= htmlspecialchars($kebersihanruang->nama_produksi); ?></td>
						</tr>
						<tr>
							<td colspan="2"><strong>Disetujui Supervisor</strong></td>
							<td colspan="4">
								<?= status_label($kebersihanruang->status_spv, 'spv'); ?>
							</td>
						</tr>
						<tr>
							<td colspan="2"><strong>Catatan Supervisor</strong></td>
							<td colspan="4"><?= !empty($kebersihanruang->catatan_spv) ? htmlspecialchars($kebersihanruang->catatan_spv) : 'Tidak ada'; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<!-- Style -->
<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.table {
		font-size: 16px;
		border-collapse: collapse;
	}

	.table th,
	.table td {
		padding: 6px 8px;
		word-wrap: break-word;
		white-space: normal !important;
	}

	.table th:first-child,
	.table td:first-child {
		width: 50px;
		max-width: 50px;
		text-align: center;
	}
</style>

<!-- Helper -->
<?php
function status_label($status, $type = '')
{
	$labels = [
		0 => ['text' => 'Created', 'color' => '#99a3a4'],
		1 => ['text' => ($type === 'spv' ? 'Verified' : 'Checked'), 'color' => '#28b463'],
		2 => ['text' => 'Revision', 'color' => 'red'],
	];
	$s = $labels[$status] ?? ['text' => 'Unknown', 'color' => 'gray'];
	return '<span style="color: ' . $s['color'] . '; font-weight: bold;">' . $s['text'] . '</span>';
}
?>
