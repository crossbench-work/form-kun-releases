<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\View;
use Models\Submission;

class DashboardController
{
    public function index(): void
    {
        Auth::requireLogin();

        $recent = Submission::recent(10);
        $byForm = Submission::countsByForm();

        (new View())->render('admin/dashboard', [
            'title'  => 'ダッシュボード',
            'recent' => $recent,
            'byForm' => $byForm,
        ], 'admin');
    }
}
