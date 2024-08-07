<?php

namespace Erfanshk\LaravelDigikala;


use Erfanshk\LaravelDigikala\Enums\DigikalaRoutes;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class DigikalaSeller
{
    private string $apiToken;
    private string $baseApiRoute = 'https://seller.digikala.com/api/v1/';
    private Client $client;

    /**
     * @throws \Exception
     */
    public function __construct(string $apiToken = null, string $baseApiRoute = null)
    {
        $this->apiToken = $apiToken ?: config('digikala.seller.apiKey');
        $this->baseApiRoute = $baseApiRoute ?: $this->baseApiRoute;
        if (!$this->apiToken) throw new \Exception('Digikala Seller API Key not provided!');

        $this->createClient();
    }

    private function createClient(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseApiRoute,
            'headers' => [
                'Authorization' => $this->apiToken
            ],
            'verify' => false,
        ]);

    }

    private function decode(ResponseInterface $request): array
    {
        return json_decode($request->getBody()->getContents(), true);
    }

    public static function orders($page = null): array
    {
        $self = new self();
        $response = $self->decode(
            $self->client->request('GET', DigikalaRoutes::LIST_ORDERS->value . !empty($page) ? '?page=' . $page : '')
        );
        return $response['data'];
    }

    public static function getOrder(string $id): array
    {
        $self = new self();
        $response = $self->decode(
            $self->client->request('GET', DigikalaRoutes::LIST_ORDERS->value . $id . '/')
        );
        return $response['data'];
    }

    public static function variants($page = null): array
    {
        $self = new self();
        $response = $self->decode(
            $self->client->request('GET', DigikalaRoutes::LIST_VARIANTS->value . (!empty($page) ? '?page=' . $page : ''))
        );
        return $response['data'];
    }

    public static function getActiveVariants($page = null): array
    {
        $self = new self();
        $response = $self->decode(
            $self->client->request('GET', DigikalaRoutes::LIST_VARIANTS->value .
                '?search[is_active]=true' . (!empty($page) ? '&page=' . $page : ''))
        );
        return $response['data'];
    }

    public static function getVariant(string $id): array
    {
        $self = new self();
        $response = $self->decode(
            $self->client->request('GET', DigikalaRoutes::LIST_VARIANTS->value . $id . '/')
        );
        return $response['data'];
    }

    public static function updateVariant(string $id, array $data): array
    {
        $self = new self();
        $response = $self->decode(
            $self->client->request('PUT', DigikalaRoutes::LIST_VARIANTS->value . $id . '/', [], $data)
        );
        return $response['data'];
    }

    public static function updateVariantStock(string $id, string $stock): array
    {
        return self::updateVariant($id, [
            'stock' => $stock
        ]);
    }

    public static function updateVariantPrice(string $id, string $price): array
    {
        return self::updateVariant($id, [
            'price' => $price
        ]);
    }
}
