
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Keluhan Pelanggan</h1>

        <a href="<?= base_url('form/keluhan_pelanggan/tambah'); ?>"
            class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Data Keluhan Pelanggan
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Keluhan</th>
                            <th>Nama Produk</th>
                            <th>Kode Produksi</th>
                            <th>Best Before</th>
                            <th>Pelanggan</th>
                            <th>Jumlah Komplain</th>
                            <th>Satuan</th>
                            <th>Jenis Ketidaksesuaian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($keluhan)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($keluhan as $row) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>

                                    <td class="text-center">
                                        <?= date('d-m-Y', strtotime($row->tanggal_keluhan)); ?>
                                    </td>

                                    <td>
                                        <?= html_escape($row->nama_produk); ?>
                                    </td>

                                    <td class="text-center">
                                        <?= html_escape($row->kode_produksi); ?>
                                    </td>

                                    <td class="text-center">
                                        <?= date('d-m-Y', strtotime($row->best_before)); ?>
                                    </td>

                                    <td>
                                        <?= html_escape($row->pelanggan); ?>
                                    </td>

                                    <td class="text-right">
                                        <?= number_format((float) $row->jumlah_komplain, 2, ',', '.'); ?>
                                    </td>

                                    <td class="text-center">
                                        <?= html_escape($row->satuan); ?>
                                    </td>

                                    <td>
                                        <?= html_escape($row->jenis_ketidaksesuaian); ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= base_url('form/keluhan_pelanggan/detail/' . $row->uuid); ?>"
                                            class="btn btn-info btn-sm"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            order: [],
            pageLength: 10,
            responsive: true,
            language: {
                url: "<?= base_url('assets/datatables/Indonesian.json'); ?>"
            }
        });
    });
</script>
