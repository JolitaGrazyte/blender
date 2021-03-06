<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * @param int $statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get the current statuscode.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Send a response with a internal server error code.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\Response
     */
    public function respondWithInternalServerError($message = 'Internal Server Error')
    {
        return $this
            ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($message);
    }

    /**
     * Send a response with a bad request.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\Response
     */
    public function respondWithBadRequest($message = 'Bad Request')
    {
        return $this
            ->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->respondWithError($message);
    }

    /**
     * Set a response.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Respond with an error message.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        return $this->respond(
            [
                'error' => $message,
                'status_code' => $this->getStatusCode(),
            ]
        );
    }
}
