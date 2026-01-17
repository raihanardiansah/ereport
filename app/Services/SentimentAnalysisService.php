<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SentimentAnalysisService
{
    protected string $apiKey;
    protected string $model;
    protected string $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        // Use v1 API (v1beta deprecated for some models)
        $this->endpoint = "https://generativelanguage.googleapis.com/v1/models/{$this->model}:generateContent";
    }

    /**
     * Analyze report for both sentiment and category using Google Gemini.
     * 
     * @param string $title Report title
     * @param string $content Report content
     * @return array ['sentiment' => string, 'category' => string, 'confidence' => float]
     */
    public function analyzeReport(string $title, string $content): array
    {
        try {
            $prompt = $this->buildPrompt($title, $content);
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'topP' => 0.8,
                    'maxOutputTokens' => 256,
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $this->getDefaultResult();
            }

            $result = $response->json();
            Log::info('Gemini Raw Response', ['response' => $result]);

            return $this->parseGeminiResponse($result);

        } catch (\Throwable $e) {
            Log::error('Gemini Analysis Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->getDefaultResult();
        }
    }

    /**
     * Analyze report AND generate title in a SINGLE API call.
     * This is faster than calling generateTitle() + analyzeReport() separately.
     * 
     * @param string $content Report content
     * @return array ['title' => string, 'sentiment' => string, 'category' => string, 'confidence' => float]
     */
    public function analyzeReportFull(string $content): array
    {
        try {
            $prompt = $this->buildFullPrompt($content);
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'topP' => 0.8,
                    'maxOutputTokens' => 300,
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini Full Analysis Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $this->getDefaultFullResult($content);
            }

            $result = $response->json();
            Log::info('Gemini Full Analysis Response', ['response' => $result]);

            return $this->parseFullResponse($result, $content);

        } catch (\Throwable $e) {
            Log::error('Gemini Full Analysis Error', [
                'error' => $e->getMessage(),
            ]);
            return $this->getDefaultFullResult($content);
        }
    }

    /**
     * Build prompt for FULL analysis (title + sentiment + category + urgency).
     */
    protected function buildFullPrompt(string $content): string
    {
        return <<<PROMPT
Anda adalah sistem AI untuk menganalisis laporan di sekolah Indonesia.

Analisis laporan berikut dan berikan:
1. **Judul**: Buat judul singkat (5-7 kata) yang merangkum inti laporan. Jangan awali dengan "Laporan" atau "Tentang".
2. **Sentimen**: positif/negatif/netral
3. **Kategori**: Pilih SATU dari: [perilaku, akademik, kehadiran, bullying, konseling, kesehatan, fasilitas, prestasi, keamanan, ekstrakurikuler, sosial, keuangan, kebersihan, kantin, transportasi, teknologi, guru, kurikulum, perpustakaan, laboratorium, olahraga, keagamaan, saran, lainnya]
4. **Urgency**: Tentukan tingkat urgensi:
   - **critical**: JIKA mengandung ancaman nyawa, bunuh diri, senjata tajam, narkoba, kebakaran, kekerasan fisik berat, pelecehan seksual.
   - **high**: JIKA mengandung bullying verbal parah, pencurian, perkelahian, merokok, bolos massal, kerusakan fasilitas berbahaya.
   - **normal**: Laporan informasi biasa, saran, kehilangan barang kecil, keluhan ringan.
5. **Confidence**: 0.0-1.0

---
ISI LAPORAN:
{$content}
---

Berikan jawaban dalam format JSON (HANYA JSON, tanpa teks lain):
{"title": "Judul Singkat Disini", "sentiment": "positif|negatif|netral", "category": "kategori", "urgency": "normal|high|critical", "confidence": 0.9}
PROMPT;
    }

    /**
     * Parse full response including title and urgency.
     */
    protected function parseFullResponse(array $result, string $content): array
    {
        try {
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $text = preg_replace('/```json\s*/', '', $text);
            $text = preg_replace('/```\s*/', '', $text);
            $text = trim($text);
            
            $parsed = json_decode($text, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse Gemini Full JSON', ['text' => $text]);
                return $this->getDefaultFullResult($content);
            }

            // Validate title
            $title = trim($parsed['title'] ?? '');
            if (empty($title) || strlen($title) > 100) {
                $title = \Str::limit($content, 50);
            }

            // Validate sentiment
            $sentiment = strtolower($parsed['sentiment'] ?? 'netral');
            if (!in_array($sentiment, ['positif', 'negatif', 'netral'])) {
                $sentiment = 'netral';
            }

            // Validate category
            $validCategories = [
                'perilaku', 'akademik', 'kehadiran', 'bullying', 'konseling',
                'kesehatan', 'fasilitas', 'prestasi', 'keamanan', 'ekstrakurikuler',
                'sosial', 'keuangan', 'kebersihan', 'kantin', 'transportasi',
                'teknologi', 'guru', 'kurikulum', 'perpustakaan', 'laboratorium',
                'olahraga', 'keagamaan', 'saran', 'lainnya'
            ];
            $category = strtolower($parsed['category'] ?? 'lainnya');
            if (!in_array($category, $validCategories)) {
                $category = 'lainnya';
            }

            // Validate urgency
            $urgency = strtolower($parsed['urgency'] ?? 'normal');
            if (!in_array($urgency, ['normal', 'high', 'critical'])) {
                $urgency = 'normal';
            }

            $confidence = floatval($parsed['confidence'] ?? 0.5);

            return [
                'title' => $title,
                'sentiment' => $sentiment,
                'category' => $category,
                'urgency' => $urgency,
                'confidence' => min(1.0, max(0.0, $confidence)),
            ];

        } catch (\Throwable $e) {
            Log::error('Failed to parse full response', ['error' => $e->getMessage()]);
            return $this->getDefaultFullResult($content);
        }
    }

    /**
     * Get default full result for fallback.
     */
    protected function getDefaultFullResult(string $content): array
    {
        return [
            'title' => \Str::limit($content, 50),
            'sentiment' => 'netral',
            'category' => 'lainnya',
            'confidence' => 0.0,
        ];
    }

    /**
     * Build the prompt for Gemini to analyze the report (legacy, kept for compatibility).
     */
    protected function buildPrompt(string $title, string $content): string
    {
        return <<<PROMPT
Anda adalah sistem AI untuk menganalisis laporan di sekolah Indonesia.

Analisis laporan berikut dan tentukan:
1. **Sentimen**: Apakah laporan ini bersifat positif, negatif, atau netral?
   - positif: Laporan tentang prestasi, penghargaan, perilaku baik
   - negatif: Laporan tentang pelanggaran, masalah, keluhan
   - netral: Laporan informatif tanpa sentimen jelas

2. **Kategori**: Pilih SATU kategori yang paling sesuai:
   - perilaku: Terkait sikap, kedisiplinan, tata tertib, sopan santun
   - akademik: Terkait nilai, tugas, prestasi belajar, remedial
   - kehadiran: Terkait absensi, keterlambatan, bolos, izin
   - bullying: Terkait perundungan, intimidasi, kekerasan fisik/verbal
   - konseling: Terkait masalah psikologis, butuh bimbingan, curhat
   - kesehatan: Terkait kesehatan fisik/mental, sakit, izin sakit, P3K
   - fasilitas: Terkait fasilitas sekolah, kerusakan, saran perbaikan
   - prestasi: Terkait pencapaian, penghargaan, lomba, kejuaraan
   - keamanan: Terkait keamanan sekolah, kehilangan barang, pencurian
   - ekstrakurikuler: Terkait kegiatan ekskul, klub, organisasi siswa
   - sosial: Terkait hubungan sosial, konflik antar siswa, pertemanan
   - keuangan: Terkait SPP, beasiswa, bantuan keuangan, pembayaran
   - kebersihan: Terkait kebersihan lingkungan, sanitasi, sampah
   - kantin: Terkait makanan, harga, kebersihan kantin, jajanan
   - transportasi: Terkait bus sekolah, antar-jemput, parkir, kendaraan
   - teknologi: Terkait internet, komputer, lab IT, e-learning, gadget
   - guru: Terkait feedback/laporan tentang pengajar, metode mengajar
   - kurikulum: Terkait materi pelajaran, jadwal, ujian, PR
   - perpustakaan: Terkait koleksi buku, layanan perpustakaan, peminjaman
   - laboratorium: Terkait lab IPA, kimia, fisika, komputer, peralatan lab
   - olahraga: Terkait kegiatan olahraga, lapangan, peralatan, cedera
   - keagamaan: Terkait kegiatan rohani, tempat ibadah, toleransi
   - saran: Saran umum untuk perbaikan sekolah, ide, masukan
   - lainnya: Tidak termasuk kategori di atas

---
JUDUL: {$title}

ISI LAPORAN:
{$content}
---

Berikan jawaban dalam format JSON berikut (HANYA JSON, tanpa teks lain):
{"sentiment": "positif|negatif|netral", "category": "perilaku|akademik|kehadiran|bullying|konseling|kesehatan|fasilitas|prestasi|keamanan|ekstrakurikuler|sosial|keuangan|kebersihan|kantin|transportasi|teknologi|guru|kurikulum|perpustakaan|laboratorium|olahraga|keagamaan|saran|lainnya", "confidence": 0.0-1.0}
PROMPT;
    }

    /**
     * Parse Gemini response and extract sentiment/category.
     */
    protected function parseGeminiResponse(array $result): array
    {
        try {
            // Extract text from Gemini response
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Clean the response - remove markdown code blocks if present
            $text = preg_replace('/```json\s*/', '', $text);
            $text = preg_replace('/```\s*/', '', $text);
            $text = trim($text);
            
            Log::info('Gemini Cleaned Text', ['text' => $text]);
            
            // Parse JSON
            $parsed = json_decode($text, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse Gemini JSON', ['text' => $text]);
                return $this->getDefaultResult();
            }

            // Validate and normalize sentiment
            $sentiment = strtolower($parsed['sentiment'] ?? 'netral');
            if (!in_array($sentiment, ['positif', 'negatif', 'netral'])) {
                $sentiment = 'netral';
            }

            // Validate and normalize category
            $validCategories = [
                'perilaku', 'akademik', 'kehadiran', 'bullying', 'konseling',
                'kesehatan', 'fasilitas', 'prestasi', 'keamanan', 'ekstrakurikuler',
                'sosial', 'keuangan', 'kebersihan', 'kantin', 'transportasi',
                'teknologi', 'guru', 'kurikulum', 'perpustakaan', 'laboratorium',
                'olahraga', 'keagamaan', 'saran', 'lainnya'
            ];
            $category = strtolower($parsed['category'] ?? 'lainnya');
            if (!in_array($category, $validCategories)) {
                $category = 'lainnya';
            }

            $confidence = floatval($parsed['confidence'] ?? 0.5);

            return [
                'sentiment' => $sentiment,
                'category' => $category,
                'confidence' => min(1.0, max(0.0, $confidence)),
            ];

        } catch (\Throwable $e) {
            Log::error('Failed to parse Gemini response', ['error' => $e->getMessage()]);
            return $this->getDefaultResult();
        }
    }

    /**
     * Get default result for fallback.
     */
    protected function getDefaultResult(): array
    {
        return [
            'sentiment' => 'netral',
            'category' => 'lainnya',
            'confidence' => 0.0,
        ];
    }

    /**
     * Legacy method for backward compatibility.
     * @deprecated Use analyzeReport() instead
     */
    public function analyze(string $text): array
    {
        $result = $this->analyzeReport('', $text);
        return [
            'label' => $result['sentiment'],
            'score' => $result['confidence'],
        ];
    }
    /**
     * Generate a concise title for the report based on its content.
     * 
     * @param string $content Report content
     * @return string Generated title
     */
    public function generateTitle(string $content): string
    {
        try {
            $prompt = <<<PROMPT
Anda adalah asisten admin sekolah.
Buatlah JUDUL SANGAT SINGKAT (maksimal 5-7 kata) yang merangkum isi laporan berikut.
Judul harus formal, objektif, dan langsung pada inti masalah.
Jangan gunakan kata "Laporan" atau "Tentang" di awal.

ISI LAPORAN:
{$content}

JUDUL SINGKAT:
PROMPT;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->timeout(15) // Fast timeout for title
            ->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3, // Lower temperature for more deterministic titles
                    'maxOutputTokens' => 20, // Short output
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini Title Generation Failed', ['status' => $response->status()]);
                return 'Laporan Baru'; // Fallback
            }

            $result = $response->json();
            $title = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Laporan Baru';
            
            // Clean up title
            $title = trim(str_replace(['"', "'", "*"], '', $title));
            
            // Truncate if too long (backup safety)
            return Str::limit($title, 100);

        } catch (\Throwable $e) {
            Log::error('Title Generation Error', ['error' => $e->getMessage()]);
            return 'Laporan Baru';
        }
    }
}
