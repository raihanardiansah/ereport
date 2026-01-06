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
     * Build the prompt for Gemini to analyze the report.
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
}
