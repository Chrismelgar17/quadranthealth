<?php


namespace App\Services;

class DecodingService {
    
    /**
     * Receives a response and slices it.
     *
     * @param string $response The response to be sliced.
     * @param int $start The starting position for the slice.
     * @param int $length The length of the slice.
     * @return string The sliced response.
     */
    public function decodeResponse($response) {
        $decodedResponse = json_decode($response);

        if (!isset($decodedResponse[0]->transcript)) {
            return null;
        }
        
        $transcript = $decodedResponse[0]->transcript;

        $split = explode("\n", $transcript);

        $pieces = [];
        foreach ($split as $line) {
            if (strpos($line, 'User:') !== false) {
            $pieces[] = $line;
            }
        }

        return $pieces;
    }
}

