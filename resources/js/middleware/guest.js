// src/middleware/guest.js
export function guest({ next, store }) {
  // Cek apakah pengguna sudah terautentikasi
  if (store.state.isAuthenticated) {
      return next({ name: 'home' }); // Redirect ke halaman home jika sudah terautentikasi
  }
  return next(); // Lanjutkan ke halaman yang diminta
}