<?php
namespace App\Providers;

use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		//
	}

	public function boot(): void
	{
		// Set application locale from session (simple i18n support)
		if ($locale = Session::get('locale')) {
			AppFacade::setLocale($locale);
		}
	}
}
