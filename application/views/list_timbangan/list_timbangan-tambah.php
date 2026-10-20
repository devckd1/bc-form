<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah List Timbangan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('list_timbangan')?>">
                    <i class="fas fa-arrow-left">
                    </i> Daftar List Timbangan</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form class="user" method="post" action="<?= base_url('list_timbangan/tambah');?>">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="form-label font-weight-bold">Kode Timbangan</label>
                            <input type="text" name="kode_timbangan" class="form-control <?= form_error('kode_timbangan') ? 'invalid' : '' ?> " value="<?= set_value('kode_timbangan'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('kode_timbangan')) ? 'd-block' : '' ; ?> ">
                                <?= form_error('kode_timbangan') ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label font-weight-bold">Kapasitas (Kg)</label>
                            <input type="number" name="kapasitas" class="form-control <?= form_error('kapasitas') ? 'invalid' : '' ?> " value="<?= set_value('kapasitas'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('kapasitas')) ? 'd-block' : '' ; ?> ">
                                <?= form_error('kapasitas') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="form-label font-weight-bold">Model</label>
                            <input type="text" name="model" class="form-control <?= form_error('model') ? 'invalid' : '' ?> " value="<?= set_value('model'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('model')) ? 'd-block' : '' ; ?> ">
                                <?= form_error('model') ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label font-weight-bold">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control <?= form_error('lokasi') ? 'invalid' : '' ?> " value="<?= set_value('lokasi'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('lokasi')) ? 'd-block' : '' ; ?> ">
                                <?= form_error('lokasi') ?>
                            </div>
                        </div>
                    </div>
                  <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="form-label font-weight-bold">Standar (gr)</label>
                            <input type="number" name="standar" class="form-control <?= form_error('standar') ? 'invalid' : '' ?> " value="<?= set_value('standar'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('standar')) ? 'd-block' : '' ; ?> ">
                                <?= form_error('standar') ?>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('list_timbangan')?>" class="btn btn-md btn-danger">
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
    .breadcrumb{
        background-color: #2E86C1;
    }
</style>
