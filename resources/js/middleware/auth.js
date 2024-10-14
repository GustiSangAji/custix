export function auth({ next, store }) {
  // Cek apakah pengguna terautentikasi
  if (!store.state.isAuthenticated) {
      return next({ name: 'sign-in' }); // Redirect ke halaman login jika tidak terautentikasi
  }
  return next(); // Lanjutkan ke halaman yang diminta
}