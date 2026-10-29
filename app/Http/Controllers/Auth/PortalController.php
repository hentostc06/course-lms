<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Identity\Services\AccessPortal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

// COURSE_SINGLE_LOGIN_V1
class PortalController extends Controller
{
    public function __invoke(Request $request, AccessPortal $portal)
    {
        $target = $portal->home($request->user());

        return $target === '/dashboard'
            ? Inertia::render('Dashboard')
            : redirect($target);
    }
}
