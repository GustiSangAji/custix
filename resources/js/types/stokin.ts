export interface StokIn {
    id: number;                // Primary key stok-in
    ticket_id: number;         // Foreign key, merujuk ke ID tiket
    description: string;       // Deskripsi penambahan stok
    added_at: string;          // Tanggal penambahan stok (format ISO string)
    amount: number;            // Jumlah penambahan stok
    created_at?: string;       // Tanggal pembuatan record (optional)
    updated_at?: string;       // Tanggal terakhir update record (optional)
}
