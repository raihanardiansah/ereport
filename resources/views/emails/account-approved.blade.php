<x-mail::message>
# Halo, {{ $user->name }}! 🎉

Selamat! Akun Anda telah **disetujui** oleh Admin Sekolah.
Sekarang Anda sudah bisa login dan mengakses aplikasi E-Report sekolah Anda.

<x-mail::button :url="route('login')">
Login Sekarang
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
