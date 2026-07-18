<?php

declare(strict_types=1);

namespace App\Modules\AI\Actions;

use App\Modules\AI\Models\AiHistory;
use App\Modules\AI\Models\AiPrompt;
use App\Modules\AI\Services\AiProviderInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class GenerateContentAction
{
    public function __construct(
        private readonly AiProviderInterface $aiProvider,
    ) {}

    public function execute(Authenticatable $user, string $feature, string $prompt, ?string $model = null): array
    {
        $template = AiPrompt::where('code', $feature)->where('is_active', true)->first();

        $model = $model ?? $template?->model ?? 'openai/gpt-4o-mini';
        $temperature = $template?->temperature ?? 0.7;
        $maxTokens = $template?->max_tokens ?? 2048;

        $systemPrompt = $template?->system_prompt ?? $this->getDefaultSystemPrompt($feature);
        $userPrompt = $template
            ? str_replace('{prompt}', $prompt, $template->user_prompt_template)
            : $prompt;

        $result = $this->aiProvider->generate($systemPrompt, $userPrompt, $model, $maxTokens, $temperature);

        AiHistory::create([
            'user_id' => $user->id,
            'feature' => $feature,
            'prompt' => $userPrompt,
            'response' => $result['content'],
            'model' => $result['model'],
            'prompt_tokens' => $result['prompt_tokens'],
            'completion_tokens' => $result['completion_tokens'],
        ]);

        return $result;
    }

    private function getDefaultSystemPrompt(string $feature): string
    {
        return match ($feature) {
            'story' => 'Kamu adalah AI Wedding Assistant. Buatkan wedding story yang romantis dan personal berdasarkan informasi pengguna. Gunakan bahasa Indonesia. Format dalam paragraf yang menarik.',
            'invitation' => 'Kamu adalah AI Wedding Assistant. Buatkan konten undangan pernikahan yang elegan dan informatif. Gunakan bahasa Indonesia. Sertakan nama kedua mempelai, tanggal, waktu, dan lokasi.',
            'checklist' => 'Kamu adalah AI Wedding Assistant. Buatkan checklist persiapan pernikahan yang lengkap dan terorganisir. Gunakan bahasa Indonesia. Kelompokkan berdasarkan kategori (H-6, H-3, H-1, etc).',
            'budget' => 'Kamu adalah AI Wedding Assistant. Buatkan anggaran pernikahan yang detail dan realistis. Gunakan bahasa Indonesia. Kelompokkan berdasarkan kategori pengeluaran.',
            'timeline' => 'Kamu adalah AI Wedding Assistant. Buatkan timeline pernikahan yang terperinci dari H-6 bulan hingga hari H. Gunakan bahasa Indonesia.',
            'rundown' => 'Kamu adalah AI Wedding Assistant. Buatkan rundown acara pernikahan yang detail. Gunakan bahasa Indonesia. Sertakan waktu, durasi, dan deskripsi setiap sesi.',
            'caption' => 'Kamu adalah AI Wedding Assistant. Buatkan caption media sosial untuk konten pernikahan yang menarik dan engaging. Gunakan bahasa Indonesia. Sertakan hashtag yang relevan.',
            'vendor_recommendation' => 'Kamu adalah AI Wedding Assistant. Berikan rekomendasi vendor pernikahan berdasarkan kebutuhan pengguna. Gunakan bahasa Indonesia. Sertakan jenis vendor, kriteria, dan tips memilih.',
            default => 'Kamu adalah AI Wedding Assistant yang membantu persiapan pernikahan. Gunakan bahasa Indonesia. Berikan jawaban yang informatif dan membantu.',
        };
    }
}
