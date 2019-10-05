<?php namespace Ivacuum\Generic\Utilities;

class EnvironmentForCss
{
    protected $userAgent;

    public function __construct(?string $userAgent)
    {
        $this->userAgent = mb_strtolower($userAgent);
    }

    public function __toString(): string
    {
        return implode(' ', array_merge(
            $this->browserClasses(),
            $this->mobileOrDesktopClasses(),
            $this->operatingSystemClasses()
        ));
    }

    public function browserClasses(): array
    {
        if (preg_match('/msie|trident/', $this->userAgent) && !preg_match('/opera/', $this->userAgent)) {
            return ['ie'];
        } elseif (preg_match('/edge/', $this->userAgent)) {
            return ['edge'];
        } elseif (preg_match('/firefox/', $this->userAgent)) {
            return ['firefox'];
        } elseif (preg_match('/safari/', $this->userAgent) && !preg_match('/chrome/', $this->userAgent)) {
            return ['safari'];
        } elseif (preg_match('/opera|opr/', $this->userAgent)) {
            return ['opera'];
        } elseif (preg_match('/chrome/', $this->userAgent)) {
            return ['chrome'];
        }

        return [];
    }

    public function isCrawler(): bool
    {
        return preg_match('/(bot|crawler|google|spider)/i', $this->userAgent);
    }

    public function isMobile(): bool
    {
        return preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone|Opera Mini/i', $this->userAgent);
    }

    public function mobileOrDesktopClasses(): array
    {
        return $this->isMobile() ? ['is-mobile'] : ['is-desktop'];
    }

    public function operatingSystemClasses(): array
    {
        if (preg_match('/win/', $this->userAgent)) {
            return ['windows'];
        } elseif (preg_match('/iphone|ipad|ipod/', $this->userAgent)) {
            return ['ios'];
        } elseif (preg_match('/mac/', $this->userAgent)) {
            return ['macos'];
        } elseif (preg_match('/linux/', $this->userAgent)) {
            return ['linux'];
        } elseif (preg_match('/android/', $this->userAgent)) {
            return ['android'];
        }

        return [];
    }
}
