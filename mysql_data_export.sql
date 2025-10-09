-- MySQL Data Export from SQLite
-- Generated on: 2025-10-09 16:12:04

-- Data for table: roles
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES ('1', 'admin', '2025-10-08 18:21:49', '2025-10-08 18:21:49');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES ('2', 'operator', '2025-10-08 18:21:49', '2025-10-08 18:21:49');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES ('3', 'warga', '2025-10-08 18:21:49', '2025-10-08 18:21:49');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES ('4', 'admin', '2025-10-08 18:22:28', '2025-10-08 18:22:28');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES ('5', 'operator', '2025-10-08 18:22:28', '2025-10-08 18:22:28');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES ('6', 'warga', '2025-10-08 18:22:28', '2025-10-08 18:22:28');

-- Data for table: jenis_surats
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES ('1', 'Surat Keterangan Penghasilan', 'SKP', 'Surat yang menerangkan penghasilan seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES ('2', 'Surat Keterangan Domisili Tinggal', 'SKDT', 'Surat yang menerangkan domisili tempat tinggal seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES ('3', 'Surat Keterangan Domisili Usaha', 'SKDU', 'Surat yang menerangkan domisili tempat usaha', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES ('4', 'Surat Keterangan Pindah/Mandah', 'SKM', 'Surat yang menerangkan kepindahan seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES ('5', 'Surat Keterangan Kematian', 'SKK', 'Surat yang menerangkan kematian seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES ('6', 'Surat Keterangan Nikah', 'SKN', 'Surat yang menerangkan status pernikahan seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');

-- Data for table: users
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `nik`, `tempat_lahir`, `tanggal_lahir`, `agama`, `pekerjaan`, `lingkungan`, `alamat`, `no_hp`) VALUES ('1', 'Test User', 'test@example.com', NULL, '$2y$12$HSVnmDcdSuciEe.I9vaOk.k5KF2IsLb5uJTIORxxE2Xi8KBCChb3G', NULL, '2025-10-08 18:35:51', '2025-10-08 18:35:51', '3', '1234567890123456', 'Jakarta', '1990-01-01 00:00:00', 'Islam', 'Karyawan', '1', 'Jl. Test No. 123', '081234567890');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `nik`, `tempat_lahir`, `tanggal_lahir`, `agama`, `pekerjaan`, `lingkungan`, `alamat`, `no_hp`) VALUES ('2', 'Test Registration User', 'testreg@example.com', NULL, '$2y$12$95mwQwfpgjvTOJuYNvUN5uHw4wPwv6mNNjoTG8WKE/gn.LSy9Joh6', NULL, '2025-10-08 18:39:13', '2025-10-08 19:09:54', '3', '5555555555555555', 'Bandung', '1985-05-15 00:00:00', 'Islam', 'Programmer', '5', 'Jl. Merdeka No. 45', '082345678901');

