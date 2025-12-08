<?php

// Add this to AppServiceProvider boot method
Route::macro('breadcrumb', function ($name, $title = null) {
    $title = $title ?: ucfirst($name);
    
    return $this->name($name)->setBreadcrumb($title);
});

// Custom Route class with breadcrumb support
if (!class_exists('BreadcrumbRoute')) {
    class BreadcrumbRoute extends Illuminate\Routing\Route
    {
        protected $breadcrumb;

        public function setBreadcrumb($breadcrumb)
        {
            $this->breadcrumb = $breadcrumb;
            return $this;
        }

        public function getBreadcrumb()
        {
            return $this->breadcrumb;
        }
    }
}
