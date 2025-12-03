USE ewallet_db;

ALTER TABLE transactions MODIFY COLUMN type ENUM('earn', 'redeem', 'transfer', 'topup') NOT NULL;
