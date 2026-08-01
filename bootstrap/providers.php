<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    Modules\Auth\Providers\AuthServiceProvider::class,
    Modules\Rbac\Providers\RbacServiceProvider::class,
    Modules\Catalog\Providers\CatalogServiceProvider::class,
    Modules\Learning\Providers\LearningServiceProvider::class,
    Modules\Teaching\Providers\TeachingServiceProvider::class,
    Modules\Quizzes\Providers\QuizzesServiceProvider::class,
    Modules\Media\Providers\MediaServiceProvider::class,
    Modules\Support\Providers\SupportServiceProvider::class,
    Modules\Finance\Providers\FinanceServiceProvider::class,
    Modules\Marketing\Providers\MarketingServiceProvider::class,
    Modules\Team\Providers\TeamServiceProvider::class,
    Modules\Notifications\Providers\NotificationsServiceProvider::class,
    Modules\Reports\Providers\ReportsServiceProvider::class,
    Modules\Admin\Providers\AdminServiceProvider::class,
    Modules\Students\Providers\StudentsServiceProvider::class,
    Modules\Settings\Providers\SettingsServiceProvider::class,
];
