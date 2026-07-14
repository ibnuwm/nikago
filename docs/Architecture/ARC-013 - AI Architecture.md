# ARC-013 - AI Architecture

## Tujuan

Menjelaskan arsitektur AI Nikago.

---

## Provider

OpenRouter

---

## Pattern

Application

↓

AI Service

↓

AI Provider Interface

↓

OpenRouter Adapter

↓

LLM

---

## Abstraction Layer

interface AIProvider

↓

OpenRouterProvider

Future

OpenAIProvider

AnthropicProvider

GeminiProvider

DeepSeekProvider

---

## AI Feature

- Wedding Story
- Invitation Content
- Checklist
- Budget
- Timeline
- Rundown
- Vendor Recommendation
- AI Chat

---

## Logging

Semua request AI disimpan.

---

## Rules

- Tidak memanggil provider secara langsung.
- Semua provider menggunakan interface.
- Prompt dipisahkan dari kode.

---

## Future

- AI Memory
- MCP
- Multi Provider
- AI Agent
