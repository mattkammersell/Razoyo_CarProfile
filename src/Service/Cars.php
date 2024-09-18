<?php

namespace Razoyo\CarProfile\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\Session as CustomerSession;

class Cars
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CarService constructor.
     *
     * @param Curl $curl
     * @param LoggerInterface $logger
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Curl $curl,
        LoggerInterface $logger,
        CustomerSession $customerSession
    ) {
        $this->logger = $logger;
        $this->curl = $curl;
        $this->customerSession = $customerSession;
        $this->getCars();
    }

    /**
     * Fetch the list of cars from the API and store the "your-token" header in the session if present.
     *
     * @return array
     * @throws LocalizedException
     */
    public function getCars(): array
    {
        $apiUrl = 'https://exam.razoyo.com/api/cars';

        try {
            // Initialize curl and set options
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->logger->info('Making API request to: ' . $apiUrl);
            $this->curl->get($apiUrl);

            // Get response and headers
            $response = $this->curl->getBody();
            $responseHeaders = $this->curl->getHeaders();

            // Log request details
            $this->logger->info('API Response: ' . $response);
            $this->logger->info('Response Headers: ' . print_r($responseHeaders, true));

            // Check if the "your-token" header exists and store it in the session
            if (isset($responseHeaders['your-token'])) {
                $this->customerSession->setYourToken($responseHeaders['your-token']);
                $this->logger->info('Token saved to session.');
            } else {
                $this->logger->warning('Token not found in response headers.');
            }

            // Decode JSON response to array
            $decodedResponse = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new LocalizedException(__('Unable to decode API response.'));
            }

            return $decodedResponse;
        } catch (Exception $e) {
            $this->logger->error('Error fetching cars: ' . $e->getMessage());
            throw new LocalizedException(__('Error fetching cars: %1', $e->getMessage()));
        }
    }

    /**
     * Fetch the car details from the API.
     *
     * @param string $carId
     * @return array
     * @throws LocalizedException
     */
    public function getCarDetails(string $carId): array
    {
        $apiUrl = 'https://exam.razoyo.com/api/cars/' . $carId;
        $token = $this->customerSession->getYourToken();

        if (!$token) {
            throw new LocalizedException(__('Authorization token is not available in the session.'));
        }

        try {
            // Initialize curl and set options
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->addHeader('Authorization', 'Bearer ' . $token);
            $this->curl->get($apiUrl);

            // Get response
            $response = $this->curl->getBody();

            // Decode JSON response to array
            $decodedResponse = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new LocalizedException(__('Unable to decode API response.'));
            }

            return $decodedResponse;
        } catch (Exception $e) {
            throw new LocalizedException(__('Error fetching car details: %1', $e->getMessage()));
        }
    }
}
