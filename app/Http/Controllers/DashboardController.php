<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\DispositionRepositoryInterface;
use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Models\Letter;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
        private readonly DispositionRepositoryInterface $dispositionRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function index()
    {
        $user = Auth::user();

        // High Level Metrics via Repository
        $counts = $this->letterRepository->getCounts();
        $totalSurat = $counts['total'];
        $suratHariIni = $counts['today'];
        $suratBaru = $counts['baru'];
        $suratDiproses = $counts['diproses'];
        $suratSelesai = $counts['selesai'];

        // Dispositions via Repository
        $myPendingDispositions = $this->dispositionRepository->getPendingDispositionsForRecipient($user->id);
        $sentDispositions = $this->dispositionRepository->getSentDispositionsForSender($user->id, 5);

        // Recent Letters & Audits
        $recentLetters = $this->letterRepository->getLatestLetters(6);
        $recentAudits = $this->auditLogRepository->getLatestAudits(5);

        // Notifications
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->get();

        // Monthly Breakdown Chart Data (Driver-agnostic)
        $driver = DB::connection()->getDriverName();
        $dateFormatSql = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at) as month_year"
            : "DATE_FORMAT(created_at, '%Y-%m') as month_year";

        $monthlyStats = Letter::select(
                DB::raw($dateFormatSql),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc')
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'totalSurat',
            'suratHariIni',
            'suratBaru',
            'suratDiproses',
            'suratSelesai',
            'myPendingDispositions',
            'sentDispositions',
            'recentLetters',
            'recentAudits',
            'unreadNotifications',
            'monthlyStats'
        ));
    }
}
