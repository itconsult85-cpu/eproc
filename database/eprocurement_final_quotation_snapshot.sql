-- Migration CodeIgniter: 2026-09-24-000012_AddFinalQuotationSnapshot.php
ALTER TABLE quotations ADD final_snapshot_json LONGTEXT NULL;
