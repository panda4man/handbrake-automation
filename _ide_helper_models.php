<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $file_name
 * @property int|null $file_size_before
 * @property int|null $file_size_after
 * @property string|null $started_at
 * @property string|null $failed_at
 * @property int $progress
 * @property string|null $eta
 * @property string $file_type
 * @property int $active
 * @property int|null $pid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereEta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereFailedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereFileSizeAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereFileSizeBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileCompression whereUpdatedAt($value)
 */
	class FileCompression extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

