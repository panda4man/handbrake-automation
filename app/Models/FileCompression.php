<?php


namespace App\Models;

use App\Support\HandBrakeAudio;
use App\Support\HandBrakeFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class FileCompression extends Model
{
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (FileCompression $file_compression) {
            if (empty($file_compression->file_size_after)
                && $file_compression->isDirty('completed_at')
                && $file_compression->completed_at !== null) {

                $output_file_path = $file_compression->relative_output_path;

                if (Storage::disk(config('handbrake.io.output.disk'))->exists($output_file_path)) {
                    $fileSize = Storage::disk(config('handbrake.io.output.disk'))->size($output_file_path);
                    $file_compression->update(['file_size_after' => $fileSize]);
                }
            }
        });
    }

    /* ------- Accessors --------- */

    protected function title(): Attribute
    {
        return Attribute::get(function () {
            return HandBrakeFiles::titleFromFileName($this);
        });
    }

    protected function cleanFileName(): Attribute
    {
        return Attribute::get(function () {
            return HandBrakeFiles::getCleanFileName($this->file_name);
        });
    }

    protected function compressionRatio(): Attribute
    {
        return Attribute::get(function () {
            if (empty($this->file_size_before)) {
                return null;
            }

            return number_format(($this->file_size_after / $this->file_size_before) * 100, 2);
        });
    }

    /**
     * Log file path accessor.
     */
    protected function logFile(): Attribute
    {
        return Attribute::get(function () {
            return Storage::disk(config('handbrake.io.logs.disk'))->path(
                sprintf('%s/compression_%d.log', config('handbrake.io.logs.folder'), $this->id)
            );
        });
    }

    /**
     * Preset name accessor based on file type.
     */
    protected function preset(): Attribute
    {
        return Attribute::get(function () {
            return config('handbrake.presets')[$this->file_type] ?? null;
        });
    }

    /**
     * Input file path accessor using the relative path.
     */
    protected function inputFile(): Attribute
    {
        return Attribute::get(function () {
            return Storage::disk(config('handbrake.io.input.disk'))->path(
                sprintf('%s/%s', config('handbrake.io.input.folder'), $this->relative_path)
            );
        });
    }

    /**
     * Output file path accessor using the relative path.
     */
    protected function outputFile(): Attribute
    {
        return Attribute::get(function () {
            return Storage::disk(config('handbrake.io.output.disk'))->path(
                sprintf('%s/%s', config('handbrake.io.output.folder'), HandBrakeFiles::getCleanFileName($this->relative_path))
            );
        });
    }

    protected function relativeOutputPath(): Attribute
    {
        return Attribute::get(function () {
            return sprintf('%s/%s', config('handbrake.io.output.folder'), HandBrakeFiles::getCleanFileName($this->relative_path));
        });
    }

    /**
     * Preset file path accessor based on file type.
     */
    protected function presetFile(): Attribute
    {
        return Attribute::get(function () {
            return Storage::disk(config('handbrake.io.presets.disk'))->path(
                sprintf('%s/%s.json', config('handbrake.io.presets.folder'), $this->preset)
            );
        });
    }

    protected function requestedAudioTracks(): Attribute
    {
        return Attribute::get(function () {
            return HandBrakeAudio::getRequestedAudioTracks($this);
        });
    }

    protected function requestsAudioTracks(): Attribute
    {
        return Attribute::get(function () {
            return HandBrakeAudio::requestsAudioTracks($this);
        });
    }

    /* --- Scopes --- */

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('active', false)
                     ->whereNull('started_at')
                     ->whereNull('completed_at');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->whereNotNull('failed_at');
    }

    /* ----- Helpers ----- */
    public function retry(): void
    {
        $this->update([
            'pid' => null,
            'failed_at' => null,
            'started_at' => null,
            'completed_at' => null,
            'cli_command' => null,
        ]);
    }
}
