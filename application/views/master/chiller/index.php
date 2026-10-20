<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">
            Master Chiller
        </h1>

        <a href="<?= base_url('master-chiller/tambah'); ?>"
           class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus mr-1"></i>
            Tambah Master
        </a>
    </div>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Data Master Chiller
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover"
                       id="dataTable"
                       width="100%"
                       cellspacing="0">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Chiller</th>
                            <th>Range Standar</th>
                            <th>Keterangan</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $no = 1; ?>

                        <?php foreach ($master_chiller as $item): ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?= html_escape($item->nama_chiller); ?>
                                </td>

                                <td>
                                    <span class="badge badge-info">
                                        <?= html_escape($item->range_standar); ?>
                                    </span>
                                </td>

                                <td>
                                    <?= !empty($item->keterangan)
                                        ? html_escape($item->keterangan)
                                        : '-'; ?>
                                </td>

                                <td>
                                    <a href="<?= base_url('master-chiller/edit/' . $item->uuid); ?>"
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="<?= base_url('master-chiller/delete/' . $item->uuid); ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Hapus Master Chiller ini?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
