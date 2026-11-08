<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumLike;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForumDemoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Buat 10 akun demo
        |--------------------------------------------------------------------------
        */

        $accountData = [
            ['Nabila Putri', 'nabila'],
            ['Dimas Pratama', 'dimas'],
            ['Raka Saputra', 'raka'],
            ['Siti Aulia', 'sitiaulia'],
            ['Fajar Ramadhan', 'fajar'],
            ['Intan Permata', 'intan'],
            ['Rizky Maulana', 'rizky'],
            ['Maya Lestari', 'maya'],
            ['Bagas Firmansyah', 'bagas'],
            ['Tiara Anindya', 'tiara'],
        ];

        $users = [];

        foreach ($accountData as [$name, $username]) {
            $user = User::firstOrNew([
                'email' => $username . '@urlinsight.test',
            ]);

            $user->name = $name;
            $user->password = Hash::make('password123');
            $user->role = 'user';
            $user->email_verified_at = now();
            $user->save();

            $users[] = $user;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Pastikan kategori tersedia
        |--------------------------------------------------------------------------
        */

        $categoryData = [
            [
                'name' => 'Edukasi Phishing',
                'slug' => 'edukasi-phishing',
                'description' => 'Belajar mengenali berbagai bentuk phishing.',
            ],
            [
                'name' => 'Keamanan Digital',
                'slug' => 'keamanan-digital',
                'description' => 'Diskusi tentang keamanan akun dan data.',
            ],
            [
                'name' => 'Tanya Jawab',
                'slug' => 'tanya-jawab',
                'description' => 'Tempat bertanya dan berbagi pengetahuan.',
            ],
            [
                'name' => 'Pengalaman Pengguna',
                'slug' => 'pengalaman-pengguna',
                'description' => 'Berbagi pengalaman menghadapi ancaman digital.',
            ],
            [
                'name' => 'Berita dan Informasi',
                'slug' => 'berita-dan-informasi',
                'description' => 'Informasi seputar perkembangan keamanan digital.',
            ],
        ];

        $categories = [];

        foreach ($categoryData as $data) {
            $category = ForumCategory::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            $categories[$data['slug']] = $category;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Data topik diskusi
        |--------------------------------------------------------------------------
        */

        $topics = [
            [
                'title' => 'Link hadiah gratis yang meminta data pribadi',
                'category' => 'edukasi-phishing',
                'context' => 'Saya menemukan halaman yang menjanjikan hadiah gratis, tetapi meminta nama lengkap, nomor HP, dan data pribadi lainnya.',
            ],
            [
                'title' => 'Undangan pernikahan berbentuk file APK',
                'category' => 'edukasi-phishing',
                'context' => 'Ada pesan WhatsApp berisi undangan pernikahan dalam bentuk file APK. Saya penasaran kenapa undangan dikirim dalam format aplikasi.',
            ],
            [
                'title' => 'Cara mengenali email reset password palsu',
                'category' => 'edukasi-phishing',
                'context' => 'Beberapa email meminta saya segera mengganti password melalui tautan yang diberikan.',
            ],
            [
                'title' => 'Domain website yang mirip dengan website asli',
                'category' => 'edukasi-phishing',
                'context' => 'Saya melihat website dengan nama domain yang hanya berbeda satu huruf dari layanan aslinya.',
            ],
            [
                'title' => 'Bahaya menggunakan shortlink sembarangan',
                'category' => 'edukasi-phishing',
                'context' => 'Tautan pendek sering digunakan untuk membagikan promosi, tetapi alamat tujuan sebenarnya tidak terlihat.',
            ],
            [
                'title' => 'Apakah kode OTP boleh diberikan kepada customer service?',
                'category' => 'keamanan-digital',
                'context' => 'Saya ingin memahami mengapa kode OTP harus dirahasiakan meskipun seseorang mengaku sebagai petugas resmi.',
            ],
            [
                'title' => 'Mengamankan akun WhatsApp dari pembajakan',
                'category' => 'keamanan-digital',
                'context' => 'Saya ingin meningkatkan keamanan WhatsApp karena banyak cerita tentang akun yang diambil alih.',
            ],
            [
                'title' => 'Mengapa menggunakan password yang sama berbahaya?',
                'category' => 'keamanan-digital',
                'context' => 'Selama ini saya menggunakan satu password untuk beberapa akun agar mudah diingat.',
            ],
            [
                'title' => 'Manfaat mengaktifkan autentikasi dua faktor',
                'category' => 'keamanan-digital',
                'context' => 'Saya baru mengetahui fitur autentikasi dua faktor dan ingin memahami manfaatnya.',
            ],
            [
                'title' => 'Apakah HTTPS menjamin website aman?',
                'category' => 'tanya-jawab',
                'context' => 'Saya sering melihat ikon gembok pada browser dan mengira semua website dengan HTTPS pasti aman.',
            ],
            [
                'title' => 'Bagaimana cara memeriksa keaslian sebuah URL?',
                'category' => 'tanya-jawab',
                'context' => 'Saya ingin tahu langkah sederhana untuk memeriksa sebuah URL sebelum membukanya.',
            ],
            [
                'title' => 'Apakah membuka link phishing langsung berbahaya?',
                'category' => 'tanya-jawab',
                'context' => 'Saya tidak sengaja membuka tautan mencurigakan, tetapi tidak mengisi formulir atau mengunduh file.',
            ],
            [
                'title' => 'Pengalaman menerima pesan penipuan mengatasnamakan bank',
                'category' => 'pengalaman-pengguna',
                'context' => 'Saya pernah menerima pesan yang mengaku dari bank dan meminta saya memperbarui informasi rekening.',
            ],
            [
                'title' => 'Hampir tertipu lowongan kerja palsu',
                'category' => 'pengalaman-pengguna',
                'context' => 'Saya menemukan lowongan kerja yang meminta biaya administrasi sebelum proses seleksi.',
            ],
            [
                'title' => 'Pengalaman mendapatkan pesan undian palsu',
                'category' => 'pengalaman-pengguna',
                'context' => 'Saya menerima pesan bahwa nomor saya memenangkan undian, padahal tidak pernah mengikuti program tersebut.',
            ],
            [
                'title' => 'Modus penipuan melalui marketplace',
                'category' => 'pengalaman-pengguna',
                'context' => 'Saya menemukan penjual yang meminta transaksi dilanjutkan di luar platform marketplace.',
            ],
            [
                'title' => 'Pentingnya memperbarui browser secara berkala',
                'category' => 'keamanan-digital',
                'context' => 'Saya sering menunda pembaruan browser karena merasa versi lama masih bisa digunakan.',
            ],
            [
                'title' => 'QR code palsu di tempat umum',
                'category' => 'edukasi-phishing',
                'context' => 'Saya melihat QR code yang ditempel di atas QR code lain dan penasaran apakah hal tersebut berbahaya.',
            ],
            [
                'title' => 'Penipuan berkedok investasi dengan keuntungan tetap',
                'category' => 'berita-dan-informasi',
                'context' => 'Saya melihat promosi investasi yang menjanjikan keuntungan tinggi dalam waktu singkat.',
            ],
            [
                'title' => 'Mengapa WiFi publik perlu digunakan dengan hati-hati?',
                'category' => 'keamanan-digital',
                'context' => 'Saya sering menggunakan WiFi gratis di kampus dan tempat umum untuk mengakses berbagai layanan.',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | 4. Variasi sudut pandang postingan
        |--------------------------------------------------------------------------
        */

        $angles = [
            [
                'suffix' => 'Bagaimana cara mengenalinya?',
                'body' => "Saya ingin meminta pendapat teman-teman mengenai topik ini.\n\n%s\n\nApa ciri-ciri yang paling mudah diperhatikan oleh pengguna biasa? Apakah ada langkah pemeriksaan yang sebaiknya dilakukan sebelum mengambil tindakan?",
            ],
            [
                'suffix' => 'Pengalaman yang perlu dibagikan',
                'body' => "Saya merasa topik ini penting untuk dibahas karena masih banyak orang yang belum terlalu memperhatikan keamanan digital.\n\n%s\n\nMenurut saya, kita perlu lebih teliti sebelum mempercayai pesan atau tautan yang diterima. Apakah teman-teman pernah mengalami hal serupa?",
            ],
            [
                'suffix' => 'Minta pendapat teman-teman',
                'body' => "Mau diskusi sedikit tentang masalah ini.\n\n%s\n\nKalau kalian berada dalam situasi seperti ini, apa yang akan kalian lakukan terlebih dahulu? Saya ingin mengetahui sudut pandang yang berbeda.",
            ],
            [
                'suffix' => 'Tips yang bisa diterapkan',
                'body' => "Saya sedang mencoba meningkatkan kebiasaan menjaga keamanan akun dan menemukan topik ini cukup menarik.\n\n%s\n\nAdakah tips sederhana yang bisa diterapkan sehari-hari? Akan sangat membantu kalau ada yang mau berbagi pengalaman.",
            ],
            [
                'suffix' => 'Hal yang sering luput diperhatikan',
                'body' => "Kadang kita terlalu terburu-buru ketika menerima pesan atau membuka sebuah website.\n\n%s\n\nMenurut kalian, bagian mana yang paling sering diabaikan pengguna? Semoga diskusi ini bisa menjadi pengingat bagi kita semua.",
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | 5. Buat 100 postingan
        |--------------------------------------------------------------------------
        */

        $posts = [];

        foreach ($topics as $topicIndex => $topic) {
            foreach ($angles as $angleIndex => $angle) {
                $title = $topic['title'] . ' - ' . $angle['suffix'];

                $slug = Str::slug($title);

                // Distribusi 10 akun secara merata.
                $userIndex = (($topicIndex * 5 + $angleIndex) * 7) % 10;

                // Sebarkan tanggal dalam 45 hari terakhir.
                $postNumber = ($topicIndex * 5) + $angleIndex;
                $daysAgo = 45 - (($postNumber * 11) % 46);

                $createdAt = now()
                    ->subDays($daysAgo)
                    ->subHours(($postNumber * 3) % 24);

                $post = ForumPost::firstOrNew([
                    'slug' => $slug,
                ]);

                $post->fill([
                    'user_id' => $users[$userIndex]->id,
                    'category_id' => $categories[$topic['category']]->id,
                    'title' => $title,
                    'slug' => $slug,
                    'content' => sprintf(
                        $angle['body'],
                        $topic['context']
                    ),
                    'status' => 'published',
                ]);

                // Atur tanggal postingan tanpa ditimpa timestamp otomatis.
                $post->timestamps = false;
                $post->created_at = $createdAt;
                $post->updated_at = $createdAt;
                $post->save();
                $post->timestamps = true;

                $posts[] = $post;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Tambahkan komentar dan balasan
        |--------------------------------------------------------------------------
        */

        $commentTexts = [
            'Menurut saya, jangan langsung percaya sebelum memeriksa sumber informasinya.',
            'Pernah mengalami hal serupa. Sekarang saya lebih berhati-hati saat menerima tautan.',
            'Terima kasih sudah membagikan informasi ini. Cukup membantu untuk pengguna awam.',
            'Hal seperti ini memang perlu lebih sering dibahas agar orang tidak mudah tertipu.',
            'Saya biasanya memeriksa alamat domain terlebih dahulu sebelum melanjutkan.',
            'Setuju. Jangan sampai terburu-buru memberikan informasi pribadi.',
            'Informasi yang menarik. Semoga semakin banyak orang yang sadar tentang keamanan digital.',
            'Kalau menemukan hal seperti ini, sebaiknya simpan bukti dan laporkan melalui kanal resmi.',
            'Saya baru tahu kalau modusnya bisa seperti ini.',
            'Menurut saya edukasi sejak dini juga penting, terutama untuk pengguna yang belum terbiasa dengan teknologi.',
            'Pernah melihat kasus seperti ini di grup keluarga. Untungnya belum ada yang menjadi korban.',
            'Menarik untuk didiskusikan lebih lanjut. Apalagi modus penipuan terus berkembang.',
        ];

        $replyTexts = [
            'Benar, memeriksa sumber informasi adalah langkah awal yang cukup penting.',
            'Setuju. Kita juga perlu mengingatkan keluarga agar lebih berhati-hati.',
            'Terima kasih atas tambahannya. Ini bisa menjadi referensi bagi yang lain.',
            'Saya juga berpikir demikian. Jangan mudah tergiur dengan penawaran yang tidak masuk akal.',
            'Betul, lebih baik berhenti sejenak dan memeriksa daripada menyesal kemudian.',
        ];

        foreach ($posts as $postIndex => $post) {

            // Jangan menggandakan komentar ketika seeder dijalankan ulang.
            if ($post->comments()->exists()) {
                continue;
            }

            $commentCount = 2 + ($postIndex % 4);

            for ($i = 0; $i < $commentCount; $i++) {
                $commentUser = $users[
                    ($postIndex + $i + 1) % 10
                ];

                $comment = $post->comments()->create([
                    'user_id' => $commentUser->id,
                    'content' => $commentTexts[
                        ($postIndex + $i) % count($commentTexts)
                    ],
                ]);

                // Beberapa komentar mendapatkan balasan.
                if (($postIndex + $i) % 3 === 0) {
                    $replyUser = $users[
                        ($postIndex + $i + 2) % 10
                    ];

                    $comment->replies()->create([
                        'post_id' => $post->id,
                        'user_id' => $replyUser->id,
                        'content' => $replyTexts[
                            ($postIndex + $i) % count($replyTexts)
                        ],
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Tambahkan like dari akun yang berbeda
        |--------------------------------------------------------------------------
        */

        foreach ($posts as $postIndex => $post) {
            $likeCount = 2 + ($postIndex % 7);

            for ($i = 0; $i < $likeCount; $i++) {
                $likeUser = $users[
                    ($postIndex + $i + 1) % 10
                ];

                ForumLike::firstOrCreate([
                    'post_id' => $post->id,
                    'user_id' => $likeUser->id,
                ]);
            }
        }

        $this->command->info('Forum demo berhasil dibuat!');
        $this->command->info('10 akun, 100 postingan, komentar, balasan, dan like.');
    }
}

/**
 * Nabila Putri:nabila@urlinsight.test
 * Dimas: dimas@urlinsight.test
 * Raka: raka@urlinsight.test
 * Siti: sitiaulia@urlinsight.test
 * Fajar: fajar@urlinsight.test
 * Intan: intan@urlinsight.test
 * Rizky: rizky@urlinsight.test
 * Maya: maya@urlinsight.test
 * Bagas: bagas@urlinsight.test
 * Tiara: tiara@urlinsight.test
 * Password semua akun: password123
 */