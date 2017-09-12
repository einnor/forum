<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

trait Favoritable
{

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
    }/**
     * @return bool
 */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->exists();
    }/**
     * @return mixed
 */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }}