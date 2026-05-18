<?php
namespace Controllers;

use Core\View;
use Models\Form;

class HomeController
{
    public function index(): void
    {
        $forms = array_values(array_filter(Form::all(), fn($f) => $f['status'] === 'active'));
        (new View())->render('front/home', [
            'forms' => $forms,
            'title' => 'フォーム一覧',
        ], 'public');
    }
}
