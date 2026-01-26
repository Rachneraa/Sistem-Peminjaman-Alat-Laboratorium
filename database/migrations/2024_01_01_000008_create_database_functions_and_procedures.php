<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Function untuk menghitung denda
        DB::unprepared("
            CREATE FUNCTION hitung_denda(
                tanggal_pinjam DATE,
                tanggal_kembali DATE,
                batas_hari INT
            ) RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
                DECLARE hari_terlambat INT;
                DECLARE denda DECIMAL(10,2);
                DECLARE denda_per_hari DECIMAL(10,2) DEFAULT 5000;
                
                SET hari_terlambat = DATEDIFF(tanggal_kembali, DATE_ADD(tanggal_pinjam, INTERVAL batas_hari DAY));
                
                IF hari_terlambat > 0 THEN
                    SET denda = hari_terlambat * denda_per_hari;
                ELSE
                    SET denda = 0;
                END IF;
                
                RETURN denda;
            END
        ");

        // Stored Procedure untuk update stok setelah peminjaman disetujui
        DB::unprepared("
            CREATE PROCEDURE update_stok_setelah_peminjaman(
                IN p_borrowing_id INT
            )
            BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_tool_id INT;
                DECLARE v_jumlah INT;
                
                DECLARE cur CURSOR FOR 
                    SELECT tool_id, jumlah 
                    FROM borrowing_details 
                    WHERE borrowing_id = p_borrowing_id;
                    
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                START TRANSACTION;
                
                OPEN cur;
                
                read_loop: LOOP
                    FETCH cur INTO v_tool_id, v_jumlah;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;
                    
                    UPDATE tools 
                    SET stok = stok - v_jumlah 
                    WHERE id = v_tool_id;
                    
                END LOOP;
                
                CLOSE cur;
                
                COMMIT;
            END
        ");

        // Stored Procedure untuk update stok setelah pengembalian
        DB::unprepared("
            CREATE PROCEDURE update_stok_setelah_pengembalian(
                IN p_borrowing_id INT
            )
            BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_tool_id INT;
                DECLARE v_jumlah INT;
                
                DECLARE cur CURSOR FOR 
                    SELECT tool_id, jumlah 
                    FROM borrowing_details 
                    WHERE borrowing_id = p_borrowing_id;
                    
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                START TRANSACTION;
                
                OPEN cur;
                
                read_loop: LOOP
                    FETCH cur INTO v_tool_id, v_jumlah;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;
                    
                    UPDATE tools 
                    SET stok = stok + v_jumlah 
                    WHERE id = v_tool_id;
                    
                END LOOP;
                
                CLOSE cur;
                
                COMMIT;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS hitung_denda');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_stok_setelah_peminjaman');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_stok_setelah_pengembalian');
    }
};





