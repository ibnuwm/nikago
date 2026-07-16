'use client';

import { Sparkles } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface AiGenerateButtonProps {
  onGenerate: () => void;
  isLoading: boolean;
}

export function AiGenerateButton({ onGenerate, isLoading }: AiGenerateButtonProps) {
  return (
    <Button
      type="button"
      onClick={onGenerate}
      disabled={isLoading}
      className="gap-2"
    >
      <Sparkles className={`h-4 w-4 ${isLoading ? 'animate-pulse' : ''}`} />
      {isLoading ? 'Generating...' : 'AI Generate Timeline'}
    </Button>
  );
}
