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

$route['penerimaankemasan'] = 'form/penerimaankemasan';
$route['penerimaankemasan/tambah'] = 'form/penerimaankemasan/tambah';
$route['penerimaankemasan/detail/(:any)'] = 'form/penerimaankemasan/detail/$1';
$route['penerimaankemasan/edit/(:any)'] = 'form/penerimaankemasan/edit/$1';
$route['penerimaankemasan/verifikasi'] = 'form/penerimaankemasan/verifikasi';
$route['penerimaankemasan/status/(:any)'] = 'form/penerimaankemasan/status/$1';
$route['penerimaankemasan/cetak'] = 'form/penerimaankemasan/cetak';
$route['penerimaankemasan/delete/(:any)'] = 'form/penerimaankemasan/delete/$1';
$route['penerimaankemasan/logs'] = 'form/penerimaankemasan_logs/index';

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

$route['seasoning'] = 'form/seasoning';
$route['seasoning/tambah'] = 'form/seasoning/tambah';
$route['seasoning/detail/(:any)'] = 'form/seasoning/detail/$1';
$route['seasoning/edit/(:any)'] = 'form/seasoning/edit/$1';
$route['seasoning/verifikasi'] = 'form/seasoning/verifikasi';
$route['seasoning/status/(:any)'] = 'form/seasoning/status/$1';
$route['seasoning/cetak'] = 'form/seasoning/cetak';
$route['seasoning/delete/(:any)'] = 'form/seasoning/delete/$1';
$route['seasoning/logs'] = 'form/seasoning_logs/index';

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

$route['residu'] = 'form/residu';
$route['residu/tambah'] = 'form/residu/tambah';
$route['residu/detail/(:any)'] = 'form/residu/detail/$1';
$route['residu/edit/(:any)'] = 'form/residu/edit/$1';
$route['residu/verifikasi'] = 'form/residu/verifikasi';
$route['residu/status/(:any)'] = 'form/residu/status/$1';
$route['residu/cetak'] = 'form/residu/cetak';
$route['residu/delete/(:any)'] = 'form/residu/delete/$1';
$route['residu/logs'] = 'form/residu_logs/index';

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

$route['suhu'] = 'form/suhu';
$route['suhu/tambah'] = 'form/suhu/tambah';
$route['suhu/detail/(:any)'] = 'form/suhu/detail/$1';
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

// rotes iot 
$route['iot/suhu/(:any)'] = 'iot/suhu/$1';

// akses plant
$route['akses-plant'] = 'akses_plant/index';
$route['akses-plant/save'] = 'akses_plant/save';
