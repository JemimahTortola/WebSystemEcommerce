<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CircuitBreaker
{
    protected array $services = [];
    protected int $failureThreshold;
    protected int $timeout;

    public function __construct(int $failureThreshold = 5, int $timeout = 60)
    {
        $this->failureThreshold = $failureThreshold;
        $this->timeout = $timeout;
    }

    public function registerService(string $service): void
    {
        $this->services[$service] = [
            'failures' => 0,
            'last_failure' => null,
            'state' => 'closed'
        ];
    }

    public function call(string $service, callable $callback, ...$args)
    {
        if (!$this->isAvailable($service)) {
            throw new \Exception("Circuit breaker is OPEN for service: {$service}");
        }

        try {
            $result = $callback(...$args);
            $this->recordSuccess($service);
            return $result;
        } catch (\Exception $e) {
            $this->recordFailure($service);
            throw $e;
        }
    }

    public function recordFailure(string $service): void
    {
        if (!isset($this->services[$service])) {
            $this->registerService($service);
        }

        $this->services[$service]['failures']++;
        $this->services[$service]['last_failure'] = time();

        if ($this->services[$service]['failures'] >= $this->failureThreshold) {
            $this->services[$service]['state'] = 'open';
            Cache::put("circuit_breaker:{$service}", 'open', $this->timeout);
        }
    }

    public function recordSuccess(string $service): void
    {
        if (isset($this->services[$service])) {
            $this->services[$service]['failures'] = 0;
            $this->services[$service]['state'] = 'closed';
            Cache::forget("circuit_breaker:{$service}");
        }
    }

    public function isAvailable(string $service): bool
    {
        $state = Cache::get("circuit_breaker:{$service}");
        
        if ($state === 'open') {
            $lastFailure = $this->services[$service]['last_failure'] ?? 0;
            
            if (time() - $lastFailure >= $this->timeout) {
                $this->services[$service]['state'] = 'half-open';
                return true;
            }
            
            return false;
        }

        return true;
    }

    public function getState(string $service): string
    {
        return $this->services[$service]['state'] ?? 'unknown';
    }

    public function reset(string $service): void
    {
        if (isset($this->services[$service])) {
            $this->services[$service] = [
                'failures' => 0,
                'last_failure' => null,
                'state' => 'closed'
            ];
            Cache::forget("circuit_breaker:{$service}");
        }
    }
}
