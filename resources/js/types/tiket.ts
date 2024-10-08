export type Tiket = {
    id: BigInteger;
    kode_tiket: string;
    name: string;
    place: string;
    datetime: string;
    status: 'available' | 'unavailable';
    quantity: number;
    price: number;
    image?: string;
    uuid?: string;
};
