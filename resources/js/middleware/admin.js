// src/middleware/admin.js
export function admin({ next, store }) {
  // Cek apakah pengguna adalah admin
  if (store.state.user.role !== 'admin') {
      return next({ name: '404' }); // Redirect ke halaman 404 jika bukan admin
  }
  return next(); // Lanjutkan ke halaman yang diminta
}