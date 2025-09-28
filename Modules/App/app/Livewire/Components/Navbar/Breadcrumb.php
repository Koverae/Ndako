<?php

namespace Modules\App\Livewire\Components\Navbar;

class Breadcrumb
{
    protected string $separator = '/';
    protected string $urlPrefix = '';
    protected bool   $skipIdLike = true;

    /** @var array<string,string> map of segment => label override */
    protected array $overrides = [];

    public function separator(string $separator): self {
        $this->separator = $separator;
        return $this;
    }

    public function urlPrefix(string $prefix): self {
        $this->urlPrefix = trim($prefix, '/');
        return $this;
    }

    /** e.g. ['ndako' => 'Ndako', 'erp' => 'ERP'] */
    public function overrides(array $map): self {
        $this->overrides = $map;
        return $this;
    }

    /** disable if you want numeric/UUID segments visible */
    public function skipIdLike(bool $skip = true): self {
        $this->skipIdLike = $skip;
        return $this;
    }

    /** Build breadcrumbs from current request */
    public function generate(): array
    {
        $segments = array_values(array_filter(request()->segments(), fn ($s) => $s !== null && $s !== ''));

        $isIdLike = function (string $seg): bool {
            if (ctype_digit($seg)) return true;
            return (bool) preg_match(
                '/^(?:[0-9a-f]{8}-?[0-9a-f]{4}-?[1-5][0-9a-f]{3}-?[89ab][0-9a-f]{3}-?[0-9a-f]{12}|[0-9A-HJKMNP-TV-Z]{26})$/i',
                $seg
            );
        };

        $labelFor = function (string $seg) {
            if (array_key_exists($seg, $this->overrides)) {
                return $this->overrides[$seg];
            }
            $decoded = rawurldecode($seg);
            $decoded = str_replace(['%20'], ' ', $decoded);
            $tidy = preg_replace('/[\-_]+/', ' ', $decoded) ?? $decoded;
            $tidy = preg_replace('/\s+/', ' ', $tidy) ?? $tidy;
            $tidy = trim($tidy);

            $words = explode(' ', $tidy);
            $words = array_map(function ($w) {
                return preg_match('/^[A-Z0-9]{2,}$/', $w) ? $w : mb_convert_case($w, MB_CASE_TITLE, "UTF-8");
            }, $words);
            return implode(' ', $words);
        };

        $prefix = $this->urlPrefix ? '/' . $this->urlPrefix : '';
        $accum = $prefix;
        $crumbs = [];

        foreach ($segments as $seg) {
            $accum .= '/' . $seg;

            if ($this->skipIdLike && $isIdLike($seg)) {
                // still grow the URL for next visible crumb
                continue;
            }

            $crumbs[] = [
                'url'   => url($accum),
                'label' => $labelFor($seg),
            ];
        }

        // de-dup adjacent
        $dedup = [];
        $lastUrl = null;
        foreach ($crumbs as $c) {
            if ($c['url'] !== $lastUrl) {
                $dedup[] = $c;
                $lastUrl = $c['url'];
            }
        }

        return $dedup;
    }
}
