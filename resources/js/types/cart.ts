// Tipe untuk User yang terhubung dengan Cart
export interface User {
    id: number;
    name: string;
    email: string;
    // Tambahkan properti lain yang diperlukan dari User
}

// Tipe untuk Ticket yang terhubung dengan Cart
export interface Ticket {
    id: number;
    name: string;
    harga: number;
    // Tambahkan properti lain yang diperlukan dari Ticket
}

// Tipe untuk Cart
export interface Cart {
    id: number;
    user_id: number;
    ticket_id: number;
    jumlah_pemesanan: number;
    total_harga: number;
    status: 'Unpaid' | 'Paid';  // Status bisa 'Unpaid' atau 'Paid'
    created_at: string;  // Tanggal pembuatan (atau gunakan Date jika Anda sudah mem-parsing)
    updated_at: string;  // Tanggal update terakhir

    // Relasi dengan User dan Ticket
    user: User;
    ticket: Ticket;
}
