<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['traceability_modules'] = [

	/*
	|--------------------------------------------------------------------------
	| PRODUKSI
	|--------------------------------------------------------------------------
	*/
	'produksi' => [
		'label' => 'Produksi',
		'detail_model' => 'Traceability_model',
		'form_table' => 'mixing',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [
			'proses_produksi',
			'proses_packing'
		],

		'shift' => true,

		'detail_sections' => [

			'Verifikasi Proses Produksi' => [
				'Tanggal' => 'date',
				'Shift' => 'shift',
				'Jenis Produk' => 'nama_produk',
				'Kode Produksi' => 'kode_produksi'
			],

			'Raw Material' => [
				'Tepung Terigu - Kode' => 'tegu_kode',
				'Tepung Terigu - Berat' => 'tegu_berat',
				'Tepung Terigu - Sensori' => 'tegu_sens',

				'Tapioka Starch - Kode' => 'tapioka_kode',
				'Tapioka Starch - Berat' => 'tapioka_berat',
				'Tapioka Starch - Sensori' => 'tapioka_sens',

				'Ragi - Kode' => 'ragi_kode',
				'Ragi - Berat' => 'ragi_berat',
				'Ragi - Sensori' => 'ragi_sens',

				'Bread Improver - Kode' => 'bread_kode',
				'Bread Improver - Berat' => 'bread_berat',
				'Bread Improver - Sensori' => 'bread_sens',

				'Shortening - Kode' => 'shortening_kode',
				'Shortening - Berat' => 'shortening_berat',
				'Shortening - Sensori' => 'shortening_sens',

				'Chill Water - Kode' => 'chill_water_kode',
				'Chill Water - Berat' => 'chill_water_berat',
				'Chill Water - Sensori' => 'chill_water_sens'
			],

			'Daftar Premix' => [
				'Premix' => 'premix'
			],

			'Mixing Dough' => [
				'Waktu Mixing' => 'mix_dough_waktu_1',
				'Hasil & Nomor Mesin' => 'mix_dough_mesin',
				'Dough Cutting' => 'mix_dough_cutting',
				'Suhu & RH Ruang' => 'mix_dough_suhu_ruang',
				'Suhu Adonan' => 'mix_dough_suhu_adonan'
			],

			'Fermentasi' => [
				'Suhu' => 'fermen_suhu',
				'RH' => 'fermen_rh',
				'Jam Mulai' => 'fermen_jam_mulai',
				'Jam Selesai' => 'fermen_jam_selesai',
				'Lama Proses' => 'fermen_lama_proses'
			],

			'Electric Baking' => [
				'Suhu Produk' => 'electric_baking_suhu',
				'No Mesin & Expand Roti %' => 'electric_baking_mesin',
				'Sensori Kematangan' => 'sens_kematangan',
				'Sensori Rasa' => 'sens_rasa',
				'Sensori Aroma' => 'sens_aroma',
				'Sensori Tekstur' => 'sens_tekstur',
				'Sensori Warna' => 'sens_warna'
			],

			'Verifikasi Proses Produksi - Stalling' => [
				'Tanggal Stalling' => 'date_stall',
				'Shift Packing' => 'shift_pack',
				'Jam Mulai' => 'stall_jam_mulai',
				'Jam Berhenti' => 'stall_jam_berhenti',
				'Kadar Air Produk' => 'stall_kadar_air'
			],

			'Drying' => [
				'Suhu' => 'dry_suhu',
				'Speed Rotasi' => 'dry_rotasi',
				'Kadar Air' => 'dry_kadar_air'
			],

			'Packing Area' => [
				'Produk Hasil' => 'produk_hasil',
				'Produk Rasa' => 'produk_rasa',
				'Produk Aroma' => 'produk_aroma',
				'Produk Tekstur' => 'produk_tekstur',
				'Produk Warna' => 'produk_warna',
				'Gambar Aktual Kemasan' => 'gambar_kode_kemasan',
				'Kondisi Kemasan' => 'packing_kondisi_kemasan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Produksi' => 'nama_produksi',
				'Status SPV' => 'status_spv',
				'Catatan SPV' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| MAGNET TRAP
	|--------------------------------------------------------------------------
	*/
	'magnettrap' => [
		'label' => 'Pemeriksaan Magnet Trap',
		'detail_model' => 'Traceability_model',
		'form_table' => 'magnet_trap',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => true,

		'detail_sections' => [

			'Informasi Pemeriksaan' => [
				'Tanggal' => 'date',
				'Shift' => 'shift',
				'Pukul' => 'time',
				'Nama Produk' => 'nama_produk',
				'Kode Produksi' => 'kode_produksi'
			],

			'Hasil Pemeriksaan' => [
				'Tahapan' => 'tahapan',
				'Jenis Kontaminasi' => 'kontaminasi',
				'Bukti Temuan' => 'bukti',
				'Analisis Temuan' => 'analisis',
				'Tindakan Koreksi' => 'tindakan',
				'Verifikasi' => 'verifikasi',
				'Keterangan' => 'keterangan',
				'Catatan' => 'catatan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Engineer' => 'nama_enginer',
				'Status Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| VERIFIKASI MAGNET
	|--------------------------------------------------------------------------
	*/
	'verifikasimagnet' => [
		'label' => 'Verifikasi Magnet',
		'detail_model' => 'Traceability_model',
		'form_table' => 'verifikasi_mt',

		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => true,

		'display_fields' => [
			'Kode Produksi' => 'kode_produksi',
			'Nama Produk' => 'nama_produk',
			'Tanggal' => 'date',
			'Shift' => 'shift'
		]
	],


	/*
	|--------------------------------------------------------------------------
	| METAL DETECTOR
	|--------------------------------------------------------------------------
	*/
	'metal' => [
		'label' => 'Metal Detector',
		'detail_model' => 'Traceability_model',
		'form_table' => 'metal',
		'route_key_column' => 'uuid',
		'date_column' => 'date_metal',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => true,

		'detail_sections' => [

			'Informasi Pemeriksaan' => [
				'Tanggal' => 'date_metal',
				'Shift' => 'shift',
				'Jenis Produk' => 'nama_produk',
				'Kode Produksi' => 'kode_produksi',
				'No. Program' => 'no_program',
				'Deteksi NG' => 'deteksi_ng'
			],

			'STD. Spesimen' => [
				'STD Fe' => 'std_fe',
				'STD Non Fe' => 'std_nonfe',
				'STD SUS 304' => 'std_sus304'
			],

			'Deteksi Pertama' => [
				'Pukul' => 'time',
				'Fe' => 'fe_d',
				'Non Fe' => 'nonfe_d',
				'SUS 304' => 'sus_d'
			],

			'Deteksi Kedua' => [
				'Pukul' => 'update_time_t',
				'Fe' => 'fe_t',
				'Non Fe' => 'nonfe_t',
				'SUS 304' => 'sus_t'
			],

			'Deteksi Terakhir' => [
				'Pukul' => 'update_time_b',
				'Fe' => 'fe_b',
				'Non Fe' => 'nonfe_b',
				'SUS 304' => 'sus_b'
			],

			'Keterangan Pemeriksaan' => [
				'Keterangan' => 'keterangan',
				'Catatan' => 'catatan_metal'
			],

			'Verifikasi' => [
				'QC' => 'username_1',
				'Produksi' => 'nama_produksi_metal',
				'Disetujui Supervisor' => 'status_spv',
				'Catatan SPV' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| KONTAMINASI
	|--------------------------------------------------------------------------
	*/
	'kontaminasi' => [
		'label' => 'Kontaminasi',
		'detail_model' => 'Traceability_model',
		'form_table' => 'kontaminasi',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => true,

		'detail_sections' => [

			'Informasi Pemeriksaan' => [
				'Tanggal' => 'date',
				'Pukul' => 'time',
				'Shift' => 'shift',
				'Jenis Kontaminasi' => 'jenis_kontaminasi',
				'Nama Produk' => 'nama_produk',
				'Kode Produksi' => 'kode_produksi',
				'Tahapan' => 'tahapan',
				'Jumlah Temuan' => 'jumlah_temuan'
			],

			'Hasil Pemeriksaan' => [
				'Bukti Temuan' => 'bukti',
				'Analisis Temuan' => 'analisis',
				'Tindakan Koreksi' => 'tindakan',
				'Keterangan' => 'keterangan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Produksi' => 'nama_produksi',
				'Status Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| PENGEMASAN
	|--------------------------------------------------------------------------
	*/
	'pengemasan' => [
		'label' => 'Pengemasan',
		'detail_model' => 'Traceability_model',
		'form_table' => 'pengemasan',

		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => true,

		'display_fields' => [
			'Kode Produksi' => 'kode_produksi',
			'Nama Produk' => 'nama_produk',
			'Tanggal' => 'date',
			'Shift' => 'shift'
		]
	],


	/*
	|--------------------------------------------------------------------------
	| RELEASE PACKING
	|--------------------------------------------------------------------------
	*/
	'releasepacking' => [
		'label' => 'Release Packing',
		'detail_model' => 'Traceability_model',
		'form_table' => 'release_packing',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => false,

		'detail_sections' => [

			'Informasi Release Packing' => [
				'Tanggal' => 'date',
				'Nama Produk' => 'nama_produk',
				'Kode Produksi' => 'kode_produksi',
				'Best Before' => 'best_before',
				'Jumlah' => 'jumlah',
				'Keterangan' => 'keterangan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Disetujui Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| PENGAYAKAN
	|--------------------------------------------------------------------------
	*/
	'pengayakan' => [
		'label' => 'Pengayakan',
		'detail_model' => 'Traceability_model',
		'form_table' => 'pengayakan',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',
		'search_columns' => [
			'kode_produksi',
			'nama_barang'
		],
		'json_search_columns' => [],
		'shift' => true,

		'detail_sections' => [
			'Informasi Pemeriksaan' => [
				'Tanggal' => 'date',
				'Shift' => 'shift',
				'Nama Barang' => 'nama_barang',
				'Kode Produksi' => 'kode_produksi',
				'Expired Date' => 'expired_date',
				'Jumlah Barang' => 'jumlah_barang'
			],

			'Kontaminasi Benda Asing' => [
				'Screen Mess' => 'kba_screenmess',
				'Kerikil' => 'kba_kerikil',
				'Benang' => 'kba_benang'
			],

			'Pemeriksaan' => [
				'Kondisi Screen Ayakan' => 'kondisi',
				'Catatan' => 'catatan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Produksi' => 'nama_produksi',
				'Status Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| PEMUSNAHAN
	|--------------------------------------------------------------------------
	*/
	'pemusnahan' => [
		'label' => 'Pemusnahan',
		'detail_model' => 'Traceability_model',
		'form_table' => 'pemusnahan',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => false,

		'detail_sections' => [

			'Informasi Pemusnahan' => [
				'Tanggal' => 'date',
				'Nama Produk' => 'nama_produk',
				'Kode Produksi' => 'kode_produksi',
				'Best Before' => 'best_before',
				'Analisa Masalah' => 'analisa',
				'Keterangan' => 'keterangan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Disetujui Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| PEMERIKSAAN CHEMICAL
	|--------------------------------------------------------------------------
	*/
	'pemeriksaanchemical' => [
		'label' => 'Pemeriksaan Chemical',
		'detail_model' => 'Traceability_model',
		'form_table' => 'pemeriksaan_chemical',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi'
		],

		'json_search_columns' => [],

		'shift' => true,

		'detail_sections' => [

			'Informasi Pemeriksaan' => [
				'Tanggal' => 'date',
				'Shift' => 'shift',
				'Jenis Chemical' => 'jenis_chemical',
				'Pemasok' => 'pemasok',
				'Jenis Mobil' => 'jenis_mobil',
				'No. Polisi' => 'no_polisi',
				'Identitas Pengantar' => 'identitas_pengantar',
				'No. PO / DO' => 'no_po',
				'Kode Produksi' => 'kode_produksi',
				'Expired Date' => 'expired',
				'Jumlah Barang' => 'jumlah_barang',
				'Sampel (pcs)' => 'sampel',
				'Jumlah Reject' => 'jumlah_reject'
			],

			'Kondisi Mobil' => [
				'Kondisi Mobil' => 'kondisi_mobil'
			],

			'Kondisi Fisik' => [
				'Kemasan' => 'kemasan',
				'Warna' => 'warna',
				'pH' => 'ph',
				'Segel' => 'segel'
			],

			'Halal & Penerimaan' => [
				'Halal Berlaku' => 'halal_berlaku',
				'Penerimaan' => 'penerimaan',
				'COA' => 'coa',
				'Bukti COA' => 'bukti_coa',
				'Keterangan' => 'keterangan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Disetujui Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| PENERIMAAN KEMASAN
	|--------------------------------------------------------------------------
	*/
	'penerimaankemasan' => [
		'label' => 'Penerimaan Kemasan',
		'detail_model' => 'Traceability_model',
		'form_table' => 'penerimaan_kemasan',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi'
		],

		'json_search_columns' => [],

		'shift' => true,

		'detail_sections' => [

			'Informasi Umum' => [
				'Tanggal' => 'date',
				'Shift' => 'shift',
				'Jenis Kemasan' => 'jenis_kemasan',
				'Pemasok' => 'pemasok',
				'Jenis Mobil' => 'jenis_mobil',
				'No. Polisi' => 'no_polisi',
				'Identitas Pengantar' => 'identitas_pengantar',
				'No. PO / DO' => 'no_po',
				'Kode Produksi' => 'kode_produksi'
			],

			'Kondisi Mobil' => [
				'Kondisi Mobil' => 'kondisi_mobil'
			],

			'Jumlah & Sampel' => [
				'Jumlah Datang' => 'jumlah_datang',
				'Sampel (pcs)' => 'sampel',
				'Jumlah Reject' => 'jumlah_reject'
			],

			'Kondisi Fisik' => [
				'Warna' => 'warna',
				'Panjang' => 'panjang',
				'Diameter' => 'diameter',
				'Lebar' => 'lebar',
				'Tinggi' => 'tinggi',
				'Berat' => 'berat',
				'Delaminasi' => 'delaminasi',
				'Bau' => 'bau',
				'Desain' => 'desain',
				'Segel' => 'segel',
				'Penerimaan' => 'penerimaan',
				'COA' => 'coa',
				'Bukti COA' => 'bukti_coa',
				'Keterangan' => 'keterangan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Status Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| SEASONING
	|--------------------------------------------------------------------------
	*/
	'seasoning' => [
		'label' => 'Penerimaan Seasoning',
		'detail_model' => 'Traceability_model',
		'form_table' => 'seasoning',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi'
		],

		'json_search_columns' => [],

		'shift' => false,

		'detail_sections' => [

			'Informasi Pemeriksaan' => [
				'Tanggal' => 'date',
				'Jenis Seasoning' => 'jenis_seasoning',
				'Spesifikasi' => 'spesifikasi',
				'Pemasok' => 'pemasok',
				'Jenis Mobil' => 'jenis_mobil',
				'No. Polisi' => 'no_polisi',
				'Identitas Pengantar' => 'identitas_pengantar',
				'No. PO / DO' => 'no_po',
				'Kode Produksi' => 'kode_produksi',
				'Expired Date' => 'expired'
			],

			'Kondisi Mobil' => [
				'Kondisi Mobil' => 'kondisi_mobil'
			],

			'Jumlah & Sampel' => [
				'Jumlah Barang' => 'jumlah_barang',
				'Sampel (pcs)' => 'sampel',
				'Jumlah Reject' => 'jumlah_reject'
			],

			'Kondisi Fisik' => [
				'Kemasan' => 'kemasan',
				'Warna' => 'warna',
				'Kotoran' => 'kotoran',
				'Aroma' => 'aroma',
				'Kadar Air' => 'kadar_air',
				'Negara Asal Dibuat' => 'negara_asal',
				'Segel' => 'segel',
				'Penerimaan' => 'penerimaan'
			],

			'Persyaratan Dokumen' => [
				'Logo Halal' => 'logo_halal',
				'Halal' => 'sertif_halal',
				'COA' => 'coa',
				'Allergen' => 'allergen',
				'Bukti COA' => 'bukti_coa'
			],

			'Keterangan' => [
				'Keterangan' => 'keterangan',
				'Catatan' => 'catatan'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Disetujui Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| ANALISIS LABORATORIUM
	|--------------------------------------------------------------------------
	*/
	'analisis' => [
		'label' => 'Analisis Laboratorium',
		'detail_model' => 'Traceability_model',
		'form_table' => 'analisis_lab',

		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'kode_produksi',
			'nama_produk'
		],

		'json_search_columns' => [],

		'shift' => false,

		'display_fields' => [
			'Kode Produksi' => 'kode_produksi',
			'Nama Produk' => 'nama_produk',
			'Tanggal' => 'date'
		]
	],


	/*
	|--------------------------------------------------------------------------
	| LOADING
	|--------------------------------------------------------------------------
	*/
	'loading' => [
		'label' => 'Loading',
		'detail_model' => 'Traceability_model',
		'form_table' => 'loading',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [],

		'json_search_columns' => [
			'loading'
		],

		'shift' => true,

		'detail_sections' => [

			'Informasi Loading' => [
				'Tanggal' => 'date',
				'Shift' => 'shift',
				'Start Loading' => 'start_loading',
				'Finish Loading' => 'finish_loading',
				'No. Polisi' => 'no_pol',
				'Nama Supir' => 'nama_supir',
				'Ekspedisi' => 'ekspedisi',
				'Tujuan' => 'tujuan',
				'No. Segel' => 'no_segel'
			],

			'Kondisi Mobil' => [
				'Kondisi Mobil' => 'kondisi_mobil'
			],

			'Loading Produk' => [
				'Data Produk' => 'loading'
			],

			'Verifikasi' => [
				'QC' => 'username',
				'Warehouse' => 'nama_wh',
				'Disetujui Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| SENSORI FINISHED GOOD
	|--------------------------------------------------------------------------
	*/
	'sensori' => [
		'label' => 'Sensori Finished Good',
		'detail_model' => 'Traceability_model',
		'form_table' => 'sensori_fg',
		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [
			'nama_produk'
		],

		'json_search_columns' => [
			'produk'
		],

		'shift' => false,

		'detail_sections' => [

			'Informasi Pemeriksaan' => [
				'Tanggal' => 'date',
				'Nama Produk' => 'nama_produk'
			],

			'Hasil Sensori Produk' => [
				'Data Sensori' => 'produk'
			],

			'Catatan' => [
				'Catatan' => 'catatan'
			],

			'Ve­rifikasi' => [
				'QC' => 'username',
				'Produksi' => 'nama_produksi',
				'Status Supervisor' => 'status_spv',
				'Catatan Supervisor' => 'catatan_spv'
			]
		]
	],


	/*
	|--------------------------------------------------------------------------
	| REAGEN
	|--------------------------------------------------------------------------
	| DATE ONLY
	|--------------------------------------------------------------------------
	*/
	'reagen' => [
		'label' => 'Reagen',
		'detail_model' => 'Traceability_model',
		'form_table' => 'reagen',

		'route_key_column' => 'uuid',
		'date_column' => 'date',
		'plant_column' => 'plant',

		'search_columns' => [],

		'json_search_columns' => [],

		'shift' => false,

		'date_only' => true,

		'display_fields' => [
			'Tanggal' => 'date'
		]
	]

];
