<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const PUBLIC_UPLOAD_PATH = "app/public/uploads/";
    const FORBIDDEN_ADMIN_TEAM_ACTION = "Sorry, you are forbidden from performing this action. Only team members who belong to unique teams can create, update or delete this resource. Please try switching team or contact IT support.";
    const TRY_ANOTHER_ROUTE = "Please try another route.";
    const TRY_CATCH_FORM_SUBMISSION_ERROR = "Oops! Something went wrong with your form submission. Please try again.";
}
