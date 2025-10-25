<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Session;
use App\Core\Response;
use App\Core\View;

class AdminMiddleware
{
public function handle(): void
{
$session = new Session();
$user = $session->get('user');

if (!$user || $user['role'] !== 'admin') {
(new Response())->setStatusCode(403);
View::render('errors.403');
exit();
}
}
}