<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Report;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;

class ReportController extends ApiController
{
    public function __construct(private ReportRepository $reports) {}

    public function index(Request $request)
    {
        return $this->ok($this->reports->paginatedForUser($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $report = $this->reports->createFor($request->user(), $data);

        return $this->ok($report, 'Created', 201);
    }

    public function show(Report $report)
    {
        return $this->ok($report);
    }

    public function destroy(Report $report)
    {
        $this->reports->delete($report);

        return $this->ok(null, 'Deleted');
    }
}
