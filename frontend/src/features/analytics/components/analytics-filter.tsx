'use client';

import { useState } from 'react';

interface AnalyticsFilterProps {
  onApply: (filters: { start_date?: string; end_date?: string }) => void;
}

export function AnalyticsFilter({ onApply }: AnalyticsFilterProps) {
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');

  const handleApply = () => {
    const filters: { start_date?: string; end_date?: string } = {};
    if (startDate) filters.start_date = startDate;
    if (endDate) filters.end_date = endDate;
    onApply(filters);
  };

  const handleReset = () => {
    setStartDate('');
    setEndDate('');
    onApply({});
  };

  return (
    <div className="flex items-center gap-3 flex-wrap">
      <div>
        <label className="block text-xs text-gray-500 mb-1">Start Date</label>
        <input
          type="date"
          value={startDate}
          onChange={(e) => setStartDate(e.target.value)}
          className="border rounded px-2 py-1.5 text-sm"
        />
      </div>
      <div>
        <label className="block text-xs text-gray-500 mb-1">End Date</label>
        <input
          type="date"
          value={endDate}
          onChange={(e) => setEndDate(e.target.value)}
          className="border rounded px-2 py-1.5 text-sm"
        />
      </div>
      <button
        onClick={handleApply}
        className="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 mt-5"
      >
        Apply
      </button>
      <button
        onClick={handleReset}
        className="px-3 py-1.5 border text-sm rounded hover:bg-gray-50 mt-5"
      >
        Reset
      </button>
    </div>
  );
}
