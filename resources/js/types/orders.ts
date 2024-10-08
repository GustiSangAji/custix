export type Orders = {
    uuid?: string;        // UUID biasanya berupa string
    id: number;           // BigInteger di TypeScript biasanya direpresentasikan sebagai number
    no: number;           // Sama dengan id, direpresentasikan sebagai number
    total_price: number;   // Sama, BigInteger direpresentasikan sebagai number
    payment_status: 1 | 2 | 3; // Enum dari status pembayaran (1 = menunggu, 2 = sudah dibayar, 3 = kadaluarsa)
};
