<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Font Inter dari Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Menggunakan font Inter sebagai default */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Kontainer utama untuk memusatkan konten -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
            <!-- Angka 404 -->
            <h1 class="text-8xl font-bold text-indigo-600 dark:text-indigo-400">404</h1>

            <!-- Judul Pesan Error -->
            <h2 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Halaman Hilang</h2>

            <!-- Deskripsi Pesan Error -->
            <p class="mt-4 text-base text-gray-600 dark:text-gray-300">
                Maaf, kami tidak dapat menemukan halaman yang lo cari. Mungkin sudah dipindahkan atau dihapus.
            </p>

            <!-- Tombol Kembali ke Beranda -->
            <!-- <a href="/" class="mt-8 inline-block rounded-md bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                Kembali ke Beranda
            </a> -->
        </div>
    </div>
</body>

</html>