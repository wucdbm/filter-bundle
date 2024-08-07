<?php

/*
 * This file is part of the WucdbmFilterBundle package.
 *
 * Copyright (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * Author Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\WucdbmFilterBundle\Controller;

use JsonException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wucdbm\Bundle\WucdbmFilterBundle\Controller\Exception\DecodeRequestException;
use Wucdbm\Bundle\WucdbmFilterBundle\Error\Error;
use Wucdbm\Bundle\WucdbmFilterBundle\Error\ErrorInterface;
use Wucdbm\Bundle\WucdbmFilterBundle\Filter\AbstractFilter;
use Wucdbm\Bundle\WucdbmFilterBundle\Helper\FormHelper;

trait FilterControllerTrait {

    private function error(
        string $message, string $code = null, int $statusCode = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return $this->errorResponse(
            [$message], $code ?? 'ERROR_GENERAL', [], $statusCode
        );
    }

    private function unauthorized(
        string $message = 'Unauthorized', array $customData = []
    ): JsonResponse {
        return $this->errorResponse(
            [$message], 'ERROR_UNAUTHORIZED', $customData, Response::HTTP_UNAUTHORIZED
        );
    }

    private function notFound(): JsonResponse {
        return $this->errorResponse(
            [], 'ERROR_NOT_FOUND', [], Response::HTTP_NOT_FOUND
        );
    }

    private function formError(FormInterface $form): JsonResponse {
        return $this->errorResponse($form->getErrors(true, true), 'ERROR_VALIDATION');
    }

    private function formErrorAtPath(string $error, string $path): JsonResponse {
        return $this->errorResponse([new Error($error, $path)], 'ERROR_VALIDATION');
    }

    private function errorResponse(
        iterable $errors, string $code, array $customData = [], int $statusCode = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        $data = [];

        foreach ($errors as $key => $error) {
            if ($error instanceof \Throwable) {
                $data[] = [
                    'message' => $error->getMessage(),
                ];
            } elseif ($error instanceof ErrorInterface) {
                $err = [
                    'message' => $error->getMessage(),
                ];

                $path = $error->getPath();

                if (null !== $path) {
                    $err['path'] = $error->getPath();
                }

                $data[] = $err;
            } elseif ($error instanceof FormError) {
                $data[] = [
                    'message' => $error->getMessage(),
                    'path' => FormHelper::formErrorPath($error),
                ];
            } elseif (is_string($error)) {
                $data[] = [
                    'message' => $error,
                    'path' => $key,
                ];
            } else {
                $data[] = [
                    'message' => $error,
                ];
            }
        }

        return $this->response(
            array_merge(
                $customData,
                [
                    'success' => false,
                    'code' => $code,
                    'errors' => $data,
                ]
            ),
            $statusCode
        );
    }

    private function paginatedResponse(
        array $data, AbstractFilter $filter, int $statusCode = Response::HTTP_OK, array $extra = []
    ): JsonResponse {
        return $this->response([
            'data' => $data,
            'page' => $filter->getPage(),
            'pages' => $filter->getPages(),
            'extra' => $extra,
        ], $statusCode);
    }

    private function response(?array $data, int $statusCode = Response::HTTP_OK): JsonResponse {
        if (null === $data) {
            return new JsonResponse('null', 200, [], true);
        }

        return new JsonResponse($data, $statusCode);
    }

    /**
     * @throws DecodeRequestException
     */
    private function decode(Request $request): array {
        try {
            return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new DecodeRequestException($e->getMessage());
        }
    }
}
