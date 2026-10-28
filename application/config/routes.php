<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth/login';
$route['login'] = 'auth/login';
$route['home'] = 'home';
$route['profil'] = 'profil/index';

//sso
$route['sso/login'] = 'sso/login';

// $route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/user-sync']['post']       = 'User/syncApi';
$route['api/user-desync']     		  = 'User/desyncApi'; 
$route['api/activation']['post']      = 'User/activation';
$route['api/password-change']['post'] = 'User/changePassword';

// $route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
// $route['departemen'] = 'departemen';
// $route['departemen/tambah'] = 'departemen/tambah';
// $route['departemen/edit/(:any)'] = 'departemen/edit/$1';
// $route['departemen/delete/(:any)'] = 'departemen/delete/$1';

// $route['plant'] = 'plant';
// $route['plant/tambah'] = 'plant/tambah';
// $route['plant/edit/(:any)'] = 'plant/edit/$1';
// $route['plant/delete/(:any)'] = 'plant/delete/$1';

$route['pegawai'] = 'pegawai';
$route['pegawai/tambah'] = 'pegawai/tambah';
$route['pegawai/edit/(:any)'] = 'pegawai/edit/$1';
$route['pegawai/edituser/(:any)'] = 'pegawai/edituser/$1';
$route['pegawai/editpass/(:any)'] = 'pegawai/editpass/$1';
$route['pegawai/delete/(:any)'] = 'pegawai/delete/$1';

$route['pengayakan'] = 'form/pengayakan';
$route['pengayakan/tambah'] = 'form/pengayakan/tambah';
$route['pengayakan/edit/(:any)'] = 'form/pengayakan/edit/$1';
$route['pengayakan/detail/(:any)'] = 'form/pengayakan/detail/$1';
$route['pengayakan/verifikasi'] = 'form/pengayakan/verifikasi';
$route['pengayakan/status/(:any)'] = 'form/pengayakan/status/$1';
$route['pengayakan/cetak'] = 'form/pengayakan/cetak';
$route['pengayakan/diketahui'] = 'form/pengayakan/diketahui';
$route['pengayakan/statusprod/(:any)'] = 'form/pengayakan/statusprod/$1';
$route['pengayakan/delete/(:any)'] = 'form/pengayakan/delete/$1';
$route['pengayakan/logs'] = 'form/pengayakan_logs/index';
$route['pengayakan/copy-to-audit/(:any)'] = 'form/pengayakan/copy_to_audit/$1';
$route['pengayakan/audit'] = 'form/pengayakan/audit';
$route['pengayakan/cetak-audit'] = 'form/pengayakan/cetak_audit';
$route['pengayakan/edit-audit/(:any)'] = 'form/pengayakan/edit_audit/$1';
$route['pengayakan/detail-audit/(:any)'] = 'form/pengayakan/detail_audit/$1';

$route['produksi'] = 'form/produksi';
$route['produksi/tambah'] = 'form/produksi/tambah';
$route['produksi/detail/(:any)'] = 'form/produksi/detail/$1';
$route['produksi/edit/(:any)'] = 'form/produksi/edit/$1';
$route['produksi/cetak'] = 'form/produksi/cetak';
$route['produksi/bahan/(:any)'] = 'form/produksi/bahan/$1';
$route['produksi/mixing/(:any)'] = 'form/produksi/mixing/$1';
$route['produksi/fermentasi/(:any)'] = 'form/produksi/fermentasi/$1';
$route['produksi/baking/(:any)'] = 'form/produksi/baking/$1';
$route['produksi/stalling/(:any)'] = 'form/produksi/stalling/$1';
$route['produksi/grinding/(:any)'] = 'form/produksi/grinding/$1';
$route['produksi/drying/(:any)'] = 'form/produksi/drying/$1';
$route['produksi/packing/(:any)'] = 'form/produksi/packing/$1';
$route['produksi/verifikasi'] = 'form/produksi/verifikasi';
$route['produksi/status/(:any)'] = 'form/produksi/status/$1';
$route['produksi/diketahui'] = 'form/produksi/diketahui';
$route['produksi/statusprod/(:any)'] = 'form/produksi/statusprod/$1';
$route['produksi/delete/(:any)'] = 'form/produksi/delete/$1';
$route['produksi/get_produk_by_tanggal'] = 'form/produksi/get_produk_by_tanggal';
$route['produksi/export_excel'] = 'form/produksi/export_excel';
$route['produksi/logs'] = 'form/produksi_logs/index';
$route['produksi/copy-to-audit/(:any)'] = 'form/produksi/copy_to_audit/$1';
$route['produksi/audit'] = 'form/produksi/audit';
$route['produksi/cetak-audit'] = 'form/produksi/cetak_audit';
$route['produksi/edit-audit/(:any)'] = 'form/produksi/edit_audit/$1';
$route['produksi/detail-audit/(:any)'] = 'form/produksi/detail_audit/$1';
$route['produksi/bahan-audit/(:any)'] = 'form/produksi/bahan_audit/$1';
$route['produksi/mixing-audit/(:any)'] = 'form/produksi/mixing_audit/$1';
$route['produksi/fermentasi-audit/(:any)'] = 'form/produksi/fermen_audit/$1';
$route['produksi/baking-audit/(:any)']     = 'form/produksi/bake_audit/$1';
$route['produksi/stalling-audit/(:any)'] = 'form/produksi/stalling_audit/$1';
$route['produksi/drying-audit/(:any)'] = 'form/produksi/drying_audit/$1';
$route['produksi/packing-audit/(:any)'] = 'form/produksi/packing_audit/$1';
$route['produksi/halus-audit/(:any)'] = 'form/produksi/halus_audit/$1';

$route['proses'] = 'form/proses';
$route['proses/tambah'] = 'form/proses/tambah';
$route['proses/detail/(:any)'] = 'form/proses/detail/$1';
$route['proses/edit/(:any)'] = 'form/proses/edit/$1';
$route['proses/packing/(:any)'] = 'form/proses/packing/$1';
$route['proses/cetak'] = 'form/proses/cetak';
$route['proses/verifikasi'] = 'form/proses/verifikasi';
$route['proses/status/(:any)'] = 'form/proses/status/$1';
$route['proses/delete/(:any)'] = 'form/proses/delete/$1';
$route['proses/logs'] = 'form/proses_logs/index';
$route['proses/export_excel'] = 'form/proses/export_excel';
$route['proses/copy-to-audit/(:any)'] = 'form/proses/copy_to_audit/$1';
$route['proses/audit'] = 'form/proses/audit';
$route['proses/cetak-audit'] = 'form/proses/cetak_audit';
$route['proses/edit-audit/(:any)'] = 'form/proses/edit_audit/$1';
$route['proses/detail-audit/(:any)'] = 'form/proses/detail_audit/$1';
$route['proses/audit/packing/(:any)'] = 'form/proses/audit_packing/$1';

$route['metal'] = 'form/metal';
$route['metal/tambah'] = 'form/metal/tambah';
$route['metal/detail/(:any)'] = 'form/metal/detail/$1';
$route['metal/edit/(:any)'] = 'form/metal/edit/$1';
$route['metal/edit2/(:any)'] = 'form/metal/edit2/$1';
$route['metal/edit3/(:any)'] = 'form/metal/edit3/$1';
$route['metal/verifikasi'] = 'form/metal/verifikasi';
$route['metal/status/(:any)'] = 'form/metal/status/$1';
$route['metal/cetak'] = 'form/metal/cetak';
$route['metal/diketahui'] = 'form/metal/diketahui';
$route['metal/statusprod/(:any)'] = 'form/metal/statusprod/$1';
$route['metal/delete/(:any)'] = 'form/metal/delete/$1';
$route['metal/logs'] = 'form/metal_logs/index';
$route['metal/copy-to-audit/(:any)'] = 'form/metal/copy_to_audit/$1';
$route['metal/audit'] = 'form/metal/audit';
$route['metal/cetak-audit'] = 'form/metal/cetak_audit';
$route['metal/edit-audit/(:any)'] = 'form/metal/edit_audit/$1';
$route['metal/detail-audit/(:any)'] = 'form/metal/detail_audit/$1';

$route['falserejection'] = 'form/falserejection';
$route['falserejection/tambah'] = 'form/falserejection/tambah';
$route['falserejection/detail/(:any)'] = 'form/falserejection/detail/$1';
$route['falserejection/edit/(:any)'] = 'form/falserejection/edit/$1';
$route['falserejection/verifikasi'] = 'form/falserejection/verifikasi';
$route['falserejection/status/(:any)'] = 'form/falserejection/status/$1';
$route['falserejection/cetak'] = 'form/falserejection/cetak';
$route['falserejection/diketahui'] = 'form/falserejection/diketahui';
$route['falserejection/statusprod/(:any)'] = 'form/falserejection/statusprod/$1';
$route['falserejection/delete/(:any)'] = 'form/falserejection/delete/$1';
$route['falserejection/logs'] = 'form/falserejection_logs/index';
$route['falserejection/audit'] = 'form/falserejection/audit';
$route['falserejection/cetak-audit'] = 'form/falserejection/cetak_audit';
$route['falserejection/detail-audit/(:any)'] = 'form/falserejection/detail_audit/$1';
$route['falserejection/edit-audit/(:any)'] = 'form/falserejection/edit_audit/$1';

$route['kontaminasi'] = 'form/kontaminasi';
$route['kontaminasi/tambah'] = 'form/kontaminasi/tambah';
$route['kontaminasi/detail/(:any)'] = 'form/kontaminasi/detail/$1';
$route['kontaminasi/edit/(:any)'] = 'form/kontaminasi/edit/$1';
$route['kontaminasi/verifikasi'] = 'form/kontaminasi/verifikasi';
$route['kontaminasi/status/(:any)'] = 'form/kontaminasi/status/$1';
$route['kontaminasi/cetak'] = 'form/kontaminasi/cetak';
$route['kontaminasi/diketahui'] = 'form/kontaminasi/diketahui';
$route['kontaminasi/statusprod/(:any)'] = 'form/kontaminasi/statusprod/$1';
$route['kontaminasi/delete/(:any)'] = 'form/kontaminasi/delete/$1';
$route['kontaminasi/logs'] = 'form/kontaminasi_logs/index';
$route['kontaminasi/export_excel'] = 'form/kontaminasi/export_excel';
$route['kontaminasi/copy-to-audit/(:any)'] = 'form/kontaminasi/copy_to_audit/$1';
$route['kontaminasi/audit'] = 'form/kontaminasi/audit';
$route['kontaminasi/cetak-audit'] = 'form/kontaminasi/cetak_audit';
$route['kontaminasi/edit-audit/(:any)'] = 'form/kontaminasi/edit_audit/$1';
$route['kontaminasi/detail-audit/(:any)'] = 'form/kontaminasi/detail_audit/$1';

$route['kekuatanmagnet'] = 'form/kekuatanmagnet';
$route['kekuatanmagnet/tambah'] = 'form/kekuatanmagnet/tambah';
$route['kekuatanmagnet/detail/(:any)'] = 'form/kekuatanmagnet/detail/$1';
$route['kekuatanmagnet/edit/(:any)'] = 'form/kekuatanmagnet/edit/$1';
$route['kekuatanmagnet/verifikasi'] = 'form/kekuatanmagnet/verifikasi';
$route['kekuatanmagnet/status/(:any)'] = 'form/kekuatanmagnet/status/$1';
$route['kekuatanmagnet/cetak'] = 'form/kekuatanmagnet/cetak';
$route['kekuatanmagnet/diketahui'] = 'form/kekuatanmagnet/diketahui';
$route['kekuatanmagnet/statusprod/(:any)'] = 'form/kekuatanmagnet/statusprod/$1';
$route['kekuatanmagnet/delete/(:any)'] = 'form/kekuatanmagnet/delete/$1';
$route['kekuatanmagnet/logs'] = 'form/kekuatanmagnet_logs/index';
$route['kekuatanmagnet/audit'] = 'form/kekuatanmagnet/audit';
$route['kekuatanmagnet/cetak-audit'] = 'form/kekuatanmagnet/cetak_audit';
$route['kekuatanmagnet/detail-audit/(:any)'] = 'form/kekuatanmagnet/detail_audit/$1';
$route['kekuatanmagnet/edit-audit/(:any)'] = 'form/kekuatanmagnet/edit_audit/$1';

$route['verifikasimagnet'] = 'form/verifikasimagnet';
$route['verifikasimagnet/tambah'] = 'form/verifikasimagnet/tambah';
$route['verifikasimagnet/detail/(:any)'] = 'form/verifikasimagnet/detail/$1';
$route['verifikasimagnet/edit/(:any)'] = 'form/verifikasimagnet/edit/$1';
$route['verifikasimagnet/verifikasi'] = 'form/verifikasimagnet/verifikasi';
$route['verifikasimagnet/status/(:any)'] = 'form/verifikasimagnet/status/$1';
$route['verifikasimagnet/cetak'] = 'form/verifikasimagnet/cetak';
$route['verifikasimagnet/diketahui'] = 'form/verifikasimagnet/diketahui';
$route['verifikasimagnet/statusprod/(:any)'] = 'form/verifikasimagnet/statusprod/$1';
$route['verifikasimagnet/delete/(:any)'] = 'form/verifikasimagnet/delete/$1';
$route['verifikasimagnet/logs'] = 'form/verifikasimagnet_logs/index';
$route['verifikasimagnet/audit'] = 'form/verifikasimagnet/audit';
$route['verifikasimagnet/cetak-audit'] = 'form/verifikasimagnet/cetak_audit';
$route['verifikasimagnet/detail-audit/(:any)'] = 'form/verifikasimagnet/detail_audit/$1';
$route['verifikasimagnet/edit-audit/(:any)'] = 'form/verifikasimagnet/edit_audit/$1';

$route['thermometer'] = 'form/thermometer';
$route['thermometer/tambah'] = 'form/thermometer/tambah';
$route['thermometer/detail/(:any)'] = 'form/thermometer/detail/$1';
$route['thermometer/edit/(:any)'] = 'form/thermometer/edit/$1';
$route['thermometer/verifikasi'] = 'form/thermometer/verifikasi';
$route['thermometer/status/(:any)'] = 'form/thermometer/status/$1';
$route['thermometer/cetak'] = 'form/thermometer/cetak';
$route['thermometer/diketahui'] = 'form/thermometer/diketahui';
$route['thermometer/statusprod/(:any)'] = 'form/thermometer/statusprod/$1';
$route['thermometer/delete/(:any)'] = 'form/thermometer/delete/$1';
$route['thermometer/logs'] = 'form/thermometer_logs/index';
$route['thermometer/copy-to-audit/(:any)'] = 'form/thermometer/copy_to_audit/$1';
$route['thermometer/audit'] = 'form/thermometer/audit';
$route['thermometer/cetak-audit'] = 'form/thermometer/cetak_audit';
$route['thermometer/edit-audit/(:any)'] = 'form/thermometer/edit_audit/$1';
$route['thermometer/detail-audit/(:any)'] = 'form/thermometer/detail_audit/$1';

$route['timbangan'] = 'form/timbangan';
$route['timbangan/tambah'] = 'form/timbangan/tambah';
$route['timbangan/detail/(:any)'] = 'form/timbangan/detail/$1';
$route['timbangan/edit/(:any)'] = 'form/timbangan/edit/$1';
$route['timbangan/verifikasi'] = 'form/timbangan/verifikasi';
$route['timbangan/status/(:any)'] = 'form/timbangan/status/$1';
$route['timbangan/cetak'] = 'form/timbangan/cetak';
$route['timbangan/diketahui'] = 'form/timbangan/diketahui';
$route['timbangan/statusprod/(:any)'] = 'form/timbangan/statusprod/$1';
$route['timbangan/delete/(:any)'] = 'form/timbangan/delete/$1';
$route['timbangan/logs'] = 'form/timbangan_logs/index';
$route['timbangan/copy-to-audit/(:any)'] = 'form/timbangan/copy_to_audit/$1';
$route['timbangan/audit'] = 'form/timbangan/audit';
$route['timbangan/cetak-audit'] = 'form/timbangan/cetak_audit';
$route['timbangan/edit-audit/(:any)'] = 'form/timbangan/edit_audit/$1';
$route['timbangan/detail-audit/(:any)'] = 'form/timbangan/detail_audit/$1';

$route['releasepacking'] = 'form/releasepacking';
$route['releasepacking/tambah'] = 'form/releasepacking/tambah';
$route['releasepacking/detail/(:any)'] = 'form/releasepacking/detail/$1';
$route['releasepacking/edit/(:any)'] = 'form/releasepacking/edit/$1';
$route['releasepacking/verifikasi'] = 'form/releasepacking/verifikasi';
$route['releasepacking/status/(:any)'] = 'form/releasepacking/status/$1';
$route['releasepacking/cetak'] = 'form/releasepacking/cetak';
$route['releasepacking/diketahui'] = 'form/releasepacking/diketahui';
$route['releasepacking/statusprod/(:any)'] = 'form/releasepacking/statusprod/$1';
$route['releasepacking/delete/(:any)'] = 'form/releasepacking/delete/$1';
$route['releasepacking/logs'] = 'form/releasepacking_logs/index';
$route['releasepacking/copy-to-audit/(:any)'] = 'form/releasepacking/copy_to_audit/$1';
$route['releasepacking/audit'] = 'form/releasepacking/audit';
$route['releasepacking/cetak-audit'] = 'form/releasepacking/cetak_audit';
$route['releasepacking/detail-audit/(:any)'] = 'form/releasepacking/detail_audit/$1';
$route['releasepacking/edit-audit/(:any)'] = 'form/releasepacking/edit_audit/$1';

$route['pengemasan'] = 'form/pengemasan';
$route['pengemasan/tambah'] = 'form/pengemasan/tambah';
$route['pengemasan/detail/(:any)'] = 'form/pengemasan/detail/$1';
$route['pengemasan/edit/(:any)'] = 'form/pengemasan/edit/$1';
$route['pengemasan/verifikasi'] = 'form/pengemasan/verifikasi';
$route['pengemasan/status/(:any)'] = 'form/pengemasan/status/$1';
$route['pengemasan/cetak'] = 'form/pengemasan/cetak';
$route['pengemasan/diketahui'] = 'form/pengemasan/diketahui';
$route['pengemasan/statusprod/(:any)'] = 'form/pengemasan/statusprod/$1';
$route['pengemasan/delete/(:any)'] = 'form/pengemasan/delete/$1';
$route['pengemasan/logs'] = 'form/pengemasan_logs/index';

$route['master-chiller']              = 'master_chiller/index';
$route['master-chiller/tambah']      = 'master_chiller/tambah';
$route['master-chiller/edit/(:any)'] = 'master_chiller/edit/$1';
$route['master-chiller/delete/(:any)'] = 'master_chiller/delete/$1';
$route['chiller/edit-audit/(:any)'] = 'form/chiller/edit_audit/$1';
$route['chiller/detail-audit/(:any)'] = 'form/chiller/detail_audit/$1';

$route['chiller'] = 'form/chiller';
$route['chiller/tambah'] = 'form/chiller/tambah';
$route['chiller/detail/(:any)'] = 'form/chiller/detail/$1';
$route['chiller/delete/(:any)'] = 'form/chiller/delete/$1';
$route['chiller/edit/(:any)'] = 'form/chiller/edit/$1';
$route['chiller/verifikasi'] = 'form/chiller/verifikasi';
$route['chiller/status/(:any)'] = 'form/chiller/status/$1';
$route['chiller/cetak'] = 'form/chiller/cetak';
$route['chiller/diketahui'] = 'form/chiller/diketahui';
$route['chiller/statusprod/(:any)'] = 'form/chiller/statusprod/$1';
$route['chiller/export-excel'] = 'form/chiller/export_excel';
$route['chiller/logs'] = 'form/chiller_logs/index';
$route['chiller/audit'] = 'form/chiller/audit';
$route['chiller/copy-to-audit/(:any)'] = 'form/chiller/copy_to_audit/$1';
$route['chiller/cetak-audit'] = 'form/chiller/cetak_audit';

$route['master-sanitasi'] = 'master_sanitasi/index';
$route['master-sanitasi/tambah'] = 'master_sanitasi/tambah';
$route['master-sanitasi/edit/(:any)'] = 'master_sanitasi/edit/$1';
$route['master-sanitasi/delete/(:any)'] = 'master_sanitasi/delete/$1';
$route['sanitasi/edit-audit/(:any)'] = 'form/sanitasi/edit_audit/$1';
$route['sanitasi/detail-audit/(:any)'] = 'form/sanitasi/detail_audit/$1';

$route['sanitasi'] = 'form/sanitasi';
$route['sanitasi/tambah'] = 'form/sanitasi/tambah';
$route['sanitasi/detail/(:any)'] = 'form/sanitasi/detail/$1';
$route['sanitasi/edit/(:any)'] = 'form/sanitasi/edit/$1';
$route['sanitasi/verifikasi'] = 'form/sanitasi/verifikasi';
$route['sanitasi/status/(:any)'] = 'form/sanitasi/status/$1';
$route['sanitasi/cetak'] = 'form/sanitasi/cetak';
$route['sanitasi/diketahui'] = 'form/sanitasi/diketahui';
$route['sanitasi/statusprod/(:any)'] = 'form/sanitasi/statusprod/$1';
$route['sanitasi/delete/(:any)'] = 'form/sanitasi/delete/$1';
$route['sanitasi/logs'] = 'form/sanitasi_logs/index';
$route['sanitasi/audit'] = 'form/sanitasi/audit';
$route['sanitasi/cetak-audit'] = 'form/sanitasi/cetak_audit';
$route['sanitasi/copy-to-audit/(:any)'] = 'form/sanitasi/copy_to_audit/$1';

$route['ketidaksesuaian'] = 'form/ketidaksesuaian';
$route['ketidaksesuaian/tambah'] = 'form/ketidaksesuaian/tambah';
$route['ketidaksesuaian/detail/(:any)'] = 'form/ketidaksesuaian/detail/$1';
$route['ketidaksesuaian/edit/(:any)'] = 'form/ketidaksesuaian/edit/$1';
$route['ketidaksesuaian/verifikasi'] = 'form/ketidaksesuaian/verifikasi';
$route['ketidaksesuaian/status/(:any)'] = 'form/ketidaksesuaian/status/$1';
$route['ketidaksesuaian/cetak'] = 'form/ketidaksesuaian/cetak';
$route['ketidaksesuaian/diketahui'] = 'form/ketidaksesuaian/diketahui';
$route['ketidaksesuaian/statusprod/(:any)'] = 'form/ketidaksesuaian/statusprod/$1';
$route['ketidaksesuaian/delete/(:any)'] = 'form/ketidaksesuaian/delete/$1';
$route['ketidaksesuaian/logs'] = 'form/ketidaksesuaian_logs/index';

$route['pemusnahan'] = 'form/pemusnahan';
$route['pemusnahan/tambah'] = 'form/pemusnahan/tambah';
$route['pemusnahan/detail/(:any)'] = 'form/pemusnahan/detail/$1';
$route['pemusnahan/edit/(:any)'] = 'form/pemusnahan/edit/$1';
$route['pemusnahan/verifikasi'] = 'form/pemusnahan/verifikasi';
$route['pemusnahan/status/(:any)'] = 'form/pemusnahan/status/$1';
$route['pemusnahan/cetak'] = 'form/pemusnahan/cetak';
$route['pemusnahan/diketahui'] = 'form/pemusnahan/diketahui';
$route['pemusnahan/statusprod/(:any)'] = 'form/pemusnahan/statusprod/$1';
$route['pemusnahan/delete/(:any)'] = 'form/pemusnahan/delete/$1';
$route['pemusnahan/logs'] = 'form/pemusnahan_logs/index';
$route['pemusnahan/copy-to-audit/(:any)'] = 'form/pemusnahan/copy_to_audit/$1';
$route['pemusnahan/audit'] = 'form/pemusnahan/audit';
$route['pemusnahan/cetak-audit'] = 'form/pemusnahan/cetak_audit';
$route['pemusnahan/edit-audit/(:any)'] = 'form/pemusnahan/edit_audit/$1';
$route['pemusnahan/detail-audit/(:any)'] = 'form/pemusnahan/detail_audit/$1';

$route['kondisikerja'] = 'form/kondisikerja';
$route['kondisikerja/tambah'] = 'form/kondisikerja/tambah';
$route['kondisikerja/detail/(:any)'] = 'form/kondisikerja/detail/$1';
$route['kondisikerja/edit/(:any)'] = 'form/kondisikerja/edit/$1';
$route['kondisikerja/verifikasi'] = 'form/kondisikerja/verifikasi';
$route['kondisikerja/status/(:any)'] = 'form/kondisikerja/status/$1';
$route['kondisikerja/cetak'] = 'form/kondisikerja/cetak';
$route['kondisikerja/diketahui'] = 'form/kondisikerja/diketahui';
$route['kondisikerja/statusprod/(:any)'] = 'form/kondisikerja/statusprod/$1';
$route['kondisikerja/delete/(:any)'] = 'form/kondisikerja/delete/$1';
$route['kondisikerja/logs'] = 'form/kondisikerja_logs/index';
$route['kondisikerja/copy-to-audit/(:any)'] = 'form/kondisikerja/copy_to_audit/$1';
$route['kondisikerja/audit'] = 'form/kondisikerja/audit';
$route['kondisikerja/cetak-audit'] = 'form/kondisikerja/cetak_audit';
$route['kondisikerja/detail-audit/(:any)'] = 'form/kondisikerja/detail_audit/$1';
$route['kondisikerja/edit-audit/(:any)'] = 'form/kondisikerja/edit_audit/$1';

$route['retain'] = 'form/retain';
$route['retain/tambah'] = 'form/retain/tambah';
$route['retain/detail/(:any)'] = 'form/retain/detail/$1';
$route['retain/edit/(:any)'] = 'form/retain/edit/$1';
$route['retain/verifikasi'] = 'form/retain/verifikasi';
$route['retain/status/(:any)'] = 'form/retain/status/$1';
$route['retain/cetak'] = 'form/retain/cetak';
$route['retain/diketahui'] = 'form/retain/diketahui';
$route['retain/statusprod/(:any)'] = 'form/retain/statusprod/$1';
$route['retain/delete/(:any)'] = 'form/retain/delete/$1';
$route['retain/logs'] = 'form/retain_logs/index';
$route['retain/copy-to-audit/(:any)'] = 'form/retain/copy_to_audit/$1';
$route['retain/audit'] = 'form/retain/audit';
$route['retain/edit-audit/(:any)'] = 'form/retain/edit_audit/$1';
$route['retain/detail-audit/(:any)'] = 'form/retain/detail_audit/$1';

$route['kebersihankaryawan'] = 'form/kebersihankaryawan';
$route['kebersihankaryawan/tambah'] = 'form/kebersihankaryawan/tambah';
$route['kebersihankaryawan/detail/(:any)'] = 'form/kebersihankaryawan/detail/$1';
$route['kebersihankaryawan/edit/(:any)'] = 'form/kebersihankaryawan/edit/$1';
$route['kebersihankaryawan/verifikasi'] = 'form/kebersihankaryawan/verifikasi';
$route['kebersihankaryawan/status/(:any)'] = 'form/kebersihankaryawan/status/$1';
$route['kebersihankaryawan/cetak'] = 'form/kebersihankaryawan/cetak';
$route['kebersihankaryawan/diketahui'] = 'form/kebersihankaryawan/diketahui';
$route['kebersihankaryawan/statusprod/(:any)'] = 'form/kebersihankaryawan/statusprod/$1';
$route['kebersihankaryawan/delete/(:any)'] = 'form/kebersihankaryawan/delete/$1';
$route['kebersihankaryawan/logs'] = 'form/kebersihankaryawan_logs/index';
$route['kebersihankaryawan/copy-to-audit/(:any)'] = 'form/kebersihankaryawan/copy_to_audit/$1';
$route['kebersihankaryawan/audit'] = 'form/kebersihankaryawan/audit';
$route['kebersihankaryawan/cetak-audit'] = 'form/kebersihankaryawan/cetak_audit';
$route['kebersihankaryawan/detail-audit/(:any)'] = 'form/kebersihankaryawan/detail_audit/$1';
$route['kebersihankaryawan/edit-audit/(:any)'] = 'form/kebersihankaryawan/edit_audit/$1';

$route['kebersihanperalatan'] = 'form/kebersihanperalatan';
$route['kebersihanperalatan/tambah'] = 'form/kebersihanperalatan/tambah';
$route['kebersihanperalatan/detail/(:any)'] = 'form/kebersihanperalatan/detail/$1';
$route['kebersihanperalatan/edit/(:any)'] = 'form/kebersihanperalatan/edit/$1';
$route['kebersihanperalatan/verifikasi'] = 'form/kebersihanperalatan/verifikasi';
$route['kebersihanperalatan/status/(:any)'] = 'form/kebersihanperalatan/status/$1';
$route['kebersihanperalatan/cetak'] = 'form/kebersihanperalatan/cetak';
$route['kebersihanperalatan/diketahui'] = 'form/kebersihanperalatan/diketahui';
$route['kebersihanperalatan/statusprod/(:any)'] = 'form/kebersihanperalatan/statusprod/$1';
$route['kebersihanperalatan/delete/(:any)'] = 'form/kebersihanperalatan/delete/$1';
$route['kebersihanperalatan/logs'] = 'form/kebersihanperalatan_logs/index';
$route['kebersihanperalatan/copy-to-audit/(:any)'] = 'form/kebersihanperalatan/copy_to_audit/$1';
$route['kebersihanperalatan/audit'] = 'form/kebersihanperalatan/audit';
$route['kebersihanperalatan/cetak-audit'] = 'form/kebersihanperalatan/cetak_audit';
$route['kebersihanperalatan/edit-audit/(:any)'] = 'form/kebersihanperalatan/edit_audit/$1';
$route['kebersihanperalatan/detail-audit/(:any)'] = 'form/kebersihanperalatan/detail_audit/$1';

$route['penerimaankemasan'] = 'form/penerimaankemasan';
$route['penerimaankemasan/tambah'] = 'form/penerimaankemasan/tambah';
$route['penerimaankemasan/detail/(:any)'] = 'form/penerimaankemasan/detail/$1';
$route['penerimaankemasan/edit/(:any)'] = 'form/penerimaankemasan/edit/$1';
$route['penerimaankemasan/verifikasi'] = 'form/penerimaankemasan/verifikasi';
$route['penerimaankemasan/status/(:any)'] = 'form/penerimaankemasan/status/$1';
$route['penerimaankemasan/cetak'] = 'form/penerimaankemasan/cetak';
$route['penerimaankemasan/delete/(:any)'] = 'form/penerimaankemasan/delete/$1';
$route['penerimaankemasan/logs'] = 'form/penerimaankemasan_logs/index';
$route['penerimaankemasan/copy-to-audit/(:any)'] = 'form/penerimaankemasan/copy_to_audit/$1';
$route['penerimaankemasan/audit'] = 'form/penerimaankemasan/audit';
$route['penerimaankemasan/cetak-audit'] = 'form/penerimaankemasan/cetak_audit';
$route['penerimaankemasan/detail-audit/(:any)'] = 'form/penerimaankemasan/detail_audit/$1';
$route['penerimaankemasan/edit-audit/(:any)'] = 'form/penerimaankemasan/edit_audit/$1';

$route['pemeriksaanpengiriman'] = 'form/pemeriksaanpengiriman';
$route['pemeriksaanpengiriman/tambah'] = 'form/pemeriksaanpengiriman/tambah';
$route['pemeriksaanpengiriman/detail/(:any)'] = 'form/pemeriksaanpengiriman/detail/$1';
$route['pemeriksaanpengiriman/edit/(:any)'] = 'form/pemeriksaanpengiriman/edit/$1';
$route['pemeriksaanpengiriman/verifikasi'] = 'form/pemeriksaanpengiriman/verifikasi';
$route['pemeriksaanpengiriman/status/(:any)'] = 'form/pemeriksaanpengiriman/status/$1';
$route['pemeriksaanpengiriman/cetak'] = 'form/pemeriksaanpengiriman/cetak';
$route['pemeriksaanpengiriman/delete/(:any)'] = 'form/pemeriksaanpengiriman/delete/$1';
$route['pemeriksaanpengiriman/logs'] = 'form/pemeriksaanpengiriman_logs/index';

$route['pembuatanlarutan'] = 'form/pembuatanlarutan';
$route['pembuatanlarutan/tambah'] = 'form/pembuatanlarutan/tambah';
$route['pembuatanlarutan/detail/(:any)'] = 'form/pembuatanlarutan/detail/$1';
$route['pembuatanlarutan/edit/(:any)'] = 'form/pembuatanlarutan/edit/$1';
$route['pembuatanlarutan/verifikasi'] = 'form/pembuatanlarutan/verifikasi';
$route['pembuatanlarutan/status/(:any)'] = 'form/pembuatanlarutan/status/$1';
$route['pembuatanlarutan/cetak'] = 'form/pembuatanlarutan/cetak';
$route['pembuatanlarutan/delete/(:any)'] = 'form/pembuatanlarutan/delete/$1';
$route['pembuatanlarutan/logs'] = 'form/pembuatanlarutan_logs/index';

$route['pemeriksaanchemical'] = 'form/pemeriksaanchemical';
$route['pemeriksaanchemical/tambah'] = 'form/pemeriksaanchemical/tambah';
$route['pemeriksaanchemical/detail/(:any)'] = 'form/pemeriksaanchemical/detail/$1';
$route['pemeriksaanchemical/edit/(:any)'] = 'form/pemeriksaanchemical/edit/$1';
$route['pemeriksaanchemical/verifikasi'] = 'form/pemeriksaanchemical/verifikasi';
$route['pemeriksaanchemical/status/(:any)'] = 'form/pemeriksaanchemical/status/$1';
$route['pemeriksaanchemical/cetak'] = 'form/pemeriksaanchemical/cetak';
$route['pemeriksaanchemical/delete/(:any)'] = 'form/pemeriksaanchemical/delete/$1';
$route['pemeriksaanchemical/logs'] = 'form/pemeriksaanchemical_logs/index';
$route['pemeriksaanchemical/copy-to-audit/(:any)'] = 'form/pemeriksaanchemical/copy_to_audit/$1';
$route['pemeriksaanchemical/audit'] = 'form/pemeriksaanchemical/audit';
$route['pemeriksaanchemical/cetak-audit'] = 'form/pemeriksaanchemical/cetak_audit';
$route['pemeriksaanchemical/detail-audit/(:any)'] = 'form/pemeriksaanchemical/detail_audit/$1';
$route['pemeriksaanchemical/edit-audit/(:any)'] = 'form/pemeriksaanchemical/edit_audit/$1';

$route['seasoning'] = 'form/seasoning';
$route['seasoning/tambah'] = 'form/seasoning/tambah';
$route['seasoning/detail/(:any)'] = 'form/seasoning/detail/$1';
$route['seasoning/edit/(:any)'] = 'form/seasoning/edit/$1';
$route['seasoning/verifikasi'] = 'form/seasoning/verifikasi';
$route['seasoning/status/(:any)'] = 'form/seasoning/status/$1';
$route['seasoning/cetak'] = 'form/seasoning/cetak';
$route['seasoning/delete/(:any)'] = 'form/seasoning/delete/$1';
$route['seasoning/logs'] = 'form/seasoning_logs/index';
$route['seasoning/audit'] = 'form/seasoning/audit';
$route['seasoning/copy-to-audit/(:any)'] = 'form/seasoning/copy_to_audit/$1';
$route['seasoning/audit'] = 'form/seasoning/audit';
$route['seasoning/edit-audit/(:any)'] = 'form/seasoning/edit_audit/$1';
$route['seasoning/cetak'] = 'form/seasoning/cetak';
$route['seasoning/cetak-audit'] = 'form/seasoning/cetak_audit';
$route['seasoning/detail-audit/(:any)'] = 'form/seasoning/detail_audit/$1';
$route['seasoning/edit-audit/(:any)'] = 'form/seasoning/edit_audit/$1';

$route['kebersihanruang'] = 'form/kebersihanruang';
$route['kebersihanruang/tambah'] = 'form/kebersihanruang/tambah';
$route['kebersihanruang/detail/(:any)'] = 'form/kebersihanruang/detail/$1';
$route['kebersihanruang/edit/(:any)'] = 'form/kebersihanruang/edit/$1';
$route['kebersihanruang/verifikasi'] = 'form/kebersihanruang/verifikasi';
$route['kebersihanruang/status/(:any)'] = 'form/kebersihanruang/status/$1';
$route['kebersihanruang/cetak'] = 'form/kebersihanruang/cetak';
$route['kebersihanruang/diketahui'] = 'form/kebersihanruang/diketahui';
$route['kebersihanruang/statusprod/(:any)'] = 'form/kebersihanruang/statusprod/$1';
$route['kebersihanruang/delete/(:any)'] = 'form/kebersihanruang/delete/$1';
$route['kebersihanruang/logs'] = 'form/kebersihanruang_logs/index';
$route['kebersihanruang/export_excel'] = 'form/kebersihanruang/export_excel';
$route['kebersihanruang/audit'] = 'form/kebersihanruang/audit';
$route['kebersihanruang/copy-to-audit/(:any)'] = 'form/kebersihanruang/copy_to_audit/$1';
$route['kebersihanruang/cetak-audit'] = 'form/kebersihanruang/cetak_audit';
$route['kebersihanruang/edit-audit/(:any)'] = 'form/kebersihanruang/edit_audit/$1';
$route['kebersihanruang/detail-audit/(:any)'] = 'form/kebersihanruang/detail_audit/$1';

$route['sanitasiwarehouse'] = 'form/sanitasiwarehouse';
$route['sanitasiwarehouse/tambah'] = 'form/sanitasiwarehouse/tambah';
$route['sanitasiwarehouse/detail/(:any)'] = 'form/sanitasiwarehouse/detail/$1';
$route['sanitasiwarehouse/edit/(:any)'] = 'form/sanitasiwarehouse/edit/$1';
$route['sanitasiwarehouse/verifikasi'] = 'form/sanitasiwarehouse/verifikasi';
$route['sanitasiwarehouse/status/(:any)'] = 'form/sanitasiwarehouse/status/$1';
$route['sanitasiwarehouse/cetak'] = 'form/sanitasiwarehouse/cetak';
$route['sanitasiwarehouse/diketahui'] = 'form/sanitasiwarehouse/diketahui';
$route['sanitasiwarehouse/statuswh/(:any)'] = 'form/sanitasiwarehouse/statuswh/$1';
$route['sanitasiwarehouse/delete/(:any)'] = 'form/sanitasiwarehouse/delete/$1';
$route['sanitasiwarehouse/logs'] = 'form/sanitasiwarehouse_logs/index';
$route['sanitasiwarehouse/copy-to-audit/(:any)'] = 'form/sanitasiwarehouse/copy_to_audit/$1';
$route['sanitasiwarehouse/audit'] = 'form/sanitasiwarehouse/audit';
$route['sanitasiwarehouse/cetak-audit'] = 'form/sanitasiwarehouse/cetak_audit';
$route['sanitasiwarehouse/detail-audit/(:any)'] = 'form/sanitasiwarehouse/detail_audit/$1';
$route['sanitasiwarehouse/edit-audit/(:any)'] = 'form/sanitasiwarehouse/edit_audit/$1';

$route['loading'] = 'form/loading';
$route['loading/tambah'] = 'form/loading/tambah';
$route['loading/detail/(:any)'] = 'form/loading/detail/$1';
$route['loading/edit/(:any)'] = 'form/loading/edit/$1';
$route['loading/verifikasi'] = 'form/loading/verifikasi';
$route['loading/status/(:any)'] = 'form/loading/status/$1';
$route['loading/cetak'] = 'form/loading/cetak';
$route['loading/diketahui'] = 'form/loading/diketahui';
$route['loading/statuswh/(:any)'] = 'form/loading/statuswh/$1';
$route['loading/delete/(:any)'] = 'form/loading/delete/$1';
$route['loading/logs'] = 'form/loading_logs/index';
$route['loading/copy-to-audit/(:any)'] = 'form/loading/copy_to_audit/$1';
$route['loading/audit'] = 'form/loading/audit';
$route['loading/detail-audit/(:any)'] = 'form/loading/detail_audit/$1';
$route['loading/edit-audit/(:any)'] = 'form/loading/edit_audit/$1';

$route['disposisi'] = 'form/disposisi';
$route['disposisi/tambah'] = 'form/disposisi/tambah';
$route['disposisi/detail/(:any)'] = 'form/disposisi/detail/$1';
$route['disposisi/edit/(:any)'] = 'form/disposisi/edit/$1';
$route['disposisi/verifikasi'] = 'form/disposisi/verifikasi';
$route['disposisi/status/(:any)'] = 'form/disposisi/status/$1';
$route['disposisi/cetak'] = 'form/disposisi/cetak';
$route['disposisi/diketahui'] = 'form/disposisi/diketahui';
$route['disposisi/statusprod/(:any)'] = 'form/disposisi/statusprod/$1';
$route['disposisi/delete/(:any)'] = 'form/disposisi/delete/$1';
$route['disposisi/logs'] = 'form/disposisi_logs/index';
$route['disposisi/copy-to-audit/(:any)'] = 'form/disposisi/copy_to_audit/$1';
$route['disposisi/audit'] = 'form/disposisi/audit';
$route['disposisi/edit-audit/(:any)']  = 'form/disposisi/edit_audit/$1';
$route['disposisi/detail-audit/(:any)'] = 'form/disposisi/detail_audit/$1';

$route['magnettrap'] = 'form/magnettrap';
$route['magnettrap/tambah'] = 'form/magnettrap/tambah';
$route['magnettrap/detail/(:any)'] = 'form/magnettrap/detail/$1';
$route['magnettrap/edit/(:any)'] = 'form/magnettrap/edit/$1';
$route['magnettrap/verifikasi'] = 'form/magnettrap/verifikasi';
$route['magnettrap/status/(:any)'] = 'form/magnettrap/status/$1';
$route['magnettrap/cetak'] = 'form/magnettrap/cetak';
$route['magnettrap/diketahui'] = 'form/magnettrap/diketahui';
$route['magnettrap/statuseng/(:any)'] = 'form/magnettrap/statuseng/$1';
$route['magnettrap/delete/(:any)'] = 'form/magnettrap/delete/$1';
$route['magnettrap/logs'] = 'form/magnettrap_logs/index';
$route['magnettrap/export_excel'] = 'form/magnettrap/export_excel';
$route['magnettrap/audit'] = 'form/magnettrap/audit';
$route['magnettrap/cetak-audit'] = 'form/magnettrap/cetak_audit';
$route['magnettrap/detail-audit/(:any)'] = 'form/magnettrap/detail_audit/$1';
$route['magnettrap/edit-audit/(:any)'] = 'form/magnettrap/edit_audit/$1';
$route['magnettrap/verifikasi_produksi/(:any)'] = 'form/magnettrap/verifikasi_produksi/$1';
$route['magnettrap/verifikasi_massal']['POST'] = 'form/magnettrap/verifikasi_massal';

$route['kebersihanmesin'] = 'form/kebersihanmesin';
$route['kebersihanmesin/tambah'] = 'form/kebersihanmesin/tambah';
$route['kebersihanmesin/detail/(:any)'] = 'form/kebersihanmesin/detail/$1';
$route['kebersihanmesin/edit/(:any)'] = 'form/kebersihanmesin/edit/$1';
$route['kebersihanmesin/verifikasi'] = 'form/kebersihanmesin/verifikasi';
$route['kebersihanmesin/status/(:any)'] = 'form/kebersihanmesin/status/$1';
$route['kebersihanmesin/cetak'] = 'form/kebersihanmesin/cetak';
$route['kebersihanmesin/diketahui'] = 'form/kebersihanmesin/diketahui';
$route['kebersihanmesin/statusprod/(:any)'] = 'form/kebersihanmesin/statusprod/$1';
$route['kebersihanmesin/delete/(:any)'] = 'form/kebersihanmesin/delete/$1';
$route['kebersihanmesin/logs'] = 'form/kebersihanmesin_logs/index';
$route['kebersihanmesin/copy-to-audit/(:any)'] = 'form/kebersihanmesin/copy_to_audit/$1';
$route['kebersihanmesin/audit'] = 'form/kebersihanmesin/audit';
$route['kebersihanmesin/cetak-audit'] = 'form/kebersihanmesin/cetak_audit';
$route['kebersihanmesin/edit-audit/(:any)'] = 'form/kebersihanmesin/edit_audit/$1';
$route['kebersihanmesin/detail-audit/(:any)'] = 'form/kebersihanmesin/detail_audit/$1';

$route['sensori'] = 'form/sensori';
$route['sensori/tambah'] = 'form/sensori/tambah';
$route['sensori/detail/(:any)'] = 'form/sensori/detail/$1';
$route['sensori/edit/(:any)'] = 'form/sensori/edit/$1';
$route['sensori/verifikasi'] = 'form/sensori/verifikasi';
$route['sensori/status/(:any)'] = 'form/sensori/status/$1';
$route['sensori/cetak'] = 'form/sensori/cetak';
$route['sensori/diketahui'] = 'form/sensori/diketahui';
$route['sensori/statusprod/(:any)'] = 'form/sensori/statusprod/$1';
$route['sensori/delete/(:any)'] = 'form/sensori/delete/$1';
$route['sensori/logs'] = 'form/sensori_logs/index';
$route['sensori/copy-to-audit/(:any)'] = 'form/sensori/copy_to_audit/$1';
$route['sensori/audit'] = 'form/sensori/audit';
$route['sensori/cetak-audit'] = 'form/sensori/cetak_audit';
$route['sensori/edit-audit/(:any)'] = 'form/sensori/edit_audit/$1';
$route['sensori/detail-audit/(:any)'] = 'form/sensori/detail_audit/$1';

$route['reagen'] = 'form/reagen';
$route['reagen/tambah'] = 'form/reagen/tambah';
$route['reagen/detail/(:num)/(:num)'] = 'form/reagen/detail/$1/$2';
$route['reagen/edit/(:any)'] = 'form/reagen/edit/$1';
$route['reagen/update/(:any)'] = 'form/reagen/update/$1';
$route['reagen/delete/(:any)'] = 'form/reagen/delete/$1';
$route['reagen/logs'] = 'form/reagen_logs/index';
$route['reagen/detail_bulan/(:num)/(:num)'] = 'form/reagen/detail_bulan/$1/$2';
$route['reagen/edit_bulan/(:num)/(:num)']   = 'form/reagen/edit_bulan/$1/$2';
$route['reagen/delete_bulan/(:num)/(:num)'] = 'form/reagen/delete_bulan/$1/$2';
$route['reagen/verifikasi'] = 'form/reagen/verifikasi';
$route['reagen/status/(:num)/(:num)']   = 'form/reagen/status/$1/$2';
$route['reagen/verifikasi/(:num)/(:num)'] = 'form/reagen/verifikasi_bulan/$1/$2';
$route['reagen/cetak/(:num)/(:num)']   = 'form/reagen/cetak/$1/$2';
$route['reagen/audit'] = 'form/reagen/audit';
$route['reagen/detail-audit/(:num)/(:num)'] = 'form/reagen/detail_audit/$1/$2';
$route['reagen/edit-audit/(:any)'] = 'form/reagen/edit_audit/$1';

$route['residu'] = 'form/residu';
$route['residu/tambah'] = 'form/residu/tambah';
$route['residu/detail/(:any)'] = 'form/residu/detail/$1';
$route['residu/edit/(:any)'] = 'form/residu/edit/$1';
$route['residu/verifikasi'] = 'form/residu/verifikasi';
$route['residu/status/(:any)'] = 'form/residu/status/$1';
$route['residu/cetak'] = 'form/residu/cetak';
$route['residu/delete/(:any)'] = 'form/residu/delete/$1';
$route['residu/logs'] = 'form/residu_logs/index';
$route['residu/copy-to-audit/(:any)'] = 'form/residu/copy_to_audit/$1';
$route['residu/audit'] = 'form/residu/audit';
$route['residu/cetak-audit'] = 'form/residu/cetak_audit';
$route['residu/edit-audit/(:any)'] = 'form/residu/edit_audit/$1';
$route['residu/detail-audit/(:any)'] = 'form/residu/detail_audit/$1';

$route['larutan'] = 'form/larutan';
$route['larutan/tambah'] = 'form/larutan/tambah';
$route['larutan/detail/(:any)'] = 'form/larutan/detail/$1';
$route['larutan/edit/(:any)'] = 'form/larutan/edit/$1';
$route['larutan/verifikasi'] = 'form/larutan/verifikasi';
$route['larutan/status/(:any)'] = 'form/larutan/status/$1';
$route['larutan/cetak'] = 'form/larutan/cetak';
$route['larutan/diketahui'] = 'form/larutan/diketahui';
$route['larutan/statusprod/(:any)'] = 'form/larutan/statusprod/$1';
$route['larutan/delete/(:any)'] = 'form/larutan/delete/$1';
$route['larutan/logs'] = 'form/larutan_logs/index';
$route['larutan/copy-to-audit/(:any)'] = 'form/larutan/copy_to_audit/$1';
$route['larutan/audit'] = 'form/larutan/audit';
$route['larutan/cetak-audit'] = 'form/larutan/cetak_audit';
$route['larutan/edit-audit/(:any)'] = 'form/larutan/edit_audit/$1';
$route['larutan/detail-audit/(:any)'] = 'form/larutan/detail_audit/$1';

$route['analisis'] = 'form/analisis';
$route['analisis/tambah'] = 'form/analisis/tambah';
$route['analisis/detail/(:any)'] = 'form/analisis/detail/$1';
$route['analisis/edit/(:any)'] = 'form/analisis/edit/$1';
$route['analisis/analis/(:any)'] = 'form/analisis/analis/$1';
$route['analisis/verifikasi'] = 'form/analisis/verifikasi';
$route['analisis/status/(:any)'] = 'form/analisis/status/$1';
$route['analisis/cetak'] = 'form/analisis/cetak';
$route['analisis/diketahui'] = 'form/analisis/diketahui';
$route['analisis/statusprod/(:any)'] = 'form/analisis/statusprod/$1';
$route['analisis/diterima'] = 'form/analisis/diterima';
$route['analisis/statuslab/(:any)'] = 'form/analisis/statuslab/$1';
$route['analisis/ajax-detail/(:any)'] = 'form/analisis/ajax_detail/$1';
$route['analisis/delete/(:any)'] = 'form/analisis/delete/$1';
$route['analisis/logs'] = 'form/analisis_logs/index';
$route['analisis/copy-to-audit/(:any)'] = 'form/analisis/copy_to_audit/$1';
$route['analisis/audit'] = 'form/analisis/audit';
$route['analisis/cetak-audit'] = 'form/analisis/cetak_audit';
$route['analisis/edit-audit/(:any)'] = 'form/analisis/edit_audit/$1';
$route['analisis/detail-audit/(:any)'] = 'form/analisis/detail_audit/$1';
$route['analisis/analis-audit/(:any)'] = 'form/analisis/analis_audit/$1';
$route['analisis/ajax-detail-audit/(:any)'] = 'form/analisis/ajax_detail_audit/$1';

$route['inventaris'] = 'form/inventaris';
$route['inventaris/tambah'] = 'form/inventaris/tambah';
$route['inventaris/detail/(:any)'] = 'form/inventaris/detail/$1';
$route['inventaris/edit/(:any)'] = 'form/inventaris/edit/$1';
$route['inventaris/verifikasi'] = 'form/inventaris/verifikasi';
$route['inventaris/status/(:any)'] = 'form/inventaris/status/$1';
$route['inventaris/cetak'] = 'form/inventaris/cetak';
$route['inventaris/check/(:any)'] = 'form/inventaris/check/$1';
$route['inventaris/delete/(:any)'] = 'form/inventaris/delete/$1';
$route['inventaris/logs'] = 'form/inventaris_logs/index';
$route['inventaris/copy-to-audit/(:any)'] = 'form/inventaris/copy_to_audit/$1';
$route['inventaris/audit'] = 'form/inventaris/audit';
$route['inventaris/edit-audit/(:any)'] = 'form/inventaris/edit_audit/$1';
$route['inventaris/detail-audit/(:any)'] = 'form/inventaris/detail_audit/$1';

$route['pecahbelah'] = 'form/pecahbelah';
$route['pecahbelah/tambah'] = 'form/pecahbelah/tambah';
$route['pecahbelah/detail/(:any)'] = 'form/pecahbelah/detail/$1';
$route['pecahbelah/edit/(:any)'] = 'form/pecahbelah/edit/$1';
$route['pecahbelah/verifikasi'] = 'form/pecahbelah/verifikasi';
$route['pecahbelah/status/(:any)'] = 'form/pecahbelah/status/$1';
$route['pecahbelah/cetak'] = 'form/pecahbelah/cetak';
$route['pecahbelah/diketahui'] = 'form/pecahbelah/diketahui';
$route['pecahbelah/statusprod/(:any)'] = 'form/pecahbelah/statusprod/$1';
$route['pecahbelah/check/(:any)'] = 'form/pecahbelah/check/$1';
$route['pecahbelah/delete/(:any)'] = 'form/pecahbelah/delete/$1';
$route['pecahbelah/logs'] = 'form/pecahbelah_logs/index';
$route['pecahbelah/copy-to-audit/(:any)'] = 'form/pecahbelah/copy_to_audit/$1';
$route['pecahbelah/audit'] = 'form/pecahbelah/audit';
$route['pecahbelah/cetak-audit'] = 'form/pecahbelah/cetak_audit';
$route['pecahbelah/audit'] = 'form/pecahbelah/audit';
$route['pecahbelah/edit-audit/(:any)'] = 'form/pecahbelah/edit_audit/$1';
$route['pecahbelah/detail-audit/(:any)'] = 'form/pecahbelah/detail_audit/$1';

$route['master-suhu'] = 'master_suhu/index';
$route['master-suhu/tambah'] = 'master_suhu/tambah';
$route['master-suhu/edit/(:any)'] = 'master_suhu/edit/$1';
$route['master-suhu/status/(:any)'] = 'master_suhu/status/$1';
$route['master-suhu/delete/(:any)'] = 'master_suhu/delete/$1';
$route['suhu/edit-audit/(:any)'] = 'form/suhu/edit_audit/$1';

$route['suhu'] = 'form/suhu';
$route['suhu/tambah'] = 'form/suhu/tambah';
$route['suhu/detail/(:any)'] = 'form/suhu/detail/$1';
$route['suhu/audit/detail/(:any)'] = 'form/suhu/detail_audit/$1';
$route['suhu/edit/(:any)'] = 'form/suhu/edit/$1';
$route['suhu/verifikasi'] = 'form/suhu/verifikasi';
$route['suhu/status/(:any)'] = 'form/suhu/status/$1';
$route['suhu/cetak'] = 'form/suhu/cetak';
$route['suhu/diketahui'] = 'form/suhu/diketahui';
$route['suhu/statusprod/(:any)'] = 'form/suhu/statusprod/$1';
$route['suhu/delete/(:any)'] = 'form/suhu/delete/$1';
$route['suhu/export-excel'] = 'form/suhu/export_excel';
$route['suhu/export-excel-salatiga'] = 'form/suhu/export_excel_salatiga';
$route['suhu/logs'] = 'form/suhu_logs/index';
$route['suhu/audit'] = 'form/suhu/audit';
$route['suhu/cetak_audit'] = 'form/suhu/cetak_audit';
$route['suhu/copy-to-audit/(:any)'] = 'form/suhu/copy_to_audit/$1';
$route['suhu/verifikasi_produksi/(:any)'] = 'form/suhu/verifikasi_produksi/$1';

$route['gosong'] = 'form/gosong';
$route['gosong/tambah'] = 'form/gosong/tambah';
$route['gosong/detail/(:any)'] = 'form/gosong/detail/$1';
$route['gosong/edit/(:any)'] = 'form/gosong/edit/$1';
$route['gosong/verifikasi'] = 'form/gosong/verifikasi';
$route['gosong/status/(:any)'] = 'form/gosong/status/$1';
$route['gosong/cetak'] = 'form/gosong/cetak';
$route['gosong/diketahui'] = 'form/gosong/diketahui';
$route['gosong/statusprod/(:any)'] = 'form/gosong/statusprod/$1';
$route['gosong/delete/(:any)'] = 'form/gosong/delete/$1';
$route['gosong/export-excel'] = 'form/gosong/export_excel';
$route['gosong/logs'] = 'form/gosong_logs/index';
$route['gosong/export_excel'] = 'form/gosong/export_excel';
$route['gosong/copy-to-audit/(:any)'] = 'form/gosong/copy_to_audit/$1';
$route['gosong/audit'] = 'form/gosong/audit';
$route['gosong/cetak-audit'] = 'form/gosong/cetak_audit';
$route['gosong/edit-audit/(:any)'] = 'form/gosong/edit_audit/$1';
$route['gosong/detail-audit/(:any)'] = 'form/gosong/detail_audit/$1';

// akses plant
$route['akses-plant'] = 'akses_plant/index';
$route['akses-plant/save'] = 'akses_plant/save';

	// traceability
$route['traceability'] = 'traceability/index';
$route['traceability/search'] = 'traceability/search';
$route['traceability/form-details'] = 'traceability/form_details';

//Master Dokumen
$route['master-dokumen'] = 'master_dokumen/index';
$route['master-dokumen/tambah'] = 'master_dokumen/tambah';
$route['master-dokumen/edit/(:any)'] = 'master_dokumen/edit/$1';
$route['master-dokumen/delete/(:any)'] = 'master_dokumen/delete/$1';
