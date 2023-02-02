<?php

namespace App\Providers;

use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        \App\Models\User::class => 'App\Admin\Sections\Users',
        \App\Models\Categories::class => 'App\Admin\Sections\Categories',
        \App\Models\Phrases::class => 'App\Admin\Sections\Phrases',
        \App\Models\DoublePhrases::class => 'App\Admin\Sections\DoublePhrases',
        \App\Models\DayPictures::class => 'App\Admin\Sections\DayPictures',
        \App\Models\Gallery::class => 'App\Admin\Sections\Gallery'
    ];

    /**
     * Register sections.
     *
     * @param \SleepingOwl\Admin\Admin $admin
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
    	//

        parent::boot($admin);
    }
}
