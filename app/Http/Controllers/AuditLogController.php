<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function index(Request $request)
    {
        $logs = $this->auditLogRepository->getPaginatedLogs($request->all(), 20);
        $modules = $this->auditLogRepository->getDistinctModules();

        return view('audit_logs.index', compact('logs', 'modules'));
    }
}
