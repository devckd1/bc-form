<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo base_url('assets\img\favicon.ico');?>" type="image/x-icon">

    <title><?= $page_title ?> | E-BC</title>
    <!-- Custom fonts for this template -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css')?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css')?>" rel="stylesheet">
    <!-- Custom styles for this page -->
    <!-- Bootstrap 4 Datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Tempus Dominus Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css');?>">
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js')?>"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('home');?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-bread-slice"></i>
                </div>
                <div class="sidebar-brand-text mx-3">BREAD CRUMB</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item <?= $active_nav == 'home' ?'active':'';?>">
                <a class="nav-link" href="<?= base_url('home');?>">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <?php
            $tipe_user = $this->session->userdata('tipe_user');
            $plant_uuid = $this->session->userdata('plant');

            $cikande_uuid = '651ac623-5e48-44cc-b2f6-5d622603f53c';
            $salatiga_uuid = '1eb341e0-1ec4-4484-ba8f-32d23352b84d';
            ?>

            <?php if ($tipe_user == 0): ?>

				<li class="nav-item <?= $active_nav == 'akses_plant' ? 'active' : ''; ?>">
					<a class="nav-link" href="<?= base_url('akses-plant'); ?>">
						<i class="fas fa-user-lock"></i>
						<span>Akses Plant</span>
					</a>
				</li>

			<?php endif; ?>

            <!-- MASTER DATA (hanya tipe_user 0 & 1) -->
            <?php if ($tipe_user == 0): ?>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">MASTER DATA</div>
                <li class="nav-item <?= $active_nav == 'data_master' | $active_nav == 'pegawai' | $active_nav == 'departemen' | $active_nav == 'plant' | $active_nav == 'alatqc' | $active_nav == 'bendapecah' | $active_nav == 'peralatan' | $active_nav == 'produk' | $active_nav == 'material' | $active_nav == 'area_kebersihan' | $active_nav == 'list_thermometer' | $active_nav == 'list_timbangan' | $active_nav == 'list_kebersihan' ?'active':'';?>">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDataMaster"
                    aria-expanded="true" aria-controls="collapseDataMaster">
                    <i class="fas fa-briefcase"></i>
                    <span>Data Master</span>
                </a>

                <div id="collapseDataMaster" class="collapse <?= $active_nav == 'pegawai' | $active_nav == 'departemen' | $active_nav == 'plant' | $active_nav == 'alatqc' | $active_nav == 'bendapecah' | $active_nav == 'peralatan' | $active_nav == 'produk' | $active_nav == 'material' | $active_nav == 'area_kebersihan' | $active_nav == 'list_thermometer' | $active_nav == 'list_timbangan' | $active_nav == 'list_kebersihan' ?'show':'';?>" aria-labelledby="headingDataMaster" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!-- <a class="collapse-item <?= $active_nav == 'pegawai' ?'active':'';?>" href="<?= base_url('pegawai')?>">Pegawai</a>
                        <a class="collapse-item <?= $active_nav == 'departemen' ?'active':'';?>" href="<?= base_url('departemen')?>">Departemen</a>
                        <a class="collapse-item <?= $active_nav == 'plant' ?'active':'';?>" href="<?= base_url('plant')?>">Plant</a> -->
                        <a class="collapse-item <?= $active_nav == 'produk' ?'active':'';?>" href="<?= base_url('produk')?>">List Produk</a>
                        <a class="collapse-item <?= $active_nav == 'material' ?'active':'';?>" href="<?= base_url('material')?>">List Material</a>
                        <a class="collapse-item <?= $active_nav == 'alatqc' ?'active':'';?>" href="<?= base_url('alatqc')?>">Alat QC</a>
                        <a class="collapse-item <?= $active_nav == 'bendapecah' ?'active':'';?>" href="<?= base_url('bendapecah')?>">Benda Pecah Belah</a>
                        <a class="collapse-item <?= $active_nav == 'peralatan' ?'active':'';?>" href="<?= base_url('peralatan')?>">Peralatan Kebersihan</a>
                        <a class="collapse-item <?= $active_nav == 'area_kebersihan' ?'active':'';?>" href="<?= base_url('area_kebersihan')?>">Area Kebersihan</a>
                        <a class="collapse-item <?= $active_nav == 'list_thermometer' ?'active':'';?>" href="<?= base_url('list_thermometer')?>">List Thermometer</a>
                        <a class="collapse-item <?= $active_nav == 'list_timbangan' ?'active':'';?>" href="<?= base_url('list_timbangan')?>">List Timbangan</a>
                        <a class="collapse-item <?= $active_nav == 'list_kebersihan' ?'active':'';?>" href="<?= base_url('list_kebersihan')?>">List Kebersihan Ruang</a>
                    </div>
                </div>
            </li>
        <?php endif; ?>
        <!-- Awal Form QC -->

<!-- FORM QC (hanya user_type 0,1,4) -->
<?php if (in_array($tipe_user, [0, 1, 4, 8])): ?>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">FORM QC</div>
    <li class="nav-item <?= $active_nav == 'form_qc' | $active_nav == 'pengayakan' | $active_nav == 'produksi' | $active_nav == 'metal' |  $active_nav == 'falserejection' |  $active_nav == 'kontaminasi' |  $active_nav == 'kekuatanmagnet' |  $active_nav == 'verifikasimagnet' |  $active_nav == 'thermometer' |  $active_nav == 'timbangan' |  $active_nav == 'releasepacking' |  $active_nav == 'pengemasan' |  $active_nav == 'chiller' |  $active_nav == 'sanitasi' |  $active_nav == 'ketidaksesuaian' |  $active_nav == 'pemusnahan' |  $active_nav == 'kondisikerja' |  $active_nav == 'retain' |  $active_nav == 'kebersihankaryawan' |  $active_nav == 'kebersihanperalatan' |  $active_nav == 'penerimaankemasan' |  $active_nav == 'pemeriksaanpengiriman' |  $active_nav == 'pembuatanlarutan' |  $active_nav == 'pemeriksaanchemical' |  $active_nav == 'seasoning' |  $active_nav == 'kebersihanruang' |  $active_nav == 'sanitasiwarehouse' |  $active_nav == 'loading' |  $active_nav == 'disposisi' |  $active_nav == 'magnettrap' |  $active_nav == 'kebersihanmesin' |  $active_nav == 'sensori' |  $active_nav == 'reagen' |  $active_nav == 'residu' |  $active_nav == 'larutan' |  $active_nav == 'analisis' |  $active_nav == 'inventaris' |  $active_nav == 'pecahbelah' |  $active_nav == 'suhu' | $active_nav == 'proses' ? 'active' : ''; ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC1"
        aria-expanded="true" aria-controls="collapseQC1">
        <i class="fas fa-broom"></i>
        <span>KEBERSIHAN & SUHU</span></a>
        <div id="collapseQC1" class="collapse <?=  $active_nav == 'suhu' |$active_nav == 'chiller' |  $active_nav == 'sanitasi' |  $active_nav == 'kondisikerja' |  $active_nav == 'kebersihankaryawan' |  $active_nav == 'kebersihanperalatan' |  $active_nav == 'pembuatanlarutan' |  $active_nav == 'kebersihanruang' | $active_nav == 'kebersihanmesin' |  $active_nav == 'reagen' |  $active_nav == 'residu' |  $active_nav == 'larutan' ?'show':'';?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?= $active_nav == 'suhu' ?'active':'';?>" href="<?= base_url('suhu')?>">Pemeriksaan Suhu Ruang</a>
                <a class="collapse-item <?= $active_nav == 'chiller' ?'active':'';?>" href="<?= base_url('chiller')?>">Pemeriksaan Suhu Chiller</a>
                <a class="collapse-item <?= $active_nav == 'sanitasi' ?'active':'';?>" href="<?= base_url('sanitasi')?>">Pemeriksaan Sanitasi</a>
                <a class="collapse-item <?= $active_nav == 'kondisikerja' ?'active':'';?>" href="<?= base_url('kondisikerja')?>">Kondisi Kerja Selama Produksi</a>
                <a class="collapse-item <?= $active_nav == 'kebersihankaryawan' ?'active':'';?>" href="<?= base_url('kebersihankaryawan')?>">Kebersihan Karyawan</a>
                <a class="collapse-item <?= $active_nav == 'kebersihanperalatan' ?'active':'';?>" href="<?= base_url('kebersihanperalatan')?>">Kebersihan Peralatan</a>
                <a class="collapse-item <?= $active_nav == 'kebersihanruang' ?'active':'';?>" href="<?= base_url('kebersihanruang')?>">Kebersihan Ruang Produksi</a>
                <?php if ($plant_uuid == $salatiga_uuid): ?>
                    <a class="collapse-item <?= $active_nav == 'kebersihanmesin' ?'active':'';?>" href="<?= base_url('kebersihanmesin')?>">Pemeriksaan Kebersihan Mesin</a> 
                    <a class="collapse-item <?= $active_nav == 'pembuatanlarutan' ?'active':'';?>" href="<?= base_url('pembuatanlarutan')?>">Pembuatan Larutan</a>
                    <a class="collapse-item <?= $active_nav == 'reagen' ?'active':'';?>" href="<?= base_url('reagen')?>">Verifikasi Penggunaan Reagen Klorin</a>
                    <a class="collapse-item <?= $active_nav == 'residu' ?'active':'';?>" href="<?= base_url('residu')?>">Verifikasi Residu Klorin</a>
                    <a class="collapse-item <?= $active_nav == 'larutan' ?'active':'';?>" href="<?= base_url('larutan')?>">Pembuatan Larutan Cleaning & Sanitasi</a>
                <?php endif; ?>
            </div>
        </div>

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC2"
        aria-expanded="true" aria-controls="collapseQC2">
        <i class="fas fa-database"></i>
        <span>PRODUKSI</span></a>

        <div id="collapseQC2" class="collapse <?= in_array($active_nav, [
            'pengayakan', 'kekuatanmagnet', 'verifikasimagnet', 'thermometer', 'timbangan',
            'magnettrap', 'inventaris', 'pecahbelah', 'produksi', 'ketidaksesuaian', 'proses', 'retain', 'disposisi', 'pemusnahan', 'gosong'
            ]) ? 'show' : ''; ?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item <?= $active_nav == 'pengayakan' ? 'active' : ''; ?>" href="<?= base_url('pengayakan') ?>">Pengayakan</a>
                <a class="collapse-item <?= $active_nav == 'kekuatanmagnet' ? 'active' : ''; ?>" href="<?= base_url('kekuatanmagnet') ?>">Pemeriksaan Kekuatan Magnet Trap</a>
                <a class="collapse-item <?= $active_nav == 'verifikasimagnet' ? 'active' : ''; ?>" href="<?= base_url('verifikasimagnet') ?>">Verifikasi Magnet Trap</a>
                <a class="collapse-item <?= $active_nav == 'thermometer' ? 'active' : ''; ?>" href="<?= base_url('thermometer') ?>">Peneraan Thermometer</a>
                <a class="collapse-item <?= $active_nav == 'timbangan' ? 'active' : ''; ?>" href="<?= base_url('timbangan') ?>">Pemeriksaan Timbangan</a>
                <?php if ($plant_uuid == $salatiga_uuid): ?>
                    <a class="collapse-item <?= $active_nav == 'magnettrap' ? 'active' : ''; ?>" href="<?= base_url('magnettrap') ?>">Pemeriksaan Magnet Trap</a>
                <?php endif; ?>

                <?php if ($plant_uuid == $cikande_uuid): ?>
                    <a class="collapse-item <?= $active_nav == 'produksi' ? 'active' : ''; ?>" href="<?= base_url('produksi') ?>">Verifikasi Proses Produksi</a>
                <?php endif; ?>

                <a class="collapse-item <?= $active_nav == 'ketidaksesuaian' ? 'active' : ''; ?>" href="<?= base_url('ketidaksesuaian') ?>">Ketidaksesuaian Produk</a>

                <?php if ($plant_uuid == $salatiga_uuid): ?>
                    <a class="collapse-item <?= $active_nav == 'proses' ? 'active' : ''; ?>" href="<?= base_url('proses') ?>">Verifikasi Proses Produksi</a>
                    <a class="collapse-item <?= $active_nav == 'inventaris' ? 'active' : ''; ?>" href="<?= base_url('inventaris') ?>">Checklist Inventaris Peralatan QC</a>
                    <a class="collapse-item <?= $active_nav == 'pecahbelah' ? 'active' : ''; ?>" href="<?= base_url('pecahbelah') ?>">Pemeriksaan Benda Mudah Pecah</a>
                <?php endif; ?>
                <a class="collapse-item <?= $active_nav == 'disposisi' ?'active':'';?>" href="<?= base_url('disposisi')?>">Disposisi Produk dan Prosedur</a>
                <a class="collapse-item <?= $active_nav == 'pemusnahan' ?'active':'';?>" href="<?= base_url('pemusnahan')?>">Pemusnahan Barang / Produk</a>
                <a class="collapse-item <?= $active_nav == 'retain' ?'active':'';?>" href="<?= base_url('retain')?>">Retain Sample Report</a>
                <?php if ($plant_uuid == $salatiga_uuid): ?>
                    <a class="collapse-item <?= $active_nav == 'gosong' ?'active':'';?>" href="<?= base_url('gosong')?>">Laporan Roti Gosong</a>
                <?php endif; ?>
            </div>
        </div>

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC3"
        aria-expanded="true" aria-controls="collapseQC3">
        <i class="fas fa-cube"></i>
        <span>PACKING</span></a>
        <div id="collapseQC3" class="collapse <?= $active_nav == 'metal' |  $active_nav == 'falserejection' | $active_nav == 'kontaminasi' |  $active_nav == 'releasepacking' |  $active_nav == 'pengemasan' |  $active_nav == 'sensori' ?'show':'';?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?= $active_nav == 'metal' ?'active':'';?>" href="<?= base_url('metal')?>">Pemeriksaan Metal Detector</a>
                <a class="collapse-item <?= $active_nav == 'falserejection' ?'active':'';?>" href="<?= base_url('falserejection')?>">Monitoring False Rejection</a>
                <a class="collapse-item <?= $active_nav == 'kontaminasi' ?'active':'';?>" href="<?= base_url('kontaminasi')?>">Kontaminasi Benda Asing</a>
                <a class="collapse-item <?= $active_nav == 'sensori' ?'active':'';?>" href="<?= base_url('sensori')?>">Sensori Finish Good</a>
                <?php if ($plant_uuid == $salatiga_uuid): ?>
                    <a class="collapse-item <?= $active_nav == 'pengemasan' ?'active':'';?>" href="<?= base_url('pengemasan')?>">Pemeriksaan Proses Pengemasan</a>
                <?php endif; ?>
                <a class="collapse-item <?= $active_nav == 'releasepacking' ?'active':'';?>" href="<?= base_url('releasepacking')?>">Release Packing</a>
            </div>
        </div>

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC4"
        aria-expanded="true" aria-controls="collapseQC4">
        <i class="fas fa-cubes"></i>
        <span>WAREHOUSE</span></a>
        <div id="collapseQC4" class="collapse <?=  $active_nav == 'penerimaankemasan' |  $active_nav == 'pemeriksaanpengiriman' |  $active_nav == 'pemeriksaanchemical' |  $active_nav == 'seasoning' |  $active_nav == 'sanitasiwarehouse' |  $active_nav == 'loading' |  $active_nav == 'analisis' ?'show':'';?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?= $active_nav == 'sanitasiwarehouse' ?'active':'';?>" href="<?= base_url('sanitasiwarehouse')?>">Pemeriksaan Sanitasi Warehouse</a>
                <?php if ($plant_uuid == $salatiga_uuid): ?>
                    <a class="collapse-item <?= $active_nav == 'analisis' ?'active':'';?>" href="<?= base_url('analisis')?>">Permohonan Analisis Sampel Lab</a>
                <?php endif; ?>
                <a class="collapse-item <?= $active_nav == 'penerimaankemasan' ?'active':'';?>" href="<?= base_url('penerimaankemasan')?>">Penerimaan Kemasan</a>
                <!-- <a class="collapse-item <?= $active_nav == 'pemeriksaanpengiriman' ?'active':'';?>" href="<?= base_url('pemeriksaanpengiriman')?>">Pemeriksaan Pengiriman</a> -->
                <a class="collapse-item <?= $active_nav == 'pemeriksaanchemical' ?'active':'';?>" href="<?= base_url('pemeriksaanchemical')?>">Pemeriksaan Chemical</a>
                <a class="collapse-item <?= $active_nav == 'seasoning' ?'active':'';?>" href="<?= base_url('seasoning')?>">Pemeriksaan Seasoning</a>
                <a class="collapse-item <?= $active_nav == 'loading' ?'active':'';?>" href="<?= base_url('loading')?>">Pemeriksaan Loading Produk</a>
            </div>
        </div>
    </li>
<!-- Batas Form QC -->
<?php endif; ?>

<!-- Verifikasi SPV -->
<!-- VERIFIKASI SPV (hanya tipe_user 0,1,2) -->
<?php if (in_array($tipe_user, [0, 1, 2])): ?>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">VERIFIKASI SUPERVISOR</div>
    <li class="nav-item <?= ($active_nav == 'verifikasi' || $active_nav == 'verifikasi-pengayakan' || $active_nav == 'verifikasi-produksi' || $active_nav == 'verifikasi-metal' || $active_nav == 'verifikasi-falserejection' || $active_nav == 'verifikasi-kontaminasi' || $active_nav == 'verifikasi-kekuatanmagnet' || $active_nav == 'verifikasi-verifikasimagnet' || $active_nav == 'verifikasi-thermometer' || $active_nav == 'verifikasi-timbangan' || $active_nav == 'verifikasi-releasepacking' || $active_nav == 'verifikasi-pengemasan' || $active_nav == 'verifikasi-chiller' || $active_nav == 'verifikasi-sanitasi' || $active_nav == 'verifikasi-ketidaksesuaian' || $active_nav == 'verifikasi-pemusnahan' || $active_nav == 'verifikasi-kondisikerja' || $active_nav == 'verifikasi-retain' || $active_nav == 'verifikasi-kebersihankaryawan' || $active_nav == 'verifikasi-kebersihanperalatan' || $active_nav == 'verifikasi-penerimaankemasan' || $active_nav == 'verifikasi-pemeriksaanpengiriman' || $active_nav == 'verifikasi-pembuatanlarutan' || $active_nav == 'verifikasi-pemeriksaanchemical' || $active_nav == 'verifikasi-seasoning' || $active_nav == 'verifikasi-kebersihanruang' || $active_nav == 'verifikasi-sanitasiwarehouse' || $active_nav == 'verifikasi-loading' || $active_nav == 'verifikasi-disposisi' || $active_nav == 'verifikasi-magnettrap' || $active_nav == 'verifikasi-kebersihanmesin' || $active_nav == 'verifikasi-sensori' || $active_nav == 'verifikasi-reagen' || $active_nav == 'verifikasi-residu' || $active_nav == 'verifikasi-larutan' || $active_nav == 'verifikasi-analisis' || $active_nav == 'verifikasi-inventaris' || $active_nav == 'verifikasi-pecahbelah' || $active_nav == 'verifikasi-suhu' || $active_nav == 'verifikasi-proses' ) ? 'active' : ''; ?>">

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC21" aria-expanded="true" aria-controls="collapseQC21">
            <i class="fas fa-broom"></i>
            <span>KEBERSIHAN & SUHU</span></a>
            <div id="collapseQC21" class="collapse <?= ( $active_nav == 'verifikasi-suhu' || $active_nav == 'verifikasi-chiller' || $active_nav == 'verifikasi-sanitasi' || $active_nav == 'verifikasi-kondisikerja' || $active_nav == 'verifikasi-kebersihankaryawan' || $active_nav == 'verifikasi-kebersihanperalatan' || $active_nav == 'verifikasi-pembuatanlarutan' || $active_nav == 'verifikasi-kebersihanruang' || $active_nav == 'verifikasi-kebersihanmesin' || $active_nav == 'verifikasi-reagen' || $active_nav == 'verifikasi-residu' || $active_nav == 'verifikasi-larutan' ) ? 'show' : ''; ?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= $active_nav == 'verifikasi-suhu' ? 'active' : ''; ?>" href="<?= base_url('suhu/verifikasi')?>">Pemeriksaan Suhu Ruang</a>
                    <a class="collapse-item <?= $active_nav == 'verifikasi-chiller' ? 'active' : ''; ?>" href="<?= base_url('chiller/verifikasi')?>">Pemeriksaan Suhu Chiller</a>
                    <a class="collapse-item <?= $active_nav == 'verifikasi-sanitasi' ? 'active' : ''; ?>" href="<?= base_url('sanitasi/verifikasi')?>">Pemeriksaan Sanitasi</a>
                    <a class="collapse-item <?= $active_nav == 'verifikasi-kondisikerja' ? 'active' : ''; ?>" href="<?= base_url('kondisikerja/verifikasi')?>">Kondisi Kerja Selama Produksi</a>
                    <a class="collapse-item <?= $active_nav == 'verifikasi-kebersihankaryawan' ? 'active' : ''; ?>" href="<?= base_url('kebersihankaryawan/verifikasi')?>">Kebersihan Karyawan</a>
                    <a class="collapse-item <?= $active_nav == 'verifikasi-kebersihanperalatan' ? 'active' : ''; ?>" href="<?= base_url('kebersihanperalatan/verifikasi')?>">Kebersihan Peralatan</a>
                    <a class="collapse-item <?= $active_nav == 'verifikasi-kebersihanruang' ? 'active' : ''; ?>" href="<?= base_url('kebersihanruang/verifikasi')?>">Kebersihan Ruang Produksi</a>
                    <?php if ($plant_uuid == $salatiga_uuid): ?>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-kebersihanmesin' ? 'active' : ''; ?>" href="<?= base_url('kebersihanmesin/verifikasi')?>">Pemeriksaan Kebersihan Mesin</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-pembuatanlarutan' ? 'active' : ''; ?>" href="<?= base_url('pembuatanlarutan/verifikasi')?>">Pembuatan Larutan</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-reagen' ? 'active' : ''; ?>" href="<?= base_url('reagen/verifikasi')?>">Verifikasi Penggunaan Reagen Klorin</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-residu' ? 'active' : ''; ?>" href="<?= base_url('residu/verifikasi')?>">Verifikasi Residu Klorin</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-larutan' ? 'active' : ''; ?>" href="<?= base_url('larutan/verifikasi')?>">Pembuatan Larutan Cleaning & Sanitasi</a>
                    <?php endif; ?>
                </div>
            </div>

            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC22" aria-expanded="true" aria-controls="collapseQC22">
                <i class="fas fa-database"></i>
                <span>PRODUKSI</span></a>
                <div id="collapseQC22" class="collapse <?= ($active_nav == 'verifikasi-pengayakan' || $active_nav == 'verifikasi-produksi' || $active_nav == 'verifikasi-kekuatanmagnet' || $active_nav == 'verifikasi-verifikasimagnet' || $active_nav == 'verifikasi-thermometer' || $active_nav == 'verifikasi-timbangan' || $active_nav == 'verifikasi-ketidaksesuaian' ||  $active_nav == 'verifikasi-magnettrap' || $active_nav == 'verifikasi-inventaris' || $active_nav == 'verifikasi-pecahbelah' || $active_nav == 'verifikasi-proses' || $active_nav == 'verifikasi-pemusnahan' || $active_nav == 'verifikasi-retain' || $active_nav == 'verifikasi-disposisi' || $active_nav == 'verifikasi-gosong' ) ? 'show' : ''; ?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'verifikasi-pengayakan' ? 'active' : ''; ?>" href="<?= base_url('pengayakan/verifikasi')?>">Pengayakan</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-kekuatanmagnet' ? 'active' : ''; ?>" href="<?= base_url('kekuatanmagnet/verifikasi')?>">Pemeriksaan Kekuatan Magnet Trap</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-verifikasimagnet' ? 'active' : ''; ?>" href="<?= base_url('verifikasimagnet/verifikasi')?>">Verifikasi Magnet Trap</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-thermometer' ? 'active' : ''; ?>" href="<?= base_url('thermometer/verifikasi')?>">Peneraan Thermometer</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-timbangan' ? 'active' : ''; ?>" href="<?= base_url('timbangan/verifikasi')?>">Pemeriksaan Timbangan</a>
                        <?php if ($plant_uuid == $salatiga_uuid): ?>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-magnettrap' ? 'active' : ''; ?>" href="<?= base_url('magnettrap/verifikasi')?>">Pemeriksaan Magnet Trap</a>
                        <?php endif; ?>

                        <?php if ($plant_uuid == $cikande_uuid): ?>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-produksi' ? 'active' : ''; ?>" href="<?= base_url('produksi/verifikasi')?>">Verifikasi Proses Produksi</a>
                        <?php endif; ?>

                        <a class="collapse-item <?= $active_nav == 'verifikasi-ketidaksesuaian' ? 'active' : ''; ?>" href="<?= base_url('ketidaksesuaian/verifikasi')?>">Ketidaksesuaian Produk</a>

                        <?php if ($plant_uuid == $salatiga_uuid): ?>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-proses' ? 'active' : ''; ?>" href="<?= base_url('proses/verifikasi')?>">Verifikasi Proses Produksi</a>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-inventaris' ? 'active' : ''; ?>" href="<?= base_url('inventaris/verifikasi')?>">Checklist Inventaris Peralatan QC</a>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-pecahbelah' ? 'active' : ''; ?>" href="<?= base_url('pecahbelah/verifikasi')?>">Pemeriksaan Benda Mudah Pecah</a>
                        <?php endif; ?>

                        <a class="collapse-item <?= $active_nav == 'verifikasi-disposisi' ? 'active' : ''; ?>" href="<?= base_url('disposisi/verifikasi')?>">Disposisi Produk dan Prosedur</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-pemusnahan' ? 'active' : ''; ?>" href="<?= base_url('pemusnahan/verifikasi')?>">Pemusnahan Barang / Produk</a>
                        <a class="collapse-item <?= $active_nav == 'verifikasi-retain' ? 'active' : ''; ?>" href="<?= base_url('retain/verifikasi')?>">Retain Sample Report</a>
                        <?php if ($plant_uuid == $salatiga_uuid): ?>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-gosong' ? 'active' : ''; ?>" href="<?= base_url('gosong/verifikasi')?>">Laporan Roti Gosong</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC23" aria-expanded="true" aria-controls="collapseQC23">
                    <i class="fas fa-cube"></i>
                    <span>PACKING</span></a>
                    <div id="collapseQC23" class="collapse <?= ( $active_nav == 'verifikasi-metal' || $active_nav == 'verifikasi-falserejection' || $active_nav == 'verifikasi-kontaminasi' || $active_nav == 'verifikasi-releasepacking' || $active_nav == 'verifikasi-pengemasan' || $active_nav == 'verifikasi-sensori' ) ? 'show' : ''; ?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item <?= $active_nav == 'verifikasi-metal' ? 'active' : ''; ?>" href="<?= base_url('metal/verifikasi')?>">Pemeriksaan Metal Detector</a>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-falserejection' ? 'active' : ''; ?>" href="<?= base_url('falserejection/verifikasi')?>">Monitoring False Rejection</a>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-kontaminasi' ? 'active' : ''; ?>" href="<?= base_url('kontaminasi/verifikasi')?>">Kontaminasi Benda Asing</a>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-sensori' ? 'active' : ''; ?>" href="<?= base_url('sensori/verifikasi')?>">Sensori Finish Good</a>
                            <?php if ($plant_uuid == $salatiga_uuid): ?>
                                <a class="collapse-item <?= $active_nav == 'verifikasi-pengemasan' ? 'active' : ''; ?>" href="<?= base_url('pengemasan/verifikasi')?>">Pemeriksaan Proses Pengemasan</a>
                            <?php endif; ?>
                            <a class="collapse-item <?= $active_nav == 'verifikasi-releasepacking' ? 'active' : ''; ?>" href="<?= base_url('releasepacking/verifikasi')?>">Release Packing</a>
                        </div>
                    </div>

                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseQC24" aria-expanded="true" aria-controls="collapseQC24">
                        <i class="fas fa-cubes"></i>
                        <span>WAREHOUSE</span></a>
                        <div id="collapseQC24" class="collapse <?= ( $active_nav == 'verifikasi-penerimaankemasan' || $active_nav == 'verifikasi-pemeriksaanpengiriman' || $active_nav == 'verifikasi-pemeriksaanchemical' || $active_nav == 'verifikasi-seasoning' || $active_nav == 'verifikasi-sanitasiwarehouse' || $active_nav == 'verifikasi-loading' || $active_nav == 'verifikasi-analisis') ? 'show' : ''; ?>" aria-labelledby="headingQC" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item <?= $active_nav == 'verifikasi-sanitasiwarehouse' ? 'active' : ''; ?>" href="<?= base_url('sanitasiwarehouse/verifikasi')?>">Pemeriksaan Sanitasi Warehouse</a>
                                <?php if ($plant_uuid == $salatiga_uuid): ?>
                                    <a class="collapse-item <?= $active_nav == 'verifikasi-analisis' ? 'active' : ''; ?>" href="<?= base_url('analisis/verifikasi')?>">Permohonan Analisis Sampel Lab</a>
                                <?php endif; ?>
                                <a class="collapse-item <?= $active_nav == 'verifikasi-penerimaankemasan' ? 'active' : ''; ?>" href="<?= base_url('penerimaankemasan/verifikasi')?>">Penerimaan Kemasan</a>
                                <!-- <a class="collapse-item <?= $active_nav == 'verifikasi-pemeriksaanpengiriman' ? 'active' : ''; ?>" href="<?= base_url('pemeriksaanpengiriman/verifikasi')?>">Pemeriksaan Pengiriman</a> -->
                                <a class="collapse-item <?= $active_nav == 'verifikasi-pemeriksaanchemical' ? 'active' : ''; ?>" href="<?= base_url('pemeriksaanchemical/verifikasi')?>">Pemeriksaan Chemical</a>
                                <a class="collapse-item <?= $active_nav == 'verifikasi-seasoning' ? 'active' : ''; ?>" href="<?= base_url('seasoning/verifikasi')?>">Pemeriksaan Seasoning</a>
                                <a class="collapse-item <?= $active_nav == 'verifikasi-loading' ? 'active' : ''; ?>" href="<?= base_url('loading/verifikasi')?>">Pemeriksaan Loading Produk</a>
                            </div>
                        </div>
                    </li>
                    <!-- Batas SPV -->
                <?php endif; ?>

                <?php if (in_array($tipe_user, [0])): ?>
                    <hr class="sidebar-divider">
                    <div class="sidebar-heading">LOGS</div>
                    <li class="nav-item <?= ($active_nav == 'logs_disposisi' | $active_nav == 'logs_analisis' | $active_nav == 'logs_chiller' | $active_nav == 'logs_falserejection' | $active_nav == 'logs_gosong' | $active_nav == 'logs_inventaris' | $active_nav == 'logs_kebersihankaryawan' | $active_nav == 'logs_kebersihanmesin' | $active_nav == 'logs_kebersihanperalatan' | $active_nav == 'logs_kebersihanruang' | $active_nav == 'logs_kebersihanmagnet' | $active_nav == 'logs_ketidaksesuaian' | $active_nav == 'logs_kondisikerja' | $active_nav == 'logs_kontaminasi' | $active_nav == 'logs_larutan' | $active_nav == 'logs_loading' | $active_nav == 'logs_magnettrap' | $active_nav == 'logs_metal' | $active_nav == 'logs_pecahbelah' | $active_nav == 'logs_pembuatanlarutan' | $active_nav == 'logs_pemeriksaanchemical' | $active_nav == 'logs_pemeriksaanpengiriman' | $active_nav == 'logs_pemusnahan' | $active_nav == 'logs_penerimaankemasan' | $active_nav == 'logs_pengayakan' | $active_nav == 'logs_pengemasan' | $active_nav == 'logs_produksi' | $active_nav == 'logs_proses' | $active_nav == 'logs_reagen' | $active_nav == 'logs_releasepacking' | $active_nav == 'logs_residu' | $active_nav == 'logs_retain' | $active_nav == 'logs_sanitasi' | $active_nav == 'logs_sanitasiwarehouse' | $active_nav == 'logs_seasoning' | $active_nav == 'logs_sensori' | $active_nav == 'logs_suhu' | $active_nav == 'logs_thermometer' | $active_nav == 'logs_timbangan' | $active_nav == 'logs_verifikasimagnet') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapselogs"
                        aria-expanded="true" aria-controls="collapselogs">
                        <i class="fas fa-broom"></i>
                        <span>LOGGING</span></a>
                        <div id="collapselogs" class="collapse <?= ($active_nav == 'logs_disposisi' | $active_nav == 'logs_analisis' | $active_nav == 'logs_chiller') ? 'show' : ''; ?>"
                            aria-labelledby="headingQC" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item <?= ($active_nav == 'logs_disposisi') ? 'active' : ''; ?>" href="<?= base_url('disposisi/logs')?>">Disposisi Produk</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_analisis') ? 'active' : ''; ?>" href="<?= base_url('analisis/logs')?>">Analisis Produk</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_chiller') ? 'active' : ''; ?>" href="<?= base_url('chiller/logs')?>">Suhu Chiller</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_falserejection') ? 'active' : ''; ?>" href="<?= base_url('falserejection/logs')?>">False Rejection</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_gosong') ? 'active' : ''; ?>" href="<?= base_url('gosong/logs')?>">Roti Gosong</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_inventaris') ? 'active' : ''; ?>" href="<?= base_url('inventaris/logs')?>">Inventaris</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_kebersihankaryawan') ? 'active' : ''; ?>" href="<?= base_url('kebersihankaryawan/logs')?>">Kebersihan Karyawan</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_kebersihanmesin') ? 'active' : ''; ?>" href="<?= base_url('kebersihanmesin/logs')?>">Kebersihan Mesin</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_kebersihanperalatan') ? 'active' : ''; ?>" href="<?= base_url('kebersihanperalatan/logs')?>">Kebersihan Peralatan</a>
                                <a class="collapse-item <?= ($active_nav == 'logs_kebersihanruang') ? 'active' : ''; ?>" href="<?= base_url('kebersihanruang/logs')?>">Kebersihan Ruang</a>
                                <a class="collapse-item" href="<?= base_url('kekuatanmagnet/logs')?>">Kekuatan Magnet</a>
                                <a class="collapse-item" href="<?= base_url('ketidaksesuaian/logs')?>">Ketidaksesuaian</a>
                                <a class="collapse-item" href="<?= base_url('kondisikerja/logs')?>">Kondisi Kerja</a>
                                <a class="collapse-item" href="<?= base_url('kontaminasi/logs')?>">Kontaminasi</a>
                                <a class="collapse-item" href="<?= base_url('larutan/logs')?>">Larutan</a>
                                <a class="collapse-item" href="<?= base_url('loading/logs')?>">Loading</a>
                                <a class="collapse-item" href="<?= base_url('magnettrap/logs')?>">Magnet Trap</a>
                                <a class="collapse-item" href="<?= base_url('metal/logs')?>">Metal Detector</a>
                                <a class="collapse-item" href="<?= base_url('pecahbelah/logs')?>">Benda Pecah Belah</a>
                                <a class="collapse-item" href="<?= base_url('pembuatanlarutan/logs')?>">Pembuatan Larutan</a>
                                <a class="collapse-item" href="<?= base_url('pemeriksaanchemical/logs')?>">Pemeriksaan Chemical</a>
                                <a class="collapse-item" href="<?= base_url('pemeriksaanpengiriman/logs')?>">Pemeriksaan Pengiriman</a>
                                <a class="collapse-item" href="<?= base_url('pemusnahan/logs')?>">Pemusnahan</a>
                                <a class="collapse-item" href="<?= base_url('penerimaankemasan/logs')?>">Penerimaan Kemasan</a>
                                <a class="collapse-item" href="<?= base_url('pengayakan/logs')?>">Pengayakan</a>
                                <a class="collapse-item" href="<?= base_url('pengemasan/logs')?>">Pengemasan</a>
                                <a class="collapse-item" href="<?= base_url('produksi/logs')?>">Verifikasi Produksi</a>
                                <a class="collapse-item" href="<?= base_url('reagen/logs')?>">Reagen</a>
                                <a class="collapse-item" href="<?= base_url('releasepacking/logs')?>">Release Packing</a>
                                <a class="collapse-item" href="<?= base_url('residu/logs')?>">Residu</a>
                                <a class="collapse-item" href="<?= base_url('retain/logs')?>">Retain</a>
                                <a class="collapse-item" href="<?= base_url('sanitasi/logs')?>">Sanitasi</a>
                                <a class="collapse-item" href="<?= base_url('sanitasiwarehouse/logs')?>">Sanitasi Warehouse</a>
                                <a class="collapse-item" href="<?= base_url('seasoning/logs')?>">Seasoning</a>
                                <a class="collapse-item" href="<?= base_url('sensori/logs')?>">Sensori FG</a>
                                <a class="collapse-item" href="<?= base_url('suhu/logs')?>">Suhu</a>
                                <a class="collapse-item" href="<?= base_url('thermometer/logs')?>">Thermometer</a>
                                <a class="collapse-item" href="<?= base_url('timbangan/logs')?>">Timbangan</a>
                                <a class="collapse-item" href="<?= base_url('verifikasimagnet/logs')?>">Verifikasi Magnet</a>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

                <hr class="sidebar-divider">
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>
            </ul>

            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<!-- Sidebar Toggle (Topbar) -->
					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>

					<!-- Nama Perusahaan -->
					<div class="d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 nama-pt">
						<strong>PT. CHAROEN POKPHAND INDONESIA - FOOD DIVISION</strong>
					</div>

					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">

						<?php
						$foto = $this->session->userdata('foto') ?? 'profil.png';
						$foto_url = base_url('uploads/foto/' . $foto);

						$user_uuid = $this->session->userdata('user_uuid');
						$user_plants = $this->auth_model->get_user_plants($user_uuid);
						?>

						<!-- SWITCH PLANT -->
						<?php if (count($user_plants) > 1): ?>
							<li class="nav-item dropdown no-arrow mr-2">

								<a class="nav-link dropdown-toggle d-flex align-items-center"
									href="#"
									id="plantDropdown"
									role="button"
									data-toggle="dropdown"
									aria-haspopup="true"
									aria-expanded="false">

									<i class="fas fa-building mr-2"></i>

									<span class="text-dark small font-weight-bold">
										<?php
										$active_plant = $this->session->userdata('plant');
										$active_plant_name = '';

										foreach ($user_plants as $plant) {
											if ($plant->uuid == $active_plant) {
												$active_plant_name = $plant->plant;
												break;
											}
										}
										?>

										<?= htmlspecialchars($active_plant_name); ?>
									</span>

								</a>

								<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
									aria-labelledby="plantDropdown">

									<h6 class="dropdown-header">
										<i class="fas fa-industry fa-sm mr-2"></i>
										Pilih Plant
									</h6>

									<?php foreach ($user_plants as $plant): ?>

										<form action="<?= base_url('auth/switch_plant'); ?>" method="POST">

											<input type="hidden"
												name="plant_uuid"
												value="<?= $plant->uuid; ?>">

											<input type="hidden"
												name="redirect_url"
												value="<?= current_url(); ?>">

											<button type="submit"
												class="dropdown-item d-flex align-items-center <?= $plant->uuid == $active_plant ? 'active' : ''; ?>">

												<i class="fas fa-industry fa-sm fa-fw mr-2"></i>

												<span>
													<?= htmlspecialchars($plant->plant); ?>
												</span>

												<?php if ($plant->uuid == $active_plant): ?>
													<i class="fas fa-check ml-auto"></i>
												<?php endif; ?>

											</button>

										</form>

									<?php endforeach; ?>

								</div>
							</li>
						<?php endif; ?>


						<!-- USER -->
						<li class="nav-item dropdown no-arrow">

							<a class="nav-link dropdown-toggle d-flex align-items-center"
								href="#"
								id="userDropdown"
								role="button"
								data-toggle="dropdown"
								aria-haspopup="true"
								aria-expanded="false">

								<span class="mr-2 d-none d-lg-inline text-dark small font-weight-bold">
									Hallo, <?= $this->session->userdata('nama'); ?>
								</span>

								<img class="img-profile rounded-circle"
									src="<?= $foto_url ?>"
									width="40"
									height="40"
									onerror="this.onerror=null;this.src='<?= base_url('uploads/foto/profil.png') ?>';"
									alt="Foto Profil">

							</a>

							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
								aria-labelledby="userDropdown">

								<a class="dropdown-item" href="<?= base_url('logout'); ?>">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
									<span class="text-dark">Logout</span>
								</a>

							</div>

						</li>

					</ul>
				</nav>


            <style type="text/css">
                #wrapper {
                    background-color: #2E86C1;
                }
                .mr-2 {
                    font-size: 18px;
                    font-weight: bold;
                } 
                .navbar .dropdown-menu .dropdown-item:hover {
                    background-color: #f8f9fc;
                    color: #4e73df;
                    font-weight: 500;
                }

                .navbar .fa-user-circle {
                    transition: transform 0.3s ease;
                }

                .navbar .fa-user-circle:hover {
                    transform: scale(1.1);
                    color: #4e73df;
                }

                .dropdown-menu .dropdown-item i {
                    width: 20px;
                    text-align: center;
                }

            </style>
