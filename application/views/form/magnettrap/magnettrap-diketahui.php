<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Magnet Trap</h1>
    </div>

    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
        <br>
    <?php endif ?>

    <?php if($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div>
        <br>
    <?php endif ?> 

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="20px" class="text-center">No</th>
                            <th>Tanggal / Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
                            <th>Tahapan</th>
                            <th>Jenis Kontaminasi</th>
                            <th>Bukti</th>
                            <th>Last Updated</th>
                            <th>Last Verified</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($magnettrap as $val) {
                            $datetime = new datetime($val->date);
                            $datetime = $datetime->format('d-m-Y');
                            ?>
                            <tr>
                                <td class="text-center"><?= $no; ?></td>
                                <td><?= $datetime . " / " . $val->shift; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->kode_produksi; ?></td>
                                <td><?= $val->tahapan; ?></td>
                                <td><?= $val->kontaminasi; ?></td>
                                <td>
                                    <?php if (!empty($val->bukti)): ?>
                                        <img src="<?= base_url('uploads/' . $val->bukti); ?>" alt="Bukti Temuan" style="max-width: 150px; max-height: 100px;">
                                    <?php else: ?>
                                        <p>No image available</p>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('H:i - d m Y', strtotime($val->modified_at)); ?></td>
                                <td><?= date('H:i - d m Y', strtotime($val->tgl_update_enginer)); ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($val->status_enginer == 0) {
                                        echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
                                    } elseif ($val->status_enginer == 1) {
                                        echo '<span style="color: #28b463; font-weight: bold;">Checked</span>';
                                    } elseif ($val->status_enginer == 2) {
                                        echo '<span style="color: red; font-weight: bold;">Re-Check</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('magnettrap/statuseng/'.$val->uuid);?>" class="btn btn-warning btn-icon-split">
                                        <span class="text">Verifikasi</span>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<style> 
    th {
        background-color: #f8f9fc;
    }
</style>
