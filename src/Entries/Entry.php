<?php

namespace Statamic\Eloquent\Entries;

use Illuminate\Support\Str;
use Statamic\Eloquent\Entries\EntryModel as Model;
use Statamic\Entries\Entry as FileEntry;

class Entry extends FileEntry
{
    protected $model;

    public static function fromModel(Model $model)
    {
        return (new static)
            ->locale($model->site)
            ->slug($model->slug)
            ->date($model->date)
            ->collection($model->collection)
            ->data($model->data)
            ->published($model->published)
            ->model($model);
    }

    public function toModel()
    {
        // get the class from the collection
        // it's the handle, singular, title case
        $class = Str::of($this->collectionHandle())->singular()->title();

        // $class = app('statamic.eloquent.entries.model');

        return $class::findOrNew($this->id())->fill([
            'origin_id' => $this->originId(),
            'site' => $this->locale(),
            'slug' => $this->slug(),
            'uri' => $this->uri(),
            'date' => $this->hasDate() ? $this->date() : null,
            'collection' => $this->collectionHandle(),
            'data' => $this->data(),
            'published' => $this->published(),
            'status' => $this->status(),
        ]);
    }

    public function model($model = null)
    {
        if (func_num_args() === 0) {
            return $this->model;
        }

        $this->model = $model;

        $this->id($model->id);

        return $this;
    }

    public function lastModified()
    {
        return $this->model->updated_at;
    }

    public function origin($origin = null)
    {
        if (func_num_args() > 0) {
            $this->origin = $origin;

            return $this;
        }

        if ($this->origin) {
            return $this->origin;
        }

        if (! $this->model->origin) {
            return;
        }

        return self::fromModel($this->model->origin);
    }

    public function originId()
    {
        return optional($this->origin)->id() ?? optional($this->model)->origin_id;
    }

    public function hasOrigin()
    {
        return $this->originId() !== null;
    }
}
