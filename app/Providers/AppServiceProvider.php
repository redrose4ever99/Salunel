<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Dusk\DuskServiceProvider;

use App\Models\SalonService;
use App\Models\SalonBooking;
use App\Models\SlonWorkingHours;
use App\Models\SalonDays;

use Carbon\Carbon;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
		// Additional code to fix php artisan migrate error for (unique key too long on certain systems)
        Schema::defaultStringLength(191);
       

    }

    /**
     * Register any application services.
     *
     * @return void
     */
   
}
