<?php

namespace App\Services\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Policy;

class CustomPolicies extends Policy
{
    public function configure()
    {
        $this->setDefaultPolicies();
        $this->addGoogleFontPolicies();
    }

    protected function setDefaultPolicies()
    {
        return $this
            ->addDirective(Directive::IMG, 'self')
            ->addDirective(Directive::MEDIA, 'self')
            ->addDirective(Directive::OBJECT, 'self')
            ->addDirective(Directive::SCRIPT, 'self')
            ->addDirective(Directive::SCRIPT, 'unsafe-eval')
            ->addDirective(Directive::SCRIPT, 'unsafe-inline')
            ->addDirective(Directive::FONT, 'self')
            ->addDirective(Directive::STYLE, 'self')
            ->addDirective(Directive::STYLE, 'unsafe-inline');
    }
    private function addGoogleFontPolicies()
    {
        $this->addDirective(Directive::FONT, [
            'fonts.gstatic.com',
            'fonts.googleapis.com',
            'data:',
        ])
            ->addDirective(Directive::STYLE, 'fonts.googleapis.com');
    }
}
