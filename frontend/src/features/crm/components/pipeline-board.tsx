'use client';

import type { Pipeline } from '@/types';
import { useMoveStage } from '@/features/crm/hooks/use-crm';

interface PipelineBoardProps {
  pipelines: Pipeline[];
}

const stageColors: Record<string, string> = {
  new: 'border-blue-400 bg-blue-50',
  contacted: 'border-yellow-400 bg-yellow-50',
  negotiation: 'border-purple-400 bg-purple-50',
  won: 'border-green-400 bg-green-50',
  lost: 'border-red-400 bg-red-50',
};

const stageLabelColors: Record<string, string> = {
  new: 'text-blue-700',
  contacted: 'text-yellow-700',
  negotiation: 'text-purple-700',
  won: 'text-green-700',
  lost: 'text-red-700',
};

function formatCurrency(amount: number): string {
  return `Rp${amount.toLocaleString('id-ID')}`;
}

export function PipelineBoard({ pipelines }: PipelineBoardProps) {
  return (
    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
      {pipelines.map((pipeline) => (
        <div
          key={pipeline.id}
          className={`rounded-lg border-l-4 p-4 ${stageColors[pipeline.id] ?? 'border-gray-400 bg-gray-50'}`}
        >
          <p className={`text-sm font-semibold ${stageLabelColors[pipeline.id] ?? 'text-gray-700'}`}>
            {pipeline.label}
          </p>
          <p className="mt-2 text-2xl font-bold text-card-foreground">{pipeline.count}</p>
          <p className="mt-1 text-xs text-muted-foreground">
            {formatCurrency(pipeline.value)}
          </p>
        </div>
      ))}
    </div>
  );
}
