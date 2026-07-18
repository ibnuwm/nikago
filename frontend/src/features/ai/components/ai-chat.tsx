'use client';

import { useState, useRef, useEffect } from 'react';
import { useAiChat } from '@/features/ai/hooks/use-ai';
import type { AiMessage } from '@/types';

export function AiChat() {
  const [messages, setMessages] = useState<AiMessage[]>([
    { role: 'system', content: 'Halo! Saya AI Wedding Assistant. Saya bisa membantu Anda membuat story, undangan, checklist, budget, timeline, rundown, caption, dan rekomendasi vendor. Ada yang bisa saya bantu?' },
  ]);
  const [input, setInput] = useState('');
  const chatMutation = useAiChat();
  const messagesEndRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  const handleSend = async () => {
    if (!input.trim() || chatMutation.isPending) return;

    const userMessage: AiMessage = { role: 'user', content: input.trim() };
    const updatedMessages = [...messages.filter((m) => m.role !== 'system'), userMessage];
    setMessages((prev) => [...prev, userMessage]);
    setInput('');

    try {
      const result = await chatMutation.mutateAsync({ messages: updatedMessages });
      setMessages((prev) => [...prev, { role: 'assistant', content: result.content }]);
    } catch {
      setMessages((prev) => [...prev, { role: 'assistant', content: 'Maaf, terjadi kesalahan. Silakan coba lagi.' }]);
    }
  };

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      handleSend();
    }
  };

  return (
    <div className="flex flex-col h-[600px] border rounded-lg bg-white shadow-sm">
      <div className="px-4 py-3 border-b bg-gray-50 rounded-t-lg">
        <h3 className="font-semibold text-gray-900">AI Wedding Assistant</h3>
        <p className="text-sm text-gray-500">Tanyakan apapun tentang persiapan pernikahan Anda</p>
      </div>

      <div className="flex-1 overflow-y-auto p-4 space-y-4">
        {messages.map((msg, i) => (
          <div key={i} className={`flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}`}>
            <div
              className={`max-w-[80%] rounded-lg px-4 py-2 ${
                msg.role === 'user'
                  ? 'bg-blue-600 text-white'
                  : msg.role === 'system'
                    ? 'bg-gray-100 text-gray-600 italic'
                    : 'bg-gray-100 text-gray-900'
              }`}
            >
              <p className="whitespace-pre-wrap text-sm">{msg.content}</p>
            </div>
          </div>
        ))}
        {chatMutation.isPending && (
          <div className="flex justify-start">
            <div className="bg-gray-100 rounded-lg px-4 py-2">
              <div className="flex space-x-1">
                <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" />
                <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: '0.1s' }} />
                <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: '0.2s' }} />
              </div>
            </div>
          </div>
        )}
        <div ref={messagesEndRef} />
      </div>

      <div className="border-t p-4">
        <div className="flex space-x-2">
          <textarea
            value={input}
            onChange={(e) => setInput(e.target.value)}
            onKeyDown={handleKeyDown}
            placeholder="Ketik pesan Anda..."
            rows={2}
            className="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
            disabled={chatMutation.isPending}
          />
          <button
            onClick={handleSend}
            disabled={!input.trim() || chatMutation.isPending}
            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed self-end"
          >
            Kirim
          </button>
        </div>
      </div>
    </div>
  );
}
