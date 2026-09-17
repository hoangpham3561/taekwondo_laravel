<?php
namespace App\Traits;


use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

trait HasModify
{
    // TODO: Add auth user
    public static $CREATED_BY = 'created_by';

    public static $UPDATED_BY = 'updated_by';

    public static $DELETED_BY = 'deleted_by';

    private static function currentUser()
    {
        if (!auth('admin')->check() && !auth('web')->check()) {
            return 'seeder';
        } else {
            $userInfo = auth('admin')->user() ? optional(auth('admin')->user()) : optional(auth('web')->user());
            $guard = (auth('admin')->check()) ? 'admin' : 'user';
            return $guard . '.' . $userInfo->id ?? 0;
        }
    }

    public static function bootHasModify()
    {
        static::creating(function($model) {
            /**
             * @var $model Model
             */
            if (Schema::hasColumn($model->getTable(), static::$CREATED_BY)) {
                $model->{static::$CREATED_BY} = self::currentUser();
            }
        });

        static::updating(function ($model) {

            /**
             * @var $model Model
             */
            if (Schema::hasColumn($model->getTable(), static::$UPDATED_BY)) {
                $model->{static::$UPDATED_BY} = self::currentUser();
            }
        });

        static::deleting(function ($model) {

            /**
             * @var $model Model
             */
            if (Schema::hasColumn($model->getTable(), static::$DELETED_BY)) {
                $model->{static::$DELETED_BY} = self::currentUser();
                if ($model instanceof User) {
                    $model->status = UserStatusEnum::DELETED;
                    $model->save();
                }
            }
        });
    }

}
