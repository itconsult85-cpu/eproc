-- Migration CodeIgniter: 2026-09-24-000011_AddMasterQuotationSnapshot.php
-- Jalankan ALTER TABLE ini bila menggunakan SQL manual. Untuk mengisi snapshot item secara aman dan kompatibel, lanjutkan dengan: php spark migrate --all
ALTER TABLE quotations ADD master_snapshot_json LONGTEXT NULL;
