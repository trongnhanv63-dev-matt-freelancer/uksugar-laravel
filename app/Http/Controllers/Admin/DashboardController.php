<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService; // 1. Import the new service

class DashboardController extends Controller
{
    /**
     * The dashboard service instance.
     */
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 2. Call the service to get all data
        $statistics = $this->dashboardService->getDashboardStatistics();

        // 3. Pass all data to the view using the spread operator
        return view('admin.dashboard', [...$statistics]);
    }
}
