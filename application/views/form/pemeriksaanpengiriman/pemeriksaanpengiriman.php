
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">
            Pemeriksaan Pengiriman RM, Seasoning, Kemasan dan Chemical
        </h1>
    </div>

    <?php if ($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger text-center">
            <i class="fas fa-times"></i>
            <?= $this->session->flashdata('error_msg'); ?>
        </div>
    <?php endif; ?>

    <?php $tipe_user = (int) $this->session->userdata('tipe_user'); ?>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

                <form method="get"
                    action="<?= base_url('pemeriksaanpengiriman'); ?>"
                    class="form-inline mb-2">

                    <label class="font-weight-bold mr-2">Filter Tanggal:</label>

                    <input type="date"
                        name="tanggal"
                        class="form-control form-control-sm mr-2 mb-2"
                        value="<?= html_escape($filter_tanggal ?? ''); ?>">

                    <button type="submit"
                        class="btn btn-primary btn-sm mr-2 mb-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>

                    <a href="<?= base_url('pemeriksaanpengiriman'); ?>"
                        class="btn btn-secondary btn-sm mr-2 mb-2">
                        <i class="fas fa-sync"></i> Reset
                    </a>

                    <?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
                        <button type="button"
                            class="btn btn-success btn-sm mb-2"
                            id="btnVerifikasiMassal">
                            <i class="fas fa-check-double"></i>
                            Verifikasi Massal
                        </button>
                    <?php endif; ?>

                </form>

                <div class="mb-2">
                    <a href="<?= base_url('pemeriksaanpengiriman/tambah'); ?>"
                        class="btn btn-md btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        Tambah
                    </a>
                </div>

            </div>

            <hr>

            <div class="table-responsive">
                <table class="table table-bordered"
                    id="dataTable"
                    width="100%"
                    cellspacing="0">

                    <thead>
                        <tr>
                            <th width="20px" class="text-center" rowspan="2">No</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Nama Supplier</th>
                            <th rowspan="2">Nama Barang</th>
                            <th colspan="5" class="text-center">Kondisi Mobil</th>
                            <th rowspan="2">Supervisor</th>
                            <th rowspan="2" class="text-center">Action</th>
                        </tr>
                        <tr>
                            <th>Segel</th>
                            <th>Kebersihan</th>
                            <th>Bocor</th>
                            <th>Hama</th>
                            <th>Jam Datang</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = (int) ($start ?? 0) + 1;

                        if (!empty($pemeriksaanpengiriman)):
                            foreach ($pemeriksaanpengiriman as $val):

                                $datetime = new DateTime($val->date);
                                $tanggal_tampil = $datetime->format('d-m-Y');

                                $timing = new DateTime($val->jam_datang);
                                $jam_tampil = $timing->format('H:i');
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>

                                <td><?= $tanggal_tampil; ?></td>

                                <td><?= html_escape($val->nama_supplier); ?></td>

                                <td><?= html_escape($val->nama_barang); ?></td>

                                <td class="text-center">
                                    <?= ($val->segel == 'ok') ? '✔️' : (($val->segel == 'tidak ok') ? '❌' : '−'); ?>
                                </td>

                                <td class="text-center">
                                    <?= ($val->kebersihan == 'ok') ? '✔️' : (($val->kebersihan == 'tidak ok') ? '❌' : '−'); ?>
                                </td>

                                <td class="text-center">
                                    <?= ($val->bocor == 'ok') ? '✔️' : (($val->bocor == 'tidak ok') ? '❌' : '−'); ?>
                                </td>

                                <td class="text-center">
                                    <?= ($val->hama == 'ok') ? '✔️' : (($val->hama == 'tidak ok') ? '❌' : '−'); ?>
                                </td>

                                <td class="text-center"><?= $jam_tampil; ?></td>

                                <td class="text-center">
                                    <?php
                                    if ($val->status_spv == 0) {
                                        echo '<span style="color:#99a3a4;font-weight:bold;">Created</span>';
                                    } elseif ($val->status_spv == 1) {
                                        echo '<span style="color:#28b463;font-weight:bold;">Verified</span>';
                                    } elseif ($val->status_spv == 2) {
                                        echo '<span style="color:red;font-weight:bold;">Revision</span>';
                                    }
                                    ?>
                                </td>

                                <td class="text-center">
                                    <a href="<?= base_url('pemeriksaanpengiriman/edit/' . $val->uuid); ?>"
                                        class="btn btn-warning btn-icon-split mb-1">
                                        <span class="text">Update</span>
                                    </a>

                                    <a href="<?= base_url('pemeriksaanpengiriman/detail/' . $val->uuid); ?>"
                                        class="btn btn-success btn-icon-split mb-1">
                                        <span class="text">Detail</span>
                                    </a>
                                </td>
                            </tr>

                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="11" class="text-center">
                                    Tidak ada data Pemeriksaan Pengiriman.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($pagination)): ?>
                <div class="mt-3">
                    <?= $pagination; ?>
                </div>
            <?php endif; ?>

            <div class="text-center text-muted small mt-2">
                Menampilkan <?= count($pemeriksaanpengiriman); ?> data
                <?php if (!empty($filter_tanggal)): ?>
                    untuk tanggal <?= date('d-m-Y', strtotime($filter_tanggal)); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<style>
    th {
        background-color: #f8f9fc;
    }
</style>

<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
<script>
$(document).ready(function () {

    $('#btnVerifikasiMassal').on('click', function () {

        let tanggal = $('input[name="tanggal"]').val();

        if (!tanggal) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Tanggal',
                text: 'Silakan pilih tanggal pemeriksaan terlebih dahulu.'
            });
            return;
        }

        Swal.fire({
            title: 'Verifikasi Massal?',
            text: 'Seluruh data Pemeriksaan Pengiriman dengan status Created atau Revision pada tanggal ' + tanggal + ' akan diverifikasi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Verifikasi',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: "<?= base_url('pemeriksaanpengiriman/verifikasi_massal'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    tanggal: tanggal
                },

                beforeSend: function () {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang melakukan verifikasi massal.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },

                success: function (response) {

                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            window.location.href =
                                "<?= base_url('pemeriksaanpengiriman'); ?>?tanggal=" +
                                encodeURIComponent(tanggal);
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Informasi',
                            text: response.message
                        });
                    }
                },

                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memproses verifikasi massal.'
                    });
                }
            });
        });
    });
});
</script>
<?php endif; ?>
