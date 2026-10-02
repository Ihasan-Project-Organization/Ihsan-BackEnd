-- Database Dump generated on 2026-10-02 15:42:27

-- Table: migrations
CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_08_25_000001_create_service_requests_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_08_25_000003_create_service_reviews_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_09_02_000001_update_service_requests_table_for_provider_workflow', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_09_06_000001_rename_tables_and_columns', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_09_06_000002_create_profiles_and_roles_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_09_06_000003_add_missing_columns_to_existing_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_09_06_000004_update_requests_foreign_keys_to_profiles', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_09_06_000005_create_remaining_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_09_06_000006_add_phone_number_to_profiles_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_09_06_000007_create_provider_reliability_incidents_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_09_06_000008_update_ratings_unique_constraint', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2026_09_11_000001_add_resubmission_note_to_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2026_09_11_000002_add_suspended_status_and_suspension_reason_to_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('17', '2026_09_11_000003_create_system_settings_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('18', '2026_09_11_000004_create_admin_audit_logs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('19', '2026_09_11_000005_add_id_number_and_previous_provider', '1');

-- Table: users
CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime, "status" varchar check ("status" in ('pending', 'approved', 'rejected', 'suspended')) not null default 'pending', "rejection_reason" text, "profile_picture_path" varchar, "resubmission_note" text, "suspension_reason" text);

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `status`, `rejection_reason`, `profile_picture_path`, `resubmission_note`, `suspension_reason`) VALUES ('1', 'مدير النظام الأعلى', 'superadmin@anees.com', '2026-10-02 13:31:16', '$2y$12$JWVrfDrRZIgYtxubt.2IheO1AJeb.QAmtaj/VV21Ec3kazESemA9q', NULL, '2026-10-02 13:31:16', '2026-10-02 13:31:16', 'approved', NULL, NULL, NULL, NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `status`, `rejection_reason`, `profile_picture_path`, `resubmission_note`, `suspension_reason`) VALUES ('2', 'Yousef Elhabil', 'yousefelhabil2@gmail.com', '2026-10-02 14:31:45', '$2y$12$tFjRi6ytlZXfGDmvxtI/HuASoltEVX1cqT0isc9QcxnOT/rVi/MUS', NULL, '2026-10-02 13:37:49', '2026-10-02 15:24:29', 'approved', NULL, 'profile-pictures/iLEPYSZEWua2aK6O7N8iGZcnSABoqHpRVhodYj2a.webp', NULL, NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `status`, `rejection_reason`, `profile_picture_path`, `resubmission_note`, `suspension_reason`) VALUES ('3', 'زين باسل الهبيل', 'lunaelhabil@gmail.com', '2026-10-02 15:08:08', '$2y$12$C4Q/9t97nioj9PIt3aVsRu8lbFRY8Lnt12hboAV1CpA/knu9bjVXK', NULL, '2026-10-02 15:03:18', '2026-10-02 15:38:45', 'approved', NULL, 'profile-pictures/IAfxYC73dnssZj8Htm04TykHzafqfDuHv6wUkQSC.jpg', NULL, NULL);

-- Table: password_reset_tokens
CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));

-- Table: sessions
CREATE TABLE "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('0OE2isFVwDMKmNIBA161IJ0xlVLvK8zmhBoHwqgx', NULL, '127.0.0.1', 'curl/8.9.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWnlWN2llYmdBWXpBcUhwQnI0dFJsZEtYejJhalYzRXlOUlZtdjlEYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', '1790950246');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('mRB2p2InxbfU9ZxkjMus2dEyMyiIHWHucLsx5pPa', NULL, '127.0.0.1', 'curl/8.9.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEUyYXAyWEpUd1FTbW5OTFVqejJQYW9xWWZVN1VKRGw4Z3dDZkVZQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1790950426');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('EesVgNQIEdmt3Ar6Md3AOQtQ5TPV14KZfBzTOBK5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidmxYYjR2czRzS1pBZElOTlJZam5HV1BrVnNPVTdmTGRySEdhWTNvSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', '1790950511');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('WYXSaQTdSnAlcUSqcL4Y3nvPpWWhJPcXiyrRWB9C', NULL, '127.0.0.1', 'curl/8.9.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmhZN0VCWUhtRHlkQ3kwdjh6TVlQWWNkaVdDekF1eFNqZ0F0SmtmYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1790951326');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('7CzkPtc3onh25seG5BHBCPjx9wk0DtEwQTXUJp28', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib1ZEZ05tOUF0OXRPalA5TDd6TnhKOWlFM2J6UHJBOXJ1ZDlGMVU1MCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO319', '1790952083');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('jH7k1q5C591H4RBADjKFaNF5TAzaZQYxeVEiTAuU', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREdPYjRjbGxZNmFyZFVrakdISWgzWmhpRlNJZHJHUG1mNVJGcm9LMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC92ZXJpZnktZW1haWwiO3M6NToicm91dGUiO3M6MTk6InZlcmlmaWNhdGlvbi5ub3RpY2UiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', '1790953633');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('bDCRLzQyESrlfdcyDDtod5dSD3yGeP9bFsBHB9oe', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaDFkSnFWb3dRdXRGSEliQTl5Mkloa0tjSUxsbEFtU2c1WVNxRXFBeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm92aWRlci9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTg6InByb3ZpZGVyLmRhc2hib2FyZCI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', '1790953936');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('hyV24NYaeOKZD6GDYMnNS17RwQydegEONK1ohL5v', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGU5ckNNdVVQeDQzYTJNOTlKS1lmdzB1SjJZekhJNUR5TTc0c3pWaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm92aWRlci90YXNrcyI7czo1OiJyb3V0ZSI7czoxNDoicHJvdmlkZXIudGFza3MiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', '1790955741');

-- Table: cache
CREATE TABLE "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-admin@hostzera.com|127.0.0.1:timer', 'i:1790950764;', '1790950764');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-admin@hostzera.com|127.0.0.1', 'i:2;', '1790950765');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1790951518;', '1790951518');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', '1790951519');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1790953747;', '1790953747');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:2;', '1790953747');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1790954404;', '1790954404');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('anys-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:5;', '1790954405');

-- Table: cache_locks
CREATE TABLE "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));

-- Table: jobs
CREATE TABLE "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);

-- Table: job_batches
CREATE TABLE "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));

-- Table: failed_jobs
CREATE TABLE "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);

-- Table: ratings
CREATE TABLE "ratings" ("id" integer primary key autoincrement not null, "service_request_id" integer not null, "elderly_id" integer not null, "provider_id" integer not null, "stars" integer not null, "comment" text, "created_at" datetime, "updated_at" datetime, "rater_role" varchar check ("rater_role" in ('elder', 'provider')) not null default 'elder', "visible_to_provider" tinyint(1) not null default '1', foreign key("service_request_id") references "requests"("id") on delete cascade, foreign key("elderly_id") references "users"("id") on delete cascade, foreign key("provider_id") references "users"("id") on delete cascade);

INSERT INTO `ratings` (`id`, `service_request_id`, `elderly_id`, `provider_id`, `stars`, `comment`, `created_at`, `updated_at`, `rater_role`, `visible_to_provider`) VALUES ('1', '1', '2', '3', '5', NULL, '2026-10-02 15:29:45', '2026-10-02 15:29:45', 'elder', '1');

-- Table: elder_profiles
CREATE TABLE "elder_profiles" ("id" integer primary key autoincrement not null, "user_id" integer not null, "full_name" varchar not null, "city" varchar not null, "id_document_path" varchar, "created_at" datetime, "updated_at" datetime, "phone_number" varchar not null, "id_number" varchar, "birth_date" date, "address" varchar, "housing_type" varchar, foreign key("user_id") references "users"("id") on delete cascade);

INSERT INTO `elder_profiles` (`id`, `user_id`, `full_name`, `city`, `id_document_path`, `created_at`, `updated_at`, `phone_number`, `id_number`, `birth_date`, `address`, `housing_type`) VALUES ('1', '2', 'Yousef Elhabil', 'gaza', NULL, '2026-10-02 13:37:49', '2026-10-02 13:37:49', '0597368936', '409918364', '2004-05-05 00:00:00', 'gaza', 'apartment');

-- Table: service_provider_profiles
CREATE TABLE "service_provider_profiles" ("id" integer primary key autoincrement not null, "user_id" integer not null, "full_name" varchar not null, "birth_date" date not null, "id_document_path" varchar not null, "good_conduct_cert_path" varchar not null, "tier" integer not null default '1', "completed_tasks_count" integer not null default '0', "average_rating" numeric, "is_available" tinyint(1) not null default '0', "reliability_incidents_count" integer not null default '0', "created_at" datetime, "updated_at" datetime, "phone_number" varchar not null, "id_number" varchar, foreign key("user_id") references "users"("id") on delete cascade);

INSERT INTO `service_provider_profiles` (`id`, `user_id`, `full_name`, `birth_date`, `id_document_path`, `good_conduct_cert_path`, `tier`, `completed_tasks_count`, `average_rating`, `is_available`, `reliability_incidents_count`, `created_at`, `updated_at`, `phone_number`, `id_number`) VALUES ('1', '3', 'زين باسل الهبيل', '1999-06-03 00:00:00', 'documents/ids/sWaEpCIjgen4GPdQQ2kMvYwMCmgOcJn81BBC7zeO.png', 'documents/certificates/3fOpGpI7in7c3HWAL3DErZF2h6mAuwMdvD8oBc0d.png', '3', '1', '5', '1', '0', '2026-10-02 15:03:18', '2026-10-02 15:37:00', '0597368936', '123456789');

-- Table: admins
CREATE TABLE "admins" ("id" integer primary key autoincrement not null, "user_id" integer not null, "admin_level" varchar check ("admin_level" in ('admin', 'super_admin')) not null, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade);

INSERT INTO `admins` (`id`, `user_id`, `admin_level`, `created_at`, `updated_at`) VALUES ('1', '1', 'super_admin', '2026-10-02 13:31:16', '2026-10-02 13:31:16');

-- Table: request_attachments
CREATE TABLE "request_attachments" ("id" integer primary key autoincrement not null, "request_id" integer not null, "file_path" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("request_id") references "requests"("id") on delete cascade);

-- Table: complaints
CREATE TABLE "complaints" ("id" integer primary key autoincrement not null, "request_id" integer not null, "reporter_id" integer not null, "description" text not null, "status" varchar check ("status" in ('open', 'under_review', 'closed')) not null default 'open', "admin_notes" text, "created_at" datetime, "updated_at" datetime, foreign key("request_id") references "requests"("id") on delete cascade, foreign key("reporter_id") references "users"("id") on delete cascade);

-- Table: volunteer_certificates
CREATE TABLE "volunteer_certificates" ("id" integer primary key autoincrement not null, "provider_id" integer not null, "certificate_number" varchar not null, "issued_at" date not null, "created_at" datetime, "updated_at" datetime, foreign key("provider_id") references "service_provider_profiles"("id") on delete cascade);

-- Table: notifications
CREATE TABLE "notifications" ("id" integer primary key autoincrement not null, "user_id" integer not null, "type" varchar not null, "message" text not null, "is_read" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade);

INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `is_read`, `created_at`, `updated_at`) VALUES ('1', '3', 'account_approved', 'تم اعتماد حسابك بنجاح من قِبل إدارة منصة أنيس. يمكنك الآن الاستفادة من كافة خدمات المنصة.', '0', '2026-10-02 15:04:55', '2026-10-02 15:04:55');

-- Table: provider_reliability_incidents
CREATE TABLE "provider_reliability_incidents" ("id" integer primary key autoincrement not null, "provider_id" integer not null, "request_id" integer, "incident_type" varchar check ("incident_type" in ('apology', 'delay', 'no_show')) not null default 'apology', "created_at" datetime, "updated_at" datetime, foreign key("provider_id") references "service_provider_profiles"("id") on delete cascade, foreign key("request_id") references "requests"("id") on delete set null);

-- Table: system_settings
CREATE TABLE "system_settings" ("id" integer primary key autoincrement not null, "key" varchar not null, "value" text, "description" varchar, "created_at" datetime, "updated_at" datetime);

INSERT INTO `system_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES ('1', 'tier_2_tasks_threshold', '10', 'integer', '2026-10-02 13:30:52', '2026-10-02 13:31:17');
INSERT INTO `system_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES ('2', 'tier_2_rating_threshold', '4', 'float', '2026-10-02 13:30:52', '2026-10-02 13:31:17');
INSERT INTO `system_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES ('3', 'tier_3_tasks_threshold', '30', 'integer', '2026-10-02 13:30:52', '2026-10-02 13:31:17');
INSERT INTO `system_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES ('4', 'tier_3_rating_threshold', '4.3', 'float', '2026-10-02 13:30:52', '2026-10-02 13:31:17');
INSERT INTO `system_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES ('5', 'reliability_incidents_threshold', '3', 'integer', '2026-10-02 13:31:18', '2026-10-02 13:31:18');

-- Table: admin_audit_logs
CREATE TABLE "admin_audit_logs" ("id" integer primary key autoincrement not null, "admin_id" integer, "action" varchar not null, "target_type" varchar, "target_id" integer, "reason" text, "metadata" text, "created_at" datetime, "updated_at" datetime, foreign key("admin_id") references "users"("id") on delete set null);

INSERT INTO `admin_audit_logs` (`id`, `admin_id`, `action`, `target_type`, `target_id`, `reason`, `metadata`, `created_at`, `updated_at`) VALUES ('1', '1', 'approved_user', 'User', '3', 'تم اعتماد الحساب وتفعيله بنجاح.', '{"user_name":"\u0632\u064a\u0646 \u0628\u0627\u0633\u0644 \u0627\u0644\u0647\u0628\u064a\u0644","email":"lunaelhabil@gmail.com"}', '2026-10-02 15:04:55', '2026-10-02 15:04:55');

-- Table: requests
CREATE TABLE "requests" ("id" integer primary key autoincrement not null, "public_id" varchar not null, "elder_id" integer not null, "provider_id" integer, "title" varchar not null, "description" text not null, "location" varchar not null, "scheduled_at" datetime not null, "status" varchar not null default ('pending_acceptance'), "accepted_at" datetime, "started_at" datetime, "completed_at" datetime, "cancelled_at" datetime, "cancellation_reason" varchar, "created_at" datetime, "updated_at" datetime, "service_type" varchar not null, "pricing_type" varchar not null default ('volunteer'), "proposed_price" numeric, "timing_type" varchar not null default ('scheduled'), "gender_preference" varchar not null default ('any'), "incident_type" varchar, "assigned_at" datetime, "previous_provider_id" integer, foreign key("provider_id") references service_provider_profiles("id") on delete set null on update no action, foreign key("elder_id") references elder_profiles("id") on delete cascade on update no action, foreign key("elder_id") references users("id") on delete cascade on update no action, foreign key("provider_id") references users("id") on delete set null on update no action, foreign key("previous_provider_id") references "service_provider_profiles"("id") on delete set null);

INSERT INTO `requests` (`id`, `public_id`, `elder_id`, `provider_id`, `title`, `description`, `location`, `scheduled_at`, `status`, `accepted_at`, `started_at`, `completed_at`, `cancelled_at`, `cancellation_reason`, `created_at`, `updated_at`, `service_type`, `pricing_type`, `proposed_price`, `timing_type`, `gender_preference`, `incident_type`, `assigned_at`, `previous_provider_id`) VALUES ('1', '#REQ-1001', '1', '1', 'شراء أغراض', 'حابب انه يجي معي مشوارين', 'gaza', '2026-10-03 05:00:00', 'completed', '2026-10-02 15:25:55', '2026-10-02 15:28:59', '2026-10-02 15:29:45', NULL, NULL, '2026-10-02 15:20:37', '2026-10-02 15:29:45', 'grocery', 'volunteer', NULL, 'scheduled', 'any', NULL, '2026-10-02 15:25:55', NULL);
INSERT INTO `requests` (`id`, `public_id`, `elder_id`, `provider_id`, `title`, `description`, `location`, `scheduled_at`, `status`, `accepted_at`, `started_at`, `completed_at`, `cancelled_at`, `cancellation_reason`, `created_at`, `updated_at`, `service_type`, `pricing_type`, `proposed_price`, `timing_type`, `gender_preference`, `incident_type`, `assigned_at`, `previous_provider_id`) VALUES ('2', '#REQ-1002', '1', '1', 'مساعدة منزلية', 'أحتاج إلى خدمة مساعدة منزلية', 'gaza', '2026-10-30 23:30:00', 'assigned', '2026-10-02 15:37:46', NULL, NULL, NULL, NULL, '2026-10-02 15:30:06', '2026-10-02 15:37:46', 'home_help', 'volunteer', NULL, 'scheduled', 'any', NULL, '2026-10-02 15:37:46', NULL);

