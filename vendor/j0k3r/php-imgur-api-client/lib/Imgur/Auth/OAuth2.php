<?php

namespace Imgur\Auth;

use Imgur\Exception\AuthException;
use Imgur\HttpClient\HttpClientInterface;

/**
 * Authentication class used for handling OAuth2.
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class OAuth2 implements AuthInterface
{
    public const AUTHORIZATION_ENDPOINT = 'https://api.imgur.com/oauth2/authorize';
    public const ACCESS_TOKEN_ENDPOINT = 'https://api.imgur.com/oauth2/token';

    /**
     * Indicates the client that is making the request.
     *
     * @var string
     */
    private $clientId;

    /**
     * The client_secret for the application.
     *
     * @var string
     */
    private $clientSecret;

    /**
     * The class handling communication with Imgur servers.
     *
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * The access token and refresh token values, with keys:.
     *
     * For "token":
     *     - access_token
     *     - expires_in
     *     - token_type
     *     - refresh_token
     *     - account_username
     *     - account_id
     *
     * For "code":
     *     - code
     *     - state
     *
     * For "pin":
     *     - pin
     *     - state
     *
     * @var array
     */
    private $token;

    /**
     * Instantiates the OAuth2 class, but does not trigger the authentication process.
     */
    public function __construct(HttpClientInterface $httpClient, string $clientId, string $clientSecret)
    {
        $this->httpClient = $httpClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Generates the authentication URL to which a user should be pointed at in order to start the OAuth2 process.
     */
    public function getAuthenticationURL(string $responseType = 'code', string $state = null): string
    {
        $httpQueryParameters = [
            'client_id' => $this->clientId,
            'response_type' => $responseType,
            'state' => $state,
        ];

        $httpQueryParameters = http_build_query($httpQueryParameters);

        return self::AUTHORIZATION_ENDPOINT . '?' . $httpQueryParameters;
    }

    /**
     * Exchanges a code/pin for an access token.
     */
    public function requestAccessToken(string $code, string $requestType = null): array
    {
        switch ($requestType) {
            case 'pin':
                $grantType = 'pin';
                $type = 'pin';
                break;
            case 'code':
            default:
                $grantType = 'authorization_code';
                $type = 'code';
        }

        try {
            $response = $this->httpClient->post(
                self::ACCESS_TOKEN_ENDPOINT,
                [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => $grantType,
                    $type => $code,
                ]
            );

            $responseBody = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new AuthException('Request for access token failed: ' . $e->getMessage(), $e->getCode());
        }

        $responseBody['created_at'] = time();
        $this->setAccessToken($responseBody);

        $this->sign();

        return $responseBody;
    }

    /**
     * If a user has authorized their account but you no longer have a valid access_token for them,
     * then a new one can be generated by using the refresh_token.
     * When your application receives a refresh token, it is important to store that refresh token for future use.
     * If your application loses the refresh token,
     * you will have to prompt the user for their login information again.
     *
     * @throws AuthException
     */
    public function refreshToken(): array
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->httpClient->post(
                self::ACCESS_TOKEN_ENDPOINT,
                [
                    'refresh_token' => \is_array($token) ? $token['refresh_token'] : null,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'refresh_token',
                ]
            );

            $responseBody = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new AuthException('Request for refresh access token failed: ' . $e->getMessage(), $e->getCode());
        }

        $this->setAccessToken($responseBody);

        $this->sign();

        return $responseBody;
    }

    /**
     * Stores the access token, refresh token and expiration date.
     *
     * @throws AuthException
     */
    public function setAccessToken(array $token = null): void
    {
        if (!\is_array($token)) {
            throw new AuthException('Token is not a valid json string.');
        }

        if (isset($token['data']['access_token'])) {
            $token = $token['data'];
        }

        if (!isset($token['access_token'])) {
            throw new AuthException('Access token could not be retrieved from the decoded json response.');
        }

        $this->token = $token;

        $this->sign();
    }

    /**
     * Getter for the current access token.
     */
    public function getAccessToken(): ?array
    {
        return $this->token;
    }

    /**
     * Check if the current access token (if present), is still usable.
     */
    public function checkAccessTokenExpired(): bool
    {
        // don't have the data? Let's assume the token has expired
        if (!isset($this->token['created_at']) || !isset($this->token['expires_in'])) {
            return true;
        }

        return ((int) ($this->token['created_at'] + $this->token['expires_in'])) < time();
    }

    /**
     * Add middleware for attaching header signature to each request.
     */
    public function sign(): void
    {
        $this->httpClient->addAuthMiddleware(
            $this->getAccessToken(),
            $this->clientId
        );
    }
}
