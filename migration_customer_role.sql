-- Migration script to add customer role to the users table
-- This script modifies the enum values in the phan_quyen column to include 'khach_hang'

-- First, let's backup the current enum values by creating a temporary table
-- Then alter the column to support the new enum values

ALTER TABLE users MODIFY COLUMN phan_quyen ENUM('admin', 'nhan_vien', 'khach_hang') DEFAULT 'nhan_vien';

-- Insert a sample customer account if needed
-- INSERT INTO users (ma_user, ten_user, password, email, phan_quyen) VALUES ('U_CUSTOMER', 'Khách Hàng', '123', 'customer@example.com', 'khach_hang');