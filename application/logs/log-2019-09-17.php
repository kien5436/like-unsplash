<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-09-17 10:11:42 --> Not Found: Migrate/index
ERROR - 2019-09-17 10:12:14 --> Query error: COLLATION 'utf8mb4_unicode_ci' is not valid for CHARACTER SET 'utf8' - Invalid query: CREATE TABLE IF NOT EXISTS `migrations` (
	`version` BIGINT(20) NOT NULL
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8mb4_unicode_ci
ERROR - 2019-09-17 10:12:30 --> Severity: error --> Exception: Too few arguments to function Migrate::version(), 0 passed in /var/www/like-unsplash/system/core/CodeIgniter.php on line 532 and exactly 1 expected /var/www/like-unsplash/application/controllers/Migrate.php 18
ERROR - 2019-09-17 21:39:18 --> Not Found: Migrations/20180909204702
ERROR - 2019-09-17 21:39:34 --> Not Found: Migrations/version
ERROR - 2019-09-17 21:51:04 --> Query error: ERROR:  date/time field value out of range: "0000-00-00 00:00:00" - Invalid query: CREATE TABLE "photos" (
	"pid" serial NOT NULL,
	"title" varchar(255) NOT NULL,
	"content" varchar(255) NOT NULL,
	"thumbnail" varchar(255) NOT NULL,
	"size" smallint NOT NULL,
	"dim" varchar(9) NOT NULL,
	"views" smallint DEFAULT '0' NOT NULL,
	"downloaded" smallint DEFAULT '0' NOT NULL,
	"uid" smallint NOT NULL,
	"loved" smallint DEFAULT '0' NOT NULL,
	"loved_people" varchar(255) DEFAULT NULL NULL,
	"created_at" timestamp default CURRENT_TIMESTAMP NOT NULL,
	"updated_at" timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
	CONSTRAINT "pk_photos" PRIMARY KEY("pid")
)
ERROR - 2019-09-17 22:14:25 --> Query error: ERROR:  table "tags" does not exist - Invalid query: DROP TABLE "tags"
