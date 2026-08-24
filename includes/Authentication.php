<?php

class Authentication {
    private array $authorizedClasses;
    
    public function __construct(array $authorizedClasses) {
        $this->authorizedClasses = $authorizedClasses;
    }
    
    public function isAuthorized($userClass): bool {
        return in_array($userClass, $this->authorizedClasses);
    }
    
    public function redirectIfNotAuthorized($userClass, string $redirectUrl): void {
        if (!$this->isAuthorized($userClass) || !isset($userClass)) {
            header("Location: $redirectUrl");
            exit();
        }
    }
    
    public function allowNoLowerLimit($userClass): bool {
        return $this->isAuthorized($userClass);
    }
}
