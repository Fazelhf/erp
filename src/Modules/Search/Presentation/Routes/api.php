<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Search\Presentation\Http\Controllers\Api\V1\SearchController;

Route::get('search', SearchController::class);
