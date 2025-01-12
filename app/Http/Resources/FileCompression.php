<?php

namespace App\Http\Resources;

use App\Actions\FetchHandbrakeStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileCompression extends JsonResource
{
    protected bool $current = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if($this->current) {
            $process_info = (new FetchHandbrakeStatus)->handle($this->resource);

            $data['process_info'] = $process_info->toArray();
        }

        return $data;
    }

    public function current(): self
    {
        $this->current = true;

        return $this;
    }
}
