<?php

namespace App\Traits;


trait Attachable
{
    public function getPathAttribute(): string
    {
        $context = explode('\\', $this::class);

        $idx = count($context) - 1;

        $context = strtolower($context[$idx]);

        return  env('APP_URL') . '/files/' . $context . '/' . $this->id;
    }

    public function getRealPathAttribute(): string
    {
        return 'https://'  . $this->cid .  '.ipfs.dweb.link' . '/' . $this->name;
    }
}
