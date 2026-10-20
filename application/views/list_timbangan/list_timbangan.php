<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"></h1>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Daftar List Timbangan</h1>
        <a href="<?= base_url('list_timbangan/tambah')?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i>Tambah</a>
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

    <!-- DataTales Example -->
    <div class="card shadow mb-4">

        <div class="form-group" id="cpi">
          <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Timbangan</th>
                            <th>Kapasitas</th>
                            <th>Model</th>
                            <th>Lokasi</th>
                            <th>Standar</th> 
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($list_timbangan as $val) {
                            ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $val->kode_timbangan; ?></td>
                                <td><?= $val->kapasitas; ?></td>
                                <td><?= $val->model; ?></td>
                                <td><?= $val->lokasi; ?></td>
                                <td><?= $val->standar; ?></td>
                                <td><?= $val->created_at; ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('list_timbangan/edit/'.$val->uuid);?>" class="btn btn-primary btn-icon-split">
                                        <span class="text">Edit</span>
                                    </a> 
                                    <a href="<?= base_url('list_timbangan/delete/'.$val->uuid);?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <span class="text">Delete</span>
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
</div>
<style type="text/css">
    .breadcrumb{
        background-color: #2E86C1;
    }
</style>
