import { useQuery, useMutation, useQueryClient } from "@tanstack/vue-query";
import axios from "@/libs/axios";

// Fungsi untuk mengambil daftar tiket
export function useTiketList(options = {}) {
  return useQuery({
    queryKey: ["tikets"],
    queryFn: async () => {
      const response = await axios.get("/tiket");
      return response.data;
    },
    ...options,
  });
}

// Fungsi untuk mengambil detail tiket berdasarkan ID
export function useTiketDetail(tiketId: string, options = {}) {
  return useQuery({
    queryKey: ["tiket", tiketId],
    queryFn: async () => {
      const response = await axios.get(`/tiket/${tiketId}`);
      return response.data;
    },
    enabled: !!tiketId, // Query ini hanya akan dijalankan jika tiketId tersedia
    ...options,
  });
}

// Fungsi untuk membuat tiket baru
export function useCreateTiket(options = {}) {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: async (newTiket: any) => {
      const response = await axios.post("/tiket", newTiket);
      return response.data;
    },
    onSuccess: () => {
      // Refresh data tiket setelah berhasil menambahkan tiket
      queryClient.invalidateQueries(["tiket"]);
    },
    ...options,
  });
}

// Fungsi untuk memperbarui tiket
export function useUpdateTiket(tiketId: string, options = {}) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (updatedTiket: any) => {
      const response = await axios.put(`/tiket/${tiketId}`, updatedTiket);
      return response.data;
    },
    onSuccess: () => {
      // Refresh data tiket setelah berhasil memperbarui tiket
      queryClient.invalidateQueries(["tikets", tiketId]);
    },
    ...options,
  });
}

// Fungsi untuk menghapus tiket
export function useDeleteTiket(tiketId: string, options = {}) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async () => {
      const response = await axios.delete(`/tiket/${tiketId}`);
      return response.data;
    },
    onSuccess: () => {
      // Refresh data tiket setelah berhasil menghapus tiket
      queryClient.invalidateQueries(["tikets"]);
    },
    ...options,
  });
}
