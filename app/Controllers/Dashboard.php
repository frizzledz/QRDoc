<?php

namespace App\Controllers;

use App\Models\QRCodeModel;

class Dashboard extends BaseController
{
	public function index()
	{
		$qrModel = new QRCodeModel();
		$userId = session()->get('user_id');
		$isAdmin = session()->get('role') === 'admin';

		// Get current month's stats
		$currentMonth = date('Y-m');
		$lastMonth = date('Y-m', strtotime('-1 month'));

		// Base query
		$baseQuery = $isAdmin ? $qrModel : $qrModel->where('user_id', $userId);

		// Calculate current month stats
		$totalQRCodes = $baseQuery->countAllResults();
		$verifiedQRCodes = $baseQuery->where('status', 'verified')->countAllResults();
		$pendingQRCodes = $baseQuery->where('status', 'pending')->countAllResults();

		// Calculate last month's stats for growth percentage
		$lastMonthTotal = $baseQuery->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)->countAllResults();
		$lastMonthVerified = $baseQuery->where('status', 'verified')
			->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)->countAllResults();
		$lastMonthPending = $baseQuery->where('status', 'pending')
			->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)->countAllResults();

		// Calculate growth percentages
		$totalGrowth = $lastMonthTotal ? round((($totalQRCodes - $lastMonthTotal) / $lastMonthTotal) * 100) : 0;
		$verifiedGrowth = $lastMonthVerified ? round((($verifiedQRCodes - $lastMonthVerified) / $lastMonthVerified) * 100) : 0;
		$pendingGrowth = $lastMonthPending ? round((($pendingQRCodes - $lastMonthPending) / $lastMonthPending) * 100) : 0;

		// Get recent activities
		$recentActivities = $baseQuery->orderBy('updated_at', 'DESC')
			->limit(5)
			->find();

		return view('dashboard', [
			'totalQRCodes' => $totalQRCodes,
			'verifiedQRCodes' => $verifiedQRCodes,
			'pendingQRCodes' => $pendingQRCodes,
			'totalGrowth' => $totalGrowth,
			'verifiedGrowth' => $verifiedGrowth,
			'pendingGrowth' => $pendingGrowth,
			'recentActivities' => $recentActivities
		]);
	}
}