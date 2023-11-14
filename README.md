
# SchoolAdmin

Solusi terkini untuk membantu sekolah Anda mengoptimalkan pengelolaan fasilitas secara efisien dan efektif. Dengan berbagai fitur yang mempermudah tugas-tugas administratif terkait manajemen fasilitas, sehingga staf sekolah dapat fokus pada pendidikan dan pengembangan siswa.


## Features

- Manajemen User
- Manajemen Lingkungan
- Manajemen Fasilitas
- Pengajuan Perbaikan Fasilitas
- Pengajuan Pengadaan Fasilitas
- Update Status Pasca Persetujuan Pengajuan
- Export PDF (Pengajuan)
- Cross platform


## Getting Started

- Clone repo ini **https://github.com/bayutb123/SchoolAdmin**
- Jalankan perintah berikut ini pada root project 
```bash
composer install
```
- Rename file `example.env` menjadi `.env`
- Jalankan lagi perintah berikut ini pada root project **SECARA BERURUTAN**
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Deployment
- Local Deployment

  Jalankan perintah berikut untuk menjalankan local deployment
  ```
  php artisan serve --host=0.0.0.0
  ```

### Login Data (Admin)
  Email Address : `admin@school.sch`

  Password      : `admin`
## Authors

- [@bayutb123](https://www.github.com/bayutb123)


## Tech Stack

**Client:** Laravel, SB Admin 2, DOMPDF

**Server:** Laravel


## License

[MIT](https://choosealicense.com/licenses/mit/)
