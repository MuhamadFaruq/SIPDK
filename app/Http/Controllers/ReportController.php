<?php

namespace App\Http\Controllers;

use App\Models\LetterCategory;
use App\Models\User;
use App\UseCases\Report\ExportReportCsvUseCase;
use App\UseCases\Report\GenerateReportUseCase;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, GenerateReportUseCase $useCase)
    {
        $startDate = $request->get('date_start', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('date_end', now()->endOfMonth()->format('Y-m-d'));
        $categoryId = $request->filled('category_id') ? (int) $request->category_id : null;
        $status = $request->get('status');

        $reportData = $useCase->execute($startDate, $endDate, $categoryId, $status);
        $categories = LetterCategory::all();

        return view('reports.index', array_merge($reportData, [
            'categories' => $categories,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'categoryId' => $categoryId,
            'status' => $status,
        ]));
    }

    public function exportCsv(Request $request, ExportReportCsvUseCase $useCase)
    {
        $startDate = $request->get('date_start', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('date_end', now()->endOfMonth()->format('Y-m-d'));

        return $useCase->execute($startDate, $endDate);
    }

    public function printReport(Request $request, GenerateReportUseCase $useCase)
    {
        $startDate = $request->get('date_start', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('date_end', now()->endOfMonth()->format('Y-m-d'));

        $reportData = $useCase->execute($startDate, $endDate);
        $letters = $reportData['letters'];

        $lurahUser = User::whereHas('role', function ($q) {
            $q->where('name', 'pimpinan');
        })->orderBy('id', 'asc')->first(); // Pimpinan pertama (biasanya Lurah)

        return view('reports.print', compact('letters', 'startDate', 'endDate', 'lurahUser'));
    }
}
