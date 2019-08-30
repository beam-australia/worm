<?php

namespace Beam\Worm;

use Beam\Worm\Enums\UserStatus;
use Beam\Worm\Traits\HasUser;
use Beam\Worm\Collection;

class User extends Model
{
    use HasUser;

    const ROLE = 'staff';

    public $attributes = [
        'first_name',
        'last_name',
        'status',
    ];

    public $fillable = [
        'first_name',
        'last_name',
        'status',
    ];

    public static $defaults = [
        'status' => UserStatus::APPROVED,
    ];

    /**
     * Object constructor.
     *
     * @param int|User $user
     */
    public function __construct($user = null)
    {
        if ($user instanceof User) {
            $this->ID = $user->ID;
            $this->user = $user->user;
        } else if (is_numeric($user)) {
            $this->ID = $user;
            $this->user = get_user_by('id', $user);
        }
    }

    /**
     * Create a new model
     *
     * @param array $attributes
     * @return User
     */
    public static function create(array $attributes): User
    {
        $userId = wp_insert_user(array_merge($attributes, [
            'user_login' => $attributes['user_email'],
            'user_email' => $attributes['user_email'],
            'role' => static::ROLE,
            'show_admin_bar_front' => 'false',
        ]));

        if (is_wp_error($userId)) {
            throw new \Exception($userId->get_error_message());
        }

        $user = new static($userId);

        $user->setDefaults();

        $user->fill($attributes);

        return $user->load();
    }

    /**
     * Return current logged in user
     *
     * @return User
     */
    public static function current(): ?User
    {
        if ($userId = get_current_user_id()) {
            return new static($userId);
        }

        return null;
    }

    /**
     * Fetch user query
     *
     * @param array $args
     * @return Collection
     */
    public static function find(array $args = []): Collection
    {
        $args = array_merge(['role' => static::ROLE], $args);

        if ($users = get_users($args)) {
            return new Collection($users);
        }

        return new Collection;
    }

    /**
     * Return approved users
     *
     * @return Collection
     */
    public static function approved(): Collection
    {
        return static::find([
            'meta_key'     => 'status',
            'meta_value'   => UserStatus::APPROVED,
        ]);
    }

    /**
     * Return pending users
     *
     * @return Collection
     */
    public static function pending(): Collection
    {
        return static::find([
            'meta_key'     => 'status',
            'meta_value'   => UserStatus::PENDING,
        ]);
    }
}
