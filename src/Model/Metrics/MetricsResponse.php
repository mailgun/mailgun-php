<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Metrics;

use Mailgun\Model\ApiResponse;

final class MetricsResponse implements ApiResponse
{
    private string $start;
    private string $end;
    private string $resolution;
    private array $dimensions;
    private array $pagination;
    private array $items;
    private array $aggregates;


    private function __construct()
    {
    }

    /**
     * @param array $data
     * @return self
     * @throws \Exception
     */
    public static function create(array $data): MetricsResponse
    {
        $model = new MetricsResponse();
        $model->setDimensions($data['dimensions'] ?? []);
        $model->setStart($data['start']);
        $model->setEnd($data['end']);
        $model->setAggregates($data['aggregates'] ?? []);
        $model->setItems($data['items'] ?? []);
        $model->setPagination($data['pagination'] ?? []);
        $model->setResolution($data['resolution']);

        return $model;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @param string $start
     */
    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @param string $end
     */
    public function setEnd(string $end): void
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getResolution(): string
    {
        return $this->resolution;
    }

    /**
     * @param string $resolution
     */
    public function setResolution(string $resolution): void
    {
        $this->resolution = $resolution;
    }

    /**
     * @return array
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    /**
     * @param array $dimensions
     */
    public function setDimensions(array $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }

    /**
     * @param array $pagination
     */
    public function setPagination(array $pagination): void
    {
        $this->pagination = $pagination;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    public function getAggregates(): array
    {
        return $this->aggregates;
    }

    /**
     * @param array $aggregates
     */
    public function setAggregates(array $aggregates): void
    {
        $this->aggregates = $aggregates;
    }
}
