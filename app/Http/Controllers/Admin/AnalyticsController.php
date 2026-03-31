<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Analytics\AnalyticsReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request, AnalyticsReport $analyticsReport): View
    {
        $days = $this->resolveDays($request);
        $analytics = $analyticsReport->build($days);

        return view('admin.analytics.index', [
            'selectedDays' => $days,
            'periodOptions' => [7, 30, 90],
            ...$analytics,
        ]);
    }

    public function export(Request $request, AnalyticsReport $analyticsReport): StreamedResponse
    {
        $days = $this->resolveDays($request);
        $rows = $analyticsReport->exportRows($days);
        $filename = "phhaiyan-analytics-{$days}d.csv";

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Path', 'Page Label', 'Device', 'Referrer', 'Views', 'Unique Visitors']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->path,
                    $row->page_label,
                    $row->device_type,
                    $row->referrer_host,
                    $row->views,
                    $row->visitors,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function resolveDays(Request $request): int
    {
        return match ((int) $request->integer('days', 30)) {
            7, 30, 90 => (int) $request->integer('days', 30),
            default => 30,
        };
    }
}
