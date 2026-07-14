# DB-014 - AI

## Tujuan

Mengelola seluruh fitur Artificial Intelligence pada Nikago.

---

## Daftar Tabel

- ai_providers
- ai_models
- ai_conversations
- ai_messages
- ai_prompts
- ai_prompt_versions
- ai_usages
- ai_tokens

---

# Table : ai_providers

Master AI Provider.

Contoh

- OpenRouter
- OpenAI
- Anthropic
- Google

---

# Table : ai_models

Master Model AI.

Contoh

- GPT-5
- Claude
- Gemini
- DeepSeek

---

# Table : ai_conversations

Percakapan AI.

---

# Table : ai_messages

Isi chat AI.

---

# Table : ai_prompts

Prompt System.

---

# Table : ai_prompt_versions

Versi Prompt.

---

# Table : ai_usages

## Deskripsi

Mencatat seluruh penggunaan AI.

| Column           | Type          | Nullable | Index  | Keterangan                        |
| ---------------- | ------------- | -------- | ------ | --------------------------------- |
| id               | BIGINT        | No       | PK     |                                   |
| uuid             | UUID          | No       | UNIQUE |                                   |
| tenant_id        | BIGINT        | No       | INDEX  | Tenant                            |
| user_id          | BIGINT        | No       | FK     | User                              |
| provider_id      | BIGINT        | No       | FK     | Provider                          |
| model_id         | BIGINT        | No       | FK     | Model                             |
| feature          | VARCHAR(100)  | No       | INDEX  | Wedding Story / Caption / AI Chat |
| input_tokens     | INT           | No       |        |                                   |
| output_tokens    | INT           | No       |        |                                   |
| total_tokens     | INT           | No       |        |                                   |
| estimated_cost   | DECIMAL(18,6) | Yes      |        | Biaya                             |
| response_time_ms | INT           | Yes      |        | Latency                           |
| created_at       | TIMESTAMP     | No       |        |                                   |

---

# Table : ai_tokens

Quota AI pengguna.

---

## Business Rules

- Semua request AI dicatat.
- Prompt dapat memiliki banyak versi.
- AI Usage menjadi dasar billing.
- Quota AI dihitung per Subscription.

---

## Future Improvement

- AI Memory
- MCP
- AI Agent
- AI Workflow
