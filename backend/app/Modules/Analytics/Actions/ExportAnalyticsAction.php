<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Actions;

use App\Modules\Analytics\Models\AnalyticsReport;
use Illuminate\Http\Request;

class ExportAnalyticsAction
{
    public function execute(Request $request): array
    {
        $type = $request->input('type', 'dashboard');
        $format = $request->input('format', 'csv');
        $filters = $request->only(['start_date', 'end_date']);

        $headers = $this->getHeaders($type);
        $data = $this->getData($type, $request);

        $report = AnalyticsReport::create([
            'type' => $type,
            'filters' => $filters,
            'format' => $format,
            'status' => 'completed',
            'user_id' => $request->user()->id,
        ]);

        return [
            'report_id' => $report->id,
            'type' => $type,
            'format' => $format,
            'headers' => $headers,
            'data' => $data,
            'status' => 'completed',
        ];
    }

    private function getHeaders(string $type): array
    {
        return match ($type) {
            'dashboard' => ['metric', 'value', 'period'],
            'invitations' => ['date', 'status', 'count'],
            'rsvp' => ['date', 'attendance', 'count'],
            'guests' => ['date', 'status', 'count'],
            'vendors' => ['date', 'city', 'count'],
            'subscriptions' => ['date', 'plan', 'count'],
            'revenue' => ['date', 'amount', 'transactions'],
            'traffic' => ['date', 'views', 'visitors'],
            'ai' => ['date', 'tokens', 'cost', 'requests'],
            default => ['date', 'value'],
        };
    }

    private function getData(string $type, Request $request): array
    {
        return match ($type) {
            'dashboard' => $this->getDashboardExport($request),
            'invitations' => $this->getInvitationExport($request),
            'rsvp' => $this->getRsvpExport($request),
            'guests' => $this->getGuestExport($request),
            'vendors' => $this->getVendorExport($request),
            'subscriptions' => $this->getSubscriptionExport($request),
            'revenue' => $this->getRevenueExport($request),
            'traffic' => $this->getTrafficExport($request),
            'ai' => $this->getAiExport($request),
            default => [],
        };
    }

    private function getDashboardExport(Request $request): array
    {
        $data = app(GetDashboardAnalyticsAction::class)->execute($request);

        return [
            ['Total Users', (string) $data['total_users'], ''],
            ['Active Users', (string) $data['active_users'], ''],
            ['Total Vendors', (string) $data['total_vendors'], ''],
            ['Total Revenue', (string) $data['total_revenue'], ''],
            ['MRR', (string) $data['mrr'], ''],
            ['Active Subscriptions', (string) $data['active_subscriptions'], ''],
        ];
    }

    private function getInvitationExport(Request $request): array
    {
        $data = app(GetInvitationAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            '',
            (string) $row['count'],
        ], $data['trend']);
    }

    private function getRsvpExport(Request $request): array
    {
        $data = app(GetRsvpAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            '',
            (string) $row['count'],
        ], $data['trend']);
    }

    private function getGuestExport(Request $request): array
    {
        $data = app(GetGuestAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            '',
            (string) $row['count'],
        ], $data['trend']);
    }

    private function getVendorExport(Request $request): array
    {
        $data = app(GetVendorAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            '',
            (string) $row['count'],
        ], $data['trend']);
    }

    private function getSubscriptionExport(Request $request): array
    {
        $data = app(GetSubscriptionAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            '',
            (string) $row['count'],
        ], $data['trend']);
    }

    private function getRevenueExport(Request $request): array
    {
        $data = app(GetRevenueAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            (string) $row['revenue'],
            (string) $row['transactions'],
        ], $data['daily']);
    }

    private function getTrafficExport(Request $request): array
    {
        $data = app(GetTrafficAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            (string) $row['views'],
            (string) $row['visitors'],
        ], $data['daily']);
    }

    private function getAiExport(Request $request): array
    {
        $data = app(GetAiAnalyticsAction::class)->execute($request);

        return array_map(fn (array $row): array => [
            $row['date'],
            (string) $row['tokens'],
            (string) $row['cost'],
            (string) $row['count'],
        ], $data['daily']);
    }
}
