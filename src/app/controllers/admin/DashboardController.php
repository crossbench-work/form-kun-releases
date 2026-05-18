<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\View;
use Models\Form;
use Models\Submission;

class DashboardController
{
    public function index(): void
    {
        Auth::requireLogin();

        $recent = Submission::recent(10);
        $byForm = Submission::countsByForm();
        $formCount = Form::count();
        $maxForms = effective_max_forms();

        (new View())->render('admin/dashboard', [
            'title'         => 'ダッシュボード',
            'recent'        => $recent,
            'byForm'        => $byForm,
            'form_count'    => $formCount,
            'max_forms'     => $maxForms,
            'at_form_limit' => $formCount >= $maxForms,
        ], 'admin');
    }
}
