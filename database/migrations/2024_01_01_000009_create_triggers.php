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
        // Trigger untuk log aktivitas setelah insert borrowing
        DB::unprepared("
            CREATE TRIGGER log_aktivitas_borrowing_insert
            AFTER INSERT ON borrowings
            FOR EACH ROW
            BEGIN
                INSERT INTO activity_logs (user_id, aktivitas, model_type, model_id, created_at, updated_at)
                VALUES (NEW.user_id, CONCAT('Mengajukan peminjaman #', NEW.id), 'App\\\\Models\\\\Borrowing', NEW.id, NOW(), NOW());
            END
        ");

        // Trigger untuk log aktivitas setelah update borrowing
        DB::unprepared("
            CREATE TRIGGER log_aktivitas_borrowing_update
            AFTER UPDATE ON borrowings
            FOR EACH ROW
            BEGIN
                IF OLD.status != NEW.status THEN
                    INSERT INTO activity_logs (user_id, aktivitas, model_type, model_id, created_at, updated_at)
                    VALUES (NEW.user_id, CONCAT('Status peminjaman #', NEW.id, ' diubah menjadi ', NEW.status), 'App\\\\Models\\\\Borrowing', NEW.id, NOW(), NOW());
                END IF;
            END
        ");

        // Trigger untuk update stok setelah insert borrowing_detail (jika status disetujui)
        DB::unprepared("
            CREATE TRIGGER update_stok_after_borrowing_detail
            AFTER INSERT ON borrowing_details
            FOR EACH ROW
            BEGIN
                DECLARE borrowing_status VARCHAR(20);
                
                SELECT status INTO borrowing_status 
                FROM borrowings 
                WHERE id = NEW.borrowing_id;
                
                IF borrowing_status = 'disetujui' THEN
                    UPDATE tools 
                    SET stok = stok - NEW.jumlah 
                    WHERE id = NEW.tool_id;
                END IF;
            END
        ");

        // Trigger untuk update stok setelah insert return
        DB::unprepared("
            CREATE TRIGGER update_stok_after_return
            AFTER INSERT ON returns
            FOR EACH ROW
            BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_tool_id INT;
                DECLARE v_jumlah INT;
                
                DECLARE cur CURSOR FOR 
                    SELECT tool_id, jumlah 
                    FROM borrowing_details 
                    WHERE borrowing_id = NEW.borrowing_id;
                    
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
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
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS log_aktivitas_borrowing_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS log_aktivitas_borrowing_update');
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_after_borrowing_detail');
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_after_return');
    }
};





