<?

declare(strict_types=1);

class GoogleWrapperImpl implements GoogleWrapper {
    private $google;
    
    public function __construct(Google_Client $google) {
        $this->google = $google;
    }

    public function getEmail(string $token) : ?string {
        $payload = $this->google->verifyIdToken($token);

        if ($payload) {
            $email = $payload['email'];
            $verified = $payload['email_verified'];

            if ($verified) {
                return $email;
            } else {
                error_log('Email verification failed: ' . json_encode($payload));
                return null;
            }
        } else {
            error_log('Verification failed for token: ' . $token);
            return null;
        }
    }
}