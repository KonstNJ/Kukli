<?php

namespace App\Models;

use App\Models\Traits\ExtendModel;
use Phalcon\Security\Random;

class Users extends \Phalcon\Mvc\Model
{
    use ExtendModel;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $roles;

    /**
     *
     * @var string
     */
    public $ident;

    /**
     *
     * @var string
     */
    public $interests;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $fullname;

    /**
     *
     * @var string
     */
    public $patronymic;

    /**
     *
     * @var string
     */
    public $fullname_indx;

    /**
     *
     * @var string
     */
    public $about_me;

    /**
     *
     * @var string
     */
    public $date_of_birth;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $confirm_email;

    /**
     *
     * @var string
     */
    public $forgot_password;

    /**
     *
     * @var string
     */
    public $date_create;

    /**
     *
     * @var boolean
     */
    public $moderadet;

    /**
     *
     * @var boolean
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $gender;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $currency;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_users');
        $this->setSource("users");
        $this->hasMany('id', 'App\Models\Album', 'users', ['alias' => 'Album']);
        $this->hasMany('id', 'App\Models\BannedAnnotations', 'manage', ['alias' => 'BannedAnnotations']);
        $this->hasMany('id', 'App\Models\BannedAnnotations', 'users', ['alias' => 'BannedAnnotations']);
        $this->hasMany('id', 'App\Models\Basket', 'users', ['alias' => 'Basket']);
        $this->hasMany('id', 'App\Models\Blogs', 'users', ['alias' => 'Blogs']);
        $this->hasMany('id', 'App\Models\Community', 'users', ['alias' => 'Community']);
        $this->hasMany('id', 'App\Models\CommunitySubscription', 'users', ['alias' => 'CommunitySubscription']);
        $this->hasMany('id', 'App\Models\Notifications', 'users', ['alias' => 'Notifications']);
        $this->hasMany('id', 'App\Models\Offers', 'users', ['alias' => 'Offers']);
        $this->hasMany('id', 'App\Models\Orders', 'users', ['alias' => 'Orders']);
        $this->hasMany('id', 'App\Models\PaidServices', 'users', ['alias' => 'PaidServices']);
        $this->hasMany('id', 'App\Models\Pictures', 'users', ['alias' => 'Pictures']);
        $this->hasMany('id', 'App\Models\Preference', 'users', ['alias' => 'Preference']);
        $this->hasMany('id', 'App\Models\Reviews', 'users', ['alias' => 'Reviews']);
        $this->hasMany('id', 'App\Models\Shops', 'users', ['alias' => 'Shops']);
        $this->hasMany('id', 'App\Models\Subscribe', 'users', ['alias' => 'Subscribe']);
        $this->hasMany('id', 'App\Models\UsersAuth', 'users', ['alias' => 'UsersAuth']);
        $this->hasMany('id', 'App\Models\UsersStats', 'users', ['alias' => 'UsersStats']);
        $this->hasMany('id', 'App\Models\UsersBanned', 'users', ['alias' => 'UsersBanned']);
        $this->hasMany('id', 'App\Models\UsersBanned', 'users_to', ['alias' => 'UsersBanned']);
        $this->hasMany('id', 'App\Models\UsersFailedLogins', 'users', ['alias' => 'UsersFailedLogins']);
        $this->hasMany('id', 'App\Models\UsersFriends', 'users_from', ['alias' => 'UsersFriends']);
        $this->hasMany('id', 'App\Models\UsersFriends', 'users_to', ['alias' => 'UsersFriends']);
        $this->hasMany('id', 'App\Models\UsersFollow', 'users', ['alias' => 'UsersFollow']);
        $this->hasMany('id', 'App\Models\UsersMessage', 'users_from', ['alias' => 'UsersMessage']);
        $this->hasMany('id', 'App\Models\UsersMessage', 'users_to', ['alias' => 'UsersMessage']);
        $this->hasMany('id', 'App\Models\UsersNotice', 'users', ['alias' => 'UsersNotice']);
        $this->hasMany('id', 'App\Models\UsersSocial', 'users', ['alias' => 'UsersSocial']);
        $this->hasMany('id', 'App\Models\UsersSubscription', 'users', ['alias' => 'UsersSubscription']);
        $this->hasMany('id', 'App\Models\UsersTape', 'users', ['alias' => 'UsersTape']);
        $this->hasMany('id', 'App\Models\Vote', 'users', ['alias' => 'Vote']);
        $this->belongsTo('roles', 'App\Models\Roles', 'id', ['alias' => 'Roles']);
        $this->belongsTo('status', 'App\Models\Status', 'id', ['alias' => 'Status']);
    }

    /**
     * Before save method for model.
     */
    public function beforeSave()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->interests = $this->Jsonb($this->interests);
    }

    /**
     * Before create method for model.
     * @return void
     * @throws \Phalcon\Security\Exception
     */
    public function beforeCreate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->interests = $this->Jsonb($this->interests);
        $this->confirm_email = (new Random())->base64Safe(25);
    }

    /**
     * Before update method for model.
     */
    public function beforeUpdate()
    {
        $this->picture = $this->Jsonb($this->picture);
        $this->interests = $this->Jsonb($this->interests);
    }

    /**
     * Before update method for model.
     */
    public function beforeDelete()
    {
        if(!is_null($this->picture)) {
            //$this->removeImages(json_decode($this->picture, true));
            $this->removeUsersDir((string) $this->id);
        }
    }

    /**
     * @return void
     */
    public function afterCreate()
    {
        if(!is_null($this->email))
        {
            $this->getDI()
                ->get('notifications')
                ->registration($this->email, $this->confirm_email, $this->name);
        }
    }

}
