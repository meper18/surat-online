-- Complete MySQL Data Export from SQLite
-- Generated on: 2025-10-09 16:20:58
-- Total tables: 21

SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- Data for table: migrations (22 records)
-- =============================================
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2023_10_01_000001_create_roles_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2023_10_01_000002_add_role_id_to_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2023_10_01_000003_create_jenis_surats_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2023_10_01_000004_create_permohonans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2023_10_01_000005_create_dokumen_persyaratans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2023_10_01_000006_create_surat_penghasilans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10, '2023_10_01_000007_create_surat_domisili_tinggals_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11, '2023_10_01_000008_create_surat_domisili_usahas_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12, '2023_10_01_000009_create_surat_mandahs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13, '2023_10_01_000010_create_surat_kematians_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14, '2023_10_01_000011_create_surat_nikahs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15, '2025_01_27_000001_create_dokumen_wajib_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16, '2025_09_27_000001_add_keperluan_to_permohonans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17, '2025_09_29_142317_create_audit_trails_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18, '2025_09_29_170048_add_keterangan_status_to_permohonans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19, '2025_10_01_155413_add_signature_settings_to_permohonan_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20, '2025_10_02_fix_qr_code_data_column', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21, '2025_10_03_150941_create_sessions_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22, '2025_10_07_000000_fix_qr_code_image_column_length', 1);

-- =============================================
-- Data for table: sessions (9 records)
-- =============================================
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('sGCuQd6J2jujsVPNydVgdgNP9X2rCGOWNT9fYa59', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaWh1OHUyeHl2VHZnZjZmSWJmWjFKZkNjU25oSnU3b1BpVlU0ZEc5ViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWRlX3dlYnZpZXdfcmVxdWVzdF90aW1lPTE3NTk5NDg3ODcyMDIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1759948790);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('RoGZxFuPRLrG9imOCtyiMK5HkpjSlkquiUE5KoFP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWxmNFNyc081UFdNYlBIZEM2WTFUelFtZUJTdEIycEt6MEIzMG1HSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759948793);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('F1wB49TdTjP0hTVpD0LXUOSmdgcH0tfOFkkc9ojQ', 1, '127.0.0.1', 'Symfony', 'YToyOntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759950025);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('KjOBjsFW5FwPmuJVyoHR9aLRlHeoYv6i4rzykG7G', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkpxaEI5am5UQjRUNW9JZ2ZMZnBmR0wyMm5XYk9wUURWVEFkZU1tMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1759950593);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('h334QbYFLo6WOc6hHN0mhLNCukDQHJb859zwdlLV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVo0a2FSVWZkTXVxTmRpRjdKS1JMb01jRjdNemhPSENYUVU3TGNJZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759973475);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('Ia2157fuz1i9x6Q2FAl7LtcIVq9bxYCfqrBsv1lG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVRSeHR0YWZIN2VBd21HVDVEaEdTMGN2TzgxODBHbUsxYzFoVlRCbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759973475);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('z0Xdc6ZQp1Prz3SsOflqi8fNJNLGUuPN3Vz3d43r', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiR3pleTk2UFYzWWJvQkYzdUk2OWxFNWFpOEVHRUdPYmZyRnFiR2JqTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1759973489);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('qn88Nci200OrVwYyARGUUqA0DVBgPTNwf4o6UWuR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia2hzQUI4NFd1MkRwZGttSWRaNk9ZOTZ5ZGtGU2dzYWtaREVpbjhaWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759973501);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('7r1L3oa5jwNdEyR8XVk5HmupIGQpKrvJD3fYsqD6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWGdsN2dkSlVTdGF1TnYwQjg4SDhCR3dRMENzeUxPT3ZGTEt5aWVqdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1759975036);

-- =============================================
-- Data for table: roles (6 records)
-- =============================================
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'admin', '2025-10-08 18:21:49', '2025-10-08 18:21:49');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (2, 'operator', '2025-10-08 18:21:49', '2025-10-08 18:21:49');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (3, 'warga', '2025-10-08 18:21:49', '2025-10-08 18:21:49');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (4, 'admin', '2025-10-08 18:22:28', '2025-10-08 18:22:28');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (5, 'operator', '2025-10-08 18:22:28', '2025-10-08 18:22:28');
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (6, 'warga', '2025-10-08 18:22:28', '2025-10-08 18:22:28');

-- =============================================
-- Data for table: users (2 records)
-- =============================================
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `nik`, `tempat_lahir`, `tanggal_lahir`, `agama`, `pekerjaan`, `lingkungan`, `alamat`, `no_hp`) VALUES (1, 'Test User', 'test@example.com', NULL, '$2y$12$HSVnmDcdSuciEe.I9vaOk.k5KF2IsLb5uJTIORxxE2Xi8KBCChb3G', NULL, '2025-10-08 18:35:51', '2025-10-08 18:35:51', 3, 1234567890123456, 'Jakarta', '1990-01-01 00:00:00', 'Islam', 'Karyawan', 1, 'Jl. Test No. 123', 081234567890);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `nik`, `tempat_lahir`, `tanggal_lahir`, `agama`, `pekerjaan`, `lingkungan`, `alamat`, `no_hp`) VALUES (2, 'Test Registration User', 'testreg@example.com', NULL, '$2y$12$95mwQwfpgjvTOJuYNvUN5uHw4wPwv6mNNjoTG8WKE/gn.LSy9Joh6', NULL, '2025-10-08 18:39:13', '2025-10-08 19:09:54', 3, 5555555555555555, 'Bandung', '1985-05-15 00:00:00', 'Islam', 'Programmer', 5, 'Jl. Merdeka No. 45', 082345678901);

-- =============================================
-- Data for table: jenis_surats (6 records)
-- =============================================
DELETE FROM `jenis_surats`;
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES (1, 'Surat Keterangan Penghasilan', 'SKP', 'Surat yang menerangkan penghasilan seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES (2, 'Surat Keterangan Domisili Tinggal', 'SKDT', 'Surat yang menerangkan domisili tempat tinggal seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES (3, 'Surat Keterangan Domisili Usaha', 'SKDU', 'Surat yang menerangkan domisili tempat usaha', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES (4, 'Surat Keterangan Pindah/Mandah', 'SKM', 'Surat yang menerangkan kepindahan seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES (5, 'Surat Keterangan Kematian', 'SKK', 'Surat yang menerangkan kematian seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');
INSERT INTO `jenis_surats` (`id`, `nama`, `kode`, `deskripsi`, `created_at`, `updated_at`) VALUES (6, 'Surat Keterangan Nikah', 'SKN', 'Surat yang menerangkan status pernikahan seseorang', '2025-10-09 02:20:47', '2025-10-09 02:20:47');

SET FOREIGN_KEY_CHECKS = 1;

-- Export completed: 45 total records from 5 tables
