<?php

/*
|--------------------------------------------------------------------------
| MASTER CHILLER
|--------------------------------------------------------------------------
*/

$master_chiller_1 = null;
$master_chiller_2 = null;
$master_chiller_3 = null;
$master_chiller_4 = null;

if (!empty($master_chiller)) {

    foreach ($master_chiller as $master) {

        switch ($master->nama_chiller) {

            case 'Chiller 1':
                $master_chiller_1 = $master;
                break;

            case 'Chiller 2':
                $master_chiller_2 = $master;
                break;

            case 'Chiller 3':
                $master_chiller_3 = $master;
                break;

            case 'Chiller 4':
                $master_chiller_4 = $master;
                break;
        }
    }
}


/*
|--------------------------------------------------------------------------
| FUNGSI NILAI AUDIT
|--------------------------------------------------------------------------
|
| 1. Data asli ada + Master ada
|    -> nilai tengah Master
|
| 2. Data asli ada + Master tidak ada
|    -> nilai asli
|
| 3. Data asli kosong
|    -> -
|
*/

function nilai_audit_chiller($nilai_asli, $master)
{
    // Kondisi 3:
    // Data asli kosong -> -
    if ($nilai_asli === null || $nilai_asli === '') {
        return '-';
    }

    // Kondisi 2:
    // Data ada + Master tidak ada -> nilai asli
    if (!$master || empty($master->range_standar)) {
        return $nilai_asli;
    }

    /*
     * Ambil angka dari range Master.
     *
     * Contoh:
     * 0 - 4
     * 0-4
     * 0 sampai 4
     * 0.5 - 4.5
     */
    preg_match_all(
        '/-?\d+(?:[.,]\d+)?/',
        $master->range_standar,
        $matches
    );

    // Range Master tidak valid
    if (count($matches[0]) < 2) {
        return $nilai_asli;
    }

    $min = (float) str_replace(',', '.', $matches[0][0]);
    $max = (float) str_replace(',', '.', $matches[0][1]);

    // Kondisi 1:
    // Data ada + Master ada -> nilai tengah Master
    $nilai_tengah = ($min + $max) / 2;

    // 2.0 menjadi 2
    if (floor($nilai_tengah) == $nilai_tengah) {
        return (int) $nilai_tengah;
    }

    return $nilai_tengah;
}

?>
<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Pemeriksaan Chiller

			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('chiller'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Chiller
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan Chiller.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<div class="card shadow mb-4 card-audit">
		<div class="card-body">
			<hr>

			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Waktu</th>
							<th>Chiller 1</th>
							<th>Chiller 2</th>
							<th>Chiller 3</th>
							<th>Chiller 4</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>

						<?php
						$no = 1;

						if (!empty($chiller)):
							foreach ($chiller as $val):

								$formatted_date = '-';

								if (!empty($val->date)) {
									$datetime = new DateTime($val->date);
									$formatted_date = $datetime->format('d-m-Y');
								}
						?>

								<tr>

									<td class="text-center">
										<?= $no; ?>
									</td>

									<td>
										<?= $formatted_date; ?>
									</td>

									<td>
										<?= !empty($val->waktu)
											? date('H:i', strtotime($val->waktu))
											: '-'; ?>
									</td>

									<td class="text-center">
										<?= nilai_audit_chiller(
											$val->chiller_1,
											$master_chiller_1
										); ?>
									</td>

									<td class="text-center">
										<?= nilai_audit_chiller(
											$val->chiller_2,
											$master_chiller_2
										); ?>
									</td>

									<td class="text-center">
										<?= nilai_audit_chiller(
											$val->chiller_3,
											$master_chiller_3
										); ?>
									</td>

									<td class="text-center">
										<?= nilai_audit_chiller(
											$val->chiller_4,
											$master_chiller_4
										); ?>
									</td>

									<td><?= date('H:i - d m Y', strtotime($val->modified_at)) ?></td>
									<td><?= date('H:i - d m Y', strtotime($val->tgl_update_spv)) ?></td>

									<td class="text-center">
										<?php
										if ($val->status_spv == 0) {

											echo '<span style="color:#99a3a4;font-weight:bold;">
                                                    Created
                                                  </span>';
										} elseif ($val->status_spv == 1) {

											echo '<span style="color:#28b463;font-weight:bold;">
                                                    Verified
                                                  </span>';
										} elseif ($val->status_spv == 2) {

											echo '<span style="color:red;font-weight:bold;">
                                                    Revision
                                                  </span>';
										} else {

											echo '<span style="color:#99a3a4;font-weight:bold;">
                                                    -
                                                  </span>';
										}
										?>
									</td>

									<td class="text-center">

										<a href="<?= base_url('chiller/detail-audit/' . $val->uuid); ?>"
											class="btn btn-success btn-icon-split btn-sm">

											<span class="text">
												Detail
											</span>

										</a>

										<a href="<?= base_url('chiller/edit-audit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split btn-sm">

											<span class="text">
												Edit
											</span>

										</a>

									</td>

								</tr>

						<?php
								$no++;
							endforeach;
						endif;
						?>

					</tbody>

				</table>

			</div>

			<br>

			<div class="mb-3">

				<form action="<?= base_url('chiller/cetak-audit'); ?>"
					method="post"
					class="form-inline">

					<label for="tanggal"
						class="mr-2 font-weight-bold">

						Pilih Tanggal:

					</label>

					<input
						type="date"
						name="tanggal"
						id="tanggal"
						class="form-control mr-2"
						required>

					<button type="submit"
						class="btn btn-success">

						<i class="fas fa-print fa-sm text-white-50"></i>
						Cetak PDF

					</button>

				</form>

			</div>

		</div>
	</div>

</div>

<style>
	th {
		background-color: #f8f9fc;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	th {
		background-color: #f8f9fc;
	}

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/* Label Mode Audit */
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}
</style>
