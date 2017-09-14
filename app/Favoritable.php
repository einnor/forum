<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

trait Favoritable
{

    protected static function bootFavoritable()
    {
        static::deleting(function($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * @param $userId
     * @return Model
     */
    public function favorite($userId)
    {
        $attributes = ['user_id' => $userId];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * @param $userId
     * @return Model
     */
    public function unfavorite($userId)
    {
        $attributes = ['user_id' => $userId];

        return $this->favorites()->where($attributes)->get()->each->delete();
    }

    /**
     * @return bool
 */
    public function isFavorited()
    {
        return !! $this->favorites()->where('user_id', auth()->id())->exists();
    }

    /**
     * @return bool
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * @return mixed
 */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }}